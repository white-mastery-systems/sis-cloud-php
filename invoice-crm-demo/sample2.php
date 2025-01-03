<?php
 header("Content-type: application/vnd.ms-word");
    header("Content-Disposition: attachment; Filename=yourcoolwordfile.doc");
echo "<html>";
echo "<meta http-equiv='Content-Type' content='text/html; charset=Windows-1252'>";
echo "<body>";
echo "<b>testdata1</b> \t <u>testdata2</u> \t \n ";
echo "</body>";
echo "</html>";
?>