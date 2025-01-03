<?php
require_once '../../../vendor/autoload.php';
include "../../../include/conn.php";
header('Content-Encoding: UTF-8');
header('Content-type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename=Inventorylist.csv');
echo "\xEF\xBB\xBF";

$status = $_GET['status'];
$project_short = $_GET['project_short'];
$emp_id = $_GET['emp_id'];
$collection = $db->inventory;    
$delimiter = ',';
      $response['site_list'] = array();
        $response['project_short_list'] = array(); //for query
 $collectionProject =  $db->project_details;
                $projectData = $collectionProject->aggregate(array(
                    array( '$group' => array(
                        '_id' => array(
                            'project_name' => '$project_name',
                            'project_short' => '$project_short'
                        )
                    ))
                ));
                foreach($projectData as $rowData)
                {
                    $product['project_name'] = $rowData['_id']['project_name'];
                    $product['project_short'] = $rowData['_id']['project_short'];                    
                    array_push($response['site_list'], $product);
                    array_push($response['project_short_list'], $rowData['_id']['project_short']);
                }


if($project_short =='undefined')
{
$response['site_list'] = $response['site_list'];
$proShortArray = $response['project_short_list'];	
}
else
{
$proShortArray = array($project_short);	
}

  $cursor = $collection->aggregate(array(
            array( '$match' => array(
                "cancel_status" => '0',
                "mail_status" => '1',	
                "completed_status" => '1',
				 "project_short" => array( '$in' => $proShortArray ),
            )),
            array( '$lookup' => array(
                'from' => 'purchase_master',
                'localField' => 'po_number',
                'foreignField' => 'po_number',
                'as' => 'purchase_data'
            )),
            array( '$lookup' => array(
                'from' => 'project_details',
                'localField' => 'project_id',
                'foreignField' => '_id',
                'as' => 'project_data'
            )),
            array( '$sort' => array( 
                '_id' => -1
            ))
        ));

 if($cursor) 
        {
	 echo 'PO_no,PO_Date,Project Name,Product Name,Vendor Name,Delivery Date,Ordered,Received,Return,Unit'."\n";	 
            $response['inventory'] = array();    
	
            foreach($cursor as $rowData){				
                $sendData['_id'] = $rowData['_id'];
                $sendData['po_number'] = isset($rowData['purchase_data'][0]['po_number'])?$rowData['purchase_data'][0]['po_number']:'-';
                $sendData['project_name'] = isset($rowData['project_data'][0]['project_name'])?$rowData['project_data'][0]['project_name']:'-';
                $sendData['block_name'] = isset($rowData['project_data'][0]['block_name'])?$rowData['project_data'][0]['block_name']:'-';
                $sendData['item'] = $rowData['item'];
				$productname = str_replace(array("\n", "\r", ","), '', $sendData['item']);	  
                $sendData['code'] = $rowData['code'];
                $sendData['quantity'] = isset($rowData['quantity']) ? isset($rowData['quantity']):'-';
                $sendData['vendorname'] = isset($rowData['purchase_data'][0]['company'])?$rowData['purchase_data'][0]['company']:'-';
				$vendorname = str_replace(array("\n", "\r", ","), '', $sendData['vendorname']);	
                $sendData['stock'] = isset($rowData['stock']) ? $rowData['stock']:'0';
                $sendData['stock_percentage'] = isset($rowData['stock_percentage'])?$rowData['stock_percentage']:'0';
                $sendData['return'] = isset($rowData['return'])?$rowData['return']:'0';
                $sendData['unit'] = $rowData['unit'];
                $sendData['return_percentage'] = isset($rowData['return_percentage'])?$rowData['return_percentage']:'0';
                $sendData['po_date'] = isset($rowData['purchase_data'][0]['po_date'])?$rowData['purchase_data'][0]['po_date']:'-';
                $sendData['delivery_date'] = isset($rowData['purchase_data'][0]['delivery_date'])?$rowData['purchase_data'][0]['delivery_date']:'-';   
				$delivery_date = str_replace(array("\n", "\r", ","), '', $sendData['delivery_date']);	
		
				if( $sendData['po_number'] != '-')
				{
				echo $sendData['po_number'] .', '. $sendData['po_date'] .', '.$sendData['project_name'].', '.$productname.', '.$vendorname.', '.$delivery_date.', '.$sendData['quantity'].', '.$sendData['stock'].', '.$sendData['return'].', '.$sendData['unit']."\n";	
				}
			
            }


 }

?>