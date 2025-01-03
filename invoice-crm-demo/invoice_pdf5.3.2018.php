<?php
require('connect.php');
include "writelog.php";
include "moneyformat.php";
include('MPDF57/mpdf.php');
date_default_timezone_set('Asia/Calcutta'); 
$time = date('Y-m-d H:i:s');
$po_no=$_GET['po_no'];
$po_year=$_GET['po_year'];
$ip = getenv('REMOTE_ADDR');
$userAgent = getenv('HTTP_USER_AGENT');
$referrer = getenv('HTTP_REFERER');
$query = getenv('QUERY_STRING');
$n = 0;



$result1 = mysql_query("SELECT * FROM  invoicetable where po_year='".$po_year."' and  invoicenotype='" .$po_no."'",$conn);

if($row1 = mysql_fetch_array($result1))
	 {
$projectname=$row1['projectname'] ;
$block=$row1['block'];
$floorno=$row1['floorno'];
$flatno=$row1['flatno'];
$invoiceno=$row1['invoiceno'];
$invoicenotype=$row1['invoicenotype'];
$invoicedate=$row1['invoicedate'];
$gstin=$row1['gstin'];
$panno=$row1['panno'];
    $hsn_sac_code=$row1['hsn_sac_code'];    
$total=$row1['total'];
    $lc_amount=$row1['lc_amount'];
     $taxamount=$row1['taxamount'];
    $sgst=$row1['sgst'];
    $cgst=$row1['cgst'];
     $roundtotal=$row1['roundtotal'];
$grandtotal=$row1['grandtotal'];
$totalword=$row1['totalword'];
    $po_year=$row1['po_year'];
$curYear = $row1['po_year'];
$nexYear = $row1['po_year'] + 1;
    $gstper = $row1['gstper'];
    if($gstper == '0')
    {
      $gtotal = '0';   
    }
    else
    {
       $gstper = $gstper/2; 
    $gtotal =  $roundtotal-$total;
     $finaltotal =  $roundtotal-$gtotal;    
    }
   
	 }

  	$result = mysql_query("SELECT * FROM clientmaster where projectname='$projectname' and block='$block' and floor='$floorno' and flatno='$flatno' ",$conn);
	if($row= mysql_fetch_array($result))
	 {
	    $projectname = $row['projectname'] ;
	    $buyername=$row['name'];	
		$address1=$row['address1']; 
        $address2=$row['address2'];
        $address3=$row['address3'];	
		$contact=$row['contact'];	
        $pannoc=$row['panno'];
        		}
		else
		{
			echo "Error";
	 }


$mpdf=new mPDF('c','A4','','',10,10,37,37,5,5); 

$mpdf->mirrorMargins = 1;	// Use different Odd/Even headers and footers and mirror margins
$mpdf->shrink_tables_to_fit=1;

//$mpdf->SetHTMLHeader('<div align="right" ><img src="images/head.jpg" align="right"/></div>'); 
//$mpdf->setHTMLFooter('<img src="images/footerp.jpg"/>');
$header = '<div align="right"><img src="images/head.jpg" width="126px" /></div>';
$headerE = '<div align="right"><img src="images/head.jpg" width="126px" /></div>';

$footer = '<div align="center"><img src="images/footer.jpg" /></div>';
$footerE = '<div align="center"><img src="images/footer.jpg" /></div>';
$mpdf->SetHTMLHeader($header);
$mpdf->SetHTMLHeader($headerE,'E');
$mpdf->SetHTMLFooter($footer);
$mpdf->SetHTMLFooter($footerE,'E');

    
     $html.= "<div style='width:100%'>";
     $html.= "<table style='width:100%;page-break-inside: avoid;white-space:nowrap' align='center'>";
        $html.= "<tr>";
            $html.= "<td style='width:70%;white-space:nowrap'>";
            $html.= "<table>";
            $html.= "<tr>";
            $html.= "<td style=' font-size:16px; '><b>Project</b></td>";
            $html.= "<td style=' font-size:16px '>:</td>";
            $html.= "<td style=' font-size:16px '><b>".$projectname."</b></td>";
            $html.= "</tr>";
           if($projectname == 'S.I.S Luxor')
           {
           $html.= "<tr>";
            $html.= "<td style=' font-size:16px '><b>Flat No</b></td>";
            $html.= "<td style=' font-size:16px '>:</td>";
            $html.= "<td style=' font-size:16px '><b>".$flatno."</b></td>";
            $html.= "</tr>";    
           }
           else
           {
               $html.= "<tr>";
            $html.= "<td style=' font-size:16px '><b>Flat No</b></td>";
            $html.= "<td style=' font-size:16px '>:</td>";
            $html.= "<td style=' font-size:16px '><b>".$block."-".$flatno."</b></td>";
            $html.= "</tr>";
           }
           
            $html.= "<tr>";
            $html.= "<td style=' font-size:16px '>Buyer Name</td>";
            $html.= "<td style=' font-size:16px '>:</td>";
            $html.= "<td style=' font-size:16px '><b>".$buyername."</b></td>";
            $html.= "</tr>";
           
           $html.= "<tr>";
            $html.= "<td style=' font-size:13px'>Address1</td>";
            $html.= "<td style=' font-size:13px'>:</td>";
            $html.= "<td style=' font-size:13px'>".$address1."</td>";
            $html.= "</tr>";
           
            $html.= "<tr>";
            $html.= "<td style=' font-size:13px'>Address2</td>";
            $html.= "<td>:</td>";
            $html.= "<td style=' font-size:13px'>".$address2."</td>";
            $html.= "</tr>";
           
               $html.= "<tr>";
            $html.= "<td style=' font-size:13px'>Address3</td>";
            $html.= "<td style=' font-size:13px'>:</td>";
            $html.= "<td style=' font-size:13px'>".$address3."</td>";
            $html.= "</tr>";
           
           
             $html.= "<tr>";
            $html.= "<td style=' font-size:13px'>Tel</td>";
            $html.= "<td style=' font-size:13px'>:</td>";
            $html.= "<td style=' font-size:13px'>".$contact."</td>";
            $html.= "</tr>";
           
             $html.= "<tr>";
            $html.= "<td style=' font-size:13px'>GST / PAN</td>";
            $html.= "<td style=' font-size:13px'>:</td>";
            $html.= "<td style=' font-size:13px'>".$pannoc."</td>";
            $html.= "</tr>";
                 
            $html.= "</table>";
            $html.= "</td>";
            $html.= "<td>";
            $html.= "<table style='width:30%; border:solid 2px #000'>";
                $html.= "<tr>";
                    $html.= "<td style=' font-size:13px'><b>Invoice No</b></td>";
                    $html.= "<td style=' font-size:13px'>:</td>";
                    $html.= "<td style=' font-size:13px'><b>".$invoicenotype."</b></td>";
                $html.= "</tr>";
                 $html.= "<tr>";
                    $html.= "<td style=' font-size:13px'><b>Date</b></td>";
                    $html.= "<td style=' font-size:13px'>:</td>";
                    $html.= "<td style=' font-size:13px'><b>".$invoicedate."</b></td>";
                $html.= "</tr>";
                 $html.= "<tr>";
                   $html.= "<td style=' font-size:13px'>GSTIN</td>";
                   $html.= "<td style=' font-size:13px'>:</td>";
                   $html.= "<td style=' font-size:13px'>".$gstin."</td>";
               $html.= "</tr>";
                $html.= "<tr>";
                   $html.= "<td style=' font-size:13px'>PAN</td>";
                   $html.= "<td style=' font-size:13px'>:</td>";
                   $html.= "<td style=' font-size:13px'>".$panno."</td>";
               $html.= "</tr>";
                
           $html.= "</table></td>";
       $html.= "</tr>";

        $html.= "<tr>";
            $html.= "<td colspan='2' >";
              $html.= "<table  cellpadding='0' cellspacing='0'  align='center' style='width:80%; border-collapse: collapse; border: 1px solid black;white-space:nowrap' >";

                    $html.= "<tr>";
                        $html.= "<td style='width:3%; border:1px solid #000; padding:10px; font-size:18px' align='center'><b>S.No</b></td>";
                        $html.= "<td style='width:70%; border:1px solid #000;  font-size:18px' colspan='2' align='center'><b>Description of Goods / Services</b></td>";


                        $html.= "<td  style='width:28%; border:1px solid #000;padding:10px;  font-size:18px' align='center'><b>Amount in Rs</b></td>";
                    $html.= "</tr>";
                    $html.= "<tr>";
if($gstper == '0')
{
   $html.= "<td rowspan='3' style='border:1px solid #000;padding:5px;  font-size:18px' valign='top' align='center'>1</td>";
      $html.= "<td colspan='2' style='border:1px solid #000;padding:5px 5px 5px 5px;  font-size:16px;'>Land & Construction Value - Part Payment - Exempted Supply </td>";
}
   else
   {
       $html.= "<td rowspan='8' style='border:1px solid #000;padding:5px;' valign='top' align='center'>1</td>";
         $html.= "<td colspan='2' style='border:1px solid #000;padding:5px 5px 5px 5px; font-size:16px;'>Land & Construction Value - Part Payment / Construction Services of multi-storied residential buildings <b>HSN/SAC Code:".$hsn_sac_code."</b></td>";
   }
                      
                        $html.= "<td style='border:1px solid #000; padding:5px;  font-size:16px;' align='right'>".$lc_amount."</td>";
                    $html.= "</tr>";
                    
                  
                
if($gstper != '0')
{
       $html.= "<tr>";                       
                         $html.= "<td colspan='2' style='border:1px solid #000; padding:5px;  font-size:16px;'>Taxable Value (Rs.".$taxamount.")</td>";
                         $html.= "<td style='border:1px solid #000; padding:5px;  font-size:16px;' align='right'></td>";
                        
                     $html.= "</tr>";
    $html.= "<tr>";                       
                         $html.= "<td colspan='2' style='border:1px solid #000; padding:5px;  font-size:16px;'>CGST - ".$gstper."% for ".$taxamount."</td>";
                         $html.= "<td style='border:1px solid #000; padding:5px;  font-size:16px;' align='right'>".$cgst."</td>";
                         
                     $html.= "</tr>";
                       $html.= "<tr>";                       
                         $html.= "<td colspan='2' style='border:1px solid #000; padding:5px;  font-size:16px;'>SGST - ".$gstper."% for ".$taxamount."</td>";
                         $html.= "<td style='border:1px solid #000; padding:5px;  font-size:16px;' align='right'>".$sgst."</td>";
                         
                
                     $html.= "<tr>";                       
                         $html.= "<td colspan='2' style='border:1px solid #000; padding:5px;  font-size:16px;'><b>Total</b></td>";
                         $html.= "<td style='border:1px solid #000; padding:5px;  font-size:16px;' align='right'>".$roundtotal."</td>";
    
    
      $html.= "</tr>";
                     $html.= "<tr>";                       
                         $html.= "<td colspan='2' style='border:1px solid #000; padding:5px;  font-size:16px;'><b>Round off</b></td>";
                         $html.= "<td style='border:1px solid #000;  padding:5px; font-size:16px;' align='right'>".$gtotal."</td>";
                         
                     $html.= "</tr>";
                   
    
    
    
                        
}
                   
    
                     $html.= "<tr>"; 
                          
                   
if($gstper == '0')
{
                         $html.= "<td colspan='2' style='border:1px solid #000; padding:5px;  font-size:16px;'><b>Grand Total</b></td>";
}
else
{
  $html.= "<td colspan='2' style='border:1px solid #000; padding:5px;  font-size:16px;'><b>Grand Total (Inclusive of GST)</b></td>";  
}

                         $html.= "<td style='border:1px solid #000; padding:5px;  font-size:16px;' align='right'><b>".$grandtotal."</b></td>";
                         
                     $html.= "</tr>";
                    
                      $html.= "<tr>";
                                 
                         $html.= "<td  colspan='3'  style='border:1px solid #000' align='left'><table cellpadding='0' cellspacing='0'  align='left' style='width:100%; border-collapse: collapse;; font-size:16px; ' ><tr><td style='border-right:2px solid #000;width:25%;padding:10px 10px 10px 5px; font-size:16px;'><b>Invoice Value(Inwords)</b></td><td style='width:75%;padding:10px'><b>Rupees ".$totalword."</b></td></tr></table></td>";
                         
                       
                     $html.= "</tr>";
                     $html.= "<tr>";
                        $html.= "<td style='border:1px solid #000;white-space:nowrap'></td>";                       
                       
                         $html.= "<td colspan='2' style='border:1px solid #000; padding:5px;  font-size:16px;' align='center'><b>Amount subject to Reverse Charge</b></td>";
                          $html.= "<td style='border:1px solid #000;padding:5px;  font-size:16px;'>Nil</td>";
                       
                     $html.= "</tr>";
                    
                 $html.= "</table>";
                 $html.= "<table style='width:80%;white-space:nowrap' align='left' >";
                   
                     $html.= "<tr>";
             $html.= "<td colspan='2' style='white-space:nowrap'>&nbsp;</td>";
         $html.= "</tr>";
                  
                     $html.= "<tr>";
             $html.= "<td colspan='2' style='white-space:nowrap' align='left'><b>For South India Shelters Private Limited</b></td>";
         $html.= "</tr>";
          $html.= "<tr>";
             $html.= "<td colspan='2' style='white-space:nowrap'>&nbsp;</td>";
         $html.= "</tr>";
          $html.= "<tr>";
             $html.= "<td colspan='2' style='white-space:nowrap'>&nbsp;</td>";
         $html.= "</tr>";
          $html.= "<tr>";
             $html.= "<td colspan='2' style='white-space:nowrap' align='left'><b>Authorised Signatory</b></td>";
         $html.= "</tr>";
                 $html.= "</table>";
             $html.= "</td>";
         $html.= "</tr>";
        
        
     $html.= "</table>";
    $html.= " </div>";
 
$mpdf->WriteHTML($html);
$mpdf->SetDisplayMode('fullpage');

$mpdf->Output("invoice/invoice-".$invoiceno. ".pdf");
//header("Location: purchaseorder/PO-".$po_no. ".pdf");
$file = "invoice/invoice-".$invoiceno. ".pdf";

if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($file));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
 

//$msg = "IP: " . $ip . " TIME: " . $time . " User: " .$_SESSION['st_username'].  " USERAGENT: " .$userAgent. "Action : Purchase order_".$po_no." has been downloaded as pdf";
//writeToLogFile($msg);

    readfile($file);
	
unlink("invoice/invoiceno-".$invoiceno. ".pdf");

    exit;
}




// unlink file

 ?>
