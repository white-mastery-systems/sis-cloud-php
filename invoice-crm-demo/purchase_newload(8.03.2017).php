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
if($type == "standard")
{
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
echo "<div class='col s12 m6 l6'>&nbsp;</div>";
echo "<div class='col s12 m6 l6 lineheight'><div class='input-field col s3 right rightspace'><input type='text' name='po_date' id='po_date'  placeholder='Date' value='".date('d.m.Y')."' readonly /> </div></div>";
echo "</div>";
echo "</div>";
echo "<div class='row'>";
echo "<div class='col s12 m6 l6'><div class='input-field col s6 left leftspace'><input type='text' name='po_no' id='po_no' readonly  class='validate' > </div></div>";

echo "<div class='col12 s12 m6 l3 cyan'>";
echo "<div class='col s12 leftspace'><b>To</b></div>";
echo "<div class='col s12 m6 l6'><div class='input-field col s6 left leftspace'><input type='text' placeholder='Company Name' id='vendor_name' name='vendor_name' class='ui-autocomplete-input resetThis' autocomplete='off' required ></div></div>";
echo "<div class='col s12 m6 l6 right rightspace'><div class='input-field col s4 left leftspace'>&nbsp;</div></div>
</div>
</div>";
echo "<div class='row'>";
echo "<div class='col12 s12 m6 l3 cyan'>";
echo "<div class='col s12 m6 l6'><div class='input-field col s6 left leftspace'><textarea name='ven_add' id='ven_add' placeholder='Address'  style='height:80px' class='resetThis' required='required' ></textarea>";
echo "<input type='hidden' name='ven_id' id='ven_id' placeholder='vendor-id' class='resetThis' required='required'  /><br /></div></div>
<div class='col s12 m6 l6 right rightspace'><div class='input-field col s8 left leftspace'>&nbsp;</div></div>
</div>
</div>";
echo "<div class='row'>
<div class='col s12'>
<center>
<div class='input-field col s3 leftspace' style='width:auto; margin-right:8px'> <b>Kind Attention: </b></div>
<div class='input-field col s2'> 
<input type='text' name='ven_contactperson' id='ven_contactperson' placeholder='Contact Person' class='resetThis' required='required' /></div>
</center>
</div>
</div>";
echo "<div class='row leftspace'>
<div class='col12 s12 m6 l3 cyan'>
<div class='col ref1'>Ref : </div><div class='input-field col ref2'><input type='text' name='refno' id='refno' class='validate resetThis' placeholder='Ref No'  ></div> <div class='col dat1'> Dated on </div> <div class='input-field col dat2'> <input type='text' name='refdate' id='refdate' placeholder='Ref Date' class='validate resetThis' class='resetThis'/> </div>";
echo "<div class='col s12 m6 l6 right rightspace'><div class='input-field col s8 left leftspace'>&nbsp;</div></div>
</div>
</div>";
echo "<div class='row  leftspace'><div class='col s2'  style='width:auto; margin:0 8px 0 0px'> Sub : PO for </div><div class='input-field col s3 left'> <input type='text' name='subject' id='subject' class='validate resetThis' placeholder='Subject' required='required' ></div> <div class='col s2'  style='width:auto; margin:0 8px 0 0px'>.</div> </div>";

echo "<div class='row  leftspace'>
<div class='col s8 left' style='width:auto; margin-right:10px'>
We are pleased to place the Purchase order as per the details mentioned below for our </div> <div class='col s2 left' ><select name='project_name' id='project_name' onChange='projectlist(this.value)' required='required' class='resetThis' required='required' >";
 
$result = mysql_query("SELECT distinct project_name FROM project_details",$conn);
  echo "<option selected value=''>SELECT</option>";
  while ($row = mysql_fetch_array($result)) {
	  $project_name = $row['project_name'];
    echo "<option  value='".$row['project_name']."'>" . $row['project_name'] . "</option>";
   }
 echo $project_name;

   

echo "</select></div><div class='col s2 left' style='margin-left:5px'>";
echo "<script>
$('#project_name').change(function () {
var project_name = $(this).val(); 
$.post('blockname.php', { project_name: project_name }, function (data) {
$('#blockname').html(data);
});
});
</script>";

echo "<select name='blockname' id='blockname' required='required' class='resetThis' required >";
echo "<option selected value=''>SELECT</option>";
echo "</select><br></div>";

echo "<div class='col s6 left'>";
echo " the address and the contact person are mentioned below. <input type='hidden' name='place2' id='place2' style='width:auto' placeholder='Place' readonly class='resetThis'/> </div>";
echo "</div>";
echo "<div class='invoice-table'>";
echo "<div class='row '>";
echo "<div class='col s12 m12 l12 table-responsive '>";
echo "<table class='table table-full' id='itemsTable'>";
echo "<thead>";
echo "<tr>";
echo "<th ><input type='checkbox' id='check_all' class='' /><label for='check_all'></label></th>";
echo "<th>Item Name</th>";
echo "<th>Quantity</th>";
echo "<th>Unit</th>";
echo "<th>price</th>";
echo "<th>vat</th>";
echo "<th>Total</th>";
echo "</tr>";
echo "</thead>";
echo "<tbody>";
echo "<tr>";
echo "<td><input type='checkbox' id='selecttd1'  class='case' /><label for='selecttd1'></label></td>";
echo "<td><div class='input-field col s8'><input type='text' data-type='pro_name' name='itemDesc[]' id='itemDesc_1' class='autocomplete_txt1 resetThis' autocomplete='off' required placeholder='DESC'></div></td>";
echo "<td><div class='input-field col s8'>

<input type='text' name='itemQty[]' id='itemQty_1' class='changesNo1 resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required placeholder='QTY'>  <input name='cat_name[]' class='resetThis' id='cat_name_1' type='hidden'/>
 <input name='sub_catname[]' class='resetThis' id='sub_catname_1' type='hidden'/>
<input name='brand[]'   id='brand_1' tabindex='1' type='hidden'  /> <input name='itemCode[]'  class='resetThis' id='itemCode_1'  type='hidden' data-type='product_code' /></div></td>";
echo "<td><div class='input-field col s8'><input name='units[]'  class='units resetThis' id='units_1' tabindex='1' type='text' required placeholder='units' /></div></td>";

echo "<td><div class='input-field col s8'><input type='text' name='itemPrice[]' id='itemPrice_1' class='changesNo1 resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required placeholder='PRICE'/></div></td>";
echo "<td><div class='input-field col s8'><input type='text' name='itemVat[]' id='itemVat_1' class='resetThis changesNo1' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required placeholder='VAT'/></div></td>";
echo "<td><div class='input-field col s8'><input type='text' name='itemLineTotal[]' id='itemLineTotal_1' class='resetThis totalLinePrice_s' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required placeholder='Total' /></div></td>";
echo "</tr>";
echo "</tbody>";
echo "</table>";
echo "<table class='table table-full'><tr>";
echo "<td class='white'><button class='btn btn-success addmore1' type='button'>+ Add</button> <button class='btn btn-danger delete1' type='button'>- Delete</button> &nbsp; <input type='checkbox' name='inclusive' id='inclusive'  class=''  value='0' /><label for='inclusive'>Inclusive of All Tax(vat must be zero)</label></td>";
echo "<td  class='white'> </td>";
echo "<td class='white'></td>";
echo "<td class='white'></td>";
echo "<td class='cyan white-text'>GRAND TOTAL</td>";
echo "<td class='cyan strong white-text'><div class='input-field col s8 '><input type='text' readonly class='resetThis' id='subTotal' name='subTotal' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' placeholder='Grand Total' required style='border-bottom:1px solid #fff; color:#fff; text-transform:uppercase; margin: 0 0 5px 0;' /></div></td>";
echo "</tr>";
echo "</table></div>";
echo "</div>";
echo "<div class='invoice-footer'>";
echo "<div class='row'>";
echo "<div class='col12 s12 m12 l6 leftspace lineheight'>";
echo "<div class='row'><b>Term and Condition:</b><span class='input-field col s9'>";
echo "</span></div>";
echo "<div class='row'><div class='col s2'>Tin No </div> <div class='input-field col s2 left'><input type='text' name='tinno' id='tinno'  class='resetThis' style='width:100%' placeholder='Tin No' readonly required/> </div></div>";	
echo "<div class='row'><div class='col s2'>CST No </div><div class='input-field col s2 left'><input type='text' name='cstno' id='cstno'  class='resetThis' style='width:100%' placeholder='CST No' readonly required/> </div></div>";
echo "<div class='row'><div class='col s2'>Payment </div> <div class='input-field col s2 left'><input type='text' name='payment' id='payment'  class='resetThis' placeholder='Payment'  required/> </div> </div>";
echo "<div class='row'><div class='col s2'>Delivery</div><div class='input-field col s2 left'><input type='text' name='place' id='place'   style='width:100%' placeholder='Place' class='resetThis' required /></div></div>";
echo "<div class='row'><div class='col s2'>Loading & Vat</div><div class='resetThis input-field col s2 left'><input type='text' name='vat_o' id='vat_o'   style='width:100%'  placeholder='VAT'  class='resetThis' /></div></div>";
echo "<div class='row'><div class='col s2'>Please Supply by</div>   <div class='input-field col s2  date form_date'> <input size='16' type='text' value='' id='ddate' name='ddate' placeholder='Delivery Date'>
                    <span class='add-on'><i class='icon-remove'></i></span>
					<span class='add-on'><i class='icon-th'></i></span>
                </div><div class='col s1  textaligncenter'>at our</div><div class='col s2'><input type='text' name='place1' id='place1'  class='resetThis' style='width:100%' required placeholder='Place'/> </div> <div class='col s1 textaligncenter'>Site</div></div>";
				echo "<div class='row'><div class='col s2'>Transportation </div> <div class='input-field col s2 left'><input type='text' name='tpwords' id='tpwords'  class='resetThis' style='width:100%' placeholder='Transportation'  /> </div></div>";	
echo "<div class='row'><b> <div class='col s2'>Site contact Person </div><div class='input-field col s2 left'><input type='text' name='contactname' id='contactname'  class='resetThis' style='width:100%' placeholder='Site Person' required/></div><div class='col s1' style='padding-left:40px;'>Mobile :</div>     <div class='input-field col s2 left'><input type='text' name='mobilno' id='mobilno'  class='resetThis' placeholder='Mobile No' required/></div> </b></div>
<div class='row'><div class='input-field col s2'><b>Site address </b></div>";
echo "<div class='input-field col s2 left'><b><input type='text' name='projectname' id='projectname'  class='resetThis' style='width:100%; font-weight:bold' placeholder='Project Name' readonly required /></b></div></div>";
echo "<div class='row'><div class='input-field col s4 left'><textarea name='address' id='address' value='resetThis' class='resetThis' readonly placeholder='address' style='height:80px' required></textarea><br />";
echo "<input type='hidden' name='project_id' id='project_id'  class='resetThis' style='width:100%' required /></div></div></p></div>";
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
echo "<div class='row'><div class='col s2 right'><button type='submit' class='btn-floating btn-large waves-effect waves-light pink' title='submit'><i class='material-icons medium'>done</i>></button> <a class='btn-floating btn-large waves-effect waves-light pink' title='Cancel' id='clear'><i class='material-icons medium'>clear</i></a></div></div>";
      echo "<input type='hidden' name='city' id='city' style='width:auto' placeholder='city' readonly  class='resetThis'  />"; 
}
else if($type == "upvc")
{
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
echo "<div class='col s12 m6 l6'>&nbsp;</div>";
echo "<div class='col s12 m6 l6 lineheight'><div class='input-field col s3 right rightspace'><input type='text' name='po_date' id='po_date'  placeholder='Date' value='".date('d.m.Y')."' required /> </div></div>";
echo "</div>";
echo "</div>";
echo "<div class='row'>";
  echo "<div class='col s12 m6 l6'><div class='input-field col s6 left leftspace'><input type='text' name='po_no' id='po_no' readonly  class='validate' required> </div></div>";

echo "<div class='col12 s12 m6 l3 cyan'>";
echo "<div class='col s12 leftspace'><b>To</b></div>";
echo "<div class='col s12 m6 l6'><div class='input-field col s6 left leftspace'><input type='text' placeholder='Company Name' id='vendor_name' name='vendor_name' class='ui-autocomplete-input resetThis' autocomplete='off' required /></div></div>";
echo "<div class='col s12 m6 l6 right rightspace'><div class='input-field col s4 left leftspace'>&nbsp;</div></div>
</div>
</div>";
echo "<div class='row'>";
echo "<div class='col12 s12 m6 l3 cyan'>";
echo "<div class='col s12 m6 l6'><div class='input-field col s6 left leftspace'><textarea name='ven_add' id='ven_add' placeholder='Address'  style='height:80px' class='resetThis' required></textarea>";
echo "<input type='hidden' name='ven_id' id='ven_id' placeholder='vendor-id' class='resetThis'  /><br /></div></div>
<div class='col s12 m6 l6 right rightspace'><div class='input-field col s8 left leftspace'>&nbsp;</div></div>
</div>
</div>";
echo "<div class='row'>
<div class='col s12'>
<center>
<div class='input-field col s3 leftspace' style='width:auto; margin-right:8px'> <b>Kind Attention: </b></div>
<div class='input-field col s2'> 
<input type='text' name='ven_contactperson' id='ven_contactperson' placeholder='Contact Person' class='resetThis' required /></div>
</center>
</div>
</div>";
echo "<div class='row leftspace'>
<div class='col12 s12 m6 l3 cyan'>
<div class='col ref1'>Ref : </div><div class='input-field col ref2'><input type='text' name='refno' id='refno' class='validate resetThis' placeholder='Ref No'/></div> <div  class='col s4' style='width:auto; margin:0 10px 0 8px'> - Amended Qty Without Glass Dated</div> <div class='input-field col dat2'> <input type='text' name='refdate' id='refdate' placeholder='Ref Date' class='validate resetThis' class='resetThis'  /> </div>";
echo "<div class='col s12 m6 l6 right rightspace'><div class='input-field col s8 left leftspace'>&nbsp;</div></div>
</div>
</div>";
echo "<div class='row  leftspace'>
<div class='col s3 left'>
<b>Sub:</b>Our PO for window for our project</div> <div class='col s2 left' ><select name='project_name' id='project_name' onChange='projectlist(this.value)' required='required' class='resetThis' required >";
 
$result = mysql_query("SELECT distinct project_name FROM project_details",$conn);
  echo "<option selected value=''>SELECT</option>";
  while ($row = mysql_fetch_array($result)) {
	  $project_name = $row['project_name'];
    echo "<option  value='".$row['project_name']."'>" . $row['project_name'] . "</option>";
   }
 echo $project_name;
echo "</select></div><div class='col s2 left' style='margin-left:5px'>";
echo "<script>
$('#project_name').change(function () {
var project_name = $(this).val(); 
$.post('blockname.php', { project_name: project_name }, function (data) {
$('#blockname').html(data);
});
});
</script>";

echo "<select name='blockname' id='blockname' required='required' class='resetThis'>";
echo "<option selected value=''>SELECT</option>";
echo "</select><br></div>";
echo "<div class='col s1 left' style='width:auto; margin:0 10px 0 10px'> at </div>";
echo "<div class='col s2 left'>";
echo "<input type='text' name='place2' id='place2'   style='width:auto' placeholder='Place' readonly class='resetThis' required/> </div>";
echo "<div class='col s2 left'>";
echo "<input type='text' name='city' id='city' style='width:auto' placeholder='city' readonly  class='resetThis' required /> </div>
<div class='col s1 left' style='width:auto;'> . </div>
</div>
</div>";
echo "<div class='invoice-table'>";
echo "<div class='row'>";
echo "<div class='col s12 m12 l12 table-responsive '>";
echo "<table class='table table-full' id='itemsTable'>";
echo "<thead>";
echo "<tr>";
echo "<th><input type='checkbox' id='check_all' class='' /><label for='check_all'></label></th>";
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
echo "<tr><td><input type='checkbox' id='selecttd1'  class='case' /><label for='selecttd1'></label></td>";
echo "<td><div class='input-field col s8 '> <input  data-type='product_code'  id='itemCode_1'  name='itemCode[]' class='autocomplete_txt resetThis'   type='text' required autocomplete='off' placeholder='Code' /><input  id='itemBrand_1'  name='itemBrand[]' class='resetThis' type='hidden'  required autocomplete='off'  /></div></td>";
echo "<td><div class='input-field col s8 resetThis '> <input name='itemWidth[]' id='itemWidth_1'  type='text'  required autocomplete='off' placeholder='Width' class='resetThis' /></div></td>";
echo "<td><div class='input-field col s8'><input name='itemHeight[]' id='itemHeight_1' type='text' required autocomplete='off'  placeholder='Height' class='resetThis'/></div></td>";
echo "<td><div class='input-field col s8'><input name='itemQty[]'  id='itemQty_1' type='text' class='changesNo resetThis' required autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' placeholder='QTY' /> <input name='itemUnit[]'  id='itemUnit_1' class='resetThis' type='hidden' required autocomplete='off' /></div></td>";
echo "<td><div class='input-field col s8 '><input data-type='pro_name' name='itemDesc[]'  id='itemDesc_1' type='text' class='autocomplete_txt resetThis'  required tocomplete='off' placeholder='Type'></div></td>";
echo "<td><div class='input-field col s8 '><input name='itemBasic_m[]'  id='itemBasicm_1' type='text' autocomplete='off' class='changesNo resetThis' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' placeholder='M Basic' required /></div></td>
<td><div class='input-field col s8 '><input name='itemTotal_m[]' id='itemTotalm_1' readonly='readonly' type='text' autocomplete='off' class='totalLinePrice1 resetThis' placeholder='B Total' required /></div></td>";
echo "<td><div class='input-field col s8'><input name='itemBasic_i[]'  id='itemBasici_1' type='text' autocomplete='off' class='changesNo resetThis' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' placeholder='I Basic' required /></div></td>
<td><div class='input-field col s8'><input name='itemTotal_i[]' id='itemTotali_1' readonly='readonly' type='text' autocomplete='off' class='totalLinePrice2 resetThis' Placeholder='I Total' required/></div></td>";
echo "<td><div class='input-field col s8'><input name='itemLineTotal[]'  id='itemLineTotal_1' readonly='readonly' type='text' placeholder='Total' autocomplete='off' class='totalLinePrice' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required/></div></td>";
echo "</tr>";
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
echo "<td><div class='input-field col s9'><input name='basictotal'  class='basictotal resetThis' id='basictotal' type='text' required  Placeholder='M Total'/></div><input type='hidden'  class='itotal resetThis' id='itotal' name='itotal' required /><input type='hidden' class='resetThis' name='subTotal' id='subTotal' placeholder='Grand Total' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' style='border-bottom:1px solid #fff; color:#fff; text-transform:uppercase; margin: 0 0 5px 0;' required></td>";
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
<td><div class='input-field col s9'><input type='text'  name='ed' id='ed' placeholder='ED' onKeyPress='return IsNumeric(event);' ondrop='return false;' class='resetThis' onpaste='return false;' required/></div></td>";
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
echo "<td><div class='input-field col s9'><input type='text' class='resetThis' id='vat' name='vat' placeholder='vat' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required ><input type='hidden' class='resetThis' id='taxAmount1' placeholder='Tax' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;'><input type='hidden' class='resetThis' id='taxAmount' placeholder='Tax' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;'></div></td>";
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
echo "<td><div class='input-field col s9'><input type='text' class='resetThis' id='tp' name='tp' placeholder='Amount Paid' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required > <input type='hidden' class='resetThis' id='tp1' name='tp1' placeholder='Amount Paid' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;'></div></td>";
echo "<td></td>
<th></td>
<td></td>
<td></td>
</tr>
<tr>
<td></td>
<td></td>
<td></td>";
echo "<td><input type='hidden' class='amountDue resetThis' id='amountDue' placeholder='Amount Due' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required ></td>";
echo "<td>ST</td>";
echo "<td><div class='input-field col s9'><input type='text' class='resetThis' name='st' id='st' placeholder='st' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required/> <input type='hidden' class='resetThis' id='taxAmount2' placeholder='Tax2' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;'></div></td>";
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
echo "<td><div class='input-field col s9'><input name='gt'  class='amountDue1 gt resetThis' id='gt' type='text' required readonly placeholder='M Total with tax' /></div></td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>";
echo "<td class='cyan white-text'><div class='input-field col s9'><input name='gt1'  class='gt1 resetThis' id='gt1' type='text' required  readonly placeholder='Installation Total' required /></div></td>";
echo "<td class='cyan strong white-text'><div class='input-field col s9'><input type='text' class='resetThis' id='totalAftertax' name='totalAftertax' placeholder='Total' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' readonly  required /></div></td>
</tr>";
echo "</table>
</div>
</div>
</div>";
echo "<div class='invoice-footer'>";
echo "<div class='row'>";
echo "<div class='col12 s12 m12 l6 leftspace lineheight'>";
echo "<div class='row'><b>Term and Condition:</b><span class='input-field col s9'></span></div>";
echo "<div class='row'><div class='col s2'>Tin No </div> <div class='input-field col s2 left'><input type='text' name='tinno' id='tinno'  class='resetThis' style='width:auto' placeholder='Tin No' readonly required/> </div></div>";
echo "<div class='row'><div class='col s2'>CST No </div><div class='input-field col s2 left'><input type='text' name='cstno' id='cstno'  class='resetThis' style='width:auto' placeholder='CST No' readonly required/> </div></div>";
echo "<div class='row'><div class='col s2'>Payment </div> <div class='input-field col s2 left'><input type='text' name='payment' id='payment'  class='resetThis' placeholder='Payment' required/> </div> </div>";
echo "<div class='row'><div class='col s2'>Delivery</div><div class='input-field col s2 left'><input type='text' name='place' id='place'   style='width:auto' placeholder='Place' class='resetThis' required /></div></div>";
echo "<div class='row'><div class='col s2'>Loading & Vat</div><div class='input-field col s2 left'><input type='text' name='vat_o' id='vat_o'   style='width:auto' required class='resetThis' required  placeholder='VAT'/></div></div>";
echo "<div class='row'><div class='col s2'>Please Supply by</div>  <div class='input-field col s2  date form_date'> <input size='16' type='text' value='' id='ddate' name='ddate' placeholder='Delivery Date'>
                    <span class='add-on'><i class='icon-remove'></i></span>
					<span class='add-on'><i class='icon-th'></i></span>
                </div> <div class='col s1  textaligncenter'>at our</div><div class='col s2'><input type='text' name='place1' id='place1'  class='resetThis' style='width:auto' required placeholder='Place'/> </div> <div class='col s1 textaligncenter'>Site</div></div>";
echo "<div class='row'><div class='col s2'>Transportation </div> <div class='input-field col s2 left'><input type='text' name='tpwords' id='tpwords'  class='resetThis' style='width:auto' placeholder='Transportation'  /> </div></div>";	
echo "<div class='row'><b> <div class='col s2'>Site contact Person </div><div class='input-field col s2 left'><input type='text' name='contactname' id='contactname'  class='resetThis' style='width:auto' placeholder='Site Person' required /></div><div class='col s1' style='padding-left:40px;'>Mobile :</div>     <div class='input-field col s2 left'><input type='text' name='mobilno' id='mobilno'  class='resetThis' placeholder='Mobile No' required /></div> </b></div>";
echo "<div class='row'><div class='input-field col s2'><b>Site address </b></div>
<div class='input-field col s2 left'><b><input type='text' name='projectname' id='projectname'  class='resetThis' style='width:auto; font-weight:bold' placeholder='Project Name' readonly required /></b></div></div>";
echo "<div class='row'><div class='input-field col s4 left'><textarea name='address' id='address' value='' class='resetThis' readonly placeholder='address' style='height:80px' required></textarea><br />
<input type='hidden' name='project_id' id='project_id'  class='resetThis' style='width:auto'/></div></div>
</p>
</div>";
echo "<div class='col12 s12 m6 l6 left leftspace'>
<p>Thanking you, <br />
 Yours Sincerely<br />
</p>
<img src='images/anisa_digi.jpg' width='83' height='57' />
<p class='header'>Anisa Fathima.H</p>
<p>Purchase Manager</p>
</div>
</div>
</div>"; 
echo " <div class='row'><div class='col s2 right'>  <button type='submit' class='btn-floating btn-large waves-effect waves-light  pink' title='submit'><i class='material-icons medium'>done</i>></button> <a class='btn-floating btn-large waves-effect waves-light pink' title='Cancel' id='clear'><i class='material-icons medium'>clear</i></a></div></div></div>";
     
}
else if($type == "steel")
{
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
echo "<div class='col s12 m6 l6'>&nbsp;</div>";
echo "<div class='col s12 m6 l6 lineheight'><div class='input-field col s3 right rightspace'><input type='text' name='po_date' id='po_date'  placeholder='Date' value='".date('d.m.Y')."' required='required' /> </div></div>";
echo "</div>";
echo "</div>";
echo "<div class='row'>";
  echo "<div class='col s12 m6 l6'><div class='input-field col s6 left leftspace'><input type='text' name='po_no' id='po_no' readonly  class='validate' required='required' /> </div></div>";

echo "<div class='col12 s12 m6 l3 cyan'>";
echo "<div class='col s12 leftspace'><b>To</b></div>";
echo "<div class='col s12 m6 l6'><div class='input-field col s6 left leftspace'><input type='text' placeholder='Company Name' id='vendor_name' name='vendor_name' class='ui-autocomplete-input resetThis' autocomplete='off' required='required'></div></div>";
echo "<div class='col s12 m6 l6 right rightspace'><div class='input-field col s4 left leftspace'>&nbsp;</div></div>
</div>
</div>";
echo "<div class='row'>";
echo "<div class='col12 s12 m6 l3 cyan'>";
echo "<div class='col s12 m6 l6'><div class='input-field col s6 left leftspace'><textarea name='ven_add' id='ven_add' placeholder='Address'  style='height:80px' class='resetThis' required='required'></textarea>";
echo "<input type='hidden' name='ven_id' id='ven_id' placeholder='vendor-id' class='resetThis'  /><br /></div></div>
<div class='col s12 m6 l6 right rightspace'><div class='input-field col s8 left leftspace'>&nbsp;</div></div>
</div>
</div>";
echo "<div class='row'>
<div class='col s12'>
<center>
<div class='input-field col s3 leftspace' style='width:auto; margin-right:8px'> <b>Kind Attention: </b></div>
<div class='input-field col s2'> 
<input type='text' name='ven_contactperson' id='ven_contactperson' placeholder='Contact Person' class='resetThis' required='required' /></div>
</center>
</div>
</div>";
echo "<div class='row leftspace'>
<div class='col12 s12 m6 l3 cyan'>
<div class='col ref1'>Ref : </div><div class='input-field col ref2'><input type='text' name='refno' id='refno' class='validate resetThis' placeholder='Ref No'  ></div> <div  class='col dat1'> Dated on </div> <div class='input-field col dat2'> <input type='text' name='refdate' id='refdate' placeholder='Ref Date' class='validate resetThis' class='resetThis' /> </div>";
echo "<div class='col s12 m6 l6 right rightspace'><div class='input-field col s8 left leftspace'>&nbsp;</div></div>
</div>
</div>";
echo "<div class='row  leftspace'><div class='col s2'  style='width:auto; margin:0 8px 0 0px'> Sub : PO for </div><div class='input-field col s3 left'> <input type='text' name='subject' id='subject' class='validate resetThis' placeholder='Subject' required='required' ></div> <div class='col s2'  style='width:auto; margin:0 8px 0 0px'>.</div> </div>";
echo "<div class='row  leftspace'>
<div class='col s8 left' style='width:auto; margin-right:10px'>
We are pleased to place the Purchase order as per the details mentioned below for our </div> <div class='col s2 left' ><select name='project_name' id='project_name' onChange='projectlist(this.value)' required='required' class='resetThis' >";
 
$result = mysql_query("SELECT distinct project_name FROM project_details",$conn);
  echo "<option selected value='select'>SELECT</option>";
  while ($row = mysql_fetch_array($result)) {
	  $project_name = $row['project_name'];
    echo "<option  value='".$row['project_name']."'>" . $row['project_name'] . "</option>";
   }
 echo $project_name;

   

echo "</select></div><div class='col s2 left' style='margin-left:5px'>";
echo "<script>
$('#project_name').change(function () {
var project_name = $(this).val(); 
$.post('blockname.php', { project_name: project_name }, function (data) {
$('#blockname').html(data);
});
});
</script>";

echo "<select name='blockname' id='blockname' required='required' class='resetThis'>";
echo "<option selected value='select'>SELECT</option>";
echo "</select><br></div>";

echo "<div class='col s6 left'>";
echo " the address and the contact person are mentioned below. <input type='hidden' name='place2' id='place2' style='width:auto' placeholder='Place' readonly class='resetThis' required='required' /> </div>";
echo "</div>";
echo "<div class='invoice-table'>";
echo "<div class='row '>";
echo "<div class='col s12 m12 l12 table-responsive '>";
echo "<table class='table table-full' id='itemsTable'>";
echo "<thead>";
echo "<tr>";
echo "<th ><input type='checkbox' id='check_all' class='' /><label for='check_all'></label></th>";
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
echo "<tr>";
echo "<td><input type='checkbox' id='selecttd1'  class='case' /><label for='selecttd1'></label></td>";
echo "<td><div class='input-field col s8'><input type='text' data-type='pro_name' name='itemDesc[]' id='itemDesc_1' class='resetThis autocomplete_txtsteel' autocomplete='off' required='required' placeholder='DESC'></div></td>";
echo "<td><div class='input-field col s8'><input name='details[]'   id='details_1' type='text' required='required' placeholder='Details' /></div></td>";

echo "<td><div class='input-field col s8'>
<input type='text' name='itemQty[]' id='itemQty_1' class='changesNo_st resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required='required' placeholder='QTY'>  <input name='cat_name[]' class='resetThis' id='cat_name_1' type='hidden'/>
 <input name='sub_catname[]' class='resetThis' id='sub_catname_1' type='hidden'/>
 
<input name='itemCode[]'  class='resetThis' id='itemCode_1'  type='hidden' data-type='product_code' /><input name='size[]' class='resetThis' id='size_1' tabindex='1' type='hidden'  /><input name='units[]'  class='units resetThis' id='units_1' tabindex='1' type='hidden' required placeholder='units' /></div></td>";
echo "<td><div class='input-field col s8'><input type='text' name='itemPrice[]' id='itemPrice_1' class='changesNo_st resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required='required' placeholder='Price'/></div></td>";
echo "<td><div class='input-field col s8'><input type='text' name='itemVat[]' id='itemVat_1' class='resetThis changesNo_st' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required='required' placeholder='VAT'></div></td>";
echo "<td><div class='input-field col s8'><input name='brand[]'   id='brand_1' tabindex='1' type='text' required='required' placeholder='Brand' /></div></td>";
echo "<td><div class='input-field col s8'><input type='text' name='itemLineTotal[]' id='itemLineTotal_1' class='resetThis totalLinePricest' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required='required' placeholder='Total'></div></td>";
echo "</tr>";
echo "</tbody>";
echo "</table>";
echo "<table class='table table-full'><tr>";
echo "<td class='white'><button class='btn btn-success addmore_st' type='button'>+ Add</button> <button class='btn btn-danger delete_st' type='button'>- Delete</button> &nbsp; <input type='checkbox' name='inclusive' id='inclusive'  class=''  value='0' /><label for='inclusive'>Inclusive of All Tax(vat must be zero)</label></td>";
echo "<td  class='white'></td>";
echo "<td class='white'></td>";
echo "<td class='white'></td>";
echo "<td class='cyan white-text'>Grand Total</td>";
echo "<td class='cyan strong white-text'><div class='input-field col s8 '><input type='text' readonly class='resetThis' id='subTotal' name='subTotal' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' placeholder='Total' required='required' placeholder='Grand total' style='border-bottom:1px solid #fff; color:#fff; text-transform:uppercase; margin: 0 0 5px 0;' /></div></td>";
echo "</tr>";
echo "</table></div>";
echo "</div>";
echo "<div class='invoice-footer'>";
echo "<div class='row'>";
echo "<div class='col12 s12 m12 l6 leftspace lineheight'>";
echo "<div class='row'><b>Term and Condition:</b><span class='input-field col s9'>";
echo "</span></div>";
echo "<div class='row'><div class='col s2'>Tin No </div> <div class='input-field col s2 left'><input type='text' name='tinno' id='tinno'  class='resetThis' style='width:auto' placeholder='Tin No' readonly required/> </div></div>";	
echo "<div class='row'><div class='col s2'>CST No </div><div class='input-field col s2 left'><input type='text' name='cstno' id='cstno'  class='resetThis' style='width:auto' placeholder='CST No' readonly required/> </div></div>";
echo "<div class='row'><div class='col s2'>Payment </div> <div class='input-field col s2 left'><input type='text' name='payment' id='payment'  class='resetThis' placeholder='Payment' required/> </div> </div>";
echo "<div class='row'><div class='col s2'>Delivery</div><div class='input-field col s2 left'><input type='text' name='place' id='place'   style='width:auto' placeholder='Place' class='resetThis' required /></div></div>";
echo "<div class='row'><div class='col s2'>Loading & Vat</div><div class='resetThis input-field col s2 left'><input type='text' name='vat_o' id='vat_o'   style='width:auto'  placeholder='VAT'  class='resetThis' /></div></div>";
echo "<div class='row'><div class='col s2'>Please Supply by</div>   <div class='input-field col s2  date form_date'> <input size='16' type='text' value='' id='ddate' name='ddate' placeholder='Delivery Date'>
                    <span class='add-on'><i class='icon-remove'></i></span>
					<span class='add-on'><i class='icon-th'></i></span>
                </div><div class='col s1  textaligncenter'>at our</div><div class='col s2'><input type='text' name='place1' id='place1'  class='resetThis' style='width:auto' required placeholder='Place'/> </div> <div class='col s1 textaligncenter'>Site</div></div>";
echo "<div class='row'><div class='col s2'>Transportation </div> <div class='input-field col s2 left'><input type='text' name='tpwords' id='tpwords'  class='resetThis' style='width:auto' placeholder='Transportation' /> </div></div>";	
echo "<div class='row'><b> <div class='col s2'>Site contact Person </div><div class='input-field col s2 left'><input type='text' name='contactname' id='contactname'  class='resetThis' style='width:auto' placeholder='Site Person' required/></div><div class='col s1' style='padding-left:40px;'>Mobile :</div>     <div class='col s2'><input type='text' name='mobilno' id='mobilno'  class='resetThis' style='width:auto' placeholder='Mobile No' required/></div> </b></div>
<div class='row'><div class='input-field col s2'><b>Site address </b></div>";
echo "<div class='input-field col s2 left'><b><input type='text' name='projectname' id='projectname'  class='resetThis' style='width:auto; font-weight:bold' placeholder='Project Name' readonly required /></b></div></div>";
echo "<div class='row'><div class='input-field col s4 left'><textarea name='address' id='address' value='resetThis' class='resetThis' readonly placeholder='address' style='height:80px' required></textarea><br />";
echo "<input type='hidden' name='project_id' id='project_id'  class='resetThis' style='width:auto' required /></div></div></p></div>";
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
echo "<div class='row'><div class='col s2 right'>  <button type='submit' class='btn-floating btn-large waves-effect waves-light pink' title='submit'><i class='material-icons medium'>done</i>></button> <a class='btn-floating btn-large waves-effect waves-light  pink' title='Cancel' id='clear'><i class='material-icons medium'>clear</i></a></div></div>";
    echo "<input type='hidden' name='city' id='city' style='width:auto' placeholder='city' readonly  class='resetThis'  />"; 
}
else if($type == "door")
{
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
echo "<div class='col s12 m6 l6'>&nbsp;</div>";
echo "<div class='col s12 m6 l6 lineheight'><div class='input-field col s3 right rightspace'><input type='text' name='po_date' id='po_date'  placeholder='Date' value='".date('d.m.Y')."' required='required' /> </div></div>";
echo "</div>";
echo "</div>";
echo "<div class='row'>";
  echo "<div class='col s12 m6 l6'><div class='input-field col s6 left leftspace'><input type='text' name='po_no' id='po_no' readonly  class='validate' required='required' /> </div></div>";

echo "<div class='col12 s12 m6 l3 cyan'>";
echo "<div class='col s12 leftspace'><b>To</b></div>";
echo "<div class='col s12 m6 l6'><div class='input-field col s6 left leftspace'><input type='text' placeholder='Company Name' id='vendor_name' name='vendor_name' class='ui-autocomplete-input resetThis' autocomplete='off' required='required' /></div></div>";
echo "<div class='col s12 m6 l6 right rightspace'><div class='input-field col s4 left leftspace'>&nbsp;</div></div>
</div>
</div>";
echo "<div class='row'>";
echo "<div class='col12 s12 m6 l3 cyan'>";
echo "<div class='col s12 m6 l6'><div class='input-field col s6 left leftspace'><textarea name='ven_add' id='ven_add' placeholder='Address'  style='height:80px' class='resetThis' required='required' ></textarea>";
echo "<input type='hidden' name='ven_id' id='ven_id' placeholder='vendor-id' class='resetThis'  /><br /></div></div>
<div class='col s12 m6 l6 right rightspace'><div class='input-field col s8 left leftspace'>&nbsp;</div></div>
</div>
</div>";
echo "<div class='row'>
<div class='col s12'>
<center>
<div class='input-field col s3 leftspace' style='width:auto; margin-right:8px'> <b>Kind Attention: </b></div>
<div class='input-field col s2'> 
<input type='text' name='ven_contactperson' id='ven_contactperson' placeholder='Contact Person' class='resetThis' required='required' /></div>
</center>
</div>
</div>";
echo "<div class='row leftspace'>
<div class='col12 s12 m6 l3 cyan'>
<div class='col ref1'>Ref : </div><div class='input-field col ref2'><input type='text' name='refno' id='refno' class='validate resetThis' placeholder='Ref No' ></div> <div  class='col dat1'> Dated on </div> <div class='input-field col dat2'> <input type='text' name='refdate' id='refdate' placeholder='Ref Date' class='validate resetThis' class='resetThis'/> </div>";
echo "<div class='col s12 m6 l6 right rightspace'><div class='input-field col s8 left leftspace'>&nbsp;</div></div>
</div>
</div>";
echo "<div class='row  leftspace'><div class='col s2'  style='width:auto; margin:0 8px 0 0px'> Sub : PO for </div><div class='input-field col s3 left'> <input type='text' name='subject' id='subject' class='validate resetThis' placeholder='Subject' required='required' ></div> <div class='col s2'  style='width:auto; margin:0 8px 0 0px'>.</div> </div>";
echo "<div class='row  leftspace'>
<div class='col s8 left' style='width:auto; margin-right:10px'>
We are pleased to place the Purchase order as per the details mentioned below for our </div> <div class='col s2 left' ><select name='project_name' id='project_name' onChange='projectlist(this.value)' required='required' class='resetThis' >";
 
$result = mysql_query("SELECT distinct project_name FROM project_details",$conn);
  echo "<option selected value=''>SELECT</option>";
  while ($row = mysql_fetch_array($result)) {
	  $project_name = $row['project_name'];
    echo "<option  value='".$row['project_name']."'>" . $row['project_name'] . "</option>";
   }
 echo $project_name;

   

echo "</select></div><div class='col s2 left' style='margin-left:5px'>";
echo "<script>
$('#project_name').change(function () {
var project_name = $(this).val(); 
$.post('blockname.php', { project_name: project_name }, function (data) {
$('#blockname').html(data);
});
});
</script>";

echo "<select name='blockname' id='blockname' required='required' class='resetThis'>";
echo "<option selected value=''>SELECT</option>";
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
echo "<th ><input type='checkbox' id='check_all' class='' /><label for='check_all'></label></th>";
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
echo "<td><input type='checkbox' id='selecttd1'  class='case' /><label for='selecttd1'></label></td>";
echo "<td><div class='input-field col s8'><input  data-type='product_code'  id='itemCode_1'  name='itemCode[]' class='autocomplete_txtdoor resetThis'   type='text' required autocomplete='off' placeholder='Code' required='required' placeholder='CODE' /></div></td>";

echo "<td><div class='input-field col s8'><input type='text' data-type='pro_name' name='itemDesc[]' id='itemDesc_1' class='resetThis autocomplete_txtdoor' autocomplete='off' required='required' placeholder='DESC' ></div></td>";
echo "<td><div class='input-field col s8'><input name='size[]'   id='size_1' type='text' class='resetThis changesNo_dr' required='required' placeholder='Size'/></div></td>";

echo "<td><div class='input-field col s8'>
<input type='text' name='itemQtyd[]' id='itemQtyd_1' class='resetThis changesNo_dr' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required='required' placeholder='QTY'> 
<input type='hidden' name='itemQtyds[]' id='itemQtyds_1' class='resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;'> <input name='cat_name[]' class='resetThis' id='cat_name_1' type='hidden'/>
 <input name='sub_catname[]' class='resetThis' id='sub_catname_1' type='hidden'/>
 <input name='brand[]' class='resetThis' id='brand_1' tabindex='1' type='hidden'  /><input name='units[]'  class='units resetThis' id='units_1' tabindex='1' type='hidden' required placeholder='units' /></div></td>";
echo "<td><div class='input-field col s8'><input type='text' name='itemQty[]' id='itemQty_1' class='changesNo_dr resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required='required' placeholder='CFT'> </div></td>";

echo "<td><div class='input-field col s8'><input type='text' name='itemPrice[]' id='itemPrice_1' class='changesNo_dr resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required='required' placeholder='Price' /></div></td>";
echo "<td><div class='input-field col s8'><input type='text' name='itemLineTotal[]' id='itemLineTotal_1' class='resetThis totalLinePricedr' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required='required' placeholder='Total'></div></td>";
echo "</tr>";
echo "</tbody>";
echo "</table>";
echo "<table class='table table-full'><tr>";
echo "<td class='white'><button class='btn btn-success addmore_dr' type='button'>+ Add</button> <button class='btn btn-danger delete_dr' type='button'>- Delete</button></td>";
echo "<td  class='white'></td>";
echo "<td class='white'></td>";
echo "<td class='white'></td>";
echo "<td class='cyan white-text'>Subotal</td>";
echo "<td class='cyan strong white-text'><div class='input-field col s8 '><input type='text'  readonly class='resetThis' id='subTotal' name='subTotal' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' placeholder='Subtotal' required='required' style='border-bottom:1px solid #fff; color:#fff; text-transform:uppercase; margin: 0 0 5px 0;' /></div></td>";
echo "</tr>";
echo "<tr>";
echo "<td class='white'></td>";
echo "<td  class='white'></td>";
echo "<td class='white'></td>";
echo "<td class='white'></td>";
echo "<td class=''>Vat</td>";
echo "<td class='strong'><div class='input-field col s8 '><input type='text' class='resetThis' id='vatdr' name='vat' placeholder='VAT' onkeypress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required='required'> <input type='hidden' class='resetThis' id='taxAmountdr' placeholder='Tax'  onkeypress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;'></div></td>";
echo "</tr>";
echo "<tr>";
echo "<td class='white'></td>";
echo "<td  class='white'></td>";
echo "<td class='white'></td>";
echo "<td class='white'></td>";
echo "<td class='cyan white-text'>Grand Total</td>";
echo "<td class='cyan strong white-text'><div class='input-field col s8'><input type='text' class='resetThis' id='totalAftertaxdr' name='totalAftertax' placeholder='Grand Total' onkeypress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' readonly='' required='required'></div></td>";
echo "</tr>"; 
echo "</table></div>";
echo "</div>";
echo "<div class='invoice-footer'>";
echo "<div class='row'>";
echo "<div class='col12 s12 m12 l6 leftspace lineheight'>";
echo "<div class='row'><b>Term and Condition:</b><span class='input-field col s9'>";
echo "</span></div>";
echo "<div class='row'><div class='col s2'>Tin No </div> <div class='input-field col s2 left'><input type='text' name='tinno' id='tinno'  class='resetThis' style='width:auto' placeholder='Tin No' readonly required/> </div></div>";	
echo "<div class='row'><div class='col s2'>CST No </div><div class='input-field col s2 left'><input type='text' name='cstno' id='cstno'  class='resetThis' style='width:auto' placeholder='CST No' readonly required/> </div></div>";
echo "<div class='row'><div class='col s2'>Payment </div> <div class='input-field col s2 left'><input type='text' name='payment' id='payment'  class='resetThis' placeholder='Payment' required/> </div> </div>";
echo "<div class='row'><div class='col s2'>Delivery</div><div class='input-field col s2 left'><input type='text' name='place' id='place'   style='width:auto' placeholder='Place' class='resetThis' required /></div></div>";
echo "<div class='row'><div class='col s2'>Loading & Vat</div><div class='resetThis input-field col s2 left'><input type='text' name='vat_o' id='vat_o'   style='width:auto'  placeholder='VAT'  class='resetThis' /></div></div>";
echo "<div class='row'><div class='col s2'>Please Supply by</div>   <div class='input-field col s2  date form_date'> <input size='16' type='text' value='' id='ddate' name='ddate' placeholder='Delivery Date'>
<span class='add-on'><i class='icon-remove'></i></span><span class='add-on'><i class='icon-th'></i></span>
</div><div class='col s1  textaligncenter'>at our</div><div class='col s2'><input type='text' name='place1' id='place1'  class='resetThis' style='width:auto' required placeholder='Place'/> </div> <div class='col s1 textaligncenter'>Site</div></div>";
echo "<div class='row'><div class='col s2'>Transportation </div> <div class='input-field col s2 left'><input type='text' name='tpwords' id='tpwords'  class='resetThis' style='width:auto' placeholder='Transportation'/> </div></div>";	
echo "<div class='row'><b> <div class='col s2'>Site contact Person </div><div class='input-field col s2 left'><input type='text' name='contactname' id='contactname'  class='resetThis' style='width:auto' placeholder='Site Person' required/></div><div class='col s1' style='padding-left:40px;'>Mobile :</div>     <div class='input-field col s2 left'><input type='text' name='mobilno' id='mobilno'  class='resetThis' placeholder='Mobile No' required/></div> </b></div>
<div class='row'><div class='input-field col s2'><b>Site address </b></div>";
echo "<div class='input-field col s2 left'><b><input type='text' name='projectname' id='projectname'  class='resetThis' style='width:auto; font-weight:bold' placeholder='Project Name' readonly required /></b></div></div>";
echo "<div class='row'><div class='input-field col s4 left'><textarea name='address' id='address' value='resetThis' class='resetThis' readonly placeholder='address' style='height:80px' required></textarea><br />";
echo "<input type='hidden' name='project_id' id='project_id'  class='resetThis' style='width:auto' required /></div></div></p></div>";
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
echo "<div class='row'><div class='col s2 right'><button type='submit' class='btn-floating btn-large waves-effect waves-light pink' title='submit'><i class='material-icons medium'>done</i>></button> <a class='btn-floating btn-large waves-effect waves-light  pink' title='Cancel' id='clear'><i class='material-icons medium'>clear</i></a></div></div>";
    echo "<input type='hidden' name='city' id='city' style='width:auto' placeholder='city' readonly  class='resetThis' />"; 
}

 ?>