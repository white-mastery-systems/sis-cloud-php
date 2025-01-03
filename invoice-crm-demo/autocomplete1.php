<?php
require_once 'connect.php';
if(!empty($_POST['type'])){
	$type = $_POST['type'];
	$name = $_POST['name_startsWith'];

	$fetch = mysql_query("SELECT * FROM vendor_tbl where $type LIKE '%".$name."%'");
	$data = array();
	 while ($row = mysql_fetch_array($fetch, MYSQL_ASSOC)) {
		$names = $row['ven_id'].'|'.$row['ven_compname'].'|'.$row['ven_contactperson'].'|'.$row['ven_add1'].'|'.$row['pgstin'];
		array_push($data, $names);
	}	
	echo json_encode($data);exit;

}
?>