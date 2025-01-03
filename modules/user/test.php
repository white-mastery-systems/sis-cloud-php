<?php
require_once '../../vendor/autoload.php';
include "../../include/conn.php";
date_default_timezone_set("Asia/Kolkata");

/*$collectionInv =$db->inventory;
$collectionPur = $db->purchase_table;*/

$cursor = $collectionPur->find();
if($cursor)
{
    foreach($cursor as $rowData)
    {
        $collectionInv->updateOne(
            array("po_number" => $rowData["po_number"], "code" => $rowData["code"], "item" => $rowData["item"]),
            array('$set' => array(
                "sgst" => $rowData["sgst"],
                "cgst" => $rowData["cgst"]
        )));
    }
}
?>