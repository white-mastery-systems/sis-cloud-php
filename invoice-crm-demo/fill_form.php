<?php
$ven_add1;
$ven_add2;
$ven_city;
$ven_contactperson;
$ven_mob;
 $company=$_GET['company'];
 
 // $company=$_REQUEST['company'];
require('connect.php');

 $result =mysql_query("SELECT * FROM  vendor_tbl  WHERE ven_compname = '" .$company. "'", $conn);
if($row = mysql_fetch_array($result))
 {  
    $ven_id = $row['ven_id'];
    $ven_add1 = $row['ven_add1'];
    $ven_add2 = $row['ven_add2'];
    $ven_city = $row['ven_city'];
    $ven_contactperson = $row['ven_contactperson'];
	    $ven_pincode = $row['ven_pincode'];
    $returnXML = $ven_id. '|' .$ven_add1. '|' .$ven_add2. '|' .$ven_city. '|' .$ven_contactperson. '|' .$ven_pincode;
	 echo $returnXML;
	
  }
  else
{
	 $returnXML = "empty";
}
 

?>


<?php
require_once 'connect.php';
if(!empty($_POST['type'])){
	$type = $_POST['type'];
	$name = $_POST['name_startsWith'];
	$fetch = mysql_query("SELECT * FROM  vendor_tbl where UPPER($type) LIKE '%".strtoupper($name)."%'");
	$data = array();
	 while ($row = mysql_fetch_array($fetch, MYSQL_ASSOC)) {
   
		$names = $row['product_code'].'|'.$row['pro_name'].'|'.$row['Product_price'].'|'.$row['pro_height'].'|'.$row['pro_width'].'|'.$row['m_cost'].'|'.$row['i_cost'].'|'.$row['brand'].'|'.$row['unit'];
		array_push($data, $names);
	}	
	echo json_encode($data);exit;

}
