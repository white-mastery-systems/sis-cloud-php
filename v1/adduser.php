<?php
error_reporting(0);
if($_POST['addData'])
{
    require_once '../vendor/autoload.php';
    include "../include/conn.php";
    
    $collectionUser = $db->user_list;
    $collectionSign = $db->signintable;
    $collectionPerm = $db->permissiontable;
    $collectionTransfer = $db->transfer_history;
    $collectionSal = $db->salary_list;
    $collectionSalHistory = $db->salary_history;
    
    $jsonData = json_decode($_POST['addData'], true);
    
    //emp id
    $cursor = $collectionUser ->aggregate(array(
        array( '$sort' => array( 
            'emp_id' => -1
        )),
        array('$limit' => 1)
    ));
    if($cursor)
    {
        foreach($cursor as $rowInvData)
        {
            $empId = $rowInvData['emp_id']+1;
        }
        if(!$empId){ $empId = 1001; }
    }
    
    if(!empty($_FILES['files'])) {
		$ext = pathinfo($_FILES['files']['name'], PATHINFO_EXTENSION);
        $profileimage = $empId.'.'.$ext;
		$path = 'modules/uploads/profile/'.$profileimage;
        $shortPath = '../'.$path;
        move_uploaded_file($_FILES["files"]["tmp_name"], $shortPath);
	}
    else {
        $path = 'images/common_profile.png';
    }
	
   	$collectionUser->insertOne(array(
        "sirname" => $jsonData['sirname'], 
        "firstname" => $jsonData['firstname'],
        "lastname" => $jsonData['lastname'],
        "name" => $jsonData['firstname'].$jsonData['lastname'], 
		"designation" => $jsonData['designation'],
        "department" => $jsonData['department'],
        "dob" => $jsonData['dob'],
		"bloodgroup" => $jsonData['bloodgroup'],
		"doj" => $jsonData['doj'],
		"address" => $jsonData['address'],
	    "mobile" => $jsonData['mobile'],
        "site" => $jsonData['site'],
        "emp_id" => (int)$empId,
        "relationship" => $jsonData['relationship'],
        "emergency_conno" => $jsonData['emergency_conno'],
		"personnel_email" => $jsonData['personnel_email'],
		"email" => $jsonData['email'],
		"empsalary" => $jsonData['empsalary'],
        "qualification" => $jsonData['qualification'],
		"officeno" => $jsonData['officeno'],
		"companyexperience" => $jsonData['companyexperience'],
		"status" => 'Active',
        "dateinserted" => date("d-m-Y"),
        "profileimage" => $path,
        "profilepath" => $shortPath
    ));
    
    $collectionSign->insertOne(array(
        "emp_id" => (int)$empId, 
        "name" => $jsonData['firstname'], 
        "mobile" => $jsonData['officeno'],
        "designation" => $jsonData['designation'],
        "department" => $jsonData['department'],
        "username" => $jsonData['firstname'].$empId,
        "password" => "welcome2sis",
        "site" => $jsonData['site'],
        "mail_id"=>$jsonData['email']
    ));
    
    $gross = (int)$jsonData['empsalary'];
    $basicpay = (int)($gross *60)/100;
    $hra = ($gross *20)/100;
    $conveyance = ($gross *10)/100;
    $flexbileplan = ($gross *10)/100;
    $total_amount = $basicpay + $hra + $conveyance + $flexbileplan;
    if($gross > 15000) { $pf = 1800; }
    else { $pf = ($gross *12)/100; }
    $totalearnedamount = $total_amount;
     
    //salary data
    $collectionSal->insertOne(array(
        "emp_id" => (int)$empId, 
        "location" => $jsonData['site'],
        "gross" => $gross,
        "basicpay" => $basicpay,
        "hra" => $hra,
        "conveyance" => $conveyance,
        "flexbileplan" => $flexbileplan,
        "total_amount" => $total_amount,
        "totalearnedamount" => $totalearnedamount,
        "ndw" => 31,
        "user_status" => 'Active' 
    ));
    
    //salary history
    $collectionSalHistory->insertOne(array(
        "emp_id" => (int)$empId, 
        "location" => $jsonData['site'],
        "grosspay" => (int)$jsonData['empsalary'],
        "status" => "intialsalary",
        "credited" => date("d-m-Y"),
        "inc" => "0%"
    ));
    
    //transfer history
    $collectionTransfer->insertOne(array(
        "emp_id" => (int)$empId, 
        "transfer_date" => date("d-m-Y"),
        "transfer_dateformat" => date("d F Y"),
        "transfer_site" => $jsonData['site'],
        "block" => '',
        "transfer_duration" => ""
    ));
    
    //permission table
    $collectionPerm->insertOne(array(
        "emp_id" => (int)$empId
    ));
    
    echo "Success";
}
?>