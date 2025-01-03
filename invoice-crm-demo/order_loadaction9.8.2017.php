<?php 
include "connect.php";
 isset($_POST['po_no']) ? $po_no = mysql_real_escape_string($_POST['po_no']) : $po_no = "0";
isset($_POST['action']) ? $action = mysql_real_escape_string($_POST['action']) : $action = "none";
isset($_POST['status']) ? $status = mysql_real_escape_string($_POST['status']) : $status = "none";
isset($_POST['po_year']) ? $po_year = mysql_real_escape_string($_POST['po_year']) : $po_year = "none";
isset($_POST['ids']) ? $ids = mysql_real_escape_string($_POST['ids']) : $ids = "none";

echo $ids;

if($action == 'deletet'  && $ids == 'none' &&  $status == 'open')
{
$sql = "UPDATE purchase_master SET status = 'cancel' WHERE po_no ='$po_no' and po_year='$po_year'" ;
$result = mysql_query( $sql, $conn );
$sql2 = "UPDATE  purchaseorder_tbl SET status = 'cancel' WHERE Po_no ='$po_no' and po_year='$po_year'" ;
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
 
    
	$sql = "delete from purchase_master where po_no ='$po_no' and po_year = '$po_year'" ;
	echo $sql;
$result = mysql_query( $sql, $conn );

$sql1 = "delete from purchaseorder_tbl where Po_no ='$po_no' and po_year = '$po_year'" ;
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

else if($action == 'reopen'  && $ids == 'none' &&  $status == 'cancel')
{
$sql = "UPDATE purchase_master SET status = 'open' WHERE po_no ='$po_no' and po_year='$po_year'" ;
$result = mysql_query( $sql, $conn );
$sql2 = "UPDATE  purchaseorder_tbl SET status = 'open' WHERE Po_no ='$po_no' and po_year='$po_year'" ;
$result2 = mysql_query( $sql2, $conn );
$sql1 = "UPDATE  stocklist SET status = 'open' WHERE po_no ='$po_no'" ;
$result1 = mysql_query( $sql1, $conn );

if($result)
{
echo "PO Reopen";
}
}

else if($ids != 'none' && $status == 'open' )
{
//echo $ids;
echo "Update";
    
	 $id = explode(",", $ids);
	  $n=0;
    for($i = 0; $i < count($id); $i++)
	{
           $val= explode("_",$id[$i]);
	//	echo $id[$i];
		$sql = "UPDATE purchase_master SET status = 'cancel' WHERE po_no ='$val[0]' and po_year = '$val[1]'" ;
        echo $sql;
$result = mysql_query( $sql, $conn );
$sql2 = "UPDATE  purchaseorder_tbl SET status = 'cancel' WHERE Po_no ='$val[0]' and po_year = '$val[1]'" ;
         echo $sql2;
$result2 = mysql_query( $sql2, $conn );
$sql1 = "UPDATE  stocklist SET status = 'cancel' WHERE po_no ='$val[0]'" ;
$result1 = mysql_query( $sql1, $conn );
$n = $n+1;      
}
echo $n. "Records Deleted";
}

else

{
   // echo $ids;
		$id = explode(",", $ids);
	  $n=0;
    for($i = 0; $i < count($id); $i++)
	{
		//echo $id[$i];
   $val= explode("_",$id[$i]);
$sql = "delete from purchase_master where po_no ='$val[0]' and po_year = '$val[1]'" ;
	echo $sql;
//$result = mysql_query( $sql, $conn );

$sql1 = "delete from purchaseorder_tbl where Po_no ='$val[0]' and po_year = '$val[1]'" ;
echo $sql1;
//$result2 = mysql_query( $sql1, $conn );
$sql2 = "delete from  stocklist where po_no ='$val[0]'" ;
//echo $sql2;
//$result2 = mysql_query( $sql2, $conn );		
		$n = $n+1;
}
//echo $n. "Records Deleted";
}
    ?>