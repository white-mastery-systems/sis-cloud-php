<?php
if($_POST['user_data'])
{
    require_once '../vendor/autoload.php';
    include "../include/conn.php";
    
    $collectionUser = $db->user_list;
    $collectionSign = $db->signintable;
    
    $jsonData = json_decode($_POST['user_data'], true);
    
    if($jsonData["lastname"]!=''){ $lastName = ' .'.$jsonData["lastname"]; }
    else { $lastName = ''; }
    
    $collectionUser->updateOne(
        array("emp_id" => (int)$jsonData["emp_id"]),
        array('$set' => array(
            "firstname" => $jsonData["firstname"],
            "lastname" => $jsonData["lastname"],
            "name" => $jsonData["firstname"].$lastName,
            "designation" => $jsonData["designation"],
            "department" => $jsonData["department"],
            "dob" =>$jsonData["dob"],
            "bloodgroup" => $jsonData["bloodgroup"],
            "doj" => $jsonData["doj"],
            "address" => $jsonData["address"],
            "mobile" => $jsonData["mobile"],
            "site" => $jsonData["site"],
            "emergency_conno" => $jsonData["emergency_conno"],
            "personnel_email" => $jsonData["personnel_email"],
            "email" => $jsonData["email"],
            "qualification" => $jsonData["qualification"],
            "officeno" => $jsonData["officeno"],
            "status" => $jsonData["status"],
            "resigned_date" => $jsonData["resigned_date"]
        ))
    );
    
    if(!empty($_FILES['files'])) 
    {
        $userData = $collectionUser->findOne(array("emp_id" => (int)$jsonData["emp_id"]));
        if($userData)
        {
            if($userData['profilepath']) { unlink($userData['profilepath']); }
            
            $ext = pathinfo($_FILES['files']['name'], PATHINFO_EXTENSION);
            $profileimage = $jsonData["emp_id"].'.'.$ext;
            $path = 'modules/uploads/profile/'.$profileimage;
            $shortPath = '../'.$path;
            move_uploaded_file($_FILES["files"]["tmp_name"], $shortPath);

             $collectionUser->updateOne(
                array("emp_id" => (int)$jsonData["emp_id"]),
                array('$set' => array(
                    "profileimage" => $path,
                    "profilepath" => $shortPath
                ))
            );
        }
	}
    
    $collectionSign->updateOne(
        array("emp_id" => (int)$jsonData["emp_id"]),
        array('$set' => array(
            "name"=>$jsonData["firstname"].$lastName,
            "mobile" => $jsonData["officeno"],
            "mail_id" => $jsonData["email"],
			 "designation" => $jsonData["designation"],
            "department" => $jsonData["department"],		   
            "site" => $jsonData["site"],
			"mail_id" => $jsonData["email"],
			"status" => $jsonData["status"],
			"inactivedate" => $jsonData["resigned_date"]
        ))
    );
    
    echo "Success";
}
?>