 	<?php
	include "connect.php";
      $ids = $_POST["ids"];
echo $ids;
      
	  $id = explode(",", $ids);
    for($i = 0; $i < count($id); $i++){
		$sql_delete = "delete from vendor_tbl where ven_id=$id[$i]";
echo $sql_delete;

    echo "Piece $i = $id[$i] <br />";
			
//$result_delete = mysql_query($sql_delete,$conn);
    }
	  
	  
   // for($i = 0; $i < count($id); $i++){
			//$sql_delete = "delete from vendor_tbl where ven_id=$id[$i]";
			//echo $sql_delete;
			
//$result_delete = mysql_query($sql_delete,$conn);
		//}
?>