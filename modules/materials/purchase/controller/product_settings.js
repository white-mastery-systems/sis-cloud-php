app.controller('productSettingCtrl', function ($scope, $http, $window, $timeout, $state, $stateParams) {
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
    $scope.category = true;
    $scope.protype = false;

   

//change table type
$scope.tableData = function(x,y) {
    console.log("data",x);
    $('#preloader').show();

    if(x == 'category')
    {
     sendURL = './v1/getprocategory'
     $scope.category = true;
     $scope.protype = false;
    }
    else
    {
        $scope.category = false;
        $scope.protype = true;
        sendURL = './v1/getprotype'
      
    }
    $scope.cancel_status = "1";
    $http({
    url: sendURL,
    method: "POST",
    data: {'cancel_status':  $scope.cancel_status}
    })
    .then(function (response) {
    
    if(x == 'category')
    {
        $scope.list = response.data.category;
       // $scope.category = response.data.category;
    }
    else
    {
     
        $scope.list = response.data.protype_details;
        
        
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
    
  
    

    $scope.addCategoryModal = function(){
        console.log("addCategoryModal.............")
        $scope.addCategoryForm = {};  
        $('#addCategoryModal').modal('show');
        }    
        
        $scope.onAddcategorySubmit = function(){                
            jsonArrayData = JSON.stringify($scope.addCategoryForm);
            mystring = $scope.addCategoryForm.category.replace(" ","")
            mystring1 = $scope.addCategoryForm.short.replace(" ","")     
            $scope.addCategoryForm.category_ws = angular.lowercase(mystring);    
            $scope.addCategoryForm.shortname = angular.uppercase(mystring1); 
            $scope.addCategoryForm.emp_id = localStorage.getItem("user_id");
            console.log("Add Category",$scope.addCategoryForm);
            $http({
            url: './v1/addcategory',
            method: "POST",
            data: $scope.addCategoryForm
            })
            .then(function(response){
            $('#addcategoryModal').modal('hide');
            $scope.alert = true;
            // $scope.tableData($scope.tabletype);
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
        $scope.onEditCategory = function(x){
            console.log("edit category..................", x)
            $http({
            url: './v1/editcategory',
            method: "POST",
            data: { 'id': x }
            })
            .then(function(response){
            console.log("edit department..................", response)
            $('#editCategoryModal').modal('show');
            $scope.editCategoryForm._id = x;
            $scope.editCategoryForm = response.data.category_details;
            });
            }
    
            $scope.onEditCategorySubmit = function(){ 
    
                mystring = $scope.editCategoryForm.category.replace(" ","")
                mystring1 = $scope.editCategoryForm.short.replace(" ","")     
                $scope.editCategoryForm.category_ws = angular.lowercase(mystring);    
                $scope.editCategoryForm.shortname = angular.uppercase(mystring1); 
                $scope.editCategoryForm.emp_id = localStorage.getItem("user_id");
            $http({
            url: './v1/updatecategory',
            method: "POST",
            data: $scope.editCategoryForm
            })
            .then(function(response){
            console.log("update",response)
            $('#editCategoryModal').modal('hide');
            $scope.alert = true;
            $scope.tableData('category','1');
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
    $scope.onDeleteCategory = function(x){
        $('#categoryConfirmModal').modal('show');
        $scope.alert_msg = 'Are you sure to permanently delete this?';
        $scope.modal_id = x;
        $scope.confirmFn = 'delete';
    }
    $scope.delete = function(x) {
        $http({
            url: './v1/deletecategory',
            method: "POST",
            data: { 'type': 'deletecategory', 'id': x }
        })
        .then(function(response){
            $('#categoryConfirmModal').modal('hide');
            $scope.alert = true;
            $scope.tableData('category','1');
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



    $scope.addtypeModal = function(){
        $scope.addTypeForm = {};  
        $('#addTypeModal1').modal('show');
        }         
        $scope.onAddTypeSubmit = function(){                
        jsonArrayData = JSON.stringify($scope.addTypeForm);
        console.log("add department.......");
        mystring1 = $scope.addTypeForm.type.replace(" ","")  
        $scope.addTypeForm.type_ws = angular.lowercase(mystring1);         
        $scope.addTypeForm.emp_id = localStorage.getItem("user_id");
        $scope.addTypeForm.status = "1";
        console.log("Add Type",$scope.addTypeForm);
        $http({
        url: './v1/addprotype',
        method: "POST",
        data: $scope.addTypeForm
        })
        .then(function(response){
            console.log("response Type.....", response)
            $('#addTypeModal1').modal('hide');
        $scope.alert = true;
        $scope.tableData('protype','1');
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
        $scope.onEditType = function(x){
        $scope.editTypeForm= {}
        $http({
        url: './v1/editprotype',
        method: "POST",
        data: { 'id': x }
        })
        .then(function(response){
        console.log("edit department..................", response)
        $('#editTypeModal').modal('show');
        $scope.editTypeForm._id = x;
        $scope.editTypeForm = response.data.type_details;
        });
        }

        $scope.onEditTypeSubmit = function(){ 
        mystring = $scope.editTypeForm.type.replace(" ","")
        $scope.editTypeForm.type_ws = angular.lowercase(mystring);      

        $http({
        url: './v1/updateprotype',
        method: "POST",
        data: $scope.editTypeForm
        })
        .then(function(response){
        console.log("update",response)
        $('#editTypeModal').modal('hide');
        $scope.alert = true;
        $scope.tableData('protype','1');
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


        $scope.onDeleteType = function(x){
            $('#typeConfirmModal').modal('show');
            $scope.alert_msg = 'Are you sure to permanently delete this?';
            $scope.modal_id1 = x;
            $scope.confirmFn = 'deleteType';
            console.log("modal_id1............",  $scope.confirmFn)
            }
            $scope.deleteType = function(x) {
            $http({
            url: './v1/deleteprotype',
            method: "POST",
            data: {'type': 'deletetype', 'id': x}
            })
            .then(function(response){
            $('#typeConfirmModal').modal('hide');
            $scope.alert = true;
            $scope.tableData('protype','1');
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