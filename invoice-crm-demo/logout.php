<?php
date_default_timezone_set('Asia/Calcutta'); 
$time = date('Y-m-d H:i:s');
//$time = date("M j G:i:s Y"); 
$ip = getenv('REMOTE_ADDR');
$userAgent = getenv('HTTP_USER_AGENT');
$referrer = getenv('HTTP_REFERER');
$query = getenv('QUERY_STRING');

$msg = "IP: " . $ip . " TIME: " . $time . " User: " .$_SESSION['st_username'].  " USERAGENT: " .$userAgent. "Action :Logout";
writeToLogFile($msg);

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
session_start();
if(session_destroy())
{
header("Location: login.php");
}


?>