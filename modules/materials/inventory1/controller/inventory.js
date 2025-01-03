app.controller('inventoryCtrl', function($scope, $http, $timeout, $state) {
        
        var jsonData = jQuery.parseJSON(localStorage.getItem("inventory"));
        if(jsonData){
            $scope.editPermission = jsonData['edit'];
        }
        else{
            $state.go('home.dashboard');
        }

        //change table type
        $scope.tableData = function(x, y) {
            $('#preloader').show();
            $http({
                url: './v1/getinventorylist',
                method: "POST",
                data: {'cancel_status': x, 'emp_id': localStorage.getItem("user_id"), 'project_short': y}
            })
            .then(function (response){
                $scope.projects = response.data.site_list;
                $scope.list = response.data.inventory;
				console.log($scope.list)
                $scope.filteredItems = $scope.list.length;
                $scope.filter();
                $timeout(function() { $('#preloader').fadeOut("slow"); }, 200);
            });
        };
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
            $http({
                url: './v1/getstockdetails',
                method: "POST",
                data: { 'id': x }
            })
            .then(function (response) {
                $('#stockModal').modal('show');
                $scope.dc_list = response.data.dc_list;
                $scope.stockForm = response.data.inventory;
                $scope.stockForm.status = 'receive';
                $scope.stockForm.return_status = 'at';
            });
        };
        
        //form reset
        $scope.tableReset = function(){
            $scope.stockForm.received_qty = '';
            $scope.stockForm.return_qty = '';
            $scope.stockForm.return_reason = '';
        };
        
        //detailed view
        $scope.onView = function(x) {
            $http({
                url: './v1/inventorydetailedview',
                method: "POST",
                data: { 'id': x._id.$oid }
            })
            .then(function (response) {
                $('#detailModal').modal('show');
                $scope.detailForm = x;
                $scope.tableList = response.data.inventory;
            });
            $scope.currentModalPage = 1;
        };
        $scope.modalSort_by = function(modalPredicate) {
            $scope.modalPredicate = modalPredicate;
            $scope.modalReverse = !$scope.modalReverse;
        };
        
        //stock update
        $scope.onStockUpdate = function()
        {
            $scope.stockForm.emp_id = localStorage.getItem("user_id");			
			
			 var stkQty = parseInt($scope.stockForm.stock) + parseInt($scope.stockForm.return) + parseInt($scope.stockForm.received_qty);
			console.log($scope.stockForm);
            if($scope.stockForm.status=='receive')
            {
				  $http({
                url: './v1/getsingleproductdetails',
                method: "POST",
                data: {'product_name': $scope.stockForm.item}
            })
            .then(function (response) {				              
                $scope.productlist = response.data.product_details;		
					  console.log($scope.productlist[0].category)
		if($scope.productlist[0].category === 'Steel' || $scope.productlist[0].category === 'Sand / Blue Metal' || $scope.productlist[0].category === 'MS Items')
			{
				stkQtyper = ((parseInt($scope.stockForm.quantity)*20)/100);
				var qty = parseInt($scope.stockForm.quantity)+stkQtyper;
				var stkQty = parseInt($scope.stockForm.stock) + parseInt($scope.stockForm.return) + parseInt($scope.stockForm.received_qty);
				
			}
			else
			{
				var qty = parseInt($scope.stockForm.quantity);
			    var stkQty = parseInt($scope.stockForm.stock) + parseInt($scope.stockForm.return) + parseInt($scope.stockForm.received_qty);
		    }	
					  
                if(stkQty <= qty)
                {
					console.log(stkQty , "<=" , qty)
					$scope.onStockUpdateData($scope.stockForm);
                }
                else 
				{
                    $scope.modalAlert = true;
                    $scope.modal_alert_content = "Received quantity exceed the ordered quantity(more than 10%) !!!";
                }	
						   
            });
                $scope.stockForm.return_qty = '0';
            }
            else {
                $scope.stockForm.received_qty = '0';
                if($scope.stockForm.return_status=='after')
                {
                    $scope.stockForm.available = $scope.stockForm.stock - $scope.stockForm.usage;
                    if($scope.stockForm.available >= $scope.stockForm.return_qty)
                    {
                        $scope.onStockUpdateData($scope.stockForm);
                    }
                    else {
                        $scope.modalAlert = true;
                        $scope.modal_alert_content = 'There is no stock available to return !!!';
                    }
                }
                else if($scope.stockForm.return_status=='at')
                {
                    var stkQty = parseInt($scope.stockForm.stock) + parseInt($scope.stockForm.return) + parseInt($scope.stockForm.return_qty);
                    if($scope.stockForm.quantity >= stkQty)
                    {
                        $scope.onStockUpdateData($scope.stockForm);
                    }
                    else {
                        $scope.modalAlert = true;
                        $scope.modal_alert_content = "Return quantity exceed the ordered quantity !!!";
                    }
                }
            }
        };
    
        //stock update in db
        $scope.onStockUpdateData = function(x)
        {
            $('#sendLoader').addClass('fa fa-circle-o-notch fa-spin');
            $('#send, #cancel').prop('disabled', true);
            $http({
                url: './v1/stockupdate_in',
                method: "POST",
                data: x
            })
            .then(function (response){
                $('#stockModal').modal('hide');
                $scope.alert = true;
                $scope.tableData($scope.tabletype, $scope.project_short);
                if(response.data.code == 'Success'){
                    $(".alert").removeClass().addClass('alert alert-success');
                    $scope.alert_content = 'Updated Successfully !!!';
                }
                else{
                    $(".alert").removeClass().addClass('alert alert-danger');
                    $scope.alert_content = 'Failure !!!';
                }
                $('#send, #cancel').prop('disabled', false);
                $('#sendLoader').removeClass('fa fa-circle-o-notch fa-spin');
                $timeout(function() { $scope.alert = false; }, 2000);
            });
        }
	
				   //export
    $scope.export = function () {
        window.location.href = 'modules/materials/inventory/inventory_export.php?status='+$scope.tabletype+'&project_short='+$scope.project_short+'&emp_id='+localStorage.getItem("user_id");
		console.log ('export');
    }
		
});