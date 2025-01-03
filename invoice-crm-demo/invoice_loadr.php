<?php
include "connect.php";
include "moneyformat.php";
// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;
$columns = array( 
// datatable column index  => database column name
	0 => 'invoiceid',
    1 => 'date',
	2 => 'flatno', 
	3 => 'projectname', 	
	4 => 'total',
	5 => 'invoiceid',
	
);	
// getting total number records without any search

 $sql = "SELECT invoiceid,invoicenotype,invoiceno,po_year,invoicedate,projectname,block,flatno,floorno,total,lc_amount,taxamount,sgst,cgst,roundtotal,grandtotal,status FROM invoicetable where gst_status='".$_GET['action']."' and projectname='".$_GET['projectname']."'";     

$query = mysql_query( $sql, $conn ) or die("invoice_load.php: get orders");
$totalData = mysql_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

if ($requestData['search']['value']!= ""){  
//$sql = "SELECT p_id,folder,po_no,po_date,description,subtotal,status FROM purchase_master WHERE ";  
    $sql.=" AND projectname LIKE '%".$requestData['search']['value']."%' ";
    $sql.= " OR  gst_status='".$_GET['action']."' AND invoicenotype LIKE '%".$requestData['search']['value']."%'"; 
    $sql.=" OR  gst_status='".$_GET['action']."' AND invoicedate LIKE '%".$requestData['search']['value']."%'";
	   // $requestData['search']['value'] contains search parameter
	$sql.=" OR  gst_status='".$_GET['action']."' AND flatno LIKE '%".$requestData['search']['value']."%'";
	$sql.=" OR  gst_status='".$_GET['action']."' AND projectname LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR  gst_status='".$_GET['action']."' AND total LIKE '%".$requestData['search']['value']."%' ";
	
	
	$query= mysql_query( $sql, $conn ) or die("invoice_load.php: get orders");
	$totalFiltered = mysql_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result without limit in the query 
}
	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   "; // $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc , $requestData['start'] contains start row number ,$requestData['length'] contains limit length.
	
$query= mysql_query( $sql, $conn ) or die("invoice_load.php:get orders");
$data = array();
while( $row=mysql_fetch_array($query) ) {  // preparing an array
	$nestedData=array(); 

	$nestedData[] = $row['invoicenotype'];
	$nestedData[] = $row['invoicedate'];
    if($row['projectname'] == 'S.I.S Luxor')
    {
      $nestedData[] = $row['flatno'];  
    }
	else
    {
        $nestedData[] = $row['block']."-".$row['flatno'];   
    }
    
    $nestedData[] = $row['projectname'];
	$nestedData[] = indian_number_format($row['total']);
    $nestedData[] = $row['status'];
	$nestedData[] = '<span style="display:none">'.$row['po_year'].'+</span><i class="material-icons tiny"><a href="#" title="Edit" onClick='.chr(34).'fun_edit('.chr(39).$row['invoicenotype'].chr(39).','.chr(39).$row['po_year'].chr(39).')'.chr(34).')">edit</a></i><span style="padding-left:8px;"><i class="material-icons tiny" title="Cancel"><a href="#" title="PDF" onClick='.chr(34).'fun_pdf('.chr(39).$row['invoicenotype'].chr(39).','.chr(39).$row['po_year'].chr(39).')'.chr(34).' ><i class="material-icons i1">picture_as_pdf</i></a></i></span><span style="padding-left:8px;"><i class="material-icons tiny" title="Cancel"><a href="#" title="View" onClick='.chr(34).'fun_view('.chr(39).$row['invoicenotype'].chr(39).','.chr(39).$row['po_year'].chr(39).')'.chr(34).' ><i class="material-icons tiny">visibility</i></a></i></span> <span style="padding-left:8px;"><i class="material-icons tiny" title="Cancel"><a class="cancel" id="delete_p" href="#" title="Delete" ><i class="material-icons i1">delete</i></a></i></span> <span style="padding-left:8px;"><i class="material-icons tiny" title="Cancel"><a class="cancelinvoice" id="cancelinvoice" href="#" title="Cancel" ><i class="material-icons i1">clear</i></a></i></span>' ;
	$nestedData[] =  '<span style="display:none">'.$row['po_year'].'+</span> <span class="fixed-action-btn navigations horizontal click-to-toggle sicon">
<a class="btn-floating" href="javascript:void"><i class="mfb-component__main-icon--resting material-icons i1">add</i></a>	
	 <!--<span class="fixed-action-btn navigations horizontal click-to-toggle">  
  <a class="btn-floating" href="javascript:void">
  <i class="mfb-component__main-icon--resting material-icons i1">add</i>
   <i class="mfb-component__main-icon--resting material-icons i1">close</i>
    </a>-->
  <ul>
<li><a class="btn-floating" title="Edit" onClick="fun_edit('.$row['invoiceno'].','. $row['po_year'].')"><i class="material-icons i1">edit</i></a></li>

 
 <li><a class="btn-floating" title="Email" onClick="fun_email('.$row['invoiceno'].','. $row['po_year'].')"><i class="material-icons i1">email</i></a></li>
 <li><a id="delete_p" class="btn-floating "'.$_GET['action'].'"  title="Cancel"><i class="material-icons i1">delete</i></a></li>
  <li><a class="btn-floating" title="PDF" onClick='.chr(34).'fun_pdf('.chr(39).$row['invoiceno'].chr(39).','.chr(39).$row['po_year'].chr(39).')'.chr(34).' ><i class="material-icons i1">picture_as_pdf</i></a></li>
  
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
