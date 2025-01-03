app.controller('billListCtrl', function($scope, $http, $timeout, $state, $stateParams, orderService) {
        
        var jsonData = jQuery.parseJSON(localStorage.getItem("billing"));
        if(jsonData){
            $scope.addPermission = jsonData['add'];
            $scope.deletePermission = jsonData['delete'];
        }
        else{
            $state.go('home.dashboard');
        }
        
        //state parameter
        if($stateParams.filter != '') {
            var splitData = $stateParams.filter.split("_");
            $scope.project_short = splitData[0];
            $scope.entryLimit = splitData[1];
            $scope.search = splitData[2];
            $scope.pageNo = splitData[3];
        }
        
        if(!$scope.project_short){ $scope.project_short = ''; }
        if(!$scope.entryLimit){ $scope.entryLimit = '10'; }
        if(!$scope.search){ $scope.search = ''; }
        if(!$scope.pageNo){ $scope.pageNo = 1; }
        
        //change table type
        $scope.tableData = function(x) {
            $('#preloader').show();
            $http({
                url: './v1/getbillinglist',
                method: "POST",
                data: { "emp_id": localStorage.getItem("user_id"), 'project_short': x }
            })
            .then(function (response){
                console.log(response)
                $scope.projects = response.data.site_list;
                $scope.list = response.data.bill_list;
                $scope.filteredItems = $scope.list.length;
                $scope.setPage($scope.pageNo);
                $scope.filter();
                $timeout(function() { $('#preloader').fadeOut("slow"); }, 200);
            });
        };
        
        $scope.setPage = function(pageNo) {
            $scope.currentPage = pageNo;
        };
        $scope.filter = function() {
            $timeout(function() { 
				$scope.filteredItems = $scope.filtered.length;
            }, 10);
        };
        $scope.sort_by = function(predicate) {
            $scope.predicate = predicate;
            $scope.reverse = !$scope.reverse;
        };
        
        //view
        $scope.onView = function(x) {
            localStorage.setItem("bill_id", x);
            if($scope.project_short==undefined){ $scope.project_short = ''; }
            $state.go('home.bill_view', { filter: $scope.project_short+'_'+$scope.entryLimit+'_'+$scope.search+'_'+$scope.currentPage });
        }

        //new bill
        $scope.onNewBill = function() {
            if($scope.project_short==undefined){ $scope.project_short = ''; }
            $state.go('home.new_bill', { filter: $scope.project_short+'_'+$scope.entryLimit+'_'+$scope.search+'_'+$scope.currentPage });
        }

        //delete
        $scope.onDelete = function(x){
            $('#confirmModal').modal('show');
            $scope.alert_msg = 'Are you sure to permanently delete this?';
            $scope.modal_id = x;
            $scope.confirmFn = 'delete';
        }
        $scope.delete = function(x) {
            $http({
                url: './v1/deletepurchasebill',
                method: "POST",
                data: { 'bill_id': x }
            })
            .then(function(response){
                $('#confirmModal').modal('hide');
                $scope.alert = true;
                $scope.tableData($scope.project_short);
                if(response.data.code == 'Success'){
                    $(".alert").removeClass().addClass('alert alert-success');
                    $scope.alert_content = 'Deleted Successfully !!!';
                }
                else{
                    $(".alert").removeClass().addClass('alert alert-danger');
                    $scope.alert_content = 'Failure !!!';
                }
            });
            $timeout(function() { $scope.alert = false; }, 2000);
        }
});