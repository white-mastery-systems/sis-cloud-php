app.controller('userListCtrl', function ($scope, $http, $window, $timeout, $state, $stateParams) {
    
    var jsonData = jQuery.parseJSON(localStorage.getItem("userlist"));
    if(jsonData){
        $scope.addPermission = jsonData['add'];
        $scope.editPermission = jsonData['edit'];
        $scope.deletePermission = jsonData['delete'];
        $scope.leavePermission = jsonData['leave'];
    }
    else{
        $state.go('home.dashboard');
    }
    
    //state parameter
    if($stateParams.filter != '') {
        var splitData = $stateParams.filter.split("_");
        $scope.status = splitData[0];
        $scope.site = splitData[1];
        $scope.department = splitData[2];
        $scope.entryLimit = splitData[2];
        $scope.search = splitData[3];
        $scope.pageNo = splitData[4];
    }
        
    if(!$scope.status){ $scope.status = 'Active'; }
    if(!$scope.site){ $scope.site = 'All'; }
    if(!$scope.department){ $scope.department = 'All'; }
    if(!$scope.entryLimit){ $scope.entryLimit = '10'; }
    if(!$scope.search){ $scope.search = ''; }
    if(!$scope.pageNo){ $scope.pageNo = 1; }
    
    $scope.addUser = function () {
        $state.go('home.add_user', {
            filter: $scope.status + '_' + $scope.site + '_' + $scope.department + '_' + $scope.search + '_' + $scope.currentPage
        });
    }
    
    $http({
        url: './v1/userlistfilter'
    }).then(function (response) {
        $scope.dynamic = response.data.userfilter;
    });
    
    $scope.tableData = function () {
        $('#preloader').show();
        if(!$scope.site || $scope.site == '') { $scope.site = 'All'; }
        if(!$scope.status || $scope.status == '') { $scope.status = 'All'; }
        if(!$scope.department || $scope.department == '') { $scope.department = 'All'; }
        $http({
            url: './v1/userlist',
            method: "POST",
            data: { 'status': $scope.status, 'site': $scope.site, 'department': $scope.department }
        }).then(function (response) {
            $scope.list = response.data.user_list;
            $scope.filteredItems = $scope.list.length;
            $scope.setPage($scope.pageNo);
            $scope.filter();
            $scope.showDelBtn = false;
            $timeout(function() { $('#preloader').fadeOut("slow"); }, 200);
        });
    }
    
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
    
    //export
    $scope.export = function () {
        window.location.href = 'modules/user/userexport.php?status='+$scope.status+'&site='+$scope.site+'&department='+$scope.department;
    }
    
    $scope.onEdit = function (x) {
        localStorage.setItem("emp_id", x);
        $state.go('home.user_view', {
            filter: $scope.status+'_'+$scope.site+'_'+$scope.department+'_'+$scope.search+'_'+$scope.pageNo
        });
    };
    
    //salary history
    $scope.salaryhistory = function (x) {
        $('#salaryModal').modal('show');
        $('.nav-tabs a[data-target="#tab1"]').tab('show');
        $scope.emp_id =x;
        $http({
            url: './v1/salaryhistory',
            method: "POST",
            data: { 'emp_id' : x }
        }).then(function (response) {
            $scope.leave = {};
            $scope.leave.year = response.data.curr_fin_year;
            $scope.salary_data = response.data.salary_data;
            $scope.financial_year = response.data.financial_year;
            $scope.leave_details = response.data.leave_data;
        });
        $scope.currentModalPage = 1;
        $scope.currentLeavePage = 1;
    }
    $scope.leavechange = function () {
        $scope.leave.emp_id = $scope.emp_id;
        $http({
            url: './v1/leaveyear',
            method: "POST",
            data: $scope.leave
        }).then(function (response) {
            $scope.leave_details = response.data.leave_data;
        });
        $scope.currentLeavePage = 1;
    }
    
    //user leave
    $scope.userleave = function(x) {
        $scope.leaveForm = {};
        $scope.leaveForm.emp_id = x;
        $("#user_leave").modal('show');
    }
    $scope.leaveinsert = function () {
        $http({
            url: './v1/leaveinsert',
            method: "POST",
            data: $scope.leaveForm
        }).then(function (response) {
            $("#user_leave").modal('hide');
            $scope.alert = true;
            $scope.alert_content = "your leave status have been inserted";
        });
        $timeout(function () { $scope.alert = false; }, 2000);
    }
    
    //delete
    $scope.onDelete = function(x) {
        $('#confirmModal').modal('show');
        $scope.alert_msg = 'Are you sure to permanently delete this?';
        $scope.modal_id = x;
        $scope.confirmFn = 'delete';
    }
    $scope.delete = function(x) {
        $http({
            url: './v1/userdelete',
            method: "POST",
            data: { 'emp_id': x }
        }).then(function (response) {
            $('#confirmModal').modal('hide');
            $scope.alert = true;
            $scope.tableData();
            if(response.data.code == 'Success') { $scope.alert_content = 'you have been successfully deleted'; }
            else { $scope.alert_content = 'Failure'; }
        });
        $timeout(function () { $scope.alert = false; }, 2000);
    }
    
    $scope.deleteShow = function () {
        var count=0;
        angular.forEach($scope.filtered,function(x){
            if(x.singlecheck) { count++; }
        });
        if(count > 0){ $scope.showDelBtn = true; }
        else{ $scope.showDelBtn = false; }
    }
    
    //multi delete
    $scope.multiple_delete = function () {
        $scope.tableItems = [];
        angular.forEach($scope.filtered, function (x) {
            if(x.singlecheck) {
                $scope.tableItems.push({ emp_id: x.emp_id });
            }
        });
        var jsonArrayData = JSON.stringify($scope.tableItems);
        if (jsonArrayData != '[]') {
            $('#confirmModal').modal('show');
            $scope.modal_id = jsonArrayData;
            $scope.confirmFn = 'alldelete';
            $scope.alert_msg = 'Are you sure to permanently delete the selected users?';
        }
    }
    $scope.alldelete = function(x) {
        $http({
            url: './v1/deleteallusers',
            method: "POST",
            data: {'jsonArrayData': x}
        })
        .then(function(response){
            $('#confirmModal').modal('hide');
            $scope.alert = true;
            $scope.tableData();
            if(response.data.code == 'Success'){ $scope.alert_content = 'Deleted Successfully !'; }
            else{ $scope.alert_content = 'Failure !'; }
        });
        $timeout(function() { $scope.alert = false; }, 2000);
    }
    
    //salary slip
    $scope.salaryslip = function (x) {
        $scope.salaryForm = {};
        $scope.salaryForm.emp_id = x;
        $http({
            url: './v1/getdirectorlist'
        }).then(function (response) {
            $scope.director_list = response.data.director_list;
            $('#salaryslipModal').modal('show');
        });
    }
    $scope.sallastmonth = function () {
        $('#salaryslipModal').modal('hide');
        window.location.href = 'modules/user/salaryslip_pdf.php?emp_id='+$scope.salaryForm.emp_id+'&director_id='+$scope.salaryForm.dirname;
    }
    
    $scope.onPermission = function (x) {
        localStorage.setItem("emp_id", x);
        $state.go('home.permission', {
            filter: $scope.status+'_'+$scope.site+'_'+$scope.department+'_'+$scope.search+'_'+$scope.pageNo
        });
    };
    
    $scope.holidayCalc = function () {
         $http({
            url: './v1/holidaycalc',
            method: "POST",
            data: $scope.leaveForm
        }).then(function (response) {
            $scope.leaveForm.leave = response.data.leave;
        });
    };
});
