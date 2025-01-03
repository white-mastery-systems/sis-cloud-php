app.controller('quotationUpdateAdminCtrl', function($scope, $http, $state, $stateParams, orderService, $timeout) {
      
        localStorage.removeItem("page_redirect");
        localStorage.removeItem("page_filter");
    
        var jsonData = jQuery.parseJSON(localStorage.getItem("quotation"));
        if(!jsonData || jsonData['approve'] != 1){
            $('#restrictModal').modal('show');
        }
    
        $scope.pageRedirect = function(){
            $('#restrictModal').modal('hide');
            localStorage.clear();
            $timeout(function() { $state.go('login'); }, 500);
        }
        
        //page back
        $scope.onBack = function(){
            $state.go('home.quotation', $stateParams);
        }
        
        var quotNum = localStorage.getItem("quot_number");
        $('#preloader').show();
        $http({
            url: './v1/admineditquotation',
            method: "POST",
            data: {'quot_number': quotNum, 'emp_id': localStorage.getItem("user_id")}
        })
        .then(function (response){
console.log("update data",response)
            if(response.data.invoice_data.approved_status=='')
            {
                $scope.indentForm = response.data.invoice_data;
                $scope.indent_items = response.data.indent_list;
                $scope.names = response.data.quotation_list;
                $scope.items = response.data.supplier_list;
                $scope.mail_list = response.data.user_mail;

                $scope.mailForm.from_mail = response.data.invoice_data.from_mail;
                
                $scope.mailForm.cc_mail = response.data.invoice_data.cc_mail;
                $timeout(function() { $('#preloader').fadeOut("slow"); }, 200);
            }
            else{
                $state.go('home.quotation', $stateParams);
            }
        });
        
        //row calculation
        $scope.rowcalc = function(x, pindex, cindex) {
            if(!x.quantity){ x.quantity = ''; }
            if(!x.price){ x.price = ''; }
            if(!x.gst){ x.gst = ''; }
            $scope.names[pindex]['table_items'][cindex].amount = (x.quantity * x.price).toFixed(2);
            $scope.names[pindex]['table_items'][cindex].gst_amount = ((x.gst)/100 * x.amount).toFixed(2);
            $scope.names[pindex]['table_items'][cindex].total = ((x.amount * 1) + (x.gst_amount * 1)).toFixed(2);
            $scope.calculateSum(pindex);
        }
        
        //door calculation
        $scope.doorcalc = function(x, pindex, cindex){
            if(x.size != null)
            {
                var total = orderService.doorcalculation(x.size);
                if(x.quantity==null){
                    quantity = 1;  
                }
                else{
                    quantity = x.quantity;
                }
                $scope.names[pindex]['table_items'][cindex].cft = (total * quantity).toFixed(3);
            }
            else{
                $scope.names[pindex]['table_items'][cindex].cft = '';
            }
            if(!x.price){ x.price = ''; }
            if(!x.cft){ x.cft = ''; }
            $scope.names[pindex]['table_items'][cindex].amount = (x.cft * x.price).toFixed(2);
            $scope.names[pindex]['table_items'][cindex].gst_amount = ((x.gst)/100 * x.amount).toFixed(2);
            $scope.names[pindex]['table_items'][cindex].total = ((x.amount * 1) + (x.gst_amount * 1)).toFixed(2);
            $scope.calculateSum(pindex);
        };
        
        //grand total calculation
        $scope.calculateSum = function(x){
            var sum = 0;
            angular.forEach($scope.names[x]['table_items'],function(tableForm){
                sum += tableForm.total * 1;
            });
            $scope.names[x].grand_total = (sum).toFixed(2);
        };
        
        $scope.mailContent = function(x){
            $scope.indentForm.submit_type = x;
            $scope.mailForm.subject = 'Quotation No: '+quotNum+' was '+x;
            if(x=='approved') {
                var checkCount = 0;
                angular.forEach($scope.names, function(tableForm){
                    if(tableForm.singlecheck){
                        checkCount++;
                    }
                });
                if(checkCount == 1) {
                    document.getElementById('editor').innerHTML = 'Dear Sir/Mam,<br><br>The following attached quotation form for <b>'+$scope.indentForm.site+'</b> was approved and proceed the further process.';
                    
                    $('#mailModal').modal('show'); 
                }
                else { $('#alertModal').modal('show'); }
            }
            else {
                document.getElementById('editor').innerHTML = 'Dear Sir/Mam,<br><br>The following attached quotation form for <b>'+$scope.indentForm.site+'</b> was declined, contact me immediately.';
                
                $('#mailModal').modal('show');
            }
        };
        
        //send mail
        $scope.onSend = function()
        {
            $scope.tableItems = [];
            angular.forEach($scope.names, function(tableForm){
                if(tableForm.singlecheck){
                    $scope.tableItems.push({ table_items: tableForm.table_items, vendor_name: tableForm.vendor_name, reason: tableForm.reason, grand_total: tableForm.grand_total });
                }
            });
            $scope.indentForm.jsonArrayData = JSON.stringify($scope.tableItems);
            $scope.indentForm.emp_id = localStorage.getItem("user_id");
            
            $scope.indentForm.from_mail = $scope.mailForm.from_mail;
            $scope.indentForm.to_mail = $scope.mailForm.to_mail;
            $scope.indentForm.cc_mail = $scope.mailForm.cc_mail;
            $scope.indentForm.bcc_mail = $scope.mailForm.bcc_mail;
            $scope.indentForm.subject = $scope.mailForm.subject;
            $scope.indentForm.content = $("#editor").html();
            
            $('#sendLoader').addClass('fa fa-circle-o-notch fa-spin');
            $('#send, #cancel').prop('disabled', true);
            $http({
                url: './v1/approvequotation',
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
});