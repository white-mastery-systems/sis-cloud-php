<?php
include "connect.php";


// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;
$columns = array( 
// datatable column index  => database column name
	0 => 'po_no', 
	1 => 'folder', 
	2 => 'po_no', 
	3 => 'po_date',
	4 => 'po_no',
	5 => 'description',
	6 => 'subtotal',
	7 => 'po_no',
	8 => 'po_no',
);

	
// getting total number records without any search
$sql = "SELECT p_id,folder,po_no,po_date,description,subtotal,status FROM purchase_master WHERE status='".$_GET['action']."'";
$query = mysql_query( $sql, $conn ) or die("employee-grid-data.php: get employees");
$totalData = mysql_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

if ($requestData['search']['value']!= ""){
//$sql = "SELECT p_id,folder,po_no,po_date,description,subtotal,status FROM purchase_master WHERE ";
	$sql.= " AND po_no LIKE '%".$requestData['search']['value']."%'";    // $requestData['search']['value'] contains search parameter
	$sql.=" OR po_date LIKE '%".$requestData['search']['value']."%'";
	$sql.=" OR description LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR subtotal LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR folder LIKE '%".$requestData['search']['value']."%'" ;
	
	$query= mysql_query( $sql, $conn ) or die("employee-grid-data.php: get employees");
	$totalFiltered = mysql_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result without limit in the query 
}
	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   "; // $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc , $requestData['start'] contains start row number ,$requestData['length'] contains limit length.
	
$query= mysql_query( $sql, $conn ) or die("employee-grid-data.php: get employees");
$data = array();
while( $row=mysql_fetch_array($query) ) {  // preparing an array
	$nestedData=array(); 

	$nestedData[] = '<input type="checkbox"  name="id[]" class="checkappear-td selectedId checkbox1 case" id="'.$row['p_id'].'" value="'.$row['po_no'].'"/> <label class="case" for="'.$row['p_id'].'"></label>';
	$nestedData[] ='<span style="display:none">'.$row['folder'].'</span><i class="material-icons icon-color amber darken-1">folder</i>';
	$nestedData[] = $row['po_no'];
	$nestedData[] = $row['po_date'];
	$nestedData[] = $row['po_no'].'<br/>'.$row['po_date'];
	$nestedData[] = '<a href="#purchaseview" id="'.$row['po_no'].'"onClick="viewpo('. $row['po_no'].')" rel="ajax">'.$row['description'].' </a>';
	$nestedData[] = $row["subtotal"];
	$nestedData[] = '<i class="material-icons tiny"><a href="#" title="Edit" onClick="fun_edit('.$row['po_no'].')">edit</a></i> <span style="padding-left:8px;"><i class="material-icons tiny"><a href="#" title="duplicate" onClick="fun_duplicate('.$row['po_no'].')">content_copy</a></i></span><span style="padding-left:8px;"><i class="material-icons tiny" ><a href="#" title="Print"  id="print_p" onClick="fun_print('.$row['po_no'].')" title="Print" >print</a></i></span><span style="padding-left:8px;"><i class="material-icons tiny"><a href="#" title="Email" onClick="fun_email('.$row['po_no'].')">email</a></i></span><span style="padding-left:8px;"><i class="material-icons tiny" title="Cancel"><a href="#" id="delete_p" title="duplicate" class="'.$_GET['action'].'"   title="Cancel">delete</a></i></span><span style="padding-left:8px;"> <i class="material-icons tiny"><a href="#" title="PDF" onClick="fun_pdf('.$row['po_no'].')" >picture_as_pdf</a></i></span><span style="padding-left:8px;"><i class="material-icons tiny"><a href="#" title="Word" onClick="fun_word('.$row['po_no'].')" id="download" >insert_drive_file</a></i></span>' ;
	$nestedData[] = '<span class="fixed-action-btn navigations horizontal click-to-toggle sicon">
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
 <li><a id="delete_p" class="btn-floating "'.$_GET['action'].'"  title="Cancel"><i class="material-icons i1">delete</i></a></li>
  <li><a class="btn-floating" title="PDF" onClick="fun_pdf('.$row['po_no'].')" ><i class="material-icons i1">picture_as_pdf</i></a></li>
   <li><a class="btn-floating" title="Word" onClick="fun_word('.$row['po_no'].')" id="download" ><i class="material-icons i1">insert_drive_file</i></a></li>
</ul>
</span>' ;
	
	
	$data[] = $nestedData;
}



$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $data   // total data array
			);


echo json_encode($json_data);  // send data as json format

?>
