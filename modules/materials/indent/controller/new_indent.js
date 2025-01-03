app.controller('newIndentCtrl', function($scope, $http, dateFilter, $state, $stateParams, $timeout, orderService) {
    $scope.isLoading = false;
    $scope.isLoading2 = false;
    $scope.isLoading3 = false;
    var jsonData = jQuery.parseJSON(localStorage.getItem("indent"));
    $scope.mailPermission = jsonData['mail'];
    if(jsonData['add'] != 1){
        $state.go('home.dashboard');
    }
    else
    {
        //page back
        $scope.onBack = function(){       
            $scope.isLoading3 = true;    
            // $state.go('home.indent', $stateParams); 
            $timeout(function() {
                $state.go('home.indent', $stateParams);
                $scope.isLoading3 = false;
            }, 100);
        };
        
        //po date
        $scope.date = new Date();
        $scope.$watch('date', function(date) {
            $scope.indentForm = {};
            $scope.indentForm.prf_date = dateFilter(date, 'dd-MM-yyyy');
        });
        
        //get project list
        $('#preloader').show();
        $http({
            url: './v1/getindentdetails',
            method: "POST",
            data: { 'emp_id': localStorage.getItem("user_id") }
        })
        .then(function (response){
            $scope.projects = response.data.project_list;
            $scope.vendor_list = response.data.vendor_list;
            $scope.category_list = response.data.category_list;
            $scope.product_list = response.data.product_list;
            $scope.indentForm.type = 'Standard';
            $scope.names = [{ 'available_stock': "0" }];
            $scope.items = [{ 'company_name': "" }];
            $timeout(function() { $('#preloader').fadeOut("slow"); }, 200);
        });
        
        //change type
        $scope.typeChange = function(type){
            $http({
                url: './v1/getproductlist',
                method: "POST",
                data: {'type': type, 'category': 'none'}
            })
            .then(function (response){
                $scope.product_list = response.data.product_list;
            });
            $scope.names = [{ 'available_stock': "0" }];
        };
        
        //change project
        $scope.changeProjectName = function(x){
            if(!x) {
                $scope.indentForm.prf_number = '';
                $scope.indentForm.sis_project_block = '';
            }
            else {
                var len = $scope.projects.length;
                for(var i=0; i<len; i++)
                {
                    if(x == $scope.projects[i].project_name) {
                        $http({
                            url: './v1/getprfnumber',
                            method: "POST",
                            data: {'project_short': $scope.projects[i].project_short}
                        })
                        .then(function (response){
                            $scope.indentForm.prf_number = response.data.prf_number;
                        });
                        break;
                    }
                }
            }
            $scope.names = [{ 'available_stock': "0" }];
        };
        
        //change block
        $scope.changeBlockName = function(){ $scope.names = [{ 'available_stock': "0" }]; };
        
        //row calculation
        $scope.rowcalc = function(x, index) {
            if(!x.quantity){ x.quantity = ''; }
            if(!x.price){ x.price = ''; }
            $scope.names[index].total = (x.quantity * x.price).toFixed(2);
            $scope.calculateSum();
        }
       
        //door calculation
        $scope.doorcalc = function(x, index){
            if(x.size != null) {
                var total = orderService.doorcalculation(x.size);
                if(x.quantity==null) {
                    quantity = 1;  
                }
                else {
                    quantity = x.quantity;
                }
                $scope.names[index].cft = (total * quantity).toFixed(3);
            }
            else {
                $scope.names[index].cft = '';
            }
            if(!x.quantity){ x.quantity = ''; }
            if(!x.price){ x.price = ''; }
            if(!x.gst){ x.gst = ''; }
            $scope.names[index].amount = (x.cft * x.price).toFixed(2);
            $scope.names[index].gst_amount = ((x.gst)/100 * x.amount).toFixed(2);
            $scope.names[index].total = ((x.amount * 1) + (x.gst_amount * 1)).toFixed(2);
            $scope.calculateSum();
        };
        
        //grand total calculation
        $scope.calculateSum = function(){
            var sum = 0;
            angular.forEach($scope.names,function(tableForm){
                sum += tableForm.total * 1;
            });
            if($scope.names[0].total) { $scope.indentForm.grand_total = (sum).toFixed(2); }
            else{ $scope.indentForm.grand_total = ''; }
        };
        
        //add new row
        $scope.addNew = function(){
            $scope.names.push({ 'available_stock': "0" });
        };
        $scope.addSupplierNew = function(){
            $scope.items.push({ 'company_name': "" });
        };
        
        //remove existing row
        $scope.remove = function(){
            var newDataList=[];
            $scope.selectedAll = false;
            angular.forEach($scope.names, function(selected){
                if(!selected.singlecheck){
                    newDataList.push(selected);
                }
            });
            $scope.names = newDataList;
            if(newDataList==''){
                $scope.names = [{ 'available_stock': "0" }];
            }
            $scope.checkedAll = '';
            $scope.calculateSum();
        };
        $scope.removeSupplier = function(){
            var newDataList=[];
            $scope.selectedAll = false;
            angular.forEach($scope.items, function(selected){
                if(!selected.singleSupplierCheck){
                    newDataList.push(selected);
                }
            });
            $scope.items = newDataList;
            if(newDataList==''){
                $scope.items = [{ 'company_name': "" }];
            }
            $scope.$parent.checkedSupplierAll = '';
        };
        
        //check all
        $scope.selectAll = function () {
            $scope.names.forEach(function (v) {
                v.singlecheck = $scope.checkedAll;
            });
        };
        $scope.selectSupplierAll = function () {
            $scope.items.forEach(function (v) {
                v.singleSupplierCheck = $scope.checkedSupplierAll;
            });
        };
        
		$scope.querySearch = function(query) {
		$scope.results = query ?  $scope.maildatas.filter($scope.createFilterFor(query)) : [];
		console.log("Result",$scope.results);
		return $scope.results;
		}

		$scope.createFilterFor = function(query) {
		var lowercaseQuery = angular.lowercase(query);
		return $scope.filterFn = function(mail_id) {
		return (mail_id._lowername.indexOf(lowercaseQuery) === 0);
		};
		}     
		
		//Mail Content
		
			$scope.mailContent = function(){
            $scope.isLoading = true;			
			$scope.readonly = false;
			$scope.selectedItem = null;
			$scope.searchText = null;
			$scope.querySearch= $scope.querySearch; 
			$scope.to_mail = [];
			$scope.cc_mail = [];
			$scope.bcc_mail = [];
			$scope.to_mail.push({mail_id:'vinodhini@sis.in'});
			$scope.cc_mail.push({mail_id:'ranjith@sis.in'});
			$scope.mailForm.bcc_mail = '';
			$('#mailModal').modal('show');
			$http({
			url: './v1/getindentmailcontent',
			method: "POST",
			data: { 'emp_id': localStorage.getItem("user_id"), 'prf_number': $scope.indentForm.prf_number, 'purpose': $scope.indentForm.purpose, 'project_name': $scope.indentForm.sis_project_name, 'block_name': $scope.indentForm.sis_project_block }
			})
			.then(function (response){
			$scope.mailForm.from_mail = response.data.mail_id;
			$scope.mailForm.subject = response.data.subject;
			document.getElementById('editor').innerHTML = response.data.content;
			$scope.mail_list = response.data.user_mail;
			$scope.loadMailids(response.data.user_mail)
			$scope.maildatas = $scope.loadMailids(response.data.user_mail)
			})

            .finally(function() {
                $scope.isLoading = false;
            });

			};
        

		
		//Load Mail IDs
			$scope.loadMailids = function(data) {
			$scope.mailids = data;
			console.log(data)
			return $scope.mailids.map(function (mailid) {
			mailid._lowername = mailid.mail_id.toLowerCase();
			//console.log("1", mailid)
			return mailid;
			});

			}

		
		
		
        //save
        $scope.onSubmit = function(){
            $scope.isLoading2 = true;
            $scope.indentForm.jsonArrayData = JSON.stringify($scope.names);
            $scope.indentForm.jsonSupplierData = JSON.stringify($scope.items);
            $scope.indentForm.emp_id = localStorage.getItem("user_id");
            
            $http({
                url: './v1/newindent',
                method: "POST",
                data: $scope.indentForm
            })
            .then(function(response) {
                if(response.data.code == 'Success'){
                    $state.go('home.indent', $stateParams);
                }
                else{
                    $scope.alert = true;
                }
            })

            .finally(function() {
                $scope.isLoading2 = false;
            });
        };
        
        //send
        $scope.onSend = function(){
            $scope.indentForm.jsonArrayData = JSON.stringify($scope.names);
            $scope.indentForm.jsonSupplierData = JSON.stringify($scope.items);
            $scope.indentForm.emp_id = localStorage.getItem("user_id");
			
			
			var to_id = $scope.to_mail;
			var cc_id = $scope.cc_mail;
			var bcc_id = $scope.bcc_mail;
			if($scope.to_mail)
				{
				for(i=0 ; i<to_id.length ; i++)
				{					
					if(i === 0)
						{
							$scope.mailForm.to_mail= to_id[i].mail_id
						}
					else
					{
						$scope.mailForm.to_mail += ","+to_id[i].mail_id
					}
					console.log("toi", to_id[i].mail_id );
					
				}	
				}
			
			if($scope.cc_mail)
				{
				for(i=0 ; i<cc_id.length ; i++)
				{					
					if(i === 0)
						{
							$scope.mailForm.cc_mail= cc_id[i].mail_id
						}
					else
					{
						$scope.mailForm.cc_mail += ","+cc_id[i].mail_id
					}
					console.log("cci", cc_id[i].mail_id );
					
				}	
				}
				
				if($scope.bcc_mail)
				{
				for(i=0 ; i<bcc_id.length ; i++)
				{					
					if(i === 0)
						{
							$scope.mailForm.bcc_mail= bcc_id[i].mail_id
						}
					else
					{
						$scope.mailForm.bcc_mail += ","+bcc_id[i].mail_id
					}
					console.log("bcci", bcc_id[i].mail_id );
					
				}	
				}
			
            
            $scope.indentForm.from_mail = $scope.mailForm.from_mail;
            $scope.indentForm.to_mail = $scope.mailForm.to_mail;
            $scope.indentForm.cc_mail = $scope.mailForm.cc_mail;
            $scope.indentForm.bcc_mail = $scope.mailForm.bcc_mail;
            $scope.indentForm.subject = $scope.mailForm.subject;
            $scope.indentForm.content = $("#editor").html();
            
			
			
			
            $('#sendLoader').addClass('fa fa-circle-o-notch fa-spin');
            $('#send, #cancel').prop('disabled', true);
            $http({
                url: './v1/newindent',
                method: "POST",
                data: $scope.indentForm
            })
            .then(function(response) {
                $('#mailModal').modal('hide');
                if(response.data.code == 'Success'){
                    $timeout(function() { $state.go('home.indent', $stateParams); }, 500);
                }
                else{
                    $scope.alert = true;
                }
                $('#send, #cancel').prop('disabled', false);
                $('#sendLoader').removeClass('fa fa-circle-o-notch fa-spin');
            });
        };
    }
});