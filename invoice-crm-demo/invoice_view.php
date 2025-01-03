<?php
session_start();
include "connect.php";

include "writelog.php";
date_default_timezone_set('Asia/Calcutta'); 
$ip = getenv('REMOTE_ADDR');
$userAgent = getenv('HTTP_USER_AGENT');
$referrer = getenv('HTTP_REFERER');
$query = getenv('QUERY_STRING');
$time = date('Y-m-d H:i:s');
$po_no=$_GET['po_no'];
$po_year=$_GET['po_year'];
$html="";


$result1 = mysql_query("SELECT * FROM  invoicetable where invoicenotype='" .$po_no."'",$conn);
if($row1 = mysql_fetch_array($result1))
	 {
$projectname=$row1['projectname'] ;
$block=$row1['block'];
$floorno=$row1['floorno'];
$flatno=$row1['flatno'];
$invoiceno=$row1['invoiceno'];
$invoicenotype=$row1['invoicenotype'];
$invoicedate=$row1['invoicedate'];
$gstin=$row1['gstin'];
$panno=$row1['panno'];
$status=$row1['status'];
$description=$row1['description'];
$hsn_sac_code=$row1['hsn_sac_code'];    
$total=$row1['total'];
$lc_amount=$row1['lc_amount'];
$taxamount=$row1['taxamount'];
$sgst=$row1['sgst'];
$gstper=$row1['gstper'];
$cgst=$row1['cgst'];
$roundtotal=$row1['roundtotal'];
$grandtotal=$row1['grandtotal'];
$totalword=$row1['totalword'];
$po_year=$row1['po_year'];
$curYear = $row1['po_year'];
$nexYear = $row1['po_year'] + 1;
    
  if($gstper == '0')
    {
      $gtotal = '0';   
    }
    else
    {
       $gstper = $gstper/2; 
    $gtotal =  $roundtotal-$total;
     $finaltotal =  $roundtotal-$gtotal;    
    }
    
    
   
	 }

  	$result = mysql_query("SELECT * FROM clientmaster where projectname='$projectname' and block='$block' and floor='$floorno' and flatno='$flatno' ",$conn);
	if($row= mysql_fetch_array($result))
	 {
	    $projectname = $row['projectname'] ;
	    $buyername=$row['name'];	
		$address1=$row['address1']; 
        $address2=$row['address2'];
        $address3=$row['address3'];	
		$contact=$row['contact'];	
        $pannoc=$row['panno'];
        		}
		else
		{
			echo "Error";
	 }
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
      <script type="text/javascript">var j = jQuery.noConflict();</script>
     <script type='text/javascript' src='http://code.jquery.com/jquery-1.7.js'></script>
     
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
      <div id="pur-head" class='container-title'>
             <div class='row'>
               <div class='col s12 m12 l12'>
                 <h5 class='breadcrumbs-title'>Invoice View</h5>
                
                <div id="pur-head" class='left leftspace navigations'><a href="invoice.php" class='btn-floating btn-large waves-effect waves-light  pink'><i class='material-icons'>arrow_back</i></a></div>
              </div>
            </div>
          </div>
        </div>
      
        <div class='row white' id="pur-new">
       <!-- Disp Area --> 
   
<div id="disp-area">
    <?php
    
echo "<input type='hidden' name='po_year' id='po_year'  placeholder='Date' value='".$po_year."' readonly />";

echo "<div id='invoice'>";
echo "<div class='invoice-header'>";
echo "<div class='row section'>";
echo "<div class='col12 s12 m12 l6 right rightspace'><img src='images/SISLOGO.png' />";
echo "</div>";
echo "</div>";
echo "</div>";
echo "<div class='invoice-lable'>";
echo "<div class='row'>";
echo "<div class='col12 s12 m6 l3 cyan'>";
echo "<div class='col s12 m6 l6' id='cy-txt'>&nbsp;</div>";
echo "<div class='col s12 m12 l12 left'>";
echo "</div></div>";
echo "</div>";
echo "</div>";
echo "<div class='invoice-table'>";
echo "<div class='row '>";
    echo "<div class='col s12 m12 l12 table-responsive ' >";
echo "<table class='table table-full' style='border:1px solid #FFF;'>";
    echo "<tr>";
echo "<td>";
    echo "<table style='padding:10px'>";
            echo "<tr>";
            echo "<td><b>Project</b></td>";
            echo "<td>:</td>";
            echo "<td><b>".$projectname."</b></td>";
            echo "</tr>";
           
            echo "<tr>";
            echo "<td><b>Flat No</b></td>";
            echo "<td>:</td>";
            echo "<td><b>".$block."-".$flatno."</b></td>";
            echo "</tr>";
           
            echo "<tr>";
            echo "<td>Buyer Name</td>";
            echo "<td style='white-space:nowrap;'>:</td>";
            echo "<td style='white-space:nowrap;'><b>".$buyername."</b></td>";
            echo "</tr>";
           
           echo "<tr>";
            echo "<td>Address1</td>";
            echo "<td>:</td>";
            echo "<td>".$address1."</td>";
            echo "</tr>";
           
            echo "<tr>";
            echo "<td>Address2</td>";
            echo "<td>:</td>";
            echo "<td>".$address2."</td>";
            echo "</tr>";
           
               echo "<tr>";
            echo "<td>Address3</td>";
            echo "<td>:</td>";
            echo "<td>".$address3."</td>";
            echo "</tr>";
           
           
             echo "<tr>";
            echo "<td>Tel</td>";
            echo "<td>:</td>";
            echo "<td>".$contact."</td>";
            echo "</tr>";
           
             echo "<tr>";
            echo "<td>GST / PAN</td>";
            echo "<td>:</td>";
            echo "<td>".$pannoc."</td>";
            echo "</tr>";
       echo "<tr>";
                   echo "<td><b>status</b></td>";
                   echo "<td>:</td>";
                   echo "<td><b>".$status."</b></td>";
               echo "</tr>";
     echo "<tr>";
    if($status == 'cancel')
    {
         echo "<td>Description</td>";
                   echo "<td>:</td>";
                   echo "<td>".$description."</td>";
               echo "</tr>"; 
    }
                 
            echo "</table>";
            echo "</td>";
    echo "<td align='right'>";
    echo "<table style='border:solid 2px #000; padding:10px 10px 40px 0px'>";
                echo "<tr>";
                    echo "<td><b>Invoice No</b></td>";
                    echo "<td>:</td>";
                    echo "<td><b>".$invoicenotype."</b></td>";
                echo "</tr>";
                 echo "<tr>";
                    echo "<td><b>Date</b></td>";
                    echo "<td>:</td>";
                    echo "<td><b>".$invoicedate."</b></td>";
                echo "</tr>";
                 echo "<tr>";
                   echo "<td>GSTIN</td>";
                   echo "<td>:</td>";
                   echo "<td>".$gstin."</td>";
               echo "</tr>";
                echo "<tr>";
                   echo "<td>PAN</td>";
                   echo "<td>:</td>";
                   echo "<td>".$panno."</td>";
               echo "</tr>";
                echo "<tr>";
                   echo "<td>HSN/SAC Code</td>";
                   echo "<td>:</td>";
                   echo "<td>".$hsn_sac_code."</td>";
               echo "</tr>";
   
           echo "</table>";
    
    echo  "</td>";
    
    
             echo "</tr>";

    echo "</table></div>";
    
echo "<div class='col s12 m12 l12 table-responsive '>";
echo "<table class='table table-full' id='itemsTable'>";
echo "<thead>";
echo "<tr>";
echo "<th>S.No</th>";
echo "<th>Description of Goods / Services</th>";
echo "<th>Amount</th>";

echo "</tr>";
echo "</thead>";
echo "<tbody>";
echo "<tr>";

echo "<td><div class='input-field col s8'>1</div></td>";

echo "<td><div class='input-field col s8'>Construction services of multi-storied residential buildings - Advance</div></td>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "</tr>";

echo "<tr>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "<td><div class='input-field col s8'>Total</div></td>";
echo "<td><div class='input-field col s8'>".$total."</div></td>";
    
echo "</tr>";

echo "<tr>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "<td><div class='input-field col s8'>Discount</div></td>";
echo "<td><div class='input-field col s8'>
</div></td>";    
echo "</tr>";

echo "<tr>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "<td><div class='input-field col s8'>Land and Construction value</div></td>";
echo "<td><div class='input-field col s8'>".$lc_amount."</div></td>";
    
echo "</tr>";

echo "<tr>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "<td><div class='input-field col s8'>Taxable Value</div></td>";
echo "<td><div class='input-field col s8'>".$taxamount."</div></td>";
    
echo "</tr>";

echo "<tr>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";    
echo "</tr>";

echo "<tr>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "<td><div class='input-field col s8'>CGST - 9%</div></td>";
echo "<td><div class='input-field col s8'>".$cgst."</div></td>";    
echo "</tr>";

echo "<tr>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "<td><div class='input-field col s8'>SGST - 9%</div></td>";
echo "<td><div class='input-field col s8'>".$sgst."</div></td>";    
echo "</tr>";


echo "<tr>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "<td><div class='input-field col s8'>Total</div></td>";
echo "<td><div class='input-field col s8'>".$roundtotal."</div></td>";    
echo "</tr>";

echo "<tr>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "<td><div class='input-field col s8'>Round off</div></td>";
echo "<td><div class='input-field col s8'>".$gtotal."</div></td>";    
echo "</tr>";

echo "<tr>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "<td><div class='input-field col s8'>Grand Total(Inclusive of GST)</div></td>";
echo "<td><div class='input-field col s8'>".$grandtotal."</div></td>";    
echo "</tr>";


echo "<tr>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "<td><div class='input-field col s8'>Invoice Value(Inwords)</div></td>";
echo "<td><div class='input-field col s8'><b>".$totalword."</b>  </div></td>";    
echo "</tr>";

echo "</tbody>";
echo "</table>";
echo "</div>";
echo "</div>";

echo "<div class='col12 s12 m6 l6 left leftspace'>";
echo "<p>For South India Shelters Private Limited, <br />
 </p>
&nbsp;
<br>
&nbsp;
 <p>Authorised Signatory</p>

</div>
</div>
</div>
</div>";

   
    ?>
    
    
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