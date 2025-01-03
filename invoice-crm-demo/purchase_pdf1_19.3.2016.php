<?php
require('connect.php');
include "writelog.php";
include('MPDF57/mpdf.php');
date_default_timezone_set('Asia/Calcutta'); 
$time = date('Y-m-d H:i:s');
$po_no=$_GET['po_no'];
$ip = getenv('REMOTE_ADDR');
$userAgent = getenv('HTTP_USER_AGENT');
$referrer = getenv('HTTP_REFERER');
$query = getenv('QUERY_STRING');
$po_no=$_GET['po_no'];
$curYear = date('Y');
$nexYear = date('y')+1;

$n = 0;

"<style>
body
{
	font-size:12px ; font-family:cambria ;
	
}
	</style>
";
$mpdf=new mPDF('c','A4','','',10,10,37,37,5,5); 

$mpdf->mirrorMargins = 1;	// Use different Odd/Even headers and footers and mirror margins
//$mpdf->SetHTMLHeader('<div align="right" ><img src="images/head.jpg" align="right"/></div>'); 
//$mpdf->setHTMLFooter('<img src="images/footerp.jpg"/>');
$header = '<div align="right"><img src="images/head.jpg" width="126px" /></div>';
$headerE = '<div align="right"><img src="images/head.jpg" width="126px" /></div>';

$footer = '<div align="center"><img src="images/footer.jpg"  /></div>';
$footerE = '<div align="center"><img src="images/footer.jpg"  /></div>';
$mpdf->SetHTMLHeader($header);
$mpdf->SetHTMLHeader($headerE,'E');
$mpdf->SetHTMLFooter($footer);
$mpdf->SetHTMLFooter($footerE,'E');

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



	 }
if($type == 'standard')
{

    $html.= "<div style='position:relative; width:1024px; margin-top:100px;word-spacing:1px'>";
    $html.= "<div>"; 
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


$html.=  "<div id='res' style='display:none'></div>";
$html.= "<div align='right'><b>" .$po_date. "</b><br /></div>";
$html.= "<b>F/PUR/SIS-PO/".$projectsingle."/".$po_no. "/". $curYear."-".$nexYear."</b><br />";
$html.= "<b>To:</b><br />";
$html.= "<b>M/S.".$row2['ven_compname'] ."</b><br/>";
$html.= "<div style='width:250px; height:auto'>".$row2['ven_add1']. "</div>";
$html.= "<div align='center' style='width:100%'><b>Kind Attention: " .$row2['ven_contactperson'] . "</b></div>";
if( $refno != '')
{
$html.= "<b>Ref : </b>". $refno.  " <b>Dated </b> on " .$refdate."<br>";
}
$html.= "<div style='line-height:200%'><b>Sub : PO for " .$subject. ".</div></b>";
$html.=  "We are pleased to place the Purchase order as per the details mentioned below for our " ;
if( $row['project_name'] != 'Head Office')
{
$html.=  " Project <b> " .$project_name."&nbsp; Block &nbsp; ".$block."</b> ";
}
else
{
	$html.=  "<b> " .$project_name. "</b> ";
}
$html.= " the address and the contact person are mentioned below.";
$html.= "</div>";
$html.= "<div id='space'></div>";
$html.= "</br>&nbsp;";
$html.= "<table  cellpadding='0' cellspacing='0'  width='100%'  id='itemsTable' border='0' style='border-collapse: collapse; border: 1px solid black; font-siz:13px; font-family:cambria' >";
$html.= "<thead>";
$html.= "<tr>";
$html.= "<th style='border-collapse: collapse; border:solid 1px #000;  padding:5px; width:20px; font-siz:13px; font-family:cambria' align='center'><b>No</b></th>";
if($folder == 'Switches')
{
$html.= "<th style='border-collapse: collapse; border:solid 1px #000;  padding:5px;width:80px; font-siz:13px; font-family:cambria' align='center'><b>Code</b></th>";
}
$html.= "<th style='border-collapse: collapse; border:solid 1px #000;  padding:5px;width:320px; font-siz:13px; font-family:cambria' align='left'><b>Description</b></th>";
$html.= "<th style='border-collapse: collapse; border:solid 1px #000; padding:5px; width:50px; font-siz:13px; font-family:cambria' align='center'><b>Qty</b></th>";
$html.= "<th style='border-collapse: collapse; border:solid 1px #000;  padding:5px;width:80px; font-siz:13px; font-family:cambria' align='center'><b>Units</b></th>";

$html.= "<th style='border-collapse: collapse; border:solid 1px #000;  padding:5px;width:100px; font-siz:13px; font-family:cambria' align='center'><b>Price in <span><img src='images/rupee.jpg' /> </span> </b></th>";
if($inclusive != '0')
{
$html.= "<th style='border-collapse: collapse; border:solid 1px #000;  padding:5px;width:50px; font-siz:13px; font-family:cambria' align='center'><b>Vat in %</b></th>";
}
$html.= "<th style='border-collapse: collapse; border:solid 1px #000;  padding:5px;width:180px; font-siz:13px; font-family:cambria' align='right' ><b>Total  in <span><img src='images/rupee.jpg' /> </span> </b></th>";
$html.= "</tr>";
$html.= "</thead>";
$html.= "<tbody>";
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
	$product_code = $row['product_code'];	
		$n+=1;
$html.= "<tr>";                
$html.= "<tr><td style='border-collapse: collapse; border:solid 1px #000;  padding:5px; font-siz:13px; font-family:cambria' align='center'>".$n."</td>";
if($row['product_code'] != '' and  $row['product_code'] != '0')
{
$html.= "<td style='border-collapse: collapse; border:solid 1px #000; padding:5px; font-siz:13px; font-family:cambria' align='left' valign='middle'>" .$product_code. "</td>";
 }
$html.= "<td style='border-collapse: collapse; border:solid 1px #000;  padding:5px; font-siz:13px; font-family:cambria' align='left' valign='middle'>" .$productname. "</td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000; padding:5px; font-siz:13px; font-family:cambria' align='center'   >" .$product_qty. "</td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000;  padding:5px; font-siz:13px; font-family:cambria' align='center'>" .$units. "</td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000;  padding:5px; font-siz:13px; font-family:cambria' align='center'  > " .number_format($ratesperproduct, 2, '.', ''). "</td>";
if($inclusive != '0')
{
$html.= "<td style='border-collapse: collapse; border:solid 1px #000; padding:5px; font-siz:13px; font-family:cambria' align='center'>" .$vat. "</td>";
}
$html.= "<td style='border-collapse: collapse; border:solid 1px #000;  padding:5px; font-siz:13px; font-family:cambria' align='right' > " .number_format($Amount, 2, '.', ''). "</td>";
$html.= "</tr>";
 }

$html.= "<tr>";
if($inclusive != '0')
{

$html.= "<td colspan='1' class='white'></td>";
}
 if($folder == 'Switches')
{
	$html.= "<td colspan='4' class='white'></td>";

}
else
{
	$html.= "<td colspan='3' class='white'></td>";

}
$html.= "<td class='cyan white-text'  colspan='2'  align='center' style='border-collapse: collapse; border:solid 1px #000;padding:5px'><b>Grand Total</b></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000; padding-right:10px; font-siz:13px; font-family:cambria' align='right'><b><span><img src='images/rupee.jpg' /> </span> ".number_format($subtotal, 2, '.', '')."</b></td>";
$html.= "</tr>";
$html.= "</tbody>";
$html.= "</table>";
$html.= "<div clas='space'>&nbsp;</div>";
$html.= "<div class='invoice-footer' style='word-spacing:1px'>";
$html.="<div class='row'>";
$html.= "<div class='col12 s12 m12 l6 leftspace lineheight'>";
$html.= "<table style='width:100%' style='word-spacing:1px'>";
$html.= "<tr>";
$html.= "<td colspan='3'><b>Term and Condition:</b> </td>";
$html.= "</tr>";
$html.= "<tr>";
$html.= "<td valign='top'>Tin No</td>";
$html.= "<td>:</td>";
$html.= "<td>".$tinno."</td>";
$html.= "<td></td>";
$html.= "</tr>";
$html.= "<tr>";
$html.= "<td valign='top'>CST No</td>";
$html.= "<td>:</td>";
$html.= "<td>".$cstno."</td>";
$html.= "</tr>";
$html.= "<tr>";
$html.= "<td valign='top'>Payment</td>";
$html.= "<td>:</td>";
$html.= "<td>".$payment."</td>";
$html.= "</tr>";
$html.= "<tr>";
$html.= "<td valign='top'>Delivery</td>";
$html.= "<td>:</td>";
$html.= "<td>Door Delivery to our " .$place;
if( $project_name != 'Head Office')
{
$html.= "Site.";
}
else
{
	$html.= ".";
}
$html.= "</tr>";

$html.= "<tr>";
$html.= "<td valign='top'>Loading & Vat</td>";
$html.= "<td>:</td>";
$html.= "<td>".$vat_o."</td>";
$html.= "</tr>";

if($tpwords != '')
{
$html.= "<tr>";
$html.= "<td valign='top'>Transportation</td>";
$html.= "<td>:</td>";
$html.= "<td>".$tpwords."</td>";
$html.= "</tr>";
}

$html.= "<tr><td colspan='3'>Please Supply by " .$ddate. " at our ".$place;
if( $project_name != 'Head Office')
{
$html.= "Site.";
}
else
{
	$html.= ".";
}
$html.= "</td></tr>";

$html.= "</table>";
$html.= "<table style='width:100%'>";
$html.= "<tr>";
$html.= "<td style='width:38%' valign='top'>Site contact Person </td>";
$html.= "<td style='width:5%'>:</td>";
$html.= "<td style='width:30%'>".$contactname."</td>";
$html.= "<td  style='width:5%'>:</td>";
$html.= "<td  style='width:22%'>".$mobileno."</td>";
$html.= "</tr>";
$html.= "<tr>";
$html.= "<td valign='top'>Site address</td>";
$html.= "<td>:</td>";
$html.= "<td><b>".$addressdispname. "</b></td>";
$html.= "<td></td>";
$html.= "<td></td>";
$html.= "<td></td>";
$html.= "</tr>";
$html.= "<tr>";
$html.= "<td></td>";
$html.= "<td></td>";
$html.= "<td style='width:300px'>".$address."</td>";
$html.= "<td></td>";
$html.= "<td></td>";
$html.= "<td></td>";
$html.= "</tr>";
$html.= "</table>";
$html.= "</div>
<div class='col12 s12 m6 l6 left leftspace lineheight'>
<img src='images/anisa_digisign.jpg'  />
</div>
</div>
</div>
</div></div>";
$mpdf->WriteHTML($html);
$mpdf->SetDisplayMode('fullpage');

$mpdf->Output("purchaseorder/PO-".$po_no. ".pdf");
//header("Location: purchaseorder/PO-".$po_no. ".pdf");
$file = "purchaseorder/PO-".$po_no. ".pdf";

if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($file));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
 

$msg = "IP: " . $ip . " TIME: " . $time . " User: " .$_SESSION['st_username'].  " USERAGENT: " .$userAgent. "Action : Purchase order_".$po_no." has been downloaded as pdf";
writeToLogFile($msg);

    readfile($file);
	
	unlink("purchaseorder/PO-".$po_no. ".pdf");

    exit;
}
}
else if($type == 'upvc')
{
	$html.= "<div style='position:relative; width:1024px;'>";
    $html.= "<div>"; $result2 = mysql_query("SELECT * FROM  vendor_tbl where ven_compname='".mysql_real_escape_string($ven_id)."'",$conn);
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
		}
		else
		{
			echo "Error";
	 }

		
		
		
$html.=  "<div align='right'><b>" .$po_date. "</b><br /></div>";
$html.= "<b>F/PUR/SIS-PO/".$projectsingle."/".$po_no. "/". $curYear."-".$nexYear."</b><br />";
$html.= "<b>To:</b><br />";
$html.= "<b>M/S.".$row2['ven_compname'] ."</b><br/>";
$html.= "<div style='width:250px; height:auto'>".$row2['ven_add1']. "</div>";
$html.= "<div align='center' style='width:100%'><b>Kind Attention: " .$row2['ven_contactperson'] . "</b></div>";
if( $refno != '')
{
$html.= "Ref : ". $refno.  " - Amended Qty Without Glass Dated dated on " . $refdate."<br>";
}


if( $row['project_name'] != 'Head Office')
{
$html.= "<b>Sub : &nbsp;</b>Our PO : " .$po_no." for window for our project <b> " .$project_name."&nbsp; Block &nbsp; ".$block."</b> at ".$place."&nbsp;".$city." .<br>";
$html.=  "We are pleased to place the Purchase order as per the details mentioned below for our Project " ;

}
else
{
	$html.= "<b>Sub : &nbsp;</b>Our PO : " .$po_no." for window for our <b> " .$project_name."</b> at ".$place."&nbsp;".$city." .<br>";
	$html.=  "We are pleased to place the Purchase order as per the details mentioned below for our " ;

}
$html.=  "<b>" .$project_name. "</b>";
$html.= "the address and the contact person are mentioned below.";
$html.= "</div>";
$html.= "<div id='space'></div>";
$html.= "<table  cellpadding='0' cellspacing='0'  width='100%'  id='itemsTable' border='0' style='border-collapse: collapse; border: 1px solid black;' >";
$html.= "<tr>";
$html.= "<th style='border-collapse: collapse; border:solid 1px #000' align='center'>SNo</th>";
$html.= "<th style='border-collapse: collapse; border:solid 1px #000' align='center'><b>Code</b></th>";
$html.= "<th style='border-collapse: collapse; border:solid 1px #000' align='center'><b>width</b></th>";
$html.= "<th style='border-collapse: collapse; border:solid 1px #000' align='center'><b>height</b></th>";
$html.= "<th style='border-collapse: collapse; border:solid 1px #000' align='center'><b>unit</b></th>";
$html.= "<th style='border-collapse: collapse; border:solid 1px #000' align='center'><b>type</b></th>";
$html.= "<th colspan='2' style='border-collapse: collapse; border:solid 1px #000' align='center' ><b>Material Cost <span> in <img src='images/rupee.jpg' /> </span> </b></th>";
$html.= "<th colspan='2' style='border-collapse: collapse; border:solid 1px #000' align='center'><b> Installation Cost  in <span><img src='images/rupee.jpg' /> </span></b></th>";
$html.= "<th style='border-collapse: collapse; border:solid 1px #000; padding-right:10px' align='right'><b>Amount in <span><img src='images/rupee.jpg' /> </span> </b></th>";
$html.=  "</tr>";
$html.= "<tr>";
$html.= "<th style='border-collapse: collapse; border:solid 1px #000'></th>";
$html.= "<th style='border-collapse: collapse; border:solid 1px #000'>&nbsp;</th>";
$html.= "<th style='border-collapse: collapse; border:solid 1px #000'></th>";
$html.= "<th style='border-collapse: collapse; border:solid 1px #000'></th>";
$html.= "<th style='border-collapse: collapse; border:solid 1px #000'></th>";
$html.= "<th style='border-collapse: collapse; border:solid 1px #000'></th>";
$html.= "<th style='border-collapse: collapse; border:solid 1px #000'  ><b>Basic</b></th>";
$html.=  "<th style='border-collapse: collapse; border:solid 1px #000' align='right'><b>Total</b></th>";
$html.= "<th style='border-collapse: collapse; border:solid 1px #000'><b>Basic</b></th>";
$html.= "<th style='border-collapse: collapse; border:solid 1px #000; padding-right:10px' align='right'><b>Total</b></th>";
$html.= "<th style='border-collapse: collapse; border:solid 1px #000; padding-right:10px' align='right'><b>&nbsp;</b></th>";
$html.= " </tr>";
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
$html.= "<tr><td style='border-collapse: collapse; border:solid 1px #000'>".$n."</label></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000' align='center'><div class='input-field col s8'> ".$product_code."</div></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'><div class='input-field col s8' align='center'>".$pro_width."</div></td>";
$html.=  "<td style='border-collapse: collapse; border:solid 1px #000'><div class='input-field col s8' align='center'>".$pro_height."</div></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'><div class='input-field col s8' align='center'>".$product_qty."</div></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'><div class='input-field col s8' align='center'>".$productname."</div></td>";
$html.=  "<td style='border-collapse: collapse; border:solid 1px #000'><div class='input-field col s8' align='center'>".number_format($m_basic, 2, '.', '')."</div></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000; padding-right:10px' align='right'><div class='input-field col s8'>".$m_total."</div></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000' align='center'><div class='input-field col s8'>".number_format($i_basic, 2, '.', '')."</div></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000; padding-right:10px' align='right'><div class='input-field col s8'>".number_format($i_total, 2, '.', '')."</div></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000; padding-right:10px' align='right'><div class='input-field col s8'>".number_format($Amount, 2, '.', '')."</div></td>";
$html.= "</tr>";
 }

$html.= "<tr>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'>BC</td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000; padding-right:10px' align='right'><div class='input-field col s9'>". number_format($basictotal, 2, '.', '')."</td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
$html.= "</tr>";
$html.= "<tr>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'>ED</td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000; padding-right:10px' align='right'><div class='input-field col s9'>".$ed."%</div></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
$html.= "<th style='border-collapse: collapse; border:solid 1px #000'></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
$html.= "</tr>";
$html.= "<tr>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'>Vat</td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000; padding-right:10px' align='right'><div class='input-field col s9'>".$tax."%</div></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
$html.= "<th style='border-collapse: collapse; border:solid 1px #000'></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
$html.= "</tr>";
$html.= "<tr>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'>Transpotation</td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000; padding-right:10px' align='right'><div class='input-field col s9'>". number_format($transportation, 2, '.', '')."</div></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
$html.= "<th style='border-collapse: collapse; border:solid 1px #000'></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
$html.= "</tr>";
$html.= "<tr>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'>ST</td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000; padding-right:10px' align='right'><div class='input-field col s9'>".$st."%</div></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'>&nbsp;</td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
$html.= "<th style='border-collapse: collapse; border:solid 1px #000'></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
$html.= "</tr>";
$html.= "<tr>";	
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'></td>";	
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'></td>";	
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'></td>";	
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'></td>";	
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'>Grand Total</td>";	
$html.= "<td style='border-collapse: collapse; border:solid 1px #000; padding-right:10px' align='right'><div class='input-field col s9'>". number_format($gt, 2, '.', '')."</div></td>";	
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'>&nbsp;</td>";	
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'>&nbsp;</td>";	
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'>&nbsp;</td>";	
$html.= "<td style='border-collapse: collapse; border:solid 1px #000; padding-right:10px' align='right'><div class='input-field col s9'>". number_format($gt1, 2, '.', '')."</div></td>";	
$html.= "<td style='border-collapse: collapse; border:solid 1px #000; padding-right:10px' align='right'><div class='input-field col s9'> <span><img src='images/rupee.jpg' /> </span>  ". number_format($subtotal, 2, '.', '')."</div></td>";	
$html.= "</tr>";



$html.= "</tbody>";	
$html.= "</table>";
$html.= "<div class='col12 s12 m12 l6 leftspace lineheight'>";
$html.= "<table style='width:100%' style='word-spacing:1px'>";
$html.= "<tr>";
$html.= "<td colspan='3'><b>Term and Condition:</b> </td>";
$html.= "</tr>";
$html.= "<tr>";
$html.= "<td valign='top'>Tin No</td>";
$html.= "<td>:</td>";
$html.= "<td>".$tinno."</td>";
$html.= "<td></td>";
$html.= "</tr>";
$html.= "<tr>";
$html.= "<td valign='top'>CST No</td>";
$html.= "<td>:</td>";
$html.= "<td>".$cstno."</td>";
$html.= "</tr>";
if($tpwords != '')
{
$html.= "<tr>";
$html.= "<td valign='top'>Transportation</td>";
$html.= "<td>:</td>";
$html.= "<td>".$tpwords."</td>";
$html.= "</tr>";

}
$html.= "<tr><td colspan='3'>Please Supply by " .$ddate. " at our ".$place;
if( $project_name != 'Head Office')
{
$html.= "Site.";
}
else
{
	$html.= ".";
}

$html.= "</td></tr>";
$html.= "</table>";
$html.= "<table style='width:100%'>";
$html.= "<tr>";
$html.= "<td style='width:38%' valign='top'>Site contact Person </td>";
$html.= "<td style='width:5%'>:</td>";
$html.= "<td style='width:30%'>".$contactname."</td>";
$html.= "<td  style='width:5%'>:</td>";
$html.= "<td  style='width:22%'>".$mobileno."</td>";
$html.= "</tr>";
$html.= "<tr>";
$html.= "<td valign='top'>Site address</td>";
$html.= "<td>:</td>";
$html.= "<td><b>".$addressdispname."</b></td>";
$html.= "<td></td>";
$html.= "<td></td>";
$html.= "<td></td>";
$html.= "</tr>";
$html.= "<tr>";
$html.= "<td></td>";
$html.= "<td></td>";
$html.= "<td style='width:300px'>".$address."</td>";
$html.= "<td></td>";
$html.= "<td></td>";
$html.= "<td></td>";
$html.= "</tr>";
$html.= "</table>";
$html.= "</div>
<div class='col12 s12 m6 l6 left leftspace lineheight'>
<img src='images/anisa_digisign.jpg'  />
</div>
</div>
</div>
</div></div>";
$mpdf->WriteHTML($html);
$mpdf->SetDisplayMode('fullpage');
$mpdf->Output("purchaseorder/PO-".$po_no.".pdf");
$file = "purchaseorder/PO-".$po_no.".pdf";
if (file_exists($file)) {
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename='.basename($file));
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header('Content-Length: ' . filesize($file));
readfile($file);
unlink("purchaseorder/PO-".$po_no.".pdf");
exit;
}
}
else if($type == 'steel')
{

    $html.= "<div style='position:relative; width:1024px; margin-top:100px; '>";
    $html.= "<div>"; $result2 = mysql_query("SELECT * FROM  vendor_tbl where ven_compname='".mysql_real_escape_string($ven_id)."'",$conn);
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

		 

$html.=  "<div align='right'><b>" .$po_date. "</b><br /></div>";
$html.= "<b>F/PUR/SIS-PO/".$projectsingle."/".$po_no. "/". $curYear."-".$nexYear."</b><br />";
$html.= "<b>To:</b><br />";
$html.= "<b>M/S.".$row2['ven_compname'] ."</b><br/>";
$html.= "<div style='width:250px; height:auto'>".$row2['ven_add1']. "</div><br/>";
$html.= "<div align='center' style='width:100%'><b>Kind Attention: " .$row2['ven_contactperson'] . "</b></div>";
if( $refno != '')
{
$html.= "<b>Ref : </b>". $refno.  " <b>Dated </b> on " .$refdate."<br>";
}
$html.= "<b>Sub : PO for " .$subject. ".</b><br>";
$html.=  "We are pleased to place the Purchase order as per the details mentioned below for our " ;
if( $row['project_name'] != 'Head Office')
{
$html.=  "Project  <b>" .$project_name."&nbsp; Block  ".$block."</b> ";
}
else
{
	$html.=  "<b> " .$project_name. "</b> ";
}
$html.= " the address and the contact person are mentioned below.";
$html.= "</div>";
$html.= "<div id='space'>&nbsp;</div>";
$html.= "<table  cellpadding='0' cellspacing='0'  width='100%'  id='itemsTable' border='0' style='border-collapse: collapse; border: 1px solid black;' >";
$html.= "<thead>";
$html.=  "<tr>";
$html.= "<th style='border-collapse: collapse; border:solid 1px #000;padding:5px;width:10px; height:25px' align='center'><b>No</b></th>";
$html.= "<th style='border-collapse: collapse; border:solid 1px #000; padding:5px;width:180px; height:25px' align='center'><b>Description</b></th>";
$html.=  "<th style='border-collapse: collapse; border:solid 1px #000;padding:5px;;width:140px; height:25px' align='center'><b>Detail</b></th>";
$html.= "<th style='border-collapse: collapse; border:solid 1px #000;padding:5px;width:80px; height:25px' align='center'><b>Tonnage</b></th>";
$html.= "<th style='border-collapse: collapse; border:solid 1px #000;padding:5px;width:100px; height:25px' align='center'><b>Rate per ton in <span><img src='images/rupee.jpg' /> </span> </b></th>";
if($inclusive != '0')
{
$html.= "<th style='border-collapse: collapse; border:solid 1px #000;padding:5px;width:70px' align='center'><b>Vat in %</b></th>";
}
$html.= "<th style='border-collapse: collapse; border:solid 1px #000; padding:5px;width:100px' align='center'><b>Make</b></th>";

$html.= "<th style='border-collapse: collapse; border:solid 1px #000; padding-right:5px;width:200px' align='right'><b>Total in <span><img src='images/rupee.jpg' /> </span> </b></th>";
$html.= "</tr>";
$html.= "</thead>";
$html.= "<tbody>";
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
$html.= "<tr>";                
$html.= "<tr><td style='border-collapse: collapse; border:solid 1px #000' align='center'>".$n."</td>";
$html.=  "<td style='border-collapse: collapse; border:solid 1px #000;padding:5px' align='left'>" .$productname. "</td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000;padding:5px' align='center'>" .$details. "</td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000;padding:5px' align='center'>" .$product_qty. "</td>";
$html.=  "<td style='border-collapse: collapse; border:solid 1px #000;padding:5px' align='center'> " .number_format($ratesperproduct, 2, '.', ''). "</td>";
if($inclusive != '0')
{
$html.=  "<td style='border-collapse: collapse; border:solid 1px #000;padding:5px' align='center'>" .$vat. "</td>";	
}
$html.=  "<td style='border-collapse: collapse; border:solid 1px #000;padding:5px' align='center'>" .$brand. "</td>";

$html.= "<td style='border-collapse: collapse; border:solid 1px #000; padding-right:5px' align='right' >" .number_format($Amount, 2, '.', ''). "</td>";
$html.=  "</tr>";
 }

$html.= "<tr>";
if($inclusive != '0')
{
$html.= "<td colspan='6' class='white'></td>";
}
else
{
	$html.= "<td colspan='5' class='white'></td>";

}
$html.=  "<td style='border-collapse: collapse; border:solid 1px #000; padding:5px' align='center' >Grand Total</td>";
$html.= "<td  style='border-collapse: collapse; border:solid 1px #000; padding-right:5px' align='right'> <span><img src='images/rupee.jpg' /> </span> ".number_format($subtotal, 2, '.', '')."</td>";
$html.= "</tr>";
$html.= "</tbody>";
$html.=  "</table>";
$html.= "<div class='invoice-footer'>";
$html.="<div class='row'>";
$html.= "<div class='col12 s12 m12 l6 leftspace lineheight'>";
$html.= "<table style='width:100%' style='word-spacing:1px'>";
$html.= "<tr>";
$html.= "<td colspan='3'><b>Term and Condition:</b> </td>";
$html.= "</tr>";
$html.= "<tr>";
$html.= "<td valign='top'>Tin No</td>";
$html.= "<td>:</td>";
$html.= "<td>".$tinno."</td>";
$html.= "<td></td>";
$html.= "</tr>";
$html.= "<tr>";
$html.= "<td valign='top'>CST No</td>";
$html.= "<td>:</td>";
$html.= "<td>".$cstno."</td>";
$html.= "</tr>";
$html.= "<tr>";
$html.= "<td valign='top'>Payment</td>";
$html.= "<td>:</td>";
$html.= "<td>".$payment."</td>";
$html.= "</tr>";
$html.= "<tr>";
$html.= "<td valign='top'>Delivery</td>";
$html.= "<td>:</td>";
$html.= "<td>Door Delivery to our " .$place;
if( $project_name != 'Head Office')
{
$html.= "Site.";
}
else
{
	$html.= ".";
}
$html.= "</td>";
$html.= "</tr>";
$html.= "<tr>";
$html.= "<td valign='top'>Loading & Vat</td>";
$html.= "<td>:</td>";
$html.= "<td>".$vat_o."</td>";
$html.= "</tr>";
if($tpwords != '')
{
$html.= "<tr>";
$html.= "<td valign='top'>Transportation</td>";
$html.= "<td>:</td>";
$html.= "<td>".$tpwords."</td>";
$html.= "</tr>";

}

$html.= "<tr><td colspan='3'>Please Supply by " .$ddate. " at our ".$place;
if( $project_name != 'Head Office')
{
$html.= "Site.";
}
else
{
	$html.= ".";
}

$html.= "</td></tr>";

$html.= "</table>";
$html.= "<table style='width:100%'>";
$html.= "<tr>";
$html.= "<td style='width:38%' valign='top'>Site contact Person </td>";
$html.= "<td style='width:5%'>:</td>";
$html.= "<td style='width:30%'>".$contactname."</td>";
$html.= "<td  style='width:5%'>:</td>";
$html.= "<td  style='width:22%'>".$mobileno."</td>";
$html.= "</tr>";
$html.= "<tr>";
$html.= "<td valign='top'>Site address</td>";
$html.= "<td>:</td>";
$html.= "<td><b>".$addressdispname."</b></td>";
$html.= "<td></td>";
$html.= "<td></td>";
$html.= "<td></td>";
$html.= "</tr>";
$html.= "<tr>";
$html.= "<td></td>";
$html.= "<td></td>";
$html.= "<td style='width:300px'>".$address."</td>";
$html.= "<td></td>";
$html.= "<td></td>";
$html.= "<td></td>";
$html.= "</tr>";
$html.= "</table>";
$html.= "</div>
<div class='col12 s12 m6 l6 left leftspace'>
<img src='images/anisa_digisign.jpg'  />

</div>
</div>
</div>
</div></div>";
$mpdf->WriteHTML($html);
$mpdf->SetDisplayMode('fullpage');

$mpdf->Output("purchaseorder/PO-".$po_no. ".pdf");
//header("Location: purchaseorder/PO-".$po_no. ".pdf");
$file = "purchaseorder/PO-".$po_no. ".pdf";

if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($file));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
 
 require('connect.php');
$po_no=$_GET['po_no'];


$msg = "IP: " . $ip . " TIME: " . $time . " User: " .$_SESSION['st_username'].  " USERAGENT: " .$userAgent. "Action : Purchase order_".$po_no." has been downloaded as pdf";
writeToLogFile($msg);

    readfile($file);
	
	unlink("purchaseorder/PO-".$po_no. ".pdf");

    exit;
}
}
else if($type == 'door')
{

    $html.= "<div style='position:relative; width:1024px; margin-top:100px; '>";
    $html.= "<div>"; $result2 = mysql_query("SELECT * FROM  vendor_tbl where ven_compname='".mysql_real_escape_string($ven_id)."'",$conn);
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
		
		 

$html.=  "<div align='right'><b>" .$po_date. "</b><br /></div>";
$html.= "<b>F/PUR/SIS-PO/".$projectsingle."/".$po_no. "/". $curYear."-".$nexYear."</b><br />";
$html.= "<b>To:</b><br />";
$html.= "<b>M/S.".$row2['ven_compname'] ."</b><br/>";
$html.= "<div style='width:250px; height:auto'>".$row2['ven_add1']. "</div><br/>";
$html.= "<div align='center' style='width:100%'><b>Kind Attention: " .$row2['ven_contactperson'] . "</b></div>";

if( $refno != '')
{
$html.= "<b>Ref : </b>". $refno.  " <b>Dated </b> on " .$refdate."<br>";
}
$html.= "<b>Sub : PO for " .$subject. ".</b><br>";
$html.=  "We are pleased to place the Purchase order as per the details mentioned below for our " ;
if( $row['project_name'] != 'Head Office')
{
$html.=  " Project <b> " .$project_name."  Block  ".$block."</b> ";
}
else
{
	$html.=  "<b> " .$project_name. "</b> ";
}
$html.= " the address and the contact person are mentioned below.";
$html.= "</div>";
$html.= "<div id='space'></div>";
$html.= "<table  cellpadding='0' cellspacing='0'  width='100%'  id='itemsTable' border='0' style='border-collapse: collapse; border: 1px solid black;' >";
$html.= "<thead>";
$html.= "<tr>";
$html.= "<th style='border-collapse: collapse; border:solid 1px #000;width:25px; height:28px;' align='center'><b>SNo</b></th>";
$html.= "<th style='border-collapse: collapse; border:solid 1px #000;width:250px; height:28px;padding-left:5px ' align='left' valign='middle'><b>Description</b></th>";
$html.= "<th style='border-collapse: collapse; border:solid 1px #000;width:100px; height:28px ' align='center'><b>Size</b></th>";
$html.= "<th style='border-collapse: collapse; border:solid 1px #000;width:80px ; height:28px' align='center'><b>Qty</b></th>";
$html.= "<th style='border-collapse: collapse; border:solid 1px #000;width:100px ; height:28px' align='center'><b>CFT</b></th>";
$html.= "<th style='border-collapse: collapse; border:solid 1px #000;width:100px ; height:28px' align='center'><b>Price per CFT</b> </th>";
$html.= "<th style='border-collapse: collapse; border:solid 1px #000; padding-right:5px;width:170px' align='right'><b>Total in <span><img src='images/rupee.jpg' /> </span> </b></th>";
$html.= "</tr>";$html.= "</thead>";
$html.= "<tbody>";
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
$html.= "<tr>";                
$html.= "<tr><td style='border-collapse: collapse; border:solid 1px #000;' align='center'>".$n."</td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000;padding-left:5px ; ' align='left'>" .$productname. "</td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000; ' align='center'>" .$size. "</td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000; ' align='center'>" .$product_qty1. "</td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000; ' align='center'>" .$product_qty. "</td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000; ' align='center'> " .number_format($ratesperproduct, 2, '.', ''). "</td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000; padding-right:10px ' align='right'>" .number_format($Amount, 2, '.', ''). "</td>";
$html.= "</tr>";
 }
$html.= "<tr>";
$html.= "<td colspan='5'  style='border-collapse: collapse; border:solid 1px #000; ' align='center'></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000; ' align='center'><b>SubTotal</b></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000; padding-right:10px ' align='right'>".number_format($stotal, 2, '.', '')."</td>";
$html.= "</tr>";
$html.= "<tr>";
$html.= "<td colspan='5'  ></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000; ' align='center'><b>vat</b></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000; padding-right:10px ' align='right'>".$tax."%</td>";
$html.= "</tr>";
$html.= "<tr>";
$html.= "<td colspan='5' style='border-collapse: collapse; border:solid 1px #000; '></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000; ' align='center'><b>Grand Total</b></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000; padding-right:10px ' align='right'>  <span><img src='images/rupee.jpg' /> </span>  ".number_format($subtotal, 2, '.', '')."</td>";
$html.= "</tr>";
$html.= "</tbody>";
$html.=  "</table>";

$html.= "<div class='invoice-footer'>";
$html.="<div class='row'>";
$html.= "<div class='col12 s12 m12 l6 leftspace lineheight'>";
$html.= "<table style='width:100%' style='word-spacing:1px'>";
$html.= "<tr>";
$html.= "<td colspan='3'><b>Term and Condition:</b> </td>";
$html.= "</tr>";
$html.= "<tr>";
$html.= "<td valign='top'>Tin No</td>";
$html.= "<td>:</td>";
$html.= "<td>".$tinno."</td>";
$html.= "<td></td>";
$html.= "</tr>";
$html.= "<tr>";
$html.= "<td valign='top'>CST No</td>";
$html.= "<td>:</td>";
$html.= "<td>".$cstno."</td>";
$html.= "</tr>";
$html.= "<tr>";
$html.= "<td valign='top'>Payment</td>";
$html.= "<td>:</td>";
$html.= "<td>".$payment."</td>";
$html.= "</tr>";
$html.= "<tr>";
$html.= "<td valign='top'>Delivery</td>";
$html.= "<td>:</td>";
$html.= "<td>Door Delivery to our " .$place. " Site  </td>";
$html.= "</tr>";
$html.= "<tr>";
$html.= "<td valign='top'>Loading & Vat</td>";
$html.= "<td>:</td>";
$html.= "<td>".$vat_o."</td>";
$html.= "</tr>";
if($tpwords != '')
{
$html.= "<tr>";
$html.= "<td valign='top'>Transportation</td>";
$html.= "<td>:</td>";
$html.= "<td>".$tpwords."</td>";
$html.= "</tr>";

}

$html.= "<tr><td colspan='3'>Please Supply by " .$ddate. " at our ".$place;
if( $project_name != 'Head Office')
{
$html.= "Site.";
}
else
{
	$html.= ".";
}

$html.= "</td></tr>";
$html.= "</table>";
$html.= "<table style='width:100%'>";
$html.= "<tr>";
$html.= "<td style='width:38%' valign='top'>Site contact Person </td>";
$html.= "<td style='width:5%'>:</td>";
$html.= "<td style='width:30%'>".$contactname."</td>";
$html.= "<td  style='width:5%'>:</td>";
$html.= "<td  style='width:22%'>".$mobileno."</td>";
$html.= "</tr>";
$html.= "<tr>";
$html.= "<td valign='top'>Site address</td>";
$html.= "<td>:</td>";
$html.= "<td><b>".$addressdispname."</b></td>";
$html.= "<td></td>";
$html.= "<td></td>";
$html.= "<td></td>";
$html.= "</tr>";
$html.= "<tr>";
$html.= "<td></td>";
$html.= "<td></td>";
$html.= "<td style='width:300px'>".$address."</td>";
$html.= "<td></td>";
$html.= "<td></td>";
$html.= "<td></td>";
$html.= "</tr>";
$html.= "</table>";
$html.= "</div>
<div class='col12 s12 m6 l6 left leftspace'>
<img src='images/anisa_digisign.jpg'  />
</div>
</div>
</div>
</div></div>";
$mpdf->WriteHTML($html);
$mpdf->SetDisplayMode('fullpage');

$mpdf->Output("purchaseorder/PO-".$po_no. ".pdf");
//header("Location: purchaseorder/PO-".$po_no. ".pdf");
$file = "purchaseorder/PO-".$po_no. ".pdf";

if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($file));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
 
 require('connect.php');
$po_no=$_GET['po_no'];


$msg = "IP: " . $ip . " TIME: " . $time . " User: " .$_SESSION['st_username'].  " USERAGENT: " .$userAgent. "Action : Purchase order_".$po_no." has been downloaded as pdf";
writeToLogFile($msg);

    readfile($file);
	
	unlink("purchaseorder/PO-".$po_no. ".pdf");

    exit;
}
}

// unlink file

 ?>
