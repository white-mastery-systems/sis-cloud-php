<?php
require_once '../../vendor/autoload.php';
include "../../include/conn.php";
header('Content-Encoding: UTF-8');
header('Content-type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename=user.csv');
echo "\xEF\xBB\xBF";

$collection =$db->user_list;

$status = $_GET['status'];
$site = $_GET['site'];
$department = $_GET['department'];

$delimiter = ',';

if($status!='All' && $site!='All'  && $department!='All'){
    $cursor = $collection->aggregate(array(
        array( '$match' => array(
            "status" => $status,
            "site" =>  $site,
            "department" => $department
        )),
        array( '$sort' => array( 
            'emp_id' => -1
        ))
    ));
}
else if($status =="All" && $site =='All'  && $department!='All'){
	 $cursor = $collection->aggregate(array(
        array( '$match' => array(
            "department" => $department
        )),
        array( '$sort' => array( 
            'emp_id' => -1
        ))
    ));
}
else if($status =="All" && $site!='All'  && $department =='All'){
    $cursor = $collection->aggregate(array(
        array( '$match' => array(
            "site" =>  $site
        )),
        array( '$sort' => array( 
            'emp_id' => -1
        ))
    ));
}
else if($status =="All" && $site!='All'  && $department!='All'){
    $cursor = $collection->aggregate(array(
        array( '$match' => array(
            "site" =>  $site,
            "department" => $department
        )),
        array( '$sort' => array( 
            'emp_id' => -1
        ))
    ));
}
else if($status =='Active' && $site!='All'  && $department=='All'){
    $cursor = $collection->aggregate(array(
        array( '$match' => array(
            "status" => $status,
            "site" =>  $site
        )),
        array( '$sort' => array( 
            'emp_id' => -1
        ))
    ));
}
else if($status =='Active' && $site =='All'  && $department!='All'){
    $cursor = $collection->aggregate(array(
        array( '$match' => array(
            "status" => $status,
            "department" => $department
        )),
        array( '$sort' => array( 
            'emp_id' => -1
        ))
    ));
} 
else if($status!='Active' && $site =='All'  && $department!='All'){
    $cursor = $collection->aggregate(array(
        array( '$match' => array(
            "status" => $status,
            "department" => $department
        )),
        array( '$sort' => array( 
            'emp_id' => -1
        ))
    ));
}
else if($status!='Active' && $site!='All'  && $department =='All'){
    $cursor = $collection->aggregate(array(
        array( '$match' => array(
            "status" => $status,
            "site" =>  $site
        )),
        array( '$sort' => array( 
            'emp_id' => -1
        ))
    ));
}	
else if($status!='Active' && $site =='All'  && $department!='All'){
    $cursor = $collection->aggregate(array(
        array( '$match' => array(
            "site" =>  $site
        )),
        array( '$sort' => array( 
            'emp_id' => -1
        ))
    ));
}	
else if($status!='Active' && $site =='All'  && $department =='All'){
    $cursor = $collection->aggregate(array(
        array( '$match' => array(
            "status" => $status
        )),
        array( '$sort' => array( 
            'emp_id' => -1
        ))
    ));
}
else{
	$cursor = $collection->aggregate(array(
        array( '$match' => array(
            "status" => "Active"
        )),
        array( '$sort' => array( 
            'emp_id' => -1
        ))
    ));
}
		 	 
echo 'Surname,FirstName,LastName, Designation, Department,Dob,Bloodgroup, Doj, Address, Mobile,Official-no,Site, Empno,Relationship,Emergency_connno,Personnel_Email,Official_Email,empsalary, experiencein our company,exp in othercompany, totalno of experience, qualification,Status'."\n";

foreach($cursor as $cur)
{
	$array=$cur['address'];
    
    $diff = strtotime(date('Y-m-d')) - strtotime($cur['doj']);
				
    $years = floor($diff / (365*60*60*24));
    $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
    $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
    $exp = ''.$years.':years.'.$months.':months';

    $compexp=strtotime(($cur['companyexperience']),0);
    $diff2 = $diff + $compexp;
    $year2 = floor($diff2 / (365*60*60*24));
    $month2 = floor(($diff2 - $year2 * 365*60*60*24) / (30*60*60*24));
    $day2 = floor(($diff2 - $year2 * 365*60*60*24 - $month2*30*60*60*24)/ (60*60*24));
    $exp2 = ''.$year2.'.'.$month2.':months';

    echo $cur["sirname"].', '.$cur["firstname"].','.$cur["lastname"].','.$cur["designation"].', '.$cur["department"].', '.$cur["dob"].', '.$cur['bloodgroup'].', '.$cur['doj'].',"'.$array.'",'.$cur['mobile'].','.$cur['officeno'].','.$cur['site'].','.$cur['emp_id'].','.$cur['relationship'].','.$cur['emergency_conno'].','.$cur['personnel_email'].', '.$cur['official_email'].', '.$cur['empsalary'].',  '.$exp.','.$cur['companyexperience'].','.$exp2.',  '.$cur['qualification'].', '.$cur['status']."\n";
}
?>