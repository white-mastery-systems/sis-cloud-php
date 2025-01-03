app.controller('productCtrl', function($scope, $http, $timeout, $state, orderService) {

var jsonData = jQuery.parseJSON(localStorage.getItem("product"));
 $scope.designation = localStorage.getItem("user_designation");
 console.log("designation",$scope.designation)
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
console.log("data",x);
$('#preloader').show();
$http({
url: './v1/getproductdetails',
method: "POST",
data: {'cancel_status': x}
})
.then(function (response) {
console.log("response",response.data.product_details)
$scope.category_list = response.data.category_list;
$scope.list = response.data.product_details;
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

//change type
$scope.typeChange = function(type, category){
//get product list
//    console.log(type," ",category)
if(type && category) {
$http({
url: './v1/getproductlist',
method: "POST",
data: { 'type':type, 'category':category }
})
.then(function (response){ 
$scope.product_list = response.data.product_list;
});
}
};
   
$scope.getproductcode = function(type, category){
//get product list
     console.log(category);
if(type && category) {
$http({
url: './v1/getproductcode',
method: "POST",
data: { 'type':type, 'category':category }
})
.then(function (response){ 
    console.log(response);
$scope.addProductForm.product_code = response.data.product_code;
});
}
};    
    
    
    
    

$scope.doorcalcAdd = function(x) {
$scope.addProductForm.cft = orderService.doorcalculation(x);
}

$scope.doorcalcEdit = function(x) {
$scope.editProductForm.cft = orderService.doorcalculation(x);
}
//add category Modal
$scope.addcategoryModal = function(){
$scope.addCategoryForm = {};  
$scope.addCategoryForm.type = 'Standard';
$('#addcategoryModal').modal('show');
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

//add product
$scope.addModal = function(){
$scope.addProductForm = {};
$scope.addProductForm.type = 'Standard';
$('#addModal').modal('show');
}


$scope.onAddSubmit = function(){
jsonArrayData = JSON.stringify($scope.addProductForm);
mystring = $scope.addProductForm.product_name.replace(" ","")
$scope.addProductForm.product_name_ws = angular.lowercase(mystring); 
$scope.addProductForm.emp_id = localStorage.getItem("user_id");
$http({
url: './v1/addpoproduct',
method: "POST",
data: $scope.addProductForm
})
.then(function(response){
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
});
$timeout(function() { $scope.alert = false; }, 2000);
}

//edit
$scope.onEdit = function(x){
$http({
url: './v1/editpoproduct',
method: "POST",
data: { 'id': x }
})
.then(function(response){
$('#editModal').modal('show');
$scope.editProductForm = response.data.product_details;
});
}

$scope.onEditSubmit = function(){
    
    mystring = $scope.editProductForm.product_name.replace(" ","")
$scope.editProductForm.product_name_ws = angular.lowercase(mystring); 
$http({
url: './v1/updatepoproduct',
method: "POST",
data: $scope.editProductForm
})
.then(function(response){
    console.log("update",response)
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

//remove
$scope.onRemove = function(x){
$('#confirmModal').modal('show');
$scope.alert_msg = 'Are you sure to remove this?';
$scope.modal_id = x;
$scope.confirmFn = 'remove';
}
$scope.remove = function(x) {
$http({
url: './v1/deleteproduct',
method: "POST",
data: {'type': 'removeproduct','id': x}
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

//reopen
$scope.onReopen = function(x){
$('#confirmModal').modal('show');
$scope.alert_msg = 'Are you sure to reopen this?';
$scope.modal_id = x;
$scope.confirmFn = 'reopen';
}
$scope.reopen = function(x) {
$http({
url: './v1/deleteproduct',
method: "POST",
data: {'type': 'reopenproduct','id': x}
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

//delete
$scope.onDelete = function(x){
$('#confirmModal').modal('show');
$scope.alert_msg = 'Are you sure to permanently delete this?';
$scope.modal_id = x;
$scope.confirmFn = 'delete';
}
$scope.delete = function(x) {
$http({
url: './v1/deleteproduct',
method: "POST",
data: {'type': 'deleteproduct', 'id': x}
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
url: './v1/deleteallproducts',
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

//export
$scope.export = function () {
window.location.href = 'modules/materials/purchase/products_export.php?status='+$scope.tabletype+'&search='+$scope.search;
}

});