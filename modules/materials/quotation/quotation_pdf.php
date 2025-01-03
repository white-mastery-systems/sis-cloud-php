<?php
error_reporting(0);
$quotNum = $_GET['id'];
$type = $_GET['type'];

require_once '../../../vendor/autoload.php';
include "../../../include/conn.php";
include "../../../include/money_format.php";

$collectionIndent = $db->indent_master;
$collectionMaster = $db->quotation_master;
$collectionsignin = $db->signintable;

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

$html = "";

$cursor = $collectionMaster->aggregate(array(
        array('$match' => array(
            "quot_number" => $quotNum
        )),
        array( '$lookup' => array(
            'from' => 'indent_master',
            'localField' => 'prf_number',
            'foreignField' => 'prf_number',
            'as' => 'indent_data'
        )),
        array( '$lookup' => array(
            'from' => 'indent_table',
            'localField' => 'prf_number',
            'foreignField' => 'prf_number',
            'as' => 'indent_table_data'
        )),
        array( '$lookup' => array(
            'from' => 'signintable',
            'localField' => 'approved_by',
            'foreignField' => 'emp_id',
            'as' => 'admin_data'
        )),
        array( '$lookup' => array(
            'from' => 'project_details',
            'localField' => 'project_id',
            'foreignField' => '_id',
            'as' => 'project_details_data'
        ))
));
if($cursor)
{
    foreach($cursor as $rowData)
    {
        $cursorUser = $collectionIndent->aggregate(array(
            array('$match' => array(
                "prf_number" => $rowData['indent_data'][0]['prf_number']
            )),
            array( '$lookup' => array(
                'from' => 'signintable',
                'localField' => 'emp_id',
                'foreignField' => 'emp_id',
                'as' => 'user_data'
            )),
        ));
        if($cursorUser)
        {
            foreach($cursorUser as $rowUserData)
            {
                $empId = $rowUserData['user_data'][0]['emp_id'];
                $empName = $rowUserData['user_data'][0]['name'];
                $empDesignation = $rowUserData['user_data'][0]['designation'];
            }
        }
        
        if($rowData['project_details_data'][0]['block_name'] != 'none') {
            $block = " - ".$rowData['project_details_data'][0]['block_name']." Block";
        }
        
        $html.= "
<div style='padding-left:40px; padding-right:40px' align='center'>
            <h3 align='center'>QUOTATION FORM</h3>
            <table cellpadding='0' cellspacing='0' width='100%' border='0' style='border-collapse: collapse; border: 1px solid #000; font-size:15px;' >
            <tbody>
            <tr>
                <td style='border:solid 1px #000; padding: 5px;' width='50%'>Name</td>
                <td style='border:solid 1px #000; padding: 5px;'>".$empId." - ".$empName."</td>
            </tr>
            <tr>
                <td style='border:solid 1px #000; padding: 5px;'>Designation</td>
                <td style='border:solid 1px #000; padding: 5px;'>".$empDesignation."</td>
            </tr>
            <tr>
                <td style='border:solid 1px #000; padding: 5px;'>Indent No & Date</td>
                <td style='border:solid 1px #000; padding: 5px;'>".$rowData['indent_data'][0]['prf_number']." - ".$rowData['indent_data'][0]['prf_date']."</td>
            </tr>
            <tr>
                <td style='border:solid 1px #000; padding: 5px;'>Quotation No & Date</td>
                <td style='border:solid 1px #000; padding: 5px;'>".$rowData['quot_number']." - ".$rowData['quot_date']."</td>
            </tr>
            <tr>
                <td style='border:solid 1px #000; padding: 5px;'>Site</td>
                <td style='border:solid 1px #000; padding: 5px;'><b>".$rowData['project_details_data'][0]['project_name'].$block."</b></td>
            </tr>
            <tr>
                <td style='border:solid 1px #000; padding: 5px;'>Purpose</td>
                <td style='border:solid 1px #000; padding: 5px;'>".$rowData['indent_data'][0]['purpose']."</td>
            </tr>
            </tbody>
            </table>
          
            <h3 align='center'>INDENT PRODUCT LIST</h3>
            <table cellpadding='0' cellspacing='0' width='100%' border='0' style='border-collapse: collapse; border: 1px solid #000; font-size:15px;' >";
        
        if($rowData['type']=='Standard' || $rowData['type']=='Work')
        {
            $html.= "
            <thead>
                <tr>
                    <th style='border:solid 1px #000; padding: 5px; width:30px;' align='center'>No</th>
                    <th style='border:solid 1px #000; padding: 5px; width:200px;' align='left'>Product Name</th>
                    <th style='border:solid 1px #000; padding: 5px; width:100px;' align='center'>Qty</th>
                    <th style='border:solid 1px #000; padding: 5px; width:100px;' align='center'>Approx Price &#8377;</th>
                    <th style='border:solid 1px #000; padding: 5px; width:120px;' align='center'>Item Required Date</th>
                    <th style='border:solid 1px #000; padding: 5px; width:60px;' align='center'>Stock</th>
                    <th style='border:solid 1px #000; padding: 5px; width:100px;' align='right'>Total &#8377;</th>
                </tr>
            </thead>
            <tbody>";
        
            $productCount = count($rowData['indent_table_data']);
            $n=1;
            for($i=0; $i<$productCount; $i++)
            {
                $total = $rowData['indent_table_data'][$i]['quantity'] * $rowData['indent_table_data'][$i]['price'];
                $html .= "
                <tr>
                    <td style='border:solid 1px #000; padding: 5px;' align='center'>".$n."</td>
                    <td style='border:solid 1px #000; padding: 5px;' align='left'>".$rowData['indent_table_data'][$i]['item']."</td>
                    <td style='border:solid 1px #000;' align='center'>".$rowData['indent_table_data'][$i]['quantity']."&nbsp;".$rowData['indent_table_data'][$i]['unit']."</td>
                    <td style='border:solid 1px #000;' align='center'>".moneyFormat($rowData['indent_table_data'][$i]['price'])."</td>
                    <td style='border:solid 1px #000;' align='center'>".$rowData['indent_table_data'][$i]['required_date']."</td>
                    <td style='border:solid 1px #000;' align='center'>".$rowData['indent_table_data'][$i]['stock_details']['block_available']."&nbsp;".$rowData['indent_table_data'][$i]['unit']."</td>
                    <td style='border:solid 1px #000;' align='right'>".moneyFormat($total)."&nbsp;</td>
                </tr>";
                $n++;
            }
            $colspan = '5';
        }
        elseif($rowData['type']=='Steel')
        {
            $html.= "
            <thead>
                <tr>
                    <th style='border:solid 1px #000; padding: 5px; width:30px;' align='center'>No</th>
                    <th style='border:solid 1px #000; padding: 5px; width:200px;' align='left'>Product Name</th>
                    <th style='border:solid 1px #000; padding: 5px; width:100px;' align='center'>Details</th>
                    <th style='border:solid 1px #000; padding: 5px; width:100px;' align='center'>Qty</th>
                    <th style='border:solid 1px #000; padding: 5px; width:100px;' align='center'>Approx Price &#8377;</th>
                    <th style='border:solid 1px #000; padding: 5px; width:120px;' align='center'>Item Required Date</th>
                    <th style='border:solid 1px #000; padding: 5px; width:60px;' align='center'>Stock</th>
                    <th style='border:solid 1px #000; padding: 5px; width:100px;' align='center'>Make</th>
                    <th style='border:solid 1px #000; padding: 5px; width:100px;' align='right'>Total &#8377;</th>
                </tr>
            </thead>
            <tbody>";
        
            $productCount = count($rowData['indent_table_data']);
            $n=1;
            for($i=0; $i<$productCount; $i++)
            {
                $total = $rowData['indent_table_data'][$i]['quantity'] * $rowData['indent_table_data'][$i]['price'];
                $html .= "
                <tr>
                    <td style='border:solid 1px #000; padding: 5px;' align='center'>".$n."</td>
                    <td style='border:solid 1px #000; padding: 5px;' align='left'>".$rowData['indent_table_data'][$i]['item']."</td>
                    <td style='border:solid 1px #000; padding: 5px;' align='center'>".$rowData['indent_table_data'][$i]['details']."</td>
                    <td style='border:solid 1px #000;' align='center'>".$rowData['indent_table_data'][$i]['quantity']."&nbsp;".$rowData['indent_table_data'][$i]['unit']."</td>
                    <td style='border:solid 1px #000;' align='center'>".moneyFormat($rowData['indent_table_data'][$i]['price'])."</td>
                    <td style='border:solid 1px #000;' align='center'>".$rowData['indent_table_data'][$i]['required_date']."</td>
                    <td style='border:solid 1px #000;' align='center'>".$rowData['indent_table_data'][$i]['stock_details']['block_available']."&nbsp;".$rowData['indent_table_data'][$i]['unit']."</td>
                    <td style='border:solid 1px #000; padding: 5px;' align='center'>".$rowData['indent_table_data'][$i]['make']."</td>
                    <td style='border:solid 1px #000;' align='right'>".moneyFormat($total)."&nbsp;</td>
                </tr>";
                $n++;
            }
            $colspan = '7';
        }
        elseif($rowData['type']=='UPVC')
        {
            $html.= "
            <thead>
                <tr>
                    <th style='border:solid 1px #000; padding: 5px; width:30px;' align='center'>No</th>
                    <th style='border:solid 1px #000; padding: 5px; width:200px;' align='left'>Product Name</th>
                    <th style='border:solid 1px #000; padding: 5px; width:100px;' align='center'>Qty</th>
                    <th style='border:solid 1px #000; padding: 5px; width:100px;' align='center'>Approx Price &#8377;</th>
                    <th style='border:solid 1px #000; padding: 5px; width:120px;' align='center'>Item Required Date</th>
                    <th style='border:solid 1px #000; padding: 5px; width:60px;' align='center'>Stock</th>
                    <th style='border:solid 1px #000; padding: 5px; width:100px;' align='right'>Total &#8377;</th>
                </tr>
            </thead>
            <tbody>";
        
            $productCount = count($rowData['indent_table_data']);
            $n=1;
            for($i=0; $i<$productCount; $i++)
            {
                $total = $rowData['indent_table_data'][$i]['quantity'] * $rowData['indent_table_data'][$i]['price'];
                $html .= "
                <tr>
                    <td style='border:solid 1px #000; padding: 5px;' align='center'>".$n."</td>
                    <td style='border:solid 1px #000; padding: 5px;' align='left'>".$rowData['indent_table_data'][$i]['item']." - (".$rowData['indent_table_data'][$i]['upvc_type'].") W-".$rowData['indent_table_data'][$i]['width']."; H-".$rowData['indent_table_data'][$i]['height']."</td>
                    <td style='border:solid 1px #000;' align='center'>".$rowData['indent_table_data'][$i]['quantity']."&nbsp;".$rowData['indent_table_data'][$i]['unit']."</td>
                    <td style='border:solid 1px #000;' align='center'>".moneyFormat($rowData['indent_table_data'][$i]['price'])."</td>
                    <td style='border:solid 1px #000;' align='center'>".$rowData['indent_table_data'][$i]['required_date']."</td>
                    <td style='border:solid 1px #000;' align='center'>".$rowData['indent_table_data'][$i]['stock_details']['block_available']."&nbsp;".$rowData['indent_table_data'][$i]['unit']."</td>
                    <td style='border:solid 1px #000;' align='right'>".moneyFormat($total)."&nbsp;</td>
                </tr>";
                $n++;
            }
            $colspan = '5';
        }
        elseif($rowData['type']=='Door')
        {
            $html.= "
            <thead>
                <tr>
                    <th style='border:solid 1px #000; padding: 5px; width:30px;' align='center'>No</th>
                    <th style='border:solid 1px #000; padding: 5px; width:200px;' align='left'>Product Name</th>
                    <th style='border:solid 1px #000; padding: 5px; width:100px;' align='center'>Size</th>
                    <th style='border:solid 1px #000; padding: 5px; width:100px;' align='center'>Qty</th>
                    <th style='border:solid 1px #000; padding: 5px; width:100px;' align='center'>CFT</th>
                    <th style='border:solid 1px #000; padding: 5px; width:100px;' align='center'>Approx Price &#8377;</th>
                    <th style='border:solid 1px #000; padding: 5px; width:120px;' align='center'>Item Required Date</th>
                    <th style='border:solid 1px #000; padding: 5px; width:60px;' align='center'>Stock</th>
                    <th style='border:solid 1px #000; padding: 5px; width:100px;' align='right'>Total &#8377;</th>
                </tr>
            </thead>
            <tbody>";
        
            $productCount = count($rowData['indent_table_data']);
            $n=1;
            for($i=0; $i<$productCount; $i++)
            {
                $total = $rowData['indent_table_data'][$i]['cft'] * $rowData['indent_table_data'][$i]['price'];
                $html .= "
                <tr>
                    <td style='border:solid 1px #000; padding: 5px;' align='center'>".$n."</td>
                    <td style='border:solid 1px #000; padding: 5px;' align='left'>".$rowData['indent_table_data'][$i]['item']."</td>
                    <td style='border:solid 1px #000; padding: 5px;' align='center'>".$rowData['indent_table_data'][$i]['size']."</td>
                    <td style='border:solid 1px #000; padding: 5px;' align='center'>".$rowData['indent_table_data'][$i]['quantity']."&nbsp; Nos</td>
                    <td style='border:solid 1px #000; padding: 5px;' align='center'>".$rowData['indent_table_data'][$i]['cft']."</td>
                    <td style='border:solid 1px #000;' align='center'>".moneyFormat($rowData['indent_table_data'][$i]['price'])."</td>
                    <td style='border:solid 1px #000; padding: 5px;' align='center'>".$rowData['indent_table_data'][$i]['required_date']."</td>
                    <td style='border:solid 1px #000; padding: 5px;' align='center'>".$rowData['indent_table_data'][$i]['stock_details']['block_available']."&nbsp;Nos</td>
                    <td style='border:solid 1px #000; padding: 5px;' align='right'>".moneyFormat($total)."&nbsp;</td>
                </tr>";
                $n++;
            }
            $colspan = '7';
        }
            
        $html.= "
            <tr>
                <td colspan='".$colspan."'></td>
                <td style='border:solid 1px #000; padding: 5px;' align='center'><b>Grand Total</b></td>
                <td style='border:solid 1px #000;' align='right'><b>".moneyFormat($rowData['indent_data'][0]['grand_total'])."</b>&nbsp;</td>
            </tr>
            </tbody>
            </table>";
        
        if($rowData['final_vendor'])
        {
            $html .= "<h3 align='center'>FINAL PRODUCT LIST</h3>
            <table cellpadding='0' cellspacing='0' width='100%' border='0'>
            <tr><td>
            
            <table width='100%' style='font-size:20px;'>
                <tr>
                    <td width='50%'><b>Vendor Name:</b> ".$rowData['final_vendor'][0]['vendor_name']."</td>
                    <td><b>Reason:</b> ".$rowData['final_vendor'][0]['reason']."</td>
                </tr>
            </table>";
            
            $quotProCount = count($rowData['final_vendor'][0]['table_items']);
            if($rowData['type']=='Standard' || $rowData['type']=='Work')
            {
                $html.= "
                <table width='100%' style='border-collapse: collapse; border: 1px solid #000; font-size:15px;' >
                <thead>
                    <tr>
                        <th style='border:solid 1px #000; padding: 5px; width:30px;' align='center'>No</th>
                        <th style='border:solid 1px #000; padding: 5px; width:200px;' align='left'>Product Name</th>
                        <th style='border:solid 1px #000; width:100px;' align='center'>Qty</th>
                        <th style='border:solid 1px #000; width:100px;' align='center'>Price &#8377;</th>
                        <th style='border:solid 1px #000; width:120px;' align='center'>Amount &#8377;</th>
                        <th style='border:solid 1px #000; width:60px;' align='center'>GST %</th>
                        <th style='border:solid 1px #000; width:100px;' align='center'>GST Amount &#8377;</th>
                        <th style='border:solid 1px #000; padding: 5px; width:120px;' align='center'>Item Required Date</th>
                        <th style='border:solid 1px #000; padding: 5px; width:60px;' align='center'>Stock</th>
                        <th style='border:solid 1px #000; width:140px;' align='right'>Total &#8377;&nbsp;</th>
                    </tr>
                </thead>
                <tbody>";
                $k=1;
                for($i=0; $i<$quotProCount; $i++)
                {
                    $html .= "
                    <tr>
                        <td style='border:solid 1px #000; padding: 5px;' align='center'>".$k."</td>
                        <td style='border:solid 1px #000; padding: 5px;' align='left'>".$rowData['final_vendor'][0]['table_items'][$i]['item']."</td>
                        <td style='border:solid 1px #000;' align='center'>".$rowData['final_vendor'][0]['table_items'][$i]['quantity']."&nbsp;".$rowData['final_vendor'][0]['table_items'][$i]['unit']."</td>
                        <td style='border:solid 1px #000;' align='center'>".moneyFormat($rowData['final_vendor'][0]['table_items'][$i]['price'])."</td>
                        <td style='border:solid 1px #000;' align='center'>".moneyFormat($rowData['final_vendor'][0]['table_items'][$i]['amount'])."</td>
                        <td style='border:solid 1px #000;' align='center'>".$rowData['final_vendor'][0]['table_items'][$i]['gst']."</td>
                        <td style='border:solid 1px #000;' align='center'>".moneyFormat($rowData['final_vendor'][0]['table_items'][$i]['gst_amount'])."</td>
                        <td style='border:solid 1px #000;' align='center'>".$rowData['final_vendor'][0]['table_items'][$i]['required_date']."</td>
                        <td style='border:solid 1px #000;' align='center'>".$rowData['final_vendor'][0]['table_items'][$i]['available_stock']."&nbsp;".$rowData['final_vendor'][0]['table_items'][$i]['unit']."</td>
                        <td style='border:solid 1px #000;' align='right'>".moneyFormat($rowData['final_vendor'][0]['table_items'][$i]['total'])."&nbsp;</td>
                    </tr>";
                    $k++;
                }
                $colspan = '8';
            }
            elseif($rowData['type']=='Steel')
            {
                $html.= "
                <table width='100%' style='border-collapse: collapse; border: 1px solid #000; font-size:15px;' >
                <thead>
                    <tr>
                        <th style='border:solid 1px #000; padding: 5px; width:30px;' align='center'>No</th>
                        <th style='border:solid 1px #000; padding: 5px; width:200px;' align='left'>Product Name</th>
                        <th style='border:solid 1px #000; width:80px;' align='center'>Details</th>
                        <th style='border:solid 1px #000; width:80px;' align='center'>Qty</th>
                        <th style='border:solid 1px #000; width:100px;' align='center'>Price &#8377;</th>
                        <th style='border:solid 1px #000; width:120px;' align='center'>Amount &#8377;</th>
                        <th style='border:solid 1px #000; width:60px;' align='center'>GST %</th>
                        <th style='border:solid 1px #000; width:100px;' align='center'>GST Amount &#8377;</th>
                        <th style='border:solid 1px #000; padding: 5px; width:120px;' align='center'>Item Required Date</th>
                        <th style='border:solid 1px #000; padding: 5px; width:60px;' align='center'>Stock</th>
                        <th style='border:solid 1px #000; width:100px;' align='center'>Make</th>
                        <th style='border:solid 1px #000; width:140px;' align='right'>Total &#8377;&nbsp;</th>
                    </tr>
                </thead>
                <tbody>";
                $k=1;
                for($i=0; $i<$quotProCount; $i++)
                {
                    $html .= "
                    <tr>
                        <td style='border:solid 1px #000; padding: 5px;' align='center'>".$k."</td>
                        <td style='border:solid 1px #000; padding: 5px;' align='left'>".$rowData['final_vendor'][0]['table_items'][$i]['item']."</td>
                        <td style='border:solid 1px #000; padding: 5px;' align='center'>".$rowData['final_vendor'][0]['table_items'][$i]['details']."</td>
                        <td style='border:solid 1px #000;' align='center'>".$rowData['final_vendor'][0]['table_items'][$i]['quantity']."&nbsp;".$rowData['final_vendor'][0]['table_items'][$i]['unit']."</td>
                        <td style='border:solid 1px #000;' align='center'>".moneyFormat($rowData['final_vendor'][0]['table_items'][$i]['price'])."</td>
                        <td style='border:solid 1px #000;' align='center'>".moneyFormat($rowData['final_vendor'][0]['table_items'][$i]['amount'])."</td>
                        <td style='border:solid 1px #000;' align='center'>".$rowData['final_vendor'][0]['table_items'][$i]['gst']."</td>
                        <td style='border:solid 1px #000;' align='center'>".moneyFormat($rowData['final_vendor'][0]['table_items'][$i]['gst_amount'])."</td>
                        <td style='border:solid 1px #000;' align='center'>".$rowData['final_vendor'][0]['table_items'][$i]['required_date']."</td>
                        <td style='border:solid 1px #000;' align='center'>".$rowData['final_vendor'][0]['table_items'][$i]['available_stock']['block_available']."&nbsp;".$rowData['final_vendor'][0]['table_items'][$i]['unit']."</td>
                        <td style='border:solid 1px #000; padding: 5px;' align='center'>".$rowData['final_vendor'][0]['table_items'][$i]['make']."</td>
                        <td style='border:solid 1px #000;' align='right'>".moneyFormat($rowData['final_vendor'][0]['table_items'][$i]['total'])."&nbsp;</td>
                    </tr>";
                    $k++;
                }
                $colspan = '10';
            }
            elseif($rowData['type']=='UPVC')
            {
                $html.= "
                <table width='100%' style='border-collapse: collapse; border: 1px solid #000; font-size:15px;' >
                <thead>
                    <tr>
                        <th style='border:solid 1px #000; padding: 5px; width:30px;' align='center'>No</th>
                        <th style='border:solid 1px #000; padding: 5px; width:200px;' align='left'>Product Name</th>
                        <th style='border:solid 1px #000; width:100px;' align='center'>Qty</th>
                        <th style='border:solid 1px #000; width:100px;' align='center'>Price &#8377;</th>
                        <th style='border:solid 1px #000; width:120px;' align='center'>Amount &#8377;</th>
                        <th style='border:solid 1px #000; width:60px;' align='center'>GST %</th>
                        <th style='border:solid 1px #000; width:100px;' align='center'>GST Amount &#8377;</th>
                        <th style='border:solid 1px #000; padding: 5px; width:120px;' align='center'>Item Required Date</th>
                        <th style='border:solid 1px #000; padding: 5px; width:60px;' align='center'>Stock</th>
                        <th style='border:solid 1px #000; width:140px;' align='right'>Total &#8377;&nbsp;</th>
                    </tr>
                </thead>
                <tbody>";
                $k=1;
                for($i=0; $i<$quotProCount; $i++)
                {
                    $html .= "
                    <tr>
                        <td style='border:solid 1px #000; padding: 5px;' align='center'>".$k."</td>
                        <td style='border:solid 1px #000; padding: 5px;' align='left'>".$rowData['final_vendor'][0]['table_items'][$i]['item']." - (".$rowData['final_vendor'][0]['table_items'][$i]['upvc_type'].") W-".$rowData['final_vendor'][0]['table_items'][$i]['width']."; H-".$rowData['final_vendor'][0]['table_items'][$i]['height']."</td>
                        <td style='border:solid 1px #000;' align='center'>".$rowData['final_vendor'][0]['table_items'][$i]['quantity']."&nbsp;".$rowData['final_vendor'][0]['table_items'][$i]['unit']."</td>
                        <td style='border:solid 1px #000;' align='center'>".moneyFormat($rowData['final_vendor'][0]['table_items'][$i]['price'])."</td>
                        <td style='border:solid 1px #000;' align='center'>".moneyFormat($rowData['final_vendor'][0]['table_items'][$i]['amount'])."</td>
                        <td style='border:solid 1px #000;' align='center'>".$rowData['final_vendor'][0]['table_items'][$i]['gst']."</td>
                        <td style='border:solid 1px #000;' align='center'>".moneyFormat($rowData['final_vendor'][0]['table_items'][$i]['gst_amount'])."</td>
                        <td style='border:solid 1px #000;' align='center'>".$rowData['final_vendor'][0]['table_items'][$i]['required_date']."</td>
                        <td style='border:solid 1px #000;' align='center'>".$rowData['final_vendor'][0]['table_items'][$i]['stock_details']['block_available']."&nbsp;".$rowData['final_vendor'][0]['table_items'][$i]['unit']."</td>
                        <td style='border:solid 1px #000;' align='right'>".moneyFormat($rowData['final_vendor'][0]['table_items'][$i]['total'])."&nbsp;</td>
                    </tr>";
                    $k++;
                }
                $colspan = '8';
            }
            elseif($rowData['type']=='Door')
            {
                $html.= "
                <table width='100%' style='border-collapse: collapse; border: 1px solid #000; font-size:15px;' >
                <thead>
                    <tr>
                        <th style='border:solid 1px #000; padding: 5px; width:30px;' align='center'>No</th>
                        <th style='border:solid 1px #000; padding: 5px; width:200px;' align='left'>Product Name</th>
                        <th style='border:solid 1px #000; width:100px;' align='center'>Size</th>
                        <th style='border:solid 1px #000; width:80px;' align='center'>Qty</th>
                        <th style='border:solid 1px #000; width:60px;' align='center'>CFT</th>
                        <th style='border:solid 1px #000; width:100px;' align='center'>Price &#8377;</th>
                        <th style='border:solid 1px #000; width:120px;' align='center'>Amount &#8377;</th>
                        <th style='border:solid 1px #000; width:60px;' align='center'>GST %</th>
                        <th style='border:solid 1px #000; width:100px;' align='center'>GST Amount &#8377;</th>
                        <th style='border:solid 1px #000; padding: 5px; width:120px;' align='center'>Item Required Date</th>
                        <th style='border:solid 1px #000; padding: 5px; width:60px;' align='center'>Stock</th>
                        <th style='border:solid 1px #000; width:140px;' align='right'>Total &#8377;&nbsp;</th>
                    </tr>
                </thead>
                <tbody>";
                $k=1;
                for($i=0; $i<$quotProCount; $i++)
                {
                    $html .= "
                    <tr>
                        <td style='border:solid 1px #000; padding: 5px;' align='center'>".$k."</td>
                        <td style='border:solid 1px #000; padding: 5px;' align='left'>".$rowData['final_vendor'][0]['table_items'][$i]['item']."</td>
                        <td style='border:solid 1px #000; padding: 5px;' align='center'>".$rowData['final_vendor'][0]['table_items'][$i]['size']."</td>
                        <td style='border:solid 1px #000; padding: 5px;' align='center'>".$rowData['final_vendor'][0]['table_items'][$i]['quantity']."&nbsp; Nos</td>
                        <td style='border:solid 1px #000; padding: 5px;' align='center'>".$rowData['final_vendor'][0]['table_items'][$i]['cft']."</td>
                        <td style='border:solid 1px #000;' align='center'>".moneyFormat($rowData['final_vendor'][0]['table_items'][$i]['price'])."</td>
                        <td style='border:solid 1px #000;' align='center'>".moneyFormat($rowData['final_vendor'][0]['table_items'][$i]['amount'])."</td>
                        <td style='border:solid 1px #000;' align='center'>".$rowData['final_vendor'][0]['table_items'][$i]['gst']."</td>
                        <td style='border:solid 1px #000;' align='center'>".moneyFormat($rowData['final_vendor'][0]['table_items'][$i]['gst_amount'])."</td>
                        <td style='border:solid 1px #000; padding: 5px;' align='center'>".$rowData['final_vendor'][0]['table_items'][$i]['required_date']."</td>
                        <td style='border:solid 1px #000; padding: 5px;' align='center'>".$rowData['final_vendor'][0]['table_items'][$i]['stock_details']['block_available']."&nbsp;Nos</td>
                        <td style='border:solid 1px #000; padding: 5px;' align='right'>".moneyFormat($rowData['final_vendor'][0]['table_items'][$i]['total'])."&nbsp;</td>
                    </tr>";
                    $k++;
                }
                $colspan = '10';
            }
            
            $html.= "
            <tr>
                <td colspan='".$colspan."'></td>
                <td style='border:solid 1px #000; padding: 5px;' align='center'><b>Grand Total</b></td>
                <td style='border:solid 1px #000;' align='right'><b>".moneyFormat($rowData['final_vendor'][0]['grand_total'])."</b>&nbsp;</td>
            </tr>
            </tbody>
            </table></td></tr>
            </table>";
        }
        
        $tabCount = count($rowData['quot_list']);
        if($tabCount > 0) {
            $html .= "<h3 align='center'>QUOTATION PRODUCT LIST</h3>";
        }
        for($j=0; $j<$tabCount; $j++)
        {
            $html .= "<table cellpadding='0' cellspacing='0' width='100%' border='0'>
            <tr><td>
            <table width='100%' style='font-size:20px;'>
                <tr>
                    <td width='50%'><b>Vendor Name:</b> ".$rowData['quot_list'][$j]['vendor_name']."</td>
                    <td><b>Reason:</b> ".$rowData['quot_list'][$j]['reason']."</td>
                </tr>
            </table>";
            
            $quotProCount = count($rowData['quot_list'][$j]['table_items']);
            if($rowData['type']=='Standard' || $rowData['type']=='Work')
            {
                $html.= "
                <table width='100%' style='border-collapse: collapse; border: 1px solid #000; font-size:15px;' >
                <thead>
                    <tr>
                        <th style='border:solid 1px #000; padding: 5px; width:30px;' align='center'>No</th>
                        <th style='border:solid 1px #000; padding: 5px; width:200px;' align='left'>Product Name</th>
                        <th style='border:solid 1px #000; width:100px;' align='center'>Qty</th>
                        <th style='border:solid 1px #000; width:100px;' align='center'>Price &#8377;</th>
                        <th style='border:solid 1px #000; width:120px;' align='center'>Amount &#8377;</th>
                        <th style='border:solid 1px #000; width:60px;' align='center'>GST %</th>
                        <th style='border:solid 1px #000; width:100px;' align='center'>GST Amount &#8377;</th>
                        <th style='border:solid 1px #000; padding: 5px; width:120px;' align='center'>Item Required Date</th>
                        <th style='border:solid 1px #000; padding: 5px; width:60px;' align='center'>Stock</th>
                        <th style='border:solid 1px #000; width:140px;' align='right'>Total &#8377;&nbsp;</th>
                    </tr>
                </thead>
                <tbody>";
                $k=1;
                for($i=0; $i<$quotProCount; $i++)
                {
                    $html .= "
                    <tr>
                        <td style='border:solid 1px #000; padding: 5px;' align='center'>".$k."</td>
                        <td style='border:solid 1px #000; padding: 5px;' align='left'>".$rowData['quot_list'][$j]['table_items'][$i]['item']."</td>
                        <td style='border:solid 1px #000;' align='center'>".$rowData['quot_list'][$j]['table_items'][$i]['quantity']."&nbsp;".$rowData['quot_list'][$j]['table_items'][$i]['unit']."</td>
                        <td style='border:solid 1px #000;' align='center'>".moneyFormat($rowData['quot_list'][$j]['table_items'][$i]['price'])."</td>
                        <td style='border:solid 1px #000;' align='center'>".moneyFormat($rowData['quot_list'][$j]['table_items'][$i]['amount'])."</td>
                        <td style='border:solid 1px #000;' align='center'>".$rowData['quot_list'][$j]['table_items'][$i]['gst']."</td>
                        <td style='border:solid 1px #000;' align='center'>".moneyFormat($rowData['quot_list'][$j]['table_items'][$i]['gst_amount'])."</td>
                        <td style='border:solid 1px #000;' align='center'>".$rowData['quot_list'][$j]['table_items'][$i]['required_date']."</td>
                        <td style='border:solid 1px #000;' align='center'>".$rowData['quot_list'][$j]['table_items'][$i]['available_stock']."&nbsp;".$rowData['quot_list'][$j]['table_items'][$i]['unit']."</td>
                        <td style='border:solid 1px #000;' align='right'>".moneyFormat($rowData['quot_list'][$j]['table_items'][$i]['total'])."&nbsp;</td>
                    </tr>";
                    $k++;
                }
                $colspan = '8';
            }
            elseif($rowData['type']=='Steel')
            {
                $html.= "
                <table width='100%' style='border-collapse: collapse; border: 1px solid #000; font-size:15px;' >
                <thead>
                    <tr>
                        <th style='border:solid 1px #000; padding: 5px; width:30px;' align='center'>No</th>
                        <th style='border:solid 1px #000; padding: 5px; width:200px;' align='left'>Product Name</th>
                        <th style='border:solid 1px #000; width:80px;' align='center'>Details</th>
                        <th style='border:solid 1px #000; width:80px;' align='center'>Qty</th>
                        <th style='border:solid 1px #000; width:100px;' align='center'>Price &#8377;</th>
                        <th style='border:solid 1px #000; width:120px;' align='center'>Amount &#8377;</th>
                        <th style='border:solid 1px #000; width:60px;' align='center'>GST %</th>
                        <th style='border:solid 1px #000; width:100px;' align='center'>GST Amount &#8377;</th>
                        <th style='border:solid 1px #000; padding: 5px; width:120px;' align='center'>Item Required Date</th>
                        <th style='border:solid 1px #000; padding: 5px; width:60px;' align='center'>Stock</th>
                        <th style='border:solid 1px #000; width:100px;' align='center'>Make</th>
                        <th style='border:solid 1px #000; width:140px;' align='right'>Total &#8377;&nbsp;</th>
                    </tr>
                </thead>
                <tbody>";
                $k=1;
                for($i=0; $i<$quotProCount; $i++)
                {
                    $html .= "
                    <tr>
                        <td style='border:solid 1px #000; padding: 5px;' align='center'>".$k."</td>
                        <td style='border:solid 1px #000; padding: 5px;' align='left'>".$rowData['quot_list'][$j]['table_items'][$i]['item']."</td>
                        <td style='border:solid 1px #000; padding: 5px;' align='center'>".$rowData['quot_list'][$j]['table_items'][$i]['details']."</td>
                        <td style='border:solid 1px #000;' align='center'>".$rowData['quot_list'][$j]['table_items'][$i]['quantity']."&nbsp;".$rowData['quot_list'][$j]['table_items'][$i]['unit']."</td>
                        <td style='border:solid 1px #000;' align='center'>".moneyFormat($rowData['quot_list'][$j]['table_items'][$i]['price'])."</td>
                        <td style='border:solid 1px #000;' align='center'>".moneyFormat($rowData['quot_list'][$j]['table_items'][$i]['amount'])."</td>
                        <td style='border:solid 1px #000;' align='center'>".$rowData['quot_list'][$j]['table_items'][$i]['gst']."</td>
                        <td style='border:solid 1px #000;' align='center'>".moneyFormat($rowData['quot_list'][$j]['table_items'][$i]['gst_amount'])."</td>
                        <td style='border:solid 1px #000;' align='center'>".$rowData['quot_list'][$j]['table_items'][$i]['required_date']."</td>
                        <td style='border:solid 1px #000;' align='center'>".$rowData['quot_list'][$j]['table_items'][$i]['available_stock']['block_available']."&nbsp;".$rowData['quot_list'][$j]['table_items'][$i]['unit']."</td>
                        <td style='border:solid 1px #000; padding: 5px;' align='center'>".$rowData['quot_list'][$j]['table_items'][$i]['make']."</td>
                        <td style='border:solid 1px #000;' align='right'>".moneyFormat($rowData['quot_list'][$j]['table_items'][$i]['total'])."&nbsp;</td>
                    </tr>";
                    $k++;
                }
                $colspan = '10';
            }
            elseif($rowData['type']=='UPVC')
            {
                $html.= "
                <table width='100%' style='border-collapse: collapse; border: 1px solid #000; font-size:15px;' >
                <thead>
                    <tr>
                        <th style='border:solid 1px #000; padding: 5px; width:30px;' align='center'>No</th>
                        <th style='border:solid 1px #000; padding: 5px; width:200px;' align='left'>Product Name</th>
                        <th style='border:solid 1px #000; width:100px;' align='center'>Qty</th>
                        <th style='border:solid 1px #000; width:100px;' align='center'>Price &#8377;</th>
                        <th style='border:solid 1px #000; width:120px;' align='center'>Amount &#8377;</th>
                        <th style='border:solid 1px #000; width:60px;' align='center'>GST %</th>
                        <th style='border:solid 1px #000; width:100px;' align='center'>GST Amount &#8377;</th>
                        <th style='border:solid 1px #000; padding: 5px; width:120px;' align='center'>Item Required Date</th>
                        <th style='border:solid 1px #000; padding: 5px; width:60px;' align='center'>Stock</th>
                        <th style='border:solid 1px #000; width:140px;' align='right'>Total &#8377;&nbsp;</th>
                    </tr>
                </thead>
                <tbody>";
                $k=1;
                for($i=0; $i<$quotProCount; $i++)
                {
                    $html .= "
                    <tr>
                        <td style='border:solid 1px #000; padding: 5px;' align='center'>".$k."</td>
                        <td style='border:solid 1px #000; padding: 5px;' align='left'>".$rowData['quot_list'][$j]['table_items'][$i]['item']." - (".$rowData['quot_list'][$j]['table_items'][$i]['upvc_type'].") W-".$rowData['quot_list'][$j]['table_items'][$i]['width']."; H-".$rowData['quot_list'][$j]['table_items'][$i]['height']."</td>
                        <td style='border:solid 1px #000;' align='center'>".$rowData['quot_list'][$j]['table_items'][$i]['quantity']."&nbsp;".$rowData['quot_list'][$j]['table_items'][$i]['unit']."</td>
                        <td style='border:solid 1px #000;' align='center'>".moneyFormat($rowData['quot_list'][$j]['table_items'][$i]['price'])."</td>
                        <td style='border:solid 1px #000;' align='center'>".moneyFormat($rowData['quot_list'][$j]['table_items'][$i]['amount'])."</td>
                        <td style='border:solid 1px #000;' align='center'>".$rowData['quot_list'][$j]['table_items'][$i]['gst']."</td>
                        <td style='border:solid 1px #000;' align='center'>".moneyFormat($rowData['quot_list'][$j]['table_items'][$i]['gst_amount'])."</td>
                        <td style='border:solid 1px #000;' align='center'>".$rowData['quot_list'][$j]['table_items'][$i]['required_date']."</td>
                        <td style='border:solid 1px #000;' align='center'>".$rowData['quot_list'][$j]['table_items'][$i]['stock_details']['block_available']."&nbsp;".$rowData['quot_list'][$j]['table_items'][$i]['unit']."</td>
                        <td style='border:solid 1px #000;' align='right'>".moneyFormat($rowData['quot_list'][$j]['table_items'][$i]['total'])."&nbsp;</td>
                    </tr>";
                    $k++;
                }
                $colspan = '8';
            }
            elseif($rowData['type']=='Door')
            {
                $html.= "
                <table width='100%' style='border-collapse: collapse; border: 1px solid #000; font-size:15px;' >
                <thead>
                    <tr>
                        <th style='border:solid 1px #000; padding: 5px; width:30px;' align='center'>No</th>
                        <th style='border:solid 1px #000; padding: 5px; width:200px;' align='left'>Product Name</th>
                        <th style='border:solid 1px #000; width:100px;' align='center'>Size</th>
                        <th style='border:solid 1px #000; width:80px;' align='center'>Qty</th>
                        <th style='border:solid 1px #000; width:60px;' align='center'>CFT</th>
                        <th style='border:solid 1px #000; width:100px;' align='center'>Price &#8377;</th>
                        <th style='border:solid 1px #000; width:120px;' align='center'>Amount &#8377;</th>
                        <th style='border:solid 1px #000; width:60px;' align='center'>GST %</th>
                        <th style='border:solid 1px #000; width:100px;' align='center'>GST Amount &#8377;</th>
                        <th style='border:solid 1px #000; padding: 5px; width:120px;' align='center'>Item Required Date</th>
                        <th style='border:solid 1px #000; padding: 5px; width:60px;' align='center'>Stock</th>
                        <th style='border:solid 1px #000; width:140px;' align='right'>Total &#8377;&nbsp;</th>
                    </tr>
                </thead>
                <tbody>";
                $k=1;
                for($i=0; $i<$quotProCount; $i++)
                {
                    $html .= "
                    <tr>
                        <td style='border:solid 1px #000; padding: 5px;' align='center'>".$k."</td>
                        <td style='border:solid 1px #000; padding: 5px;' align='left'>".$rowData['quot_list'][$j]['table_items'][$i]['item']."</td>
                        <td style='border:solid 1px #000; padding: 5px;' align='center'>".$rowData['quot_list'][$j]['table_items'][$i]['size']."</td>
                        <td style='border:solid 1px #000; padding: 5px;' align='center'>".$rowData['quot_list'][$j]['table_items'][$i]['quantity']."&nbsp; Nos</td>
                        <td style='border:solid 1px #000; padding: 5px;' align='center'>".$rowData['quot_list'][$j]['table_items'][$i]['cft']."</td>
                        <td style='border:solid 1px #000;' align='center'>".moneyFormat($rowData['quot_list'][$j]['table_items'][$i]['price'])."</td>
                        <td style='border:solid 1px #000;' align='center'>".moneyFormat($rowData['quot_list'][$j]['table_items'][$i]['amount'])."</td>
                        <td style='border:solid 1px #000;' align='center'>".$rowData['quot_list'][$j]['table_items'][$i]['gst']."</td>
                        <td style='border:solid 1px #000;' align='center'>".moneyFormat($rowData['quot_list'][$j]['table_items'][$i]['gst_amount'])."</td>
                        <td style='border:solid 1px #000; padding: 5px;' align='center'>".$rowData['quot_list'][$j]['table_items'][$i]['required_date']."</td>
                        <td style='border:solid 1px #000; padding: 5px;' align='center'>".$rowData['quot_list'][$j]['table_items'][$i]['stock_details']['block_available']."&nbsp;Nos</td>
                        <td style='border:solid 1px #000; padding: 5px;' align='right'>".moneyFormat($rowData['quot_list'][$j]['table_items'][$i]['total'])."&nbsp;</td>
                    </tr>";
                    $k++;
                }
                $colspan = '10';
            }
            
            $html.= "
            <tr>
                <td colspan='".$colspan."'></td>
                <td style='border:solid 1px #000; padding: 5px;' align='center'><b>Grand Total</b></td>
                <td style='border:solid 1px #000;' align='right'><b>".moneyFormat($rowData['quot_list'][$j]['grand_total'])."</b>&nbsp;</td>
            </tr>
            </tbody>
            </table></td></tr>
            </table><br>";
        }
        
        $supplierCount = count($rowData['indent_data'][0]['supplier_list']);
        if($supplierCount > 0)
        {
            $html.= "
            <h3 align='center'>SUGGESTED SUPPLIERS FROM SITE</h3>
            <table cellpadding='0' cellspacing='0' width='100%' border='0' style='border-collapse: collapse; border: 1px solid #000; font-size:15px;' >
            <thead>
                <tr>
                    <th style='border:solid 1px #000; padding: 5px; width:30px;' align='center'>No</th>
                    <th style='border:solid 1px #000; padding: 5px; width:100px;' align='left'>Name</th>
                    <th style='border:solid 1px #000; padding: 5px; width:100px;' align='center'>Reason</th>
                    <th style='border:solid 1px #000; padding: 5px; width:100px;' align='center'>Contact Person</th>
                    <th style='border:solid 1px #000; padding: 5px; width:120px;' align='center'>Mobile</th>
                    <th style='border:solid 1px #000; padding: 5px; width:60px;' align='center'>Address</th>
                </tr>
            </thead>
            <tbody>";

            $n=1;
            for($j=0; $j<$supplierCount; $j++)
            {
                $html .= "
                <tr>
                    <td style='border:solid 1px #000; padding: 5px;' align='center'>".$n."</td>
                    <td style='border:solid 1px #000; padding: 5px;' align='left'>".$rowData['indent_data'][0]['supplier_list'][$j]['company_name']."</td>
                    <td style='border:solid 1px #000; padding: 5px;' align='center'>".$rowData['indent_data'][0]['supplier_list'][$j]['reason']."</td>
                    <td style='border:solid 1px #000; padding: 5px;' align='center'>".$rowData['indent_data'][0]['supplier_list'][$j]['contact_person']."</td>
                    <td style='border:solid 1px #000; padding: 5px;' align='center'>".$rowData['indent_data'][0]['supplier_list'][$j]['mobile']."</td>
                    <td style='border:solid 1px #000; padding: 5px;' align='center'>".$rowData['indent_data'][0]['supplier_list'][$j]['comp_address']."&nbsp;</td>
                </tr>";
                $n++;
            }
            
            $html.= "</tbody></table>";
        }
		
		
		$cursorsign = $collectionsignin->aggregate(array(
            array('$match' => array(
                 "emp_id" => $rowData['emp_id']      
            )),
          ));
		if($cursorsign)
		{
		foreach($cursorsign as $rowsign)
		{
			 $html.= "<br><div class='col12 s12 m6 l6 left leftspace lineheight'>
            <b>Prepared By : ".  $rowsign['name'] ."</b></div>";
		}	
		}
		
        
        if($rowData['approved_status']!='')
        {
            $html.= "<br><div class='col12 s12 m6 l6 left leftspace lineheight'>
                <img src=".$rowData['admin_data'][0]['digital_sign']." />
            </div>";
        }

          $html.= "</div>";
        if($rowData['approved_status']=='declined')
        {
            $mpdf->SetWatermarkText('Declined');
            $mpdf->showWatermarkText = true;
        }
       
       $mpdf->AddPage('','A4','','',0,0,0,37,45,0);
        $mpdf->WriteHTML($html);
        $mpdf->SetDisplayMode('fullpage');
        
        if($type=='upload'){
            $mpdf->Output('../../../modules/uploads/'.$quotNum.'.pdf');
        }
        else{
            $mpdf->Output($quotNum.'.pdf', 'D');
        }
    }
}
?>