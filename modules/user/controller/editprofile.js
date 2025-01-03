
app.controller('userEditCtrl', function ($scope, $http, $window, $timeout, $state, $stateParams, Lightbox) {
    $scope.currentTab = 'Tab1';
    
    var jsonData = jQuery.parseJSON(localStorage.getItem("userlist"));
    if(jsonData){ $scope.editPermission = jsonData['edit']; }
    else{ $scope.editPermission = false; }
    
    //page back
    $scope.onBack = function(){
        if(jsonData) { $state.go('home.userlist', $stateParams); }
        else { $state.go('home.dashboard'); }
    }
    
    $scope.tableData = function() {
        $http({
            url: './v1/userdetails',
            method: "POST", 
            data: { 'emp_id': localStorage.getItem("emp_id") }
        }).then(function (response) {
            $scope.users = response.data.user_data;
            $scope.dprt_list = response.data.dprt_list;
            $scope.project_list = response.data.project_list;
            $scope.doc_list = response.data.doc_list;
            $scope.transferhistory = response.data.transfer_data;
            $scope.image_list = response.data.image_list;
            $scope.openLightboxModal = function (index){
                Lightbox.openModal($scope.image_list, index);
            };
        });
    };
    
    //change password
    $scope.pwdModal = function() {
        $scope.pwdForm = {};
        $('#pwdModal').modal('show');
    }
    $scope.changePwd = function() {
        if($scope.users.password == $scope.pwdForm.curr_pwd)
        {
            $http({
            url: './v1/changepwd',
            method: "POST", 
            data: { 'emp_id': localStorage.getItem("emp_id"), 'password': $scope.pwdForm.new_pwd }
            }).then(function (response) {
                if(response.data.code=='Success') {
                    $('#pwdModal').modal('hide');
                    $timeout(function() { logout(); }, 500);
                }
                else {
                    $scope.modalAlert = true;
                    $scope.modalAlertMsg = 'Failure !';
                }
            });
        }
        else {
            $scope.modalAlert = true;
            $scope.modalAlertMsg = 'Invalid Password !';
        }
    };
    
    //user update
    $scope.update = function () {
        var user = JSON.stringify($scope.users);
        var form_data = new FormData();
        form_data.append('user_data', user);
        angular.forEach($scope.files, function (file) {
            if(file) { form_data.append('files', file); }
        });
        $http({
            url: './v1/user_update.php',
            method: "POST",
            data: form_data,
            transformRequest: angular.identity,
            headers: {
                'Content-Type': undefined
            }
        }).then(function (response) {
            $scope.tableData();
        });
    }
    
    //transfer
    $scope.onProjectModal = function () {
        $scope.project = {};
        $scope.project.emp_id = $scope.users.emp_id;
        $('#projectModal').modal('show');
    }
    $scope.projectinsert = function () {
        if(!$scope.project.project_block || $scope.project.project_block == '') {
            $scope.project.project_block = 'All';
        }
        if($scope.project.site =='Head Office' || $scope.project.site  == 'S.I.S Capetown' || $scope.project.site  == 'S.I.S Luxor') {
            $scope.project.project_block = '';
        }
        $http({
            url: './v1/usertransfer',
            method: "POST",
            data: $scope.project
        }).then(function (response) {
            $('#projectModal').modal('hide');
            $scope.alert = true;
            if(response.data.code=='Success') {
                $scope.alert_content = "your project details have been succesfully inserted";
            }
            else {
                $scope.alert_content = response.data.code;
            }
            $scope.tableData();
        });
        $timeout(function () { $scope.alert = false; }, 4000);
    }
    
    //upload documents
    $scope.onDocModal = function () {
        $scope.document = {};
        $scope.document.emp_id = $scope.users.emp_id;
        $('#documentModal').modal('show');
    }
    $scope.uploadFile = function () {
        var docData = JSON.stringify($scope.document);
        var form_data = new FormData();
        form_data.append('doc_data', docData);
        angular.forEach($scope.files, function (file) {
            if(file) { form_data.append('files', file); }
        });
        $http({
            url: './v1/user_doc.php',
            method: "POST",
            data: form_data,
            transformRequest: angular.identity,
            headers: {
                'Content-Type': undefined
            }
        }).then(function (response) {
            $('#documentModal').modal('hide');
            $scope.tableData();
        });
    }
    $scope.onDownload = function () {
        window.location.href = 'modules/user/download_doc.php?emp_id='+localStorage.getItem("emp_id");
    }
    
    $scope.setFile = function(element) {
        $scope.currentFile = element.files[0];
        var reader = new FileReader();

        reader.onload = function(event) {
            $scope.users.profileimage = event.target.result
            $scope.$apply()
        }
        reader.readAsDataURL(element.files[0]);
    }
 });