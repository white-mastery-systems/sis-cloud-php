
<?php

include 'connect.php';

    extract($_POST);

date_default_timezone_set('Asia/Calcutta'); 

     $today = date("Y.m.d"); 
//echo $stockid;
isset($_POST['v_companyname']) ? $v_companyname = mysql_real_escape_string($_POST['v_companyname']) : $pro_name = "0";
isset($_POST['ven_id']) ? $ven_id = mysql_real_escape_string($_POST['ven_id']) : $ven_id = "0";
isset($_POST['v_address']) ? $v_address = mysql_real_escape_string($_POST['v_address']) : $v_address = "0";
isset($_POST['v_name']) ? $v_name = mysql_real_escape_string($_POST['v_name']) : $v_name = "0";
isset($_POST['v_mobile']) ? $v_mobile = mysql_real_escape_string($_POST['v_name']) : $v_mobile = "0";
isset($_POST['v_phone']) ? $v_phone = mysql_real_escape_string($_POST['v_phone']) : $subcat_name = "0";
isset($_POST['v_fax']) ? $v_fax = mysql_real_escape_string($_POST['v_fax']) : $v_fax = "0";
isset($_POST['v_email']) ? $v_email = mysql_real_escape_string($_POST['v_email']) : $v_email = "0";
isset($_POST['action']) ? $action = mysql_real_escape_string($_POST['action']) : $action = "none";
isset($_POST['ids']) ? $ids = mysql_real_escape_string($_POST['ids']) : $ids = "none";

										
//$pro_name = mysql_real_escape_string($_POST['pro_name']);
//$cat_name = mysql_real_escape_string($_POST['cat_name']);
//$subcat_name = mysql_real_escape_string($_POST['subcat_name']);
//$flat = mysql_real_escape_string($_POST['flat']);
//$block = mysql_real_escape_string($_POST['block']);
//$projectname = mysql_real_escape_string($_POST['projectname']);
//$required = mysql_real_escape_string($_POST['required']);
//$action = mysql_real_escape_string($_POST['action']);
if($ids == "none")
{
	if($action == 'open')
	{
		echo '<div id="result"></div>
<table  class="table table-full m-b-60" cellpadding="0" cellspacing="0"  id="itemsTable" style="width:100%">
<thead> 
<tr><th> <input type="checkbox" id="selectAll" />
    <label for="selectAll"></label></th>
<th><b>Vendor Name</b></th>
<th><b>Address</b></th>
<th><b>Name</b></th>
<th><b>Mobile</b></th>

<th><b>Action</b></th>
</tr>
</thead><tbody>';
require('connect.php');
$sql = 'SELECT * from vendor_tbl where status="open" order  by ven_id Desc';
$retval = mysql_query( $sql, $conn );
if(! $retval )
{
  die('Could not get data: ' . mysql_error());
}
while($row = mysql_fetch_array($retval, MYSQL_ASSOC))
{ 
echo '<tr><td style="width:10%"><input type="checkbox" id="'.$row['ven_id'].'" value="'.$row['ven_id'].'" class="checkappear-td selectedId checkbox1" />
    <label  for="'.$row['ven_id'].'"></label></td>
<td style="width:20%"><span style="display:none">'.$row['ven_id'].'-'.$row['ven_phone'].'-'.$row['ven_fax'].'-'.$row['ven_email'].'-</span>'.$row['ven_compname'].' </td>
<td style="width:30%">'.$row['ven_add1'].'</td>
<td style="width:20%">'.$row['ven_contactperson'].'</td>
<td style="width:10%">'.$row['ven_mob'].'</td>
<td style="width:10%"> <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="col-md-4 col-sm-12 col-lg-4 col-xs-12"><i class="material-icons tiny"><a href="#" title="duplicate" id="edit_p" class="use-edit" onClick="fun_edit('.$row['ven_id'].');">edit</a></i> </div> &nbsp;&nbsp; <div class="col-md-4 col-sm-12 col-lg-4 col-xs-12"><i class="material-icons tiny"><a href="#" id="delete_p" title="duplicate" class="use-delete" onClick="fun_delete('.$row['ven_id'].');">delete</a></i></div></div></tr>';
              
}    
echo  '</tbody></table>';
	}
	
	if($action == 'cancel')
	{
		echo '<div id="result"></div>
<table  class="table table-full m-b-60" cellpadding="0" cellspacing="0"  id="itemsTable" style="width:100%">
<thead> 
<tr><th> <input type="checkbox" id="selectAll" />
    <label for="selectAll"></label></th>
<th><b>Vendor Name</b></th>
<th><b>Address</b></th>
<th><b>Name</b></th>
<th><b>Mobile</b></th>

<th><b>Action</b></th>
</tr>
</thead><tbody>';
require('connect.php');
$sql = 'SELECT * from vendor_tbl where status="cancel" order  by ven_id Desc';
$retval = mysql_query( $sql, $conn );
if(! $retval )
{
  die('Could not get data: ' . mysql_error());
}
while($row = mysql_fetch_array($retval, MYSQL_ASSOC))
{ 
echo '<tr><td style="width:10%"><input type="checkbox" id="'.$row['ven_id'].'" value="'.$row['ven_id'].'" class="checkappear-td selectedId checkbox1" />
    <label  for="'.$row['ven_id'].'"></label></td>
<td style="width:20%"><span style="display:none">'.$row['ven_id'].'-'.$row['ven_phone'].'-'.$row['ven_fax'].'-'.$row['ven_email'].'-</span>'.$row['ven_compname'].' </td>
<td style="width:30%">'.$row['ven_add1'].'</td>
<td style="width:20%">'.$row['ven_contactperson'].'</td>
<td style="width:10%">'.$row['ven_mob'].'</td>
<td style="width:10%"> <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="col-md-4 col-sm-12 col-lg-4 col-xs-12"><i class="material-icons tiny"><a href="#" title="duplicate" id="edit_p" class="use-edit">edit</a></i> </div> &nbsp;&nbsp; <div class="col-md-4 col-sm-12 col-lg-4 col-xs-12"><i class="material-icons tiny"><a href="#" id="delete_p" title="duplicate" class="use-delete1" >delete</a></i></div></div></tr>';
              
}    
echo  '</tbody></table>';
	}
	
if($action == 'edit')
{


$sql_edit = "update vendor_tbl set ven_compname='$v_companyname',ven_add1='$v_address',ven_contactperson='$v_name',ven_mob='$v_mobile',ven_phone='$v_phone',ven_fax='$v_fax',ven_email='$v_email' where ven_id=$ven_id";
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
$sql_add = "insert into vendor_tbl(ven_compname,ven_contactperson,ven_add1,ven_mob,ven_phone,ven_fax,ven_email,status)values('$v_companyname','$v_name','$v_address','$v_mobile','$v_phone','$v_fax','$v_email','open')";
echo $sql_add;
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
$sql_deletep = "delete from vendor_tbl where ven_id=$ven_id";
$result_deletep = mysql_query($sql_deletep,$conn);
if($result_deletep)
{
	echo "Data Deleted Permanently";
}
else
{
	echo "Error";
}
}
else if($action == 'deletet')

{
$sql_deletet = "update vendor_tbl set status='cancel' where ven_id=$ven_id";
$result_deletet = mysql_query($sql_deletet,$conn);
if($result_deletet)
{
	echo "Data Deleted";
}
else
{
	echo "Error";
}
}
}
else
{
	  $id = explode(",", $ids);
	 $n=0;
    for($i = 0; $i < count($id); $i++){
		echo $i;
		$sql_update = "update vendor_tbl set status='cancel' where ven_id=$id[$i]";
$result_update = mysql_query($sql_update,$conn);
$n = $n+1;
}
echo $n. "Records Deleted";

}
 ?>
