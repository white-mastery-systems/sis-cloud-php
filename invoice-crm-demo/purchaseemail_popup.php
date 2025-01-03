<?php
ini_set('max_execution_time', 300);
ini_set("memory_limit","1024M");
include "writelog.php";
$time = date('Y-m-d H:i:s');
$po_no = $_GET['po_no'] ;
$po_year = $_GET['po_year'] ;
$time = date('Y-m-d H:i:s');
$ip = getenv('REMOTE_ADDR');
$userAgent = getenv('HTTP_USER_AGENT');
$referrer = getenv('HTTP_REFERER');
$query = getenv('QUERY_STRING');
require('connect.php');
$n = 0;
$result1 = mysql_query("SELECT * FROM  purchase_master where  po_year='".$po_year."' and po_no=" .$po_no,$conn);
if($row1 = mysql_fetch_array($result1))
{
$project_id=$row1['project_id'] ;
$ven_id=$row1['ven_id'];
$vat_o=$row1['vat'];
$subtotal=$row1['subtotal'];
$ddate=$row1['ddate'];
$ed=$row1['ed'];
$st=$row1['st'];
$tax=$row1['tax'];
$transportation=$row1['transportation'];
$basictotal=$row1['basictotal'];
$itotal=$row1['itotal'];
$gt=$row1['gt'];
$gt1=$row1['gt1'];
$po_date=$row1['po_date'];
$type = $row1['type'];
$po_date=$row1['po_date'];
isset($row1['refno']) ? $refno = $row1['refno'] : $refno = "none";
$refdate=$row1['refdate'];
$block=$row1['Block'];
$payment = $row1['payment'];
$subject=$row1['subject'];
$contactname=$row1['contactname'];
$mobileno=$row1['mobileno'];
$inclusive = $row1['inclusive'];
$tpwords = $row1['tpwords'];
$cur_year = $row1['po_year'];
}	
$result2 = mysql_query("SELECT * FROM  vendor_tbl where ven_compname='".mysql_real_escape_string($ven_id)."'",$conn);
if($row2= mysql_fetch_array($result2))
{
$ven_id1 = $row2['ven_id'];
$ven_compname = $row2['ven_compname'] ;
$ven_add1=$row2['ven_add1'];
$ven_add2=$row2['ven_add2'];
$ven_city=$row2['ven_city'];
$ven_pincode=$row2['ven_pincode'];
$ven_state=$row2['ven_state'];
$ven_country=$row2['ven_country']; }
$result = mysql_query("SELECT * FROM project_details where project_id=" .$project_id,$conn);
if($row= mysql_fetch_array($result))
{
	    $project_name = $row['project_name'] ;
	    $place=$row['place'];
		$tinno=$row['tinno'];
	    $cstno=$row['cstno'];
		$address=$row['address'];
		$projectshort=$row['projectshort'];  
		$addressdispname=$row['addressdispname']; 
		$projectsingle=$row['projectsingle']; 		
		}
		else
		{
			echo "Error";
	 }
    
      echo '<form id="emailForm" method="get" novalidate="novalidate">';

     echo '<div style="width:95%"><input type="email" class="form-control" name="email_invoice" id="email_invoice" placeholder="Enter email" required/> </div>
	 <div align="right" ><span style="padding:0px 0px 0 10px;" id="ccspan"><a href="#" id="ccl" onclick="cc()">Cc</a></span>  <span style="padding:0px 0px 0 5px;" id="bccspan"><a href="#" id="bccl" onclick="bcc()" >Bcc</a></span></div>'; 

     echo '<div style="width:95%; display:none" id="ccdiv" ><input type="email" class="form-control" name="emailcc" id="emailcc" placeholder="CC" /></div>';  
echo '<div style="width:95%; display:none" id="bccdiv" ><input type="email" class="form-control" name="emailbcc" id="emailbcc" placeholder="BCC" /></div>'; 
echo '<div style="width:95%; " id="subdiv" ><input type="text" class="form-control" name="esub" id="esub" placeholder="Subject" /></div>'; 
     echo '<div class="container">
    <div class="row">
    <div class="btn-toolbar" data-role="editor-toolbar" data-target="#editor">
      <div class="btn-group formatgroups">
        <a class="formatbuttons dropdown-toggle" data-toggle="dropdown" title="" data-original-title="Font" href="#"><i class="material-icons">text_format</i><b class="caret"></b></a>
          <ul class="dropdown-menu">
          <li><a data-edit="fontName Serif" style="font-family:Serif">Serif</a></li><li><a data-edit="fontName Sans" style="font-family:Sans">Sans</a></li><li><a data-edit="fontName Arial" style="font-family:Arial">Arial</a></li><li><a data-edit="fontName Arial Black" style="font-family:Arial Black">Arial Black</a></li><li><a data-edit="fontName Courier" style="font-family:Courier">Courier</a></li><li><a data-edit="fontName Courier New" style="font-family:Courier New">Courier New</a></li><li><a data-edit="fontName Comic Sans MS" style="font-family:Comic Sans MS">Comic Sans MS</a></li><li><a data-edit="fontName Helvetica" style="font-family:Helvetica">Helvetica</a></li><li><a data-edit="fontName Impact" style="font-family:Impact">Impact</a></li><li><a data-edit="fontName Lucida Grande" style="font-family:Lucida Grande">Lucida Grande</a></li><li><a data-edit="fontName Lucida Sans" style="font-family:Lucida Sans">Lucida Sans</a></li><li><a data-edit="fontName Tahoma" style="font-family:Tahoma">Tahoma</a></li><li><a data-edit="fontName Times" style="font-family:Times">Times</a></li><li><a data-edit="fontName Times New Roman" style="font-family:Times New Roman">Times New Roman</a></li><li><a data-edit="fontName Verdana" style="font-family:Verdana">Verdana</a></li></ul>
      </div>
      <div class="btn-group formatgroups leftpad rightpad">
        <a class="formatbuttons dropdown-toggle" data-toggle="dropdown" title="Font Size" data-original-title="Font Size" href="#"><i class="material-icons">format_size</i>&nbsp;<b class="caret"></b></a>
          <ul class="dropdown-menu">
          <li><a data-edit="fontSize 5"><font size="5">Huge</font></a></li>
          <li><a data-edit="fontSize 3"><font size="3">Normal</font></a></li>
          <li><a data-edit="fontSize 1"><font size="1">Small</font></a></li>
          </ul>
      </div>
	  
      <div class="btn-group leftpad rightpad">
        <a class="formatbuttons" data-edit="bold" title="" data-original-title="Bold (Ctrl/Cmd+B)" href="#"><i class="material-icons">format_bold</i></a></div>
	  <div class="btn-group leftpad rightpad">
        <a class="formatbuttons" data-edit="italic" title="" data-original-title="Italic (Ctrl/Cmd+I)" href="#"><i class="material-icons">format_italic</i></a>
      </div>
	  <div class="btn-group  leftpad rightpad">
       <a class="formatbuttons" data-edit="underline" title="" data-original-title="Underline (Ctrl/Cmd+U)" href="#"><i class="material-icons">format_underlined</i></a>
      </div>
  
      <div class="btn-group leftpad rightpad">
        <a class="formatbuttons" data-edit="justifyleft" title="" data-original-title="Align Left (Ctrl/Cmd+L)" href="#"><i class="material-icons">format_align_left</i></a>
       </div>
	   <div class="btn-group leftpad rightpad">        
        <a class="formatbuttons" data-edit="justifycenter" title="" data-original-title="Center (Ctrl/Cmd+E)" href="#"><i class="material-icons">format_align_center</i></a>
      </div>
	   <div class="btn-group leftpad rightpad">
        <a class="formatbuttons" data-edit="justifyright" title="" data-original-title="Align Right (Ctrl/Cmd+R)" href="#"><i class="material-icons">format_align_right</i></a>
       </div>
	    <div class="btn-group leftpad rightpad">
       <a class="formatbuttons" data-edit="justifyfull" title="" data-original-title="Justify (Ctrl/Cmd+J)" href="#"><i class="material-icons">format_align_justify</i></a>
       </div>
       <div class="btn-group  leftpad rightpad">
        <a class="formatbuttons" data-edit="undo" title="" data-original-title="Undo (Ctrl/Cmd+Z)" href="#"><i class="material-icons" >undo</i></a>
      </div>
	  <div class="btn-group  leftpad rightpad">
        <a class="formatbuttons" data-edit="redo" title="" data-original-title="Redo (Ctrl/Cmd+Y)" href="#"><i class="material-icons" >redo</i></a>
      </div>
    </div>    
    <div id="editor" name="editor" class="well col-md-9" style="width:100%"><b>Dear '. $row2['ven_contactperson'] .',</b><br/>We are pleased to place the Purchase order as per the details mentioned in the attachment for our project ' .$project_name. ' @ ' .$place. '.  </div>';
      echo '<input type="hidden" class="form-control" name="email_po" id="email_po" value="'.$po_no.'">';
 echo '<input type="hidden" class="form-control" name="po_year" id="po_year" value="'.$po_year.'">';
 echo '<div align="right"><button data-loading-text="Email Sending..." id="email_btn" type="button" class="btn btn-success" autocomplete="off" onClick="sendemail()">Send</button></div></form>';
   
   


?>