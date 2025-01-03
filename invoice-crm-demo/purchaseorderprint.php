
<?php
session_start();
require('connect.php');
include "writelog.php";
$time = date('Y-m-d H:i:s');
$po_no=$_GET['po_no'];
$ip = getenv('REMOTE_ADDR');
$userAgent = getenv('HTTP_USER_AGENT');
$referrer = getenv('HTTP_REFERER');
$query = getenv('QUERY_STRING');

$po_no=$_GET['po_no'];
//echo $po_no;
$curYear = date('Y');
$nexYear = date('y')+1;
$n=0;
$result1 = mysql_query("SELECT * FROM  purchase_master where po_no=" .$po_no,$conn);
if($row1 = mysql_fetch_array($result1))
	 {
$project_id=$row1['project_id'] ;
$ven_id=$row1['ven_id'];
$vat_o=$row1['vat'];
$subtotal=$row1['subtotal'];
$ddate=$row1['ddate'];
$ed=$row1['ed'];
$st=$row1['st'];
$tax=$row1['tax'];
$transportation=$row1['transportation'];
$basictotal=$row1['basictotal'];
$itotal=$row1['itotal'];
$gt=$row1['gt'];
$gt1=$row1['gt1'];
$po_date=$row1['po_date'];
$type = $row1['type'];
$po_date=$row1['po_date'];
 $refno = $row1['refno'];
$refdate=$row1['refdate'];
$block=$row1['Block'];
$payment = $row1['payment'];
$subject=$row1['subject'];
$contactname=$row1['contactname'];
$mobileno=$row1['mobileno'];
$inclusive = $row1['inclusive'];
$tpwords = $row1['tpwords'];


$stotal = $row1['stotal'];
}
	if($type == 'standard')
	{
	echo "<div id='content' style='position:relative; width:600px; font-family:Times New Roman; font-size:13.5px;margin:0 auto'>";
    echo "<div>"; 
	 $result2 = mysql_query("SELECT * FROM  vendor_tbl where ven_compname='".mysql_real_escape_string($ven_id)."'",$conn);
	if($row2= mysql_fetch_array($result2))
	 {
		$ven_id1 = $row2['ven_id'];
	    $ven_compname = $row2['ven_compname'] ;
	    $ven_add1=$row2['ven_add1'];
		$ven_add2=$row2['ven_add2'];
	    $ven_city=$row2['ven_city'];
		$ven_pincode=$row2['ven_pincode'];
		$ven_state=$row2['ven_state'];
		$ven_country=$row2['ven_country'];
		 
		}
		
	 
		$result = mysql_query("SELECT * FROM project_details where project_id=" .$project_id,$conn);
	if($row= mysql_fetch_array($result))
	 {
	    $project_name = $row['project_name'];
	    $place=$row['place'];
		$tinno=$row['tinno'];
	    $cstno=$row['cstno'];
		$address=$row['address']; 		 
		 $city=$row['city'];
		$projectshort=$row['projectshort'];  
		$addressdispname=$row['addressdispname']; 
		$projectsingle=$row['projectsingle']; 
		}
		else
		{
			echo "Error";
	 }
		 

echo "<table> <tbody> <tr><td> ";
echo "<div align='right'><b>" .$po_date. "</b><br /></div>";
echo "<b>F/PUR/SIS-PO/".$projectsingle."/".$po_no. "/". $curYear."-".$nexYear."</b><br />&nbsp;<br/>";
echo "<b>To:</b><br />";
echo "<div style='width:250px; height:auto'><b>M/S.".$row2['ven_compname']. "</b></div>";
echo "<div style='width:300px'>" .$row2['ven_add1']. ".<br/></div>";
echo "<div align='center' style='width:100%'><b>Kind Attention: " .$row2['ven_contactperson'] . "</b></div>";
if( $refno != '')
{
echo "<b>Ref : </b>". $refno.  " <b>Dated </b> on " .$refdate."<br>";
}
echo "<div style='line-height:200%'><b>Sub : PO for " .$subject. ".</div></b>";
echo  "We are pleased to place the Purchase order as per the details mentioned below for our " ;
if( $row['project_name'] != 'Head Office' or $row['project_name'] != 'S.I.S Safaa' )
{
echo  "  Project <b> " .$project_name."&nbsp; Block &nbsp; ".$block."</b> ";
}
else
{
	echo  "<b> " .$project_name. "</b> ";
}
echo " the address and the contact person are mentioned below.";
echo "</div>";
 echo "</br>&nbsp;";
echo "<table  cellpadding='0' cellspacing='0'  width='100%'  id='itemsTable' border='0' style='border-collapse: collapse; border: 1px solid black;' >";
echo "<thead>";
echo "<tr>";
echo "<th style='border-collapse: collapse; border:solid 1px #000; padding:5px; width:15px' align='center'><b>No</b></th>";
echo "<th style='border-collapse: collapse; border:solid 1px #000; padding:5px;width:295px' align='center'><b>Description</b></th>";
echo "<th style='border-collapse: collapse; border:solid 1px #000; padding:5px; width:50px' align='center'><b>Qty</b></th>";
echo "<th style='border-collapse: collapse; border:solid 1px #000; padding:5px;width:80px' align='center'><b>Units</b></th>";

echo "<th style='border-collapse: collapse; border:solid 1px #000; padding:5px;width:100px' align='center'><b>Price in <span><img src='images/rupee.jpg' /> </span> </b></th>";
if($inclusive != '0')
{

echo "<th style='border-collapse: collapse; border:solid 1px #000; padding:5px;width:50px' align='center'><b>Vat in %</b></th>";
}
echo "<th style='border-collapse: collapse; border:solid 1px #000; padding-right:5px;;width:270px' align='right' ><b>Total  in <span><img src='images/rupee.jpg' /> </span> </b></th>";
echo "</tr>";
echo "</thead>";
echo "<tbody>";
$result_r = mysql_query("SELECT * FROM  purchaseorder_tbl where Po_no=" .$po_no,$conn);
while($row = mysql_fetch_array($result_r))
 {
	$productname = $row['product_name'];
	$product_qty=$row['product_qty'];
	$ratesperproduct = $row['ratesperproduct'];
	$vat = $row['vat'];
	$Amount = $row['Amount'];
	$po_date = $row['po_date'];
	$units = $row['units'];
		$n+=1;
echo "<tr>";                
echo "<tr><td style='border-collapse: collapse; border:solid 1px #000; padding:5px' align='center' valign='center' >".$n."</td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000;padding:5px' align='left' >" .$productname. "</td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000;padding:5px' align='center'   >" .$product_qty. "</td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000;padding:5px' align='center'>" .$units. "</td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000;padding:5px' align='center'  > " .number_format($ratesperproduct, 2, '.', ''). "</td>";
if($inclusive != '0')
{
echo "<td style='border-collapse: collapse; border:solid 1px #000;padding:5px' align='center'>" .$vat. "</td>";
}
echo "<td style='border-collapse: collapse; border:solid 1px #000; padding:10px' align='right' > " .number_format($Amount, 2, '.', ''). "</td>";
echo "</tr>";
 }

echo "<tr>";
if($inclusive != '0')
{

echo "<td colspan='4' class='white'></td>";
}
else
{
	echo "<td colspan='3' class='white'></td>";

}
echo "<td class='cyan white-text'  colspan='2'  align='center' style='border-collapse: collapse; border:solid 1px #000;padding:5px'><b>Grand Total</b></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000; padding-right:10px' align='right'><b><span><img src='images/rupee.jpg' /> </span> ".number_format($subtotal, 2, '.', '')."</b></td>";
echo "</tr>";
echo "</tbody>";
echo "</table>";
echo "<div class='invoice-footer'>";
echo "<div class='row'>";
echo "<div class='col12 s12 m12 l6  lineheight'>";
echo "<table style='width:100%' style='word-spacing:1px'>";
echo "<tr>";
echo "<td colspan='3'><b>Term and Condition:</b> </td>";
echo "</tr>";
echo "<tr>";
echo "<td valign='top'>Tin No</td>";
echo "<td>:</td>";
echo "<td>".$tinno."</td>";
echo "<td></td>";
echo "</tr>";
echo "<tr>";
echo "<td valign='top'>CST No</td>";
echo "<td>:</td>";
echo "<td>".$cstno."</td>";
echo "</tr>";
echo "<tr>";
echo "<td valign='top'>Payment</td>";
echo "<td>:</td>";
echo "<td>".$payment."</td>";
echo "</tr>";
echo "<tr>";
echo "<td valign='top'>Delivery</td>";
echo "<td>:</td>";
echo "<td>Door Delivery to our " .$place. " Site  </td>";
echo "</tr>";
echo "<tr>";
echo "<td valign='top'>Loading & Vat</td>";
echo "<td>:</td>";
echo "<td>".$vat_o."</td>";
echo "</tr>";
if($tpwords != '')
{
echo "<tr>";
echo "<td valign='top'>Transportation</td>";
echo "<td>:</td>";
echo "<td>".$tpwords."</td>";
echo "</tr>";
}

echo "<tr><td colspan='3'>Please Supply by " .$ddate. " at our ".$place. " Site </td></tr>";
echo "<tr>";



echo "</table>";
echo "<table style='width:100%'>";
echo "<tr>";
echo "<td style='width:38%' valign='top'>Site contact Person </td>";
echo "<td style='width:5%'>:</td>";
echo "<td style='width:30%'>".$contactname."</td>";
echo "<td  style='width:5%'>:</td>";
echo "<td  style='width:22%'>".$mobileno."</td>";
echo "</tr>";
echo "<tr>";
echo "<td valign='top'>Site address</td>";
echo "<td>:</td>";
echo "<td style='width:auto' colspan='4'><b>".$addressdispname."</b></td>";

echo "</tr>";
echo "<tr>";
echo "<td></td>";
echo "<td></td>";
echo "<td style='width:auto' colspan='4'>".$address."</td>";
echo "</tr>";
echo "</table>";
echo " </div>
<div class='col12 s12 m6 l6 left '>
<img src='images/anisa_digisign.jpg'  />
</div>
</div>
</div>
</div></div>
<tbody>
   </td></tr>
   </tbody>
  
</table>";
$msg = "IP: " . $ip . " TIME: " . $time . " User: " .$_SESSION['st_username'].  " USERAGENT: " .$userAgent. "Action : Purchase order_".$po_no." Print Preview Open";
writeToLogFile($msg);
	 }
	 
	 else if($type == 'upvc')
	 {
echo "<div id='content' style='position:relative; width:600px; font-family:Times New Roman; font-size:13.5px;  margin:0 auto'>";
	 
    echo "<div>"; $result2 = mysql_query("SELECT * FROM  vendor_tbl where ven_compname='".mysql_real_escape_string($ven_id)."'",$conn);
	if($row2= mysql_fetch_array($result2))
	 {
		$ven_id1 = $row2['ven_id'];
	    $ven_compname = $row2['ven_compname'] ;
	    $ven_add1=$row2['ven_add1'];
		$ven_add2=$row2['ven_add2'];
	    $ven_city=$row2['ven_city'];
		$ven_pincode=$row2['ven_pincode'];
		$ven_state=$row2['ven_state'];
		$ven_country=$row2['ven_country'];
		 
		}
		
	 
		$result = mysql_query("SELECT * FROM project_details where project_id=" .$project_id,$conn);
	if($row= mysql_fetch_array($result))
	 {
	    $project_name = $row['project_name'];
	    $place=$row['place'];
		$tinno=$row['tinno'];
	    $cstno=$row['cstno'];
		$address=$row['address']; 
		$city=$row['city'];
		$addressdispname=$row['addressdispname']; 
		$projectshort=$row['projectshort']; 
		$projectsingle=$row['projectsingle']; 	
			
		}
		else
		{
			echo "Error";
	 }
		 

echo  "<div align='right'><b>" .$po_date. "</b><br /></div>";
echo "<b>F/PUR/SIS-PO/".$projectsingle."/".$po_no. "/". $curYear."-".$nexYear."</b><br />";
echo "<b>To:</b><br />";
echo "<div style='width:250px; height:auto'><b>M/S.".$row2['ven_compname']. "</b></div>";
echo  $row2['ven_add1']. "<br/>";
echo "<div align='center' style='width:100%'><b>Kind Attention: " .$row2['ven_contactperson'] . "</b></div>";
if( $refno != '')
{
echo "Ref : ". $refno.  " - Amended Qty Without Glass Dated dated on " . $refdate."<br>";
}

if( $row['project_name'] != 'Head Office' or $row['project_name'] != 'S.I.S Safaa' )
{
echo "<b>Sub : &nbsp;</b>Our PO : " .$po_no." for window for our project <b> " .$project_name."&nbsp; Block &nbsp; ".$block."</b> at ".$place."&nbsp;".$city." .<br>";
echo  "We are pleased to place the Purchase order as per the details mentioned below for our Project " ;

}
else
{
	echo "<b>Sub : &nbsp;</b>Our PO : " .$po_no." for window for our <b> " .$project_name."</b> at ".$place."&nbsp;".$city." .<br>";
	echo  "We are pleased to place the Purchase order as per the details mentioned below for our  " ;

}
echo  "<b>" .$project_name. "</b>";
echo "the address and the contact person are mentioned below.";
echo "</div>";
echo "<table  cellpadding='0' cellspacing='0'  width='100%'  id='itemsTable' border='0' style='border-collapse: collapse; border: 1px solid black;' >";
echo "<tr>";
echo "<th style='border-collapse: collapse; border:solid 1px #000' align='center'>SNo</th>";
echo "<th style='border-collapse: collapse; border:solid 1px #000' align='center'><b>Code</b></th>";
echo "<th style='border-collapse: collapse; border:solid 1px #000' align='center'><b>width</b></th>";
echo "<th style='border-collapse: collapse; border:solid 1px #000' align='center'><b>height</b></th>";
echo "<th style='border-collapse: collapse; border:solid 1px #000' align='center'><b>unit</b></th>";
echo "<th style='border-collapse: collapse; border:solid 1px #000' align='center'><b>type</b></th>";
echo "<th colspan='2' style='border-collapse: collapse; border:solid 1px #000' align='center' ><b>Material Cost <span> in <img src='images/rupee.jpg' /> </span> </b></th>";
echo "<th colspan='2' style='border-collapse: collapse; border:solid 1px #000' align='center'><b> Installation Cost  in <span><img src='images/rupee.jpg' /> </span></b></th>";
echo "<th style='border-collapse: collapse; border:solid 1px #000; padding-right:10px' align='right'><b>Amount in <span><img src='images/rupee.jpg' /> </span> </b></th>";
echo  "</tr>";
echo "<tr>";
echo "<th style='border-collapse: collapse; border:solid 1px #000'></th>";
echo "<th style='border-collapse: collapse; border:solid 1px #000'>&nbsp;</th>";
echo "<th style='border-collapse: collapse; border:solid 1px #000'></th>";
echo "<th style='border-collapse: collapse; border:solid 1px #000'></th>";
echo "<th style='border-collapse: collapse; border:solid 1px #000'></th>";
echo "<th style='border-collapse: collapse; border:solid 1px #000'></th>";
echo "<th style='border-collapse: collapse; border:solid 1px #000'  ><b>Basic</b></th>";
echo  "<th style='border-collapse: collapse; border:solid 1px #000' align='right'><b>Total</b></th>";
echo "<th style='border-collapse: collapse; border:solid 1px #000'><b>Basic</b></th>";
echo "<th style='border-collapse: collapse; border:solid 1px #000; padding-right:10px' align='right'><b>Total</b></th>";
echo "<th style='border-collapse: collapse; border:solid 1px #000; padding-right:10px' align='right'><b></b></th>";
echo " </tr>";
$result_r = mysql_query("SELECT * FROM  purchaseorder_tbl where Po_no=" .$po_no,$conn);

while($row = mysql_fetch_array($result_r))
 {
	$productname = $row['product_name'] ;
	$product_qty=$row['product_qty'];
	$pro_height = $row['pro_height'];
	$pro_width = $row['pro_width'];
	$m_basic = $row['m_basic'];
	$m_total = $row['m_total'];
	$i_basic = $row['i_basic'];
	$i_total = $row['i_total'];
	$Amount = $row['Amount'];
	$product_code = $row['product_code'];
		$po_date = $row['po_date'];
		$productname=$row['product_name'];		
		$n+=1;
echo "<tr><td style='border-collapse: collapse; border:solid 1px #000'>".$n."</label></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000' align='center'><div class='input-field col s8'> ".$product_code."</div></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000'><div class='input-field col s8' align='center'>".$pro_width."</div></td>";
echo  "<td style='border-collapse: collapse; border:solid 1px #000'><div class='input-field col s8' align='center'>".$pro_height."</div></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000'><div class='input-field col s8' align='center'>".$product_qty."</div></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000'><div class='input-field col s8' align='center'>".$productname."</div></td>";
echo  "<td style='border-collapse: collapse; border:solid 1px #000'><div class='input-field col s8' align='center'>".number_format($m_basic, 2, '.', '')."</div></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000; padding-right:10px' align='right'><div class='input-field col s8'>".$m_total."</div></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000' align='center'><div class='input-field col s8'>".number_format($i_basic, 2, '.', '')."</div></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000; padding-right:10px' align='right'><div class='input-field col s8'>".number_format($i_total, 2, '.', '')."</div></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000; padding-right:10px' align='right'><div class='input-field col s8'>".number_format($Amount, 2, '.', '')."</div></td>";
echo "</tr>";
 }

echo "<tr>";
echo "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000'>BC</td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000; padding-right:10px' align='right'><div class='input-field col s9'>". number_format($basictotal, 2, '.', '')."</td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
echo "</tr>";
echo "<tr>";
echo "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000'>ED</td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000; padding-right:10px' align='right'><div class='input-field col s9'>".$ed."%</div></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
echo "<th style='border-collapse: collapse; border:solid 1px #000'></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
echo "</tr>";
echo "<tr>";
echo "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000'>Vat</td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000; padding-right:10px' align='right'><div class='input-field col s9'>".$tax."%</div></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
echo "<th style='border-collapse: collapse; border:solid 1px #000'></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
echo "</tr>";
echo "<tr>";
echo "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000'>Transpotation</td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000; padding-right:10px' align='right'><div class='input-field col s9'>". number_format($transportation, 2, '.', '')."</div></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
echo "<th style='border-collapse: collapse; border:solid 1px #000'></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
echo "</tr>";
echo "<tr>";
echo "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000'>ST</td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000; padding-right:10px' align='right'><div class='input-field col s9'>".$st."%</div></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000'>&nbsp;</td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
echo "<th style='border-collapse: collapse; border:solid 1px #000'></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
echo "</tr>";
echo "<tr>";	
echo "<td style='border-collapse: collapse; border:solid 1px #000'></td>";	
echo "<td style='border-collapse: collapse; border:solid 1px #000'></td>";	
echo "<td style='border-collapse: collapse; border:solid 1px #000'></td>";	
echo "<td style='border-collapse: collapse; border:solid 1px #000'></td>";	
echo "<td style='border-collapse: collapse; border:solid 1px #000'>Grand Total</td>";	
echo "<td style='border-collapse: collapse; border:solid 1px #000; padding-right:10px' align='right'><div class='input-field col s9'>". number_format($gt, 2, '.', '')."</div></td>";	
echo "<td style='border-collapse: collapse; border:solid 1px #000'>&nbsp;</td>";	
echo "<td style='border-collapse: collapse; border:solid 1px #000'>&nbsp;</td>";	
echo "<td style='border-collapse: collapse; border:solid 1px #000'>&nbsp;</td>";	
echo "<td style='border-collapse: collapse; border:solid 1px #000; padding-right:10px' align='right'><div class='input-field col s9'>". number_format($gt1, 2, '.', '')."</div></td>";	
echo "<td style='border-collapse: collapse; border:solid 1px #000; padding-right:10px' align='right'><div class='input-field col s9'> <span><img src='images/rupee.jpg' /> </span>  ". number_format($subtotal, 2, '.', '')."</div></td>";	
echo "</tr>";



echo "</tbody>";	
echo "</table>";
echo "<div class='invoice-footer'>";
echo "<div class='row'>";
echo "<div class='col12 s12 m12 l6 lineheight'>";

echo "<table style='width:100%' style='word-spacing:1px'>";
echo "<tr>";
echo "<td colspan='3'><b>Term and Condition:</b> </td>";
echo "</tr>";
echo "<tr>";
echo "<td valign='top'>Tin No</td>";
echo "<td>:</td>";
echo "<td>".$tinno."</td>";
echo "<td></td>";
echo "</tr>";
echo "<tr>";
echo "<td valign='top'>CST No</td>";
echo "<td>:</td>";
echo "<td>".$cstno."</td>";
echo "</tr>";
if($tpwords != '')
{
echo "<tr>";
echo "<td valign='top'>Transportation</td>";
echo "<td>:</td>";
echo "<td>".$tpwords."</td>";
echo "</tr>";
}

echo "</table>";
echo "<table style='width:100%'>";
echo "<tr>";
echo "<td style='width:38%' valign='top'>Site contact Person </td>";
echo "<td style='width:5%'>:</td>";
echo "<td style='width:30%'>".$contactname."</td>";
echo "<td  style='width:5%'>:</td>";
echo "<td  style='width:22%'>".$mobileno."</td>";
echo "</tr>";
echo "<tr>";
echo "<td valign='top'>Site address</td>";
echo "<td>:</td>";
echo "<td><b>".$addressdispname."</b></td>";
echo "</tr>";
echo "<tr>";
echo "<td></td>";
echo "<td></td>";
echo "<td style='width:auto' colspan='4'>".$address."</td>";
echo "</tr>";
echo "</table>";
echo " </div>
<div class='col12 s12 m6 l6 left '>
<img src='images/anisa_digisign.jpg'  />
</div>
</div>
</div>
</div>";
 echo "</div>";
$msg = "IP: " . $ip . " TIME: " . $time . " User: " .$_SESSION['st_username'].  " USERAGENT: " .$userAgent. "Action : Purchase order_".$po_no." Print Preview Open";
writeToLogFile($msg);

 
	 }
else if($type == 'steel')
	{
	echo "<div id='content' style='position:relative; width:600px; font-family:Times New Roman; font-size:13.5px;  margin:0 auto'>";
	 
    echo "<div>"; $result2 = mysql_query("SELECT * FROM  vendor_tbl where ven_compname='".mysql_real_escape_string($ven_id)."'",$conn);
	if($row2= mysql_fetch_array($result2))
	 {
		$ven_id1 = $row2['ven_id'];
	    $ven_compname = $row2['ven_compname'] ;
	    $ven_add1=$row2['ven_add1'];
		$ven_add2=$row2['ven_add2'];
	    $ven_city=$row2['ven_city'];
		$ven_pincode=$row2['ven_pincode'];
		$ven_state=$row2['ven_state'];
		$ven_country=$row2['ven_country'];
		 
		}
		
	 
		$result = mysql_query("SELECT * FROM project_details where project_id=" .$project_id,$conn);
	if($row= mysql_fetch_array($result))
	 {
	    $project_name = $row['project_name'];
	    $place=$row['place'];
		$tinno=$row['tinno'];
	    $cstno=$row['cstno'];
		$address=$row['address']; 
	$addressdispname=$row['addressdispname']; 
		$projectshort=$row['projectshort']; 
		$projectsingle=$row['projectsingle']; 	
			
		}
		else
		{
			echo "Error";
	 }


echo "<div align='right'><b>" .$po_date. "</b><br /></div>";
echo "<b>F/PUR/SIS-PO/".$projectsingle."/".$po_no. "/". $curYear."-".$nexYear."</b><br />&nbsp;<br/>";
echo "<b>To:</b><br />";
echo "<div style='width:250px; height:auto'><b>M/S.".$row2['ven_compname']. "</b></div>";
echo $row2['ven_add1']. "<br/>";
echo "<div align='center' style='width:100%'><b>Kind Attention: " .$row2['ven_contactperson'] . "</b></div>";
if( $refno != 'none')
{
echo "<b>Ref : </b>". $refno.  " <b>Dated </b> on " .$refdate."<br>";
}
echo "<div style='line-height:200%'><b>Sub : PO for " .$subject. ".</div></b>";
echo  "We are pleased to place the Purchase order as per the details mentioned below for our  " ;
if( $row['project_name'] != 'Head Office' or $row['project_name'] != 'S.I.S Safaa' )
{
echo  " Project <b> " .$project_name."&nbsp; Block &nbsp; ".$block."</b> ";
}
else
{
	echo  "<b> " .$project_name. "</b> ";
}
echo " the address and the contact person are mentioned below.";
echo "</div>";
 echo "</br>&nbsp;";
echo "<table  cellpadding='0' cellspacing='0'  width='100%'  id='itemsTable' border='0' style='border-collapse: collapse; border: 1px solid black;' >";
echo "<thead>";
echo  "<tr>";
echo "<th style='border-collapse: collapse; border:solid 1px #000;padding:5px;width:10px' align='center'><b>No</b></th>";
echo "<th style='border-collapse: collapse; border:solid 1px #000; padding:5px;width:280px' align='left'><b>Description</b></th>";
echo  "<th style='border-collapse: collapse; border:solid 1px #000;padding:5px;;width:100px' align='center'><b>Detail</b></th>";
echo "<th style='border-collapse: collapse; border:solid 1px #000;padding:5px;width:80px' align='center'><b>Tonnage</b></th>";
echo "<th style='border-collapse: collapse; border:solid 1px #000;padding:5px;width:100px' align='center'><b>Price in <span><img src='images/rupee.jpg' /> </span> </b></th>";
if($inclusive != '0')
{
echo "<th style='border-collapse: collapse; border:solid 1px #000;padding:5px;width:80px' align='center'><b>Vat in %</b></th>";
}
echo "<th style='border-collapse: collapse; border:solid 1px #000; padding-right:5px;width:270px' align='right'><b>Total in <span><img src='images/rupee.jpg' /> </span> </b></th>";
echo "</tr>";
echo "</thead>";
echo "<tbody>";
$result_r = mysql_query("SELECT * FROM  purchaseorder_tbl where Po_no=" .$po_no,$conn);
		
while($row = mysql_fetch_array($result_r))
 {
	$productname = $row['product_name'] ;
	$product_qty=$row['product_qty'];
	$ratesperproduct = $row['ratesperproduct'];
	$vat = $row['vat'];
	$Amount = $row['Amount'];
		$po_date = $row['po_date'];
		$brand = $row['brand'];
		$details= $row['details'];
		$n+=1;
echo "<tr>";                
echo "<tr><td style='border-collapse: collapse; border:solid 1px #000' align='center'>".$n."</td>";
echo  "<td style='border-collapse: collapse; border:solid 1px #000;padding:5px' align='center'>" .$productname. "</td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000;padding:5px' align='center'>" .$details. "</td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000;padding:5px' align='center'>" .$product_qty. "</td>";
echo  "<td style='border-collapse: collapse; border:solid 1px #000;padding:5px' align='center'> " .number_format($ratesperproduct, 2, '.', ''). "</td>";
if($inclusive != '0')
{
echo  "<td style='border-collapse: collapse; border:solid 1px #000;padding:5px' align='center'>" .$vat. "</td>";	
}
echo "<td style='border-collapse: collapse; border:solid 1px #000; padding-right:5px' align='right' >" .number_format($Amount, 2, '.', ''). "</td>";
echo  "</tr>";
 }

echo "<tr>";
if($inclusive != '0')
{
echo "<td colspan='5' class=''></td>";
}
else
{
	echo "<td colspan='4' class=''></td>";

}
echo  "<td class='' align='center' >Grand Total</td>";
echo "<td class='' align='right'> <span><img src='images/rupee.jpg' /> </span> ".number_format($subtotal, 2, '.', '')."</td>";
echo "</tr>";
echo "</tbody>";
echo  "</table>";

echo "<div class='invoice-footer'>";
echo "<div class='row'>";
echo "<div class='col12 s12 m12 l6  lineheight'>";

echo "<table style='width:100%' style='word-spacing:1px'>";
echo "<tr>";
echo "<td colspan='3'><b>Term and Condition:</b> </td>";
echo "</tr>";
echo "<tr>";
echo "<td valign='top'>Tin No</td>";
echo "<td>:</td>";
echo "<td>".$tinno."</td>";
echo "<td></td>";
echo "</tr>";
echo "<tr>";
echo "<td valign='top'>CST No</td>";
echo "<td>:</td>";
echo "<td>".$cstno."</td>";
echo "</tr>";
echo "<tr>";
echo "<td valign='top'>Payment</td>";
echo "<td>:</td>";
echo "<td>".$payment."</td>";
echo "</tr>";
echo "<tr>";
echo "<td valign='top'>Delivery</td>";
echo "<td>:</td>";
echo "<td>Door Delivery to our " .$place. " Site  </td>";
echo "</tr>";
echo "<tr>";
echo "<td valign='top'>Loading & Vat</td>";
echo "<td>:</td>";
echo "<td>".$vat_o."</td>";
echo "</tr>";
if($tpwords != '')
{
echo "<tr>";
echo "<td valign='top'>Transportation</td>";
echo "<td>:</td>";
echo "<td>".$tpwords."</td>";
echo "</tr>";
}
echo "<tr><td colspan='3'>Please Supply by " .$ddate. " at our ".$place. " Site </td></tr>";
echo "</table>";
echo "<table style='width:100%'>";
echo "<tr>";
echo "<td style='width:38%' valign='top'>Site contact Person </td>";
echo "<td style='width:5%'>:</td>";
echo "<td style='width:30%'>".$contactname."</td>";
echo "<td  style='width:5%'>:</td>";
echo "<td  style='width:22%'>".$mobileno."</td>";
echo "</tr>";
echo "<tr>";
echo "<td valign='top'>Site address</td>";
echo "<td>:</td>";
echo "<td style='width:auto' colspan='4'><b>".$addressdispname."</b></td>";

echo "</tr>";
echo "<tr>";
echo "<td></td>";
echo "<td></td>";
echo "<td style='width:auto' colspan='4'>".$address."</td>";
echo "</tr>";
echo "</table>";
echo " </div>
<div class='col12 s12 m6 l6 left '>
<img src='images/anisa_digisign.jpg'  /></div>
</div>
</div>
</div></div>";
$msg = "IP: " . $ip . " TIME: " . $time . " User: " .$_SESSION['st_username'].  " USERAGENT: " .$userAgent. "Action : Purchase order_".$po_no." Print Preview Open";
writeToLogFile($msg);

	 }
	 
else if($type == 'door')
	{
	echo "<div id='content' style='position:relative; width:600px; font-family:Times New Roman; font-size:13.5px;  margin:0 auto'>";
	 
    echo "<div>"; $result2 = mysql_query("SELECT * FROM  vendor_tbl where ven_compname='".mysql_real_escape_string($ven_id)."'",$conn);
	if($row2= mysql_fetch_array($result2))
	 {
		$ven_id1 = $row2['ven_id'];
	    $ven_compname = $row2['ven_compname'] ;
	    $ven_add1=$row2['ven_add1'];
		$ven_add2=$row2['ven_add2'];
	    $ven_city=$row2['ven_city'];
		$ven_pincode=$row2['ven_pincode'];
		$ven_state=$row2['ven_state'];
		$ven_country=$row2['ven_country'];
		 
		}
		
	 
		$result = mysql_query("SELECT * FROM project_details where project_id=" .$project_id,$conn);
	if($row= mysql_fetch_array($result))
	 {
	    $project_name = $row['project_name'];
	    $place=$row['place'];
		$tinno=$row['tinno'];
	    $cstno=$row['cstno'];
		$address=$row['address']; 
		$addressdispname=$row['addressdispname']; 
		$projectshort=$row['projectshort']; 	
		$projectsingle=$row['projectsingle']; 
				}
		else
		{
			echo "Error";
	 }
		 

echo  "<div id='res' style='display:none'></div>";

echo "<div align='right'><b>" .$po_date. "</b><br /></div>";
echo "<b>F/PUR/SIS-PO/".$projectsingle."/".$po_no. "/". $curYear."-".$nexYear."</b><br />&nbsp;<br/>";
echo "<b>To:</b><br />";
echo "<div style='width:250px; height:auto'><b>M/S.".$row2['ven_compname']. "</b></div>";
echo $row2['ven_add1']. "<br/>";
echo "<div align='center' style='width:100%'><b>Kind Attention: " .$row2['ven_contactperson'] . "</b></div>";
if( $refno != 'none')
{
echo "<b>Ref : </b>". $refno.  " <b>Dated </b> on " .$refdate."<br>";
}
echo "<div style='line-height:200%'><b>Sub : PO for " .$subject. ".</div></b>";
echo  "We are pleased to place the Purchase order as per the details mentioned below for our " ;
if( $row['project_name'] != 'Head Office' or $row['project_name'] != 'S.I.S Safaa' )
{
echo  " Project  <b> " .$project_name."&nbsp; Block &nbsp; ".$block."</b> ";
}
else
{
	echo  "<b> " .$project_name. "</b> ";
}
echo " the address and the contact person are mentioned below.";
echo "</div>";
 echo "</br>&nbsp;";

echo "<table  cellpadding='0' cellspacing='0'  width='100%'  id='itemsTable' border='0' style='border-collapse: collapse; border: 1px solid black;' >";
echo "<thead>";
echo "<tr>";
echo "<th style='border-collapse: collapse; border:solid 1px #000; ' align='center'><b>SNo</b></th>";
echo "<th style='border-collapse: collapse; border:solid 1px #000; ' align='center'><b>Description</b></th>";
echo "<th style='border-collapse: collapse; border:solid 1px #000; ' align='center'><b>Size</b></th>";
echo "<th style='border-collapse: collapse; border:solid 1px #000; ' align='center'><b>Qty</b></th>";
echo "<th style='border-collapse: collapse; border:solid 1px #000; ' align='center'><b>CFT</b></th>";
echo "<th style='border-collapse: collapse; border:solid 1px #000; ' align='center'><b>Price in <span><img src='images/rupee.jpg' /> </span> </b> </th>";
echo "<th style='border-collapse: collapse; border:solid 1px #000; padding-right:10px' align='right'><b>Total in <span><img src='images/rupee.jpg' /> </span> </b></th>";
echo "</tr>";echo "</thead>";
echo "<tbody>";
$result_r = mysql_query("SELECT * FROM  purchaseorder_tbl where Po_no=" .$po_no,$conn);
while($row = mysql_fetch_array($result_r))
 {
	$productname = $row['product_name'] ;
	$product_code = $row['product_code'] ;
	$product_qty=$row['product_cft'];
	$product_qty1=$row['product_qty'];
	$ratesperproduct = $row['ratesperproduct'];
	$vat = $row['vat'];
	$Amount = $row['Amount'];
		$size = $row['size'];
		$po_date = $row['po_date'];
		$brand = $row['brand'];	
		$n+=1;
echo "<tr>";                
echo "<tr><td style='border-collapse: collapse; border:solid 1px #000; ' align='center'>".$n."</td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000; ' align='center'>" .$productname. "</td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000; ' align='center'>" .$size. "</td>";
echo "<td>" .$product_qty1. "</td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000; ' align='center'>" .$product_qty. "</td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000; ' align='center'> " .number_format($ratesperproduct, 2, '.', ''). "</td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000; ' align='center'>" .number_format($Amount, 2, '.', ''). "</td>";
echo "</tr>";
 }
echo "<tr>";
echo "<td colspan='6' class='white'></td>";
echo "<td class='cyan white-text'>SubTotal</td>";
echo "<td class='cyan strong white-text'>".number_format($stotal, 2, '.', '')."</td>";
echo "</tr>";
echo "<tr>";
echo "<td colspan='6' class='white'></td>";
echo "<td class=''>vat</td>";
echo "<td class=''>".$tax."</td>";
echo "</tr>";
echo "<tr>";
echo "<td colspan='6' class='white'></td>";
echo "<td class='cyan white-text'>Grand Total</td>";
echo "<td class='cyan strong white-text'><span><img src='images/rupee.jpg' /></span>".number_format($subtotal, 2, '.', '')."</td>";
echo "</tr>";
echo "</tbody>";
echo  "</table>";

echo "<div class='invoice-footer'>";
echo "<div class='row'>";
echo "<div class='col12 s12 m12 l6  lineheight'>";
echo "<table style='width:100%' style='word-spacing:1px'>";
echo "<tr>";
echo "<td colspan='3'><b>Term and Condition:</b> </td>";
echo "</tr>";
echo "<tr>";
echo "<td valign='top'>Tin No</td>";
echo "<td>:</td>";
echo "<td>".$tinno."</td>";
echo "<td></td>";
echo "</tr>";
echo "<tr>";
echo "<td valign='top'>CST No</td>";
echo "<td>:</td>";
echo "<td>".$cstno."</td>";
echo "</tr>";
echo "<tr>";
echo "<td valign='top'>Payment</td>";
echo "<td>:</td>";
echo "<td>".$payment."</td>";
echo "</tr>";
echo "<tr>";
echo "<td valign='top'>Delivery</td>";
echo "<td>:</td>";
echo "<td>Door Delivery to our " .$place. " Site  </td>";
echo "</tr>";
echo "<tr>";
echo "<td valign='top'>Loading & Vat</td>";
echo "<td>:</td>";
echo "<td>".$vat_o."</td>";
echo "</tr>";
if($tpwords != '')
{
echo "<tr>";
echo "<td valign='top'>Transportation</td>";
echo "<td>:</td>";
echo "<td>".$tpwords."</td>";
echo "</tr>";
}
echo "<tr><td colspan='3'>Please Supply by " .$ddate. " at our ".$place. " Site </td></tr>";
echo "</table>";
echo "<table style='width:100%'>";
echo "<tr>";
echo "<td style='width:38%' valign='top'>Site contact Person </td>";
echo "<td style='width:5%'>:</td>";
echo "<td style='width:30%'>".$contactname."</td>";
echo "<td  style='width:5%'>:</td>";
echo "<td  style='width:22%'>".$mobileno."</td>";
echo "</tr>";
echo "<tr>";
echo "<td valign='top'>Site address</td>";
echo "<td>:</td>";
echo "<td><b>".$addressdispname."</b></td>";
echo "<td></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
echo "<tr>";
echo "<td></td>";
echo "<td></td>";
echo "<td style='width:300px'>".$address."</td>";
echo "<td></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
echo "</table>";
echo " </div>
<div class='col12 s12 m6 l6 left '>
<img src='images/anisa_digisign.jpg'  />
</div>
</div>
</div>
</div></div>";
$msg = "IP: " . $ip . " TIME: " . $time . " User: " .$_SESSION['st_username'].  " USERAGENT: " .$userAgent. "Action : Purchase order_".$po_no." Print Preview Open";
writeToLogFile($msg);
	 }
	 


	 
 ?>
