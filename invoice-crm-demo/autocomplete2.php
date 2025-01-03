<?php
include "connect.php";

    $term = $_GET["term"];
    $fetch = mysql_query("SELECT * FROM  productlist where pro_name like '% as %'");
    $json = array();

   while ($row = mysql_fetch_array($fetch)) {
        $json[] = array(
            'value'=> $row["pro_name"],
            'label'=> $row["pro_name"],
            'id'=> $row["Product_price"]
        );
    }

    echo json_encode($json);
?>