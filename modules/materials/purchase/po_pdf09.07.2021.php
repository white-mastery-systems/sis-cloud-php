<?php
$poNum = $_GET['id'];
$type = $_GET['type'];
$emp_id = (int)$_GET['emp_id'];

require_once '../../../vendor/autoload.php';
include "../../../include/conn.php";
include "../../../include/money_format.php";
$html;
$collectionMaster = $db->purchase_master;

$mpdfConfig = array(
				'mode' => 'utf-8', 
				'format' => 'A4',   
				'default_font_size' => 0, 
				'default_font' => '',   
				'margin_left' => 0, 
				'margin_right' => 0,  
				// 'mgt' => $headerTopMargin,     // 16 margin top
				// 'mgb' => $footerTopMargin,    	// margin bottom
				'margin_header' => 0,  
				'margin_footer' => 0,  
				'orientation' => 'P'  
			);


$mpdf = new \Mpdf\Mpdf($mpdfConfig);

$mpdf->mirrorMargins = 1;

$header = '<div align="center"><img src="../../../images/head.jpg" /></div>';
$footer = '<div align="center"><img src="../../../images/footer.jpg" /></div>';
$mpdf->SetHTMLHeader($header);
$mpdf->SetHTMLFooter($footer);
$mpdf->SetHTMLHeader($header,'E');
$mpdf->SetHTMLFooter($footer,'E');

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
        if($rowData['project_details_data'][0]['block_name'] != 'none') {
            $block = " Block ".$rowData['project_details_data'][0]['block_name'];
        }
        
        $html.= "<div style='padding-left:40px; padding-right:40px' align='center'>
        <div style='font-size:13px; font-family:cambria;'>
            <div align='right'>
                <b>".$rowData['po_date']."</b><br>
            </div>
            <b>".$rowData['order_type_short']." Number: ".$rowData['po_number']."</b><br>
            <b>To:</b><br>
            <b>M/S.".$rowData['vendor_details_data'][0]['company_name']."</b><br>
            <div style='width:250px; height:auto;'>".$rowData['vendor_details_data'][0]['address']."<br>Mob:".$rowData['vendor_details_data'][0]['mobile']."</div>
            <div align='center' style='width:100%;'>
                <b>Kind Attention: ".$rowData['vendor_details_data'][0]['contact_person']."</b>
            </div>";
        
            if($rowData['reference_no']!='' && $rowData['reference_date']!='') {
                $html.= "<b>Ref: </b>".$rowData['reference_no']." <b>Dated </b>on ".$rowData['reference_date']."<br>";
            } 
                
            $html.= "<div style='line-height:200%'><b>Sub: ".$rowData['order_type_short']." for " .$rowData['po_subject']. ".</b></div>
            We are pleased to place the ".$rowData['order_type']." as per the details mentioned below for our project <b> ".$rowData['project_details_data'][0]['project_name'].$block."</b> the address and the contact person are mentioned below.
        </div>
        <br>";
        
        //standard
        if($rowData['type']=='Standard' || $rowData['type']=='Work')
        {
            $html.= "
            <table cellpadding='0' cellspacing='0' width='100%' border='0' style='border-collapse: collapse; border: 1px solid #000; font-size:15px; font-family:cambria' >
            <thead>
                <tr>
                    <th style='border:solid 1px #000; padding: 5px; width:30px;' align='center'>No</th>
                    <th style='border:solid 1px #000; padding: 5px; width:100px;' align='left'>HSN Code</th>
                    <th style='border:solid 1px #000; padding: 5px; width:200px;' align='left'>Product Name</th>
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

                $html .= "
                <tr>
                    <td style='border:solid 1px #000; padding: 5px;' align='center'>".$n."</td>
                    <td style='border:solid 1px #000; padding: 5px;' align='left'>".$rowData['purchase_table_data'][$i]['code']."</td>
                    <td style='border:solid 1px #000; padding: 5px;' align='left'>".$rowData['purchase_table_data'][$i]['item']."<br>".$rowData['purchase_table_data'][$i]['desc']."</td>
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
            $html.= "
            <table cellpadding='0' cellspacing='0' width='100%' border='0' style='border-collapse: collapse; border: 1px solid #000; font-size:15px; font-family:cambria' >
            <thead>
                <tr>
                    <th style='border:solid 1px #000; padding: 5px; width:30px;' align='center'>No</th>
                    <th style='border:solid 1px #000; padding: 5px; width:100px;' align='left'>HSN Code</th>
                    <th style='border:solid 1px #000; padding: 5px; width:200px;' align='left'>Product Name</th>
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

                $html .= "
                <tr>
                    <td style='border:solid 1px #000; padding: 5px;' align='center'>".$n."</td>
                    <td style='border:solid 1px #000; padding: 5px;' align='left'>".$rowData['purchase_table_data'][$i]['code']."</td>
                    <td style='border:solid 1px #000; padding: 5px;' align='left'>".$rowData['purchase_table_data'][$i]['item']."<br>".$rowData['purchase_table_data'][$i]['desc']."</td>
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
            $html.= "
            <table cellpadding='0' cellspacing='0' width='100%' border='0' style='border-collapse: collapse; border: 1px solid #000; font-size:15px; font-family:cambria' >
            <thead>
                <tr>
                    <th style='border:solid 1px #000; padding: 5px; width:30px;' align='center'>No</th>
                    <th style='border:solid 1px #000; padding: 5px; width:100px;' align='left'>HSN Code</th>
                    <th style='border:solid 1px #000; padding: 5px; width:200px;' align='left'>Product Name</th>
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

                $html .= "
                <tr>
                    <td style='border:solid 1px #000; padding: 5px;' align='center'>".$n."</td>
                    <td style='border:solid 1px #000; padding: 5px;' align='left'>".$rowData['purchase_table_data'][$i]['code']."</td>
                    <td style='border:solid 1px #000; padding: 5px;' align='left'>".$rowData['purchase_table_data'][$i]['item']."<br>".$rowData['purchase_table_data'][$i]['desc']." - (".$rowData['purchase_table_data'][$i]['upvc_type'].") W-".$rowData['purchase_table_data'][$i]['width']."; H-".$rowData['purchase_table_data'][$i]['height']."</td>
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
			
            $html.= "
            <table cellpadding='0' cellspacing='0' width='100%' border='0' style='border-collapse: collapse; border: 1px solid #000; font-size:15px; font-family:cambria' >
            <thead>
                <tr>
                    <th style='border:solid 1px #000; padding: 5px; width:30px;' align='center'>No</th>
                    <th style='border:solid 1px #000; padding: 5px; width:100px;' align='left'>HSN Code</th>
                    <th style='border:solid 1px #000; padding: 5px; width:200px;' align='left'>Product Name</th>
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

                $html .= "
                <tr>
                    <td style='border:solid 1px #000; padding: 5px;' align='center'>".$n."</td>
                    <td style='border:solid 1px #000; padding: 5px;' align='left'>".$rowData['purchase_table_data'][$i]['code']."</td>
                    <td style='border:solid 1px #000; padding: 5px;' align='left'>".$rowData['purchase_table_data'][$i]['item']."<br>".$rowData['purchase_table_data'][$i]['desc']."</td>
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
                $html.= "
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
                $html.= "
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
                $html.= "
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
			
			else if($rowData['plain_amount'])
			{
			if($rowData['plain_amount'] != '0') {
				$plainval = $rowData['plain_amount'] * $rowData['sum_cft'];
                $plainGstAmt = $plainval *($rowData['plain_tax']/100);
                $html.= "
				<tr>
                    <td colspan='".$colspan."'></td>
                    <td style='border:solid 1px #000; padding: 5px;' align='center'><b>Sub Total</b></td>
                    <td style='border:solid 1px #000;' align='right'><b>".moneyFormat($subTotal)."</b>&nbsp;</td>
                </tr>
				<tr>
                    <td colspan='".$colspan."'></td>
                    <td style='border:solid 1px #000; padding: 5px;' align='center'>Total CFT</td>
                    <td style='border:solid 1px #000;' align='right'>".$rowData['sum_cft']."&nbsp;</td>
                </tr>
                <tr>
                    <td colspan='".$colspan."'></td>
                    <td style='border:solid 1px #000; padding: 5px;' align='center'>Plaining Charges</td>
                    <td style='border:solid 1px #000;' align='right'>".moneyFormat($rowData['plain_amount'])."&nbsp;</td>
                </tr>
                <tr>
                    <td colspan='".$colspan."'></td>
                    <td style='border:solid 1px #000; padding: 5px;' align='center'>Plaining GST ".$rowData['plain_tax']."%</td>
                    <td style='border:solid 1px #000;' align='right'>".moneyFormat($plainGstAmt)."&nbsp;</td>
                </tr>
                <tr>
                    <td colspan='".$colspan."'></td>
                    <td style='border:solid 1px #000; padding: 5px;' align='center'><b>Grand Total</b></td>
                    <td style='border:solid 1px #000;' align='right'><b>".moneyFormat($rowData['grand_total'])."</b>&nbsp;</td>
                </tr>";
            }
			
			
			
			else if($rowData['plain_amount'] != '0' && $rowData['trans_amount'] != '0' ) {
				$plainval = $rowData['plain_amount'] * $rowData['sum_cft'];
                $plainGstAmt = $plainval *($rowData['plain_tax']/100);
                $html.= "
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
                    <td style='border:solid 1px #000; padding: 5px;' align='center'>Total CFT</td>
                    <td style='border:solid 1px #000;' align='right'>".$rowData['sum_cft']."&nbsp;</td>
                </tr>
                 <tr>
                    <td colspan='".$colspan."'></td>
                    <td style='border:solid 1px #000; padding: 5px;' align='center'>Plaining Charges</td>
                    <td style='border:solid 1px #000;' align='right'>".moneyFormat($rowData['plain_amount'])."&nbsp;</td>
                </tr>
                <tr>
                    <td colspan='".$colspan."'></td>
                    <td style='border:solid 1px #000; padding: 5px;' align='center'>Plaining GST ".$rowData['plain_tax']."%</td>
                    <td style='border:solid 1px #000;' align='right'>".moneyFormat($plainGstAmt)."&nbsp;</td>
                </tr>
                <tr>
                    <td colspan='".$colspan."'></td>
                    <td style='border:solid 1px #000; padding: 5px;' align='center'><b>Grand Total</b></td>
                    <td style='border:solid 1px #000;' align='right'><b>".moneyFormat($rowData['grand_total'])."</b>&nbsp;</td>
                </tr>";
            }
				
			}
				
				
			
			
            else {
                $html.= "
                <tr>
                    <td colspan='".$colspan."'></td>
                    <td style='border:solid 1px #000; padding: 5px;' align='center'><b>Grand Total</b></td>
                    <td style='border:solid 1px #000;' align='right'><b>".moneyFormat($rowData['grand_total'])."</b>&nbsp;</td>
                </tr>";
            }
        }

        $html.= "</tbody></table><br>";

        if($rowData['project_details_data'][0]['project_name'] != 'Head Office') {
            $site = " Site";
        }

        $html.= "
        <table style='width:100%' style='word-spacing:1px; font-size:13px; font-family:cambria;'>
            <tr><td colspan='3'><b>Term and Condition:</b></td></tr>
            <tr>
                <td valign='top'>Our GSTIN</td>
                <td>:</td>
                <td>".$rowData['project_details_data'][0]['gst_in']."</td>
                <td></td>
            </tr>
            <tr>
                <td valign='top'>Party GSTIN</td>
                <td>:</td>
                <td>".$rowData['vendor_details_data'][0]['party_gst']."</td>
            </tr>
            <tr>
                <td valign='top'>Payment</td>
                <td>:</td>
                <td>".$rowData['payment']."</td>
            </tr>
            <tr>
                <td valign='top'>Delivery</td>
                <td>:</td>
                <td>Door Delivery to our " .$rowData['delivery_place'].$site.".</td>
            </tr>
            <tr>
                <td valign='top'>Loading & Transport </td>
                <td>:</td>
                <td>".$rowData['vat']."</td>
            </tr>
            <tr>
                <td colspan='3'><b>Please Supply by " .$rowData['delivery_date']. " at our ".$rowData['project_details_data'][0]['place'].$site."</b></td>
            </tr>
            <tr><td colspan='3'><b>Note: Kindly Attach Copy of the ".$rowData['order_type_short']." Along with your Invoice for Payment Purpose</b></td></tr>
        </table>

        <table style='width:100%; font-size:13px; font-family:cambria;'>
            <tr>
                <td style='width:38%' valign='top'><b>Site contact Person </b></td>
                <td style='width:5%'>:</td>
                <td style='width:30%'><b>".$rowData['site_contact_person']."</b></td>
                <td  style='width:5%'>:</td>
                <td  style='width:22%'><b>".$rowData['mobile']."</b></td>
            </tr>
            <tr>
                <td valign='top'><b>Site address</b></td>
                <td>:</td>
                <td><b>".$rowData['project_details_data'][0]['project_name']. "</b></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>".$rowData['project_details_data'][0]['address']."</td>
                <td></td>
                <td></td>
                <td></td>
            </tr></table>";
		 $html.= "<table style='width:100%; font-size:13px; font-family:cambria;'>";
           
			if($rowData['note'])
			{
			    $html.= "<tr>
                <td valign='top' style='width:38%' ><b>Note</b></td>  
				<td valign='top' style='width:5%'>:</td>
              <td valign='top'>".$rowData['note']."</td>  
            </tr>";	
			}
				
         $html.= "</table>
			 
         <div class='col12 s12 m6 l6 left leftspace lineheight'>
         <img src=".$rowData['user_data'][0]['digital_sign']." />
         </div></div>";

        $mpdf->AddPage('','A4','','',0,0,0,37,45,0);
        $mpdf->WriteHTML($html);
        $mpdf->SetDisplayMode('fullpage');
       if($type=='upload'){
            $mpdf->Output($poNum.'.pdf');
        }
        else{
            $mpdf->Output($poNum.'.pdf', 'D');
        }
    }
}
?>