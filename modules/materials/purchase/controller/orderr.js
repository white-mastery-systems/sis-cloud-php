app.controller('orderCtrl', function($scope, $http, $timeout, $state, $stateParams, orderService) {
        
        var jsonData = jQuery.parseJSON(localStorage.getItem("order"));
        if(jsonData){
            $scope.addPermission = jsonData['add'];
            $scope.editPermission = jsonData['edit'];
            $scope.deletePermission = jsonData['delete'];
            $scope.mailPermission = jsonData['mail'];
			$scope.emp_designation=localStorage.getItem("user_designation");
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
                url: './v1/getorderlist',
                method: "POST",
                data: {'cancel_status': x, 'emp_id': localStorage.getItem("user_id"), 'project_short': y}
            })
            .then(function (response){
                $scope.projects = response.data.site_list;
                $scope.list = response.data.purchase_data;
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
				console.log("filtered---------", $scope.filtered);
				localStorage.setItem("po_nos",JSON.stringify($scope.filtered));
            }, 10);
        };
        $scope.sort_by = function(predicate) {
            $scope.predicate = predicate;
            $scope.reverse = !$scope.reverse;
        };
        
        //add
        $scope.onAdd = function(){
            if(!$scope.project_short){ $scope.project_short = ''; }
            $state.go('home.new_purchase', { filter: $scope.project_short+'_'+$scope.tabletype+'_'+$scope.entryLimit+'_'+$scope.search+'_'+$scope.currentPage });
        }
        
        //edit
        $scope.onEdit = function(x){
            if(!$scope.project_short){ $scope.project_short = ''; }
            localStorage.setItem("po_number", x);
            $state.go('home.purchase_update', { filter: $scope.project_short+'_'+$scope.tabletype+'_'+$scope.entryLimit+'_'+$scope.search+'_'+$scope.currentPage });
        }
        
        //duplicate
        $scope.onDuplicate = function(x){
            if(!$scope.project_short){ $scope.project_short = ''; }
            localStorage.setItem("po_number", x);
            $state.go('home.purchase_duplicate', { filter: $scope.project_short+'_'+$scope.tabletype+'_'+$scope.entryLimit+'_'+$scope.search+'_'+$scope.currentPage });
        }
        
        //view
        $scope.onView = function(x){
            if(!$scope.project_short){ $scope.project_short = ''; }
            localStorage.setItem("po_number", x);
            $state.go('home.purchase_view', { filter: $scope.project_short+'_'+$scope.tabletype+'_'+$scope.entryLimit+'_'+$scope.search+'_'+$scope.currentPage });
        }
        
        //pdf
        $scope.onPdf = function(x){
            window.location.href = 'modules/materials/purchase/po_pdf.php?type=none&id='+x+'&emp_id='+localStorage.getItem("user_id");
        }
        
        //word doc
        $scope.onWord = function(x){
            window.location.href = 'modules/materials/purchase/po_doc.php?id='+x+'&emp_id='+localStorage.getItem("user_id");
        }
        
		   //export
    $scope.export = function () {
        window.location.href = 'modules/materials/purchase/po_export.php?status='+$scope.tabletype+'&project_short='+$scope.project_short+'&emp_id='+localStorage.getItem("user_id");
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
                url: './v1/deleteorder',
                method: "POST",
                data: {'type': 'removeorder','po_number': x}
            })
            .then(function(response){
                $('#confirmModal').modal('hide');
                $scope.alert = true;
                $scope.tableData($scope.tabletype, $scope.project_short);
                if(response.data.code == 'Success'){
                    $(".alert").removeClass().addClass('alert alert-success');
                    $scope.alert_content = 'Removed Successfully !!!';
                }
                else{
                    $(".alert").removeClass().addClass('alert alert-danger');
                    $scope.alert_content = 'Failure !!!';
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
                url: './v1/deleteorder',
                method: "POST",
                data: {'type': 'reopenorder','po_number': x}
            })
            .then(function(response){
                $('#confirmModal').modal('hide');
                $scope.alert = true;
                $scope.tableData($scope.tabletype, $scope.project_short);
                if(response.data.code == 'Success'){
                    $(".alert").removeClass().addClass('alert alert-success');
                    $scope.alert_content = 'Reopened Successfully !!!';
                }
                else{
                    $(".alert").removeClass().addClass('alert alert-danger');
                    $scope.alert_content = 'Failure !!!';
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
                url: './v1/deleteorder',
                method: "POST",
                data: {'type': 'deleteorder', 'po_number': x}
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
        
        //mail
        $scope.onMail = function (x) {
            $http({
                url: './v1/getpomailcontent',
                method: "POST",
                data: {'po_number': x, 'emp_id': localStorage.getItem("user_id")}
            })
            .then(function (response){
                $('#mailModal').modal('show');
                $scope.mailForm = {};
                $scope.mailForm.po_number = x;
                $scope.mailForm.order_type_short = response.data.order_type_short;
                $scope.mailForm.emp_id = localStorage.getItem("user_id");
                $scope.mailForm.from_mail = response.data.mail_id;
                $scope.mailForm.to_mail = response.data.vendor_mail;
                $scope.mail_list = response.data.user_mail;
                $scope.mailForm.subject = response.data.subject;
                document.getElementById('editor').innerHTML = response.data.mail_data;
            });
        };
        
        //send mail
        $scope.onSend = function(){
            $scope.mailForm.content = $("#editor").html();
            $('#sendLoader').addClass('fa fa-circle-o-notch fa-spin');
            $('#send, #cancel').prop('disabled', true);
            $http({
                url: './v1/sendpomail',
                method: "POST",
                data: $scope.mailForm
            })
            .then(function(response) {
                $('#mailModal').modal('hide');
                $scope.alert = true;
                $scope.tableData($scope.tabletype, $scope.project_short);
                if(response.data.code == 'Success'){
                    $(".alert").removeClass().addClass('alert alert-success');
                    $scope.alert_content = "Mail sent successfully !";
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
                    $scope.tableItems.push({ type: $scope.tabletype, po_number: x.po_number });  
                }
            });
            var jsonArrayData = JSON.stringify($scope.tableItems);
            
            if(jsonArrayData != '[]')
            {
                $('#confirmModal').modal('show');
                $scope.modal_id = jsonArrayData;
                $scope.confirmFn = 'alldelete';

                if($scope.tabletype=='usage'){
                    $scope.alert_msg = 'Are you sure to remove the selected purchase orders?';
                    $scope.alert_content = 'Removed Successfully !';
                }
                else{
                    $scope.alert_msg = 'Are you sure to permanently delete the selected purchase orders?';
                    $scope.alert_content = 'Deleted Successfully !';
                }
            }
        }
        $scope.alldelete = function(x) {
            $http({
                url: './v1/deleteallorders',
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
        
        //add vendor
        $scope.addVendorModal = function(){
            $scope.addVendorForm = {};
            $('#addVendorModal').modal('show');
        }
        $scope.onAddVendor = function(){
            $http({
                url: './v1/addpovendor',
                method: "POST",
                data: $scope.addVendorForm
            })
            .then(function(response){
                $('#addVendorModal').modal('hide');
                $scope.alert = true;
                if(response.data.code == 'Success'){
                    $(".alert").removeClass().addClass('alert alert-success');
                    $scope.alert_content = 'Added Successfully !!!';
                }
                else{
                    $(".alert").removeClass().addClass('alert alert-danger');
                    $scope.alert_content = response.data.code;
                }
            });
            $timeout(function() { $scope.alert = false; }, 2000);
        }
        
        //add product
        $scope.addProductModal = function(){
            $http({
                url: './v1/getproductdetails',
                method: "POST",
                data: {'cancel_status': 'none'}
            })
            .then(function (response) {
                $scope.category_list = response.data.category_list;
            });
            $scope.addProductForm = {};
            $scope.addProductForm.type = 'Standard';
            $('#addProductModal').modal('show');
        }
        
        //change type
        $scope.typeChange = function(type, category, sub_category){
            //get product list
            if(type && category && sub_category) {
                $http({
                    url: './v1/getproductlist',
                    method: "POST",
                    data: { 'type':type, 'category':category, 'sub_category':sub_category }
                })
                .then(function (response){
                    $scope.product_list = response.data.product_list;
                });
            }
        };
    
        $scope.onAddProduct = function(){
            $http({
                url: './v1/addpoproduct',
                method: "POST",
                data: $scope.addProductForm
            })
            .then(function(response){
                $('#addProductModal').modal('hide');
                $scope.alert = true;
                if(response.data.code == 'Success'){
                    $(".alert").removeClass().addClass('alert alert-success');
                    $scope.alert_content = 'Added Successfully !';
                }
                else{
                    $(".alert").removeClass().addClass('alert alert-danger');
                    $scope.alert_content = response.data.code;
                }
            });
            $timeout(function() { $scope.alert = false; }, 2000);
        }
        
        $scope.doorcalc = function(x) {
            $scope.addProductForm.cft = orderService.doorcalculation(x);
        }
});