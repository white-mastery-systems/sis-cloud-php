<?php 
require_once("configure.php");
date_default_timezone_set('Asia/Calcutta'); 
$time = date('Y-m-d H:i:s');
//$time = date("M j G:i:s Y"); 
$ip = getenv('REMOTE_ADDR');
$userAgent = getenv('HTTP_USER_AGENT');
$referrer = getenv('HTTP_REFERER');
$query = getenv('QUERY_STRING');

 session_start();

 $username = "";
 $password = "";
 
 if(isset($_POST['username'])){
  $username = $_POST['username'];
 }
 if (isset($_POST['password'])) {
  $password = $_POST['password'];

 }

 $q = 'SELECT * FROM signintable WHERE st_emailid=:username AND st_pwd=:password';

 $query = $DB->prepare($q);

 $query->execute(array(':username' => $username, ':password' => $password));


 if($query->rowCount() == 0){
  header('Location: login.php?err=1');
  $msg = "IP: " . $ip . " TIME: " . $time . " User: " .$_SESSION['st_username'].  " USERAGENT: " .$userAgent. "Action :Login Failed";
writeToLogFile($msg);

 }else{

  $row = $query->fetch(PDO::FETCH_ASSOC);

  session_regenerate_id();
  
	
	$_SESSION['st_emailid']=$row['st_emailid'];
  $_SESSION['st_username'] = $row['st_username'];
        $_SESSION['sess_userrole'] = $row['st_rolename'];
		   $_SESSION['st_permission'] = $row['st_permission'];
		
	$_SESSION['st_pwd']=$row['st_pwd'];
	
	
        echo $_SESSION['sess_userrole'];
		
  session_write_close();

 
   header('Location: order.php');
    $msg = "IP: " . $ip . " TIME: " . $time . " User: " .$_SESSION['st_username'].  " USERAGENT: " .$userAgent. "Action : Admin Login";
writeToLogFile($msg);
 
  
  
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