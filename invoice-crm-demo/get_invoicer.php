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

$gststatus = $_POST['gststatus'] ;
$projectname = $_POST['projectname'] ;	
$block = $_POST['block'] ;	

require('connect.php');
$invoiceno = 0;
if($projectname == 'S.I.S Acropole')
{
$result = mysql_query("SELECT invoiceno FROM invoicetable where po_year='".$FinancialYear."' and gst_status='".$gststatus."' and projectname = '$projectname' and block='$block' ORDER BY invoiceno DESC LIMIT 1",$conn);
}
else
{
$result = mysql_query("SELECT invoiceno FROM invoicetable where po_year='".$FinancialYear."' and gst_status='".$gststatus."' and projectname = '$projectname' ORDER BY invoiceno DESC LIMIT 1",$conn);  
}

  if ($row = mysql_fetch_array($result)) {
$num_rows= mysql_num_rows($result);
$invoiceno = $row['invoiceno'] +1;
echo $invoiceno;   
  }
else
  {
  $invoiceno+=1;
  echo $invoiceno;
  }
  ?>