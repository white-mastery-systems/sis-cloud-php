<?php
require('connect.php');
include "writelog.php";
include "moneyformat.php";
include('MPDF57/mpdf.php');
date_default_timezone_set('Asia/Calcutta'); 
$time = date('Y-m-d H:i:s');
$po_no=$_GET['po_no'];
$po_year=$_GET['po_year'];
$ip = getenv('REMOTE_ADDR');
$userAgent = getenv('HTTP_USER_AGENT');
$referrer = getenv('HTTP_REFERER');
$query = getenv('QUERY_STRING');
$n = 0;
//header part
header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=".$po_no.".doc");
//starting html tag
echo "<html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:w='urn:schemas-microsoft-com:office:word'
    xmlns='http://www.w3.org/TR/REC-html40'>";
 echo "<!--[if gte mso 9]-->";
    echo "<xml>";
       echo "<w:WordDocument>";
            echo "<w:View>Print</w:View>";
            echo "<w:Zoom>90</w:Zoom>";
            echo "<w:DoNotOptimizeForBrowser/>";
        echo "</w:WordDocument>";
    echo "</xml>";
   echo "<!-- [endif]-->
    <style>
            p.MsoHeader, li.MsoHeader, div.MsoHeader{
                margin:0in;
                margin-top:.0001pt;
                mso-pagination:widow-orphan;
                tab-stops:center 3.0in right 6.0in;
            }
            p.MsoFooter, li.MsoFooter, div.MsoFooter{
                margin:0in;
                margin-bottom:.0001pt;
                mso-pagination:widow-orphan;
                
            }
            @page Section1{
                size:8.5in 11.0in; 				 
				font-family:cambria;
				font-size:12px !important;
                margin:0.3in 0.5in 0.3in 0.5in;
                mso-header-margin:0.5in;
                mso-header:h1;
                mso-footer:f1; 
                mso-footer-margin:0.5in;
                mso-paper-source:0;
				 
				
				
            }
            div.Section1{
                page:Section1;
				
			
            }
			 p.MsoNormal, li.MsoNormal, div.MsoNormal
    {margin:0cm;
    margin-bottom:.0001pt;
    font-size:12.0pt;
    font-family:'cambria';}
 div.leftcolumn{
                position:relative;
				float:left;
				width:45%;
            }
			 div.center{
                position:relative;
				float:left;
				width:10%
            }
			div.main
			{
				width:100%;
			}
 div.rightcolumn{
                position:relative;
				float:left;
				width:45%
            }
            table#hrdftrtbl{
                margin:0in 0in 0in 9in;
            }  
			
        </style>
   
   ";//body part start here
echo "<body>";
//print the content
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
$curYear = $row1['po_year'];
$nexYear = $row1['po_year'] + 1;
	 }
if($type == 'standard')
{
    $html.= "<div class='Section1'  style='font-family:cambria;	font-size:12px !important;'>";
    $html.= "<div class=MsoNormal>"; 
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
$html.= "<b>F/PUR/SIS-PO/".$projectsingle."/".$po_no. "/". $curYear."-".$nexYear."</b><br />&nbsp;<br/>";
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
$html.= "<table  cellpadding='0' cellspacing='0'  width='100%'  id='itemsTable' border='0' style='border-collapse: collapse; border: 1px solid black;font-family:cambria;	font-size:12px !important;'>";
$html.= "<thead>";
$html.= "<tr>";
$html.= "<th style='border-collapse: collapse; border:solid 1px #000; padding:5px; width:15px' align='center'><b>No</b></th>";
$html.= "<th style='border-collapse: collapse; border:solid 1px #000; padding:5px;width:295px' align='center'><b>Description</b></th>";
$html.= "<th style='border-collapse: collapse; border:solid 1px #000; padding:5px; width:50px' align='center'><b>Qty</b></th>";
$html.= "<th style='border-collapse: collapse; border:solid 1px #000; padding:5px;width:80px' align='center'><b>Units</b></th>";

$html.= "<th style='border-collapse: collapse; border:solid 1px #000; padding:5px;width:100px' align='center'><b>Price in <span style='font-family:Rupee Foradian'>`</span> </b></th>";
if($inclusive != '0')
{

$html.= "<th style='border-collapse: collapse; border:solid 1px #000; padding:5px;width:50px' align='center'><b>Vat in %</b></th>";
}
$html.= "<th style='border-collapse: collapse; border:solid 1px #000; padding-right:5px;;width:270px' align='right' ><b>Total  in <span style='font-family:Rupee Foradian'>`</span> </b></th>";
$html.= "</tr>";
$html.= "</thead>";
$html.= "<tbody>";
$result_r = mysql_query("SELECT * FROM  purchaseorder_tbl where po_year='".$po_year."' and Po_no=" .$po_no,$conn);
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
$html.= "<tr>";                
$html.= "<tr><td style='border-collapse: collapse; border:solid 1px #000; padding:5px' align='center' valign='center' >".$n."</td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000;padding:5px' align='left' >" .$productname. "</td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000;padding:5px' align='center'   >" .$product_qty. "</td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000;padding:5px' align='center'>" .$units. "</td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000;padding:5px' align='center'  > " .formatInIndianStyle($ratesperproduct). "</td>";
if($inclusive != '0')
{
$html.= "<td style='border-collapse: collapse; border:solid 1px #000;padding:5px' align='center'>" .$vat. "</td>";
}
$html.= "<td style='border-collapse: collapse; border:solid 1px #000; padding:10px' align='right' > " .formatInIndianStyle($Amount). "</td>";
$html.= "</tr>";
 }

$html.= "<tr>";
if($inclusive != '0')
{

$html.= "<td colspan='4' class='white'></td>";
}
else
{
	$html.= "<td colspan='3' class='white'></td>";

}
$html.= "<td class='cyan white-text'  colspan='2'  align='center' style='border-collapse: collapse; border:solid 1px #000;padding:5px'><b>Grand Total</b></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000; padding-right:10px' align='right'><b><span style='font-family:Rupee Foradian'>`</span> </span> ".formatInIndianStyle($subtotal)."</b></td>";
$html.= "</tr>";
$html.= "</tbody>";
$html.= "</table>";
$html.= "<div class='invoice-footer'>";
$html.="<div class=MsoNormal>";
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
<div class=MsoNormal>
<p>Thanking you, <br />
Yours Sincerely<br />
</p>
<img src='images/anisa_digi.jpg' width='83' height='57' />
<p class='header'>Anisa Fathima.H<br/>Purchase Manager</p>
</div>
</div>
</div>
</div>
 <table id='hrdftrtbl' border='0' cellspacing='0' cellpadding='0'>
 <tr><td><!--Header--> <div style='mso-element:header' id='h1' > <p class='MsoHeader'>
 <table border='0' width='100%'><tr><td align='right' width='100%'><img src='images/head.jpg'  style='width:126px'/></td></tr></table></p>
</div></td>
<tr><td><div style='mso-element:footer' id='f1'><p class='MsoFooter'><table width='100%' border='0' cellspacing='0' cellpadding='0'>
<tr><td align='center' width='100%'> <div align='center'><img src='images/footerp.jpg' alt='footer' /></div> </td> </tr></table></p></div>
</td></tr></table>
</div>";
}

else if($type == 'upvc')
{
	$html.= "<div class='Section1'>";
    $html.= "<div class=MsoNormal>"; $result2 = mysql_query("SELECT * FROM  vendor_tbl where ven_compname='".mysql_real_escape_string($ven_id)."'",$conn);
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

		
		
		
$html.=  "<div align='right' class=MsoNormal><b>" .$po_date. "</b><br /></div>";
$html.= "<b>F/PUR/SIS-PO/".$projectsingle."/".$po_no. "/". $curYear."-".$nexYear."</b><br />";
$html.= "<b>To:</b><br />";
$html.= "<b>M/S.".$row2['ven_compname'] ."</b><br/>";
$html.= "<div style='width:250px; height:auto' class=MsoNormal>".$row2['ven_add1']. "</div>";
$html.= "<div align='center' style='width:100%' class=MsoNormal><b>Kind Attention: " .$row2['ven_contactperson'] . "</b></div>";
if( $refno != '')
{
$html.= "Ref : ". $refno.  " - Amended Qty Without Glass Dated dated on " . $refdate."<br>";
}


if( $row['project_name'] != 'Head Office')
{
$html.= "<b>Sub : &nbsp;</b>Our PO : " .$po_no." for window for our project <b> " .$project_name."&nbsp; Block &nbsp; ".$block."</b> at ".$place."&nbsp;".$row['city']." .<br>";
$html.=  "We are pleased to place the Purchase order as per the details mentioned below for our Project " ;

}
else
{
	$html.= "<b>Sub : &nbsp;</b>Our PO : " .$po_no." for window for our <b> " .$project_name."</b> at ".$place."&nbsp;".$row['city']." .<br>";
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
$html.= "<th colspan='2' style='border-collapse: collapse; border:solid 1px #000' align='center' ><b>Material Cost <span style='font-family:Rupee Foradian'>`</span> </b></th>";
$html.= "<th colspan='2' style='border-collapse: collapse; border:solid 1px #000' align='center'><b> Installation Cost  in <span style='font-family:Rupee Foradian'>`</span></b></th>";
$html.= "<th style='border-collapse: collapse; border:solid 1px #000; padding-right:10px' align='right'><b>Amount in  </b></th>";
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
$html.= "<th style='border-collapse: collapse; border:solid 1px #000; padding-right:10px' align='right'><b></b></th>";
$html.= " </tr>";
$result_r = mysql_query("SELECT * FROM  purchaseorder_tbl where po_year='".$po_year."' and  Po_no=" .$po_no,$conn);

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
$html.=  "<td style='border-collapse: collapse; border:solid 1px #000'><div class='input-field col s8' align='center'>".formatInIndianStyle($m_basic)."</div></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000; padding-right:10px' align='right'><div class='input-field col s8'>".$m_total."</div></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000' align='center'><div class='input-field col s8'>".formatInIndianStyle($i_basic)."</div></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000; padding-right:10px' align='right'><div class='input-field col s8'>".formatInIndianStyle($i_total)."</div></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000; padding-right:10px' align='right'><div class='input-field col s8'>".formatInIndianStyle($Amount)."</div></td>";
$html.= "</tr>";
 }

$html.= "<tr>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'>BC</td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000; padding-right:10px' align='right'><div class='input-field col s9'>". formatInIndianStyle($basictotal)."</td>";
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
$html.= "<td style='border-collapse: collapse; border:solid 1px #000; padding-right:10px' align='right'><div class='input-field col s9'>". formatInIndianStyle($transportation)."</div></td>";
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
$html.= "<td style='border-collapse: collapse; border:solid 1px #000; padding-right:10px' align='right'><div class='input-field col s9'>". formatInIndianStyle($gt)."</div></td>";	
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'>&nbsp;</td>";	
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'>&nbsp;</td>";	
$html.= "<td style='border-collapse: collapse; border:solid 1px #000'>&nbsp;</td>";	
$html.= "<td style='border-collapse: collapse; border:solid 1px #000; padding-right:10px' align='right'><div class='input-field col s9'>". formatInIndianStyle($gt1)."</div></td>";	
$html.= "<td style='border-collapse: collapse; border:solid 1px #000; padding-right:10px' align='right'><div class='input-field col s9'> <span style='font-family:Rupee Foradian'>`</span>  ". formatInIndianStyle($subtotal)."</div></td>";	
$html.= "</tr>";



$html.= "</tbody>";	
$html.= "</table>";
$html.= "<div class=MsoNormal>";
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
$html.= "</div>";
$html.= "Thanking you, <br />";
$html.= "Yours Sincerely<br />";
$html.= "<img src='images/anisa_digi.jpg' width='83' height='57' /><br />";
$html.= " Anisa Fathima.H <br />";
$html.= " Purchase Manager";
$html.= " <div>";
$html.= "</div>";
$html.= "</div>
 <table id='hrdftrtbl' border='0' cellspacing='0' cellpadding='0'>
 <tr><td><!--Header--> <div style='mso-element:header' id='h1' > <p class='MsoHeader'>
 <table border='0' width='100%'><tr><td align='right' width='100%'><img src='images/head.jpg'  style='width:126px'/></td></tr></table></p>
</div></td>
<tr><td><div style='mso-element:footer' id='f1'><p class='MsoFooter'><table width='100%' border='0' cellspacing='0' cellpadding='0'>
<tr><td align='center' width='100%'> <div align='center'><img src='images/footerp.jpg' alt='footer' /></div> </td> </tr></table></p></div>
</td></tr></table>
";

$html.= "</div>";
}
else if($type == 'steel')
{

    $html.= "<div class='Section1'  style='position:relative; width:1024px; margin-top:100px; '>";
    $html.= "<div class=MsoNormal>"; $result2 = mysql_query("SELECT * FROM  vendor_tbl where ven_compname='".mysql_real_escape_string($ven_id)."'",$conn);
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

		 

$html.=  "<div align='right' class=MsoNormal><b>" .$po_date. "</b><br /></div>";
$html.= "<b>F/PUR/SIS-PO/".$projectsingle."/".$po_no. "/". $curYear."-".$nexYear."</b><br />";
$html.= "<b>To:</b><br />";
$html.= "<b>M/S.".$row2['ven_compname'] ."</b><br/>";
$html.= "<div style='width:250px; height:auto' class=MsoNormal>".$row2['ven_add1']. "</div><br/>";
$html.= "<div align='center' style='width:100%' class=MsoNormal><b>Kind Attention: " .$row2['ven_contactperson'] . "</b></div>";
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
$html.= "<th style='border-collapse: collapse; border:solid 1px #000;padding:5px;width:100px; height:25px' align='center'><b>Rate per ton in <span style='font-family:Rupee Foradian'>`</span> </b></th>";
if($inclusive != '0')
{
$html.= "<th style='border-collapse: collapse; border:solid 1px #000;padding:5px;width:70px' align='center'><b>Vat in %</b></th>";
}
$html.= "<th style='border-collapse: collapse; border:solid 1px #000; padding:5px;width:100px' align='center'><b>Make</b></th>";

$html.= "<th style='border-collapse: collapse; border:solid 1px #000; padding-right:5px;width:200px' align='right'><b>Total in <span style='font-family:Rupee Foradian'>`</span> </b></th>";
$html.= "</tr>";
$html.= "</thead>";
$html.= "<tbody>";
$result_r = mysql_query("SELECT * FROM  purchaseorder_tbl where po_year='".$po_year."' and Po_no=" .$po_no,$conn);
		
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
$html.=  "<td style='border-collapse: collapse; border:solid 1px #000;padding:5px' align='center'> " .formatInIndianStyle($ratesperproduct). "</td>";
if($inclusive != '0')
{
$html.=  "<td style='border-collapse: collapse; border:solid 1px #000;padding:5px' align='center'>" .$vat. "</td>";	
}
$html.=  "<td style='border-collapse: collapse; border:solid 1px #000;padding:5px' align='center'>" .$brand. "</td>";

$html.= "<td style='border-collapse: collapse; border:solid 1px #000; padding-right:5px' align='right' >" .formatInIndianStyle($Amount). "</td>";
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
$html.= "<td  style='border-collapse: collapse; border:solid 1px #000; padding-right:5px' align='right'> <span style='font-family:Rupee Foradian'>`</span> ".formatInIndianStyle($subtotal)."</td>";
$html.= "</tr>";
$html.= "</tbody>";
$html.=  "</table>";
$html.= "<div class='invoice-footer'>";
$html.="<div class='row'>";
$html.= "<div class=MsoNormal>";
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
<div class=MsoNormal>
<p>Thanking you, <br />
Yours Sincerely<br />
</p>
<img src='images/anisa_digi.jpg' width='83' height='57' />
<p class='header'>Anisa Fathima.H<br/>Purchase Manager</p>

</div>
</div>
</div>
</div>
 <table id='hrdftrtbl' border='0' cellspacing='0' cellpadding='0'>
 <tr><td><!--Header--> <div style='mso-element:header' id='h1' > <p class='MsoHeader'>
 <table border='0' width='100%'><tr><td align='right' width='100%'><img src='images/head.jpg'  style='width:126px'/></td></tr></table></p>
</div></td>
<tr><td><div style='mso-element:footer' id='f1'><p class='MsoFooter'><table width='100%' border='0' cellspacing='0' cellpadding='0'>
<tr><td align='center' width='100%'> <div align='center'><img src='images/footerp.jpg' alt='footer' /></div> </td> </tr></table></p></div>
</td></tr></table>
</div>";
}
else if($type == 'door')
{

    $html.= "<div class='Section1'>";
    $html.= "<div class=MsoNormal>"; $result2 = mysql_query("SELECT * FROM  vendor_tbl where ven_compname='".mysql_real_escape_string($ven_id)."'",$conn);
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
		
		 

$html.=  "<div align='right' class=MsoNormal><b>" .$po_date. "</b><br /></div>";
$html.= "<b>F/PUR/SIS-PO/".$projectsingle."/".$po_no. "/". $curYear."-".$nexYear."</b><br />";
$html.= "<b>To:</b><br />";
$html.= "<b>M/S.".$row2['ven_compname'] ."</b><br/>";
$html.= "<div style='width:250px; height:auto' class=MsoNormal>".$row2['ven_add1']. "</div><br/>";
$html.= "<div align='center' style='width:100%' class=MsoNormal><b>Kind Attention: " .$row2['ven_contactperson'] . "</b></div>";

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
$html.= "<th style='border-collapse: collapse; border:solid 1px #000; ' align='center'><b>SNo</b></th>";
$html.= "<th style='border-collapse: collapse; border:solid 1px #000; ' align='center'><b>Description</b></th>";
$html.= "<th style='border-collapse: collapse; border:solid 1px #000; ' align='center'><b>Size</b></th>";
$html.= "<th style='border-collapse: collapse; border:solid 1px #000; ' align='center'><b>Qty</b></th>";
$html.= "<th style='border-collapse: collapse; border:solid 1px #000; ' align='center'><b>CFT</b></th>";
$html.= "<th style='border-collapse: collapse; border:solid 1px #000; ' align='center'><b>Price in <span style='font-family:Rupee Foradian'>`</span> </b> </th>";
$html.= "<th style='border-collapse: collapse; border:solid 1px #000; padding-right:10px' align='right'><b>Total in <span style='font-family:Rupee Foradian'>`</span> </b></th>";
$html.= "</tr>";$html.= "</thead>";
$html.= "<tbody>";
$result_r = mysql_query("SELECT * FROM  purchaseorder_tbl where po_year='".$po_year."' and Po_no=" .$po_no,$conn);
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
$html.= "<tr><td style='border-collapse: collapse; border:solid 1px #000; ' align='center'>".$n."</td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000; ' align='center'>" .$productname. "</td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000; ' align='center'>" .$size. "</td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000; ' align='center'>" .$product_qty1. "</td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000; ' align='center'>" .$product_qty. "</td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000; ' align='center'> " .formatInIndianStyle($ratesperproduct). "</td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000; padding-right:10px' align='right'>" .formatInIndianStyle($Amount). "</td>";
$html.= "</tr>";
 }
$html.= "<tr>";
$html.= "<td colspan='5' style='border-collapse: collapse; border:solid 1px #000; ' align='center'></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000; ' align='center'>SubTotal</td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000; padding-right:10px' align='right'>".formatInIndianStyle($stotal)."</td>";
$html.= "</tr>";
$html.= "<tr>";
$html.= "<td colspan='5' ></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000; ' align='center'>vat</td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000; ' align='center'>".$tax."%</td>";
$html.= "</tr>";
$html.= "<tr>";
$html.= "<td colspan='5' class='white'></td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000; ' align='center'>Grand Total</td>";
$html.= "<td style='border-collapse: collapse; border:solid 1px #000; padding-right:10px' align='right'>  <span style='font-family:Rupee Foradian'>`</span>  ".formatInIndianStyle($subtotal)."</td>";
$html.= "</tr>";
$html.= "</tbody>";
$html.=  "</table>";
$html.= "<div class='invoice-footer'>";
$html.="<div>";
$html.= "<div class=MsoNormal>";
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
<div class=MsoNormal >
<p>Thanking you, <br />
Yours Sincerely<br />
</p>
<img src='images/anisa_digi.jpg' width='83' height='57' />
<p class='header'>Anisa Fathima.H<br/>Purchase Manager</p>

</div>
</div>
</div>
</div>
 <table id='hrdftrtbl' border='0' cellspacing='0' cellpadding='0'>
 <tr><td><!--Header--> <div style='mso-element:header' id='h1' > <p class='MsoHeader'>
 <table border='0' width='100%'><tr><td align='right' width='100%'><img src='images/head.jpg'  style='width:126px'/></td></tr></table></p>
</div></td>
<tr><td><div style='mso-element:footer' id='f1'><p class='MsoFooter'><table width='100%' border='0' cellspacing='0' cellpadding='0'>
<tr><td align='center' width='100%'> <div align='center'><img src='images/footerp.jpg' alt='footer' /></div> </td> </tr></table></p></div>
</td></tr></table>
</div>";
}
echo $html;
echo "</body>";
echo "</html>";
?>