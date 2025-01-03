<?php
include "connect.php";
isset($_POST['ids']) ? $ids = mysql_real_escape_string($_POST['ids']) : $ids = "none";
if($ids != 'none')
{
 $id = explode(",", $ids);
	  $n=0;
    for($i = 0; $i < count($id); $i++)
	{
		$sql_update = "delete from  purchaseorder_tbl where po_id=$id[$i]";
		echo $sql_update;
        $result_update = mysql_query($sql_update,$conn);
        $n = $n+1;
}
echo $n. "Records Deleted";
}
else
{
	echo "Data Not in the List";
}
?>
