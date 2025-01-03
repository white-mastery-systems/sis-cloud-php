app.controller('loginCtrl', function($scope, $http, $state) {
    console.log("login...................")
    $scope.onSubmit = function(){
        console.log("login Data........",$scope.loginFormData)
        $http({
            url: './v1/userlogin',
            method: "POST",
            data: $scope.loginFormData
        })
        .then(function(response) {
            console.log("login response........",response)
            if(response.data.code=='Success')
            {
                localStorage.setItem("user_id", response.data.emp_id);
                localStorage.setItem("user_name", response.data.emp_name);
                localStorage.setItem("user_department", response.data.emp_department);
                localStorage.setItem("user_designation", response.data.emp_designation);
                localStorage.setItem("user_profile", response.data.emp_profile);
                var staffData = response.data.staff_data;
               // console.log("ip",response.data.ip);
                for(i=0; i<staffData.length; i++)
                {
                    localStorage.setItem(staffData[i]['permission_type'], staffData[i]['access']);
                }
                
                if(localStorage.getItem("page_redirect")){
                    $state.go(localStorage.getItem("page_redirect"), { filter: localStorage.getItem("page_filter") });
                }
                else{
                    $state.go('home.dashboard');
                }
            }
            else {
                $scope.alert = true;
                $scope.alert_content = response.data.code;
            }
        });
    };
});