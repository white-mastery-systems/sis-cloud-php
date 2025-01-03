app.controller('quotationUpdateCtrl', function($scope, $http, $state, $stateParams, orderService, $timeout) {
        
    var jsonData = jQuery.parseJSON(localStorage.getItem("quotation"));
    $scope.approvePermission = jsonData['approve'];
    $scope.mailPermission = jsonData['mail'];
    if(jsonData['edit'] != 1){
        $state.go('home.dashboard');
    }
    else
    {
        //page back
        $scope.onBack = function(){
            $state.go('home.quotation', $stateParams);
        }
        
        var quotNum = localStorage.getItem("quot_number");
        $('#preloader').show();
        $http({
            url: './v1/editpurchasequotation',
            method: "POST",
            data: {'quot_number': quotNum, 'emp_id': localStorage.getItem("user_id")}
        })
        .then(function (response){
            $scope.vendor_list = response.data.vendor_list;
            $scope.product_list = response.data.product_list;
            $scope.indentForm = response.data.invoice_data;
            $scope.quot_list = response.data.quot_list;
            $timeout(function() { $('#preloader').fadeOut("slow"); }, 200);
        });
        
        //row calculation
        $scope.rowcalc = function(x, vendorIndex, tableIndex) {
            if(!x.quantity){ x.quantity = ''; }
            if(!x.price){ x.price = ''; }
            if(!x.gst){ x.gst = ''; }
            $scope.quot_list[vendorIndex]['table_items'][tableIndex].amount = (x.quantity * x.price).toFixed(2);
            $scope.quot_list[vendorIndex]['table_items'][tableIndex].gst_amount = ((x.gst)/100 * x.amount).toFixed(2);
            $scope.quot_list[vendorIndex]['table_items'][tableIndex].total = ((x.amount * 1) + (x.gst_amount * 1)).toFixed(2);
            $scope.calculateSum(vendorIndex);
        }
        
        //door calculation
        $scope.doorcalc = function(x, vendorIndex, tableIndex){			
            if(x.size != null)
            {
                var total = orderService.doorcalculation(x.size);
				
                if(x.quantity==null){
                    quantity = 1;  
                }
                else{
                    quantity = x.quantity;
                }
               x.cft = (total * quantity).toFixed(3);
            }
            else{
                x.cft = '';
            }
            if(!x.quantity){ x.quantity = ''; }
            if(!x.price){ x.price = ''; }
            if(!x.gst){ x.gst = ''; }
			if(!x.cft){ x.cft = ''; }
            x.amount = (x.cft * x.price).toFixed(2);
            x.gst_amount = ((x.gst)/100 * x.amount).toFixed(2);
            x.total = ((x.amount * 1) + (x.gst_amount * 1)).toFixed(2);
            $scope.calculateSum(vendorIndex);
        };
        
        //grand total calculation
        $scope.calculateSum = function(vendorIndex){
            var sum = 0;
            angular.forEach($scope.quot_list[vendorIndex]['table_items'],function(tableForm){
                sum += tableForm.total * 1;
            });
            $scope.quot_list[vendorIndex].grand_total = (sum).toFixed(2);
        };
        
        //add
        $scope.addSuggestion = function(){
            $scope.tableItems = [];
            for(var i=0; i<$scope.quot_list[0]['table_items'].length; i++)
            {
                $scope.tableItems.push({
                    "code" : $scope.quot_list[0]['table_items'][i]['code'],
                    "item" : $scope.quot_list[0]['table_items'][i]['item'],
                    "quantity" : $scope.quot_list[0]['table_items'][i]['quantity'],
                    "unit" : $scope.quot_list[0]['table_items'][i]['unit'],
                    "price" : $scope.quot_list[0]['table_items'][i]['price'],
                    "amount" : $scope.quot_list[0]['table_items'][i]['amount'],
                    "gst" : $scope.quot_list[0]['table_items'][i]['gst'],
                    "gst_amount" : $scope.quot_list[0]['table_items'][i]['gst_amount'],
                    "total" : $scope.quot_list[0]['table_items'][i]['total'],
                    "make" : $scope.quot_list[0]['table_items'][i]['make'],
                    "details" : $scope.quot_list[0]['table_items'][i]['details'],
                    "width" : $scope.quot_list[0]['table_items'][i]['width'],
                    "height" : $scope.quot_list[0]['table_items'][i]['height'],
                    "upvc_type" : $scope.quot_list[0]['table_items'][i]['upvc_type'],
                    "size" : $scope.quot_list[0]['table_items'][i]['size'],
                    "cft" : $scope.quot_list[0]['table_items'][i]['cft'],
                    "required_date" : $scope.quot_list[0]['table_items'][i]['required_date'],
                    "stock_details" : $scope.quot_list[0]['table_items'][i]['stock_details'],
                    "available_stock" : $scope.quot_list[0]['table_items'][i]['available_stock']
                });  
            }
            $scope.quot_list.push({
                "vendor_name" : '',
                "reason" : '',
                "grand_total" : $scope.quot_list[0]['grand_total'],
                "table_items" : $scope.tableItems
            });
        };
        $scope.addRow = function(x) {
            x.push({"available_stock" : '0'});
        };
        
        //remove
        $scope.removeSuggestion = function(){
            var newDataList=[];
            $scope.selectedAll = false;
            angular.forEach($scope.quot_list, function(selected){
                if(!selected.tablecheck){
                    newDataList.push(selected);
                }
            });
            if(newDataList==''){
                $scope.tableItems = [];
                for(var i=0; i<$scope.quot_list[0]['table_items'].length; i++)
                {
                    $scope.tableItems.push({
                        "code" : $scope.quot_list[0]['table_items'][i]['code'],
                        "item" : $scope.quot_list[0]['table_items'][i]['item'],
                        "quantity" : $scope.quot_list[0]['table_items'][i]['quantity'],
                        "unit" : $scope.quot_list[0]['table_items'][i]['unit'],
                        "price" : $scope.quot_list[0]['table_items'][i]['price'],
                        "amount" : $scope.quot_list[0]['table_items'][i]['amount'],
                        "gst" : $scope.quot_list[0]['table_items'][i]['gst'],
                        "gst_amount" : $scope.quot_list[0]['table_items'][i]['gst_amount'],
                        "total" : $scope.quot_list[0]['table_items'][i]['total'],
                        "make" : $scope.quot_list[0]['table_items'][i]['make'],
                        "details" : $scope.quot_list[0]['table_items'][i]['details'],
                        "width" : $scope.quot_list[0]['table_items'][i]['width'],
                        "height" : $scope.quot_list[0]['table_items'][i]['height'],
                        "upvc_type" : $scope.quot_list[0]['table_items'][i]['upvc_type'],
                        "size" : $scope.quot_list[0]['table_items'][i]['size'],
                        "cft" : $scope.quot_list[0]['table_items'][i]['cft'],
                        "required_date" : $scope.quot_list[0]['table_items'][i]['required_date'],
                        "stock_details" : $scope.quot_list[0]['table_items'][i]['stock_details'],
                        "available_stock" : $scope.quot_list[0]['table_items'][i]['available_stock']
                    });  
                }
                $scope.quot_list = [{
                    "vendor_name" : '',
                    "reason" : '',
                    "grand_total" : $scope.quot_list[0]['grand_total'],
                    "table_items" : $scope.tableItems
                }];
            }
            else { $scope.quot_list = newDataList; }
        };
        $scope.removeRow = function(x, vendorIndex){
            var newDataList=[];
            angular.forEach(x, function(selected){
                if(!selected.rowCheck){
                    newDataList.push(selected);
                }
            });
            if(newDataList==''){
                $scope.quot_list[vendorIndex]['table_items'] = [{
                    "table_items" : [{ "available_stock" : '0' }]
                }];
            }
            else { $scope.quot_list[vendorIndex]['table_items'] = newDataList; }
            $scope.calculateSum(vendorIndex);
        };
        
        $scope.mailContent = function() {
            $http({
                url: './v1/getquotationmailcontent',
                method: "POST",
                data: {'quot_number': quotNum, 'emp_id': localStorage.getItem("user_id")}
            })
            .then(function (response){
                $('#mailModal').modal('show');
                $scope.mailForm = {};
                $scope.mailForm.from_mail = response.data.mail_id;
                $scope.mailForm.to_mail = "suhail@sis.in";
                $scope.mail_list = response.data.user_mail;
                $scope.mailForm.subject = response.data.subject;
                document.getElementById('editor').innerHTML = response.data.content;
                $('#mailModal').modal('show');
            });
        };
        
        //update, update and send for approval
        $scope.onUpdate = function(x){
            $scope.tableItems = [];
            angular.forEach($scope.quot_list,function(tableForm){
                $scope.tableItems.push({ table_items: tableForm.table_items, vendor_name: tableForm.vendor_name, reason: tableForm.reason, grand_total: tableForm.grand_total });
            });
            $scope.indentForm.jsonArrayData = JSON.stringify($scope.tableItems);
            $scope.indentForm.emp_id = localStorage.getItem("user_id");
            
            if(x=='mail') {
                $scope.indentForm.from_mail = $scope.mailForm.from_mail;
                $scope.indentForm.to_mail = $scope.mailForm.to_mail;
                $scope.indentForm.cc_mail = $scope.mailForm.cc_mail;
                $scope.indentForm.bcc_mail = $scope.mailForm.bcc_mail;
                $scope.indentForm.subject = $scope.mailForm.subject;
                $scope.indentForm.content = $("#editor").html();
            }
            
            $('#sendLoader').addClass('fa fa-circle-o-notch fa-spin');
            $('#send, #cancel').prop('disabled', true);
            $http({
                url: './v1/updatepurchasequotation',
                method: "POST",
                data: $scope.indentForm
            })
            .then(function(response) {
                $('#mailModal').modal('hide');
                if(response.data.code == 'Success'){
                    $timeout(function() { $state.go('home.quotation', $stateParams); }, 500);
                }
                else{
                    $scope.alert = true;
                }
                $('#send, #cancel').prop('disabled', false);
                $('#sendLoader').removeClass('fa fa-circle-o-notch fa-spin');
            });
        };
        
        $scope.selfApprove = function() {
            var checkCount = 0;
            angular.forEach($scope.quot_list, function(tableForm){
                if(tableForm.tablecheck){
                    checkCount++;
                }
            });
            if(checkCount == 1) { $('#confirmModal').modal('show'); }
            else { $('#alertModal').modal('show'); }
        };
        
        //update & self approve
        $scope.onApprove = function(){
            $scope.tableItems = [];
            angular.forEach($scope.quot_list,function(tableForm){
                if(tableForm.tablecheck) {
                    $scope.tableItems.push({ table_items: tableForm.table_items, vendor_name: tableForm.vendor_name, reason: tableForm.reason, grand_total: tableForm.grand_total });
                }
            });
            $scope.indentForm.jsonArrayData = JSON.stringify($scope.tableItems);
            $scope.indentForm.emp_id = localStorage.getItem("user_id");
            
            $http({
                url: './v1/updateandapprovequotation',
                method: "POST",
                data: $scope.indentForm
            })
            .then(function(response) {
                $('#confirmModal').modal('hide');
                if(response.data.code == 'Success') {
                    $timeout(function() { $state.go('home.new_purchase', { filter: '_usage_10__1_'+$scope.indentForm.quot_number }); }, 500);
                }
                else {
                    $scope.alert = true;
                }
            });
        };
    }
});