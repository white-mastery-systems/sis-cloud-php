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
<html lang="en"><head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection">
  <link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection">
    <!-- Custome CSS-->    
    <link href="css/custom-style.css" type="text/css" rel="stylesheet" media="screen">
     <link href="css/materialfamily.css" type="text/css" rel="stylesheet" media="screen">
     <link rel="stylesheet" type="text/css" href="media/css/jquery.dataTables.css">
<!-- viewport for Floating Button -->
   <link href="css/mfb.css" rel="stylesheet">
<!-- viewport meta to reset iPhone inital scale -->
<link rel="stylesheet" href="css/bootstrap.css" type="text/css" media="screen" charset="utf-8" />
   	<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.4.custom.css">
    	<link rel="stylesheet" type="text/css" href="css/perfect-scrollbar.css">
   <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
      <script type="text/javascript" src="https://code.jquery.com/jquery-1.8.3.js"></script>
      <script src="js/script.js"></script>
      <script type="text/javascript"> var j = jQuery.noConflict();</script>
     



<title></title>

<!-- css3-mediaqueries.js for IE8 or older -->
<!--[if lt IE 9]>
	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
<![endif]-->
<style type="text/css">
   <!--
   @page { size:8.5in 11in; margin: 1.8in 0.5in 1in 0.5in; 
   
   }
   @media print {
  a[href]:after {
    content: none !important;
  }
}
   -->
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
            <a href="vendor.php" data-mfb-label="Add Vendor" class="mfb-component__button--child">
              <i class="mfb-component__child-icon material-icons">person_add</i>
            </a>
          </li>

          <li>
            <a href="products.php" data-mfb-label="Add Products" class="mfb-component__button--child">
              <i class="mfb-component__child-icon ion-social-twitter"><img src="images/products_addmall.png" /></i>
            </a>
          </li>
        </ul>
      </li>
    </ul>
 </span>
  <span class="right navigations1"><a class="btn-floating btn-large waves-effect waves-light" title="Delete" id="del_all" style="display:none"> <i class="material-icons" title="Cancel">delete</i></a></span> 
              </div>
            </div>

          </div>
        </div>
   
        

      
        <div class="container">
     
          <div class="section topspace" >
          <div class="row" align="center">
              <div class="col s4 leftspace">
             <select id="postatus" name="postatus" onChange="changestatus1()">
<option value="open">Issued</option>
<option value="cancel">Cancelled</option>
</select> <br>&nbsp;
</div></div>
<div id="disp-area" class="table-responsive">
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
			

     <!-- Container End -->
<!-- Popup Window -->
<!-- Print -->
 <div class="modal fade" id="overlay" style="">
  <div class="modal-dialog" style="width:750px;">
    <div class="modal-content">
      <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true" id="close_but"><i class="material-icons">clear</i></button>

        <h4 class="modal-title">Purchase Order</h4>
      </div>
      <div class="modal-body" id="modalbody">
      <button id="print_content" name="print_content">Print Content</button>

      <div style="width:600px;" id="disp-content">





</div>

</div>
</div>
</div>
</div>
  
  <!-- Email -->
   <div class="modal fade" id="overlay1" style="">
  <div class="modal-dialog" style="width:600px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="material-icons">clear</i></button>
        <h4 class="modal-title">Send Email</h4>
      </div>
      <div class="modal-body" >

      <div style="width:980px;" id="email-content">
      <div style="width:980px;" id="email-content1"></div>
      <form id="emailForm" method="get" novalidate="novalidate">
      <div id="email-content"></div>
      <label for="exampleInputEmail1">Email Address: </label>
      <input type="email" class="form-control" name="email_invoice" id="email_invoice" placeholder="Enter email" style="width:400px"/>
      <input type="hidden" class="form-control" name="email_po" id="email_po" value="">
<br />&nbsp;
 <button data-loading-text="Email Sending..." id="email_btn" type="button" class="btn btn-success" autocomplete="off" onClick="sendemail()">Send Email!</button>
</form>


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
                
                <input name="action" id="action" value="deletet" style="border:none" type="hidden">
                			</div>
 <div align="right" style="padding-top:15px; padding-right:40px">
	
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
			</div>
 <div align="right" style="padding-top:15px; padding-right:40px">
	 <button type='submit' class='btn-floating btn-medium waves-effect waves-light' title='submit'><i class='material-icons small'>done</i></button>  <button type='button' id="clear" class='btn-floating btn-medium waves-effect waves-light' onClick="popupclose()"><i class='material-icons small'>clear</i></button>


</div>
			   </form>
			
 
</div>

</div>
</div>
</div>
</div>

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
			
			// Delete temp
		
		$('.use-delete').click(function () {
						   
	$('#overlaydelete').modal('show');
		var po_no = $(this).closest("tr").find('td:eq(2)').text();
	//document.getElementById("req_id").value = $(this).closest("tr").find('td:eq(0)').text();
	document.getElementById("po_no").value = po_no;
	});
		
	$('.use-delete1').click(function () {
						   
	$('#overlaydeletep').modal('show');
		var po_no = $(this).closest("tr").find('td:eq(2)').text();
	document.getElementById("po_no2").value = po_no;

	});	

		
  $('#itemsTable').dataTable();
  
			});
	}
	
	
	 function changestatus1()
  {
	  var action = $("#postatus").val();
	// alert(action);
    var dataString="&action="+action;
      $.ajax({
      url: "order_load.php",
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
	 
    var action = $("#postatus").val();	

    var dataString="&action="+action;
	$.ajax({
      url: "order_load.php",
      type: "POST",
      data: dataString,
      success:function(data)
      {
        $('#disp-area').html(data);
		tablesorter();	
		changestatus1();
      }
    });
  }
  
  
 });



 function loadData()
  {
	  var action = $("#postatus").val();	  
      var dataString="&action="+action;
      $.ajax({
      url: "order_load.php",
      type: "POST",
      data: dataString,
      success:function(data)
      {
        $('#disp-area').html(data);
		tablesorter();
		
      }
    });
  }



function popupclose()
{
	$('#overlaydelete').modal('hide');
	$('#overlaydeletep').modal('hide');
}


function fun_duplicate(str) 
{
       window.location = 'purchase_duplicate.php?po_no='+str;
}

function fun_edit(str) 
{
       window.location = 'purchase_edit.php?po_no='+str;
}

function fun_pdf(str) 
{
      location.href="purchase_pdf1.php?po_no="+str;

}
/**
function fun_print(str) 
{
      location.href="purchase_print.php?po_no="+str;

}
**/

function fun_print(str) 
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
	  document.getElementById("disp-content").innerHTML = str2;
        }
      }
	
		
      xmlhttp.open("GET","purchaseorderprint.php?po_no="+str, true);
      xmlhttp.send();
	  $('#overlay').modal('show');

}

function fun_email(str) 
{
	$('#overlay1').modal('show');
 document.getElementById("email-content1").innerHTML="";
     document.getElementById("email_po").value = str;

}


    function viewpo(str) {
            var xmlhttp;

            if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            }
            else {// code for IE6, IE5
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
function sendemail()
{
	var xmlhttp;
 var po_no= document.getElementById("email_po").value;
 var invoice_mail= document.getElementById("email_invoice").value;
 //alert(invoice_mail);
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
	   document.getElementById("email_invoice").value="";
	  document.getElementById("email-content1").innerHTML = str2;
        }
      }
	
		
      xmlhttp.open("GET","purchase_email.php?po_no="+po_no+"&invoice_mail="+invoice_mail, true);
      xmlhttp.send();
	  
}



</script>
<script type="text/javascript">
/** Delete **/

$(document).on('submit','.fm-delete',function(){
   
alert($(".fm-delete").serialize());
      $('#logerror').html('<img src="ajax.gif" align="absmiddle"> Please wait...');  
        $.ajax({
        type: "POST",
        url:"order_load.php",
        data: $(".fm-delete").serialize(), // serializes the form's elements.
        success: function(data)
        {
         //$("#itemsTable").append($(data));
            // $('#user_update')[0].reset(); 
								

		
		alert(data);
		loadData();
			

		$('#overlaydelete').modal('hide');
		$('#overlaydeletep').modal('hide');
			
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
        url:"order_load.php",
        data: $(".fm-delete1").serialize(), // serializes the form's elements.
        success: function(data)
        {
         //$("#itemsTable").append($(data));
            // $('#user_update')[0].reset(); 
								

		
		alert(data);
		loadData();			
		$('#overlaydeletep').modal('hide');
			
        }
     });
  return false;
    });


</script>


 <!-- jQuery Library -->



<script type='text/javascript' src="js/jquery-1.11.2.min.js"></script>
<script type='text/javascript' src="js/plugins.js"></script>
 <script type='text/javascript' src='https://code.jquery.com/jquery-1.10.1.js'></script>
<script type="text/javascript" language="javascript" src="media/js/jquery.js"></script>
<script type='text/javascript' src="js/WINDOWOPEN/jquery.js"></script>
<script type='text/javascript' src="js/WINDOWOPEN/jquery.min.js"></script>
<script type='text/javascript' src="js/WINDOWOPEN/bootstrap.min.js"></script>
 <script type='text/javascript' src="js/perfect-scrollbar.min.js"></script>
<script type='text/javascript' src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.3/js/materialize.js"></script>

  <!-- jQuery libs -->
<script type="text/javascript" src="js/jquery.print.js"></script>
<script type="text/javascript">
$(function() {
$("#print_content").click(function() {
// Print the DIV.
$("#disp-content").print();
	  $('#overlay').modal('hide');

return (false);
});
});
</script>

<script type="text/javascript" language="javascript" src="media/js/jquery.dataTables.js"></script>

</body>
</html>
