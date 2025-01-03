<?php
 require('connect.php');
$return_arr = array();
  

    $fetch = mysql_query("SELECT * FROM  productlist where pro_name like '%".mysql_real_escape_string($_GET['term'])."%'");

    /* Retrieve and store in array the results of the query.*/
    while ($row = mysql_fetch_array($fetch, MYSQL_ASSOC)) {

          $row_array['itemDesc'] 		    = $row['pro_name'];
          $row_array['itemPrice']      	= $row['Product_price'];
		  $row_array['cat_name']      	= $row['category_name'];
		  $row_array['sub_catname']     = $row['sub_catname'];
		  $row_array['brand']     = $row['brand'];
		  $row_array['product_code']     = $row['product_code'];
		  $row_array['size']     = $row['size'];
		 
         array_push( $return_arr, $row_array );
    }

    /* Free connection resources. */
    mysql_close($conn);

    /* Toss back results as json encoded array. */
    echo json_encode($return_arr);

