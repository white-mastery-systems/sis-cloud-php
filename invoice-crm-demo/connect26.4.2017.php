<?php 

$conn = @mysql_connect('74.50.100.95','workprogress','Progress*123');
if (!$conn) {
	die('Could not connect: ' . mysql_error());
}
mysql_select_db('progress', $conn);

?>