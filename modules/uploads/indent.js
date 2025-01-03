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
        
        //state parameter
        if($stateParams.filter != '') {
            var splitData = $stateParams.filter.split("_");
            $scope.project_short = splitData[0];
            $scope.tabletype = splitData[1];
            $scope.entryLimit = splitData[2];
            $scope.search = splitData[3];
            $scope.pageNo = splitData[4];
        }
        
        if(!$scope.project_short){ $scope.project_short = ''; }
        if(!$scope.tabletype){ $scope.tabletype = 'usage'; }
        if(!$scope.entryLimit){ $scope.entryLimit = '10'; }
        if(!$scope.search){ $scope.search = ''; }
        if(!$scope.pageNo){ $scope.pageNo = 1; }
        
        //change table type
        $scope.tableData = function(x, y) {
            $('#preloader').show();
            $http({
                url: './v1/getindentlist',
                method: "POST",
                data: {'cancel_status': x, 'quot_status': $scope.quotPermission, 'emp_id': localStorage.getItem("user_id"), 'project_short': y}
            })
            .then(function (response){
                $scope.projects = response.data.site_list;
                $scope.list = response.data.indent_data;
                $scope.filteredItems = $scope.list.length;
                $scope.setPage($scope.pageNo);
                $scope.filter();
                $scope.showDelBtn = false;
                $timeout(function() { $('#preloader').fadeOut("slow"); }, 200);
            });
        };
        
        $scope.setPage = function(pageNo) {
            $scope.currentPage = pageNo;
        };
        $scope.filter = function() {
            $timeout(function() { 
				$scope.filteredItems = $scope.filtered.length;
            }, 10);
        };
        $scope.sort_by = function(predicate) {
            $scope.predicate = predicate;
            $scope.reverse = !$scope.reverse;
        };
        
        //add
        $scope.onAdd = function(){
            if(!$scope.project_short){ $scope.project_short = ''; }
            $state.go('home.new_indent', { filter: $scope.project_short+'_'+$scope.tabletype+'_'+$scope.entryLimit+'_'+$scope.search+'_'+$scope.currentPage });
        }
        
        //edit
        $scope.onEdit = function(x){
            if(!$scope.project_short){ $scope.project_short = ''; }
            localStorage.setItem("prf_number", x);
            $state.go('home.indent_update', { filter: $scope.project_short+'_'+$scope.tabletype+'_'+$scope.entryLimit+'_'+$scope.search+'_'+$scope.currentPage });
        }
        
        //duplicate
        $scope.onDuplicate = function(x){
            if(!$scope.project_short){ $scope.project_short = ''; }
            localStorage.setItem("prf_number", x);
            $state.go('home.indent_duplicate', { filter: $scope.project_short+'_'+$scope.tabletype+'_'+$scope.entryLimit+'_'+$scope.search+'_'+$scope.currentPage });
        }
        
        //view
        $scope.onView = function(x){
            if(!$scope.project_short){ $scope.project_short = ''; }
            localStorage.setItem("prf_number", x);
            $state.go('home.indent_view', { filter: $scope.project_short+'_'+$scope.tabletype+'_'+$scope.entryLimit+'_'+$scope.search+'_'+$scope.currentPage });
        }
        
        //view quotation details
        $scope.onQuotView = function(x){
            $http({
                url: './v1/indentdetailedview',
                method: "POST",
                data: { 'prf_number': x }
            })
            .then(function (response) {
                $('#detailModal').modal('show');
                $scope.mailForm.prf_number = x;
                $scope.tableList = response.data.quot_list;
            });
        }
        
        //pdf
        $scope.onPdf = function(x){
            window.location.href = 'modules/materials/indent/indent_pdf.php?type=none&id='+x+'&emp_id='+localStorage.getItem("user_id");
        }
        
        //remove order
        $scope.onRemove = function(x) {
            $('#confirmModal').modal('show');
            $scope.alert_msg = 'Are you sure to remove this?';
            $scope.modal_id = x;
            $scope.confirmFn = 'remove';
        }
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
        }
        
        //reopen order
        $scope.onReopen = function(x) {
            $('#confirmModal').modal('show');
            $scope.alert_msg = 'Are you sure to reopen this?';
            $scope.modal_id = x;
            $scope.confirmFn = 'reopen';
        }
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
        }
        
        //delete order
        $scope.onDelete = function(x) {
            $('#confirmModal').modal('show');
            $scope.alert_msg = 'Are you sure to permanently delete this?';
            $scope.modal_id = x;
            $scope.confirmFn = 'delete';
        }
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
        }
        
        //mail
        $scope.onMail = function (x) {
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
                $scope.mailForm.to_mail = 'anisa@sis.in';
                $scope.mailForm.cc_mail = 'anandhi@sis.in';
                $scope.mail_list = response.data.user_mail;
                $scope.mailForm.subject = response.data.subject;
                document.getElementById('editor').innerHTML = response.data.content;
            });
        };
        
        //send mail
        $scope.onSend = function(){
            $scope.mailForm.content = $("#editor").html();
            $('#sendLoader').addClass('fa fa-circle-o-notch fa-spin');
            $('#send, #cancel').prop('disabled', true);
            var jsonArrayData = JSON.stringify($scope.mailForm);
            $http({
                url: './v1/sendindentmail',
                method: "POST",
                data: {'jsonArrayData': jsonArrayData}
            })
            .then(function(response) {
               //$('#mailModal').modal('hide');
                console.log(response);
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
        
        //display delete button
        $scope.deleteShow = function () {
            var count=0;
            angular.forEach($scope.filtered,function(x){
                if(x.singlecheck) {
                    count++;
                }
            });
            if(count > 0){
                $scope.showDelBtn = true;
            }
            else{
                $scope.showDelBtn = false;
            }
        }
        
        //delete all
        $scope.deleteall = function() {
            $scope.tableItems = [];
            angular.forEach($scope.filtered,function(x){
                if(x.singlecheck) {
                    $scope.tableItems.push({ type: $scope.tabletype, prf_number: x.prf_number });  
                }
            });
            var jsonArrayData = JSON.stringify($scope.tableItems);
            
            if(jsonArrayData != '[]')
            {
                $('#confirmModal').modal('show');
                $scope.modal_id = jsonArrayData;
                $scope.confirmFn = 'alldelete';

                if($scope.tabletype=='usage'){
                    $scope.alert_msg = 'Are you sure to remove the selected indents?';
                    $scope.alert_content = 'Removed Successfully !';
                }
                else{
                    $scope.alert_msg = 'Are you sure to permanently delete the selected indents?';
                    $scope.alert_content = 'Deleted Successfully !';
                }
            }
        }
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
        }
        
        $scope.onAddQuotation = function(x) {
            $state.go('home.new_quotation', { filter: '__10__1_'+x });
        }
});