app.controller('quotationViewCtrl', function($scope, $http, $stateParams, $state, $timeout) {
        
        var quotNum = localStorage.getItem("quot_number");
        
        var jsonData = jQuery.parseJSON(localStorage.getItem("quotation"));
        if(jsonData){
            $scope.approvePermission = jsonData['approve'];
        }
        
	
	
	var quot_nos = JSON.parse(localStorage.getItem("quot_nos"));
	console.log("quot_nos",quot_nos)
	length = localStorage.setItem("length",quot_nos.length);
	for(let i=0; i<quot_nos.length; i++)
	{
			console.log("length",quot_nos.length)
		if(quotNum===quot_nos[i].quot_number)
		{			
			localStorage.setItem("quot_index", i);
			console.log("index", i);			
		}
	}
	
	
        $('#preloader').show();
        $http({
            url: './v1/viewpurchasequotation',
            method: "POST",
            data: {'quot_number': quotNum, 'emp_id': localStorage.getItem("user_id")}
        })
        .then(function (response){
            console.log(response);
            $scope.indentForm = response.data.indent_data;
            $scope.names = response.data.product_list;
            $scope.items = response.data.supplier_list;
            $scope.quot_items = response.data.quotation_list;
            $scope.final_items = response.data.final_list;
            $scope.mail_list = response.data.user_mail;
            
            $scope.mailForm.from_mail = response.data.indent_data.from_mail;
            $scope.mailForm.to_mail = 'johni@sis.in';
            $scope.mailForm.cc_mail = response.data.indent_data.cc_mail;
            $timeout(function() { $('#preloader').fadeOut("slow"); }, 200);
        });
	
	
	$scope.tableData= function(quotNum){
		$('#preloader').show();
		$http({
            url: './v1/viewpurchasequotation',
            method: "POST",
            data: {'quot_number': quotNum, 'emp_id': localStorage.getItem("user_id")}
        })
        .then(function (response){
            $scope.indentForm = response.data.indent_data;
            $scope.names = response.data.product_list;
            $scope.items = response.data.supplier_list;
            $scope.quot_items = response.data.quotation_list;
            $scope.final_items = response.data.final_list;
            $scope.mail_list = response.data.user_mail;
            
            $scope.mailForm.from_mail = response.data.indent_data.from_mail;
            $scope.mailForm.to_mail = 'johni@sis.in';
            $scope.mailForm.cc_mail = response.data.indent_data.cc_mail;
            $timeout(function() { $('#preloader').fadeOut("slow"); }, 200);
        });
	}
	
	$scope.onnxtbtn = function(){
		let quot_index = parseInt(localStorage.getItem("quot_index")) + 1;
		  
				let length = localStorage.getItem("length")-1;
			
			 $scope.IsPreDisabled = false;	
			if(quot_index < length)
				{
				  quot_index = parseInt(localStorage.getItem("quot_index")) + 1;
					$scope.IsDisabled = false;	
					console.log("quot_index",quot_index)
					$scope.tableData(quot_nos[quot_index].quot_number);
				 
				}
				else if(quot_index == length)
				{
				  quot_index = parseInt(localStorage.getItem("quot_index")) + 1;
					$scope.IsDisabled = true;	
					console.log("quot_index",quot_index)
					$scope.tableData(quot_nos[quot_index].quot_number );
				 
				}
			else
				{
				  $scope.IsDisabled = true;	
				   console.log("Last Button");
					let quot_index = parseInt(localStorage.getItem("quot_index"));
			    }
				
				localStorage.setItem("quot_index", quot_index);
        }
		
          $scope.onprebtn = function(){
			    $scope.IsDisabled = false;
          		quot_index = parseInt(localStorage.getItem("quot_index")) - 1;	
			    $scope.IsDisabled = false;	
			    let length = localStorage.getItem("length")-1;
			  
			  if(quot_index > 0)
				{
				    quot_index = parseInt(localStorage.getItem("quot_index")) - 1;
					$scope.IsPreDisabled = false;	
					console.log("quot_index",quot_index)
					$scope.tableData(quot_nos[quot_index].quot_number);				 
				}
			  else if(quot_index == 0)
				{
				    quot_index = parseInt(localStorage.getItem("quot_index")) - 1;
					$scope.IsPreDisabled = true;	
					console.log("quot_index",quot_index)
					$scope.tableData(quot_nos[quot_index].quot_number);				 
				}
			else
				{
				  $scope.IsPreDisabled = true;	
				  console.log("Last Button");
				  quot_index = parseInt(localStorage.getItem("quot_index"));
			    }
			  localStorage.setItem("quot_index", quot_index);		  
			
        }
	
	
	
	
        
        //page back
        $scope.onBack = function(){
            $state.go('home.quotation', $stateParams);
        }
        
        $scope.onModal = function(x){
            $scope.mailForm.submit_type = x;
            $scope.mailForm.subject = 'Quotation No: '+quotNum+' was '+x;
            var block = '';
            if($scope.indentForm.sis_project_block != 'none'){ var block = " - "+$scope.indentForm.sis_project_block+" Block"; }
            
            if(x=='approved') {
                var checkCount = 0;
                angular.forEach($scope.quot_items, function(tableForm){
                    if(tableForm.singlecheck){
                        checkCount++;
                    }
                });
                if(checkCount == 1) {
                    document.getElementById('editor').innerHTML = 'Dear Sir/Mam,<br><br>The following attached quotation form for <b>'+$scope.indentForm.sis_project_name+block+'</b> was approved and proceed the further process.';
                    
                    $('#mailModal').modal('show'); 
                }
                else { $('#alertModal').modal('show'); }
            }
            else{
                document.getElementById('editor').innerHTML = 'Dear Sir/Mam,<br><br>The following attached quotation form for <b>'+$scope.indentForm.sis_project_name+block+'</b> was declined, contact me immediately.';
                
                $('#mailModal').modal('show');
            }
        }
        
        $scope.onSend = function(){
            $('#sendLoader').addClass('fa fa-refresh fa-spin');
            $('#send, #cancel').prop('disabled', true);
            $scope.tableItems = [];
            angular.forEach($scope.quot_items, function(tableForm){
                if(tableForm.singlecheck){
                    $scope.tableItems.push({ table_items: tableForm.table_items, vendor_name: tableForm.vendor_name, reason: tableForm.reason, grand_total: tableForm.grand_total });
                }
            });
            $scope.indentForm.jsonArrayData = JSON.stringify($scope.tableItems);
            $scope.indentForm.emp_id = localStorage.getItem("user_id");
            
            $scope.indentForm.submit_type = $scope.mailForm.submit_type;
            $scope.indentForm.from_mail = $scope.mailForm.from_mail;
            $scope.indentForm.to_mail = $scope.mailForm.to_mail;
            $scope.indentForm.cc_mail = $scope.mailForm.cc_mail;
            $scope.indentForm.bcc_mail = $scope.mailForm.bcc_mail;
            $scope.indentForm.subject = $scope.mailForm.subject;
            $scope.indentForm.content = $("#editor").html();
            
            $http({
                url: './v1/approvequotation',
                method: "POST",
                data: $scope.indentForm
            })
            .then(function (response){
                $('#mailModal').modal('hide');
                if(response.data.code=='Success'){
                    $timeout(function() { $state.go('home.quotation', $stateParams); }, 500);
                }
                else{
                    $scope.alert = true;
                    $scope.alert_msg = response.data.code;
                }
                $('#send, #cancel').prop('disabled', false);
                $('#sendLoader').removeClass('fa fa-refresh fa-spin');
            });
        }
});