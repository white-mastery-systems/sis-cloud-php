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
$msg = "IP: " . $ip . " TIME: " . $time . " User: " .$_SESSION['st_username'].  " USERAGENT: " .$userAgent. "Action :Order Page";
writeToLogFile($msg);
//$lognme ;
 if (!isset($_SESSION['st_emailid']) and !isset($_SESSION['st_pwd']))
 {
   	header("Location: login.php");
	 }
    $_SESSION['st_emailid'];$_SESSION['st_pwd'];
	  ?>


	<!doctype html>
	<html lang="en">

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width">
		<link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection">
		<link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection">
		<!-- Custome CSS-->
		<link href="css/custom-style.css" type="text/css" rel="stylesheet" media="screen">
		<link href="css/materialfamily.css" type="text/css" rel="stylesheet" media="screen">
		<link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css">
		<!-- viewport for Floating Button -->
		<link href="css/mfb.css" rel="stylesheet">
		<!-- viewport meta to reset iPhone inital scale -->
		<link rel="stylesheet" href="css/bootstrap.css" type="text/css" media="screen" charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.4.custom.css">
		<link rel="stylesheet" type="text/css" href="css/perfect-scrollbar.css">
		<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
		<script type="text/javascript" src="https://code.jquery.com/jquery-1.8.3.js"></script>
		<script src="js/script.js"></script>
		<script type="text/javascript">
			var j = jQuery.noConflict();
		</script>

		<link rel="stylesheet" href="css/jquery.scrollbar.css">
		<script src="js/jquery-1.9.1.js" type="text/javascript"></script>
		<script src="js/jquery.scrollbar.js"></script>
		<script src="js/runner.js"></script>
		<script>
			jQuery(function ($) {
				$('.page-wrapper').scrollbar();
			});
		</script>

		<title></title>
		<!-- css3-mediaqueries.js for IE8 or older -->
		<!--[if lt IE 9]>
	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
<![endif]-->
		<style type="text/css">
			<!-- @page {
				size: 8.5in 11in;
				margin: 1.8in 0.2in 1in 0.2in;
			}
			
			@media print {
				a[href]:after {
					content: none !important;
					width: 800px;
				}
			}
			
			--> .page-wrapper {
				border: none;
				height: 100%;
				margin: 0;
				padding: 0;
				width: 100%;
			}
		</style>
	</head>

	<body class="loaded">
		<!-- //////////////////////////////////////////////////////////////////////////// -->

		<!-- START HEADER -->
		<header id="header" class="page-topbar">
			<!-- start header nav-->
			<div class="navbar-fixed">
				<nav class="cyan">
					<div class="nav-wrapper">

						<ul class="left">
							<li>
								<h1 class="logo-wrapper"><a href="#" class="brand-logo darken-1"><img src="images/logo.png" alt="S.I.S logo"></a> <span class="logo-text">S.I.S</span></h1></li>
						</ul>
						<div class="header-search-wrapper">
							<!-- <i class="material-icons active">search</i>
                        <input type="text" name="Search" class="header-search-input z-depth-2" >-->
						</div>
						<?php 
					 include "connect.php";
					
					 $sql_bal = "SELECT pro_name,SUM(stk_received) as stk_received,SUM(stk_used) as stk_received,product_code,size,price,sub_catname,cat_name,project_name,SUM(stock) as stock,SUM(stk_used) as stk_used FROM  stocklist where stk_received <=50  GROUP BY pro_name,project_name ";
					 $result_bal= mysql_query($sql_bal,$conn);
					 $num_rows = mysql_num_rows($result_bal);

					 
					 ?>
							<ul class="right hide-on-med-and-down ">

								<li class=""><a href="javascript:void(0);" class="badge1 waves-effect waves-block waves-light notification-button" data-activates="notifications-dropdown" id="notifylink" data-badge="<?php echo $num_rows ?>"><i class="material-icons">notifications</i></a>

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
									<a class="btn-flat dropdown-button waves-effect waves-light white-text profile-btn" href="#" data-activates="profile-dropdown">
										<?php echo ($_SESSION['st_username'])  ?><i class="material-icons right large">arrow_drop_down</i></a>
									<ul id="profile-dropdown" class="dropdown-content">
										<li><a href="#"><i class="material-icons">face</i> Profile</a>
										</li>
										<li><a href="#"><i class="material-icons">settings</i> Settings</a>
										</li>
										<li class="divider"></li>

										<li><a href="logout.php"><i class="material-icons">power_settings_new</i> Logout</a>
										</li>
									</ul>
									<p class="user-roal">
										<?php echo $_SESSION['sess_userrole'] ?>
									</p>
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
									<span class="right rightspace navigationsbuttons"><a href="vendor.php" class="btn btn-success cyan" type="button">Vendors</a> <a href="products.php" class="btn btn-success cyan" type="button">Products</a></span>
									<h5 class="breadcrumbs-title">Purchase Order</h5>


									<span class="right rightspace navigations">  
               <ul id="menu" class="mfb-component--tr mfb-zoomin" data-mfb-toggle="hover">
      <li class="mfb-component__wrap">
        <a href="#" class="mfb-component__button--main">
          <i class="mfb-component__main-icon--resting material-icons">add</i>
          <i class="mfb-component__main-icon--active material-icons">close</i>
        </a>
        <ul class="mfb-component__list">
          <li>
            <a href="purchase_new.php" data-mfb-label="New Purchase" class="mfb-component__button--child">
              <i class="mfb-component__child-icon material-icons">add_shopping_cart</i>
              
            </a>
          </li>
          <li>
            <a href="#" data-mfb-label="Add Vendor" class="mfb-component__button--child" onClick="fun_addvendor()">
              <i class="mfb-component__child-icon material-icons">person_add</i>
            </a>
          </li>

          <li>
            <a href="#" data-mfb-label="Add Products" class="mfb-component__button--child" onClick="fun_add()">
              <i class="mfb-component__child-icon ion-social-twitter"><img src="images/products_addmall.png" /></i>
            </a>
          </li>
        </ul>
      </li>
    </ul>
 </span>
									<span class="right navigations1"><a class="mfb-component__button--main" title="Delete" id="del_all" style="display:none"> <i class=" mfb-component__main-icon--resting material-icons" title="Cancel">delete</i></a></span>
								</div>
							</div>

						</div>
					</div>




					<div class="row white">

						<div class="section topspace">
							<div class="row" align="center">
								<div class="col s4 leftspace">
									<select id="postatus" name="postatus" onChange="changestatus1()">
										<option value="open">Issued</option>
										<option value="cancel">Cancelled</option>
									</select>
									<br>&nbsp;
								</div>
							</div>
							<div id="result"></div>
							<div id="disp-area" class="table-responsive">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<table id="itemsTable" cellpadding="0" cellspacing="0" width="100%" border="0" class="table avail-div m-b-60 hidedata" width="100%">
										<thead>
											<tr class="title">
												<th class="font12" class="datatable-nosort">
													<input type="checkbox" id="itemsTable-select-all" />
													<label for="itemsTable-select-all"></label>
												</th>
												<th class="font12"><b>Icon</b></th>
												<th class="font12"><b>Invoice No</b><i class="material-icons tiny1">sort</i></th>
												<th class="font12"><b>Date </b> <i class="material-icons tiny1">sort</i></th>
												<th class="font12"><b>Invoice No</b> & <b>Date </b><i class="material-icons tiny1">sort</i></th>
												<th class="font12"><b>Summary</b> <i class="material-icons tiny1">sort</i></th>
												<th class="font12"><b>Total Rs</b> <i class="material-icons tiny1">sort</i></th>
												<th class="font12"><b>Action</b></th>
												<th class="font12"><b>Action</b></th>
											</tr>
										</thead>
									</table>

								</div>
							</div>




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
						<div class="ps-scrollbar-x-rail" style="left: 0px; bottom: 3px;">
							<div class="ps-scrollbar-x" style="left: 0px; width: 0px;"></div>
						</div>
						<div class="ps-scrollbar-y-rail" style="top: 0px; height: 677px; right: 3px;">
							<div class="ps-scrollbar-y" style="top: 0px; height: 621px;"></div>
						</div>
					</ul>
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

		<style type="text/css">
			#fm {
				margin: 0;
				padding: 10px 0px;
			}
			/*		.ftitle{
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
		.fitem textarea{
			
			height:300px;
		}*/
		</style>
		<!-- Container End -->
		<!-- Popup Window -->


		<!-- Product add -->

		<div class="modal fade" id="overlayadd" style="">
			<div class="modal-dialog col s12 m12 l12">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="material-icons">clear</i></button>
						<h4 class="modal-title">Add</h4>
					</div>
					<div class="modal-body">

						<div class="col s12 m12 l12" id="add-content">

							<form id="fm" method="get" novalidate="novalidate" class="fm-add">
								<div class="fitem" style="padding-bottom:10px;">
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
									<input type="text" name="pro_name" id="pro_name1" value="" class="textbox">
									<input type="hidden" name="action" id="action" value="add" class="textbox">
								</div>
								<div class="fitem">
									<label>Code </label>
									<input name="pro_code" type="text" id="pro_code1" value="" class="textbox">
								</div>
								<div class="fitem csize" style="display:none">
									<label>Size </label>
									<input name="pro_size" type="text" id="pro_size1" value="" class="textbox">
								</div>
								<div class="fitem cprice" style="display:none">
									<label>Price </label>
									<input name="pro_price" type="text" id="pro_price1" value="" class="textbox">
								</div>
								<div class="fitem steel" style="display:none">
									<label>Details </label>
									<input name="pro_details" type="text" id="pro_details1" value="" class="textbox">
								</div>

								<div class="fitem windisp">
									<label>Width</label>
									<input name="pro_width" type="text" id="pro_width1" value="" class="textbox">
								</div>
								<div class="fitem windisp">
									<label>Height</label>
									<input name="pro_height" type="text" id="pro_height1" value="" class="textbox">
								</div>
								<div class="fitem">
									<label>Vat</label>
									<input name="pro_vat" type="text" id="pro_vat1" value="" class="textbox">
								</div>
								<div class="fitem windisp">
									<label>ED</label>
									<input name="pro_ed" type="text" id="pro_ed1" value="" class="textbox">
								</div>
								<div class="fitem windisp">
									<label>ST</label>
									<input name="pro_st" type="text" id="pro_st1" value="" class="textbox">
								</div>
								<div class="fitem windisp">
									<label>Transportation</label>
									<input name="pro_tp" type="text" id="pro_tp1" value="" class="textbox">
								</div>
								<div class="fitem">
									<label>Units</label>
									<input name="pro_units" type="text" id="pro_units1" value="" class="textbox">
								</div>

								<div class="fitem brand">
									<label>Brand</label>
									<input name="pro_brand" type="text" id="pro_brand1" value="" class="textbox">
								</div>

								<div class="fitem windisp">
									<label>Material Cost</label>
									<input name="pro_materialcost" type="text" id="pro_materialcost1" value="" class="textbox">
								</div>
								<div class="fitem windisp">
									<label>Installation Cost</label>
									<input name="pro_icost" type="text" id="pro_icost" value="" class="textbox">
								</div>
								<div class="fitem subcat">
									<label>Sub Category</label>
									<input name="pro_subcat" type="text" id="pro_subcat1" value="" class="textbox">
								</div>
								<div class="fitem">
									<label>Category</label>
									<input name="pro_category" type="text" id="pro_category1" value="" class="textbox">
								</div>

								<div align="right">
									<button type='submit' class='btn-floating btn-medium waves-effect waves-light pink' style="" title='submit'><i class='material-icons small'>done</i></button>
									<button type='button' id="clear"  class='btn-floating btn-medium waves-effect waves-light pink' onClick="popupclose()"><i class='material-icons small'>clear</i></button>
								</div>
							</form>


						</div>

					</div>
				</div>
			</div>
		</div>
		<!-- Print -->
		<div class="modal fade" id="overlay" style="">
			<div class="modal-dialog col s12 m12 l12">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true" id="close_but"><i class="material-icons">clear</i></button>

						<h4 class="modal-title">Purchase Order</h4>
					</div>
					<div class="modal-body" id="modalbody">
						<button id="print_content" name="print_content">Print Content</button>

						<div class="col s12 m12 l12" id="disp-content">





						</div>

					</div>
				</div>
			</div>
		</div>


		<!-- Vendor Add -->

		<div class="modal fade" id="overlayadd_vendor" style="">
			<div class="modal-dialog col s12 m12 l12">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="material-icons">clear</i></button>
						<h4 class="modal-title">Add</h4>
					</div>
					<div class="modal-body">

						<div class="col s12 m12 l12" id="add-content">

							<form id="fm" method="get" novalidate="novalidate" class="fm-addvendor">
								<div class="fitem">
									<label>Vendor Name</label>
									<input name="v_companyname" type="text" id="v_companyname1" value="" class="textbox resetThis">
									<input type="hidden" name="action" id="action" value="add" class="textbox resetThis">
								</div>
								<div class="fitem">
									<label>Name </label>
									<input name="v_name" type="text" id="v_name1" value="" class="textbox resetThis">
								</div>
								<div class="fitem">
									<label>Address </label>
									<input name="v_address" type="text" id="v_address1" value="" class="textbox resetThis">
								</div>

								<div class="fitem">
									<label>Mobile</label>
									<input name="v_mobile" type="text" id="v_mobile1" value="" class="textbox resetThis">
								</div>
								<div class="fitem">
									<label>Phone</label>
									<input name="v_phone" type="text" id="v_phone1" value="" class="textbox resetThis">
								</div>
								<div class="fitem">
									<label>Fax</label>
									<input name="v_fax" type="text" id="v_fax1" value="" class="textbox resetThis">
								</div>
								<div class="fitem">
									<label>Email</label>
									<input name="v_email" type="text" id="v_email1" value="" class="textbox resetThis">
								</div>
								<div align="right">
									<button type='submit' class='btn-floating btn-medium waves-effect waves-light pink'  title='submit'><i class='material-icons small'>done</i></button>
									<button type='button' id="clear" class='btn-floating btn-medium waves-effect waves-light pink' onClick="popupclose()"><i class='material-icons small'>clear</i></button>
								</div>
							</form>

						</div>
					</div>
				</div>
			</div>
		</div>


		<!-- Email -->
		<div class="modal fade" id="overlay1" style="">
			<div class="modal-dialog col s12 m12 l12">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="material-icons">clear</i></button>
						<h4 class="modal-title">Send</h4>
					</div>
					<div class="modal-body">

						<div style="width:980px;" id="email-content">
							<div style="width:980px;" id="email-content1"></div>
						</div>
					</div>
				</div>
			</div>
		</div>


		<!--   Delete temp -->

		<div class="modal fade" id="overlaydelete" style="">
			<div class="modal-dialog" style="width:300px;">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="material-icons">clear</i></button>

						<h4 class="modal-title">Delete</h4>
					</div>
					<div class="modal-body">

						<div style="width:300px;" id="add-content">

							<form id="fm" method="get" novalidate="novalidate" class="fm-delete">
								<div class="fitem">
									Are you sure want to <b>CANCEL</b> this
									<input name="po_no" id="po_no" value="" style="border:none" type="hidden">
									<input name="status" id="status" value="" style="border:none" type="hidden">

									<input name="action" id="action" value="deletet" style="border:none" type="hidden">
								</div>
								<div align="right" style="padding-top:15px; padding-right:40px">

									<button type='submit' class='btn-floating btn-medium waves-effect waves-light pink'  title='submit'><i class='material-icons small'>done</i></button>
									<button type='button' id="clear" class='btn-floating btn-medium waves-effect waves-light pink' onClick="popupclose()"><i class='material-icons small'>clear</i></button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!--   Delete Permanently  -->

		<div class="modal fade" id="overlaydeletep" style="">
			<div class="modal-dialog" style="width:350px;">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="material-icons">clear</i></button>
						<h4 class="modal-title">Delete</h4>
					</div>
					<div class="modal-body">

						<div style="width:350px;" id="add-content">

							<form id="fm" method="get" novalidate="novalidate" class="fm-delete1">
								<div class="fitem">
									Are you sure want to permanently <b>DELETE</b> this
									<input name="po_no" id="po_no2" value="" style="border:none" type="hidden">
									<input name="action" id="action2" value="deletep" style="border:none" type="hidden">
									<input name="status" id="status1" value="" style="border:none" type="hidden">
								</div>
								<div align="right" style="padding-top:15px; padding-right:40px">
									<button type='submit' class='btn-floating btn-medium waves-effect waves-light  pink' title='submit'><i class='material-icons small'>done</i></button>
									<button type='button' id="clear"  class='btn-floating btn-medium waves-effect waves-light  pink' onClick="popupclose()"><i class='material-icons small'>clear</i></button>


								</div>
							</form>


						</div>

					</div>
				</div>
			</div>
		</div>
		<SCRIPT language="javascript">
		

		</SCRIPT>
		
		

		<script type="text/javascript" language="javascript">
			function tablesorter() {
				$(function () {
					$("#itemsTable").on("click", ".sicon a", function () {

						if ($(this).find('i').text() == "add")
							$(this).find('i').text("close");
						else if ($(this).find('i').text() == "close")
							$(this).find('i').text("add");
					});





					// Delete temp
					$("#itemsTable").on("click", ".open", function () {
						//$('#overlaydelete').modal('show');
						$('#overlaydelete').modal('show');

						var po_no = $(this).closest("tr").find('td:eq(2)').text();


						document.getElementById("po_no").value = po_no;
						document.getElementById("status").value = $(postatus).val();


					});

					/** $('.use-delete').click(function () {
			alert("Delete");
					alert("hi");	   
	//$('#overlaydelete').modal('show');
		
	/**	var po_no = $(this).closest("tr").find('td:eq(2)').text();
				alert(po_no);
	
	document.getElementById("po_no").value = po_no; 
	});
	**/
					
					
					$('#itemsTable-select-all').on('click', function () {			

					var rows = dataTable.rows({
						'search': 'applied'
					}).nodes();

					// Check/uncheck checkboxes for all rows in the table
					$('input[type="checkbox"]', rows).prop('checked', this.checked);
					if ($('#itemsTable tbody .selectedId:checked').length >= 1) {
						$('#del_all').fadeIn('fast');
					} else {
						$('#del_all').fadeOut('fast');
					}

				});

				// Handle click on checkbox to set state of "Select all" control
				$('#itemsTable tbody').on('click', 'input[type="checkbox"]', function () {
					// If checkbox is not checked
					//alert("tbodycheck");

					if ($('#itemsTable tbody .selectedId:checked').length >= 1) {
						$('#del_all').fadeIn('fast');
					}
					if ($('#itemsTable tbody .selectedId:checked').length < 1) {
						$('#del_all').fadeOut('fast');
					} else if (!this.checked) {
						var el = $('#itemsTable-select-all').get(0);

						//alert(el);
						// If "Select all" control is checked and has 'indeterminate' property


					}
				});
					
					
					$("#itemsTable").on("click", ".cancel", function () {

						$('#overlaydeletep').modal('show');
						//alert($(postatus).val());
						document.getElementById("po_no2").value = $(this).closest("tr").find('td:eq(2)').text();
							document.getElementById("status1").value = $(postatus).val();
						
						

					})



				});
				
			}


		</script>
<script>
		
	

	
		function datatable()
	{
		$('select[name="postatus"]').change(function () {
	var typeval = $('select[name="postatus"]').find(':selected').val();
	if (typeval == 'open') {
		   $('#itemsTable').dataTable().fnDestroy();
		//alert("open");
		
	}
	else if (typeval == 'cancel') {
		  $('#itemsTable').dataTable().fnDestroy();
		//alert("cancel");
		
	}
						
			
var dataTable = $('#itemsTable').DataTable({
					"processing": true
					, "serverSide": true
					, 'ajax': {
						url: "order_load.php?action="+typeval, // json datasource	
						type: "post", // method  , by default get
						"deferLoading": 57
						, error: function () { // error handling
							$(".itemsTable-error").html("");
							$("#itemsTable").append('<tbody class="itemsTable-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
							$("#itemsTable_processing").css("display", "none");


						}
					}
					, 'columnDefs': [{
						'targets': 0
						, 'searchable': false
						, 'orderable': false
						, 

      }]
					, 'order': [2, 'desc']
				});

					
					
});
		


	}
		</script>

		<script type="text/javascript" language="javascript">
			
			
			$(document).ready(function () {

typeval =$("#postatus").val();
	var dataTable = $('#itemsTable').DataTable({
					"processing": true
					, "serverSide": true
					, 'ajax': {
						url: "order_load.php?action="+typeval, // json datasource		

						type: "post", // method  , by default get
						

						"deferLoading": 57
						, error: function () { // error handling
							$(".itemsTable-error").html("");
							$("#itemsTable").append('<tbody class="itemsTable-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
							$("#itemsTable_processing").css("display", "none");


						}
					}
					, 'columnDefs': [{
						'targets': 0
						, 'searchable': false
						, 'orderable': false
						, 

      }]
					, 'order': [2, 'desc']
				});
datatable();
				
$('#itemsTable-select-all').on('click', function () {			

					var rows = dataTable.rows({
						'search': 'applied'
					}).nodes();

					// Check/uncheck checkboxes for all rows in the table
					$('input[type="checkbox"]', rows).prop('checked', this.checked);
					if ($('#itemsTable tbody .selectedId:checked').length >= 1) {
						$('#del_all').fadeIn('fast');
					} else {
						$('#del_all').fadeOut('fast');
					}

				});

$(document).on('submit', '.fm-delete', function () {
				// alert("hi");
				//alert($(".fm-delete").serialize());
				
				$('#logerror').html('<img src="ajax.gif" align="absmiddle"> Please wait...');
				$.ajax({
					type: "POST"
					, url: "order_loadaction.php"
					, data: $(".fm-delete input").filter(function () {
						return !!this.value;
					}).serialize(), // serializes the form's elements.
					success: function (data) {
						//alert(data);
						$('#overlaydelete').modal('hide');
						$('#overlaydeletep').modal('hide');
						dataTable.ajax.reload();

					}
				});
				return false;
			});

			$(document).on('submit', '.fm-delete1', function () {

				//alert($(".fm-delete1").serialize());
				$('#logerror').html('<img src="ajax.gif" align="absmiddle"> Please wait...');
				$.ajax({
					type: "POST"
					, url: "order_loadaction.php"
					, data: $(".fm-delete1").serialize(), // serializes the form's elements.
					success: function (data) {
						//alert(data)
						$('#overlaydeletep').modal('hide');
						dataTable.ajax.reload();

					}
				});
				return false;
			});
				// Handle click on "Select all" control
				
				$("#del_all").on('click', function (e) {

					var action = $("#postatus").val();
					e.preventDefault();
					var checkValues = $('.checkbox1:checked').map(function () {
						return $(this).val();
					}).get();
					console.log(checkValues);

					$.each(checkValues, function (i, val) {
						//$("#"+val).remove();
					});
					//                    return  false;
					$.ajax({
						url: 'order_loadaction.php'
						, type: 'post'
						, data: 'ids=' + checkValues + '&action=' + action
					}).done(function (data) {
						//$("#result").html(data);
						

						$('#selectAll').attr('checked', false);
						$('.checkbox1').attr('checked', false);
						

						$("#del_all").css('display', 'none');
						dataTable.ajax.reload();

						
					});
				});
				

				tablesorter();


				$('select[name="typemat"]').change(function () {

					var selval = $('select[name="typemat"]').find(':selected').val();
					if (selval == 'window') {
						$('.windisp').css('display', 'block');
						$('.cprice').css('display', 'none');
						$('.csize').css('display', 'none');
						$('.steel').css('display', 'none');
					} else if (selval == 'steel') {
						$('.windisp').css('display', 'none');
						$('.cprice').css('display', 'block');
						$('.steel').css('display', 'block');
						$('.csize').css('display', 'none');
					} else if (selval == 'door') {
						$('.windisp').css('display', 'none');
						$('.cprice').css('display', 'block');
						$('.steel').css('display', 'none');
						$('.csize').css('display', 'block');
					} else if (selval == 'standard') {
						$('.windisp').css('display', 'none');
						$('.cprice').css('display', 'block');
						$('.steel').css('display', 'none');
						$('.csize').css('display', 'none');
					} else {
						$('.windisp').css('display', 'none');
						$('.cprice').css('display', 'block');
						$('.csize').css('display', 'block');
					}

					//$('.'+classname).colorbox({inline:true, width:666});
				});
				$('#divLoading').ajaxStart(function () {
					$(this).fadeIn();
					$(this).html("<img src='loading.gif' /> ");
				}).ajaxStop(function () {
					$(this).fadeOut();
				});
				$("#notifylink").click(function () // onclick function for notification
					{
						$("#notificationContainer").fadeToggle(300); // show notification div
						$("#msg_count").fadeOut("slow");
						return false;
					});

				//Document Click
				$(document).click(function () {
					$("#notificationContainer").hide(); // hide notification div
				});
				//Popup Click
				$("#notificationContainer").click(function () {
					return false
				});



			});


			function popupclose() {
				$('#overlaydelete').modal('hide');
				$('#overlaydeletep').modal('hide');
				$('#overlayadd').modal('hide');
				$('#overlayadd_vendor').modal('hide');
			}

			function fun_add() {
				$('#overlayadd').modal('show');
			}

			function overlaydelete(pono) {
				//alert(pono);
				$('#overlaydelete').modal('show');

				document.getElementById("po_no").value = pono;
			}


			function fun_addvendor() {
				$('#overlayadd_vendor').modal('show');
			}

			$(document).on('submit', '.fm-add', function () {
				//alert("hi")
					//alert( $(".fm-add").serialize());
				$('#logerror').html('<img src="ajax.gif" align="absmiddle"> Please wait...');
				$.ajax({
					type: "POST"
					, url: "products_load.php"
					, data: $(".fm-add input").filter(function () {
						return !!this.value;
					}).serialize(), // serializes the form's elements.
					success: function (data) {
						//$("#result").html(data);
						sorting();
						$('#overlayadd').modal('hide');

					}
				});
				return false;
			});

			

			$(document).on('submit', '.fm-addvendor', function () {

				//alert($(".fm-add").serialize());
				$('#logerror').html('<img src="ajax.gif" align="absmiddle"> Please wait...');
				$.ajax({
					type: "POST"
					, url: "vendor_actioncode.php"
					, data: $(".fm-addvendor input").filter(function () {
						return !!this.value;
					}).serialize(), // serializes the form's elements.
					success: function (data) {
						//$("#itemsTable").append($(data));
						// $('#user_update')[0].reset(); 
//alert(data);
						
						$('#overlayadd_vendor').modal('hide');
						dataTable.ajax.reload();

					}
				});
				return false;
			});


			function fun_duplicate(str) {
				window.location = 'purchase_duplicate.php?po_no=' + str;
			}

			function fun_edit(str) {
				window.location = 'purchase_edit.php?po_no=' + str;
			}

			function fun_pdf(str) {
				location.href = "purchase_pdf1.php?po_no=" + str;

			}

			function fun_word(str) {
				location.href = "purchase_word.php?po_no=" + str;

			}

			/**
			function fun_print(str) 
			{
			      location.href="purchase_print.php?po_no="+str;

			}
			**/

			function fun_print(str) {
				var xmlhttp;

				if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp = new XMLHttpRequest();
				} else { // code for IE6, IE5
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}

				xmlhttp.onreadystatechange = function () {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						var str2 = xmlhttp.responseText;
						document.getElementById("disp-content").innerHTML = str2;
					}
				}


				xmlhttp.open("GET", "purchaseorderprint.php?po_no=" + str, true);
				xmlhttp.send();
				$('#overlay').modal('show');

			}


			function fun_email(str) {
				var xmlhttp;
				if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp = new XMLHttpRequest();
				} else { // code for IE6, IE5
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}

				xmlhttp.onreadystatechange = function () {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						var str2 = xmlhttp.responseText;
						document.getElementById("email-content").innerHTML = str2;


					}
				}


				xmlhttp.open("GET", "purchaseemail_popup.php?po_no=" + str, true);
				xmlhttp.send();
				$('#overlay1').modal('show');
			}






			function viewpo(str) {
				var xmlhttp;

				if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp = new XMLHttpRequest();
				} else { // code for IE6, IE5
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}

				xmlhttp.onreadystatechange = function () {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						var str2 = xmlhttp.responseText;


						document.getElementById("content").innerHTML = str2;

					}
				}


				xmlhttp.open("GET", "purchase-view.php?po_no=" + str, true);
				xmlhttp.send();


			}
		</script>




		<script type="text/javascript">
			function sendemail() {
				var xmlhttp;
				var po_no = document.getElementById("email_po").value;
				var invoice_mail = document.getElementById("email_invoice").value;
				var email_content = document.getElementById("email_content").value;
				//alert(invoice_mail);
				if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp = new XMLHttpRequest();
				} else { // code for IE6, IE5
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}

				xmlhttp.onreadystatechange = function () {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						var str2 = xmlhttp.responseText;
						document.getElementById("email_invoice").value = "";
						document.getElementById("email-content1").innerHTML = str2;
					}
				}

				xmlhttp.open("GET", "purchase_email.php?po_no=" + po_no + "&invoice_mail=" + invoice_mail + "&email_content=" + email_content, true);
				xmlhttp.send();

			}
		</script>
		<script type="text/javascript">
			/** Delete **/




			/** Permanent **/

		</script>





		<!-- jQuery Library -->



		<script type='text/javascript' src="js/jquery-1.11.2.min.js"></script>
		<script type='text/javascript' src="js/plugins.js"></script>
		<script type='text/javascript' src='https://code.jquery.com/jquery-1.10.1.js'></script>
		<script type="text/javascript" language="javascript" src="js/jquery.js"></script>
		<script type='text/javascript' src="js/WINDOWOPEN/jquery.js"></script>
		<script type="text/javascript" src="js/fnReloadAjax.js"></script>
		<script type='text/javascript' src="js/WINDOWOPEN/jquery.min.js"></script>
		<script type='text/javascript' src="js/WINDOWOPEN/bootstrap.min.js"></script>
		<script type='text/javascript' src="js/perfect-scrollbar.min.js"></script>
		<script type='text/javascript' src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.3/js/materialize.js"></script>

		<!-- jQuery libs -->
		<script type="text/javascript" src="js/jquery.print.js"></script>
		<script type="text/javascript">
			$(function () {
				$("#print_content").click(function () {
					// Print the DIV.
					$("#disp-content").print();
					$('#overlay').modal('hide');

					return (false);
				});
			});
		</script>

		<script type="text/javascript" language="javascript" src="js/jquery.dataTables.js"></script>
	</body>

	</html>