<?php
// connect to mongodb
$m = new MongoDB\Client("mongodb://sis_crm_user:Welcome*747@151.106.7.74:27017,162.252.81.226:27017,139.99.120.182:27017/sis_crm_DB?replicaSet=mongodineamik&readPreference=primaryPreferred");
$db = $m->sis_crm_DB;
?>