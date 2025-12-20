<?php
class DbConnect {

    private $conn;

    function __construct() 
    {        
    }

    function connect() 
    {
        require '../vendor/autoload.php';
        
    $MongoClient = new MongoDB\Client("mongodb://sis_crm_user:Welcome*747@139.99.120.182:27017,151.106.7.74:27017,162.252.81.226:27017/sis_crm_DB?replicaSet=mongodineamik&readPreference=primaryPreferred");
    $this->conn = $MongoClient->selectDatabase("sis_crm_DB");
   //  $MongoClient = new MongoDB\Client("mongodb://sis_crm_newuser:Welcome*747@151.106.7.18:27017/sis_crm_newDB");
   // $this->conn = $MongoClient->selectDatabase("sis_crm_newDB");
		
        return $this->conn;
    }

}
?>