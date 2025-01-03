<?php
$project_name = $_POST['project_name'];
require('connect1.php');

$result = mysqli_query($conn,"SELECT distinct block FROM clientmaster where projectname='$project_name'");
echo "<option selected value='select'>SELECT</option>";

while ($row = mysqli_fetch_array($result)) 
{
	$block	 = mysqli_real_escape_string($conn,$row['block']);
    echo "<option  value='".$block."'>" .$block. "</option>";
      
        }
  
if($project_name == 'S.I.S Capetown')
    {
echo "|<thead>";
echo "<tr>";
echo "<th>S.No</th>";
echo "<th>Description of Goods / Services</th>";
echo "<th>Amount</th>";

echo "</tr>";
echo "</thead>";
echo "<tbody>";
echo "<tr>";

echo "<td><div class='input-field col s8'>1</div></td>";

echo "<td><div class='input-field col s8'>Plot - Advance</div></td>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "</tr>";

    
echo "<tr>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "<td><div class='input-field col s8'>Total</div></td>";
echo "<td><div class='input-field col s8'>
<input type='text' name='plotcost' id='plotcost' class='changesNop resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required placeholder='Plot Cost'>  
</div></td>";
echo "</tr>";
    
echo "<tr>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "<td><div class='input-field col s8'>Invoice Total</div></td>";
echo "<td><div class='input-field col s8'>
<input type='text' name='invoicetotal' id='invoicetotal' class='changesNop resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required placeholder='Invoice Total'>  
</div></td>";
    
    
echo "</tr>";
    
echo "<tr>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "<td><div class='input-field col s8'>Total</div></td>";
echo "<td><div class='input-field col s8'>
<input type='text' name='subvalue' id='subvalue' class='changesNop resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required placeholder='Payment'>    <span style='color:red' id='err'></span>  
<input type='hidden' name='lcvalue' id='lcvalue' class='changesNop resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required placeholder='Land and Construction'  readonly> 
</div></td>"; 
echo "</tr>";
    
echo "<tr>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "<td><div class='input-field col s8'>Land Value</div></td>";
echo "<td><div class='input-field col s8'>
<input type='text' name='landvalue' id='landvalue' class='changesNop resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required placeholder='Payment'> 
</div></td>"; 
echo "</tr>";


echo "<tr>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "<td><div class='input-field col s8'>Land Area</div></td>";
echo "<td><div class='input-field col s8'>
<input type='text' name='larea' id='larea' class='changesNop resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required placeholder='Land and Construction'  readonly >  </div></td>";    
echo "</tr>";    
    
    
echo "<tr>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "<td><div class='input-field col s8'>Guideline value</div></td>";
echo "<td><div class='input-field col s8'>
<input type='text' name='gvalue' id='gvalue' class='changesNop resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required placeholder='Guideline Value' >  
<input type='hidden' name='landcost' id='landcost' class='changesNop resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required placeholder='Land and Construction'  readonly  >

</div></td>";    
echo "</tr>";
     

echo "<tr>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "<td><div class='input-field col s8'>Registration Charge</div></td>";
echo "<td><div class='input-field col s8'>
<input type='text' name='regcharge' id='regcharge' class='changesNop resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required placeholder='Taxable Value'  readonly>  </div></td>";
echo "<input type='hidden' name='landtotal' id='landtotal' class='changesNop resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required placeholder='Guideline Value' >";  
echo "</tr>";
    
echo "<tr>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "<td><div class='input-field col s8'>Developement Charges</div></td>";
echo "<td><div class='input-field col s8'><input type='text' name='developmentcharge' id='developmentcharge' class='changesNop resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required placeholder='Development Charge' readonly> </div></td>";
echo "</tr>";

echo "<tr>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "<td><div class='input-field col s8'>CGST - 9%</div></td>";
echo "<td><div class='input-field col s8'>
<input type='hidden' name='taxvalue' id='taxvalue' class='changesNop resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required placeholder='Taxable Value'  readonly> 
<input type='text' name='cgst' id='cgst' class='changesNop resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required placeholder='CGST' readonly>  </div></td>";    
echo "</tr>";

echo "<tr>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "<td><div class='input-field col s8'>SGST - 9%</div></td>";
echo "<td><div class='input-field col s8'>
<input type='text' name='sgst' id='sgst' class='changesNop resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required placeholder='SGST' readonly>  </div></td>";    
echo "</tr>";


echo "<tr>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "<td><div class='input-field col s8'>Total Round off</div></td>";
echo "<td><div class='input-field col s8'>
<input type='text' name='roundvalue' id='roundvalue' class='changesNop resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required placeholder='Round Off'  readonly>  </div></td>";    
echo "</tr>";



echo "<tr>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "<td><div class='input-field col s8'>Grand Total(Inclusive of GST)</div></td>";

echo "<td><div class='input-field col s8'>
<input type='text' name='grandtotal' id='grandtotal' class='changesNop resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required placeholder='Grand Total'  readonly>  </div></td>";    
echo "</tr>";



echo "<tr>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "<td><div class='input-field col s8'>Invoice Value(Inwords)</div></td>";
echo "<td><div class='input-field col s8'><b><span id='grandtotalwords'></span><input type='hidden' name='totalword' id='totalword'></b>  </div></td>";    
echo "</tr>";

echo "</tbody>";
    
   }




else
{
  echo "|<thead>";
echo "<tr>";
echo "<th>S.No</th>";
echo "<th>Description of Goods / Services</th>";
echo "<th>Amount</th>";

echo "</tr>";
echo "</thead>";
echo "<tbody>";
echo "<tr>";

echo "<td><div class='input-field col s8'>1</div></td>";

echo "<td><div class='input-field col s8'>Construction services of multi-storied residential buildings - Advance</div></td>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "</tr>";

echo "<tr>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "<td><div class='input-field col s8'>Total</div></td>";
echo "<td><div class='input-field col s8'>

<input type='text' name='subvalue' id='subvalue' class='changesNop resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required placeholder='Total'></div></td>";
    
echo "</tr>";

echo "<tr>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "<td><div class='input-field col s8'>Discount</div></td>";
echo "<td><div class='input-field col s8'>
</div></td>";    
echo "</tr>";

echo "<tr>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "<td><div class='input-field col s8'>Land and Construction value</div></td>";
echo "<td><div class='input-field col s8'>

<input type='text' name='lcvalue' id='lcvalue' class='changesNop resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required placeholder='Land and Construction'  disable >  </div></td>";
    
echo "</tr>";

echo "<tr>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "<td><div class='input-field col s8'>Taxable Value</div></td>";
echo "<td><div class='input-field col s8'>

<input type='text' name='taxvalue' id='taxvalue' class='changesNop resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required placeholder='Taxable Value'  readonly >  </div></td>";
    
echo "</tr>";

echo "<tr>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "<td><div class='input-field col s8'> </div></td>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";    
echo "</tr>";

echo "<tr>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "<td><div class='input-field col s8'>CGST - 9%</div></td>";
echo "<td><div class='input-field col s8'>
<input type='text' name='cgst' id='cgst' class='changesNop resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required placeholder='CGST' readonly >  </div></td>";    
echo "</tr>";

echo "<tr>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "<td><div class='input-field col s8'>SGST - 9%</div></td>";
echo "<td><div class='input-field col s8'>
<input type='text' name='sgst' id='sgst' class='changesNop resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required placeholder='SGST' readonly >  </div></td>";    
echo "</tr>";


echo "<tr>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "<td><div class='input-field col s8'>Total Round off</div></td>";
echo "<td><div class='input-field col s8'>
<input type='text' name='roundvalue' id='roundvalue' class='changesNop resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required placeholder='Round Off'  readonly >  </div></td>";    
echo "</tr>";



echo "<tr>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "<td><div class='input-field col s8'>Grand Total(Inclusive of GST)</div></td>";

echo "<td><div class='input-field col s8'>
<input type='text' name='grandtotal' id='grandtotal' class='changesNop resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required placeholder='Grand Total'  readonly >  </div></td>";    
echo "</tr>";



echo "<tr>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "<td><div class='input-field col s8'>Invoice Value(Inwords)</div></td>";
echo "<td><div class='input-field col s8'><b><span id='grandtotalwords'></span><input type='hidden' name='totalword' id='totalword'></b>  </div></td>";    
echo "</tr>";

echo "</tbody>";  
}


   
 ?>