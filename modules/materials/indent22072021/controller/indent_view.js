app.controller('indentViewCtrl', function($scope, $http, $stateParams, $state, $timeout) {
        
    var prfNum = localStorage.getItem("prf_number");
	var index = localStorage.getItem("index");
	
	var prfNos = JSON.parse(localStorage.getItem("prfnos"));
	console.log(prfNos)
	length = localStorage.setItem("length",prfNos.length);
	
	for(let i=0; i<prfNos.length; i++)
	{
		if(prfNum===prfNos[i].prf_number)
		{
			localStorage.setItem("prf_index", i);
			console.log("index", i);
		}
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

        $('#preloader').show();
	
        $http({
            url: './v1/viewpurchaseindent',
            method: "POST",
            data: {'prf_number': prfNum}
        })
        .then(function (response){
            $scope.indentForm = response.data.indent_data;
            $scope.names = response.data.product_list;
            $scope.items = response.data.supplier_list;
            $scope.purchase_list = response.data.purchase_list;
            $timeout(function() { $('#preloader').fadeOut("slow"); }, 200);
        });
	
	
		$scope.tableData = function(prfNum){
			$('#preloader').show();
			$http({
				url: './v1/viewpurchaseindent',
				method: "POST",
				data: {'prf_number': prfNum}
			})
			.then(function (response){
				$scope.indentForm = response.data.indent_data;
				$scope.names = response.data.product_list;
				$scope.items = response.data.supplier_list;
				$scope.purchase_list = response.data.purchase_list;
				$timeout(function() { $('#preloader').fadeOut("slow"); }, 200);
			});
		};
	
	
	
	
	
	
	
	
	
	//page back
        $scope.onnxtbtn = function(){
		let prf_index = parseInt(localStorage.getItem("prf_index")) + 1;
		  
				let length = localStorage.getItem("length")-1;
			
			 $scope.IsPreDisabled = false;	
			if(prf_index < length)
				{
				  prf_index = parseInt(localStorage.getItem("prf_index")) + 1;
					$scope.IsDisabled = false;	
					console.log("prf_index",prf_index)
					$scope.tableData(prfNos[prf_index].prf_number);
				 
				}
				else if(prf_index == length)
				{
				  prf_index = parseInt(localStorage.getItem("prf_index")) + 1;
					$scope.IsDisabled = true;	
					console.log("prf_index",prf_index)
					$scope.tableData(prfNos[prf_index].prf_number);
				 
				}
			else
				{
				  $scope.IsDisabled = true;	
				   console.log("Last Button");
					let prf_index = parseInt(localStorage.getItem("prf_index"));
			    }
				
				localStorage.setItem("prf_index", prf_index);
        }
		
          $scope.onprebtn = function(){
			    $scope.IsDisabled = false;
          		prf_index = parseInt(localStorage.getItem("prf_index")) - 1;	
			    $scope.IsDisabled = false;	
			    let length = localStorage.getItem("length")-1;
			  
			  if(prf_index > 0)
				{
				    prf_index = parseInt(localStorage.getItem("prf_index")) - 1;
					$scope.IsPreDisabled = false;	
					console.log("prf_index",prf_index)
					$scope.tableData(prfNos[prf_index].prf_number);				 
				}
			  else if(prf_index == 0)
				{
				    prf_index = parseInt(localStorage.getItem("prf_index")) - 1;
					$scope.IsPreDisabled = true;	
					console.log("prf_index",prf_index)
					$scope.tableData(prfNos[prf_index].prf_number);				 
				}
			else
				{
				  $scope.IsPreDisabled = true;	
				  console.log("Last Button");
				  prf_index = parseInt(localStorage.getItem("prf_index"));
			    }
			  localStorage.setItem("prf_index", prf_index);		  
			
        }	
	
	
        //page back
        $scope.onBack = function(){
            $state.go('home.indent', $stateParams);
        }
});