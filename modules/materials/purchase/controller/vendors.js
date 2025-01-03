app.controller('vendorCtrl', function($scope, $http, $timeout, $state) {

        

        var jsonData = jQuery.parseJSON(localStorage.getItem("vendor"));

        if(jsonData){

            $scope.addPermission = jsonData['add'];

            $scope.editPermission = jsonData['edit'];

            $scope.deletePermission = jsonData['delete'];

        }

        else{

            $state.go('home.dashboard');

        }

       

        //change table type

        $scope.tableData = function(x) {

            $('#preloader').show();

            $http({

                url: './v1/getvendorlist',

                method: "POST",

                data: {'cancel_status': x}

            })

            .then(function (response){

                $scope.list = response.data.vendor_details;

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

            }, 10);

        };

        $scope.sort_by = function(predicate) {

            $scope.predicate = predicate;

            $scope.reverse = !$scope.reverse;

        };

        

        //add vendor

        $scope.addModal = function()
        {
            $scope.addVendorForm = {};
            $('#addModal').modal('show');
        }

        $scope.isSubmitting = false;

        $scope.onAddSubmit = function()
        {
            $scope.isSubmitting = true; // Disable the button
            $scope.addVendorForm.emp_id = localStorage.getItem("user_id");
            console.log("addform...........", $scope.addVendorForm)
            $http({
                url: './v1/addpovendor',
                method: "POST",
                data: $scope.addVendorForm
            })
            .then(function(response)
            {
                $('#addModal').modal('hide');
                $scope.alert = true;
                $scope.tableData($scope.tabletype);
                if(response.data.code == 'Success'){
                    $(".alert").removeClass().addClass('alert alert-success');
                    $scope.alert_content = 'Added Successfully !!!';
                }
                else{
                    $(".alert").removeClass().addClass('alert alert-danger');
                    $scope.alert_content = response.data.code;
                }
            })
            .catch(function(error) {
                $(".alert").removeClass().addClass('alert alert-danger');
                $scope.alert_content = 'An error occurred while adding the vendor!';
                console.error("Error:", error);
            })
            .finally(function() {
                $scope.isSubmitting = false; // Enable the button
            });
            $timeout(function() { $scope.alert = false; }, 2000);
        }

        

        //edit

        $scope.onEdit = function(x){

            $http({

                url: './v1/editpovendor',

                method: "POST",

                data: { 'id': x }

            })

            .then(function(response){

                $('#editModal').modal('show');

                $scope.editVendorForm = response.data.vendor_details;

                console.log("vendor Details.......",  response.data.vendor_details)

            });

        }

        

        //update vendor

        $scope.onEditSubmit = function(){

            $http({

                url: './v1/updatepovendor',

                method: "POST",

                data: $scope.editVendorForm

            })

            .then(function(response){

                $('#editModal').modal('hide');

                $scope.alert = true;

                $scope.tableData($scope.tabletype);

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

        

        //remove order

        $scope.onRemove = function(x){

            $('#confirmModal').modal('show');

            $scope.alert_msg = 'Are you sure to remove this?';

            $scope.modal_id = x;

            $scope.confirmFn = 'remove';

        }

        $scope.remove = function(x) {

            $http({

                url: './v1/deletevendor',

                method: "POST",

                data: {'type': 'removevendor','id': x}

            })

            .then(function(response){

                $('#confirmModal').modal('hide');

                $scope.alert = true;

                $scope.tableData($scope.tabletype);

                if(response.data.code == 'Success'){

                    $(".alert").removeClass().addClass('alert alert-success');

                    $scope.alert_content = 'Removed Successfully !!!';

                }

                else{

                    $(".alert").removeClass().addClass('alert alert-danger');

                    $scope.alert_content = 'Failure !!!';

                }

            });

            $timeout(function() { $scope.alert = false; }, 2000);

        }

        

        //reopen order

        $scope.onReopen = function(x){

            $('#confirmModal').modal('show');

            $scope.alert_msg = 'Are you sure to reopen this?';

            $scope.modal_id = x;

            $scope.confirmFn = 'reopen';

        }

        $scope.reopen = function(x) {

            $http({

                url: './v1/deletevendor',

                method: "POST",

                data: {'type': 'reopenvendor','id': x}

            })

            .then(function(response){

                $('#confirmModal').modal('hide');

                $scope.alert = true;

                $scope.tableData($scope.tabletype);

                if(response.data.code == 'Success'){

                    $(".alert").removeClass().addClass('alert alert-success');

                    $scope.alert_content = 'Reopened Successfully !!!';

                }

                else{

                    $(".alert").removeClass().addClass('alert alert-danger');

                    $scope.alert_content = 'Failure !!!';

                }

            });

            $timeout(function() { $scope.alert = false; }, 2000);

        }

        

        //delete order

        $scope.onDelete = function(x){

            $('#confirmModal').modal('show');

            $scope.alert_msg = 'Are you sure to permanently delete this?';

            $scope.modal_id = x;

            $scope.confirmFn = 'delete';

        }

        $scope.delete = function(x) {

            $http({

                url: './v1/deletevendor',

                method: "POST",

                data: {'type': 'deletevendor', 'id': x}

            })

            .then(function(response){

                $('#confirmModal').modal('hide');

                $scope.alert = true;

                $scope.tableData($scope.tabletype);

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

        

        //display delete button

        $scope.deleteShow = function () {

            var count=0;

            angular.forEach($scope.filtered,function(x){

                if(x.singlecheck) {

                    count++;

                }

            });

            if(count > 0){

                $scope.showDelBtn = true;

            }

            else{

                $scope.showDelBtn = false;

            }

        }

        

        //delete all

        $scope.deleteall = function() {

            

            $scope.tableItems = [];

            angular.forEach($scope.filtered,function(x){

                if(x.singlecheck) {

                    $scope.tableItems.push({ type: $scope.tabletype, id: x._id.$oid });  

                }

            });

            var jsonArrayData = JSON.stringify($scope.tableItems);

            

            if(jsonArrayData != '[]')

            {

                $('#confirmModal').modal('show');

                $scope.modal_id = jsonArrayData;

                $scope.confirmFn = 'alldelete';



                if($scope.tabletype=='usage'){

                    $scope.alert_msg = 'Are you sure to remove the selected products?';

                    $scope.alert_content = 'Removed Successfully !!!';

                }

                else{

                    $scope.alert_msg = 'Are you sure to permanently delete the selected products?';

                    $scope.alert_content = 'Deleted Successfully !!!';

                }

            }

        }

       $scope.alldelete = function(x) {

            $http({

                url: './v1/deleteallvendors',

                method: "POST",

                data: {'jsonArrayData': x}

            })

            .then(function(response){

                $('#confirmModal').modal('hide');

                $scope.alert = true;

                $scope.tableData($scope.tabletype);

                if(response.data.code == 'Success'){

                    $(".alert").removeClass().addClass('alert alert-success');

                }

                else{

                    $(".alert").removeClass().addClass('alert alert-danger');

                    $scope.alert_content = 'Failure !!!';

                }

            });

            $timeout(function() { $scope.alert = false; }, 2000);

        }

});