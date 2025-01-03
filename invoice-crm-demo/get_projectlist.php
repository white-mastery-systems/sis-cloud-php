<?php

$tinno;
$cstno;
$address;
$contactname;
$gstno = "";
$project_name=$_GET['project_name'];
 
 // $company=$_REQUEST['company'];
require('connect.php');

 $result =mysql_query("SELECT * FROM  project_details  WHERE project_name = '" .$project_name. "'", $conn);
if($row = mysql_fetch_array($result))
 {  
    $project_id = $row['project_id'];
    $project_name = $row['project_name'];
    $place = $row['place'];
    $tinno = $row['tinno'];
    $cstno = $row['cstno'];
    $gstno = $row['gstin'];
    $address = $row['address'];
	$contactname	= $row['contactname'];
	$mobilno = $row['mobilno'];
	$payment = $row['payment'];
	$city = $row['city'];         
			  
    $returnXML = $project_id. '|' .$project_name. '|' .$place. '|' .$tinno. '|' .$cstno. '|' .$address. '|' .$contactname. '|' .$mobilno. '|' .$payment.'|'.$city.'|'.$gstno;
	 echo $returnXML;
	
  }
  else
{
	 $returnXML = "empty";
}

?>