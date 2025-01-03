app.controller('sisProjectCtrl', function($scope, $http, $timeout, $state, orderService) {
        
        var jsonData = jQuery.parseJSON(localStorage.getItem("project"));
        if(jsonData){
            $scope.addPermission = jsonData['add'];
            $scope.editPermission = jsonData['edit'];
            $scope.deletePermission = jsonData['delete'];
        }
        else{
            $state.go('home.dashboard');
        }
        
        $scope.tableData = function() {
            $('#preloader').show();
            $http({
                url: './v1/getsisprojects'
            })
            .then(function (response){
                $scope.list = response.data.project_list;
                $scope.user_list = response.data.user_list;
                $scope.filteredItems = $scope.list.length;
                $scope.filter();
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
        
        //add project
        $scope.addModal = function(){
            $scope.addProjectForm = {};
            $('#addModal').modal('show');
        }
        $scope.onAddSubmit = function(){
            $http({
                url: './v1/addsisproject',
                method: "POST",
                data: $scope.addProjectForm
            })
            .then(function(response) {
                $('#addModal').modal('hide');
                $scope.alert = true;
                $scope.tableData();
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
        };
        
        //edit project
        $scope.onEdit = function(x){
            $http({
                url: './v1/editsisproject',
                method: "POST",
                data: { 'id': x }
            })
            .then(function(response) {
                $scope.editProjectForm = response.data.project_list;
                $('#editModal').modal('show');
            });
        };
        
        //update project
        $scope.onEditSubmit = function(){
            $http({
                url: './v1/updatesisproject',
                method: "POST",
                data: $scope.editProjectForm
            })
            .then(function(response) {
                $('#editModal').modal('hide');
                $scope.alert = true;
                $scope.tableData();
                if(response.data.code == 'Success'){
                    $(".alert").removeClass().addClass('alert alert-success');
                    $scope.alert_content = 'Updated Successfully !!!';
                }
                else{
                    $(".alert").removeClass().addClass('alert alert-danger');
                    $scope.alert_content = response.data.code;
                }
            });
            $timeout(function() { $scope.alert = false; }, 2000);
        };
        
        //delete
        $scope.onDelete = function(x){
            $('#confirmModal').modal('show');
            $scope.alert_msg = 'Are you sure to permanently delete this?';
            $scope.modal_id = x;
            $scope.confirmFn = 'delete';
        }
        $scope.delete = function(x) {
            $http({
                url: './v1/delsisproject',
                method: "POST",
                data: { 'id': x }
            })
            .then(function(response){
                $('#confirmModal').modal('hide');
                $scope.alert = true;
                $scope.tableData();
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