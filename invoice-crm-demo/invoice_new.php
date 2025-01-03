<?php
session_start();
include "writelog.php";
include "connect1.php";
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

  <title>S.I.S Payment</title>

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
   <script src="https://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
      <script type="text/javascript" src="https://code.jquery.com/jquery-1.8.3.js"></script>
      <script type="text/javascript">var j = jQuery.noConflict();</script>
     <script type='text/javascript' src='https://code.jquery.com/jquery-1.7.js'></script>
     
<!-- Date -->
  <script>
 $(function() 
 { 
      $("#ddate" ).datepicker({
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
    <script src="https://code.jquery.com/jquery-1.8.3.min.js"></script>
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
	<script src="https://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
<![endif]-->
<!-- Purchase New Jquery -->

    
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
			$result= mysqli_query($conn,$sql_menu);
			while($row = mysqli_fetch_array($result))
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
      <!-- END LEFT SIDEBAR NAV-->

      <!-- //////////////////////////////////////////////////////////////////////////// -->

      <!-- START CONTENT -->
      <section id="content">
       <!-- Page disp Area -->     
       <form method="post" id="fm" action="invoice_insertrow.php"> 
<div id='breadcrumbs-wrapper' class='grey lighten-3'>
      <div id="pur-head" class='container-title'>
             <div class='row'>
               <div class='col s12 m12 l12'>
                 <h5 class='breadcrumbs-title'>Invoice New</h5>
                
                <div id="pur-head" class='left leftspace navigations'><a href="invoice.php" class='btn-floating btn-large waves-effect waves-light  pink'><i class='material-icons'>arrow_back</i></a></div>
              </div>
            </div>
          </div>
        </div>
      
        <div class='row white' id="pur-new">
       <!-- Disp Area --> 
   
<div id="disp-area" align='center'>
    

    
</div>

</div>

</form>
    </section>
    
      <!-- END CONTENT -->
 <!-- //////////////////////////////////////////////////////////////////////////// -->

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
	
 
<script src="https://code.jquery.com/jquery-2.1.1.js"></script>
<script type="text/javascript">      var j = jQuery.noConflict();</script>
    <!-- Our jQuery Script to make everything work -->

<script type="text/javascript" src="js/autocomplete_payment.js"></script>


<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery-ui.min.js"></script>
<script type="text/javascript" src="js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
 <script type='text/javascript' src="js/perfect-scrollbar.min.js"></script>
<script type='text/javascript' src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.3/js/materialize.js"></script>


<script type="text/javascript" language="javascript">
 function changetax()
  {
      	 var gstvalue = $("#gstvalue").val();
	  //alert(action);
       $('#ino').css('display','block');
       $('#ino').html('<img src="images/loading.gif" alt="loading">');
  $("#gstper").val(gstvalue);
      block = $("#blockname").val();
       projectname = $("#project_name").val();
        if(gstvalue == '0')
         {
         gststatus = 'nil';
         }
         else
         {
         gststatus = 'included';
         }
    var dataString="&gststatus="+gststatus+"&projectname="+projectname+"&block="+block;
      $.ajax({
      url: "get_invoice.php",
      type: "POST",
      data: dataString,
      success:function(data)
      {
         console.log("data..........", data)
           $('#ino').html('');
           $('#subvalue').val('0');
          $('#itemsTable1').css('display','block');
          var str = data;
          data_disp = str.trim();
          document.getElementById('po_no').value = data_disp;
		    //  document.getElementById('po_no1').value = data_disp;
		
      }
    });
  }
	
	</script>
       <script>  
$(document).ready(function(){
 loadData();

 function loadData()
  {
	var action = $("#newstatus").val()
     $('#disp-area').html('<img src="images/loading.gif" alt="loading">');
    var dataString="&type="+action;
      $.ajax({
      url: "invoice_newload.php",
      type: "POST",
      data: dataString,
      success:function(data)
      {
        $('#disp-area').html(data);
		 tablesorter();	
		changetax();
	
		
      }
    });
  }
  
 });


 function loadData()
  {
	   var action = $("#newstatus").val()
    var dataString="&type="+action;
        $('#disp-area').html('<img src="images/loading.gif" alt="loading">');
      $.ajax({
      url: "invoice_newload.php",
      type: "POST",
      data: dataString,
      success:function(data)
      {
        $('#disp-area').html(data);
		tablesorter();
		changetax();
		
      }
    });
  }






</script>
<script>

function tablesorter()
	{		
$(function () {		 
    
	
		
		/** reset **/
		$('#clear').click(function(){
   //alert("hi");
	$(".resetThis").val("");
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