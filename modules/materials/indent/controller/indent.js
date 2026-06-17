app.controller('indentCtrl', function($scope, $http, $timeout, $state, $stateParams) {
        
    var jsonData = jQuery.parseJSON(localStorage.getItem("indent"));
    if(jsonData){
        $scope.addPermission = jsonData['add'];
        $scope.editPermission = jsonData['edit'];
        $scope.deletePermission = jsonData['delete'];
        $scope.mailPermission = jsonData['mail'];
        $scope.quotPermission = jsonData['quotation'];
    }
    else{
        $state.go('home.dashboard');
    }
    
    // State parameter
    if($stateParams.filter != '') {
        var splitData = $stateParams.filter.split("_");
        $scope.project_short = splitData[0];
        $scope.tabletype = splitData[1];
        $scope.entryLimit = splitData[2];
        $scope.search = splitData[3];
        $scope.pageNo = parseInt(splitData[4]) || 1;
    }
    
    if(!$scope.project_short){ $scope.project_short = ''; }
    if(!$scope.tabletype){ $scope.tabletype = 'usage'; }
    if(!$scope.entryLimit){ $scope.entryLimit = '10'; }
    if(!$scope.search){ $scope.search = ''; }
    if(!$scope.pageNo){ $scope.pageNo = 1; }
    
    // Pagination variables
    $scope.currentPage = $scope.pageNo;
    $scope.totalRecords = 0;
    $scope.totalPages = 0;
    $scope.filteredItems = 0;
    $scope.list = [];
    $scope.projects = [];
    $scope.searchTimeout = null;
    
    // Main function to fetch paginated data
    $scope.tableData = function(cancelStatus, projectShort) {
        $('#preloader').show();
        
        var requestData = {
            emp_id: localStorage.getItem("user_id"),
            project_short: projectShort || '',
            cancel_status: cancelStatus,
            quot_status: $scope.quotPermission,
            page: $scope.currentPage,
            limit: parseInt($scope.entryLimit),
            search: $scope.search
        };
        
        $http({
            url: './v1/getindentlistPaginated',
            method: "POST",
            data: requestData
        })
        .then(function(response) {
            console.log(response);
            $scope.projects = response.data.site_list;
            $scope.list = response.data.indent_data;
            $scope.totalRecords = response.data.pagination.total_records;
            $scope.totalPages = response.data.pagination.total_pages;
            $scope.filteredItems = response.data.pagination.total_records;
            
            // Store filtered PRF numbers
            localStorage.setItem("prfnos", JSON.stringify($scope.list));
            
            $scope.showDelBtn = false;
            $timeout(function() { $('#preloader').fadeOut("slow"); }, 200);
        })
        .catch(function(error) {
            console.error('Error fetching data:', error);
            $scope.list = [];
            $scope.filteredItems = 0;
            $timeout(function() { $('#preloader').fadeOut("slow"); }, 200);
        });
    };
    
    // Page change handler - called by uib-pagination
    $scope.pageChanged = function() {
        $scope.tableData($scope.tabletype, $scope.project_short);
    };
    
    // Entry limit change handler
    $scope.pageEntries = function() {
        $scope.currentPage = 1; // Reset to first page
        $scope.tableData($scope.tabletype, $scope.project_short);
    };
    
    // Project change handler
    $scope.projectChanged = function() {
        $scope.currentPage = 1; // Reset to first page
        $scope.tableData($scope.tabletype, $scope.project_short);
    };
    
    // Table type change handler
    $scope.tableTypeChanged = function() {
        $scope.currentPage = 1; // Reset to first page
        $scope.tableData($scope.tabletype, $scope.project_short);
    };
    
    // Search with debounce (wait 500ms after user stops typing)
    $scope.filter = function() {
        if ($scope.searchTimeout) {
            $timeout.cancel($scope.searchTimeout);
        }
        $scope.searchTimeout = $timeout(function() {
            $scope.currentPage = 1; // Reset to first page on search
            $scope.tableData($scope.tabletype, $scope.project_short);
        }, 500);
    };
    
    // Sort function
    $scope.sort_by = function(predicate) {
        $scope.predicate = predicate;
        $scope.reverse = !$scope.reverse;
    };
    
    // Get showing text for pagination info
    $scope.getShowingText = function() {
        if ($scope.totalRecords === 0) {
            return 'No entries';
        }
        var start = (($scope.currentPage - 1) * parseInt($scope.entryLimit)) + 1;
        var end = Math.min($scope.currentPage * parseInt($scope.entryLimit), $scope.totalRecords);
        return 'Showing ' + start + ' to ' + end + ' of ' + $scope.totalRecords + ' entries';
    };
    
    // Add
    $scope.onAdd = function(){
        if(!$scope.project_short){ $scope.project_short = ''; }
        $state.go('home.new_indent', { filter: $scope.project_short+'_'+$scope.tabletype+'_'+$scope.entryLimit+'_'+$scope.search+'_'+$scope.currentPage });
    };
    
    // Edit
    $scope.onEdit = function(x){
        if(!$scope.project_short){ $scope.project_short = ''; }
        localStorage.setItem("prf_number", x);
        $state.go('home.indent_update', { filter: $scope.project_short+'_'+$scope.tabletype+'_'+$scope.entryLimit+'_'+$scope.search+'_'+$scope.currentPage });
    };
    
    // Duplicate
    $scope.onDuplicate = function(x){
        if(!$scope.project_short){ $scope.project_short = ''; }
        localStorage.setItem("prf_number", x);
        $state.go('home.indent_duplicate', { filter: $scope.project_short+'_'+$scope.tabletype+'_'+$scope.entryLimit+'_'+$scope.search+'_'+$scope.currentPage });
    };
    
    // View
    $scope.onView = function(x, index){
        if(!$scope.project_short){ $scope.project_short = ''; }
        localStorage.setItem("prf_number", x);
        localStorage.setItem("index", index);
        $state.go('home.indent_view', { filter: $scope.project_short+'_'+$scope.tabletype+'_'+$scope.entryLimit+'_'+$scope.search+'_'+$scope.currentPage });
    };
    
    // View quotation details
    $scope.onQuotView = function(x){
        $http({
            url: './v1/indentdetailedview',
            method: "POST",
            data: { 'prf_number': x }
        })
        .then(function (response) {
            $('#detailModal').modal('show');
            $scope.mailForm = {};
            $scope.mailForm.prf_number = x;
            $scope.tableList = response.data.quot_list;
        });
    };
    
    // PDF
    $scope.onPdf = function(x){
        window.location.href = 'modules/materials/indent/indent_pdf.php?type=none&id='+x+'&emp_id='+localStorage.getItem("user_id");
    };
    
    // Remove order
    $scope.onRemove = function(x) {
        $('#confirmModal').modal('show');
        $scope.alert_msg = 'Are you sure to remove this?';
        $scope.modal_id = x;
        $scope.confirmFn = 'remove';
    };
    
    $scope.remove = function(x) {
        $http({
            url: './v1/deleteindent',
            method: "POST",
            data: {'type': 'removeindent','prf_number': x}
        })
        .then(function(response){
            $('#confirmModal').modal('hide');
            $scope.alert = true;
            $scope.tableData($scope.tabletype, $scope.project_short);
            if(response.data.code == 'Success'){
                $(".alert").removeClass().addClass('alert alert-success');
                $scope.alert_content = 'Removed Successfully !';
            }
            else{
                $(".alert").removeClass().addClass('alert alert-danger');
                $scope.alert_content = 'Failure !';
            }
        });
        $timeout(function() { $scope.alert = false; }, 2000);
    };
    
    // Reopen order
    $scope.onReopen = function(x) {
        $('#confirmModal').modal('show');
        $scope.alert_msg = 'Are you sure to reopen this?';
        $scope.modal_id = x;
        $scope.confirmFn = 'reopen';
    };
    
    $scope.reopen = function(x) {
        $http({
            url: './v1/deleteindent',
            method: "POST",
            data: {'type': 'reopenindent','prf_number': x}
        })
        .then(function(response){
            $('#confirmModal').modal('hide');
            $scope.alert = true;
            $scope.tableData($scope.tabletype, $scope.project_short);
            if(response.data.code == 'Success'){
                $(".alert").removeClass().addClass('alert alert-success');
                $scope.alert_content = 'Reopened Successfully !';
            }
            else{
                $(".alert").removeClass().addClass('alert alert-danger');
                $scope.alert_content = 'Failure !';
            }
        });
        $timeout(function() { $scope.alert = false; }, 2000);
    };
    
    // Delete order
    $scope.onDelete = function(x) {
        $('#confirmModal').modal('show');
        $scope.alert_msg = 'Are you sure to permanently delete this?';
        $scope.modal_id = x;
        $scope.confirmFn = 'delete';
    };
    
    $scope.delete = function(x) {
        $http({
            url: './v1/deleteindent',
            method: "POST",
            data: {'type': 'deleteindent', 'prf_number': x}
        })
        .then(function(response){
            $('#confirmModal').modal('hide');
            $scope.alert = true;
            $scope.tableData($scope.tabletype, $scope.project_short);
            if(response.data.code == 'Success'){
                $(".alert").removeClass().addClass('alert alert-success');
                $scope.alert_content = 'Deleted Successfully !';
            }
            else{
                $(".alert").removeClass().addClass('alert alert-danger');
                $scope.alert_content = 'Failure !';
            }
        });
        $timeout(function() { $scope.alert = false; }, 2000);
    };

    // Mail functionality
    $scope.querySearch = function(query) {
        $scope.results = query ? $scope.maildatas.filter($scope.createFilterFor(query)) : [];
        console.log("Result", $scope.results);
        return $scope.results;
    };
    
    $scope.createFilterFor = function(query) {
        var lowercaseQuery = angular.lowercase(query);
        return function(mail_id) {
            return (mail_id._lowername.indexOf(lowercaseQuery) === 0);
        };
    };

    // Mail
    $scope.onMail = function (x) {			
        $scope.readonly = false;
        $scope.selectedItem = null;
        $scope.searchText = null;
        $scope.querySearch = $scope.querySearch; 
     
        $scope.to_mail = [];
        $scope.cc_mail = [];
        $scope.bcc_mail = [];
        $scope.vendormail = [];							
        
        $http({
            url: './v1/getindentmailcontent',
            method: "POST",
            data: {'send_from': 'home', 'prf_number': x, 'emp_id': localStorage.getItem("user_id")}
        })
        .then(function (response){
            $('#mailModal').modal('show');	
            $scope.mailForm = {};
            $scope.mailForm.prf_number = x;
            $scope.mailForm.emp_id = localStorage.getItem("user_id");
            $scope.mailForm.from_mail = response.data.mail_id;  
            $scope.to_mail.push({mail_id:'vinodhini@sis.in'});
            $scope.cc_mail.push({mail_id:'ranjith@sis.in'});              
            $scope.mail_list = response.data.user_mail;
            console.log("datalist", $scope.mail_list);
            $scope.mailForm.subject = response.data.subject;
            document.getElementById('editor').innerHTML = response.data.content;			
            console.log("data", response.data.user_mail);	
            $scope.loadMailids(response.data.user_mail);			
            $scope.maildatas = $scope.loadMailids(response.data.user_mail);
        });
    };
    
    $scope.loadMailids = function(data) {
        $scope.mailids = data;
        console.log(data);
        return $scope.mailids.map(function (mailid) {
            mailid._lowername = mailid.mail_id.toLowerCase();
            return mailid;
        });
    };

    // Send mail
    $scope.onSend = function(){
        $scope.mailForm.content = $("#editor").html();
        $('#sendLoader').addClass('fa fa-circle-o-notch fa-spin');
        $('#send, #cancel').prop('disabled', true);
        
        var to_id = $scope.to_mail;
        var cc_id = $scope.cc_mail;
        var bcc_id = $scope.bcc_mail;
        
        if($scope.to_mail) {
            for(var i = 0; i < to_id.length; i++) {					
                if(i === 0) {
                    $scope.mailForm.to_mail = to_id[i].mail_id;
                } else {
                    $scope.mailForm.to_mail += "," + to_id[i].mail_id;
                }
            }	
        }
        
        if($scope.cc_mail) {
            for(var i = 0; i < cc_id.length; i++) {					
                if(i === 0) {
                    $scope.mailForm.cc_mail = cc_id[i].mail_id;
                } else {
                    $scope.mailForm.cc_mail += "," + cc_id[i].mail_id;
                }
            }	
        }
        
        if($scope.bcc_mail) {
            for(var i = 0; i < bcc_id.length; i++) {					
                if(i === 0) {
                    $scope.mailForm.bcc_mail = bcc_id[i].mail_id;
                } else {
                    $scope.mailForm.bcc_mail += "," + bcc_id[i].mail_id;
                }
            }	
        }
        
        var jsonArrayData = JSON.stringify($scope.mailForm);
        $http({
            url: './v1/sendindentmail',
            method: "POST",
            data: {'jsonArrayData': jsonArrayData}
        })
        .then(function(response) {
            console.log("log", response);
            $('#mailModal').modal('hide');
            $scope.alert = true;
            $scope.tableData($scope.tabletype, $scope.project_short);
            if(response.data.code == 'Success'){
                $(".alert").removeClass().addClass('alert alert-success');
                $scope.alert_content = "Mail send successfully !";
            }
            else{
                $(".alert").removeClass().addClass('alert alert-danger');
                $scope.alert_content = response.data.code;
            }
            $('#send, #cancel').prop('disabled', false);
            $('#sendLoader').removeClass('fa fa-circle-o-notch fa-spin');
            $timeout(function() { $scope.alert = false; }, 2000);
        });
    };
    
    // Display delete button
    $scope.deleteShow = function () {
        var count = 0;
        angular.forEach($scope.list, function(x){
            if(x.singlecheck) {
                count++;
            }
        });
        $scope.showDelBtn = count > 0;
    };
    
    // Delete all
    $scope.deleteall = function() {
        $scope.tableItems = [];
        angular.forEach($scope.list, function(x){
            if(x.singlecheck) {
                $scope.tableItems.push({ type: $scope.tabletype, prf_number: x.prf_number });  
            }
        });
        var jsonArrayData = JSON.stringify($scope.tableItems);
        
        if(jsonArrayData != '[]') {
            $('#confirmModal').modal('show');
            $scope.modal_id = jsonArrayData;
            $scope.confirmFn = 'alldelete';

            if($scope.tabletype == 'usage'){
                $scope.alert_msg = 'Are you sure to remove the selected indents?';
                $scope.alert_content = 'Removed Successfully !';
            }
            else{
                $scope.alert_msg = 'Are you sure to permanently delete the selected indents?';
                $scope.alert_content = 'Deleted Successfully !';
            }
        }
    };
    
    $scope.alldelete = function(x) {
        $http({
            url: './v1/deleteallindent',
            method: "POST",
            data: {'jsonArrayData': x}
        })
        .then(function(response){
            $('#confirmModal').modal('hide');
            $scope.alert = true;
            $scope.tableData($scope.tabletype, $scope.project_short);
            if(response.data.code == 'Success'){
                $(".alert").removeClass().addClass('alert alert-success');
            }
            else{
                $(".alert").removeClass().addClass('alert alert-danger');
                $scope.alert_content = 'Failure !';
            }
        });
        $timeout(function() { $scope.alert = false; }, 2000);
    };
    
    $scope.onAddQuotation = function(x) {
        $state.go('home.new_quotation', { filter: '__10__1_'+x });
    };
    
    // Initialize - load data on controller load
    $scope.tableData($scope.tabletype, $scope.project_short);
    
});