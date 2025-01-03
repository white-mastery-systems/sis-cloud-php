<?php
error_reporting(0);
$file = '/path/to/your/dir/'.$file;
require '../../../vendor/autoload.php';
header('Content-Type: application/csv');
header('Content-Disposition: attachment; filename=salary.xls');
header('Pragma: no-cache');

$m = new MongoDB\Client("mongodb://localhost:27017");

$db = $m->usermongo;
$collection =$db->salaryData;
$site = $_GET['filterBy'];

$department = $_GET['orderBy'];
$bank = $_GET['bankby'];


 if($site!='All'  && $department!='All' && $bank!='All'){
     $cursor = $collection->aggregate(array(
                 array( '$match' => array(
				 "location" =>  $jsonData->site,
				  "department" => $jsonData->department,
                 "mode" => $jsonData->bank,
                      
				)),
				array( '$sort' => array( 
					'empid' => -1))));
		 }
    else if ($site!='All'  && $department =='All' && $bank!='All'){
	 $cursor = $collection->aggregate(array(
				array( '$match' => array(
                     "location" =>  $site,
                    "mode" => $bank
                    )),
				array( '$sort' => array( 
					'empid' => -1))));
        }
         
    else if( $site =='All'  && $department!='All' && $bank =='All'){
	 $cursor = $collection->aggregate(array(
				array( '$match' => array(
                    "department" => $jsonData->department
                    
					
				)),
				array( '$sort' => array( 
					'empid' => -1))));
        }
         
     else if($site!='All'  && $department =='All' && $bank =='All'){
	 $cursor = $collection->aggregate(array(
				array( '$match' => array(
                   "location" =>  $jsonData->site
					
				)),
				array( '$sort' => array( 
					'empid' => -1
				))
			));
        }
          
    else if($site!='All'  && $department!='All' && $bank =='All'){
	   $cursor = $collection->aggregate(array(
				array( '$match' => array(
                   "location" =>  $site,
                    "department" => $department
					
				)),
				array( '$sort' => array( 
					'empid' => -1))));
        }
        
    else if($site =='All'  && $department!='All' && $bank!='All'){
	 $cursor = $collection->aggregate(array(
				array( '$match' => array(
                   "department" => $department,
                    "mode" => $bank
					
				)),
				array( '$sort' => array( 
					'empid' => -1))));
        }
  if($site!='All'  && $department =='All' && $bank =='All'){
	 $cursor = $collection->aggregate(array(
				array( '$match' => array(
                   "location" =>$site,
                   
					
				)),
				array( '$sort' => array( 
					'empid' => -1))));
        }
         else{
			 $cursor =  $collection->aggregate(array(
				
				array( '$sort' => array( 
					'empid' => -1
				))
			));
		 }


 echo 'Employeeno, Name,department,Monthly salary, Hra, conveyance, Flexible plan, provisional-fund,advance,Incometax,otherdeduction,total_amount, credited-date, mode of payment,increment '."\n";
foreach($cursor as $cur)
{
    echo $cur["empid"].','.$cur['name'].','.$cur['department'].','.$cur["gross"].','.$cur["hra"].', '.$cur["conveyance"].', '.$cur["flexibleplan"].', '.$cur['pf'].', '.$cur['advance'].','.$cur['it'].','.$cur['others'].','.$cur['totalearnedamount'].',  '.$cur['credited'].', '.$cur['mode'].','.$cur['inc']."\n";
}

?>
