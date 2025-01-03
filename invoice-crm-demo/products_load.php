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
isset($_POST['pro_vat']) ? $pro_vat = mysql_real_escape_string($_POST['pro_vat']) :$pro_vat = "0";
isset($_POST['pro_ed']) ? $pro_ed = mysql_real_escape_string($_POST['pro_ed']) : $pro_ed = "0";
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


if($action == 'open' and $ids == 'none')
{
echo '<div id="result"></div>';
echo '<table  class="table table-full m-b-60" cellpadding="0" cellspacing="0"  id="itemsTable" style="width:100%">
<thead> 
<tr><th><input type="checkbox" id="selectAll" />
    <label for="selectAll"></label></th>
<th><b>Name</b></th>
<th><b>Code</b></th>
<th><b>Size</b></th>
<th><b>Price</b></th>
<th><b>Category</b></th>
<th><b>Sub Category</b></th>
<th><b>Action</b></th>
</tr>
</thead><tbody>';
require('connect.php');
$sql = 'SELECT * from productlist where status="open" order by product_id Desc';
$retval = mysql_query( $sql, $conn );
if(! $retval )
{
  die('Could not get data: ' . mysql_error());
}
while($row = mysql_fetch_array($retval, MYSQL_ASSOC))
{ 
echo '<tr><td style="width:10%"><input type="checkbox"  class="checkappear-td selectedId checkbox1" id="'.$row['product_id'].'" value="'.$row['product_id'].'"  />
    <label  for="'.$row['product_id'].'"></label></td>
<td style="width:20%">'.$row['pro_name'].'<span style="display:none">+'.$row['product_id'].'+'.$row['brand'].'+'.$row['typemat'].'+'.$row['unit'].'+'.$row['pro_details'].'+'.$row['vat'].'+'.$row['tp'].'+'.$row['st'].'+'.$row['ed'].'+'.$row['i_cost'].'+'.$row['m_cost'].'+'.$row['pro_width'].'+'.$row['pro_height'].'</span></td>
<td style="width:10%">'.$row['product_code'].'</td>
<td style="width:10%">'.$row['size'].'</td>
<td style="width:10%">'.$row['Product_price'].'</td>
<td style="width:15%">'.$row['category_name'].'</td>
<td style="width:15%">'.$row['sub_catname'].'</td>


<td style="width:10%"> <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="col-md-4 col-sm-12 col-lg-4 col-xs-12"><i class="material-icons tiny"><a href="#" title="Edit" id="edit_p" class="use-edit" onClick="fun_edit('.$row['product_id'].');">edit</a></i> </div> &nbsp;&nbsp; <div class="col-md-4 col-sm-12 col-lg-4 col-xs-12"><i class="material-icons tiny"><a href="#" id="delete_p" class="use-delete" title="Delete" onClick="fun_delete('.$row['product_id'].');">delete</a></i></div></div></tr>';
              
}    
echo  '</tbody></table>';
$msg = "IP: " . $ip . " TIME: " . $time . " User: " .$_SESSION['st_username'].  " USERAGENT: " .$userAgent. "Action :Product Active Page";
writeToLogFile($msg);
}
if($action == 'cancel')
{
echo '<div id="result"></div>';
echo '<table  class="table table-full m-b-60" cellpadding="0" cellspacing="0"  id="itemsTable" style="width:100%">
<thead> 
<tr><th> <input type="checkbox" id="selectAll" />
    <label for="selectAll"></label></th>
<th><b>Name</b></th>
<th><b>Code</b></th>
<th><b>Size</b></th>
<th><b>Price</b></th>
<th><b>Category</b></th>
<th><b>Sub Category</b></th>

<th><b>Action</b></th>
</tr>
</thead><tbody>';
require('connect.php');
$sql = 'SELECT * from productlist where status="cancel" order by product_id Desc';
$retval = mysql_query( $sql, $conn );
if(! $retval )
{
  die('Could not get data: ' . mysql_error());
}
while($row = mysql_fetch_array($retval, MYSQL_ASSOC))
{ 
echo '<tr><td style="width:10%"><input type="checkbox"  class="checkappear-td selectedId checkbox1" id="'.$row['product_id'].'"  />
    <label  for="'.$row['product_id'].'"></label></td>
<td style="width:20%"><span style="display:none"></span>'.$row['pro_name'].'<span style="display:none">+'.$row['product_id'].'+'.$row['brand'].'+'.$row['typemat'].'+'.$row['unit'].'+'.$row['pro_ton'].'+'.$row['pro_details'].'+'.$row['vat'].'+'.$row['tp'].'+'.$row['st'].'+'.$row['ed'].'+'.$row['i_cost'].'+'.$row['m_cost'].'+'.$row['pro_width'].'+'.$row['pro_height'].'</span></td>
<td style="width:10%">'.$row['product_code'].'</td>
<td style="width:10%">'.$row['size'].'</td>
<td style="width:10%">'. number_format($row['Product_price'], 2, '.', '').'</td>
<td style="width:15%">'.$row['category_name'].'</td>
<td style="width:15%">'.$row['sub_catname'].'</td>


<td style="width:10%"> <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="col-md-4 col-sm-12 col-lg-4 col-xs-12"><i class="material-icons tiny"><a href="#" title="Edit" id="edit_p" class="use-edit" >edit</a></i> </div> &nbsp;&nbsp; <div class="col-md-4 col-sm-12 col-lg-4 col-xs-12"><i class="material-icons tiny"><a href="#" id="delete_p" class="use-delete1" title="Delete" onClick="fun_delete('.$row['product_id'].');">delete</a></i></div></div></tr>';
              
}    
echo  '</tbody></table>';
$msg = "IP: " . $ip . " TIME: " . $time . " User: " .$_SESSION['st_username'].  " USERAGENT: " .$userAgent. "Action :Product Cancel Page";
writeToLogFile($msg);

}
else if($action == 'edit' and $ids == 'none')
{


$sql_edit = "update productlist set pro_name='$pro_name',product_code='$pro_code',size='$pro_size',Product_price='$pro_price',brand='$pro_brand',category_name='$pro_category',sub_catname='$pro_subcat',pro_width='$pro_width',pro_height='$pro_height',unit='$pro_units',vat='$pro_vat',m_cost='$pro_materialcost',i_cost='$pro_icost',ed='$pro_ed',st='$pro_st',tp='$pro_tp',typemat='$typemat',pro_details='$pro_details',status='open' where product_id=$pro_id";
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
else if($action == 'add' and $ids == 'none')

{
$sql_add = "insert into  productlist(pro_name,product_code,size,Product_price,brand,category_name,sub_catname,pro_width,pro_height,unit,vat,m_cost,i_cost,ed,st,tp,typemat,pro_ton,pro_details,status)values('$pro_name','$pro_code','$pro_size','$pro_price','$pro_brand','$pro_category','$pro_subcat','$pro_width','$pro_height','$pro_units','$pro_vat','$pro_icost','$pro_materialcost','$pro_ed','$pro_st','$pro_tp','$typemat','$pro_ton','$pro_details','open')";
echo $sql_add;
$result_add = mysql_query($sql_add,$conn);

if($result_add)
{
	
	$msg = "IP: " . $ip . " TIME: " . $time . " User: " .$_SESSION['st_username'].  " USERAGENT: " .$userAgent. "Action : " .$pro_code. "Added";
writeToLogFile($msg);

}
else
{
	echo "Adding Error";
		$msg = "IP: " . $ip . " TIME: " . $time . " User: " .$_SESSION['st_username'].  " USERAGENT: " .$userAgent. "Action : " .$pro_code. "Added Error";
writeToLogFile($msg);
}
}
else if($action == 'deletep' and $ids == 'none')

{

$sql_delete = "delete from productlist where product_id=$pro_id";
echo $sql_delete;
$result_delete = mysql_query($sql_delete,$conn);
if($result_delete)
{
	echo "Data Deleted Permanetly";
	$msg = "IP: " . $ip . " TIME: " . $time . " User: " .$_SESSION['st_username'].  " USERAGENT: " .$userAgent. "Action : " .$pro_code. "Deleted Permanently";
writeToLogFile($msg);
}
}
else if($action == 'deletet' and $ids == 'none' )
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
else if($action == 'open' and $ids != 'none')
{
	
	  $id = explode(",", $ids);
	  $n=0;
    for($i = 0; $i < count($id); $i++)
	{
		
		$sql_update = "update productlist set status='cancel' where product_id=$id[$i]";
		//echo $sql_update;
$result_update = mysql_query($sql_update,$conn);
$n = $n+1;
}
//echo $n. "Records Deleted";
}
else
{
	$id = explode(",", $ids);
	  $n=0;
    for($i = 0; $i < count($id); $i++)
	{
		
		$sql_update = "delete from productlist where product_id=$id[$i]";
		//echo $sql_update;
$result_update = mysql_query($sql_update,$conn);
$n = $n+1;
}
	//echo $n. "Records Deleted Permanently";
}
	?>