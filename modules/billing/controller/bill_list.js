app.controller('billListCtrl', function($scope, $http, $timeout, $state, $stateParams, orderService) {
        
    var jsonData = jQuery.parseJSON(localStorage.getItem("billing"));
    if(jsonData){
        $scope.addPermission = jsonData['add'];
        $scope.deletePermission = jsonData['delete'];
    }
    else{
        $state.go('home.dashboard');
    }
    
    // State parameter
    if($stateParams.filter != '') {
        var splitData = $stateParams.filter.split("_");
        $scope.project_short = splitData[0];
        $scope.entryLimit = splitData[1];
        $scope.search = splitData[2];
        $scope.pageNo = parseInt(splitData[3]) || 1;
    }
    
    if(!$scope.project_short){ $scope.project_short = ''; }
    if(!$scope.entryLimit){ $scope.entryLimit = '10'; }
    if(!$scope.search){ $scope.search = ''; }
    if(!$scope.pageNo){ $scope.pageNo = 1; }
    
    // Pagination variables
    $scope.currentPage = $scope.pageNo;
    $scope.totalRecords = 0;
    $scope.totalPages = 0;
    $scope.filteredItems = 0;
    $scope.list = [];
    $scope.projects = [];
    $scope.searchTimeout = null;
    
    // Main function to fetch paginated data
    $scope.tableData = function(project_short) {
        $('#preloader').show();
        
        var requestData = {
            emp_id: localStorage.getItem("user_id"),
            project_short: project_short || '',
            page: $scope.currentPage,
            limit: parseInt($scope.entryLimit),
            search: $scope.search
        };
        
        $http({
            url: './v1/getbillinglistPaginated',
            method: "POST",
            data: requestData
        })
        .then(function(response) {
            console.log(response);
            $scope.projects = response.data.site_list;
            $scope.list = response.data.bill_list;
            $scope.totalRecords = response.data.pagination.total_records;
            $scope.totalPages = response.data.pagination.total_pages;
            $scope.filteredItems = response.data.pagination.total_records;
            $timeout(function() { $('#preloader').fadeOut("slow"); }, 200);
        })
        .catch(function(error) {
            console.error('Error fetching data:', error);
            $scope.list = [];
            $scope.filteredItems = 0;
            $timeout(function() { $('#preloader').fadeOut("slow"); }, 200);
        });
    };
    
    // Page change handler - called by uib-pagination
    $scope.pageChanged = function() {
        $scope.tableData($scope.project_short);
    };
    
    // Entry limit change handler
    $scope.pageEntries = function() {
        $scope.currentPage = 1; // Reset to first page
        $scope.tableData($scope.project_short);
    };
    
    // Project change handler
    $scope.projectChanged = function() {
        $scope.currentPage = 1; // Reset to first page
        $scope.tableData($scope.project_short);
    };
    
    // Search with debounce (wait 500ms after user stops typing)
    $scope.filter = function() {
        if ($scope.searchTimeout) {
            $timeout.cancel($scope.searchTimeout);
        }
        $scope.searchTimeout = $timeout(function() {
            $scope.currentPage = 1; // Reset to first page on search
            $scope.tableData($scope.project_short);
        }, 500);
    };
    
    // Sort function (server-side sorting can be added later if needed)
    $scope.sort_by = function(predicate) {
        $scope.predicate = predicate;
        $scope.reverse = !$scope.reverse;
    };
    
    // View bill
    $scope.onView = function(x) {
        localStorage.setItem("bill_id", x);
        if($scope.project_short == undefined){ $scope.project_short = ''; }
        $state.go('home.bill_view', { filter: $scope.project_short + '_' + $scope.entryLimit + '_' + $scope.search + '_' + $scope.currentPage });
    };

    // New bill
    $scope.onNewBill = function() {
        if($scope.project_short == undefined){ $scope.project_short = ''; }
        $state.go('home.new_bill', { filter: $scope.project_short + '_' + $scope.entryLimit + '_' + $scope.search + '_' + $scope.currentPage });
    };

    // Delete - show confirmation modal
    $scope.onDelete = function(x) {
        $('#confirmModal').modal('show');
        $scope.alert_msg = 'Are you sure to permanently delete this?';
        $scope.modal_id = x;
        $scope.confirmFn = 'delete';
    };
    
    // Confirm delete
    $scope.delete = function(x) {
        $http({
            url: './v1/deletepurchasebill',
            method: "POST",
            data: { 'bill_id': x }
        })
        .then(function(response) {
            $('#confirmModal').modal('hide');
            $scope.alert = true;
            
            if(response.data.code == 'Success') {
                $(".alert").removeClass().addClass('alert alert-success');
                $scope.alert_content = 'Deleted Successfully !!!';
                // Reload current page data
                $scope.tableData($scope.project_short);
            }
            else {
                $(".alert").removeClass().addClass('alert alert-danger');
                $scope.alert_content = 'Failure !!!';
            }
        })
        .catch(function(error) {
            $('#confirmModal').modal('hide');
            $(".alert").removeClass().addClass('alert alert-danger');
            $scope.alert_content = 'Error deleting record!';
        });
        
        $timeout(function() { $scope.alert = false; }, 2000);
    };
    
    // Get showing text for pagination info
    $scope.getShowingText = function() {
        if ($scope.totalRecords === 0) {
            return 'No entries';
        }
        var start = (($scope.currentPage - 1) * parseInt($scope.entryLimit)) + 1;
        var end = Math.min($scope.currentPage * parseInt($scope.entryLimit), $scope.totalRecords);
        return 'Showing ' + start + ' to ' + end + ' of ' + $scope.totalRecords + ' entries';
    };
    
    // Refresh data
    $scope.refreshData = function() {
        $scope.currentPage = 1;
        $scope.search = '';
        $scope.project_short = '';
        $scope.tableData('');
    };
    
    // Initialize - load data on controller load
    $scope.tableData($scope.project_short);
    
});