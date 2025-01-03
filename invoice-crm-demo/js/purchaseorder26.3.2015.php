<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

<title>Work Process System</title>

 <link rel="stylesheet" type="text/css" href="css/easyui.css">
	<link rel="stylesheet" type="text/css" href="css/icon.css">
	<link rel="stylesheet" type="text/css" href="css/color.css">
    
	<link rel="stylesheet" type="text/css" href="css/demo.css">
     <link rel="stylesheet" type="text/css" href="jquery.autocomplete.css" />

    <script type="text/javascript" src="http://code.jquery.com/jquery-1.6.min.js"></script>
	<script type="text/javascript" src="http://www.jeasyui.com/easyui/jquery.easyui.min.js"></script>
   <script type="text/javascript" src="jquery-1.7.min.js"></script>
        <script type='text/javascript' src='jquery-1.9.1.js'></script>
                <script type='text/javascript' src='jquery.autocomplete.js'></script>
             
                  <script type="text/javascript">
				 $(document).ready(function(){
				 $(document).ready(function(){
											
 $(document).ready(function(){
  $("#itemDesc").autocomplete({
        source: 'ajax.php', // The source of the AJAX results
        minLength: 1, // The minimum amount of characters that must be typed before the autocomplete is triggered
        focus: function( event, ui ) { // What happens when an autocomplete result is focused on
            $("#itemDesc").val( ui.item.value );
            return false;
      },
      select: function ( event, ui ) { // What happens when an autocomplete result is selected
          $("#itemDesc").val( ui.item.value );
           }
  });
});
 							
   
 
			  $('#company').keyup(function() {
	 alert(val($(this).val()));
         
     callvendor();
            });
		  $("#company").keypress(function(event) {
         $("#kpress").html(++kpress);
        });
   
		 

});
				 
</script>
                    
                    
         <script type="text/javascript">
		 	  
    function callvendor()
   {
	
      var xmlhttp;
 
      if (window.XMLHttpRequest)
      {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
      }
      else
      {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
      }	
 
      xmlhttp.onreadystatechange = function() {
        if(xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
			var str =  xmlhttp.responseText;
var res = str.split("|");
           document.getElementById('ven_add').value = res[1]+res[2]+res[3]+res[5];
    // document.getElementById('ven_add2').value = res[2];
       //document.getElementById('ven_city').value = res[3];
	  
      
	  // document.getElementById('ven_pincode').value = res[5]; 

		 document.getElementById('ven_id').value = res[0]; 
		  document.getElementById('ven_contactperson').value = res[4]; 
        }
      }
	  
 
      xmlhttp.open("GET","fill_form.php?company=" +document.getElementById('company').value);
      xmlhttp.send();
  }	
		 </script>
                
         
                
                <!-- Call PO No -->
   
    <script type="text/javascript">
   function callpono()
   {
	
      var xmlhttp;
 
      if (window.XMLHttpRequest)
      {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
      }
      else
      {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
      }	
 
      xmlhttp.onreadystatechange = function() {
        if(xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
			$result = xmlhttp.responseText;
          document.getElementById('po_no').value = $result;
		  document.getElementById('po_no1').value = document.getElementById('po_no').value ;
		 
        }
      }
	  
 
      xmlhttp.open("GET","get_pono.php");
      xmlhttp.send();
  }
</script>

<script language="JavaScript">// change to text/javascript or even remove, no effect
window.onload = function() {
  callpono();
  
};
</script>
      
      <!-- projectlist -->
  <script type="text/javascript">

function projectlist(str1)
   {
	
      var xmlhttp;
 
      if (window.XMLHttpRequest)
      {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
      }
      else
      {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
      }	
 
      xmlhttp.onreadystatechange = function() {
        if(xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
      var str1 =  xmlhttp.responseText;
var res1= str1.split("|");
    document.getElementById('project_id').value = res1[0];
  document.getElementById('projectname').value = res1[1];
	  
      
	document.getElementById('place').value = res1[2]; 
	document.getElementById('place1').value = res1[2]; 

	 document.getElementById('tinno').value = res1[3]; 
		 document.getElementById('cstno').value = res1[4]; 
		  	 document.getElementById('address').value = res1[5]; 

	 document.getElementById('contactname').value = res1[6]; 
		document.getElementById('mobilno').value = res1[7]; 
		 document.getElementById('payment').value = res1[8]; 
        }
      }
	  
 alert(str1);
      xmlhttp.open("GET","get_projectlist.php?project_name="+str1, true);
      xmlhttp.send();
  }
</script>    
  <!-- vendorlist -->
  <script type="text/javascript">
  
function vendorlist(str2)
   {
	
      var xmlhttp;
 
      if (window.XMLHttpRequest)
      {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
      }
      else
      {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
      }	
 
      xmlhttp.onreadystatechange = function() {
        if(xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
      var str2 =  xmlhttp.responseText;
var res2= str2.split("|");
    document.getElementById('ven_id').value = res2[0];
  document.getElementById('ven_add').value = res2[1]+res2[2]+res2[3]+res2[4];
	   document.getElementById('ven_contactperson').value = res2[5]; 
        }
      }
	  
 alert(str2);
      xmlhttp.open("GET","get_vendorlist.php?vendor_name="+str2, true);
      xmlhttp.send();
  }
</script> 

<style type="text/css">
body
{margin:0px;
font-family: 'Raleway', sans-serif;
font-size:12px}
	textarea {
				
				vertical-align: top;
				width: 200px;
			}
			textarea:focus {
				outline-style: solid;
				outline-width: 1px;
			}
#space
{
	
	height:8px;

	
}
#tab_logic
{
	border:#DDD 1px solid;
}
.pur_lbl {width:150px !important; height:30px; display:inline-block}
  body
{
    counter-reset: Serial;           /* Set the Serial counter to 0 */
}

table
{
    border-collapse: separate;
}

tr td:first-child:before
{
  counter-increment: Serial;      /* Increment the Serial counter */
  content:  counter(Serial); /* Display the counter */
}

</style>
</head>

<body>
<div id="container">
<div id="head" style="width:100%; position:relative; margin-top:0px; height:60px; background-color:#769ea6">
<a href="index.html#"><img src="images/logo.png" alt=""></a>
</div>
</div>
<div id="space"></div>
<div id="content" style="position:relative; width:1024px; margin:0 auto">
<form autocomplete="on" method="post" id="fm" action="purchase_insertrow.php">

<div>
<div id="res" style="display:none"></div>
<div align="right"><input type="text" name="po_date" id="po_date" class="textbox"  placeholder="Date" /><br /></div>

<b>To:</b><br /><br />
 <select name="vendor_name" id="vendor_name" onChange="vendorlist(this.value)">
<?php
require('connect.php');
$result = mysql_query("SELECT ven_compname FROM  vendor_tbl",$conn);
  echo "<option selected value='select'>SELECT</option>";
  while ($row = mysql_fetch_array($result)) {
    echo "<option  value='".$row['ven_compname']."'>" . $row['ven_compname'] . "</option>";
   }

   
 ?>
</select><br />
<textarea name="ven_add" id="ven_add" value="" ></textarea>


<input type="text" name="ven_id" id="ven_id" placeholder="vendor-id" class="textbox"  /><br />


<center>
  <b>Kind Attention: 
  <input type="text" name="ven_contactperson" id="ven_contactperson"  class="textbox" style="font-weight:bold"   /></b></center>

<b>Sub: PO for <input type="text" name="po_no" id="po_no" readonly class="textbox"  placeholder="PO No"/></b><br />
We are pleased to place the Purchase order as per the details mentioned below for our Project <select name="project_name" id="project_name" onChange="projectlist(this.value)">
<?php
require('connect.php');
$result = mysql_query("SELECT distinct project_name FROM project_details",$conn);
  echo "<option selected value='select'>SELECT</option>";
  while ($row = mysql_fetch_array($result)) {
    echo "<option  value='".$row['project_name']."'>" . $row['project_name'] . "</option>";
   }

   
 ?>
</select><br />
the address and the contact person are mentioned below.
</div>
<div id="space"></div>
<div>	
<div class="panel-title">Category</div>
<table  class="datagrid-htable" cellpadding="0" cellspacing="0"  width="100%"  id="itemsTable" border="1">

            <tr>
                    <th colspan="7" class="panel-header" height="20px"><div class="panel-title">Purchase Order</div></th>
                    </tr>
                      <tr>
                    <th colspan="7" id="toolbar" class="datagrid-toolbar" height="20px"><a id="addRowBtn" plain="true" class="easyui-linkbutton l-btn l-btn-small l-btn-plain" iconCls="icon-add">Add New</a></th></tr>
                 <tr>
                
						<th class="datagrid-header" ><b>S.No.</b></th>
						<th class="datagrid-header">
					<b>Description</b> 
		    </th>
						<th class="datagrid-header" height="18">
	      <b>Qty</b></th>
						<th class="datagrid-header">
							<b>Rate per Product in Rs</b><br />
						</th>
                        <th class="datagrid-header">
							<b>Vat in Rs</b><br />
						</th>
                        <th class="datagrid-header">
							<b>Amount in Rs</b>
						</th>
                        <th class="datagrid-header">
						
						</th>
					</tr>
       
    
                            <tr class="item-row">

                    
                    <td class="datagrid-body">&nbsp;</td>
                    <td class="datagrid-body"><input  data-type="productName" name="itemDesc[]" class="form-control textbox autocomplete_txt" id="itemDesc"  type="text"></td>
                    <td class="datagrid-body"><input name="itemQty[]" class="form-control textbox" id="itemQty" tabindex="2" type="text">
                    </td>
                    <td class="datagrid-body">
                  
             <input name="itemPrice[]" class="form-control textbox" id="itemPrice" type="text" value=" "></td>
                    <td class="datagrid-body">
                       
                        <input name="itemVat[]" class="form-control textbox" id="itemVat" type="text">
                    </td>	
                    <td class="datagrid-body">
                     
                        <input name="itemLineTotal[]" class="form-control textbox" id="itemLineTotal" readonly="readonly" type="text" style="border:none">
                      
                    </td>
                    <td class="datagrid-body">                                          </td>
                </tr>
            
        
        </table>
<div class="col-md-6 text-right"><h5>Sub Total:</h5></div>
            <div class="col-md-6 text-left">
                  <h5 id="subTotal">Rs</h5>
                  <input placeholder="Sales Tax" name="tax" id="tax" class="form-control input-sm" type="text"><span class="input-group-addon">%</span></div>
                   <div class="col-md-6 text-left" id="salesTax"><h5>
                    Rs.</h5></div>
                       <div class="col-md-6 text-right"><h4>Grand Total:</h4></div>
            <div class="col-md-6 text-left">
                   Rs. <input name="grandTotal" class="form-control textbox" id="grandTotal" readonly="readonly" type="text" style="border:none">
               
            </div>
            <table width="100%"> </table>
             <!-- End terms block-->
            <div>
              <p><b>Term and Condition:</b><br />
              
          <p> <span class="pur_lbl"> Tin No : </span><input type="text" name='tinno' id="tinno"  class="form-control textbox" style="width:auto"/><br /></p>	
            <span class="pur_lbl"> CST No: </span><input type="text" name='cstno' id="cstno"  class="form-control textbox" style="width:auto"/>	<br />
             <span class="pur_lbl">Payment : </span> <input type="text" name='payment' id="payment"  class="form-control textbox" style="width:auto"/>  <br />  
             <span class="pur_lbl">Delivery : </span>Door Dellivery to our<input type="text" name='place' id="place"  class="form-control textbox" style="width:auto"/> Site  <br /> 
            <span class="pur_lbl"> Loading & Vat : </span> <input type="text" name='vat_o' id="vat_o"  class="form-control textbox" style="width:auto"/><br />
           <span class="pur_lbl"> Please Supply by </span> <input type="text" name='ddate' id="ddate"  class="form-control textbox" style="width:auto" placeholder="Delivery Date"/> at our <input type="text" name='place1' id="place1"  class="form-control textbox" style="width:auto"/> Site<br />
            The above price is net price inclusive of all Taxes and Transport Charges.
The Other terms and conditions remains the same as per your Quote. <br />
 <span class="pur_lbl"><b>Site contact Person 	:  </b>  </span>
<input type="text" name='contactname' id="contactname"  class="form-control textbox" style="width:auto"/> <input type="text" name='mobilno' id="mobilno"  class="form-control textbox"/> <br />
<span class="pur_lbl"><b> Site address 	:</b> </span>
<input type="text" name='projectname' id="projectname"  class="form-control textbox" style="width:auto; font-weight:bold"/>
<br />
<span class="pur_lbl">&nbsp;</span><textarea name="address" id="address" value="" ></textarea><br />
<input type="text" name='project_id' id="project_id"  class="form-control textbox" style="width:auto"/>

              </p>
Thanking you, <br />
Yours Sincerely<br />
<img src="images/anisa_digi.jpg" width="112" height="90"  /><br />
Anisa Fathima.H
<br />Purchase Manager
</div>
            <!-- End terms block-->
            <div>
	
</div>
    </div>
   <input type="submit" value="Submit" name="subm" id="sub_m" />
  </form>
  
    
</div>
<div id="space"></div>
<div id="footer" style="background-color:#242323; position:relative; bottom:0px; width:100%; height:80px">
<div style="position:relative; height:5px; background-color:#bfbfbf" ></div>
South India Shelters Pvt.Ltd.
</div>
<script type="text/javascript" src="js/calc/jquery.js"></script>

<!-- Needed Assets -->
<!-- Our jQuery Script to make everything work -->
<script type="text/javascript" src="js/calc/jquery-ui.js"></script>
<script type="text/javascript" src="sample/invoice/js/jquery.min.js"></script>
<script type="text/javascript" src="sample/invoice/js/jquery-ui.min.js"></script>

<script type="text/javascript" src="js/calc/mioinvoice-scripts.js"></script>
<script type="text/javascript" src="js/calc/general-scripts.js"></script>

   <script src="autosize.js"  type='text/javascript'></script>
	<script>
		autosize(document.querySelectorAll('textarea'));
	</script>
   
</body>
</html>
