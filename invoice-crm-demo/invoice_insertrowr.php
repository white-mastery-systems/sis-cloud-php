<?php
session_start();
include 'connect.php';
include 'writelog.php';
isset($_POST['po_date']) ? $po_date = $_POST['po_date'] : $po_date = "none";
isset($_POST['ven_id']) ? $ven_id = $_POST['ven_id'] : $ven_id = "none";

isset($_POST['project_name']) ? $project_name = $_POST['project_name'] : $project_name = "none";
isset($_POST['blockname']) ? $blockname = $_POST['blockname'] : $blockname = "none";
isset($_POST['floorno']) ? $floorno = $_POST['floorno'] : $floorno = "none";
isset($_POST['flatno']) ? $flatno = $_POST['flatno'] : $flatno = "none";
isset($_POST['cname']) ? $cname = $_POST['cname'] : $cname = " ";
isset($_POST['address1']) ? $address1 = $_POST['address1'] : $address1 = "0";
isset($_POST['address2']) ? $address2 = $_POST['address2'] : $address2 = "0";
isset($_POST['address3']) ? $address3 = $_POST['address3'] : $address3 = "0";
isset($_POST['contact']) ? $contact = $_POST['contact'] : $contact = "0";
isset($_POST['panno']) ? $pannoc = $_POST['panno'] : $pannoc = "0";
isset($_POST['subvalue']) ? $subvalue = $_POST['subvalue'] : $subvalue = "0";
isset($_POST['totalword']) ? $totalword = $_POST['totalword'] : $totalword = "0";
isset($_POST['lcvalue']) ? $lcvalue = $_POST['lcvalue'] : $lcvalue = "0";
isset($_POST['taxvalue']) ? $taxvalue = $_POST['taxvalue'] : $taxvalue = "0";
isset($_POST['cgst']) ? $cgst = $_POST['cgst'] : $cgst = "0";
isset($_POST['sgst']) ? $sgst = $_POST['sgst'] : $sgst = "0";
isset($_POST['roundvalue']) ? $roundvalue = $_POST['roundvalue'] : $roundvalue = "0";
isset($_POST['grandtotal']) ? $grandtotal = $_POST['grandtotal'] : $grandtotal = "0";
isset($_POST['gstper']) ? $gstper = $_POST['gstper'] : $gstper = "0";

if($gstper == '0')
{
    $gst_status = 'nil';
   
}
else
{
  $gst_status = 'included';

}


$MM = date('m');
if($MM < 4 )
{
$FinancialYear = date('Y')-1;
$po_year = date('Y')-1;
    $po_year1 = date('y')-1;
}
else
{
$FinancialYear = date('Y');
$po_year = date('Y');
     $po_year1 = date('y');
}
$invoiceno = 0;

if($project_name == 'S.I.S Acropole')
{
    $result = mysql_query("SELECT invoiceno FROM invoicetable where po_year='".$FinancialYear."' and gst_status='".$gst_status."' and projectname = '$project_name' and block='$blockname' ORDER BY invoiceno DESC LIMIT 1",$conn);
    echo "SELECT invoiceno FROM invoicetable where po_year='".$FinancialYear."' and gst_status='".$gst_status."' and projectname = ' $project_name' and block='$blockname' ORDER BY invoiceno DESC LIMIT 1";
}
else
{
  $result = mysql_query("SELECT invoiceno FROM invoicetable where po_year='".$FinancialYear."' and gst_status='".$gst_status."' and projectname = '$project_name' ORDER BY invoiceno DESC LIMIT 1",$conn); 
   echo "SELECT invoiceno FROM invoicetable where po_year='".$FinancialYear."' and gst_status='".$gst_status."' and projectname = ' $project_name' ORDER BY invoiceno DESC LIMIT 1";
    
}

if ($row = mysql_fetch_array($result)) {
$num_rows= mysql_num_rows($result);
$invoiceno = $row['invoiceno'] +1;
}
else
{
$invoiceno+=1;
}

$invoiceno_round = str_pad($invoiceno, 3 ,'0', STR_PAD_LEFT);

$i=0;
	$result = mysql_query("SELECT * FROM project_details where project_name='" .$project_name. "'",$conn);
	if($row= mysql_fetch_array($result))
	 {
	    $project_name = $row['project_name'] ;
	    $place=$row['place'];	
		$address=$row['address'];  		
        $projectshort_i=$row['projectshort_i']; 	
		$city=$row['city'];	
        $panno =$row['panno'];
         $gstin=$row['gstin'];
        $hsn_sac_code=$row['hsn_sac_code'];
        
		}
		else
		{
			echo "Error";
	 }

$curYear = $po_year;
$nexYear = $po_year1 + 1;


$result = mysql_query("SELECT * FROM  clientmaster where projectname='$project_name' and block='$blockname' and floor='$floorno' and flatno= '$flatno'",$conn);
echo "SELECT * FROM  clientmaster where projectname='$project_name' and block='$blockname' and floor='$floorno' and flatno= '$flatno'";
if ($row = mysql_fetch_array($result)) {
$num_rows= mysql_num_rows($result);

if($num_rows != 0){
  $sqlc = "update  clientmaster set name='$cname',address1='$address1',address2='$address2',address3='$address3',contact='$contact',panno='$pannoc' where projectname='$project_name' and block='$blockname' and floor='$floorno' and flatno= '$flatno' ";
  $resultc = mysql_query($sqlc,$conn);
    echo $sqlc ;
}
    
}

if($gstper == '0')
{
    $gst_status = 'nil';
    if($project_name == 'S.I.S Acropole')
    {
        $invoicenotype = $projectshort_i."/".$blockname."/"."CP/".$curYear."-".$nexYear."/".$invoiceno_round;
        echo  $invoicenotype;
    }
    else
    {
        $invoicenotype = $projectshort_i."/"."CP/".$curYear."-".$nexYear."/".$invoiceno_round;
        echo  $invoicenotype;
    }
    
}
else
{
  $gst_status = 'included';
     if($project_name == 'S.I.S Acropole')
    {
         $invoicenotype = $projectshort_i."/".$blockname."/".$curYear."-".$nexYear."/".$invoiceno_round;
         echo  $invoicenotype;
     }
    else
    {
   $invoicenotype = $projectshort_i."/".$curYear."-".$nexYear."/".$invoiceno_round;
        echo  $invoicenotype;
    }

}
$sql1 = "INSERT INTO invoicetable(invoiceno,invoicenotype,projectname,block,floorno,flatno,total,totalword,lc_amount,taxamount,cgst,sgst,roundtotal,grandtotal,po_year,city,gstin,hsn_sac_code,panno,gstper,invoicedate,gst_status) VALUES ('$invoiceno_round','$invoicenotype','$project_name','$blockname','$floorno','$flatno','$subvalue','$totalword','$lcvalue','$taxvalue','$cgst','$sgst','$roundvalue','$grandtotal','$po_year','$city','$gstin','$hsn_sac_code','$panno','$gstper','$po_date','$gst_status')";
$result1 = mysql_query($sql1,$conn);
//$msg = "IP: " . $ip . " TIME: " . $time . " User: " .$_SESSION['st_username'].  " USERAGENT: " .$userAgent. "Action : PO-NO".$po_no."Inserted";
//writeToLogFile($msg);
if($result1)
{
header("Location: invoice.php");   
}
    


mysql_close($conn);
?>