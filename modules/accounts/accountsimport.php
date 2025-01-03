<?php
require_once '../../vendor/autoload.php';
include "../../include/conn.php";

$collection =$db->salary_list1;
$collectionLeave = $db->userleave_details1;
$collectionHistory = $db->salary_history1;

$i=0;
if(!empty($_FILES["employee_file"]["name"]))
{
	$imageupload = $_FILES["employee_file"]["tmp_name"];
    $handle = fopen($imageupload,"r"); 
    while (($arr = fgetcsv($handle, 10000, ",")) !== FALSE)
    {
        if($i>0)
        {
            $credited = $arr[19];
            $day = date("t", strtotime($credited));
            if(empty($day)) {
                $day1 =(int) 31;
                $day = $day1;
            }
            else {
                $day = date("t", strtotime($credited));
            }
            $leave = $arr[16];
            $dw = $day-$leave;

            $d = date_parse_from_format('d-m-Y',$credited );

            $month = $d["month"];
            $monthname=date('F', mktime(0, 0, 0,$month , 10));

            $year = $d["year"];

            $increment = $arr[26];

            $gross = $arr[4];
            $inc2 = $gross * ($increment)/100;

            $gross = $arr[4] + $inc2;
            $basicpay = ($gross *60)/100;
            $hra = ($gross *20)/100;
            $conveyance = ($gross *10)/100;
            $flexbileplan = ($gross *10)/100;
            $total_amount = $basicpay + $hra + $conveyance +  $flexbileplan;

            $pt = $arr[10];
            if(empty($pt)) {
                $pt1 = 0;
                $pt = $pt1;
            }
            else {
                $pt = $arr[10];
            }

            $advance = $arr[11];
            $it = $arr[24];
            if(empty($it)) {
                $it1 = 0;
                $it = $it1;
            }
            else {
                $it = $arr[24];
            }

            $others = $arr[25];
            $los = $arr[17]; 

            if(empty($los)) {
                $los1 = 0;
                $los = $los1;
            }
            else {
                $los = $arr[17];  
            }
            $status = $arr[27]; 
            if($status == 'yes') {
                if($gross > 15000) { $pf = 1800; } else { $pf = ($gross *12)/100; }
                $amount = $pf + $pt + $advance + $it + others;
                $totalearnedamount =  $total_amount - $amount - $los;
            }
            else {
                $pf = 0;
                $amount = $pt + $advance + $it + others ;
                $totalearnedamount =  $total_amount - $amount - $los;
            }

            $empid =(int) $arr[0];
            $leave = $arr[16];
            $no_leave = (int)15;
            $availleave = $no_leave - $leave ;
            $monthfrom =April;
            $monthto =April;
            $year_from =  date("Y");
            $finmonth =$monthfrom .$year_from;
            $year_to = date('Y', strtotime('+1 year'));
            $finmoth_to= $monthto.$year_to;
            $prevmonth = date('F', strtotime("last month"));
            $cursor = $collectionSal->findOne(array("empid" => (int)$arr[0]));
            if($cursor)
            {
                $collectionSal->updateOne(array("empid" => (int)$arr[0]),
                    array('$set' => array(
                        "name" => $arr[1], 
                        "department" => $arr[2], 
                        "location" => $arr[3], 
                        "gross" => $gross, 
                        "basicpay" => $basicpay, 
                        "hra" => $hra, 
                        "conveyance" => $conveyance, 
                        "flexibleplan" => $flexbileplan,
                        "pf" => $pf,
                        "pt" => $pt,
                        "advance" => $advance,
                        "amount" => $amount,
                        "total_amount" => $total_amount,
                        "totalearnedamount" => $totalearnedamount ,
                        "ndw" => $day,
                        "leave" => $arr[16],
                        "los" => $arr[17],
                        "dw" => $dw,
                        "credited" => $arr[19],
                        "salmonth" =>  $month,
                        "salyear" => $year,
                        "monthname" =>  $monthname,
                        "mode" => $arr[23],
                        "it" => $it,
                        "others" => $others,
                        "increment" => $inc,
                        "status" => $status
                    ))
                );

                $collectionHistory->insertOne(array(
                    "empid" => $arr[0], 
                    "gross" => $gross, 
                    "credited" => $arr[19],
                    "mode" => $arr[23],
                    "increment" => $inc,
                    "status" => $status
                ));

                $collectionLeave->insertOne(array(
                    "empid" =>$empid,
                    "leave" =>$leave , 
                    "no_leave" =>  $no_leave,
                    "availleave" => $availleave,
                    "finmonth" => $finmonth,
                    "finmonth_to" => $finmoth_to
                ));
            }
            else {
                $collectionSal->insertOne(array(
                    "empid" => $arr[0], 
                    "name" => $arr[1], 
                    "department" => $arr[2], 
                    "location" => $arr[3], 
                    "gross" => $gross, 
                    "basicpay" => $basicpay, 
                    "hra" => $hra, 
                    "conveyance" => $conveyance, 
                    "flexibleplan" => $flexbileplan,
                    "pf" => $pf,
                    "pt" => $pt,
                    "advance" => $advance,
                    "amount" => $amount,
                    "total_amount" => $total_amount,
                    "totalearnedamount" => $totalearnedamount ,
                    "ndw" => $day,
                    "leave" => $arr[16],
                    "los" => $arr[17],
                    "dw" => $dw,
                    "credited" => $arr[19],
                    "salmonth" =>  $month,
                    "salyear" => $year,
                    "monthname" =>  $monthname,
                    "mode" => $arr[23],
                    "it" => $it,
                    "others" => $others,
                    "increment" => $inc,
                    "status" => $status
                ));

                $collectionHistory->insertOne(array(
                    "empid" => $arr[0], 
                    "gross" => $gross, 
                    "credited" => $arr[19],
                    "mode" => $arr[23],
                    "increment" => $inc,
                    "status" => $status
                ));

                $collectionLeave->insertOne(array(
                    "empid" =>$empid,
                    "leave" =>$leave , 
                    "no_leave" =>  $no_leave,
                    "availleave" => $availleave,
                    "finmonth" => $finmonth,
                    "finmonth_to" => $finmoth_to
                ));
            }
        }
	   $i++;
    }
    fclose($handle);
	echo "File Uploaded successfully";
}
?>