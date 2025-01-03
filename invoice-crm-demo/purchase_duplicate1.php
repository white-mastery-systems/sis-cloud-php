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
	   document.getElementById('ven_contactperson').value = res2[10]; 
        }
      }
	  

      xmlhttp.open("GET","get_vendorlist.php?vendor_name="+str2, true);
      xmlhttp.send();
  }
</script> 
<!-- Purchase Edit End -->
<?php
//echo $po_no;

$result1 = mysql_query("SELECT * FROM  purchase_master where po_no=" .$po_no,$conn);
	if($row1 = mysql_fetch_array($result1))
	 {
	$project_id=$row1['project_id'] ;
	$ven_id=$row1['ven_id'];
	$vat_o=$row1['vat'];
	$amounttobepaid=$row1['amounttobepaid'];
	$subtotal=$row1['subtotal'];
	$amountdue=$row1['amountdue'];
	$ddate=$row1['ddate'];
	$po_date=$row1['po_date'];
	$taxamount=$row1['taxamount'];
	$totalAftertax=$row1['totalAftertax'];
	$project_name_m=$row1['project_name'];
	$block=$row1['Block'];
	 ?>
<script type="text/javascript">
     $(document).ready(function () {
         $("#vendor_name").autocomplete({
             source: 'autocomplete1.php',
             minLength: 1,
             select: function (event, ui) {
                 var $itemrow = $(this).closest('tr');
                 // Populate the input fields from the returned values
                 this.form.ven_add.value = ui.item.ven_add;
                 this.form.vendor_name.value = ui.item.vendor_name;
                 this.form.ven_contactperson.value = ui.item.ven_contactperson;
                 this.form.ven_id.value = ui.item.ven_id;
                 // Give focus to the next input field to recieve input from user

                 return false;
             }
             // Format the list menu output of the autocomplete
         }).data("autocomplete")._renderItem = function (ul, item) {
             return $("<li></li>")
            .data("item.autocomplete", item)
            .append("<a>" + item.vendor_name + "</a>")
            .appendTo(ul);
         };

     });
        </script>


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
            <li class="li-hover">
                <div class="row">
                    <div class="col s12 m12 l12">
                        <div class="sample-chart-wrapper">                            
                            <div class="ct-chart ct-golden-section" id="ct2-chart"><svg xmlns:ct="#" width="100%" height="100%" class="ct-chart-line" style="width: 100%; height: 100%;"><g class="ct-labels"><foreignobject style="overflow: visible;" x="45" y="104" width="21" height="30"><span class="ct-label ct-horizontal" xmlns="http://www.w3.org/1999/xhtml">1</span></foreignobject><foreignobject style="overflow: visible;" x="66" y="104" width="21" height="30"><span class="ct-label ct-horizontal" xmlns="http://www.w3.org/1999/xhtml">2</span></foreignobject><foreignobject style="overflow: visible;" x="87" y="104" width="21" height="30"><span class="ct-label ct-horizontal" xmlns="http://www.w3.org/1999/xhtml">3</span></foreignobject><foreignobject style="overflow: visible;" x="108" y="104" width="21" height="30"><span class="ct-label ct-horizontal" xmlns="http://www.w3.org/1999/xhtml">4</span></foreignobject><foreignobject style="overflow: visible;" x="129" y="104" width="21" height="30"><span class="ct-label ct-horizontal" xmlns="http://www.w3.org/1999/xhtml">5</span></foreignobject><foreignobject style="overflow: visible;" x="150" y="104" width="21" height="30"><span class="ct-label ct-horizontal" xmlns="http://www.w3.org/1999/xhtml">6</span></foreignobject><foreignobject style="overflow: visible;" x="171" y="104" width="21" height="30"><span class="ct-label ct-horizontal" xmlns="http://www.w3.org/1999/xhtml">7</span></foreignobject><foreignobject style="overflow: visible;" x="192" y="104" width="21" height="30"><span class="ct-label ct-horizontal" xmlns="http://www.w3.org/1999/xhtml">8</span></foreignobject><foreignobject style="overflow: visible;" y="84" x="-5" height="20.88888888888889" width="40"><span class="ct-label ct-vertical" xmlns="http://www.w3.org/1999/xhtml">0</span></foreignobject><foreignobject style="overflow: visible;" y="66.9090909090909" x="-5" height="20.88888888888889" width="40"><span class="ct-label ct-vertical" xmlns="http://www.w3.org/1999/xhtml">2</span></foreignobject><foreignobject style="overflow: visible;" y="49.81818181818181" x="-5" height="20.88888888888889" width="40"><span class="ct-label ct-vertical" xmlns="http://www.w3.org/1999/xhtml">4</span></foreignobject><foreignobject style="overflow: visible;" y="32.72727272727273" x="-5" height="20.88888888888889" width="40"><span class="ct-label ct-vertical" xmlns="http://www.w3.org/1999/xhtml">6</span></foreignobject><foreignobject style="overflow: visible;" y="15.63636363636364" x="-5" height="20.88888888888889" width="40"><span class="ct-label ct-vertical" xmlns="http://www.w3.org/1999/xhtml">8</span></foreignobject></g><g class="ct-grids"><line x1="45" x2="45" y1="5" y2="99" class="ct-grid ct-horizontal"></line><line x1="66" x2="66" y1="5" y2="99" class="ct-grid ct-horizontal"></line><line x1="87" x2="87" y1="5" y2="99" class="ct-grid ct-horizontal"></line><line x1="108" x2="108" y1="5" y2="99" class="ct-grid ct-horizontal"></line><line x1="129" x2="129" y1="5" y2="99" class="ct-grid ct-horizontal"></line><line x1="150" x2="150" y1="5" y2="99" class="ct-grid ct-horizontal"></line><line x1="171" x2="171" y1="5" y2="99" class="ct-grid ct-horizontal"></line><line x1="192" x2="192" y1="5" y2="99" class="ct-grid ct-horizontal"></line><line y1="99" y2="99" x1="45" x2="213" class="ct-grid ct-vertical"></line><line y1="81.9090909090909" y2="81.9090909090909" x1="45" x2="213" class="ct-grid ct-vertical"></line><line y1="64.81818181818181" y2="64.81818181818181" x1="45" x2="213" class="ct-grid ct-vertical"></line><line y1="47.72727272727273" y2="47.72727272727273" x1="45" x2="213" class="ct-grid ct-vertical"></line><line y1="30.63636363636364" y2="30.63636363636364" x1="45" x2="213" class="ct-grid ct-vertical"></line></g><g class="ct-series ct-series-a"><path d="M45,99L45,56.273C48.5,50.576,59,24.939,66,22.091C73,19.242,80,37.758,87,39.182C94,40.606,101,27.788,108,30.636C115,33.485,122,49.152,129,56.273C136,63.394,143,73.364,150,73.364C157,73.364,164,57.697,171,56.273C178,54.848,188.5,63.394,192,64.818L192,99" class="ct-area" ct:values="5,9,7,8,5,3,5,4"></path><path d="M45,56.273C48.5,50.576,59,24.939,66,22.091C73,19.242,80,37.758,87,39.182C94,40.606,101,27.788,108,30.636C115,33.485,122,49.152,129,56.273C136,63.394,143,73.364,150,73.364C157,73.364,164,57.697,171,56.273C178,54.848,188.5,63.394,192,64.818" class="ct-line" ct:values="5,9,7,8,5,3,5,4"></path><line x1="45" y1="56.27272727272727" x2="45.01" y2="56.27272727272727" class="ct-point" ct:value="5"></line><line x1="66" y1="22.090909090909093" x2="66.01" y2="22.090909090909093" class="ct-point" ct:value="9"></line><line x1="87" y1="39.18181818181818" x2="87.01" y2="39.18181818181818" class="ct-point" ct:value="7"></line><line x1="108" y1="30.63636363636364" x2="108.01" y2="30.63636363636364" class="ct-point" ct:value="8"></line><line x1="129" y1="56.27272727272727" x2="129.01" y2="56.27272727272727" class="ct-point" ct:value="5"></line><line x1="150" y1="73.36363636363636" x2="150.01" y2="73.36363636363636" class="ct-point" ct:value="3"></line><line x1="171" y1="56.27272727272727" x2="171.01" y2="56.27272727272727" class="ct-point" ct:value="5"></line><line x1="192" y1="64.81818181818181" x2="192.01" y2="64.81818181818181" class="ct-point" ct:value="4"></line></g></svg></div>
                        </div>
                    </div>
                </div>
            </li>
        <div class="ps-scrollbar-x-rail" style="left: 0px; bottom: 3px;"><div class="ps-scrollbar-x" style="left: 0px; width: 0px;"></div></div><div class="ps-scrollbar-y-rail" style="top: 0px; height: 613px; right: 3px;"><div class="ps-scrollbar-y" style="top: 0px; height: 321px;"></div></div></ul>
        <a href="#" data-activates="slide-out" class="sidebar-collapse btn-floating btn-medium waves-effect waves-light hide-on-large-only cyan"><i class="mdi-navigation-menu"></i></a>
      </aside>
      <!-- END LEFT SIDEBAR NAV-->

      <!-- //////////////////////////////////////////////////////////////////////////// -->

      <!-- START CONTENT -->
      <form>
      <section id="content">
       <!-- Page disp Area -->      
<div id='breadcrumbs-wrapper' class='grey lighten-3'>
      <div class='container-title'>
             <div class='row'>
               <div class='col s12 m12 l12'>
                 <h5 class='breadcrumbs-title'>Purchase New</h5>
                
                <div  style='position:relative; top:40px;width:auto; padding-top:10px' class='left leftspace'><a class='btn-floating btn-large waves-effect waves-light'><i class='material-icons'>arrow_back</i></a></div>
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
<table class='table table-full'>
<thead>
<tr>
<th><b>No</b></th>
<th><b>Description</b></th>
<th><b>Qty</b></th>
<th><b>Price</b></th>
 <th><b>VAT</b></th>
<th><b>Total</b></th>
</tr>
</thead>
<tbody>
<?php
require('connect.php');
$po_no=$_GET['po_no'];
//echo $po_no;
$productname;
$product_qty;
$ratesperproduct;
$vat;
$Amount;
	$po_date;
	$result_r = mysql_query("SELECT * FROM  purchaseorder_tbl where Po_no=" .$po_no,$conn);

while($row = mysql_fetch_array($result_r))
 {
	 $po_id=$row['po_id'] ;
	$productname = $row['product_name'] ;
	$product_qty=$row['product_qty'];
	$ratesperproduct = $row['ratesperproduct'];
	$vat = $row['vat'];
	$Amount = $row['Amount'];
		$po_date = $row['po_date'];
		
		
   ?>
  <tr class="item-row">

                      <td class="datagrid-body">&nbsp;</td>
                    <td class="datagrid-body"><a id="deleteRow"><img src="images/icon-minus.png" alt="Remove Item" title="Remove Item"></a> &nbsp;
                      <input  data-type="productName" name="itemDesc[]" id="itemDesc"  value="<?php echo $productname;?>"  class="textbox2"  type="text" />                      <input type="hidden" value="<?php echo $po_id;?>" name="po_id[]" id="po_id"/></td>
                    <td class="datagrid-body"><input name="itemQty[]" value="<?php echo $product_qty;?>" id="itemQty" class="quantity textbox "  tabindex="1" type="number"/></td>
                    <td class="datagrid-body">
                  <input name="itemPrice[]" value="<?php echo $ratesperproduct;?>" id="itemPrice" class="textbox rate"  tabindex="2" type="number"/></td>
 <td class="datagrid-body"><input name="itemVat[]" id="itemVat" value="<?php echo $vat;?>" class="textbox itemVat"  type="number" tabindex="3" /> </td>	
                    <td class="datagrid-body"><input name="itemLineTotal[]" id="itemLineTotal" value="<?php echo $Amount;?>" class="textbox totalLinePrice"  readonly="readonly" type="number" style="border:none" tabindex="4" />  </td>
                    <td class="datagrid-body"> </td>
                </tr>
                
               <?php
			
 }
			 ?>
          <tr class="item-row">

                      <td class="datagrid-body">&nbsp;</td>
                    <td class="datagrid-body"><a id="deleteRow"><img id="image1" src="images/icon-minus.png"/></a> &nbsp; <input  data-type="productName" name="itemDesc[]" class="textbox2 autocomplete_txt" id="itemDesc"  type="text" onChange="showimg()" required /></td>
                    <td class="datagrid-body"><input  type="number"  name="itemQty[]" value="" class="quantity textbox" id="itemQty" tabindex="1" required />
                    
                                       </td>
                    <td class="datagrid-body">
                  <input type="number"  name="itemPrice[]" value="" class="rate textbox" id="itemPrice" tabindex="2" /></td>
 <td class="datagrid-body"><input  type="number"  name="itemVat[]" class="itemVat textbox" id="itemVat" tabindex="3" /></td>	
                    <td class="datagrid-body"><input name="itemLineTotal[] textbox" class="totalLinePrice textbox" id="itemLineTotal" readonly="readonly" type="number" tabindex="4"/>  </td>
                    <td class="datagrid-body"> </td>
                </tr>
<td colspan='4' class='white'></td>
<td class='cyan white-text'>Grand Total</td>
<td class='cyan strong white-text'>

<div class="input-field col s8 "><input type="number" readonly class="textbox" id="subTotal" name="subTotal" value="<?php echo $subtotal;?>" placholder="Total"/></div></td>
</tr>
</tbody>
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
<div class="row"><div class="col s2">Payment </div> <div class="input-field col s2 left"><input type="text" name='payment' id="payment"  value="<?php echo $payment;?>"  placeholder="Payment"/> </div> </div>
<div class="row"><div class="col s2">Delivery</div><div class="input-field col s2 left"><input type="text" name='place' id="place" value="<?php echo $place;?>"   style="width:auto"/></div></div>
<div class="row"><div class="col s2">Loading & Vat</div><div class="input-field col s2 left"><input type="text" name='vat_o' id="vat_o" value="<?php echo $vat_o;?>"   style="width:auto" required/></div></div>
<div class="row"><div class="col s2">Please Supply by</div> <div class="col s2"><input type="text" name='ddate' id="ddate" value="<?php echo $ddate;?>"  style="width:auto" placeholder="Delivery Date" required/></div> <div class="col s1  textaligncenter">at our</div><div class="col s2"><input type="text" name='place1' id="place1" value="<?php echo $place;?>" style="width:auto"/> </div> <div class="col s1 textaligncenter">Site</div></div>
<div class="row">The above price is net price inclusive of all Taxes and Transport Charges.The Other terms and conditions remains the same as per your Quote.</div><br />
<div class="row"><b> <div class="col s2">Site contact Person </div><div class="input-field col s2 left"><input type="text" name='contactname' id="contactname"  value="<?php echo $contactname;?>" style="width:auto"/></div><div class="col s2 textaligncenter">Mobile :</div>     <div class="input-field col s2 left"><input type="text" name='mobilno' id="mobilno" value="<?php echo $mobilno;?>"/></div> </b></div>
<div class="row"><div class="input-field col s2"><b>Site address </b></div>
<div class="input-field col s2 left"><b><input type="text" name='projectname' id="projectname"  value="<?php echo $project_name;?>"  class="" style="width:auto; font-weight:bold"/></b></div></div>
<div class="row"><div class="input-field col s4 left"><textarea name="address" id="address" value="" class="" ><?php echo $address;?></textarea><br />
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



    <!-- ================================================
    Scripts
    ================================================ -->
       <!-- Container End -->
    <script  type="text/javascript" src="jquery-1.7.2.min.js"></script>
    <script  type="text/javascript" src="js/jquery-ui-1.8.14.custom.min.js"></script>
    <!-- Our jQuery Script to make everything work -->
    <script  type="text/javascript" src="js/jq-ac-script.js"></script>
   
  
</body></html>