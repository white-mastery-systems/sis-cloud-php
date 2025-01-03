<?php
//header part
header("Content-type: application/vinod.ms-word");
header("Content-Disposition: attachment;Filename=MCN.doc");
//starting html tag
echo "<html>";
echo "<meta http-equiv='Content-Type' content='text/html; charset=Windows-1252'>";
//body part start here
echo "<body>";
//print the content
echo "<b>This is my first document created by PHP</b>";
echo "</body>";
//end html tag
echo "</html>";
?>