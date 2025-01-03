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
    <th column="A" class="font12" id="data1"><input type="checkbox" id="selectAll" /><label for="selectAll"></label></th>
	<th column="B" class="font12" id="data2"><b>Icon</b></th>
	<th column="C" class="font12" id="data3"><b>Invoice No</b><i class="material-icons tiny1">sort</i></th>
	<th column="D" class="font12" id="data4"><b>Date </b> <i class="material-icons tiny1">sort</i></th>
	<th column="E" class="font12" id="data8"><b>Invoice No</b> & <b>Date </b><i class="material-icons tiny1">sort</i></th>
	<th column="F" class="font12" id="data5"><b>Summary</b> <i class="material-icons tiny1">sort</i></th>
	<th column="G" class="font12" id="data6"><b>Total Rs</b> <i class="material-icons tiny1">sort</i></th>
	<th column="H" class="font12" id="data7"><b>Action</b></th>
	<th column="I" class="font12 data9" id="data9"><b>Action</b></th>
	</thead>
      <tbody>'; 
    $result = mysql_query("SELECT * FROM  purchase_master where status='open' ORDER BY po_no DESC",$conn);
     while ($row = mysql_fetch_array($result)) {
     echo '<tr class="avail-td-content1">
    <td column="A" id="data1"><input type="checkbox"  class="checkappear-td selectedId checkbox1" id="'.$row['p_id'].'"/> <label for="'.$row['p_id'].'"></label></td>
	<td column="B" id="data2"><span style="display:none">'.$row['folder'].'</span><i class="material-icons icon-color amber darken-1">folder</i></td>
	<td column="C" id="data3">'.$row['po_no'].'</td>
	<td column="D" id="data4">'.$row['po_date'].'</td>
	<td column="E" id="data8">'.$row['po_no'].'<br/>'.$row['po_date'].'</td>
	<td column="F" id="data5"><a href="#purchaseview" id="'.$row['po_no'].'"onClick="viewpo('. $row['po_no'].')" rel="ajax">'.$row['description'].' </a></td>
	<td column="G" id="data6">'.number_format($row['subtotal'], 2, '.', '').'</td>
	<td column="H" id="data7"><i class="material-icons tiny"><a href="#" title="Edit" onClick="fun_edit('.$row['po_no'].')">edit</a></i><span style="padding-left:8px;"><i class="material-icons tiny"><a href="#" title="duplicate" onClick="fun_duplicate('.$row['po_no'].')">content_copy</a></i></span><span style="padding-left:8px;"><i class="material-icons tiny" ><a href="#" title="Print"  id="print_p" onClick="fun_print('.$row['po_no'].')" title="Print" >print</a></i></span><span style="padding-left:8px;"><i class="material-icons tiny"><a href="#" title="Email" onClick="fun_email('.$row['po_no'].')">email</a></i></span><span style="padding-left:8px;"><i class="material-icons tiny" title="Cancel"><a href="#" id="delete_p" title="duplicate" class="use-delete"  title="Cancel">delete</a></i></span><span style="padding-left:8px;"> <i class="material-icons tiny"><a href="#" title="PDF" onClick="fun_pdf('.$row['po_no'].')" >picture_as_pdf</a></i></span><span style="padding-left:8px;"><i class="material-icons tiny"><a href="#" title="Word" onClick="fun_word('.$row['po_no'].')" id="download" >insert_drive_file</a></i></span></td>
	<td column="I" id="data9">
	
	<span class="fixed-action-btn navigations horizontal click-to-toggle sicon">
<a class="btn-floating" href="javascript:void"><i class="mfb-component__main-icon--resting material-icons i1">add</i></a>
 
	
	 <!--<span class="fixed-action-btn navigations horizontal click-to-toggle">  
  <a class="btn-floating" href="javascript:void">
  <i class="mfb-component__main-icon--resting material-icons i1">add</i>
   <i class="mfb-component__main-icon--resting material-icons i1">close</i>
    </a>-->

  <ul>
<li><a class="btn-floating" title="Edit" onClick="fun_edit('.$row['po_no'].')"><i class="material-icons i1">edit</i></a></li>
<li><a class="btn-floating" title="Duplicate" onClick="fun_duplicate('.$row['po_no'].')"><i class="material-icons i1">content_copy</i></a></li>
 <li><a class="btn-floating" title="Print" onClick="fun_print('.$row['po_no'].')"><i class="material-icons i1">print</i></a></li>
 <li><a class="btn-floating" title="Email" onClick="fun_email('.$row['po_no'].')"><i class="material-icons i1">email</i></a></li>
 <li><a id="delete_p" class="btn-floating use-delete" title="Cancel"><i class="material-icons i1">delete</i></a></li>
  <li><a class="btn-floating" title="PDF" onClick="fun_pdf('.$row['po_no'].')" ><i class="material-icons i1">picture_as_pdf</i></a></li>
   <li><a class="btn-floating" title="Word" onClick="fun_word('.$row['po_no'].')" id="download" ><i class="material-icons i1">insert_drive_file</i></a></li>
</ul>
</span></td>
	
</tr>';
}
       echo '</tbody>
<tfoot> </tfoot>    
</table>';
}
else if($action == 'cancel')

{
	
      echo '<table width="100%" border="0" class="table avail-div m-b-60" cellpadding="0" cellspacing="0" id="itemsTable">
      <thead class="bordernone">
    <tr class="title">
    <th column="A" class="font12" id="data1"><input type="checkbox" id="selectAll" /><label for="selectAll"></label></th>
	<th column="B" class="font12" id="data2"><b>Icon</b></th>
	<th column="C" class="font12" id="data3"><b>Invoice No</b><i class="material-icons tiny1">sort</i></th>
	<th column="D" class="font12" id="data4"><b>Date </b> <i class="material-icons tiny1">sort</i></th>
	<th column="E" class="font12" id="data8"><b>Invoice No</b> & <b>Date </b><i class="material-icons tiny1">sort</i></th>
	<th column="F" class="font12" id="data5"><b>Summary</b> <i class="material-icons tiny1">sort</i></th>
	<th column="G" class="font12" id="data6"><b>Total Rs</b> <i class="material-icons tiny1">sort</i></th>
	<th column="H" class="font12" id="data7"><b>Action</b></th>
	<th column="I" class="font12 data9" id="data9"><b>Action</b></th>
	</thead>
      <tbody>'; 


    $result = mysql_query("SELECT * FROM  purchase_master where status='cancel' ORDER BY po_no DESC",$conn);
     while ($row = mysql_fetch_array($result)) {
				
					
     echo '<tr class="avail-td-content1">
    <td column="A" id="data1"><input type="checkbox"  class="checkappear-td selectedId checkbox1" id="'.$row['p_id'].'"/> <label for="'.$row['p_id'].'"></label></td>
	<td column="B" id="data2"><span style="display:none">'.$row['folder'].'</span><i class="material-icons icon-color amber darken-1">folder</i></td>
	<td column="C" id="data3">'.$row['po_no'].'</td>
	<td column="D" id="data4">'.$row['po_date'].'</td>
	<td column="E" id="data8">'.$row['po_no'].'<br/>'.$row['po_date'].'</td>
	<td column="F" id="data5"><a href="#purchaseview" id="'.$row['po_no'].'"onClick="viewpo('. $row['po_no'].')" rel="ajax">'.$row['description'].' </a></td>
	<td column="G" id="data6">'.number_format($row['subtotal'], 2, '.', '').'</td>
	<td column="H" id="data7"><i class="material-icons tiny"><a href="#" title="Edit" onClick="fun_edit('.$row['po_no'].')">edit</a></i><span style="padding-left:8px;"><i class="material-icons tiny"><a href="#" title="duplicate" onClick="fun_duplicate('.$row['po_no'].')">content_copy</a></i></span><span style="padding-left:8px;"><i class="material-icons tiny" ><a href="#" title="Print"  id="print_p" onClick="fun_print('.$row['po_no'].')" title="Print" >print</a></i></span><span style="padding-left:8px;"><i class="material-icons tiny"><a href="#" title="Email" onClick="fun_email('.$row['po_no'].')">email</a></i></span><span style="padding-left:8px;"><i class="material-icons tiny" title="Cancel"><a href="#" id="delete_p" title="duplicate" class="use-delete"  title="Cancel">delete</a></i></span><span style="padding-left:8px;"> <i class="material-icons tiny"><a href="#" title="PDF" onClick="fun_pdf('.$row['po_no'].')" >picture_as_pdf</a></i></span><span style="padding-left:8px;"><i class="material-icons tiny"><a href="#" title="Word" onClick="fun_word('.$row['po_no'].')" id="download" >insert_drive_file</a></i></span></td>
	<td column="I" id="data9">
	
	<span class="fixed-action-btn navigations horizontal click-to-toggle sicon">
<a class="btn-floating" href="javascript:void"><i class="mfb-component__main-icon--resting material-icons i1">add</i></a>
 
	
	 <!--<span class="fixed-action-btn navigations horizontal click-to-toggle">  
  <a class="btn-floating" href="javascript:void">
  <i class="mfb-component__main-icon--resting material-icons i1">add</i>
   <i class="mfb-component__main-icon--resting material-icons i1">close</i>
    </a>-->

  <ul>
<li><a class="btn-floating" title="Edit" onClick="fun_edit('.$row['po_no'].')"><i class="material-icons i1">edit</i></a></li>
<li><a class="btn-floating" title="Duplicate" onClick="fun_duplicate('.$row['po_no'].')"><i class="material-icons i1">content_copy</i></a></li>
 <li><a class="btn-floating" title="Print" onClick="fun_print('.$row['po_no'].')"><i class="material-icons i1">print</i></a></li>
 <li><a class="btn-floating" title="Email" onClick="fun_email('.$row['po_no'].')"><i class="material-icons i1">email</i></a></li>
 <li><a id="delete_p" class="btn-floating use-delete" title="Cancel"><i class="material-icons i1">delete</i></a></li>
  <li><a class="btn-floating" title="PDF" onClick="fun_pdf('.$row['po_no'].')" ><i class="material-icons i1">picture_as_pdf</i></a></li>
   <li><a class="btn-floating" title="Word" onClick="fun_word('.$row['po_no'].')" id="download" ><i class="material-icons i1">insert_drive_file</i></a></li>
</ul>
</span></td>
	
</tr>';
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