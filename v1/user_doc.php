<?php
if($_POST['doc_data'])
{
    require_once '../vendor/autoload.php';
    include "../include/conn.php";
    
    $jsonData = json_decode($_POST['doc_data'], true);
    
    if(!empty($_FILES['files'])) 
    {
        if(!is_dir('modules/uploads/documents/'.$jsonData['emp_id']))
        {
            mkdir('../modules/uploads/documents/'.$jsonData['emp_id'], 0775, TRUE);
        }
        
        $path = 'modules/uploads/documents/'.$jsonData['emp_id'].'/';
        $ext = pathinfo($_FILES['files']['name'], PATHINFO_EXTENSION);
        $profileimage = $jsonData["docname"].'.'.$ext;
        $shortPath = '../'.$path.$profileimage;
        move_uploaded_file($_FILES["files"]["tmp_name"], $shortPath);
        
        echo "Success";
	}
}
?>