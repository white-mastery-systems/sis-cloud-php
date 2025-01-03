	<?php 
/**$conn = @mysql_connect('localhost','queens','queens123');**/
$conn = @mysql_connect('151.106.7.74','cloud_user','cloud_pwd*747');
if (!$conn) {
	die('Could not connect: ' . mysql_error());
}
mysql_select_db('cloud_db', $conn);

?>