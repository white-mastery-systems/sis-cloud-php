<?php
session_start();
include 'connect.php';
$po_date=$_POST['po_date'];
$ed=$_POST['ed'];
$vat=$_POST['vat'];
$st=$_POST['st'];
$transportation=$_POST['tp'];
$basictotal=$_POST['basictotal'];
$totalAftertax=$_POST['totalAftertax'];
$itotal=$_POST['itotal'];
$gt=$_POST['gt'];
$gt1=$_POST['gt1'];
$refno=$_POST['refno'];
$refdate=$_POST['refdate'];


$ven_id=$_POST['ven_id'];
$project_name=$_POST['project_name'];
$blockname=$_POST['blockname'];
$project_id=$_POST['project_id'];
$product_names = $_POST['itemDesc'];
$ven_compname = mysql_real_escape_string($_POST['vendor_name']);
$ven_add = mysql_real_escape_string($_POST['ven_add']);
$ven_contactperson = mysql_real_escape_string($_POST['ven_contactperson']);

$i=0;

// for displaying Purpose 

isset($product_names[0]) ? $product_names1 = $product_names[0] : $product_names1 = "";
isset($product_names[1]) ? $product_names2 = $product_names[1] : $product_names2 = "";

$tax=$_POST['vat_o'];

$ddate=$_POST['ddate'];
$time = date('Y-m-d H:i:s');
$po_no=$_POST['po_no'];
$ip = getenv('REMOTE_ADDR');
$userAgent = getenv('HTTP_USER_AGENT');
$referrer = getenv('HTTP_REFERER');
$query = getenv('QUERY_STRING');

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

$itemDesc = trim("$itemDesc");

if( $itemCode != '')
{

$sql = "INSERT INTO purchaseorder_tbl(product_code,product_name,Po_no,product_qty,m_basic,m_total,i_basic,i_total,ed,vat,st,transportation,units,Amount,status,pro_height,pro_width) VALUES ('$itemCode','$itemDesc',$po_no,$itemQty,$itemBasic_m,$itemTotal_m,$itemBasic_i,$itemTotal_i,ed,$vat,$st,$transportation,'$itemUnit','$itemLineTotal','open','$itemHeight','$itemWidth')";
echo $sql;
$result = mysql_query($sql,$conn);

$sql3 = "update productlist set m_basic ='$itemBasic_i',i_basic='$itemTotal_i' where product_code='$itemCode'";
echo $sql3;
$result3 = mysql_query($sql3,$conn);

$sql4 = "INSERT INTO stocklist(pro_name,product_code,itemHeight,itemWidth,itemBasic_m,item_totali,brand,project_name,status,po_no,stock,stock_balance) VALUES('$itemDesc','$itemCode','$itemHeight','$itemWidth','$itemBasic_m','$itemBasic_i','$itemBrand','$project_name','invoiceraised',$po_no,$itemQty,$itemQty)";
$result4 = mysql_query($sql4,$conn);
echo $sql4;

$query = "select * from requirementtable where product_code='$itemCode' and project_name='$project_name'";
echo $query;
$query_run = mysql_query($query,$conn);
 	
while ($req_row = mysql_fetch_array($query_run)) 
{
$req_stock=mysql_real_escape_string($req_row['itemsrequired']);
$product_name=mysql_real_escape_string($req_row['product_name']);
echo $req_stock."+++++". $product_qty;
if($req_stock <= $product_qty)
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
if (!$result)
{
die('Error1: ' . mysql_error());
}
/** vendor add **/

$sql_ven = "select * from vendor_tbl where ven_compname	= '$ven_compname'";
$result_ven=mysql_query($sql_ven,$conn);

if(!$result_ven)
{
$sql_ven1 = "INSERT INTO vendor_tbl (ven_compname,ven_add1,ven_contactperson) VALUES ('$ven_compname','$ven_add','$ven_contactperson')";
$result_ven1 = mysql_query($sql_ven1,$conn);
echo "Inserted";
}
else
{

$sql_ven2 = "update vendor_tbl set ven_compname='$ven_compname',ven_add1='$ven_add',ven_contactperson='$ven_contactperson' where ven_compname='$ven_compname'";
$result_ven2 = mysql_query($sql_ven2,$conn);
echo "Updated";
}
/** vendor add end**/
$projectname = substr("$project_name", 6, 1);
		 if($projectname == 'Q')
		 {
			 $projectname = 'QT';
		 }
		 else if($projectname == 'A')
		 {
			$projectname = 'AP'; 
		 }
		  else if($projectname == 'M')
		 {
			 $projectname = 'MK'; 
		 }
		 else
		 {
			 $projectname = ''; 
		 }
		 $companyname = explode(" ", $ven_compname);
		  $companyname1=$companyname[0];

			   $x = strlen($companyname[0]);
			  


$y=35-(($x+3)+1+1);

 $a = explode(" ", $product_names[0]);
 $b = explode(" ", $product_names[1]);
 isset($a[0]) ? $a1 = $a[0] : $a1 = "";
 isset($a[1]) ? $a2 = $a[1] : $a2 = "";
 isset($a[2]) ? $a3 = $a[2] : $a3 = "";
 isset($a[3]) ? $a4 = $a[3] : $a4 = "";
 isset($a[4]) ? $a5 = $a[4] : $a5 = "";		
 
 isset($b[0])? $b1 = $b[0] : $b1 = "";
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
								
								else if(($a1_c+$a2_c+$b1_c+$b2_c) <= $y)
										 {
											$productname1 = $a1." ".$a2.", ".$b1." ".$b2;
											$desc = $projectname.' '.$companyname1.'-'.$productname1;
										 }
									
else if(($a1_c+$a2_c+$b1_c+$b2_c) > $y and ($a1_c+$b1_c+$b2_c+$b3_c > $a1_c+$a2_c+$a3_c+$b1_c) and $a1_c+$a2_c+$a3_c+$b1_c <= $y )
								{
									$productname1 = $a1." ".$a2." ".$a3.", ".$b1;
									$desc = $projectname.' '.$companyname1.'-'.$productname1;
								}
								else if($a1_c+$b1_c < $y  and $a1_c+$b1_c+$b2_c <= $y and $a1_c+$b1_c+$b2_c < $a1_c+$a2_c+ $b1_c )
								{
									$productname1 = $a1.", ".$b1." ".$b2;
									$desc = $projectname.' '.$companyname1.'-'.$productname1;
								}
								else if($a1_c+$b1_c < $y  and $a1_c+$a2_c+$b1_c <= $y and $a1_c+$b1_c+$b2_c > $a1_c+$a2_c+ $b1_c )
								{
									$productname1 = $a1." ".$a2 .", ".$b1;
									$desc = $projectname.' '.$companyname1.'-'.$productname1;
								}
								else if($a1 < $y and $a1_c+$a2_c < $a1_c+$b1_c and $a1_c+$a2_c <= $y)
								{
									$productname1 = $a1." ".$a2;
									$desc = $projectname.' '.$companyname1.'-'.$productname1;
								}
								else if($a1_c < $y and $a1_c+a2_c > a1_c+b1_c and a1_c+b1_c <= $y)
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


		 

$sql1 = "INSERT INTO purchase_master(po_no,po_date,project_id,ven_id,subtotal,ddate,ed,tax,st,transportation,status,project_name,Block,description,gt,gt1,type,refno,refdate,stotal,basictotal,itotal)VALUES($po_no,'$po_date',$project_id,$ven_id,$totalAftertax,'$ddate','$ed','$vat','$st','$transportation','open','$project_name','$blockname','$desc','$gt','$gt1','window','$refno','$refdate','$subTotal','$basictotal','$itotal')";
$result1 = mysql_query($sql1,$conn);


header("Location: order.php");

if (!$result1)
{
die('Error2: ' . mysql_error());
}

mysql_close($conn)

?> 

