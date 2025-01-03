app.controller('addUserCtrl', function ($scope, $http, $window, $timeout, $state, $stateParams) {
    
    $("#step-1").show();
    $http({
        url: './v1/adduserdetails'
    }).then(function (response) {
        $scope.emp_id = response.data.emp_id;
        $scope.department = response.data.dprt_list;
        $scope.project_list = response.data.project_list;
    });
    
    $scope.insert = function () {
        var form_data = new FormData();
        var user = JSON.stringify($scope.user);
        form_data.append('addData', user);
        angular.forEach($scope.files, function (file) {
            if(file) { form_data.append('files', file); }
        });
        
        $http({
            url: './v1/adduser.php',
            method: "POST",
            data: form_data,
            transformRequest: angular.identity,
            headers: { 'Content-Type': undefined }
        }).then(function (response) {
            console.log(response);
            if(response.data=='Success') {
                $state.go('home.userlist', $stateParams);
            }
            else {
                $scope.alert = true;
            }
        });
    }
});