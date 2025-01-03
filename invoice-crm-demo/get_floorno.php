<?php
$project_name = $_POST['project_name'];
$block = $_POST['blockname'];

require('connect1.php');

$result = mysqli_query($conn,"SELECT distinct floor FROM clientmaster where projectname='$project_name' and block='$block' ORDER BY  cast(floor as int) ASC");
echo "<option selected value='select'>SELECT</option>";

while ($row = mysqli_fetch_array($result)) 
{
	$floor	 = mysqli_real_escape_string($conn,$row['floor']);
  echo "<option  value='".$floor."'>" .$floor. "</option>";
  
   }

   
 ?>