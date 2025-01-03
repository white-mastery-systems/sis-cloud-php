app.controller('inventoryUseCtrl', function($scope, $http, $timeout, $state, $stateParams) {
        
        var jsonData = jQuery.parseJSON(localStorage.getItem("inventory_use"));
		$scope.empid = localStorage.getItem("user_id");
        if(jsonData){
            $scope.editPermission = jsonData['edit'];
        }
        else{
            $state.go('home.dashboard');
        }
        
        //page back
        $scope.onBack = function(){
            $state.go('home.category', { filter: 'inventory_use' });
        }
        
        $scope.currentPage = 1;
        $scope.entryLimit = 10;
        if(!$scope.project_short){ $scope.project_short = ''; }
        
        $scope.tableData = function(x) {
            $('#preloader').show();
            $http({
                url: './v1/getinventoryuselist',
                method: "POST",
                data: {'emp_id': localStorage.getItem("user_id"), 'project_short': x, 'category': $stateParams.filter}
            })
            .then(function (response){
                $scope.site = response.data.site_list;
                $scope.projects = response.data.project_list;
                $scope.list = response.data.inventory;
                $scope.filteredItems = $scope.list.length;
                $scope.filter();
                $timeout(function() { $('#preloader').fadeOut("slow"); }, 200);
            });
        }
        $scope.currentPage = 1;
        
        $scope.filter = function() {
            $timeout(function() { 
				$scope.filteredItems = $scope.filtered.length;
            }, 10);
        };
        $scope.sort_by = function(predicate) {
            $scope.predicate = predicate;
            $scope.reverse = !$scope.reverse;
        };
        
        $scope.onEdit = function(x) {
            $scope.stockForm = {};
            $scope.modalAlert = false;
            $('#stockModal').modal('show');
            if(x.block_name!='none') { var block = ' - '+x.block_name+' Block'; }
            else { var block = ''; }
            
            $scope.stockForm.site = x.project_name+block;
            $scope.stockForm.exist_project_name = x.project_name;
            $scope.stockForm.exist_block_name = x.block_name;
            $scope.stockForm.item = x.item;
            $scope.stockForm.code = x.code;
            $scope.stockForm.size = x.size;
            $scope.stockForm.available = x.available;
            $scope.stockForm.inventory_id = x.inventory_id;
            $scope.stockForm.project_id = x.project_id;
            $scope.stockForm.status = 'use';
            
            $http({
                url: './v1/get_flatlist',
                method: "POST",
                data: { 'id': x.project_id }
            })
            .then(function (response){
                $scope.flat_details = response.data.flat_details;
            });
            $scope.use_array = [{ 'use_flat_no': "" }];
            $scope.break_array = [{ 'break_flat_no': "" }];
			 $scope.balance_array = [{ 'balance_flat_no': "" }];
        };
        
        //table reset
        $scope.tableReset = function(){
            $scope.use_array = [{ 'use_flat_no': "" }];
            $scope.break_array = [{ 'break_flat_no': "" }];
			$scope.balance_array = [{ 'balance_flat_no': "" }];
            $scope.stockForm.total_use_qty = '';
            $scope.stockForm.total_break_qty = '';
			$scope.stockForm.total_balance_qty = '';
        };
        
	
	 $scope.onUseEdit = function(x,index,currentModalPage) {

			console.log(x.id);
			$scope.stockForm.balance_qty1 = '';
			$scope.typeUse = ((currentModalPage-1)*10)+index+1;					
 
        };
	
       $scope.onUseupdate = function(y,x,index,currentModalPage) {
			//console.log("update",x.id,"qty",$scope.stockForm.balance_qty1);
			$http({
                url: './v1/stockuse_balanceupdate',
                method: "POST",
                data: { 'id': x, balance:$scope.stockForm.balance_qty1 }
            })
            .then(function (response){
				$scope.typeUse = " "
				  if(response.data.code == 'Success'){
                            $(".alert").removeClass().addClass('alert alert-success');
                            $scope.alert_content = 'Updated Successfully !!!';
                        }
				y.balance = $scope.stockForm.balance_qty1;
				console.log(response);
            });

        };  
		
        //detailed view
        $scope.onView = function(x) {
            $http({
                url: './v1/inventoryusedetailedview',
                method: "POST",
                data: { 'project_id': x.project_id, 'code': x.code, 'item': x.item }
            })
            .then(function (response){
                $('#detailModal').modal('show');
                if(x.block_name!='none') { var block = ' - '+x.block_name+' Block'; }
                else { var block = ''; }
                $scope.stockForm.site = x.project_name+block;
                $scope.stockForm.item = x.item;
                $scope.tableList = response.data.inventory;
                $scope.currentModalPage = 1;
            });
        };
        $scope.modalSort_by = function(modalPredicate) {
            $scope.modalPredicate = modalPredicate;
            $scope.modalReverse = !$scope.modalReverse;
        };
        
        //add new row
        $scope.addUseNew = function(){ $scope.use_array.push({ 'use_flat_no': "" }); };
        $scope.addBreakNew = function(){ $scope.break_array.push({ 'break_flat_no': "" }); };
	    $scope.addBalanceNew = function(){ $scope.balance_array.push({ 'balance_flat_no': "" }); };
        
        //remove existing row
        $scope.useRemove = function(){
            var newDataList=[];
            angular.forEach($scope.use_array, function(selected){
                if(!selected.singlecheck){
                    newDataList.push(selected);
                }
            });
            
            if(newDataList==''){
                $scope.use_array = [{ 'use_flat_no': "" }];
            }
            else{
                $scope.use_array = newDataList;
            }
            $scope.useCheckedAll = '';
            $scope.calculateSum();
        };
        $scope.breakRemove = function(){
            var newDataList=[];
            angular.forEach($scope.break_array, function(selected){
                if(!selected.singlecheck){
                    newDataList.push(selected);
                }
            });
            
            if(newDataList==''){
                $scope.break_array = [{ 'break_flat_no': "" }];
            }
            else{
                $scope.break_array = newDataList;
            }
            $scope.breakCheckedAll = '';
            $scope.calculateSum();
        };
        
	
	$scope.balanceRemove = function(){
            var newDataList=[];
            angular.forEach($scope.balance_array, function(selected){
                if(!selected.singlecheck){
                    newDataList.push(selected);
                }
            });
            
            if(newDataList==''){
                $scope.balance_array = [{ 'balance_flat_no': "" }];
            }
            else{
                $scope.balance_array = newDataList;
            }
            $scope.balanceCheckedAll = '';
            $scope.calculateSum();
        };
        //check all
        $scope.useSelectAll = function () {
            $scope.use_array.forEach(function (v) {
                v.singlecheck = $scope.useCheckedAll;
            });
        };
        $scope.breakSelectAll = function () {
            $scope.break_array.forEach(function (v) {
                v.singlecheck = $scope.breakCheckedAll;
            });
        };
              $scope.balanceSelectAll = function () {
            $scope.balance_array.forEach(function (v) {
                v.singlecheck = $scope.balanceCheckedAll;
            });
        };
        //grand usage quantity
        $scope.calculateSum = function(){
            var sum = 0;
            if($scope.stockForm.status=='use'){
                angular.forEach($scope.use_array,function(stockForm){
                    if(!stockForm.used_qty){ stockForm.used_qty = ''; }
                    sum += stockForm.used_qty * 1;
                });
                $scope.stockForm.total_use_qty = sum;
            }
            else if($scope.stockForm.status=='break'){
                angular.forEach($scope.break_array,function(stockForm){
                    if(!stockForm.break_qty){ stockForm.break_qty = ''; }
                    sum += stockForm.break_qty * 1;
                });
                $scope.stockForm.total_break_qty = sum;
            }
			     else if($scope.stockForm.status=='balance'){
                angular.forEach($scope.balance_array,function(stockForm){
                    if(!stockForm.balance_qty){ stockForm.balance_qty = ''; }
                    sum += stockForm.balance_qty * 1;
                });
                $scope.stockForm.total_balance_qty = sum;
            }
        };
        
        $scope.useRepeatFn = function(x){
            $scope.use_array = x;
        };
        $scope.breakRepeatFn = function(x){
            $scope.break_array = x;
        };
            $scope.balanceRepeatFn = function(x){
            $scope.balance_array = x;
        };
        
        //stock update
        $scope.onStockUpdate = function() {
            $('#sendLoader').addClass('fa fa-circle-o-notch fa-spin');
            $('#send, #cancel').prop('disabled', true);
            if($scope.stockForm.status=='use'){
                $scope.stockForm.total_break_qty = '0';
                $scope.stockForm.total_transfer_qty = '0';
				$scope.stockForm.total_balance_qty = '0';
            }
            else if($scope.stockForm.status=='break'){
                $scope.stockForm.total_use_qty = '0';
                $scope.stockForm.total_transfer_qty = '0';
				$scope.stockForm.total_balance_qty = '0';
            }
			  else if($scope.stockForm.status=='balance'){
                $scope.stockForm.total_use_qty = '0';
                $scope.stockForm.total_transfer_qty = '0';
				$scope.stockForm.total_use_qty = '0';
            }
            else {
                $scope.stockForm.total_use_qty = '0';
                $scope.stockForm.total_break_qty = '0';
				$scope.stockForm.total_balance_qty = '0';
            }
            var usedQty = parseInt($scope.stockForm.total_use_qty) + parseInt($scope.stockForm.total_break_qty) + parseInt($scope.stockForm.total_transfer_qty)+parseInt($scope.stockForm.total_balance_qty);
			$scope.useTableItems = [];
            if($scope.stockForm.status=='balance')
			   {
			   angular.forEach($scope.balance_array,function(stockForm){
                        $scope.useTableItems.push({ flat_no: stockForm.balance_flat_no, contractor: stockForm.balance_contractor, quantity: stockForm.balance_qty });
                    });
			 $scope.stockForm.jsonArrayData = JSON.stringify($scope.useTableItems);
                $scope.stockForm.emp_id = localStorage.getItem("user_id");
			          $http({
                        url: './v1/stockupdate_use',
                        method: "POST",
                        data: $scope.stockForm
                    })
                    .then(function (response){
						  console.log(response);
                        $('#send, #cancel').prop('disabled', false);
                        $('#sendLoader').removeClass('fa fa-circle-o-notch fa-spin');
                        $('#stockModal').modal('hide');
                        $scope.alert = true;
                        $scope.tableData($scope.project_short);
                        if(response.data.code == 'Success'){
                            $(".alert").removeClass().addClass('alert alert-success');
                            $scope.alert_content = 'Updated Successfully !!!';
                        }
                        else{
                            $(".alert").removeClass().addClass('alert alert-danger');
                            $scope.alert_content = 'Failure !!!';
                        }
                        $timeout(function() { $scope.alert = false; }, 2000);
                    });
			
			   }
		else
			{		
            if($scope.stockForm.available >= usedQty)
            {
               
                if($scope.stockForm.status=='use'){
                    angular.forEach($scope.use_array,function(stockForm){
                        $scope.useTableItems.push({ flat_no: stockForm.use_flat_no, contractor: stockForm.contractor, quantity: stockForm.used_qty });
                    });
                }
                else{
                    angular.forEach($scope.break_array,function(stockForm){
                        $scope.useTableItems.push({ flat_no: stockForm.break_flat_no, quantity: stockForm.break_qty, reason: stockForm.break_reason });
                    });
                }
                $scope.stockForm.jsonArrayData = JSON.stringify($scope.useTableItems);
                $scope.stockForm.emp_id = localStorage.getItem("user_id");
                
                //transfer
                if($scope.stockForm.status=='transfer' && $scope.stockForm.exist_project_name==$scope.stockForm.project_name && $scope.stockForm.exist_block_name==$scope.stockForm.block_name)
                {
                    $scope.modalAlert = true;
                    $scope.modal_alert_content = 'Transfer only possible between different blocks !!!';
                }
                else {
                    $http({
                        url: './v1/stockupdate_use',
                        method: "POST",
                        data: $scope.stockForm
                    })
                    .then(function (response){
                        $('#send, #cancel').prop('disabled', false);
                        $('#sendLoader').removeClass('fa fa-circle-o-notch fa-spin');
                        $('#stockModal').modal('hide');
                        $scope.alert = true;
                        $scope.tableData($scope.project_short);
                        if(response.data.code == 'Success'){
                            $(".alert").removeClass().addClass('alert alert-success');
                            $scope.alert_content = 'Updated Successfully !!!';
                        }
                        else{
                            $(".alert").removeClass().addClass('alert alert-danger');
                            $scope.alert_content = 'Failure !!!';
                        }
                        $timeout(function() { $scope.alert = false; }, 2000);
                    });
                }
            }
            else {
                $scope.modalAlert = true;
                $scope.modal_alert_content = 'There is no limited stock to use !!!';
            }
}
        };
});