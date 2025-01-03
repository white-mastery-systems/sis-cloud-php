<?php

$tinno;
$cstno;
$address;
$contactname;
$gstno = "";
$project_name = $_POST['project_name'];
$block = $_POST['blockname'];
$floor = $_POST['floor'];
$flatno = $_POST['flatno']; 
 $grandtotal  = 0;
 
require('connect.php');

 $result =mysql_query("SELECT * FROM  clientmaster where projectname='$project_name' and block='$block' and floor='$floor' and flatno= '$flatno'", $conn);
if($row = mysql_fetch_array($result))
 {  
    $name = $row['name'];
    $address1 = $row['address1'];
    $address2 = $row['address2'];
    $address3 = $row['address3'];
    $contact = $row['contact'];
    $panno = $row['panno'];
    $area = $row['area'];   
    $totalamt= $row['totalamt']; 
    $gvalue= $row['gvalue'];
			  
$result1 =mysql_query("SELECT SUM(grandtotal) AS grandtotal  FROM invoicetable where projectname='$project_name' and flatno= '$flatno'", $conn);
   if($row1 = mysql_fetch_array($result1)) 
    {
     
           
           $grandtotal = $row1['grandtotal']; 
       
    }
    
    
   if($row1['grandtotal'] == '') 
   {
     $grandtotal1 = 0;  
   }
    else
    {
    $grandtotal1 = $row1['grandtotal'];   
    }
    
$returnXML = $name. '|' .$address1. '|' .$address2. '|' .$address3. '|' .$contact. '|' .$panno. '|' . $area . '|'.$totalamt . '|' .$gvalue . '|' .$grandtotal1;
echo $returnXML;
	
  }
  else
{
	 $returnXML = "empty";
}

?>