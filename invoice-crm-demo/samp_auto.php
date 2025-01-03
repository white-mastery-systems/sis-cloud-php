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


  <!-- INCLUDED PLUGIN CSS ON THIS PAGE -->
      <link rel="Stylesheet" href="css/bootstrap.css"  media="screen"/>
     <!-- <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"> -->
      <link href="css/materialfamily.css" type="text/css" rel="stylesheet" media="screen">
 
    	 <link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.4.custom.css">
          
   <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
      <script type="text/javascript" src="https://code.jquery.com/jquery-1.8.3.js"></script>
      
     
      <script type="text/javascript">          var j = jQuery.noConflict();</script>

     <script type='text/javascript' src='http://code.jquery.com/jquery-1.7.js'></script>

 <script type='text/javascript'>//<![CDATA[ 
$(function() {
     var pgurl = window.location.href.substr(window.location.href.lastIndexOf("/")+1);
     $("#cssmenu ul a").each(function(){
          if($(this).attr("href") == pgurl || $(this).attr("href") == '' )
          $(this).addClass("active");
     })
});

</script>


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

<!-- css3-mediaqueries.js for IE8 or older -->
<!--[if lt IE 9]>
	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
<![endif]-->
<!-- Purchase New Jquery -->
      <script type="text/javascript">
			
											
 $(document).ready(function(){
						
       callvendor();
            });
		
   
		 

});
				 
</script>
        
   
        
                    
     
         
                
                <!-- Call PO No -->
   
    <script type="text/javascript">
   function callpono()
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

	 document.getElementById('tinno').value = res1[3]; 
		 document.getElementById('cstno').value = res1[4]; 
		  	 document.getElementById('address').value = res1[5]; 

	 document.getElementById('contactname').value = res1[6]; 
		document.getElementById('mobilno').value = res1[7]; 
		 document.getElementById('payment').value = res1[8]; 
		 document.getElementById('city').value = res1[9];
        }
      }
	  

      xmlhttp.open("GET","get_projectlist.php?project_name="+str1, true);
      xmlhttp.send();
  }
</script>    
  
<script type="text/javascript" >
$(document).ready(function()
{
$("#notificationLink").click(function()
{
$("#notificationContainer").fadeToggle(300);
$("#notification_count").fadeOut("slow");
return false;
});

//Document Click
$(document).click(function()
{
$("#notificationContainer").hide();
});
//Popup Click
$("#notificationContainer").click(function()
{
return false;
});

});
</script>



<script type='text/javascript' src='https://code.jquery.com/jquery-1.9.1.js'></script>
<script type='text/javascript'>//<![CDATA[ 
function showimg()
{
$('#itemsTable tr:first-child input').each(function() {
  // alert($(this).val());
	if($(this).val() != "")
	{
		$("#image1").attr("src","images/icon-minus.png")

	}
	
	
});
	
}
</script>


        </head>
<body>
	<!-- Static navbar -->
	<div role="navigation" class="navbar navbar-inverse navbar-fixed-top">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" data-toggle="collapse"
					data-target=".navbar-collapse" class="navbar-toggle collapsed">
					<span class="sr-only">Toggle navigation</span> <span
						class="icon-bar"></span> <span class="icon-bar"></span> <span
						class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#">jQuery UI Autocomplete Tutorial</a>
			</div>
			<div id="navbar" class="collapse navbar-collapse">
				<ul class="nav navbar-nav">
					<li class="active"><a href="http://www.smarttutorials.net/"><i class="fa fa-home"></i>Home</a></li>
					<li><a href="http://www.smarttutorials.net/demo/"> <i class="fa fa-book"></i> Demo </a></li>
					<li><a href="http://blog.smarttutorials.net/"> <i class="fa fa-bank"></i> Blog </a></li>
					<li><a href="http://forum.smarttutorials.net/"> <i class="fa fa-file"></i> Forum </a></li>
				</ul>
			</div>
			<!--/.nav-collapse -->
			<!--/.nav-collapse -->
		</div>
	</div>
	
	
	<!-- Begin page content -->
	<div class="container content-invoice">
		<h1 class="text-center title">jQuery UI Autocomplete 2 Fields</h1>
		<h1>&nbsp;</h1>
		<div class="row">
			<div  class="col-xs-12 col-sm-offset-1 col-md-offset-1 col-lg-offset-1 col-sm-3 col-md-3 col-lg-3  ">
				<div class="form-group">
               
					<input type="text" placeholder="Company Name" id="vendor_name" name="data[vendor_name]" class="form-control ui-autocomplete-input" value="" autocomplete="off">
				</div>
			</div>
			<div  class="col-xs-12 col-sm-3 col-md-3 col-lg-3  text-center">
				<button type="button" id="myButton" class="btn btn-success" autocomplete="off" style="display: none">
				  	Loading....
				</button>
			</div>
			<div  class="col-xs-12 col-sm-3 col-md-3 col-lg-3 ">
				<div class="form-group">
					<textarea placeholder="Your Address" id="clientAddress" name="data[clientAddress]" rows="3" class="form-control txt"></textarea>
				</div>
			</div>
		</div>
	</div>
    <!-- ================================================
    Scripts
    ================================================ -->
         <script src="http://code.jquery.com/jquery-2.1.1.js"></script>
     <script type="text/javascript">      var j = jQuery.noConflict();</script>

    <!-- Our jQuery Script to make everything work -->
  
 <script type="text/javascript" src="js/autocomplete.js"></script>
 
    <script type="text/javascript" src="js/jquery.min.js"></script>
   <script type="text/javascript" src="js/jquery-ui.min.js"></script>
	<script>
    	
		$('#vendor_name').autocomplete({
    		source: function( request, response ) {
				
    			$.ajax({
    				url : 'autocomplete1.php',
    				dataType: "json",
    				method: 'post',
    				data: {
    					name_startsWith: request.term,
    					type: 'ven_compname'
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
        		$('#myButton').show();
    			var names = ui.item.data.split("|");
    			$("#vendorname").val(names[0]);
				$("#clientAddress").val(names[2]);
    		
    		}		      	
    	});
    	    
    </script>
     
	<div class="clearfix"></div>
	<footer class="footer">
		<div class="container-fluid">
		
		</div>
	</footer>
  
  </body>
</html>




