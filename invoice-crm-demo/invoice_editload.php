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

$result1 = mysql_query("SELECT * FROM  invoicetable where invoicenotype='".$po_no."'",$conn);
if($row1 = mysql_fetch_array($result1))
	 {
$projectname=$row1['projectname'] ;
$block=$row1['block'];
$floorno=$row1['floorno'];
$flatno=$row1['flatno'];
$invoiceno=$row1['invoiceno'];
$invoicenotype=$row1['invoicenotype'];
$invoicedate=$row1['invoicedate'];
$gstin=$row1['gstin'];
$panno=$row1['panno'];
$hsn_sac_code=$row1['hsn_sac_code'];    
$total=$row1['total'];
$lc_amount=$row1['lc_amount'];
$taxamount=$row1['taxamount'];
$sgst=$row1['sgst'];
$cgst=$row1['cgst'];
$roundtotal=$row1['roundtotal'];
$grandtotal=$row1['grandtotal'];
$gstper=$row1['gstper'];
$status=$row1['status'];
$invoicedate=$row1['invoicedate'];
$description=$row1['description'];
$totalword=$row1['totalword'];
$po_year=$row1['po_year'];
$curYear = $row1['po_year'];
$nexYear = $row1['po_year'] + 1;
    
    if($status == 'cancel')
{
    $stylecss = 'display:block' ;   
    }
    else
    {
      $stylecss = 'display:none';       
    }
    if($gstper == '0')
    {
    $roundamount = '0' ;   
    }
    else
    {
     $roundamount =  $roundtotal-$total;
     $finaltotal =  $roundtotal-$gtotal;   
    }
   
	 }

  	$result = mysql_query("SELECT * FROM clientmaster where projectname='".$row1['projectname']."' and block='".$row1['block']."' and floor='".$row1['floorno']."' and flatno='".$row1['flatno']."' ",$conn);
	if($row= mysql_fetch_array($result))
	 {
	    $projectname = $row['projectname'] ;
	    $buyername=$row['name'];	
		$address1=$row['address1']; 
        $address2=$row['address2'];
        $address3=$row['address3'];	
		$contact=$row['contact'];	
        $pannoc=$row['panno'];
        		}
		else
		{
			echo "Error";
	 }

	 

include "connect.php";

echo "<input type='hidden' name='po_year' id='po_year'  placeholder='Date' value='".$po_year."' readonly />";

echo "<div id='invoice'>";
echo "<div class='invoice-header'>";
echo "<div class='row section'>";
echo "<div class='col12 s12 m12 l6 right rightspace'><img src='images/SISLOGO.png' />";
echo "</div>";
echo "</div>";
echo "</div>";
echo "<div class='invoice-lable'>";
echo "<div class='row'>";
echo "<div class='col12 s12 m6 l3 cyan'>";
echo "<div class='col s12 m6 l6' id='cy-txt'>&nbsp;</div>";
echo "<div class='col s12 m6 l6 lineheight'><div class='input-field col s3 right rightspace'><input type='text' name='po_date' id='po_date'  placeholder='Date' value='".$invoicedate."' /> </div></div>";

echo "</div>";
echo "</div>";
echo "<div class='row'>";
echo "<div class='col s12 m6 l6'><div class='input-field col s8 left leftspace'><input type='text' name='po_no' id='po_no' readonly  class='validate' value='".$invoicenotype."'> </div></div>";
echo "<div class='col s12 m6 l6 lineheight'>
<div class='col s2 left leftspace' >
<select name='status' id='status' required='required' class='resetThis' required >";
echo "<option".(($status == 'open')? ' selected="selected"' : '').">open</option>";	
echo "<option".(($status == 'cancel')? ' selected="selected"' : '').">cancel</option>";	

echo "</select><br>

<script>
$('#status').change(function () {

var status = $('#status').val();
if(status == 'cancel')
{
$('#description').css('display','block');
}
else
{
$('#description').css('display','none');
}
});
</script>";
echo "</div><div class='col s8 left leftspace' id='description' style='$stylecss'> 
<input type='text' name='desc' id='desc'  class='validate'  value='".$description."' placeholder>";
echo "</div>   </div>";

echo "<div class='col s12 leftspace'>&nbsp;</div>";
echo "<div class='col s12 m12 l12' id='pur-new'>
<div class='col s2 left leftspace' ><input type='text' name='project_name' id='project_name' readonly  class='validate' value='".$projectname."'></div><div class='col s2 left' style='margin-left:5px'>";
echo "<input type='text' name='blockname' id='blockname' readonly  class='validate' value='".$block."'></div><div class='col s2 left' style='margin-left:5px'><br></div>";
echo "</div>
<script>
$('#blockname').change(function () {
$('#bload').css('display','block');
 $('#bload').html(".chr(34)."<img src='images/loading.gif' alt='loading'>".chr(34).");
var project_name = $('#project_name').val();

var blockname = $(this).val(); 
$.post('get_floorno.php', { project_name: project_name,blockname:blockname }, function (data) {

$('#floorno').html(data);
$('#bload').css('display','none');
});
});
</script>";

echo "<div class='col s12 leftspace'>&nbsp;</div> ";
echo "<div class='col s12 m12 l12' id='pur-new'><div id='bload' class='col s2 left leftspace' style='display:none'></div> <div class='col s2 left leftspace' >
<select name='floorno' id='floorno' required='required' class='resetThis' required >";
echo "<option selected value=''>SELECT FLOOR NO</option>";
$result = mysql_query("SELECT distinct floor FROM clientmaster where projectname='".$projectname."' and block='".$block."'",$conn);
  while ($row = mysql_fetch_array($result)) {
	  	echo "<option".(($floorno == $row['floor'])? ' selected="selected"' : '').">" .$row['floor']. "</option>";	
   }
echo "</select><br>
";
echo "</div> <div id='fload' class='col s2 left leftspace' style='display:none'></div><div class='col s2 left' style='margin-left:5px'>";
echo "<script>
$('#floorno').change(function () {
$('#fload').css('display','block');
 $('#fload').html(".chr(34)."<img src='images/loading.gif' alt='loading'>".chr(34).");
var project_name = $('#project_name').val();
var blockname = $('#blockname').val(); 
var floorno = $(this).val(); 
$.post('get_flatno.php', { project_name: project_name,blockname:blockname,floor:floorno }, function (data) {
$('#fload').css('display','none');
$('#flatno').html(data);
});
});
</script>";

echo "<select name='flatno' id='flatno' required='required' class='resetThis' required >";
echo "<option selected value=''>SELECT FLAT NO</option>";
$result = mysql_query("SELECT flatno FROM clientmaster where projectname='".$projectname."' and block='".$block."' and floor='".$floorno."'",$conn);

  while ($row = mysql_fetch_array($result)) {
	  	echo "<option".(($flatno == $row['flatno'])? ' selected="selected"' : '').">" .$row['flatno']. "</option>";	
   }
echo "</select><br></div>";
echo "</div>";
echo "<script>
$('#flatno').change(function () {

var project_name = $('#project_name').val();
var blockname = $('#blockname').val(); 
var floorno = $('#floorno').val(); 
var flatno = $(this).val(); 
$('#cload').css('display','block');
 $('#cload').html(".chr(34)."<img src='images/loading.gif' alt='loading'>".chr(34).");
$.post('get_client.php', { project_name: project_name,blockname:blockname,floor:floorno,flatno:flatno }, function (data) {
$('#cload').css('display','none');
var res1= data.split('|');    

    $('#cname').val(res1[0]);
    $('#address1').val(res1[1]);
     $('#address2').val(res1[2]);
      $('#address3').val(res1[3]);
       $('#contact').val(res1[4]);
       $('#panno').val(res1[5]);
});
});
</script>";
echo "<div class='col12 s12 m6 l3 cyan'>";
echo "<div class='col s12 leftspace' id='cload' style='display:none'>&nbsp;</div>";
echo "<div class='col s12 leftspace' id='cload'>&nbsp;</div>";
echo "<div class='col s12 m12 l12'><div class='input-field col s3 left leftspace'><input type='text' placeholder='Buyer Name' id='cname' name='cname' class='ui-autocomplete-input resetThis'  required value='".$buyername."'></div><div class='input-field col s3 left leftspace'><input type='text' placeholder='Address1' id='address1' name='address1' class='ui-autocomplete-input resetThis'  required value='".$address1."'></div></div>";

echo "<div class='col s12 m12 l12'><div class='input-field col s3 left leftspace'><input type='text' placeholder='Address2' id='address2' name='address2' class='ui-autocomplete-input resetThis'  required  value='".$address2."'></div><div class='input-field col s3 left leftspace'><input type='text' placeholder='Address3' id='address3' name='address3' class='ui-autocomplete-input resetThis' value='".$address3."'></div></div>";

echo "<div class='col s12 m12 l12'><div class='input-field col s3 left leftspace'><input type='text' placeholder='Tel' id='contact' name='contact' class=' resetThis'  required value='".$contact."' ></div><div class='input-field col s3 left leftspace'><input type='text' placeholder='GST/PAN' id='panno' name='panno' class=' resetThis' value='".$pannoc."' > </div></div>";

echo "</div>
</div>";
echo "<div class='invoice-table'>";
echo "<div class='row '>";
echo "<div class='col s12 m12 l12 table-responsive '>";
echo "<table class='table table-full' id='itemsTable'>";
echo "<thead>";
echo "<tr>";
echo "<th>S.No</th>";
echo "<th>Description of Goods / Services</th>";
echo "<th>Amount</th>";

echo "</tr>";
echo "</thead>";
echo "<tbody>";
echo "<tr>";

echo "<td><div class='input-field col s8'>1</div></td>";

echo "<td><div class='input-field col s8'>Construction services of multi-storied residential buildings - Advance</div></td>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "</tr>";

echo "<tr>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "<td><div class='input-field col s8'>Total</div></td>";
echo "<td><div class='input-field col s8'><input type='text' name='subvalue' id='subvalue' class='changesNop resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required placeholder='Total'  value='".$total."'> </div></td>";
    
echo "</tr>";

echo "<tr>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "<td><div class='input-field col s8'>Discount</div></td>";
echo "<td><div class='input-field col s8'>
</div></td>";    
echo "</tr>";

echo "<tr>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "<td><div class='input-field col s8'>Land and Construction value</div></td>";
echo "<td><div class='input-field col s8'>

<input type='text' name='lcvalue' id='lcvalue' class='changesNop resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required placeholder='Land and Construction'    value='".$lc_amount."' readonly >  </div></td>";
    
echo "</tr>";

echo "<tr>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "<td><div class='input-field col s8'>Taxable Value</div></td>";
echo "<td><div class='input-field col s8'>

<input type='text' name='taxvalue' id='taxvalue' class='changesNop resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required placeholder='Taxable Value' readonly  value='".$taxamount."'>  </div></td>";
    
echo "</tr>";

echo "<tr>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "<td><div class='input-field col s8'><input type='hidden' name='gstper' id='gstper' class='changesNop resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required placeholder='per' readonly  value='".$gstper."'> </div></td>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";    
echo "</tr>";

echo "<tr>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "<td><div class='input-field col s8'>CGST - 9%</div></td>";
echo "<td><div class='input-field col s8'>
<input type='text' name='cgst' id='cgst' class='changesNop resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required placeholder='CGST' readonly  value='".$cgst."'>  </div></td>";    
echo "</tr>";

echo "<tr>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "<td><div class='input-field col s8'>SGST - 9%</div></td>";
echo "<td><div class='input-field col s8'>
<input type='text' name='sgst' id='sgst' class='changesNop resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required placeholder='SGST' readonly  value='".$sgst."'>  </div></td>";    
echo "</tr>";


echo "<tr>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "<td><div class='input-field col s8'>Total </div></td>";
echo "<td><div class='input-field col s8'>
<input type='text' name='roundvalue' id='roundvalue' class='changesNop resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required placeholder='Round Off'  readonly  value='".$roundtotal."'>  </div></td>";    
echo "</tr>";
echo "<tr>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "<td><div class='input-field col s8'>Total</div></td>";
echo "<td><div class='input-field col s8'>
<input type='text' name='roundvalue1' id='roundvalue1' class='changesNop resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required placeholder='Round Off'  readonly  value='".$roundamount."'>  </div></td>";    
echo "</tr>";
echo "<tr>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";    
echo "</tr>";

echo "<tr>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "<td><div class='input-field col s8'>Grand Total(Inclusive of GST)</div></td>";
echo "<td><div class='input-field col s8'>
<input type='text' name='grandtotal' id='grandtotal' class='changesNop resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required placeholder='Grand Total'  readonly  value='".$grandtotal."'>  </div></td>";    
echo "</tr>";



echo "<tr>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "<td><div class='input-field col s8'>Invoice Value(Inwords)</div></td>";
echo "<td><div class='input-field col s8'><b><span id='grandtotalwords'>".$totalword."</span><input type='hidden' name='totalword' id='totalword'  value='".$totalword."'></b>  </div></td>";    
echo "</tr>";

echo "</tbody>";
echo "</table>";
echo "</div>";
echo "</div>";

echo "<div class='col12 s12 m6 l6 left leftspace'>";
echo "<p>For South India Shelters Private Limited, <br />
 </p>
&nbsp;
<br>
&nbsp;
 <p class='header'>Authorised Signatory</p>

</div>
</div>
</div>
</div>";
echo "<div class='row'><div class='col s2 right'><button type='submit' class='btn-floating btn-large waves-effect waves-light pink' title='submit'><i class='material-icons medium'>done</i>></button> <a class='btn-floating btn-large waves-effect waves-light pink' title='Cancel' id='clear'><i class='material-icons medium'>clear</i></a></div></div>";
      echo "<input type='hidden' name='city' id='city' style='width:auto' placeholder='city' readonly  class='resetThis'  />"; 


 ?>