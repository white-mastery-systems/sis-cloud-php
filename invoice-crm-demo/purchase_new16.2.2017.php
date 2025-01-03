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
$msg = "IP: " . $ip . " TIME: " . $time . " User: " .$_SESSION['st_username'].  " USERAGENT: " .$userAgent. "Action :Purchase New Page";
writeToLogFile($msg);
//$lognme ;
 if (!isset($_SESSION['st_emailid']) and !isset($_SESSION['st_pwd']))
 {
   	header("Location: login.php");
	 }
    $_SESSION['st_emailid'];$_SESSION['st_pwd'];
	  ?>

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
   <link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
  <!-- INCLUDED PLUGIN CSS ON THIS PAGE -->
      <link rel="Stylesheet" href="css/bootstrap.css"  media="screen"/>
     <!-- <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"> -->
      <link href="css/materialfamily.css" type="text/css" rel="stylesheet" media="screen">
 
    	 <link rel="stylesheet" type="text/css" href="css/jquery-ui.min.css" media="screen"/>
   <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
      <script type="text/javascript" src="https://code.jquery.com/jquery-1.8.3.js"></script>
      
     
      <script type="text/javascript">          var j = jQuery.noConflict();</script>

     <script type='text/javascript' src='http://code.jquery.com/jquery-1.7.js'></script>
     
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
<script>
    // fix for IE < 11
    if ($("<input />").prop("required") === undefined) {
        $(document).on("submit", function(e) {
            $(this)
                    .find("input, select, textarea")
                    .filter("[required]")
                    .filter(function() { return this.value == ''; })
                    .each(function() {
                        e.preventDefault();
                        $(this).css({ "border-color":"red" });
                        alert( $(this).prev('label').html() + " is required!");
                    });
        });

    }
	</script>
    <script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
<!-- Your scripts-->
<script type='text/javascript'>
    //This will execute when your page is loaded
	$(document).ready(function(){
    $("#frm").submit(function(e) {

    var ref = $(this).find("[required]");

    $(ref).each(function(){
        if ( $(this).val() == '' )
        {
            alert("Required field should not be blank.");

            $(this).focus();

            e.preventDefault();
            return false;
        }
    });  return true;
});
							   });
    //This checks if a specific attribute is supported
     jQuery.noConflict();
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
	
	//alert("hi");
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
		 //document.getElementById('payment').value = res1[8]; 
		 document.getElementById('city').value = res1[9];
           
        }
      }
	  

      xmlhttp.open("GET","get_projectlist.php?project_name="+str1, true);
      xmlhttp.send();
  }
</script>    
<script type='text/javascript' src='https://code.jquery.com/jquery-1.9.1.js'></script>




</head>

<body>
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
       <!-- Page disp Area -->     
       <form method="post" id="fm" action="purchase_insertrow.php"> 
<div id='breadcrumbs-wrapper' class='grey lighten-3'>
      <div class='container-title'>
             <div class='row'>
               <div class='col s12 m12 l12'>
                 <h5 class='breadcrumbs-title'>Purchase New</h5>
                
                <div class='left leftspace navigations'><a href="order.php" class='btn-floating btn-large waves-effect waves-light  pink'><i class='material-icons'>arrow_back</i></a></div>
              </div>
            </div>
          </div>
        </div>
      
        <div class='row white'>
       <!-- Disp Area -->
       <div class="row topspacelarge" align="center">
              <div class="col s2 leftspace">
<select id="newstatus" name="newstatus" onChange="changestatus1()">
<option value="standard">Standard</option>
<option value="upvc">UPVC</option>
<option value="steel">Steel</option>
<option value="door">Door</option>


</select> &nbsp;
</div>
<div class="col s2 leftspace">
<input type='text' placeholder='Folder' id='folder' name='folder' class='ui-autocomplete-input resetThis'  value='' autocomplete='off' required ><br>&nbsp;
</div></div>
<div id="disp-area"></div>

</div>

</form>
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
    <script type='text/javascript' src="js/jquery-1.11.2.min.js"></script>
<script type='text/javascript' src="js/plugins.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-1.10.1.js"></script>

    <script type="text/javascript" language="javascript" src="media/js/jquery.js"></script>
	
 
<script src="http://code.jquery.com/jquery-2.1.1.js"></script>
<script type="text/javascript">      var j = jQuery.noConflict();</script>
    <!-- Our jQuery Script to make everything work -->
<script type="text/javascript" src="js/autocomplete.js"></script>
<script type="text/javascript" src="js/autocomplete1.js"></script>
<script type="text/javascript" src="js/autocomplete_steel.js"></script>
<script type="text/javascript" src="js/autocomplete_door.js"></script>

<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery-ui.min.js"></script>
<script type="text/javascript" src="js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
 <script type='text/javascript' src="js/perfect-scrollbar.min.js"></script>
<script type='text/javascript' src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.3/js/materialize.js"></script>


<script type="text/javascript" language="javascript">

	
	 function changestatus1()
  {
	  var action = $("#newstatus").val();
	  //alert(action);
    var dataString="&type="+action;
      $.ajax({
      url: "purchase_newload.php",
      type: "POST",
      data: dataString,
      success:function(data)
      {
        $('#disp-area').html(data);
		tablesorter();
		 callpono();
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
 
  

 loadData();
 changestatus1();
 function loadData()
  {
	var action = $("#newstatus").val()
    var dataString="&type="+action;
      $.ajax({
      url: "purchase_newload.php",
      type: "POST",
      data: dataString,
      success:function(data)
      {
        $('#disp-area').html(data);
		 tablesorter();	
		  callpono();
		 changestatus1();
		
      }
    });
  }
  
 });


 function loadData()
  {
	   var action = $("#newstatus").val()
    var dataString="&type="+action;
      $.ajax({
      url: "purchase_newload.php",
      type: "POST",
      data: dataString,
      success:function(data)
      {
        $('#disp-area').html(data);
		tablesorter();
		callpono();
		
      }
    });
  }






</script>
<script>

function tablesorter()
	{
		
$(function () {
		 
    $('#vendor_name').autocomplete({
    		source: function( request, response ) {
    			$.ajax({
    				url : 'autocomplete1.php',
    				dataType: "json",
    				method: 'post',
    				data: {
    					name_startsWith: request.term,
    					type: 'folder_name'
    				},
    				success: function( data ) {
                        alert(data);
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
        		var names = ui.item.data.split("|");
    			$("#vendor_name").val(names[1]);
				$("#ven_add").val(names[3]);
				$("#ven_id").val(names[0]);
				$("#ven_contactperson").val(names[2]);
			
    		
    		}		      	
    	});
    
    
		$('#folder').autocomplete({
    		source: function( request, response ) {
    			$.ajax({
    				url : 'autocompletefolder.php',
    				dataType: "json",
    				method: 'post',
    				data: {
    					name_startsWith: request.term,
    					type: 'folder_name'
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
        		
    			var namesf = ui.item.data.split("|");
    			$("#folder").val(namesf[1]);
			
    		
    		}		      	
    	});
		
		
		/** reset **/
		$('#clear').click(function(){
   //alert("hi");
	$(".resetThis").val("");
});
		/** table row add standard Purchase **/
var i=$('#itemsTable tr').length;
$(".addmore1").on('click',function(){
								 
	html = '<tr>';
	html += '<td><input type="checkbox" id="selecttd'+i+'" class="case" /><label for="selecttd'+i+'"></label></td>';
	html += '<td><div class="input-field col s8 "><input type="text" data-type="pro_name" name="itemDesc[]" id="itemDesc_'+i+'" class=" autocomplete_txt1 resetThis" autocomplete="off" placeholder="DESC"> <input name="sub_catname[]"  class="resetThis" id="sub_catname_'+i+'" type="hidden"/> <input name="brand[]"   id="brand_'+i+'" tabindex="1" type="hidden"  /> <input name="itemCode[]"  class="resetThis" id="itemCode_'+i+'"  type="hidden" data-type="product_code" /><input name="size[]" class="resetThis" id="size_'+i+'" tabindex="1" type="hidden"  /><input name="cat_name[]"  class="resetThis" id="cat_name_'+i+'" type="hidden"/></div></td>';
	
	html += '<td><div class="input-field col s8 "><input type="text" name="itemQty[]" id="itemQty_'+i+'" class="changesNo1 resetThis" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" placeholder="QTY"></div></td>';
	html += '<td><div class="input-field col s8 "><input type="text" name="units[]" id="units_'+i+'" class="resetThis" autocomplete="off" placeholder="Unis"></div></td>';
		html += '<td><div class="input-field col s8 "><input type="text" name="itemPrice[]" id="itemPrice_'+i+'" class="changesNo1 resetThis" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" placeholder="Price"></div></td>';	
		html += '<td><div class="input-field col s8 "><input type="text" name="itemVat[]" id="itemVat_'+i+'" class="changesNo1 resetThis" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" placeholder="VAT"></div></td>';
	html += '<td><div class="input-field col s8 "><input type="text" name="itemLineTotal[]" id="itemLineTotal_'+i+'" class="totalLinePrice_s resetThis" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" required="required" placeholder="Total" ></div></td>';
	html += '</tr>';
	$('#itemsTable').append(html);
	i++;
});


//deletes the selected table rows
$(".delete1").on('click', function() {
	$('.case:checkbox:checked').parents("tr").remove();
	$('#check_all').prop("checked", false); 
	calculateTotal1();
});


	/** table row add steel Purchase **/
		var i=$('#itemsTable tr').length;
$(".addmore_st").on('click',function(){
								 
html = '<tr>';
html += '<td><input type="checkbox" id="selecttd'+i+'" class="case" /><label for="selecttd'+i+'"></label></td>';
html += '<td><div class="input-field col s8 "><input type="text" data-type="pro_name" name="itemDesc[]" id="itemDesc_'+i+'" class=" autocomplete_txtsteel resetThis " autocomplete="off" required="required" placeholder="DESC" > <input name="sub_catname[]"  class="resetThis" id="sub_catname_'+i+'" type="hidden"/> <input name="itemCode[]"  class="resetThis" id="itemCode_'+i+'"  type="hidden" data-type="product_code" /><input name="size[]" class="resetThis" id="size_'+i+'" tabindex="1" type="hidden"  /><input name="cat_name[]"  class="resetThis" id="cat_name_'+i+'" type="hidden"/></div></td>';
html += '<td><div class="input-field col s8"><input name="details[]"   id="details_'+i+'" type="text" required="required" placeholder="Details" /></div></td>';
html += '<td><div class="input-field col s8 "><input type="text" name="itemQty[]" id="itemQty_'+i+'" class="changesNo_st resetThis" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" required="required" placeholder="QTY"/>  <input  class="units resetThis" id="units_'+i+'" name="units[]" tabindex="1" type="hidden" placeholder="units" /></div></td>';
html += '<td><div class="input-field col s8 "><input type="text" name="itemPrice[]" id="itemPrice_'+i+'" class="changesNo_st resetThis" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" required="required" placeholder="Price" /></div></td>';
html += '<td><div class="input-field col s8 "><input type="text" name="itemVat[]" id="itemVat_'+i+'" class="changesNo_st resetThis" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" required="required" placeholder="VAT" ></div></td>';
html += '<td><div class="input-field col s8"><input name="brand[]"   id="brand_'+i+'" tabindex="1" type="text" required="required" placeholder="Brand"   /></div></td>';
html += '<td><div class="input-field col s8 "><input type="text" name="itemLineTotal[]" id="itemLineTotal_'+i+'" class="totalLinePricest resetThis" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" required="required" placeholder="Total" ></div></td>';
html += '</tr>';
$('#itemsTable').append(html);
i++;
});


//deletes the selected table rows
$(".delete_st").on('click', function() {
	$('.case:checkbox:checked').parents("tr").remove();
	$('#check_all').prop("checked", false); 
	calculateTotal2();
});

/** table row add door Purchase **/
		var i=$('#itemsTable tr').length;
$(".addmore_dr").on('click',function(){
								 
html = '<tr>';
html += '<td><input type="checkbox" id="selecttd'+i+'" class="case" /><label for="selecttd'+i+'"></label></td>';
html += '<td> <div class="input-field col s8 "><input  data-type="product_code"  id="itemCode_'+i+'"  name="itemCode[]" class="autocomplete_txtdoor resetThis" type="text"  required autocomplete="off" required="required" placeholder="Code"  /> </div></td>';
html += '<td><div class="input-field col s8 "><input type="text" data-type="pro_name" name="itemDesc[]" id="itemDesc_'+i+'" class=" autocomplete_txtdoor resetThis " autocomplete="off" required="required" placeholder="DESC" > <input name="sub_catname[]"  class="resetThis" id="sub_catname_'+i+'" type="hidden"/> <input name="brand[]"   id="brand_'+i+'" tabindex="1" type="hidden"  /> <input name="cat_name[]"  class="resetThis" id="cat_name_'+i+'" type="hidden"/></div></td>';
html += '<td> <div class="input-field col s8 "><input    id="size_'+i+'"  name="size[]" class="resetThis changesNo_dr"  type="text" autocomplete="off" required="required" placeholder="size"   /> </div></td>';
html += '<td><div class="input-field col s8 "><input type="text" name="itemQtyd[]" id="itemQtyd_'+i+'" class="changesNo_dr resetThis" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" required="required" placeholder="QTY" > <input type="hidden" name="itemQtyds[]" id="itemQtyds_1" class="resetThis" autocomplete="off" onKeyPress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;"></div></td>';
html += '<td><div class="input-field col s8 "><input type="text" name="itemQty[]" id="itemQty_'+i+'" class="changesNo_dr resetThis" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" required="required" placeholder="CFT" readonly>  <input class="units resetThis" id="units_'+i+'" name="units[]" tabindex="1" type="hidden" required placeholder="units" /></div></td>';
html += '<td><div class="input-field col s8 "><input type="text" name="itemPrice[]" id="itemPrice_'+i+'" class="changesNo_dr resetThis" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" required="required" placeholder="Price"></div></td>';
html += '<td><div class="input-field col s8 "><input type="text" name="itemLineTotal[]" id="itemLineTotal_'+i+'" class="totalLinePricedr resetThis" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" required="required" placeholder="Total" ></div></td>';
html += '</tr>';
$('#itemsTable').append(html);
i++;
});


//deletes the selected table rows
$(".delete_dr").on('click', function() {
	$('.case:checkbox:checked').parents("tr").remove();
	$('#check_all').prop("checked", false); 
	calculateTotal3();
});


/** add row to the UPVC purchase **/

var i=$('#itemsTable tr').length;
$(".addmore").on('click',function(){
								  
	html = '<tr>';
	html += '<td><input type="checkbox" id="selecttd'+i+'" class="case" /><label for="selecttd'+i+'"></label></td>';
		html += '<td> <div class="input-field col s8 "><input  data-type="product_code"  id="itemCode_'+i+'"  name="itemCode[]" class="autocomplete_txt resetThis" type="text" autocomplete="off" required="required" placeholder="Code"  /> <input  id="itemBrand_'+i+'"  name="itemBrand[]" class="resetThis" type="hidden" autocomplete="off" required="required"  /></div></td>';
	html += '<td><div class="input-field col s8 "><input name="itemWidth[]" id="itemWidth_'+i+'"  type="text" class="resetThis"  required="required" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" placeholder="Width"/></div></td>';
	html += '<td><div class="input-field col s8 "><input name="itemHeight[]" id="itemHeight_'+i+'" type="text" pattern="[0-9]*" required="required" autocomplete="off" class="resetThis" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" placeholder="Height"/></div></td>';
	html += '<td><div class="input-field col s8 "><input name="itemQty[]"  id="itemQty_'+i+'" type="text" pattern="[0-9]*" required="required" autocomplete="off" class="changesNo resetThis" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" placeholder="QTY" /> <input name="itemUnit[]"  id="itemUnit_'+i+'" type="hidden" autocomplete="off" /></div></td>';
	html += '<td><div class="input-field col s8 "><input data-type="pro_name" name="itemDesc[]"  id="itemDesc_'+i+'" type="text" class="autocomplete_txt resetThis" tocomplete="off" required="required" placeholder="DESC"  ></div></td>';
	html += '<td><div class="input-field col s8 "><input name="itemBasic_m[]"  id="itemBasicm_'+i+'" type="text" autocomplete="off" class="changesNo resetThis" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" required="required" placeholder="M Basic"/></div></td>';
	html += '<td><div class="input-field col s8 "><input name="itemTotal_m[]" id="itemTotalm_'+i+'" readonly="readonly" type="text" autocomplete="off" class="totalLinePrice1" class="resetThis" required="required" placeholder="M Total" /></div></td>';
	html += '<td><div class="input-field col s8 "><input name="itemBasic_i[]"  id="itemBasici_'+i+'" type="text" autocomplete="off" class="changesNo" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" class="resetThis" required="required" placeholder="I Basic"/></div></td>';
	html += '<td><div class="input-field col s8 "><input name="itemTotal_i[]" id="itemTotali_'+i+'" readonly="readonly" type="text" autocomplete="off" class="totalLinePrice2 resetThis" required="required" placeholder="I Total"/></div></td>';
	html += '<td><div class="input-field col s8 "><input name="itemLineTotal[]"  id="itemLineTotal_'+i+'" readonly="readonly" type="text" placeholder="Total" autocomplete="off" class="totalLinePrice resetThis" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" required="required" placeholder="Total" /></div></td>';
	html += '</tr>';
	$('#itemsTable').append(html);
	i++;
});


//deletes the selected table rows
$(".delete").on('click', function() {
	$('.case:checkbox:checked').parents("tr").remove();
	$('#check_all').prop("checked", false); 
	calculateTotal();
});


$('.form_date').datetimepicker({
							   
		format: "dd.mm.yyyy",   
        autoclose: true,
        todayBtn: true,
        pickerPosition: "bottom-right",
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		minView: 2,
		forceParse: 0,showMeridian: 1
    });


			});




	}
	

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