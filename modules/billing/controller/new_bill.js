app.controller('newBillCtrl', function($scope, $http, dateFilter, $state, $stateParams, orderService, $timeout) {
        
    //page back
    $scope.onBack = function(){
        $state.go('home.bill_list', $stateParams);
    }

    var jsonData = jQuery.parseJSON(localStorage.getItem("billing"));
    if(jsonData['add'] != 1){
        $state.go('home.dashboard');
    }
    else
    {
        $scope.bill_list = [];
        $scope.billForm = {};

        //get project list
        $('#preloader').show();
        $http({
            url: './v1/getBillingVendorList',
            method: "POST",
            data: { }
        })
        .then(function (response) {
            $scope.vendor_list = response.data.vendor_details;
            $scope.project_list = response.data.project_list;
            $scope.po_list = [];
            $timeout(function() { $('#preloader').fadeOut("slow"); }, 200);
        });

        $scope.sort_by = function(predicate) {
            $scope.predicate = predicate;
            $scope.reverse = !$scope.reverse;
        };

        $scope.onProjectChange = function() {
            $scope.search = '';
            $scope.billForm.vendor_bill_no = '';
            $scope.billForm.vendor_name = '';
            $scope.po_list = [];
            $scope.item_list = [];
            $scope.bill_list = [];
        };
        
        $scope.onPoDetails = function(x) {
            $scope.checkedAll = false;
            $scope.unloading_cost = '0'; $scope.transport_cost = '0'; $scope.transport_tax = '0'; $scope.transport_tax_amt = '0';$scope.loading_tax_amt = '0';$scope.loading_tax = '0';$scope.plain_tax_amt = '0';$scope.plain_tax = '0'; $scope.plain_cost = '0'
            $scope.po_number = x.po_number;
            $scope.po_date = x.po_date;
            $scope.po_type = x.po_type;
            $scope.project_id = x.project_id;
            $('#preloader').show();
            $http({
                url: './v1/billingPoDetails',
                method: "POST",
                data: { 'po_number': $scope.po_number, 'po_type': $scope.po_type }
            })
            .then(function (response) {
                console.log("response", response.data.purchase_items)
                $scope.item_list = response.data.purchase_items;			
                $timeout(function() { $('#preloader').fadeOut("slow"); }, 200);
            });
        };

        $scope.onTaxCalc = function() {
            if(!$scope.transport_cost){ $scope.transport_cost = ''; }
            if(!$scope.transport_tax){ $scope.transport_tax = ''; }
            $scope.transport_tax_amt = ($scope.transport_cost * ($scope.transport_tax/100)).toFixed(2);
        };
		
		
  $scope.onloadingTaxCalc = function() {
            if(!$scope.unloading_cost){ $scope.unloading_cost = ''; }
            if(!$scope.loading_tax){ $scope.loading_tax = ''; }
            $scope.loading_tax_amt = ($scope.unloading_cost * ($scope.loading_tax/100)).toFixed(2);
        };
        //check all
        $scope.selectAll = function () {
            $scope.item_list.forEach(function (v) {
                v.singlecheck = $scope.checkedAll;
            });
        }

        //row calculation
        $scope.rowcalc = function(x, index) {
            if(!x.unbilled_qty){ x.unbilled_qty = ''; }
            $scope.item_list[index].amount = (x.unbilled_qty * x.price).toFixed(2);
            $scope.item_list[index].sgst_amount = ((x.sgst)/100 * x.amount).toFixed(2);
            $scope.item_list[index].cgst_amount = ((x.cgst)/100 * x.amount).toFixed(2);
            $scope.item_list[index].total = ((x.amount * 1) + (x.sgst_amount * 1) + (x.cgst_amount * 1)).toFixed(2);
        }

        //door calculation
        $scope.doorcalc = function(x, index){
            if(!x.unbilled_qty){ x.unbilled_qty = ''; }
            var total = orderService.doorcalculation(x.size);
            $scope.item_list[index].cft = (total * x.unbilled_qty).toFixed(3);
            $scope.item_list[index].amount = x.cft * x.price;
            $scope.item_list[index].sgst_amount = ((x.sgst)/100 * x.amount).toFixed(2);
            $scope.item_list[index].cgst_amount = ((x.cgst)/100 * x.amount).toFixed(2);
            $scope.item_list[index].total = ((x.amount * 1) + (x.sgst_amount * 1) + (x.cgst_amount * 1)).toFixed(2);
        };

        $scope.addItemToBill = function () {
            var sum = 0;
			 var sum_cft = 0;
            var itemArray = [];
            angular.forEach($scope.item_list, function(selected) {
                if(selected.singlecheck){
                    sum += selected.total * 1;
					 sum_cft += selected.cft * 1;
                    itemArray.push(selected);
                }
            });
			
            if(!$scope.unloading_cost) { $scope.unloading_cost=''; }
            if(!$scope.transport_cost) { $scope.transport_cost=''; }
            if(!$scope.transport_tax_amt) { $scope.transport_tax_amt=''; }
			if(!$scope.loading_tax_amt) { $scope.loading_tax_amt=''; }
			
			if($scope.plain_cost > 0) {  			
			console.log("plain_cost",$scope.plain_cost)
			var plain_cft =  sum_cft;
			$scope.sum_cft = sum_cft;			
			var plaincharge =  sum_cft * $scope.plain_cost ;				
			var plain_tax_amt = ($scope.plain_tax)/100 * plaincharge;
			$scope.plain_tax_amt = plain_tax_amt;			
		
			}
			else{
				var plaincharge = 0
				var plain_tax_amt = 0;
				$scope.plain_cost = 0;
				$scope.plain_tax= 0;
				$scope.plain_tax_amt = 0;
			}			
		   
		
            $scope.sub_total = (sum + ($scope.unloading_cost*1) + ($scope.transport_cost*1) + ($scope.transport_tax_amt*1)+($scope.loading_tax_amt*1) + (plaincharge*1) + (plain_tax_amt*1)).toFixed(2);
      
			
            $scope.bill_list.push({
                "po_number": $scope.po_number, "po_date": $scope.po_date, "po_type": $scope.po_type, "project_id": $scope.project_id,"bill_item_list": itemArray, "unloading_cost": $scope.unloading_cost, "transport_cost": $scope.transport_cost,"transport_tax": $scope.transport_tax, "transport_tax_amt": $scope.transport_tax_amt,
                "loading_tax": $scope.loading_tax, "loading_tax_amt": $scope.loading_tax_amt,"plain_cost": $scope.plain_cost,
                "plain_tax": $scope.plain_tax, "plain_tax_amt": $scope.plain_tax_amt,"sum_cft":$scope.sum_cft, "sub_total": $scope.sub_total
            });
            $scope.item_list = [];
        }

        $scope.billCheckout = function() {
            $http({
                url: './v1/billcheckout',
                method: "POST",
                data: { 'project_short': $scope.billForm.project_short, 'vendor_name': $scope.billForm.vendor_name }
            })
            .then(function(response){
                if(response.data.code=='Success') {
                    $scope.calcGrandBillAmount();
                    $('#billModal').modal('show');
                }
                else {
                    $('#alertModal').modal('show');
                }
            });
        }

        //modal
        $scope.removePO = function(x) {
            $scope.bill_list.splice(x, 1);
            $scope.calcGrandBillAmount();
        }
        $scope.modalSelectAll = function (parentIndex) {
            $scope.bill_list[parentIndex]['bill_item_list'].forEach(function (v) {
                v.modalSingleCheck = $scope.bill_list[parentIndex]['modalCheckAll'];
            });
        }
        $scope.removeModalRow = function(parentIndex) {
            var newDataList=[];
            $scope.bill_list[parentIndex]['modalCheckAll'] = false;
            angular.forEach($scope.bill_list[parentIndex]['bill_item_list'], function(selected) {
                if(!selected.modalSingleCheck) {
                    newDataList.push(selected);
                }
            });
            $scope.bill_list[parentIndex]['bill_item_list'] = newDataList;
            $scope.calculateModalSum($scope.bill_list[parentIndex]);
        }

        $scope.calculateModalSum = function(x) {
            var sum = 0;
            angular.forEach(x['bill_item_list'], function(tableForm) {
                sum += tableForm.total * 1;
            });
            x['sub_total'] = (sum + (x['unloading'] * 1) + (x['transport'] * 1) + (x['transport'] * 1) ).toFixed(2);
            $scope.calcGrandBillAmount();
        };

        $scope.calcGrandBillAmount = function() {
            $scope.grand_total = 0;
            angular.forEach($scope.bill_list, function(tableForm) {
                $scope.grand_total += tableForm.sub_total * 1;
            });
        };

        //generate bill
        $scope.generateBill = function() {
            $scope.billForm.emp_id = localStorage.getItem("user_id");
            $scope.billForm.bill_list = $scope.bill_list;
            $scope.billForm.grand_total = $scope.grand_total;
            console.log($scope.billForm);
            $http({
                url: './v1/generatePurchaseBill',
                method: "POST",
                data: $scope.billForm
            })
            .then(function (response) {
                $('#billModal').modal('hide');
                if(response.data.code=='Success') {
                    $timeout(function() { 
                        $state.go('home.bill_list', $stateParams);
                    }, 500);
                }
                else {

                }
            });
        }
    }
});