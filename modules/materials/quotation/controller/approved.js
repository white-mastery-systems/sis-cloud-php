app.controller('quotationApprovedCtrl', function($scope, $http, $stateParams, $state, $timeout, $window) {
        
        var url = window.location.href;
        var arr = url.split("/");
        $scope.onLogin = function(){
            window.location.href = arr[0]+"//"+arr[2]+"/"+arr[3]+"/login";
        }
        $scope.onClose = function(){
            window.close();
        }
        
         
       console.log($stateParams.filter);
       console.log("emp_id",localStorage.getItem("user_id"))
       
        $http({
            url: './v1/viewpurchasequotation',
            method: "POST",
            data: { 'quot_number': $stateParams.filter, 'emp_id' : localStorage.getItem("user_id") }
        })
        .then(function (response){
            console.log("response",response);
            $scope.alert_msg = '';
            $scope.indentForm = response.data.indent_data;
            $scope.names = response.data.product_list;
            $scope.items = response.data.supplier_list;
            $scope.quot_items = response.data.quotation_list;
            $scope.final_items = response.data.final_list;
            $scope.mail_list = response.data.user_mail;
    

            if(response.data.indent_data)
            {
                if(response.data.indent_data.approved_status=='approved') {
                    $('#pageloader').fadeOut("slow");
                    $scope.alert_msg = 'Indent was already approved by '+response.data.indent_data.admin_name;
                }
                else if(response.data.indent_data.approved_status=='declined') {
                    $('#pageloader').fadeOut("slow");
                    $scope.alert_msg = 'Indent was already declined by '+response.data.indent_data.admin_name;
                }
                else {
                    localStorage.setItem("quot_number", $stateParams.filter);
                    if(localStorage.getItem("user_id")) {
                       // console.log("userid",arr[0]+"//"+arr[2]+"/"+arr[3]+"/home/quotation_update_admin/__10__1" );
                        //$scope.redirectlink = arr[0]+"//"+arr[2]+"/"+arr[3]+"/#/home/quotation_update_admin/__10__1";
                        //console.log(arr[0]+"//"+arr[2]+"/"+arr[3]+"/home/quotation_update_admin/__10__1");
                       window.location.href = arr[0]+"//"+arr[2]+"/"+arr[3]+"/home/quotation_update_admin/__10__1";
                    }
                    else {
                        console.log("test")
                        localStorage.setItem("page_redirect", "home.quotation_update_admin");
                        localStorage.setItem("page_filter", "__10__1");
                        window.location.href = arr[0]+"//"+arr[2]+"/"+arr[3]+"/#/login";
                    }
                }
            }
            else{
                window.close();
            }
                
        });
});