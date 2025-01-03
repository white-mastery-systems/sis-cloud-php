<?php
$prfNum = $_GET['id'];
$type = $_GET['type'];


require_once '../../../vendor/autoload.php';
include "../../../include/conn.php";
include "../../../include/money_format.php";

$collectionMaster = $db->indent_master;
$collectionUser = $db->signintable;
$collectionPurchase = $db->purchase_master;

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
            "prf_number" => $prfNum
        )),
        array( '$lookup' => array(
            'from' => 'indent_table',
            'localField' => 'prf_number',
            'foreignField' => 'prf_number',
            'as' => 'indent_table_data'
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
        ))
));
if($cursor)
{
    foreach($cursor as $rowData)
    {
        if($rowData['project_details_data'][0]['block_name'] != 'none') {
            $block = " - ".$rowData['project_details_data'][0]['block_name']." Block";
        }
        
        $html.= "
<div style='padding-left:40px; padding-right:40px' align='center'>
            <h3 align='center'>INDENT FORM</h3>
            <table cellpadding='0' cellspacing='0' width='100%' border='0' style='border-collapse: collapse; border: 1px solid #000; font-size:15px;' >
            <tbody>
            <tr>
                <td style='border:solid 1px #000; padding: 5px;' width='50%'>Name</td>
                <td style='border:solid 1px #000; padding: 5px;'>".$rowData['user_data'][0]['emp_id']." - ".$rowData['user_data'][0]['name']."</td>
            </tr>
            <tr>
                <td style='border:solid 1px #000; padding: 5px;'>Designation</td>
                <td style='border:solid 1px #000; padding: 5px;'>".$rowData['user_data'][0]['designation']."</td>
            </tr>
            <tr>
                <td style='border:solid 1px #000; padding: 5px;'>Indent No</td>
                <td style='border:solid 1px #000; padding: 5px;'>".$rowData['prf_number']."</td>
            </tr>
            <tr>
                <td style='border:solid 1px #000; padding: 5px;'>Indent Date</td>
                <td style='border:solid 1px #000; padding: 5px;'>".$rowData['prf_date']."</td>
            </tr>
            <tr>
                <td style='border:solid 1px #000; padding: 5px;'>Site</td>
                <td style='border:solid 1px #000; padding: 5px;'><b>".$rowData['project_details_data'][0]['project_name'].$block."</b></td>
            </tr>
            <tr>
                <td style='border:solid 1px #000; padding: 5px;'>Purpose</td>
                <td style='border:solid 1px #000; padding: 5px;'>".$rowData['purpose']."</td>
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
                    <th style='border:solid 1px #000; padding: 5px; width:100px;' align='center'>Quantity</th>
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
                    <th style='border:solid 1px #000; padding: 5px; width:100px;' align='center'>Quantity</th>
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
                    <th style='border:solid 1px #000; padding: 5px; width:100px;' align='center'>Quantity</th>
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
                    <th style='border:solid 1px #000; padding: 5px; width:100px;' align='center'>Quantity</th>
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
                <td style='border:solid 1px #000;' align='right'><b>".moneyFormat($rowData['grand_total'])."</b>&nbsp;</td>
            </tr>
            </tbody>
            </table>";
        
        $purValidate = $collectionPurchase->findOne(array("prf_number" => $rowData['prf_number']));
        if($purValidate)
        {
            $html.= "<h3 align='center'>PURCHASE PRODUCT LIST</h3>
                    <table cellpadding='0' cellspacing='0' width='100%' border='0' style='border-collapse: collapse; border: 1px solid #000; font-size:15px;' >
                    <thead>
                        <tr>
                            <th style='border:solid 1px #000; padding: 5px; width:30px;' align='center'>No</th>
                            <th style='border:solid 1px #000; padding: 5px; width:100px;' align='left'>Product Name</th>
                            <th style='border:solid 1px #000; padding: 5px; width:100px;' align='center'>Quantity</th>
                        </tr>
                    </thead>
                    <tbody>";
            $purchaseData = $collectionPurchase->aggregate(array(
                array('$match' => array(
                    "prf_number" => $rowData['prf_number']
                )),
                array( '$lookup' => array(
                    'from' => 'purchase_table',
                    'localField' => 'po_number',
                    'foreignField' => 'po_number',
                    'as' => 'purchase_data'
                ))
            ));
            if($purchaseData)
            {
                $n=1;
                foreach($purchaseData as $rowPurData)
                {
                    $proCount = count($rowPurData['purchase_data']);
                    for($i=0; $i<$proCount; $i++)
                    {
                        $html .= "
                        <tr>
                            <td style='border:solid 1px #000; padding: 5px;' align='center'>".$n."</td>
                            <td style='border:solid 1px #000; padding: 5px;' align='left'>".$rowPurData['purchase_data'][$i]['item']."</td>
                            <td style='border:solid 1px #000;' align='center'>".$rowPurData['purchase_data'][$i]['quantity']."&nbsp;".$rowPurData['purchase_data'][$i]['unit']."</td>
                        </tr>";
                        $n++;
                    }
                }
            }
            $html .= "</tbody></table>";
        }
   
        $supplierCount = count($rowData['supplier_list']);
        if($supplierCount > 0)
        {
        $html.= "<h3 align='center'>SUPPLIER DETAILS</h3>
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
                    <td style='border:solid 1px #000; padding: 5px;' align='left'>".$rowData['supplier_list'][$j]['company_name']."</td>
                    <td style='border:solid 1px #000; padding: 5px;' align='center'>".$rowData['supplier_list'][$j]['reason']."</td>
                    <td style='border:solid 1px #000; padding: 5px;' align='center'>".$rowData['supplier_list'][$j]['contact_person']."</td>
                    <td style='border:solid 1px #000; padding: 5px;' align='center'>".$rowData['supplier_list'][$j]['mobile']."</td>
                    <td style='border:solid 1px #000; padding: 5px;' align='center'>".$rowData['supplier_list'][$j]['comp_address']."</td>
                </tr>";
                $n++;
            }
            
        $html.= "
            </tbody>
        </table> </div>";
        }
       
        $mpdf->AddPage('','A4','','',0,0,0,37,45,0);
        $mpdf->WriteHTML($html);
        $mpdf->SetDisplayMode('fullpage');
        
        if($type=='upload'){
            $mpdf->Output($prfNum.'.pdf');
        }
        else{
            $mpdf->Output($prfNum.'.pdf', 'D');
        }
    }
}
?>