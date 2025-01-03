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

//$lognme ;
 if (!isset($_SESSION['st_emailid']) and !isset($_SESSION['st_pwd']))
 {
   	header("Location: login.php");
	 }
    $_SESSION['st_emailid'];$_SESSION['st_pwd'];
	   extract($_POST);

date_default_timezone_set('Asia/Calcutta'); 

     $today = date("Y.m.d"); 
//echo $stockid;
isset($_POST['pro_name']) ? $pro_name = mysql_real_escape_string($_POST['pro_name']) : $pro_name = "0";
isset($_POST['pro_id']) ? $pro_id = mysql_real_escape_string($_POST['pro_id']) : $pro_id = "0";
isset($_POST['pro_code']) ? $pro_code = mysql_real_escape_string($_POST['pro_code']) : $pro_code = "0";
isset($_POST['pro_price']) ? $pro_price = mysql_real_escape_string($_POST['pro_price']) : $pro_price = "0";
isset($_POST['pro_brand']) ? $pro_brand = mysql_real_escape_string($_POST['pro_brand']) : $pro_brand = "0";
isset($_POST['pro_category']) ? $pro_category = mysql_real_escape_string($_POST['pro_category']) : $pro_category = "0";
isset($_POST['pro_subcat']) ? $pro_subcat = mysql_real_escape_string($_POST['pro_subcat']) : $pro_subcat = "0";
isset($_POST['pro_size']) ? $pro_size = mysql_real_escape_string($_POST['pro_size']) : $pro_size = "0";
isset($_POST['pro_width']) ? $pro_width = mysql_real_escape_string($_POST['pro_width']) : $pro_width = "0";
isset($_POST['pro_height']) ? $pro_height = mysql_real_escape_string($_POST['pro_height']) : $pro_height = "0";
isset($_POST['pro_vat']) ? $pro_vat = mysql_real_escape_string($_POST['pro_vat']) : $pro_ed = "0";
isset($_POST['pro_ed']) ? $pro_ed = mysql_real_escape_string($_POST['pro_ed']) : $pro_size = "0";
isset($_POST['pro_st']) ? $pro_st = mysql_real_escape_string($_POST['pro_st']) : $pro_st = "0";
isset($_POST['pro_tp']) ? $pro_tp = mysql_real_escape_string($_POST['pro_tp']) : $pro_tp = "0";
isset($_POST['pro_units']) ? $pro_units = mysql_real_escape_string($_POST['pro_units']) : $pro_units = "0";
isset($_POST['pro_materialcost']) ? $pro_materialcost = mysql_real_escape_string($_POST['pro_materialcost']) : $pro_materialcost = "0";
isset($_POST['pro_icost']) ? $pro_icost = mysql_real_escape_string($_POST['pro_icost']) : $pro_icost = "0";
isset($_POST['pro_tp']) ? $pro_tp = mysql_real_escape_string($_POST['pro_tp']) : $pro_tp = "0";
isset($_POST['pro_units']) ? $pro_units = mysql_real_escape_string($_POST['pro_units']) : $pro_units = "0";
isset($_POST['pro_materialcost']) ? $pro_materialcost = mysql_real_escape_string($_POST['pro_materialcost']) : $pro_materialcost = "0";
isset($_POST['pro_icost']) ? $pro_icost = mysql_real_escape_string($_POST['pro_icost']) : $pro_icost = "0";
isset($_POST['pro_ton']) ? $pro_ton = mysql_real_escape_string($_POST['pro_ton']) : $pro_ton = "0";
isset($_POST['pro_details']) ? $pro_details = mysql_real_escape_string($_POST['pro_details']) : $pro_details = "0";

isset($_POST['typemat']) ? $typemat = mysql_real_escape_string($_POST['typemat']) : $typemat = "0";
isset($_POST['action']) ? $action = mysql_real_escape_string($_POST['action']) : $action = "none";
isset($_POST['ids']) ? $ids = mysql_real_escape_string($_POST['ids']) : $ids = "none";
if($ids == 'none')
{

if($action == 'open')
{
echo '<table  class="table" cellpadding="0" cellspacing="0"  id="itemsTable" style="width:100%">
<thead> 
<tr><th><input type="checkbox" id="selectAll" />
    <label for="selectAll"></label></th>
<th><b>Product Name</b></th>
<th><b>Category</b></th>
<th><b>Sub Category</b></th>
<th><b>Flat</b></th>
<th><b>Project Name</b></th>
<th><b>Required</b></th>
<th><b>Used</b> </th>
<th><b>Action</b></th></tr>
</thead><tbody>';
require('connect.php');
$sql = 'SELECT * from  requirementtable where type="open" order by req_id Desc';
$retval = mysql_query( $sql, $conn );
if(! $retval )
{
  die('Could not get data: ' . mysql_error());
}
while($row = mysql_fetch_array($retval, MYSQL_ASSOC))
{ (isset($row['item_used'])) ? $item_used = $row['item_used'] : $item_used = "0";
echo '<tr><td><input type="checkbox"  class="checkappear-td selectedId checkbox1" id="'.$row['req_id'].'" value="'.$row['req_id'].'"  /><label  for="'.$row['req_id'].'"></label></td>
<td><span style="display:none">' .$row['req_id']. '-</span>'.$row['product_name'].' </td>
<td>'.$row['cat_name'].'</td>
<td>'.$row['sub_catname'].'</td>
<td>'.$row['flatno'] .'-'.  $row['block'].'</td>
<td>'.$row['project_name'].'</td>
<td>'.$row['itemsrequired'].'</td>
<td>'.$item_used.'</td>
<td> <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="col-md-4 col-sm-12 col-lg-4 col-xs-12"><button id="edit_p" class="but_icon use-edit" ><i class="fa fa-pencil"></i></button> </div> &nbsp;&nbsp; <div class="col-md-4 col-sm-12 col-lg-4 col-xs-12"><button id="delete_p" class="but_icon use-delete" ><i class="fa fa-trash"></i></button> </div></div></tr>';
              
}    
echo  '</tbody></table>';
}
if($action == 'cancel')
{
echo '<table  class="table" cellpadding="0" cellspacing="0"  id="itemsTable" style="width:100%">
<thead> 
<tr><th><b>S.No.</b></th>
<th><b>Product Name</b></th>
<th><b>Category</b></th>
<th><b>Sub Category</b></th>
<th><b>Flat</b></th>
<th><b>Project Name</b></th>
<th><b>Required</b></th>
<th><b>Used</b> </th>
<th><b>Action</b></th></tr>
</thead><tbody>';
require('connect.php');
$sql = 'SELECT * from  requirementtable order where type="cancel" by req_id Desc';
$retval = mysql_query( $sql, $conn );
if(! $retval )
{
  die('Could not get data: ' . mysql_error());
}
while($row = mysql_fetch_array($retval, MYSQL_ASSOC))
{ (isset($row['item_used'])) ? $item_used = $row['item_used'] : $item_used = "0";
echo '<tr><td>  </td>
<td><span style="display:none">' .$row['req_id']. '-</span>'.$row['product_name'].' </td>
<td>'.$row['cat_name'].'</td>
<td>'.$row['sub_catname'].'</td>
<td>'.$row['flatno'] .'-'.  $row['block'].'</td>
<td>'.$row['project_name'].'</td>
<td>'.$row['itemsrequired'].'</td>
<td>'.$item_used.'</td>
<td> <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="col-md-4 col-sm-12 col-lg-4 col-xs-12"><button id="edit_p" class="but_icon use-edit" ><i class="fa fa-pencil"></i></button> </div> &nbsp;&nbsp; <div class="col-md-4 col-sm-12 col-lg-4 col-xs-12"><button id="delete_p" class="but_icon use-delete" ><i class="fa fa-trash"></i></button> </div></div></tr>';
              
}    
echo  '</tbody></table>';
}
else if($action == 'edit')
{
$sql_edit = "update requirementtable set product_name='$pro_name',cat_name='$cat_name',sub_catname='$subcat_name',flatno='$flat',block='$block',project_name='$projectname',status='less',itemsrequired=$required where req_id=$req_id";
echo $sql_edit;
$result_edit = mysql_query($sql_edit,$conn);
if($result_edit)
{
	echo "Updated";
}
else
{
	echo "Updating Error";
}
}
else if($action == 'add')

{
$sql_add = "insert into requirementtable(product_name,cat_name,sub_catname,flatno,block,project_name,status,itemsrequired)values('$pro_name','$cat_name','$subcat_name','$flat','$block','$projectname','less','$required')";
$result_add = mysql_query($sql_add,$conn);
if($result_add)
{
	echo "added";
}
else
{
	echo "Adding Error";
}
}
else if($action == 'deletep')
{
$sql_delete = "delete from requirementtable where req_id=$req_id";
$result_delete = mysql_query($sql_delete,$conn);
if($result_delete)
{
	echo "Data Deleted";
}
else
{
	echo "Error";
}
}

else if($action == 'deletet')
{
$sqlupdate = "update productlist set status='cancel' where product_id=$pro_id";
echo $sqlupdate;
$resultupdate = mysql_query($sqlupdate,$conn);
if($resultupdate)
{
	echo $sqlupdate;
	echo "Data Deleted";
	$msg = "IP: " . $ip . " TIME: " . $time . " User: " .$_SESSION['st_username'].  " USERAGENT: " .$userAgent. "Action : " .$pro_code. "Cancelled";
writeToLogFile($msg);
}

else
{
	echo $sqlupdate;
	$msg = "IP: " . $ip . " TIME: " . $time . " User: " .$_SESSION['st_username'].  " USERAGENT: " .$userAgent. "Action : " .$pro_code. "Cancelled Error";
writeToLogFile($msg);
	echo "Error";
}
}
}
else
{
	  $id = explode(",", $ids);
	  $n=0;
    for($i = 0; $i < count($id); $i++)
	{
		
		$sql_update = "update productlist set status='cancel' where product_id=$id[$i]";
		echo $sql_update;
$result_update = mysql_query($sql_update,$conn);
$n = $n+1;
}
echo $n. "Records Deleted";
}
	?>