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
"<style>
body
{
	font-size:10px ; font-family:cambria ;
	
}
	</style>
";


$result1 = mysql_query("SELECT * FROM  invoicetable where po_year='".$po_year."' and  invoiceno='" .$po_no."' and gst_status='included'",$conn);

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
$mpdf->use_kwt = false;
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

    
     $html.= "<div style='width:100%; font-size:10px'>";
     $html.= "<table style='width:100%' align='center'>";
        $html.= "<tr>";
            $html.= "<td style='width:60%;font-size:10px'>";
            $html.= "<table>";
            $html.= "<tr>";
            $html.= "<td style='font-size:10px'><b>Project</b></td>";
            $html.= "<td>:</td>";
            $html.= "<td style='font-size:10px'><b>".$projectname."</b></td>";
            $html.= "</tr>";
           if($projectname == 'S.I.S Capetown')
           {
            $html.= "<tr>";
            $html.= "<td style='font-size:10px'><b>Plot No</b></td>";
            $html.= "<td>:</td>";
            $html.= "<td style='font-size:10px'><b>".$flatno."</b></td>";
            $html.= "</tr>";
           }
else
{
            $html.= "<tr>";
            $html.= "<td style='font-size:10px'><b>Flat No</b></td>";
            $html.= "<td>:</td>";
            $html.= "<td style='font-size:10px'><b>".$block."-".$flatno."</b></td>";
            $html.= "</tr>";
           }
            $html.= "<tr>";
            $html.= "<td style='font-size:10px'>Buyer Name</td>";
            $html.= "<td>:</td>";
            $html.= "<td style='font-size:10px'><b>".$buyername."</b></td>";
            $html.= "</tr>";
           
           $html.= "<tr>";
            $html.= "<td style='font-size:10px'>Address1</td>";
            $html.= "<td style='font-size:10px'>:</td>";
            $html.= "<td style='font-size:10px'>".$address1."</td>";
            $html.= "</tr>";
           
            $html.= "<tr>";
            $html.= "<td style='font-size:10px'>Address2</td>";
            $html.= "<td>:</td>";
            $html.= "<td style='font-size:10px'>".$address2."</td>";
            $html.= "</tr>";
           
               $html.= "<tr>";
            $html.= "<td style='font-size:10px'>Address3</td>";
            $html.= "<td style='font-size:10px'>:</td>";
            $html.= "<td style='font-size:10px'>".$address3."</td>";
            $html.= "</tr>";
           
           
             $html.= "<tr>";
            $html.= "<td>Tel</td>";
            $html.= "<td>:</td>";
            $html.= "<td style='font-size:10px'>".$contact."</td>";
            $html.= "</tr>";
           
             $html.= "<tr>";
            $html.= "<td style='font-size:10px'>GST / PAN</td>";
            $html.= "<td style='font-size:10px'>:</td>";
            $html.= "<td style='font-size:10px'>".$pannoc."</td>";
            $html.= "</tr>";
           
  

           
            $html.= "</table>";
            $html.= "</td>";
            $html.= "<td>";
            $html.= "<table style='width:40%; border:solid 2px #000'>";
                $html.= "<tr>";
                    $html.= "<td style='font-size:10px'><b>Invoice No</b></td>";
                    $html.= "<td style='font-size:10px'>:</td>";
                    $html.= "<td style='font-size:10px'><b>".$invoicenotype."</b></td>";
                $html.= "</tr>";
                 $html.= "<tr>";
                    $html.= "<td style='font-size:10px'><b>Date</b></td>";
                    $html.= "<td style='font-size:10px'>:</td>";
                    $html.= "<td style='font-size:10px'><b>".$invoicedate."</b></td>";
                $html.= "</tr>";
                 $html.= "<tr>";
                   $html.= "<td style='font-size:10px'>GSTIN</td>";
                   $html.= "<td style='font-size:10px'>:</td>";
                   $html.= "<td style='font-size:10px'>".$gstin."</td>";
               $html.= "</tr>";
                $html.= "<tr>";
                   $html.= "<td style='font-size:10px'>PAN</td>";
                   $html.= "<td style='font-size:10px'>:</td>";
                   $html.= "<td style='font-size:10px'>".$panno."</td>";
               $html.= "</tr>";
                $html.= "<tr>";
                   $html.= "<td style='font-size:10px'>HSN/SAC Code</td>";
                   $html.= "<td style='font-size:10px'>:</td>";
                   $html.= "<td style='font-size:10px'>".$hsn_sac_code."</td>";
               $html.= "</tr>";
           $html.= "</table></td>";
       $html.= "</tr>";
        $html.= "<tr>";
            $html.= "<td colspan='2'>";
                $html.= "<table  cellpadding='0' cellspacing='0'  align='center' style='width:80%; border-collapse: collapse; border: 1px solid black;' >";
                    $html.= "<tr>";
                        $html.= "<td style='width:3%; border:1px solid #000; padding:10px' align='center'>S.No</td>";
                        $html.= "<td style='width:80%; border:1px solid #000' colspan='2' align='center'><b>Description of Goods / Services</b></td>";
                        $html.= "<td colspan='2'  style='width:18%; border:1px solid #000;padding:10px' align='center'><b>Amount</b></td>";
                    $html.= "</tr>";
                    $html.= "<tr>";
                        $html.= "<td rowspan='15' style='border:1px solid #000;padding:5px' valign='top' align='center'>1</td>";
                        $html.= "<td colspan='2' style='border:1px solid #000;padding:10px 5px 10px 5px'>Construction Services of multi-storied residential buildings - Advance</td>";
                        $html.= "<td colspan='2' style='border:1px solid #000'>";
                    $html.= "</tr>";
                    $html.= "<tr>";
                       
                        $html.= "<td colspan='2' style='border:1px solid #000'></td>";
                        $html.= "<td style='border:1px solid #000; padding:5px' align='center'><b>Rs</b></td>";
                        $html.= "<td style='border:1px solid #000; padding:5px' align='center'><b>P</b></td>";
                    $html.= "</tr>";
  
                    $html.= "<tr>";                      
                        $html.= "<td colspan='2' style='border:1px solid #000; padding:5px'><b>Total Value</b></td>";
                         $html.= "<td style='border:1px solid #000; padding:5px' align='right'><b>".$total."</b></td>";
                         $html.= "<td style='border:1px solid #000; padding:5px' align='right'>0</td>";
                     $html.= "</tr>";
                    
                     $html.= "<tr>";                       
                     $html.= "<td colspan='2' style='border:1px solid #000; padding:5px'>Discount</td>";
                         $html.= "<td style='border:1px solid #000; padding:5px' align='right'></td>";
                         $html.= "<td style='border:1px solid #000; padding:5px' align='right'></td>";
                     $html.= "</tr>";
                     $html.= "<tr>";                       
                         $html.= "<td colspan='2' style='border:1px solid #000; padding:5px'>Land & Construction Value</td>";
                         $html.= "<td style='border:1px solid #000; padding:5px' align='right'>".$lc_amount."</td>";
                         $html.= "<td style='border:1px solid #000; padding:5px' align='right'>0</td>";
                     $html.= "</tr>";
                      $html.= "<tr>";                       
                         $html.= "<td colspan='2' style='border:1px solid #000; padding:5px'>Taxable Value(Rs.)</td>";
                         $html.= "<td style='border:1px solid #000; padding:5px' align='right'>".$tax_amount."</td>";
                         $html.= "<td style='border:1px solid #000; padding:5px' align='right'>0</td>";
                     $html.= "</tr>";
                       $html.= "<tr>";                       
                         $html.= "<td colspan='2' style='border:1px solid #000'>&nbsp;</td>";
                         $html.= "<td style='border:1px solid #000'>&nbsp;</td>";
                         $html.= "<td style='border:1px solid #000'>&nbsp;</td>";
                     $html.= "</tr>";
                         $html.= "<tr>";                       
                         $html.= "<td colspan='2' style='border:1px solid #000; padding:5px'>CGST - 9%</td>";
                         $html.= "<td style='border:1px solid #000; padding:5px' align='right'>".$cgst."</td>";
                         $html.= "<td style='border:1px solid #000; padding:5px' align='right'>0</td>";
                     $html.= "</tr>";
                       $html.= "<tr>";                       
                         $html.= "<td colspan='2' style='border:1px solid #000; padding:5px'>SGST - 9%</td>";
                         $html.= "<td style='border:1px solid #000; padding:5px' align='right'>".$sgst."</td>";
                         $html.= "<td style='border:1px solid #000; padding:5px' align='right'>0</td>";
                     $html.= "</tr>";
                     $html.= "<tr>";                       
                         $html.= "<td colspan='2' style='border:1px solid #000'>&nbsp;</td>";
                         $html.= "<td style='border:1px solid #000'>&nbsp;</td>";
                         $html.= "<td style='border:1px solid #000'>&nbsp;</td>";
                     $html.= "</tr>";
                     $html.= "<tr>";                       
                         $html.= "<td colspan='2' style='border:1px solid #000; padding:5px'><b>Total</b></td>";
                         $html.= "<td style='border:1px solid #000; padding:5px' align='right'>".$roundtotal."</td>";
                         $html.= "<td style='border:1px solid #000; padding:5px' align='right'>0</td>";
                     $html.= "</tr>";
                     $html.= "<tr>";                       
                         $html.= "<td colspan='2' style='border:1px solid #000; padding:5px'><b>Round off</b></td>";
                         $html.= "<td style='border:1px solid #000' align='right'>&nbsp;</td>";
                         $html.= "<td style='border:1px solid #000' align='right'>&nbsp;</td>";
                     $html.= "</tr>";
                     $html.= "<tr>";                       
                         $html.= "<td colspan='2' style='border:1px solid #000'>&nbsp;</td>";
                         $html.= "<td style='border:1px solid #000' align='right'>&nbsp;</td>";
                         $html.= "<td style='border:1px solid #000' align='right'>&nbsp;</td>";
                     $html.= "</tr>";
                     $html.= "<tr>";                       
                         $html.= "<td colspan='2' style='border:1px solid #000; padding:5px'><b>Grand Total(Inclusive of GST)</b></td>";
                         $html.= "<td style='border:1px solid #000; padding:5px' align='right'><b>".$grandtotal."</b></td>";
                         $html.= "<td style='border:1px solid #000; padding:5px' align='right'>0</td>";
                     $html.= "</tr>";
                     $html.= "<tr>";                       
                         $html.= "<td colspan='2' style='border:1px solid #000'>&nbsp;</td>";
                         $html.= "<td style='border:1px solid #000'>&nbsp;</td>";
                         $html.= "<td style='border:1px solid #000'>&nbsp;</td>";
                     $html.= "</tr>";
                      $html.= "<tr>";
                        $html.= "<td style='border:1px solid #000'></td>";                       
                         $html.= "<td style='width:40%;border:1px solid #000' ><b>Invoice Value(Inwords)</b></td>";
                         $html.= "<td colspan='3' style='border:1px solid #000; padding:5px' align='left'><b>Rupees ".$totalword."</b></td>";
                       
                     $html.= "</tr>";
                     $html.= "<tr>";
                        $html.= "<td style='border:1px solid #000'></td>";                       
                       
                         $html.= "<td colspan='3' style='border:1px solid #000; padding:5px' align='center'><b>Amount subject to Reverse Charge</b></td>";
                          $html.= "<td style='border:1px solid #000; padding:5px'>Nil</td>";
                       
                     $html.= "</tr>";
                    
                 $html.= "</table>";
                 $html.= "<table style='width:80%;' align='left' >";
                     $html.= "<tr>";
             $html.= "<td colspan='2'>&nbsp;</td>";
         $html.= "</tr>";
                     $html.= "<tr>";
             $html.= "<td colspan='2'>&nbsp;</td>";
         $html.= "</tr>";
                  
                     $html.= "<tr>";
             $html.= "<td colspan='2' ><b>For South India Shelters Private Limited</b></td>";
         $html.= "</tr>";
          $html.= "<tr>";
             $html.= "<td colspan='2'>&nbsp;</td>";
         $html.= "</tr>";
          $html.= "<tr>";
             $html.= "<td colspan='2'>&nbsp;</td>";
         $html.= "</tr>";
          $html.= "<tr>";
             $html.= "<td colspan='2'><b>Authorise Signatory</b></td>";
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
