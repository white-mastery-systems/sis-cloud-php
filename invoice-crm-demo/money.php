<?php
include "money1.php";
setlocale(LC_MONETARY, 'en_IN.UTF-8');
echo "<br/>".money_format('The price Indian rupee is %n', 250250.75);
?>