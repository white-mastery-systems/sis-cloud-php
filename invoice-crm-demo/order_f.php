
<!doctype html>
<html lang="en"><head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

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

<script>
//After you click on Track It
function fun_edit(str) 
{
       window.location = 'purchase_duplicate.php?po_no='+str;
}


function fun_pdf(str) 
{
      location.href="purchase_pdf1.php?po_no="+str;

}
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

     document.getElementById("email_po").value = str;

}
function fun_cancel(str) 
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
	  //alert(str2);
	  location.reload();
	  
        }
      }
	 
		
      xmlhttp.open("GET","purchasecancel.php?po_no="+str, true);
      xmlhttp.send();
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
	  document.getElementById("email-content").innerHTML = str2;
        }
      }
	
		
      xmlhttp.open("GET","purchase_email.php?po_no="+po_no+"&invoice_mail="+invoice_mail, true);
      xmlhttp.send();
}



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
                    $('#AdvancedOption').fadeIn('fast');

                    if ($('.selectedId:checked').length == 1) {
                        $('.toolbar1').fadeIn('fast');
                    } else {
                        $('.toolbar1').fadeOut('fast');
                        $('.toolbar2').fadeIn('fast');
                    }
                } else {
                    $('.toolbar1').fadeOut('fast');
                    $('#AdvancedOption').fadeOut('fast');
                }
            });
        });//]]> 
    function changestatus()
  {
	 
    var dataString="&action=all";
      $.ajax({
      url: "purchaseload.php",
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

<title></title>

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
      <section id="content">
      
        <div id="breadcrumbs-wrapper" class=" grey lighten-3">
          <div class="container-title">
            <div class="row">
              <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Purchase Order</h5>
              
                <div  style="position:relative; top:45px;width:auto" class="right rightspace"><span><a class="btn-floating btn-large waves-effect waves-light" title="Delete" id="AdvancedOption" style="display:none" > <i class="material-icons" title="Cancel">delete</i></a></span> <span><a href="purchase_new.php" class="btn-floating btn-large waves-effect waves-light" title="New Purchase"><img src="images/cart-plus.png" /></a></span>  <span><a class="btn-floating btn-large waves-effect waves-light" title="Add Vendor"><img src="images/addvendor_small.png" /></a></span> <span><a class="btn-floating btn-large waves-effect waves-light" title="New Products"><img src="images/products_addmall.png" /></a></span> </div>
              </div>
            </div>

          </div>
        </div>
   
        

      
        <div class="container">
        <select id="status" name="status" onChange="statuschange()">
<option value="select">Select</option>
<option value="open">Open</option>
<option value="cancel">Cancel</option>
</select>
          <div class="section topspace" >

      <table  class="table table-full m-b-60" cellpadding="0" cellspacing="0"   id="itemsTable" style="width:100%">
               
                <thead>
        <tr>
          <th align="center" style="width:10%">  <input type="checkbox" id="selectAll" />
    <label for="selectAll"></label></th>
<th><b>Icon</b></th>
          <th  style="width:10%"><b>Invoice No  </b> <i class="material-icons tiny1">sort</i></th>
          <th  style="width:10%"><b>Date </b> <i class="material-icons tiny1">sort</i></th>
           <th  style="width:25%"><b>Summary</b></th>
           <th  style="width:15%" ><div class="textalignright" style="width:60%"><b>Total Rs</b></div></th>
            <th  style="width:20%" ><b>Action</b></th>
        </tr>
      </thead>
      <tbody>
      <?php
     include "connect.php";
   
  
$result = mysql_query("SELECT * FROM  purchase_master where status='open' ORDER BY po_no DESC",$conn);
  while ($row = mysql_fetch_array($result)) {
  ?>
        <tr>
          <td align="center"><input type="checkbox"  class="checkappear-td selectedId" id="<?php echo $row['p_id']; ?>"  />
    <label for="<?php echo $row['p_id']; ?>"></label></td>
          <td><i class="material-icons icon-color amber darken-1 ">folder</i></td>
          <td><?php echo $row['po_no'];?></td>
          <td><?php echo $row['po_date']; ?> </td>
          <td><a href="#purchaseview" id="<?php echo $row['po_no'];?>" onClick="viewpo(<?php echo $row['po_no'];?>)" rel="ajax">View PO</a></td>
          <td class=""><div class="textalignright" style="width:60%"><?php  echo $row['subtotal']; ?></div></td>
          <td ><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="col-md-2 col-sm-12 col-lg-2 col-xs-12"><i class="material-icons tiny"><a href="#" title="duplicate" onClick="fun_edit(<?php echo $row['po_no'] ?>);">content_copy</a></i></div>  <div class="col-md-2 col-sm-12 col-lg-2 col-xs-12"><i class="material-icons tiny"><a href="#" title="Print"  id="print_p" onClick="fun_print(<?php echo $row['po_no']?>);" >print</a></i></div><div class="col-md-2 col-sm-12 col-lg-2 col-xs-12"><i class="material-icons tiny"><a href="#" title="Email" onClick="fun_email(<?php echo $row['po_no'] ?>);">email</a></i></div><div class="col-md-2 col-sm-12 col-lg-2 col-xs-12"><i class="material-icons tiny" title="Cancel"><a href="#" onClick="fun_cancel(<?php echo $row['po_no'] ?>);" title="Cancel">delete</a></i></div> <div class="col-md-2 col-sm-12 col-lg-2 col-xs-12"><i class="material-icons tiny"><a href="#" title="PDF" onClick="fun_pdf(<?php echo $row['po_no'] ?>);" >picture_as_pdf</a></i></div></div></td>
        </tr>
        <?php
}
?>            
       
      </tbody>
      <tfoot>
   
      </tfoot>    
        
    </table>
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
			

     <!-- Container End -->
<!-- Popup Window -->
<!-- Print -->
 <div class="modal fade" id="overlay" style="">
  <div class="modal-dialog" style="width:750px;">
    <div class="modal-content">
      <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true" id="close_but">x</button>

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
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h4 class="modal-title">Send Email</h4>
      </div>
      <div class="modal-body" >

      <div style="width:980px;" id="email-content">
      
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
<script type='text/javascript' src="js/WINDOWOPEN/jquery.js"></script>
<script type='text/javascript' src="js/WINDOWOPEN/jquery.min.js"></script>
<script type='text/javascript' src="js/WINDOWOPEN/bootstrap.min.js"></script>
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

</body>
</html>
