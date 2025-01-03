<?php
$project_name = $_POST['project_name'];
require('connect.php');

$result = mysql_query("SELECT distinct block FROM clientmaster where projectname='$project_name'",$conn);
echo "<option selected value='select'>SELECT</option>";

while ($row = mysql_fetch_array($result)) 
{
	$block	 = mysql_real_escape_string($row['block']);
    
  echo "<option  value='".$block."'>" .$block. "</option>| ";
 echo "<div class='row'>";
echo "<div class='col s12 m12 l12 table-responsive '>";
echo "<table class='table table-full' id='itemsTable'>";
echo "<thead>";
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

<input type='text' name='subvalue' id='subvalue' class='changesNop resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required placeholder='Total'> </div></td>";
    
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

<input type='text' name='lcvalue' id='lcvalue' class='changesNop resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required placeholder='Land and Construction'  readonly>  </div></td>";
    
echo "</tr>";

echo "<tr>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "<td><div class='input-field col s8'>Taxable Value</div></td>";
echo "<td><div class='input-field col s8'>

<input type='text' name='taxvalue' id='taxvalue' class='changesNop resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required placeholder='Taxable Value'  readonly>  </div></td>";
    
echo "</tr>";

echo "<tr>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "<td><div class='input-field col s8'><input type='hidden' name='gstper' id='gstper' class='changesNop resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required placeholder='CGST' readonly> </div></td>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";    
echo "</tr>";

echo "<tr>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "<td><div class='input-field col s8'>CGST - 9%</div></td>";
echo "<td><div class='input-field col s8'>
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
echo "</table>";
echo "</div>";
echo "</div>";

echo "<div class='col12 s12 m6 l6 left leftspace'>";
echo "<p>For South India Shelters Private Limited, <br />
 </p>
&nbsp;
<br>
&nbsp;
 <p class='header'>Authorised Signatory</p>

</div>
</div>
</div>";    
  
   }

   
 ?>