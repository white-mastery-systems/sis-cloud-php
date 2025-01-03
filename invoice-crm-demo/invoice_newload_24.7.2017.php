 <?php 

	 
isset($_POST['type']) ? $type = $_POST['type'] : $type = "none";
 date_default_timezone_set('Asia/Calcutta');
date('d.m.Y');
$MM = date('m');
if($MM < 4 )
{
    $po_year = date('Y')-1;
}
else
{
   $po_year = date('Y');
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
echo "<div class='col s12 m6 l6 lineheight'><div class='input-field col s3 right rightspace'><input type='text' name='po_date' id='po_date'  placeholder='Date' value='".date('d-m-Y')."' readonly /> </div></div>";
echo "</div>";
echo "</div>";
echo "<div class='row'>";
echo "<div class='col s12 m12 l12'><div class='input-field col s3 left leftspace'><select id='gstvalue' name='gstvalue' class='changesNop' required onChange='changetax()'><option value=''>Select Tax</option><option value='0'>No GST</option><option value='18'>GST</option></select></div> <div id='ino' class='col s2 left leftspace' style='display:none'></div> <div class='input-field col s3 left leftspace' ><input type='text' name='po_no' id='po_no' readonly  class='validate' placeholder='Invoice No' > </div></div>";
echo "<div class='col s12 m6 l6'></div>";

echo "<div class='col s12 leftspace'>&nbsp;</div>";
echo "<div class='col s12 m12 l12' id='pur-new'>
<div class='col s3 left leftspace' ><select name='project_name' id='project_name' required='required' class='resetThis' required='required' >";
 
$result = mysql_query("SELECT distinct projectname FROM clientmaster",$conn);
  echo "<option selected value=''>SELECT PROJECT NAME</option>";
  while ($row = mysql_fetch_array($result)) {
	  $project_name = $row['projectname'];
    echo "<option  value='".$row['projectname']."'>" . $row['projectname'] . "</option>";
   }
 

   

echo "</select></div> <div id='pload' class='col s2 left leftspace' style='display:none'></div>  <div class='col s3 left leftspace' style='margin-left:5px'>";
echo "<script>
$('#project_name').change(function () {
var project_name = $(this).val(); 
 $('#pload').css('display','block');
 $('#pload').html(".chr(34)."<img src='images/loading.gif' alt='loading'>".chr(34).");
$.post('get_block.php', { project_name: project_name }, function (data) {
$('#pload').css('display','none');
$('#blockname').html(data);
});
});
</script>";

echo "<select name='blockname' id='blockname' required='required' class='resetThis' required >";
echo "<option selected value=''>SELECT BLOCK</option>";
echo "</select><br></div>";
echo "</div>
<script>
$('#blockname').change(function () {

var project_name = $('#project_name').val();
var blockname = $(this).val(); 
 $('#bload').css('display','block');
 $('#bload').html(".chr(34)."<img src='images/loading.gif' alt='loading'>".chr(34).");
$.post('get_floorno.php', { project_name: project_name,blockname:blockname }, function (data) {
$('#bload').css('display','none');
$('#floorno').html(data);
});
});
</script>";

echo "<div class='col s12 leftspace'>&nbsp;</div>";
echo "<div class='col s12 m12 l12' id='pur-new'><div id='bload' class='col s2 left leftspace' style='display:none'></div> <div class='col s3 left leftspace' >
<select name='floorno' id='floorno' required='required' class='resetThis' required >";
echo "<option selected value=''>SELECT FLOOR NO</option>";
echo "</select><br>
";
echo "</div><div id='fload' class='col s2 left leftspace' style='display:none'></div> <div id='fload' class='col s2 left leftspace' style='display:none'></div> <div class='col s3 left leftspace' style='margin-left:5px'>";
echo "<script>
$('#floorno').change(function () {

var project_name = $('#project_name').val();
var blockname = $('#blockname').val(); 
var floorno = $(this).val(); 
$('#fload').css('display','block');
 $('#fload').html(".chr(34)."<img src='images/loading.gif' alt='loading'>".chr(34).");
$.post('get_flatno.php', { project_name: project_name,blockname:blockname,floor:floorno }, function (data) {
$('#fload').css('display','none');
$('#flatno').html(data);
});
});
</script>";

echo "<select name='flatno' id='flatno' required='required' class='resetThis' required >";
echo "<option selected value=''>SELECT FLAT NO</option>";
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


echo "<div class='col s12 leftspace'>&nbsp;</div>";
echo "<div class='col12 s12 m6 l3 cyan'>";
echo "<div class='col s12 leftspace' id='cload'>&nbsp;</div>";
echo "<div class='col s12 m12 l12'><div class='input-field col s3 left leftspace'><input type='text' placeholder='Buyer Name' id='cname' name='cname' class='ui-autocomplete-input resetThis'  required ></div><div class='input-field col s3 left leftspace'><input type='text' placeholder='Address1' id='address1' name='address1' class='ui-autocomplete-input resetThis'  required></div></div>";
echo "<div class='col s12 m12 l12'><div class='input-field col s3 left leftspace'><input type='text' placeholder='Address2' id='address2' name='address2' class='ui-autocomplete-input resetThis'  required></div><div class='input-field col s3 left leftspace'><input type='text' placeholder='Address3' id='address3' name='address3' class='ui-autocomplete-input resetThis'  ></div></div>";
echo "<div class='col s12 m12 l12'><div class='input-field col s3 left leftspace'><input type='text' placeholder='Tel' id='contact' name='contact' class=' resetThis' ></div><div class='input-field col s3 left leftspace'><input type='text' placeholder='GST/PAN' id='panno' name='panno' class=' resetThis'></div></div>";
echo "</div>
</div>";
echo "<div class='invoice-table'>";
echo "<div class='row'>";
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
echo "<td><div class='input-field col s8'>

<input type='text' name='subvalue' id='subvalue' class='changesNop resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required placeholder='Total'> </div></td>";
    
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

<input type='text' name='lcvalue' id='lcvalue' class='changesNop resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required placeholder='Land and Construction'  readonly>  </div></td>";
    
echo "</tr>";

echo "<tr>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "<td><div class='input-field col s8'>Taxable Value</div></td>";
echo "<td><div class='input-field col s8'>

<input type='text' name='taxvalue' id='taxvalue' class='changesNop resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required placeholder='Taxable Value'  readonly>  </div></td>";
    
echo "</tr>";

echo "<tr>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "<td><div class='input-field col s8'><input type='hidden' name='gstper' id='gstper' class='changesNop resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required placeholder='CGST' readonly> </div></td>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";    
echo "</tr>";

echo "<tr>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "<td><div class='input-field col s8'>CGST - 9%</div></td>";
echo "<td><div class='input-field col s8'>
<input type='text' name='cgst' id='cgst' class='changesNop resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required placeholder='CGST' readonly>  </div></td>";    
echo "</tr>";

echo "<tr>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "<td><div class='input-field col s8'>SGST - 9%</div></td>";
echo "<td><div class='input-field col s8'>
<input type='text' name='sgst' id='sgst' class='changesNop resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required placeholder='SGST' readonly>  </div></td>";    
echo "</tr>";


echo "<tr>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "<td><div class='input-field col s8'>Total Round off</div></td>";
echo "<td><div class='input-field col s8'>
<input type='text' name='roundvalue' id='roundvalue' class='changesNop resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required placeholder='Round Off'  readonly>  </div></td>";    
echo "</tr>";



echo "<tr>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "<td><div class='input-field col s8'>Grand Total(Inclusive of GST)</div></td>";

echo "<td><div class='input-field col s8'>
<input type='text' name='grandtotal' id='grandtotal' class='changesNop resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required placeholder='Grand Total'  readonly>  </div></td>";    
echo "</tr>";



echo "<tr>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "<td><div class='input-field col s8'>Invoice Value(Inwords)</div></td>";
echo "<td><div class='input-field col s8'><b><span id='grandtotalwords'></span><input type='hidden' name='totalword' id='totalword'></b>  </div></td>";    
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