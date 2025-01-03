app.controller('sisUserSettingCtrl', function ($scope, $http, $window, $timeout, $state, $stateParams) {
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
    $scope.department = true;
    $scope.designation = false;

    // $scope.tableData = function(x,y) {
    //     console.log("data",x);
    //     $('#preloader').show();
    //     if(x == 'department')
    //     {
    //      sendURL = './v1/getuserdepartment'
    //      $scope.department = true;
    //      $scope.designation = false;
    //     }
    //     else
    //     {
    //         $scope.department = false;
    //         $scope.designation = true;
    //         sendURL = './v1/getuserdesignation'
          
    //     }
    //     $http({
    //     url: sendURL,
    //     method: "POST",
    //     data: {'cancel_status': y}
    //     })
    //     .then(function (response) {
       
    //    // $scope.category_list = response.data.category_list;
    //    if(x == 'department')
    //    {
    //     $scope.list = response.data.user_department;
    //     $scope.filteredItems = $scope.list.length;
    //     $scope.filter();
    //    }
    //    else
    //    {
    //     $scope.list = response.data.user_designation;
    //     $scope.filteredItems = $scope.list.length;
    //     $scope.filter();
    //    }
    //    console.log("response department..........",$scope.list)
      
        
    //     $scope.showDelBtn = false;
    //     $timeout(function() { $('#preloader').fadeOut("slow"); }, 200);
    //     });
    //     };

//change table type
$scope.tableData = function(x,y) {
    console.log("data",y);
    $('#preloader').show();
    if(x == 'department')
    {
     sendURL = './v1/getuserdepartment'
     $scope.department = true;
     $scope.designation = false;
    }
    else
    {
        $scope.department = false;
        $scope.designation = true;
        sendURL = './v1/getuserdesignation'
      
    }
    $http({
    url: sendURL,
    method: "POST",
    data: {'cancel_status': y}
    })
    .then(function (response) {
    
    if(x == 'department')
    {
        $scope.list = response.data.user_department;
    }
    else
    {
        $scope.department_list = response.data.department_list;
        $scope.list = response.data.user_designation;
        console.log("designation...........",response.data.department_list)
    }
    console.log("response",$scope.list)
    
    $scope.filteredItems = $scope.list.length;
    $scope.filter();
    $scope.showDelBtn = false;
    $timeout(function() { $('#preloader').fadeOut("slow"); }, 200);
    });
    };
        
    $scope.currentPage = 1;
    
    $scope.filter = function() {
        $timeout(function() { 
            $scope.filteredItems = $scope.filtered.length;
        }, 15);
    };
    $scope.sort_by = function(predicate) {
        $scope.predicate = predicate;
        $scope.reverse = !$scope.reverse;
    };
    
  
    

    $scope.addDepartmentModal = function(){
        $scope.addDepartmentForm = {};  
        $('#addDepartmentModal').modal('show');
        }         
        $scope.onAdddepartmentSubmit = function(){                
        jsonArrayData = JSON.stringify($scope.addDepartmentForm);
        console.log("add department.......")
        mystring1 = $scope.addDepartmentForm.name.replace(" ","")  
        $scope.addDepartmentForm.name_ws = angular.lowercase(mystring1); 
        $scope.addDepartmentForm.emp_id = localStorage.getItem("user_id");
        console.log("Add department",$scope.addDepartmentForm);
        $http({
        url: './v1/adddepartment',
        method: "POST",
        data: $scope.addDepartmentForm
        })
        .then(function(response){
            console.log("response department.....", response)
            $('#addDepartmentModal').modal('hide');
        $scope.alert = true;
        $scope.tableData("department",'1');
        if(response.data.code == 'Success'){
        $(".alert").removeClass().addClass('alert alert-success');
        $scope.alert_content = 'Added Successfully !!!';
        }
        else{
        $(".alert").removeClass().addClass('alert alert-danger');
        $scope.alert_content = response.data.code;
        }
        });
        $timeout(function() { $scope.alert = false; }, 2000);
        }

    

        //edit
        $scope.onEditDepartment = function(x){
            $http({
            url: './v1/editdepartment',
            method: "POST",
            data: { 'id': x }
            })
            .then(function(response){
            console.log("edit department..................", response)
            $('#editDepartmentModal').modal('show');
            $scope.editDepartmentForm._id = x;
            $scope.editDepartmentForm.status = '1';
            $scope.editDepartmentForm = response.data.department_details;
            });
            }
    
            $scope.onEditDepartmentSubmit = function(){ 
    
            mystring = $scope.editDepartmentForm.department.replace(" ","")
            $scope.editDepartmentForm.department_ws = angular.lowercase(mystring);      
    
            $http({
            url: './v1/updatedepartment',
            method: "POST",
            data: $scope.editDepartmentForm
            })
            .then(function(response){
            console.log("update",response)
            $('#editDepartmentModal').modal('hide');
            $scope.alert = true;
            $scope.tableData('department','1');
            if(response.data.code == 'Success'){
            $(".alert").removeClass().addClass('alert alert-success');
            $scope.alert_content = 'Updated Successfully !!!';
            }
            else{
            $(".alert").removeClass().addClass('alert alert-danger');
            $scope.alert_content = 'Failure !!!';
            }
            });
            $timeout(function() { $scope.alert = false; }, 2000);
            }
    //delete
    $scope.onDeleteDepartment = function(x){
        $('#departmentConfirmModal').modal('show');
        $scope.alert_msg = 'Are you sure to permanently delete this?';
        $scope.modal_id = x;
        $scope.confirmFn = 'delete';
    }
    $scope.delete = function(x) {
        $http({
            url: './v1/deletedepartment',
            method: "POST",
            data: { 'type': 'deletedepartment','id': x }
        })
        .then(function(response){
            $('#departmentConfirmModal').modal('hide');
            $scope.alert = true;
            $scope.tableData('department','1');
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



    $scope.addDesigModal = function(){
        $scope.addDesigForm = {};  
        $('#addDesigModal').modal('show');
        }         
        $scope.onAddDesigSubmit = function(){                
        jsonArrayData = JSON.stringify($scope.addDesigForm);
        console.log("add department.......")

        mystring1 = $scope.addDesigForm.designation.replace(" ","")  
        $scope.addDesigForm.designation_ws = angular.lowercase(mystring1);         
        $scope.addDesigForm.emp_id = localStorage.getItem("user_id");
        console.log("Add department",$scope.addDesigForm);
        $http({
        url: './v1/adddesignation',
        method: "POST",
        data: $scope.addDesigForm
        })
        .then(function(response){
            console.log("response designation.....", response)
            $('#addDesigModal').modal('hide');
        $scope.alert = true;
        $scope.tableData('designation','1');
        if(response.data.code == 'Success'){
        $(".alert").removeClass().addClass('alert alert-success');
        $scope.alert_content = 'Added Successfully !!!';
        }
        else{
        $(".alert").removeClass().addClass('alert alert-danger');
        $scope.alert_content = response.data.code;
        }
        });
        $timeout(function() { $scope.alert = false; }, 2000);
        }
    


        //edit
        $scope.onEditDsignation = function(x){
        $scope.editDesignationForm= {}
        $http({
        url: './v1/editdesignation',
        method: "POST",
        data: { 'id': x }
        })
        .then(function(response){
        console.log("edit department..................", response)
        $('#editDesigModal').modal('show');
        $scope.editDesignationForm._id = x;
        $scope.editDesignationForm = response.data.designation_details;
        });
        }

        $scope.onEditDesignationSubmit = function(){ 

        mystring = $scope.editDesignationForm.designation.replace(" ","")
        $scope.editDesignationForm.designation_ws = angular.lowercase(mystring);      

        $http({
        url: './v1/updatedesignation',
        method: "POST",
        data: $scope.editDesignationForm
        })
        .then(function(response){
        console.log("update",response)
        $('#editDesigModal').modal('hide');
        $scope.alert = true;
        $scope.tableData('designation','1');
        if(response.data.code == 'Success'){
        $(".alert").removeClass().addClass('alert alert-success');
        $scope.alert_content = 'Updated Successfully !!!';
        }
        else{
        $(".alert").removeClass().addClass('alert alert-danger');
        $scope.alert_content = 'Failure !!!';
        }
        });
        $timeout(function() { $scope.alert = false; }, 2000);
        }


        $scope.onDeleteDesignation = function(x){
            $('#desigConfirmModal').modal('show');
            $scope.alert_msg = 'Are you sure to permanently delete this?';
            $scope.modal_id1 = x;
            $scope.confirmFn = 'deleteDesig';
            console.log("modal_id1............",  $scope.confirmFn)
            }
            $scope.deleteDesig = function(x) {
            $http({
            url: './v1/deletedesignation',
            method: "POST",
            data: {'type': 'deletedesignation', 'id': x}
            })
            .then(function(response){
            $('#desigConfirmModal').modal('hide');
            $scope.alert = true;
            $scope.tableData('designation','1');
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