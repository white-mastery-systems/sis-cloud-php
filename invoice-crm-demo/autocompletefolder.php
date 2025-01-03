

<?php
require_once 'connect.php';

if(!empty($_POST['type'])){
	$type = $_POST['type'];
	$name = $_POST['name_startsWith'];

	$fetch = mysql_query("SELECT * FROM  folder where UPPER($type) LIKE '%".strtoupper($name)."%'");
	$data = array();
	 while ($row = mysql_fetch_array($fetch, MYSQL_ASSOC)) {
		$namesf = $row['id'].'|'.$row['folder_name'];
		array_push($data, $namesf);
	}	
	echo json_encode($data);exit;

}




?>