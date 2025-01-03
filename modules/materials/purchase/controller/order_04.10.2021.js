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
    
	
	  $scope.querySearch = function(query) {
		  console.log("3",query)
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
    $scope.readonly = false;
    $scope.selectedItem = null;
    $scope.searchText = null;
    $scope.querySearch = $scope.querySearch; 
 
	$scope.to_mail = [];
	$scope.cc_mail = [];
	$scope.bcc_mail = [];
		$scope.vendormail =[];							
            $http({
                url: './v1/getpomailcontent',
                method: "POST",
                data: {'po_number': x, 'emp_id': localStorage.getItem("user_id")}
            })
            .then(function (response){
				
                $('#mailModal').modal('show');
				
				//$scope.vendormail.mailid = response.vendor_mail;
				
                $scope.mailForm = {};
                $scope.mailForm.po_number = x;
                $scope.mailForm.order_type_short = response.data.order_type_short;
                $scope.mailForm.emp_id = localStorage.getItem("user_id");
                $scope.mailForm.from_mail = response.data.mail_id;
				console.log("vendorMail",$scope.vendormail);
				$scope.to_mail.push({mail_id:response.data.vendor_mail})
                console.log(localStorage.getItem("user_id"))
                 if(localStorage.getItem("user_id") === '1358')
                {
                    $scope.cc_mail.push({mail_id:'rajagopalramesh1960@gmail.com'},{mail_id:'venkat@sis.in'},{mail_id:'suhail@sis.in'});
                
                }
            else
                {
                  $scope.cc_mail.push({mail_id:'johni@sis.in'},{mail_id:'rajagopalramesh1960@gmail.com'},{mail_id:'venkat@sis.in'},{mail_id:'suhail@sis.in'});
                }
                //$scope.to_mail = $scope.vendormail;
               // $scope.mail_list = response.data.user_mail;
               

               var res = response.data.user_mail;
                  
               var email = [];
             
           for(i=0 ; i<res.length ; i++)
           {		
             //  console.log("mailid............", res[i].mail_id)
               var res1 = res[i].mail_id.split(",");
               for(j=0 ; j<res1.length ; j++)
               {
               var data  = {};
               data.mail_id = res1[j].trim();                    
              //console.log("toi", data.mail_id );
               email.push(data);
               }
           }	
           console.log("email data............",email);	
                $scope.mailForm.subject = response.data.subject;
                document.getElementById('editor').innerHTML = response.data.mail_data;
				console.log("response",response.data);
			    $scope.loadMailids(email);
			    $scope.maildatas = $scope.loadMailids(email);				
            });
        };
	
	
	
		
  $scope.loadMailids = function(data) {
     		$scope.mailids = data;
	 console.log(data)
      return $scope.mailids.map(function (mailid) {
        mailid._lowername = mailid.mail_id.toLowerCase();
//		  console.log("1", mailid)
        return mailid;
      });
			
    }
	
        
        //send mail
        $scope.onSend = function(){
            $scope.mailForm.content = $("#editor").html();
            $('#sendLoader').addClass('fa fa-circle-o-notch fa-spin');
            $('#send, #cancel').prop('disabled', true);
		
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
			console.log("Mailform",$scope.mailForm)
		
         
			if($scope.to_mail)
				{
				   $http({
                url: './v1/sendpomail',
                method: "POST",
                data: $scope.mailForm
            }).then(function(response) {
				console.log("res",response)
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
				}
			else
				{
				$(".alert").removeClass().addClass('alert alert-danger');
				$scope.alert_content = "Kindy Fill the To Email ID";
				$('#send, #cancel').prop('disabled', false);
                $('#sendLoader').removeClass('fa fa-circle-o-notch fa-spin');
                $timeout(function() { $scope.alert = false; }, 2000);
				}
           
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