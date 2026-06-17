<?php
ini_set('max_execution_time', 1800);    // 30 minutes

require_once '../include/db_handler.php';
require_once '../libs/Slim/Slim.php';

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

/***** LOGIN *****/

$app->get('/testmailer', function() {
    $db = new DbHandler();	
    $result = $db->testmailer();
    echo json_encode($result);
});

$app->post('/userlogin', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->userlogin($postData);
    echo json_encode($result);
});

/***** SIS PROJECT *****/

$app->get('/getsisprojects', function() {
    $db = new DbHandler();	
    $result = $db->getsisprojects();
    echo json_encode($result);
});

$app->post('/addsisproject', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->addsisproject($postData);
    echo json_encode($result);
});

$app->post('/editsisproject', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->editsisproject($postData);
    echo json_encode($result);
});

$app->post('/updatesisproject', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->updatesisproject($postData);
    echo json_encode($result);
});

$app->post('/delsisproject', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->delsisproject($postData);
    echo json_encode($result);
});

/***** VENDOR *****/

$app->post('/getvendorlist', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->getvendorlist($postData);
    echo json_encode($result);
});

$app->post('/addpovendor', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->addpovendor($postData);
    echo json_encode($result);
});

$app->post('/editpovendor', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->editpovendor($postData);
    echo json_encode($result);
});

$app->post('/updatepovendor', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->updatepovendor($postData);
    echo json_encode($result);
});

$app->post('/deletevendor', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->deletevendor($postData);
    echo json_encode($result);
});

$app->post('/deleteallvendors', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->deleteallvendors($postData);
    echo json_encode($result);
});

/***** PRODUCT *****/

$app->post('/getproductdetails', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->getproductdetails($postData);
    echo json_encode($result);
});
$app->post('/getsingleproductdetails', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->getsingleproductdetails($postData);
    echo json_encode($result);
});

$app->post('/addcategory', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->addcategory($postData);
    echo json_encode($result);
});

$app->post('/getproductcode', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->getproductcode($postData);
    echo json_encode($result);
});

$app->post('/addpoproduct', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->addpoproduct($postData);
    echo json_encode($result);
});

$app->post('/editpoproduct', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->editpoproduct($postData);
    echo json_encode($result);
});

$app->post('/updatepoproduct', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->updatepoproduct($postData);
    echo json_encode($result);
});

$app->post('/deleteproduct', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->deleteproduct($postData);
    echo json_encode($result);
});

$app->post('/deleteallproducts', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->deleteallproducts($postData);
    echo json_encode($result);
});

/***** INDENT *****/

$app->post('/getindentlistPaginated', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->getindentlistPaginated($postData);
    echo json_encode($result);
});

$app->post('/getindentlist', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->getindentlist($postData);
    echo json_encode($result);
});

$app->post('/indentdetailedview', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->indentdetailedview($postData);
    echo json_encode($result);
});

$app->post('/getprfnumber', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->getprfnumber($postData);
    echo json_encode($result);
});

$app->post('/getindentdetails', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->getindentdetails($postData);
    echo json_encode($result);
});

$app->post('/newindent', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->newindent($postData);
    echo json_encode($result);
});

$app->post('/viewpurchaseindent', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->viewpurchaseindent($postData);
    echo json_encode($result);
});

$app->post('/editpurchaseindent', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->editpurchaseindent($postData);
    echo json_encode($result);
});

$app->post('/updatepurchaseindent', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->updatepurchaseindent($postData);
    echo json_encode($result);
});

$app->post('/getindentmailcontent', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->getindentmailcontent($postData);
    echo json_encode($result);
});

$app->post('/sendindentmail', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->sendindentmail($postData);
    echo json_encode($result);
});

$app->post('/deleteindent', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->deleteindent($postData);
    echo json_encode($result);
});

$app->post('/deleteallindent', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->deleteallindent($postData);
    echo json_encode($result);
});

/***** QUOTATION *****/

$app->post('/getquotationdetails', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->getquotationdetails($postData);
    echo json_encode($result);
});

$app->post('/getitemstock', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->getitemstock($postData);
    echo json_encode($result);
});

$app->post('/newquotation', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->newquotation($postData);
    echo json_encode($result);
});

$app->post('/deletequotation', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->deletequotation($postData);
    echo json_encode($result);
});

$app->post('/getquotationlist', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->getquotationlist($postData);
    echo json_encode($result);
});

$app->post('/viewpurchasequotation', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->viewpurchasequotation($postData);
    echo json_encode($result);
});

$app->post('/editpurchasequotation', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->editpurchasequotation($postData);
    echo json_encode($result);
});

$app->post('/updatepurchasequotation', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->updatepurchasequotation($postData);
    echo json_encode($result);
});

$app->post('/updateandapprovequotation', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->updateandapprovequotation($postData);
    echo json_encode($result);
});

$app->post('/getquotationmailcontent', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->getquotationmailcontent($postData);
    echo json_encode($result);
});

$app->post('/sendquotationmail', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->sendquotationmail($postData);
    echo json_encode($result);
});

$app->post('/approvequotation', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->approvequotation($postData);
    echo json_encode($result);
});

$app->post('/pofromquotation', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->pofromquotation($postData);
    echo json_encode($result);
});

/***** QUOTATION -> Admin *****/

$app->post('/admineditquotation', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->admineditquotation($postData);
    echo json_encode($result);
});

/***** PURCHASE ORDER *****/

$app->post('/getorderlistPaginated', function() use ($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();
    $result = $db->getorderlistPaginated($postData);    
    echo json_encode($result);
});

$app->post('/getorderlist', function() use ($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();
    $result = $db->getorderlist($postData);    
    echo json_encode($result);
});

$app->post('/getpomailcontent', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->getpomailcontent($postData);
    echo json_encode($result);
});

$app->post('/getmailid', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->getmailid($postData);
    echo json_encode($result);
});

$app->post('/getprojectdetails', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->getprojectdetails($postData);
    echo json_encode($result);
});

$app->post('/getproductlist', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->getproductlist($postData);
    echo json_encode($result);
});

$app->post('/getponumber', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->getponumber($postData);
    echo json_encode($result);
});

$app->post('/newpurchase', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->newpurchase($postData);
    echo json_encode($result);
});

$app->post('/viewpurchaseorder', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->viewpurchaseorder($postData);
    echo json_encode($result);
});

$app->post('/editpurchaseorder', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->editpurchaseorder($postData);
    echo json_encode($result);
});

$app->post('/updatepurchaseorder', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->updatepurchaseorder($postData);
    echo json_encode($result);
});

$app->post('/sendpomail', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->sendpomail($postData);
    echo json_encode($result);
});

$app->post('/deleteorder', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->deleteorder($postData);
    echo json_encode($result);
});

$app->post('/deleteallorders', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->deleteallorders($postData);
    echo json_encode($result);
});

/***** INVENTORY *****/

$app->post('/getinventorylist', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->getinventorylist($postData);
    echo json_encode($result);
});

$app->post('/getstockdetails', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->getstockdetails($postData);
    echo json_encode($result);
});

$app->post('/inventorydetailedview', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->inventorydetailedview($postData);
    echo json_encode($result);
});

$app->post('/inventory_detailupdate', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->inventory_detailupdate($postData);
    echo json_encode($result);
});



$app->post('/stockupdate_in', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->stockupdate_in($postData);
    echo json_encode($result);
});

$app->post('/stockupdate_use', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->stockupdate_use($postData);
    echo json_encode($result);
});

$app->post('/stockuse_balanceupdate', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->stockuse_balanceupdate($postData);
    echo json_encode($result);
});

$app->post('/getinventoryuselist', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->getinventoryuselist($postData);
    echo json_encode($result);
});

$app->post('/inventoryusedetailedview', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->inventoryusedetailedview($postData);
    echo json_encode($result);
});

$app->post('/get_flatlist', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->get_flatlist($postData);
    echo json_encode($result);
});

$app->get('/internalcategorylist', function() {
    $db = new DbHandler();	
    $result = $db->internalcategorylist();
    echo json_encode($result);
});

/***** USER LIST *****/

$app->get('/userlistfilter', function() {
    $db = new DbHandler();	
    $result = $db->userlistfilter();
    echo json_encode($result);
});

$app->post('/userlist', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->userlist($postData);
    echo json_encode($result);
});

$app->post('/userdetails', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->userdetails($postData);
    echo json_encode($result);
});

$app->post('/userdelete', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->userdelete($postData);
    echo json_encode($result);
});

$app->post('/deleteallusers', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->deleteallusers($postData);
    echo json_encode($result);
});

$app->post('/leaveyear', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->leaveyear($postData);
    echo json_encode($result);
});

$app->post('/leaveinsert', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->leaveinsert($postData);
    echo json_encode($result);
});

$app->get('/adduserdetails', function() {
    $db = new DbHandler();	
    $result = $db->adduserdetails();
    echo json_encode($result);
});

$app->post('/usertransfer', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->usertransfer($postData);
    echo json_encode($result);
});

$app->post('/permissiondetails', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->permissiondetails($postData);
    echo json_encode($result);
});

$app->post('/permissionupdate', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->permissionupdate($postData);
    echo json_encode($result);
});

$app->post('/holidaycalc', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->holidaycalc($postData);
    echo json_encode($result);
});

$app->post('/changepwd', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->changepwd($postData);
    echo json_encode($result);
});

/***** SALARY LIST *****/

$app->post('/salarylist', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->salarylist($postData);
    echo json_encode($result);
});

$app->post('/salarydetails', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->salarydetails($postData);
    echo json_encode($result);
});

$app->post('/salaryupdate', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->salaryupdate($postData);
    echo json_encode($result);
});

$app->get('/getdirectorlist', function() {
    $db = new DbHandler();	
    $result = $db->getdirectorlist();
    echo json_encode($result);
});

$app->post('/salaryhistory', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();	
    $result = $db->salaryhistory($postData);
    echo json_encode($result);
});

$app->get('/get_annualsales', function() {
    $db = new DbHandler();  
    $result = $db->get_annualsales();
    echo json_encode($result);
});

$app->post('/getbillinglist', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();  
    $result = $db->getbillinglist($postData);
    echo json_encode($result);
});

$app->post('/getbillinglistPaginated', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();  
    $result = $db->getbillinglistPaginated($postData);
    echo json_encode($result);
});

$app->post('/getBillingVendorList', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();  
    $result = $db->getBillingVendorList($postData);
    echo json_encode($result);
});

$app->post('/getVendorPoList', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();  
    $result = $db->getVendorPoList($postData);
    echo json_encode($result);
});

$app->post('/billingPoDetails', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();  
    $result = $db->billingPoDetails($postData);
    echo json_encode($result);
});

$app->post('/generatePurchaseBill', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();  
    $result = $db->generatePurchaseBill($postData);
    echo json_encode($result);
});

$app->post('/viewpurchasebill', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();  
    $result = $db->viewpurchasebill($postData);
    echo json_encode($result);
});

$app->post('/confirmpurchasebill', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();  
    $result = $db->confirmpurchasebill($postData);
    echo json_encode($result);
});

$app->post('/deletepurchasebill', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();  
    $result = $db->deletepurchasebill($postData);
    echo json_encode($result);
});

$app->post('/billcheckout', function() use($app) {
    $postData = $app->request->getBody();
    $db = new DbHandler();  
    $result = $db->billcheckout($postData);
    echo json_encode($result);
});

$app->run();
?>