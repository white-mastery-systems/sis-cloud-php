<?php 
include "connect.php";
 isset($_POST['po_no']) ? $po_no = mysql_real_escape_string($_POST['po_no']) : $po_no = "0";
isset($_POST['action']) ? $action = mysql_real_escape_string($_POST['action']) : $action = "none";
isset($_POST['desc']) ? $desc = mysql_real_escape_string($_POST['desc']) : $desc = "none";

if($action == 'delete')

{ 
$sql = "delete from invoicetable where invoicenotype ='$po_no'" ;

$result = mysql_query( $sql, $conn );
if($result)
{
echo "Deleted Permanently";
}
}
else if($action == 'cancel')
{
    $sql1 = "UPDATE invoicetable SET status = 'cancel', description = '$desc'  WHERE invoicenotype ='$po_no' " ;   
    $result = mysql_query( $sql1, $conn );  
}




    ?>