<?php
$project_name = $_POST['project_name'];
require('connect.php');

$result = mysql_query("SELECT * FROM project_details where project_name='$project_name'",$conn);
echo "<option selected value='select'>SELECT</option>";

while ($row = mysql_fetch_array($result)) 
{
	$blockname	 = mysql_real_escape_string($row['blockname']);
  echo "<option  value='".$blockname."'>" .$blockname. "</option>";
  
   }

   
 ?>