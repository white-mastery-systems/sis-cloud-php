<?php
isset($_GET['action']) ? $action = mysql_real_escape_string($_GET['action']) : $action = "none";
isset($_GET['stock_id']) ? $stock_id = mysql_real_escape_string($_GET['stock_id']) : $stock_id = "";
echo $action;
date_default_timezone_set('Asia/Calcutta'); 
$today = date("Y.m.d");

include "connect.php";
if($action == 'view')
{
echo "<table class='table table-full m-b-60' cellpadding='0' cellspacing='0'  id='itemsTable' style='width:100%'>

<thead>
<tr class='panel-header1'>
<th style='width:5%'><b>S.No.</b></th>
<th style='width:20%'><b>project_name</b></th>
<th style='width:35%'><b>Product Name</b> </th>
<th style='width:20%'><b>Product Code</b></th>
<th style='width:10%'><b>Stock</b></th>
<th style='width:10%'><b>Used Qty</b></th>
<th style='width:10%'><b>Action</b></th>
</tr>
</thead>
<tbody>";
$result = mysql_query("SELECT pro_name,SUM(stk_received) as stk_received,SUM(stk_used) as stk_received, SUM(stock_balance) as stock_balance,product_code,size,price,sub_catname,cat_name,project_name,SUM(stock) as stock,SUM(stk_used) as stk_used FROM  stocklist GROUP BY pro_name,project_name order by po_no desc",$conn);

while ($row = mysql_fetch_array($result)) {
	(isset($row['stk_used'])) ? $stk_used = $row['stk_used'] : $stk_used = "0"; 
	(isset($row['stock_balance'])) ? $stock_balance = $row['stock_balance'] : $stock_balance = "0"; 
	(isset($row['stk_received'])) ? $stk_received = $row['stk_received'] : $stk_received = "0"; 
echo "<tr>
<td> </td>
<td>".$row['project_name']."</td>
<td>".$row['pro_name']."</td>
<td>".$row['product_code']."</td>
<td>".$stk_received."</td>	
<td>".$stk_used."</td>	
<td> <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'><div class='col-md-3 col-sm-12 col-lg-3 col-xs-12'><button id='edit_p' onClick=".chr(34)."fun_edit(encodeURIComponent(".chr(39).urlencode($row['pro_name']).chr(39)."),".chr(39).$row['project_name'].chr(39).")".chr(34)."class='but_icon'><i class='material-icons'>remove</i></button></div></div></td>	
</tr>";
}
echo "</tbody>    
</table>";
}
else if($action == 'edit')
{
	isset($_GET['pro_name']) ? $pro_name = mysql_real_escape_string(urldecode($_GET['pro_name'])) : $pro_name = "";
isset($_GET['project_name']) ? $project_name = mysql_real_escape_string($_GET['project_name']) : $project_name = "";
$stock2 = 0;
$sqlstk = "SELECT pro_name,product_code,SUM(stk_used) AS stk_used,SUM(stk_received) AS stk_received,size,brand,sub_catname,cat_name,project_name,SUM(stock) AS stock FROM stocklist  where pro_name='".$pro_name. "' and  project_name='" .$project_name. "'";
//echo $sqlstk;

$resultsql = mysql_query($sqlstk,$conn);
while($rowsql = mysql_fetch_array($resultsql))
	 {
	  $sub_catname =  htmlspecialchars($rowsql["sub_catname"], ENT_QUOTES);
if($rowsql["stk_received"] == "")
{
	$stk_received = "0";
}
else
{
	$stk_received = $rowsql["stk_received"];
}
	
	echo $stk_received;
		$size =  htmlspecialchars($rowsql["size"], ENT_QUOTES);
		$pro_name = htmlentities(stripslashes(utf8_decode($rowsql["pro_name"])));
		$project_name =  htmlspecialchars($rowsql["project_name"], ENT_QUOTES);
		$brand =  htmlspecialchars($rowsql["brand"], ENT_QUOTES);
		$cat_name = htmlspecialchars($rowsql["cat_name"], ENT_QUOTES);
		$product_code = htmlspecialchars($rowsql["product_code"], ENT_QUOTES);
		$stock = htmlspecialchars($rowsql["stock"], ENT_QUOTES);
		
		
		$stk_used = htmlspecialchars($rowsql["stk_used"], ENT_QUOTES);		
		if($rowsql["stk_used"] == "")
		{
			$stk_used='0';
		}
		else
		{
			$stk_used = $rowsql["stk_used"];
		}
$stock2=$stk_used+$stock2;
//echo $stock2;	
	 }
echo '<form id="fm" method="get" novalidate="novalidate" class="form_update">';
echo '<div id="result1"></div>';
        echo '<div class="fitem">';
				echo '<label>Product Name:</label>';
				 echo '<input name="pro_name" id="pro_name" value="'.$pro_name.'"class="">';
			 echo '</div>';
			 echo '<div class="fitem">';
				 echo '<label>product Code :</label>';
				 echo '<input name="product_code" id="product_code" value="'.$product_code.'"class="">';
			 echo '</div>';
             echo '<div class="fitem">';
				 echo '<label>product Size :</label>';
				 echo '<input name="size" id="size" value="' .$size.'" class="" readonly />';
			 echo '</div>';
			
			 echo '<div class="fitem">';
				 echo '<label>Sub Category:</label>';
				 echo '<input name="sub_catname" id="sub_catname" value="' .$sub_catname.'" class="" readonly />';
			 echo '</div>';
			 echo '<div class="fitem">';
				 echo '<label>Category :</label>';
				 echo '<input name="cat_name" id="cat_name" value="'.$cat_name.'" class="" readonly />';
			 echo '</div>';
           echo '<div class="fitem">';
				 echo '<label>Brand Name :</label>';
				 echo '<input id="brand" name="brand" value="' .$brand. '"class="" readonly />';				 
				 echo '<input type="text" id="stock" name="stock" value="' .$stock. '"class="textbox" readonly />';
				    echo '<input type="text" id="stk_used" name="stk_used" value="' .$stk_used. '"class="textbox" readonly />';
				   echo '';
			 echo '</div>';		 
			   echo '<div class="fitem">';
				 echo '<label>Project Name :</label>';
				 echo '<input id="project_name" name="project_name" value="' .$project_name. '"class="textbox" readonly />';
				 echo '</div>';	
				   echo '<div class="fitem">';
				 echo '<label>Block Name :</label>';
				 echo '<input id="blockname" name="blockname" value=" " class="textbox"/>';
				 echo '</div>';	
				   echo '<div class="fitem">';
				 echo '<label>Flat No :</label>';
				 echo '<input id="flatno" name="flatno" value=" " class="textbox" />';
				 echo '</div>';	
				   echo '<div class="fitem">';
                 echo '<label>Stock :</label>';                                       
				 echo '<input type="text" id="stk_received" name="stk_received" value="' .$stk_received. '" />';
			 echo '</div>';
		  echo '<div class="fitem">';
                 echo '<label>Used Stock :</label>';                                       
				 echo '<input name="stock1" id="stock1" class="textbox" />';
			 echo '</div>';
			 echo '<div id="dlg-buttons">';
echo '<button data-loading-text="Saving..." id="save" name="save" type="button" class="sub_button save" value="edit" onClick="return update();">Save</button>';
echo '</div>';
			  echo '</form>';
			    
}
else if($action == 'update')
{
$pro_name=mysql_real_escape_string($_GET['pro_name']);
$project_name=mysql_real_escape_string($_GET['project_name']);
$stock = mysql_real_escape_string($_GET["stock"]);
date_default_timezone_set('Asia/Calcutta'); 
$today = date("Y.m.d"); 
$stock2 = mysql_real_escape_string($_GET["stock1"]);
$stock = mysql_real_escape_string($_GET["stock"]);
$cat_name =mysql_real_escape_string($_GET["cat_name"]);
$sub_catname = mysql_real_escape_string($_GET["sub_catname"]);
$brand = mysql_real_escape_string($_GET["brand"]);
$block_name = mysql_real_escape_string($_GET["block_name"]);
//echo $block_name;
$flatno = mysql_real_escape_string($_GET["flatno"]);
$item_used = 0;
$item_used1 = 0;
//echo $pro_name. "----" .$project_name . "----" . $stock2;
$item_used = 0;
$item_used1 = 0;
//echo $pro_name. "----" .$project_name . "----" . $stock2;
$stock_received ;
$sqlstk = "SELECT * FROM  stocklist  where pro_name='".$pro_name."' and  project_name='" .$project_name. "'";
//echo $sqlstk;
$result1 = mysql_query($sqlstk,$conn);
while($row1 = mysql_fetch_array($result1))
	 {
		  $stkusedstock = 0;
	 $stock_received = $row1["stk_received"];
	  $stock_id = $row1["stock_id"];
	  $stk_used = $row1["stk_used"]; 
	 if($stock_received < $stock2)
	 {
		  $stkusedstock = $row1["stk_received"];		 
		// echo $stkusedstock;
		 $sqlup1 = "update stocklist set stk_received=0,stk_useddate='$today' where stock_id=$stock_id";
		 //echo $sqlup1;
		 $result2 = mysql_query($sqlup1,$conn);
		  //echo "true";	
	 }
elseif($stock2 <= $stock_received)
{	
	 $stockreceived1 = $row1["stk_received"] + $stkusedstock;
	
	 $stockreceived2 = $stockreceived1 - $stock2;
	   $stk_used = $stock2+$stk_used+$stkusedstock;
	 $sqlup2 = "update stocklist set stk_received=$stockreceived2,stk_useddate='$today',stk_used=$stk_used where stock_id=$stock_id";
// echo $sqlup2;
	 $result3 = mysql_query($sqlup2,$conn);
$sqluse1 = "insert into stockuseage(product_name,cat_name,sub_catname,brand,block_name,project_name,flatno,stock_used,stock_useddate)values('$pro_name','$cat_name','$sub_catname','$brand','$block_name','$project_name','$flatno','$stock2','$today')";
//echo $sqluse1;
$resultuse1 = mysql_query($sqluse1,$conn);
$sqluse2 = "SELECT * FROM requirementtable where product_name='".$pro_name."' and LOWER(project_name)='".strtolower($project_name)."' and flatno='".trim($flatno)."' and LOWER(block)='".trim(strtolower($block_name))."' and cat_name='".$cat_name."' and sub_catname='".$sub_catname."'";
//echo $sqluse2;
$resultuse2 = mysql_query($sqluse2,$conn);
while($rowuse2 = mysql_fetch_array($resultuse2))
{
	$item_used = $rowuse2["item_used"];
//echo $stock2;
	$item_used1 = $item_used + $stock2;
$sqluse3 = "update requirementtable set item_used =" .$item_used1. " where product_name='".$pro_name."' and LOWER(project_name)='".strtolower($project_name)."' and flatno='".trim($flatno)."' and LOWER(block)='".trim(strtolower($block_name))."' and cat_name='".$cat_name."' and sub_catname='".$sub_catname."'";
//echo $sqluse3;
$resultuse3 = mysql_query($sqluse3,$conn);

}
}
else
{
	//echo "No Rows Affected";
	//echo "false";

	 }
	 }
	 }
?>