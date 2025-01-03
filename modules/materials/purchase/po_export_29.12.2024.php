<?php
require_once '../../../vendor/autoload.php';
include "../../../include/conn.php";
header('Content-Encoding: UTF-8');
header('Content-type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename=Orderlist.csv');
echo "\xEF\xBB\xBF";

$status = $_GET['status'];
$project_short = $_GET['project_short'];
$emp_id = $_GET['emp_id'];
$category = $_GET['category'];
$collectionMaster = $db->purchase_master;  
$delimiter = ',';
if($project_short == '' &&  $status == 'usage')
{
	 $result = $collectionMaster->aggregate(array(
        array( '$match' => array(
            "cancel_status" =>  '0'			
        )),
        array( '$sort' => array( 
            '_id' => -1
        ))
    ));
}
else if($project_short == '' && $status == 'cancel')
{
	  $result = $collectionMaster->aggregate(array(
        array( '$match' => array(
            "cancel_status" =>  '1'			
        )),
        array( '$sort' => array( 
            '_id' => -1
        ))
    ));
}

else if($project_short != '' &&  $status == 'usage')
{
	 $result = $collectionMaster->aggregate(array(
        array( '$match' => array(
            "cancel_status" =>  '0',
			"project_short" =>  $project_short
        )),
        array( '$sort' => array( 
            '_id' => -1
        ))
    ));
}
else if($project_short != '' &&  $status == 'cancel')
{
	 $result = $collectionMaster->aggregate(array(
        array( '$match' => array(
            "cancel_status" =>  '1',
			"project_short" =>  $project_short
        )),
        array( '$sort' => array( 
            '_id' => -1
        ))
    ));
}
        if($result)
        {
		 	 
echo 'PO_no,PO_Date,Project,Company,Grandtotal,Category'."\n";

foreach($result as $rows)
            {

    			$product['category'] = $rows['category'];
                $product['company'] = $rows['company'];
                $product['po_number'] = $rows['po_number'];
                $product['po_date'] = $rows['po_date'];
                $product['summary'] = $rows['description'];
                $product['total'] = $rows['grand_total'];
                $product['project_short'] = $rows['project_short'];
	 $product['category'] = $rows['category'];
           if($product['project_short']   == 'MK')
		   {
			   $project = 'S.I.S Marakesh';
		   }
	else if($product['project_short']   == 'QT')
	 	   {
			   $project = 'S.I.S Queenstown';
		   }
    else if($product['project_short']   == 'H')
		   {
			   $project = 'Head Office';
		   }
	else if($product['project_short']   == 'SN')
		   {
			   $project = 'S.I.S Sintra';
		   }
		else if($product['project_short']   == 'AP')
		   {
			   $project = 'S.I.S Acropole';
		   }
	else if($product['project_short']   == 'SF')
		   {
			   $project = 'S.I.S Safaa';
		   }
	else if($product['project_short']   == 'D')
		   {
			   $project = 'S.I.S Danube';
		   }
		else if($product['project_short']   == 'CT')
		   {
			   $project = 'S.I.S Capetown';
		   }
	else if($product['project_short']   == 'FL')
		   {
			   $project = 'S.I.S Florence';
		   }
		else if($product['project_short']   == 'CM')
		   {
			   $project = 'Common';
		   }
	else if($product['project_short']   == 'SIS')
		   {
			   $project = 'S.I.S';
		   }
	else if($product['project_short']   == 'SIS')
		   {
			   $project = 'S.I.S';
		   }
	else if($product['project_short']   == 'ME')
		   {
			   $project = 'S.I.S Meridian';
		   }
    echo $product['po_number'].', '.$product['po_date'].','.$project.','.$product['company'].', '.$product['total'].', '.$product['category']."\n";
}
		}







//
//
//
//
//if($cancelStatus == 'usage'){ $cancelStatus = '0'; }
//        else{ $cancelStatus = '1'; }
//        
//        // collection
//        $collectionMaster = $db->purchase_master;    
//        
//       
//  $response = array();
//        $response['purchase_data'] = array();     
//    
//        
//        if($cancelStatus == 'usage'){ $cancelStatus = '0'; }
//        else{ $cancelStatus = '1'; }
//        
//      
//      
//
// if($project_short !='' && $cancelStatus = '0' ) {
//	 
//	  $result = $collectionMaster->aggregate(array(
//        array( '$match' => array(
//            "cancel_status" =>  '0',
//			 "project_short" => $project_short 
//			
//        )),
//        array( '$sort' => array( 
//            '_id' => -1
//        ))
//    ));
//	 
//	
// }
//
//else if($project_short =='' && $cancelStatus = '0')
//{
// $result = $collectionMaster->aggregate(array(
//        array( '$match' => array(
//            "cancel_status" =>  '0'	
//			
//        )),
//        array( '$sort' => array( 
//            '_id' => -1
//        ))
//    ));
//}
//
//else if($project_short !='' && $cancelStatus = '1')
//{
// $result = $collectionMaster->aggregate(array(
//        array( '$match' => array(
//            "cancel_status" =>  '1',
//			"project_short" => $project_short 
//			
//        )),
//        array( '$sort' => array( 
//            '_id' => -1
//        ))
//    ));
//}
//else if($project_short =='' && $cancelStatus = '1')
//{
// $result = $collectionMaster->aggregate(array(
//        array( '$match' => array(
//            "cancel_status" =>  '1',
//			
//        )),
//        array( '$sort' => array( 
//            '_id' => -1
//        ))
//    ));
//}
//
//
//

?>