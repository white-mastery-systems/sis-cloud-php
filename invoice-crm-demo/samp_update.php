<?php
include 'connect.php';
include 'writelog.php';
isset($_POST['po_date']) ? $po_date = $_POST['po_date'] : $po_date = "none";
isset($_POST['ven_id']) ? $ven_id = $_POST['ven_id'] : $ven_id = "none";
$project_name=$_POST['project_name'];
isset($_POST['project_name']) ? $project_name = $_POST['project_name'] : $project_name = "none";
isset($_POST['blockname']) ? $blockname = $_POST['blockname'] : $blockname = "blockname";
isset($_POST['project_id']) ? $project_id = $_POST['project_id'] : $project_id = "none";
isset($_POST['itemDesc']) ? $product_names  = $_POST['itemDesc'] : $product_names  = "none";
isset($_POST['vat_o']) ? $tax = $_POST['vat_o'] : $tax = "0";
isset($_POST['subTotal']) ? $subTotal = $_POST['subTotal'] : $subTotal = "0";
isset($_POST['payment']) ? $payment = $_POST['payment'] : $payment = "0";
isset($_POST['ddate']) ? $ddate = $_POST['ddate'] : $ddate = "none";
isset($_POST['newstatus']) ? $type = $_POST['newstatus'] : $type = "none";
isset($_POST['po_no']) ? $po_no = $_POST['po_no'] : $po_no = "none";
isset($_POST['ed']) ? $ed = $_POST['ed'] : $ed = "0";
isset($_POST['vat']) ? $vat = $_POST['vat'] : $vat = "0";
isset($_POST['st']) ? $st = $_POST['st'] : $st = "0";
isset($_POST['tp']) ? $transportation = $_POST['tp'] : $transportation = "0";
isset($_POST['basictotal']) ? $basictotal = $_POST['basictotal'] : $basictotal = "0";
isset($_POST['totalAftertax']) ? $totalAftertax = $_POST['totalAftertax'] : $totalAftertax = "0";
isset($_POST['itotal']) ? $itotal = $_POST['itotal'] : $itotal = "0";
isset($_POST['gt']) ? $gt = $_POST['gt'] : $gt = "0";
isset($_POST['gt1']) ? $gt1 = $_POST['gt1'] : $gt1 = "0";
isset($_POST['refno']) ? $refno = $_POST['refno'] : $refno = "none";
isset($_POST['refdate']) ? $refdate = $_POST['refdate'] : $refdate = "none";
isset($_POST['vendor_name']) ? $ven_compname = $_POST['vendor_name'] : $ven_compname = "none";
isset($_POST['ven_add']) ? $ven_add = $_POST['ven_add'] : $ven_add = "none";
isset($_POST['details']) ? $details = $_POST['details'] : $details = "none";
isset($_POST['ven_contactperson']) ? $ven_contactperson = $_POST['ven_contactperson'] : $ven_contactperson = "none";
isset($_POST['product_code']) ? $product_code = $_POST['product_code'] : $product_code = "none";
isset($_POST['itemCode']) ? $itemCode = $_POST['itemCode'] : $itemCode = "none";
isset($_POST['folder']) ? $folder = $_POST['folder'] : $folder = "none";
isset($_POST['units']) ? $units = $_POST['units'] : $units = "none";
isset($_POST['itemQty']) ? $itemQty = $_POST['itemQty'] : $itemQty = "0";
isset($_POST['subject']) ? $subject = $_POST['subject'] : $subject = "0";
isset($_POST['contactname']) ? $contactname = $_POST['contactname'] : $contactname = "0";
isset($_POST['mobilno']) ? $mobileno = $_POST['mobilno'] : $mobileno = "0";



$i=0;
isset($_POST['itemQtyd']) ? $itemQtyd = $_POST['itemQtyd'] : $itemQtyd = "none";
isset($product_names[0]) ? $product_names1 = $product_names[0] : $product_names1 = "";
isset($product_names[1]) ? $product_names2 = $product_names[1] : $product_names2 = "";
date_default_timezone_set('Asia/Calcutta'); 
$time = date('Y-m-d H:i:s');
$ip = getenv('REMOTE_ADDR');
$userAgent = getenv('HTTP_USER_AGENT');
$referrer = getenv('HTTP_REFERER');
$query = getenv('QUERY_STRING');

foreach($_POST['itemDesc'] as $row=>$itemDesc)
{
$product_name=$itemDesc;
$product_qty=$_POST['itemQty'][$row];
$rates=$_POST['itemPrice'][$row];
$itemVat=$_POST['itemVat'][$row];
$itemLineTotal=$_POST['itemLineTotal'][$row];
$po_date=$_POST['po_date'];
$ddate=$_POST['ddate'];
$po_id=$_POST['po_id'][$row];


}


//enter rows into database
foreach($_POST['itemDesc'] as $row=>$itemDesc)
{
$itemDesc=mysql_real_escape_string($itemDesc);
$itemQty=mysql_real_escape_string($_POST['itemQty'][$row]);
$po_id=mysql_real_escape_string($_POST['po_id'][$row]);

$itemPrice=mysql_real_escape_string($_POST['itemPrice'][$row]);
$itemVat=mysql_real_escape_string($_POST['itemVat'][$row]);
$itemLineTotal=mysql_real_escape_string($_POST['itemLineTotal'][$row]);
$po_date=mysql_real_escape_string($_POST['po_date']);
$project_id=mysql_real_escape_string($_POST['project_id']);
$itemDesc = trim("$itemDesc");
$tax=mysql_real_escape_string($_POST['vat_o']);
$sql_s="select * from purchaseorder_tbl where Po_no=$po_no";
$result_s=mysql_query($sql_s,$conn);
if(!$result_s)
{

$sql = "INSERT INTO purchaseorder_tbl (product_name,Po_no,product_qty,ratesperproduct,Amount,vat,po_date) VALUES ('$itemDesc',$po_no,$itemQty,$itemPrice,'$itemLineTotal',$itemVat,'$po_date')";
echo $sql;
$result = mysql_query($sql,$conn);

$query = "select * from requirementtable where product_name='$itemDesc'";
//echo $query;
$query_run = mysql_query($query,$conn);
 	
while ($req_row = mysql_fetch_array($query_run)) 
{
$req_stock=mysql_real_escape_string($req_row['itemsrequired']);
$product_name=mysql_real_escape_string($req_row['product_name']);
//echo $req_stock."+++++". $product_qty;
if($req_stock <= $product_qty)
	{
		$sql_req = "update requirementtable SET status='invoiceraised' where product_name='$product_name'";
		//echo $sql_req;
		$result_req = mysql_query($sql_req,$conn);
	
	}
	else
	{
	    
		$sql_req = "update requirementtable SET status='less' where product_name='$product_name'";
		//echo $sql_req;
		$result_req = mysql_query($sql_req,$conn);
	
	}
}
}
else
{
	$sql = "update purchaseorder_tbl set product_name='$itemDesc',product_qty=$itemQty,ratesperproduct=$itemPrice,Amount=$itemLineTotal,vat=$itemVat,po_date='$po_date' where po_id=$po_id and Po_no=$po_no";
echo $sql;
$result = mysql_query($sql,$conn);
}

}

if (!$result)
{
	echo mysql_errno($conn) . ": " . mysql_error($conn) . "\n";
die('Error1: ' . mysql_error());
}
$sql_s1="select * from  purchase_master where po_no=$po_no";
$result_s1=mysql_query($sql_s1,$conn);

if(!$result_s1)
{

$sql1 = "INSERT INTO purchase_master(po_no,po_date,project_id,ven_id,subtotal,ddate,vat,status) VALUES ($po_no,'$po_date',$project_id,$ven_id,$subTotal,'$ddate','$tax','open')";

$result1 = mysql_query($sql1,$conn);
header("Location: order.php");
$msg = "IP: " . $ip . " TIME: " . $time . " User: " .$_SESSION['st_username'].  " USERAGENT: " .$userAgent. "Action :Purchase Order_" .$po_no . "New Product added in update page";
writeToLogFile($msg);

}
else
{
	$sql1 = "update purchase_master set po_date='$po_date',project_id=$project_id,ven_id=$ven_id,vat='$tax',subtotal=$subTotal,ddate='$ddate' where po_no=$po_no";
//echo $sql1;
$result1 = mysql_query($sql1,$conn);
header("Location: order.php");
 $msg = "IP: " . $ip . " TIME: " . $time . " User: " .$_SESSION['st_username'].  " USERAGENT: " .$userAgent. "Action :Purchase Order_" .$po_no . "Updated";
writeToLogFile($msg);

}
if (!$result1)
{
die('Error2: ' . mysql_error());
}

mysql_close($conn);
function writeToLogFile($msg) {
     $today = date("Y_m_d"); 
     $logfile = $today."_log.txt"; 
     $dir = 'logs';
     $saveLocation=$dir . '/' . $logfile;
     if  (!$handle = @fopen($saveLocation, "a")) {
          exit;
     }
     else {
          if (@fwrite($handle,"$msg\r\n") === FALSE) {
               exit;
          }
   
          @fclose($handle);
     }
}
?> 