<?php
session_start();
date_default_timezone_set('Asia/Calcutta'); 
$time = date('Y-m-d H:i:s');
include "connect.php";
$po_no=$_GET['po_no'];

?>

<!DOCTYPE html>
<html lang="en">
<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="msapplication-tap-highlight" content="no">

  <title>Work Process</title>

  <meta name="msapplication-TileColor" content="#00bcd4">
<link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection">
  <link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection">
    <!-- Custome CSS-->    
    <link href="css/custom-style.css" type="text/css" rel="stylesheet" media="screen">
     <link href="css/materialfamily.css" type="text/css" rel="stylesheet" media="screen">
<!-- viewport meta to reset iPhone inital scale -->
<link rel="stylesheet" href="css/bootstrap.css" type="text/css" media="screen" charset="utf-8" />
    	<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.4.custom.css">

   <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
      <script type="text/javascript" src="https://code.jquery.com/jquery-1.8.3.js"></script>
         <script src="js/script.js"></script>

      <script type="text/javascript"> var j = jQuery.noConflict();</script>
      <script type="text/javascript" src="http://code.jquery.com/jquery-1.6.min.js"></script>
	<script type="text/javascript" src="http://www.jeasyui.com/easyui/jquery.easyui.min.js"></script>

   
 <script type='text/javascript'>//<![CDATA[ 
$(function() {
     var pgurl = window.location.href.substr(window.location.href.lastIndexOf("/")+1);
     $("#cssmenu ul a").each(function(){
          if($(this).attr("href") == pgurl || $(this).attr("href") == '' )
          $(this).addClass("active");
     })
});

</script>
<title></title>

<!-- css3-mediaqueries.js for IE8 or older -->
<!--[if lt IE 9]>
	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
<![endif]-->
<!-- Purchase Edit Code -->
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
	  

      xmlhttp.open("GET","get_projectlist.php?project_name="+str1, true);
      xmlhttp.send();
  }
</script>   

 
<!-- Purchase Edit End -->
 <script type="text/javascript">
        function callpono() {

            var xmlhttp;

            if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            }
            else {// code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }

            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    $result = xmlhttp.responseText;
                    document.getElementById('po_no').value = $result;
                    document.getElementById('po_no1').value = document.getElementById('po_no').value;

                }
            }


            xmlhttp.open("GET", "get_pono.php");
            xmlhttp.send();
        }
</script>

<script language="JavaScript">    // change to text/javascript or even remove, no effect
    window.onload = function () {
        callpono();

    };
</script>
      
<?php
//echo $po_no;

$result1 = mysql_query("SELECT * FROM  purchase_master where po_no=" .$po_no,$conn);
	if($row1 = mysql_fetch_array($result1))
	 {
		 
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
	$subtotal=$row1['subtotal'];
	$ddate=$row1['ddate'];
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
      <form method="post" action="purchase_insertrow.php">
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
                     $('#blockname').html(data); // Have jquery change the html within the second select