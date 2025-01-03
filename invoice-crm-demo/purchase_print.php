
<?php

require('connect.php');
include "writelog.php";

date_default_timezone_set('Asia/Calcutta'); 
$time = date('Y-m-d H:i:s');
$po_no=$_GET['po_no'];
$po_year=$_GET['po_year'];
$ip = getenv('REMOTE_ADDR');
$userAgent = getenv('HTTP_USER_AGENT');
$referrer = getenv('HTTP_REFERER');
$query = getenv('QUERY_STRING');

$n = 0;
"<style>
body
{
	font-size:11px; font-family:cambria;
}
	</style>
";


$result1 = mysql_query("SELECT * FROM  purchase_master where po_year='".$po_year."' and po_no=" .$po_no,$conn);
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
$type = $row1['type'];
$po_date=$row1['po_date'];
$refno = $row1['refno'];
$refdate=$row1['refdate'];
$block=$row1['Block'];
$payment=$row1['payment'];
$subject=$row1['subject'];
$contactname=$row1['contactname'];
$mobileno=$row1['mobileno'];
$stotal = $row1['stotal'];
$inclusive = $row1['inclusive'];
$tpwords = $row1['tpwords'];
$folder = $row1['folder'];
$curYear = $row1['po_year'];
$nexYear = $row1['po_year'] + 1;

	 }
if($type == 'standard')
{
	
    echo "<div style='position:relative; width:100%; margin-top:100px;word-spacing:1px'>";
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
		$ven_country=$row2['ven_country']; }
		
	   
		$result = mysql_query("SELECT * FROM project_details where project_id=" .$project_id,$conn);
	if($row= mysql_fetch_array($result))
	 {
	    $project_name = $row['project_name'] ;
	    $place=$row['place'];
		$tinno=$row['tinno'];
	    $cstno=$row['cstno'];
		$address=$row['address'];  
		$projectshort=$row['projectshort'];  
		$addressdispname=$row['addressdispname']; 
		$projectsingle=$row['projectsingle']; 
		$city=$row['city'];	
			
		}
		else
		{
			echo "Error";
	 }


echo  "<div id='res' style='display:none'></div>";
echo "<div align='right'><b>" .$po_date. "</b><br /></div>";
echo "<b>F/PUR/SIS-PO/".$projectsingle."/".$po_no. "/". $curYear."-".$nexYear."</b><br />";
echo "<b>To:</b><br />";
echo "<b>M/S.".$row2['ven_compname'] ."</b><br/>";
echo "<div style='width:250px; height:auto'>".$row2['ven_add1']. "</div>";
echo "<div align='center' style='width:100%'><b>Kind Attention: " .$row2['ven_contactperson'] . "</b></div>";
if( $refno != '')
{
echo "<b>Ref : </b>". $refno.  " <b>Dated </b> on " .$refdate."<br>";
}
echo "<div style='line-height:200%'><b>Sub : PO for " .$subject. ".</div></b>";
echo  "We are pleased to place the Purchase order as per the details mentioned below for our " ;
if( $row['project_name'] != 'Head Office')
{
echo  " Project <b> " .$project_name."&nbsp; Block &nbsp; ".$block."</b> ";
}
else
{
	echo  "<b> " .$project_name. "</b> ";
}
echo " the address and the contact person are mentioned below.";
echo "</div>";
echo "<div id='space'></div>";
echo "</br>&nbsp;";
echo "<table  cellpadding='0' cellspacing='0'  width='95%'  id='itemsTable' border='0' style='border-collapse: collapse; border: 1px solid black; font-siz:13px; font-family:cambria' >";
echo "<thead>";
echo "<tr>";
echo "<th style='border-collapse: collapse; border:solid 1px #000;  padding:5px; width:20px; font-siz:13px; font-family:cambria' align='center'><b>No</b></th>";
if($row1['folder'] == 'Switches')
{
echo "<th style='border-collapse: collapse; border:solid 1px #000;  padding:5px;width:80px; font-siz:13px; font-family:cambria' align='center'><b>Code</b></th>";
}
echo "<th style='border-collapse: collapse; border:solid 1px #000;  padding:5px;width:320px; font-siz:13px; font-family:cambria' align='left'><b>Description</b></th>";
echo "<th style='border-collapse: collapse; border:solid 1px #000; padding:5px; width:50px; font-siz:13px; font-family:cambria' align='center'><b>Qty</b></th>";
echo "<th style='border-collapse: collapse; border:solid 1px #000;  padding:5px;width:80px; font-siz:13px; font-family:cambria' align='center'><b>Units</b></th>";

echo "<th style='border-collapse: collapse; border:solid 1px #000;  padding:5px;width:100px; font-siz:13px; font-family:cambria' align='center'><b>Price in <span><img src='images/rupee.jpg' /> </span> </b></th>";
if($inclusive != '0')
{
echo "<th style='border-collapse: collapse; border:solid 1px #000;  padding:5px;width:50px; font-siz:13px; font-family:cambria' align='center'><b>Vat in %</b></th>";
}
echo "<th style='border-collapse: collapse; border:solid 1px #000;  padding:5px;width:180px; font-siz:13px; font-family:cambria' align='right' ><b>Total  in <span><img src='images/rupee.jpg' /> </span> </b></th>";
echo "</tr>";
echo "</thead>";
echo "<tbody>";
$result_r = mysql_query("SELECT * FROM  purchaseorder_tbl where  po_year='".$po_year."' and  Po_no=" .$po_no,$conn);
while($row = mysql_fetch_array($result_r))
 {
	$productname = $row['product_name'];
	$product_qty=$row['product_qty'];
	$ratesperproduct = $row['ratesperproduct'];
	$vat = $row['vat'];
	$Amount = $row['Amount'];
	$po_date = $row['po_date'];
	$units = $row['units'];
	$product_code = $row['product_code'];	
		$n+=1;
echo "<tr>";                
echo "<tr><td style='border-collapse: collapse; border:solid 1px #000;  padding:5px; font-siz:13px; font-family:cambria' align='center'>".$n."</td>";
if($row1['folder'] == 'Switches')
{
echo "<td style='border-collapse: collapse; border:solid 1px #000; padding:5px; font-siz:13px; font-family:cambria' align='left' valign='middle'>" .$product_code. "</td>";
 }
echo "<td style='border-collapse: collapse; border:solid 1px #000;  padding:5px; font-siz:13px; font-family:cambria' align='left' valign='middle'>" .$productname. "</td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000; padding:5px; font-siz:13px; font-family:cambria' align='center'   >" .$product_qty. "</td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000;  padding:5px; font-siz:13px; font-family:cambria' align='center'>" .$units. "</td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000;  padding:5px; font-siz:13px; font-family:cambria' align='center'  > " .formatInIndianStyle($ratesperproduct). "</td>";
if($inclusive != '0')
{
echo "<td style='border-collapse: collapse; border:solid 1px #000; padding:5px; font-siz:13px; font-family:cambria' align='center'>" .$vat. "</td>";
}
echo "<td style='border-collapse: collapse; border:solid 1px #000;  padding:5px; font-siz:13px; font-family:cambria' align='right' > " .formatInIndianStyle($Amount). "</td>";
echo "</tr>";
 }

echo "<tr>";
if($inclusive != '0')
{

echo "<td colspan='1' class='white'></td>";
}
 if($folder == 'Switches')
{
	echo "<td colspan='4' class='white'></td>";

}
else
{
	echo "<td colspan='3' class='white'></td>";

}
echo "<td class='cyan white-text'  colspan='2'  align='center' style='border-collapse: collapse; border:solid 1px #000;padding:5px'><b>Grand Total</b></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000; padding-right:10px; font-siz:13px; font-family:cambria' align='right'><b><span><img src='images/rupee.jpg' /> </span> ".formatInIndianStyle($subtotal)."</b></td>";
echo "</tr>";
echo "</tbody>";
echo "</table>";
echo "<div clas='space'>&nbsp;</div>";
echo "<div class='invoice-footer' style='word-spacing:1px'>";
$html.="<div class='row'>";
echo "<div class='col12 s12 m12 l6 leftspace lineheight'>";
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
echo "<td>Door Delivery to our " .$place;
if( $project_name != 'Head Office')
{
echo "Site.";
}
else
{
	echo ".";
}
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

echo "<tr><td colspan='3'>Please Supply by " .$ddate. " at our ".$place;
if( $project_name != 'Head Office')
{
echo "Site.";
}
else
{
	echo ".";
}
echo "</td></tr>";

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
echo "<td><b>".$addressdispname. "</b></td>";
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
echo "</div>
<div class='col12 s12 m6 l6 left leftspace lineheight'>
<img src='images/anisa_digisign.jpg'  />
</div>
</div>
</div>
</div></div>";

}
else if($type == 'upvc')
{	
echo "<div style='position:relative; width:100%;'>";
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
	    $project_name = $row['project_name'] ;
	    $place=$row['place'];
		$tinno=$row['tinno'];
	    $cstno=$row['cstno'];
		$address=$row['address'];
		$projectshort=$row['projectshort'];  
		$addressdispname=$row['addressdispname']; 
		$projectsingle=$row['projectsingle']; 		
		}
		else
		{
			echo "Error";
	 }

		
		
		
echo  "<div align='right'><b>" .$po_date. "</b><br /></div>";
echo "<b>F/PUR/SIS-PO/".$projectsingle."/".$po_no. "/". $curYear."-".$nexYear."</b><br />";
echo "<b>To:</b><br />";
echo "<b>M/S.".$row2['ven_compname'] ."</b><br/>";
echo "<div style='width:250px; height:auto'>".$row2['ven_add1']. "</div>";
echo "<div align='center' style='width:100%'><b>Kind Attention: " .$row2['ven_contactperson'] . "</b></div>";
if( $refno != '')
{
echo "Ref : ". $refno.  " - Amended Qty Without Glass Dated dated on " . $refdate."<br>";
}


if( $row['project_name'] != 'Head Office')
{
echo "<b>Sub : &nbsp;</b>Our PO : " .$po_no." for window for our project <b> " .$project_name."&nbsp; Block &nbsp; ".$block."</b> at ".$place."&nbsp;".$row['city']." .<br>";
echo  "We are pleased to place the Purchase order as per the details mentioned below for our Project " ;

}
else
{
	echo "<b>Sub : &nbsp;</b>Our PO : " .$po_no." for window for our <b> " .$project_name."</b> at ".$place."&nbsp;".$row['city']." .<br>";
	echo  "We are pleased to place the Purchase order as per the details mentioned below for our " ;

}
echo  "<b>" .$project_name. "</b>";
echo "the address and the contact person are mentioned below.";
echo "</div>";
echo "<div id='space'></div>";
echo "<div id='table-responsive'>";
echo "<table  cellpadding='0' cellspacing='0'  width='95%'  id='itemsTable' border='0' style='border-collapse: collapse; border: 1px solid black;' >";
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
echo "<th style='border-collapse: collapse; border:solid 1px #000'><b>Basic</b></th>";
echo  "<th style='border-collapse: collapse; border:solid 1px #000' align='right'><b>Total</b></th>";
echo "<th style='border-collapse: collapse; border:solid 1px #000'><b>Basic</b></th>";
echo "<th style='border-collapse: collapse; border:solid 1px #000; padding-right:10px' align='right'><b>Total</b></th>";
echo "<th style='border-collapse: collapse; border:solid 1px #000; padding-right:10px' align='right'><b>&nbsp;</b></th>";
echo " </tr>";
$result_r = mysql_query("SELECT * FROM  purchaseorder_tbl where  po_year='".$po_year."' and  Po_no=" .$po_no,$conn);

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
echo  "<td style='border-collapse: collapse; border:solid 1px #000'><div class='input-field col s8' align='center'>".formatInIndianStyle($m_basic)."</div></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000; padding-right:10px' align='right'><div class='input-field col s8'>".$m_total."</div></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000' align='center'><div class='input-field col s8'>".formatInIndianStyle($i_basic)."</div></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000; padding-right:10px' align='right'><div class='input-field col s8'>".formatInIndianStyle($i_total)."</div></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000; padding-right:10px' align='right'><div class='input-field col s8'>".formatInIndianStyle($Amount)."</div></td>";
echo "</tr>";
 }

echo "<tr>";
echo "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000'>BC</td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000; padding-right:10px' align='right'><div class='input-field col s9'>". formatInIndianStyle($basictotal)."</td>";
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
echo "<td style='border-collapse: collapse; border:solid 1px #000; padding-right:10px' align='right'><div class='input-field col s9'>". formatInIndianStyle($transportation)."</div></td>";
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
echo "<td style='border-collapse: collapse; border:solid 1px #000; padding-right:10px' align='right'><div class='input-field col s9'>". formatInIndianStyle($gt)."</div></td>";	
echo "<td style='border-collapse: collapse; border:solid 1px #000'>&nbsp;</td>";	
echo "<td style='border-collapse: collapse; border:solid 1px #000'>&nbsp;</td>";	
echo "<td style='border-collapse: collapse; border:solid 1px #000'>&nbsp;</td>";	
echo "<td style='border-collapse: collapse; border:solid 1px #000; padding-right:10px' align='right'><div class='input-field col s9'>". formatInIndianStyle($gt1)."</div></td>";	
echo "<td style='border-collapse: collapse; border:solid 1px #000; padding-right:10px' align='right'><div class='input-field col s9'> <span><img src='images/rupee.jpg' /> </span>  ". formatInIndianStyle($subtotal)."</div></td>";	
echo "</tr>";



echo "</tbody>";	
echo "</table>";
echo "<div class='col12 s12 m12 l6 leftspace lineheight'>";
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
echo "<tr><td colspan='3'>Please Supply by " .$ddate. " at our ".$place;
if( $project_name != 'Head Office')
{
echo "Site.";
}
else
{
	echo ".";
}

echo "</td></tr>";
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
	echo "</div>";
echo "</div>
<div class='col12 s12 m6 l6 left leftspace lineheight'>
<img src='images/anisa_digisign.jpg'  />
</div>
</div>
</div>
</div></div>";

}
else if($type == 'steel')
{
	
	
    echo "<div style='position:relative; width:100%; margin-top:100px; '>";
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
		$ven_country=$row2['ven_country']; }
		
	   
		$result = mysql_query("SELECT * FROM project_details where project_id=" .$project_id,$conn);
	if($row= mysql_fetch_array($result))
	 {
	    $project_name = $row['project_name'] ;
	    $place=$row['place'];
		$tinno=$row['tinno'];
	    $cstno=$row['cstno'];
		$address=$row['address'];  
		$projectshort=$row['projectshort'];  
		$addressdispname=$row['addressdispname']; 
		$projectsingle=$row['projectsingle']; 	
		$city=$row['city'];	
			
		}
		else
		{
			echo "Error";
	 }

		 

echo  "<div align='right'><b>" .$po_date. "</b><br /></div>";
echo "<b>F/PUR/SIS-PO/".$projectsingle."/".$po_no. "/". $curYear."-".$nexYear."</b><br />";
echo "<b>To:</b><br />";
echo "<b>M/S.".$row2['ven_compname'] ."</b><br/>";
echo "<div style='width:250px; height:auto'>".$row2['ven_add1']. "</div><br/>";
echo "<div align='center' style='width:100%'><b>Kind Attention: " .$row2['ven_contactperson'] . "</b></div>";
if( $refno != '')
{
echo "<b>Ref : </b>". $refno.  " <b>Dated </b> on " .$refdate."<br>";
}
echo "<b>Sub : PO for " .$subject. ".</b><br>";
echo  "We are pleased to place the Purchase order as per the details mentioned below for our " ;
if( $row['project_name'] != 'Head Office')
{
echo  "Project  <b>" .$project_name."&nbsp; Block  ".$block."</b> ";
}
else
{
	echo  "<b> " .$project_name. "</b> ";
}
echo " the address and the contact person are mentioned below.";
echo "</div>";
echo "<div id='space'>&nbsp;</div>";
echo "<table  cellpadding='0' cellspacing='0'  width='95%'  id='itemsTable' border='0' style='border-collapse: collapse; border: 1px solid black;' >";
echo "<thead>";
echo  "<tr>";
echo "<th style='border-collapse: collapse; border:solid 1px #000;width:40px; height:30px' align='center'><b>No</b></th>";
echo "<th style='border-collapse: collapse; border:solid 1px #000; width:180px; height:30px; padding-left:5px' align='center'><b>Description</b></th>";
echo  "<th style='border-collapse: collapse; border:solid 1px #000;width:100px; height:30px' align='center'><b>Detail</b></th>";
echo "<th style='border-collapse: collapse; border:solid 1px #000;width:80px; height:30px' align='center'><b>Tonnage</b></th>";
echo "<th style='border-collapse: collapse; border:solid 1px #000;width:140px; height:30px' align='center'><b>Rate per ton in <span><img src='images/rupee.jpg' /> </span> </b></th>";
if($inclusive != '0')
{
echo "<th style='border-collapse: collapse; border:solid 1px #000;width:70px' align='center'><b>Vat in %</b></th>";
}
echo "<th style='border-collapse: collapse; border:solid 1px #000;width:100px' align='center'><b>Make</b></th>";

echo "<th style='border-collapse: collapse; border:solid 1px #000; padding-right:5px;width:185px' align='right'><b>Total in <span><img src='images/rupee.jpg' /> </span> </b></th>";
echo "</tr>";
echo "</thead>";
echo "<tbody>";
$result_r = mysql_query("SELECT * FROM  purchaseorder_tbl where  po_year='".$po_year."' and  Po_no=" .$po_no,$conn);
		
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
echo  "<td style='border-collapse: collapse; border:solid 1px #000;padding-left:5px' align='left'>" .$productname. "</td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000;' align='center'>" .$details. "</td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000;' align='center'>" .$product_qty. "</td>";
echo  "<td style='border-collapse: collapse; border:solid 1px #000;' align='center'> " .formatInIndianStyle($ratesperproduct). "</td>";
if($inclusive != '0')
{
echo  "<td style='border-collapse: collapse; border:solid 1px #000;' align='center'>" .$vat. "</td>";	
}
echo  "<td style='border-collapse: collapse; border:solid 1px #000;' align='center'>" .$brand. "</td>";

echo "<td style='border-collapse: collapse; border:solid 1px #000; padding-right:5px' align='right' >" .formatInIndianStyle($Amount). "</td>";
echo  "</tr>";
 }

echo "<tr>";
if($inclusive != '0')
{
echo "<td colspan='6' class='white'></td>";
}
else
{
	echo "<td colspan='5' class='white'></td>";

}
echo  "<td style='border-collapse: collapse; border:solid 1px #000; padding:5px' align='center' >Grand Total</td>";
echo "<td  style='border-collapse: collapse; border:solid 1px #000; padding-right:5px' align='right'> <span><img src='images/rupee.jpg' /> </span> ".formatInIndianStyle($subtotal)."</td>";
echo "</tr>";
echo "</tbody>";
echo  "</table>";
echo "<div class='invoice-footer'>";
$html.="<div class='row'>";
echo "<div class='col12 s12 m12 l6 leftspace lineheight'>";
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
echo "<td>Door Delivery to our " .$place;
if( $project_name != 'Head Office')
{
echo "Site.";
}
else
{
	echo ".";
}
echo "</td>";
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

echo "<tr><td colspan='3'>Please Supply by " .$ddate. " at our ".$place;
if( $project_name != 'Head Office')
{
echo "Site.";
}
else
{
	echo ".";
}

echo "</td></tr>";

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
echo "</div>
<div class='col12 s12 m6 l6 left leftspace'>
<img src='images/anisa_digisign.jpg'  />

</div>
</div>
</div>
</div></div>";
}

else if($type == 'door')
{

    echo "<div style='position:relative; width:100%; margin-top:100px; '>";
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
	    $project_name = $row['project_name'] ;
	    $place=$row['place'];
		$tinno=$row['tinno'];
	    $cstno=$row['cstno'];
		$address=$row['address'];  
		$city=$row['city'];	
		$addressdispname=$row['addressdispname']; 
		$projectshort=$row['projectshort']; 
		$projectsingle=$row['projectsingle']; 	
		}
		
		 

echo  "<div align='right'><b>" .$po_date. "</b><br /></div>";
echo "<b>F/PUR/SIS-PO/".$projectsingle."/".$po_no. "/". $curYear."-".$nexYear."</b><br />";
echo "<b>To:</b><br />";
echo "<b>M/S.".$row2['ven_compname'] ."</b><br/>";
echo "<div style='width:250px; height:auto'>".$row2['ven_add1']. "</div><br/>";
echo "<div align='center' style='width:100%'><b>Kind Attention: " .$row2['ven_contactperson'] . "</b></div>";

if( $refno != '')
{
echo "<b>Ref : </b>". $refno.  " <b>Dated </b> on " .$refdate."<br>";
}
echo "<b>Sub : PO for " .$subject. ".</b><br>";
echo  "We are pleased to place the Purchase order as per the details mentioned below for our " ;
if( $row['project_name'] != 'Head Office')
{
echo  " Project <b> " .$project_name."  Block  ".$block."</b> ";
}
else
{
	echo  "<b> " .$project_name. "</b> ";
}
echo " the address and the contact person are mentioned below.";
echo "</div>";
echo "<div id='space'></div>";
echo "<table  cellpadding='0' cellspacing='0'  width='100%'  id='itemsTable' border='0' style='border-collapse: collapse; border: 1px solid black;' >";
echo "<thead>";
echo "<tr>";
echo "<th style='border-collapse: collapse; border:solid 1px #000;width:25px; height:28px;' align='center'><b>SNo</b></th>";
echo "<th style='border-collapse: collapse; border:solid 1px #000;width:250px; height:28px;padding-left:5px ' align='left' valign='middle'><b>Description</b></th>";
echo "<th style='border-collapse: collapse; border:solid 1px #000;width:100px; height:28px ' align='center'><b>Size</b></th>";
echo "<th style='border-collapse: collapse; border:solid 1px #000;width:80px ; height:28px' align='center'><b>Qty</b></th>";
echo "<th style='border-collapse: collapse; border:solid 1px #000;width:100px ; height:28px' align='center'><b>CFT</b></th>";
echo "<th style='border-collapse: collapse; border:solid 1px #000;width:100px ; height:28px' align='center'><b>Price per CFT</b> </th>";
echo "<th style='border-collapse: collapse; border:solid 1px #000; padding-right:5px;width:170px' align='right'><b>Total in <span><img src='images/rupee.jpg' /> </span> </b></th>";
echo "</tr>";echo "</thead>";
echo "<tbody>";
$result_r = mysql_query("SELECT * FROM  purchaseorder_tbl where  po_year='".$po_year."' and  Po_no=" .$po_no,$conn);
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
echo "<tr><td style='border-collapse: collapse; border:solid 1px #000;' align='center'>".$n."</td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000;padding-left:5px ; ' align='left'>" .$productname. "</td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000; ' align='center'>" .$size. "</td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000; ' align='center'>" .$product_qty1. "</td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000; ' align='center'>" .$product_qty. "</td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000; ' align='center'> " .formatInIndianStyle($ratesperproduct). "</td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000; padding-right:10px ' align='right'>" .formatInIndianStyle($Amount). "</td>";
echo "</tr>";
 }
echo "<tr>";
echo "<td colspan='5'  style='border-collapse: collapse; border:solid 1px #000; ' align='center'></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000; ' align='center'><b>SubTotal</b></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000; padding-right:10px ' align='right'>".formatInIndianStyle($stotal)."</td>";
echo "</tr>";
echo "<tr>";
echo "<td colspan='5'  ></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000; ' align='center'><b>vat</b></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000; padding-right:10px ' align='right'>".$tax."%</td>";
echo "</tr>";
echo "<tr>";
echo "<td colspan='5' style='border-collapse: collapse; border:solid 1px #000; '></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000; ' align='center'><b>Grand Total</b></td>";
echo "<td style='border-collapse: collapse; border:solid 1px #000; padding-right:10px ' align='right'>  <span><img src='images/rupee.jpg' /> </span>  ".formatInIndianStyle($subtotal)."</td>";
echo "</tr>";
echo "</tbody>";
echo  "</table>";

echo "<div class='invoice-footer'>";
$html.="<div class='row'>";
echo "<div class='col12 s12 m12 l6 leftspace lineheight'>";
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

echo "<tr><td colspan='3'>Please Supply by " .$ddate. " at our ".$place;
if( $project_name != 'Head Office')
{
echo "Site.";
}
else
{
	echo ".";
}

echo "</td></tr>";
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
echo "</div>
<div class='col12 s12 m6 l6 left leftspace'>
<img src='images/anisa_digisign.jpg'  />
</div>
</div>
</div>
</div></div>";

}

// unlink file

 ?>
