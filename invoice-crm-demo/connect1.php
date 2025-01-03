	<?php 

$dbHost = '151.106.7.74';
$dbUsername = 'cloud_user';
$dbPassword = 'cloud_pwd*747';
$dbName = 'cloud_db';

$conn = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbName);
if (!$conn) 
{
  die("Database connection failed: " . mysqli_connect_error());   
}
 



?>


