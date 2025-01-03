<?php
session_start();
	  ?>
<!doctype html>
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
                      <?php 
					 include "connect.php";
					 $sql_bal = "select * from stocklist where stk_received <=50";
					 $result_bal= mysql_query($sql_bal,$conn);
					 $num_rows = mysql_num_rows($result_bal);					 
					 ?>
                     
                      <ul class="right hide-on-med-and-down " >  
                   
                      <li class=""><a href="javascript:void(0);" class="badge1 waves-effect waves-block waves-light notification-button" data-activates="notifications-dropdown"  data-badge="<?php echo $num_rows ?> " ><i class="material-icons">notifications</i>
                        
                        </a><ul id="notifications-dropdown" class="dropdown-content " >
                      <li>
                        <h5>NOTIFICATIONS</h5>
                      </li>
                      
                    
                      <?php while($row_bal = mysql_fetch_array($result_bal))
					  {
						    echo '<li class="divider"></li>';
					 
					  ?>
                      <li class="">
                        <a href="#!"><?php echo $row_bal["pro_name"] ?></a>
                       
                      </li>
                 
                      <?php  } ?>
                      
                      
                    </ul>
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
                        
                        <a class="btn-flat dropdown-button waves-effect waves-light white-text profile-btn" href="#" data-activates="profile-dropdown">John Doe<i class=" material-icons right">arrow_drop_down</i></a><ul id="profile-dropdown" class="dropdown-content">
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
          </ul>
        <a href="#" data-activates="slide-out" class="sidebar-collapse btn-floating btn-medium waves-effect waves-light hide-on-large-only cyan"><i class="mdi-navigation-menu"></i></a>
      </aside>
      <!-- END LEFT SIDEBAR NAV-->

      <!-- //////////////////////////////////////////////////////////////////////////// -->

      <!-- START CONTENT -->
      <section id="content">
      
        <div id="breadcrumbs-wrapper" class=" grey lighten-3">
          <div class="container-title">

            <div class="row">
              <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Inventory</h5>
              <div  class='left leftspace navigations'><a href="order.php" class='btn-floating btn-large waves-effect waves-light'><i class='material-icons'>arrow_back</i></a></div>
                   <div  class='right rightspace navigations'><span><a class="btn-floating btn-large waves-effect waves-light" title="Delete" id="del_all" style="display:none" > <i class="material-icons" title="Cancel">delete</i></a></span> 
                </div>
              </div>
            </div>

          </div>
        </div>
   
        

      
        <div class="container">
         <div class="section topspacelarge" >
          <div class="row" align="center">
          <div id="result"></div>
          <!-- Select Box
              <div class="col s4 leftspace">
             <select id="prostatus" name="prostatus" onChange="changestatus1()">
<option value="open">In Usage</option>
<option value="cancel">Deleted</option>
</select> <br>&nbsp;
</div> -->
</div>
<div id="result"></div>
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










  

  <!-- edit -->
<div class="modal fade" id="overlay-edit" style="">
  <div class="modal-dialog" style="width:600px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h4 class="modal-title">Stock Update</h4>
      </div>
      <div class="modal-body" >

      <div style="width:980px;" id="edit-content">
      
  
 
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
function OnChangeCheckbox (checkbox) {
            if (checkbox.checked) {
               // alert ("The check box is checked.");
				  document.getElementById("fitem1").setAttribute("style", "display:block");
            }
            else {
               document.getElementById("fitem1").setAttribute("Style", "display:none");
            }
        }


 function OnChangeCheckbox1 (checkbox) {
            if (checkbox.checked) {
               // alert ("The check box is checked.");
				  document.getElementById("ret_qty").setAttribute("style", "display:block");
				  document.getElementById("ret_qty1").setAttribute("style", "display:block");
				 
            }
            else {
               document.getElementById("ret_qty").setAttribute("Style", "display:none");
			    document.getElementById("ret_qty1").setAttribute("Style", "display:none");
			 
            }
        }
		
		function OnChangeCheckbox2 (checkbox) {
            if (checkbox.checked) {
               // alert ("The check box is checked.");
				  document.getElementById("brk_qty").setAttribute("style", "display:block");
				  document.getElementById("brk_qty1").setAttribute("style", "display:block");
            }
            else {
               document.getElementById("brk_qty").setAttribute("Style", "display:none");
			   document.getElementById("brk_qty1").setAttribute("Style", "display:none");
            }
        }

	function tablesorter()
{
	
$(function () {
	$('.percent').percentageLoader({
		valElement: 'p',
		strokeWidth: 5,
		bgColor: '#d9d9d9',
		ringColor: '#2da3a5',
		textColor: '#2C3E50',
		fontSize: '14px',
		fontWeight: 'normal'
	});	
		
 var t = $('#itemsTable').DataTable( {
        "columnDefs": [ {
            "searchable": false,
            "orderable": false,
            "targets": 0
        } ],
        "order": [[ 1, 'asc' ]]
    } );
 
    t.on( 'order.dt search.dt', function () {
        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();
	
	
	  
		
		
	
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
 
  
   tablesorter();
 loadData();
 changestatus1();
 function loadData()
  {
	   var dataString="&action=view";
      $.ajax({
      url: "inventory_load.php",
      type: "GET",
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
	    var dataString="&action=view";
      $.ajax({
      url: "inventory_load.php",
      type: "GET",
      data: dataString,
      success:function(data)
      {
        $('#disp-area').html(data);
		tablesorter();
		
      }
    });
  }

function fun_edit(str)
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
	  document.getElementById("edit-content").innerHTML = str2;
        }
      }
	
//alert(str);
      xmlhttp.open("GET","inventory_load.php?stock_id="+str+"&action=edit", true);
      xmlhttp.send();
	  $('#overlay-edit').modal('show');
}

/**

function fun_edit(str)
  {
	    var dataString="&stock_id="+str+"action=edit";
      $.ajax({
      url: "inventory_load.php",
      type: "POST",
      data: dataString,
      success:function(data)
      {
		  
        $('#edit-content').html(data);

		
      }
    });
  }
**/

$(document).on('submit','.fm-edit',function(){
   
alert($(".fm-edit").serialize());
      $('#logerror').html('<img src="ajax.gif" align="absmiddle"> Please wait...');  
        $.ajax({
        type: "get",
        url:"inventory_load.php",
        data: $(".fm-edit").serialize(), // serializes the form's elements.
        success: function(data)
        {
			
         loadData();
		 document.getElementById("result").innerHTML = data;
		$('#overlay-edit').modal('hide');
		
			
        }
     });
  return false;
    });


function popupclose()
{
	$('#overlay-edit').modal('hide');
}








</script>
 <script type='text/javascript' src='https://code.jquery.com/jquery-1.10.1.js'></script>

 <script type='text/javascript' src="js/WINDOWOPEN/jquery.js"></script>
<script type='text/javascript' src="js/WINDOWOPEN/jquery.min.js"></script>
<!-- Circular Bar -->
<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="js/jQuery.circleProgressBar.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.4/raphael-min.js"></script>
<!-- End -->
<script type="text/javascript" language="javascript" src="media/js/jquery.dataTables.js"></script>
<script type='text/javascript' src="js/WINDOWOPEN/bootstrap.min.js"></script>

</body>
</html>