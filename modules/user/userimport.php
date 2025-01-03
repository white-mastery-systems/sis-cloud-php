<?php
require_once '../../vendor/autoload.php';
include "../../include/conn.php";
date_default_timezone_set("Asia/Kolkata");

$collectionUser =$db->user_list;
$collectionSign = $db->signintable;
$collectionTransfer = $db->transfer_history;
$collectionPerm = $db->permissiontable;
$i=0;

if(!empty($_FILES["employee_file"]["name"]))
{
    $imageupload = $_FILES["employee_file"]["tmp_name"];
    //$imageupload = 'user.csv';
    $handle = fopen($imageupload,"r"); 
    while(($arr = fgetcsv($handle, 10000, ";")) !== FALSE)
    {
        if($i>0)
        {
            $sirName = trim($arr[0]);
            $firstName = trim($arr[1]);
            $lastName = trim($arr[2]);
            
            if($lastName!='') { $name = $firstName.' .'.trim($arr[2]); }
            else { $name = $firstName; }
            
            $designation = trim($arr[4]);
            $department = trim($arr[5]);
            
            if($arr[6]!='') { $dob = date("d-m-Y", strtotime(trim($arr[6]))); }
            else { $dob = ''; }
            if($arr[8]!='') { $doj = date("d-m-Y", strtotime(trim($arr[8]))); }
            else { $doj = ''; }
            
            $bloodGrp = trim($arr[7]);
            $address = trim($arr[9]);
            $mobile = trim($arr[10]);
            $site = trim($arr[11]);
            $rShip = trim($arr[13]);
            $emergencyNo = trim($arr[14]);
            $personelEmail = trim($arr[15]);
            $email = trim($arr[16]);
            $empSalary = trim($arr[17]);
            $qualification = trim($arr[18]);
            $officeNo = trim($arr[19]);
            $companyExp = trim($arr[20]);
            $empId = (int)trim($arr[12]);
            
            if($arr[21]!='') { $status = trim($arr[21]); }
            else { $status = 'Resigned'; }
            
            $cursor = $collectionUser->findOne(array("emp_id" => $empId));
            if($cursor)
            {
                $collectionUser->updateOne(
                    array("emp_id" => $empId),
                    array('$set' => array(
                        "sirname" => $sirName, 
                        "firstname" => $firstName, 
                        "lastname" => $lastName, 
                        "name" => $name, 
                        "designation" => $designation, 
                        "department" => $department,
                        "dob" => $dob,
				        "bloodgroup" => $bloodGrp,
				        "doj" => $doj,
				        "address" => $address,
				        "mobile" => $mobile,
				        "site" => $site,
				        "relationship" => $rShip,
				        "emergency_conno" => $emergencyNo,
				        "personnel_email" => $personelEmail,
				        "email" => $email,
				        "empsalary" => $empSalary,
				        "qualification" => $qualification,
				        "officeno" => $officeNo,
				        "companyexperience" => $companyExp,
				        "status" => $status
                    ))
                );
                
                $collectionSign->updateOne(
                    array("emp_id" => $empId),
                    array('$set' => array(
                        "name" => $name, 
                        "mobile" => $officeNo,
                        "designation" => $designation,
                        "department" => $department,
                        "site" => $site,
                        "mail_id"=> $email
                    ))
                );
            }
            else
            {
                $collectionUser->insertOne(array(
                    "emp_id" => $empId,
                    "sirname" => $sirName, 
                    "firstname" => $firstName, 
                    "lastname" => $lastName, 
                    "name" => $name, 
                    "designation" => $designation, 
                    "department" => $department,
                    "dob" => $dob,
				    "bloodgroup" => $bloodGrp,
				    "doj" => $doj,
				    "address" => $address,
				    "mobile" => $mobile,
				    "site" => $site,
				    "relationship" => $rShip,
				    "emergency_conno" => $emergencyNo,
				    "personnel_email" => $personelEmail,
				    "email" => $email,
				    "empsalary" => $empSalary,
				    "qualification" => $qualification,
				    "officeno" => $officeNo,
				    "companyexperience" => $companyExp,
				    "status" => $status,
                    "profileimage" => 'images/common_profile.png'
                ));
               
                $collectionSign->insertOne(array(
                    "emp_id" => $empId,
                    "name" => $name, 
                    "mobile" => $officeNo,
                    "designation" => $designation,
                    "department" => $department,
                    "site" => $site,
                    "mail_id"=> $email,
                    "password" => "welcome2sis"
                ));
                
                //transfer history
                if($doj!='') {
                    $transferDate = $doj;
                    $transferDateFormat = date("d F Y", strtotime(trim($doj)));
                }
                else {
                    if($status=='Active') {
                        $transferDate = date("d-m-Y");
                        $transferDateFormat = date("d F Y");
                    }
                    else {
                        $transferDate = '';
                        $transferDateFormat = '';
                    }
                }
                
                $collectionTransfer->insertOne(array(
                    "emp_id" => $empId, 
                    "transfer_date" => $transferDate,
                    "transfer_dateformat" => $transferDateFormat,
                    "transfer_site" => $site,
                    "block" => '',
                    "transfer_duration" => ""
                ));
                
                //permission table
                $collectionPerm->insertOne(array(
                    "emp_id" => $empId
                ));
            }
        }
        $i++;
    }
    fclose($handle);
	echo "File Uploaded successfully !";
}
?>