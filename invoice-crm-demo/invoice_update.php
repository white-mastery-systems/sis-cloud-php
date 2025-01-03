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
isset($_POST['po_no']) ? $po_no = $_POST['po_no'] : $po_no = "none";
isset($_POST['po_year']) ? $po_year = $_POST['po_year'] : $po_year = "none";
isset($_POST['desc']) ? $desc = $_POST['desc'] : $desc = "none";
isset($_POST['status']) ? $status = $_POST['status'] : $status = "none";
$result1 = mysql_query("SELECT * FROM invoicetable where invoicenotype='".$po_no."'",$conn);
echo "SELECT invoiceno FROM invoicetable where invoicenotype='".$po_no."'";
if ($row1 = mysql_fetch_array($result1)) {
$num_rows= mysql_num_rows($result1);

if($num_rows != 0){
$po_year = $row1['po_year'];
    echo $po_year;

$i=0;
	$result = mysql_query("SELECT * FROM project_details where project_name='" .$project_name. "'",$conn);
	if($row= mysql_fetch_array($result))
	 {
	    $project_name = $row['project_name'] ;
	    $place=$row['place'];	
		$address=$row['address'];  		
        $projectshort_i=$row['projectshort_i']; 	
		$city=$row['city'];	
        $panno=$row['panno'];
         $gstin=$row['gstin'];
        $hsn_sac_code=$row['hsn_sac_code'];
        
		}
		else
		{
			echo "Error";
	 }

    
    
    $resultc = mysql_query("SELECT * FROM  clientmaster where projectname='$project_name' and block='$blockname' and floor='$floorno' and flatno= '$flatno'",$conn);
echo "SELECT * FROM  clientmaster where projectname='$project_name' and block='$blockname' and floor='$floorno' and flatno= '$flatno'";
if ($rowc = mysql_fetch_array($resultc)) {
$num_rowsc= mysql_num_rows($result);

if($num_rowsc != 0){
  $sqlc = "update  clientmaster set name='$cname',address1='$address1',address2='$address2',address3='$address3',contact='$contact',panno='$pannoc' where projectname='$project_name' and block='$blockname' and floor='$floorno' and flatno= '$flatno' ";
$resultc = mysql_query($sqlc,$conn);
    echo $sqlc ;
}    
}
    
$sql1 = "update invoicetable set block='$blockname',floorno='$floorno',flatno='$flatno',total='$subvalue',totalword='$totalword',lc_amount='$lcvalue',taxamount='$taxvalue',cgst='$cgst',sgst='$sgst',roundtotal='$roundvalue',grandtotal='$grandtotal',gstper='$gstper',invoicedate='$po_date',description='$desc',status='$status' where invoicenotype = '$po_no'";
//echo $sql1;
$result1 = mysql_query($sql1,$conn);
    if($result)
    {
        header("Location: invoice.php"); 
    }
    
$msg = "IP: " . $ip . " TIME: " . $time . " User: " .$_SESSION['st_username'].  " USERAGENT: " .$userAgent. "Action : INVOICE-NO".$po_no."Inserted";
writeToLogFile($msg);
}
}
//
mysql_close($conn)
?>