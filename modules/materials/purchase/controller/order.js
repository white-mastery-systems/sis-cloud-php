app.controller('orderCtrl', function ($scope, $http, $timeout, $state, $stateParams, orderService) {

    var jsonData = jQuery.parseJSON(localStorage.getItem("order"));

    if (jsonData) {
        $scope.addPermission = jsonData['add'];
        $scope.editPermission = jsonData['edit'];
        $scope.deletePermission = jsonData['delete'];
        $scope.mailPermission = jsonData['mail'];
        $scope.emp_designation = localStorage.getItem("user_designation");
    }
    else {
        $state.go('home.dashboard');
    }

    // State parameter
    if ($stateParams.filter != '') {
        var splitData = $stateParams.filter.split("_");
        $scope.project_short = splitData[0];
        $scope.tabletype = splitData[1];
        $scope.entryLimit = parseInt(splitData[2]) || 10;  // Parse as integer
        $scope.search = splitData[3];
        $scope.pageNo = parseInt(splitData[4]) || 1;
        $scope.category = splitData[5] || '';
    }

    if (!$scope.project_short) { $scope.project_short = ''; }
    if (!$scope.tabletype) { $scope.tabletype = 'usage'; }
    if (!$scope.entryLimit) { $scope.entryLimit = 10; }  // Use number, not string
    if (!$scope.search) { $scope.search = ''; }
    if (!$scope.pageNo) { $scope.pageNo = 1; }
    if (!$scope.category) { $scope.category = ''; }

    $scope.entryLimit = parseInt($scope.entryLimit) || 10;

    // Pagination variables
    $scope.currentPage = $scope.pageNo;
    $scope.totalRecords = 0;
    $scope.totalPages = 0;
    $scope.filteredItems = 0;
    $scope.list = [];
    $scope.projects = [];
    $scope.searchTimeout = null;

    // Mail search functions
    $scope.querySearch = function (query) {
        console.log("3", query);
        $scope.results = query ? $scope.maildatas.filter($scope.createFilterFor(query)) : [];
        console.log("Result", $scope.results);
        return $scope.results;
    };

    $scope.createFilterFor = function (query) {
        var lowercaseQuery = angular.lowercase(query);
        return function (mail_id) {
            return (mail_id._lowername.indexOf(lowercaseQuery) === 0);
        };
    };

    // Get category list
    $scope.getcategory = function () {
        console.log("getcategory");
        $http({
            url: './v1/getprojectdetails',
            method: "POST",
            data: { 'emp_id': localStorage.getItem("user_id") }
        })
            .then(function (response) {
                $scope.category_list = response.data.category_list.filter(item => item.category !== null);
            });
    };

    // Main function to fetch paginated data
    $scope.tableData = function () {
        $('#preloader').show();

        var requestData = {
            emp_id: localStorage.getItem("user_id"),
            project_short: $scope.project_short || '',
            cancel_status: $scope.tabletype,
            category: $scope.category || '',
            page: $scope.currentPage,
            limit: parseInt($scope.entryLimit),
            search: $scope.search
        };

        console.log('=== REQUEST DATA ===', requestData);

        $http({
            url: './v1/getorderlistPaginated',
            method: "POST",
            data: requestData
        })
            .then(function (response) {
                console.log('=== RESPONSE ===', response.data);
                $scope.projects = response.data.site_list;
                $scope.list = response.data.purchase_data;

                if (response.data.pagination) {
                    $scope.totalRecords = response.data.pagination.total_records;
                    $scope.totalPages = response.data.pagination.total_pages;
                    $scope.filteredItems = response.data.pagination.total_records;
                }

                // Store PO numbers
                localStorage.setItem("po_nos", JSON.stringify($scope.list));

                $scope.showDelBtn = false;
                $scope.getcategory();
                $timeout(function () { $('#preloader').fadeOut("slow"); }, 200);
            })
            .catch(function (error) {
                console.error('Error fetching data:', error);
                $scope.list = [];
                $scope.filteredItems = 0;
                $timeout(function () { $('#preloader').fadeOut("slow"); }, 200);
            });
    };

    // Page change handler - called by uib-pagination
    $scope.pageChanged = function () {
        $scope.tableData();
    };

    // Entry limit change handler
    $scope.pageEntries = function () {
        $scope.currentPage = 1;
        $scope.tableData();
    };

    // Project change handler
    $scope.projectChanged = function () {
        $scope.currentPage = 1;
        $scope.tableData();
    };

    // Table type change handler
    $scope.tableTypeChanged = function () {
        $scope.currentPage = 1;
        $scope.tableData();
    };

    // Category change handler
    $scope.categoryChanged = function () {
        $scope.currentPage = 1;
        $scope.tableData();
    };

    // Search with debounce (wait 500ms after user stops typing)
    $scope.filter = function () {
        if ($scope.searchTimeout) {
            $timeout.cancel($scope.searchTimeout);
        }
        $scope.searchTimeout = $timeout(function () {
            $scope.currentPage = 1;
            $scope.tableData();
        }, 500);
    };

    // Sort function (for future server-side sorting)
    $scope.sort_by = function (predicate) {
        $scope.predicate = predicate;
        $scope.reverse = !$scope.reverse;
    };

    // Get showing text for pagination info
    $scope.getShowingText = function () {
        if ($scope.totalRecords === 0) {
            return 'No entries';
        }
        var start = (($scope.currentPage - 1) * parseInt($scope.entryLimit)) + 1;
        var end = Math.min($scope.currentPage * parseInt($scope.entryLimit), $scope.totalRecords);
        return 'Showing ' + start + ' to ' + end + ' of ' + $scope.totalRecords + ' entries';
    };

    // Build state filter string
    $scope.getFilterString = function () {
        return $scope.project_short + '_' + $scope.tabletype + '_' + $scope.entryLimit + '_' + $scope.search + '_' + $scope.currentPage + '_' + $scope.category;
    };

    // Add
    $scope.onAdd = function () {
        $state.go('home.new_purchase', { filter: $scope.getFilterString() });
    };

    // Edit
    $scope.onEdit = function (x) {
        localStorage.setItem("po_number", x);
        $state.go('home.purchase_update', { filter: $scope.getFilterString() });
    };

    // Duplicate
    $scope.onDuplicate = function (x) {
        localStorage.setItem("po_number", x);
        $state.go('home.purchase_duplicate', { filter: $scope.getFilterString() });
    };

    // View
    $scope.onView = function (x) {
        localStorage.setItem("po_number", x);
        $state.go('home.purchase_view', { filter: $scope.getFilterString() });
    };

    // PDF
    $scope.onPdf = function (x) {
        window.location.href = 'modules/materials/purchase/po_pdf.php?type=none&id=' + x + '&emp_id=' + localStorage.getItem("user_id");
    };

    // Word doc
    $scope.onWord = function (x) {
        window.location.href = 'modules/materials/purchase/po_doc.php?id=' + x + '&emp_id=' + localStorage.getItem("user_id");
    };

    // Export
    $scope.export = function () {
        var categoryParam = $scope.category;
        console.log("test", $scope.category);

        if (categoryParam === undefined || categoryParam === null || categoryParam === '') {
            var url = 'modules/materials/purchase/po_export.php?status=' + $scope.tabletype +
                '&project_short=' + $scope.project_short +
                '&emp_id=' + localStorage.getItem("user_id");
        } else {
            var url = 'modules/materials/purchase/po_export_category.php?status=' + $scope.tabletype +
                '&project_short=' + $scope.project_short +
                '&category=' + $scope.category +
                '&emp_id=' + localStorage.getItem("user_id");
        }
        window.location.href = url;
    };

    // Remove order
    $scope.onRemove = function (x) {
        $('#confirmModal').modal('show');
        $scope.alert_msg = 'Are you sure to remove this?';
        $scope.modal_id = x;
        $scope.confirmFn = 'remove';
    };

    $scope.remove = function (x) {
        $http({
            url: './v1/deleteorder',
            method: "POST",
            data: { 'type': 'removeorder', 'po_number': x }
        })
            .then(function (response) {
                $('#confirmModal').modal('hide');
                $scope.alert = true;
                $scope.tableData();
                if (response.data.code == 'Success') {
                    $(".alert").removeClass().addClass('alert alert-success');
                    $scope.alert_content = 'Removed Successfully!';
                }
                else {
                    $(".alert").removeClass().addClass('alert alert-danger');
                    $scope.alert_content = 'Failure!';
                }
            });
        $timeout(function () { $scope.alert = false; }, 2000);
    };

    // Reopen order
    $scope.onReopen = function (x) {
        $('#confirmModal').modal('show');
        $scope.alert_msg = 'Are you sure to reopen this?';
        $scope.modal_id = x;
        $scope.confirmFn = 'reopen';
    };

    $scope.reopen = function (x) {
        $http({
            url: './v1/deleteorder',
            method: "POST",
            data: { 'type': 'reopenorder', 'po_number': x }
        })
            .then(function (response) {
                $('#confirmModal').modal('hide');
                $scope.alert = true;
                $scope.tableData();
                if (response.data.code == 'Success') {
                    $(".alert").removeClass().addClass('alert alert-success');
                    $scope.alert_content = 'Reopened Successfully!';
                }
                else {
                    $(".alert").removeClass().addClass('alert alert-danger');
                    $scope.alert_content = 'Failure!';
                }
            });
        $timeout(function () { $scope.alert = false; }, 2000);
    };

    // Delete order
    $scope.onDelete = function (x) {
        $('#confirmModal').modal('show');
        $scope.alert_msg = 'Are you sure to permanently delete this?';
        $scope.modal_id = x;
        $scope.confirmFn = 'delete';
    };

    $scope.delete = function (x) {
        $http({
            url: './v1/deleteorder',
            method: "POST",
            data: { 'type': 'deleteorder', 'po_number': x }
        })
            .then(function (response) {
                $('#confirmModal').modal('hide');
                $scope.alert = true;
                $scope.tableData();
                if (response.data.code == 'Success') {
                    $(".alert").removeClass().addClass('alert alert-success');
                    $scope.alert_content = 'Deleted Successfully!';
                }
                else {
                    $(".alert").removeClass().addClass('alert alert-danger');
                    $scope.alert_content = 'Failure!';
                }
            });
        $timeout(function () { $scope.alert = false; }, 2000);
    };

    // Mail
    $scope.onMail = function (x) {
        $scope.readonly = false;
        $scope.selectedItem = null;
        $scope.searchText = null;
        $scope.to_mail = [];
        $scope.cc_mail = [];
        $scope.bcc_mail = [];
        $scope.vendormail = [];

        $http({
            url: './v1/getpomailcontent',
            method: "POST",
            data: { 'po_number': x, 'emp_id': localStorage.getItem("user_id") }
        })
            .then(function (response) {
                $('#mailModal').modal('show');
                $scope.mailForm = {};
                $scope.mailForm.po_number = x;
                $scope.mailForm.order_type_short = response.data.order_type_short;
                $scope.mailForm.emp_id = localStorage.getItem("user_id");
                $scope.mailForm.from_mail = response.data.mail_id;

                $scope.to_mail.push({ mail_id: response.data.vendor_mail });

                if (localStorage.getItem("user_id") === '1358') {
                    $scope.cc_mail.push({ mail_id: 'ranjith@sis.in' }, { mail_id: 'vinodhini@sis.in' });
                } else {
                    $scope.cc_mail.push({ mail_id: 'vinodhini@sis.in' }, { mail_id: 'ranjith@sis.in' });
                }

                var res = response.data.user_mail;
                var email = [];

                for (var i = 0; i < res.length; i++) {
                    var res1 = res[i].mail_id.split(",");
                    for (var j = 0; j < res1.length; j++) {
                        var data = {};
                        data.mail_id = res1[j].trim();
                        email.push(data);
                    }
                }

                $scope.mailForm.subject = response.data.subject;
                document.getElementById('editor').innerHTML = response.data.mail_data;
                $scope.loadMailids(email);
                $scope.maildatas = $scope.loadMailids(email);
            });
    };

    $scope.loadMailids = function (data) {
        $scope.mailids = data;
        return $scope.mailids.map(function (mailid) {
            mailid._lowername = mailid.mail_id.toLowerCase();
            return mailid;
        });
    };

    // Send mail
    $scope.onSend = function () {
        $scope.mailForm.content = $("#editor").html();
        $('#sendLoader').addClass('fa fa-circle-o-notch fa-spin');
        $('#send, #cancel').prop('disabled', true);

        var to_id = $scope.to_mail;
        var cc_id = $scope.cc_mail;
        var bcc_id = $scope.bcc_mail;

        if ($scope.to_mail) {
            for (var i = 0; i < to_id.length; i++) {
                if (i === 0) {
                    $scope.mailForm.to_mail = to_id[i].mail_id;
                } else {
                    $scope.mailForm.to_mail += "," + to_id[i].mail_id;
                }
            }
        }

        if ($scope.cc_mail) {
            for (var i = 0; i < cc_id.length; i++) {
                if (i === 0) {
                    $scope.mailForm.cc_mail = cc_id[i].mail_id;
                } else {
                    $scope.mailForm.cc_mail += "," + cc_id[i].mail_id;
                }
            }
        }

        if ($scope.bcc_mail) {
            for (var i = 0; i < bcc_id.length; i++) {
                if (i === 0) {
                    $scope.mailForm.bcc_mail = bcc_id[i].mail_id;
                } else {
                    $scope.mailForm.bcc_mail += "," + bcc_id[i].mail_id;
                }
            }
        }

        if ($scope.to_mail) {
            $http({
                url: './v1/sendpomail',
                method: "POST",
                data: $scope.mailForm
            }).then(function (response) {
                $('#mailModal').modal('hide');
                $scope.alert = true;
                $scope.tableData();
                if (response.data.code == 'Success') {
                    $(".alert").removeClass().addClass('alert alert-success');
                    $scope.alert_content = "Mail sent successfully!";
                }
                else {
                    $(".alert").removeClass().addClass('alert alert-danger');
                    $scope.alert_content = response.data.code;
                }
                $('#send, #cancel').prop('disabled', false);
                $('#sendLoader').removeClass('fa fa-circle-o-notch fa-spin');
                $timeout(function () { $scope.alert = false; }, 2000);
            });
        }
        else {
            $scope.alert = true;
            $(".alert").removeClass().addClass('alert alert-danger');
            $scope.alert_content = "Kindly fill the To Email ID";
            $('#send, #cancel').prop('disabled', false);
            $('#sendLoader').removeClass('fa fa-circle-o-notch fa-spin');
            $timeout(function () { $scope.alert = false; }, 2000);
        }
    };

    // Display delete button
    $scope.deleteShow = function () {
        var count = 0;
        angular.forEach($scope.list, function (x) {
            if (x.singlecheck) {
                count++;
            }
        });
        $scope.showDelBtn = count > 0;
    };

    // Delete all
    $scope.deleteall = function () {
        $scope.tableItems = [];
        angular.forEach($scope.list, function (x) {
            if (x.singlecheck) {
                $scope.tableItems.push({ type: $scope.tabletype, po_number: x.po_number });
            }
        });
        var jsonArrayData = JSON.stringify($scope.tableItems);

        if (jsonArrayData != '[]') {
            $('#confirmModal').modal('show');
            $scope.modal_id = jsonArrayData;
            $scope.confirmFn = 'alldelete';

            if ($scope.tabletype == 'usage') {
                $scope.alert_msg = 'Are you sure to remove the selected purchase orders?';
                $scope.alert_content = 'Removed Successfully!';
            }
            else {
                $scope.alert_msg = 'Are you sure to permanently delete the selected purchase orders?';
                $scope.alert_content = 'Deleted Successfully!';
            }
        }
    };

    $scope.alldelete = function (x) {
        $http({
            url: './v1/deleteallorders',
            method: "POST",
            data: { 'jsonArrayData': x }
        })
            .then(function (response) {
                $('#confirmModal').modal('hide');
                $scope.alert = true;
                $scope.tableData();
                if (response.data.code == 'Success') {
                    $(".alert").removeClass().addClass('alert alert-success');
                }
                else {
                    $(".alert").removeClass().addClass('alert alert-danger');
                    $scope.alert_content = 'Failure!';
                }
            });
        $timeout(function () { $scope.alert = false; }, 2000);
    };

    // Add vendor modal
    $scope.addVendorModal = function () {
        $scope.addVendorForm = {};
        $('#addVendorModal').modal('show');
    };

    $scope.onAddVendor = function () {
        $http({
            url: './v1/addpovendor',
            method: "POST",
            data: $scope.addVendorForm
        })
            .then(function (response) {
                $('#addVendorModal').modal('hide');
                $scope.alert = true;
                if (response.data.code == 'Success') {
                    $(".alert").removeClass().addClass('alert alert-success');
                    $scope.alert_content = 'Added Successfully!';
                }
                else {
                    $(".alert").removeClass().addClass('alert alert-danger');
                    $scope.alert_content = response.data.code;
                }
            });
        $timeout(function () { $scope.alert = false; }, 2000);
    };

    // Add product modal
    $scope.addProductModal = function () {
        $http({
            url: './v1/getproductdetails',
            method: "POST",
            data: { 'cancel_status': 'none' }
        })
            .then(function (response) {
                $scope.category_list = response.data.category_list;
            });
        $scope.addProductForm = {};
        $scope.addProductForm.type = 'Standard';
        $('#addProductModal').modal('show');
    };

    // Change type
    $scope.typeChange = function (type, category, sub_category) {
        if (type && category && sub_category) {
            $http({
                url: './v1/getproductlist',
                method: "POST",
                data: { 'type': type, 'category': category, 'sub_category': sub_category }
            })
                .then(function (response) {
                    $scope.product_list = response.data.product_list;
                });
        }
    };

    $scope.onAddProduct = function () {
        $http({
            url: './v1/addpoproduct',
            method: "POST",
            data: $scope.addProductForm
        })
            .then(function (response) {
                $('#addProductModal').modal('hide');
                $scope.alert = true;
                if (response.data.code == 'Success') {
                    $(".alert").removeClass().addClass('alert alert-success');
                    $scope.alert_content = 'Added Successfully!';
                }
                else {
                    $(".alert").removeClass().addClass('alert alert-danger');
                    $scope.alert_content = response.data.code;
                }
            });
        $timeout(function () { $scope.alert = false; }, 2000);
    };

    $scope.doorcalc = function (x) {
        $scope.addProductForm.cft = orderService.doorcalculation(x);
    };

    // Initialize - load data on controller load
    $scope.tableData();

});