app.controller('neworderCtrl', function($scope, $http, dateFilter, $state, $stateParams, orderService, $timeout) {

var jsonData = jQuery.parseJSON(localStorage.getItem("order"));

$scope.mailPermission = jsonData['mail'];
if(jsonData['add'] != 1){
$state.go('home.dashboard');
}
else
{
$scope.cc_mail =[];
if(localStorage.getItem("user_id") === '1358')
{
$scope.cc_mail.push({mail_id:'ranjith@sis.in'},{mail_id:'venkat@sis.in'},{mail_id:'vinodhini@sis.in'});
}
else
{
$scope.cc_mail.push({mail_id:'johni@sis.in'},{mail_id:'ranjith@sis.in'});
}
//page back
var splitData = $stateParams.filter.split("_");
$scope.onBack = function(){
$state.go('home.orders', { filter: splitData[0]+'_'+splitData[1]+'_'+splitData[2]+'_'+splitData[3]+'_'+splitData[4] });
}
//po date
$scope.date = new Date();
$scope.$watch('date', function(date){
$scope.purchaseForm.po_date = dateFilter(date, 'dd-MM-yyyy');
$scope.purchaseForm.payment = "30 days after receipt of materials @ Site";
});

//get project list
$('#preloader').show();
$http({
url: './v1/getprojectdetails',
method: "POST",
data: {'emp_id': localStorage.getItem("user_id")}
})
.then(function (response){
$scope.quotation_list = response.data.quotation_list;
$scope.order_type = response.data.order_type;
$scope.category_list = response.data.category_list;
$scope.vendor_details = response.data.vendor_details;
$scope.payment_list = response.data.payment_list;
$scope.vat_list = response.data.vat_list;
$scope.projects = response.data.project_list;
$scope.mail_list = response.data.user_mail;
$scope.mailForm.from_mail = response.data.from_mail;
$scope.purchaseForm.order_type_short = 'PO';
$scope.names = [{ 'item': "" }];

//state parameter
if($stateParams.filter != '') {
var splitData = $stateParams.filter.split("_");
if(splitData[5]){
$scope.purchaseForm.quot_status = true;
$scope.purchaseForm.quot_number = splitData[5];
$("#quot_status").prop('disabled', true);
$("#quot_number").prop('readonly', true);
$scope.poFromQuotation(splitData[5]);
}
}
$timeout(function() { $('#preloader').fadeOut("slow"); }, 200);
});

//change type
$scope.typeChange = function(){
//get project list
if($scope.purchaseForm.type && $scope.purchaseForm.category) {
$http({
url: './v1/getproductlist',
method: "POST",
data: {'type':$scope.purchaseForm.type, 'category':$scope.purchaseForm.category}
})
.then(function (response){
console.log(response    )
$scope.product_list = response.data.product_list;
});
}
$scope.names = [{ 'item': "" }];
$scope.purchaseForm.sub_total = '';
$scope.purchaseForm.trans_amount = '';
$scope.purchaseForm.trans_tax = '';
$scope.purchaseForm.plain_amount = '';
$scope.purchaseForm.plain_tax = '';
$scope.purchaseForm.grand_total = '';
};
$scope.to_mail =[];

//po from quotation
$scope.poFromQuotation = function(x){
$http({
url: './v1/pofromquotation',
method: "POST",
data: { 'quot_number': x }
})
.then(function (response){
$scope.purchaseForm.invoice_no = response.data.invoice_no;
$scope.purchaseForm.quot_number = response.data.invoice_data.quot_number;
$scope.purchaseForm.type = response.data.invoice_data.type;
$scope.purchaseForm.category = response.data.invoice_data.category;
$scope.purchaseForm.sub_category = response.data.invoice_data.sub_category;
$scope.purchaseForm.sis_project_name = response.data.invoice_data.sis_project_name;
$scope.purchaseForm.sis_project_block = response.data.invoice_data.sis_project_block;
$scope.purchaseForm.company = response.data.invoice_data.company;
$scope.purchaseForm.comp_address = response.data.invoice_data.comp_address;
$scope.purchaseForm.contact_person = response.data.invoice_data.contact_person;
$scope.purchaseForm.party_gst = response.data.invoice_data.party_gst;
// $scope.mailForm.to_mail = response.data.invoice_data.to_mail;
$scope.to_mail.push({mail_id:response.data.invoice_data.to_mail})
console.log("vendordata:",response.data.invoice_data.to_mail);
$scope.names = response.data.quotation_list;
$scope.person_list = response.data.person_list;
$scope.changeProjectName(response.data.invoice_data.sis_project_name);
$scope.changeBlockName(response.data.invoice_data.sis_project_block);
$scope.calculateSum();
});
};

//change order type
$scope.orderChange = function (x) {
var len = $scope.order_type.length;
for(var i=0; i<len; i++)
{
if(x == $scope.order_type[i].name) {
$scope.purchaseForm.order_type_short = $scope.order_type[i].short_name;
break;
}
}
};

//change project
$scope.changeProjectName = function(x){
if(!x) {
$scope.purchaseForm.our_gst = '';
$scope.purchaseForm.delivery_place = '';
$scope.purchaseForm.supply_place = '';
$scope.purchaseForm.site_address = '';
$scope.purchaseForm.invoice_no = '';
$scope.purchaseForm.sis_project_block = '';
$scope.purchaseForm.site_contact_person = '';
$scope.purchaseForm.mobile = '';
}
else {
var len = $scope.projects.length;
for(var i=0; i<len; i++)
{
if(x == $scope.projects[i].project_name) {
$http({
url: './v1/getponumber',
method: "POST",
data: {'project_short': $scope.projects[i].project_short, 'project_name': x}
})
.then(function (response){
$scope.purchaseForm.invoice_no = response.data.invoice_no;
$scope.person_list = response.data.person_list;
});
$scope.purchaseForm.our_gst = $scope.projects[i].gst_in;
$scope.purchaseForm.delivery_place = $scope.projects[i].place;
$scope.purchaseForm.supply_place = $scope.projects[i].place;
$scope.purchaseForm.site_address = $scope.projects[i].address;
break;
}
}
} 
};

//change block
$scope.changeBlockName = function(x){
if(!x) {
$scope.purchaseForm.site_contact_person = '';
$scope.purchaseForm.mobile = '';
}
else {
var len = $scope.projects.length;
for(var i=0; i<len; i++)
{
if($scope.purchaseForm.sis_project_name == $scope.projects[i].project_name && x == $scope.projects[i].block_name)
{
$scope.purchaseForm.site_contact_person = $scope.projects[i].contact_name;
$scope.purchaseForm.mobile = $scope.projects[i].mobile_no;
break;
}
}
} 
};

//row calculation
$scope.rowcalc = function(x, index) {
if(!x.quantity){ x.quantity = ''; }
if(!x.price){ x.price = ''; }
if(!x.gst){ x.gst = ''; }
$scope.names[index].amount = (x.quantity * x.price).toFixed(2);
$scope.names[index].gst_amount = ((x.gst)/100 * x.amount).toFixed(2);
$scope.names[index].total = ((x.amount * 1) + (x.gst_amount * 1)).toFixed(2);
$scope.calculateSum();
}

//door calculation
$scope.doorcalc = function(x, index){
if(x.size != null) {
var total = orderService.doorcalculation(x.size);
if(x.quantity==null) {
quantity = 1;  
}
else {
quantity = x.quantity;
}
$scope.names[index].cft = (total * quantity).toFixed(3);
$scope.names[index].amount = x.cft * x.price;
$scope.names[index].gst_amount = (x.gst)/100 * x.amount;
$scope.names[index].total = x.amount + x.gst_amount;
}
else {
$scope.names[index].cft = '';
$scope.names[index].amount = '';
$scope.names[index].gst_amount = '';
$scope.names[index].total = '';
}
if(!x.price){ x.price = ''; }
if(!x.gst){ x.gst = ''; }
if(!x.cft){ x.cft = ''; }
$scope.names[index].amount = (x.cft * x.price).toFixed(2);
$scope.names[index].gst_amount = ((x.gst)/100 * x.amount).toFixed(2);
$scope.names[index].total = ((x.amount * 1) + (x.gst_amount * 1)).toFixed(2);
$scope.calculateSum();
};

//grand total calculation
$scope.calculateSum = function(){
var sum = 0;
var sum_cft = 0
angular.forEach($scope.names,function(tableForm){
sum += tableForm.total * 1;
if(tableForm.cft)
{
sum_cft += tableForm.cft * 1;	
}				
});            
if(!$scope.purchaseForm.trans_tax) { $scope.purchaseForm.trans_tax=''; }
if(!$scope.purchaseForm.trans_amount) { $scope.purchaseForm.trans_amount=''; }
if(!$scope.purchaseForm.plain_tax) { $scope.purchaseForm.plain_tax=''; }
if(!$scope.purchaseForm.plain_amount) { $scope.purchaseForm.plain_amount=''; }

if($scope.names[0].total) { $scope.purchaseForm.sub_total = (sum).toFixed(2); }
else{ $scope.purchaseForm.sub_total = '0'; }
if($scope.names[0].cft) { $scope.purchaseForm.sum_cft =  sum_cft; }
else{ $scope.purchaseForm.sum_cft = '0'; }


var plaincharge =  sum_cft * $scope.purchaseForm.plain_amount;

$scope.purchaseForm.grand_total = ((($scope.purchaseForm.trans_tax)/100 * $scope.purchaseForm.trans_amount)+($scope.purchaseForm.trans_amount * 1) + (($scope.purchaseForm.plain_tax)/100 * plaincharge) + (plaincharge * 1) + ($scope.purchaseForm.sub_total * 1)).toFixed(2);
};

//add new row
$scope.addNew = function(){
$scope.names.push({ 'item': "" });
};


//remove existing row
$scope.remove = function(){
var newDataList=[];
$scope.selectedAll = false;
angular.forEach($scope.names, function(selected){
if(!selected.singlecheck){
newDataList.push(selected);
}
});

if(newDataList==''){
$scope.purchaseForm.trans_amount='0';
$scope.purchaseForm.trans_tax='0';
$scope.names = [{ 'item': "" }];
}
else{
$scope.names = newDataList;
}
$scope.checkedAll = '';
$scope.calculateSum();
};


//check all
$scope.selectAll = function () {
$scope.names.forEach(function (v) {
v.singlecheck = $scope.checkedAll;
});
};

//change site contact person
$scope.onPersonValidate = function (x) {
if(!x){ $scope.purchaseForm.site_contact_person = ''; }
};


$scope.querySearch = function(query) {
$scope.results = query ?  $scope.maildatas.filter($scope.createFilterFor(query)) : [];
console.log("Result",$scope.results);
return $scope.results;
}

$scope.createFilterFor = function(query) {
var lowercaseQuery = angular.lowercase(query);
return $scope.filterFn = function(mail_id) {
return (mail_id._lowername.indexOf(lowercaseQuery) === 0);
};
}

$scope.mailContent = function(){
$scope.readonly = false;
$scope.selectedItem = null;
$scope.searchText = null;
$scope.querySearch= $scope.querySearch;               
$scope.bcc_mail = [];
$scope.mailForm = {};
$scope.mailForm.subject = $scope.purchaseForm.sis_project_name+' - Purchase order';
document.getElementById('editor').innerHTML = '<b>Dear '+$scope.purchaseForm.contact_person+',</b><br><br>We are pleased to place the purchase order as per the details mentioned in the attachment for our project <b>'+$scope.purchaseForm.sis_project_name+' at '+$scope.purchaseForm.supply_place+'.</b>';
$http({
url: './v1/getmailid',
method: "POST",
data: {'emp_id': localStorage.getItem("user_id")}
})
.then(function (response){	
$('#mailModal').modal('show');
$scope.mailForm.from_mail = response.data.mail_id;
$scope.mail_list = response.data.user_mail;
$scope.loadMailids(response.data.user_mail)
$scope.maildatas = $scope.loadMailids(response.data.user_mail)
});
};
    

$scope.loadMailids = function(data) {
$scope.mailids = data;
console.log(data)
return $scope.mailids.map(function (mailid) {
mailid._lowername = mailid.mail_id.toLowerCase();
//console.log("1", mailid)
return mailid;
});

}


//save
$scope.onSubmit = function(){
$scope.tableItems = [];
angular.forEach($scope.names,function(tableForm){
$scope.tableItems.push({ code: tableForm.code, item: tableForm.item,desc: tableForm.desc, unit: tableForm.unit, quantity: tableForm.quantity, price: tableForm.price, gst: tableForm.gst, details: tableForm.details, make: tableForm.make, width: tableForm.width, height: tableForm.height, upvc_type: tableForm.upvc_type, size: tableForm.size, cft: tableForm.cft });
});
$scope.purchaseForm.jsonArrayData = JSON.stringify($scope.tableItems);			
$scope.purchaseForm.emp_id = localStorage.getItem("user_id");
$scope.purchaseForm.to_mail = '';
$http({
url: './v1/newpurchase',
method: "POST",
data: $scope.purchaseForm
})
.then(function(response) {
if(response.data.code == 'Success'){
$state.go('home.orders', { filter: splitData[0]+'_'+splitData[1]+'_'+splitData[2]+'_'+splitData[3]+'_'+splitData[4] });
}
else{
$scope.alert = true;
}
});
};

//send
$scope.onSend = function(){
$scope.tableItems = [];
angular.forEach($scope.names,function(tableForm){
$scope.tableItems.push({ code: tableForm.code, item: tableForm.item, unit: tableForm.unit, quantity: tableForm.quantity, price: tableForm.price, gst: tableForm.gst, details: tableForm.details, make: tableForm.make, width: tableForm.width, height: tableForm.height, upvc_type: tableForm.upvc_type, size: tableForm.size, cft: tableForm.cft });
});
$scope.purchaseForm.jsonArrayData = JSON.stringify($scope.tableItems);
$scope.purchaseForm.emp_id = localStorage.getItem("user_id");
var to_id = $scope.to_mail;
var cc_id = $scope.cc_mail;
var bcc_id = $scope.bcc_mail;
if($scope.to_mail)
{
for(i=0 ; i<to_id.length ; i++)
{					
if(i === 0)
{
$scope.mailForm.to_mail= to_id[i].mail_id
}
else
{
$scope.mailForm.to_mail += ","+to_id[i].mail_id
}
console.log("toi", to_id[i].mail_id );
}	
}

if($scope.cc_mail)
{
for(i=0 ; i<cc_id.length ; i++)
{					
if(i === 0)
{
$scope.mailForm.cc_mail= cc_id[i].mail_id
}
else
{
$scope.mailForm.cc_mail += ","+cc_id[i].mail_id
}
console.log("cci", cc_id[i].mail_id );
}	
}
if($scope.bcc_mail)
{
for(i=0 ; i<bcc_id.length ; i++)
{					
if(i === 0)
{
$scope.mailForm.bcc_mail= bcc_id[i].mail_id
}
else
{
$scope.mailForm.bcc_mail += ","+bcc_id[i].mail_id
}
console.log("bcci", bcc_id[i].mail_id );

}	
}
console.log("Mailform",$scope.mailForm)
$scope.purchaseForm.to_mail = $scope.mailForm.to_mail;
$scope.purchaseForm.cc_mail = $scope.mailForm.cc_mail;
$scope.purchaseForm.bcc_mail = $scope.mailForm.bcc_mail;
$scope.purchaseForm.subject = $scope.mailForm.subject;
$scope.purchaseForm.content = $("#editor").html();
$('#sendLoader').addClass('fa fa-circle-o-notch fa-spin');
$('#send, #cancel').prop('disabled', true);
$http({
url: './v1/newpurchase',
method: "POST",
data: $scope.purchaseForm
})
.then(function(response) {
$('#mailModal').modal('hide');
if(response.data.code == 'Success'){
$timeout(function() { 
$state.go('home.orders', { filter: splitData[0]+'_'+splitData[1]+'_'+splitData[2]+'_'+splitData[3]+'_'+splitData[4] });
}, 500);
}
else{
$scope.alert = true;
}
$('#send, #cancel').prop('disabled', false);
$('#sendLoader').removeClass('fa fa-circle-o-notch fa-spin');
});
};
}
})