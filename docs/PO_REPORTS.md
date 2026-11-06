# Purchase Order Reports

Last updated: 6 Nov 2026

One command, `python scripts/po_reports.py <CODE>`, exports three purchase order CSV reports for any project, straight from the live database.

## What it does

- **Script:** `scripts/po_reports.py`
- **Data:** active purchase orders only (`cancel_status = '0'`). Cancelled POs are left out.
- **Period:** full project history by default. Use `--from` / `--to` for a date range.
- **Output folder:** `exports/<Project name>/`, for example `exports/Sintra/`. Running it again overwrites that project's files.
- **Safety:** it only reads from the database. Nothing in the app or the database is changed.

## One-time setup

1. Check Python 3 is installed: `python --version`
2. Install the MongoDB driver: `pip install pymongo`
3. Open a terminal in the project folder: `cd D:\Projects\sis-cloud-php`

The database connection is read from `include/conn.php`, so there is nothing else to configure.

## Commands

Run these from the project folder. Project codes are not case-sensitive (`sn` works the same as `SN`).

| What you want | Command |
| --- | --- |
| See all project codes and PO counts | `python scripts/po_reports.py --list` |
| Full report for one project | `python scripts/po_reports.py SN` |
| Several projects at once | `python scripts/po_reports.py AP LX RI` |
| Every project | `python scripts/po_reports.py all` |
| A date range | `python scripts/po_reports.py FL --from 2025-11-01 --to 2026-10-31` |
| From a date up to today | `python scripts/po_reports.py IF --from 2026-04-01` |
| Save to a different folder | `python scripts/po_reports.py SN --out D:\Reports\Sintra` |
| Show help | `python scripts/po_reports.py --help` |

Dates are `YYYY-MM-DD` and are matched on the date the PO was created. Each run prints the PO count, the month range, the grand total and the paths of the files it wrote.

Example output:

```
> python scripts/po_reports.py SN
SN (S.I.S Sintra): 1307 POs, 4735 items, Mar-2021 to Sep-2025, grand total 138,866,440.72
  D:\Projects\sis-cloud-php\exports\Sintra\Orderlist_SN_complete.csv
  D:\Projects\sis-cloud-php\exports\Sintra\Orderlist_SN_items_complete.csv
  D:\Projects\sis-cloud-php\exports\Sintra\SN_monthwise_quantities_complete.csv
```

## Project codes

These projects had active POs on 6 Nov 2026. Run `--list` for current counts; new projects show up there automatically.

| Code | Project | Output folder | Active POs (6 Nov 2026) |
| --- | --- | --- | --- |
| FL | S.I.S Florence | exports/Florence | 3,418 |
| SN | S.I.S Sintra | exports/Sintra | 1,307 |
| FA | S.I.S Florida | exports/Florida | 1,034 |
| AP | S.I.S Acropole | exports/Acropole | 1,011 |
| IF | S.I.S Isfahan | exports/Isfahan | 971 |
| H | Head Office | exports/Head Office | 738 |
| LX | S.I.S Luxor | exports/Luxor | 537 |
| RI | SIS RIGA | exports/RIGA | 149 |
| CT | S.I.S Capetown | exports/Capetown | 63 |
| QT | S.I.S Queenstown | exports/Queenstown | 6 |
| GP | S.I.S Grand Palace | exports/Grand Palace | 2 |
| SF | S.I.S Safaa | exports/Safaa | 1 |

## Output files

Each run writes three CSV files per project. `<tag>` is `complete` for the full history, or the date range (for example `2025-11-01_to_2026-10-31`). The files open directly in Excel.

| File | One row per | Columns |
| --- | --- | --- |
| `Orderlist_<CODE>_<tag>.csv` | PO | PO_no, PO_Date, Project, Company, Grandtotal, Category |
| `Orderlist_<CODE>_items_<tag>.csv` | PO item, plus transport and plain charge rows | PO_no, PO_Date, Project, Company, Category, Item_No, HSN_Code, Item, Description, Make, Size, CFT, Qty, Unit, Price, Amount, GST_%, SGST_Amt, CGST_Amt, Item_Total, PO_Grandtotal |
| `<CODE>_monthwise_quantities_<tag>.csv` | Item and unit | Category, Item, Unit, one column per month (Mar-2021, Apr-2021 …), Total_Qty, No_of_POs, Total_Value_incl_GST |

- **Summary and items files:** both end with a Total row.
- **Items file:** PO_Grandtotal is filled only on each PO's first row, so summing that column counts each PO once.
- **Month-wise file:** every month between the first and last PO gets a column, even months with no purchases. A blank cell means nothing was ordered that month.

## How amounts are calculated

The calculations match the PO PDF (`modules/materials/purchase/po_pdf.php`), so each PO's rows add up to its grand total.

| Row type | Amount | Tax |
| --- | --- | --- |
| Standard, Steel, Work, UPVC items | Qty × Price | SGST % + CGST % of Amount |
| Door (timber) items | CFT × Price (priced per cubic foot) | SGST % + CGST % of Amount |
| Transport charges | trans_amount from the PO | trans_tax % of Amount, split half SGST, half CGST |
| Plain charges (Door POs) | plain_amount × the PO's total CFT | plain_tax % of Amount, split half SGST, half CGST |

- **Item_Total** = Amount + SGST_Amt + CGST_Amt.
- **Mismatch check:** if any PO's rows differ from its grand total by more than ₹1, the script prints a WARNING listing those POs.
- **Month-wise file:** quantities come from item rows only. Transport and plain charges have no quantity, so they are not included there.

## Notes and troubleshooting

- **IGST vendors:** tax is always split into SGST and CGST, as stored in the database. The PO PDF shows the same tax as IGST for these vendors, so add the two columns together.
- **Item names are free text.** The same material typed differently on different POs (for example "M Fold Tissue" and "M-Fold Tissue") shows as separate rows in the month-wise file. Rows are merged only when the name and unit match, ignoring capitalisation and spacing.
- **Ordered, not received.** Quantities are what was ordered on POs, not what was received at site.
- **Months come from the PO date** (`po_date`). The `--from` / `--to` filter uses the PO creation time (`date_time`), which is normally the same day.
- **Files open in Excel?** Close a CSV in Excel before re-running, or the script can't overwrite it (PermissionError).
- `ModuleNotFoundError: No module named 'pymongo'` → run `pip install pymongo`.
- `unknown project code` → check the code with `python scripts/po_reports.py --list`.
- **Connection timeout** → check the internet connection; the database servers are remote.

## Updating this document

Edit `docs/PO_REPORTS.md`, then regenerate the HTML and Word versions:

```
cd docs
node _md2html.js PO_REPORTS.md
```
