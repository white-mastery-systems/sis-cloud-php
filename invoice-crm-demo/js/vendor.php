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
$msg = "IP: " . $ip . " TIME: " . $time . " User: " .$_SESSION['st_username'].  " USERAGENT: " .$userAgent. "Action :Vendor Page";
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



   <script type='text/javascript'>//<![CDATA[
        $(window).load(function () {
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
        });//]]> 

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
                      <?php 
					 include "connect.php";
					
					 $sql_bal = "SELECT pro_name,SUM(stk_received) as stk_received,SUM(stk_used) as stk_received,product_code,size,price,sub_catname,cat_name,project_name,SUM(stock) as stock,SUM(stk_used) as stk_used FROM  stocklist where stk_received <=50  GROUP BY pro_name,project_name ";
					 $result_bal= mysql_query($sql_bal,$conn);
					 $num_rows = mysql_num_rows($result_bal);

					 
					 ?>
                     <ul class="right hide-on-med-and-down " >  
                   
                      <li class=""><a href="javascript:void(0);" class="badge1 waves-effect waves-block waves-light notification-button" data-activates="notifications-dropdown" id="notifylink"  data-badge="<?php echo $num_rows ?>" ><i class="material-icons">notifications</i></a>
                       
<div id="notificationContainer">

<div id="notificationsBody" class="notifications page-wrapper scrollbar-macosx">
 <?php while($row_bal = mysql_fetch_array($result_bal))
					  {
echo '<div class="content" style="width:100%"><div style="width:85%; position:relative; float:left;height:auto; line-height:160%">'.$row_bal["pro_name"].'</div><div style="width:15%; position:relative;  float:left; height:auto; line-height:160%"><i class="material-icons" style="height:35px; line-height:35px">cancel</i></div></div>';
					  }
					  ?>                 

</div>

<div id="notificationFooter"><a href="#">See All</a></div>
</div>
                        </li>
                                           
                        <li><a href="logout.php" class="waves-effect waves-block waves-light" title="Logout"><i class="material-icons">power_settings_new</i></a> 
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
                <h5 class="breadcrumbs-title">Vendor</h5>
              <div class='left leftspace navigations'><a href="order.php" class='btn-floating btn-large waves-effect waves-light'><i class='material-icons'>arrow_back</i></a></div>
                <div  class="right rightspace navigations"><span><a class="btn-floating btn-large waves-effect waves-light" title="Delete" id="del_all" style="display:none" > <i class="material-icons" title="Cancel">delete</i></a></span> 
                <span><a class="btn-floating btn-large waves-effect waves-light" title="Add Vendor" onClick="fun_add()"><i class="material-icons" title="add">add</i></a></span> </div>
              </div>
            </div>

          </div>
        </div>
   
        

      
        <div class="container">
         <div class="section topspace" >
          <div class="row" align="center">
              <div class="col s4 leftspace">
             <select id="venstatus" name="venstatus" onChange="changestatus1()">
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

 <div class="modal fade" id="overlayadd" style="">
  <div class="modal-dialog" style="width:600px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        
        <h4 class="modal-title">Add</h4>
      </div>
      <div class="modal-body" >

      <div style="width:600px;" id="add-content">
      
  <form id="fm" class="fm-add">
        <div class="fitem">
				<label>Vendor Name</label>
				<input name="v_companyname" id="v_companyname1" value="" class="textbox resetThis">
                <input type="hidden" name="action" id="action" value="add" class="textbox resetThis">
			</div>
		<div class="fitem">
				 <label>Name </label>
				 <input name="v_name" id="v_name1" value=""class="textbox resetThis">
			 </div>
            <div class="fitem">
				 <label>Address </label>
				 <input name="v_address" id="v_address1" value="" class="textbox resetThis">
			 </div>
			 
			<div class="fitem">
				 <label>Mobile</label>
				<input name="v_mobile" id="v_mobile1" value="" class="textbox resetThis">
			</div>
			 <div class="fitem">
				 <label>Phone</label>
				 <input name="v_phone" id="v_phone1" value="" class="textbox resetThis">
			 </div>
              <div class="fitem">
				 <label>Fax</label>
				 <input name="v_fax" id="v_fax1" value=""class="textbox resetThis">
			 </div>
               <div class="fitem">
				 <label>Email</label>
				 <input name="v_email" id="v_email1" value=""class="textbox resetThis">
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
      
  <form id="fm"  class="fm-edit">
       
		<div class="fitem">
				<label>Vendor Name</label>
				<input name="v_companyname" id="v_companyname" value="" class="textbox">
                   <input type="hidden" name="ven_id" id="ven_id" value="" class="textbox" >
                <input type="hidden" name="action" id="action" value="edit" class="textbox">
			</div>
		<div class="fitem">
				 <label>Name </label>
				 <input name="v_name" id="v_name" value=""class="textbox" >
			 </div>
            <div class="fitem">
				 <label>Address </label>
				 <input name="v_address" id="v_address" value="" class="textbox" >
			 </div>
			 
			<div class="fitem">
				 <label>Mobile</label>
				<input name="v_mobile" id="v_mobile" value="" class="textbox" >
			</div>
			 <div class="fitem">
				 <label>Phone</label>
				 <input name="v_phone" id="v_phone" value="" class="textbox" >
			 </div>
              <div class="fitem">
				 <label>Fax</label>
				 <input name="v_fax" id="v_fax" value=""class="textbox" >
			 </div>
               <div class="fitem">
				 <label>Email</label>
				 <input name="v_email" id="v_email" value=""class="textbox" >
			 </div>
         
            <div align="right" >           
<button type='submit' class='btn-floating btn-medium waves-effect waves-light' title='submit'><i class='material-icons small'>done</i></button>  <button type='button' id="clear" class='btn-floating btn-medium waves-effect waves-light' onClick="popupclose()"><i class='material-icons small'>clear</i></button></div>
			   </form>
			
 
</div>

</div>
</div>
</div>
</div>


<!--   Deletet -->

 <div class="modal fade" id="overlaydelete" style="">
  <div class="modal-dialog" style="width:600px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        
        <h4 class="modal-title">Delete</h4>
      </div>
      <div class="modal-body">

      <div style="width:600px;" id="add-content">
      
  <form id="fm" method="get" novalidate="novalidate" class="fm-delete">
        <div class="fitem">
				Are you sure want to <b>CANCEL</b> this
				<input name="v_companyname" id="v_companyname2"  style="border:none" type="hidden">
                <input type="hidden" name="ven_id" id="ven_id2" class="textbox" >
                 <input type="hidden" name="action" id="action2" value="deletet" class="textbox">
			</div>
	 <div align="right" >           
<button type='submit' class='btn-floating btn-medium waves-effect waves-light' title='submit'><i class='material-icons small'>done</i></button>  <button type='button' id="clear" class='btn-floating btn-medium waves-effect waves-light' onClick="popupclose()"><i class='material-icons small'>clear</i></button></div>
			   </form>
			
 
</div>

</div>
</div>
</div>
</div>


<!--   Deletep -->

 <div class="modal fade" id="overlaydeletep" style="">
  <div class="modal-dialog" style="width:600px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        
        <h4 class="modal-title">Delete</h4>
      </div>
      <div class="modal-body">

      <div style="width:600px" id="add-content">
      
  <form id="fm" method="get" novalidate="novalidate" class="fm-delete1">
        <div class="fitem">
					Are you sure want to permanently <b>DELETE</b> this
				<input name="v_companyname" id="v_companyname3" value="" style="border:none" type="hidden">
                <input type="hidden" name="ven_id" id="ven_id3" value="" class="textbox" >
                 <input type="hidden" name="action" id="action3" value="deletep" class="textbox" >
			</div>
		 <div align="right" >           
<button type='submit' class='btn-floating btn-medium waves-effect waves-light' title='submit'><i class='material-icons small'>done</i></button>  <button type='button' id="clear" class='btn-floating btn-medium waves-effect waves-light' onClick="popupclose()"><i class='material-icons small'>clear</i></button></div>


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
			
		//edit
		$('.use-edit').click(function () {
						   
	$('#overlayedit').modal('show');
	var str = $(this).closest("tr").find('td:eq(1)').text();
var res = str.split("-");
	//document.getElementById("req_id").value = $(this).closest("tr").find('td:eq(0)').text();
	document.getElementById("v_companyname").value = res[4];
	document.getElementById("ven_id").value = res[0];
	document.getElementById("v_address").value = $(this).closest("tr").find('td:eq(2)').text();
	document.getElementById("v_name").value = $(this).closest("tr").find('td:eq(3)').text();
	document.getElementById("v_mobile").value = $(this).closest("tr").find('td:eq(4)').text();
	document.getElementById("v_phone").value = res[1];
	document.getElementById("v_fax").value = res[2];
	document.getElementById("v_email").value = res[3];
	
	

});
		
		//Deletet
		
		$('.use-delete').click(function () {
						   
	$('#overlaydelete').modal('show');
		var str = $(this).closest("tr").find('td:eq(1)').text();
var res = str.split("-");
var str1 = $(this).closest("tr").find('td:eq(4)').text();
var flat = str1.split("-");
	//document.getElementById("req_id").value = $(this).closest("tr").find('td:eq(0)').text();
	document.getElementById("v_companyname2").value = res[4];
	document.getElementById("ven_id2").value = res[0];
	});

		//Deletet
		
		$('.use-delete1').click(function () {
						   
	$('#overlaydeletep').modal('show');
		var str = $(this).closest("tr").find('td:eq(1)').text();
var res = str.split("-");
var str1 = $(this).closest("tr").find('td:eq(4)').text();
var flat = str1.split("-");
	//document.getElementById("req_id").value = $(this).closest("tr").find('td:eq(0)').text();
	document.getElementById("v_companyname3").value = res[4];
	document.getElementById("ven_id3").value = res[0];
	});
		
  $('#itemsTable').dataTable();
  /** reset **/
		$('#clear').click(function(){
   //alert("hi");
	$(".resetThis").val("");
});
  
  
			});
	}
	
	 function changestatus1()
  {
	  var action = $("#venstatus").val()
	  //alert(action);
    var dataString="&action="+action;
      $.ajax({
      url: "vendor_actioncode.php",
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
 
  
  $('#divLoading').ajaxStart(function(){
      $(this).fadeIn();
      $(this).html("<img src='loading.gif' /> ");
  }).ajaxStop(function(){
      $(this).fadeOut();
  });
 
  $("#del_all").on('click', function(e) {
									 //alert("hi");
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
                        url: 'vendor_actioncode.php',
                        type: 'post',
                        data: 'ids=' + checkValues
                    }).done(function(data) {
                        $("#result").html(data);
						loadData();
                      $('#selectAll').attr('checked', false);
					    $('.checkbox1').attr('checked', false);
                    });
                });
 loadData();
  tablesorter();
  function loadData()
  {
	 
    var dataString="&action=open";
      $.ajax({
       url: "vendor_actioncode.php",
      type: "POST",
      data: dataString,
      success:function(data)
      {
        $('#disp-area').html(data);
		tablesorter();
		
      }
    });
  }
  

  
 });


 function loadData()
  {
	 
   var dataString="&action=open";
      $.ajax({
      url: "vendor_actioncode.php",
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

function itemupdate()
{

}
function itemdelete()
{
}
function popupclose()
{
	$('#overlaydelete').modal('hide');
	$('#overlaydeletep').modal('hide');
	$('#overlayedit').modal('hide');
	$('#overlayadd').modal('hide');
}

$(document).on('submit','.fm-edit',function(){
   
// alert($(".fm-edit").serialize());
      $('#logerror').html('<img src="ajax.gif" align="absmiddle"> Please wait...');  
        $.ajax({
        type: "POST",
        url:"vendor_actioncode.php",
        data: $(".fm-edit :input[value!='']").serialize(), // serializes the form's elements.
        success: function(data)
        {
         //$("#itemsTable").append($(data));
            // $('#user_update')[0].reset(); 
		//alert(data);
		loadData();
		$('#overlayedit').modal('hide');
			
        }
     });
  return false;
    });

$(document).on('submit','.fm-add',function(){
   
	// alert($(".fm-edit").serialize());
      $('#logerror').html('<img src="ajax.gif" align="absmiddle"> Please wait...');  
        $.ajax({
        type: "POST",
        url:"vendor_actioncode.php",
        data: $(".fm-add :input[value!='']").serialize(), // serializes the form's elements.
        success: function(data)
        {
         //$("#itemsTable").append($(data));
            // $('#user_update')[0].reset(); 
		//alert(data);
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
        url:"vendor_actioncode.php",
        data: $(".fm-delete").serialize(), // serializes the form's elements.
        success: function(data)
        {
         //$("#itemsTable").append($(data));
            // $('#user_update')[0].reset(); 
		//alert(data);
		loadData();
		$('#overlaydelete').modal('hide');
			
        }
     });
  return false;
    });


$(document).on('submit','.fm-delete1',function(){
   
//alert($(".fm-delete1").serialize());
      $('#logerror').html('<img src="ajax.gif" align="absmiddle"> Please wait...');  
        $.ajax({
        type: "POST",
        url:"vendor_actioncode.php",
        data: $(".fm-delete1").serialize(), // serializes the form's elements.
        success: function(data)
        {
         //$("#itemsTable").append($(data));
            // $('#user_update')[0].reset(); 
		//alert(data);
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