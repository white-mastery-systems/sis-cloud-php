<?php 
 $source_file = $_FILES['upload']['tmp_name'];
 $ftp_server='68.233.226.231';//serverip
    $conn_id = ftp_connect($ftp_server); 
    
   
    // login with username and password
    $user="cloud";
    $passwd="Cloud@747";
    $login_result = ftp_login($conn_id, $user, $passwd); 

// check connection
   if ((!$conn_id) || (!$login_result)) { 
        echo "FTP connection has failed!";
        echo "Attempted to connect to $ftp_server for user $ftp_user_name"; 
        die; 
    } else {
        echo "<br>Connected to $ftp_server, for user $user<br>";
    }
//directorylike /www.velibaba.com/images
  ftp_chdir($conn_id, "http://cloud.sis.in/uploads");
 // ftp_chdir($conn_id, "compimages");

//$destination_file=ftp_pwd($conn_id);

$destination_file=$_FILES['upload']['name'];
echo ("<br>");
print $destination_file;


echo ("<br>");

// upload the file
$upload = ftp_put($conn_id, "http://cloud.sis.in/uploads/"$destination_file, $source_file, FTP_BINARY); 

// check upload status
if (!$upload) { 
        echo "FTP upload has failed!";
    } else {
        echo "Uploaded $source_file to $ftp_server as $destination_file";
    }

// close the FTP stream 
ftp_close($conn_id); 








?> 
