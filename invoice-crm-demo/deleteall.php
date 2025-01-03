<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Webrocom Codeigniter tutorial</title>
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
                    $('#del_all1').fadeIn('fast');

                    if ($('.selectedId:checked').length == 1) {
                        $('.toolbar1').fadeIn('fast');
                    } else {
                        $('.toolbar1').fadeOut('fast');
                        $('.toolbar2').fadeIn('fast');
                    }
                } else {
                    $('.toolbar1').fadeOut('fast');
                    $('#del_all1').fadeOut('fast');
                }
            });
        });//]]> 

</script>

        <script src="https://code.jquery.com/jquery-2.1.1.min.js"/></script>
    </head>
    <body>
        <div class="container">
            <div class="row clear_fix">
 
                <div class="col-md-12">
                    <div class="row clear_fix">
 
                        <div class="col-md-12" style="position: relative">
                        
                        
                            <style>
                        #response{display: none}
                        div #fb, div #gp, div #tw{display: inline-block;}
                        #fb{width: 180px;}
                        #gp{width:  100px;}
                        #tw{width: 180px;}
                    </style>
                  
                        </div>
                    </div>
 
                    <div class="row clear_fix"><div class="col-md-12" id="respose" style="margin-top:3% "></div></div>
 
   <button class="btn btn-warning btn-sm pull-left" id="del_all">Delete selected</button>
 
                    <table class="table">
                        <thead>
                            <tr>
                                <th><input id="selecctall" type="checkbox">&nbsp;Check All</th><th>S. NO.</th><th>Country code</th><th>Country name</th>
                            </tr>
                        </thead>
                        <tbody><?php
						include "connect.php";
						 $sql = "SELECT * from vendor_tbl order by ven_id Desc"  ?>
                            <?php $retval = mysql_query( $sql, $conn );
if(! $retval )
{
  die('Could not get data: ' . mysql_error());
}
while($row = mysql_fetch_array($retval, MYSQL_ASSOC))
{ 
?>
 
                                <tr class="tbl_view" id="">
                                    <td>
                                        <input name="checkbox[]" class="checkbox1" type="checkbox" id="checkbox[]" value="<?php echo $row['ven_id'] ?>">
                                    </td>
                                    <td>
                                        <?php echo $row['ven_compname']; ?>
                                    </td>
                                    <td>
                                        <?= $row['ven_add1'] ?>
                                    </td>
 
                                    <td>
                                        <?= $row['ven_contactperson'] ?>
                                    </td>
 
                                </tr>
                             <?php } ?>
 
                        </tbody>
 
                    </table>
                  
                  
                </div>
 
            </div>
        </div>
 
        <script>
            $(document).ready(function() {
                resetcheckbox();
                $('#selectall').click(function(event) {  //on click
                    if (this.checked) { // check select status
                        $('.checkbox1').each(function() { //loop through each checkbox
                            this.checked = true;  //select all checkboxes with class "checkbox1"              
                        });
                    } else {
                        $('.checkbox1').each(function() { //loop through each checkbox
                            this.checked = false; //deselect all checkboxes with class "checkbox1"                      
                        });
                    }
                });
 
 
                $("#del_all").on('click', function(e) {
                    e.preventDefault();
                    var checkValues = $('.checkbox1:checked').map(function()
                    {
                        return $(this).val();
                    }).get();
                    console.log(checkValues);
                     
                    $.each( checkValues, function( i, val ) {
                        $("#"+val).remove();
                        });
//                    return  false;
                    $.ajax({
                        url: 'deleteallcode.php',
                        type: 'post',
                        data: 'ids=' + checkValues
                    }).done(function(data) {
                        $("#respose").html(data);
                        $('#selecctall').attr('checked', false);
                    });
                });
 
                $(".addrecord").click(function(e) {
                    e.preventDefault();
                    var url = $(this).attr('href');
                    $.ajax({
                        type: 'POST',
                        url: url
                    }).done(function() {
                        window.location.reload();
                    });
                });
                 
                function  resetcheckbox(){
                $('input:checkbox').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "checkbox1"                      
                   });
                }
            });
        </script>
        
<script type="text/javascript" language="javascript" src="media/js/jquery.js"></script>
	<script type="text/javascript" language="javascript">
	function tablesorter()
	{
$(document).ready(function() {
  $('#itemsTable').dataTable();
} );
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
 
 
 loadData();
  tablesorter();
  function loadData()
  {
	 
    var dataString;
      $.ajax({
       url: "vendor_load.php",
      type: "GET",
      data: dataString,
      success:function(data)
      {
        $('#disp-area').html(data);
		tablesorter();
		 itemupdate();
		 itemdelete();
      }
    });
  }
  

  
 });


 function loadData()
  {
	 
    var dataString;
      $.ajax({
      url: "vendor_load.php",
      type: "GET",
      data: dataString,
      success:function(data)
      {
        $('#disp-area').html(data);
		tablesorter();
		 itemupdate();
		 itemdelete();
      }
    });
  }

function fun_add()
{
	$('#overlayadd').modal('show');
}

function itemupdate()
{
$('.use-edit').click(function () {
						   
	$('#overlayedit').modal('show');
	var str = $(this).closest("tr").find('td:eq(1)').text();
var res = str.split("-");
	//document.getElementById("req_id").value = $(this).closest("tr").find('td:eq(0)').text();
	document.getElementById("v_companyname").value = res[4];
	document.getElementById("ven_id").value = res[0];
	document.getElementById("v_address").value = $(this).closest("tr").find('td:eq(2)').text();
	document.getElementById("v_name").value = $(this).closest("tr").find('td:eq(3)').text();
	document.getElementById("v_mobile").value = $(this).closest("tr").find('td:eq(4)').text();
	document.getElementById("v_phone").value = res[1];
	document.getElementById("v_fax").value = res[2];
	document.getElementById("v_email").value = res[3];
	
	

});
}
function itemdelete()
{
$('.use-delete').click(function () {
						   
	$('#overlaydelete').modal('show');
		var str = $(this).closest("tr").find('td:eq(1)').text();
var res = str.split("-");
var str1 = $(this).closest("tr").find('td:eq(4)').text();
var flat = str1.split("-");
	//document.getElementById("req_id").value = $(this).closest("tr").find('td:eq(0)').text();
	document.getElementById("v_companyname2").value = res[4];
	document.getElementById("ven_id2").value = res[0];
	});
}
function popupclose()
{
	$('#overlaydelete').modal('hide');
}

$(document).on('submit','.fm-edit',function(){
   
 alert($(".fm-edit").serialize());
      $('#logerror').html('<img src="ajax.gif" align="absmiddle"> Please wait...');  
        $.ajax({
        type: "POST",
        url:"vendor_actioncode.php",
        data: $(".fm-edit").serialize(), // serializes the form's elements.
        success: function(data)
        {
         //$("#itemsTable").append($(data));
            // $('#user_update')[0].reset(); 
								

		
		alert(data);
		loadData();
			

		$('#overlayedit').modal('hide');
			
        }
     });
  return false;
    });

$(document).on('submit','.fm-add',function(){
   
	// alert($(".fm-edit").serialize());
      $('#logerror').html('<img src="ajax.gif" align="absmiddle"> Please wait...');  
        $.ajax({
        type: "POST",
        url:"vendor_actioncode.php",
        data: $(".fm-add").serialize(), // serializes the form's elements.
        success: function(data)
        {
         //$("#itemsTable").append($(data));
            // $('#user_update')[0].reset(); 
								

		
		alert(data);
		loadData();
			

		$('#overlayadd').modal('hide');
			
        }
     });
  return false;
    });

/** Delete **/

$(document).on('submit','.fm-delete',function(){
   
alert($(".fm-delete").serialize());
      $('#logerror').html('<img src="ajax.gif" align="absmiddle"> Please wait...');  
        $.ajax({
        type: "POST",
        url:"vendor_actioncode.php",
        data: $(".fm-delete").serialize(), // serializes the form's elements.
        success: function(data)
        {
         //$("#itemsTable").append($(data));
            // $('#user_update')[0].reset(); 
								

		
		alert(data);
		loadData();
			

		$('#overlaydelete').modal('hide');
			
        }
     });
  return false;
    });




</script>
 <script type='text/javascript' src='https://code.jquery.com/jquery-1.10.1.js'></script>

 <script type='text/javascript' src="js/WINDOWOPEN/jquery.js"></script>
<script type='text/javascript' src="js/WINDOWOPEN/jquery.min.js"></script>
<script type="text/javascript" language="javascript" src="media/js/jquery.dataTables.js"></script>
<script type='text/javascript' src="js/WINDOWOPEN/bootstrap.min.js"></script>
    </body>
</html>