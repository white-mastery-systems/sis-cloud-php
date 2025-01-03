<?php
 require('connect.php');
$return_arr = array();
  

    $fetch = mysql_query("SELECT * FROM  productlist where product_code like '%".mysql_real_escape_string($_GET['term'])."%'");

    /* Retrieve and store in array the results of the query.*/
    while ($row = mysql_fetch_array($fetch, MYSQL_ASSOC)) {
	  $row_array['itemCode']     = $row['product_code'];
        $row_array['itemDesc'] 		    = $row['pro_name'];
       	 $row_array['itemBasic_m']     = $row['m_cost'];
		   $row_array['itemBasic_i']     = $row['i_cost'];
		   $row_array['itemHeight']     = $row['pro_height'];
		   $row_array['itemWidth']     = $row['pro_width'];
		 
         array_push( $return_arr, $row_array );
    }

    /* Free connection resources. */
    mysql_close($conn);

    /* Toss back results as json encoded array. */
    echo json_encode($return_arr);

