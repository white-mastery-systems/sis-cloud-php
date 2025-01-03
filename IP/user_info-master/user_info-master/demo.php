<?php 
require('UserInfo.php');
?>

<!DOCTYPE html>
<html>
<head>
	<title>UserInfo Demo</title>
	<style>
table {
	margin-top: 20px;
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

td, th {
    border: 1px solid #dddddd;
    text-align: center;
    padding: 8px;
}

tr:nth-child(even) {
    background-color: #dddddd;
}
h2{font-family: sans-serif,'Helvetica';}
        
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js" type="text/javascript"></script>



</head>
<body>
<center><h2>UserInfo demo</h2></center>


	<table>
		<tr>
			<th>Ip</th>
			<th>Device</th>
			<th>Os</th>
			<th>Browser</th>
			<th>computer name</th>
			<th>test</th>
		</tr>
		<tr>
			<td> <?= UserInfo::get_ip();?></td>
			<td> <?= UserInfo::get_device();?></td>
			<td><?= UserInfo::get_os();?></td>
			<td><?= UserInfo::get_browser();?></td>
            <td><?= gethostbyaddr(UserInfo::get_ip());?></td>
            <?php print_r( $_SERVER); ?>
            <td><?PHP
echo "<script>$.getJSON('https://api.ipgeolocation.io/ipgeo?apiKey=68994f0ca59a4394977b1474ab0eba8f', function(data) { "; //console.log(JSON.stringify(data, null, 2));";        
       $data1 = "document.write(data.ip)";  
          
                
                
echo "});</script>";
    ?></td>
		</tr>
	</table>

</body>
</html>