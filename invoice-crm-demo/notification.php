<html>
<head>
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
<style type="text/css">
#notify_li{position:relative}
#notificationContainer {
background-color: #fff;
border: 1px solid rgba(100, 100, 100, .4);
-webkit-box-shadow: 0 3px 8px rgba(0, 0, 0, .25);
overflow: visible;
position: absolute;
top: 30px;
margin-left:0px;
width: 400px;
z-index:9999;
display: none;
}
#notificationContainer:before {
content: '';
display: block;
position: absolute;
width: 0;
height: 0;
color: transparent;
border: 10px solid black;
border-color: transparent transparent white;
margin-top: -20px;
margin-left: 188px;
}
#notificationTitle {
z-index: 1000;
font-weight: bold;
padding: 8px;
font-size: 13px;
background-color: #ffffff;
width: 384px;
border-bottom: 1px solid #dddddd;
}
#notificationsBody {
padding: 2px 0px 0px 0px !important;
min-height:300px;
}
#notificationFooter {
background-color: #e9eaed;
text-align: center;
font-weight: bold;
padding: 8px;
font-size: 12px;
border-top: 1px solid #dddddd;
}
#msg_count {
padding: 3px 7px 3px 7px;
background: #cc0000;
color: #ffffff;
font-weight: bold;
margin-left: 77px;
border-radius: 9px;
position: absolute;
margin-top: -11px;
font-size: 11px;
}
.content {
    padding-left: 10px;
	}</style>
</head>
<body>
   <?php 
					 include "connect.php";
					
					 $sql_bal = "SELECT pro_name,SUM(stk_received) as stk_received,SUM(stk_used) as stk_received,product_code,size,price,sub_catname,cat_name,project_name,SUM(stock) as stock,SUM(stk_used) as stk_used FROM  stocklist where stk_received <=50  GROUP BY pro_name,project_name ";
					 $result_bal= mysql_query($sql_bal,$conn);
					 $num_rows = mysql_num_rows($result_bal);

					 
					 ?>
                     
	<li id="notify_li">
<span id="msg_count"><?php echo $num_rows ?></span>
<a href="#" id="notifylink">Notifications</a>
<div id="notificationContainer">
<div id="notificationTitle">Notifications</div>

<div id="notificationsBody" class="notifications">
 <?php while($row_bal = mysql_fetch_array($result_bal))
					  {
echo '<div class="content">'.$row_bal["pro_name"].'<i class="material-icons">cancel</i></div>';
					  }
					  ?>
</div>
<div id="notificationFooter"><a href="#">See All</a></div>
</div>

</li>
 
</body>
<script type="text/javascript" src="js/jquery.min.js"></script>

<script type="text/javascript" >
$(document).ready(function()
{
$("#notifylink").click(function()    // onclick function for notification
{
$("#notificationContainer").fadeToggle(300);   // show notification div
$("#msg_count").fadeOut("slow");
return false;
});

//Document Click
$(document).click(function()
{
$("#notificationContainer").hide();     // hide notification div
});
//Popup Click
$("#notificationContainer").click(function()
{
return false
});

});



</script>

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
</html>