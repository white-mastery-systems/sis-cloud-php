app.controller('categoryCtrl', function($scope, $http, $stateParams, $state, $timeout) {
        
    $('#preloader').show();
    $http({
        url: './v1/internalcategorylist'
    })
    .then(function (response){
        $scope.category_list = response.data;
        $timeout(function() { $('#preloader').fadeOut("slow"); }, 200);
    });
    
    $scope.pageredirect = function(x) {
        $state.go('home.'+$stateParams.filter, { filter: x });
    };
});