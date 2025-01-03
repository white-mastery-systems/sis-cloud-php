<?php 
include "connect.php";
 isset($_POST['po_no']) ? $po_no = mysql_real_escape_string($_POST['po_no']) : $po_no = "0";
isset($_POST['action']) ? $action = mysql_real_escape_string($_POST['action']) : $action = "none";
isset($_POST['ids']) ? $ids = mysql_real_escape_string($_POST['ids']) : $ids = "none";
if($action == 'open')
{
      echo '<table width="100%" border="0" class="table avail-div m-b-60" cellpadding="0" cellspacing="0" id="itemsTable">
      <thead class="bordernone">
    <tr class="title">
    <th width="7%" class="font12"><input type="checkbox" id="selectAll" /><label for="selectAll"></label></th>
	<th width="7%" class="font12"><b>Icon</b></th>
	<th width="15%" class="font12"><b>Invoice No</b><i class="material-icons tiny1">sort</i></th>
	<th width="12%" class="font12"><b>Date </b> <i class="material-icons tiny1">sort</i></th>
	<th width="23.5%" class="font12"><b>Summary</b> <i class="material-icons tiny1">sort</i></th>
	<th width="15%" class="font12"><b>Total Rs</b> <i class="material-icons tiny1">sort</i></th>
	<th width="20.5%" class="font12"><b>Action</b></th></thead>
      <tbody>'; 
    $result = mysql_query("SELECT * FROM  purchase_master where status='open' ORDER BY po_no DESC",$conn);
     while ($row = mysql_fetch_array($result)) {
     echo '<tr class="avail-td-content1">
    <td width="7%" class="ptop5"><input type="checkbox"  class="checkappear-td selectedId checkbox1" id="'.$row['p_id'].'"/> <label for="'.$row['p_id'].'"></label></td>
	<td width="7%" class="ptop5"><span style="display:none">'.$row['folder'].'</span><i class="material-icons icon-color amber darken-1">folder</i></td>
	<td width="15%" class="ptop5">'.$row['po_no'].'</td>
	<td width="12%" class="ptop5">'.$row['po_date'].'</td>
	<td width="23.5%" class="ptop5"><a href="#purchaseview" id="'.$row['po_no'].'"onClick="viewpo('. $row['po_no'].')" rel="ajax">'.$row['description'].' </a></td>
	<td width="15%" class="ptop5">'.number_format($row['subtotal'], 2, '.', '').'</td>
	<td width="20.5%" class="ptop5"><i class="material-icons tiny"><a href="#" title="Edit" onClick="fun_edit('.$row['po_no'].')">edit</a></i><span style="padding-left:8px;"><i class="material-icons tiny"><a href="#" title="duplicate" onClick="fun_duplicate('.$row['po_no'].')">content_copy</a></i></span><span style="padding-left:8px;"><i class="material-icons tiny" ><a href="#" title="Print"  id="print_p" onClick="fun_print('.$row['po_no'].')" title="Print" >print</a></i></span><span style="padding-left:8px;"><i class="material-icons tiny"><a href="#" title="Email" onClick="fun_email('.$row['po_no'].')">email</a></i></span><span style="padding-left:8px;"><i class="material-icons tiny" title="Cancel"><a href="#" id="delete_p" title="duplicate" class="use-delete"  title="Cancel">delete</a></i></span><span style="padding-left:8px;"> <i class="material-icons tiny"><a href="#" title="PDF" onClick="fun_pdf('.$row['po_no'].')" >picture_as_pdf</a></i></span><span style="padding-left:8px;"><i class="material-icons tiny"><a href="#" title="Word" onClick="fun_word('.$row['po_no'].')" id="download" >insert_drive_file</a></i></span></td>
</tr>';
}
       echo '</tbody>
<tfoot> </tfoot>    
</table>';
}
else if($action == 'cancel')

{
	
      echo '<table  class="table table-full m-b-60" cellpadding="0" cellspacing="0" id="itemsTable" style="width:100%">
      <thead>
      <tr> <th align="center" style="width:10%">  <input type="checkbox" id="selectAll" /><label for="selectAll"></label></th>
      <th style="width:8%"><b>Icon</b></th>
      <th  style="width:10%"><b>Invoice No  </b> <i class="material-icons tiny1">sort</i></th>
      <th  style="width:10%"><b>Date </b> <i class="material-icons tiny1">sort</i></th>
      <th  style="width:28%"><b>Summary</b> <i class="material-icons tiny1">sort</i></th>
      <th  style="width:15%" ><div class="textalignright" style="width:60%"><b>Total Rs</b> <i class="material-icons tiny1">sort</i></div></th>
      <th  style="width:25%" ><b>Action</b></th></tr></thead>
      <tbody>';


    $result = mysql_query("SELECT * FROM  purchase_master where status='cancel' ORDER BY po_no DESC",$conn);
     while ($row = mysql_fetch_array($result)) {
				
					
     echo '<tr><td align="center"><input type="checkbox"  class="checkappear-td selectedId" id="'.$row['p_id'].'"/>
    <label for="'.$row['p_id'].'"></label></td>
     <td><i class="material-icons icon-color amber darken-1">folder</i></td>
     <td>'.$row['po_no'].'</td>
      <td>'.$row['po_date'].'</td>
       <td><a href="#purchaseview" id="'.$row['po_no'].'"onClick="viewpo('. $row['po_no'].')" rel="ajax">'.$row['description'].' </a></td>
      <td><div class="textalignright" style="width:60%">'.number_format($row['subtotal'], 2, '.', '').'</div></td>
       <td ><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="col-md-2 col-sm-12 col-lg-2 col-xs-12" style="width:auto; padding:0 5px 0 5px"><i class="material-icons tiny"><a href="#" title="duplicate" onClick="fun_edit('.$row['po_no'].')">content_copy</a></i></div> <div class="col-md-2 col-sm-12 col-lg-2 col-xs-12" style="width:auto; padding:0 5px 0 5px"><i class="material-icons tiny"><a href="#" title="Print" title="Print"  id="print_p" onClick="fun_print('.$row['po_no'].')" >print</a></i></div> <div class="col-md-2 col-sm-12 col-lg-2 col-xs-12" style="width:auto; padding:0 5px 0 5px"><i class="material-icons tiny"><a href="#" title="Email" onClick="fun_email('.$row['po_no'].')">email</a></i></div><div class="col-md-2 col-sm-12 col-lg-2 col-xs-12" style="width:auto; padding:0 5px 0 5px"><i class="material-icons tiny" title="Cancel"><a href="#"  class="use-delete1" title="Cancel">delete</a></i></div> <div class="col-md-2 col-sm-12 col-lg-2 col-xs-12" style="width:auto; padding:0 5px 0 5px"><i class="material-icons tiny"><a href="#" title="PDF" onClick="fun_pdf('.$row['po_no'].')" >picture_as_pdf</a></i></div></div></td></tr>';
}
       
echo '</tbody>
<tfoot> </tfoot>    
</table>';
}
else if($action == 'deletet')
{
	$sql = "UPDATE purchase_master SET status = 'cancel' WHERE po_no ='$po_no'" ;
$result = mysql_query( $sql, $conn );
$sql2 = "UPDATE  purchaseorder_tbl SET status = 'cancel' WHERE Po_no ='$po_no'" ;
$result2 = mysql_query( $sql2, $conn );
$sql1 = "UPDATE  stocklist SET status = 'cancel' WHERE po_no ='$po_no'" ;
$result1 = mysql_query( $sql1, $conn );

if($result)
{
echo "PO Cancelled";
}
}
else if($action == 'deletep')

{
	$sql = "delete from purchase_master where po_no =$po_no" ;
	echo $sql;
$result = mysql_query( $sql, $conn );

$sql1 = "delete from purchaseorder_tbl where Po_no =$po_no" ;
echo $sql1;
$result2 = mysql_query( $sql1, $conn );
$sql2 = "delete from  stocklist where po_no =$po_no" ;
echo $sql2;
$result2 = mysql_query( $sql2, $conn );

if($result)
{
echo "Deleted Permanently";
}
}

    ?>