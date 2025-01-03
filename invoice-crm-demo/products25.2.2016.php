<?php
session_start();
include "writelog.php";
include "connect.php";
date_default_timezone_set('Asia/Calcutta'); 
$time = date('Y-m-d H:i:s');

$ip = getenv('REMOTE_ADDR');
$userAgent = getenv('HTTP_USER_AGENT');
$referrer = getenv('HTTP_REFERER');
$query = getenv('QUERY_STRING');
$msg = "IP: " . $ip . " TIME: " . $time . " User: " .$_SESSION['st_username'].  " USERAGENT: " .$userAgent. "Action :products Page";
writeToLogFile($msg);
//$lognme ;
 if (!isset($_SESSION['st_emailid']) and !isset($_SESSION['st_pwd']))
 {
   	header("Location: login.php");
	 }
    $_SESSION['st_emailid'];$_SESSION['st_pwd'];
	  ?><!doctype html>
<html lang="en"><head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection">
  <link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection">
  <link rel="stylesheet" type="text/css" href="media/css/jquery.dataTables.css">
    <!-- Custome CSS-->    
    <link href="css/custom-style.css" type="text/css" rel="stylesheet" media="screen">
     <link href="css/materialfamily.css" type="text/css" rel="stylesheet" media="screen">
<!-- viewport meta to reset iPhone inital scale -->
<link rel="stylesheet" href="css/bootstrap.css" type="text/css" media="screen" charset="utf-8" />
   <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
      <script type="text/javascript" src="https://code.jquery.com/jquery-1.8.3.js"></script>
         <script src="js/script.js"></script>

      <script type="text/javascript"> var j = jQuery.noConflict();</script>
 <script type='text/javascript'>//<![CDATA[ 
$(function() {
     var pgurl = window.location.href.substr(window.location.href.lastIndexOf("/")+1);
     $("#cssmenu ul a").each(function(){
          if($(this).attr("href") == pgurl || $(this).attr("href") == '' )
          $(this).addClass("active");
     })
});

</script>

<script src="https://code.jquery.com/jquery-2.1.1.min.js"/></script>
<title>Work Process</title>

<!-- css3-mediaqueries.js for IE8 or older -->
<!--[if lt IE 9]>
	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
<![endif]-->


</head>
<body class="loaded">
  <!-- Start Page Loading -->
  <div id="loader-wrapper">
      <div id="loader"></div>        
      <div class="loader-section section-left"></div>
      <div class="loader-section section-right"></div>
  </div>
  <!-- End Page Loading -->

  <!-- //////////////////////////////////////////////////////////////////////////// -->

  <!-- START HEADER -->
 <header id="header" class="page-topbar">
        <!-- start header nav-->
        <div class="navbar-fixed">
            <nav class="cyan">
                <div class="nav-wrapper">                    
                    
                    <ul class="left">                      
                    <li><h1 class="logo-wrapper"><a href="#" class="brand-logo darken-1"><img src="images/logo.png" alt="S.I.S logo"></a> <span class="logo-text">S.I.S</span></h1></li>
                    </ul>
                    <div class="header-search-wrapper">
                        <i class="material-icons active">search</i>
                        <input type="text" name="Search" class="header-search-input z-depth-2" >
                    </div>
                    <ul class="right hide-on-med-and-down">                        
                       <li><a href="logout.php" class="waves-effect waves-block waves-light toggle-fullscreen" title="Logout"><i class="material-icons">power_settings_new</i></a> 
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
                        <a class="btn-flat dropdown-button waves-effect waves-light white-text profile-btn" href="#" data-activates="profile-dropdown"><?php echo ($_SESSION['st_username'])  ?><i class="material-icons right large">arrow_drop_down</i></a><ul id="profile-dropdown" class="dropdown-content">
                            <li><a href="#"><i class="material-icons">face</i> Profile</a>
                            </li>
                            <li><a href="#"><i class="material-icons">settings</i> Settings</a>
                            </li>                           
                            <li class="divider"></li>
                            
                            <li><a href="logout.php"><i class="material-icons">power_settings_new</i> Logout</a>
                            </li>
                        </ul>
                        <p class="user-roal"><?php echo $_SESSION['sess_userrole'] ?></p>
                    </div>
                </div>
            </li>
            <li class="bold"><a href="#dashboard" class="waves-effect waves-cyan"><i class="material-icons">dashboard</i> Dashboard</a>
            </li>
            
              <?php 
	 	$dbper= explode('-',$_SESSION['st_permission']);
	 foreach($dbper as $val)
		{
			$sql_menu = "select * from  menu where menuname ='".$val."'";
			$result= mysql_query($sql_menu,$conn);
			while($row = mysql_fetch_array($result))
			{
				$iconsname = $row["iconsname"];
				$menuname = $row["menuname"];
				$dispname = $row["dispname"];
				$link = $row["link"];
				if($menuname == "Order")
				{
					$classname = 'active';
				}
				else
				{
					$classname = '';
				}
				
			echo '<li class="bold '.$classname.'"><a href="'.$link.'" class="waves-effect waves-cyan"><i class="material-icons">'.$iconsname.'</i>'.$dispname.'</a>
            </li>';
			}
	
		}
	 
	 ?>   
           
              
            </ul>
        <a href="#" data-activates="slide-out" class="sidebar-collapse btn-floating btn-medium waves-effect waves-light hide-on-large-only cyan"><i class="material-icons">menu</i></a>
      </aside>
      <!-- END LEFT SIDEBAR NAV-->

      <!-- //////////////////////////////////////////////////////////////////////////// -->

      <!-- START CONTENT -->
      <section id="content">
      
        <div id="breadcrumbs-wrapper" class=" grey lighten-3">
          <div class="container-title">

            <div class="row">
              <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Products</h5>
              <div  class='left leftspace navigations'><a href="order.php" class='btn-floating btn-large waves-effect waves-light'><i class='material-icons'>arrow_back</i></a></div>
                   <div  class='right rightspace navigations'><span><a class="btn-floating btn-large waves-effect waves-light" title="Delete" id="del_all" style="display:none" > <i class="material-icons" title="Cancel">delete</i></a></span> 
                <span><a class="btn-floating btn-large waves-effect waves-light" title="Add Vendor" onClick="fun_add()"><i class="material-icons" title="add">add</i></a></span> </div>
              </div>
            </div>

          </div>
        </div>
   
        

      
        <div class="container">
         <div class="section topspacelarge" >
          <div class="row" align="center">
          <div id="result"></div>
              <div class="col s4 leftspace">
             <select id="prostatus" name="prostatus" onChange="changestatus1()">
<option value="open">In Usage</option>
<option value="cancel">Deleted</option>
</select> <br>&nbsp;
</div></div>

<div class="table-responsive"  id="disp-area">
</div>
     
<!-- Form End -->
    
          
          </div>
               </div>
      
      
      </section>
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
                        <input id="icon_prefix" type="text" class="validate">
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
			
      <!-- Add -->
<script>
 

</script>
    <!-- Add -->

  <div class="modal fade" id="overlayadd" style="">
  <div class="modal-dialog" style="width:600px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        
        <h4 class="modal-title">Add</h4>
      </div>
      <div class="modal-body">

      <div style="width:600px;" id="add-content">
      
  <form id="fm" class="fm-add" novalidate="novalidate" >
     <div class="fitem">
				<label>Type</label>
			<select name="typemat" id="typemat1">
      <option value="window">Window</option>
      <option value="door">Door</option>
      <option value="tiles">tiles</option>
      <option value="steel">steel</option>
      <option value="standard">standard</option>
    </select>
			</div>

        <div class="fitem">
				<label>Product Name</label>
				<input name="pro_name" id="pro_name1" value="" class="textbox">
                <input type="hidden" name="action" id="action" value="add" class="textbox">
			</div>
		<div class="fitem">
				 <label>Code </label>
				 <input name="pro_code" id="pro_code1" value=""class="textbox">
			 </div>
             <div class="fitem csize" style="display:none">
				 <label>Size </label>
				 <input name="pro_size" id="pro_size1" value="" class="textbox">
			 </div>
            <div class="fitem cprice" style="display:none">
				 <label>Price </label>
				 <input name="pro_price" id="pro_price1" value="" class="textbox">
			 </div>
              <div class="fitem steel" style="display:none">
				 <label>Details </label>
				 <input name="pro_details" id="pro_details1" value="" class="textbox">
			 </div>
              
                <div class="fitem windisp">
				 <label>Width</label>
				 <input name="pro_width" id="pro_width1" value=""class="textbox">
			 </div>
             <div class="fitem windisp">
				 <label>Height</label>
				 <input name="pro_height" id="pro_height1" value=""class="textbox">
			 </div>	
			 <div class="fitem">
				 <label>Vat</label>
				 <input name="pro_vat" id="pro_vat1" value="" class="textbox">
			 </div>
              <div class="fitem windisp">
				 <label>ED</label>
				 <input name="pro_ed" id="pro_ed1" value="" class="textbox">
			 </div>
              <div class="fitem windisp">
				 <label>ST</label>
				 <input name="pro_st" id="pro_st1" value="" class="textbox">
			 </div>
              <div class="fitem windisp">
				 <label>Transportation</label>
				 <input name="pro_tp" id="pro_tp1" value="" class="textbox">
			 </div>
                <div class="fitem">
				 <label>Units</label>
				 <input name="pro_units" id="pro_units1" value="" class="textbox">
			 </div>
			 
			<div class="fitem brand">
				 <label>Brand</label>
				<input name="pro_brand" id="pro_brand1" value="" class="textbox">
			</div>
              
             <div class="fitem windisp">
				 <label>Material Cost</label>
				 <input name="pro_materialcost" id="pro_materialcost1" value="" class="textbox">
			 </div>
             <div class="fitem windisp">
				 <label>Installation Cost</label>
				 <input name="pro_icost" id="pro_icost" value="" class="textbox">
			 </div>
        <div class="fitem subcat">
				 <label>Sub Category</label>
				 <input name="pro_subcat" id="pro_subcat1" value=""class="textbox">
			 </div>
            <div class="fitem">
				 <label>Category</label>
				 <input name="pro_category" id="pro_category1" value="" class="textbox">
			 </div>
           
           <div align="right" >           
<button type='submit' class='btn-floating btn-medium waves-effect waves-light' title='submit'><i class='material-icons small'>done</i></button>  <button type='button' id="clear" class='btn-floating btn-medium waves-effect waves-light' onClick="popupclose()"><i class='material-icons small'>clear</i></button></div>
			   </form>
			
 
</div>

</div>
</div>
</div>
</div>

  <!-- edit -->

 <div class="modal fade" id="overlayedit" style="">
  <div class="modal-dialog" style="width:600px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        
        <h4 class="modal-title">Edit</h4>
      </div>
      <div class="modal-body" >

      <div style="width:600px;" id="add-content">
      
  <form id="fm" class="fm-edit" novalidate="novalidate" >
  
  
  
        <div class="fitem common" style="display:none">
				<label>Product Name</label>
				<input name="pro_name" id="pro_name" value="" class="textbox">
                <input type="hidden" name="action" id="action" value="add" class="textbox">
                   <input type="hidden" name="pro_id" id="pro_id" value="" class="textbox" >
                <input type="hidden" name="action" id="action" value="edit" class="textbox">
                 <input type="hidden" name="typemat" id="typemat" value="" class="textbox">
			</div>
		<div class="fitem common" style="display:none">
				 <label>Code </label>
				 <input name="pro_code" id="pro_code" class="textbox">
			 </div>
             <div class="fitem csize" style="display:none">
				 <label>Size </label>
				 <input name="pro_size" id="pro_size"  class="textbox">
			 </div>
            <div class="fitem cprice" style="display:none">
				 <label>Price </label>
				 <input name="pro_price" id="pro_price"  class="textbox">
			 </div>
              <div class="fitem steel" style="display:none">
				 <label>Details </label>
				 <input name="pro_details" id="pro_details"  class="textbox">
			 </div>

                <div class="fitem windisp" style="display:none">
				 <label>Width</label>
				 <input name="pro_width" id="pro_width" class="textbox">
			 </div>
             <div class="fitem windisp" style="display:none">
				 <label>Height</label>
				 <input name="pro_height" id="pro_height" class="textbox">
			 </div>	
			 <div class="fitem common" style="display:none">
				 <label>Vat</label>
				 <input name="pro_vat" id="pro_vat"  class="textbox">
			 </div>
              <div class="fitem windisp" style="display:none">
				 <label>ED</label>
				 <input name="pro_ed" id="pro_ed" class="textbox">
			 </div> 
              <div class="fitem windisp" style="display:none">
				 <label>ST</label>
				 <input name="pro_st" id="pro_st"  class="textbox">
			 </div>
              <div class="fitem windisp" style="display:none">
				 <label>Transportation</label>
				 <input name="pro_tp" id="pro_tp"  class="textbox">
			 </div>
                <div class="fitem common" style="display:none" >
				 <label>Units</label>
				 <input name="pro_units" id="pro_units"  class="textbox">
			 </div>
			 
			<div class="fitem common" style="display:none" >
				 <label>Brand</label>
				<input name="pro_brand" id="pro_brand"  class="textbox">
			</div>
              
             <div class="fitem windisp" style="display:none" >
				 <label>Material Cost</label>
				 <input name="pro_materialcost" id="pro_materialcost"  class="textbox">
			 </div>
             <div class="fitem windisp" style="display:none">
				 <label>Installation Cost</label>
				 <input name="pro_icost" id="pro_icost"  class="textbox">
			 </div>
        <div class="fitem subcat common" style="display:none">
				 <label>Sub Category</label>
				 <input name="pro_subcat" id="pro_subcat" class="textbox">
			 </div>
            <div class="fitem common" style="display:none">
				 <label>Category</label>
				 <input name="pro_category" id="pro_category" class="textbox">
			 </div>
   
        <div align="right">       
      <button type='submit' class='btn-floating btn-medium waves-effect waves-light' title='submit'><i class='material-icons small'>done</i></button>  <button type='button' id="clear" class='btn-floating btn-medium waves-effect waves-light' onClick="popupclose()"><i class='material-icons small'>clear</i></button>
</div>
			   </form>
			
 
</div>

</div>
</div>
</div>
</div>


<!--   Delete temp -->

 <div class="modal fade" id="overlaydelete" style="">
  <div class="modal-dialog" style="width:600px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        
        <h4 class="modal-title">Delete</h4>
      </div>
      <div class="modal-body">

      <div style="width:600px;" id="add-content">
      
  <form id="fm" class="fm-delete">
        <div class="fitem">
				Are you sure want to <b>CANCEL</b> this
				<input name="pro_name" id="pro_name2"  style="border:none" type="hidden">
                <input type="hidden" name="pro_id" id="pro_id2" value="" class="textbox" >
                 <input type="hidden" name="action" id="action2" value="deletet" class="textbox" >
			</div>
<div  align="right">   
<button type='submit' class='btn-floating btn-medium waves-effect waves-light' title='submit'><i class='material-icons small'>done</i></button>  <button type='button' id="clear" class='btn-floating btn-medium waves-effect waves-light' onClick="popupclose()"><i class='material-icons small'>clear</i></button>

</div>
			   </form>
			
 
</div>

</div>
</div>
</div>
</div>

<!--   Delete Permanently  -->

 <div class="modal fade" id="overlaydeletep" style="">
  <div class="modal-dialog" style="width:600px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        
        <h4 class="modal-title">Delete</h4>
      </div>
      <div class="modal-body">

      <div style="width:600px;" id="add-content">
      
  <form id="fm"  class="fm-delete1">
        <div class="fitem">
					Are you sure want to permanently <b>DELETE</b> this
				<input name="pro_name" id="pro_name3"  style="border:none" type="hidden">
                <input type="hidden" name="pro_id" id="pro_id3"  class="textbox" >
                 <input type="hidden" name="action" id="action3" value="deletep" class="textbox" >
			</div>
	<div  align="right" >   
<button type='submit' class='btn-floating btn-medium waves-effect waves-light' title='submit'><i class='material-icons small'>done</i></button>  <button type='button' id="clear" class='btn-floating btn-medium waves-effect waves-light' onClick="popupclose()"><i class='material-icons small'>clear</i></button>

</div>
			   </form>
			
 
</div>

</div>
</div>
</div>
</div>


<style type="text/css">
		#fm{
			margin:0;
			padding:10px 30px;
		}
		.ftitle{
			font-size:14px;
			font-weight:bold;
			padding:5px 0;
			margin-bottom:10px;
			border-bottom:1px solid #ccc;
		}
		.fitem{
			margin-bottom:5px;
		}
		.fitem label{
			display:inline-block;
			width:140px;
		}
		.fitem input{
			width:160px;
		}
	</style>
<script type='text/javascript' src='https://code.jquery.com/jquery-1.9.1.js'></script>
<script type="text/javascript" language="javascript" src="media/js/jquery.js"></script>
	<script type="text/javascript" language="javascript">
	function tablesorter()
	{
$(function () {
			
			    $('input[type="checkbox"]').click(function () {
                /* Check all checkboxes if 'Select All' is clicked */
                if (this.id == 'selectAll') {
                    var isChecked = this.checked;
                    $('.selectedId').each(function () {
                        this.checked = isChecked;
                    });
                }

                /* Check / Uncheck 'Select All' checkbox if all the checkboxes are checked / unchecked */
                if ($('.selectedId:checked').length == $('.selectedId').length)
                    $('#selectAll').prop('checked', true);
                else
                    $('#selectAll').prop('checked', false);

                /* Toggle controls on the basis of selected checkboxes */
                if ($('.selectedId:checked').length >= 1) {
                    $('#del_all').fadeIn('fast');

                    if ($('.selectedId:checked').length == 1) {
                        $('.toolbar1').fadeIn('fast');
                    } else {
                        $('.toolbar1').fadeOut('fast');
                        $('.toolbar2').fadeIn('fast');
                    }
                } else {
                    $('.toolbar1').fadeOut('fast');
                    $('#del_all').fadeOut('fast');
                }
            });
				
			
			//Edit
		$('.use-edit').click(function () {
	$('#overlayedit').modal('show');
	var str = $(this).closest("tr").find('td:eq(1)').text();
    var res = str.split("+");
	var typemat = res[3];
		if(typemat == 'window')
	{
	$('.windisp').css('display','block');
	$('.common').css('display','block');
	$('.cprice').css('display','none');
	$('.csize').css('display','none');	
	$('.steel').css('display','none');	
	}
	else if(typemat == 'steel')
	{
	$('.common').css('display','block');
	$('.windisp').css('display','none');
	$('.cprice').css('display','block');
	$('.steel').css('display','block');	
	$('.csize').css('display','none');
	}
	else if(typemat == 'door')
	{
	$('.common').css('display','block');
	$('.windisp').css('display','none');
	$('.cprice').css('display','block');
	$('.steel').css('display','none');	
	$('.csize').css('display','block');
	}
	else if(typemat == 'standard')
	{
	$('.common').css('display','block');
	$('.windisp').css('display','none');
	$('.cprice').css('display','block');
	$('.steel').css('display','none');	
	$('.csize').css('display','none');
	}
	else
	{
	$('.common').css('display','block');
	$('.windisp').css('display','none');
	$('.cprice').css('display','block');
	$('.csize').css('display','none');
	$('.steel').css('display','none');	
	}
	//document.getElementById("req_id").value = $(this).closest("tr").find('td:eq(0)').text();
	document.getElementById("pro_name").value = res[0];
	document.getElementById("pro_code").value = $(this).closest("tr").find('td:eq(2)').text()
	document.getElementById("pro_size").value = $(this).closest("tr").find('td:eq(3)').text();
	document.getElementById("pro_price").value = $(this).closest("tr").find('td:eq(4)').text();
	document.getElementById("pro_category").value = $(this).closest("tr").find('td:eq(5)').text();
	document.getElementById("pro_subcat").value = $(this).closest("tr").find('td:eq(6)').text();
	document.getElementById("pro_id").value = res[1];
	document.getElementById("pro_brand").value = res[2];	
	document.getElementById("typemat").value = res[3];
	document.getElementById("pro_units").value = res[4];
	document.getElementById("pro_details").value = res[5];
	document.getElementById("pro_vat").value = res[6];
	document.getElementById("pro_tp").value = res[7];
	document.getElementById("pro_st").value = res[8];
	document.getElementById("pro_ed").value = res[9];	
	document.getElementById("pro_materialcost").value = res[10];
	document.getElementById("pro_icost").value = res[11];	
	document.getElementById("pro_width").value = res[12];
	document.getElementById("pro_height").value = res[13];	
});
		// Delete temp
		
$('.use-delete').click(function () {
$('#overlaydelete').modal('show');
var str = $(this).closest("tr").find('td:eq(1)').text();
var res = str.split("+");
var str1 = $(this).closest("tr").find('td:eq(4)').text();
var flat = str1.split("+");
	document.getElementById("pro_name2").value = res[0];
	document.getElementById("pro_id2").value = res[1];
	});
		
	$('.use-delete1').click(function () {
	$('#overlaydeletep').modal('show');
		var str = $(this).closest("tr").find('td:eq(1)').text();
var res = str.split("+");
var str1 = $(this).closest("tr").find('td:eq(4)').text();
var flat = str1.split("+");
document.getElementById("pro_name3").value = res[0];
document.getElementById("pro_id3").value = res[1];

	});	
	
  $('#itemsTable').dataTable();
  
			});
	}
	
	 function changestatus1()
  {
	var action = $("#prostatus").val();
    var dataString="&action="+action;
      $.ajax({
      url: "products_load.php",
      type: "POST",
      data: dataString,
      success:function(data)
      {
        $('#disp-area').html(data);
		tablesorter();
      }
    });
  }
	</script>
    <script>  
$(document).ready(function(){
  $.ajaxSetup({
    timeout: 10000,
    cache: false,
    error:function(x,e){
        if(x.status==0){
          alert('You are offline,please check your connection');
        }else if(x.status==404){
          alert('Requested URL unknown');
        }else if(x.status==500){
          alert('Internal Server Error!');
        }else if(e=='parsererror'){
          alert('Error.\nParsing JSON Request failed!');
        }else if(e=='timeout'){
          alert('Request Time out!');
        }else {
          alert('Unknown Error: \n'+x.responseText);
        }
    }
  });
 
$('select[name="typemat"]').change(function(){
															  
    var selval = $('select[name="typemat"]').find(':selected').val();   
	if(selval == 'window')
	{		
	$('.windisp').css('display','block');
	$('.cprice').css('display','none');
	$('.csize').css('display','none');	
	$('.steel').css('display','none');	
	}
	else if(selval == 'steel')
	{
		$('.windisp').css('display','none');
	$('.cprice').css('display','block');
	$('.steel').css('display','block');	
	$('.csize').css('display','none');
		}
	else if(selval == 'door')
	{
	$('.windisp').css('display','none');
	$('.cprice').css('display','block');
	$('.steel').css('display','none');	
	$('.csize').css('display','block');
	}
	else if(selval == 'standard')
	{
	$('.windisp').css('display','none');
	$('.cprice').css('display','block');
	$('.steel').css('display','none');	
	$('.csize').css('display','none');
	}
	else
	{
	$('.windisp').css('display','none');
	$('.cprice').css('display','block');
	$('.csize').css('display','block');
	}
	
    //$('.'+classname).colorbox({inline:true, width:666});
});
  $('#divLoading').ajaxStart(function(){
      $(this).fadeIn();
      $(this).html("<img src='loading.gif' /> ");
  }).ajaxStop(function(){
      $(this).fadeOut();
  });
 
   $("#del_all").on('click', function(e) {
								//	 alert("hi");
                    e.preventDefault();
                    var checkValues = $('.checkbox1:checked').map(function()
                    {
                        return $(this).val();
                    }).get();
                    console.log(checkValues);
                     
                    $.each( checkValues, function( i, val ) {
                        //$("#"+val).remove();
                        });
//                    return  false;
                    $.ajax({
                        url: 'products_load.php',
                        type: 'post',
                        data: 'ids=' + checkValues
                    }).done(function(data) {
                        $("#result").html(data);
						loadData();
                      $('#selectAll').attr('checked', false);
					    $('.checkbox1').attr('checked', false);
                    });
                });
   tablesorter();
 loadData();
 changestatus1();
 function loadData()
  {
	var action = $("#prostatus").val()
    var dataString="&action="+action;
      $.ajax({
      url: "products_load.php",
      type: "POST",
      data: dataString,
      success:function(data)
      {
        $('#disp-area').html(data);
		tablesorter();
		 itemdelete();
		 changestatus1();
      }
    });
  }
  
 });


 function loadData()
  {
	   var action = $("#prostatus").val()
    var dataString="&action="+action;
      $.ajax({
      url: "products_load.php",
      type: "POST",
      data: dataString,
      success:function(data)
      {
        $('#disp-area').html(data);
		tablesorter();
		
      }
    });
  }

function fun_add()
{
	$('#overlayadd').modal('show');
}

function popupclose()
{
	$('#overlaydelete').modal('hide');
	$('#overlaydeletep').modal('hide');
	$('#overlayadd').modal('hide');
	$('#overlayedit').modal('hide');
}

$(document).on('submit','.fm-edit',function(){
   
//alert($(".fm-edit :input[value!='']").serialize());
      $('#logerror').html('<img src="ajax.gif" align="absmiddle"> Please wait...');  
        $.ajax({
        type: "POST",
        url:"products_load.php",
        data: $(".fm-edit input").filter(function () {
        return !!this.value;
    }).serialize(), // serializes the form's elements. // serializes the form's elements.
        success: function(data)
        {
         loadData();
		$('#overlayedit').modal('hide');
			
        }
     });
  return false;
    });

$(document).on('submit','.fm-add',function(){
//alert( $(".fm-add").serialize());
      $('#logerror').html('<img src="ajax.gif" align="absmiddle"> Please wait...');  
        $.ajax({
        type: "POST",
        url:"products_load.php",
        data: $(".fm-add input").filter(function () {
        return !!this.value;
    }).serialize(), // serializes the form's elements.
        success: function(data)
        {
			//$("#result").html(data);
		loadData();
		$('#overlayadd').modal('hide');
			
        }
     });
  return false;
    });

/** Delete **/

$(document).on('submit','.fm-delete',function(){
   
//alert($(".fm-delete").serialize());
      $('#logerror').html('<img src="ajax.gif" align="absmiddle"> Please wait...');  
        $.ajax({
        type: "POST",
        url:"products_load.php",
        data: $(".fm-delete").serialize(), // serializes the form's elements.
        success: function(data)
        {
         //$('#result').html(data);
		loadData();
		$('#overlaydelete').modal('hide');
		//$('#overlaydeletep').modal('hide');
			
        }
     });
  return false;
    });


/** Permanent **/


$(document).on('submit','.fm-delete1',function(){
   
alert($(".fm-delete1").serialize());
      $('#logerror').html('<img src="ajax.gif" align="absmiddle"> Please wait...');  
        $.ajax({
        type: "POST",
        url:"products_load.php",
        data: $(".fm-delete1").serialize(), // serializes the form's elements.
        success: function(data)
        {
         // $('#result').html(data);
		loadData();
		$('#overlaydeletep').modal('hide');
        }
     });
  return false;
    });


</script>
<script type='text/javascript' src="js/jquery-1.11.2.min.js"></script>
<script type='text/javascript' src="js/plugins.js"></script>
 <script type='text/javascript' src='https://code.jquery.com/jquery-1.10.1.js'></script>
 <script type='text/javascript' src="js/WINDOWOPEN/jquery.js"></script>
<script type='text/javascript' src="js/WINDOWOPEN/jquery.min.js"></script>
<script type="text/javascript" language="javascript" src="media/js/jquery.dataTables.js"></script>
<script type='text/javascript' src="js/WINDOWOPEN/bootstrap.min.js"></script>
 <script type='text/javascript' src="js/perfect-scrollbar.min.js"></script>
<script type='text/javascript' src="js/materialize.js"></script>

</body>
</html>