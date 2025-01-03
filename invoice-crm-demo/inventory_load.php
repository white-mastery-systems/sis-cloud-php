<?php
isset($_GET['action']) ? $action = mysql_real_escape_string($_GET['action']) : $action = "none";
isset($_GET['stock_id']) ? $stock_id = mysql_real_escape_string($_GET['stock_id']) : $stock_id = "";
isset($_GET['pro_name']) ? $pro_name = mysql_real_escape_string($_GET['pro_name']) : $pro_name = "";
isset($_GET['product_code']) ? $product_code = mysql_real_escape_string($_GET['product_code']) : $product_code = "";
isset($_GET['price']) ? $price = mysql_real_escape_string($_GET['price']) : $price = "";
isset($_GET['size']) ? $size = mysql_real_escape_string($_GET['size']) : $size = "";
isset($_GET['size']) ? $size = mysql_real_escape_string($_GET['size']) : $size = "";
isset($_GET['cat_name']) ? $cat_name = mysql_real_escape_string($_GET['cat_name']) : $cat_name = "";
isset($_GET['project_name']) ? $project_name = mysql_real_escape_string($_GET['project_name']) : $project_name = "";
isset($_GET['sub_catname']) ? $sub_catname = mysql_real_escape_string($_GET['sub_catname']) : $sub_catname = "";
isset($_GET['stock1']) ? $stock1 = mysql_real_escape_string($_GET['stock1']) : $stock1 = "";
isset($_GET['stock']) ? $stock = mysql_real_escape_string($_GET['stock']) : $stock = "";
isset($_GET['brand']) ? $brand = mysql_real_escape_string($_GET['brand']) : $brand = "";
isset($_GET['status']) ? $status = mysql_real_escape_string($_GET['status']) : $status = "";
isset($_GET['retqty']) ? $retqty = mysql_real_escape_string($_GET['retqty']) : $retqty = "";
isset($_GET['retreason']) ? $retreason = mysql_real_escape_string($_GET['retreason']) : $retreason = "";
isset($_GET['brkqty']) ? $brkqty  = mysql_real_escape_string($_GET['brkqty']) : $brkqty  = "";
isset($_GET['brkreason']) ? $brkreason  = mysql_real_escape_string($_GET['brkreason']) : $brkreason  = "";
isset($_GET['stock_balance']) ? $stock_balance  = mysql_real_escape_string($_GET['stock_balance']) : $stock_balance  = "";
isset($_GET['po_no']) ? $po_no  = mysql_real_escape_string($_GET['po_no']) : $po_no  = "";
date_default_timezone_set('Asia/Calcutta'); 

     $today = date("Y.m.d"); 
include "connect.php";
if($action == 'view')
{
echo "<table  class='table table-full m-b-60' cellpadding='0' cellspacing='0'  id='itemsTable' style='width:100%'>
<thead>
<tr>
<th width='6%'><b>S.No.</b></th>
<th width='6%'><b>PO NO</b></th>
<th width='22%'><b>Product Name</b></th>
<th width='12%'><b>Product Code</b></th>
<th width='7%'><b>Stock</b></th>
<th width='7%'><b>Received</b></th>
<th width='10%'><b> % of Received</b> </th>
<th><b>Action</b></th>
</tr>
</thead>
<tbody>";
include "connect.php";
$sql = 'SELECT * FROM stockview ORDER BY po_no DESC';

$retval = mysql_query( $sql, $conn );
if(! $retval )
{
die('Could not get data: ' . mysql_error());
}
while($row = mysql_fetch_array($retval, MYSQL_ASSOC))
{
echo "<tr> <td> </td>
<td>".$row['po_no']."</td>
<td>".$row['pro_name']."</td>
<td>".$row['product_code']."</td>
<td>".$row['stock']."</td>";

if($row['stock_received'] == "")
{
	$received_stock = "0";
}
else
{
	$received_stock = $row['stock_received'];
}

echo"<td>".$received_stock."</td>";

$val1 = $row['stock_received'];
$val2 = $row['stock'];
if($val2!=0)
{
$res = ( $val1 / $val2) * 100;
$res = round($res); 
}
else
{
	$res=0;
}
echo "<td><div class='percent' style='width:40px;height:40px;'><p style='display:none;' id='cir'>".$res."</p></div></td>
<td class='datagrid-body' width='9%'><div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'><div class='col-md-3 col-sm-12 col-lg-3 col-xs-12'><i class='material-icons tiny'> <a href='#' title='Edit' id='edit_p' class='use-edit' onClick='fun_edit(".$row['stock_id'].");'>add</a></i> &nbsp;&nbsp;</div></td></tr>";
}
echo "</tbody></table>";
}
else if($action == 'edit')
{
	
	$sql = 'SELECT * FROM stocklist where stock_id ='.$stock_id;


 // $sql = 'SELECT a.*,b.* FROM stocklist a, purchaseorder_tbl b WHERE a.po_no = b.po_no and b.pro_name = b.product_name ORDER BY a.stock_id';

$retval = mysql_query( $sql, $conn );
if(! $retval )
{
  die('Could not get data: ' . mysql_error());
}
if($row1 = mysql_fetch_array($retval))
{
	  $sub_catname =  htmlspecialchars($row1["sub_catname"], ENT_QUOTES);
	  $stock_id =  htmlspecialchars($row1["stock_id"], ENT_QUOTES);
		$size =  htmlspecialchars($row1["size"], ENT_QUOTES);
		$pro_name = htmlentities(stripslashes(utf8_decode($row1["pro_name"])));
		
		$brand =  htmlspecialchars($row1["brand"], ENT_QUOTES);
		
		$price = htmlspecialchars($row1["price"], ENT_QUOTES);
		$cat_name = htmlspecialchars($row1["cat_name"], ENT_QUOTES);
		$product_code = htmlspecialchars($row1["product_code"], ENT_QUOTES);
		$project_name = htmlspecialchars($row1["project_name"], ENT_QUOTES);
		$po_no = htmlspecialchars($row1["po_no"], ENT_QUOTES);
			$stock = htmlspecialchars($row1["stock"], ENT_QUOTES);
		$stock_balance = htmlspecialchars($row1["stock_balance"], ENT_QUOTES);
		
		
	$sql1 = "SELECT * from purchaseorder_tbl WHERE po_no =" .$po_no. " and product_name ='" .mysql_real_escape_string($pro_name). "'";
	//echo $sql1;
	$retval1 = mysql_query( $sql1, $conn );
if($row = mysql_fetch_array($retval1))
{
$stock_received = htmlspecialchars($row["stock_received"], ENT_QUOTES);
}
else
{
	$stock_received = 0;
}


	echo '<form id="fm" method="get" novalidate="novalidate" class="fm-edit">';
        echo '<div class="fitem">';
				echo '<label>Product Name:</label>';
				 echo '<input type="text" name="pro_name" id="pro_name" value="'.$pro_name.'"class="">';
				 echo '<input type="hidden" name="action" id="action1" value="update" class="">';
			 echo '</div>';
			 echo '<div class="fitem">';
				 echo '<label>product Code </label>';
				 echo '<input type="text" name="product_code" id="product_code" value="'.$product_code.'"class="">';
			 echo '</div>';
             echo '<div class="fitem">';
				 echo '<label>product Size</label>';
				 echo '<input type="text" name="size" id="size" value="' .$size.'" class="" readonly />';
			 echo '</div>';
			 echo '<div class="fitem">';
				 echo '<label>Product Price </label>';
				 echo '<input type="text" name="price" id="price" value="'.$price.'" class="textbox" readonly />';
			 echo '</div>';
			 echo '<div class="fitem">';
				 echo '<label>Sub Category </label>';
				 echo '<input type="text" name="sub_catname" id="sub_catname" value="' .$sub_catname.'" class="" readonly />';
			 echo '</div>';
			 echo '<div class="fitem">';
				 echo '<label>Category </label>';
				 echo '<input type="text" name="cat_name" id="cat_name" value="'.$cat_name.'" class="" readonly />';
			 echo '</div>';
           echo '<div class="fitem">';
				 echo '<label>Brand Name </label>';
				 echo '<input type="text" id="brand" name="brand" value="' .$brand. '"class="" readonly />';
				 
				 echo '<input type="hidden" id="stock_balance" name="stock_balance" value="' .$stock_balance. '"class="textbox" />';
				  echo '<input type="hidden" name="stock_received" id="stock_received"  value="' .$stock_received. '"class="" readonly />';
				   
				  echo  '<input type="hidden" class="textbox" name="stock_id" id="stockid1" value="' .$stock_id. '">';
			 echo '</div>';
			  echo '<div class="fitem">';
				 echo '<label>Project Name :</label>';
				 echo '<input type="text" name="project_name" id="project_name" value="'.$project_name.'" class="" readonly />';
			 echo '</div>';
			  echo '<div class="fitem">';
				 echo '<label>Po No </label>';
				 echo '<input type="text" name="po_no" id="po_no" value="'.$po_no.'" class="" readonly />';
			 echo '</div>';
			
			  echo '<div class="fitem" id="statusdiv">';
                 echo '<label>Status </label>';                                       
				 echo '<input type="checkbox" onclick="OnChangeCheckbox(this)" name="status" id="status" />
    <label for="status">Received</label>';
			 echo '</div>';
			 
			   echo '<div class="fitem" id="fitem1" style="display:none">';
			   echo '<label>&nbsp;</label>';  
               echo '
			   <input type="checkbox" onclick="OnChangeCheckbox1(this)" value="return" name="return" id="chk1" />
    <label for="chk1">Return</label>
			    <input type="checkbox" onclick="OnChangeCheckbox2(this)"  value="breakage" name="breakage" id="chk2" />
    <label for="chk2">Breakage</label>';
			  echo '</div>';
			   echo '<div class="fitem">';
                 echo '<label>Stock</label>';                                       
				 echo ' <input type="text" id="stock" name="stock" value="' .$stock. '"class="textbox"/>';
			 echo '</div>';
			 
			    echo '<div class="fitem">';
                 echo '<label>Received Qty</label>';                                       
				 echo '<input type="text" name="stock1" id="stock1" class="textbox" />';
			 echo '</div>';
			  echo '<div class="fitem"  id="ret_qty" style="display:none">';
              echo '<label>Ret QTY </label>';                                       
				 echo '<input type="text" name="retqty" id="retqty" class=""/>';
			 echo '</div>';
			 echo '<div class="fitem"  id="ret_qty1" style="display:none">';
                 echo '<label>Ret Reason </label>';                                       
				 echo '<input type="text" name="retreason" id="retreason" class="textbox"/>';
			 echo '</div>';
			  echo '<div class="fitem"  id="brk_qty" style="display:none">';
                 echo '<label>Break QTY </label>';                                       
				 echo '<input  type="text" name="brkqty" id="brkqty"  class=""/>';
			 echo '</div>';
			  echo '<div class="fitem"  id="brk_qty1" style="display:none">';
                 echo '<label>Break Reason </label>';                                       
				 echo '<input type="text" name="brkreason" id="brkreason" class=""/>';
			 echo '</div>';
			 
		
			 
			 echo '<div align="right" style="width:58%">       
      <button type="submit" class="btn-floating btn-medium waves-effect waves-light" title="submit"><i class="material-icons small">done</i></button>  <button type="button" id="clear" class="btn-floating btn-medium waves-effect waves-light" onClick="popupclose()"><i class="material-icons small">clear</i></button>
</div>';
			  echo '</form>';
		
	 }
}
else if($action == 'update')
{
	


$brkqty = 0;
$retqty = 0;
$stock_rec = 0;
$stock_tot = 0;
$stock_received1 = 0;
$stock2 = $stock_balance-$stock1;
$stocklist;

$sql_sel = "select * from purchaseorder_tbl where po_no=$po_no and product_name='$pro_name'";
//echo $sql_sel;
$result_sel= mysql_query($sql_sel,$conn);
if($row_sel = mysql_fetch_array($result_sel))
{
//echo $row_sel['stock_received'];
//echo $stock1;
	$stock_received1 = $row_sel['stock_received']+$stock1;
	$product_qty = $row_sel['product_qty'];
	$val1 = $stock_received1;
$val2 = $product_qty;
if($val2!=0)
{
$res = ( $val1 / $val2) * 100;
$res = round($res); 
}
else
{
	$res=0;
}
    // echo $stock_received1;
	if($stock_received1 <= $product_qty)
	{
$sql_pur = "update purchaseorder_tbl set stock_received=$stock_received1,stk_receiveddate='$today' where po_no=$po_no and product_name='$pro_name'";
//echo $sql_pur;
$result_pur = mysql_query($sql_pur,$conn);
$sql_up="select * from stocklist where stock_id=$stock_id";
//echo $sql_up;
$result_up= mysql_query($sql_up,$conn);
if($row_up = mysql_fetch_array($result_up))
{
$stock_rec=$row_up["stk_received"]+$stock1;
$stock_tot=$row_up["stock"]+$stock1;
$po_no=$row_up["po_no"];


$sql = "update stocklist set stock_balance=$stock2,stock=$stock,status='$status',ret_qty=$retqty,ret_reason='$retreason',brk_qty	=$brkqty,brk_reason='$brkreason',stk_received=$stock_rec,stk_receiveddate='$today', receivedpercentage='$res' where stock_id=$stock_id";
//echo $sql;
$result = mysql_query($sql,$conn);

}
//echo "updated";

$sql1="select * from requirementtable where cat_name='$cat_name' and sub_catname='$sub_catname' and product_name='$pro_name' and status !='available' and LOWER(project_name)='strtolower($project_name)'";
//echo $sql1;

	$result1 = mysql_query( $sql1, $conn );
	
	if(!$result1)
	{
		die('Invalid query: ' . mysql_error());
	}
 while ($row1 = mysql_fetch_array($result1))
{
    	$itemsrequired = $row1['itemsrequired'];
		$req_id	 = $row1['req_id'];
	   // echo $itemsrequired. "<br/>";
		$sql4 = "select * from stocklist  where cat_name='$cat_name' and sub_catname='$sub_catname' and pro_name='$pro_name'" ;
	//echo $sql4;
		$result4 = mysql_query( $sql4 , $conn );
		
		if($row4 = mysql_fetch_array($result4))
		{
			$stocklist = $row4["stock"];
			//echo $stocklist;
		
        	if($itemsrequired <= $stocklist)
         {
       //  $stock3=$stocklist-$itemsrequired;
$sqlup_req="update requirementtable set status='available' where cat_name='$cat_name' and sub_catname='$sub_catname' and product_name='$pro_name' and req_id=$req_id";
//echo $sqlup_req;
$result2 = mysql_query($sqlup_req, $conn);
//$sqlup_pro="update stocklist set stock=$stock3 where cat_name='$cat_name' and sub_catname='$sub_catname' and pro_name='$pro_name'";
//echo $sqlup_pro;
//$result5 = mysql_query($sqlup_pro, $conn);
		 }
		 else
    {
    $sqlup_req="update requirementtable set status='invoice raised' where cat_name='$cat_name' and sub_catname='$sub_catname' and product_name='$pro_name' and req_id=$req_id";
$result2 = mysql_query( $sqlup_req, $conn);
//echo $sqlup_req;

	}
		 
		}
		
}
	}
	else
	{
		//echo "Your Product stock is empty";
	}
	
}
	
}
?>