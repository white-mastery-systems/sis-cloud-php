<?php
$empId = (int)$_GET['emp_id'];
$directorId = (int)$_GET['director_id'];
error_reporting(0);

$month=date("d-m-Y"); 

require_once '../../vendor/autoload.php';
include "../../include/conn.php";

$mpdf = new \Mpdf\Mpdf();

$mpdf->mirrorMargins = 1;
$header = '<div align="right"></div>';
$footer = '<div align="center"></div>';
$mpdf->SetHTMLHeader($header);
$mpdf->SetHTMLFooter($footer);
$mpdf->SetHTMLHeader($header,'E');
$mpdf->SetHTMLFooter($footer,'E');

$collection = $db->salary_history;

$directorData = $collection->findOne(array("emp_id" => $directorId));

$cursor = $collection->aggregate(array(
    array('$match' => array(
        "emp_id" => $empId
    )),
    array( '$sort' => array( 
        '_id' => -1
    )),
    array( '$limit' => 3 )
));
if($cursor)
{
    $html = '<style>table{ border-collapse:collapse; }</style>';
    foreach($cursor as $rowData)
    {
        $html .= "
        <div style='font-size:15px; font-family:cambria;'>
		  <div  style='font-family:cambria;margin-top:48px;border:1.5px solid #000;border-bottom:none; padding-left:10px;line-height:1px;text-align:justify;padding-bottom:5px;'>
            <p>Pay slip for  the month of ".$rowData['salary_details_data'][$i]['monthname']."-".$rowData['salary_details_data'][$i]['salyear']." <br></p>
            <table style='font-size:15px; font-family:cambria;'>
                <tr><td>Employee ID</td><td>:</td><td>".$rowData['emp_id']."</td></tr>
                <tr><td> Name</td><td>:</td><td>".$rowData['firstname']." ".$rowData['lastname']."</td></tr><br>
                <tr><td>Designation</td><td>:</td><td>".$rowData['designation']."</td></tr>
                <tr><td>Department</td><td>:</td><td>".$rowData['department']."</td></tr>
            </table>
          </div>
        </div>
        <table cellpadding='0' cellspacing='0'  border='0' style='border:1px solid black;width:100%; padding:15px;font-size:18px;' >
            <tr>
                <td style='border-right:solid 1px #000;border-bottom:solid 1px #000;padding:15px;' align='center'>Earnings</td>
                <td style='border-right:solid 1px #000;border-bottom:solid 1px #000;padding:15px;' align='center'>Amount(Rs.)</td>
                <td style='border-right:solid 1px #000;border-bottom:solid 1px #000;padding:15px;' align='center'>Deduction</td>
                <td style='border-right:solid 1px #000;border-bottom:solid 1px #000;padding:15px;' align='center'>Amount(Rs.)</td>
            </tr>
            <tr>
                <td style='border-bottom:none; border-right:solid 1px #000;padding-bottom:20px; padding-left:5px;'>Basic</td>
                <td style='text-align:right;border-right:solid 1px #000;padding-left:90px;float:right;padding-right:5px;'>".$rowData['salary_details_data'][$i]['basicpay']."</td>
                <td style='border-bottom:none;border-right:solid 1px #000;padding-left:5px;' >PF</td>
                <td style='text-align:right;border-bottom:none;border-right:solid 1px #000;padding-left:90px;float:right;padding-right:5px;'>".$rowData['salary_details_data'][$i]['pf']."</td>
            </tr>
            <tr>
                <td style='border-right:solid 1px #000;padding-bottom:20px;padding-left:5px;'>HRA</td>
                <td style='text-align:right;border-right:solid 1px #000;padding-left:90px;float:right;padding-right:5px;'>".$rowData['salary_details_data'][$i]['hra']."</td>
                <td style='border-right:solid 1px #000; padding-left:5px;'>PT</td>
                <td style='text-align:right;padding-left:90px;padding-right:5px;'>".$rowData['salary_details_data'][$i]['pt']."</td>
            </tr>
            <tr>
                <td style='border-right:solid 1px #000;padding-bottom:20px;padding-left:5px;'>Conveyance</td>
                <td style='text-align:right;border-right:solid 1px #000;padding-left:90px;float:right;padding-right:5px;'>".$rowData['salary_details_data'][$i]['conveyance']."</td>
                <td style='border-right:solid 1px #000;padding-left:30px;padding-left:5px;'>Advance</td>
                <td style='text-align:right;padding-left:90px;padding-right:5px;'>".$rowData['salary_details_data'][$i]['advance']."</td>
            </tr>
            <tr>
                <td style='border-right:solid 1px #000;padding-bottom:20px;padding-left:5px;'>Flexible Benefits  plan</td>
                <td style='text-align:right;border-right:solid 1px #000;padding-left:90px;float:right;padding-right:5px;'>".$rowData['salary_details_data'][$i]['flexibleplan']."</td>
                <td style='border-right:solid 1px #000;padding-left:30px;padding-left:5px;'>IT</td>
                <td style='text-align:right;padding-left:90px;padding-right:5px;float:right;'>".$rowData['salary_details_data'][$i]['it']."</td>
            </tr>
            <tr>
                <td style='border-right:solid 1px #000;padding-bottom:20px;padding-left:5px;'>others</td>
                <td style='text-align:right;border-right:solid 1px #000;padding-left:90px;float:right;'>".$rowData['salary_details_data'][$i]['others']."</td>
                <td style='border-right:solid 1px #000;padding-left:30px;padding-left:5px;'></td>
            </tr>
            <tr>
                <td style='border:solid 1px #000;border-bottom:none;border-right:none;border-left:none;padding-top:5px;padding-bottom:5px;padding-left:5px;'>Total</td>
                <td style='border:solid 1px #000; border-bottom:none;border-right:none;padding-top:5px;padding-bottom:5px;padding-left:90px;float:right;padding-right:5px;text-align:right;'>".$rowData['salary_details_data'][$i]['total_amount']."</td>
                <td style='border:solid 1px #000;border-bottom:none;border-right:none;padding-top:5px;padding-bottom:5px;'></td>
                <td style='border:solid 1px #000;border-bottom:none;border-right:none;padding-left:90px;float:right;padding-right:5px;text-align:right;'>".$rowData['salary_details_data'][$i]['amount']."</td>
            </tr>
            <tr>
                <td style='border:solid 1px #000;border-bottom:none;border-right:none;border-left:none;padding-top:5px;padding-bottom:5px;padding-left:5px;'><b>TOTAL EARNED for  the MONTH</b></td>
                <td style='border:solid 1px #000;border-bottom:none;border-right:none;padding-top:5px;padding-bottom:5px;padding-left:90px;text-align:right;padding-right:5px;'>".$rowData['salary_details_data'][$i]['totalearnedamount']."</td>
                <td style='border:solid 1px #000;border-bottom:none;border-right:none;padding-top:5px;padding-bottom:5px;padding: 15px;'></td>
                <td style='border:solid 1px #000;border-bottom:none;border-right:none;padding-top:5px;padding-bottom:5px;padding: 15px;'></td>
			</tr>
        </table>
        <div style='margin-top:20px;'>
			<table width='100%' style='border:solid 1px #000;'>
                <tr>
			     <td style='padding-top:10px;padding-bottom:10px;padding-left:50px;'>Days- Nwd-".$rowData['salary_details_data'][$i]['ndw']."</td>
			     <td>NDW-".$rowData['salary_details_data'][$i]['dw']."</td>
			     <td>LOP-".$rowData['salary_details_data'][$i]['los']."</td>
			    </tr>
			</table>
			<div style='margin-top:35px;'>For <b>South India Shelters Pvt Ltd</b></br></div>
			<div style='margin-top:60px;padding-left:8px;'>
			 <b>".$directorData['lastname'].".".$directorData['firstname']."<br>".$directorData['designation']."</b>
			</div>
        </div>";
                    
        $mpdf->AddPage('','A4','','','',10,10,55,37,5,5);
        $mpdf->WriteHTML($html);
        $mpdf->SetDisplayMode('fullpage');
    }
}
$mpdf->Output('salaryslip'.$empId.'.pdf', 'I');
?>