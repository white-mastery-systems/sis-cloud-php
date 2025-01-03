<?php
$project_name = $_POST['project_name'];

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

echo "<td><div class='input-field col s8'>Rent of the Month <input type='text' name='monthval' id='monthval' class='resetThis' required placeholder='Month'></div></td>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "</tr>";

echo "<tr>";
echo "<td><div class='input-field col s8'>&nbsp;</div></td>";
echo "<td><div class='input-field col s8'>Amount</div></td>";
echo "<td><div class='input-field col s8'>

<input type='text' name='subvalue' id='subvalue' class='changesNop resetThis' autocomplete='off' onKeyPress='return IsNumeric(event);' ondrop='return false;' onpaste='return false;' required placeholder='Total'></div></td>";
    
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



   
 ?>