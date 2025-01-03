<?php 
include "connect.php";
 isset($_POST['po_no']) ? $po_no = mysql_real_escape_string($_POST['po_no']) : $po_no = "0";
isset($_POST['action']) ? $action = mysql_real_escape_string($_POST['action']) : $action = "none";
isset($_POST['status']) ? $status = mysql_real_escape_string($_POST['status']) : $status = "none";
isset($_POST['ids']) ? $ids = mysql_real_escape_string($_POST['ids']) : $ids = "none";


if($action == 'deletet'  && $ids == 'none' &&  $status == 'open')
{
	$sql = "UPDATE purchase_master SET status = 'cancel' WHERE po_no ='$po_no'" ;
$result = mysql_query( $sql, $conn );
$sql2 = "UPDATE  purchaseorder_tbl SET status = 'cancel' WHERE Po_no ='$po_no'" ;
$result2 = mysql_query( $sql2, $conn );
$sql1 = "UPDATE  stocklist SET status = 'cancel' WHERE po_no ='$po_no'" ;
$result1 = mysql_query( $sql1, $conn );

if($result)
{
echo "PO Cancelled";
}
}
else if($action == 'deletep'  && $ids == 'none' and $status == 'cancel')

{
	$sql = "delete from purchase_master where po_no =$po_no" ;
	echo $sql;
$result = mysql_query( $sql, $conn );

$sql1 = "delete from purchaseorder_tbl where Po_no =$po_no" ;
echo $sql1;
$result2 = mysql_query( $sql1, $conn );
$sql2 = "delete from  stocklist where po_no =$po_no" ;
echo $sql2;
$result2 = mysql_query( $sql2, $conn );

if($result)
{
echo "Deleted Permanently";
}
}


else if($ids != 'none' && $status == 'open' )
{

	
	 $id = explode(",", $ids);
	  $n=0;
    for($i = 0; $i < count($id); $i++)
	{
		echo $id[$i];
		$sql = "UPDATE purchase_master SET status = 'cancel' WHERE po_no ='$id[$i]'" ;
$result = mysql_query( $sql, $conn );
$sql2 = "UPDATE  purchaseorder_tbl SET status = 'cancel' WHERE Po_no ='$id[$i]'" ;
$result2 = mysql_query( $sql2, $conn );
$sql1 = "UPDATE  stocklist SET status = 'cancel' WHERE po_no ='$id[$i]'" ;
$result1 = mysql_query( $sql1, $conn );
$n = $n+1;
}
echo $n. "Records Deleted";
}

else

{
		$id = explode(",", $ids);
	  $n=0;
    for($i = 0; $i < count($id); $i++)
	{
		//echo $id[$i];
	
		$sql = "delete from purchase_master where po_no =$id[$i]" ;
	//echo $sql;
$result = mysql_query( $sql, $conn );

$sql1 = "delete from purchaseorder_tbl where Po_no =$id[$i]" ;
//echo $sql1;
$result2 = mysql_query( $sql1, $conn );
$sql2 = "delete from  stocklist where po_no =$id[$i]" ;
//echo $sql2;
$result2 = mysql_query( $sql2, $conn );		
		$n = $n+1;
}
echo $n. "Records Deleted";
}
    ?>