<?php
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
	?>