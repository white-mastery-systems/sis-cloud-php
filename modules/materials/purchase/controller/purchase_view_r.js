app.controller('orderViewCtrl', function($scope, $http, $stateParams, $state, $timeout) {
        
   var poNum = localStorage.getItem("po_number");        
	var po_nos = JSON.parse(localStorage.getItem("po_nos"));
	console.log("po_nos",po_nos)
	length = localStorage.setItem("length",po_nos.length);
	for(let i=0; i<po_nos.length; i++)
	{
			console.log("length",po_nos.length)
		if(poNum===po_nos[i].po_number)
		{			
			localStorage.setItem("po_index", i);
			console.log("index", i);			
		}
	}
	
    $('#preloader').show();
    $http({
        url: './v1/viewpurchaseorder',
        method: "POST",
        data: {'po_number': poNum}
    })
    .then(function (response){
        $scope.purchaseForm = response.data.invoice_data;
		console.log($scope.purchaseForm)
        $scope.names = response.data.purchase_list;
        $timeout(function() { $('#preloader').fadeOut("slow"); }, 200);
    });
        
	
	
		$scope.tableData = function(poNum){
			 $('#preloader').show();
			 $http({
        url: './v1/viewpurchaseorder',
        method: "POST",
        data: {'po_number': poNum}
    })
    .then(function (response){
        $scope.purchaseForm = response.data.invoice_data;
		console.log($scope.purchaseForm)
        $scope.names = response.data.purchase_list;
        $timeout(function() { $('#preloader').fadeOut("slow"); }, 200);
    });
		};
	
	
		 //page back
        $scope.onnxtbtn = function(){
		let po_index = parseInt(localStorage.getItem("po_index")) + 1;
		  
				let length = localStorage.getItem("length")-1;
			
			 $scope.IsPreDisabled = false;	
			if(po_index < length)
				{
				  po_index = parseInt(localStorage.getItem("po_index")) + 1;
					$scope.IsDisabled = false;	
					console.log("po_index",po_index)
					$scope.tableData(po_nos[po_index].po_number);
				 
				}
				else if(po_index == length)
				{
				  po_index = parseInt(localStorage.getItem("po_index")) + 1;
					$scope.IsDisabled = true;	
					console.log("po_index",po_index)
					$scope.tableData(po_nos[po_index].po_number);
				 
				}
			else
				{
				  $scope.IsDisabled = true;	
				   console.log("Last Button");
					let po_index = parseInt(localStorage.getItem("po_index"));
			    }
				
				localStorage.setItem("po_index", po_index);
        }
		
          $scope.onprebtn = function(){
			    $scope.IsDisabled = false;
          		po_index = parseInt(localStorage.getItem("po_index")) - 1;	
			    $scope.IsDisabled = false;	
			    let length = localStorage.getItem("length")-1;
			  
			  if(po_index > 0)
				{
				    po_index = parseInt(localStorage.getItem("po_index")) - 1;
					$scope.IsPreDisabled = false;	
					console.log("po_index",po_index)
					$scope.tableData(po_nos[po_index].po_number);				 
				}
			  else if(po_index == 0)
				{
				    po_index = parseInt(localStorage.getItem("po_index")) - 1;
					$scope.IsPreDisabled = true;	
					console.log("po_index",po_index)
					$scope.tableData(po_nos[po_index].po_number);				 
				}
			else
				{
				  $scope.IsPreDisabled = true;	
				  console.log("Last Button");
				  po_index = parseInt(localStorage.getItem("po_index"));
			    }
			  localStorage.setItem("po_index", po_index);		  
			
        }
    //page back
    $scope.onBack = function(){
        $state.go('home.orders', $stateParams);
    }
});