<?php
ini_set('max_execution_time', 300);
ini_set("memory_limit","1024M");
include "moneyformat.php";

include "writelog.php";
date_default_timezone_set('Asia/Calcutta'); 
$time = date('Y-m-d H:i:s');
$sdate=date('Y-m-d');
$stime=date('H:i:s');
$email = $_GET['invoice_mail'] ;
$po_no = $_GET['po_no'];
$po_year=$_GET['po_year'];
$emailcc= $_GET['emailcc'];
$email_content1= $_GET['email_content1'];
$emailbcc= $_GET['emailbcc'];
$fname='Preethi';
$femail='preethi@sis.in';
$esub=$_GET['esub'];
$time = date('Y-m-d H:i:s');

$email_content=$_GET['email_content'];

$ip = getenv('REMOTE_ADDR');
$userAgent = getenv('HTTP_USER_AGENT');
$referrer = getenv('HTTP_REFERER');
$query = getenv('QUERY_STRING');
require('connect.php');
include('MPDF57/mpdf.php');

$curYear = date('Y');
$nexYear = date('y')+1;
$n = 0;
$mpdf=new mPDF('c','A4','','',10,10,37,37,5,5); 
$mpdf->mirrorMargins = 1;	// Use different Odd/Even headers and footers and mirror margins
//$mpdf->SetHTMLHeader('<div align="right" ><img src="images/head.jpg" align="right"/></div>'); 
//$mpdf->setHTMLFooter('<img src="images/footer.jpg"/>');
$header = '<div align="right"><img src="images/head.jpg" width="126px" /></div>';
$headerE = '<div align="right"><img src="images/head.jpg" width="126px" /></div>';
$footer = '<div align="center"><img src="images/footer.jpg"  /></div>';
$footerE = '<div align="center"><img src="images/footer.jpg"  /></div>';
$mpdf->SetHTMLHeader($header);
//$mpdf->SetHTMLHeader($headerE,'E');
$mpdf->SetHTMLFooter($footer);
//$mpdf->SetHTMLFooter($footerE,'E');

$result1 = mysql_query("SELECT * FROM  invoicetable where invoicenotype='" .$po_no."'",$conn);

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
    
   $gtotal =  $roundtotal-$total;
     $finaltotal =  $roundtotal-$gtotal;
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

  $html.= "<div style='width:100%'>";
     $html.= "<table style='width:100%;page-break-inside: avoid;white-space:nowrap' align='center'>";
        $html.= "<tr>";
            $html.= "<td style='width:60%;white-space:nowrap'>";
            $html.= "<table>";
            $html.= "<tr>";
            $html.= "<td style='white-space:nowrap;font-size:12px '><b>Project</b></td>";
            $html.= "<td style='white-space:nowrap;font-size:12px '>:</td>";
            $html.= "<td style='white-space:nowrap;font-size:12px '><b>".$projectname."</b></td>";
            $html.= "</tr>";
           
            $html.= "<tr>";
            $html.= "<td style='white-space:nowrap;font-size:12px '><b>Flat No</b></td>";
            $html.= "<td style='white-space:nowrap;font-size:12px '>:</td>";
            $html.= "<td style='white-space:nowrap;font-size:12px '><b>".$block."-".$flatno."</b></td>";
            $html.= "</tr>";
           
            $html.= "<tr>";
            $html.= "<td style='white-space:nowrap;font-size:12px '>Buyer Name</td>";
            $html.= "<td style='white-space:nowrap;font-size:12px '>:</td>";
            $html.= "<td style='white-space:nowrap;font-size:12px '><b>".$buyername."</b></td>";
            $html.= "</tr>";
           
           $html.= "<tr>";
            $html.= "<td style='white-space:nowrap;font-size:12px'>Address1</td>";
            $html.= "<td style='white-space:nowrap;font-size:12px'>:</td>";
            $html.= "<td style='white-space:nowrap;font-size:12px'>".$address1."</td>";
            $html.= "</tr>";
           
            $html.= "<tr>";
            $html.= "<td style='white-space:nowrap;font-size:12px'>Address2</td>";
            $html.= "<td>:</td>";
            $html.= "<td style='white-space:nowrap;font-size:12px'>".$address2."</td>";
            $html.= "</tr>";
           
               $html.= "<tr>";
            $html.= "<td style='white-space:nowrap;font-size:12px'>Address3</td>";
            $html.= "<td style='white-space:nowrap;font-size:12px'>:</td>";
            $html.= "<td style='white-space:nowrap;font-size:12px'>".$address3."</td>";
            $html.= "</tr>";
           
           
             $html.= "<tr>";
            $html.= "<td style='white-space:nowrap;font-size:12px'>Tel</td>";
            $html.= "<td style='white-space:nowrap;font-size:12px'>:</td>";
            $html.= "<td style='white-space:nowrap;font-size:12px'>".$contact."</td>";
            $html.= "</tr>";
           
             $html.= "<tr>";
            $html.= "<td style='white-space:nowrap;font-size:12px'>GST / PAN</td>";
            $html.= "<td style='white-space:nowrap;font-size:12px'>:</td>";
            $html.= "<td style='white-space:nowrap;font-size:12px'>".$pannoc."</td>";
            $html.= "</tr>";
                 
            $html.= "</table>";
            $html.= "</td>";
            $html.= "<td>";
            $html.= "<table style='width:40%; border:solid 2px #000'>";
                $html.= "<tr>";
                    $html.= "<td style='white-space:nowrap;font-size:12px'><b>Invoice No</b></td>";
                    $html.= "<td style='white-space:nowrap;font-size:12px'>:</td>";
                    $html.= "<td style='white-space:nowrap;font-size:12px'><b>".$invoicenotype."</b></td>";
                $html.= "</tr>";
                 $html.= "<tr>";
                    $html.= "<td style='white-space:nowrap;font-size:12px'><b>Date</b></td>";
                    $html.= "<td style='white-space:nowrap;font-size:12px'>:</td>";
                    $html.= "<td style='white-space:nowrap;font-size:12px'><b>".$invoicedate."</b></td>";
                $html.= "</tr>";
                 $html.= "<tr>";
                   $html.= "<td style='white-space:nowrap;font-size:12px'>GSTIN</td>";
                   $html.= "<td style='white-space:nowrap;font-size:12px'>:</td>";
                   $html.= "<td style='white-space:nowrap;font-size:12px'>".$gstin."</td>";
               $html.= "</tr>";
                $html.= "<tr>";
                   $html.= "<td style='white-space:nowrap;font-size:12px'>PAN</td>";
                   $html.= "<td style='white-space:nowrap;font-size:12px'>:</td>";
                   $html.= "<td style='white-space:nowrap;font-size:12px'>".$panno."</td>";
               $html.= "</tr>";
                $html.= "<tr>";
                   $html.= "<td style='white-space:nowrap;font-size:12px'>HSN/SAC Code</td>";
                   $html.= "<td style='white-space:nowrap;font-size:12px'>:</td>";
                   $html.= "<td style='white-space:nowrap;font-size:12px'>".$hsn_sac_code."</td>";
               $html.= "</tr>";
           $html.= "</table></td>";
       $html.= "</tr>";
        $html.= "<tr>";
            $html.= "<td colspan='2'>";
                $html.= "<table  cellpadding='0' cellspacing='0'  align='center' style='width:80%; border-collapse: collapse; border: 1px solid black;white-space:nowrap' >";
                    $html.= "<tr>";
                        $html.= "<td style='width:3%; border:1px solid #000; padding:10px;white-space:nowrap' align='center'>S.No</td>";
                        $html.= "<td style='width:70%; border:1px solid #000;white-space:nowrap' colspan='2' align='center'><b>Description of Goods / Services</b></td>";
                        $html.= "<td  style='width:28%; border:1px solid #000;padding:10px;white-space:nowrap' align='center'><b>Amount</b></td>";
                    $html.= "</tr>";
                    $html.= "<tr>";
                        $html.= "<td rowspan='15' style='border:1px solid #000;padding:5px;white-space:nowrap' valign='top' align='center'>1</td>";
                        $html.= "<td colspan='2' style='border:1px solid #000;padding:10px 5px 10px 5px;white-space:nowrap'>Construction Services of multi-storied residential buildings - Advance</td>";
                        $html.= "<td colspan='2' style='border:1px solid #000;white-space:nowrap'>";
                    $html.= "</tr>";
                    $html.= "<tr>";
                       
                        $html.= "<td colspan='2' style='border:1px solid #000;white-space:nowrap'></td>";
                        $html.= "<td style='border:1px solid #000; padding:5px;white-space:nowrap' align='center'><b>Rs</b></td>";
                       
                    $html.= "</tr>";
  
                    $html.= "<tr>";                      
                        $html.= "<td colspan='2' style='border:1px solid #000; padding:5px;white-space:nowrap'><b>Total Value</b></td>";
                         $html.= "<td style='border:1px solid #000; padding:5px;white-space:nowrap' align='right'><b>".$total."</b></td>";
                        
                     $html.= "</tr>";
                    
                     $html.= "<tr>";                       
                     $html.= "<td colspan='2' style='border:1px solid #000; padding:5px;white-space:nowrap'>Discount</td>";
                         $html.= "<td style='border:1px solid #000; padding:5px;white-space:nowrap' align='right'></td>";
                        
                     $html.= "</tr>";
                     $html.= "<tr>";                       
                         $html.= "<td colspan='2' style='border:1px solid #000; padding:5px;white-space:nowrap'>Land & Construction Value</td>";
                         $html.= "<td style='border:1px solid #000; padding:5px;white-space:nowrap' align='right'>".$lc_amount."</td>";
                         
                     $html.= "</tr>";
                      $html.= "<tr>";                       
                         $html.= "<td colspan='2' style='border:1px solid #000; padding:5px;white-space:nowrap'>Taxable Value(Rs.)</td>";
                         $html.= "<td style='border:1px solid #000; padding:5px;white-space:nowrap' align='right'>".$taxamount."</td>";
                        
                     $html.= "</tr>";
                       $html.= "<tr>";                       
                         $html.= "<td colspan='2' style='border:1px solid #000;white-space:nowrap'>&nbsp;</td>";
                         $html.= "<td style='border:1px solid #000;white-space:nowrap'>&nbsp;</td>";
                        
                     $html.= "</tr>";
                         $html.= "<tr>";                       
                         $html.= "<td colspan='2' style='border:1px solid #000; padding:5px;white-space:nowrap'>CGST - 9%</td>";
                         $html.= "<td style='border:1px solid #000; padding:5px;white-space:nowrap' align='right'>".$cgst."</td>";
                         
                     $html.= "</tr>";
                       $html.= "<tr>";                       
                         $html.= "<td colspan='2' style='border:1px solid #000; padding:5px;white-space:nowrap'>SGST - 9%</td>";
                         $html.= "<td style='border:1px solid #000; padding:5px;white-space:nowrap' align='right'>".$sgst."</td>";
                         
                     $html.= "</tr>";
                     $html.= "<tr>";                       
                         $html.= "<td colspan='2' style='border:1px solid #000;white-space:nowrap'>&nbsp;</td>";
                         $html.= "<td style='border:1px solid #000;white-space:nowrap'>&nbsp;</td>";
                         
                     $html.= "</tr>";
                     $html.= "<tr>";                       
                         $html.= "<td colspan='2' style='border:1px solid #000; padding:5px;white-space:nowrap'><b>Total</b></td>";
                         $html.= "<td style='border:1px solid #000; padding:5px;white-space:nowrap' align='right'>".$roundtotal."</td>";
                        
                     $html.= "</tr>";
                     $html.= "<tr>";                       
                         $html.= "<td colspan='2' style='border:1px solid #000; padding:5px;white-space:nowrap'><b>Round off</b></td>";
                         $html.= "<td style='border:1px solid #000;white-space:nowrap; padding:5px' align='right'>".$gtotal."</td>";
                         
                     $html.= "</tr>";
                     $html.= "<tr>";                       
                         $html.= "<td colspan='2' style='border:1px solid #000;white-space:nowrap'>&nbsp;</td>";
                         $html.= "<td style='border:1px solid #000;white-space:nowrap' align='right'>&nbsp;</td>";
                        
                     $html.= "</tr>";
                     $html.= "<tr>";                       
                         $html.= "<td colspan='2' style='border:1px solid #000; padding:5px;white-space:nowrap'><b>Grand Total(Inclusive of GST)</b></td>";
                         $html.= "<td style='border:1px solid #000; padding:5px;white-space:nowrap' align='right'><b>".$grandtotal."</b></td>";
                         
                     $html.= "</tr>";
                     $html.= "<tr>";                       
                         $html.= "<td colspan='2' style='border:1px solid #000;white-space:nowrap'>&nbsp;</td>";
                         $html.= "<td style='border:1px solid #000;white-space:nowrap'>&nbsp;</td>";
                         
                     $html.= "</tr>";
                      $html.= "<tr>";
                        $html.= "<td style='border:1px solid #000;white-space:nowrap'></td>";                       
                         $html.= "<td style='width:40%;border:1px solid #000;white-space:nowrap' ><b>Invoice Value(Inwords)</b></td>";
                         $html.= "<td colspan='2' style='border:1px solid #000; padding:5px;white-space:nowrap' align='left'><b>Rupees ".$totalword."</b></td>";
                       
                     $html.= "</tr>";
                     $html.= "<tr>";
                        $html.= "<td style='border:1px solid #000;white-space:nowrap'></td>";                       
                       
                         $html.= "<td colspan='2' style='border:1px solid #000; padding:5px;white-space:nowrap' align='center'><b>Amount subject to Reverse Charge</b></td>";
                          $html.= "<td style='border:1px solid #000;padding:5px;white-space:nowrap'>Nil</td>";
                       
                     $html.= "</tr>";
                    
                 $html.= "</table>";
                 $html.= "<table style='width:80%;white-space:nowrap' align='left' >";
                   
                     $html.= "<tr>";
             $html.= "<td colspan='2' style='white-space:nowrap'>&nbsp;</td>";
         $html.= "</tr>";
                  
                     $html.= "<tr>";
             $html.= "<td colspan='2' style='white-space:nowrap' ><b>For South India Shelters Private Limited</b></td>";
         $html.= "</tr>";
          $html.= "<tr>";
             $html.= "<td colspan='2' style='white-space:nowrap'>&nbsp;</td>";
         $html.= "</tr>";
          $html.= "<tr>";
             $html.= "<td colspan='2' style='white-space:nowrap'>&nbsp;</td>";
         $html.= "</tr>";
          $html.= "<tr>";
             $html.= "<td colspan='2' style='white-space:nowrap'><b>Authorise Signatory</b></td>";
         $html.= "</tr>";
                 $html.= "</table>";
             $html.= "</td>";
         $html.= "</tr>";
        
        
     $html.= "</table>";
    $html.= " </div>";
$mpdf->WriteHTML($html);
$mpdf->SetDisplayMode('fullpage');

$mpdf->Output("purchaseorder/PO-".$po_no. ".pdf");

//$message = $_REQUEST['message'] ;
require("PHPMailer-master/class.phpmailer.php");
require("PHPMailer-master/PHPMailerAutoload.php");
$mail = new PHPMailer();	
$mail->IsSMTP();
$mail->CharSet="UTF-8";
$mail->Host = 'mail.sis.in';
$mail->Port = 25;
$mail->Username = 'menaka@sis.in';
$mail->Password = 'meenu1089';
$mail->SMTPAuth = true;
$mail->From = $femail;
$mail->FromName = $fname;
$mail->AddAddress($email);
$mail->AddReplyTo($femail);
$mail->addCC($emailcc);
$mail->addBCC($emailbcc);
$mail->addBCC('menaka@sis.in');
$mail->AddAttachment("purchaseorder/PO-".$po_no. ".pdf",'', $encoding = 'base64', $type = 'application/pdf');         // add attachments
$mail->IsHTML(true);
$mail->Subject= $esub;
$mail->Body    = $email_content.'<div><br><img src="http://www.sis.in/Signature_new/preethi.jpg"></div>';

if(!$mail->Send())
{
  echo "Mailer Error: " . $email->ErrorInfo;
}
else
{
  echo "Mail sent!";
 $sqlsent = "INSERT INTO invoicesentitems(fname,femail,temail,tname,subject,tarea,sdate,stime,invoiceno,tarea1,po_year) VALUES ('$fname','$femail','$email','$ven_contactperson','$esub','$email_content','$sdate','$stime','$po_no','$email_content1','$po_year')";

$resultsent = mysql_query($sqlsent,$conn);
  
  $file = "purchaseorder/PO-".$po_no. ".pdf";
$msg = "IP: " . $ip . " TIME: " . $time . " User: " .$_SESSION['st_username'].  " USERAGENT: " .$userAgent. "Action : Purchase order_".$po_no." Mail has been Sent";
writeToLogFile($msg);

  
if (file_exists($file)) {
	unlink("invoice/invoice-".$invoiceno. ".pdf");
    exit;
}

}

   
     
   


?>