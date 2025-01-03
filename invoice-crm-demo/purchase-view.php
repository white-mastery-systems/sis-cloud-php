<?php
session_start();
include "connect.php";
include "writelog.php";
date_default_timezone_set('Asia/Calcutta'); 
$ip = getenv('REMOTE_ADDR');
$userAgent = getenv('HTTP_USER_AGENT');
$referrer = getenv('HTTP_REFERER');
$query = getenv('QUERY_STRING');
$time = date('Y-m-d H:i:s');
$po_no=$_GET['po_no'];
$po_year=$_GET['po_year'];
$n = 0;
$result1 = mysql_query("SELECT * FROM  purchase_master where po_year='".$po_year."' and po_no=".$po_no,$conn);

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
$type=$row1['type'];
$po_date=$row1['po_date'];
$stotal = $row1['stotal'];
 $refno = $row1['refno'];
$refdate =  $row1['refdate'];
$block=$row1['Block'];
$payment=$row1['payment'];
$subject=$row1['subject'];
$contactname=$row1['contactname'];
$mobileno=$row1['mobileno'];
$inclusive = $row1['inclusive'];
$tpwords = $row1['tpwords'];
$folder = $row1["folder"];
$curYear = $row1['po_year'];
$nexYear = $row1['po_year'] + 1;
}
if($type == 'standard')
{ 
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
	    $project_name = $row['project_name'] ;
	    $place=$row['place'];
		$tinno=$row['tinno'];
	    $cstno=$row['cstno'];
		 $city=$row['city'];
		$address=$row['address'];
		$addressdispname=$row['addressdispname']; 
		$projectshort=$row['projectshort']; 
		$projectsingle=$row['projectsingle'];
			
		}
		
 
echo "<div id='breadcrumbs-wrapper' class=' grey lighten-3'>";
          //Search for small screen
echo "<div class='container-title'>";
echo "<div class='row'>";
echo "<div class='col s12 m12 l12'>";
echo "<h5 class='breadcrumbs-title'>Invoice</h5>";                
echo "<div class='left leftspace navigations'><a href='order.php' class='btn-floating btn-large waves-effect waves-light pink'><i class='material-icons'>arrow_back</i></a></div>
</div>
</div>
</div>
</div>";
      //breadcrumbs end
      //start container
echo "<div class='container'>";
echo "<div id='invoice'>";
echo "<div class='invoice-header'>";
echo "<div class='row section'>";
echo "<div class='col12 s12 m12 l6 right rightspace'><img src='images/SISLOGO.png' />";
echo "</div>";
echo " </div>";
echo "</div>";
echo "<div class='row'>";
echo "<div class='col12 s12 m6 l3 '>";
echo "<div class='col s12 leftspace'><b>To</b></div>";
echo "<div class='col s12 m6 l6'><div class='input-field col s6 left leftspace'>".$row2['ven_compname']. "</div></div>";
echo "<div class='col s12 m6 l6 right rightspace'><div class='input-field col s4 left leftspace'>&nbsp;</div></div></div></div>";
echo "<div class='row'>
<div class='col12 s12 m6 l3'>
<div class='col s12 m6 l6'><div class='input-field col s6 left leftspace' style='width:300px'>".$row2['ven_add1'].".<br /></div></div>
<div class='col s12 m6 l6 right rightspace'><div class='input-field col s8 left leftspace'>&nbsp;</div></div>
</div>
</div>";
echo "<div class='row lineheight'>";
echo "<div class='col s12 leftspace'>";
echo "<b>Kind Attention : </b>".$row2['ven_contactperson']."</div></div>";
if( $refno != '')
{
echo "<div class='row leftspace lineheight'><b>Ref : </b>". $refno.  " <b>Dated </b> on " .$refdate.".</div>";
}


echo "<div class='row  leftspace rightspace'> Sub :  " . $subject ." . </div>";
echo "<div class='row leftspace lineheight rightspace'>We are pleased to place the Purchase order as per the details mentioned below for our"; 
if( $row['project_name'] != 'Head Office')
{
echo "Project <b> " .$project_name."&nbsp; Block &nbsp;".$block."</b>";
}
else
{
	echo "<b> ".$project_name."&nbsp;</b>";

}
echo "the address and the contact person are mentioned below.</div>";

echo "<div class='invoice-lable'>";
echo "<div class='row'>";
echo "<div class='col12 s12 m6 l3 cyan'>";
echo "<div class='col s12 m6 l6 cyan'><h4 class='white-text invoice-text leftspace'><b>F/PUR/SIS-PO/".$projectsingle."/".$po_no. "/". $curYear."-".$nexYear."</b></h4></div>";
echo "<div class='col s12 m6 l6 cyan'><h4 class='white-text invoice-text right rightspace'>".$po_date."</h4></div>";
echo "</div>";
echo "</div>";
echo "</div>";
echo "<div class='invoice-table'>";
echo "<div class='row'>";
echo "<div class='col s12 m12 l12'>";
echo "<table class='table table-full'>";
echo "<thead>";
echo "<tr>";
echo "<th><b>No</b></th>";
if($folder == 'Switches')
{
echo  "<th><b>Code</b></th>";
}
echo "<th><b>Description</b></th>";
echo "<th><b>Qty</b></th>";
echo "<th><b>Units</b></th>";
echo "<th><b>Price</b></th>";

	
if($inclusive != '0')
{
echo "<th><b>Vat in %</b></th>";
}
echo "<th><b>Total</b></th>";
echo "</tr>";
echo "</thead>";
echo "<tbody>";
$result_r = mysql_query("SELECT * FROM  purchaseorder_tbl where po_year='".$po_year."' and Po_no=" .$po_no,$conn);
		
while($row = mysql_fetch_array($result_r))
 {
	$productname = $row['product_name'] ;
	$product_qty=$row['product_qty'];
	$ratesperproduct = $row['ratesperproduct'];
		$product_code = $row['product_code'];
	$vat = $row['vat'];
	$Amount = $row['Amount'];
	$po_date = $row['po_date'];
	$units = $row['units'];
	$n+=1;
                      echo "<tr>";                
echo "<tr><td >".$n."</td>";
if($folder == 'Switches')
{
echo "<td>" .$product_code. "</td>";
 }
echo "<td>" .$productname. "</td>";
echo "<td>" .$product_qty. "</td>";
echo "<td>" .$units. "</td>";
echo "<td> " .number_format($ratesperproduct, 2, '.', '')."</td>";
if($inclusive != '0')
{
echo "<td>" .$vat. "</td>";
}
echo "<td>" .number_format($Amount, 2, '.', ''). "</td>";
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
	echo "<td colspan='4' class='white'></td>";

}
echo "<td class='cyan white-text'>Grand Total</td>";
echo "<td class='cyan strong white-text'>".number_format($subtotal, 2, '.', '')."</td>";
echo "</tr>";
echo "</tbody>";
echo "</table>";
echo "</div>";
echo "</div>";
echo "</div>";
echo "<div class='invoice-footer'>";
echo "<div class='row'>";
echo "<div class='col12 s12 m12 l6 leftspace lineheight'>";

echo "<b>Term and Condition:</b><br />";
echo "Tin No :" .$tinno. "<br />";	
echo "CST No : " .$cstno. "<br />";
echo "Payment : " .$payment. "<br />";  
echo "Delivery : Door Delivery to our" .$place. " ";
if( $project_name != 'Head Office')
{
echo "Site.<br/>";
}
else
{
	echo ".<br/>";
}
if($tpwords != '')
{
echo "Transportation :".$tpwords."<br/>";
}
echo "<span>Loading & Vat :" .$vat_o. "<br />";
echo "<span>Please Supply by " .$ddate. "at our ".$place;
if( $project_name != 'Head Office')
{
echo "Site.<br/>";
}
else
{
	echo ".<br/>";
}


echo "<p class='topalign'><b>Site contact Person : " .$contactname.  " : " .$mobileno. "</b></p>";
echo  "<b>Site address : </b>";
echo   "<b>".$addressdispname. "</b><br />";
echo "<div style='width:300px; padding-left:87px'>" .$address. "</br></div>" ;	
echo "</p>";
echo " </div>
<div class='col12 s12 m6 l6 left leftspace'>
<p>Thanking you, <br />
Yours Sincerely<br />
</p>
<img src='images/anisa_digi.jpg' width='83' height='57' />
<p class='header'>Anisa Fathima.H</p>
<p>Purchase Manager</p>
</div>
</div>
</div>
</div></div>";
$msg = "IP: " . $ip . " TIME: " . $time . " User: " .$_SESSION['st_username'].  " USERAGENT: " .$userAgent. "Action :PONO". $po_no." view";
writeToLogFile($msg);

	   }
else if($type == 'upvc')	
{  $result2 = mysql_query("SELECT * FROM  vendor_tbl where ven_compname='".mysql_real_escape_string($ven_id)."'",$conn);
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
		 $addressdispname=$row['addressdispname']; 
		$projectshort=$row['projectshort']; 	
		$projectsingle=$row['projectsingle'];
		 $city=$row['city'];
			
		}
		else
		{
			echo "Error";
	 }

 
echo "<div id='breadcrumbs-wrapper' class=' grey lighten-3'>";
          //Search for small screen
echo "<div class='container-title'>";
echo "<div class='row'>";
echo "<div class='col s12 m12 l12'>";
echo "<h5 class='breadcrumbs-title'>Invoice</h5>";
echo "<div class='left leftspace navigations'><a href='order.php' class='btn-floating btn-large waves-effect waves-light pink'><i class='material-icons'>arrow_back</i></a></div>
              </div>
            </div>
          </div>
        </div>";
      //breadcrumbs end
	  
  //start container
echo "<div class='container'>";
echo "<div id='invoice'>";
echo "<div class='invoice-header'>";
echo "<div class='row section'>";
echo "<div class='col12 s12 m12 l6 right rightspace'><img src='images/SISLOGO.png' />";
echo "</div>";
echo "</div>";
echo "</div>";
echo "<div class='row'>";
echo "<div class='col12 s12 m6 l3 '>";
echo "<div class='col s12 leftspace'><b>To</b></div>";
echo "<div class='col s12 m6 l6'><div class='input-field col s6 left leftspace'>".$row2['ven_compname']. "</div></div>";
echo "<div class='col s12 m6 l6 right rightspace'><div class='input-field col s4 left leftspace'>&nbsp;</div></div></div></div>";
echo "<div class='row'>
<div class='col12 s12 m6 l3'>
<div class='col s12 m6 l6'><div class='input-field col s6 left leftspace' style='width:300px'>".$row2['ven_add1'].".<br /></div></div>
<div class='col s12 m6 l6 right rightspace'><div class='input-field col s8 left leftspace'>&nbsp;</div></div>
</div>
</div>";
echo "<div class='row lineheight'>";
echo "<div class='col s12 leftspace'>";
echo "<b>Kind Attention : </b>".$row2['ven_contactperson']."</div></div>";
if( $refno != '')
{
echo "<div class='row leftspace lineheight'><b>Ref : &nbsp;</b>".$refno. " - Amended Qty Without Glass <b>Dated</b> &nbsp;".$refdate.".</div>"; 
}
if( $row['project_name'] != 'Head Office')
{

echo "<div class='row leftspace lineheight'><b>Sub : &nbsp;</b>Our PO : " .$po_no." for window for our project <b> " .$project_name."&nbsp;&nbsp; Block &nbsp;".$block."</b> at ".$place."&nbsp;".$row['city']." .</div>";
echo "<div class='row leftspace lineheight'>We are pleased to place the Purchase order as per the details mentioned below for our Project " ;

}
else
{
	echo "<div class='row leftspace lineheight'><b>Sub : &nbsp;</b>Our PO : " .$po_no." for window for our <b> " .$project_name."</b> at &nbsp; " .$place."&nbsp;".$row['city']." .</div>";
echo "<div class='row leftspace lineheight'>We are pleased to place the Purchase order as per the details mentioned below for our Project " ;


}
echo  "<b>" .$project_name. " .</b></div>";
echo "<div class='invoice-lable'>";
echo "<div class='row'>";
echo "<div class='col12 s12 m6 l3 cyan'>";
echo "<div class='col s12 m6 l6 cyan'><h4 class='white-text invoice-text leftspace'><b>F/PUR/SIS-PO/".$projectsingle."/".$po_no. "/". $curYear."-".$nexYear."</b></h4></div>";echo "<div class='col s12 m6 l6 cyan'><h4 class='white-text invoice-text right rightspace'>".$po_date."</h4></div>";
echo "</div>";
echo "</div>";
 echo "</div>";
 echo "<div class='invoice-table'>";
 echo "<div class='row'>";
 echo "<div class='col s12 m12 l12'>";
echo "<table class='table table-full' id=''>";
echo "<thead>";
echo "<tr>";
echo "<th>Sno</th>";
echo "<th><b>Code</b></th>";
echo "<th><b>width</b></th>";
echo "<th><b>height</b></th>";
echo "<th><b>unit</b></th>";
echo "<th><b>type</b></th>";
echo "<th colspan='2'><b>Material Cost</b></th>";
echo "<th colspan='2'><b>Installation Cost</b></th>";
echo "<th><b>Amount</b></th>";
echo "</tr>";
echo "<tr>";
echo "<th></th>";
echo "<th>&nbsp;</th>";
echo "<th></th>";
echo "<th></th>";
echo "<th></th>";
echo "<th></th>";
echo "<th><b>Basic</b></th>";
echo "<th><b>Total</b></th>";
echo "<th><b>Basic</b></th>";
echo "<th><b>Total</b></th>";
echo "<th><b>Total</b></th>";
echo "</tr>";
echo "</thead>";
echo "<tbody>";

include "moneyformat.php";
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
		
echo "<tr><td>".$n."</label></td>";
echo "<td><div class='input-field col s8'> ".$product_code."</div></td>";
echo "<td><div class='input-field col s8'>".$pro_width."</div></td>";
echo "<td><div class='input-field col s8'>".$pro_height."</div></td>";
echo "<td><div class='input-field col s8'>".$product_qty."</div></td>
<td><div class='input-field col s8'>".$productname."</div></td>";
echo "<td><div class='input-field col s8'>".number_format($m_basic, 2, '.', '')."</div></td>
<td><div class='input-field col s8'>".number_format($m_total, 2, '.', '')."</div></td>";
echo "<td><div class='input-field col s8'>".number_format($i_basic, 2, '.', '')."</div></td>
<td><div class='input-field col s8'>".number_format($i_total, 2, '.', '')."</div></td>";
echo "<td><div class='input-field col s8'>".number_format($Amount, 2, '.', '')."</div></td>";

echo "</tr>";
 }

echo "<tr>";
echo "<td></td>";
echo "<td></td>";
echo "<td></td>";
echo "<td></td>";
echo "<td>BC</td>";
echo "<td><div class='input-field col s9'>".number_format($basictotal, 2, '.', '')."</td>";
echo "<td></td>";
echo "<td></td>";
echo "<td></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
echo "<tr>";
echo "<td></td>";
echo "<td></td>";
echo "<td></td>";
echo "<td></td>";
echo "<td>ED</td>";
echo "<td><div class='input-field col s9'>".$ed."%</div></td>
<td></td>
<td></td>
<th></td>
<td></td>
<td></td>
</tr>

<tr>
<td></td>
<td></td>
<td></td>
<td></td>
<td>Vat</td>
<td><div class='input-field col s9'>".$tax."%</div></td>
<td></td>
<td></td>
<th></td>
<td></td>
<td></td>
</tr>
<tr>
<td></td>
<td></td>
<td></td>
<td></td>
<td>Transpotation</td>
<td><div class='input-field col s9'>".number_format($transportation, 2, '.', '')."%</div></td>
<td></td>
<td></td>
<th></td>
<td></td>
<td></td>
</tr>
<tr>
<td></td>
<td></td>
<td></td>
<td></td>
<td>ST</td>
<td><div class='input-field col s9'>".$st."%</div></td>
<td>&nbsp;</td>
<td></td>
<th></td>
<td></td>
<td></td>
</tr>


<tr>
<td></td>
<td></td>
<td></td>
<td></td>
<td>Grand Total</td>
<td><div class='input-field col s9'>".number_format($gt, 2, '.', '')."</div></td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td class='cyan white-text'><div class='input-field col s9'>".number_format($gt1, 2, '.', '')."</div></td>
<td class='cyan strong white-text'><div class='input-field col s9'>".number_format($subtotal, 2, '.', '')."</div></td>
</tr>";
echo "</tbody>";
echo "</table>";
echo "</div>";
echo "</div>";
echo "</div>";
echo "<div class='invoice-footer'>";
echo "<div class='row'>";
echo "<div class='col12 s12 m12 l6 leftspace lineheight'>";

echo "<b>Term and Condition:</b><br />";
echo "<span>Tin No :" .$tinno. "<br />";	
echo "<span>CST No : </span>" .$cstno. "<br />";
echo "<span>Payment : </span>" .$payment. "<br />";  
echo "<span>Delivery : </span>Door Delivery to our" .$place;
if( $project_name != 'Head Office')
{
echo "Site.<br/>";
}
else
{
	echo ".<br/>";
}
if($tpwords != '')
{
echo "Transportation :".$tpwords."<br/>";
}

echo "<span>Loading & Vat :" .$vat_o. "<br />";
echo "<span>Please Supply by " .$ddate. "at our ".$place. "Site<br />";
echo "<p class='topalign'><b>Site contact Person : " .$contactname.  " : " .$mobileno. "</b></p>";
echo  "<b>Site address : </b>";
echo   "<b>".$addressdispname. "</b><br />";
echo "<div style='width:300px; padding-left:87px'>" .$address. "</br></div>" ;	
echo "</p>";
echo " </div>
<div class='col12 s12 m6 l6 left leftspace'>
<p>Thanking you, <br />
Yours Sincerely<br />
</p>
<img src='images/anisa_digi.jpg' width='83' height='57' />
<p class='header'>Anisa Fathima.H</p>
<p>Purchase Manager</p>
</div>
</div>
</div>
</div>";
 echo "</div>";
 $msg = "IP: " . $ip . " TIME: " . $time . " User: " .$_SESSION['st_username'].  " USERAGENT: " .$userAgent. "Action :PONO". $po_no." view";
writeToLogFile($msg);

	  
}
else if($type == 'steel')
{  $result2 = mysql_query("SELECT * FROM  vendor_tbl where ven_compname='".mysql_real_escape_string($ven_id)."'",$conn);
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
		$addressdispname=$row['addressdispname']; 
		$projectshort=$row['projectshort']; 
		$projectsingle=$row['projectsingle']; 
		 $city=$row['city'];	
		}
		else
		{
			echo "Error";
	 }
 
echo "<div id='breadcrumbs-wrapper' class=' grey lighten-3'>";
          //Search for small screen
echo "<div class='container-title'>";
echo "<div class='row'>";
echo "<div class='col s12 m12 l12'>";
echo "<h5 class='breadcrumbs-title'>Invoice</h5>";
echo "<div class='left leftspace navigations'><a href='order.php' class='btn-floating btn-large waves-effect waves-light pink'><i class='material-icons'>arrow_back</i></a></div>
              </div>
            </div>
          </div>
        </div>";
      //breadcrumbs end
      //start container
echo "<div class='container'>";
echo "<div id='invoice'>";
echo "<div class='invoice-header'>";
echo "<div class='row section'>";
echo "<div class='col12 s12 m12 l6 right rightspace'><img src='images/SISLOGO.png' />";
echo "</div>";
echo "</div>";
echo "</div>";
echo "<div class='row'>";
echo "<div class='col12 s12 m6 l3 '>";
echo "<div class='col s12 leftspace'><b>To</b></div>";
echo "<div class='col s12 m6 l6'><div class='input-field col s6 left leftspace'>".$row2['ven_compname']. "</div></div>";
echo "<div class='col s12 m6 l6 right rightspace'><div class='input-field col s4 left leftspace'>&nbsp;</div></div></div></div>";
echo "<div class='row'>
<div class='col12 s12 m6 l3'>
<div class='col s12 m6 l6'><div class='input-field col s6 left leftspace' style='width:300px'>".$row2['ven_add1'].".<br /></div></div>
<div class='col s12 m6 l6 right rightspace'><div class='input-field col s8 left leftspace'>&nbsp;</div></div>
</div>
</div>";
echo "<div class='row lineheight'>";
echo "<div class='col s12 leftspace'>";
echo "<b>Kind Attention : </b>".$row2['ven_contactperson']."</div></div>";
if( $refno != '')
{
echo "<div class='row leftspace lineheight'><b>Ref : </b>". $refno.  " <b>Dated </b> on " .$refdate.".</div>";
}
echo "<div class='row leftspace lineheight'><b>Sub : &nbsp;  " .$subject. ".</b></div>";
echo "<div class='row leftspace lineheight rightspace'>We are pleased to place the Purchase order as per the details mentioned below for our"; 
if( $row['project_name'] != 'Head Office' or $row['project_name'] != 'S.I.S Safaa' )
{
echo "Project <b> " .$project_name."&nbsp; Block &nbsp;".$block."</b>";
}
else
{
	echo "<b> " .$project_name."&nbsp;</b>";

}
echo "the address and the contact person are mentioned below.</div>";

echo "<div class='invoice-lable'>";
echo "<div class='row'>";
echo "<div class='col12 s12 m6 l3 cyan'>";
echo "<div class='col s12 m6 l6 cyan'><h4 class='white-text invoice-text leftspace'><b>F/PUR/SIS-PO/".$projectsingle."/".$po_no. "/". $curYear."-".$nexYear."</b></h4></div>";echo "<div class='col s12 m6 l6 cyan'><h4 class='white-text invoice-text right rightspace'>".$po_date."</h4></div>";
echo "</div>";
echo "</div>";
 echo "</div>";
echo "<div class='invoice-table'>";
echo "<div class='row'>";
echo "<div class='col s12 m12 l12'>";
echo "<table class='table table-full'>";
echo "<thead>";
echo "<tr>";
echo "<th><b>No</b></th>";
echo "<th><b>Description</b></th>";
echo "<th><b>Detail</b></th>";

echo "<th><b>Tonnage</b></th>";
echo "<th><b>Price</b></th>";
if($inclusive != '0')
{
echo "<th><b>Vat in %</b></th>";
}
echo "<th><b>Make</b></th>";
echo "<th><b>Total</b></th>";
echo "</tr>";
echo "</thead>";
echo "<tbody>";
include "moneyformat.php";
$result_r = mysql_query("SELECT * FROM  purchaseorder_tbl where po_year='".$po_year."' and  Po_no=" .$po_no,$conn);		
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
echo "<tr><td >".$n."</td>";
echo "<td>" .$productname. "</td>";
echo "<td>" .$details. "</td>";
echo "<td>" .$product_qty. "</td>";
echo "<td> " .number_format($ratesperproduct, 2, '.', ''). "</td>";
if($inclusive != '0')
{
echo "<td>" .$vat. "</td>";	
}
echo "<td>" .$brand. "</td>";	
echo "<td>" .number_format($Amount, 2, '.', ''). "</td>";
echo "</tr>";
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
echo "<td class='cyan white-text'>Grand Total</td>";
echo "<td class='cyan strong white-text'>".number_format($subtotal, 2, '.', '')."</td>";
echo "</tr>";
echo "</tbody>";
echo "</table>";
echo "</div>";
echo "</div>";
echo "</div>";
echo "<div class='invoice-footer'>";
echo "<div class='row'>";
echo "<div class='col12 s12 m12 l6 leftspace lineheight'>";

echo "<b>Term and Condition:</b><br />";
echo "<span>Tin No :" .$tinno. "<br />";	
echo "<span>CST No : </span>" .$cstno. "<br />";
echo "<span>Payment : </span>" .$payment. "<br />";  
echo "<span>Delivery : </span>Door Delivery to our" .$place;
if( $project_name != 'Head Office')
{
echo "Site.<br>";
}
else
{
	echo ".<br>";
}

if($tpwords != '')
{
echo "Transportation :".$tpwords."<br/>";
}
echo "<span>Loading & Vat :" .$vat_o. "<br />";
echo "<span>Please Supply by " .$ddate. "at our ".$place;
if( $project_name != 'Head Office')
{
echo "Site.<br>";
}
else
{
	echo ".<br>";
}

echo "<p class='topalign'><b>Site contact Person : " .$contactname.  " : " .$mobileno. "</b></p>";
echo  "<b>Site address : </b>";
echo   "<b>".$addressdispname. "</b><br />";
echo "<div style='width:300px; padding-left:87px'>" .$address. "</br></div>" ;	
echo "</p>";
echo " </div>
<div class='col12 s12 m6 l6 left leftspace'>
<p>Thanking you, <br />
Yours Sincerely<br />
</p>
<img src='images/anisa_digi.jpg' width='83' height='57' />
<p class='header'>Anisa Fathima.H</p>
<p>Purchase Manager</p>
</div>
</div>
</div>
</div>";
echo "</div>";
$msg = "IP: " . $ip . " TIME: " . $time . " User: " .$_SESSION['st_username'].  " USERAGENT: " .$userAgent. "Action :PONO". $po_no." view";
writeToLogFile($msg);

	   }
else if($type == 'door')
{  $result2 = mysql_query("SELECT * FROM  vendor_tbl where ven_compname='".mysql_real_escape_string($ven_id)."'",$conn);
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
		
 
echo "<div id='breadcrumbs-wrapper' class=' grey lighten-3'>";
          //Search for small screen
echo "<div class='container-title'>";
echo "<div class='row'>";
echo "<div class='col s12 m12 l12'>";
echo "<h5 class='breadcrumbs-title'>Invoice</h5>";
echo "<div class='left leftspace navigations'><a href='order.php' class='btn-floating btn-large waves-effect waves-light pink'><i class='material-icons pink'>arrow_back</i></a></div>
              </div>
            </div>
          </div>
        </div>";
      //breadcrumbs end
      //start container
echo "<div class='container'>";
echo "<div id='invoice'>";
echo "<div class='invoice-header'>";
echo "<div class='row section'>";
echo "<div class='col12 s12 m12 l6 right rightspace'><img src='images/SISLOGO.png' />";
echo "</div>";
echo "</div>";
echo "</div>";
echo "<div class='row'>";
echo "<div class='col12 s12 m6 l3 '>";
echo "<div class='col s12 leftspace'><b>To</b></div>";
echo "<div class='col s12 m6 l6'><div class='input-field col s6 left leftspace'>".$row2['ven_compname']. "</div></div>";
echo "<div class='col s12 m6 l6 right rightspace'><div class='input-field col s4 left leftspace'>&nbsp;</div></div></div></div>";
echo "<div class='row'>
<div class='col12 s12 m6 l3'>
<div class='col s12 m6 l6'><div class='input-field col s6 left leftspace' style='width:300px'>".$row2['ven_add1'].".<br /></div></div>
<div class='col s12 m6 l6 right rightspace'><div class='input-field col s8 left leftspace'>&nbsp;</div></div>
</div>
</div>";
echo "<div class='row lineheight'>";
echo "<div class='col s12 leftspace'>";
echo "<b>Kind Attention : </b>".$row2['ven_contactperson']."</div></div>";
if( $refno != '')
{
echo "<div class='row leftspace lineheight'><b>Ref : </b>". $refno.  " <b>Dated </b> on " .$refdate.".</div>";
}
echo "<div class='row leftspace lineheight'><b>Sub : &nbsp;  " .$subject. ".</b></div>";
echo "<div class='row leftspace lineheight rightspace'>We are pleased to place the Purchase order as per the details mentioned below for our"; 
if( $row['project_name'] != 'Head Office' or $row['project_name'] != 'S.I.S Safaa' )
{
echo "Project <b> " .$project_name."&nbsp; Block &nbsp;".$block."</b>";
}
else
{
	echo "<b> " .$project_name."&nbsp;</b>";

}
echo "the address and the contact person are mentioned below.</div>";

echo "<div class='invoice-lable'>";
echo "<div class='row'>";
echo "<div class='col12 s12 m6 l3 cyan'>";
echo "<div class='col s12 m6 l6 cyan'><h4 class='white-text invoice-text leftspace'><b>F/PUR/SIS-PO/".$projectsingle."/".$po_no. "/". $curYear."-".$nexYear."</b></h4></div>";echo "<div class='col s12 m6 l6 cyan'><h4 class='white-text invoice-text right rightspace'>".$po_date."</h4></div>";
echo "</div>";
echo "</div>";
 echo "</div>";
echo "<div class='invoice-table'>";
echo "<div class='row'>";
echo "<div class='col s12 m12 l12'>";
echo "<table class='table table-full'>";
echo "<thead>";
echo "<tr>";
echo "<th ><input type='checkbox' id='check_all' class='case' /><label for='check_all'></label></th>";
echo "<th>Code</th>";
echo "<th>Description</th>";
echo "<th>Size</th>";
echo "<th>Qty</th>";
echo "<th>CFT</th>";
echo "<th>price</th>";
echo "<th>Total</th>";
echo "</tr>";
echo "</thead>";
echo "<tbody>";
echo "<tr>";
echo "</thead>";
echo "<tbody>";
include "moneyformat.php";
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
echo "<tr>";                
echo "<tr><td >".$n."</td>";
echo "<td>" .$product_code. "</td>";
echo "<td>" .$productname. "</td>";
echo "<td>" .$size. "</td>";
echo "<td>" .$product_qty1. "</td>";
echo "<td>" .$product_qty. "</td>";
echo "<td> " .number_format($ratesperproduct, 2, '.', ''). "</td>";
echo "<td>" .$Amount. "</td>";
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
echo "<td class=''>".$tax."%</td>";
echo "</tr>";
echo "<tr>";
echo "<td colspan='6' class='white'></td>";
echo "<td class='cyan white-text'>Grand Total</td>";
echo "<td class='cyan strong white-text'>".number_format($subtotal, 2, '.', '')."</td>";
echo "</tr>";

echo "</tbody>";
echo "</table>";
echo "</div>";
echo "</div>";
echo "</div>";
echo "<div class='invoice-footer'>";
echo "<div class='row'>";
echo "<div class='col12 s12 m12 l6 leftspace lineheight'>";

echo "<b>Term and Condition:</b><br />";
echo "<span>Tin No :" .$tinno. "<br />";	
echo "<span>CST No : </span>" .$cstno. "<br />";
echo "<span>Payment : </span>" .$payment. "<br />";  
echo "<span>Delivery : </span>Door Delivery to our" .$place;
if( $project_name != 'Head Office')
{
echo "Site.<br/>";
}
else
{
	echo ".<br/>";
}

echo "</td></tr>";
if($tpwords != '')
{
echo "Transportation :".$tpwords."<br/>";
}
echo "<span>Loading & Vat :" .$vat_o. "<br />";
echo "<span>Please Supply by " .$ddate. "at our ".$place. "Site<br />";
echo "<p class='topalign'><b>Site contact Person : " .$contactname.  " : " .$mobileno. "</b></p>";
echo  "<b>Site address : </b>";
echo   "<b>".$addressdispname. "</b><br />";
echo "<div style='width:300px; padding-left:87px'>" .$address. "</br></div>" ;	
echo "</p>";
echo " </div>
<div class='col12 s12 m6 l6 left leftspace'>
<p>Thanking you, <br />
Yours Sincerely<br />
</p>
<img src='images/anisa_digi.jpg' width='83' height='57' />
<p class='header'>Anisa Fathima.H</p>
<p>Purchase Manager</p>
</div>
</div>
</div>
</div>";
    //Floating Action Button -->
           
           // Floating Action Button -->
echo "</div>";
$msg = "IP: " . $ip . " TIME: " . $time . " User: " .$_SESSION['st_username'].  " USERAGENT: " .$userAgent. "Action :PONO". $po_no." view";
writeToLogFile($msg);

	   }
       ?>