<?php
require_once 'connect.php';
if(!empty($_POST['type'])){
	$type = $_POST['type'];
	$name = $_POST['name_startsWith'];
	$fetch = mysql_query("SELECT * FROM productlist where status = 'open' AND UPPER($type) LIKE '%".mysql_real_escape_string(strtoupper($name))."%'");
	$data = array();
	 while ($row = mysql_fetch_array($fetch, MYSQL_ASSOC)) {
   
		$names =  $row['product_code'].'|'.$row['pro_name'].'|'.$row['Product_price'].'|'.$row['category_name'].'|'.$row['sub_catname'].'|'.$row['brand'].'|'.$row['size'].'|'.$row['unit'];
		array_push($data, $names);
	}	
	echo json_encode($data);exit;

}

?>
