<?php
session_start();
include 'connect.php';
include 'writelog.php';
isset($_POST['po_date']) ? $po_date = $_POST['po_date'] : $po_date = "none";
isset($_POST['ven_id']) ? $ven_id = $_POST['ven_id'] : $ven_id = "none";
isset($_POST['project_name']) ? $project_name = $_POST['project_name'] : $project_name = "none";
isset($_POST['blockname']) ? $blockname = $_POST['blockname'] : $blockname = "blockname";
isset($_POST['project_id']) ? $project_id = $_POST['project_id'] : $project_id = "none";
isset($_POST['itemDesc']) ? $product_names  = $_POST['itemDesc'] : $product_names  = "none";
isset($_POST['vat_o']) ? $tax = $_POST['vat_o'] : $tax = "0";
isset($_POST['vat']) ? $vat = $_POST['vat'] : $vat = "0";
isset($_POST['subTotal']) ? $subTotal = $_POST['subTotal'] : $subTotal = "0";
isset($_POST['payment']) ? $payment = $_POST['payment'] : $payment = "0";
isset($_POST['ddate']) ? $ddate = $_POST['ddate'] : $ddate = "none";
isset($_POST['newstatus']) ? $type = $_POST['newstatus'] : $type = "none";
isset($_POST['po_no']) ? $po_no = $_POST['po_no'] : $po_no = "none";
isset($_POST['ed']) ? $ed = $_POST['ed'] : $ed = "0";
isset($_POST['st']) ? $st = $_POST['st'] : $st = "0";
isset($_POST['tp']) ? $transportation = $_POST['tp'] : $transportation = "0";
isset($_POST['basictotal']) ? $basictotal = $_POST['basictotal'] : $basictotal = "0";
isset($_POST['totalAftertax']) ? $totalAfter = $_POST['totalAftertax'] : $totalAfter = "0";
isset($_POST['itotal']) ? $itotal = $_POST['itotal'] : $itotal = "0";
isset($_POST['gt']) ? $gt = $_POST['gt'] : $gt = "0";
isset($_POST['gt1']) ? $gt1 = $_POST['gt1'] : $gt1 = "0";
isset($_POST['refno']) ? $refno = $_POST['refno'] : $refno = "none";
isset($_POST['refdate']) ? $refdate = $_POST['refdate'] : $refdate = "none";
isset($_POST['vendor_name']) ? $ven_compname = $_POST['vendor_name'] : $ven_compname = "none";
isset($_POST['ven_add']) ? $ven_add = $_POST['ven_add'] : $ven_add = "none";
isset($_POST['details']) ? $details = $_POST['details'] : $details = "none";
isset($_POST['ven_contactperson']) ? $ven_contactperson = $_POST['ven_contactperson'] : $ven_contactperson = "none";
isset($_POST['itemCode']) ? $itemCode = $_POST['itemCode'] : $itemCode = "none";
isset($_POST['folder']) ? $folder = $_POST['folder'] : $folder = "none";
isset($_POST['units']) ? $units = $_POST['units'] : $units = "none";
isset($_POST['itemQty']) ? $itemQty = $_POST['itemQty'] : $itemQty = "0";
isset($_POST['subject']) ? $subject = $_POST['subject'] : $subject = "0";
isset($_POST['contactname']) ? $contactname = $_POST['contactname'] : $contactname = "0";
isset($_POST['mobilno']) ? $mobileno = $_POST['mobilno'] : $mobileno = "0";
isset($_POST['inclusive']) ? $inclusive = $_POST['inclusive'] : $inclusive = "none";
isset($_POST['tpwords']) ? $tpwords = $_POST['tpwords'] : $tpwords = "none";
isset($_POST['po_year']) ? $po_year = $_POST['po_year'] : $po_year = "none";
$i=0;
isset($_POST['itemQtyd']) ? $itemQtyd = $_POST['itemQtyd'] : $itemQtyd = "none";
isset($product_names[0]) ? $product_names1 = $product_names[0] : $product_names1 = "0";
isset($product_names[1]) ? $product_names2 = $product_names[1] : $product_names2 = "0";
date_default_timezone_set('Asia/Calcutta'); 
$time = date('Y-m-d H:i:s');
$ip = getenv('REMOTE_ADDR');
$userAgent = getenv('HTTP_USER_AGENT');
$referrer = getenv('HTTP_REFERER');
$query = getenv('QUERY_STRING');
if($type == 'standard')
{
foreach($_POST['itemDesc'] as $row=>$itemDesc)
{
$product_name=$itemDesc;
$itemQty=$_POST['itemQty'][$row];
$units=$_POST['units'][$row];
isset($_POST['po_id'][$row]) ? $po_id = $_POST['po_id'][$row] : $po_id = "none";
$rates=$_POST['itemPrice'][$row];
$itemVat=$_POST['itemVat'][$row];
$itemLineTotal=$_POST['itemLineTotal'][$row];
$po_date=$_POST['po_date'];
$ddate=$_POST['ddate'];
$po_no=$_POST['po_no'];
$project_name=$_POST['project_name'];
$cat_name=$_POST['cat_name'][$row];
$sub_catname=$_POST['sub_catname'][$row];
$brand=$_POST['brand'][$row];
$product_code=$_POST['itemCode'][$row];
}
//enter rows into database
foreach($_POST['itemDesc'] as $row=>$itemDesc)
{
$itemDesc=mysql_real_escape_string($itemDesc);
$itemQty=mysql_real_escape_string($_POST['itemQty'][$row]);
$units=mysql_real_escape_string($_POST['units'][$row]);
isset($_POST['po_id'][$row]) ? $po_id = $_POST['po_id'][$row] : $po_id = "none";
$po_no=mysql_real_escape_string($_POST['po_no']);
$itemPrice=mysql_real_escape_string($_POST['itemPrice'][$row]);
$itemLineTotal=mysql_real_escape_string($_POST['itemLineTotal'][$row]);
$project_name=mysql_real_escape_string($_POST['project_name']);
$po_date=mysql_real_escape_string($_POST['po_date']);
$cat_name=mysql_real_escape_string($_POST['cat_name'][$row]);
$sub_catname=mysql_real_escape_string($_POST['sub_catname'][$row]);
$po_date=mysql_real_escape_string($_POST['po_date']);
$brand=mysql_real_escape_string($_POST['brand'][$row]);
$product_code=mysql_real_escape_string($_POST['itemCode'][$row]);
	
if($inclusive == '0')
{
	$itemVat = '0';
}
else
{
$itemVat=mysql_real_escape_string($_POST['itemVat'][$row]);
}
$itemDesc = trim("$itemDesc");
if($po_id == 'none')
{
if( $itemDesc != '')
{
$sql = "INSERT INTO purchaseorder_tbl(product_name,product_code,Po_no,product_qty,ratesperproduct,Amount,vat,po_date,cat_name,sub_catname,units,status,stock_received,project_name,block,po_year) VALUES ('$itemDesc','$product_code',$po_no,$itemQty,$itemPrice,'$itemLineTotal','$itemVat','$po_date','$cat_name','$sub_catname','$units','open','0','$project_name','$blockname','$po_year')";
echo $sql;
$result = mysql_query($sql,$conn);
$sql3 = "update productlist set Product_price='$itemPrice' where pro_name='$itemDesc' and category_name='$cat_name' and sub_catname='$sub_catname'";
echo $sql3;
$result3 = mysql_query($sql3,$conn);
$sql4 = "INSERT INTO stocklist(pro_name,product_code,cat_name,sub_catname,price,brand,project_name,block,status,po_no,stock,stock_balance,stk_received,istatus) VALUES ('$itemDesc','$product_code','$cat_name','$sub_catname','$itemPrice','$brand','$project_name','$blockname','invoiceraised',$po_no,$itemQty,$itemQty,'0','intransit' )";
$result4 = mysql_query($sql4,$conn);
echo $sql4;
$query = "select * from requirementtable where product_name='$itemDesc' and cat_name='$cat_name' and sub_catname='$sub_catname'";
echo $query;
$query_run = mysql_query($query,$conn); 	
while ($req_row = mysql_fetch_array($query_run)) 
{
$req_stock=mysql_real_escape_string($req_row['itemsrequired']);
$product_name=mysql_real_escape_string($req_row['product_name']);
echo $req_stock."+++++". $itemQty;
if($req_stock <= $itemQty)
{
$sql_req = "update requirementtable SET status='invoiceraised' where product_name='$product_name' and cat_name='$cat_name' and sub_catname='$sub_catname'";
echo $sql_req;
$result_req = mysql_query($sql_req,$conn);	
}
else
{	    
$sql_req = "update requirementtable SET status='less' where product_name='$product_name' and cat_name='$cat_name' and sub_catname='$sub_catname'";
		echo $sql_req;
		$result_req = mysql_query($sql_req,$conn);	
}
}
}
}
else
{
if( $itemDesc != '')
{
$sql = "update purchaseorder_tbl set product_name='$itemDesc',Po_no='$po_no',product_code='$product_code',product_qty='$itemQty',ratesperproduct='$itemPrice',Amount='$itemLineTotal',vat='$itemVat',po_date='$po_date',cat_name='$cat_name',sub_catname='$sub_catname',units='$units',status='open',project_name='$project_name',block='$blockname' , po_year='$po_year' WHERE Po_no ='$po_no' and po_id=$po_id and  po_year='$po_year'";

echo $sql."Updated";
$result = mysql_query($sql,$conn);
$sql3 = "update productlist set Product_price='$itemPrice' where pro_name='$itemDesc' and category_name='$cat_name' and sub_catname='$sub_catname'";
$result3 = mysql_query($sql3,$conn);	
$sql4 = "update  stocklist set pro_name='$itemDesc',product_code='$product_code',cat_name='$cat_name',sub_catname='$sub_catname',price='$itemPrice',brand='$brand',project_name='$project_name',block='$blockname',status='invoiceraised',po_no='$po_no',stock='$itemQty',stock_balance='$itemQty',istatus='intransit',po_year='$po_year' where WHERE po_year='$po_year' and po_no='$po_no' and pro_name='$itemDesc' ";
$result4 = mysql_query($sql4,$conn);
$query = "select * from requirementtable where product_name='$itemDesc' and cat_name='$cat_name' and sub_catname='$sub_catname'";
$query_run = mysql_query($query,$conn); 	
while ($req_row = mysql_fetch_array($query_run)) 
{
$req_stock=mysql_real_escape_string($req_row['itemsrequired']);
$product_name=mysql_real_escape_string($req_row['product_name']);
echo $req_stock."+++++". $itemQty;
if($req_stock <= $itemQty)
	{
$sql_req = "update requirementtable SET status='invoiceraised' where product_name='$product_name' and cat_name='$cat_name' and sub_catname='$sub_catname'";
echo $sql_req;
$result_req = mysql_query($sql_req,$conn);	
	}
	else
	{	    
$sql_req = "update requirementtable SET status='less' where product_name='$product_name' and cat_name='$cat_name' and sub_catname='$sub_catname'";
		echo $sql_req;
		$result_req = mysql_query($sql_req,$conn);	
}
}
}
}
}
if (!$result)
{
die('Error1: ' . mysql_error());
}
/** vendor add **/
$sql_ven = "select * from vendor_tbl where ven_compname	= '$ven_compname'";
$result_ven=mysql_query($sql_ven,$conn);
if($row_ven = mysql_fetch_array($result_ven))
{
$sql_ven2 = "update vendor_tbl set ven_compname='$ven_compname',ven_add1='$ven_add',ven_contactperson='$ven_contactperson' where ven_compname='$ven_compname'";
$result_ven2 = mysql_query($sql_ven2,$conn);
echo "Updated";	
}
else
{
$sql_ven1 = "INSERT INTO vendor_tbl (ven_compname,ven_add1,ven_contactperson) VALUES ('$ven_compname','$ven_add','$ven_contactperson')";
$result_ven1 = mysql_query($sql_ven1,$conn);
echo "Inserted";
}
/** vendor add end**/
/** Folder add **/
$sql_f = "select * from folder where folder_name = '".$folder."'";
echo $sql_f;
$result_f=mysql_query($sql_f,$conn);
if($row_f = mysql_fetch_array($result_f))
{
echo "already Inserted";	
}
else{
$sql_f1 = "INSERT INTO folder (folder_name) VALUES ('$folder')";
echo $sql_f1;
$result_f1 = mysql_query($sql_f1,$conn);
echo "Inserted";
}
/** Folder add end**/
$sql_ps = "select * from project_details where project_name = '".$project_name."'";
$result_ps=mysql_query($sql_ps,$conn);
if($row_ps = mysql_fetch_array($result_ps))
{
 $projectname = $row_ps["projectshort"];	
}
$companyname = explode(" ", $ven_compname);
$companyname1=$companyname[0];
$x = strlen($companyname[0]);
$y=35-(($x+3)+1+1);
$a = explode(" ", $product_names1);
$b = explode(" ", $product_names2);  
isset($a[0]) ? $a1 = $a[0] : $a1 = "";
isset($a[1]) ? $a2 = $a[1] : $a2 = "";
isset($a[2]) ? $a3 = $a[2] : $a3 = "";
isset($a[3]) ? $a4 = $a[3] : $a4 = "";
isset($a[4]) ? $a5 = $a[4] : $a5 = "";	
isset($b[0]) ? $b1 = $b[0] : $b1 = "";
isset($b[1]) ? $b2 = $b[1] : $b2 = "";
isset($b[2]) ? $b3 = $b[2] : $b3 = "";
isset($b[3]) ? $b4 = $b[3] : $b4 = "";
isset($b[4]) ? $b5 = $b[4] : $b5 = "";	 
$a1_c=strlen($a1);
$a2_c=strlen($a2);
$a3_c=strlen($a3);
$a4_c=strlen($a4);
$a5_c=strlen($a5);
$b1_c=strlen($b1);
$b2_c=strlen($b2);
$b3_c=strlen($b3);
$b4_c=strlen($b4);
$b5_c=strlen($b5);
if($product_names2 == "0")
{
if($a1_c+$a2_c+$a3_c+$a4_c <= $y)
{	
$productname1 = $a1." ".$a2." ".$a3." ".$a4;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}
else if($a1_c+$a2_c+$a3_c+$a4_c <= $y)
{
$productname1 = $a1." ".$a2." ".$a3;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}
else if($a1_c+$a2_c <= $y)
{
$productname1 = $a1." ".$a2;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}
else if($a1_c <= $y)
{
$productname1 = $a1;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}
}
if($product_names2 != "0")
{
 if($a1_c+$a2_c+$a3_c+$a4_c+$b1_c+$b2_c+$b3_c+$b4_c <= $y )
 {
 $productname1 = $a1." ".$a2." ".$a3." ".$a4.", ".$b1." ".$b2." ".$b3." ".$b4;
 $desc = $projectname.' '.$companyname1.'-'.$productname1;
 }	
if($a1_c+$a2_c+$a3_c+$b1_c+$b2_c+$b3_c+$b4_c <= $y and ($a1_c+$a2_c+$b1_c+$b2_c+$b3_c+$b4_c < $a1_c+$a2_c+$a3_c+$a4_c+$b1_c+$b2_c) and $a1_c+$a2_c+$b1_c+$b2_c+$b3_c+$b4_c <=$y)
{
$productname1 = $a1." ".$a2." ,".$b1." ".$b2." ".$b3." ".$b4;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}
if($a1_c+$a2_c+$a3_c+$a4_c+$b1_c+$b2_c <= $y and ($a1_c+$a2_c+$b1_c+$b2_c+$b3_c+$b4_c > $a1_c+$a2_c+$a3_c+$a4_c+$b1_c+$b2_c) and $a1_c+$a2_c+$a3_c+$a4_c+$b1_c+$b2_c <= $y)
{
$productname1 = $a1." ".$a2." ".$a3." ".$a4." , ".$b1." ".$b2." ".$b3." ".$b4;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}
if($a1_c+$a2_c+$a3_c+$b1_c+$b2_c+$b3_c <= $y and ($a1_c+$a2_c+$b1_c+$b2_c+$b3_c+$b4_c > $a1_c+$a2_c+$a3_c+$a4_c+$b1_c+$b2_c)<=$y)
{
$productname1 = $a1." ".$a2." ".$a3.", ".$a4." ".$b1." ".$b2." ".$b3." ".$b4;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}
if($a1_c+$a2_c+$a3_c+$b1_c+$b2_c+$b3_c <= $y)
{
$productname1 = $a1." ".$a2." ".$a3.", ".$b1." ".$b2." ".$b3;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}
 else if(($a1_c+$a2_c+$b1_c+$b2_c) > $y and ($a1_c+$b1_c+$b2_c+$b3_c < $a1_c+$a2_c+$a3_c+$b1_c) and $a1_c+$b1_c+$b2_c+$b3_c <= $y)
{
$productname1 = $a1.", ".$b1." ".$b2." ".$b3;
$desc = $projectname.' '.$companyname1.'-'.$productname1;									
}
else if(($a1_c+$a2_c+$b1_c+$b2_c) > $y and ($a1_c+$b1_c+$b2_c+$b3_c > $a1_c+$a2_c+$a3_c+$b1_c) and $a1_c+$a2_c+$a3_c+$b1_c <= $y)
{
$productname1 = $a1." ".$a2." ".$a3.", ".$b1;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}								
else if(($a1_c+$a2_c+$b1_c+$b2_c) <= $y)
{
$productname1 = $a1." ".$a2.", ".$b1." ".$b2;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}
else if($a1_c+$b1_c < $y  and $a1_c+$b1_c+$b2_c <= $y and $a1_c+$b1_c+$b2_c < $a1_c+$a2_c+ $b1_c)
{
$productname1 = $a1.", ".$b1." ".$b2;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}
else if($a1_c+$b1_c < $y  and $a1_c+$a2_c+$b1_c <= $y and $a1_c+$b1_c+$b2_c > $a1_c+$a2_c+ $b1_c)
{
$productname1 = $a1." ".$a2 .", ".$b1;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}
else if($a1 < $y and $a1_c+$a2_c < $a1_c+$b1_c and $a1_c+$a2_c <= $y)
{
$productname1 = $a1." ".$a2;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}
else if($a1_c < $y and $a1_c+a2_c > a1_c+b1_c and a1_c+b1_c <= $y )
{
$productname1 = $a1.", ".$b1;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}
else if( $a1_c >= $y)
{
$productname1 = $a1;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}								
else
{
echo "Error";
}
}
$desc = mysql_real_escape_string($desc);
echo "SUM of y".$y."<br>";
echo "A total= ".$a1_c+$a2_c+$a3_c+$a4_c."<br>";
echo "b total = ".$b1_c+$b2_c+$b3_c+$b4_c."<br>";
$sql1 = "UPDATE purchase_master SET po_date='$po_date',project_id='$project_id',ven_id='$ven_compname',subtotal='$subTotal',ddate='$ddate',vat='$tax',status='open',project_name='$project_name',Block='$blockname',description='$desc',type='$type',refno='$refno',refdate='$refdate',folder='$folder',payment='$payment',subject='$subject',contactname='$contactname',mobileno='$mobileno', inclusive='$inclusive', tpwords='$tpwords', po_year='$po_year'where po_no='$po_no' and po_year='$po_year'";
echo $sql1;
$result1 = mysql_query($sql1,$conn);
$msg = "IP: " . $ip . " TIME: " . $time . " User: " .$_SESSION['st_username'].  " USERAGENT: " .$userAgent. "Action : PO-NO".$po_no."Inserted";
writeToLogFile($msg);
}
else if($type == 'upvc')
{
	foreach($_POST['itemCode'] as $row=>$itemCode)
{
$itemCode=$itemCode;
$itemBrand=$_POST['itemBrand'][$row];
$itemWidth=$_POST['itemWidth'][$row];
$itemHeight=$_POST['itemHeight'][$row];
$itemQty=$_POST['itemQty'][$row];
$itemDesc=$_POST['itemDesc'][$row];
$itemBasic_m=$_POST['itemBasic_m'][$row];
$itemTotal_m=$_POST['itemTotal_m'][$row];
$itemBasic_i=$_POST['itemBasic_i'][$row];
$itemTotal_i=$_POST['itemTotal_i'][$row];
$itemUnit=$_POST['itemUnit'][$row];
$ed=$_POST['ed'];
$vat=$_POST['vat'];
$blockname=$_POST['blockname'];
$transportation = $_POST['tp'];
$st=$_POST['st'];
$po_no=$_POST['po_no'];
$po_date=$_POST['po_date'];
$project_name=$_POST['project_name'];
isset($_POST['po_id'][$row]) ? $po_id = $_POST['po_id'][$row] : $po_id = "none";
$itemLineTotal=$_POST['itemLineTotal'][$row];
}
//enter rows into database
foreach($_POST['itemCode'] as $row=>$itemCode)
{
$itemCode=mysql_real_escape_string($itemCode);
$itemWidth=mysql_real_escape_string($_POST['itemWidth'][$row]);
$itemHeight=mysql_real_escape_string($_POST['itemHeight'][$row]);
$itemQty=mysql_real_escape_string($_POST['itemQty'][$row]);
$itemDesc=mysql_real_escape_string($_POST['itemDesc'][$row]);
$itemBasic_m=mysql_real_escape_string($_POST['itemBasic_m'][$row]);
$itemTotal_m=mysql_real_escape_string($_POST['itemTotal_m'][$row]);
$itemBasic_i=mysql_real_escape_string($_POST['itemBasic_i'][$row]);
$itemTotal_i=mysql_real_escape_string($_POST['itemTotal_i'][$row]);
$itemLineTotal=mysql_real_escape_string($_POST['itemLineTotal'][$row]);
$project_name=mysql_real_escape_string($_POST['project_name']);
$po_date=mysql_real_escape_string($_POST['po_date']);
$ed=$_POST['ed'];
$vat=$_POST['vat'];
$blockname=$_POST['blockname'];
$transportation = $_POST['tp'];
$st=$_POST['st'];
$itemBrand=mysql_real_escape_string($_POST['itemBrand'][$row]);
$itemUnit=mysql_real_escape_string($_POST['itemUnit'][$row]);
$po_no=mysql_real_escape_string($_POST['po_no']);
isset($_POST['po_id'][$row]) ? $po_id = $_POST['po_id'][$row] : $po_id = "none";
$itemDesc = trim("$itemDesc");
if($po_id == 'none')
{
if( $itemCode != '')
{
$sql = "INSERT INTO purchaseorder_tbl(product_code,product_name,Po_no,product_qty,m_basic,m_total,i_basic,i_total,ed,vat,st,transportation,units,Amount,status,pro_height,pro_width,stock_received,project_name,block,po_year) VALUES ('$itemCode','$itemDesc',$po_no,$itemQty,$itemBasic_m,$itemTotal_m,$itemBasic_i,$itemTotal_i,$ed,$vat,$st,$transportation,'$itemUnit','$itemLineTotal','open','$itemHeight','$itemWidth','0','$project_name','$blockname','$po_year')";
echo $sql;
$result = mysql_query($sql,$conn);
$sql3 = "update productlist set m_basic ='$itemBasic_i',i_basic='$itemTotal_i' where product_code='$itemCode'";
echo $sql3;
$result3 = mysql_query($sql3,$conn);
$sql4 = "INSERT INTO stocklist(pro_name,product_code,itemHeight,itemWidth,itemBasic_m,item_totali,brand,project_name,block,status,po_no,stock,stock_balance,stk_received,istatus) VALUES('$itemDesc','$itemCode','$itemHeight','$itemWidth','$itemBasic_m','$itemBasic_i','$itemBrand','$project_name','$blockname','invoiceraised',$po_no,$itemQty,$itemQty,'0','intransit')";
$result4 = mysql_query($sql4,$conn);
echo $sql4;
$query = "select * from requirementtable where product_code='$itemCode' and project_name='$project_name'";
echo $query;
$query_run = mysql_query($query,$conn); 	
while ($req_row = mysql_fetch_array($query_run)) 
{
$req_stock=mysql_real_escape_string($req_row['itemsrequired']);
$product_name=mysql_real_escape_string($req_row['product_name']);
echo $req_stock."+++++". $itemQty;
if($req_stock <= $itemQty)
{
$sql_req = "update requirementtable SET status='invoiceraised' where product_code='$itemCode' and project_name='$project_name' and Block='$blockname'";
echo $sql_req;
$result_req = mysql_query($sql_req,$conn);
}
else
{	    
$sql_req = "update requirementtable SET status='less' where product_code='$itemCode' and project_name='$project_name' and Block='$blockname'";
echo $sql_req;
$result_req = mysql_query($sql_req,$conn);	
}
}
}
}
else
{
if( $itemCode != '')
{
$sql ="UPDATE purchaseorder_tbl SET product_code='$itemCode',product_name='$itemDesc',product_qty='$itemQty',m_basic='$itemBasic_m',m_total='$itemTotal_m',i_basic='$itemBasic_i',i_total='$itemTotal_i',ed='$ed',vat='$vat',st='$st',transportation='$transportation',units='$itemUnit',Amount='$itemLineTotal',status='open',pro_height='$itemHeight',pro_width='$itemWidth',project_name='$project_name',block='$blockname' , po_year='$po_year' WHERE Po_no ='$po_no' and po_id=$po_id and  po_year='$po_year'";
echo $sql;
$result = mysql_query($sql,$conn);
$sql3 = "update productlist set m_basic ='$itemBasic_i',i_basic='$itemTotal_i' where product_code='$itemCode'";
echo $sql3;
$result3 = mysql_query($sql3,$conn);
$sql4 = "UPDATE stocklist SET pro_name='$itemDesc',product_code='$itemCode',itemHeight='$itemHeight',itemWidth='$itemWidth',itemBasic_m='$itemBasic_m',item_totali,brand='$itemBrand',project_name='$project_name',block='$blockname',status='invoiceraised',stock='$itemQty',stock_balance='$itemQty',stk_received='0', istatus='intransit', po_year='$po_year' where WHERE po_year='$po_year' and po_no='$po_no' and product_code='$itemCode'";
$result4 = mysql_query($sql4,$conn);
echo $sql4;
$query = "select * from requirementtable where product_code='$itemCode' and project_name='$project_name'";
echo $query;
$query_run = mysql_query($query,$conn); 	
while ($req_row = mysql_fetch_array($query_run)) 
{
$req_stock=mysql_real_escape_string($req_row['itemsrequired']);
$product_name=mysql_real_escape_string($req_row['product_name']);
echo $req_stock."+++++". $itemQty;
if($req_stock <= $itemQty)
{
$sql_req = "update requirementtable SET status='invoiceraised' where product_code='$itemCode' and project_name='$project_name' and Block='$blockname'";
echo $sql_req;
$result_req = mysql_query($sql_req,$conn);	
}
else
{	    
$sql_req = "update requirementtable SET status='less' where product_code='$itemCode' and project_name='$project_name' and Block='$blockname'";
echo $sql_req;
$result_req = mysql_query($sql_req,$conn);	
}
}
}	
}
}
if (!$result)
{
die('Error1: ' . mysql_error());
}
/** vendor add **/
$sql_ven = "select * from vendor_tbl where ven_compname	= '$ven_compname'";
$result_ven=mysql_query($sql_ven,$conn);
if($row_ven = mysql_fetch_array($result_ven))
{
$sql_ven2 = "update vendor_tbl set ven_compname='$ven_compname',ven_add1='$ven_add',ven_contactperson='$ven_contactperson' where ven_compname='$ven_compname'";
$result_ven2 = mysql_query($sql_ven2,$conn);
echo "Updated";	
}
else
{
$sql_ven1 = "INSERT INTO vendor_tbl (ven_compname,ven_add1,ven_contactperson) VALUES ('$ven_compname','$ven_add','$ven_contactperson')";
$result_ven1 = mysql_query($sql_ven1,$conn);
echo "Inserted";
}
/** vendor add end**/
/** Folder add **/
$sql_f = "select * from folder where folder_name = '".$folder."'";
echo $sql_f;
$result_f=mysql_query($sql_f,$conn);
if($row_f = mysql_fetch_array($result_f))
{
echo "already Inserted";	
}
else{
$sql_f1 = "INSERT INTO folder (folder_name) VALUES ('$folder')";
echo $sql_f1;
$result_f1 = mysql_query($sql_f1,$conn);
echo "Inserted";
}
/** Folder add end**/
$sql_ps = "select * from project_details where project_name = '".$project_name."'";
$result_ps=mysql_query($sql_ps,$conn);
if($row_ps = mysql_fetch_array($result_ps))
{
 $projectname = $row_ps["projectshort"];	
}
$companyname = explode(" ", $ven_compname);
$companyname1=$companyname[0];
$x = strlen($companyname[0]);
$y=35-(($x+3)+1+1);
$a = explode(" ", $product_names1);
$b = explode(" ", $product_names2);  
isset($a[0]) ? $a1 = $a[0] : $a1 = "";
isset($a[1]) ? $a2 = $a[1] : $a2 = "";
isset($a[2]) ? $a3 = $a[2] : $a3 = "";
isset($a[3]) ? $a4 = $a[3] : $a4 = "";
isset($a[4]) ? $a5 = $a[4] : $a5 = "";	
isset($b[0]) ? $b1 = $b[0] : $b1 = "";
isset($b[1]) ? $b2 = $b[1] : $b2 = "";
isset($b[2]) ? $b3 = $b[2] : $b3 = "";
isset($b[3]) ? $b4 = $b[3] : $b4 = "";
isset($b[4]) ? $b5 = $b[4] : $b5 = "";	
$a1_c=strlen($a1);
$a2_c=strlen($a2);
$a3_c=strlen($a3);
$a4_c=strlen($a4);
$a5_c=strlen($a5);
$b1_c=strlen($b1);
$b2_c=strlen($b2);
$b3_c=strlen($b3);
$b4_c=strlen($b4);
$b5_c=strlen($b5);
if($product_names2 == "0")
{
if($a1_c+$a2_c+$a3_c+$a4_c <= $y)
{	
$productname1 = $a1." ".$a2." ".$a3." ".$a4;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}
else if($a1_c+$a2_c+$a3_c+$a4_c <= $y)
{
$productname1 = $a1." ".$a2." ".$a3;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}
else if($a1_c+$a2_c <= $y)
{
$productname1 = $a1." ".$a2;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}
else if($a1_c <= $y)
{
$productname1 = $a1;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}
}
if($product_names2 != "0")
{
if($a1_c+$a2_c+$a3_c+$a4_c+$b1_c+$b2_c+$b3_c+$b4_c <= $y )
{								
$productname1 = $a1." ".$a2." ".$a3." ".$a4.", ".$b1." ".$b2." ".$b3." ".$b4;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}
if($a1_c+$a2_c+$a3_c+$b1_c+$b2_c+$b3_c+$b4_c <= $y and ($a1_c+$a2_c+$b1_c+$b2_c+$b3_c+$b4_c < $a1_c+$a2_c+$a3_c+$a4_c+$b1_c+$b2_c) and $a1_c+$a2_c+$b1_c+$b2_c+$b3_c+$b4_c <=$y)
{									
$productname1 = $a1." ".$a2." ,".$b1." ".$b2." ".$b3." ".$b4;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}
if($a1_c+$a2_c+$a3_c+$a4_c+$b1_c+$b2_c <= $y and ($a1_c+$a2_c+$b1_c+$b2_c+$b3_c+$b4_c > $a1_c+$a2_c+$a3_c+$a4_c+$b1_c+$b2_c) and $a1_c+$a2_c+$a3_c+$a4_c+$b1_c+$b2_c <= $y)
{									
$productname1 = $a1." ".$a2." ".$a3." ".$a4." , ".$b1." ".$b2." ".$b3." ".$b4;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}
if($a1_c+$a2_c+$a3_c+$b1_c+$b2_c+$b3_c <= $y and ($a1_c+$a2_c+$b1_c+$b2_c+$b3_c+$b4_c > $a1_c+$a2_c+$a3_c+$a4_c+$b1_c+$b2_c)<=$y)
{									
$productname1 = $a1." ".$a2." ".$a3.", ".$a4." ".$b1." ".$b2." ".$b3." ".$b4;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}
if($a1_c+$a2_c+$a3_c+$b1_c+$b2_c+$b3_c <= $y)
{									
$productname1 = $a1." ".$a2." ".$a3.", ".$b1." ".$b2." ".$b3;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}
else if(($a1_c+$a2_c+$b1_c+$b2_c) > $y and ($a1_c+$b1_c+$b2_c+$b3_c < $a1_c+$a2_c+$a3_c+$b1_c) and $a1_c+$b1_c+$b2_c+$b3_c <= $y)
{
$productname1 = $a1.", ".$b1." ".$b2." ".$b3;
$desc = $projectname.' '.$companyname1.'-'.$productname1;									
}
else if(($a1_c+$a2_c+$b1_c+$b2_c) > $y and ($a1_c+$b1_c+$b2_c+$b3_c > $a1_c+$a2_c+$a3_c+$b1_c) and $a1_c+$a2_c+$a3_c+$b1_c <= $y)
{
$productname1 = $a1." ".$a2." ".$a3.", ".$b1;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}								
else if(($a1_c+$a2_c+$b1_c+$b2_c) <= $y)
{
$productname1 = $a1." ".$a2.", ".$b1." ".$b2;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}
else if($a1_c+$b1_c < $y  and $a1_c+$b1_c+$b2_c <= $y and $a1_c+$b1_c+$b2_c < $a1_c+$a2_c+ $b1_c)
{
$productname1 = $a1.", ".$b1." ".$b2;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}
else if($a1_c+$b1_c < $y  and $a1_c+$a2_c+$b1_c <= $y and $a1_c+$b1_c+$b2_c > $a1_c+$a2_c+ $b1_c)
{
$productname1 = $a1." ".$a2 .", ".$b1;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}
else if($a1 < $y and $a1_c+$a2_c < $a1_c+$b1_c and $a1_c+$a2_c <= $y)
{
$productname1 = $a1." ".$a2;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}
else if($a1_c < $y and $a1_c+a2_c > a1_c+b1_c and a1_c+b1_c <= $y )
{
$productname1 = $a1.", ".$b1;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}
else if( $a1_c >= $y)
{
$productname1 = $a1;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}								
else
{
echo "Error";
}
}
$desc = mysql_real_escape_string($desc);
$sql1 = "UPDATE purchase_master SET po_date='$po_date',project_id='$project_id',ven_id='$ven_compname',subtotal='$totalAfter',ddate='$ddate',ed='$ed',vat='$tax',st='$st',transportation='$transportation',status='open',project_name='$project_name',Block='$blockname',description='$desc',gt='$gt',gt1='$gt1',type='$type',refno='$refno',refdate='$refdate',stotal='$subTotal',basictotal='$basictotal',itotal='$itotal',folder='$folder',payment='$payment',subject='$subject',contactname='$contactname',mobileno='$mobileno', po_year='$po_year' WHERE po_no='$po_no' and po_year='$po_year' ";
$result1 = mysql_query($sql1,$conn);
$msg = "IP: " . $ip . " TIME: " . $time . " User: " .$_SESSION['st_username'].  " USERAGENT: " .$userAgent. "Action : PO-NO".$po_no."Inserted";
writeToLogFile($msg);
}
else if($type == 'door')
{	
foreach($_POST['itemDesc'] as $row=>$itemDesc)
{
$product_name=$itemDesc;
$itemQty=$_POST['itemQty'][$row];
$itemQtyd=$_POST['itemQtyd'][$row];
$units=$_POST['units'][$row];
$rates=$_POST['itemPrice'][$row];
$itemLineTotal=$_POST['itemLineTotal'][$row];
$po_date=$_POST['po_date'];
$ddate=$_POST['ddate'];
$po_no=$_POST['po_no'];
$project_name=$_POST['project_name'];
$cat_name=$_POST['cat_name'][$row];
$sub_catname=$_POST['sub_catname'][$row];
$size=$_POST['size'][$row];
$brand=$_POST['brand'][$row];
$product_code=$_POST['itemCode'][$row];
isset($_POST['po_id'][$row]) ? $po_id = $_POST['po_id'][$row] : $po_id = "none";
}
//enter rows into database
foreach($_POST['itemDesc'] as $row=>$itemDesc)
{
$itemDesc=mysql_real_escape_string($itemDesc);
$itemQty=mysql_real_escape_string($_POST['itemQty'][$row]);
$itemQtyd=mysql_real_escape_string($_POST['itemQtyd'][$row]);
$units=mysql_real_escape_string($_POST['units'][$row]);
$po_no=mysql_real_escape_string($_POST['po_no']);
$itemPrice=mysql_real_escape_string($_POST['itemPrice'][$row]);
$itemLineTotal=mysql_real_escape_string($_POST['itemLineTotal'][$row]);
$project_name=mysql_real_escape_string($_POST['project_name']);
$po_date=mysql_real_escape_string($_POST['po_date']);
$cat_name=mysql_real_escape_string($_POST['cat_name'][$row]);
$sub_catname=mysql_real_escape_string($_POST['sub_catname'][$row]);
$po_date=mysql_real_escape_string($_POST['po_date']);
$size=mysql_real_escape_string($_POST['size'][$row]);
$brand=mysql_real_escape_string($_POST['brand'][$row]);
$product_code=mysql_real_escape_string($_POST['itemCode'][$row]);
isset($_POST['po_id'][$row]) ? $po_id = $_POST['po_id'][$row] : $po_id = "none";
$itemDesc = trim("$itemDesc");
if($po_id == 'none')
{
if( $itemDesc != '')
{
$sql = "INSERT INTO purchaseorder_tbl(product_name,Po_no,product_qty,ratesperproduct,Amount,product_code,po_date,cat_name,sub_catname,units,status,product_cft,size,brand,stock_received,project_name,block,po_year) VALUES ('$itemDesc','$po_no','$itemQtyd','$itemPrice','$itemLineTotal','$product_code','$po_date','$cat_name','$sub_catname','CFT','open','$itemQty','$size','$brand','0','$project_name','$blockname','$po_year')";
echo $sql;
$result = mysql_query($sql,$conn);
$sql3 = "update productlist set Product_price='$itemPrice' where pro_name='$itemDesc' and category_name='$cat_name' and sub_catname='$sub_catname'";
echo $sql3;
$result3 = mysql_query($sql3,$conn);
$sql4 = "INSERT INTO stocklist(pro_name,product_code,cat_name,sub_catname,price,size,brand,project_name,block,status,po_no,stock,stock_balance,stk_received,istatus) VALUES ('$itemDesc','$product_code','$cat_name','$sub_catname','$itemPrice','$size','$brand','$project_name','$blockname','invoiceraised','$po_no',$itemQtyd,$itemQtyd,'0','intransit')";
$result4 = mysql_query($sql4,$conn);
echo $sql4;
$query = "select * from requirementtable where product_name='$itemDesc' and cat_name='$cat_name' and sub_catname='$sub_catname'";
echo $query;
$query_run = mysql_query($query,$conn); 	
while ($req_row = mysql_fetch_array($query_run)) 
{
$req_stock=mysql_real_escape_string($req_row['itemsrequired']);
$product_name=mysql_real_escape_string($req_row['product_name']);
echo $req_stock."+++++". $itemQty;
if($req_stock <= $itemQty)
{
$sql_req = "update requirementtable SET status='invoiceraised' where product_name='$product_name' and cat_name='$cat_name' and sub_catname='$sub_catname'";
echo $sql_req;
$result_req = mysql_query($sql_req,$conn);	
}
else
{	    
$sql_req = "update requirementtable SET status='less' where product_name='$product_name' and cat_name='$cat_name' and sub_catname='$sub_catname'";
echo $sql_req;
$result_req = mysql_query($sql_req,$conn);	
}
}
}
}
else
{
if( $itemDesc != '')
{
$sql = "UPDATE purchaseorder_tbl SET product_name='$itemDesc',product_qty='$itemQtyd',ratesperproduct='$itemPrice',Amount='$itemLineTotal',product_code='$product_code',po_date='$po_date',cat_name='$cat_name',sub_catname='$sub_catname',units='CFT',status='open',product_cft='$itemQty',size='$size',brand='$brand',project_name='$project_name',block='$blockname', po_year='$po_year' WHERE Po_no ='$po_no' and po_id=$po_id and  po_year='$po_year'";
echo $sql;
$result = mysql_query($sql,$conn);
$sql3 = "update productlist set Product_price='$itemPrice' where pro_name='$itemDesc' and category_name='$cat_name' and sub_catname='$sub_catname'";
echo $sql3;
$result3 = mysql_query($sql3,$conn);
$sql4 = "UPDATE  stocklist SET pro_name='$itemDesc',product_code='$product_code',cat_name='$cat_name',sub_catname='$sub_catname',price='$itemPrice',size='$size',brand='$brand',project_name='$project_name',block='$blockname',status='invoiceraised',stock='$itemQtyd',stock_balance='$itemQtyd',stk_received='0', istatus='intransit', po_year='$po_year' where WHERE po_year='$po_year' and po_no='$po_no' and product_code='$product_code'";
$result4 = mysql_query($sql4,$conn);
echo $sql4;
$query = "select * from requirementtable where product_name='$itemDesc' and cat_name='$cat_name' and sub_catname='$sub_catname'";
echo $query;
$query_run = mysql_query($query,$conn); 	
while ($req_row = mysql_fetch_array($query_run)) 
{
$req_stock=mysql_real_escape_string($req_row['itemsrequired']);
$product_name=mysql_real_escape_string($req_row['product_name']);
echo $req_stock."+++++". $itemQty;
if($req_stock <= $itemQty)
{
$sql_req = "update requirementtable SET status='invoiceraised' where product_name='$product_name' and cat_name='$cat_name' and sub_catname='$sub_catname'";
echo $sql_req;
$result_req = mysql_query($sql_req,$conn);	
}
else
{	    
$sql_req = "update requirementtable SET status='less' where product_name='$product_name' and cat_name='$cat_name' and sub_catname='$sub_catname'";
echo $sql_req;
$result_req = mysql_query($sql_req,$conn);	
}
}
}	
}
}
if (!$result)
{
die('Error1: ' . mysql_error());
}
/** vendor add **/
$sql_ven = "select * from vendor_tbl where ven_compname	= '$ven_compname'";
$result_ven=mysql_query($sql_ven,$conn);
if($row_ven = mysql_fetch_array($result_ven))
{
$sql_ven2 = "update vendor_tbl set ven_compname='$ven_compname',ven_add1='$ven_add',ven_contactperson='$ven_contactperson' where ven_compname='$ven_compname'";
$result_ven2 = mysql_query($sql_ven2,$conn);
echo "Updated";	
}
else
{
$sql_ven1 = "INSERT INTO vendor_tbl (ven_compname,ven_add1,ven_contactperson) VALUES ('$ven_compname','$ven_add','$ven_contactperson')";
$result_ven1 = mysql_query($sql_ven1,$conn);
echo "Inserted";
}
/** vendor add end**/
/** Folder add **/
$sql_f = "select * from folder where folder_name = '".$folder."'";
echo $sql_f;
$result_f=mysql_query($sql_f,$conn);
if($row_f = mysql_fetch_array($result_f))
{
echo "already Inserted";	
}
else{
$sql_f1 = "INSERT INTO folder (folder_name) VALUES ('$folder')";
echo $sql_f1;
$result_f1 = mysql_query($sql_f1,$conn);
echo "Inserted";
}
/** Folder add end**/
$sql_ps = "select * from project_details where project_name = '".$project_name."'";
$result_ps=mysql_query($sql_ps,$conn);
if($row_ps = mysql_fetch_array($result_ps))
{
 $projectname = $row_ps["projectshort"];	
}
$companyname = explode(" ", $ven_compname);
$companyname1=$companyname[0];
$x = strlen($companyname[0]);
$y=35-(($x+3)+1+1);
$a = explode(" ", $product_names1);
$b = explode(" ", $product_names2);
isset($a[0]) ? $a1 = $a[0] : $a1 = "";
isset($a[1]) ? $a2 = $a[1] : $a2 = "";
isset($a[2]) ? $a3 = $a[2] : $a3 = "";
isset($a[3]) ? $a4 = $a[3] : $a4 = "";
isset($a[4]) ? $a5 = $a[4] : $a5 = "";	
isset($b[0]) ? $b1 = $b[0] : $b1 = "";
isset($b[1]) ? $b2 = $b[1] : $b2 = "";
isset($b[2]) ? $b3 = $b[2] : $b3 = "";
isset($b[3]) ? $b4 = $b[3] : $b4 = "";
isset($b[4]) ? $b5 = $b[4] : $b5 = "";	
$a1_c=strlen($a1);
$a2_c=strlen($a2);
$a3_c=strlen($a3);
$a4_c=strlen($a4);
$a5_c=strlen($a5);
$b1_c=strlen($b1);
$b2_c=strlen($b2);
$b3_c=strlen($b3);
$b4_c=strlen($b4);
$b5_c=strlen($b5);
if($product_names2 == "0")
{
if($a1_c+$a2_c+$a3_c+$a4_c <= $y)
{
$productname1 = $a1." ".$a2." ".$a3." ".$a4;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}
else if($a1_c+$a2_c+$a3_c+$a4_c <= $y)
{
$productname1 = $a1." ".$a2." ".$a3;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}
else if($a1_c+$a2_c <= $y)
{
$productname1 = $a1." ".$a2;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}
else if($a1_c <= $y)
{
$productname1 = $a1;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}
}
if($product_names2 != "0")
{
if($a1_c+$a2_c+$a3_c+$a4_c+$b1_c+$b2_c+$b3_c+$b4_c <= $y )
{									
$productname1 = $a1." ".$a2." ".$a3." ".$a4.", ".$b1." ".$b2." ".$b3." ".$b4;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}	
if($a1_c+$a2_c+$a3_c+$b1_c+$b2_c+$b3_c+$b4_c <= $y and ($a1_c+$a2_c+$b1_c+$b2_c+$b3_c+$b4_c < $a1_c+$a2_c+$a3_c+$a4_c+$b1_c+$b2_c) and $a1_c+$a2_c+$b1_c+$b2_c+$b3_c+$b4_c <=$y)
{										
$productname1 = $a1." ".$a2." ,".$b1." ".$b2." ".$b3." ".$b4;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}
if($a1_c+$a2_c+$a3_c+$a4_c+$b1_c+$b2_c <= $y and ($a1_c+$a2_c+$b1_c+$b2_c+$b3_c+$b4_c > $a1_c+$a2_c+$a3_c+$a4_c+$b1_c+$b2_c) and $a1_c+$a2_c+$a3_c+$a4_c+$b1_c+$b2_c <= $y)
{										
$productname1 = $a1." ".$a2." ".$a3." ".$a4." , ".$b1." ".$b2." ".$b3." ".$b4;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}
if($a1_c+$a2_c+$a3_c+$b1_c+$b2_c+$b3_c <= $y and ($a1_c+$a2_c+$b1_c+$b2_c+$b3_c+$b4_c > $a1_c+$a2_c+$a3_c+$a4_c+$b1_c+$b2_c)<=$y)
{										
$productname1 = $a1." ".$a2." ".$a3.", ".$a4." ".$b1." ".$b2." ".$b3." ".$b4;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}	
if($a1_c+$a2_c+$a3_c+$b1_c+$b2_c+$b3_c <= $y)
{										
$productname1 = $a1." ".$a2." ".$a3.", ".$b1." ".$b2." ".$b3;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}									
else if(($a1_c+$a2_c+$b1_c+$b2_c) > $y and ($a1_c+$b1_c+$b2_c+$b3_c < $a1_c+$a2_c+$a3_c+$b1_c) and $a1_c+$b1_c+$b2_c+$b3_c <= $y)
{
$productname1 = $a1.", ".$b1." ".$b2." ".$b3;
$desc = $projectname.' '.$companyname1.'-'.$productname1;									
}
else if(($a1_c+$a2_c+$b1_c+$b2_c) > $y and ($a1_c+$b1_c+$b2_c+$b3_c > $a1_c+$a2_c+$a3_c+$b1_c) and $a1_c+$a2_c+$a3_c+$b1_c <= $y)
{
$productname1 = $a1." ".$a2." ".$a3.", ".$b1;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}
else if(($a1_c+$a2_c+$b1_c+$b2_c) <= $y)
{
$productname1 = $a1." ".$a2.", ".$b1." ".$b2;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}
else if($a1_c+$b1_c < $y  and $a1_c+$b1_c+$b2_c <= $y and $a1_c+$b1_c+$b2_c < $a1_c+$a2_c+ $b1_c)
{
$productname1 = $a1.", ".$b1." ".$b2;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}
else if($a1_c+$b1_c < $y  and $a1_c+$a2_c+$b1_c <= $y and $a1_c+$b1_c+$b2_c > $a1_c+$a2_c+ $b1_c)
{
$productname1 = $a1." ".$a2 .", ".$b1;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}
else if($a1 < $y and $a1_c+$a2_c < $a1_c+$b1_c and $a1_c+$a2_c <= $y)
{
$productname1 = $a1." ".$a2;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}
else if($a1_c < $y and $a1_c+a2_c > a1_c+b1_c and a1_c+b1_c <= $y )
{
$productname1 = $a1.", ".$b1;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}
else if( $a1_c >= $y)
{
$productname1 = $a1;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}								
else
{
echo "Error";
}
}
$desc = mysql_real_escape_string($desc);
$sql1 = "UPDATE purchase_master SET po_date='$po_date',project_id='$project_id',ven_id='$ven_compname',subtotal='$totalAfter',ddate='$ddate',vat='$tax',status='open',project_name='$project_name',Block='$blockname',description='$desc',type='$type',refno='$refno',refdate='$refdate',folder='$folder',tax='$vat',stotal='$subTotal',payment='$payment',subject='$subject',contactname='$contactname',mobileno='$mobileno', po_year='$po_year' WHERE po_no='$po_no' and po_year='$po_year'";
echo $sql1;
$result1 = mysql_query($sql1,$conn);
$msg = "IP: " . $ip . " TIME: " . $time . " User: " .$_SESSION['st_username'].  " USERAGENT: " .$userAgent. "Action : PO-NO".$po_no."Inserted";
writeToLogFile($msg);
}
else if($type == 'steel')
{
foreach($_POST['itemDesc'] as $row=>$itemDesc)
{
$product_name=$itemDesc;
$itemQty=$_POST['itemQty'][$row];
$units=$_POST['units'][$row];
$rates=$_POST['itemPrice'][$row];
$itemVat=$_POST['itemVat'][$row];
$itemLineTotal=$_POST['itemLineTotal'][$row];
$po_date=$_POST['po_date'];
$ddate=$_POST['ddate'];
$po_no=$_POST['po_no'];
$project_name=$_POST['project_name'];
$cat_name=$_POST['cat_name'][$row];
$sub_catname=$_POST['sub_catname'][$row];
$details=$_POST['details'][$row];
$brand=$_POST['brand'][$row];
$product_code=$_POST['itemCode'][$row];
isset($_POST['po_id'][$row]) ? $po_id = $_POST['po_id'][$row] : $po_id = "none";
}
//enter rows into database
foreach($_POST['itemDesc'] as $row=>$itemDesc)
{
$itemDesc=mysql_real_escape_string($itemDesc);
$itemQty=mysql_real_escape_string($_POST['itemQty'][$row]);
$units=mysql_real_escape_string($_POST['units'][$row]);
$po_no=mysql_real_escape_string($_POST['po_no']);
$itemPrice=mysql_real_escape_string($_POST['itemPrice'][$row]);
$itemLineTotal=mysql_real_escape_string($_POST['itemLineTotal'][$row]);
$project_name=mysql_real_escape_string($_POST['project_name']);
$po_date=mysql_real_escape_string($_POST['po_date']);
$cat_name=mysql_real_escape_string($_POST['cat_name'][$row]);
$sub_catname=mysql_real_escape_string($_POST['sub_catname'][$row]);
$po_date=mysql_real_escape_string($_POST['po_date']);
$brand=mysql_real_escape_string($_POST['brand'][$row]);
$details=mysql_real_escape_string($_POST['details'][$row]);
$product_code=mysql_real_escape_string($_POST['itemCode'][$row]);
isset($_POST['po_id'][$row]) ? $po_id = $_POST['po_id'][$row] : $po_id = "none";
if($inclusive == '0')
{
$itemVat = '0';
}
else
{
$itemVat=mysql_real_escape_string($_POST['itemVat'][$row]);
}
$itemDesc = trim("$itemDesc");
if($po_id == 'none')
{
if( $itemDesc != '')
{
$sql = "INSERT INTO purchaseorder_tbl(product_name,Po_no,product_qty,ratesperproduct,Amount,vat,po_date,cat_name,sub_catname,units,status,details,brand,stock_received,project_name,block,po_year) VALUES ('$itemDesc',$po_no,$itemQty,$itemPrice,'$itemLineTotal','$itemVat','$po_date','$cat_name','$sub_catname','$units','open','$details','$brand','0','$project_name','$blockname','$po_year')";
echo $sql;
$result = mysql_query($sql,$conn);
$sql3 = "update productlist set Product_price='$itemPrice' where pro_name='$itemDesc' and category_name='$cat_name' and sub_catname='$sub_catname'";
echo $sql3;
$result3 = mysql_query($sql3,$conn);
$sql4 = "INSERT INTO stocklist(pro_name,product_code,cat_name,sub_catname,price,brand,project_name,block,status,po_no,stock,stock_balance,details,stk_received,istatus) VALUES ('$itemDesc','$product_code','$cat_name','$sub_catname','$itemPrice','$brand','$project_name','$blockname','invoiceraised',$po_no,$itemQty,$itemQty,'$details','0','intransit')";
$result4 = mysql_query($sql4,$conn);
echo $sql4;
$query = "select * from requirementtable where product_name='$itemDesc' and cat_name='$cat_name' and sub_catname='$sub_catname'";
echo $query;
$query_run = mysql_query($query,$conn); 	
while ($req_row = mysql_fetch_array($query_run)) 
{
$req_stock=mysql_real_escape_string($req_row['itemsrequired']);
$product_name=mysql_real_escape_string($req_row['product_name']);
echo $req_stock."+++++". $itemQty;
if($req_stock <= $itemQty)
{
$sql_req = "update requirementtable SET status='invoiceraised' where product_name='$product_name' and cat_name='$cat_name' and sub_catname='$sub_catname'";
echo $sql_req;
$result_req = mysql_query($sql_req,$conn);	
}
else
{	    
$sql_req = "update requirementtable SET status='less' where product_name='$product_name' and cat_name='$cat_name' and sub_catname='$sub_catname'";
echo $sql_req;
$result_req = mysql_query($sql_req,$conn);	
}
}
}
}
else
{	
$sql = "UPDATE purchaseorder_tbl SET product_name='$itemDesc',product_qty='$itemQty',ratesperproduct='$itemPrice',Amount='$itemLineTotal',vat='$itemVat',po_date='$po_date',cat_name='$cat_name',sub_catname='$sub_catname',units='$units',status='open',details='$details',brand ='$brand',project_name='$project_name',block='$blockname', po_year='$po_year' WHERE Po_no ='$po_no' and po_id=$po_id and  po_year='$po_year'";
echo $sql;
$result = mysql_query($sql,$conn);
$sql3 = "update productlist set Product_price='$itemPrice' where pro_name='$itemDesc' and category_name='$cat_name' and sub_catname='$sub_catname'";
$result3 = mysql_query($sql3,$conn);
$sql4 = "UPDATE stocklist SET pro_name='$itemDesc',product_code='$product_code',cat_name='$cat_name',sub_catname='$sub_catname',price='$itemPrice',brand='$brand',project_name='$project_name',block='$blockname',status='invoiceraised',stock='$itemQty',stock_balance='$itemQty',details='$details',stk_received='0',istatus='intransit', po_year='$po_year' where WHERE po_year='$po_year' and po_no='$po_no' and pro_name='$itemDesc'";
$result4 = mysql_query($sql4,$conn);
$query = "select * from requirementtable where product_name='$itemDesc' and cat_name='$cat_name' and sub_catname='$sub_catname'";
$query_run = mysql_query($query,$conn); 	
while ($req_row = mysql_fetch_array($query_run)) 
{
$req_stock=mysql_real_escape_string($req_row['itemsrequired']);
$product_name=mysql_real_escape_string($req_row['product_name']);
if($req_stock <= $itemQty)
{
$sql_req = "update requirementtable SET status='invoiceraised' where product_name='$product_name' and cat_name='$cat_name' and sub_catname='$sub_catname'";
$result_req = mysql_query($sql_req,$conn);	
}
else
{	    
$sql_req = "update requirementtable SET status='less' where product_name='$product_name' and cat_name='$cat_name' and sub_catname='$sub_catname'";
echo $sql_req;
$result_req = mysql_query($sql_req,$conn);	
}
}	
}
}
if (!$result)
{
die('Error1: ' . mysql_error());
}
/** vendor add **/
$sql_ven = "select * from vendor_tbl where ven_compname	= '$ven_compname'";
$result_ven=mysql_query($sql_ven,$conn);
if($row_ven = mysql_fetch_array($result_ven))
{
$sql_ven2 = "update vendor_tbl set ven_compname='$ven_compname',ven_add1='$ven_add',ven_contactperson='$ven_contactperson' where ven_compname='$ven_compname'";
$result_ven2 = mysql_query($sql_ven2,$conn);
echo "Updated";	
}
else
{
$sql_ven1 = "INSERT INTO vendor_tbl (ven_compname,ven_add1,ven_contactperson) VALUES ('$ven_compname','$ven_add','$ven_contactperson')";
$result_ven1 = mysql_query($sql_ven1,$conn);
echo "Inserted";
}
/** vendor add end**/
/** Folder add **/
$sql_f = "select * from folder where folder_name = '".$folder."'";
echo $sql_f;
$result_f=mysql_query($sql_f,$conn);
if($row_f = mysql_fetch_array($result_f))
{
echo "already Inserted";	
}
else{
$sql_f1 = "INSERT INTO folder (folder_name) VALUES ('$folder')";
echo $sql_f1;
$result_f1 = mysql_query($sql_f1,$conn);
echo "Inserted";
}
/** Folder add end**/
$sql_ps = "select * from project_details where project_name = '".$project_name."'";
$result_ps=mysql_query($sql_ps,$conn);
if($row_ps = mysql_fetch_array($result_ps))
{
$projectname = $row_ps["projectshort"];	
}		 
$companyname = explode(" ", $ven_compname);
$companyname1=$companyname[0];
$x = strlen($companyname[0]);
$y=35-(($x+3)+1+1);
$a = explode(" ", $product_names1);
$b = explode(" ", $product_names2); 
isset($a[0]) ? $a1 = $a[0] : $a1 = "";
isset($a[1]) ? $a2 = $a[1] : $a2 = "";
isset($a[2]) ? $a3 = $a[2] : $a3 = "";
isset($a[3]) ? $a4 = $a[3] : $a4 = "";
isset($a[4]) ? $a5 = $a[4] : $a5 = "";	
isset($b[0]) ? $b1 = $b[0] : $b1 = "";
isset($b[1]) ? $b2 = $b[1] : $b2 = "";
isset($b[2]) ? $b3 = $b[2] : $b3 = "";
isset($b[3]) ? $b4 = $b[3] : $b4 = "";
isset($b[4]) ? $b5 = $b[4] : $b5 = "";	
$a1_c=strlen($a1);
$a2_c=strlen($a2);
$a3_c=strlen($a3);
$a4_c=strlen($a4);
$a5_c=strlen($a5);
$b1_c=strlen($b1);
$b2_c=strlen($b2);
$b3_c=strlen($b3);
$b4_c=strlen($b4);
$b5_c=strlen($b5);
if($product_names2 == "0")
{
if($a1_c+$a2_c+$a3_c+$a4_c <= $y)
{
$productname1 = $a1." ".$a2." ".$a3." ".$a4;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}
else if($a1_c+$a2_c+$a3_c+$a4_c <= $y)
{
$productname1 = $a1." ".$a2." ".$a3;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}
else if($a1_c+$a2_c <= $y)
{
$productname1 = $a1." ".$a2;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}
else if($a1_c <= $y)
{
$productname1 = $a1;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}
}
if($product_names2 != "0")
{
 if($a1_c+$a2_c+$a3_c+$a4_c+$b1_c+$b2_c+$b3_c+$b4_c <= $y )
{
$productname1 = $a1." ".$a2." ".$a3." ".$a4.", ".$b1." ".$b2." ".$b3." ".$b4;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}
if($a1_c+$a2_c+$a3_c+$b1_c+$b2_c+$b3_c+$b4_c <= $y and ($a1_c+$a2_c+$b1_c+$b2_c+$b3_c+$b4_c < $a1_c+$a2_c+$a3_c+$a4_c+$b1_c+$b2_c) and $a1_c+$a2_c+$b1_c+$b2_c+$b3_c+$b4_c <=$y)
{										
$productname1 = $a1." ".$a2." ,".$b1." ".$b2." ".$b3." ".$b4;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}
if($a1_c+$a2_c+$a3_c+$a4_c+$b1_c+$b2_c <= $y and ($a1_c+$a2_c+$b1_c+$b2_c+$b3_c+$b4_c > $a1_c+$a2_c+$a3_c+$a4_c+$b1_c+$b2_c) and $a1_c+$a2_c+$a3_c+$a4_c+$b1_c+$b2_c <= $y)
{										
$productname1 = $a1." ".$a2." ".$a3." ".$a4." , ".$b1." ".$b2." ".$b3." ".$b4;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}
if($a1_c+$a2_c+$a3_c+$b1_c+$b2_c+$b3_c <= $y and ($a1_c+$a2_c+$b1_c+$b2_c+$b3_c+$b4_c > $a1_c+$a2_c+$a3_c+$a4_c+$b1_c+$b2_c)<=$y)
{										
$productname1 = $a1." ".$a2." ".$a3.", ".$a4." ".$b1." ".$b2." ".$b3." ".$b4;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}									
if($a1_c+$a2_c+$a3_c+$b1_c+$b2_c+$b3_c <= $y)
{										
$productname1 = $a1." ".$a2." ".$a3.", ".$b1." ".$b2." ".$b3;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}									
else if(($a1_c+$a2_c+$b1_c+$b2_c) > $y and ($a1_c+$b1_c+$b2_c+$b3_c < $a1_c+$a2_c+$a3_c+$b1_c) and $a1_c+$b1_c+$b2_c+$b3_c <= $y)
{
$productname1 = $a1.", ".$b1." ".$b2." ".$b3;
$desc = $projectname.' '.$companyname1.'-'.$productname1;									
}
else if(($a1_c+$a2_c+$b1_c+$b2_c) > $y and ($a1_c+$b1_c+$b2_c+$b3_c > $a1_c+$a2_c+$a3_c+$b1_c) and $a1_c+$a2_c+$a3_c+$b1_c <= $y)
{
$productname1 = $a1." ".$a2." ".$a3.", ".$b1;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}								
else if(($a1_c+$a2_c+$b1_c+$b2_c) <= $y)
{
$productname1 = $a1." ".$a2.", ".$b1." ".$b2;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}
else if($a1_c+$b1_c < $y  and $a1_c+$b1_c+$b2_c <= $y and $a1_c+$b1_c+$b2_c < $a1_c+$a2_c+ $b1_c)
{
$productname1 = $a1.", ".$b1." ".$b2;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}
else if($a1_c+$b1_c < $y  and $a1_c+$a2_c+$b1_c <= $y and $a1_c+$b1_c+$b2_c > $a1_c+$a2_c+ $b1_c)
{
$productname1 = $a1." ".$a2 .", ".$b1;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}
else if($a1 < $y and $a1_c+$a2_c < $a1_c+$b1_c and $a1_c+$a2_c <= $y)
{
$productname1 = $a1." ".$a2;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}
else if($a1_c < $y and $a1_c+a2_c > a1_c+b1_c and a1_c+b1_c <= $y )
{
$productname1 = $a1.", ".$b1;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}
else if( $a1_c >= $y)
{
$productname1 = $a1;
$desc = $projectname.' '.$companyname1.'-'.$productname1;
}								
else
{
echo "Error";
}
}
$desc = mysql_real_escape_string($desc);
$sql1 = "UPDATE purchase_master SET po_date='$po_date',project_id='$project_id',ven_id='$ven_compname',subtotal='$subTotal',ddate='$ddate',status='open',project_name='$project_name',Block='$blockname',description='$desc',type='$type',refno='$refno',refdate='$refdate',folder='$folder',payment='$payment',subject='$subject',contactname='$contactname',mobileno='$mobileno',vat='$tax',inclusive='$inclusive', tpwords='$tpwords', po_year='$po_year' WHERE po_no='$po_no' and po_year='$po_year'";
echo $sql1;
$result1 = mysql_query($sql1,$conn);
$msg = "IP: " . $ip . " TIME: " . $time . " User: " .$_SESSION['st_username'].  " USERAGENT: " .$userAgent. "Action : PO-NO".$po_no."Inserted";
writeToLogFile($msg);
}
header("Location: order.php");
if (!$result1)
{
die('Error2: ' . mysql_error());
}
mysql_close($conn)
?>