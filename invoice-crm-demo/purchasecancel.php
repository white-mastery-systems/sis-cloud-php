<?php

$po_no=$_POST['po_no'];
include 'connect.php';

$time = date('Y-m-d H:i:s');
$po_no=$_GET['po_no'];
$ip = getenv('REMOTE_ADDR');
$userAgent = getenv('HTTP_USER_AGENT');
$referrer = getenv('HTTP_REFERER');
$query = getenv('QUERY_STRING');

$sql = "UPDATE purchase_master SET status = 'cancel' WHERE po_no ='$po_no'" ;
$result = mysql_query( $sql, $conn );
$sql1 = "delete * from stocklist WHERE po_no ='$po_no'" ;
$result1 = mysql_query( $sql1, $conn );
if($result)
{
$msg = "IP: " . $ip . " TIME: " . $time . " User: " .$_SESSION['st_username'].  " USERAGENT: " .$userAgent. "Action : Purchase order_".$po_no." has been Cancelled";
writeToLogFile($msg);
echo "canceled";
}
function writeToLogFile($msg) {
	date_default_timezone_set('Asia/Calcutta'); 

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