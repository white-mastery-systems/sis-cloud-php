app.controller('accountsCtrl', function ($scope, $http, $window, $timeout, $state) {
    
    var jsonData = jQuery.parseJSON(localStorage.getItem("accounts"));
    if(jsonData){
        $scope.editPermission = jsonData['edit'];
    }
    else{
        $state.go('home.dashboard');
    }
    
    $scope.tableData = function() {
        $('#preloader').show();
        if(!$scope.site || $scope.site == '') { $scope.site = 'All'; }
        if(!$scope.department || $scope.department == '') { $scope.department = 'All'; }
        if(!$scope.bank || $scope.bank == '') { $scope.bank = 'All'; }
        $http({
            url: './v1/salarylist',
            method: "POST",
            data: { 'site': $scope.site, 'department': $scope.department, 'bank':$scope.bank }
        }).then(function (response) {
            $scope.list = response.data.salary_list;
            $scope.filteredItems = $scope.list.length;
            $scope.totalItems = $scope.list.length;
            $scope.filter();
            $scope.showDelBtn = false;
            $timeout(function() { $('#preloader').fadeOut("slow"); }, 200);
        });
    }
    $scope.currentPage = 1;
        
    $scope.filter = function() {
        $timeout(function() { 
            $scope.filteredItems = $scope.filtered.length;
        }, 10);
    };
    $scope.sort_by = function(predicate) {
        $scope.predicate = predicate;
        $scope.reverse = !$scope.reverse;
    };
    
    //edit
    $scope.onEdit = function (x) {
        $("#salaryEditModal").modal('show');
        $http({
            url: './v1/salarydetails',
            method: "POST",
            data: { emp_id: x }
        }).then(function (response) {
            if(response.data.salary_details.status =='yes'){ $scope.salarypf = true; }
            else{ $scope.salarypf = ''; }
            $scope.bank_list = response.data.bank_list;
            $scope.salaryedit = response.data.salary_details;
            $scope.salaryedit.ndw = response.data.salary_details.ndw;
        });
    };
    
    $scope.editcalcSalary = function () {
        $scope.salaryedit.basicpay = Math.round((($scope.salaryedit.gross * 1) * 60) / 100);
        $scope.salaryedit.hra = Math.round((($scope.salaryedit.gross * 1) * 20) / 100);
        $scope.salaryedit.conveyance = Math.round((($scope.salaryedit.gross * 1) * 10) / 100);
        $scope.salaryedit.flexibleplan = Math.round((($scope.salaryedit.gross * 1) * 10) / 100);
        if ($scope.salaryedit.gross * 1 > 15000) {
            $scope.salaryedit.pf = 1800;
        }
        else {
            $scope.salaryedit.pf = Math.round((($scope.salaryedit.gross * 1) * 12) / 100);
        }
        $scope.salaryedit.bonus = 0;
        $scope.salaryedit.total_amount = Math.round($scope.salaryedit.basicpay + $scope.salaryedit.hra + $scope.salaryedit.conveyance + $scope.salaryedit.flexibleplan + $scope.salaryedit.bonus);
        $scope.editcalcSalary2();
    }
    $scope.editcalcSalary2 = function (x) {
        if(!$scope.salaryedit.pt) { $scope.salaryedit.pt = ''; }
        if(!$scope.salaryedit.advance) { $scope.salaryedit.advance = ''; }
        if(!$scope.salaryedit.it) { $scope.salaryedit.it = ''; }
        if(!$scope.salaryedit.others) { $scope.salaryedit.others = ''; }
        if($scope.salaryedit.gross * 1 > 15000) { $scope.salaryedit.pf = 1800; }
        else { $scope.salaryedit.pf = Math.round((($scope.salaryedit.gross * 1) * 12) / 100); }
        if($scope.salarypf) {
            $scope.amount = Math.round($scope.salaryedit.pf + ($scope.salaryedit.advance * 1) + ($scope.salaryedit.pt * 1) + ($scope.salaryedit.it * 1) + ($scope.salaryedit.others * 1));
            $scope.salaryedit.totalearnedamount = Math.round($scope.salaryedit.total_amount - $scope.amount);
        }
        else {
            $scope.salaryedit.pf = 0;
            $scope.amount = Math.round(($scope.salaryedit.advance * 1) + ($scope.salaryedit.pt * 1) + ($scope.salaryedit.it * 1) + ($scope.salaryedit.others * 1));
            $scope.salaryedit.totalearnedamount = Math.round($scope.salaryedit.total_amount - $scope.amount);
        }
    }
    $scope.editsalaryInc = function () {
        if(!$scope.salaryedit.inc) { $scope.salaryedit.inc = ''; }
        $scope.percent = ($scope.salaryedit.inc * 1) / 100;
        $scope.per = $scope.salaryedit.total_amount * $scope.percent;
        $scope.salaryedit.gross = $scope.salaryedit.total_amount + ($scope.per);
        $scope.editcalcSalary();
    }
    $scope.editcalcSalary3 = function () {
        if(!$scope.salaryedit.leave) { $scope.salaryedit.leave = ''; }
        if($scope.salarylos) {
            if($scope.salaryedit.leave > $scope.salaryedit.userleave) {
                $scope.salaryedit.leaves2 = $scope.salaryedit.leave - $scope.salaryedit.userleave;  
                $scope.salaryedit.dw = Math.round(($scope.salaryedit.ndw * 1) - $scope.salaryedit.leaves2);
                var total = Math.round(($scope.salaryedit.gross * 1) / $scope.salaryedit.ndw);
                $scope.total2 = total.toFixed();
                $scope.salaryedit.los = ($scope.total2 * 1) * $scope.salaryedit.leaves2;
                $scope.salaryedit.totalearnedamount = Math.round($scope.salaryedit.total_amount - $scope.salaryedit.los);
               
            }
        }
        else {
            $scope.salaryedit.totalearnedamount = $scope.salaryedit.total_amount;
        }
    }
    
    //update
    $scope.salaryupdate = function () {
        $http({
            url: './v1/salaryupdate',
            method: "POST",
            data: $scope.salaryedit
        }).then(function (response) {
            $('#salaryEditModal').modal('hide');
            $scope.alert = true;
            if(response.data.code == 'Success') { $scope.alert_content = 'your salary details have been succesfully inserted !'; }
            else { $scope.alert_content = 'Failure !'; }
            $scope.tableData();
        });
        $timeout(function () { $scope.alert = false; }, 2000);
    }
    
    //salary history
    $scope.salaryhistory = function (x) {
        $scope.salary_data = {};
        $('#salaryhistoryModal').modal('show');
        $http({
            url: './v1/salaryhistory',
            method: "POST",
            data: { 'emp_id' : x }
        }).then(function (response) {
            $scope.salary_data = response.data.salary_data;
        });
        $scope.currentModalPage = 1;
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
    $scope.generatesalslip = function () {
        $('#salaryslipModal').modal('hide');
        window.location.href = 'modules/user/salaryslip_pdf.php?emp_id='+$scope.salaryForm.emp_id+'&director_id='+$scope.salaryForm.dirname;
    }
});