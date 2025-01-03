
app.controller('userPermissionCtrl', function ($scope, $http, $window, $timeout, $state, $stateParams) {
    
    $scope.onBack = function(){
        $state.go('home.userlist', $stateParams);
    }
    
    $http({
        url: './v1/permissiondetails',
        method: "POST",
        data: { 'emp_id': localStorage.getItem("emp_id") }
    }).then(function (response) {
        $scope.project_list = response.data.project_list;
        $scope.myForm = response.data.permission_data;
        $scope.username = response.data.name;
        $scope.userdprt = response.data.department;
    });
    
    //check all
    $scope.selectAll = function () {
        $scope.project_list.forEach(function (v) {
            v.singlecheck = $scope.myForm.checkedAll;
        });
    };
    
    $scope.onSubmit = function () {
        $scope.tableItems = [];
        angular.forEach($scope.project_list,function(tableForm){
            if(tableForm.singlecheck) {
               $scope.tableItems.push({ project_name: tableForm.project_name, project_short: tableForm.project_short });
            }
        });
        $scope.myForm.jsonArrayData = JSON.stringify($scope.tableItems);
        $scope.myForm.emp_id = localStorage.getItem("emp_id");
            
        $http({
            url: './v1/permissionupdate',
            method: "POST",
            data: $scope.myForm
        })
        .then(function(response) {
            if(response.data.code == 'Success') {
                $state.go('home.userlist', $stateParams);
            }
            else {
                $scope.alert = true;
            }
        });
    };
});