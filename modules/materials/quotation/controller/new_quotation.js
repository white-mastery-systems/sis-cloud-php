app.controller('quotationNewCtrl', function($scope, $http, $state, $stateParams, orderService, $timeout) {
        
        var jsonData = jQuery.parseJSON(localStorage.getItem("indent"));
        var quotJsonData = jQuery.parseJSON(localStorage.getItem("quotation"));
        $scope.approvePermission = quotJsonData['approve'];
        if(!jsonData || jsonData['quotation'] != 1){
            $('#restrictModal').modal('show');
        }
    
        $scope.pageRedirect = function(){
            $('#restrictModal').modal('hide');
            localStorage.clear();
            $timeout(function() { $state.go('login'); }, 500);
        }
    
        //page back
        var splitData = $stateParams.filter.split("_");
        $scope.onBack = function(){
            $state.go('home.quotation', { filter: splitData[0]+'_'+splitData[1]+'_'+splitData[2]+'_'+splitData[3]+'_'+splitData[4] });
        }
    
        $('#preloader').show();
        $http({
            url: './v1/getquotationdetails',
            method: "POST",
            data: { 'prf_number': splitData[5] }
        })
        .then(function (response){
            if(jsonData['quotation'] == 1 && response.data.quot_status=='raised') {
                $('#restrictModal').modal('hide');
                $timeout(function() { $state.go('home.quotation', { filter: '' }); }, 500);
            }
            $scope.indentForm = response.data.indent_data;
            $scope.indent_list = response.data.indent_list;
            $scope.supplier_list = response.data.supplier_list;
            $scope.vendor_list = response.data.vendor_list;
            $scope.category_list = response.data.category_list;
            $scope.quot_list = [{
                "type" : $scope.indentForm.type,
                "quot_items" : [{
                    "vendor_name" : '',
                    "table_items" : [{ "available_stock" : '0' }]
                }]
            }];
            $timeout(function() { $('#preloader').fadeOut("slow"); }, 200);
        });
        
        //change type
        $scope.typeChange = function(type, category, quotIndex){
            //get project list
            if(type && category) {
                $http({
                    url: './v1/getproductlist',
                    method: "POST",
                    data: {'type': type, 'category': category}
                })
                .then(function (response){
                    $scope.product_list = response.data.product_list;
                });
            }
            $scope.quot_list[quotIndex]['quot_items'] = [{
                "vendor_name" : $scope.quot_list[quotIndex]['quot_items'][0].vendor_name,
                "reason" : $scope.quot_list[quotIndex]['quot_items'][0].reason,
                "table_items" : [{ "available_stock" : '0' }]
            }];
        };
        
        //row calculation
        $scope.rowcalc = function(x, quotIndex, vendorIndex, tableIndex) {
            if(!x.quantity){ x.quantity = ''; }
            if(!x.price){ x.price = ''; }
            if(!x.gst){ x.gst = ''; }
            $scope.quot_list[quotIndex]['quot_items'][vendorIndex]['table_items'][tableIndex].amount = (x.quantity * x.price).toFixed(2);
            $scope.quot_list[quotIndex]['quot_items'][vendorIndex]['table_items'][tableIndex].gst_amount = ((x.gst)/100 * x.amount).toFixed(2);
            $scope.quot_list[quotIndex]['quot_items'][vendorIndex]['table_items'][tableIndex].total = ((x.amount * 1) + (x.gst_amount * 1)).toFixed(2);
            $scope.calculateSum(quotIndex, vendorIndex);
        }
        
        //door calculation
        $scope.doorcalc = function(x, quotIndex, vendorIndex, tableIndex){
            if(x.size != null)
            {
                var total = orderService.doorcalculation(x.size);
                if(x.quantity==null){
                    quantity = 1;  
                }
                else{
                    quantity = x.quantity;
                }
                $scope.quot_list[quotIndex]['quot_items'][vendorIndex]['table_items'][tableIndex].cft = (total * quantity).toFixed(3);
            }
            else{
                $scope.quot_list[quotIndex]['quot_items'][vendorIndex]['table_items'][tableIndex].cft = '';
            }
            if(!x.quantity){ x.quantity = ''; }
            if(!x.price){ x.price = ''; }
            if(!x.gst){ x.gst = ''; }
            $scope.quot_list[quotIndex]['quot_items'][vendorIndex]['table_items'][tableIndex].amount = (x.cft * x.price).toFixed(2);
            $scope.quot_list[quotIndex]['quot_items'][vendorIndex]['table_items'][tableIndex].gst_amount = ((x.gst)/100 * x.amount).toFixed(2);
            $scope.quot_list[quotIndex]['quot_items'][vendorIndex]['table_items'][tableIndex].total = ((x.amount * 1) + (x.gst_amount * 1)).toFixed(2);
            $scope.calculateSum(quotIndex, vendorIndex);
        };
        
        //grand total calculation
        $scope.calculateSum = function(quotIndex, vendorIndex){
            var sum = 0;
            angular.forEach($scope.quot_list[quotIndex]['quot_items'][vendorIndex]['table_items'], function(tableForm){
                if(!tableForm.total){ tableForm.total = ''; }
                sum += tableForm.total * 1;
            });
            $scope.quot_list[quotIndex]['quot_items'][vendorIndex].grand_total = (sum).toFixed(2);
        };
        
        //add
        $scope.addNewQuot = function() {
            $scope.quot_list.push({
                "type" : $scope.indentForm.type,
                "quot_items" : [{
                    "vendor_name" : '',
                    "table_items" : [{ "available_stock" : '0' }]
                }]
            });
        };
	
	     $scope.adddupQuot = function(x) {
		   for(i=0;i<x.length; i++){			
			if(x[i].quotCheck)
				{
					$scope.tableItems = [];
					$scope.vendorItems = [];
					$scope.quotItems = [];					
					console.log(x[i]);
					for(var k=0; k<x[i].quot_items[0].table_items.length; k++)
					{						
					 $scope.tableItems.push({					
                    "code" :  x[i].quot_items[0].table_items[k]['code'],
                    "item" : x[i].quot_items[0].table_items[k]['item'],
                    "quantity" : x[i].quot_items[0].table_items[k]['quantity'],
                    "unit" : x[i].quot_items[0].table_items[k]['unit'],
                    "price" : x[i].quot_items[0].table_items[k]['price'],
                    "amount" :x[i].quot_items[0].table_items[k]['amount'],
                    "gst" : x[i].quot_items[0].table_items[k]['gst'],
                    "gst_amount" : x[i].quot_items[0].table_items[k]['gst_amount'],
                    "total" : x[i].quot_items[0].table_items[k]['total'],
                    "make" : x[i].quot_items[0].table_items[k]['make'],
                    "details" : x[i].quot_items[0].table_items[k]['details'],
                    "width" : x[i].quot_items[0].table_items[k]['width'],
                    "height" :x[i].quot_items[0].table_items[k]['height'],
                    "upvc_type" : x[i].quot_items[0].table_items[k]['upvc_type'],
                    "size" : x[i].quot_items[0].table_items[k]['size'],
                    "cft" : x[i].quot_items[0].table_items[k]['cft'],
                    "required_date" : x[i].quot_items[0].table_items[k]['required_date'],
                    "stock_details" :x[i].quot_items[0].table_items[k]['stock_details'],
                    "available_stock" :x[i].quot_items[0].table_items[k]['available_stock']					
                    }); 						
						
					}
			$scope.quotItems.push({
               "vendor_name":x[i].quot_items[0].vendor_name,
				"reason":x[i].quot_items[0].reason,	 
				"grand_total" : x[i].quot_items[0].grand_total,
                "table_items" : $scope.tableItems
            });
						
					
		
					
					$scope.quot_list.push({
						type: x[i].type,
						category: x[i].category,						
						quot_items:  	$scope.quotItems
						
					});
					break;
				}		
		}
        };
	
        $scope.addNewSuggestion = function(x, quotIndex) {
            $scope.tableItems = [];
            for(var i=0; i<$scope.quot_list[quotIndex]['quot_items'][0]['table_items'].length; i++)
            {
                $scope.tableItems.push({
                    "code" : $scope.quot_list[quotIndex]['quot_items'][0]['table_items'][i]['code'],
                    "item" : $scope.quot_list[quotIndex]['quot_items'][0]['table_items'][i]['item'],
                    "quantity" : $scope.quot_list[quotIndex]['quot_items'][0]['table_items'][i]['quantity'],
                    "unit" : $scope.quot_list[quotIndex]['quot_items'][0]['table_items'][i]['unit'],
                    "price" : $scope.quot_list[quotIndex]['quot_items'][0]['table_items'][i]['price'],
                    "amount" : $scope.quot_list[quotIndex]['quot_items'][0]['table_items'][i]['amount'],
                    "gst" : $scope.quot_list[quotIndex]['quot_items'][0]['table_items'][i]['gst'],
                    "gst_amount" : $scope.quot_list[quotIndex]['quot_items'][0]['table_items'][i]['gst_amount'],
                    "total" : $scope.quot_list[quotIndex]['quot_items'][0]['table_items'][i]['total'],
                    "make" : $scope.quot_list[quotIndex]['quot_items'][0]['table_items'][i]['make'],
                    "details" : $scope.quot_list[quotIndex]['quot_items'][0]['table_items'][i]['details'],
                    "width" : $scope.quot_list[quotIndex]['quot_items'][0]['table_items'][i]['width'],
                    "height" : $scope.quot_list[quotIndex]['quot_items'][0]['table_items'][i]['height'],
                    "upvc_type" : $scope.quot_list[quotIndex]['quot_items'][0]['table_items'][i]['upvc_type'],
                    "size" : $scope.quot_list[quotIndex]['quot_items'][0]['table_items'][i]['size'],
                    "cft" : $scope.quot_list[quotIndex]['quot_items'][0]['table_items'][i]['cft'],
                    "required_date" : $scope.quot_list[quotIndex]['quot_items'][0]['table_items'][i]['required_date'],
                    "stock_details" : $scope.quot_list[quotIndex]['quot_items'][0]['table_items'][i]['stock_details'],
                    "available_stock" : $scope.quot_list[quotIndex]['quot_items'][0]['table_items'][i]['available_stock']
                });  
            }
            x.push({
                "vendor_name" : '',
                "grand_total" : $scope.quot_list[quotIndex]['quot_items'][0].grand_total,
                "table_items" : $scope.tableItems
            });
        };
	
        $scope.addNewRow = function(x) {
            x.push({"available_stock" : '0'});
        };
        
        //remove
        $scope.removeQuot = function(){
            var newDataList=[];
            angular.forEach($scope.quot_list, function(selected){
                if(!selected.quotCheck){
                    newDataList.push(selected);
                }
            });
            if(newDataList==''){
                $scope.quot_list = [{
                    "type" : $scope.indentForm.type,
                    "quot_items" : [{
                        "vendor_name" : '',
                        "table_items" : [{ "available_stock" : '0' }]
                    }]
                }];
            }
            else { $scope.quot_list = newDataList; }
        };
        $scope.removeSuggestion = function(x, quotIndex){
            var newDataList=[];
            angular.forEach(x, function(selected){
                if(!selected.tabCheck){
                    newDataList.push(selected);
                }
            });
            if(newDataList==''){
                $scope.quot_list[quotIndex]['quot_items'] = [{
                    "table_items" : [{ "available_stock" : '0' }]
                }];
            }
            else { $scope.quot_list[quotIndex]['quot_items'] = newDataList; }
        };
        $scope.removeRow = function(x, quotIndex, vendorIndex){
            var newDataList=[];
            angular.forEach(x, function(selected){
                if(!selected.rowCheck){
                    newDataList.push(selected);
                }
            });
            if(newDataList==''){
                $scope.quot_list[quotIndex]['quot_items'][vendorIndex]['table_items'] = [{
                    "table_items" : [{ "available_stock" : '0' }]
                }];
            }
            else { $scope.quot_list[quotIndex]['quot_items'][vendorIndex]['table_items'] = newDataList; }
            $scope.calculateSum(quotIndex, vendorIndex);
        };
        
        $scope.onModal = function(){
            var vendorCount = 0;
            angular.forEach($scope.quot_list,function(quotForm){
                if(quotForm.quot_items.length > 1) {
                    vendorCount++;
                }
            });
            if(vendorCount==0) { $('#confirmModal').modal('show'); }
            else { $('#alertModal').modal('show'); }
        };
        
        //save, save and self approve
        $scope.onSubmit = function(x) {
            $scope.quotItems = [];
            angular.forEach($scope.quot_list,function(quotForm){
                $scope.quotItems.push({ type: quotForm.type, category: quotForm.category,  quot_items: quotForm.quot_items });
            });
            $scope.indentForm.jsonArrayData = JSON.stringify($scope.quotItems);
            $scope.indentForm.emp_id = localStorage.getItem("user_id");
            $scope.indentForm.approve_status = x;
            
            $http({
                url: './v1/newquotation',
                method: "POST",
                data: $scope.indentForm
            })
            .then(function(response) {
                $('#confirmModal').modal('hide');
                if(response.data.code == 'Success') {
                    if(x=='approve' && $scope.quot_list.length == 1) {
                        $timeout(function() { 
                            $state.go('home.new_purchase', { filter: '_usage_10__1_'+response.data.quot_number }); 
                        }, 500);
                    }
                    else {
                      $timeout(function() { 
                        $state.go('home.quotation', { filter: splitData[0]+'_'+splitData[1]+'_'+splitData[2]+'_'+splitData[3]+'_'+splitData[4] }); 
                      }, 500);
                    }
                }
                else{
                    $scope.alert = true;
                }
            });
        };
    
});