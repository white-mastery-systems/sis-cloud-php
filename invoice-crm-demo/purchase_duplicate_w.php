<!DOCTYPE html>
<html lang="en">
<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="msapplication-tap-highlight" content="no">

  <title>Work Process</title>

  <meta name="msapplication-TileColor" content="#00bcd4">


<!-- CORE CSS-->
  
  <link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection">
  <link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection">
    <!-- Custome CSS-->    
    <link href="css/custom-style.css" type="text/css" rel="stylesheet" media="screen">


  <!-- INCLUDED PLUGIN CSS ON THIS PAGE -->
      <link rel="Stylesheet" href="css/bootstrap.css"  media="screen"/>
     <!-- <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"> -->
      <link href="css/materialfamily.css" type="text/css" rel="stylesheet" media="screen">
 
    	 <link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.4.custom.css">
          
   <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
      <script type="text/javascript" src="https://code.jquery.com/jquery-1.8.3.js"></script>
      
     
      <script type="text/javascript">          var j = jQuery.noConflict();</script>

     <script type='text/javascript' src='http://code.jquery.com/jquery-1.7.js'></script>

 <script type='text/javascript'>//<![CDATA[ 
$(function() {
     var pgurl = window.location.href.substr(window.location.href.lastIndexOf("/")+1);
     $("#cssmenu ul a").each(function(){
          if($(this).attr("href") == pgurl || $(this).attr("href") == '' )
          $(this).addClass("active");
     })
});

</script>


<!-- Date -->
  <script>
 $(function() 
 { 
      $( "#ddate" ).datepicker({
      changeMonth:true,
      changeYear:true,
      yearRange:"-100:+0",
      dateFormat:"dd MM yy"
     });
 });
</script>

<!-- css3-mediaqueries.js for IE8 or older -->
<!--[if lt IE 9]>
	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
<![endif]-->
<!-- Purchase New Jquery -->
  
        
   
        
                    
     
         
                
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
	document.getElementById('place2').value = res1[2]; 

	 document.getElementById('tinno').value = res1[3]; 
		 document.getElementById('cstno').value = res1[4]; 
		  	 document.getElementById('address').value = res1[5]; 

	 document.getElementById('contactname').value = res1[6]; 
		document.getElementById('mobilno').value = res1[7]; 
		 document.getElementById('payment').value = res1[8]; 
		 document.getElementById('city').value = res1[9];
        }
      }
	  

      xmlhttp.open("GET","get_projectlist.php?project_name="+str1, true);
      xmlhttp.send();
  }
</script>    
  
<script type="text/javascript" >
$(document).ready(function()
{
$("#notificationLink").click(function()
{
$("#notificationContainer").fadeToggle(300);
$("#notification_count").fadeOut("slow");
return false;
});

//Document Click
$(document).click(function()
{
$("#notificationContainer").hide();
});
//Popup Click
$("#notificationContainer").click(function()
{
return false;
});

});
</script>



<script type='text/javascript' src='https://code.jquery.com/jquery-1.9.1.js'></script>





      
<?php
//echo $po_no;
$po_no=$_GET['po_no'];
require('connect.php');

$result1 = mysql_query("SELECT * FROM  purchase_master where po_no=$po_no",$conn);
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
	 ?>



</head>

<body>
   <!-- START HEADER -->
  <header id="header" class="page-topbar">
        <!-- start header nav-->
        <div class="navbar-fixed">
            <nav class="cyan">
                <div class="nav-wrapper">                    
                    
                    <ul class="left">                      
                      <li><h1 class="logo-wrapper"><a href="#" class="brand-logo darken-1"><img src="images/materialize-logo.png" alt="materialize logo"></a> <span class="logo-text">Materialize</span></h1></li>
                    </ul>
                    <div class="header-search-wrapper hide-on-med-and-down">
                        <i class="mdi-action-search active"></i>
                        <input type="text" name="Search" class="header-search-input z-depth-2" placeholder="Explore Materialize">
                    </div>
                    <ul class="right hide-on-med-and-down">                        
                        <li><a href="javascript:void(0);" class="waves-effect waves-block waves-light toggle-fullscreen"><i class="mdi-action-settings-overscan"></i></a>
                        </li>
                        <li><a href="javascript:void(0);" class="waves-effect waves-block waves-light"><i class="mdi-navigation-apps"></i></a>
                        </li>                        
                        <li><a href="javascript:void(0);" class="waves-effect waves-block waves-light"><i class="mdi-social-notifications"></i></a>
                        </li>                        
                        <li><a href="#" data-activates="chat-out" class="waves-effect waves-block waves-light chat-collapse"><i class="mdi-communication-chat"></i></a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <!-- end header nav-->
  </header>
  <!-- END HEADER -->

  <!-- //////////////////////////////////////////////////////////////////////////// -->

  <!-- START MAIN -->
  <div id="main">
    <!-- START WRAPPER -->
    <div class="wrapper">

      <!-- START LEFT SIDEBAR NAV-->
      <aside id="left-sidebar-nav">
        <ul id="slide-out" class="side-nav fixed leftside-navigation ps-container ps-active-y" style="width: 240px;">
            <li class="user-details cyan darken-2">
                <div class="row">
                    <div class="col col s4 m4 l4">
                        <img src="images/avatar.jpg" alt="" class="circle responsive-img valign profile-image">
                    </div>
                    <div class="col col s8 m8 l8">
                        
                        <a class="btn-flat dropdown-button waves-effect waves-light white-text profile-btn" href="#" data-activates="profile-dropdown">John Doe<i class="mdi-navigation-arrow-drop-down right"></i></a><ul id="profile-dropdown" class="dropdown-content">
                            <li><a href="#"><i class="mdi-action-face-unlock"></i> Profile</a>
                            </li>
                            <li><a href="#"><i class="mdi-action-settings"></i> Settings</a>
                            </li>
                            <li><a href="#"><i class="mdi-communication-live-help"></i> Help</a>
                            </li>
                            <li class="divider"></li>
                            <li><a href="#"><i class="mdi-action-lock-outline"></i> Lock</a>
                            </li>
                            <li><a href="#"><i class="mdi-hardware-keyboard-tab"></i> Logout</a>
                            </li>
                        </ul>
                        <p class="user-roal">Administrator</p>
                    </div>
                </div>
            </li>
            <li class="bold"><a href="#dashboard" class="waves-effect waves-cyan"><i class="mdi-action-dashboard"></i> Dashboard</a>
            </li>
            
            <li class="bold"><a href="#user" class="waves-effect waves-cyan"><i class="mdi-editor-insert-invitation"></i> User</a>
            </li>
            <li class="no-padding">
                <ul class="collapsible collapsible-accordion">
                    <li class="bold"><a href="#workassign" class="collapsible-header waves-effect waves-cyan"><i class="mdi-action-invert-colors"></i> Workassign</a></li>
                    <li class="bold"><a  href="#timescale" class="collapsible-header  waves-effect waves-cyan"><i class="mdi-image-palette"></i>Timescale</a></li>
                    <li class="bold"><a href="#order" class="collapsible-header  waves-effect waves-cyan"><i class="mdi-editor-border-all"></i>Order</a></li>
                    <li class="bold active"><a  href="#Clientrequirementclass=" class="collapsible-header  waves-effect waves-cyan active"><i class="mdi-social-pages"></i> Client Requirement</a></li>
                    <li class="bold"><a  href="#inventory" class="collapsible-header  waves-effect waves-cyan"><i class="mdi-action-shopping-cart"></i>Inventory</a></li>
                    <li class="bold"><a href="#inventoryuse" class="collapsible-header  waves-effect waves-cyan"><i class="mdi-image-image"></i> Inventoryuse</a></li>
                   <li class="bold"><a href="#status" class="collapsible-header waves-effect waves-cyan"><i class="mdi-editor-insert-chart"></i>Status</a></li>
                    <li class="bold"><a href="#quality" class="collapsible-header waves-effect waves-cyan"><i class="mdi-editor-insert-chart"></i>Quality</a></li>
                    <li class="bold"><a href="#delay" class="collapsible-header waves-effect waves-cyan"><i class="mdi-editor-insert-chart"></i>Delay</a></li>
                </ul>
            </li>
            <li class="li-hover"><div class="divider"></div></li>
            <li class="li-hover"><p class="ultra-small margin more-text">MORE</p></li>
            <li><a href="#"><i class="mdi-image-grid-on"></i>Text</a>
            </li>
            <li><a href="#"><i class="mdi-editor-format-color-fill"></i>Text</a>
            </li>
            <li><a href="#"><i class="mdi-communication-live-help"></i>Text</a>
            </li>
            <li><a href="#"><i class="mdi-action-swap-vert-circle"></i>Text</a>
            </li>                    
            <li class="li-hover"><div class="divider"></div></li>
            <li class="li-hover"><p class="ultra-small margin more-text">Samp Text</p></li>
           </ul>
        <a href="#" data-activates="slide-out" class="sidebar-collapse btn-floating btn-medium waves-effect waves-light hide-on-large-only cyan"><i class="mdi-navigation-menu"></i></a>
      </aside>
      <!-- END LEFT SIDEBAR NAV-->

      <!-- //////////////////////////////////////////////////////////////////////////// -->

      <!-- START CONTENT -->
      <form method="post" action="purchase_insertrow_w.php">
      <section id="content">
       <!-- Page disp Area -->      
<div id='breadcrumbs-wrapper' class='grey lighten-3'>
      <div class='container-title'>
             <div class='row'>
               <div class='col s12 m12 l12'>
                 <h5 class='breadcrumbs-title'>Purchase Duplicate</h5>
                
                <div  style='position:relative; top:40px;width:auto; padding-top:10px' class='left leftspace'><a class='btn-floating btn-large waves-effect waves-light' href="order.php"><i class='material-icons'>arrow_back</i></a></div>
              </div>
            </div>
          </div>
        </div>
      
        <div class='container'>
         <div id='invoice'>
            <div class='invoice-header'>
               <div class='row section'>
                <div class='col12 s12 m12 l6 right rightspace'><img src='images/SISLOGO.png' />
                </div>
 </div>
</div>
<div class='invoice-lable'>
<div class='row'>
<div class='col12 s12 m6 l3 cyan'>
 
 
<div class='col s12 m6 l6'>&nbsp;</div>
<div class='col s12 m6 l6'><div class="input-field col s4 right rightspace"><input type="text" name="po_date" id="po_date"  placeholder="Date" value="<?php echo date('d.m.Y'); ?>" /> </div></div>

</div>

</div>
<div class="row">
<div class='col12 s12 m6 l3 cyan'>
 
 	<?php

	$result2 = mysql_query("SELECT * FROM  vendor_tbl where ven_id=" .$ven_id,$conn);
	if($row2= mysql_fetch_array($result2))
	 {
		$ven_id1 = $row2['ven_id'] ;
	    $ven_compname = $row2['ven_compname'] ;
	    $ven_add1=$row2['ven_add1'];
		$ven_add2=$row2['ven_add2'];
	    $ven_city=$row2['ven_city'];
		$ven_pincode=$row2['ven_pincode'];
		$ven_state=$row2['ven_state'];
		$ven_country=$row2['ven_country'];
		$ven_contactperson=$row2['ven_contactperson'];
		}
		else
		{
			echo "Error";
	    }
	    }
	 ?>
<div class='col s12 m6 l6'><div class="input-field col s5 left leftspace"><input type="text" id="vendor_name" name="vendor_name" value="<?php echo $row2['ven_compname']  ?>" required /> </div></div>
<div class='col s12 m6 l6 right rightspace'><div class="input-field col s4 left leftspace">&nbsp;</div></div>
</div>
</div>

<div class="row">
<div class='col12 s12 m6 l3 cyan'>
 
 
<div class='col s12 m6 l6'><div class="input-field col s5 left leftspace"><textarea name="ven_add" id="ven_add"><?php echo $row2['ven_add1'] ?></textarea>
<input type="hidden" name="ven_id" id="ven_id" placeholder="vendor-id" value="<?php echo $row2['ven_id']; ?>"  /><br /></div></div>
<div class='col s12 m6 l6 right rightspace'><div class="input-field col s8 left leftspace">&nbsp;</div></div>
</div>
</div>
<div class="row">
<div class="col s12">
<center>
<div class="input-field col s2"> <b>Kind Attention: </b></div>
<div class="input-field col s2"> 
  <input type="text" name="ven_contactperson" id="ven_contactperson" value="<?php echo $ven_contactperson;?>" /></div>
  </center>
  </div>
</div>
<div class="row leftspace">
<div class='col12 s12 m6 l3 cyan'>
 <div class="col s1">Sub: PO for</div>
 
<div class='col s12 m6 l6'><div class="input-field col s2 left"><input type="text" name="po_no" id="po_no" readonly  class="validate"> </div></div>
<div class='col s12 m6 l6 right rightspace'><div class="input-field col s8 left leftspace">&nbsp;</div></div>
</div>
</div>
<div class="row  leftspace">
<div class="col s7 left">
<?php

		$result = mysql_query("SELECT * FROM project_details where project_id=" .$project_id,$conn);
	if($row= mysql_fetch_array($result))
	 {
		 $project_id = $row['project_id'];
	    $project_name = $row['project_name'] ;
	    $place=$row['place'];
		$tinno=$row['tinno'];
	    $cstno=$row['cstno'];
		$address=$row['address'];
		$contactname=$row['contactname'];
		$mobilno=$row['mobilno'];
		$payment=$row['payment'];
			
		}
		else
		{
			echo "Error";
	 }

	 ?>
We are pleased to place the Purchase order as per the details mentioned below for our Project</div> <div class="col s2 left">
<select name="project_name" id="project_name" onChange="projectlist(this.value)">
  <?php
require('connect.php');
$result = mysql_query("SELECT distinct project_name FROM project_details",$conn);

  echo "<option selected value='select'>SELECT</option>";
  while ($row = mysql_fetch_array($result)) {
	  	echo "<option".(($project_name == $row['project_name'])? ' selected="selected"' : '').">" .$row['project_name']. "</option>";

	
   }

   
 ?>
</select>

   
 &nbsp; </div> <div class="col s2 left">
         <script>
             $('#project_name').change(function () { //Basically saying when the first select box changes values run the function below.
                 var project_name = $(this).val(); // Grab the value of the selection to send to the select-request.php via ajax
                 $.post('blockname.php', { project_name: project_name }, function (data) { // Run a ajax request and send the var make as a post variable named "make" and return the info in the "data" var.
                     $('#blockname').html(data); // Have jquery change the html within the second select box with the "data" we got back from the ajax request.
                 });
             });
</script>

 <select name="blockname" id="blockname" required="required">  <?php
require('connect.php');
$result = mysql_query("SELECT distinct blockname FROM project_details where project_name='$project_name_m",$conn);

  echo "<option selected value='select'>SELECT</option>";
  while ($row = mysql_fetch_array($result)) {
	  	echo "<option".(($block == $row['blockname'])? ' selected="selected"' : '').">" .$row['blockname']. "</option>";

	
   }

   
 ?>
</select><br /></div>
<div class="col s6 left">
the address and the contact person are mentioned below.</div>

</div>
</div>
<div class='invoice-table'>
<div class='row'>
<div class='col s12 m12 l12'>
<table class='table table-full' id="itemsTable">
<thead>
<tr>
<th><input type="checkbox" id="check_all" class="case" /><label for="check_all"></label></th>
<th><b>Code</b></th>
<th><b>width</b></th>
<th><b>height</b></th>
<th><b>unit</b></th>
<th><b>type</b></th>
<th colspan="2"><b>Material Cost</b></th>
<th colspan="2"><b>Installation Cost</b></th>
<th><b>Amount</b></th>
</tr>
<tr>
<th></th>
<th>&nbsp;</th>
<th></th>
<th></th>
<th></th>
<th></th>
<th><b>Basic</b></th>
<th><b>Total</b></th>
<th><b>Basic</b></th>
<th><b>Total</b></th>
<th><b>Total</b></th>
</tr>
</thead>
<tbody>
<?php

//echo $po_no;
$productname;
$product_qty;
$ratesperproduct;
$vat;
$Amount;
	$po_date;
	$result_r = mysql_query("SELECT * FROM  purchaseorder_tbl where Po_no=" .$po_no,$conn);
$n=0;
while($row = mysql_fetch_array($result_r))
 {
//	 $po_id=$row['po_id'] ;
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
		$s_total=$row['stotal'];
	
		//$brand=$row['brand'];
		$n+=1;
		
		
   ?>
<tr><td><input type="checkbox" id="selecttd1"  class="case" /><label for="selecttd1"></label></td>
<td><div class="input-field col s8 "> <input  data-type="product_code"  id="itemCode_1"  name="itemCode[]" class="autocomplete_txt resetThis"   type="text"  required autocomplete="off" placeholder="Code" value="<?php echo $product_code ?>" /><input  id="itemBrand_'+i+'"  name="itemBrand[]" class="resetThis" type="hidden"  required autocomplete="off"  /></div></td>
<td><div class="input-field col s8 "> <input name="itemWidth[]" id="itemWidth_1"  type="text"  required autocomplete="off" placeholder="Width"  value="<?php echo $pro_width  ?>" /></div></td>
<td><div class="input-field col s8 "><input name="itemHeight[]" id="itemHeight_1" type="number" required autocomplete="off"  placeholder="Height" value="<?php echo $pro_height  ?>"/></div></td>
<td><div class="input-field col s8 "><input name="itemQty[]"  id="itemQty_1" type="number" class="changesNo" required autocomplete="off" onKeyPress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" placeholder="QTY" value="<?php echo $product_qty  ?>" /> <input name="itemUnit[]"  id="itemUnit_1" type="hidden" required autocomplete="off" value="" /></div></td>
<td><div class="input-field col s8 "><input data-type="pro_name" name="itemDesc[]"  id="itemDesc_1" type="text" class="autocomplete_txt"  required tocomplete="off" placeholder="Type" value="<?php echo $productname ?>" /></div></td>
<td><div class="input-field col s8 "><input name="itemBasic_m[]"  id="itemBasicm_1" type="number" autocomplete="off" class="changesNo" onKeyPress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" placeholder="M Basic" value="<?php echo $m_basic ?>"/></div></td>
<td><div class="input-field col s8 "><input name="itemTotal_m[]" id="itemTotalm_1" readonly="readonly" type="number" autocomplete="off" class="totalLinePrice1" placeholder="B Total" value="<?php echo $m_total ?>" /></div></td>
<td><div class="input-field col s8 "><input name="itemBasic_i[]"  id="itemBasici_1" type="number" autocomplete="off" class="changesNo" onKeyPress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" placeholder="I Basic" value="<?php echo $i_basic  ?>"/></div></td>
<td><div class="input-field col s8 "><input name="itemTotal_i[]" id="itemTotali_1" readonly="readonly" type="number" autocomplete="off" class="totalLinePrice2" Placeholder="I Total" value="<?php echo $i_total ?>" /></div></td>
<td><div class="input-field col s8 "><input name="itemLineTotal[]"  id="itemLineTotal_1" readonly="readonly" type="number" placeholder="Total" autocomplete="off" class="totalLinePrice" onKeyPress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" value="<?php echo $Amount ?>" /></div></td>

</tr>
                
               <?php
			
 }
			 ?>
             </tbody>
</table>
        <table class='table table-full'>
<tr>
<td></td>
<td>
      			<button class="btn btn-success addmore" type="button">+ Add</button></td>
<td>	<button class="btn btn-danger delete" type="button">- Delete</button></td>
<td></td>
<td>BC</td>
<td><div class="input-field col s9 "><input name="basictotal"  class="basictotal" id="basictotal" type="number" required  Placeholder="M Total" value="<?php echo $basictotal ?>"/> </div>	<input type="hidden"  class="itotal" id="itotal" name='itotal' required  value="<?php echo $i_total  ?>" /><input type="hidden" class="" id="subTotal" placeholder="Subtotal" onKeyPress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;"  value="<?php echo $s_total  ?>"  /></td>
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
<td>ED</td>
<td><div class="input-field col s9 "><input type="number"  name="ed" id="ed" placeholder="ED" onKeyPress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" value="<?php echo $ed  ?>"/></div></td>
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
<td><div class="input-field col s9"><input type="number" class="" id="vat" name="vat" placeholder="vat" onKeyPress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" value="<?php echo $tax ?>" /><input type="hidden" class="" id="taxAmount1" placeholder="Tax" onKeyPress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;"><input type="hidden" class="" id="taxAmount" placeholder="Tax" onKeyPress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;"></div></td>
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
<td><div class="input-field col s9"><input type="number" class="" id="tp" name="tp" placeholder="tp" onKeyPress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" value="<?php echo $transportation  ?>"/><input type="hidden" class="" id="tp1" name="tp1" placeholder="Amount Paid" onKeyPress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" value="<?php echo $transportation  ?>" ></div></td>
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
<td><input type="hidden" class="amountDue" id="amountDue" placeholder="Amount Due" onKeyPress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;"></td>
<td>ST</td>
<td><div class="input-field col s9 "><input type="number" class="" name="st" id="st" placeholder="st" onKeyPress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" value="<?php echo $st ?>" /> <input type="hidden" class="" id="taxAmount2" placeholder="Tax2" onKeyPress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;"></div></td>
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
<td><div class="input-field col s9 "><input name="gt"  class="amountDue1 gt" id="gt" type="number" required readonly placeholder="M Total with tax"value="<?php echo $gt  ?>" /></div></td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td class='cyan white-text'><div class="input-field col s9 "><input name="gt1"  class="gt1" id="gt1" type="number" required  readonly placeholder="Installation Total" value="<?php echo $gt1  ?>" /></div></td>
<td class='cyan strong white-text'><div class="input-field col s9 "><input type="number" class="" id="totalAftertax" name="totalAftertax" placeholder="Total" onKeyPress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" readonly value="<?php echo $subtotal  ?>" /></div></td>
</tr>
</table>
</div>
</div>
</div>
<div class='invoice-footer'>
<div class='row'>
<div class='col12 s12 m12 l6 leftspace lineheight'>

<div class="row"><b>Term and Condition:</b></div>
<div class="row"><div class="col s2">Tin No </div> <div class="input-field col s2 left"><input type="text" name='tinno' id="tinno" value="<?php echo $tinno;?>" style="width:auto" placeholder="Tin No"/> </div></div>	
<div class="row"><div class="col s2">CST No </div><div class="input-field col s2 left"><input type="text" name='cstno' id="cstno"  value="<?php echo $cstno;?>" style="width:auto" placeholder="CST No"/> </div></div>
<div class="row"><div class="col s2">Delivery</div><div class="input-field col s2 left"><input type="text" name='place' id="place" value="<?php echo $place;?>"   style="width:auto"/></div></div>
<div class="row"><div class="col s2">Loading & Vat</div><div class="input-field col s2 left"><input type="text" name='vat_o' id="vat_o" value="<?php echo $vat_o;?>"   style="width:auto" required/></div></div>
<div class="row"><div class="col s2">Please Supply by</div> <div class="col s2"><input type="text" name='ddate' id="ddate" value="<?php echo $ddate;?>"  style="width:auto" placeholder="Delivery Date" required/></div> <div class="col s1  textaligncenter">at our</div><div class="col s2"><input type="text" name='place1' id="place1" value="<?php echo $place;?>" style="width:auto"/> </div> <div class="col s1 textaligncenter">Site</div></div>
<div class="row">The above price is net price inclusive of all Taxes and Transport Charges.The Other terms and conditions remains the same as per your Quote.</div><br />
<div class="row"><b> <div class="col s2">Site contact Person </div><div class="input-field col s2 left"><input type="text" name='contactname' id="contactname"  value="<?php echo $contactname;?>" style="width:auto"/></div><div class="col s2 textaligncenter">Mobile :</div>     <div class="input-field col s2 left"><input type="text" name='mobilno' id="mobilno" value="<?php echo $mobilno;?>"/></div> </b></div>
<div class="row"><div class="input-field col s2"><b>Site address </b></div>
<div class="input-field col s2 left"><b><input type="text" name='projectname' id="projectname"  value="<?php echo $project_name;?>"  class="" style="width:auto; font-weight:bold"/></b></div></div>
<div class="row"><div class="input-field col s4 left"><textarea name="address" id="address"  class="" ><?php echo $address;?></textarea><br />
<input type="hidden" name='project_id' id="project_id"  class="" style="width:auto" <?php echo $project_id; ?>/></div></div>
</p>
</div>
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
            
          </div>
   
</div>
<div class="row"><div class="col s2 right"><a class="btn-floating btn-large waves-effect waves-light cyan" title="submit"><i class="material-icons medium">done</i>></a> <a class="btn-floating btn-large waves-effect waves-light" title="Cancel"><i class="material-icons medium">clear</i>></a></div></div>
    </section>
    </form>
      <!-- END CONTENT -->
 <!-- //////////////////////////////////////////////////////////////////////////// -->
      <!-- START RIGHT SIDEBAR NAV-->
      <aside id="right-sidebar-nav">
        <ul id="chat-out" class="side-nav rightside-navigation right-aligned ps-container ps-active-y" style="width: 300px; right: -310px; height: 737px;">
            <li class="li-hover">
            <a href="#" data-activates="chat-out" class="chat-close-collapse right"><i class="mdi-navigation-close"></i></a>
            <div id="right-search" class="row">
                <form class="col s12">
                    <div class="input-field">
                        <i class="mdi-action-search prefix"></i>
                        <input id="Text1" type="text" class="validate">
                        <label for="icon_prefix">Search</label>
                    </div>
                </form>
            </div>
            </li>
            <li class="li-hover">
                <ul class="chat-collapsible" data-collapsible="expandable">
                <li class="active">
                    <div class="collapsible-header teal white-text active"><i class="mdi-social-whatshot"></i>Recent Activity</div>
                    <div class="collapsible-body recent-activity" style="display: block;">
                        <div class="recent-activity-list chat-out-list row">
                            <div class="col s3 recent-activity-list-icon"><i class="mdi-action-add-shopping-cart"></i>
                            </div>
                            <div class="col s9 recent-activity-list-text">
                                <a href="#">just now</a>
                                <p>Jim Doe Purchased new equipments for zonal office.</p>
                            </div>
                        </div>
                        <div class="recent-activity-list chat-out-list row">
                            <div class="col s3 recent-activity-list-icon"><i class="mdi-device-airplanemode-on"></i>
                            </div>
                            <div class="col s9 recent-activity-list-text">
                                <a href="#">Yesterday</a>
                                <p>Your Next flight for USA will be on 15th August 2015.</p>
                            </div>
                        </div>
                        <div class="recent-activity-list chat-out-list row">
                            <div class="col s3 recent-activity-list-icon"><i class="mdi-action-settings-voice"></i>
                            </div>
                            <div class="col s9 recent-activity-list-text">
                                <a href="#">5 Days Ago</a>
                                <p>Natalya Parker Send you a voice mail for next conference.</p>
                            </div>
                        </div>
                        <div class="recent-activity-list chat-out-list row">
                            <div class="col s3 recent-activity-list-icon"><i class="mdi-action-store"></i>
                            </div>
                            <div class="col s9 recent-activity-list-text">
                                <a href="#">Last Week</a>
                                <p>Jessy Jay open a new store at S.G Road.</p>
                            </div>
                        </div>
                        <div class="recent-activity-list chat-out-list row">
                            <div class="col s3 recent-activity-list-icon"><i class="mdi-action-settings-voice"></i>
                            </div>
                            <div class="col s9 recent-activity-list-text">
                                <a href="#">5 Days Ago</a>
                                <p>Natalya Parker Send you a voice mail for next conference.</p>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="active">
                    <div class="collapsible-header light-blue white-text active"><i class="mdi-editor-attach-money"></i>Sales Repoart</div>
                    <div class="collapsible-body sales-repoart" style="display: block;">
                        <div class="sales-repoart-list  chat-out-list row">
                            <div class="col s8">Target Salse</div>
                            <div class="col s4"><span id="sales-line-1"></span>
                            </div>
                        </div>
                        <div class="sales-repoart-list chat-out-list row">
                            <div class="col s8">Payment Due</div>
                            <div class="col s4"><span id="sales-bar-1"></span>
                            </div>
                        </div>
                        <div class="sales-repoart-list chat-out-list row">
                            <div class="col s8">Total Delivery</div>
                            <div class="col s4"><span id="sales-line-2"></span>
                            </div>
                        </div>
                        <div class="sales-repoart-list chat-out-list row">
                            <div class="col s8">Total Progress</div>
                            <div class="col s4"><span id="sales-bar-2"></span>
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="collapsible-header red white-text"><i class="mdi-action-stars"></i>Favorite Associates</div>
                    <div class="collapsible-body favorite-associates">
                        <div class="favorite-associate-list chat-out-list row">
                            <div class="col s4"><img src=".images/avatar.jpg" alt="" class="circle responsive-img online-user valign profile-image">
                            </div>
                            <div class="col s8">
                                <p>Eileen Sideways</p>
                                <p class="place">Los Angeles, CA</p>
                            </div>
                        </div>
                        <div class="favorite-associate-list chat-out-list row">
                            <div class="col s4"><img src="images/avatar.jpg" alt="" class="circle responsive-img online-user valign profile-image">
                            </div>
                            <div class="col s8">
                                <p>Zaham Sindil</p>
                                <p class="place">San Francisco, CA</p>
                            </div>
                        </div>
                        <div class="favorite-associate-list chat-out-list row">
                            <div class="col s4"><img src="images/avatar.jpg" alt="" class="circle responsive-img offline-user valign profile-image">
                            </div>
                            <div class="col s8">
                                <p>Renov Leongal</p>
                                <p class="place">Cebu City, Philippines</p>
                            </div>
                        </div>
                        <div class="favorite-associate-list chat-out-list row">
                            <div class="col s4"><img src="./Page Blank _ Materialize - Material Design Admin Template_files/avatar.jpg" alt="" class="circle responsive-img online-user valign profile-image">
                            </div>
                            <div class="col s8">
                                <p>Weno Carasbong</p>
                                <p>Tokyo, Japan</p>
                            </div>
                        </div>
                        <div class="favorite-associate-list chat-out-list row">
                            <div class="col s4"><img src="./Page Blank _ Materialize - Material Design Admin Template_files/avatar.jpg" alt="" class="circle responsive-img offline-user valign profile-image">
                            </div>
                            <div class="col s8">
                                <p>Nusja Nawancali</p>
                                <p class="place">Bangkok, Thailand</p>
                            </div>
                        </div>
                    </div>
                </li>
                </ul>
            </li>
        <div class="ps-scrollbar-x-rail" style="left: 0px; bottom: 3px;"><div class="ps-scrollbar-x" style="left: 0px; width: 0px;"></div></div><div class="ps-scrollbar-y-rail" style="top: 0px; height: 677px; right: 3px;"><div class="ps-scrollbar-y" style="top: 0px; height: 621px;"></div></div></ul>
      </aside>
      <!-- LEFT RIGHT SIDEBAR NAV-->

    </div>
    <!-- END WRAPPER -->

  </div>
  <!-- END MAIN -->


  <!-- ================================================
    Scripts
    ================================================ -->
         <script src="http://code.jquery.com/jquery-2.1.1.js"></script>
     <script type="text/javascript">      var j = jQuery.noConflict();</script>

    <!-- Our jQuery Script to make everything work -->
  
 <script type="text/javascript" src="js/autocomplete.js"></script>
 
    <script type="text/javascript" src="js/jquery.min.js"></script>
   <script type="text/javascript" src="js/jquery-ui.min.js"></script>
	<script>
    	
		$('#vendor_name').autocomplete({
    		source: function( request, response ) {
				
    			$.ajax({
    				url : 'autocomplete1.php',
    				dataType: "json",
    				method: 'post',
    				data: {
    					name_startsWith: request.term,
    					type: 'ven_compname'
    				},
    				success: function( data ) {
    					response( $.map( data, function( item ) {
    						var code = item.split("|");
    							return {
    								label: code[1],
    								value: code[1],
    								data : item
    							}
    						}));
    					}
    				});
    		},
    		autoFocus: true,	      	
    		minLength: 0,
    		select: function( event, ui ) {
        		$('#myButton').show();
    			var names = ui.item.data.split("|");
    			$("#vendorname").val(names[1]);
				$("#ven_add").val(names[3]);
				$("#ven_contactperson").val(names[2]);
    		
    		}		      	
    	});
    	    
    </script>

  <!-- //////////////////////////////////////////////////////////////////////////// -->

  <!-- START FOOTER -->
  <footer class="page-footer">
    <div class="footer-copyright">
      <div class="container">
        <span class="leftspace">&copy;  <a class="grey-text text-lighten-4" href="#" target="_blank">Copyright South India Shelters Pvt.Ltd. </a> All rights reserved.</span>
        <span class="right rightspace"> Design and Developed by <a class="grey-text text-lighten-4" href="#">S.I.S</a></span>
        </div>
    </div>
  </footer>
  <!-- END FOOTER -->



   
  
</body></html>