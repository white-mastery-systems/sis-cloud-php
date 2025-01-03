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
	<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width">
		<link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection">
		<link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection">
		<!-- Custome CSS-->
		<link href="css/custom-style.css" type="text/css" rel="stylesheet" media="screen">
		<link href="css/materialfamily.css" type="text/css" rel="stylesheet" media="screen">
		<link rel="stylesheet" type="text/css" href="css/jquery.dataTables1.css">
		<link rel="stylesheet" type="text/css" href="css/buttons.dataTables.min.css">	
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
<script type='text/javascript' src="js/WINDOWOPEN/jquery.js"></script>
<script type='text/javascript' src="js/WINDOWOPEN/jquery.min.js"></script>
<link rel="stylesheet" href="css/jquery.scrollbar.css">
<script src="js/jquery-1.9.1.js" type="text/javascript"></script>
<script src="js/jquery.scrollbar.js"></script>
<script>
jQuery(function($){
$('.page-wrapper').scrollbar();
});
</script>
<title>Invoice Report</title>
<!-- css3-mediaqueries.js for IE8 or older -->
<!--[if lt IE 9]>
<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
<![endif]-->
<style type="text/css">
<!-- @page {
size: 8.5in 11in;
margin: 1.8in 0.2in 1in 0.2in;}
@media print {a[href]:after{content: none !important;width:800px;}}			
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
<ul class="left"><li><h1 class="logo-wrapper"><a href="#" class="brand-logo darken-1"><img src="images/logo.png" alt="S.I.S logo"></a> <span class="logo-text">S.I.S</span></h1></li></ul>
<div class="header-search-wrapper"><!-- <i class="material-icons active">search</i><input type="text" name="Search" class="header-search-input z-depth-2" >--></div>
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
<li><a href="logout.php" class="waves-effect waves-block waves-light" title="Logout"><i class="material-icons">power_settings_new</i></a></li>
</ul></div></nav></div>
<!-- end header nav-->
</header>
<!-- END HEADER -->
<!-- //////////////////////////////////////////////////////////////////////////// -->
<!-- START MAIN -->
<div id="main"><!-- START WRAPPER --><div class="wrapper">
<!-- START LEFT SIDEBAR NAV-->
<aside id="left-sidebar-nav">
<ul id="slide-out" class="side-nav fixed leftside-navigation ps-container ps-active-y" style="width: 240px;">
<li class="user-details cyan darken-2">
<div class="row"><div class="col col s4 m4 l4"><img src="images/avatar.jpg" alt="" class="circle responsive-img valign profile-image"></div>
<div class="col col s8 m8 l8"><a class="btn-flat dropdown-button waves-effect waves-light white-text profile-btn" href="#" data-activates="profile-dropdown">
<?php echo ($_SESSION['st_username'])  ?><i class="material-icons right large">arrow_drop_down</i></a>
<ul id="profile-dropdown" class="dropdown-content">
<li><a href="#"><i class="material-icons">face</i> Profile</a></li><li><a href="#"><i class="material-icons">settings</i> Settings</a></li>
<li class="divider"></li><li><a href="logout.php"><i class="material-icons">power_settings_new</i> Logout</a></li></ul>
<p class="user-roal"><?php echo $_SESSION['sess_userrole'] ?></p>
</div></div>
</li>
<li class="bold"><a href="#dashboard" class="waves-effect waves-cyan"><i class="material-icons">dashboard</i> Dashboard</a></li>
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
<!-- END LEFT SIDEBAR NAV--><!-- //////////////////////////////////////////////////////////////////////////// --><!-- START CONTENT -->
<section id="content">
<div id="breadcrumbs-wrapper" class=" grey lighten-3">
<div id="con-title" class="container-title">
<div class="row">
<div class="col s12 m12 l12">


<h5 id="inv-head" class="breadcrumbs-title">Invoice</h5>
<span id="inv-headright" class="right rightspace navigations">  
<ul id="menu" class="mfb-component--tr mfb-zoomin" data-mfb-toggle="hover">
<li class="mfb-component__wrap">
<a  href="Invoice_new.php" class="mfb-component__button--main" title="Invoice New"><i class="mfb-component__main-icon--resting material-icons">add</i><i class="mfb-component__main-icon--active material-icons">add</i></a>
</li></ul>
</span>
<span class="right navigations1"><a class="mfb-component__button--main" title="Delete" id="del_all" style="display:none"> <i class=" mfb-component__main-icon--resting material-icons" title="Cancel">delete</i></a></span> 

</div></div>
</div></div>

<div class="row white"><div class="section topspace">
<div class="row" align="center"><input type="hidden" name="place" id="place" value="<?php echo $_SESSION['place']?>">
<div class="col s2 leftspace"><select id="gststatus" name="gststatus" ><option value="included">GST</option><option value="nil">No GST</option></select>
<br>&nbsp;
</div><div class="col s2 leftspace"><?php if($_SESSION['place'] == 'Trichy'){ ?><select id="projectname" name="projectname" ><option value="S.I.S Acropole">S.I.S Acropole</option><option value="S.I.S Luxor">S.I.S Luxor</option></select><?php } else {?>
    <select id="projectname" name="projectname" ><option value="S.I.S Queenstown">S.I.S Queenstown</option><option value="S.I.S Marakesh">S.I.S Marakesh</option><option value="S.I.S Acropole">S.I.S Acropole</option><option value="S.I.S Luxor">S.I.S Luxor</option></select>
    <?php
}
?>
<br>&nbsp;
</div></div>
<div id="result"></div>
</div>
<div id="disp-area" class="table-responsive">
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
<table id="itemsTable" cellpadding="0" cellspacing="0" width="100%" border="0" class="table avail-div m-b-60 hidedata">
<thead>
<tr class="title">

<th class="font12"><b>Invoice No</b><i class="material-icons tiny1">sort</i></th>
<th class="font12"><b>Date </b> <i class="material-icons tiny1">sort</i></th>


<th class="font12"><b>Flat no</b> <i class="material-icons tiny1">sort</i></th>
<th class="font12"><b>Project Name</b> & <b>Date </b><i class="material-icons tiny1">sort</i></th>
<th class="font12"><b>Total Rs</b> <i class="material-icons tiny1">sort</i></th>
<th class="font12"><b>Status</b> <i class="material-icons tiny1">sort</i></th>
<th class="font12"><b>Action</b></th>
</tr>
</thead>
</table>
</div></div></div>
</section>
<!-- END CONTENT --><!-- //////////////////////////////////////////////////////////////////////////// -->
</div><!-- END WRAPPER --></div><!-- END MAIN -->
<!-- //////////////////////////////////////////////////////////////////////////// -->
<!-- START FOOTER -->
<footer class="page-footer">
<div class="footer-copyright"><div class="container">
<span class="leftspace">&copy;  <a class="grey-text text-lighten-4" href="#" target="_blank">Copyright South India Shelters Pvt.Ltd. </a> All rights reserved.</span>
<span class="right rightspace"> Design and Developed by <a class="grey-text text-lighten-4" href="#">S.I.S</a></span>
</div></div></footer>
<style type="text/css">
#fm{margin:0;padding:10px 20px;}
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




		<!-- Email -->
		<div class="modal fade" id="overlay1" style="">
			<div class="modal-dialog col s12 m12 l12">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="material-icons">clear</i></button>
						<h4 class="modal-title">Send</h4>
					</div>
					<div class="modal-body col s12 m12 l12">

						<div id="email-content" class="">
							
						</div>
						<div style="width:100%;" id="email-content1"></div>
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
									Are you sure want to <b>Delete</b> this
									<input name="po_no" id="po_no" value="" style="border:none" type="hidden">
								    <input name="action" id="action" value="delete" style="border:none" type="hidden">
								</div>
								
								<div align="right" style="padding-top:15px; padding-right:40px">
<div id="loaddel"></div>
									<button type='submit' class='btn-floating btn-medium waves-effect waves-light pink'  title='submit'><i class='material-icons small'>done</i></button>
									<button type='button' id="clear" class='btn-floating btn-medium waves-effect waves-light pink' onClick="popupclose()"><i class='material-icons small'>clear</i></button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		
<div class="modal fade" id="overlaycancel" style="">
<div class="modal-dialog" style="width:300px;">
<div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="material-icons">clear</i></button><h4 class="modal-title">Cancel</h4></div>
<div class="modal-body">
<div style="width:300px;" id="add-content">
<form id="fm" method="get" novalidate="novalidate" class="fm-cancel">
<div class="fitem"><input name="desc" id="desc" type="text" placeholder="Description">
Are you sure want to <b>CANCEL</b> this
<input name="po_no" id="po_no1" value="" style="border:none" type="hidden">
<input name="action" id="action1" value="cancel" style="border:none" type="hidden">
</div>								
<div align="right" style="padding-top:15px; padding-right:40px">
<div id="loaddel"></div><button type='submit' class='btn-floating btn-medium waves-effect waves-light pink'  title='submit'><i class='material-icons small'>done</i></button><button type='button' id="clear" class='btn-floating btn-medium waves-effect waves-light pink' onClick="popupclose()"><i class='material-icons small'>clear</i></button></div>
</form></div></div></div></div></div>
		

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
	
				
						$("#itemsTable").on("click", ".reopen", function () {
						//$('#overlaydelete').modal('show');
						$('#overlayreopen').modal('show');
						var po_no = $(this).closest("tr").find('td:eq(2)').text();
						var str = $(this).closest("tr").find('td:eq(7)').text();
                        var res = str.split("+");
						//alert(res[0]);
                     
						document.getElementById("po_year3").value = res[0];
						document.getElementById("po_no3").value = po_no;
						document.getElementById("status3").value = $("#postatus").val();
					});
				
                    
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
					 if ($('#itemsTable tbody .selectedId:checked').length >= 1) {
						$('#del_all').fadeIn('fast');
					}
					if ($('#itemsTable tbody .selectedId:checked').length < 1) {
						$('#del_all').fadeOut('fast');
					} else if (!this.checked) {
						var el = $('#itemsTable-select-all').get(0);


					}
				});				
$("#itemsTable").on("click", ".cancel", function () {

						$('#overlaydelete').modal('show');
                        var po_no = $(this).closest("tr").find('td:eq(0)').text();
						//alert( $(this).closest("tr").find('td:eq(0)').text());
                       
 						document.getElementById("po_no").value = $(this).closest("tr").find('td:eq(0)').text();
							
						
						

					})

$("#itemsTable").on("click", ".cancelinvoice", function () {

						$('#overlaycancel').modal('show');
                        var po_no = $(this).closest("tr").find('td:eq(0)').text();
						//alert( $(this).closest("tr").find('td:eq(0)').text());
                       
 						document.getElementById("po_no1").value = $(this).closest("tr").find('td:eq(0)').text();
							
						
						

					})

				});
				
			}


		</script>
<script>
    
    
		function datatable()
	{
		$('select[name="gststatus"]').change(function () {
	var typeval = $('select[name="gststatus"]').find(':selected').val();
              var projectname = $('#projectname').val();
	if (typeval == 'included') {
		   $('#itemsTable').dataTable().fnDestroy();
	
	}
	else if (typeval == 'nil') {
		  $('#itemsTable').dataTable().fnDestroy();
			}
          
              var place = $("#place").val();
            
              
            
            
         
			
var dataTable = $('#itemsTable').DataTable({
       dom: 'Bfrtip',
       /** buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ], **/
     buttons: [
             'excel'
        ],
	
					"processing": true, 
                    "serverSide": true, 
                    'ajax': {
						url: "invoice_load.php?action="+typeval+"&projectname="+projectname+"&place="+place, // json datasource	
						type: "GET", // method  , by default get
						"deferLoading": 57,
                         error: function () { // error handling
							$(".itemsTable-error").html("");
							$("#itemsTable").append('<tbody class="itemsTable-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
							$("#itemsTable_processing").css("display", "none");
						}
					}
					, 'columnDefs': [{
						'targets': 0, 
						'searchable': false, 
						'orderable': false, 

      }]
					, 'order': [5, 'desc']
				});

					
					
});

  
        
        
        $('select[name="projectname"]').change(function () {
	var projectname = $('select[name="projectname"]').find(':selected').val();
            var typeval = $('#gststatus').val();
            var place = $("#place").val();
	if (projectname == 'S.I.S Marakesh') {
		   $('#itemsTable').dataTable().fnDestroy();
	
	}
	else if (projectname == 'S.I.S Queenstown') {
		  $('#itemsTable').dataTable().fnDestroy();
			}
        else if (projectname == 'S.I.S Acropole') {
		  $('#itemsTable').dataTable().fnDestroy();
			}  
        else if (projectname == 'S.I.S Luxor') {
		  $('#itemsTable').dataTable().fnDestroy();
			}  
                     
            
              
            
            
         
			
var dataTable = $('#itemsTable').DataTable({
       dom: 'Bfrtip',
       /** buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ], **/
     buttons: [
             'excel'
        ],
	
					"processing": true, 
                    "serverSide": true, 
                    'ajax': {
						url: "invoice_load.php?action="+typeval+"&projectname="+projectname+"&place="+place, // json datasource	
						type: "GET", // method  , by default get
						"deferLoading": 57,
                         error: function () { // error handling
							$(".itemsTable-error").html("");
							$("#itemsTable").append('<tbody class="itemsTable-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
							$("#itemsTable_processing").css("display", "none");
						}
					}
					, 'columnDefs': [{
						'targets': 0, 
						'searchable': false, 
						'orderable': false, 

      }]
					, 'order': [5, 'desc']
				});

					
					
});
        

	}
		</script>

		<script type="text/javascript" language="javascript">
			
			
			$(document).ready(function () {
				richeditor();
               typeval =$("#gststatus").val();    
                 projectname =$("#projectname").val();   
                var place = $("#place").val();
	          var dataTable = $('#itemsTable').DataTable({
                     dom: 'Bfrtip',
       /** buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ], **/
     buttons: [
             'excel'
        ],
	
					"processing": true,
        "serverSide": true, 
        'ajax': {
						url: "invoice_load.php?action="+typeval+"&projectname="+projectname+"&place="+place,// json datasource
                        type: "GET", // method  , by default get
						"deferLoading": 57,
                         error: function () { // error handling
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
					, 'order': [5, 'desc']
                  
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
				$('#loaddel').html('<img src="loading.gif" align="absmiddle"> Please wait...');
				$.ajax({
					type: "POST"
					, url: "invoice_loadaction.php"
					, data: $(".fm-delete input").filter(function () {
						return !!this.value;
					}).serialize(), // serializes the form's elements.
					success: function (data) {
                       // alert(data);
                        
						$('#overlaydelete').modal('hide');
					
						dataTable.ajax.reload();

					}
				});
				return false;
			});
            
                
                $(document).on('submit', '.fm-cancel', function () {
				$('#loaddel').html('<img src="loading.gif" align="absmiddle"> Please wait...');                   
				$.ajax({
					type: "POST"
					, url: "invoice_loadaction.php"
					, data: $(".fm-cancel input").filter(function () {
						return !!this.value;
					}).serialize(), // serializes the form's elements.
					success: function (data) {
						$('#overlaycancel').modal('hide');					
						dataTable.ajax.reload();
					}
				});
				return false;
			});
                
                
                


			$(document).on('submit', '.fm-delete1', function () {
				$('#logerror').html('<img src="ajax.gif" align="absmiddle"> Please wait...');
				$.ajax({
					type: "POST"
					, url: "order_loadaction.php"
					, data: $(".fm-delete1").serialize(), // serializes the form's elements.
					success: function (data) {
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
						url: 'order_loadaction.php',
                        type: 'post',
                        data: 'ids=' + checkValues + '&status=' + action
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

            function cc()
			{
				$("#ccdiv").css("display","block");
				$("#ccspan").css("display","none");
				
				
			}
			 function bcc()
			{
				$("#bccdiv").css("display","block");
				$("#bccspan").css("display","none");				
			}
			function popupclose() {
				$('#overlaydelete').modal('hide');
				$('#overlaydeletep').modal('hide');
				$('#overlayadd').modal('hide');
                $('#overlaycancel').modal('hide');
				$('#overlayadd_vendor').modal('hide');
			}

			function fun_add() {
				$('#overlayadd').modal('show');
			}

			function overlaydelete(pono) {
				$('#overlaydelete').modal('show');
				document.getElementById("po_no").value = pono;
			}


			function fun_addvendor() {
				$('#overlayadd_vendor').modal('show');
			}

			$(document).on('submit', '.fm-add', function () {
				$('#logerror').html('<img src="ajax.gif" align="absmiddle"> Please wait...');
				$.ajax({
					type: "POST"
					, url: "products_load.php"
					, data: $(".fm-add input").filter(function () {
						return !!this.value;
					}).serialize(), // serializes the form's elements.
					success: function (data) {
						$('#overlayadd').modal('hide');

					}
				});
				return false;
			});

			

			$(document).on('submit', '.fm-addvendor', function () {
				$('#logerror').html('<img src="ajax.gif" align="absmiddle"> Please wait...');
				$.ajax({
					type: "POST"
					, url: "vendor_actioncode.php"
					, data: $(".fm-addvendor input").filter(function () {
						return !!this.value;
					}).serialize(), // serializes the form's elements.
					success: function (data) {
						$('#overlayadd_vendor').modal('hide');
						dataTable.ajax.reload();

					}
				});
				return false;
			});

			function fun_duplicate(str,str1) 
			{
				window.location =  "purchase_duplicate.php?po_no=" + str+"&po_year="+str1;
			}

			function fun_edit(str,str1) {
			
				window.location =  "invoice_edit.php?po_no=" + str+"&po_year="+str1;
			}

			function fun_pdf(str,str1) {
				location.href = "invoice_pdf.php?po_no="+ str+"&po_year="+str1;

			}

			function fun_word(str,str1) {
				location.href = "purchase_word.php?po_no=" + str+"&po_year="+str1;

			}
function fun_view(str,str1) 
			{
				window.location =  "invoice_view.php?po_no=" + str+"&po_year="+str1;
			}
			/**
			function fun_print(str) 
			{
			      location.href="purchase_print.php?po_no="+str;

			}
			**/

			function fun_print(str,str1) {
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
				xmlhttp.open("GET", "purchase_print.php?po_no=" + str+"&po_year="+str1,true);
				xmlhttp.send();
				$('#overlay').modal('show');

			}


			function fun_email(str,str1) {
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

						richeditor();
						$("#email_invoice").val('');
						$("#emailcc").val('');
						$("#emailbcc").val('');
						$("#esub").val('');
						$("#email-content1").text('');
						$('#overlay1').modal('show');

					}
				}
	

				xmlhttp.open("GET", "invoice_emailpopup.php?po_no=" + str+"&po_year="+str1, true);
				xmlhttp.send();
				
			}






			function viewpo(str,str1) {
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
				xmlhttp.open("GET", "invoice_new.php?po_no=" + str+"&po_year="+str1, true);
				xmlhttp.send();
			}
		</script>




		<script type="text/javascript">
		
		</script>

		<script type="text/javascript">
			function richeditor()
			{
			$.getScript('http://mindmup.github.io/bootstrap-wysiwyg/external/jquery.hotkeys.js',function(){
$.getScript('http://mindmup.github.io/bootstrap-wysiwyg/bootstrap-wysiwyg.js',function(){

  $('#editor').wysiwyg();
  $('#editor').cleanHtml();

});
});
}
		</script>





		<!-- jQuery Library -->



		<script type='text/javascript' src="js/jquery-1.11.2.min.js"></script>
		<script type='text/javascript' src="js/plugins.js"></script>
		<script type='text/javascript' src='https://code.jquery.com/jquery-1.10.1.js'></script>
		<script type="text/javascript" language="javascript" src="js/jquery.js"></script>
		
		<script type="text/javascript" src="js/fnReloadAjax.js"></script>
		
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
	
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.flash.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.html5.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.print.min.js"></script>
	</body>


	</html>