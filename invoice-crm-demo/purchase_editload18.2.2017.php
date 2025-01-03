 <?php 
$po_no=$_POST['po_no'];
$po_year=$_POST['po_year'];
require('connect.php');
$n=0;
 date_default_timezone_set('Asia/Calcutta'); 
date('d.m.Y');
$result1 = mysql_query("SELECT * FROM  purchase_master where po_year='".$po_year."' and  po_no=$po_no",$conn);
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
$project_name_m=$row1['project_name'];
$block=$row1['Block'];
$type =  $row1['type'];
$refno=  $row1['refno'];
$refdate =  $row1['refdate'];
$stotal = $row1['stotal'];
$folder = $row1['folder'];
$payment=$row1['payment'];
$subject=$row1['subject'];
$contactname=$row1['contactname'];
$mobileno=$row1['mobileno'];
$inclusive = $row1['inclusive'];
$tpwords = $row1['tpwords'];

	 }
echo "<input type='hidden' name='po_year' id='po_year'  placeholder='Date' value='".$po_year."' readonly />";
if($type == "standard")
{
echo "<div class='row topspacelarge' align='center'>";
echo "<div class='col s3 leftspace'>";
echo "<input type='text' placeholder='Newstatus' id='newstatus' name='newstatus' class='resetThis' autocomplete='off'value='".$type."' ><br>&nbsp;";
echo "</div>";
echo "<div class='col s3 leftspace'>";
echo "<input type='text' placeholder='Folder' id='folder' name='folder' class='ui-autocomplete-input resetThis'   autocomplete='off' value='".$folder."' required='required'><br>&nbsp;";
echo "</div></div>";
echo "<div id='invoice'>";
echo "<div class='invoice-header'>";
echo "<div class='row '>";
echo "<div class='col12 s12 m12 l6 right rightspace'><img src='images/SISLOGO.png' /></div></div>";
echo "</div></div>";
echo "<div class='invoice-lable'>";
echo "<div class='row'>";
echo "<div class='col12 s12 m6 l3'>";
echo "<div class='col s12 m6 l6'>&nbsp;</div>";
echo "<div class='col s12 m6 l6'><div class='input-field col s3 right rightspace'><input type='text' name='po_date' id='po_date'  placeholder='Date' value='".$po_date."' /> <input type='hidden' name='type' id='type'  placeholder='Date' value='".$type."' /> </div></div></div></div>";
echo "<div class='row'>";
echo "<div class='col s12 m6 l6'><div class='input-field col s6 left leftspace'><input type='text' name='po_no' id='po_no' readonly  class='validate'  value='".$po_no."' /> </div></div>";

 echo "<div class='row'>";
 echo "<div class='col12 s12 m6 l3 '>";
 echo "<div class='col s12 leftspace'><b>To</b></div>"; 
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
 echo "<div class='col s12 m6 l6'><div class='input-field col s6 left leftspace'><input type='text' placeholder='Company Name' id='vendor_name' name='vendor_name' class='ui-autocomplete-input resetThis' autocomplete='off' value='".$row2['ven_compname']. "' required='required' ></div></div>";
 echo "<div class='col s12 m6 l6 right rightspace'><div class='input-field col s4 left leftspace'>&nbsp;</div></div></div></div>";
 echo "<div class='row'>
<div class='col12 s12 m6 l3'>
<div class='col s12 m6 l6'><div class='input-field col s6 left leftspace'><textarea name='ven_add' id='ven_add' value='' placeholder='Address'  style='height:80px' class='resetThis' required='required'>".$row2['ven_add1'] ."</textarea>";
 echo "<input type='hidden' name='ven_id' id='ven_id' placeholder='vendor-id' value='".$row2['ven_id']."' class='resetThis' required='required' /><br /></div></div>
<div class='col s12 m6 l6 right rightspace'><div class='input-field col s8 left leftspace'>&nbsp;</div></div>
</div>
</div>";
 echo "<div class='row'>";
 echo "<div class='col s12'>";
 echo "<center>";
 echo "<div class='input-field col s3 leftspace' style='width:auto; margin-right:8px'> <b>Kind Attention: </b></div>
<div class='input-field col s2'> 
<input type='text' name='ven_contactperson' id='ven_contactperson' placeholder='Contact Person' value='".$row2['ven_contactperson'] ."' class='resetThis' required='required' /></div>
</center>
</div>
</div>";
	$result = mysql_query("SELECT * FROM project_details where project_id=" .$project_id,$conn);
	if($row= mysql_fetch_array($result))
	 {
		 $project_id = $row['project_id'];
	    $project_name = $row['project_name'] ;
	    $place=$row['place'];
		 $city=$row['city'];
		$tinno=$row['tinno'];
	    $cstno=$row['cstno'];
		$address=$row['address'];  			
		}
		else
		{
			echo "Error";
	 }

 echo "<div class='row leftspace'>
<div class='col12 s12 m6 l3'>
<div class='col ref1'>Ref :</div><div class='input-field col ref2'><input type='text' name='refno' id='refno' class='validate resetThis' placeholder='Ref No' value='".$refno."'></div> <div  class='col dat1'>  Dated on </div> <div class='input-field col dat2'> <input type='text' name='refdate' id='refdate' placeholder='Ref Date' class='validate resetThis'  value='".$refdate."' > </div>";


 echo "<div class='col s12 m6 l6 right rightspace'><div class='input-field col s8 left leftspace'>&nbsp;</div></div></div>
</div>";
echo "<div class='row  leftspace'><div class='col s2'  style='width:auto; margin:0 8px 0 0px'> Sub : </div><div class='input-field col s3 left'> <input type='text' name='subject' id='subject' class='validate resetThis' placeholder='Subject' required='required' value='".$subject."' ></div> <div class='col s2'  style='width:auto; margin:0 8px 0 0px'>.</div> </div>";

 echo "<div class='row  leftspace'>
<div class='col s8 left' style='width:auto; margin-right:10px'>
We are pleased to place the Purchase order as per the details mentioned below for our </div> <div class='col s2 left' ><select name='project_name' id='project_name' onChange='projectlist(this.value)' required='required' class='resetThis'>";
	$result = mysql_query("SELECT distinct project_name FROM project_details",$conn);

  echo "<option selected value=''>SELECT</option>";
  while ($row = mysql_fetch_array($result)) {
	  	echo "<option".(($project_name == $row['project_name'])? ' selected="selected"' : '').">" .$row['project_name']. "</option>";	
   }   
echo "</select></div><div class='col s2 left' style='margin-left:5px'>";
echo "<script>
             $('#project_name').change(function () { //Basically saying when the first select box changes values run the function below.
                 var project_name = $(this).val(); // Grab the value of the selection to send to the select-request.php via ajax
                 $.post('blockname.php', { project_name: project_name }, function (data) { // Run a ajax request and send the var make as a post variable named make and return the info in the data var.
                     $('#blockname').html(data); // Have jquery change the html within the second select box with the data we got back from the ajax request.
                 });
             });
</script>";

echo "<select name='blockname' id='blockname' required='required' class='resetThis'>";

echo "<option selected value=''>SELECT</option>";
$result = mysql_query("SELECT distinct blockname FROM project_details where project_name='".$project_name_m."'",$conn);

  while ($row = mysql_fetch_array($result)) {
	  	echo "<option".(($block == $row['blockname'])? ' selected="selected"' : '').">" .$row['blockname']. "</option>";	
   }
echo "</select><br></div>";
echo "<div class='col s6 left'>";
echo " the address and the contact person are mentioned below. <input type='hidden' name='place2' id='place2'   style='width:auto' placeholder='Place' readonly class='resetThis'/> </div>";
echo "</div>";
echo "<div class='invoice-table'>";
echo "<div class='row '>";
echo "<div class='col s12 m12 l12 table-responsive '>";
echo "<table class='table table-full' id='itemsTable'>";
echo "<thead>";
echo "<tr>";
echo "<th ><input type='checkbox' id='check_all'  /><label for='check_all'></label></th>";
echo "<th>Item Name</th>";
echo "<th>Quantity</th>";
echo "<th>Units</th>";
echo "<th>price</th>";
echo "<th>vat</th>";
echo "<th>Total</th>";
echo "</tr>";
echo "</thead>";
echo "<tbody>";
$result_r = mysql_query("SELECT * FROM  purchaseorder_tbl where po_year='".$po_year."' and Po_no=" .$po_no,$conn);

while($row = mysql_fetch_array($result_r))
 {
	 $po_id=$row['po_id'] ;
	$productname = $row['product_name'] ;
	$product_qty=$row['product_qty'];
	$ratesperproduct = $row['ratesperproduct'];
	$vat = $row['vat'];
	$Amount = $row['Amount'];
	$cat_name = $row['cat_name'];
	$product_code = $row['product_code'];	
	$sub_catname = $row['sub_catname'];
	$brand = $row['brand'];
	$size = $row['size'];
	$units = $row['units'];
	$po_date = $row['po_date'];
	$product_code = $row['product_code'];
	
	$n=$n+1;
echo "<tr>";
echo "<td><input type='checkbox' id='selecttd1".$n."'  class='case' value='".$po_id."'/><label for='selecttd1".$n."'></label></td>";
echo "<td><div class='input-field col s8'><input type='text' data-type='pro_name' name='itemDesc[]' id='itemDesc_".$n."' class='resetThis autocomplete_txt1' autocomplete='off'  value=".chr(34).htmlspecialchars($productname).chr(34)." placeholder='DESC' required='required' /> </div></td>";
echo "<td><div class='input-field col s8'><input type='hidden' name='po_id[]' id='po_id_".$n."'  value='" .$po_id."' placeholder='POID' />
<input type='text' name='itemQty[]' id='itemQty_".$n."' class='resetThis changesNo1' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' value='" .$product_qty."' placeholder='QTY' required='required'/>  <input name='cat_name[]' class='resetThis' id='cat_name_".$n."' type='hidden' value='".$cat_name."' />
 <input name='sub_catname[]' class='resetThis' id='sub_catname_".$n."' type='hidden' value='".$sub_catname."'/>
<input name='brand[]'   id='brand_".$n."' tabindex='1' type='hidden' value='".$brand."' class='resetThis'  /><input name='itemCode[]'  class='resetThis' id='itemCode_".$n."'  type='hidden' data-type='product_code' value='".$product_code."'/></div></td>";
echo "<td><div class='input-field col s8'><input name='units[]'  class='units resetThis' id='units_".$n."' tabindex='1' type='text' required placeholder='units' value='".$units."' /></div></td>";

echo "<td><div class='input-field col s8'><input type='text' name='itemPrice[]' id='itemPrice_".$n."'' class='changesNo1 resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' value='".$ratesperproduct."' placeholder='Price' required='required'></div></td>";
echo "<td><div class='input-field col s8'><input type='text' name='itemVat[]' id='itemVat_".$n."' class='changesNo1 resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' value='".$vat."' placeholder='VAT' required='required' /></div></td>";
echo "<td><div class='input-field col s8'><input type='text' name='itemLineTotal[]' id='itemLineTotal_".$n."' class='totalLinePrice_s resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' value='".$Amount. "' placeholder='Total' required='required' /></div></td>";
echo "</tr>";
 }
echo "</tbody>";
echo "</table>";
echo "<table class='table table-full'><tr>";
echo "<td class='white'><button class='btn btn-success addmore1' type='button'>+ Add</button> <button class='btn btn-danger delete1' type='button'>- Delete</button> &nbsp;";
if($inclusive != '0')
{
	echo "<input type='checkbox' name='inclusive' id='inclusive'    value='0' /><label for='inclusive'>Inclusive of All Tax(vat must be zero)</label>";
}
	else
	{
		echo "<input type='checkbox' name='inclusive' id='inclusive'  value='0' checked='checked' /><label for='inclusive'>Inclusive of All Tax(vat must be zero)</label>";
	}
	
	echo "</td>";
echo "<td  class='white'></td>";
echo "<td class='white'></td>";
echo "<td class='white'></td>";
echo "<td class='cyan white-text'>Grand Total</td>";
echo "<td class='cyan strong white-text'><div class='input-field col s8 '><input type='text' readonly class='resetThis' id='subTotal' name='subTotal' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' placeholder='Total' value='".$subtotal."' required='required' /></div></td>";
echo "</tr>";
echo "</table></div>";
echo "</div>";
echo "<div class='invoice-footer'>";
echo "<div class='row'>";
echo "<div class='col12 s12 m12 l6 leftspace lineheight'>";
echo "<div class='row'><b>Term and Condition:</b><span class='input-field col s9'>";
echo "</span></div>";
echo "<div class='row'><div class='col s2'>Tin No </div> <div class='input-field col s2 left'><input type='text' name='tinno' id='tinno'  class='resetThis' style='width:100%' placeholder='Tin No' readonly value='".$tinno."' required='required'/> </div></div>";	
echo "<div class='row'><div class='col s2'>CST No </div><div class='input-field col s2 left'><input type='text' name='cstno' id='cstno'  class='resetThis' style='width:100%' placeholder='CST No' readonly value='".$cstno."' required='required'/> </div></div>";
echo "<div class='row'><div class='col s2'>Payment </div> <div class='input-field col s2 left'><input type='text' name='payment' id='payment' style='width:100%'  class='resetThis' placeholder='Payment' value='".$payment."' required='required'/> </div> </div>";
echo "<div class='row'><div class='col s2'>Loading & Vat</div><div class='resetThis input-field col s2 left'><input type='text' name='vat_o' id='vat_o'   style='width:100%' placeholder='VAT' value='".$vat_o."' required='required' /></div></div>";
echo "<div class='row'><div class='col s2'>Delivery</div><div class='input-field col s2 left'><input type='text' name='place' id='place'   style='width:100%' placeholder='Place' value='".$place."' class='resetThis' required='required' /></div></div>";
echo "<div class='row'><div class='col s2'>Please Supply by</div> <div class='input-field col s2  date form_date'> <input size='16' type='text' value='".$ddate."' id='ddate' name='ddate' placeholder='Delivery Date'><span class='add-on'><i class='icon-remove'></i></span>
<span class='add-on'><i class='icon-th'></i></span>  </div> <div class='col s1  textaligncenter'>at our</div><div class='col s2'><input type='text' name='place1' id='place1'  class='resetThis' style='width:100%'  value='".$place."' placeholder='Place' required='required'/> </div> <div class='col s1 textaligncenter'>Site</div></div>";
echo "<div class='row'><div class='col s2'>Transportation </div> <div class='input-field col s2 left'><input type='text' name='tpwords' id='tpwords'  class='resetThis' style='width:100%' placeholder='Transportation' value='" .$tpwords. "' /> </div></div>";
echo "<div class='row'><b> <div class='col s2'>Site contact Person </div><div class='input-field col s2 left'><input type='text' name='contactname' id='contactname'  class='resetThis' style='width:100%' placeholder='Site Person'  value='".$contactname."' required='required' /></div><div class='col s1' style='padding-left:40px;'>Mobile :</div><div class='input-field col s2 left'><input type='text' name='mobilno' id='mobilno'  class='resetThis' placeholder='Mobile No'  value='".$mobileno."' required='required' /></div> </b></div>
<div class='row'><div class='input-field col s2'><b>Site address </b></div>";
echo "<div class='input-field col s2 left'><b><input type='text' name='projectname' id='projectname'  class='resetThis' style='width:100%; font-weight:bold' placeholder='Project Name' readonly  value='".$project_name."' required='required' /></b></div></div>";
echo "<div class='row'><div class='input-field col s4 left'><textarea name='address' id='address' value='' class='resetThis' readonly placeholder='address' style='height:80px' required='required'>".$address."</textarea><br />";
echo "<input type='hidden' name='project_id' id='project_id'  value='".$project_id."' class='resetThis' style='width:100%'/></div></div></p></div>";
echo "<div class='col12 s12 m6 l6 left leftspace'>";
echo "<p>Thanking you, <br />
 Yours Sincerely<br /></p>
<img src='images/anisa_digi.jpg' width='83' height='57' />
 <p class='header'>Anisa Fathima.H</p>
 <p>Purchase Manager</p>
</div>
</div>
</div>
</div>";
echo "<div class='row'><div class='col s2 right'>  <button type='submit' class='btn-floating btn-large waves-effect waves-light pink' title='submit'><i class='material-icons medium'>done</i>></button> <a class='btn-floating btn-large waves-effect waves-light pink' title='Cancel' id='clear'><i class='material-icons medium'>clear</i></a></div></div>";
}
else if($type == "upvc")
{
echo "<div class='row topspacelarge' align='center'>";
echo "<div class='col s4 leftspace'>";
echo "<input type='text' placeholder='Newstatus' id='newstatus' name='newstatus' class='resetThis' autocomplete='off'value='".$type."' ><br>&nbsp;";
echo "</div>";
echo "<div class='col s4 leftspace'>";
echo "<input type='text' placeholder='Folder' id='folder' name='folder' class='ui-autocomplete-input resetThis'   autocomplete='off' value='".$folder."' required='required'><br>&nbsp;";
echo "</div></div>";
echo "<div id='invoice'>";
echo "<div class='invoice-header'>";
echo "<div class='row '>";
echo "<div class='col12 s12 m12 l6 right rightspace'><img src='images/SISLOGO.png' /></div></div>";
echo "</div></div>";
echo "<div class='invoice-lable'>";
echo "<div class='row'>";
echo "<div class='col12 s12 m6 l3'>";
echo "<div class='col s12 m6 l6'>&nbsp;</div>";
echo "<div class='col s12 m6 l6'><div class='input-field col s3 right rightspace'><input type='text' name='po_date' id='po_date'  placeholder='Date' value='".$po_date."' required='required' /> <input type='hidden' name='type' id='type'  placeholder='Date' value='".$type."' required='required' /> </div></div></div></div>";
echo "<div class='row'>";
echo "<div class='col s12 m6 l6'><div class='input-field col s6 left leftspace'><input type='text' name='po_no' id='po_no' readonly  class='validate' value='".$po_no."'  /> </div></div>";
echo "<div class='row'>";
echo "<div class='col12 s12 m6 l3 '>";
echo "<div class='col s12 leftspace'><b>To</b></div>";
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
		
	   
 echo "<div class='col s12 m6 l6'><div class='input-field col s6 left leftspace'><input type='text' placeholder='Company Name' id='vendor_name' name='vendor_name' class='ui-autocomplete-input resetThis' autocomplete='off' value='".$row2['ven_compname']. "' required='required' ></div></div>";
 echo "<div class='col s12 m6 l6 right rightspace'><div class='input-field col s4 left leftspace'>&nbsp;</div></div></div></div>";
 echo "<div class='row'>
<div class='col12 s12 m6 l3'>
<div class='col s12 m6 l6'><div class='input-field col s6 left leftspace'><textarea name='ven_add' id='ven_add' placeholder='Address'  style='height:80px' class='resetThis' required='required'>".$row2['ven_add1'] ."</textarea>";
 echo "<input type='hidden' name='ven_id' id='ven_id' placeholder='vendor-id' value='".$row2['ven_id']."' class='resetThis'  /><br /></div></div>
<div class='col s12 m6 l6 right rightspace'><div class='input-field col s8 left leftspace'>&nbsp;</div></div>
</div>
</div>";
 echo "<div class='row'>";
 echo "<div class='col s12'>";
 echo "<center>";
 echo "<div class='input-field col s3 leftspace' style='width:auto; margin-right:8px'> <b>Kind Attention: </b></div>
<div class='input-field col s2'> 
<input type='text' name='ven_contactperson' id='ven_contactperson' placeholder='Contact Person' value='".$row2['ven_contactperson'] ."' class='resetThis' required='required'/></div>
</center>
</div>
</div>";
	$result = mysql_query("SELECT * FROM project_details where project_id=" .$project_id,$conn);
	if($row= mysql_fetch_array($result))
	 {
		 $project_id = $row['project_id'];
	    $project_name = $row['project_name'] ;
	    $place=$row['place'];
		 $city=$row['city'];
		$tinno=$row['tinno'];
	    $cstno=$row['cstno'];
		$address=$row['address'];  
	
			
		}
		else
		{
			echo "Error";
	 }

 echo "<div class='row leftspace'>
<div class='col12 s12 m6 l3'>
<div class='col ref1'>Ref :</div><div class='input-field col ref2'><input type='text' name='refno' id='refno' class='validate resetThis' placeholder='Ref No' value='".$refno."' ></div> <div  class='col dat1'> -Amended Qty Without Glass Dated</div> <div class='input-field col dat2'> <input type='text' name='refdate' id='refdate' placeholder='Ref Date' class='validate resetThis'  value='".$refdate."' > </div>";
 
 echo "<div class='col s12 m6 l6 right rightspace'><div class='input-field col s8 left leftspace'>&nbsp;</div></div></div>
</div>";
 echo "<div class='row  leftspace'>
<div class='col s3 left'><b>Sub:</b>Our PO for window for our project</div> <div class='col s2 left' ><select name='project_name' id='project_name' onChange='projectlist(this.value)' required='required' class='resetThis'>";


$result = mysql_query("SELECT distinct project_name FROM project_details",$conn);

  echo "<option selected value=''>SELECT</option>";
  while ($row = mysql_fetch_array($result)) {
	  	echo "<option".(($project_name == $row['project_name'])? ' selected="selected"' : '').">" .$row['project_name']. "</option>";

	
   }

   
echo "</select></div><div class='col s2 left' style='margin-left:5px'>";
echo "<script>
             $('#project_name').change(function () { //Basically saying when the first select box changes values run the function below.
                 var project_name = $(this).val(); // Grab the value of the selection to send to the select-request.php via ajax
                 $.post('blockname.php', { project_name: project_name }, function (data) { // Run a ajax request and send the var make as a post variable named make and return the info in the data var.
                     $('#blockname').html(data); // Have jquery change the html within the second select box with the data we got back from the ajax request.
                 });
             });
</script>";

echo "<select name='blockname' id='blockname' required='required' class='resetThis'>";

echo "<option selected value=''>SELECT</option>";
$result = mysql_query("SELECT distinct blockname FROM project_details where project_name='".$project_name_m."'",$conn);

  echo "<option selected value='select'>SELECT</option>";
  while ($row = mysql_fetch_array($result)) {
	  	echo "<option".(($block == $row['blockname'])? ' selected="selected"' : '').">" .$row['blockname']. "</option>";

	
   }
echo "</select><br></div>";
echo "<div class='col s1 left' style='width:auto; margin:0 10px 0 10px'> at </div>";
echo "<div class='col s2 left'>";
echo "<input type='text' name='place2' id='place2' class='resetThis' style='width:auto' placeholder='Place' readonly value='".$place."' required='required'/> </div>";
echo "<div class='col s2 left' >";
echo "<input type='text' name='city' id='city' class='resetThis' style='width:auto' placeholder='city' readonly value='".$row['city']."'/> </div>
<div class='col s1 left' style='width:auto;' required='required'> . </div></div>";
echo "</div>";
echo "<div class='invoice-table'>";
echo "<div class='row'>";
echo "<div class='col s12 m12 l12 table-responsive '>";
echo "<table class='table table-full' id='itemsTable'>";
echo "<thead>";
echo "<tr>";
echo "<th><input type='checkbox' id='check_all'  /><label for='check_all'></label></th>";
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
$result_r = mysql_query("SELECT * FROM  purchaseorder_tbl where po_year='".$po_year."' and Po_no=" .$po_no,$conn);
$n=0;
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
		$units = $row['units'];
	 $po_id=$row['po_id'] ;
    	$n+=1;
echo "<tr><td><input type='checkbox' id='selecttd".$n."'  class='case' value='".$po_id."' /><label for='selecttd".$n."'></label></td>";
echo "<td><div class='input-field col s8 '> <input  data-type='product_code'  id='itemCode_".$n."' '  name='itemCode[]' class='autocomplete_txt resetThis'   type='text' required autocomplete='off' placeholder='Code'  value='". $product_code ."' required='required' /><input  id='itemBrand_".$n."'   name='itemBrand[]' class='resetThis' type='hidden' autocomplete='off' required='required' /> <input type='hidden' name='po_id[]' id='po_id_".$n."' value='" .$po_id."' placeholder='POID'/>

</div></td>";
echo "<td><div class='input-field col s8 '> <input name='itemWidth[]' id='itemWidth_".$n."'   type='text'  autocomplete='off' placeholder='Width'  value='".$pro_width."' class='resetThis' required='required'/></div></td>";
echo "<td><div class='input-field col s8'><input name='itemHeight[]' id='itemHeight_".$n."'  type='text' autocomplete='off'  placeholder='Height'  value='".$pro_height."' class='resetThis' required='required' /></div></td>";
echo "<td><div class='input-field col s8'><input name='itemQty[]'  id='itemQty_".$n."'  type='text' class='changesNo resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' placeholder='QTY'  value='".$product_qty."' required='required' /> <input name='itemUnit[]'  id='itemUnit_".$n."' type='hidden' required autocomplete='off'  value='".$units."' class='resetThis' /></div></td>";
echo "<td><div class='input-field col s8 '><input data-type='pro_name' name='itemDesc[]'  id='itemDesc_".$n."'  type='text' class='autocomplete_txt'  required tocomplete='off' placeholder='Type' value=".chr(34).htmlspecialchars($productname).chr(34)." class='resetThis' required='required' /></div></td>";
echo "<td><div class='input-field col s8 '><input name='itemBasic_m[]'  id='itemBasicm_".$n."'  type='text' autocomplete='off' class='changesNo resetThis' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' placeholder='M Basic'  value='". $m_basic ."' required='required'/></div></td>
<td><div class='input-field col s8 '><input name='itemTotal_m[]' id='itemTotalm_".$n."'  readonly='readonly' type='text' autocomplete='off' class='totalLinePrice1 resetThis' placeholder='B Total'  value='".$m_total."' required='required'/></div></td>";
echo "<td><div class='input-field col s8'><input name='itemBasic_i[]'  id='itemBasici_".$n."'  type='text' autocomplete='off' class='changesNo resetThis' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' placeholder='I Basic'  value='".$i_basic."' required='required'/></div></td>
<td><div class='input-field col s8'><input name='itemTotal_i[]' id='itemTotali_".$n."'  readonly='readonly' type='text' autocomplete='off' class='totalLinePrice2 resetThis' Placeholder='I Total'  value='".$i_total."' required='required'/></div></td>";
echo "<td><div class='input-field col s8'><input name='itemLineTotal[]'  id='itemLineTotal_".$n."'  readonly='readonly' type='text' placeholder='Total' autocomplete='off' class='totalLinePrice resetThis' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;'  value='".$Amount."' required='required' /></div></td>";
echo "</tr>";
 }
echo "</tbody>";
echo "</table>";
echo "<table class='table table-full'>";
echo "<tr>";
echo "<td></td>";
echo "<td>";
echo "<button class='btn btn-success addmore' type='button'>+ Add</button></td>";
echo "<td><button class='btn btn-danger delete' type='button'>- Delete</button></td>";
echo "<td></td>";
echo "<td>BC</td>";
echo "<td><div class='input-field col s9'><input name='basictotal'  class='basictotal resetThis' id='basictotal' type='text' required  Placeholder='M Total' value='".$basictotal."' required='required'/></div><input type='hidden'  class='itotal resetThis' id='itotal' name='itotal' value='".$i_total."' required /><input type='hidden' class='resetThis' name='subTotal' id='subTotal' placeholder='Subtotal' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;'  value='".$stotal."' required='required' /></td>";
echo "<td></td>
<td></td>
<th></td>
<td></td>
<td></td>
</tr>
<tr>
<td></td>
<td></td>
<td></td>
<td></td>";
echo "<td>ED</td>
<td><div class='input-field col s9'><input type='text' class='resetThis'  name='ed' id='ed' placeholder='ED' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;'  value='".$ed."' required='required'/></div></td>";
echo "<td></td>
<td></td>
<th></td>
<td></td>
<td></td>
</tr>
<tr>
<td></td>
<td></td>
<td></td>
<td></td>";
echo "<td>Vat</td>";
echo "<td><div class='input-field col s9'><input type='text' class='resetThis' id='vat' name='vat' placeholder='vat' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;'  value='".$tax."' required='required'/><input type='hidden' class='resetThis' id='taxAmount1' placeholder='Tax' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;'  /><input type='hidden' class='resetThis' id='taxAmount' placeholder='Tax' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;'></div></td>";
echo "<td></td>
<td></td>
<th></td>
<td></td>
<td></td>
</tr>
<tr>
<td></td>
<td></td>
<td></td>";
echo "<td></td>";
echo "<td>Transpotation</td>";
echo "<td><div class='input-field col s9'><input type='text' class='resetThis' id='tp' name='tp' placeholder='Amount Paid' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;'  value='".$transportation."' required='required'><input type='hidden' class='resetThis' id='tp1' name='tp1' placeholder='Amount Paid' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;'  value='".$transportation."' /></div></td>";
echo "<td></td>
<th></td>
<td></td>
<td></td>
</tr>
<tr>
<td></td>
<td></td>
<td></td>";
echo "<td><input type='hidden' class='amountDue resetThis' id='amountDue' placeholder='Amount Due' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' /></td>";
echo "<td>ST</td>";
echo "<td><div class='input-field col s9'><input type='text' class='resetThis' name='st' id='st' placeholder='st' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;'  value='".$st."' required='required' /> <input type='hidden' class='resetThis' id='taxAmount2' placeholder='Tax2' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;'></div></td>";
echo "<td>&nbsp;</td>
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
<td>Grand Total</td>";
echo "<td><div class='input-field col s9'><input name='gt'  class='amountDue1 gt resetThis' id='gt' type='text' required='required' readonly placeholder='M Total with tax'  value='".$gt."' /></div></td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>";
echo "<td class='cyan white-text'><div class='input-field col s9'><input name='gt1'  class='gt1 resetThis' id='gt1' type='text' required='required' readonly placeholder='Installation Total'  value='".$gt1."' /></div></td>";
echo "<td class='cyan strong white-text'><div class='input-field col s9'><input type='text' class='resetThis' id='totalAftertax' name='totalAftertax' placeholder='Total' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' readonly  value='".$subtotal."' required='required' /></div></td>
</tr>";
echo "</table>
</div>
</div>
</div>";
echo "<div class='invoice-footer'>";
echo "<div class='row'>";
echo "<div class='col12 s12 m12 l6 leftspace lineheight'>";
echo "<div class='row'><b>Term and Condition:</b><span class='input-field col s9'>";
echo "</span></div>";
echo "<div class='row'><div class='col s2'>Tin No </div> <div class='input-field col s2 left'><input type='text' name='tinno' id='tinno'  class='resetThis' style='width:100%' placeholder='Tin No' readonly value='".$tinno."' required='required'/> </div></div>";	
echo "<div class='row'><div class='col s2'>CST No </div><div class='input-field col s2 left'><input type='text' name='cstno' id='cstno'  class='resetThis' style='width:100%' placeholder='CST No' readonly value='".$cstno."' required='required'/> </div></div>";
echo "<div class='row'><div class='col s2'>Delivery</div><div class='input-field col s2 left'><input type='text' name='place' id='place'   style='width:100%' placeholder='Place' value='".$place."' class='resetThis' required='required' /></div></div>";
echo "<div class='row'><div class='col s2'>Please Supply by</div><div class='input-field col s2  date form_date'> <input size='16' type='text' value='".$ddate."' id='ddate' name='ddate' placeholder='Delivery Date'><span class='add-on'><i class='icon-remove'></i></span>
<span class='add-on'><i class='icon-th'></i></span>  </div> <div class='col s1  textaligncenter'>at our</div><div class='col s2'><input type='text' name='place1' id='place1'  class='resetThis' style='width:100%'  value='".$place."' placeholder='place' required='required'/> </div> <div class='col s1 textaligncenter'>Site</div></div>";
echo "<div class='row'><div class='col s2'>Transportation </div> <div class='input-field col s2 left'><input type='text' name='tpwords' id='tpwords'  class='resetThis' style='width:100%' placeholder='Transportation' value='" .$tpwords. "' /> </div></div>";
echo "<div class='row'><b> <div class='col s2'>Site contact Person </div><div class='input-field col s2 left'><input type='text' name='contactname' id='contactname'  class='resetThis' style='width:100%' placeholder='Site Person'  value='".$contactname."' required='required' /></div><div class='col s1' style='padding-left:40px;'>Mobile :</div><div class='input-field col s2 left'><input type='text' name='mobilno' id='mobilno'  class='resetThis' placeholder='Mobile No'  value='".$mobileno."' required='required'/></div> </b></div>
<div class='row'><div class='input-field col s2'><b>Site address </b></div>";
echo "<div class='input-field col s2 left'><b><input type='text' name='projectname' id='projectname'  class='resetThis' style='width:100%; font-weight:bold' placeholder='Project Name' readonly  value='".$project_name."' required='required' /></b></div></div>";
echo "<div class='row'><div class='input-field col s4 left'><textarea name='address' id='address' value='' class='resetThis' readonly placeholder='address' style='height:80px' required='required'>".$address."</textarea><br />";
echo "<input type='hidden' name='project_id' id='project_id'  value='".$project_id."' class='resetThis' style='width:100%'/></div></div></p></div>";
echo "<div class='col12 s12 m6 l6 left leftspace'>";
echo "<p>Thanking you, <br />
 Yours Sincerely<br /></p>
<img src='images/anisa_digi.jpg' width='83' height='57' />
 <p class='header'>Anisa Fathima.H</p>
 <p>Purchase Manager</p>
</div>
</div>
</div>
</div>";
echo "<div class='row'><div class='col s2 right'>  <button type='submit' class='btn-floating btn-large waves-effect waves-light cyan' title='submit'><i class='material-icons medium'>done</i>></button> <a class='btn-floating btn-large waves-effect waves-light' title='Cancel' id='clear'><i class='material-icons medium'>clear</i></a></div></div>";
}
else if($type == "steel")
{
	 echo "<div class='row topspacelarge' align='center'>";
  echo "<div class='col s4 leftspace'>";
 echo "<input type='text' placeholder='Newstatus' id='newstatus' name='newstatus' class='resetThis' autocomplete='off'value='".$type."' ><br>&nbsp;";
echo "</div>";
echo "<div class='col s4 leftspace'>";
echo "<input type='text' placeholder='Folder' id='folder' name='folder' class='ui-autocomplete-input resetThis'   autocomplete='off' value='".$folder."' required='required'><br>&nbsp;";
echo "</div></div>";
echo "<div id='invoice'>";
 echo "<div class='invoice-header'>";
 echo "<div class='row '>";
 echo "<div class='col12 s12 m12 l6 right rightspace'><img src='images/SISLOGO.png' /></div></div>";
 echo "</div></div>";
 echo "<div class='invoice-lable'>";
 echo "<div class='row'>";
 echo "<div class='col12 s12 m6 l3'>";
 echo "<div class='col s12 m6 l6'>&nbsp;</div>";
 echo "<div class='col s12 m6 l6'><div class='input-field col s3 right rightspace'><input type='text' name='po_date' id='po_date'  placeholder='Date' value='".$po_date."' /> <input type='hidden' name='type' id='type'  placeholder='Date' value='".$type."' required='required' /> </div></div></div></div>";
 echo "<div class='row'>";
  echo "<div class='col s12 m6 l6'><div class='input-field col s6 left leftspace'><input type='text' name='po_no' id='po_no' readonly  class='validate' value='".$po_no."' /> </div></div>";

 echo "<div class='row'>";
 echo "<div class='col12 s12 m6 l3 '>";
 echo "<div class='col s12 leftspace'><b>To</b></div>"; 
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
		
	   
 echo "<div class='col s12 m6 l6'><div class='input-field col s6 left leftspace'><input type='text' placeholder='Company Name' id='vendor_name' name='vendor_name' class='ui-autocomplete-input resetThis' autocomplete='off' value='".$row2['ven_compname']. "' required='required' ></div></div>";
 echo "<div class='col s12 m6 l6 right rightspace'><div class='input-field col s4 left leftspace'>&nbsp;</div></div></div></div>";
 echo "<div class='row'>
<div class='col12 s12 m6 l3'>
<div class='col s12 m6 l6'><div class='input-field col s6 left leftspace'><textarea name='ven_add' id='ven_add' value='' placeholder='Address'  style='height:80px' class='resetThis' required='required'>".$row2['ven_add1'] ."</textarea>";
 echo "<input type='hidden' name='ven_id' id='ven_id' placeholder='vendor-id' value='".$row2['ven_id']."' class='resetThis' /><br /></div></div>
<div class='col s12 m6 l6 right rightspace'><div class='input-field col s8 left leftspace'>&nbsp;</div></div>
</div>
</div>";
 echo "<div class='row'>";
 echo "<div class='col s12'>";
 echo "<center>";
 echo "<div class='input-field col s3 leftspace' style='width:auto; margin-right:8px'> <b>Kind Attention: </b></div>
<div class='input-field col s2'> 
<input type='text' name='ven_contactperson' id='ven_contactperson' placeholder='Contact Person' value='".$row2['ven_contactperson'] ."' class='resetThis' required='required' /></div>
</center>
</div>
</div>";
	$result = mysql_query("SELECT * FROM project_details where project_id=" .$project_id,$conn);
	if($row= mysql_fetch_array($result))
	 {
		 $project_id = $row['project_id'];
	    $project_name = $row['project_name'] ;
	    $place=$row['place'];
		 $city=$row['city'];
		$tinno=$row['tinno'];
	    $cstno=$row['cstno'];
		$address=$row['address'];  
	
			
		}
		else
		{
			echo "Error";
	 }

echo "<div class='row leftspace'>
<div class='col12 s12 m6 l3 cyan'>
<div class='col ref1'>Ref : </div><div class='input-field col ref2'><input type='text' name='refno' id='refno' class='validate resetThis' placeholder='Ref No' value='".$refno."'></div> <div  class='col dat1'> Dated on </div> <div class='input-field col dat2'> <input type='text' name='refdate' id='refdate' placeholder='Ref Date' class='validate resetThis' class='resetThis' value='".$refdate."' /> </div>";
echo "<div class='col s12 m6 l6 right rightspace'><div class='input-field col s8 left leftspace'>&nbsp;</div></div>
</div>
</div>";
 echo "<div class='col s12 m6 l6 right rightspace'><div class='input-field col s8 left leftspace'>&nbsp;</div></div></div>
</div>";
echo "<div class='row  leftspace'><div class='col s2'  style='width:auto; margin:0 8px 0 0px'> Sub : </div><div class='input-field col s3 left'> <input type='text' name='subject' id='subject' class='validate resetThis' placeholder='Subject' required='required' value='".$subject."' ></div> <div class='col s2'  style='width:auto; margin:0 8px 0 0px'>.</div> </div>";

 echo "<div class='row  leftspace'>
<div class='col s8 left' style='width:auto; margin-right:10px'>
We are pleased to place the Purchase order as per the details mentioned below for our </div> <div class='col s2 left' ><select name='project_name' id='project_name' onChange='projectlist(this.value)' required='required' class='resetThis'>";


$result = mysql_query("SELECT distinct project_name FROM project_details",$conn);

  echo "<option selected value=''>SELECT</option>";
  while ($row = mysql_fetch_array($result)) {
	  	echo "<option".(($project_name == $row['project_name'])? ' selected="selected"' : '').">" .$row['project_name']. "</option>";

	
   }

   
echo "</select></div><div class='col s2 left' style='margin-left:5px'>";
echo "<script>
             $('#project_name').change(function () { //Basically saying when the first select box changes values run the function below.
                 var project_name = $(this).val(); // Grab the value of the selection to send to the select-request.php via ajax
                 $.post('blockname.php', { project_name: project_name }, function (data) { // Run a ajax request and send the var make as a post variable named make and return the info in the data var.
                     $('#blockname').html(data); // Have jquery change the html within the second select box with the data we got back from the ajax request.
                 });
             });
</script>";

echo "<select name='blockname' id='blockname' required='required' class='resetThis'>";

echo "<option selected value=''>SELECT</option>";
$result = mysql_query("SELECT distinct blockname FROM project_details where project_name='".$project_name_m."'",$conn);

  while ($row = mysql_fetch_array($result)) {
	  	echo "<option".(($block == $row['blockname'])? ' selected="selected"' : '').">" .$row['blockname']. "</option>";

	
   }
echo "</select><br></div>";
echo "<div class='col s6 left'>";
echo " the address and the contact person are mentioned below. <input type='hidden' name='place2' id='place2'   style='width:auto' placeholder='Place' readonly class='resetThis'/> </div>";
echo "</div>";
echo "<div class='invoice-table'>";
echo "<div class='row '>";
echo "<div class='col s12 m12 l12 table-responsive '>";
echo "<table class='table table-full' id='itemsTable'>";
echo "<thead>";
echo "<tr>";
echo "<th ><input type='checkbox' id='check_all'  /><label for='check_all'></label></th>";
echo "<th>Description</th>";
echo "<th>Details</th>";
echo "<th>Tonnage</th>";
echo "<th>price</th>";
echo "<th>vat</th>";
echo "<th>Make</th>";
echo "<th>Total</th>";
echo "</tr>";
echo "</thead>";
echo "<tbody>";
$result_r = mysql_query("SELECT * FROM  purchaseorder_tbl where po_year='".$po_year."' and Po_no=" .$po_no,$conn);

while($row = mysql_fetch_array($result_r))
 {
	 $po_id=$row['po_id'] ;
	$productname = $row['product_name'] ;
	$product_qty=$row['product_qty'];
	$ratesperproduct = $row['ratesperproduct'];
	$vat = $row['vat'];
	$Amount = $row['Amount'];
	$cat_name = $row['cat_name'];
	$product_code = $row['product_code'];	
	$sub_catname = $row['sub_catname'];
	$brand = $row['brand'];
	$size = $row['size'];
	$units = $row['units'];
	$details = $row['details'];
	$po_date = $row['po_date'];
	$product_code = $row['product_code'];
	$n=$n+1;
echo "<tr>";

echo "<td><input type='checkbox' id='selecttd1".$n."'  class='case' value='".$po_id."' /><label for='selecttd1".$n."'></label></td>";
echo "<td><div class='input-field col s8'><input type='text' data-type='pro_name' name='itemDesc[]' id='itemDesc_".$n."'  class='resetThis autocomplete_txtsteel' autocomplete='off' value=".chr(34).htmlspecialchars($productname).chr(34)." placeholder='DESC' required='required' /></div></td>";
echo "<td><div class='input-field col s8'><input name='details[]'   id='details_".$n."'  type='text'  value='".$details."' placeholder='Details' required='required' /> <input type='hidden' name='po_id[]' id='po_id_".$n."'  value='" .$po_id."' placeholder='POID' />
</div></td>";

echo "<td><div class='input-field col s8'>
<input type='text' name='itemQty[]' id='itemQty_".$n."'  class='changesNo_st resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' value='" .$product_qty."' placeholder='QTY' required='required'/>  <input name='cat_name[]' class='resetThis' id='cat_name_".$n."'  type='hidden' value='".$cat_name."'/>
 <input name='sub_catname[]' class='resetThis' id='sub_catname_".$n."'  type='hidden' value='".$sub_catname."'/>
 
<input name='itemCode[]'  class='resetThis' id='itemCode_".$n."'  type='hidden' data-type='product_code'  value='".$product_code."'  /><input name='units[]'  class='units resetThis' id='units_".$n."' tabindex='1' type='hidden' required placeholder='units' value='".$units."'/></div></td>";
echo "<td><div class='input-field col s8'><input type='text' name='itemPrice[]' id='itemPrice_".$n."'  class='changesNo_st resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' value='".$ratesperproduct."' placeholder='Price' required='required'></div></td>";
echo "<td><div class='input-field col s8'><input type='text' name='itemVat[]' id='itemVat_".$n."'  class='resetThis changesNo_st' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' value='".$vat."' placeholder='VAT' required='required' ></div></td>";
echo "<td><div class='input-field col s8'><input name='brand[]'   id='brand_".$n."'  tabindex='1' type='text' value='".$brand."' placeholder='Brand' required='required'/></div></td>";
echo "<td><div class='input-field col s8'><input type='text' name='itemLineTotal[]' id='itemLineTotal_".$n."'  class='resetThis totalLinePricest' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' value='".$Amount. "' placeholder='Total' required='required' ></div></td>";

echo "</tr>";
 }
echo "</tbody>";
echo "</table>";
echo "<table class='table table-full'><tr>";
echo "<td class='white'><button class='btn btn-success addmore_st' type='button'>+ Add</button> <button class='btn btn-danger delete_st' type='button'>- Delete</button> &nbsp; ";
if($inclusive != '0')
{
	echo "<input type='checkbox' name='inclusive' id='inclusive'   value='0' /><label for='inclusive'>Inclusive of All Tax(vat must be zero)</label>";
}
	else
	{
		echo "<input type='checkbox' name='inclusive' id='inclusive'  value='0' checked='checked' /><label for='inclusive'>Inclusive of All Tax(vat must be zero)</label>";
	}
	
	echo "</td>";
echo "<td  class='white'></td>";
echo "<td class='white'></td>";
echo "<td class='white'></td>";
echo "<td class='cyan white-text'>Grand Total</td>";
echo "<td class='cyan strong white-text'><div class='input-field col s8 '><input type='text' readonly class='resetThis' id='subTotal' name='subTotal' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' placeholder='Total' value='".$subtotal."' required='required'/></div></td>";
echo "</tr>";
echo "</table></div>";
echo "</div>";
echo "<div class='invoice-footer'>";
echo "<div class='row'>";
echo "<div class='col12 s12 m12 l6 leftspace lineheight'>";
echo "<div class='row'><b>Term and Condition:</b><span class='input-field col s9'>";
echo "</span></div>";
echo "<div class='row'><div class='col s2'>Tin No </div> <div class='input-field col s2 left'><input type='text' name='tinno' id='tinno'  class='resetThis' style='width:100%' placeholder='Tin No' readonly value='".$tinno."' required='required'/> </div></div>";	
echo "<div class='row'><div class='col s2'>CST No </div><div class='input-field col s2 left'><input type='text' name='cstno' id='cstno'  class='resetThis' style='width:100%' placeholder='CST No' readonly value='".$cstno."' required='required'/> </div></div>";
echo "<div class='row'><div class='col s2'>Payment </div> <div class='input-field col s2 left'><input type='text' name='payment' id='payment' style='width:100%' class='resetThis' placeholder='Payment' value='".$payment."' required='required' /> </div> </div>";
echo "<div class='row'><div class='col s2'>Loading & Vat</div><div class='resetThis input-field col s2 left'><input type='text' name='vat_o' id='vat_o'   style='width:100%' placeholder='VAT' value='".$vat_o."' required='required' /></div></div>";
echo "<div class='row'><div class='col s2'>Delivery</div><div class='input-field col s2 left'><input type='text' name='place' id='place'   style='width:100%' placeholder='Place' value='".$place."' class='resetThis' required='required'/></div></div>";
echo "<div class='row'><div class='col s2'>Please Supply by</div> <div class='input-field col s2  date form_date'> <input size='16' type='text' value='".$ddate."' id='ddate' name='ddate' placeholder='Delivery Date'><span class='add-on'><i class='icon-remove'></i></span>
<span class='add-on'><i class='icon-th'></i></span>  </div> <div class='col s1  textaligncenter'>at our</div><div class='col s2'><input type='text' name='place1' id='place1'  class='resetThis' style='width:100%'  value='".$place."' required='required'/> </div> <div class='col s1 textaligncenter'>Site</div></div>";
echo "<div class='row'><div class='col s2'>Transportation </div> <div class='input-field col s2 left'><input type='text' name='tpwords' id='tpwords'  class='resetThis' style='width:100%' placeholder='Transportation' value='" .$tpwords. "' /> </div></div>";
echo "<div class='row'><b> <div class='col s2'>Site contact Person </div><div class='input-field col s2 left'><input type='text' name='contactname' id='contactname'  class='resetThis' style='width:100%' placeholder='Site Person'  value='".$contactname."' required='required'/></div><div class='col s1' style='padding-left:40px;'>Mobile :</div><div class='input-field col s2 left'><input type='text' name='mobilno' id='mobilno'  class='resetThis' placeholder='Mobile No'  value='".$mobileno."' required='required'/></div> </b></div>
<div class='row'><div class='input-field col s2'><b>Site address </b></div>";
echo "<div class='input-field col s2 left'><b><input type='text' name='projectname' id='projectname'  class='resetThis' style='width:100%; font-weight:bold' placeholder='Project Name' readonly  value='".$project_name."' required='required' /></b></div></div>";
echo "<div class='row'><div class='input-field col s4 left'><textarea name='address' id='address' value='' class='resetThis' readonly placeholder='address' style='height:80px;width:100%' required='required'>".$address."</textarea><br />";
echo "<input type='hidden' name='project_id' id='project_id'  value='".$project_id."' class='resetThis' style='width:100%'/></div></div></p></div>";
echo "<div class='col12 s12 m6 l6 left leftspace'>";
echo "<p>Thanking you, <br />
 Yours Sincerely<br /></p>
<img src='images/anisa_digi.jpg' width='83' height='57' />
 <p class='header'>Anisa Fathima.H</p>
 <p>Purchase Manager</p>
</div>
</div>
</div>
</div>";
echo "<div class='row'><div class='col s2 right'><button type='submit' class='btn-floating btn-large waves-effect waves-light cyan' title='submit'><i class='material-icons medium'>done</i>></button> <a class='btn-floating btn-large waves-effect waves-light' title='Cancel' id='clear'><i class='material-icons medium'>clear</i></a></div></div>";
}
else if($type == "door")
{
echo "<div class='row topspacelarge' align='center'>";
echo "<div class='col s4 leftspace'>";
echo "<input type='text' placeholder='Newstatus' id='newstatus' name='newstatus' class='resetThis' autocomplete='off'value='".$type."' required='required' ><br>&nbsp;";
echo "</div>";
echo "<div class='col s4 leftspace'>";
echo "<input type='text' placeholder='Folder' id='folder' name='folder' class='ui-autocomplete-input resetThis'   autocomplete='off' value='".$folder."' required='required' /><br>&nbsp;";
echo "</div></div>";
echo "<div id='invoice'>";
echo "<div class='invoice-header'>";
echo "<div class='row '>";
echo "<div class='col12 s12 m12 l6 right rightspace'><img src='images/SISLOGO.png' /></div></div>";
echo "</div></div>";
echo "<div class='invoice-lable'>";
echo "<div class='row'>";
echo "<div class='col12 s12 m6 l3'>";
echo "<div class='col s12 m6 l6'>&nbsp;</div>";
echo "<div class='col s12 m6 l6'><div class='input-field col s3 right rightspace'><input type='text' name='po_date' id='po_date'  placeholder='Date' value='".$po_date."' required='required' /> <input type='hidden' name='type' id='type'  placeholder='Date' value='".$type."' /> </div></div></div></div>";
 echo "<div class='row'>";
  echo "<div class='col s12 m6 l6'><div class='input-field col s6 left leftspace'><input type='text' name='po_no' id='po_no' readonly  class='validate' value='".$po_no."' / > </div></div>";

 echo "<div class='row'>";
 echo "<div class='col12 s12 m6 l3 '>";
 echo "<div class='col s12 leftspace'><b>To</b></div>"; $result2 = mysql_query("SELECT * FROM  vendor_tbl where ven_compname='".mysql_real_escape_string($ven_id)."'",$conn);
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
		
	   
 echo "<div class='col s12 m6 l6'><div class='input-field col s6 left leftspace'><input type='text' placeholder='Company Name' id='vendor_name' name='vendor_name' class='ui-autocomplete-input resetThis' autocomplete='off' value='".$row2['ven_compname']. "' required='required' ></div></div>";
 echo "<div class='col s12 m6 l6 right rightspace'><div class='input-field col s4 left leftspace'>&nbsp;</div></div></div></div>";
 echo "<div class='row'>
<div class='col12 s12 m6 l3'>
<div class='col s12 m6 l6'><div class='input-field col s6 left leftspace'><textarea name='ven_add' id='ven_add' value='' placeholder='Address'  style='height:80px' class='resetThis' required='required' >".$row2['ven_add1'] ."</textarea>";
 echo "<input type='hidden' name='ven_id' id='ven_id' placeholder='vendor-id' value='".$row2['ven_id']."' class='resetThis' /><br /></div></div>
<div class='col s12 m6 l6 right rightspace'><div class='input-field col s8 left leftspace'>&nbsp;</div></div>
</div>
</div>";
 echo "<div class='row'>";
 echo "<div class='col s12'>";
 echo "<center>";
 echo "<div class='input-field col s3 leftspace' style='width:auto; margin-right:8px'> <b>Kind Attention: </b></div>
<div class='input-field col s2'> 
<input type='text' name='ven_contactperson' id='ven_contactperson' placeholder='Contact Person' value='".$row2['ven_contactperson'] ."' class='resetThis' required='required' /></div>
</center>
</div>
</div>";
	$result = mysql_query("SELECT * FROM project_details where project_id=" .$project_id,$conn);
	if($row= mysql_fetch_array($result))
	 {
		 $project_id = $row['project_id'];
	    $project_name = $row['project_name'] ;
	    $place=$row['place'];
		 $city=$row['city'];
		$tinno=$row['tinno'];
	    $cstno=$row['cstno'];
		$address=$row['address'];  
	
			
		}
		else
		{
			echo "Error";
	 }

echo "<div class='row leftspace'>
<div class='col12 s12 m6 l3 cyan'>
<div class='col ref1'>Ref : </div><div class='input-field col ref2'><input type='text' name='refno' id='refno' class='validate resetThis' placeholder='Ref No' value='".$refno."' ></div> <div  class='col dat1'> Dated on </div> <div class='input-field col dat2'> <input type='text' name='refdate' id='refdate' placeholder='Ref Date' class='validate resetThis' class='resetThis' value='".$refdate."'/> </div>";
echo "<div class='col s12 m6 l6 right rightspace'><div class='input-field col s8 left leftspace'>&nbsp;</div></div>
</div>
</div>";
 echo "<div class='col s12 m6 l6 right rightspace'><div class='input-field col s8 left leftspace'>&nbsp;</div></div></div>
</div>";
echo "<div class='row  leftspace'><div class='col s2'  style='width:auto; margin:0 8px 0 0px'> Sub : </div><div class='input-field col s3 left'> <input type='text' name='subject' id='subject' class='validate resetThis' placeholder='Subject' required='required' value='".$subject."' ></div> <div class='col s2'  style='width:auto; margin:0 8px 0 0px'>.</div> </div>";

 echo "<div class='row  leftspace'>
<div class='col s8 left' style='width:auto; margin-right:10px'>
We are pleased to place the Purchase order as per the details mentioned below for our </div> <div class='col s2 left' ><select name='project_name' id='project_name' onChange='projectlist(this.value)' required='required' class='resetThis'>";


$result = mysql_query("SELECT distinct project_name FROM project_details",$conn);

  echo "<option selected value=''>SELECT</option>";
  while ($row = mysql_fetch_array($result)) {
	  	echo "<option".(($project_name == $row['project_name'])? ' selected="selected"' : '').">" .$row['project_name']. "</option>";

	
   }

   
echo "</select></div><div class='col s2 left' style='margin-left:5px'>";
echo "<script>
             $('#project_name').change(function () { //Basically saying when the first select box changes values run the function below.
                 var project_name = $(this).val(); // Grab the value of the selection to send to the select-request.php via ajax
                 $.post('blockname.php', { project_name: project_name }, function (data) { // Run a ajax request and send the var make as a post variable named make and return the info in the data var.
                     $('#blockname').html(data); // Have jquery change the html within the second select box with the data we got back from the ajax request.
                 });
             });
</script>";

echo "<select name='blockname' id='blockname' required='required' class='resetThis'>";

echo "<option selected value=''>SELECT</option>";
$result = mysql_query("SELECT distinct blockname FROM project_details where project_name='".$project_name_m."'",$conn);

  while ($row = mysql_fetch_array($result)) {
	  	echo "<option".(($block == $row['blockname'])? ' selected="selected"' : '').">" .$row['blockname']. "</option>";

	
   }
echo "</select><br></div>";
echo "<div class='col s6 left'>";
echo " the address and the contact person are mentioned below. <input type='hidden' name='place2' id='place2'   style='width:auto' placeholder='Place' readonly class='resetThis'/> </div>";
echo "</div>";
echo "<div class='invoice-table'>";
echo "<div class='row '>";
echo "<div class='col s12 m12 l12 table-responsive '>";
echo "<table class='table table-full' id='itemsTable'>";
echo "<thead>";
echo "<tr>";
echo "<th ><input type='checkbox' id='check_all' /><label for='check_all'></label></th>";
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
$result_r = mysql_query("SELECT * FROM  purchaseorder_tbl where po_year='".$po_year."' and Po_no=" .$po_no,$conn);

while($row = mysql_fetch_array($result_r))
 {
	 $po_id=$row['po_id'] ;
	$productname = $row['product_name'] ;
	$product_qty=$row['product_qty'];
	$ratesperproduct = $row['ratesperproduct'];
	$vat = $row['vat'];
	$Amount = $row['Amount'];
	$cat_name = $row['cat_name'];
	$product_code = $row['product_code'];	
	$sub_catname = $row['sub_catname'];
	$brand = $row['brand'];
	$size = $row['size'];
	$units = $row['units'];
	$po_date = $row['po_date'];
	$product_code = $row['product_code'];
	$product_cft = $row['product_cft'];	
	$n=$n+1;
echo "<tr>";

echo "<td><input type='checkbox' id='selecttd1".$n."' class='case' value='".$po_id."' /><label for='selecttd1".$n."'></label></td>";

echo "<td><div class='input-field col s8'><input  data-type='product_code'  id='itemCode_".$n."' '  name='itemCode[]' class='autocomplete_txtdoor resetThis' type='text' required autocomplete='off' placeholder='Code' value=" .chr(34).htmlspecialchars($product_code).chr(34). " placeholder='Code' required='required'/></div></td>";

echo "<td><div class='input-field col s8'><input type='text' data-type='pro_name' name='itemDesc[]' id='itemDesc_".$n."'  class='resetThis autocomplete_txtdoor' autocomplete='off'  value=".chr(34).htmlspecialchars($productname).chr(34)." placeholder='DESC' required='required'></div></td>";
echo "<td><div class='input-field col s8'><input name='size[]'   id='size_".$n."'  type='text' class='resetThis changesNo_dr' value=".chr(34).htmlspecialchars($size).chr(34)." placeholder='Size' required='required'/> <input type='hidden' name='po_id[]' id='po_id_".$n."' value='".$po_id."' placeholder='POID' />
</div></td>";
echo "<td><div class='input-field col s8'>
<input type='text' name='itemQtyd[]' id='itemQtyd_".$n."'  class='resetThis changesNo_dr' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' value='" .$product_qty."' placeholder='QTY' required='required'> 
<input type='hidden' name='itemQtyds[]' id='itemQtyds_".$n."'  class='resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;'> <input name='cat_name[]' class='resetThis' id='cat_name_".$n."' type='hidden' value='".$cat_name."'/> <input name='brand[]'   id='brand_".$n."'  tabindex='1' type='hidden' value='".$brand."'/>
 <input name='sub_catname[]' class='resetThis' id='sub_catname_".$n."'  type='hidden' value='".$sub_catname."'/>
 <input name='brand[]' class='resetThis' id='brand_".$n."' ' tabindex='1' type='hidden'  /><input name='units[]'  class='units resetThis' id='units_".$n."'  tabindex='1' type='hidden' required placeholder='units' value='".$units."' /></div></td>";
echo "<td><div class='input-field col s8'><input type='text' name='itemQty[]' id='itemQty_".$n."'  class='changesNo_dr resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' value='".$product_cft. "' placeholder='CFT' required='required'/></div></td>";

echo "<td><div class='input-field col s8'><input type='text' name='itemPrice[]' id='itemPrice_".$n."'  class='changesNo_dr resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' value='".$ratesperproduct."' placeholder='Price' required='required'></div></td>";
echo "<td><div class='input-field col s8'><input type='text' name='itemLineTotal[]' id='itemLineTotal_".$n."'  class='resetThis totalLinePricedr' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' value='".$Amount. "' placeholder='Total' required='required'></div></td>";
echo "</tr>";



 }
echo "</tbody>";
echo "</table>";
echo "<table class='table table-full'><tr>";
echo "<td class='white'><button class='btn btn-success addmore_dr' type='button'>+ Add</button> <button class='btn btn-danger delete_dr' type='button'>- Delete</button></td>";
echo "<td  class='white'></td>";
echo "<td class='white'></td>";
echo "<td class='white'></td>";
echo "<td class='cyan white-text'>Grand Total</td>";
echo "<td class='cyan strong white-text'><div class='input-field col s8 '><input type='text' readonly class='resetThis' id='subTotal' name='subTotal' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' placeholder='Total' value='".$stotal. "' required='required'/></div></td>";
echo "</tr>";
echo "<tr>";
echo "<td class='white'></td>";
echo "<td  class='white'></td>";
echo "<td class='white'></td>";
echo "<td class='white'></td>";
echo "<td class=''>Vat</td>";
echo "<td class='strong'><div class='input-field col s8 '><input type='text' class='resetThis' id='vatdr' name='vat' placeholder='vat' onkeypress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required='required' value='".$tax."'/> <input type='hidden' class='resetThis' id='taxAmountdr' placeholder='Tax'  onkeypress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;'  value='".$tax."'></div></td>";
echo "</tr>";
echo "<tr>";
echo "<td class='white'></td>";
echo "<td  class='white'></td>";
echo "<td class='white'></td>";
echo "<td class='white'></td>";
echo "<td class='cyan white-text'>Grand Total</td>";
echo "<td class='cyan strong white-text'><div class='input-field col s8'><input type='text' class='resetThis' id='totalAftertaxdr' name='totalAftertax' placeholder='Total' onkeypress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' readonly  value='".$subtotal."' required='required' ></div></td>";
echo "</tr>"; 

echo "</table></div>";
echo "</div>";
echo "<div class='invoice-footer'>";
echo "<div class='row'>";
echo "<div class='col12 s12 m12 l6 leftspace lineheight'>";
echo "<div class='row'><b>Term and Condition:</b><span class='input-field col s9'>";
echo "</span></div>";
echo "<div class='row'><div class='col s2'>Tin No </div> <div class='input-field col s2 left'><input type='text' name='tinno' id='tinno'  class='resetThis' style='width:100%' placeholder='Tin No' readonly value='".$tinno."' required='required'/> </div></div>";	
echo "<div class='row'><div class='col s2'>CST No </div><div class='input-field col s2 left'><input type='text' name='cstno' id='cstno'  class='resetThis' style='width:100%' placeholder='CST No' readonly value='".$cstno."' required='required'/> </div></div>";
echo "<div class='row'><div class='col s2'>Payment </div> <div class='input-field col s2 left'><input type='text' name='payment' id='payment'  class='resetThis' placeholder='Payment' value='".$payment."' required='required'/> </div> </div>";
echo "<div class='row'><div class='col s2'>Loading & Vat</div><div class='resetThis input-field col s2 left'><input type='text' name='vat_o' id='vat_o'   style='width:100%' placeholder='VAT' value='".$vat_o."' required='required' /></div></div>";
echo "<div class='row'><div class='col s2'>Delivery</div><div class='input-field col s2 left'><input type='text' name='place' id='place'   style='width:100%' placeholder='Place' value='".$place."' class='resetThis' placeholder='place' required='required' /></div></div>";
echo "<div class='row'><div class='col s2'>Please Supply by</div><div class='input-field col s2  date form_date'> <input size='16' type='text' value='".$ddate."' id='ddate' name='ddate' placeholder='Delivery Date'><span class='add-on'><i class='icon-remove'></i></span>
<span class='add-on'><i class='icon-th'></i></span></div><div class='col s1 textaligncenter'>at our</div><div class='col s2'><input type='text' name='place1' id='place1'  class='resetThis' style='width:100%'  value='".$place."' required='required'/> </div> <div class='col s1 textaligncenter'>Site</div></div>";
echo "<div class='row'><div class='col s2'>Transportation </div> <div class='input-field col s2 left'><input type='text' name='tpwords' id='tpwords'  class='resetThis' style='width:100%' placeholder='Transportation' value='" .$tpwords. "' /> </div></div>";
echo "<div class='row'><b> <div class='col s2'>Site contact Person </div><div class='input-field col s2 left'><input type='text' name='contactname' id='contactname'  class='resetThis' style='width:100%' placeholder='Site Person'  value='".$contactname."' required='required'/></div><div class='col s1' style='padding-left:40px;'>Mobile :</div><div class='input-field col s2 left'><input type='text' name='mobilno' id='mobilno'  class='resetThis' placeholder='Mobile No'  value='".$mobileno."' required='required'/></div> </b></div>
<div class='row'><div class='input-field col s2'><b>Site address </b></div>";
echo "<div class='input-field col s2 left'><b><input type='text' name='projectname' id='projectname'  class='resetThis' style='width:100%; font-weight:bold' placeholder='Project Name' readonly  value='".$project_name."' required='required' /></b></div></div>";
echo "<div class='row'><div class='input-field col s4 left'><textarea name='address' id='address' value='' class='resetThis' readonly placeholder='address' style='height:80px; width:100%;' required='required' >".$address."</textarea><br />";
echo "<input type='hidden' name='project_id' id='project_id'  value='".$project_id."' class='resetThis' style='width:100%'/></div></div></p></div>";
echo "<div class='col12 s12 m6 l6 left leftspace'>";
echo "<p>Thanking you, <br />
 Yours Sincerely<br /></p>
<img src='images/anisa_digi.jpg' width='83' height='57' />
 <p class='header'>Anisa Fathima.H</p>
 <p>Purchase Manager</p>
</div>
</div>
</div>
</div>";
echo "<div class='row'><div class='col s2 right'>  <button type='submit' class='btn-floating btn-large waves-effect waves-light cyan' title='submit'><i class='material-icons medium'>done</i>></button> <a class='btn-floating btn-large waves-effect waves-light' title='Cancel' id='clear'><i class='material-icons medium'>clear</i></a></div></div>";
}

 ?>