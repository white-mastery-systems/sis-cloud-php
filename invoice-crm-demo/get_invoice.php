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

require('connect1.php');
$invoiceno = 0;
if($projectname == 'S.I.S Acropole')
{
//$result = mysql_query("SELECT invoiceno FROM invoicetable where po_year='".$FinancialYear."' and gst_status='".$gststatus."' and projectname = '$projectname' and block='$block' ORDER BY invoiceno DESC LIMIT 1",$conn);
//    $result = mysqli_query($conn,"SELECT invoiceno FROM invoicetable where gst_status='".$gststatus."' and projectname = '$projectname' and block='$block' ORDER BY invoiceno DESC LIMIT 1");
	
	$result = mysqli_query($conn,"SELECT invoiceno FROM invoicetable where gst_status='".$gststatus."' and projectname = '$projectname' and block='$block' ORDER BY invoiceid DESC LIMIT 1");
}
if($projectname == 'S.I.S Capetown')
{
//$result = mysqli_query("SELECT invoiceno FROM invoicetable where po_year='".$FinancialYear."' and gst_status='".$gststatus."' and projectname = '$projectname' and block='$block' ORDER BY invoiceno DESC LIMIT 1",$conn);
//    $result = mysqli_query($conn,"SELECT invoiceno FROM invoicetable where gst_status='".$gststatus."' and projectname = '$projectname' ORDER BY invoiceno DESC LIMIT 1");
	$result = mysqli_query($conn,"SELECT invoiceno FROM invoicetable where gst_status='".$gststatus."' and projectname = '$projectname' ORDER BY invoiceid DESC LIMIT 1");
}

else
{
    if( $block == 'commercial')
    {
//    $result = mysqli_query($conn,"SELECT invoiceno FROM invoicetable where gst_status='".$gststatus."' and projectname = '$projectname' and block='$block' ORDER BY invoiceno DESC LIMIT 1");  
		$result = mysqli_query($conn,"SELECT invoiceno FROM invoicetable where gst_status='".$gststatus."' and projectname = '$projectname' and block='$block' ORDER BY invoiceid DESC LIMIT 1");
    }
  else
  {
//     $result = mysqli_query($conn,"SELECT invoiceno FROM invoicetable where gst_status='".$gststatus."' and projectname = '$projectname' ORDER BY invoiceno DESC LIMIT 1"); 
	  $result = mysqli_query($conn,"SELECT invoiceno FROM invoicetable where gst_status='".$gststatus."' and projectname = '$projectname' and block != 'commercial' ORDER BY invoiceid DESC LIMIT 1"); 
  }
}

  if ($row = mysqli_fetch_array($result)) {
$num_rows= mysqli_num_rows($result);
$invoiceno = $row['invoiceno'] +1;
echo $invoiceno;   
  }
else
  {
  $invoiceno+=1;
  echo $invoiceno;
  }
  ?>