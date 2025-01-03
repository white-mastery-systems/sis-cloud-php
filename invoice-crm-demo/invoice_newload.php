<?php
session_start();
include "writelog.php";
include "connect1.php";
date_default_timezone_set('Asia/Calcutta'); 
$time = date('Y-m-d H:i:s');
$ip = getenv('REMOTE_ADDR');
$userAgent = getenv('HTTP_USER_AGENT');
$referrer = getenv('HTTP_REFERER');
$query = getenv('QUERY_STRING');
$msg = "IP: " . $ip . " TIME: " . $time . " User: " .$_SESSION['st_username'].  " USERAGENT: " .$userAgent. "Action :Invoice New Page";
writeToLogFile($msg);
//$lognme ;
if (!isset($_SESSION['st_emailid']) and !isset($_SESSION['st_pwd']))
{
header("Location: login.php");
}
$_SESSION['st_emailid'];$_SESSION['st_pwd'];	  
	 
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

include "connect1.php";

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
echo "<div class='col s12 m6 l6 lineheight'><div class='input-field col s3 right rightspace'><input type='text' name='po_date' id='po_date'  placeholder='Date' value='".date('d-m-Y')."' /> </div></div>";
echo "</div>";
echo "</div>";
echo "<div class='row'>";
echo "<div class='col s12 m6 l6'></div>";
echo "<div class='col s12 leftspace'>&nbsp;</div>";
echo "<div class='col s12 m12 l12' id='pur-new'>

<div class='col s3 left leftspace' ><select name='project_name' id='project_name' required='required' class='resetThis' required='required' >";
 if($_SESSION['place'] == 'Trichy')
 {
  $result = mysqli_query($conn,"SELECT distinct projectname FROM clientmaster where city = 'Trichy'");   
 }
else
{
 $result = mysqli_query($conn,"SELECT distinct projectname FROM clientmaster");   
}

  echo "<option selected value=''>SELECT PROJECT NAME</option>";
  while ($row = mysqli_fetch_array($result)) {
	  $project_name = $row['projectname'];
    echo "<option  value='".$row['projectname']."'>" . $row['projectname'] . "</option>";
   
   }
 echo "<option  value='Rent'>Rent</option>";
 echo "</select></div> <div id='pload' class='col s2 left leftspace' style='display:none'></div>  <div class='col s3 left leftspace' style='margin-left:5px'>";
echo "<script>
$('#project_name').change(function () {
var project_name = $(this).val(); 
$('#pload').css('display','block');
 $('#pload').html(".chr(34)."<img src='images/loading.gif' alt='loading'>".chr(34).");

$.post('get_block.php', { project_name: project_name }, function (data) {
$('#pload').css('display','none');

var resb= data.split('|'); 
$('#blockname').html(resb[0]);
$('#itemsTable').html(resb[1]);
$('#itemsTable1').css('display','none');
});

});
</script>";

echo "<select name='blockname' id='blockname' required='required' class='resetThis' required='required' >";
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

if(blockname == 'commercial')
{
$('#itemsTable').html('');
$('#itemsTable1').css('display','none');
$.post('get_rent.php', { project_name: project_name }, function (data) {
$('#pload').css('display','none');
var resb= data; 
$('#itemsTable').html(resb);
$('#itemsTable1').css('display','none');
});
}
});
});
</script>";

echo "<div class='col s12 leftspace'>&nbsp;</div>";
echo "<div class='col s12 m12 l12' id='pur-new'><div id='bload' class='col s2 left leftspace' style='display:none'></div> <div class='col s3 left leftspace' >
<select name='floorno' id='floorno' required='required' class='resetThis' required='required' >";
echo "<option selected value=''>SELECT FLOOR NO</option>";
echo "</select><br>
";
echo "</div><div id='fload' class='col s2 left leftspace' style='display:none'></div> <div class='col s2 left leftspace' style='display:none'></div> <div class='col s3 left leftspace' style='margin-left:5px'>";
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
echo "<select name='flatno' id='flatno' required='required' class='changesNop resetThis' required >";
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
$('#larea').val(res1[6]);
//$('#subvalue').val(0);
$('#gvalue').val(res1[8]);
$('#invoicetotal').val(res1[9]);
$('#plotcost').val(res1[7]);

});
});


  
</script>";
echo "<div class='col s12 leftspace'>&nbsp; <input type='hidden' name='gstper' id='gstper' class='changesNop resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required placeholder='CGST' readonly></div>";
echo "<div class='col s12 m12 l12'><div class='input-field col s3 left leftspace'><select id='gstvalue' name='gstvalue' class='changesNop' required onChange='changetax()'><option value=''>Select Tax</option><option value='0'>No GST</option><option value='18'>GST</option></select></div> <div id='ino' class='col s2 left leftspace' style='display:none'></div> <div class='input-field col s3 left leftspace' ><input type='text' name='po_no' id='po_no' readonly  class='validate' placeholder='Invoice No' > </div></div>";
echo "<div class='col12 s12 m6 l3 cyan'>";
echo "<div class='col s12 leftspace' id='cload'>&nbsp;</div>";
echo "<div class='col s12 m12 l12'><div class='input-field col s3 left leftspace'><input type='text' placeholder='Buyer Name' id='cname' name='cname' class='ui-autocomplete-input resetThis'  required ></div><div class='input-field col s3 left leftspace'><input type='text' placeholder='Address1' id='address1' name='address1' class='ui-autocomplete-input resetThis'  ></div></div>";
echo "<div class='col s12 m12 l12'><div class='input-field col s3 left leftspace'><input type='text' placeholder='Address2' id='address2' name='address2' class='ui-autocomplete-input resetThis' ></div><div class='input-field col s3 left leftspace'><input type='text' placeholder='Address3' id='address3' name='address3' class='ui-autocomplete-input resetThis'  ></div></div>";
echo "<div class='col s12 m12 l12'><div class='input-field col s3 left leftspace'><input type='text' placeholder='Tel' id='contact' name='contact' class=' resetThis' ></div><div class='input-field col s3 left leftspace'><input type='text' placeholder='GST/PAN' id='panno' name='panno' class=' resetThis'>


</div></div>";
echo "</div>
</div>";
echo "<div class='invoice-table'>";
echo "<div class='row'>";
echo "<div class='col s12 m12 l12 table-responsive' id='itemsTable1'>";
echo "<table class='table table-full' id='itemsTable'>";
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