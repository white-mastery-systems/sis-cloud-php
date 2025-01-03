app.controller('loginCtrl', function($scope, $http, $state) {
    $scope.onSubmit = function(){
		
		$http.get("https://ipinfo.io/json").then(function (response) 
		{
			$scope.ip = response.data.ip;
			console.log(response.data.ip);
		});
		
//		var yip2=java.net.InetAddress.getLocalHost();	
//var yip=yip2.getHostAddress();
//console.log("your machine's local network IP is "+yip);
		
        $http({
            url: './v1/userlogin',
            method: "POST",
            data: $scope.loginFormData
        })
        .then(function(response) {
			console.log(response);
            if(response.data.code=='Success')
            {
				if(response.data.mac_status)
					{
						var mac_status = 1;
					}
				else
					{
					var mac_status = 0;	
					}
				
				
				if(mac_status == 1 )
					{
						if(response.data.mac == response.data.mac_db )
						   {
				localStorage.setItem("user_id", response.data.emp_id);
                localStorage.setItem("user_name", response.data.emp_name);
                localStorage.setItem("user_department", response.data.emp_department);
                localStorage.setItem("user_designation", response.data.emp_designation);
                localStorage.setItem("user_profile", response.data.emp_profile);
				localStorage.setItem("mac", response.data.mac);
                var staffData = response.data.staff_data;
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
					}
				else
					{
				localStorage.setItem("user_id", response.data.emp_id);
                localStorage.setItem("user_name", response.data.emp_name);
                localStorage.setItem("user_department", response.data.emp_department);
                localStorage.setItem("user_designation", response.data.emp_designation);
                localStorage.setItem("user_profile", response.data.emp_profile);
				localStorage.setItem("mac", response.data.mac);
                var staffData = response.data.staff_data;
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
                
            }
            else {
                $scope.alert = true;
				console.log(response);
                $scope.alert_content = response.data.code;
            }
        });
    };
});