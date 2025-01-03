
   <?php
  
     
include "connect.php";
date_default_timezone_set('Asia/Calcutta'); 
$time = date('Y-m-d H:i:s');
$po_no=$_GET['po_no'];
$curYear = date('Y');
$nexYear = date('y')+1; 
$n = 0;
$result1 = mysql_query("SELECT * FROM  purchase_master where po_no" .$po_no,$conn);
if($row1 = mysql_fetch_array($result1))
	 {
	$project_id=$row1['project_id'] ;
	$ven_id=$row1['ven_id'];
	$vat_o=$row1['vat'];
	$subtotal=$row1['subtotal'];
	$ddate=$row1['ddate'];
	$ed=$row1['ed'];
	$st=$row1['st'];
	$tax=$row1['tax'];
	$transportation=$row1['transportation'];
	$basictotal=$row1['basictotal'];
	$itotal=$row1['itotal'];
	$gt=$row1['gt'];
	$gt1=$row1['gt1'];
	$po_date=$row1['po_date'];
	$stotal = $row1['stotal'];
	 }
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
                
                echo "<div  style='position:relative; top:40px;width:auto; padding-top:10px' class='left leftspace'><a href='order.php' class='btn-floating btn-large waves-effect waves-light'><i class='material-icons'>arrow_back</i></a></div>
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
echo "<table class='table table-full' id='itemsTable'>";
echo "<thead>";
echo "<tr>";
echo "<th><input type='checkbox' id='check_all' class='case' /><label for='check_all'></label></th>";
echo "<th><b>Code</b></th>";
echo "<th><b>width</b></th>";
echo "<th><b>height</b></th>";
echo "<th><b>unit</b></th>";
echo "<th><b>type</b></th>";
echo "<th colspan='2'><b>Material Cost</b></th>";
echo "<th colspan='2'><b>Installation Cost</b></th>";
echo "<th><b>Amount</b></th>";
echo "</tr>";
echo "<tr>";
echo "<th></th>";
echo "<th>&nbsp;</th>";
echo "<th></th>";
echo "<th></th>";
echo "<th></th>";
echo "<th></th>";
echo "<th><b>Basic</b></th>";
echo "<th><b>Total</b></th>";
echo "<th><b>Basic</b></th>";
echo "<th><b>Total</b></th>";
echo "<th><b>Total</b></th>";
echo "</tr>";
echo "</thead>";
echo "<tbody>";
$result_r = mysql_query("SELECT * FROM  purchaseorder_tbl where Po_no=" .$po_no,$conn);
		
while($row = mysql_fetch_array($result_r))
 {
	$productname = $row['product_name'] ;
	$product_qty=$row['product_qty'];
	$pro_height = $row['pro_height'];
	$pro_width = $row['pro_width'];
	$m_basic = $row['m_basic'];
	$m_total = $row['m_total'];
	$i_basic = $row['i_basic'];
	$i_total = $row['i_total'];
	$Amount = $row['Amount'];
	$product_code = $row['product_code'];
		$po_date = $row['po_date'];
		$productname=$row['product_name'];
		
		$n+=1;
echo "<tr><td>".$n."</label></td>";
echo "<td><div class='input-field col s8'> ".$product_code."</div></td>";
echo "<td><div class='input-field col s8'>".$pro_width."</div></td>";
echo "<td><div class='input-field col s8'>".$pro_height."</div></td>";
echo "<td><div class='input-field col s8'>".$product_qty."</div></td>
<td><div class='input-field col s8'>".$productname."</div></td>";
echo "<td><div class='input-field col s8'>".$m_basic."</div></td>
<td><div class='input-field col s8'>".$m_total."</div></td>";
echo "<td><div class='input-field col s8'>".$i_basic."</div></td>
<td><div class='input-field col s8'>".$i_total."</div></td>";
echo "<td><div class='input-field col s8'>".$Amount."</div></td>";

echo "</tr>";
 }

echo "<tr>";
echo "<td></td>";
echo "<td></td>";
echo "<td></td>";
echo "<td></td>";
echo "<td>BC</td>";
echo "<td><div class='input-field col s9'>".$basictotal."</td>";
echo "<td></td>";
echo "<td></td>";
echo "<td></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
echo "<tr>";
echo "<td></td>";
echo "<td></td>";
echo "<td></td>";
echo "<td></td>";
echo "<td>ED</td>";
echo "<td><div class='input-field col s9'>".$ed."</div></td>
<td></td>
<td></td>
<th></td>
<td></td>
<td></td>
</tr>

<tr>
<td></td>
<td></td>
<td></td>
<td></td>
<td>Vat</td>
<td><div class='input-field col s9'>".$tax."</div></td>
<td></td>
<td></td>
<th></td>
<td></td>
<td></td>
</tr>
<tr>
<td></td>
<td></td>
<td></td>
<td></td>
<td>Transpotation</td>
<td><div class='input-field col s9'>".$transportation."</div></td>
<td></td>
<td></td>
<th></td>
<td></td>
<td></td>
</tr>
<tr>
<td></td>
<td></td>
<td></td>
<td></td>
<td>ST</td>
<td><div class='input-field col s9'>".$st."</div></td>
<td>&nbsp;</td>
<td></td>
<th></td>
<td></td>
<td></td>
</tr>


<tr>
<td></td>
<td></td>
<td></td>
<td></td>
<td>Grand Total</td>
<td><div class='input-field col s9'>".$gt."</div></td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td class='cyan white-text'><div class='input-field col s9'>".$gt1."</div></td>
<td class='cyan strong white-text'><div class='input-field col s9'>".$subtotal."</div></td>
</tr>";
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
echo "The above price is net price inclusive of all Taxes and Transport Charges.The Other terms and conditions remains the same as per your Quote.";
echo "<p class='topalign'><b>Site contact Person : " .$contactname.  " : " .$mobilno. "</b></p>";
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