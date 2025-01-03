<?php
$po_no=68;
$po_no=$po_no+1;
for(i=63
$sql2 ="SELECT * FROM  purchaseorder_tbl where Po_no=".$po_no;
		   echo $sql2;
		  
		  $result2= mysql_query($sql2,$conn);

		  while($row2 = mysql_fetch_array($result2)) {
			  //$companyname= explode("",$row1["ven_compname"]);
			 //  $product_name = explode(" ", $row2["product_name"]);
			$product_names[]=mysql_real_escape_string($row2["product_name"]);

			

		  }
		  
		  for($i=0;$i<count($product_names);$i++)
{
echo $product_names[$i]."<br>";
}
?>