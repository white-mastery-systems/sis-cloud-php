<?php
require_once '../../../vendor/autoload.php';
include "../../../include/conn.php";
header('Content-Encoding: UTF-8');
header('Content-type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename=productlist.csv');
echo "\xEF\xBB\xBF";
$delimiter = ',';
$status = $_GET['status'];
$productname = $_GET['search'];

$collection = $db->product_list;   

if($productname == 'undefined' && $status == 'usage')
{
	      //retrieve data
            $result = $collection->aggregate(array(
                array( '$match' => array(
                    "cancel_status" => '0'
                )),
                array( '$sort' => array( 
                    '_id' => -1
                ))
            ));
	
	
}
else if($productname == 'undefined' && $status == 'cancel')
{
	    //retrieve data
            $result = $collection->aggregate(array(
                array( '$match' => array(
                    "cancel_status" => '1'
                )),
                array( '$sort' => array( 
                    '_id' => -1
                ))
            ));
	
	
}
else if($productname != '' && $status == 'usage')
{
	
$regex = new \MongoDB\BSON\Regex($productname, $productname); //LIKE query
$result = $collection->aggregate(array(
array( '$match' => array( 'product_name' => $regex,
						 'cancel_status' => '0',
						)),
array( '$sort' => array( 
                    '_id' => -1
                ))
));
}
else if($productname != '' && $status == 'cancel')
{

	
	$regex = new \MongoDB\BSON\Regex($productname, "/".preg_quote($productname, '/')); //LIKE query
$result = $collection->aggregate(array(
array( '$match' => array( 'product_name' => $regex,
						 'cancel_status' => '1',
						)),
array( '$sort' => array( 
                    '_id' => -1
                ))
));
	
}

        if($result)
        {
		 	 
	
echo 'Category,Code,Category,Sub Category,Product Name,Unit,Price,GST'."\n";

  foreach($result as $rowData)
            {
	      $productname = $rowData['product_name'];
	  $productname1 = str_replace(array("\n", "\r", ","), '', $productname);
	  
 if($rowData['code']==''){$rowData['code']='-';}
                    $sendData['_id'] = $rowData['_id'];
                    $category = $rowData['category'];
	 $category1 = str_replace(array("\n", "\r", ","), '', $category);
	   $subcategory = $rowData['sub_category'];
	 $subcategory1 = str_replace(array("\n", "\r", ","), '', $subcategory);
	  
                    $code = $rowData['code'];
                $code1 = str_replace(array("\n", "\r", ","), '', $code);
                    $unit = $rowData['unit'];
	  $unit1 = str_replace(array("\n", "\r", ","), '', $unit);
                    $price = $rowData['price'];
	  $price1 = str_replace(array("\n", "\r", ","), '', $price);
      $gst = $rowData['sgst'] + $rowData['cgst'];
	  $gst1 = str_replace(array("\n", "\r", ","), '', $gst);
	  
	  
	  
    echo  $category1.', '.$code1.','.$category1.','.$subcategory1.','.$productname1.','.$unit1.', '.$price1.', '.$gst1."\n";
	  
}
		}



?>