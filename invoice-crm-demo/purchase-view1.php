
   <?php
  
     
include "connect.php";
date_default_timezone_set('Asia/Calcutta'); 
$time = date('Y-m-d H:i:s');
$po_no=$_GET['po_no'];
$curYear = date('Y');
$nexYear = date('y')+1;

$n = 0;


$result1 = mysql_query("SELECT * FROM  purchase_master where po_no=" .$po_no,$conn);
if($row1 = mysql_fetch_array($result1))
	 {
	$project_id=$row1['project_id'] ;
	$ven_id=$row1['ven_id'];
	$vat_o=$row1['vat'];
	$amounttobepaid=$row1['amounttobepaid'];
	$subtotal=$row1['subtotal'];
	$amountdue=$row1['amountdue'];
	$ddate=$row1['ddate'];
	$po_date=$row1['po_date'];
	$taxamount=$row1['taxamount'];
	$totalAftertax=$row1['totalAftertax'];
    
     $result2 = mysql_query("SELECT * FROM  vendor_tbl where ven_id=" .$ven_id,$conn);
	if($row2= mysql_fetch_array($result2))
	 {
		$ven_id1 = $row2['ven_id'] ;
	    $ven_compname = $row2['ven_compname'] ;
	    $ven_add1=$row2['ven_add1'];
		$ven_add2=$row2['ven_add2'];
	    $ven_city=$row2['ven_city'];
		$ven_pincode=$row2['ven_pincode'];
		$ven_state=$row2['ven_state'];
		$ven_country=$row2['ven_country'];
		$ven_contactperson=$row2['ven_contactperson'];
		}
		else
		{
			echo "Error";
	    }
	    }
		$result = mysql_query("SELECT * FROM project_details where project_id=" .$project_id,$conn);
	if($row= mysql_fetch_array($result))
	 {
	    $project_name = $row['project_name'] ;
	    $place=$row['place'];
		$tinno=$row['tinno'];
	    $cstno=$row['cstno'];
		$address=$row['address'];
		$contactname=$row['contactname'];
		$mobilno=$row['mobilno'];
		$payment=$row['payment'];
			
		}
		else
		{
			echo "Error";
	 }

		$projectname = substr("$project_name", 6, 1);
echo "<div id='breadcrumbs-wrapper' class=' grey lighten-3'>";
          //Search for small screen
          
              
          echo "<div class='container-title'>";
             echo "<div class='row'>";
               echo "<div class='col s12 m12 l12'>";
                 echo "<h5 class='breadcrumbs-title'>Invoice</h5>";
                
                echo "<div  style='position:relative; top:40px;width:auto; padding-top:10px' class='left leftspace'><a class='btn-floating btn-large waves-effect waves-light'><i class='material-icons'>arrow_back</i></a></div>
              </div>
            </div>
          </div>
        </div>";
      //breadcrumbs end


      //start container
         echo "<div class='container'>";
           echo "<div id='invoice'>";
             echo "<div class='invoice-header'>";
               echo "<div class='row section'>";
                 echo "<div class='col12 s12 m12 l6 right rightspace'><img src='images/SISLOGO.png' />";
                  
                echo "</div>";
 echo " </div>";
 echo "</div>";
 echo "<div class='invoice-lable'>";
 echo "<div class='row'>";
 echo "<div class='col12 s12 m6 l3 cyan'>";
 
 
 echo "<div class='col s12 m6 l6 cyan'><h4 class='white-text invoice-text leftspace'><b>F/PUR/SIS-PO/".$projectname."/".$po_no. "/". $curYear."-".$nexYear."</b></h4></div>";
echo "<div class='col s12 m6 l6 cyan'><h4 class='white-text invoice-text right rightspace'>".$po_date."</h4></div>";


 echo "</div>";

  echo "</div>";
 echo "</div>";
 echo "<div class='invoice-table'>";
 echo "<div class='row'>";
 echo "<div class='col s12 m12 l12'>";
 echo "<table class='table table-full'>";
 echo "<thead>";
 echo "<tr>";
  echo "<th><b>No</b></th>";
 echo "<th><b>Description</b></th>";
 echo "<th><b>Qty</b></th>";
 echo "<th><b>Price</b></th>";
  echo "<th><b>VAT</b></th>";
 echo "<th><b>Total</b></th>";
echo "</tr>";
echo "</thead>";
echo "<tbody>";
$result_r = mysql_query("SELECT * FROM  purchaseorder_tbl where Po_no=" .$po_no,$conn);
	 include "moneyformat.php";
while($row = mysql_fetch_array($result_r))
 {
	$productname = $row['product_name'] ;
	$product_qty=$row['product_qty'];
	$ratesperproduct = $row['ratesperproduct'];
	$vat = $row['vat'];
	$Amount = $row['Amount'];
		$po_date = $row['po_date'];
		$n+=1;
                      echo "<tr>";                
echo "<tr><td >".$n."</td>";
echo "<td>" .$productname. "</td>";
echo "<td>" .$product_qty. "</td>";
echo "<td> " .moneyFormatIndia($ratesperproduct). "</td>";
echo "<td>" .$vat. "</td>";	
echo "<td>" .moneyFormatIndia($Amount). "</td>";
echo "</tr>";
 }

echo "<tr>";
echo "<td colspan='4' class='cyan strong white-text'></td>";
echo "<td class='cyan white-text'>Grand Total</td>";
echo "<td class='cyan strong white-text'>".moneyFormatIndia($subtotal)."</td>";
echo "</tr>";
echo "</tbody>";
echo "</table>";
echo "</div>";
echo "</div>";
echo "</div>";
echo "<div class='invoice-footer'>";
echo "<div class='row'>";
echo "<div class='col12 s12 m12 l6 leftspace lineheight'>";

echo "<b>Term and Condition:</b><br />";
echo "<span>Tin No :" .$tinno. "<br />";	
echo "<span>CST No : </span>" .$cstno. "<br />";
echo "<span>Payment : </span>" .$payment. "<br />";  
echo "<span>Delivery : </span>Door Dellivery to our" .$place. " Site  <br /> ";
echo "<span>Loading & Vat :" .$vat_o. "<br />";
echo "<span>Please Supply by " .$ddate. "at our ".$place. "Site<br />";
echo "The above price is net price inclusive of all Taxes and Transport Charges.The Other terms and conditions remains the same as per your Quote. <br />";
echo "<b>Site contact Person : " .$contactname.  " : " .$mobilno. "</b><br/>";
echo  "<b>Site address : </b>";
echo   "<b>".$project_name. "</b><br />";
echo "<div style='width:300px; padding-left:87px'>" .$address. "</br></div>" ;	
echo "</p>";
echo " </div>
                <div class='col12 s12 m6 l6 left leftspace'>
                  <p>Thanking you, <br />
				  Yours Sincerely<br />
				  </p>
                 <img src='images/anisa_digi.jpg' width='83' height='57' />
                  <p class='header'>Anisa Fathima.H</p>
                  <p>Purchase Manager</p>
                </div>
              </div>
            </div>
            
          </div>";
    //Floating Action Button -->
           
           // Floating Action Button -->
      echo "</div>";
	  
	  

       ?>