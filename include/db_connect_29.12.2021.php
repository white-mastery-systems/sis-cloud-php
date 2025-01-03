<?php
class DbConnect {

    private $conn;

    function __construct() 
    {        
    }

    function connect() 
    {
        require '../vendor/autoload.php';
        
    $MongoClient = new MongoDB\Client("mongodb://sis_crm_user:Welcome*747@151.106.7.18:27017/sis_crm_DB");
        $this->conn = $MongoClient->selectDatabase("sis_crm_DB");
   //  $MongoClient = new MongoDB\Client("mongodb://sis_crm_newuser:Welcome*747@151.106.7.18:27017/sis_crm_newDB");
   // $this->conn = $MongoClient->selectDatabase("sis_crm_newDB");
		
        return $this->conn;
    }

}
?>