<?php

require_once 'connect.php';
$type = "pro_name";
//if(!empty($_POST['type']))
if(!empty($type))
{
	
	//$name = $_POST['name_startsWith'];
	
	$query = "SELECT pro_name,Product_price FROM productlist where pro_name LIKE 'opc' ";
	$result = mysql_query($conn, $query);
	$data = array();
	while ($row = mysql_fetch_assoc($result)) {
		$name = $row['pro_name'].'|'.$row['Product_price'];
		array_push($data, $name);
	}	
	echo json_encode($data);exit;
	
	
}


