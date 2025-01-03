app.controller('billViewCtrl', function($scope, $http, $stateParams, $state, $timeout) {
        
    var jsonData = jQuery.parseJSON(localStorage.getItem("billing"));
    if(jsonData){
        $scope.approvePermission = jsonData['approve'];
    }
    else{
        $state.go('home.dashboard');
    }

    $('#preloader').show();
    $http({
        url: './v1/viewpurchasebill',
        method: "POST",
        data: { 'bill_id': localStorage.getItem("bill_id") }
    })
    .then(function (response){
        $scope.billForm = response.data.bill_details;
        $timeout(function() { $('#preloader').fadeOut("slow"); }, 200);
    });
        
    //page back
    $scope.onBack = function(){
        $state.go('home.bill_list', $stateParams);
    }

    $scope.billConfirm = function(status) {
        $http({
            url: './v1/confirmpurchasebill',
            method: "POST",
            data: { 'bill_id': localStorage.getItem("bill_id"), 'status': status, 'credit_note': $scope.billForm.credit_note }
        })
        .then(function (response){
            if(response.data.code=='Success') {
                $state.go('home.bill_list', $stateParams);
            }
        });
    }
});