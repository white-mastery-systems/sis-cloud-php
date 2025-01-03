app.controller('indentMailCtrl', function($scope, $http, $stateParams, $state, $timeout, $window) {
    
    var url = window.location.href;
    var arr = url.split("/");
    
    localStorage.setItem("quot_number", $stateParams.filter);
    if(localStorage.getItem("user_id")) {
        window.location.href = arr[0]+"//"+arr[2]+"/"+arr[3]+"/home/new_quotation/__10__1_"+$stateParams.filter;
    }
    else {
        localStorage.setItem("page_redirect", "home.new_quotation");
        localStorage.setItem("page_filter", "__10__1_"+$stateParams.filter);
        window.location.href = arr[0]+"//"+arr[2]+"/"+arr[3]+"/login";
    }
});