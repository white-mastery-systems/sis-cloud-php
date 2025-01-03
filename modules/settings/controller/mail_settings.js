app.controller('mailSettingCtrl', function ($scope, $http, $window, $timeout, $state, $stateParams) {
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
    $scope.to = true;
    $scope.cc = false;
    $scope.bcc = false;

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
    if(x=='to')
    {
        $scope.to = true;
        $scope.cc = false;
        $scope.bcc = false;
        $scope.smtp = false;
        sendURL = './v1/getmailsettings'
    }
    else if(x=='cc')
    {
        $scope.to = false;
        $scope.cc = true;
        $scope.bcc = false;  
        $scope.smtp = false;
        sendURL = './v1/getmailsettings'
    }
    else if(x=='bcc')
    {
        $scope.to = false;
        $scope.cc = false;
        $scope.bcc = true;
        $scope.smtp = false;
        sendURL = './v1/getmailsettings'
    }
    else
    { 
        $scope.to = false;
        $scope.cc = false;
        $scope.bcc = false;
        $scope.smtp = true;
        sendURL = './v1/getmailsmtpsettings'
    }
    $('#preloader').show();
    $http({
    url: sendURL,
    method: "POST",
    data: {'cancel_status': '1', "mailtype" : x, "type" : y }
    })
    .then(function (response) {   
  
    $scope.list = response.data.mail_details;   
    console.log("response",$scope.list);    
    $scope.filteredItems = $scope.list.length;
    $scope.filter($scope.list.length);
    $scope.showDelBtn = false;
    $timeout(function() { $('#preloader').fadeOut("slow"); }, 200);
    });
    };
        
    $scope.currentPage = 1;
    
    $scope.filter = function(length) {
        $timeout(function() { 
            $scope.filteredItems = length;
        }, 15);
    };
    $scope.sort_by = function(predicate) {
        $scope.predicate = predicate;
        $scope.reverse = !$scope.reverse;
    };
    
  
    

    $scope.addmailsettings = function(x){
        
        if(x == 'smtp')
        {
            $scope.addSMTPForm = {};
            $('#addSMTPModal').modal('show');
           sendURL = './v1/addmailsmtpsettings'
        }
        else
        {
            $scope.addMailForm = {}; 
            $('#addMailModal').modal('show');
            sendURL = './v1/addmailsettings'
            $scope.addMailForm.mailtype= x;
        }
        }   
        

        $scope.onMailsettingsSubmit = function(){     
            $scope.addMailForm.cancel_status = '1';         
            $scope.addMailForm.emp_id = localStorage.getItem("user_id");           
           
            console.log("add to mail.......")
           
            console.log("Add department",$scope.addMailForm);
            $http({
            url: './v1/addmailsettings' ,
            method: "POST",
            data: $scope.addMailForm
            })
            .then(function(response){
            console.log("response mail form.....", response)
            $('#addMailModal').modal('hide');
            $scope.alert = true;
            $scope.tableData($scope.addMailForm.mailtype,$scope.addMailForm.type);
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
        
        $scope.onSMTPsettingsSubmit = function(){     
        $scope.addSMTPForm.cancel_status = '1';         
        $scope.addSMTPForm.emp_id = localStorage.getItem("user_id");           
       
        console.log("add to mail.......")
       
        console.log("Add department",$scope.addSMTPForm);
        $http({
        url: sendURL ,
        method: "POST",
        data: $scope.addSMTPForm
        })
        .then(function(response){
        console.log("response mail form.....", response)
        $('#addSMTPModal').modal('hide');
        $scope.alert = true;
        $scope.tableData('mailsetup','smtp');
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
        $scope.onEditMail = function(x,y){
            console.log("x..............", x)
            if(y == 'smtp')
            {
                sendURL =  './v1/editmailsmtpsettings'
            }
            else
            {
                sendURL =  './v1/editmailsettings'
            }
            $http({
            url: sendURL,
            method: "POST",
            data: { 'id': x, "mailtype":y }
            })
            .then(function(response){
            console.log("edit department..................", response);
            if(y == 'smtp')
            {
                $('#editSMTPModal').modal('show');
                $scope.editSMTPForm._id = x;    
                $scope.editSMTPForm.cancel_status = "1";          
                $scope.editSMTPForm = response.data.smtp_details;
            }
            else
            {
                $('#editMailModal').modal('show');
                $scope.editMailForm._id = x;   
                $scope.editMailForm.cancel_status = "1";                
                $scope.editMailForm = response.data.mail_details;
            }
           
           
            });
            }
    
            $scope.onEditMailSubmit = function(){    

            $http({
            url: './v1/updatemailsettings',
            method: "POST",
            data: $scope.editMailForm
            })
            .then(function(response){
            console.log("update",$scope.editMailForm)
            $('#editMailModal').modal('hide');
            $scope.alert = true;
            $scope.tableData($scope.editMailForm.mailtype,$scope.editMailForm.type);
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

            $scope.onEditSMTPSubmit = function(){  
                $http({
                url: './v1/updatemailsmtpsettings',
                method: "POST",
                data: $scope.editSMTPForm
                })
                .then(function(response){
                console.log("update",$scope.editSMTPForm)
                $('#editSMTPModal').modal('hide');
                $scope.alert = true;
                $scope.tableData('mailsetup','smtp');
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
    $scope.onDeleteMail = function(x,y,z){        
        $('#departmentConfirmModal').modal('show');
        $scope.alert_msg = 'Are you sure to permanently delete this?';
        $scope.modal_id = x;
        $scope.mailtype = y;
        $scope.type = z;        
        $scope.confirmFn = 'delete';
    }
    $scope.delete = function(x,y,z) {

        if(y == 'smtp')
        {
            sendURL = './v1/deletemailsmtpsettings'
        }
        else
        {
            sendURL = './v1/deletemailsettings'
        }
        console.log(y,"-------------------",z)
        $http({
            url: sendURL,
            method: "POST",
            data: { 'id': x, 'mailtype': y }
        })
        .then(function(response){
            console.log("delete response....", response)
            $('#departmentConfirmModal').modal('hide');
            $scope.alert = true;
            if(y == 'smtp')
            {
                $scope.tableData('mailsetup','smtp');
            }
            else
            {
                $scope.tableData(y,z);
            }
           
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