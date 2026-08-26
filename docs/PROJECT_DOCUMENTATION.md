# SIS Cloud — Project Documentation

> Reference documentation of the existing legacy application, produced as the baseline for a planned rewrite to **Next.js / React** (with a relational DB via Drizzle, per `package.json`). It captures the working flow, modules, REST API, MongoDB schema, business workflows/state machines, infrastructure, and migration notes.

Generated: 2026-06-17. Authoritative source files referenced inline (e.g. [include/db_handler.php](../include/db_handler.php), [v1/index.php](../v1/index.php), [app.js](../app.js)).

---

## 1. Overview & Purpose

SIS Cloud is an internal **materials-procurement + HR/ERP** system for a construction/real-estate company (SIS). Its core is a procurement chain:

```
Project → Indent (PRF) → Quotation → Purchase Order (PO) → Inventory (receipt → usage) → Purchase Bill
```

Supporting domains: SIS Projects, Vendors, Products/Categories, Users & HR (leave, salary, permissions, transfers), Accounts, Billing, and a (stub) Dashboard.

It is a **single-page AngularJS app** talking to a **Slim-based PHP REST API**, backed by **MongoDB** (`sis_crm_DB`, a 3-node replica set). PDFs are rendered with **mPDF**; email is sent with **PHPMailer** over Gmail SMTP.

---

## 2. Technology Stack

| Layer | Technology |
|---|---|
| Frontend | AngularJS 1.4.x SPA (ui-router, ngMaterial, Materialize CSS, Bootstrap, jQuery 2.1.4, jQuery UI) |
| API | Slim micro-framework (bundled in [libs/Slim](../libs/Slim)), single front controller [v1/index.php](../v1/index.php) |
| Business logic | One monolithic class `DbHandler` (~9000 lines) in [include/db_handler.php](../include/db_handler.php) |
| Database | MongoDB `sis_crm_DB` (replica set `mongodineamik`), driver `mongodb/mongodb ^1.1` |
| PDF | `mpdf/mpdf ^7.0` via per-document `*_pdf.php` scripts |
| Email | `phpmailer/phpmailer ^6.0` over Gmail SMTP (+ IMAP append to Sent) |
| Server | XAMPP / Apache on Windows; URL rewrite via [v1/.htaccess](../v1/.htaccess) + `web.config` |
| Auth | Custom: plaintext password check; client session held in `localStorage` |

Dependencies declared in [composer.json](../composer.json); `drizzle-kit` is the only entry in [package.json](../package.json) (placeholder for the planned relational rewrite).

---

## 3. High-Level Architecture

### 3.1 Request flow

```
Browser (AngularJS SPA, index.html)
   │  $http POST  ./v1/<endpoint>   (JSON body; some fields are JSON strings)
   ▼
Apache + .htaccess  ──►  v1/index.php  (Slim router, ~107 routes)
   │  new DbHandler(); $db-><method>($rawBody); echo json_encode($result)
   ▼
include/db_handler.php  ──►  MongoDB (sis_crm_DB)
   │  side effects: PDF (HTTP self-call to *_pdf.php) + Email (PHPMailer)
   ▼
JSON response  { code: "Success" | <error>, ...data }
```

Key conventions:
- Every endpoint reads the raw POST body, the method `json_decode`s it, and the result is `echo json_encode(...)`.
- Response status lives in a `code` field — **inconsistently** valued (`"Success"`, `"failed"`, `"failure"`, human sentences, or omitted on lookups).
- Several payload fields are **double-encoded JSON strings** (`jsonArrayData`, `jsonSupplierData`) decoded a second time server-side.

### 3.2 Directory layout (active)

```
/                       SPA root: index.html, app.js (routes), service.js, directive.js, script.js
/v1                     REST API: index.php (Slim routes), adduser.php, user_update.php, user_doc.php
/include                db_handler.php (logic), db_connect.php / conn.php (Mongo connection)
/libs/Slim              bundled Slim framework
/modules                feature modules, each with controller/*.js + view/*.html
  ├─ login, common, dashboard, projects, accounts, billing, user, settings, uploads
  └─ materials/         dashboard, indent, quotation, purchase, inventory
/modules/.../*_pdf.php  per-document mPDF generators (indent_pdf.php, quotation_pdf.php, po_pdf.php, salaryslip_pdf.php)
/vendor                 composer deps (mongodb, mpdf, phpmailer)
/uploads, /modules/uploads   file/PDF storage
```

> **Legacy/dead code to ignore in the rewrite** (dated backups & duplicate modules): `modules/materials/indent22072021/`, `modules/materials/inventory1/`, `modules/materials/indent/controller_14.12.2022/`, `include/db_handler*<date>*.php`, `include/db_connect_*.php`, `include/conn_*.php`, `directive31.05.2019.js`, `indexr.html`, `indexrr.html`, `indextest.htm`, dated controller copies (e.g. `*_29.12.2024.js`, `*_r.js`), `curlupload.php` (legacy FTP, broken), `invoice-crm-demo/`.

### 3.3 Numbering schemes (business keys)

| Document | Format | Example | Generator |
|---|---|---|---|
| Indent / PRF | `PRF-{project_short}{NNNN}` | `PRF-LX0556` | `internalprfnum()` |
| Quotation | `Q-{project_short}{NNNN}` | `Q-LX0001` | inline in `newquotation()` |
| Purchase Order | `{project_short}{NNNN}` (no prefix) | `LX0556` | `internalponum()` |
| Purchase Bill | `B-{project_short}{NNNN}` | `B-LX0001` | inline in billing |
| Product code | `{category.short}-{NNNN}` | — | `getproductcode()` |

> ⚠️ **All numbering uses read-max-then-+1 with no atomic counter/lock** → concurrent or double-submitted requests can produce duplicate keys. This already caused a real duplicate `PRF-LX0556`. A guarded insert was added to `newindent`; the same hardening is still needed for quotation/PO/bill generation. Replace with DB sequences/unique constraints in the rewrite.

---

<!-- ===================== FRONTEND ===================== -->

## 4. Frontend Architecture (AngularJS SPA)

### 4.1 Bootstrapping & entry point

Entry: [index.html](../index.html) → `<body ng-app="app"><div ui-view></div></body>`.

Module declaration ([app.js](../app.js)):
`angular.module('app', ['ui.router','ngMaterial','ui.directives','ui.filters','ngSanitize','bootstrapLightbox'])`

Global script load order: jQuery 2.1.4 → Bootstrap → AngularJS 1.4.4 (+sanitize/animate/aria/messages) → angular-material → angular-ui (ui.directives, ui.filters) → ui-router → ui-bootstrap-tpls → bootstrap-lightbox → bootstrap-wysiwyg → jQuery UI/hotkeys/numeric → `script.js` → `app.js` → `service.js` (`orderService`) → `directive.js` → ~52 controller scripts (global scope; no module bundler).

CSS: Materialize + Bootstrap + jQuery UI + Font Awesome, plus `css/common.css` and `css/style/*.css`. Primary accent cyan `#00bcd4`.

### 4.2 Routing & states ([app.js](../app.js))

`$urlRouterProvider.otherwise('/login')`. `home` (`commonHeaderCtrl`, template `modules/common/view/index.html`) is the authenticated shell; all feature states are nested `home.*`. State param `{filter}` packs list UI state as `project_tabletype_limit_search_page`.

| State | URL | Controller | Purpose |
|---|---|---|---|
| login | /login | loginCtrl | Authentication |
| home | /home | commonHeaderCtrl | Layout shell (sidebar + header) |
| home.dashboard | /dashboard | — | Stub ("under construction") |
| home.projects | /projects | sisProjectCtrl | SIS Projects CRUD |
| home.materials | /materials | — | Materials hub |
| home.indent | /indent/{filter} | indentCtrl | Indent list (paginated) |
| home.new_indent | /new_indent/{filter} | newIndentCtrl | Create indent |
| home.indent_update | /indent_update/{filter} | indentUpdateCtrl | Edit indent |
| home.indent_duplicate | /indent_duplicate/{filter} | indentDuplicateCtrl | Clone indent |
| home.indent_view | /indent_view/{filter} | indentViewCtrl | View indent |
| indent_mail | /indent_mail/{filter} | indentMailCtrl | Email-link landing |
| home.quotation | /quotation/{filter} | quotationCtrl | Quotation list |
| home.new_quotation | /new_quotation/{filter} | quotationNewCtrl | Create quotation |
| home.quotation_update | /quotation_update/{filter} | quotationUpdateCtrl | Edit quotation |
| home.quotation_update_admin | /quotation_update_admin/{filter} | quotationUpdateAdminCtrl | Admin edit |
| home.quotation_view | /quotation_view/{filter} | quotationViewCtrl | View quotation |
| quotation_approve | /quotation_approve/{filter} | quotationApprovedCtrl | Approve flow |
| home.orders | /orders/{filter} | orderCtrl | PO list |
| home.new_purchase | /new_purchase/{filter} | neworderCtrl | Create PO |
| home.purchase_duplicate | /purchase_duplicate/{filter} | orderDupCtrl | Clone PO |
| home.purchase_update | /purchase_update/{filter} | orderUpdateCtrl | Edit PO |
| home.purchase_view | /purchase_view/{filter} | orderViewCtrl | View PO |
| home.vendors | /vendors | vendorCtrl | Vendor CRUD |
| home.products | /products | productCtrl | Product catalog |
| home.inventory | /inventory | inventoryCtrl | Received stock |
| home.inventory_use | /inventory_use/{filter} | inventoryUseCtrl | Usage/consumption |
| home.category | /category/{filter} | categoryCtrl | Categories |
| home.userlist | /userlist/{filter} | userListCtrl | Employees |
| home.user_view | /user_view/{filter} | userEditCtrl | View/edit employee |
| home.add_user | /add_user/{filter} | addUserCtrl | Add employee |
| home.permission | /permission/{filter} | userPermissionCtrl | Permissions |
| home.accounts | /accounts | accountsCtrl | Salary/accounts |
| home.bill_list | /bill_list/{filter} | billListCtrl | Bills |
| home.new_bill | /new_bill/{filter} | newBillCtrl | Create bill |
| home.bill_view | /bill_view/{filter} | billViewCtrl | View/confirm bill |

### 4.3 Module → controllers/views

| Module | Controllers | Purpose |
|---|---|---|
| login | login.js | Auth; writes session + permissions to localStorage |
| common | common.js | Header + permission-gated sidebar menu; reads 11 permission keys |
| dashboard | — | Placeholder |
| projects | projects.js | SIS project CRUD |
| materials/indent | indent.js, new_indent.js, indent_update.js, indent_duplicate.js, indent_view.js, mail_link.js | PRF lifecycle; product types Standard/Work/Steel/UPVC/Door; door CFT via `orderService.doorcalculation()` |
| materials/quotation | quotation.js, new_quotation.js, quotation_update.js, quotation_update_admin.js, quotation_view.js, approved.js | Multi-vendor quotes (nested quot_list→table_items); approval flow |
| materials/purchase | order.js, new_purchase.js, purchase_duplicate.js, purchase_update.js, purchase_view.js, vendors.js, products.js | PO from quotation/direct; vendor/product autocompletes; GST |
| materials/inventory | inventory.js, inventory_use.js, category.js | Received stock & consumption |
| user | userlist.js, editprofile.js, adduser.js, user_permission.js | Employee mgmt; profile upload via FormData |
| accounts | accounts.js | Salary/payroll calcs |
| billing | bill_list.js, new_bill.js, bill_view.js | Vendor bills from PO items; partial-billed tracking; confirm |
| settings | mail_settings.js | Mail config |

### 4.4 API communication

- All calls: `$http({ url:'./v1/<endpoint>', method:'POST', data:{...} })`; base URL is relative (`./v1/`).
- Controllers branch on `response.data.code === 'Success'`; otherwise show `response.data.code` in a Bootstrap alert.
- Complex forms build nested JS structures and `JSON.stringify` them into `jsonArrayData` / `jsonSupplierData`.
- File upload (add user) posts `FormData` to `./v1/adduser.php` with `transformRequest: angular.identity` and `Content-Type: undefined`.

### 4.5 Auth & session (client-side)

`loginCtrl` POSTs `{email,password}` to `./v1/userlogin`; on `code:'Success'` it stores in `localStorage`: `user_id, user_name, user_department, user_designation, user_profile`, plus one JSON entry per permission module (`project, indent, quotation, order, vendor, product, inventory, inventory_use, userlist, accounts, billing`). Redirects via `page_redirect`/`page_filter`.

**No state-level route guards.** Each controller re-reads its permission key from localStorage and `$state.go('home.dashboard')` if missing. Menu items use `ng-if="<module>_perm"`. Permissions are plaintext JSON in localStorage (tamperable) — replace with server-enforced RBAC in the rewrite.

### 4.6 Shared UI patterns

- **Pagination:** `ng-repeat ... | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit` (`startFrom` filter in `directive.js`); page sizes 10/25/50/100. Newer lists (indent, billing, orders) use server-side pagination returning a `pagination{...}` object.
- **Modals/alerts:** Bootstrap `$('#id').modal('show'|'hide')`; `ng-show="alert"` danger/success div, auto-hidden after ~2s via `$timeout`.
- **Autocomplete/date directives** (`directive.js`): `commondatepicker`/`datepicker`/`refdatepicker` (jQuery UI, `dd-mm-yy`), `vendorAutoComplete`, `quotproductAutoComplete`/`quotcodeAutoComplete` (call `./v1/getitemstock`), `userAutoComplete`, `supplierAutoComplete`, `mailAutoComplete`.
- **Rich text:** `contenteditable` `#editor` with bootstrap-wysiwyg; content read via jQuery `.html()` for mail bodies.
- **Shared service:** `orderService.doorcalculation()` parses door dimensions → CFT.

---

<!-- ===================== API ===================== -->

## 5. REST API Endpoints (Slim, base `/v1`)

All routes accept a raw JSON POST body (unless GET) and return `json_encode($result)`. `code` is the conventional status field (inconsistent vocabulary — see §9). Routes defined in [v1/index.php](../v1/index.php); logic in [include/db_handler.php](../include/db_handler.php). ~107 routes across 12 domains.

### Auth / Login
| Endpoint | Method | Fn | Request | Response |
|---|---|---|---|---|
| /testmailer | GET | testmailer | — | mailer diagnostic |
| /userlogin | POST | userlogin | email, password | code, emp_id, emp_name, emp_department, emp_designation, emp_profile, staff_data[], mac/ip. Shells out to Windows `ipconfig` for MAC |

### SIS Projects
| Endpoint | Method | Fn | Request | Response |
|---|---|---|---|---|
| /getsisprojects | GET | getsisprojects | — | project_list[], user_list[], code |
| /addsisproject | POST | addsisproject | contact_name, block_name, project_name, project_short, place, city, address, payment | code |
| /editsisproject | POST | editsisproject | id | project_list (doc) |
| /updatesisproject | POST | updatesisproject | _id, …project fields…, gst_in, pan_no, hsn_sac_code | code |
| /delsisproject | POST | delsisproject | id | code |

### Vendors
| Endpoint | Method | Fn | Request | Response |
|---|---|---|---|---|
| /getvendorlist | POST | getvendorlist | cancel_status | vendor_details[], code |
| /addpovendor | POST | addpovendor | company_name, emp_id, contact_person, address, party_gst, taxtype, mobile, phone, fax, email | code / "Vendor already exist !!!" |
| /editpovendor | POST | editpovendor | id | vendor_details |
| /updatepovendor | POST | updatepovendor | _id, …vendor fields… | code |
| /deletevendor | POST | deletevendor | id, type (delete/remove/reopen) | code |
| /deleteallvendors | POST | deleteallvendors | jsonArrayData[{type,id}] | code |

### Products / Category
| Endpoint | Method | Fn | Request | Response |
|---|---|---|---|---|
| /getproductdetails | POST | getproductdetails | cancel_status (none/usage) | category_list, product_details[], code |
| /getsingleproductdetails | POST | getsingleproductdetails | product_name | product_details[] |
| /addcategory | POST | addcategory | category_ws, shortname, category, emp_id, type | code |
| /getproductcode | POST | getproductcode | category/type | product_code |
| /addpoproduct | POST | addpoproduct | type, category, sub_category, product_name(+_ws), emp_id, code, details, make, width, height, upvc_type, unit, price, gst, size, cft | code |
| /editpoproduct | POST | editpoproduct | id | product_details |
| /updatepoproduct | POST | updatepoproduct | _id, …product fields… | code (bug: count>1 sets `codeo`) |
| /deleteproduct | POST | deleteproduct | id, type | code |
| /deleteallproducts | POST | deleteallproducts | jsonArrayData[{type,id}] | code |

### Indent
| Endpoint | Method | Fn | Request | Response |
|---|---|---|---|---|
| /getindentlistPaginated | POST | getindentlistPaginated | emp_id, project_short, cancel_status, quot_status, page, limit, search | indent_data[], site_list, pagination{} |
| /getindentlist | POST | getindentlist | emp_id, project_short, cancel_status, quot_status | indent_data[], site_list |
| /indentdetailedview | POST | indentdetailedview | prf_number | indent_duration, quot_list[] |
| /getprfnumber | POST | getprfnumber | project_short | prf_number |
| /getindentdetails | POST | getindentdetails | emp_id | vendor_list, category_list, project_list, product_list |
| /newindent | POST | newindent | sis_project_name, sis_project_block, emp_id, type, purpose, grand_total, jsonSupplierData, jsonArrayData, to_mail, cc_mail, bcc_mail, content | code (+ duplicate guard: duplicate, prf_number) |
| /viewpurchaseindent | POST | viewpurchaseindent | prf_number | indent_data, supplier_list, product_list[], purchase_list[] |
| /editpurchaseindent | POST | editpurchaseindent | emp_id, prf_number, type | vendor/project/supplier/product/indent lists |
| /updatepurchaseindent | POST | updatepurchaseindent | emp_id, project, prf_number, purpose, grand_total, jsonSupplierData, jsonArrayData(+_id), mail fields | code |
| /getindentmailcontent | POST | getindentmailcontent | emp_id, project_name, block_name, purpose, send_from, prf_number | mail_id, user_mail[], content, subject |
| /sendindentmail | POST | sendindentmail | jsonArrayData{prf_number, emp_id, to/cc/bcc, subject, content} | code / "Network Error, Try Again !!!" |
| /deleteindent | POST | deleteindent | prf_number, type (delete/remove/reopen) | code |
| /deleteallindent | POST | deleteallindent | jsonArrayData[{prf_number,type}] | code |

### Quotation
| Endpoint | Method | Fn | Request | Response |
|---|---|---|---|---|
| /getquotationdetails | POST | getquotationdetails | prf_number | quot_status, vendor/category/supplier/indent lists |
| /getitemstock | POST | getitemstock | project_name, product_name, block_name | stock_details{} |
| /newquotation | POST | newquotation | jsonArrayData{...table_items}, sis_project, prf_number, emp_id, approve_status (approve/save) | code, quot_number |
| /deletequotation | POST | deletequotation | quot_number | code |
| /getquotationlist | POST | getquotationlist | emp_id, project_short, status | quotation_data[], site_list |
| /viewpurchasequotation | POST | viewpurchasequotation | emp_id, quot_number | indent_data, product_list[], final_list, quotation_list, supplier_list |
| /editpurchasequotation | POST | editpurchasequotation | quot_number | vendor_list, product_list, quot_list |
| /updatepurchasequotation | POST | updatepurchasequotation | emp_id, quot_number, jsonArrayData{...}, mail fields | code |
| /updateandapprovequotation | POST | updateandapprovequotation | emp_id, quot_number, jsonArrayData{...} | code |
| /getquotationmailcontent | POST | getquotationmailcontent | emp_id, quot_number, purpose | mail_id, user_mail[], content, subject |
| /sendquotationmail | POST | sendquotationmail | jsonArrayData{quot_number, emp_id, mail fields} | code |
| /approvequotation | POST | approvequotation | submit_type (approved/declined), jsonArrayData, quot_number, emp_id, mail fields | code |
| /pofromquotation | POST | pofromquotation | quot_number | invoice_no (po_number), quotation_list, invoice_data |
| /admineditquotation | POST | admineditquotation | emp_id, quot_number | user_mail[], supplier/quotation/indent lists |

### Purchase Orders
| Endpoint | Method | Fn | Request | Response |
|---|---|---|---|---|
| /getorderlistPaginated | POST | getorderlistPaginated | category, cancel_status, page, limit, search, emp_id, project_short | purchase_data[], site_list, pagination{} |
| /getorderlist | POST | getorderlist | category, cancel_status, emp_id, project_short | purchase_data[], site_list |
| /getpomailcontent | POST | getpomailcontent | po_number, emp_id | mail_data, user_mail[], vendor_mail, subject |
| /getmailid | POST | getmailid | emp_id | mail_data, mail_id, user_mail[] |
| /getprojectdetails | POST | getprojectdetails | po_number, emp_id | vendor_details, payment/vat/project/quotation lists, order_type |
| /getproductlist | POST | getproductlist | type, category, sub_category | product_list |
| /getponumber | POST | getponumber | project_short | invoice_no (po_number), person_list |
| /newpurchase | POST | newpurchase | emp_id, vendor fields, project, jsonArrayData(items), trans/plain amounts+tax, sum_cft, quot_number, prf_number, type, category, references, order_type, grand_total, payment, delivery, mail fields | code |
| /viewpurchaseorder | POST | viewpurchaseorder | po_number | invoice_data{}, purchase_list[] |
| /editpurchaseorder | POST | editpurchaseorder | po_number, emp_id, page_type | product/purchase lists, vendor_details, invoice_data |
| /updatepurchaseorder | POST | updatepurchaseorder | po_number + newpurchase fields (items w/ _id) | code |
| /sendpomail | POST | sendpomail | po_number, emp_id, mail fields, order_type_short | code, filepath. Side effects: PDF, vendor/inventory/quotation/mail updates |
| /deleteorder | POST | deleteorder | po_number, type | code |
| /deleteallorders | POST | deleteallorders | jsonArrayData[{type,po_number}] | code |

### Inventory (stock in / use)
| Endpoint | Method | Fn | Request | Response |
|---|---|---|---|---|
| /getinventorylist | POST | getinventorylist | emp_id, project_short, cancel_status | inventory[], site_list |
| /getstockdetails | POST | getstockdetails | id | dc_list[], inventory{} |
| /inventorydetailedview | POST | inventorydetailedview | id | inventory[] (movements) |
| /inventory_detailupdate | POST | inventory_detailupdate | id, grn_no, dc_no, veh_no | code |
| /stockupdate_in | POST | stockupdate_in | id, status(receive/return), received_qty, return_status, return_qty, emp_id, grn_no, dc_no, veh_no, return_reason | code |
| /stockupdate_use | POST | stockupdate_use | project_id, status(use/break/balance/transfer), total_*_qty, inventory_id[], jsonArrayData{flat_no,contractor,quantity,reason}, project, emp_id | code |
| /stockuse_balanceupdate | POST | stockuse_balanceupdate | id, balance | code |
| /getinventoryuselist | POST | getinventoryuselist | emp_id, project_short, category | inventory[], project_list, site_list |
| /inventoryusedetailedview | POST | inventoryusedetailedview | project_id, code, item | inventory[] |
| /get_flatlist | POST | get_flatlist | id (project_id) | flat_details[] |
| /internalcategorylist | GET | internalcategorylist | — | category_list |

### Users & HR
| Endpoint | Method | Fn | Request | Response |
|---|---|---|---|---|
| /userlistfilter | GET | userlistfilter | — | userfilter[] |
| /userlist | POST | userlist | status, site, department | user_list[] |
| /userdetails | POST | userdetails | emp_id | user_data{}, doc/image/project/dprt lists, transfer_data[] |
| /userdelete | POST | userdelete | emp_id | code (cascades) |
| /deleteallusers | POST | deleteallusers | jsonArrayData[{emp_id}] | code |
| /leaveyear | POST | leaveyear | emp_id, year | leave_data[] |
| /leaveinsert | POST | leaveinsert | emp_id, leavefrom, leaveto, leave, reason | code |
| /adduserdetails | GET | adduserdetails | — | emp_id (next), dprt_list[], project_list[] |
| /usertransfer | POST | usertransfer | emp_id, site, project_block | code |
| /permissiondetails | POST | permissiondetails | emp_id | project_list[], permission_data{} |
| /permissionupdate | POST | permissionupdate | emp_id, checkedAll, jsonArrayData(sites), per-module toggles | code |
| /holidaycalc | POST | holidaycalc | leavefrom, leaveto | leave (working days) |
| /changepwd | POST | changepwd | emp_id, password | code |

### Salary
| Endpoint | Method | Fn | Request | Response |
|---|---|---|---|---|
| /salarylist | POST | salarylist | — | salary_list[] |
| /salarydetails | POST | salarydetails | emp_id | salary_details{}, bank_list[] |
| /salaryupdate | POST | salaryupdate | emp_id, mode, user_status, earnings+deductions, leave, dw, credited, inc | code (inserts salary_history) |
| /getdirectorlist | GET | getdirectorlist | — | director_list[] |
| /salaryhistory | POST | salaryhistory | emp_id | salary_data[], financial_year[], leave_data[] |

### Billing
| Endpoint | Method | Fn | Request | Response |
|---|---|---|---|---|
| /getbillinglist | POST | getbillinglist | emp_id, project_short | bill_list[], site_list |
| /getbillinglistPaginated | POST | getbillinglistPaginated | emp_id, project_short, page, limit, search | bill_list[], pagination{}, site_list |
| /getBillingVendorList | POST | getBillingVendorList | — | project_list[], vendor_details[] |
| /getVendorPoList | POST | getVendorPoList | company, project_short | po_list[] |
| /billingPoDetails | POST | billingPoDetails | po_number, po_type | purchase_items[] (billed/unbilled qty) |
| /generatePurchaseBill | POST | generatePurchaseBill | project_short, emp_id, vendor_bill_no/date, vendor_name, grand_total, bill_list[] | code (creates sis_bill_no, status 0) |
| /viewpurchasebill | POST | viewpurchasebill | bill_id | bill_details{} |
| /confirmpurchasebill | POST | confirmpurchasebill | bill_id, status, credit_note | code (updates inventory billed_qty + PO) |
| /deletepurchasebill | POST | deletepurchasebill | bill_id | code |
| /billcheckout | POST | billcheckout | project_short, vendor_name | code (Success=no pending bill) |

### Dashboard / Reports
| Endpoint | Method | Fn | Request | Response |
|---|---|---|---|---|
| /get_annualsales | GET | get_annualsales | — | project_blocked[] (hard-coded date window) |

---

<!-- ===================== DB SCHEMA ===================== -->

## 6. Database Schema (MongoDB: `sis_crm_DB`)

Reconstructed from [include/db_handler.php](../include/db_handler.php) and the `*_pdf.php` files.

### Conventions
- Every collection has an ObjectId `_id` (serialized as `{"$oid":"..."}` in payloads).
- **Dates are mostly strings**: `Y-m-d H:i:s` (timestamps in `date_time`/`*_datetime`), `d-m-Y` (human dates: `prf_date`, `po_date`, `quot_date`, `delivery_date`). Newer features use BSON `UTCDateTime` (`purchase_bill.created_at`, `inventory.qty_ledger[].create_at`).
- **`emp_id` (int)** is the universal person key; it maps to BOTH `signintable.emp_id` and `user_list.emp_id`.
- **Status enums are inconsistent**: string `"0"/"1"` in legacy collections, int `0/1/2` in newer billing fields; `mail_status` ∈ {"0","", "send","failure","1"}.

### People / Org

**`signintable`** — auth + contact + per-user SMTP config.
| Field | Type | Notes |
|---|---|---|
| emp_id | int | login key |
| name, designation, department, site, mobile, officeno | string | |
| mail_id | string | login email |
| password | string | **plaintext** |
| status | string | login allowed if joined `user_list.status=="Active"` |
| mac_address, mac_status | string | device licensing |
| sign / digital_sign | string | signature image for PDFs |
| config_mailid, mail_pwd, mail_sign | string | outbound SMTP creds + HTML signature |

**`user_list`** — HR/employee profile (1:1 with signintable on emp_id). Fields: emp_id, name/firstname/lastname/sirname, status (Active/Inactive/Resigned/waiting), designation, department, site, email, personnel_email, officeno, emergency_conno, dob/doj/resigned_date, address, bloodgroup, qualification, profileimage, profilepath.

**`permissiontable`** — RBAC, one doc per emp_id. `site_list`: `"All"` or array of `{project_name, project_short}`. `permission`: array of `{permission_type (project|indent|quotation|order|inventory|inventory_use|product|vendor|userlist|accounts|billing), add, edit, delete, mail, quotation, approve, leave}` (booleans).

**`transfer_history`** — emp_id, transfer_date (d-m-Y), transfer_dateformat, transfer_site, block, transfer_duration.

### Projects
**`project_details`** — emp_id (contact), project_name, project_short (key for numbering), project_type, block_name (`"none"` if single-phase), place, city, address, payment, mobile, gst_in/pan_no/hsn_sac_code (company-wide, written via updateMany), status, date_time.

**`flat_details`** — project_id (→project_details._id), flat_no.

### Vendors & Catalog
**`vendor_details`** — emp_id, company_name (join key), contact_person, address, party_gst, taxtype, mobile/phone/fax, email, date_time, cancel_status ("0"/"1").

**`vendor_product_list`** — vendor (→company_name), type/category/sub_category, code, product_name, details, make, width/height, upvc_type, size, cft, unit, price, sgst/cgst, cancel_status. Auto-populated from POs.

**`category`** — emp_id, type, category, short (feeds product_code), category_ws, date_time.

**`product_list`** — emp_id, type, category, sub_category, code, product_code (`{short}-{NNNN}`), product_name(+_ws), details, make, width/height, upvc_type, size, cft, unit, price, gst (split sgst/cgst=gst/2), taxtype, date_time, cancel_status.

**Reference lists:** `order_types{name, short_name}`, `folder{folder}`, `bank_list{name}`, `holidays_list{leave (d-m-Y)}`, `sis_department{department, designation}`, `userdepartment{department, site, status}`, `user_documents{name}`.

### Procurement — Indent
**`indent_master`** — header per `prf_number`.
| Field | Type | Notes |
|---|---|---|
| emp_id | int | requester |
| type | string | Standard/Work/Steel/UPVC/Door (drives PDF + item shape) |
| prf_number | string | unique key (`PRF-LX0556`) |
| project_short, project_id, prf_date, purpose, description, grand_total | mixed | |
| supplier_list | array | `{company_name, reason, contact_person, mobile, comp_address}` |
| cancel_status | "0"/"1" | active / cancelled |
| mail_status | "0"/"send"/"failure" | quotation-request mail state |
| mail_datetime | string | when mail sent (drives "Raised X ago") |
| quot_status | "0"/"1" | quotation raised? |
| quot_status_details | string | "Quotation Raised"/"Approved"/"PO Partially Raised"/"PO Raised" |

**`indent_table`** — line items, child via `prf_number`: code, item, unit, quantity, price, details, make, width/height, upvc_type, size, cft, required_date, stock_details{block_available, block_usage, site_available, site_usage}.

### Procurement — Quotation
**`quotation_master`** — header per `quot_number`; links indent via `prf_number`. Fields: emp_id, prf_number, quot_number (`Q-{short}{NNNN}`), quot_date, project_id, project_short, type/category/sub_category, purpose, description, `quot_list[]` (per-vendor draft quotes: vendor_name, reason, grand_total, table_items[{item, unit, quantity, price, amount, gst, gst_amount, total, required_date, product attrs}]), `final_vendor` (selected vendor, set on approval), approved_status (""/approved/declined), approved_by, mail_status (""/send/failure), po_raised ("1"/absent).

**`quotation_duration`** — SLA audit per quot_number: indent_datetime, quot_datetime, proceed_duration/datetime, approved_duration/datetime, declined_duration/datetime, po_number, po_duration/datetime. `*_duration` are human strings via `secondsToWords`.

### Procurement — Purchase Order
**`purchase_master`** — header per `po_number` (`{short}{NNNN}`, no prefix); links quot_number + prf_number. Fields: emp_id, prf_number, quot_number, po_number, type/category/sub_category (category=="Marketing" skips inventory; type=="Door" triggers CFT), po_date, project_short/id, company (→vendor), contact_person/address/party_gst (vendor snapshot), reference_no/date, po_subject, order_type(+_short), work_ton, trans_amount/trans_tax, plain_amount/plain_tax, sum_cft, grand_total, payment, delivery_place/date, site_contact_person/mobile, description, note, date_time, cancel_status ("0"/"1"), mail_status (""/send/1/failure), received_status ("1" when received), bill_status (int 0/1/2).

**`purchase_table`** — PO line items, child via `po_number`; its `_id` is referenced by `inventory.purchase_id`. Fields: po_number, code, item, desc, quantity, unit, price, sgst/cgst, details, make, width/height, upvc_type, size, cft.

### Inventory & Stock
**`inventory`** — per-PO-line stock record. **Dual shape** (legacy stock-tracking + newer billing-ledger):
- Common: po_number, purchase_id (→purchase_table._id), project_id/short, type/category/sub_category, code/item/desc, unit, price, sgst/cgst, product attrs, quantity, date_time, cancel_status, completed_status ("0"/"1").
- Stock-tracking: stock, return, usage, breakage, transfer, available, stock_percentage/return_percentage, transfer_site[{project_name, block_name, quantity}], mail_status.
- Billing-ledger: billed_qty, qty_ledger[{dc_no, qty, billed_qty, create_at (UTCDateTime)}].

**`stockupdate_in`** — inward events, child of inventory via inventory_id: po_number, emp_id, type (received/return/transfer), date_time, stock, received_qty, return_qty, return_type ("at delivery"/"after received"), return_reason, dc_no, sis_grn_no, vehicle_no, transfer_from (→project_details._id), transfer_qty.

**`stockupdate_use`** — consumption events: type (received/return/usage/breakage/balance/transfer in|out), emp_id, project_id, code/item, total_qty, balance_qty, date_time, po_details[{inventory_id, po_number, used_qty/balance_qty}], usage_details[{flat_no, contractor, quantity, reason}] (or string for transfers).

### Billing
**`purchase_bill`** — vendor bill consolidating POs (uses BSON dates). Fields: emp_id, sis_bill_no (`B-{short}{NNNN}`), project_name/short, block_list[], vendor_name, vendor_bill_no/date, bill_list[{po_number, po_date, po_type, project_id, bill_item_list[items w/ DC refs into inventory.qty_ledger], unloading_cost, loading_tax(+amt), transport_cost/tax(+amt), plain_cost/tax(+amt), sum_cft, sub_total}], grand_total, status (int 0 draft/1 confirmed/2 completed), credit_note, created_at (UTCDateTime).

### HR / Payroll
**`salary_list`** (current) / **`salary_history`** (archive, same shape) — emp_id, earnings (gross, basicpay, hra, conveyance, flexibleplan), deductions (pf, pt, advance, it, others, amount), total_amount, totalearnedamount, ndw/dw/leave/los, credited, salmonth/salyear/monthname, mode (→bank_list.name), inc, user_status. History adds name/designation snapshots for the payslip.

**`userleave_details`** — emp_id, leave_from/to, leave (numeric, can be fractional), reason, year_from/to (FY), duration ("YYYY - YYYY"), date_time. FY runs April→March.

### Audit / Misc
**`mail_details`** — outbound mail log: emp_id, date_time, type (indent/quotation/approver/purchase), prf_number/quot_number/po_number, from/to/cc/bcc, subject, content.

**`apartment_bookingtracker`** — real-estate sales (used by annual-sales report): current_date (UTCDateTime), + other fields used elsewhere.

### Relationships (summary)
- Procurement: `indent_master.prf_number` 1—N `indent_table`; 1—N `quotation_master`; `quotation_master.quot_number` 1—1 `quotation_duration`, ←`purchase_master.quot_number`; `purchase_master.po_number` 1—N `purchase_table`; `purchase_table._id` ←`inventory.purchase_id`; `inventory._id` ←`stockupdate_in.inventory_id` / `stockupdate_use.po_details[].inventory_id`; `purchase_bill.bill_list[].po_number`→`purchase_master`; bill items ref `inventory.qty_ledger[].dc_no`.
- People: `emp_id` links `signintable`↔`user_list` and is FK on nearly every collection.
- Projects: `project_details._id` ←`project_id` on indent/quotation/purchase/inventory/stockupdate_use/flat_details/bill.
- Vendors: `vendor_details.company_name` ←`purchase_master.company`, `vendor_product_list.vendor`, `purchase_bill.vendor_name`.

---

<!-- ===================== WORKFLOWS ===================== -->

## 7. Business Workflows & State Machines

### 7.1 Procurement chain (core)

```
INDENT (PRF)                QUOTATION (Q-)              PURCHASE ORDER (no prefix)     INVENTORY                 BILL (B-)
indent_master/_table   →    quotation_master       →   purchase_master/_table     →  inventory             →  purchase_bill
newindent              →    newquotation           →   pofromquotation+newpurchase   stockupdate_in           generatePurchaseBill
sendindentmail              approvequotation           sendpomail                     stockupdate_use          confirmpurchasebill
```

**Stage 1 — Indent.** `newindent()` creates header (`cancel_status:0`, `mail_status:0`, no `quot_status`) + items. `sendindentmail()` renders the PDF and emails it; on success sets `mail_status:"send"` + `mail_datetime`. Status shown in lists: if `quot_status==1`→`quot_status_details`; else if `mail_datetime`→"Raised <duration>"; else "-". `deleteindent` types: `removeindent`(cancel_status→1), `reopenindent`(→0), `deleteindent`(hard delete master+items by prf_number).

**Stage 2 — Quotation.** `newquotation(approve_status='save')` → draft (`approved_status:""`); `='approve'` → approved immediately (`approved_status:"approved"`, `mail_status:"send"`). Creates `quotation_duration`. On approval (`approvequotation`/`updateandapprovequotation`): `quot_list` collapses into `final_vendor`, timing recorded, and the parent indent gets `quot_status:"1"` + `quot_status_details:"Approved"`. `declined` sets `approved_status:"declined"`.

**Stage 3 — Purchase Order.** `pofromquotation()` returns a PO template + next po_number; `newpurchase()` persists `purchase_master`+`purchase_table`, creates `inventory` rows (unless category=="Marketing"), sets `quotation.po_raised:"1"` and indent `quot_status_details:"PO Partially Raised"/"PO Raised"`. `sendpomail()` sets `mail_status:"send"` and stamps vendor email.

**Stage 4 — Inventory receipt.** `stockupdate_in(status=receive)` increments `inventory.stock/available/stock_percentage`, appends a `qty_ledger` DC entry, sets `purchase_master.received_status:"1"`, recomputes `completed_status`, and logs `stockupdate_in`. Returns handled "at delivery" vs "after received".

**Stage 5 — Inventory usage.** `stockupdate_use(status=use|break|balance|transfer)` adjusts `available/usage/breakage/transfer` and records per-flat/block allocation (`usage_details`) or inter-site `transfer_site`.

**Stage 6 — Billing.** `getVendorPoList`/`billingPoDetails` surface received-but-unbilled PO qty (via `qty_ledger`). `generatePurchaseBill()` creates `purchase_bill` (`status:0`). `billcheckout` guards against a second pending bill. `confirmpurchasebill(status=1)` increments `inventory.billed_qty`/ledger `billed_qty` and sets `purchase_master.bill_status` (2 if fully billed else 0); `status=2` finalizes with optional `credit_note`.

### 7.2 Status value reference

| Collection.field | Values | Meaning |
|---|---|---|
| indent_master.cancel_status | "0" / "1" | active / cancelled |
| indent_master.mail_status | "0" / "send" / "failure" | not sent / sent / failed |
| indent_master.quot_status | "1" / absent | quotation raised / none |
| indent_master.quot_status_details | Quotation Raised / Approved / PO Partially Raised / PO Raised | lifecycle text |
| quotation_master.approved_status | "" / approved / declined | draft / approved / rejected |
| quotation_master.po_raised | "1" / absent | PO created / not |
| purchase_master.cancel_status | "0" / "1" | active / cancelled |
| purchase_master.mail_status | "" / send / failure / 1 | not sent / sent / failed |
| purchase_master.received_status | "1" / absent | received / not |
| purchase_master.completed_status | "0" / "1" | receipt incomplete / complete |
| purchase_master.bill_status | 0 / 1 / 2 | partial / pending / fully billed |
| inventory.completed_status | "0" / "1" | (stock+return) < qty / ≥ qty |
| purchase_bill.status | 0 / 1 / 2 | draft / confirmed / completed |
| user_list.status | Active / Inactive / Resigned / waiting | employment state |

### 7.3 PDF + email side-effects

Each stage renders a PDF then emails it; `mail_status` flips to `send`/`failure` based on the mailer result.

| Doc | PDF script | Triggered by | Updates |
|---|---|---|---|
| Indent | [modules/materials/indent/indent_pdf.php](../modules/materials/indent/indent_pdf.php) | sendindentmail | indent_master.mail_status |
| Quotation | modules/materials/quotation/quotation_pdf.php | approvequotation / updateandapprovequotation | quotation_master.mail_status |
| PO | modules/materials/purchase/po_pdf.php | sendpomail | purchase_master.mail_status (+ vendor/inventory/quotation) |
| Salary slip | modules/user/salaryslip_pdf.php | salary slip download | — |

Mail is sent by `internalMailer()` (PHPMailer, Gmail SMTP, attachment + signature image, IMAP append to Sent). **Generation pattern is fragile — see §8.4.**

### 7.4 HR / Payroll
- **Users:** create via [v1/adduser.php](../v1/adduser.php) (default password `welcome2sis`, profile image upload, auto emp_id = max+1, seeds signintable/user_list/salary/permission). `userlist`/`userdetails`/`userdelete` (cascading), `changepwd`.
- **Leave:** `leaveinsert` (FY auto-derived April→March), `leaveyear` (running total), `holidaycalc` (working days excl. Sundays + `holidays_list`).
- **Salary:** `salarydetails`→`salaryupdate` (writes `salary_list` + appends `salary_history`; auto-resigns "waiting" users on month rollover; adds new bank to `bank_list`), `salaryhistory`, `getdirectorlist`.
- **Permissions:** `permissiondetails`/`permissionupdate` maintain `permissiontable`; consumed client-side from localStorage.

---

<!-- ===================== INFRA / REWRITE ===================== -->

## 8. Infrastructure, Setup & Rewrite Notes

### 8.1 Runtime & deployment
- Served by XAMPP/Apache on Windows from `htdocs/sis-cloud-php`. SPA entry `index.html`; API under `/v1` routed by [v1/.htaccess](../v1/.htaccess) (`web.config` mirrors for IIS).
- Composer deps: `mongodb/mongodb ^1.1`, `mpdf/mpdf ^7.0`, `phpmailer/phpmailer ^6.0`; Slim is vendored in [libs/Slim](../libs/Slim). `v1/index.php` sets `max_execution_time = 1800` (PDF/mail can be slow).
- PHP version note: code uses PHP 5/7-era idioms (curly-brace offsets in mPDF, unguarded array access) → emits many Deprecated/Notice messages on PHP 8. Harmless to file-writing PDFs but noisy; keep `display_errors` Off in production.

### 8.2 Configuration & secrets (migration concern)
- **Mongo URI with embedded credentials** in [include/db_connect.php](../include/db_connect.php) and [include/conn.php](../include/conn.php) (replica set `mongodineamik`, DB `sis_crm_DB`). Many dated backup copies exist — exclude from rewrite.
- **Gmail SMTP app password hardcoded** in [include/db_handler.php](../include/db_handler.php) (`internalMailer`), plus per-user `mail_pwd` in `signintable`.
- **FTP creds** in `curlupload.php` (legacy, broken).
- Action: externalize ALL secrets to environment variables; rotate the exposed credentials.

### 8.3 Authentication model (today)
- `userlogin` compares **plaintext** password against `signintable`; gated by joined `user_list.status=="Active"`. Collects IP/MAC via Windows `ipconfig` (device licensing) — not portable.
- No server sessions/JWT; the client holds identity + permission JSON in `localStorage` (tamperable). RBAC is advisory (client-side only).
- Rewrite: hash passwords (bcrypt/argon2 + migration), issue JWT/refresh in HTTP-only cookies, enforce RBAC in API middleware, drop the ipconfig/MAC mechanism (or replace with proper device/2FA).

### 8.4 File handling & the fragile PDF pattern
- Uploads: `modules/uploads/` (PDFs + `profile/` images); add-user posts multipart to `v1/adduser.php`.
- **PDF generation is a synchronous HTTP self-call:** `db_handler` builds `$this->path = protocol://$_SERVER['SERVER_NAME']` (no port, **no subdirectory**) and does `file_get_contents($this->path.'/modules/materials/<doc>/<doc>_pdf.php?type=upload&id=<num>')`, then `sleep(8)`, then `file_exists(...)` before mailing. This breaks when: the app isn't at the web root (e.g. `localhost/sis-cloud-php`), `SERVER_NAME` doesn't resolve/loopback, `allow_url_fopen` is off, HTTPS cert issues, or generation exceeds 8s — in which case `file_exists` is false and the caller silently returns `"Network Error, Try Again !!!"` with `mail_status` left at `"0"`.
- Rewrite: generate PDFs in-process (or via a dedicated service: Puppeteer/Headless Chrome / a queue), store in object storage, attach from there; never self-HTTP-call.

### 8.5 Known bugs / quirks to NOT carry over
- Numbering race (max+1, no lock) on PRF/PO/quotation/bill → duplicates (e.g. duplicate `PRF-LX0556`). Use sequences/unique constraints.
- `updatepoproduct` count>1 branch sets a misspelled `codeo`. `editpurchaseorder` `vat_list` from an undefined var. `usertransfer` returns empty body when no prior transfer record exists.
- Methods that always return `"Success"` regardless of outcome (`deleteall*`, `stockupdate_use`, `get_flatlist`, `getprojectdetails`, `getBillingVendorList`).
- Double-encoded `jsonArrayData`/`jsonSupplierData` payloads; inconsistent `code` vocabulary; some methods omit `code`.
- `userlogin` shells out to `ipconfig` (Windows-only).

### 8.6 Target architecture mapping (Next.js / React + Drizzle)

| Concern | Current | Target |
|---|---|---|
| Frontend | AngularJS 1.x SPA, global scripts | Next.js (App Router) + React + TypeScript; React Query for server state |
| Routing | ui-router `{filter}` strings | Next.js routes + typed search params |
| API | Slim `/v1/*.php`, one giant handler | Next.js route handlers / tRPC, modular services per domain |
| Auth | plaintext + localStorage | hashed creds + JWT cookies + server RBAC middleware |
| DB | MongoDB (denormalized, string dates, enum drift) | Postgres + Drizzle; normalize subdoc arrays to child tables; real `timestamp`/`date`; proper enums |
| Keys | app-generated max+1 | surrogate PKs + unique business-key columns via sequences |
| PDF | mPDF via HTTP self-call + sleep | in-process/service render → object storage |
| Email | PHPMailer + Gmail + hardcoded pw | provider API (SES/SendGrid) via queue; secrets in env |
| Files | local FS / FTP | object storage (S3/R2) with signed URLs |

**Schema normalization highlights:** merge `signintable`+`user_list` → one `users` table (auth + profile); merge `salary_list`+`salary_history` → `salaries` with a period/active flag; split subdocument arrays (`supplier_list`, `quot_list.table_items`, `qty_ledger`, `transfer_site`, `po_details`, `usage_details`, `permission`, `bill_list`) into child tables; reconcile the dual-shape `inventory` into inventory + receipts(qty_ledger) + movements(stockupdate_in/use). Standardize all timestamps to UTC (legacy stored Asia/Kolkata local time).

---

## 9. Cross-cutting notes (quick reference for the rewrite)

- One DB connection, one God-class (`DbHandler`) — split into per-domain services.
- Status fields are the real state machine; normalize their values during migration (§7.2).
- Business keys (`prf_number`, `quot_number`, `po_number`, `sis_bill_no`, `product_code`) are user-visible and must be preserved, but generated safely.
- No automated tests and no build pipeline today — add both in the rewrite.

---

*This document was generated from a multi-agent read of the codebase. Verify any field/endpoint against the source before relying on it for a migration script; the schema is inferred from code usage, not a formal DDL.*
