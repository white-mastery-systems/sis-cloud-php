<?php
$project_name = $_POST['project_name'];
$block = $_POST['blockname'];
$floor = $_POST['floor'];

require('connect1.php');

$result = mysqli_query($conn, "SELECT * FROM clientmaster where projectname='$project_name' and block='$block' and floor='$floor'");
echo "<option selected value='select'>SELECT FLAT NO</option>";

while ($row = mysqli_fetch_array($result)) 
{
$flatno = mysqli_real_escape_string($conn,$row['flatno']);
  echo "<option  value='".$flatno."'>" .$flatno. "</option>";
  
   }

   
 ?>