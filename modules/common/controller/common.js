



app.controller('commonHeaderCtrl', function($scope, $state) {
    $scope.common_name = localStorage.getItem("user_name");
    $scope.common_department = localStorage.getItem("user_department");
    $scope.common_designation = localStorage.getItem("user_designation");
    $scope.userProfile = localStorage.getItem("user_profile");
    
    $scope.project_perm = localStorage.getItem("project");
    $scope.indent_perm = localStorage.getItem("indent");
    $scope.quotation_perm = localStorage.getItem("quotation");
    $scope.order_perm = localStorage.getItem("order");
    $scope.vendor_perm = localStorage.getItem("vendor");
    $scope.product_perm = localStorage.getItem("product");
    $scope.inventory_perm = localStorage.getItem("inventory");
    $scope.inventory_use_perm = localStorage.getItem("inventory_use");
    $scope.userlist_perm = localStorage.getItem("userlist");
    $scope.accounts_perm = localStorage.getItem("accounts");
    $scope.billing_perm = localStorage.getItem("billing");
    
    $scope.onProfile = function () {
        localStorage.setItem("emp_id", localStorage.getItem("user_id"));
        $state.go('home.user_view', {filter: ''});
    };
    
    $scope.class = "slide-out";
    $scope.changeClass = function() {
        if($scope.class === "slide-out")
            $scope.class = "slide-in";
        else
          $scope.class = "slide-out";
    };
    
});


app.directive('myDirective', ['$window', function ($window) {
     return {
        link: link,
        restrict: 'A'           
     };
     function link(scope, element, attrs){
        scope.width = $window.innerWidth;
        
            function onResize(){
                console.log($window.innerWidth);
                // uncomment for only fire when $window.innerWidth change   
                if (scope.width !== $window.innerWidth)
                {
                    scope.width = $window.innerWidth;
                    scope.$digest();
                }
            };

            function cleanUp() {
                angular.element($window).off('resize', onResize);
            }

            angular.element($window).on('resize', onResize);
            scope.$on('$destroy', cleanUp);
     }    
 }]);