app.controller('plottype_addCtrl', function($scope, $http, $window, $timeout, $state, $stateParams) {
    $scope.emp_id = localStorage.getItem("user_id");
    $scope.emp_name= localStorage.getItem("user_name");
    $scope.projectname = $stateParams.filter;
    
     $scope.type_insert = function()
     {
        $http({
            url: './v1/plot_typeadd',
            method: "POST",
            data:{'projectname':$scope.projectname,
            emp_id:$scope.emp_id,emp_name:$scope.emp_name,'type':JSON.stringify($scope.type)}
            }).then(function (response){
                $scope.exist_data =response.data.exist;
              if($scope.exist_data!=undefined)
                  {
                      $scope.alert2 = true;
                      $scope.alert_content = "This type already exist!";
                  }
              else
                  {
                      $state.go('home.plot_type',$stateParams); 
                  }
           });
          $timeout(function () { $scope.alert2 = false; }, 2000);
     }
    $scope.onBack = function()
     {
         $state.go('home.plot_type',$stateParams); 
     }

    $scope.faceing_list = function()
    {
        $http({
            url: './v1/get_direction',
            method: "GET"
        }).then(function (response){
            $scope.faceing_list = response.data.direction_list;
        });
    }
 
 });