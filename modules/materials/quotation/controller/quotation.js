app.controller('quotationCtrl', function($scope, $http, $timeout, $state, $stateParams) {
        
        var jsonData = jQuery.parseJSON(localStorage.getItem("quotation"));
        if(jsonData){
            $scope.addPermission = jsonData['add'];
            $scope.editPermission = jsonData['edit'];
            $scope.deletePermission = jsonData['delete'];
            $scope.mailPermission = jsonData['mail'];
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
        if(!$scope.tabletype){ $scope.tabletype = ''; }
        if(!$scope.entryLimit){ $scope.entryLimit = '10'; }
        if(!$scope.search){ $scope.search = ''; }
        if(!$scope.pageNo){ $scope.pageNo = 1; }
        
        //change table type
        $scope.tableData = function(x, y) {
            $('#preloader').show();
            $http({
                url: './v1/getquotationlist',
                method: "POST",
                data: {'status': x, 'emp_id': localStorage.getItem("user_id"), 'project_short': y}
            })
            .then(function (response){
                $scope.projects = response.data.site_list;
                $scope.list = response.data.quotation_data;
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
				localStorage.setItem("quot_nos",JSON.stringify($scope.filtered));
				
            }, 10);
        };
        $scope.sort_by = function(predicate) {
            $scope.predicate = predicate;
            $scope.reverse = !$scope.reverse;
        };
        
        //edit
        $scope.onEdit = function(x) {
            if(!$scope.project_short){ $scope.project_short = ''; }
            localStorage.setItem("quot_number", x);
            $state.go('home.quotation_update', { filter: $scope.project_short+'_'+$scope.tabletype+'_'+$scope.entryLimit+'_'+$scope.search+'_'+$scope.currentPage });
        }
        
        //view
        $scope.onView = function(x) {
            if(!$scope.project_short){ $scope.project_short = ''; }
            localStorage.setItem("quot_number", x);
            $state.go('home.quotation_view', { filter: $scope.project_short+'_'+$scope.tabletype+'_'+$scope.entryLimit+'_'+$scope.search+'_'+$scope.currentPage });
        }
        
        //pdf
        $scope.onPdf = function(x){
            window.location.href = 'modules/materials/quotation/quotation_pdf.php?type=none&id='+x+'&emp_id='+localStorage.getItem("user_id");
        }
        
        //mail
        $scope.onMail = function (x) {
            $http({
                url: './v1/getquotationmailcontent',
                method: "POST",
                data: {'quot_number': x, 'emp_id': localStorage.getItem("user_id")}
            })
            .then(function (response){
                $('#mailModal').modal('show');
                $scope.mailForm = {};
                $scope.mailForm.quot_number = x;
                $scope.mailForm.emp_id = localStorage.getItem("user_id");
                $scope.mailForm.from_mail = response.data.mail_id;
                $scope.mailForm.to_mail = "suhail@sis.in";
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
                url: './v1/sendquotationmail',
                method: "POST",
                data: {'jsonArrayData': jsonArrayData}
            })
            .then(function(response) {
                $('#mailModal').modal('hide');
                $scope.alert = true;
                $scope.tableData($scope.tabletype, $scope.project_short);
                if(response.data.code == 'Success'){
                    $(".alert").removeClass().addClass('alert alert-success');
                    $scope.alert_content = "Mail send successfully !!!";
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
        
         //delete order
        $scope.onDelete = function(x) {
            $('#confirmModal').modal('show');
            $scope.alert_msg = 'Are you sure to permanently delete this?';
            $scope.modal_id = x;
            $scope.confirmFn = 'delete';
        }
        $scope.delete = function(x) {
            $http({
                url: './v1/deletequotation',
                method: "POST",
                data: {'type': 'deletequotation', 'quot_number': x}
            })
            .then(function(response){
                $('#confirmModal').modal('hide');
                $scope.alert = true;
                $scope.tableData($scope.tabletype, $scope.project_short);
                if(response.data.code == 'Success'){
                    $(".alert").removeClass().addClass('alert alert-success');
                    $scope.alert_content = 'Deleted Successfully !!!';
                }
                else{
                    $(".alert").removeClass().addClass('alert alert-danger');
                    $scope.alert_content = 'Failure !!!';
                }
            });
            $timeout(function() { $scope.alert = false; }, 2000);
        }
        
        $scope.onAddOrder = function(x) {
            $state.go('home.new_purchase', { filter: '_usage_10__1_'+x });
        }
});