 	<?php
	include "connect.php";
      $ids = $_POST["ids"];
echo $ids;
      
	  $id = explode(",", $ids);
    for($i = 0; $i < count($id); $i++){
		$sql_update = "update vendor_tbl set status='cancel' where ven_id=$id[$i]";
$result_update = mysql_query($sql_update,$conn);
echo $id. "Records Deleted";
    }
	  
	  
?>