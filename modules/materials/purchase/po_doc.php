<?php
$poNum = $_GET['id'];
$emp_id = (int)$_GET['emp_id'];

require_once '../../../vendor/autoload.php';
include "../../../include/conn.php";
include "../../../include/money_format.php";

header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment; Filename=".$poNum.".doc");

$collectionMaster = $db->purchase_master;

$cursor = $collectionMaster->aggregate(array(
        array('$match' => array(
            "po_number" => $poNum
        )),
        array( '$lookup' => array(
            'from' => 'purchase_table',
            'localField' => 'po_number',
            'foreignField' => 'po_number',
            'as' => 'purchase_table_data'
        )),
        array( '$lookup' => array(
            'from' => 'signintable',
            'localField' => 'emp_id',
            'foreignField' => 'emp_id',
            'as' => 'user_data'
        )),
        array( '$lookup' => array(
            'from' => 'project_details',
            'localField' => 'project_id',
            'foreignField' => '_id',
            'as' => 'project_details_data'
        )),
        array( '$lookup' => array(
            'from' => 'vendor_details',
            'localField' => 'company',
            'foreignField' => 'company_name',
            'as' => 'vendor_details_data'
        ))
));
if($cursor)
{
    foreach($cursor as $rowData)
    {
        $block = '';
        if($rowData['project_details_data'][0]['block_name'] != 'none') {
            $block = " Block ".$rowData['project_details_data'][0]['block_name'];
        }
?>
<html>
<head>
    <style>
        p.MsoHeader, li.MsoHeader, div.MsoHeader{
            margin:0in;
            margin-top:.0001pt;
            mso-pagination:widow-orphan;
            tab-stops:center 3.0in right 6.0in;
        }
        p.MsoFooter, li.MsoFooter, div.MsoFooter{
            margin:0in;
            margin-bottom:.0001pt;
            mso-pagination:widow-orphan;
        }
        @page Section1{
            size:8.5in 11.0in; 				 
            font-family:cambria;
            font-size:12px !important;
            margin:0.3in 0.5in 0.3in 0.5in;
            mso-header-margin:0.5in;
            mso-header:h1;
            mso-footer:f1; 
            mso-footer-margin:0.5in;
            mso-paper-source:0;	
        }
        div.Section1{
            page:Section1;
		}
        p.MsoNormal, li.MsoNormal, div.MsoNormal{
            margin:0cm;
            margin-bottom:.0001pt;
            font-size:12.0pt;
            font-family:'cambria';
        }
        div.leftcolumn{
            position:relative;
            float:left;
            width:45%;
        }
        div.center{
            position:relative;
            float:left;
            width:10%
        }
        div.main{
            width:100%;
        }
        div.rightcolumn{
            position:relative;
            float:left;
            width:45%
        }
        table#hrdftrtbl{
            margin:0in 0in 0in 9in;
        }  	
    </style>
</head>
<body>
    <div class='Section1'  style='font-family:cambria;	font-size:13px !important;'>
        <div class=MsoNormal>
        <div align='right'><b><?php echo $rowData['po_date']; ?></b><br></div>
        <b><?php echo $rowData['order_type_short']; ?> Number: <?php echo $rowData['po_number']; ?></b><br><br>
        <b>To:</b><br>
        <b>M/S.<?php echo $rowData['vendor_details_data'][0]['company_name']; ?></b><br>
        <div style='width:250px; height:auto'><?php echo $rowData['vendor_details_data'][0]['address']; ?></div>
		<div><?php echo $rowData['vendor_details_data'][0]['mobile']; ?></div>
        <div align='center' style='width:100%'><b>Kind Attention: <?php echo $rowData['vendor_details_data'][0]['contact_person']; ?></b></div>
        <?php
        if($rowData['reference_no']!='' && $rowData['reference_date']!='')
        {
            echo "<b>Ref: </b>".$rowData['reference_no']."<b> Dated</b> on ".$rowData['reference_date']."<br>";
        }
        ?>
        <div style='line-height:200%'><b>Sub: <?php echo $rowData['order_type_short']; ?> for <?php echo $rowData['po_subject']; ?>.</b></div>
            We are pleased to place the <?php echo $rowData['order_type']; ?> as per the details mentioned below for our  Project <b><?php echo $rowData['project_details_data'][0]['project_name'].$block; ?></b> the address and the contact person are mentioned below.
        </div>
        <div id='space'></div>
        <br><?php
        
        //Standard
        if($rowData['type']=='Standard' || $rowData['type']=='Work')
        {
            echo "
            <table cellpadding='0' cellspacing='0' width='100%' border='0' style='border-collapse: collapse; border: 1px solid #000; font-size:13px; font-family:cambria' >
            <thead>
                <tr>
                    <th style='border:solid 1px #000; padding: 5px; width:15px;' align='center'>No</th>
                    <th style='border:solid 1px #000; width:100px;' align='left'>&nbsp;HSN Code</th>
                    <th style='border:solid 1px #000; width:200px;' align='left'>&nbsp;Product Name</th>
                    <th style='border:solid 1px #000; width:100px;' align='center'>Qty</th>
                    <th style='border:solid 1px #000; width:100px;' align='center'>Price &#8377;</th>
                    <th style='border:solid 1px #000; width:120px;' align='center'>Amount &#8377;</th>
                    <th style='border:solid 1px #000; width:60px;' align='center'>GST %</th>
                    <th style='border:solid 1px #000; width:100px;' align='center'>SGST &#8377;</th>
                    <th style='border:solid 1px #000; width:100px;' align='center'>CGST &#8377;</th>
                    <th style='border:solid 1px #000; width:140px;' align='right'>Total &#8377;&nbsp;</th>
                </tr>
            </thead>
            <tbody>";
       
            $productCount = count($rowData['purchase_table_data']);
            $n=1;
            $subTotal=0;
            for($i=0; $i<$productCount; $i++)
            {
                $amount = $rowData['purchase_table_data'][$i]['quantity'] * $rowData['purchase_table_data'][$i]['price'];
                $gst = $rowData['purchase_table_data'][$i]['sgst'] + $rowData['purchase_table_data'][$i]['cgst'];
                $sgstAmount = ($rowData['purchase_table_data'][$i]['sgst']/100) * $amount;
                $cgstAmount = ($rowData['purchase_table_data'][$i]['cgst']/100) * $amount;
                $total = $amount + $sgstAmount + $cgstAmount;
                $subTotal += $total;
                
                echo "
                <tr>
                    <td style='border:solid 1px #000; padding: 5px;' align='center'>".$n."</td>
                    <td style='border:solid 1px #000;' align='left'>&nbsp;".$rowData['purchase_table_data'][$i]['code']."</td>
                    <td style='border:solid 1px #000;' align='left'>&nbsp;".$rowData['purchase_table_data'][$i]['item']."</td>
                    <td style='border:solid 1px #000;' align='center'>".$rowData['purchase_table_data'][$i]['quantity']."&nbsp;".$rowData['purchase_table_data'][$i]['unit']."</td>
                    <td style='border:solid 1px #000;' align='center'>".moneyFormat($rowData['purchase_table_data'][$i]['price'])."</td>
                    <td style='border:solid 1px #000;' align='center'>".moneyFormat($amount)."</td>
                    <td style='border:solid 1px #000;' align='center'>".$gst."</td>
                    <td style='border:solid 1px #000;' align='center'>".moneyFormat($sgstAmount)."</td>
                    <td style='border:solid 1px #000;' align='center'>".moneyFormat($cgstAmount)."</td>
                    <td style='border:solid 1px #000;' align='right'>".moneyFormat($total)."&nbsp;</td>
                </tr>";
                
                $n++;
            }
            $colspan = '8';
        }
        
        //steel
        elseif($rowData['type']=='Steel')
        {
            echo "
            <table cellpadding='0' cellspacing='0' width='100%' border='0' style='border-collapse: collapse; border: 1px solid #000; font-size:15px; font-family:cambria' >
            <thead>
                <tr>
                    <th style='border:solid 1px #000; padding: 5px; width:15px;' align='center'>No</th>
                    <th style='border:solid 1px #000; width:100px;' align='left'>&nbsp;HSN Code</th>
                    <th style='border:solid 1px #000; width:200px;' align='left'>&nbsp;Product Name</th>
                    <th style='border:solid 1px #000; width:80px;' align='center'>Detail</th>
                    <th style='border:solid 1px #000; width:80px;' align='center'>Qty</th>
                    <th style='border:solid 1px #000; width:100px;' align='center'>Price &#8377;</th>
                    <th style='border:solid 1px #000; width:120px;' align='center'>Amount &#8377;</th>
                    <th style='border:solid 1px #000; width:60px;' align='center'>GST %</th>
                    <th style='border:solid 1px #000; width:100px;' align='center'>SGST &#8377;</th>
                    <th style='border:solid 1px #000; width:100px;' align='center'>CGST &#8377;</th>
                    <th style='border:solid 1px #000; width:100px;' align='center'>Make</th>
                    <th style='border:solid 1px #000; width:140px;' align='right'>Total &#8377;&nbsp;</th>
                </tr>
            </thead>
            <tbody>";

            $productCount = count($rowData['purchase_table_data']);
            $n=1;
            $subTotal=0;
            for($i=0; $i<$productCount; $i++)
            {
                $amount = $rowData['purchase_table_data'][$i]['quantity'] * $rowData['purchase_table_data'][$i]['price'];
                $gst = $rowData['purchase_table_data'][$i]['sgst'] + $rowData['purchase_table_data'][$i]['cgst'];
                $sgstAmount = ($rowData['purchase_table_data'][$i]['sgst']/100) * $amount;
                $cgstAmount = ($rowData['purchase_table_data'][$i]['cgst']/100) * $amount;
                $total = $amount + $sgstAmount + $cgstAmount;
                $subTotal += $total;

                echo "
                <tr>
                    <td style='border:solid 1px #000; padding: 5px;' align='center'>".$n."</td>
                    <td style='border:solid 1px #000;' align='left'>&nbsp;".$rowData['purchase_table_data'][$i]['code']."</td>
                    <td style='border:solid 1px #000;' align='left'>&nbsp;".$rowData['purchase_table_data'][$i]['item']."</td>
                    <td style='border:solid 1px #000;' align='center'>".$rowData['purchase_table_data'][$i]['details']."</td>
                    <td style='border:solid 1px #000;' align='center'>".$rowData['purchase_table_data'][$i]['quantity']."&nbsp;".$rowData['purchase_table_data'][$i]['unit']."</td>
                    <td style='border:solid 1px #000;' align='center'>".moneyFormat($rowData['purchase_table_data'][$i]['price'])."</td>
                    <td style='border:solid 1px #000;' align='center'>".moneyFormat($amount)."</td>
                    <td style='border:solid 1px #000;' align='center'>".$gst."</td>
                    <td style='border:solid 1px #000;' align='center'>".moneyFormat($sgstAmount)."</td>
                    <td style='border:solid 1px #000;' align='center'>".moneyFormat($cgstAmount)."</td>
                    <td style='border:solid 1px #000;' align='center'>".$rowData['purchase_table_data'][$i]['make']."</td>
                    <td style='border:solid 1px #000;' align='right'>".moneyFormat($total)."&nbsp;</td>
                </tr>";
                $n++;
            }
            
            $colspan = '10';
        }
        
        //UPVC
        elseif($rowData['type']=='UPVC')
        {
            echo "
            <table cellpadding='0' cellspacing='0' width='100%' border='0' style='border-collapse: collapse; border: 1px solid #000; font-size:15px; font-family:cambria' >
            <thead>
                <tr>
                    <th style='border:solid 1px #000; padding: 5px; width:15px;' align='center'>No</th>
                    <th style='border:solid 1px #000; width:100px;' align='left'>&nbsp;HSN Code</th>
                    <th style='border:solid 1px #000; width:200px;' align='left'>&nbsp;Product Name</th>
                    <th style='border:solid 1px #000; width:100px;' align='center'>Qty</th>
                    <th style='border:solid 1px #000; width:100px;' align='center'>Price &#8377;</th>
                    <th style='border:solid 1px #000; width:120px;' align='center'>Amount &#8377;</th>
                    <th style='border:solid 1px #000; width:60px;' align='center'>GST %</th>
                    <th style='border:solid 1px #000; width:100px;' align='center'>SGST &#8377;</th>
                    <th style='border:solid 1px #000; width:100px;' align='center'>CGST &#8377;</th>
                    <th style='border:solid 1px #000; width:140px;' align='right'>Total &#8377;&nbsp;</th>
                </tr>
            </thead>
            <tbody>";

            $productCount = count($rowData['purchase_table_data']);
            $n=1;
            $subTotal=0;
            for($i=0; $i<$productCount; $i++)
            {
                $amount = $rowData['purchase_table_data'][$i]['quantity'] * $rowData['purchase_table_data'][$i]['price'];
                $gst = $rowData['purchase_table_data'][$i]['sgst'] + $rowData['purchase_table_data'][$i]['cgst'];
                $sgstAmount = ($rowData['purchase_table_data'][$i]['sgst']/100) * $amount;
                $cgstAmount = ($rowData['purchase_table_data'][$i]['cgst']/100) * $amount;
                $total = $amount + $sgstAmount + $cgstAmount;
                $subTotal += $total;

                echo "
                <tr>
                    <td style='border:solid 1px #000; padding: 5px;' align='center'>".$n."</td>
                    <td style='border:solid 1px #000;' align='left'>&nbsp;".$rowData['purchase_table_data'][$i]['code']."</td>
                    <td style='border:solid 1px #000;' align='left'>&nbsp;".$rowData['purchase_table_data'][$i]['item']." - (".$rowData['purchase_table_data'][$i]['upvc_type'].") W-".$rowData['purchase_table_data'][$i]['width']."; H-".$rowData['purchase_table_data'][$i]['height']."</td>
                    <td style='border:solid 1px #000;' align='center'>".$rowData['purchase_table_data'][$i]['quantity']."&nbsp;".$rowData['purchase_table_data'][$i]['unit']."</td>
                    <td style='border:solid 1px #000;' align='center'>".moneyFormat($rowData['purchase_table_data'][$i]['price'])."</td>
                    <td style='border:solid 1px #000;' align='center'>".moneyFormat($amount)."</td>
                    <td style='border:solid 1px #000;' align='center'>".$gst."</td>
                    <td style='border:solid 1px #000;' align='center'>".moneyFormat($sgstAmount)."</td>
                    <td style='border:solid 1px #000;' align='center'>".moneyFormat($cgstAmount)."</td>
                    <td style='border:solid 1px #000;' align='right'>".moneyFormat($total)."&nbsp;</td>
                </tr>";
                $n++;
            }
            
            $colspan = '8';
        }
        
        //door
        elseif($rowData['type']=='Door')
        {
            echo "
            <table cellpadding='0' cellspacing='0' width='100%' border='0' style='border-collapse: collapse; border: 1px solid #000; font-size:15px; font-family:cambria' >
            <thead>
                <tr>
                    <th style='border:solid 1px #000; padding: 5px; width:15px;' align='center'>No</th>
                    <th style='border:solid 1px #000; width:100px;' align='left'>&nbsp;HSN Code</th>
                    <th style='border:solid 1px #000; width:200px;' align='left'>&nbsp;Product Name</th>
                    <th style='border:solid 1px #000; width:100px;' align='center'>Size</th>
                    <th style='border:solid 1px #000; width:80px;' align='center'>Qty</th>
                    <th style='border:solid 1px #000; width:60px;' align='center'>CFT</th>
                    <th style='border:solid 1px #000; width:100px;' align='center'>Price &#8377;</th>
                    <th style='border:solid 1px #000; width:120px;' align='center'>Amount &#8377;</th>
                    <th style='border:solid 1px #000; width:60px;' align='center'>GST %</th>
                    <th style='border:solid 1px #000; width:100px;' align='center'>SGST &#8377;</th>
                    <th style='border:solid 1px #000; width:100px;' align='center'>CGST &#8377;</th>
                    <th style='border:solid 1px #000; width:140px;' align='right'>Total &#8377;&nbsp;</th>
                </tr>
            </thead>
            <tbody>";

            $productCount = count($rowData['purchase_table_data']);
            $n=1;
            $subTotal=0;
            for($i=0; $i<$productCount; $i++)
            {
                $amount = $rowData['purchase_table_data'][$i]['cft'] * $rowData['purchase_table_data'][$i]['price'];
                $gst = $rowData['purchase_table_data'][$i]['sgst'] + $rowData['purchase_table_data'][$i]['cgst'];
                $sgstAmount = ($rowData['purchase_table_data'][$i]['sgst']/100) * $amount;
                $cgstAmount = ($rowData['purchase_table_data'][$i]['cgst']/100) * $amount;
                $total = $amount + $sgstAmount + $cgstAmount;
                $subTotal += $total;

                echo "
                <tr>
                    <td style='border:solid 1px #000; padding: 5px;' align='center'>".$n."</td>
                    <td style='border:solid 1px #000;' align='left'>&nbsp;".$rowData['purchase_table_data'][$i]['code']."</td>
                    <td style='border:solid 1px #000;' align='left'>&nbsp;".$rowData['purchase_table_data'][$i]['item']."</td>
                    <td style='border:solid 1px #000;' align='center'>&nbsp;".$rowData['purchase_table_data'][$i]['size']."&nbsp;</td>
                    <td style='border:solid 1px #000;' align='center'>".$rowData['purchase_table_data'][$i]['quantity']." Nos</td>
                    <td style='border:solid 1px #000;' align='center'>".$rowData['purchase_table_data'][$i]['cft']."</td>
                    <td style='border:solid 1px #000;' align='center'>".moneyFormat($rowData['purchase_table_data'][$i]['price'])."</td>
                    <td style='border:solid 1px #000;' align='center'>".moneyFormat($amount)."</td>
                    <td style='border:solid 1px #000;' align='center'>".$gst."</td>
                    <td style='border:solid 1px #000;' align='center'>".moneyFormat($sgstAmount)."</td>
                    <td style='border:solid 1px #000;' align='center'>".moneyFormat($cgstAmount)."</td>
                    <td style='border:solid 1px #000;' align='right'>".moneyFormat($total)."&nbsp;</td>
                </tr>";
                $n++;
            }
            
            $colspan = '10';
        }
        
        if($rowData['type']=='Work') {
            if($rowData['trans_amount'] != '0') {
                $transGstAmt = $rowData['trans_amount']*($rowData['trans_tax']/100);
                echo "
                <tr>
                    <td style='border:solid 1px #000;'></td>
                    <td colspan='2' style='border:solid 1px #000; padding: 5px;' align='center'><b>Total Quantity</b></td>
                    <td style='border:solid 1px #000;' align='center'><b>".$rowData['work_ton']." Ton</b></td>
                    <td colspan='4' style='border:solid 1px #000;'></td>
                    <td style='border:solid 1px #000; padding: 5px;' align='center'><b>Sub Total</b></td>
                    <td style='border:solid 1px #000;' align='right'><b>".moneyFormat($subTotal)."</b>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan='".$colspan."'></td>
                    <td style='border:solid 1px #000; padding: 5px;' align='center'>Transport</td>
                    <td style='border:solid 1px #000;' align='right'>".moneyFormat($rowData['trans_amount'])."&nbsp;</td>
                </tr>
                <tr>
                    <td colspan='".$colspan."'></td>
                    <td style='border:solid 1px #000; padding: 5px;' align='center'>Transport GST ".$rowData['trans_tax']."%</td>
                    <td style='border:solid 1px #000;' align='right'>".moneyFormat($transGstAmt)."&nbsp;</td>
                </tr>
                <tr>
                    <td colspan='".$colspan."'></td>
                    <td style='border:solid 1px #000; padding: 5px;' align='center'><b>Grand Total</b></td>
                    <td style='border:solid 1px #000;' align='right'><b>".moneyFormat($rowData['grand_total'])."</b>&nbsp;</td>
                </tr>";
            }
            else {
                echo "
                <tr>
                    <td style='border:solid 1px #000;'></td>
                    <td colspan='2' style='border:solid 1px #000; padding: 5px;' align='center'><b>Total Quantity</b></td>
                    <td style='border:solid 1px #000;' align='center'><b>".$rowData['work_ton']." Ton</b></td>
                    <td colspan='4' style='border:solid 1px #000;'></td>
                    <td style='border:solid 1px #000; padding: 5px;' align='center'><b>Grand Total</b></td>
                    <td style='border:solid 1px #000;' align='right'><b>".moneyFormat($rowData['grand_total'])."</b>&nbsp;</td>
                </tr>";
            }
        }
        else {
            if($rowData['trans_amount'] != '0') {
                $transGstAmt = $rowData['trans_amount']*($rowData['trans_tax']/100);
                echo "
                <tr>
                    <td colspan='".$colspan."'></td>
                    <td style='border:solid 1px #000; padding: 5px;' align='center'><b>Sub Total</b></td>
                    <td style='border:solid 1px #000;' align='right'><b>".moneyFormat($subTotal)."</b>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan='".$colspan."'></td>
                    <td style='border:solid 1px #000; padding: 5px;' align='center'>Transport</td>
                    <td style='border:solid 1px #000;' align='right'>".moneyFormat($rowData['trans_amount'])."&nbsp;</td>
                </tr>
                <tr>
                    <td colspan='".$colspan."'></td>
                    <td style='border:solid 1px #000; padding: 5px;' align='center'>Transport GST ".$rowData['trans_tax']."%</td>
                    <td style='border:solid 1px #000;' align='right'>".moneyFormat($transGstAmt)."&nbsp;</td>
                </tr>
                <tr>
                    <td colspan='".$colspan."'></td>
                    <td style='border:solid 1px #000; padding: 5px;' align='center'><b>Grand Total</b></td>
                    <td style='border:solid 1px #000;' align='right'><b>".moneyFormat($rowData['grand_total'])."</b>&nbsp;</td>
                </tr>";
            }
            else {
                echo "
                <tr>
                    <td colspan='".$colspan."'></td>
                    <td style='border:solid 1px #000; padding: 5px;' align='center'><b>Grand Total</b></td>
                    <td style='border:solid 1px #000;' align='right'><b>".moneyFormat($rowData['grand_total'])."</b>&nbsp;</td>
                </tr>";
            }
        }
        echo "</tbody>
        </table><br>";
                
        if($rowData['project_details_data'][0]['project_name'] != 'Head Office') {
            $site = " Site";
        }?>
            
        <div class='invoice-footer'>
           <div class='MsoNormal'>
                <div class='col12 s12 m12 l6 leftspace lineheight'>
                    <table style='width:100%' style='word-spacing:1px'>
                        <tr><td colspan='3'><b>Term and Condition:</b> </td></tr>
                        <tr><td valign='top'>Our GSTIN</td><td>:</td><td><?php echo $rowData['project_details_data'][0]['gst_in']; ?></td><td></td></tr>
                        <tr><td valign='top'>Party GSTIN</td><td>:</td><td><?php echo $rowData['vendor_details_data'][0]['party_gst']; ?></td></tr>
                        <tr><td valign='top'>Payment</td><td>:</td><td><?php echo $rowData['payment']; ?></td></tr>
                        <tr><td valign='top'>Delivery</td><td>:</td><td>Door Delivery to our <?php echo $rowData['delivery_place'].$site; ?></td></tr>
                        <tr><td valign='top'>Loading & Vat</td><td>:</td><td><?php echo $rowData['vat']; ?></td></tr>
                        <tr><td colspan='3'><b>Please Supply by <?php echo $rowData['delivery_date']; ?> at our <?php echo $rowData['project_details_data'][0]['place'].$site; ?></b></td></tr>
                    </table>
                    <table style='width:100%'>
                        <tr>
                            <td style='width:38%' valign='top'>Site contact Person </td>
                            <td style='width:5%'>:</td>
                            <td style='width:30%'><?php echo $rowData['site_contact_person']; ?></td>
                            <td  style='width:5%'>:</td>
                            <td  style='width:22%'><?php echo $rowData['mobile']; ?></td>
                        </tr>
                        <tr>
                            <td valign='top'>Site address</td>
                            <td>:</td>
                            <td><b><?php echo $rowData['project_details_data'][0]['project_name']; ?></b></td>
                            <td></td><td></td><td></td>
                        </tr>
                        <tr>
                            <td></td><td></td>
                            <td style='width:300px'><?php echo $rowData['project_details_data'][0]['address']; ?></td>
                            <td></td><td></td><td></td>
                        </tr>
                    </table>
                </div>
                <div class='MsoNormal'>
                    <p>Thanking you,<br>Yours Sincerely<br></p>
                    <img src='<?php echo $rowData['user_data'][0]['sign']; ?>' width='auto' height='57' />
                    <p class='header'><?php echo $rowData['user_data'][0]['name']; ?><br><?php echo $rowData['user_data'][0]['designation']; ?></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html><?php
    }
}
?>