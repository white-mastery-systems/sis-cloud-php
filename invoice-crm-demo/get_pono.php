<?php
 date_default_timezone_set('Asia/Calcutta'); 
date('d.m.Y');
$MM = date('m');
if($MM < 4 )
{
    $FinancialYear = date('Y')-1;
}
else
{
    $FinancialYear = date('Y');
}

	
require('connect.php');
$po_no = 0;
$result = mysql_query("SELECT po_no FROM purchaseorder_tbl where po_year='".$FinancialYear."' and gst_status='included' ORDER BY po_no DESC LIMIT 1",$conn);
  if ($row = mysql_fetch_array($result)) {
 $num_rows= mysql_num_rows($result);
  $po_no = $row['po_no'] +1;
  echo $po_no;
   
  }
  else
  {
  $po_no+=1;
  echo $po_no;
  }
  ?>