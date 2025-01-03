 <?php
include "connect.php";
if($_GET['type'] == 'country_table'){
	$row_num = $_GET['row_num'];
	 $result =mysql_query("SELECT  $ven_compname,$ven_add1,$ven_add2,$ven_city,$ven_pincode,$ven_contactperson FROM vendor_tbl WHERE ven_compname	LIKE '".strtoupper($_GET['name_startsWith'])."%'");	

	$data = array();
	while ($row = mysql_fetch_array($result)) {
		$name = $row['ven_compname'].'|'.$row['ven_add1'].'|'.$row['ven_add2'].'|'.$row['ven_city'].'|'.$row_num;
		array_push($data, $name);	
	}	
	echo json_encode($data);
}


?>