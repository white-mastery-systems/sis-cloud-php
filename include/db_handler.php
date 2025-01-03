<?php
ini_set('max_execution_time', 1800);    // 30 minutes
error_reporting(0);
date_default_timezone_set("Asia/Kolkata");

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

class DbHandler
{
    private $conn;
    
    function __construct() 
    {
        //database connection
        require_once dirname(__FILE__).'/db_connect.php';
        $db = new DbConnect();
        $this->conn = $db->connect();        
        //file path
        if(isset($_SERVER['HTTPS'])){ $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http"; }
        else{ $protocol = 'http'; }
        $this->path = $protocol."://".$_SERVER['SERVER_NAME'];
    }
   
/***** LOGIN *****/
    
    //user login
    public function userlogin($getData)
    {
        // mongo collection
        $collection = $this->conn->signintable;
        $response = array();
        $jsonData = json_decode($getData, true);
        
		
		ob_start();
        //Get the ipconfig details using system commond
        system('ipconfig /all');
 
       // Capture the output into a variable
        $mycom=ob_get_contents();
       // Clean (erase) the output buffer
       ob_clean();
 
       $findme = "Physical";
       //Search the "Physical" | Find the position of Physical text
       $pmac = strpos($mycom, $findme);
 $ip =  $_SERVER['LOCAL_ADDR']; 
  
// Printing the stored address 
       $response["ip"]= $ip; 
       // Get Physical Address
       $mac=substr($mycom,($pmac+36),17);
       //Display Mac Address
       $response["mac"] = $mac;		

		
        $cursor = $collection->aggregate(array(
            array( '$match' => array(
                "mail_id" => $jsonData['email'],
                "password" => $jsonData['password']
            )),
            array( '$lookup' => array(
                'from' => 'user_list',
                'localField' => 'emp_id',
                'foreignField' => 'emp_id',
                'as' => 'user_data'
            )),
            array( '$lookup' => array(
                'from' => 'permissiontable',
                'localField' => 'emp_id',
                'foreignField' => 'emp_id',
                'as' => 'perm_data'
            ))
        ));
        //$response["code"] = 'Invalid Username or Password !';
        if($cursor) {
            foreach($cursor as $rows)
            {
                if($rows['user_data'][0]['status']=='Active')
                {
                    $response['staff_data']=array();
                    $perCount = count($rows['perm_data'][0]['permission']);
                    for($i=0; $i<$perCount; $i++)
                    {
                        $product['add']=$rows['perm_data'][0]['permission'][$i]['add'];
                        $product['edit']=$rows['perm_data'][0]['permission'][$i]['edit'];
                        $product['delete']=$rows['perm_data'][0]['permission'][$i]['delete'];
                        $product['mail']=$rows['perm_data'][0]['permission'][$i]['mail'];
                        $product['approve']=$rows['perm_data'][0]['permission'][$i]['approve'];
                        $product['quotation']=$rows['perm_data'][0]['permission'][$i]['quotation'];
                        $product['leave']=$rows['perm_data'][0]['permission'][$i]['leave'];

                        $sendData['access']=json_encode($product);
                        $sendData['permission_type']=$rows['perm_data'][0]['permission'][$i]['permission_type'];

                        array_push($response['staff_data'], $sendData);
                    }
                    $response["emp_id"] = $rows['emp_id'];
					$response["mac_db"] = $rows['mac_address'];
					$response["mac_status"] = $rows['mac_status'];
                    $response["emp_name"] = $rows['name'];
                    $response["emp_department"] = $rows['department'];
                    $response["emp_designation"] = $rows['designation'];
                    $response["emp_profile"] = $rows['user_data'][0]['profileimage'];
                    $response["code"] = "Success";
                }
                else {
                    $response["code"] = "Login Blocked !";
                }
            }
        }
        else {
            $response["code"] = $cursor;
        }
        return $response;   
    }
    
/***** PROJECTS *****/
    
    //sis project list
    public function getsisprojects()
    {
        $response = array();
        $response["project_list"] = array();
        $response["user_list"] = array();
        
        // mongo collection
        $collectionProject = $this->conn->project_details;
        $collectionUser = $this->conn->user_list;
        
        //project details
        $resultProject = $collectionProject->aggregate(array(
            array( '$lookup' => array(
                'from' => 'signintable',
                'localField' => 'emp_id',
                'foreignField' => 'emp_id',
                'as' => 'vendor_data'
            ))
        ));
        if($resultProject)
        {
            foreach($resultProject as $rows)
            {
                $project['_id'] = $rows['_id'];
                $project['project_name'] = $rows['project_name'];
                $project['block_name'] = $rows['block_name'];
                $project['place'] = $rows['place'];
                $project['contact_name'] = $rows['vendor_data'][0]['name'];
                $project['mobile_no'] = $rows['vendor_data'][0]['mobile'];
                
                array_push($response["project_list"], $project);
            }
        }
        
        //user details
        $cursor = $collectionUser->find(array("status" => "Active"));
        if($cursor)
        {
            foreach($cursor as $rowData)
            {
                $sendData['name'] = $rowData['emp_id'].' - '.$rowData['name'];
                array_push($response["user_list"], $sendData);
            }
        }
        
        $response["code"] = "Success";
        return $response;
    }
    
    //add sis project
    public function addsisproject($getData)
    {
        $response = array();
        $jsonData = json_decode($getData, true);
        $empData = explode(" ",$jsonData["contact_name"]);
        if(!$jsonData["block_name"]){ $jsonData["block_name"]='none'; }
        
        // mongo collection
        $collectionProject = $this->conn->project_details;
        $collectionUser = $this->conn->signintable;
        $cursorData = $collectionProject->findOne();
        
        $userData = $collectionUser->findOne(array("emp_id" => (int)$empData[0]));
        if($userData)
        {
            $cursor = $collectionProject->insertOne(array(
                    "emp_id" => (int)$empData[0],
                    "project_name" => $jsonData["project_name"],
                    "project_short" => $jsonData["project_short"],
                    "block_name" => $jsonData["block_name"],
                    "place" => $jsonData["place"],
                    "city" => $jsonData["city"],
                    "address" => $jsonData["address"],
                    "payment" => $jsonData["payment"],
                    "gst_in" => $cursorData["gst_in"],
                    "pan_no" => $cursorData["pan_no"],
                    "hsn_sac_code" => $cursorData["hsn_sac_code"],
                    "date_time" => date("Y-m-d H:i:s")
            ));

            if($cursor) {
                $response["code"] = "Success";
            }
            else {
                $response["code"] = "failed";
            }
        }
        else{
            $response["code"] = "Contact person not exist !!!";
        }
        return $response;
    }
    
    //edit sis project
    public function editsisproject($getData)
    {
        $response = array();
        $jsonData = json_decode($getData, true);
        
        // mongo collection
        $collectionProject = $this->conn->project_details;
        
        //project details
        $cursorData = $collectionProject->aggregate(array(
            array( '$match' => array(
                "_id" => new MongoDB\BSON\ObjectID($jsonData['id'])
            )),
            array( '$lookup' => array(
                'from' => 'signintable',
                'localField' => 'emp_id',
                'foreignField' => 'emp_id',
                'as' => 'vendor_data'
            ))
        ));
        if($cursorData)
        {
            foreach($cursorData as $rows)
            {
                $project['_id'] = $rows['_id'];
                $project['project_name'] = $rows['project_name'];
                $project['project_short'] = $rows['project_short'];
                $project['block_name'] = $rows['block_name'];
                $project['place'] = $rows['place'];
                $project['city'] = $rows['city'];
                $project['contact_name'] = $rows['emp_id'].' - '.$rows['vendor_data'][0]['name'];
                $project['mobile_no'] = $rows['vendor_data'][0]['mobile'];
                $project['address'] = $rows['address'];
                $project['payment'] = $rows['payment'];
                $project['gst_in'] = $rows['gst_in'];
                $project['pan_no'] = $rows['pan_no'];
                $project['hsn_sac_code'] = $rows['hsn_sac_code'];
                
                $response["project_list"] = $project;
            }
        }
        return $response;
    }
    
    //update sis project
    public function updatesisproject($getData)
    {
        $response = array();
        $jsonData = json_decode($getData, true);
        $empData = explode(" ",$jsonData["contact_name"]);
        if(!$jsonData["block_name"]){ $jsonData["block_name"]='none'; }
        
        // mongo collection
        $collectionProject = $this->conn->project_details;
        $collectionUser = $this->conn->signintable;
        
	$userData = $collectionUser->findOne(array("emp_id" => (int)$empData[0]));
        if($userData)
        {
          $cursor_project = $collectionProject->findOne(array("project_name"=>$jsonData["project_name"]));
          if($cursor_project)
          {
            $cursor = $collectionProject->updateOne(
                array("_id" => new MongoDB\BSON\ObjectID($jsonData["_id"]['$oid'])),
                array('$set' => array(
                    "emp_id" => (int)$empData[0],
                    "project_name" => $jsonData["project_name"],
                    "project_short" => $jsonData["project_short"],
                    "project_type" => $cursor_project["project_type"],
       
                    "block_name" => $jsonData["block_name"],
                    "place" => $jsonData["place"],
                    "city" => $jsonData["city"],
                    "address" => $jsonData["address"],
                    "payment" => $jsonData["payment"],
                    "status" => $cursor_project["status"]
        
                )));
    if($cursor) {
        $collectionProject->updateMany(
            array("_id" => array( '$ne' => 0)),
            array('$set' => array( 
                "gst_in" => $jsonData["gst_in"],
                "pan_no" => $jsonData["pan_no"],
                "hsn_sac_code" => $jsonData["hsn_sac_code"]
        )));
          }
          
		

         
                $response["code"] = "Success";
            } 
            else {
                $response["code"] = "failed";
            }
	    
             }


       
        else{
            $response["code"] = "Contact person not exist !!!";
        }
        return $response;
    }
    
    //delete sis project
    public function delsisproject($getData)
    {
        $response = array();
         
        // collection
        $collection = $this->conn->project_details;
        
        $jsonData = json_decode($getData, true);
        
        $cursor = $collection->deleteOne(array("_id" => new MongoDB\BSON\ObjectID($jsonData['id'])));
       
        if ($cursor) {                
            $response["code"] = "Success";
        } 
        else {
            $response["code"] = "failed";               
        }
        return $response;
    }
    
/***** VENDORS *****/
    
    //vendor list
    public function getvendorlist($getData) 
    {
        $response = array();
        $response['vendor_details'] = array();
        
        $jsonData = json_decode($getData);
        
        if($jsonData->cancel_status == 'usage'){
            $cancelStatus = '0';
        }
        else{
            $cancelStatus = '1';
        }
        
        // collection
        $collection = $this->conn->vendor_details;
        
        //retrieve data
        $result = $collection->aggregate(array(
            array( '$match' => array(
                "cancel_status" => $cancelStatus
            )),
            array( '$sort' => array( 
                '_id' => -1
            ))
        ));
        
        if($result)
        {
            foreach($result as $rowData)
            {
                if($rowData['mobile'] == ''){ $rowData['mobile'] = 'NA'; }
                array_push($response["vendor_details"], $rowData);
            }
            $response["code"] = "Success";
        }
        else {
            $response["code"] = "failed";
        }
        return $response;        
    }
    
    //add vendor
    public function addpovendor($getData) 
    {
        $response = array();
        $collectionVendor = $this->conn->vendor_details;
        $jsonData = json_decode($getData, true);

        $vendorData = $collectionVendor->findOne(array("company_name" => $jsonData["company_name"]));
        if(!$vendorData){
            $collectionVendor->insertOne(array(
                "emp_id" => (int)$jsonData["emp_id"],
                "company_name" => $jsonData["company_name"],
                "contact_person" => $jsonData["contact_person"],
                "address" => $jsonData["address"],
                "party_gst" => $jsonData["party_gst"],
                "taxtype" => $jsonData["taxtype"],
                "mobile" => $jsonData["mobile"],
                "phone" => $jsonData["phone"],
                "fax" => $jsonData["fax"],
                "email" => $jsonData["email"],
                "date_time" => date("Y-m-d H:i:s"),
                "cancel_status" => "0"
            ));
            $response["code"] = "Success";
        }
        else{
            $response["code"] = "Vendor already exist !!!";
        }
        return $response;
    }
    
    //edit vendor
    public function editpovendor($getData) 
    {
        $response = array();
        $collectionVendor = $this->conn->vendor_details;
        $jsonData = json_decode($getData, true);
        
        $rowData = $collectionVendor->findOne(array("_id" => new MongoDB\BSON\ObjectID($jsonData['id'])));
        $response['vendor_details'] = $rowData;
        
        return $response;
    }
    
    //update vendor
    public function updatepovendor($getData) 
    {
        $response = array();
        $collectionVendor = $this->conn->vendor_details;
        $jsonData = json_decode($getData, true);
        
        $cursor = $collectionVendor->updateOne(
                array("_id" => new MongoDB\BSON\ObjectID($jsonData["_id"]['$oid'])),
                array('$set' => array(
                "company_name" => $jsonData["company_name"],
                "contact_person" => $jsonData["contact_person"],
                "address" => $jsonData["address"],
                "party_gst" => $jsonData["party_gst"],
                "taxtype" => $jsonData["taxtype"],
                "mobile" => $jsonData["mobile"],
                "phone" => $jsonData["phone"],
                "fax" => $jsonData["fax"],
                "email" => $jsonData["email"]
            )));
        if($cursor){
            $response["code"] = "Success";
        }
        else{
            $response["code"] = "failed";
        }
        return $response;
    }
    
    //delete vendor
    public function deletevendor($getData)
    {
        $response = array();
         
        // collection
        $collectionVendor = $this->conn->vendor_details;
        
        $jsonData = json_decode($getData);
        $id = $jsonData->id;
        $type = $jsonData->type;
        
        if($type=='deletevendor')
        {
            $cursor = $collectionVendor->deleteOne(array("_id" => new MongoDB\BSON\ObjectID($id)));
        }
        else
        {
            if($type=='removevendor'){
                $cancelStatus='1';
            }
            elseif($type=='reopenvendor'){
                $cancelStatus='0';
            }
            
            $cursor = $collectionVendor->updateOne(
                array("_id" => new MongoDB\BSON\ObjectID($id)),
                array('$set' => array("cancel_status" => $cancelStatus))
            );
        }
       
        if ($cursor) {                
            $response["code"] = "Success";
        } 
        else {
            $response["code"] = "failed";               
        }
        return $response;
    }
    
    //delete all vendors
    public function deleteallvendors($getData)
    {
        $response = array();
         
        // collection
        $collectionVendor = $this->conn->vendor_details;
        
        $jsonData = json_decode($getData, true);
        
        $jsonArrayData = json_decode($jsonData["jsonArrayData"], true);
        $listCount = count($jsonArrayData);
        
        for($i=0; $i<$listCount; $i++)
        {
            if($jsonArrayData[$i]['type']=='usage') {
                $collectionVendor->updateOne(
                    array("_id" => new MongoDB\BSON\ObjectID($jsonArrayData[$i]['id'])),
                    array('$set' => array("cancel_status" => '1'))
                );
            }
            else {
                $collectionVendor->deleteOne(array("_id" => new MongoDB\BSON\ObjectID($jsonArrayData[$i]['id'])));
            }
        }
        
        $response["code"] = "Success";
        return $response;
    }
    
/***** PRODUCTS *****/
    
    //product list
    public function getproductdetails($getData) 
    {
        $response = array();
        $jsonData = json_decode($getData, true);
        
        //category
        $response["category_list"] = $this->internalcategorylist();
        
        if($jsonData['cancel_status']!='none')
        {
            // collection
            $collection = $this->conn->product_list;
            $response['product_details'] = array();

            if($jsonData['cancel_status'] == 'usage'){ $cancelStatus = 0; }
            else{ $cancelStatus = 1; }

            //retrieve data
            $result = $collection->aggregate(array(
                array( '$match' => array(
                    "cancel_status" => $cancelStatus,
                )),
                array( '$sort' => array( 
                    '_id' => -1
                ))
            ));

            if($result)
            {
                foreach($result as $rowData)
                {
                    
                    if($rowData['product_code'])
                    {
                    if($rowData['code']==''){$rowData['code']='-';}
                    $sendData['_id'] = $rowData['_id'];
                    $sendData['category'] = $rowData['category'];
                    $sendData['code'] = $rowData['code'];
                    $sendData['product_name'] = $rowData['product_name'];
                    $sendData['product_code'] = $rowData['product_code'];
                    $sendData['unit'] = $rowData['unit'];
                    $sendData['price'] = $rowData['price'];
                    $sendData['gst'] = $rowData['sgst'] + $rowData['cgst'];
                    array_push($response["product_details"], $sendData);
                    }
                }
                $response["code"] = "Success";
            }
            else {
                $response["code"] = "failed";
            }
        }
        return $response;        
    }
    // get single produt
	   public function getsingleproductdetails($getData) 
    {
        $response = array();
        $jsonData = json_decode($getData, true);

            // collection
            $collection = $this->conn->product_list;
            $response['product_details'] = array();

         $cancelStatus = 0;

            //retrieve data
            $result = $collection->aggregate(array(
                array( '$match' => array(
                    "cancel_status" => $cancelStatus,
					 "product_name" => $jsonData["product_name"]
                )),
                array( '$sort' => array( 
                    '_id' => -1
                ))
            ));

            if($result)
            {
                foreach($result as $rowData)
                {
                    if($rowData['code']==''){$rowData['code']='-';}
                    $sendData['_id'] = $rowData['_id'];
                    $sendData['category'] = $rowData['category'];
                    $sendData['code'] = $rowData['code'];
                    $sendData['product_name'] = $rowData['product_name'];
                    $sendData['unit'] = $rowData['unit'];
                    $sendData['price'] = $rowData['price'];
                    $sendData['gst'] = $rowData['sgst'] + $rowData['cgst'];

                    array_push($response["product_details"], $sendData);
                }
                $response["code"] = "Success";
            }
            else {
                $response["code"] = "failed";
            }
        
        return $response;        
    }
    
    //addcategory
    public function addcategory($getData) 
    {
        $response = array();
        $collectionCategory = $this->conn->category;
        $jsonData = json_decode($getData, true);        
        $categoryData = $collectionCategory->findOne(array('$or' => array(
            array("category_ws" => $jsonData["category_ws"]),
            array("short" =>  $jsonData["shortname"])
        )));
        if(!$categoryData) {
         $category = trim($jsonData["category"]);
            $collectionCategory->insertOne(array(
                "emp_id" => (int)$jsonData["emp_id"],
                "type" => $jsonData["type"],
                "category" => $category,
                "short" => $jsonData["shortname"],
                "category_ws" => $jsonData["category_ws"],
                "date_time" => date("Y-m-d H:i:s")
            ));
            $response["code"] = "Success";
        }
        else {
            $response["code"] = "Category details already exist !!!";
        }
        return $response;
    }
    
    //add product
    public function addpoproduct($getData) 
    {
        $response = array();
        
        $collectionProduct = $this->conn->product_list;
        $jsonData = json_decode($getData, true);
           $resData = $this->getproductcode(json_encode($jsonData));
        
        
        $productData = $collectionProduct->findOne(array(
            "type" => $jsonData["type"], 
            "category" => $jsonData["category"],
            "product_name_ws" =>  $jsonData["product_name_ws"],
            
        ));
        if(!$productData) {
            $collectionProduct->insertOne(array(
                "emp_id" => (int)$jsonData["emp_id"],
                "type" => $jsonData["type"],
                "category" => $jsonData["category"],
                "sub_category" => $jsonData["sub_category"],
                "code" => $jsonData["code"],
                "product_name_ws" => $jsonData["product_name_ws"],               
                "product_name" => $jsonData["product_name"],
                "product_code" => $resData['product_code'],
                "details" => $jsonData["details"],
                "make" => $jsonData["make"],
                "width" => $jsonData["width"],
                "height" => $jsonData["height"],
                "upvc_type" => $jsonData["upvc_type"],
                "unit" => $jsonData["unit"],
                "price" => $jsonData["price"],
                "gst" => $jsonData["gst"],
                "sgst" => $jsonData["gst"]/2,
                "cgst" => $jsonData["gst"]/2,               
                "size" => $jsonData["size"],
                "cft" => $jsonData["cft"],
                "date_time" => date("Y-m-d H:i:s"),
                "cancel_status" => 0
            ));
            $response["code"] = "Success";
        }
        else {
            $response["code"] = "Product details already exist !!!";
        }
        return $response;
    }
    
    //edit product
    public function editpoproduct($getData) 
    {
        $response = array();
        $collectionProduct = $this->conn->product_list;
        $jsonData = json_decode($getData, true);
        
        $productData = $collectionProduct->findOne(array("_id" => new MongoDB\BSON\ObjectID($jsonData['id'])));
        if($productData) {
            $productData['gst'] = $productData['sgst'] + $productData['cgst'];
            $response["product_details"] = $productData;
        }
        return $response;
    }
    
    //update product
    public function updatepoproduct($getData) 
    {
        $response = array();
        $collection = $this->conn->product_list;
        
        $jsonData = json_decode($getData, true);
        $count = $collection->count(array(
            "type" => $jsonData["type"], 
            "category" => $jsonData["category"],
            "product_name_ws" =>  $jsonData["product_name_ws"]
            
        ));
        $response["count"] = $count;
        
        if($count == 0) {
        $cursor = $collection->updateOne(
            array("_id" => new MongoDB\BSON\ObjectID($jsonData["_id"]['$oid'])),
            array('$set' => array(
                "code" => $jsonData["code"],
                "details" => $jsonData["details"],
                "product_name_ws" => $jsonData["product_name_ws"],               
                "product_name" => $jsonData["product_name"],
                "make" => $jsonData["make"],
                "width" => $jsonData["width"],
                "height" => $jsonData["height"],
                "upvc_type" => $jsonData["upvc_type"],
                "unit" => $jsonData["unit"],
                "price" => $jsonData["price"],
                "gst" => $jsonData["gst"],               
                "sgst" => $jsonData["gst"]/2,
                "cgst" => $jsonData["gst"]/2,
                "size" => $jsonData["size"],
                "cft" => $jsonData["cft"]
        )));
        if($cursor){
            $response["code"] = "Success";
        }
        else{
            $response["code"] = "failed";
        }
        }
        else if($count == 1) {
        $cursor = $collection->updateOne(
            array("_id" => new MongoDB\BSON\ObjectID($jsonData["_id"]['$oid'])),
            array('$set' => array(
                "code" => $jsonData["code"],
                "details" => $jsonData["details"],
                "make" => $jsonData["make"],
                "width" => $jsonData["width"],
                "height" => $jsonData["height"],
                "upvc_type" => $jsonData["upvc_type"],
                "unit" => $jsonData["unit"],
                "price" => $jsonData["price"],
                "sgst" => $jsonData["gst"]/2,
                "cgst" => $jsonData["gst"]/2,
                "size" => $jsonData["size"],
                "cft" => $jsonData["cft"]
        )));
        if($cursor){
            $response["code"] = "Success";
        }
        else{
            $response["code"] = "failed";
        }
        }
        else
        {
          $response["codeo"] = "failed";   
        }
        return $response;
    }
    
    //delete product
    public function deleteproduct($getData)
    {
        $response = array();
         
        // collection
        $collection = $this->conn->product_list;
        
        $jsonData = json_decode($getData);
        $id = $jsonData->id;
        $type = $jsonData->type;
        
        if($type=='deleteproduct')
        {
            $cursor = $collection->deleteOne(array("_id" => new MongoDB\BSON\ObjectID($id)));
        }
        else
        {
            if($type=='removeproduct'){
                $cancelStatus=1;
            }
            elseif($type=='reopenproduct'){
                $cancelStatus=0;
            }
            
            $cursor = $collection->updateOne(
                array("_id" => new MongoDB\BSON\ObjectID($id)),
                array('$set' => array("cancel_status" => $cancelStatus))
            );
        }
       
        if ($cursor) {                
            $response["code"] = "Success";
        } 
        else {
            $response["code"] = "failed";               
        }
        return $response;
    }
    
    //delete all products
    public function deleteallproducts($getData)
    {
        $response = array();
         
        // collection
        $collection = $this->conn->product_list;
        
        $jsonData = json_decode($getData, true);
        
        $jsonArrayData = json_decode($jsonData["jsonArrayData"], true);
        $listCount = count($jsonArrayData);
        
        for($i=0; $i<$listCount; $i++)
        {
            if($jsonArrayData[$i]['type']=='usage') {
                $collection->updateOne(
                    array("_id" => new MongoDB\BSON\ObjectID($jsonArrayData[$i]['id'])),
                    array('$set' => array("cancel_status" => 1))
                );
            }
            else {
                $collection->deleteOne(array("_id" => new MongoDB\BSON\ObjectID($jsonArrayData[$i]['id'])));
            }
        }
        
        $response["code"] = "Success";
        return $response;
    }
    
/***** INDENT *****/
    
    //indent list
    public function getindentlist($getData)
    {
        $response = array();
        $response['indent_data'] = array();
        $jsonData = json_decode($getData, true);
        
        // collection
        $collectionMaster = $this->conn->indent_master;
        
        //site list
        $siteData = $this->internalsitelist($jsonData['emp_id']);
        $response['site_list'] = $siteData["site_list"];
        
        $proShortArray = $siteData["project_short_list"];
        if($jsonData['project_short']!='') { $proShortArray = array($jsonData['project_short']); }
        
        //retrieve data
        if($jsonData['cancel_status'] == 'pending') {
            //mail raised and not quoted
            $result = $collectionMaster->aggregate(array(
                array( '$match' => array(
                    "project_short" => array( '$in' => $proShortArray ),
                    "cancel_status" => '0',
                    // "mail_status" => 'send',
                    "quot_status" => array( '$ne' => "1" )
                )),
                array( '$lookup' => array(
                    'from' => 'signintable',
                    'localField' => 'emp_id',
                    'foreignField' => 'emp_id',
                    'as' => 'user_data'
                )),
                array( '$sort' => array( 
                    '_id' => -1
                ))
            ));
        }
        else {
            if($jsonData['cancel_status'] == 'usage'){ $cancelStatus = '0'; }
            else{ $cancelStatus = '1'; }

            if($jsonData['quot_status'] == '1') {
                //mail raised indent only
                $result = $collectionMaster->aggregate(array(
                    array( '$match' => array(
                        "project_short" => array( '$in' => $proShortArray ),
                        "cancel_status" => $cancelStatus,
                        "mail_status" => 'send'
                    )),
                    array( '$lookup' => array(
                        'from' => 'signintable',
                        'localField' => 'emp_id',
                        'foreignField' => 'emp_id',
                        'as' => 'user_data'
                    )),
                    array( '$sort' => array( 
                        '_id' => -1
                    ))
                ));
            }
            else {
                //all indent
                $result = $collectionMaster->aggregate(array(
                    array( '$match' => array(
                        "project_short" => array( '$in' => $proShortArray ),
                        "cancel_status" => $cancelStatus
                    )),
                    array( '$lookup' => array(
                        'from' => 'signintable',
                        'localField' => 'emp_id',
                        'foreignField' => 'emp_id',
                        'as' => 'user_data'
                    )),
                    array( '$sort' => array( 
                        '_id' => -1
                    ))
                ));
            }
        }
        if($result)
        {
            $product = array();
            foreach($result as $rows)
            {
                if(!$rows['quot_status']){ $rows['quot_status']=''; }
                $product['prf_number'] = $rows['prf_number'];
                $product['prf_date'] = $rows['prf_date'];
                $product['name'] = $rows['user_data'][0]['name'];
                $product['summary'] = $rows['description'];
                $product['total'] = $rows['grand_total'];
                $product['mail_status'] = $rows['mail_status'];
                $product['quot_status'] = $rows['quot_status'];

                //indent status
                if($rows['quot_status']=='1') {
                    $product['indent_status'] = $rows['quot_status_details'];
                }
                elseif($rows['mail_datetime']) {
                    $respDuration = $this->secondsToWords($rows['mail_datetime'], date("Y-m-d H:i:s"));
                    $product['indent_status'] = 'Raised '.$respDuration;
                }
                else {
                    $product['indent_status'] = '-';
                }
                
                array_push($response['indent_data'], $product);
            }
            $response["code"] = "Success";
        }
        else {
            $response["code"] = "failed";
        }
        return $response;
    }
    
    //get quotation details
    public function indentdetailedview($getData)
    {
       $response = array();
        $response["quot_list"] = array();
        $jsonData = json_decode($getData, true);
        
        // collection
        $collectionIndent = $this->conn->indent_master;
        $collectionMaster = $this->conn->quotation_master;
        
        $indentData = $collectionIndent->findOne(array("prf_number" => $jsonData['prf_number']));
        if($indentData['quot_duration'])
        {
            $response['indent_duration'] = $indentData['quot_duration'];
            //$response['mail_datetime']= $indentData['mail_datetime'];
            $maildatime = $indentData['mail_datetime'];
           
        }
        else {
            $response['indent_duration'] = '0 min';
            //$response['mail_datetime']= " ";
            $maildatime = '';
        }
        
        
        $cursor = $collectionMaster->aggregate(array(
            array( '$match' => array(
                "prf_number" => $jsonData['prf_number']
            )),
            array( '$lookup' => array(
                'from' => 'quotation_duration',
                'localField' => 'quot_number',
                'foreignField' => 'quot_number',
                'as' => 'duration_data'
            ))
        ));
        if($cursor)
        {
            $product = array();
            foreach($cursor as $rows)
            { 
                $current_date = date("Y-m-d");
                   $indentdate = date("d-m-Y", strtotime($maildatime)); 
                
                if($current_date == $indentdate )
                {
                  $respDuration_m = $this->secondsToWords($maildatime, date("Y-m-d H:i:s"));
                  $product['mail_datetime'] = $respDuration_m." ago"; 
                }
                else
                {
                    
                     $respDuration_m = $this->secondsToWords($maildatime, date("Y-m-d H:i:s"));
                    $respDuration_m1 = date("d-m-Y h:i a", strtotime($maildatime));
                   $product['mail_datetime'] =   '<span>'.$respDuration_m1."<br>( ".$respDuration_m. " ago ) </span>"; 
                    
                }
                
              
             // $product['mail_datetime'] = date("d-m-Y h:i a", strtotime($maildatime));                
                $product['quot_number'] = $rows['duration_data'][0]['quot_number'];
				$product['po_number'] = $rows['duration_data'][0]['po_number'];
                //indent status
                if($rows['duration_data'][0]['approved_duration'])
                {
                     $quot_date = date("d-m-Y", $rows['duration_data'][0]['approved_duration']); 
                    
                      if($current_date == $quot_date )
                {
                  $respDuration1 = $this->secondsToWords($maildatime,$rows['duration_data'][0]['approved_datetime']);
                           
                  $product['quot_status'] =  '<span>Approved <br>'.$respDuration1." ago ) </span>";
                }
                else
                {
                    
                $respDuration1 = date("d-m-Y h:i a", strtotime($rows['duration_data'][0]['approved_datetime']));
                $respDuration2 = $this->secondsToWords($maildatime,$rows['duration_data'][0]['approved_datetime']);
                $product['quot_status'] = '<span>Approved '.$respDuration1."<br>( ".$respDuration2. " ago ) </span>";
                    
                }
                    
                   
                }
                elseif($rows['duration_data'][0]['declined_duration'])
                {
                    $respDuration1 = $this->secondsToWords($maildatime,$rows['duration_data'][0]['approved_datetime']);
                    $product['quot_status'] = 'Declined<br>'.$rows['duration_data'][0]['declined_duration'];
                }
                elseif($rows['duration_data'][0]['proceed_datetime'])
                {
                    $respDuration = $this->secondsToWords($indentData['mail_datetime'], date("Y-m-d H:i:s"));
                    $product['quot_status'] = '<span class="c-red">Approval Pending<br>'.$respDuration."</span>";
                }
                else {
                    if($rows['duration_data'][0]['quot_datetime']) {
                        
                         $quot_date = date("d-m-Y", $rows['duration_data'][0]['quot_datetime']); 
                        
                               if($current_date ==  $quot_date )
                {
                  $respDuration1 = $this->secondsToWords($maildatime,$rows['duration_data'][0]['quot_datetime']);
                 
                
                  $product['quot_status'] =  '<span> Raised '.$respDuration1." ago )</span>"; 
                }
                else
                {                    
                $respDuration1 = date("d-m-Y h:i a", strtotime($rows['duration_data'][0]['quot_datetime'])); 
                $respDuration2 = $this->secondsToWords($maildatime,$rows['duration_data'][0]['quot_datetime']);
                    
                $product['quot_status'] = '<span>Raised '.$respDuration1. " <br> ( ". $respDuration2. " ago )</span>";
                    
                } 
                        
                    }
                    else {
                        $product['quot_status'] = '-';
                    }
                }

                //po status
                if($rows['duration_data'][0]['po_duration'])
                {
                    
                    
                      $po_date = date("d-m-Y", $rows['duration_data'][0]['po_datetime']); 
                        
                               if($current_date == $po_date )
                {
                  $respDurationpo1 = $this->secondsToWords($maildatime,$rows['duration_data'][0]['po_datetime']);
                  
                  $product['po_status'] =  '<span> Raised '.$respDurationpo1." </span>"; 
                }
                else
                {                    
                $respDurationpo1 = date("d-m-Y h:i a", strtotime($rows['duration_data'][0]['po_datetime'])); 
                $respDuration2 = $this->secondsToWords($maildatime,$rows['duration_data'][0]['po_datetime']);
                $product['po_status'] = '<span>Raised '.$respDurationpo1."<br> ( " . $respDuration2 . " ago )</span>";
                    
                } 
                   
                   
                }
                else {
                    if($rows['duration_data'][0]['approved_datetime']) {
                        $respDurationpo = $this->secondsToWords($maildatime, date("Y-m-d H:i:s"));
                        $product['po_status'] = '<span class="c-red">Pending <br>'.$respDurationpo."</span>";
                    }
                    else {
                        $product['po_status'] = '-';
                    }
                }
                
                array_push($response['quot_list'], $product);
            }
            $response["code"] = "Success";
        }
        else {
            $response["code"] = 'failure';
        }
        return $response;
    }
    
    //get indent details
    public function getindentdetails($getData)
    {
        $response = array();
        $jsonData = json_decode($getData, true);
        
        //vendor
        $sendData['cancel_status'] = 'usage';
        $vendorData = $this->getvendorlist(json_encode($sendData));
        $response["vendor_list"] = $vendorData["vendor_details"];
        
        //category
        $response["category_list"] = $this->internalcategorylist();
        
        //get project list
        $response["project_list"] = $this->internalindentprojectlist($jsonData['emp_id']);
        
        //get product list
        $proData['type'] = 'Standard';
        $proData['category'] = 'none';
        $response["product_list"] = $this->internalproductlist(json_encode($proData));
        
        $response["code"] = 'Success';
        return $response;
    }
    
    //get prf number for new indent
    public function getprfnumber($getData)
    {
        $response = array();
        $resData = $this->internalprfnum($getData);
        $response["prf_number"] = $resData["prf_number"];
        return $response;
    }
    
    //new indent
    public function newindent($getData)
    {
        $response = array();
        $jsonData = json_decode($getData, true);
        
        //collection
        $collectionProject = $this->conn->project_details;
        $collectionMaster = $this->conn->indent_master;
        $collectionTable = $this->conn->indent_table;
        
        //get project details for po number
        $projectData = $collectionProject->findOne(array("project_name" => $jsonData["sis_project_name"], "block_name" => $jsonData["sis_project_block"]));
        
        $proData['project_short'] = $projectData['project_short'];
        $resData = $this->internalprfnum(json_encode($proData));
        
        //supplier data
        $jsonSupplierData = json_decode($jsonData["jsonSupplierData"], true);
        $supplierCount = count($jsonSupplierData);
        $product = array();
        $sendData['supplier_list'] = array();
        for($i=0; $i<$supplierCount; $i++)
        {
            if($jsonSupplierData[$i]['company_name']!='')
            {
                $product['company_name'] = $jsonSupplierData[$i]['company_name'];
                $product['reason'] = $jsonSupplierData[$i]['reason'];
                $product['contact_person'] = $jsonSupplierData[$i]['contact_person'];
                $product['mobile'] = $jsonSupplierData[$i]['mobile'];
                $product['comp_address'] = $jsonSupplierData[$i]['comp_address'];
                array_push($sendData['supplier_list'], $product);
            }
        }
        
        $jsonArrayData = json_decode($jsonData["jsonArrayData"], true);
        
        //description
        !empty($jsonArrayData[0]["item"]) ? $pro1 =$jsonArrayData[0]["item"] : $pro1= "";
        !empty($jsonArrayData[1]["item"]) ? $pro2 =$jsonArrayData[1]["item"] : $pro2= "";
        $dummyData = '';
        $desc = $this->internalsummary($projectData['project_short'],$dummyData,$pro1,$pro2);
        
        $collectionMaster->insertOne(array(
            "emp_id" => (int)$jsonData["emp_id"],
            "type" => $jsonData["type"],
            "prf_number" => $resData["prf_number"],
            "project_short" => $projectData["project_short"],
            "prf_date" => date("d-m-Y"),
            "purpose" => $jsonData["purpose"],
            "project_id" => $projectData["_id"],
            "description" => $desc,
            "grand_total" => $jsonData["grand_total"],
            "supplier_list" => $sendData['supplier_list'],
            "date_time" => date("Y-m-d H:i:s"),
            "cancel_status" => "0",
            "mail_status" => "0"
        ));
        
        //product data
        $listCount = count($jsonArrayData);
        $n=1;
        for($j=0; $j<$listCount; $j++)
        {
            if(!$jsonArrayData[$j]["stock_details"]) {
                $stockData['block_available']=0;
                $stockData['block_usage']=0;
                $stockData['site_available']=0;
                $stockData['site_usage']=0;
                $jsonArrayData[$j]["stock_details"] = $stockData;
            }
            $collectionTable->insertOne(array(
                "prf_number" => $resData["prf_number"],
                "code" => $jsonArrayData[$j]["code"],
                "item" => $jsonArrayData[$j]['item'],
                "unit" => $jsonArrayData[$j]['unit'],
                "quantity" => $jsonArrayData[$j]['quantity'],
                "price" => $jsonArrayData[$j]['price'],
                "details" => $jsonArrayData[$j]["details"],
                "make" => $jsonArrayData[$j]["make"],
                "width" => $jsonArrayData[$j]["width"],
                "height" => $jsonArrayData[$j]["height"],
                "upvc_type" => $jsonArrayData[$j]["upvc_type"],
                "size" => $jsonArrayData[$j]["size"],
                "cft" => $jsonArrayData[$j]["cft"],
                "stock_details" => $jsonArrayData[$j]["stock_details"],
                "required_date" => $jsonArrayData[$j]['required_date']
            ));
        }
        
        //mail
        if($jsonData["to_mail"]!='')
        {
            $mailData['prf_number'] = $resData["prf_number"];
            $mailData['emp_id'] = (int)$jsonData["emp_id"];
            $mailData['to_mail'] = $jsonData["to_mail"];
            $mailData['cc_mail'] = $jsonData["cc_mail"];
            $mailData['bcc_mail'] = $jsonData["bcc_mail"];
            $mailData['subject'] = $projectData['project_name'].' - '.$jsonData["purpose"].' - Indent No: '.$resData["prf_number"];
            $mailData['content'] = $jsonData["content"];
            $sendMailData['jsonArrayData'] = json_encode($mailData);
          
            $resp = $this->sendindentmail(json_encode($sendMailData));
            $response['code'] = $resp['code'];
        }
        else{
            $response['code'] = 'Success';
        }
        return $response;
    }
    
    //view purchase indent
    public function viewpurchaseindent($getData) 
    {
        $response = array();
        $response["product_list"] = array();
        $jsonData = json_decode($getData, true);        
        //collection
        $collectionMaster = $this->conn->indent_master;
        $collectionPurchase = $this->conn->purchase_master;
        
        //purchase order details
        $cursor = $collectionMaster->aggregate(array(
                array('$match' => array(
                    "prf_number" => $jsonData['prf_number']
                )),
                array( '$lookup' => array(
                    'from' => 'indent_table',
                    'localField' => 'prf_number',
                    'foreignField' => 'prf_number',
                    'as' => 'indent_table_data'
                )),
                array( '$lookup' => array(
                    'from' => 'signintable',
                    'localField' => 'emp_id',
                    'foreignField' => 'emp_id',
                    'as' => 'user_data'
                )),
                array( '$lookup' => array(
                    'from' => 'project_details',
                    'localField' => 'project_id',
                    'foreignField' => '_id',
                    'as' => 'project_details_data'
                ))
            ));
        if($cursor)
        {
            foreach($cursor as $rowData)
            {
                $sendData['emp_id'] = $rowData['emp_id'];
                $sendData['name'] = $rowData['user_data'][0]['name'];
                $sendData['designation'] = $rowData['user_data'][0]['designation'];
                $sendData['type'] = $rowData['type'];
                $sendData['prf_number'] = $rowData['prf_number'];
                $sendData['prf_date'] = $rowData['prf_date'];
                $sendData['purpose'] = $rowData['purpose'];
                $sendData['grand_total'] = $rowData['grand_total'];
                $sendData['sis_project_name'] = $rowData['project_details_data'][0]['project_name'];
                $sendData['sis_project_block'] = $rowData['project_details_data'][0]['block_name'];
                $sendData['place'] = $rowData['project_details_data'][0]['place'];
                $response["supplier_list"] = $rowData['supplier_list'];
                
                //indent table
                $productCount = count($rowData['indent_table_data']);
                for($i=0; $i<$productCount; $i++)
                {
                    $purchaseData['item']=$rowData['indent_table_data'][$i]['item'];
                    $purchaseData['quantity']=$rowData['indent_table_data'][$i]['quantity'];
                    $purchaseData['unit']=$rowData['indent_table_data'][$i]['unit'];
                    $purchaseData['price']=$rowData['indent_table_data'][$i]['price'];
                    if($rowData['type']=='Door'){
                        $purchaseData['amount'] = ($rowData['indent_table_data'][$i]['cft'] * $rowData['indent_table_data'][$i]['price']);
                    }
                    else{
                        $purchaseData['amount'] = ($rowData['indent_table_data'][$i]['quantity'] * $rowData['indent_table_data'][$i]['price']);
                    }
                    $purchaseData['total']=sprintf("%.2f", $purchaseData['amount']);
                    $purchaseData['make']=$rowData['indent_table_data'][$i]['make'];
                    $purchaseData['details']=$rowData['indent_table_data'][$i]['details'];
                    $purchaseData['width']=$rowData['indent_table_data'][$i]['width'];
                    $purchaseData['height']=$rowData['indent_table_data'][$i]['height'];
                    $purchaseData['upvc_type']=$rowData['indent_table_data'][$i]['upvc_type'];
                    $purchaseData['size']=$rowData['indent_table_data'][$i]['size'];
                    $purchaseData['cft']=$rowData['indent_table_data'][$i]['cft'];
                    $purchaseData['required_date']=$rowData['indent_table_data'][$i]['required_date'];
                    $purchaseData['available_stock'] = $rowData['indent_table_data'][$i]["stock_details"]["block_available"];
                    
                    array_push($response['product_list'], $purchaseData);
                }
                
                //quotation product list
                $response['purchase_list'] = array();
                $purchaseData = $collectionPurchase->aggregate(array(
                    array('$match' => array(
                        "prf_number" => $rowData['prf_number']
                    )),
                    array( '$lookup' => array(
                        'from' => 'purchase_table',
                        'localField' => 'po_number',
                        'foreignField' => 'po_number',
                        'as' => 'purchase_data'
                    ))
                ));
                if($purchaseData)
                {
                    foreach($purchaseData as $rowPurData)
                    {
                        $proCount = count($rowPurData['purchase_data']);
                        for($i=0; $i<$proCount; $i++)
                        {
                            $quotData['item'] = $rowPurData['purchase_data'][$i]['item'];
                            $quotData['quantity'] = $rowPurData['purchase_data'][$i]['quantity'];
                            $quotData['unit'] = $rowPurData['purchase_data'][$i]['unit'];
                            array_push($response['purchase_list'], $quotData);
                        }
                    }
                }
            }
            $response["indent_data"] = $sendData;
            $response["code"] = "Success";
        }
        else {
            $response["code"] = "failed";
        }   
        return $response;
    }
    
    //edit purchase indent
    public function editpurchaseindent($getData)
    {
        $response["product_list"] = array();
        $response['indent_list'] = array();
        
        $jsonData = json_decode($getData, true);
        
        //collection
        $collectionMaster = $this->conn->indent_master;
        $collectionProject = $this->conn->project_details;
        
        //vendor
        $arrayData['cancel_status'] = 'usage';
        $vendorData = $this->getvendorlist(json_encode($arrayData));
        $response["vendor_list"] = $vendorData["vendor_details"];
        
        //get project list
        $response["project_list"] = $this->internalindentprojectlist($jsonData['emp_id']);
        
        //purchase order details
        $cursor = $collectionMaster->aggregate(array(
                array('$match' => array(
                    "prf_number" => $jsonData['prf_number']
                )),
                array( '$lookup' => array(
                    'from' => 'indent_table',
                    'localField' => 'prf_number',
                    'foreignField' => 'prf_number',
                    'as' => 'indent_table_data'
                )),
                array( '$lookup' => array(
                    'from' => 'project_details',
                    'localField' => 'project_id',
                    'foreignField' => '_id',
                    'as' => 'project_details_data'
                ))
            ));
        if($cursor)
        {
            foreach($cursor as $rowData)
            {
                $sendData['prf_number'] = $rowData['prf_number'];
                $sendData['type'] = $rowData['type'];
                $sendData['purpose'] = $rowData['purpose'];
                $sendData['prf_date'] = $rowData['prf_date'];
                $sendData['sis_project_name'] = $rowData['project_details_data'][0]['project_name'];
                $sendData['sis_project_block'] = $rowData['project_details_data'][0]['block_name'];
                $sendData['grand_total'] = $rowData['grand_total'];
                
                $response["supplier_list"] = $rowData['supplier_list'];
                
                //prf number
                if($jsonData['type']=='duplicate') {
                    //get prf number
                    $internalData['project_short'] = $rowData['project_details_data'][0]['project_short'];
                    $internalData['project_name'] = $rowData['project_details_data'][0]['project_name'];
                    $resData = $this->internalprfnum(json_encode($internalData));
                    $sendData["prf_number"] = $resData["prf_number"];
                }
                
                $productCount = count($rowData['indent_table_data']);
                for($i=0; $i<$productCount; $i++)
                {
                    $purchaseData['_id']=$rowData['indent_table_data'][$i]['_id'].$oid;
                    $purchaseData['code']=$rowData['indent_table_data'][$i]['code'];
                    $purchaseData['item']=$rowData['indent_table_data'][$i]['item'];
                    $purchaseData['quantity']=$rowData['indent_table_data'][$i]['quantity'];
                    $purchaseData['unit']=$rowData['indent_table_data'][$i]['unit'];
                    $purchaseData['price']=$rowData['indent_table_data'][$i]['price'];
                    if($rowData['type']=='Door'){
                        $purchaseData['amount'] = ($rowData['indent_table_data'][$i]['cft'] * $rowData['indent_table_data'][$i]['price']);
                    }
                    else{
                        $purchaseData['amount'] = ($rowData['indent_table_data'][$i]['quantity'] * $rowData['indent_table_data'][$i]['price']);
                    }
                    $purchaseData['total']=sprintf("%.2f", $purchaseData['amount']);
                    $purchaseData['make']=$rowData['indent_table_data'][$i]['make'];
                    $purchaseData['details']=$rowData['indent_table_data'][$i]['details'];
                    $purchaseData['width']=$rowData['indent_table_data'][$i]['width'];
                    $purchaseData['height']=$rowData['indent_table_data'][$i]['height'];
                    $purchaseData['upvc_type']=$rowData['indent_table_data'][$i]['upvc_type'];
                    $purchaseData['size']=$rowData['indent_table_data'][$i]['size'];
                    $purchaseData['cft']=$rowData['indent_table_data'][$i]['cft'];
                    $purchaseData['required_date']=$rowData['indent_table_data'][$i]['required_date'];
                    
                    //stock
                    $listData['project_name'] = $rowData['project_details_data'][0]['project_name'];
                    $listData['block_name'] = $rowData['project_details_data'][0]['block_name'];
                    $listData['product_name'] = $rowData['indent_table_data'][$i]['item'];
                    $stockData = $this->getitemstock(json_encode($listData));
                    $purchaseData['available_stock'] = $stockData["stock_details"]["block_available"];
                    $purchaseData['stock_details'] = $stockData["stock_details"];
                    
                    array_push($response['indent_list'], $purchaseData);
                }
                
                //product
                $proData['type'] = $rowData['type'];
                $proData['category'] = 'none';
                $response["product_list"] = $this->internalproductlist(json_encode($proData));
            }
            $response["invoice_data"] = $sendData;
            $response["code"] = "Success";
        }
        else {
            $response["code"] = "failed";
        }
              
        return $response;        
    }
    
    //update purchase indent
    public function updatepurchaseindent($getData)
    {
        $response = array();
        
        // collection
        $collectionMaster = $this->conn->indent_master;
        $collectionIndentTable = $this->conn->indent_table;
        $collectionProject = $this->conn->project_details;
        
        $jsonData = json_decode($getData, true);
        $emp_id = (int)$jsonData["emp_id"];
        
        //get project details
        $projectData = $collectionProject->findOne(array("project_name" => $jsonData["sis_project_name"], "block_name" => $jsonData["sis_project_block"]));
        
        //supplier data
        $jsonSupplierData = json_decode($jsonData["jsonSupplierData"], true);
        $supplierCount = count($jsonSupplierData);
        $product = array();
        $sendData['supplier_list'] = array();
        for($i=0; $i<$supplierCount; $i++)
        {
            if($jsonSupplierData[$i]['company_name']!='')
            {
                $product['company_name'] = $jsonSupplierData[$i]['company_name'];
                $product['reason'] = $jsonSupplierData[$i]['reason'];
                $product['contact_person'] = $jsonSupplierData[$i]['contact_person'];
                $product['mobile'] = $jsonSupplierData[$i]['mobile'];
                $product['comp_address'] = $jsonSupplierData[$i]['comp_address'];
                array_push($sendData['supplier_list'], $product);
            }
        }
        
        //description
        $jsonArrayData = json_decode($jsonData["jsonArrayData"], true);
        $listCount = count($jsonArrayData);
        
        !empty($jsonArrayData[0]["item"]) ? $pro1 =$jsonArrayData[0]["item"] : $pro1= "";
        !empty($jsonArrayData[1]["item"]) ? $pro2 =$jsonArrayData[1]["item"] : $pro2= "";
        
        $dummyData = '';
        $desc = $this->internalsummary($projectData["project_short"],$dummyData,$pro1,$pro2);
       
        $collectionMaster->updateOne(
            array("prf_number" => $jsonData["prf_number"]),
            array('$set' => array(
                "project_id" => $projectData["_id"],
                "purpose" => $jsonData["purpose"],
                "description" => $desc,
                "grand_total" => $jsonData["grand_total"],
                "supplier_list" => $sendData['supplier_list']
        )));
       
        //delete indent table
        $purchaseData = $collectionIndentTable->find(array("prf_number" => $jsonData["prf_number"]));
        if($purchaseData) {
            foreach($purchaseData as $row) {
                $arrayParent[] = $row['_id'].$oid;
            }
        }
        for($k=0; $k<$listCount; $k++) {
            $arrayChild[] = $jsonArrayData[$k]["_id"];
        }
        $arrayDelIds = array_merge(array_diff($arrayParent, $arrayChild));
        $idCount = count($arrayDelIds);
        for($l=0; $l<$idCount; $l++) {
            $collectionIndentTable->deleteOne(array("_id" => new MongoDB\BSON\ObjectID($arrayDelIds[$l])));
        }        
        for($j=0; $j<$listCount; $j++)
        {
            if(!$jsonArrayData[$j]["stock_details"]) {
                $stockData['block_available']=0;
                $stockData['block_usage']=0;
                $stockData['site_available']=0;
                $stockData['site_usage']=0;
                $jsonArrayData[$j]["stock_details"] = $stockData;
            }
            $purchaseData = $collectionIndentTable->findOne(array("_id" => new MongoDB\BSON\ObjectID($jsonArrayData[$j]["_id"])));
            if($purchaseData) {
                //update indent table
                $collectionIndentTable->updateOne(
                    array("_id" => new MongoDB\BSON\ObjectID($jsonArrayData[$j]["_id"])),
                    array('$set' => array(
                        "code" => $jsonArrayData[$j]["code"],
                        "item" => $jsonArrayData[$j]['item'],
                        "quantity" => $jsonArrayData[$j]['quantity'],
                        "unit" => $jsonArrayData[$j]['unit'],
                        "price" => $jsonArrayData[$j]['price'],
                        "details" => $jsonArrayData[$j]["details"],
                        "make" => $jsonArrayData[$j]["make"],
                        "width" => $jsonArrayData[$j]["width"],
                        "height" => $jsonArrayData[$j]["height"],
                        "upvc_type" => $jsonArrayData[$j]["upvc_type"],
                        "size" => $jsonArrayData[$j]["size"],
                        "cft" => $jsonArrayData[$j]["cft"],
                        "stock_details" => $jsonArrayData[$j]["stock_details"],
                        "required_date" => $jsonArrayData[$j]['required_date']
                )));
            }
            else {
                //insert indent table
                $collectionIndentTable->insertOne(array(
                    "prf_number" => $jsonData["prf_number"],
                    "code" => $jsonArrayData[$j]["code"],
                    "item" => $jsonArrayData[$j]['item'],
                    "quantity" => $jsonArrayData[$j]['quantity'],
                    "unit" => $jsonArrayData[$j]['unit'],
                    "price" => $jsonArrayData[$j]['price'],
                    "details" => $jsonArrayData[$j]["details"],
                    "make" => $jsonArrayData[$j]["make"],
                    "width" => $jsonArrayData[$j]["width"],
                    "height" => $jsonArrayData[$j]["height"],
                    "upvc_type" => $jsonArrayData[$j]["upvc_type"],
                    "size" => $jsonArrayData[$j]["size"],
                    "cft" => $jsonArrayData[$j]["cft"],
                    "stock_details" => $jsonArrayData[$j]["stock_details"],
                    "required_date" => $jsonArrayData[$j]['required_date']
                ));
            }
        }
        
        //mail
        if($jsonData["to_mail"]!='')
        {
            $mailData['prf_number'] = $jsonData["prf_number"];
            $mailData['emp_id'] = $emp_id;
            $mailData['to_mail'] = $jsonData["to_mail"];
            $mailData['cc_mail'] = $jsonData["cc_mail"];
            $mailData['bcc_mail'] = $jsonData["bcc_mail"];
            $mailData['subject'] = $jsonData["subject"];
            $mailData['content'] = $jsonData["content"];
            $sendMailData['jsonArrayData'] = json_encode($mailData);
          
            $resp = $this->sendindentmail(json_encode($sendMailData));
            $response['code'] = $resp['code'];
        }
        else{
            $response['code'] = 'Success';
        }
        return $response;   
    }
    
    //get po mail content for indent
    public function getindentmailcontent($getData) 
    {
        $response = array();
        $jsonData = json_decode($getData, true);
        
        $collectionMaster = $this->conn->indent_master;
        $collectionUser = $this->conn->signintable;
        
        //from mail id
        $cursorData = $collectionUser->findOne(array("emp_id" => (int)$jsonData['emp_id']));
        $response['mail_id'] = $cursorData['mail_id'];

        //sis employees
        $product = array();
        $response['user_mail'] = array();
     
        $userData = $collectionUser->find(array("status" => "Active")); 
        foreach($userData as $rowData) {
			if($rowData['mail_id'])
			{
			$product['mail_id'] = $rowData['mail_id'];
            array_push($response['user_mail'], $product);	
			}
            
        }
        
        //subject and content
        $projectName = $jsonData['project_name'];
        $blockName = $jsonData['block_name'];
        $purpose = $jsonData['purpose'];
        
        //send mail from indent page
        if($jsonData['send_from']=='home') {
            $cursorData = $collectionMaster->aggregate(array(
                array('$match' => array(
                    "prf_number" => $jsonData['prf_number']
                )),
                array( '$lookup' => array(
                    'from' => 'project_details',
                    'localField' => 'project_id',
                    'foreignField' => '_id',
                    'as' => 'project_data'
                ))
            ));
            if($cursorData) {
                foreach($cursorData as $indentData) {
                    $projectName = $indentData["project_data"][0]['project_name'];
                    $blockName = $indentData["project_data"][0]["block_name"];
                    $purpose = $indentData["purpose"];
                }
            }
        }

        if($blockName != 'none') {
            $block = " - ".$blockName." Block";
        }
        $response['content'] = 'Dear Sir/Mam,<br><br>Please find the attached Indent form, for items which are needed for our <b>'.$projectName.$block.'.</b>';
        $response['subject'] = $projectName.' - '.$purpose.' - Indent No: '.$jsonData['prf_number'];
        
        $response["code"] = "Success";
        return $response;
    }
    
    //send indent mail
    public function sendindentmail($getData)
    {
        $response = array();
        $jsonData = json_decode($getData, true);
        $jsonArrayData = json_decode($jsonData['jsonArrayData'], true);
		
        
        // collection
        $collectionMail = $this->conn->mail_details;
        $collectionMaster = $this->conn->indent_master;
        $collectionUser = $this->conn->signintable;
        
        //purchase order details
        $cursor = $collectionMaster->findOne(array("prf_number" => $jsonArrayData['prf_number']));
        if($cursor)
        {
            //mail
            file_get_contents($this->path.'/modules/materials/indent/indent_pdf.php?type=upload&id='.$jsonArrayData['prf_number']);
            sleep(8);

            if(file_exists('../modules/materials/indent/'.$jsonArrayData['prf_number'].'.pdf'))
            {
                $indentUrl = $this->path.'/#/indent_mail/'.$jsonArrayData["prf_number"];
                
                $mailData['emp_id'] = $jsonArrayData["emp_id"];
                $mailData['to_mail'] = $jsonArrayData["to_mail"];
                $mailData['cc_mail'] = $jsonArrayData["cc_mail"];
                $mailData['bcc_mail'] = $jsonArrayData["bcc_mail"];
                $mailData['subject'] = $jsonArrayData["subject"];
                $mailData['content'] = $jsonArrayData["content"]."<br><br>
                                <style>
                                .btnLink {
                                    display: inline-block; padding: 6px 12px; font-size: 14px; line-height: 1.5; border: 1px solid transparent; border-radius: 4px; color: #fff; background-color: #337ab7; text-decoration:none; font-family: Arial, Helvetica, sans-serif; }
                                .btnLink:hover{ color:#fff; }
                                </style>
                                <a class='btnLink' href=".$indentUrl."> View </a>";
                $mailData['attachment'] = 'true';
                $mailData['attached_file'] = '../modules/materials/indent/'.$jsonArrayData['prf_number'].'.pdf';
                $mailData['attached_name'] = date("d-m-Y ").$jsonArrayData['prf_number'].'.pdf';
                    
                $resp = $this->internalMailer(json_encode($mailData));
                unlink('../modules/materials/indent/'.$jsonArrayData['prf_number'].'.pdf');
                    
                if($resp == 'Success'){
                    $collectionMaster->updateOne(
                        array("prf_number" => $jsonArrayData['prf_number']),
                        array('$set' => array(
                        "mail_status" => "send",
                        "mail_datetime" => date("Y-m-d H:i:s")
                    )));
                        
                    //mail details
                    $staffData = $collectionUser->findOne(array("emp_id" => (int)$jsonArrayData['emp_id']));
                    $collectionMail->insertOne(array(
                        "emp_id" => $staffData['emp_id'],
                        "date_time" => date("Y-m-d H:i:s"),
                        "prf_number" => $jsonArrayData['prf_number'],
                        "from_mail" => $staffData['mail_id'],
                        "to_mail" => $jsonArrayData['to_mail'],
                        "cc_mail" => $jsonArrayData['cc_mail'],
                        "bcc_mail" => $jsonArrayData['bcc_mail'],
                        "subject" => $jsonArrayData['subject'],
                        "content" => $jsonArrayData['content'],
                        "type" => 'indent'
                    ));
                }
                else{
                    $collectionMaster->updateOne(
                        array("prf_number" => $jsonArrayData['prf_number']),
                        array('$set' => array(
                            "mail_status" => "failure"
                    )));
                }
                $response['code'] = $resp;
            }
            else {
                $response['code'] = "Network Error, Try Again !!!";
            }
        }
        return $response;
    }
    
    //delete indent
    public function deleteindent($getData)
    {
        $response = array();
         
        // collection
        $collectionMaster = $this->conn->indent_master;
        $collectionIndentTable = $this->conn->indent_table;
        
        $jsonData = json_decode($getData);
        $prf_number = $jsonData->prf_number;
        $type = $jsonData->type;
        
        if($type=='deleteindent')
        {
            $result = $collectionMaster->deleteMany(array("prf_number" => $prf_number));
            if($result) {
                $cursor = $collectionIndentTable->deleteMany(array("prf_number" => $prf_number));
            }
        }
        else
        {
            if($type=='removeindent'){
                $cancelStatus='1';
            }
            elseif($type=='reopenindent'){
                $cancelStatus='0';
            }
            
            //purchase table
            $cursor = $collectionMaster->updateOne(
                array("prf_number" => $prf_number),
                array('$set' => array("cancel_status" => $cancelStatus))
            );
        }
       
        if ($cursor) {                
            $response["code"] = "Success";
        } 
        else {
            $response["code"] = "failed";               
        }
        return $response;
    }
    
    //delete all indents
    public function deleteallindent($getData)
    {
        $response = array();
         
        // collection
        $collectionMaster = $this->conn->indent_master;
        $collectionIndentTable = $this->conn->indent_table;
        
        $jsonData = json_decode($getData, true);
        
        $jsonArrayData = json_decode($jsonData["jsonArrayData"], true);
        $listCount = count($jsonArrayData);
        
        for($i=0; $i<$listCount; $i++)
        {
            if($jsonArrayData[$i]['type']=='usage') 
            {
                //purchase table
                $collectionMaster->updateOne(
                    array("prf_number" => $jsonArrayData[$i]['prf_number']),
                    array('$set' => array("cancel_status" => '1'))
                );
            }
            else {
                //purchase table
                $collectionMaster->deleteMany(array("prf_number" => $jsonArrayData[$i]['prf_number']));
                $collectionIndentTable->deleteMany(array("prf_number" => $jsonArrayData[$i]['prf_number']));
            }
        }
        $response["code"] = "Success";
        return $response;
    }
    
/***** QUOTATION *****/  

    //get indent details for new quotation
    public function getquotationdetails($getData) 
    {
        $response = array();
        $response['indent_list'] = array();
        $jsonData = json_decode($getData, true);
        
        //category
        $response["category_list"] = $this->internalcategorylist();
        
        //vendor
        $response["vendor_list"] = $this->internalvendorlist();
        
        // collection
        $collectionMaster = $this->conn->indent_master;
        $collectionQuot = $this->conn->quotation_master;
        
        //check quotation raised or not
        $quotData = $collectionQuot->findOne(array("prf_number" => $jsonData["prf_number"]));
        if($quotData) { $response["quot_status"] = "raised"; }
        else { $response["quot_status"] = ""; }
        
        $cursor = $collectionMaster->aggregate(array(
            array( '$match' => array(
                "prf_number" => $jsonData["prf_number"]
            )),
            array( '$lookup' => array(
                'from' => 'indent_table',
                'localField' => 'prf_number',
                'foreignField' => 'prf_number',
                'as' => 'indent_table_data'
            )),
            array( '$lookup' => array(
                'from' => 'signintable',
                'localField' => 'emp_id',
                'foreignField' => 'emp_id',
                'as' => 'user_data'
            )),
            array( '$lookup' => array(
                'from' => 'project_details',
                'localField' => 'project_id',
                'foreignField' => '_id',
                'as' => 'project_data'
            ))
        ));
        if($cursor) {
            foreach($cursor as $rowData)
            {
                $sendData['emp_id'] = $rowData['emp_id'];
                $sendData['name'] = $rowData['user_data'][0]['name'];
                $sendData['designation'] = $rowData['user_data'][0]['designation'];
                $sendData['prf_number'] = $rowData['prf_number'];
                $sendData['prf_date'] = $rowData['prf_date'];
                $sendData['type'] = $rowData['type'];
                $sendData['purpose'] = $rowData['purpose'];
                $sendData['sis_project_name'] = $rowData['project_data'][0]['project_name'];
                $sendData['sis_project_block'] = $rowData['project_data'][0]['block_name'];
                $sendData['place'] = $rowData['project_data'][0]['place'];
                $response["supplier_list"] = $rowData['supplier_list'];
                
                //indent table
                $productCount = count($rowData['indent_table_data']);
                for($i=0; $i<$productCount; $i++)
                {
                    $purchaseData['item']=$rowData['indent_table_data'][$i]['item'];
                    $purchaseData['quantity']=$rowData['indent_table_data'][$i]['quantity'];
                    $purchaseData['unit']=$rowData['indent_table_data'][$i]['unit'];
                    $purchaseData['price']=$rowData['indent_table_data'][$i]['price'];
                    $purchaseData['make']=$rowData['indent_table_data'][$i]['make'];
                    $purchaseData['details']=$rowData['indent_table_data'][$i]['details'];
                    $purchaseData['width']=$rowData['indent_table_data'][$i]['width'];
                    $purchaseData['height']=$rowData['indent_table_data'][$i]['height'];
                    $purchaseData['upvc_type']=$rowData['indent_table_data'][$i]['upvc_type'];
                    $purchaseData['size']=$rowData['indent_table_data'][$i]['size'];
                    $purchaseData['cft']=$rowData['indent_table_data'][$i]['cft'];
                    $purchaseData['required_date']=$rowData['indent_table_data'][$i]['required_date'];
                    $purchaseData['stock_details'] = $rowData['indent_table_data'][$i]["stock_details"];
                    $purchaseData['available_stock'] = $rowData['indent_table_data'][$i]["stock_details"]["block_available"];
                    
                    array_push($response['indent_list'], $purchaseData);
                }
            }
            $response["indent_data"] = $sendData;
            $response["code"] = "Success";
        }
        else {
            $response["code"] = "failed";
        }
        return $response;
    }
    
    //get stock details
    public function getitemstock($getData)
    {
        $jsonData = json_decode($getData, true);
        
        // mongo collection
        $collectionProject = $this->conn->project_details;
        $collectionInventory = $this->conn->inventory;
        $siteAvailable = 0;
        $siteUsage = 0;
        
        $cursorData = $collectionProject->find(array("project_name" => $jsonData["project_name"]));
        foreach($cursorData as $projectData)
        {
            $cursor = $collectionInventory->aggregate(array(
                 array( '$match' => array(
                        "project_id" => new MongoDB\BSON\ObjectID($projectData['_id']),
                        "item" => $jsonData["product_name"]
                    )),
                    array( '$group' => array(
                        '_id' => array(),
                        'available' => array('$sum' => '$available'),
                        'usage' => array('$sum' => '$usage')
                    ))
            ));
            $row['available'] = 0;
            $row['usage'] = 0;
            if($cursor)
            {
                foreach($cursor as $row)
                {
                    $siteAvailable += $row['available'];
                    $siteUsage += $row['usage'];
                    
                    //block
                    if($jsonData["block_name"] == $projectData["block_name"])
                    {
                        $product['block_available'] = $row['available'];
                        $product['block_usage'] = $row['usage'];
                    }
                }
            }
        }
        if(!$product['block_available']){ $product['block_available'] = 0; }
        if(!$product['block_usage']){ $product['block_usage'] = 0; }
        $product['site_available'] = $siteAvailable;
        $product['site_usage'] = $siteUsage;
        
        $response['stock_details'] = $product;
        return $response;
    }
    
    //new quotation
    public function newquotation($getData)
    {
        $response = array();
        $collection = $this->conn->quotation_master;
        $collectionDuration = $this->conn->quotation_duration;
        $collectionIndent = $this->conn->indent_master;
        $collectionProject = $this->conn->project_details;
        
        $jsonData = json_decode($getData, true);
        $jsonArrayData = json_decode($jsonData['jsonArrayData'], true);
        
        //get project details
        $projectData = $collectionProject->findOne(array("project_name" => $jsonData["sis_project_name"], "block_name" => $jsonData["sis_project_block"]));
        
        //indent update
        $indentData = $collectionIndent->findOne(array("prf_number" => $jsonData['prf_number']));
        if($indentData) {
            $respDuration = $this->secondsToWords($indentData['mail_datetime'], date("Y-m-d H:i:s"));
            $collectionIndent->updateOne(
                array("prf_number" => $jsonData['prf_number']),
                array('$set' => array(
                "quot_status" => "1",
                "quot_status_details" => "Quotation Raised",
                "quot_duration" => $respDuration
            )));
        }
        
        $quotCount = count($jsonArrayData);
        for($i=0; $i<$quotCount; $i++)
        {
            //get quotation number
            $validate = $collection->findOne(array("project_short" => $projectData['project_short']));
            if($validate) {
                $regex = new \MongoDB\BSON\Regex($projectData['project_short']);    //LIKE query
                $results = $collection->aggregate(array(
                    array( '$match' => array( 'quot_number' => $regex )),
                    array( '$project' => array( '_id' => 0, 'quot_number' => 1 )),
                    array( '$sort' => array( 'quot_number' => -1 )),
                    array( '$limit' => 1 )
                ));
                foreach($results as $rowInvData)
                {
                    $invNo = preg_replace("/[^0-9,.]/", "", $rowInvData['quot_number']) + 1;
                    $quotNumber = 'Q-'.$projectData['project_short'].sprintf('%04d',$invNo);
                }
            }
            else {
                $quotNumber = 'Q-'.$projectData['project_short'].'0001';
            }
            
            //description
            !empty($jsonArrayData[$i]['quot_items'][0]["table_items"][0]["item"]) ? $pro1 =$jsonArrayData[$i]['quot_items'][0]["table_items"][0]["item"] : $pro1= "";
            !empty($jsonArrayData[$i]['quot_items'][0]["table_items"][1]["item"]) ? $pro2 =$jsonArrayData[$i]['quot_items'][0]["table_items"][1]["item"] : $pro2= "";
            $dummyData = '';
            $desc = $this->internalsummary($projectData['project_short'],$dummyData,$pro1,$pro2);
            
            if($jsonData['approve_status']=='approve')
            {
                $collection->insertOne(array(
                    "emp_id" => (int)$jsonData["emp_id"],
                    "prf_number" => $jsonData["prf_number"],
                    "quot_number" => $quotNumber,
                    "quot_date" => date("d-m-Y"),
                    "project_id" => $projectData["_id"],
                    "project_short" => $projectData["project_short"],
                    "type" => $jsonArrayData[$i]["type"],
                    "category" => $jsonArrayData[$i]["category"],
                    "sub_category" => $jsonArrayData[$i]["sub_category"],
                    "description" => $desc,
                    "final_vendor" => $jsonArrayData[$i]['quot_items'],
                    "date_time" => date("Y-m-d H:i:s"),
                    "approved_status" => "approved",
                    "approved_by" => (int)$jsonData["emp_id"],
                    "mail_status" => "send"
                ));
                
                //indent status
                /*$collectionIndent->updateOne(
                    array("prf_number" => $jsonData['prf_number']),
                    array('$set' => array(
                    "quot_status_details" => "Approved"
                )));*/

                //timer
                $collectionDuration->insertOne(array(
                    "quot_number" => $quotNumber,
                    "quot_datetime" => date("Y-m-d H:i:s"),
                    "proceed_duration" => '0 min',
                    "proceed_datetime" => date("Y-m-d H:i:s"),
                    "approved_duration" => '0 min',
                    "approved_datetime" => date("Y-m-d H:i:s"),
                ));
            }
            elseif($jsonData['approve_status']=='save')
            {
                $collection->insertOne(array(
                    "emp_id" => (int)$jsonData["emp_id"],
                    "prf_number" => $jsonData["prf_number"],
                    "quot_number" => $quotNumber,
                    "quot_date" => date("d-m-Y"),
                    "project_id" => $projectData["_id"],
                    "project_short" => $projectData["project_short"],
                    "type" => $jsonArrayData[$i]["type"],
                    "category" => $jsonArrayData[$i]["category"],
                    "sub_category" => $jsonArrayData[$i]["sub_category"],
                    "description" => $desc,
                    "quot_list" => $jsonArrayData[$i]['quot_items'],
                    "date_time" => date("Y-m-d H:i:s"),
                    "approved_status" => "",
                    "mail_status" => ""
                ));

                //timer
                $collectionDuration->insertOne(array(
                    "quot_number" => $quotNumber,
                    "quot_datetime" => date("Y-m-d H:i:s")
                ));
            }
        }
        $response['quot_number'] = $quotNumber;
        $response['code'] = 'Success';
        return $response;
    }
    
    //delete quotation
    public function deletequotation($getData)
    {
        $response = array();
         
        // collection
        $collectionIndent = $this->conn->indent_master;
        $collectionMaster = $this->conn->quotation_master;
        $collectionDuration = $this->conn->quotation_duration;
        
        $jsonData = json_decode($getData, true);
        
        $cursorData = $collectionMaster->findOne(array("quot_number" => $jsonData['quot_number']));
        if($cursorData) {
            $prfNumber = $cursorData['prf_number'];
            
            $collectionMaster->deleteMany(array("quot_number" => $jsonData['quot_number']));
            $collectionDuration->deleteMany(array("quot_number" => $jsonData['quot_number']));
            
            $count = $collectionMaster->count(array("prf_number" => $prfNumber));
            if($count==0) {
                $collectionIndent->updateOne(
                    array("prf_number" => $prfNumber),
                    array('$unset' => array("quot_status" => '', "quot_duration" => ''))
                );
            }
            $response["code"] = "Success";
        }
        else { $response["code"] = "failed"; }
        return $response;
    }
    
    //purchase quotation list
    public function getquotationlist($getData) 
    {
        $response = array();
        $response['quotation_data'] = array();
        $jsonData = json_decode($getData, true);
      
		  $response["jsonData"] = $jsonData;
		
		// collection
        $collectionMaster = $this->conn->quotation_master;
        
        //site list
        $siteData = $this->internalsitelist($jsonData['emp_id']);
        $response['site_list'] = $siteData["site_list"];
        
        $proShortArray = $siteData["project_short_list"];		
        if($jsonData['project_short']!='') { $proShortArray = array($jsonData['project_short']); }
		 if($jsonData["status"]!='') { $statusArray = array($jsonData["status"]); }
		else
		{
			$statusArray = array("", "approved", "declined");	
		}
	
		
		if( $jsonData['status'] == 'pending')
		{			 
		$result = $collectionMaster->aggregate(array(
            array( '$match' => array(
               '$and' => array(
        array('approved_status' => array('$nin' => array('approved', 'declined')))       
    ),
                "project_short" => array( '$in' => $proShortArray )
            )),
            array( '$lookup' => array(
                'from' => 'quotation_duration',
                'localField' => 'quot_number',
                'foreignField' => 'quot_number',
                'as' => 'duration_data'
            )),
            array( '$sort' => array( 
                '_id' => -1
            ))
        ));	
			
		}
		else
		{
		$result = $collectionMaster->aggregate(array(
            array( '$match' => array(
                "approved_status" => array( '$in' =>$statusArray ),
                "project_short" => array( '$in' => $proShortArray )
            )),
            array( '$lookup' => array(
                'from' => 'quotation_duration',
                'localField' => 'quot_number',
                'foreignField' => 'quot_number',
                'as' => 'duration_data'
            )),
            array( '$sort' => array( 
                '_id' => -1
            ))
        ));
		}
 
        if($result)
        {
            $product = array();
            foreach($result as $rows)
            {
                if(!$rows['approved_status']){ $rows['approved_status']=''; }
                $product['prf_number'] = $rows['prf_number'];
                $product['quot_number'] = $rows['quot_number'];
                $product['quot_date'] = $rows['quot_date'];
                $product['summary'] = $rows['description'];
                $product['mail_status'] = $rows['mail_status'];
                $product['approved_status'] = $rows['approved_status'];
                $product['po_raised'] = $rows['po_raised'];
                
                //indent status
                if($rows['duration_data'][0]['approved_duration'])
                {
                    $product['quot_status'] = 'Approved<br>'.$rows['duration_data'][0]['approved_duration'];
                }
                elseif($rows['duration_data'][0]['declined_duration'])
                {
                    $product['quot_status'] = 'Declined<br>'.$rows['duration_data'][0]['declined_duration'];
                }
                elseif($rows['duration_data'][0]['proceed_datetime'])
                {
                    $respDuration = $this->secondsToWords($rows['duration_data'][0]['proceed_datetime'], date("Y-m-d H:i:s"));
                    $product['quot_status'] = 'Approval Pending<br>'.$respDuration;
                }
                else {
                    if($rows['duration_data'][0]['quot_datetime']) {
                        $respDuration = $this->secondsToWords($rows['duration_data'][0]['quot_datetime'], date("Y-m-d H:i:s"));
                        $product['quot_status'] = 'Raised<br>'.$respDuration;
                    }
                    else {
                        $product['quot_status'] = '-';
                    }
                }

                //po status
                if($rows['duration_data'][0]['po_duration'])
                {
                    $product['po_status'] = 'Raised<br>'.$rows['duration_data'][0]['po_duration'];
                }
                else {
                    if($rows['duration_data'][0]['approved_datetime']) {
                        $respDuration = $this->secondsToWords($rows['duration_data'][0]['approved_datetime'], date("Y-m-d H:i:s"));
                        $product['po_status'] = 'Pending<br>'.$respDuration;
                    }
                    else {
                        $product['po_status'] = '-';
                    }
                }
                
                array_push($response['quotation_data'], $product);
            }
            $response["code"] = "Success";
        }
        else {
            $response["code"] = "failed";
        }
        return $response;
    }
    
    //view purchase quotation
    public function viewpurchasequotation($getData) 
    {
        $jsonData = json_decode($getData, true);
        
        $response = array();
        $response["indent_data"] = array();
        $response["product_list"] = array();
        $response["user_mail"] = array();
        
        $collectionIndent = $this->conn->indent_master;
        $collectionMaster = $this->conn->quotation_master;
        $collectionUser = $this->conn->user_list;
        
        //user data
        $userData = $collectionUser->findOne(array("emp_id" => (int)$jsonData['emp_id']));
        
        //sis employees
        $product = array();
        $cursorMailData = $collectionUser->find(array("department" => "Purchase", "status" => "Active"));
        foreach($cursorMailData as $rowUserData) {
            $product['mail_id'] = $rowUserData['email'];
            array_push($response['user_mail'], $product);
        }
        
        //purchase order details
        $cursor = $collectionMaster->aggregate(array(
                array('$match' => array(
                    "quot_number" => $jsonData['quot_number']
                )),
                array( '$lookup' => array(
                    'from' => 'indent_master',
                    'localField' => 'prf_number',
                    'foreignField' => 'prf_number',
                    'as' => 'indent_data'
                )),
                array( '$lookup' => array(
                    'from' => 'indent_table',
                    'localField' => 'prf_number',
                    'foreignField' => 'prf_number',
                    'as' => 'indent_table_data'
                )),
                array( '$lookup' => array(
                    'from' => 'signintable',
                    'localField' => 'approved_by',
                    'foreignField' => 'emp_id',
                    'as' => 'admin_data'
                )),
                array( '$lookup' => array(
                    'from' => 'project_details',
                    'localField' => 'project_id',
                    'foreignField' => '_id',
                    'as' => 'project_details_data'
                ))
            ));
        if($cursor)
        {
            foreach($cursor as $rowData)
            {
                $cursorUser = $collectionIndent->aggregate(array(
                    array('$match' => array(
                        "prf_number" => $rowData['indent_data'][0]['prf_number']
                    )),
                    array( '$lookup' => array(
                        'from' => 'signintable',
                        'localField' => 'emp_id',
                        'foreignField' => 'emp_id',
                        'as' => 'user_data'
                    )),
                ));
                if($cursorUser)
                {
                    foreach($cursorUser as $rowUserData)
                    {
                        $sendData['emp_id'] = $rowUserData['user_data'][0]['emp_id'];
                        $sendData['name'] = $rowUserData['user_data'][0]['name'];
                        $sendData['designation'] = $rowUserData['user_data'][0]['designation'];
                        $sendData["cc_mail"] = $rowUserData['user_data'][0]['mail_id'];
                    }
                }
                if(!$rowData['approved_status']){ $rowData['approved_status']=''; }
                $sendData['type'] = $rowData['type'];
                $sendData['prf_number'] = $rowData['indent_data'][0]['prf_number'];
                $sendData['prf_date'] = $rowData['indent_data'][0]['prf_date'];
                $sendData['grand_total'] = $rowData['indent_data'][0]['grand_total'];
                $sendData['quot_number'] = $rowData['quot_number'];
                $sendData['quot_date'] = $rowData['quot_date'];
                $sendData['purpose'] = $rowData['indent_data'][0]['purpose'];
                $sendData['approved_status'] = $rowData['approved_status'];
                $sendData['sis_project_name'] = $rowData['project_details_data'][0]['project_name'];
                $sendData['sis_project_block'] = $rowData['project_details_data'][0]['block_name'];
                $sendData['place'] = $rowData['project_details_data'][0]['place'];
                $sendData['admin_name'] = $rowData['admin_data'][0]['name'];
                $sendData['admin_designation'] = $rowData['admin_data'][0]['designation'];
                $sendData['admin_sign'] = $rowData['admin_data'][0]['sign'];
                $sendData["from_mail"] = $userData['email'];
                
                //indent table
                $productCount = count($rowData['indent_table_data']);
                for($i=0; $i<$productCount; $i++)
                {
                    $purchaseData['item']=$rowData['indent_table_data'][$i]['item'];
                    $purchaseData['quantity']=$rowData['indent_table_data'][$i]['quantity'];
                    $purchaseData['unit']=$rowData['indent_table_data'][$i]['unit'];
                    $purchaseData['price']=$rowData['indent_table_data'][$i]['price'];
                    if($rowData['type']=='Door'){
                        $purchaseData['amount'] = ($rowData['indent_table_data'][$i]['cft'] * $rowData['indent_table_data'][$i]['price']);
                    }
                    else{
                        $purchaseData['amount'] = ($rowData['indent_table_data'][$i]['quantity'] * $rowData['indent_table_data'][$i]['price']);
                    }
                    $purchaseData['total']=sprintf("%.2f", $purchaseData['amount']);
                    $purchaseData['make']=$rowData['indent_table_data'][$i]['make'];
                    $purchaseData['details']=$rowData['indent_table_data'][$i]['details'];
                    $purchaseData['width']=$rowData['indent_table_data'][$i]['width'];
                    $purchaseData['height']=$rowData['indent_table_data'][$i]['height'];
                    $purchaseData['upvc_type']=$rowData['indent_table_data'][$i]['upvc_type'];
                    $purchaseData['size']=$rowData['indent_table_data'][$i]['size'];
                    $purchaseData['cft']=$rowData['indent_table_data'][$i]['cft'];
                    $purchaseData['required_date']=$rowData['indent_table_data'][$i]['required_date'];
                    $purchaseData['available_stock'] = $rowData['indent_table_data'][$i]["stock_details"]["block_available"];
                    
                    array_push($response['product_list'], $purchaseData);
                }
        
                $response['final_list'] = $rowData['final_vendor'];
                $response['quotation_list'] = $rowData['quot_list'];
                $response["supplier_list"] = $rowData['indent_data'][0]['supplier_list'];
            }
            $response["indent_data"] = $sendData;
            $response["code"] = "Success";
        }
        else {
            $response["code"] = "failed";
        }   
        return $response;
    }
    
    //edit purchase quotation
    public function editpurchasequotation($getData)
    {
        $response = array();
        $response["product_list"] = array();
        
        //collection
        $collectionMaster = $this->conn->quotation_master;
        
        //vendor
        $arrayData['cancel_status'] = 'usage';
        $vendorData = $this->getvendorlist(json_encode($arrayData));
        $response["vendor_list"] = $vendorData["vendor_details"];
        
        $jsonData = json_decode($getData, true);
        
        //purchase order details
        $cursor = $collectionMaster->aggregate(array(
                array('$match' => array(
                    "quot_number" => $jsonData['quot_number']
                )),
                array( '$lookup' => array(
                    'from' => 'project_details',
                    'localField' => 'project_id',
                    'foreignField' => '_id',
                    'as' => 'project_details_data'
                ))
            ));
        if($cursor)
        {
            foreach($cursor as $rowData)
            {
                if(!$rowData['approved_status']){ $rowData['approved_status']=''; }
                $sendData['prf_number'] = $rowData['prf_number'];
                $sendData['quot_number'] = $rowData['quot_number'];
                $sendData['quot_date'] = $rowData['quot_date'];
                $sendData['type'] = $rowData['type'];
                $sendData['category'] = $rowData['category'];
                $sendData['sub_category'] = $rowData['sub_category'];
                $sendData['purpose'] = $rowData['purpose'];
                $sendData['approved_status'] = $rowData['approved_status'];
                $sendData['sis_project_name'] = $rowData['project_details_data'][0]['project_name'];
                $sendData['sis_project_block'] = $rowData['project_details_data'][0]['block_name'];
                $sendData['quot_list'] = $rowData['quot_list'];
                
                //product
                $proData['type'] = $rowData['type'];
                $proData['category'] = $rowData['category'];
                $proData['sub_category'] = $rowData['sub_category'];
                $response["product_list"] = $this->internalproductlist(json_encode($proData));
                
                $response["invoice_data"] = $sendData;
                $response["quot_list"] = $rowData['quot_list'];
                $response["code"] = "Success";
            }
        }
        else {
            $response["code"] = "failed";
        }
              
        return $response;        
    }
    
    //update purchase quotation
    public function updatepurchasequotation($getData)
    {
        $response = array();
        
        // collection
        $collectionMaster = $this->conn->quotation_master;
        
        $jsonData = json_decode($getData, true);
        $emp_id = (int)$jsonData["emp_id"];
        
        //vendor product list
        $jsonArrayData = json_decode($jsonData["jsonArrayData"], true);
        $arrayCount = count($jsonArrayData);
        $sendData['vendor_items'] = array();
        for($i=0; $i<$arrayCount; $i++)
        {
            $product['vendor_name'] = $jsonArrayData[$i]['vendor_name'];
            $product['reason'] = $jsonArrayData[$i]['reason'];
            $product['grand_total'] = $jsonArrayData[$i]['grand_total'];
            $product['table_items'] = $jsonArrayData[$i]['table_items'];
            array_push($sendData['vendor_items'], $product);
        }
       
        $collectionMaster->updateOne(
            array("quot_number" => $jsonData["quot_number"]),
            array('$set' => array(
                "quot_list" => $sendData['vendor_items']
        )));
        
        //mail
        if($jsonData["to_mail"]!='')
        {
            $mailData['quot_number'] = $jsonData["quot_number"];
            $mailData['emp_id'] = $emp_id;
            $mailData['to_mail'] = $jsonData["to_mail"];
            $mailData['cc_mail'] = $jsonData["cc_mail"];
            $mailData['bcc_mail'] = $jsonData["bcc_mail"];
            $mailData['subject'] = $jsonData["subject"];
            $mailData['content'] = $jsonData["content"];
            $sendMailData['jsonArrayData'] = json_encode($mailData);
          
            $resp = $this->sendquotationmail(json_encode($sendMailData));
            $response['code'] = $resp['code'];
        }
        else {
            $response['code'] = 'Success';
        }
        return $response;   
    }
    
    //update and approve purchase quotation
    public function updateandapprovequotation($getData)
    {
        $response = array();
        
        // collection
        $collectionMaster = $this->conn->quotation_master;
        $collectionDuration = $this->conn->quotation_duration;
        $jsonData = json_decode($getData, true);
        
        //vendor product list
        $jsonArrayData = json_decode($jsonData["jsonArrayData"], true);
        $arrayCount = count($jsonArrayData);
        $sendData['final_items'] = array();
        for($i=0; $i<$arrayCount; $i++)
        {
            $product['vendor_name'] = $jsonArrayData[$i]['vendor_name'];
            $product['reason'] = $jsonArrayData[$i]['reason'];
            $product['grand_total'] = $jsonArrayData[$i]['grand_total'];
            $product['table_items'] = $jsonArrayData[$i]['table_items'];
            array_push($sendData['final_items'], $product);
        }
        
        //remove quoted list for self approve
        $collectionMaster->updateOne(
            array("quot_number" => $jsonData["quot_number"]),
            array('$unset' => array(
                "quot_list" => ''
            ))
        );
        
        $collectionMaster->updateOne(
            array("quot_number" => $jsonData["quot_number"]),
            array('$set' => array(
                "final_vendor" => $sendData['final_items'],
                "approved_status" => 'approved',
                "approved_by" => (int)$jsonData['emp_id'],
                "mail_status" => 'send'
        )));
        
        //timer
        $durationData = $collectionDuration->findOne(array("quot_number" => $jsonData["quot_number"]));
        if($durationData)
        {
            if(!$durationData['proceed_datetime']){
                $respDuration = $this->secondsToWords($durationData['indent_datetime'], date("Y-m-d H:i:s"));
                $collectionDuration->updateOne(
                    array("quot_number" => $jsonData['quot_number']),
                    array('$set' => array(
                    "proceed_duration" => $respDuration,
                    "proceed_datetime" => date("Y-m-d H:i:s"),
                    "approved_duration" => "0 min",
                    "approved_datetime" => date("Y-m-d H:i:s")
                )));
            }
            elseif(!$durationData['approved_datetime']){
                $respDuration = $this->secondsToWords($durationData['proceed_datetime'], date("Y-m-d H:i:s"));
                $collectionDuration->updateOne(
                    array("quot_number" => $jsonData['quot_number']),
                    array('$set' => array(
                    "approved_duration" => $respDuration,
                    "approved_datetime" => date("Y-m-d H:i:s")
                )));
            }
        }
        $response['code'] = 'Success';
        return $response;   
    }
    
    //get mail content for quotation
    public function getquotationmailcontent($getData) 
    {
        $response = array();
        $jsonData = json_decode($getData, true);
        
        $collectionMaster = $this->conn->quotation_master;
        $collectionUser = $this->conn->signintable;
        
        //from mail id
        $cursorData = $collectionUser->findOne(array("emp_id" => (int)$jsonData['emp_id']));
        $response['mail_id'] = $cursorData['mail_id'];

        //sis employees
        $product = array();
        $response['user_mail'] = array();
        
        $userData = $collectionUser->find(array('$or' => array(
            array("department" => "Management"),
            array("department" => "Purchase")
        )));
        foreach($userData as $rowData) {
            $product['mail_id'] = $rowData['mail_id'];
            array_push($response['user_mail'], $product);
        }
        
        $cursorData = $collectionMaster->aggregate(array(
            array('$match' => array(
                "quot_number" => $jsonData['quot_number']
            )),
            array( '$lookup' => array(
                'from' => 'project_details',
                'localField' => 'project_id',
                'foreignField' => '_id',
                'as' => 'project_data'
            ))
        ));
        foreach($cursorData as $indentData)
        {
            if($indentData["project_data"][0]["block_name"] != 'none') {
                $block = " - ".$indentData["project_data"][0]["block_name"]." Block";
            }
            if(!$jsonData['purpose']) {
                $jsonData['purpose'] = $indentData['purpose'];
            }
            $response['content'] = 'Dear Sir/Mam,<br><br>Please find the attached Quotation form, for items which are needed for our <b>'.$indentData["project_data"][0]['project_name'].$block.'.</b>';
            $response['subject'] = $indentData["project_data"][0]['project_name'].' - Quotation No: '.$jsonData['quot_number'];
        }
        
        $response["code"] = "Success";
        return $response;
    }
    
    //send quotation mail
    public function sendquotationmail($getData)
    {
        $response = array();
        $jsonData = json_decode($getData, true);
        $jsonArrayData = json_decode($jsonData['jsonArrayData'], true);
        
        // collection
        $collectionMail = $this->conn->mail_details;
        $collectionMaster = $this->conn->quotation_master;
        $collectionUser = $this->conn->signintable;
        $collectionDuration = $this->conn->quotation_duration;
        
        //purchase order details
        $cursor = $collectionMaster->aggregate(array(
                array('$match' => array(
                    "quot_number" => $jsonArrayData['quot_number']
                )),
                array('$lookup' => array(  
                    'from' => 'project_details',
                    'localField' => 'project_id',
                    'foreignField' => '_id',
                    'as' => 'project_data'
                ))
            ));
        if($cursor)
        {
            foreach($cursor as $rowData)
            {
                //mail
                file_get_contents($this->path.'/modules/materials/quotation/quotation_pdf.php?type=upload&id='.$jsonArrayData['quot_number']);
                sleep(8);

                if(file_exists('../modules/uploads/'.$jsonArrayData['quot_number'].'.pdf'))
                {
                    $tableContent = "<br><br>
                    <table cellpadding='0' cellspacing='0' width='100%' border='0' style='border-collapse:collapse; border:1px solid #000; font-size:15px;'>
                    <thead>
                        <tr>
                            <th rowspan='2' style='border:solid 1px #000; padding: 5px; width:30px;'>No</th>
                            <th rowspan='2' style='border:solid 1px #000; padding: 5px;' align='left'>Product Name</th>
                            <th rowspan='2' style='border:solid 1px #000; padding: 5px;'>Qty</th>
                            <th colspan='2' style='border:solid 1px #000; padding: 5px;'>Available</th>
                            <th colspan='2' style='border:solid 1px #000; padding: 5px;'>Usage</th>
                        </tr>
                        <tr>
                            <th style='border:solid 1px #000; padding: 5px;'>Block</th>
                            <th style='border:solid 1px #000; padding: 5px;'>Site</th>
                            <th style='border:solid 1px #000; padding: 5px;'>Block</th>
                            <th style='border:solid 1px #000; padding: 5px;'>Site</th>
                        </tr>
                    </thead>
                    <tbody>";
                    
                    $n=1;
                    $productCount = count($rowData['quot_list'][0]['table_items']);
                    for($i=0; $i<$productCount; $i++)
                    {
                        if($rowData['quot_list'][0]['table_items'][$i]["stock_details"]) {
                            $blockAvailable = $rowData['quot_list'][0]['table_items'][$i]["stock_details"]["block_available"];
                            $siteAvailable = $rowData['quot_list'][0]['table_items'][$i]["stock_details"]["site_available"];
                            $blockUsage = $rowData['quot_list'][0]['table_items'][$i]["stock_details"]["block_usage"];
                            $siteUsage = $rowData['quot_list'][0]['table_items'][$i]["stock_details"]["site_usage"];
                        }
                        else {
                            $blockAvailable = 0; $siteAvailable = 0; $blockUsage = 0; $siteUsage = 0;
                        }
                        
                        $tableContent.= "
                        <tr>
                            <td style='border:solid 1px #000; padding: 5px;' align='center'>".$n."</td>
                            <td style='border:solid 1px #000; padding: 5px;' align='left'>".$rowData['quot_list'][0]['table_items'][$i]['item']."</td>
                            <td style='border:solid 1px #000; padding: 5px;' align='center'>".$rowData['quot_list'][0]['table_items'][$i]['quantity']." ".$rowData['quot_list'][0]['table_items'][$i]['unit']."</td>
                            <td style='border:solid 1px #000; padding: 5px;' align='center'>".$blockAvailable." ".$rowData['quot_list'][0]['table_items'][$i]['unit']."</td>
                            <td style='border:solid 1px #000; padding: 5px;' align='center'>".$siteAvailable." ".$rowData['quot_list'][0]['table_items'][$i]['unit']."</td>
                            <td style='border:solid 1px #000; padding: 5px;' align='center'>".$blockUsage." ".$rowData['quot_list'][0]['table_items'][$i]['unit']."</td>
                            <td style='border:solid 1px #000; padding: 5px;' align='center'>".$siteUsage." ".$rowData['quot_list'][0]['table_items'][$i]['unit']."</td>
                        </tr>";
                        $n++;
                    }
                    $tableContent .= "</tbody></table>";
                    
                    $approveUrl = $this->path.'/#/quotation_approve/'.$jsonArrayData["quot_number"];

                    $mailData['emp_id'] = $jsonArrayData["emp_id"];
                    $mailData['to_mail'] = $jsonArrayData["to_mail"];
                    $mailData['cc_mail'] = $jsonArrayData["cc_mail"];
                    $mailData['bcc_mail'] = $jsonArrayData["bcc_mail"];
                    $mailData['subject'] = $jsonArrayData["subject"];
                    $mailData['content'] = $jsonArrayData["content"]."<br><br>
                                <style>
                                .btnLink {
                                    display: inline-block; padding: 6px 12px; font-size: 14px; line-height: 1.5; border: 1px solid transparent; border-radius: 4px; color: #fff; background-color: #337ab7; text-decoration:none; font-family: Arial, Helvetica, sans-serif; }
                                .btnLink:hover{ color:#fff; }
                                </style>
                                <a class='btnLink' href=".$approveUrl."> View </a>
                                ".$tableContent;
                    $mailData['attachment'] = 'true';
                    $mailData['attached_file'] = '../modules/uploads/'.$jsonArrayData['quot_number'].'.pdf';
                    $mailData['attached_name'] = date("d-m-Y ").$jsonArrayData['quot_number'].'.pdf';
                    $resp = $this->internalMailer(json_encode($mailData));
                    
                    unlink('../modules/uploads/'.$jsonArrayData['quot_number'].'.pdf');
                    if($resp == 'Success'){
                        $collectionMaster->updateOne(
                            array("quot_number" => $jsonArrayData['quot_number']),
                            array('$set' => array(
                                "mail_status" => "send"
                        )));
                        
                        //timer
                        $durationData = $collectionDuration->findOne(array("quot_number" => $jsonArrayData['quot_number']));
                        if($durationData)
                        {
                            if(!$durationData['proceed_datetime']) {
                                $respDuration = $this->secondsToWords($durationData['quot_datetime'], date("Y-m-d H:i:s"));
                                $collectionDuration->updateOne(
                                    array("quot_number" => $jsonArrayData['quot_number']),
                                    array('$set' => array(
                                    "proceed_duration" => $respDuration,
                                    "proceed_datetime" => date("Y-m-d H:i:s")
                                )));
                            }
                        }
                        
                        //mail details
                        $staffData = $collectionUser->findOne(array("emp_id" => (int)$jsonArrayData['emp_id']));
                        $collectionMail->insertOne(array(
                            "emp_id" => $staffData['emp_id'],
                            "date_time" => date("Y-m-d H:i:s"),
                            "quot_number" => $jsonArrayData['quot_number'],
                            "from_mail" => $staffData['mail_id'],
                            "to_mail" => $jsonArrayData['to_mail'],
                            "cc_mail" => $jsonArrayData['cc_mail'],
                            "bcc_mail" => $jsonArrayData['bcc_mail'],
                            "subject" => $jsonArrayData['subject'],
                            "content" => $jsonArrayData['content'],
                            "type" => 'quotation'
                        ));
                        
                        $response['code'] = 'Success';
                    }
                    else{
                        $collectionMaster->updateOne(
                            array("quot_number" => $jsonArrayData['quot_number']),
                            array('$set' => array(
                                "mail_status" => "failure"
                        )));
                        
                        $response['code'] = 'failure';
                    }
                }
                else {
                    $response['code'] = "Network Error, Try Again !!!";
                }
            }
        }
        return $response;
    }
    
     //approve indent
    public function approvequotation($getData)
    {
        $response = array();
        
        // collection
        $collectionMaster = $this->conn->quotation_master;
        $collectionUser = $this->conn->signintable;
        $collectionMail = $this->conn->mail_details;
        $collectionDuration = $this->conn->quotation_duration;
        
        $jsonData = json_decode($getData, true);
        
        if($jsonData["submit_type"]=='approved')
        {  
            //vendor product list
            $jsonArrayData = json_decode($jsonData["jsonArrayData"], true);
            $arrayCount = count($jsonArrayData);
            $sendData['final_items'] = array();
            for($i=0; $i<$arrayCount; $i++)
            {
                $product['vendor_name'] = $jsonArrayData[$i]['vendor_name'];
                $product['reason'] = $jsonArrayData[$i]['reason'];
                $product['grand_total'] = $jsonArrayData[$i]['grand_total'];
                $product['table_items'] = $jsonArrayData[$i]['table_items'];
                array_push($sendData['final_items'], $product);
            }

            $collectionMaster->updateOne(
                array("quot_number" => $jsonData["quot_number"]),
                array('$set' => array(
                    "approved_status" => 'approved',
                    "approved_by" => (int)$jsonData["emp_id"],
                    "final_vendor" => $sendData['final_items']
            )));
        }
        else {
            $collectionMaster->updateOne(
                array("quot_number" => $jsonData["quot_number"]),
                array('$set' => array(
                    "approved_status" => 'declined',
                    "approved_by" => (int)$jsonData["emp_id"]
            )));
        }
        
        //mail
        file_get_contents($this->path.'/modules/materials/quotation/quotation_pdf.php?type=upload&id='.$jsonData['quot_number']);
        sleep(8);
    
        if(file_exists('../modules/uploads/'.$jsonData['quot_number'].'.pdf'))
        {
            $mailData['emp_id'] = $jsonData["emp_id"];
            $mailData['to_mail'] = $jsonData["to_mail"];
            $mailData['cc_mail'] = $jsonData["cc_mail"];
            $mailData['bcc_mail'] = $jsonData["bcc_mail"];
            $mailData['subject'] = $jsonData["subject"];
            $mailData['content'] = $jsonData["content"];
            $mailData['attachment'] = 'true';
            $mailData['attached_file'] = '../modules/uploads/'.$jsonData['quot_number'].'.pdf';
            $mailData['attached_name'] = date("d-m-Y ").$jsonData['quot_number'].'.pdf';
                   
            $resp = $this->internalMailer(json_encode($mailData));
            unlink('../modules/uploads/'.$jsonData['quot_number'].'.pdf');
            
            if($resp=='Success')
            {
                $collectionMaster->updateOne(
                    array("quot_number" => $jsonData['quot_number']),
                    array('$set' => array(
                        "mail_status" => "send"
                    )));
                
                //timer
                $durationData = $collectionDuration->findOne(array("quot_number" => $jsonData['quot_number']));
                if($jsonData['submit_type']=='approved')
                {
                    if($durationData)
                    {
                        if(!$durationData['proceed_datetime']){
                            $respDuration = $this->secondsToWords($durationData['quot_datetime'], date("Y-m-d H:i:s"));
                            $collectionDuration->updateOne(
                                array("quot_number" => $jsonData['quot_number']),
                                array('$set' => array(
                                "proceed_duration" => $respDuration,
                                "proceed_datetime" => date("Y-m-d H:i:s"),
                                "approved_duration" => "0 min",
                                "approved_datetime" => date("Y-m-d H:i:s")
                            )));
                        }
                        elseif(!$durationData['approved_datetime']){
                            $respDuration = $this->secondsToWords($durationData['proceed_datetime'], date("Y-m-d H:i:s"));
                            $collectionDuration->updateOne(
                                array("quot_number" => $jsonData['quot_number']),
                                array('$set' => array(
                                "approved_duration" => $respDuration,
                                "approved_datetime" => date("Y-m-d H:i:s")
                            )));
                        }
                    }
                }
                else {
                    if($durationData)
                    {
                        if(!$durationData['proceed_datetime']){
                            $respDuration = $this->secondsToWords($durationData['quot_datetime'], date("Y-m-d H:i:s"));
                            $collectionDuration->updateOne(
                                array("quot_number" => $jsonData['quot_number']),
                                array('$set' => array(
                                "proceed_duration" => $respDuration,
                                "proceed_datetime" => date("Y-m-d H:i:s"),
                                "declined_duration" => "0 min",
                                "declined_datetime" => date("Y-m-d H:i:s")
                            )));
                        }
                        elseif(!$durationData['declined_datetime']){
                            $respDuration = $this->secondsToWords($durationData['proceed_datetime'], date("Y-m-d H:i:s"));
                            $collectionDuration->updateOne(
                                array("quot_number" => $jsonData['quot_number']),
                                array('$set' => array(
                                "declined_duration" => $respDuration,
                                "declined_datetime" => date("Y-m-d H:i:s")
                            )));
                        }
                    }
                }
                
                //mail details
                $staffData = $collectionUser->findOne(array("emp_id" => (int)$jsonData['emp_id']));
                $collectionMail->insertOne(array(
                    "emp_id" => $staffData['emp_id'],
                    "date_time" => date("Y-m-d H:i:s"),
                    "quot_number" => $jsonData['quot_number'],
                    "from_mail" => $staffData['mail_id'],
                    "to_mail" => $jsonData['to_mail'],
                    "cc_mail" => $jsonData['cc_mail'],
                    "bcc_mail" => $jsonData['bcc_mail'],
                    "subject" => $jsonData['subject'],
                    "content" => $jsonData['content'],
                    "type" => 'approver'
                ));
            
                $response['code'] = 'Success';
            }
            else{
                $collectionMaster->updateOne(
                    array("quot_number" => $jsonData['quot_number']),
                    array('$set' => array(
                        "mail_status" => "failure"
                )));
                
                $response['code'] = 'failure';
            }
        }
        else {
            $response['code'] = "Network Error, Try Again !!!";
        }
        return $response;   
    }
    
    //purchase order from indent
    public function pofromquotation($getData)
    {
        $response = array();
        
        // collection
        $collectionMaster = $this->conn->quotation_master;
        $collectionVendor = $this->conn->vendor_details;
        
        $jsonData = json_decode($getData, true);
        
        $cursor = $collectionMaster->aggregate(array(
                array('$match' => array(
                    "quot_number" => $jsonData['quot_number']
                )),
                array( '$lookup' => array(
                    'from' => 'project_details',
                    'localField' => 'project_id',
                    'foreignField' => '_id',
                    'as' => 'project_details_data'
                ))
            ));
        if($cursor)
        {
            foreach($cursor as $rowData)
            {
                //vendor  details
                $sendData['company'] = $rowData['final_vendor'][0]['vendor_name'];
                $vendorData = $collectionVendor->findOne(array("company_name" => $rowData['final_vendor'][0]['vendor_name']));
                if($vendorData) {
                    $sendData['comp_address'] = $vendorData['address'];
                    $sendData['contact_person'] = $vendorData['contact_person'];
                    $sendData['to_mail'] = $vendorData['email'];
                    $sendData['party_gst'] = $vendorData['party_gst'];
                }
                
                $sendData['quot_number'] = $rowData['quot_number'];
                $sendData['type'] = $rowData['type'];
                $sendData['category'] = $rowData['category'];
                $sendData['sub_category'] = $rowData['sub_category'];
                $sendData['sis_project_name'] = $rowData['project_details_data'][0]['project_name'];
                $sendData['sis_project_block'] = $rowData['project_details_data'][0]['block_name'];
                
                //get po number and contact person list
                $internalData['project_short'] = $rowData['project_details_data'][0]['project_short'];
                $internalData['project_name'] = $rowData['project_details_data'][0]['project_name'];
                $resData = $this->internalponum(json_encode($internalData));
                $response["invoice_no"] = $resData["po_number"];
                $response["person_list"] = $resData['person_list'];
                
                $response['quotation_list'] = $rowData['final_vendor'][0]['table_items'];
                
            }
            $response["invoice_data"] = $sendData;
            $response["code"] = "Success";
        }
        else {
            $response["code"] = "failed";
        }
        return $response;
    }
    
/***** APPROVER INDENT *****/
    
    //admin edit quotation
    public function admineditquotation($getData)
    {
        $response['indent_list'] = array();
        $response['user_mail'] = array();
        
        //collection
        $collection = $this->conn->signintable;
        $collectionMaster = $this->conn->quotation_master;
        $collectionIndent = $this->conn->indent_master;
        
        $jsonData = json_decode($getData, true);
        
        //user data
        $userData = $collection->findOne(array("emp_id" => (int)$jsonData['emp_id']));
        
        //sis employees
        $product = array();
        $cursorMailData = $collection->find(array("department" => "Purchase"));
        foreach($cursorMailData as $rowUserData) {
            $product['mail_id'] = $rowUserData['mail_id'];
            array_push($response['user_mail'], $product);
        }
        
        //purchase order details
        $cursor = $collectionMaster->aggregate(array(
                array('$match' => array(
                    "quot_number" => $jsonData['quot_number']
                )),
                array( '$lookup' => array(
                    'from' => 'indent_master',
                    'localField' => 'prf_number',
                    'foreignField' => 'prf_number',
                    'as' => 'indent_data'
                )),
                array( '$lookup' => array(
                    'from' => 'indent_table',
                    'localField' => 'prf_number',
                    'foreignField' => 'prf_number',
                    'as' => 'indent_table_data'
                )),
                array( '$lookup' => array(
                    'from' => 'project_details',
                    'localField' => 'project_id',
                    'foreignField' => '_id',
                    'as' => 'project_details_data'
                ))
            ));
        if($cursor)
        {
            foreach($cursor as $rowData)
            {
                $cursorUser = $collectionIndent->aggregate(array(
                    array('$match' => array(
                        "prf_number" => $rowData['indent_data'][0]['prf_number']
                    )),
                    array( '$lookup' => array(
                        'from' => 'signintable',
                        'localField' => 'emp_id',
                        'foreignField' => 'emp_id',
                        'as' => 'user_data'
                    )),
                ));
                if($cursorUser)
                {
                    foreach($cursorUser as $rowUserData)
                    {
                        $sendData['emp_id'] = $rowUserData['user_data'][0]['emp_id'];
                        $sendData['name'] = $rowUserData['user_data'][0]['name'];
                        $sendData['designation'] = $rowUserData['user_data'][0]['designation'];
                        $sendData["cc_mail"] = $rowUserData['user_data'][0]['mail_id'];
                    }
                }
                
                if($rowData['project_details_data'][0]['block_name'] != 'none'){
                    $block = " - ".$rowData['project_details_data'][0]['block_name']." Block";
                }
                
                $sendData['type'] = $rowData['type'];
                $sendData['prf_number'] = $rowData['indent_data'][0]['prf_number'];
                $sendData['prf_date'] = $rowData['indent_data'][0]['prf_date'];
                $sendData['grand_total'] = $rowData['indent_data'][0]['grand_total'];
                $sendData['quot_number'] = $rowData['quot_number'];
                $sendData['quot_date'] = $rowData['quot_date'];
                $sendData['project_name'] = $rowData['project_details_data'][0]['project_name'];
                $sendData['block_name'] = $rowData['project_details_data'][0]['block_name'];
                $sendData['site'] = $rowData['project_details_data'][0]['project_name'].$block;
                $sendData['place'] = $rowData['project_details_data'][0]['place'];
                $sendData['purpose'] = $rowData['indent_data'][0]['purpose'];
                $sendData['approved_status'] = $rowData['approved_status'];
                $sendData["from_mail"] = $userData['mail_id'];
                
                $response["supplier_list"] = $rowData['indent_data'][0]['supplier_list'];
                $response['quotation_list'] = $rowData['quot_list'];
                
                //indent list
                $productCount = count($rowData['indent_table_data']);
                for($i=0; $i<$productCount; $i++)
                {
                    $purchaseData['item']=$rowData['indent_table_data'][$i]['item'];
                    $purchaseData['quantity']=$rowData['indent_table_data'][$i]['quantity'];
                    $purchaseData['unit']=$rowData['indent_table_data'][$i]['unit'];
                    $purchaseData['price']=$rowData['indent_table_data'][$i]['price'];
                    if($rowData['type']=='Door'){
                        $purchaseData['amount'] = ($rowData['indent_table_data'][$i]['cft'] * $rowData['indent_table_data'][$i]['price']);
                    }
                    else{
                        $purchaseData['amount'] = ($rowData['indent_table_data'][$i]['quantity'] * $rowData['indent_table_data'][$i]['price']);
                    }
                    $purchaseData['total']=sprintf("%.2f", $purchaseData['amount']);
                    $purchaseData['make']=$rowData['indent_table_data'][$i]['make'];
                    $purchaseData['details']=$rowData['indent_table_data'][$i]['details'];
                    $purchaseData['width']=$rowData['indent_table_data'][$i]['width'];
                    $purchaseData['height']=$rowData['indent_table_data'][$i]['height'];
                    $purchaseData['upvc_type']=$rowData['indent_table_data'][$i]['upvc_type'];
                    $purchaseData['size']=$rowData['indent_table_data'][$i]['size'];
                    $purchaseData['cft']=$rowData['indent_table_data'][$i]['cft'];
                    $purchaseData['required_date']=$rowData['indent_table_data'][$i]['required_date'];
                    $purchaseData['stock_details'] = $rowData['indent_table_data'][$i]["stock_details"];
                    $purchaseData['available_stock'] = $rowData['indent_table_data'][$i]["stock_details"]["block_available"];
                    
                    array_push($response['indent_list'], $purchaseData);
                }
            }
            $response["invoice_data"] = $sendData;
            $response["code"] = "Success";
        }
        else {
            $response["code"] = "failed";
        }
        
        return $response;        
    }
    
/***** PURCHASE ORDER *****/
    
    //purchase order list
    public function getorderlist($getData) 
    {
        $response = array();
    
        $response['purchase_data'] = array();
        
        $jsonData = json_decode($getData, true);
        $response["category"] = $jsonData['category'];
        if($jsonData['cancel_status'] == 'usage'){ $cancelStatus = '0'; }
        else{ $cancelStatus = '1'; }
        
        // collection
        $collectionMaster = $this->conn->purchase_master;
        
        // site list
        $siteData = $this->internalsitelist($jsonData['emp_id']);
        $response['site_list'] = $siteData["site_list"];
        
        $proShortArray = $siteData["project_short_list"];
        if($jsonData['project_short'] != '') { $proShortArray = array($jsonData['project_short']); }
    
        // Get current year and previous year
        $currentYear = date('Y');
        $previousYear = $currentYear - 1;
    
        // Set the date range for the current and previous years
        $startDate = new MongoDB\BSON\UTCDateTime(strtotime("first day of January $previousYear 00:00:00") * 1000);
        $endDate = new MongoDB\BSON\UTCDateTime(strtotime("last day of December $currentYear 23:59:59") * 1000);
    
        // Match conditions
        $matchConditions = [
            "project_short" => ['$in' => $proShortArray],
            "cancel_status" => $cancelStatus,
            "date_time" => [
                '$gte' => $startDate,  // Greater than or equal to the first day of the previous year
                '$lte' => $endDate     // Less than or equal to the last day of the current year
            ]
        ];
    
        if (!empty($jsonData['category'])) {
            $matchConditions["category"] = $jsonData['category'];
        }
    
        // Query with $dateFromString conversion
        $result = $collectionMaster->aggregate([
            [
                '$addFields' => [
                    'date_time' => [
                        '$dateFromString' => [
                            'dateString' => '$date_time',  // Assuming 'date_time' is a string in "Y-m-d H:i:s"
                            'format' => '%Y-%m-%d %H:%M:%S'  // Format used to parse the date string
                        ]
                    ]
                ]
            ],
            ['$match' => $matchConditions],
            ['$sort' => ['_id' => -1]]
        ]);
    
        if($result) {
            $product = array();
            foreach($result as $rows) {
                $product['category'] = $rows['category'];
                $product['prf_number'] = $rows['prf_number'];
                $product['po_number'] = $rows['po_number'];
                $product['po_date'] = $rows['po_date'];  // This will now be a Date object
                $product['summary'] = $rows['description'];
                $product['total'] = $rows['grand_total'];
                $product['mail_status'] = $rows['mail_status'];
                $product['received_status'] = $rows['received_status'];
                
                array_push($response['purchase_data'], $product);
            }
            $response["code"] = "Success";
        } else {
            $response["code"] = "failed";
        }
    
        return $response;        
    }
    
    
    //get product list for new purchase
    public function getproductlist($getData)
    {
        $response = array();
        $response["product_list"] = $this->internalproductlist($getData);
        return $response;
    }
    
    //get po number for new purchase
    public function getponumber($getData)
    {
        $response = array();
        $resData = $this->internalponum($getData);
        $response["invoice_no"] = $resData["po_number"];
        $response["person_list"] = $resData['person_list'];
        return $response;
    }
    
    //project details for new purchase
    public function getprojectdetails($getData) 
    {
        $response = array();
        
        //user_mail
        $resp = $this->getpomailcontent($getData);
        $response["user_mail"] = $resp["user_mail"];
        $response['from_mail'] = $resp['mail_id'];
            
        //category
        $response["category_list"] = $this->internalcategorylist();
        
        //vendor
        $response["vendor_details"] = $this->internalvendorlist();
        
        //payment
        $respPayment = $this->internalpaymentandvat();
        $response["payment_list"] = $respPayment['payment'];
        $response["vat_list"] = $respPayment['vat'];
        
        //project
        $response["project_list"] = $this->internalsisproject();
        
        //quotations
        $response["quotation_list"] = $this->internalquotationlist();
        
        //order type
        $orderData = $this->internalordertype();
        $response["order_type"] = $orderData["order_type"];
    
        $response["code"] = "Success";
          
        return $response;        
    }
    
    //new purchase order
    public function newpurchase($getData)
    {
        $response = array();
        
        // collections
        $collectionMaster = $this->conn->purchase_master;
        $collectionIndent = $this->conn->indent_master;
        $collectionQuotation = $this->conn->quotation_master;
        $collectionPurtable = $this->conn->purchase_table;
        $collectionInventory = $this->conn->inventory;
        $collectionVendor = $this->conn->vendor_details;
        $collectionProduct = $this->conn->product_list;
        $collectionProVendor = $this->conn->vendor_product_list;
        $collectionProject = $this->conn->project_details;
        
        $jsonData = json_decode($getData, true);
        $emp_id = (int)$jsonData["emp_id"];
        
        //vendor
        $vendorData = $collectionVendor->findOne(array("company_name" => $jsonData["company"]));
        if(!$vendorData){
            $collectionVendor->insertOne(array(
                "emp_id" => $emp_id,
                "company_name" => $jsonData["company"],
                "contact_person" => $jsonData["contact_person"],
                "address" => $jsonData["comp_address"],
                "party_gst" => $jsonData["party_gst"],
                "date_time" => date("Y-m-d H:i:s"),
                "cancel_status" => "0"
            ));
        }
        else{
            $collectionVendor->updateOne(
                array("company_name" => $jsonData["company"]),
                array('$set' => array(
                "contact_person" => $jsonData["contact_person"],
                "address" => $jsonData["comp_address"],
                "party_gst" => $jsonData["party_gst"],
                "cancel_status" => "0"
            )));
        }
        
        //get project details for po number
        $projectData = $collectionProject->findOne(array("project_name" => $jsonData["sis_project_name"], "block_name" => $jsonData["sis_project_block"]));
        
        //po number
        $internalData['project_short'] = $projectData['project_short'];
        $resData = $this->internalponum(json_encode($internalData));
        $poNum = $resData["po_number"];
        
        //description
        $jsonArrayData = json_decode($jsonData["jsonArrayData"], true);
        $listCount = count($jsonArrayData);
        
        !empty($jsonArrayData[0]["item"]) ? $pro1 =$jsonArrayData[0]["item"] : $pro1= "";
        !empty($jsonArrayData[1]["item"]) ? $pro2 =$jsonArrayData[1]["item"] : $pro2= "";
        
        $desc = $this->internalsummary($projectData['project_short'],$jsonData["company"],$pro1,$pro2);
        
        if(!$jsonData["trans_amount"]){ $jsonData["trans_amount"]='0'; }
        if(!$jsonData["trans_tax"]){ $jsonData["trans_tax"]='0'; }
        
        if($jsonData["quot_number"]) {
            $quotData = $collectionQuotation->findOne(array("quot_number" => $jsonData["quot_number"]));
            $jsonData["prf_number"] = $quotData['prf_number'];
        }
        
        $collectionMaster->insertOne(array(
            "emp_id" => $emp_id,
            "prf_number" => $jsonData["prf_number"],
            "quot_number" => $jsonData["quot_number"],
            "po_number" => $poNum,
            "type" => $jsonData["type"], 
            "category" => $jsonData["category"],
            "sub_category" => $jsonData["sub_category"],
            "po_date" => date("d-m-Y"),
            "project_short" => $projectData['project_short'],
            "company" => $jsonData["company"],
            "reference_no" => $jsonData["reference_no"],
            "reference_date" => $jsonData["reference_date"],
            "po_subject" => $jsonData["po_subject"],
            "order_type" => $jsonData["order_type"],
            "order_type_short" => $jsonData["order_type_short"],
            "project_id" => $projectData["_id"],
            "work_ton" => $jsonData["work_ton"],
            "trans_amount" => $jsonData["trans_amount"],
            "trans_tax" => $jsonData["trans_tax"],
			"plain_amount" => $jsonData["plain_amount"],
            "plain_tax" => $jsonData["plain_tax"],
			"sum_cft" => $jsonData["sum_cft"],
            "grand_total" => $jsonData["grand_total"],
            "payment" => $jsonData["payment"],
            "delivery_place" => $jsonData["delivery_place"],
            "vat" => $jsonData["vat"],
            "delivery_date" => $jsonData["delivery_date"],
            "site_contact_person" => $jsonData["site_contact_person"],
            "mobile" => $jsonData["mobile"],
            "description" => $desc,
			"note" => $jsonData["note"],
            "date_time" => date("Y-m-d H:i:s"),
            "cancel_status" => "0",
            "bill_status" => 1,
            "mail_status" => ""
        ));
        
        //quotation used
        $collectionQuotation->updateOne(
            array("quot_number" => $jsonData["quot_number"]),
            array('$set' => array("po_raised" => "1"))
        );
        $collectionIndent->updateOne(
            array("prf_number" => $jsonData['prf_number']),
            array('$set' => array("quot_status_details" => "PO Partially Raised"))
        );
        
        //indent completed
        $quotVal = $collectionQuotation->findOne(array("prf_number" => $jsonData['prf_number'], "po_raised" => array('$ne' => "1")));
        if(!$quotVal) {
            $collectionIndent->updateOne(
                array("prf_number" => $jsonData['prf_number']),
                array('$set' => array("quot_status_details" => "PO Raised"))
            );
        }
        
        for($i=0; $i<$listCount; $i++)
        {
            //product
            $cft = $jsonArrayData[$i]["cft"]/$jsonArrayData[$i]["quantity"];
            
            /*$productData = $collectionProduct->findOne(array(
                "type" => $jsonData["type"], 
                "category" => $jsonData["category"],
                "sub_category" => $jsonData["sub_category"],
                "product_name" => $jsonArrayData[$i]["item"]
            ));
            if(!$productData) {
                $collectionProduct->insertOne(array(
                    "emp_id" => $emp_id,
                    "type" => $jsonData["type"],
                    "category" => $jsonData["category"],
                    "sub_category" => $jsonData["sub_category"],
                    "code" => $jsonArrayData[$i]["code"],
                    "product_name" => $jsonArrayData[$i]["item"],
                    "details" => $jsonArrayData[$i]["details"],
                    "make" => $jsonArrayData[$i]["make"],
                    "width" => $jsonArrayData[$i]["width"],
                    "height" => $jsonArrayData[$i]["height"],
                    "upvc_type" => $jsonArrayData[$i]["upvc_type"],
                    "unit" => $jsonArrayData[$i]["unit"],
                    "price" => $jsonArrayData[$i]["price"],
                    "sgst" => $jsonArrayData[$i]["gst"]/2,
                    "cgst" => $jsonArrayData[$i]["gst"]/2,
                    "size" => $jsonArrayData[$i]["size"],
                    "cft" => $cft,
                    "date_time" => date("Y-m-d H:i:s"),
                    "cancel_status" => "0"
                ));
            }
            else {
                $collectionProduct->updateOne(
                    array("type" => $jsonData["type"], 
                        "category" => $jsonData["category"],
                        "sub_category" => $jsonData["sub_category"],
                        "product_name" => $jsonArrayData[$i]["item"]
                    ),
                    array('$set' => array(
                    "code" => $jsonArrayData[$i]["code"],
                    "details" => $jsonArrayData[$i]["details"],
                    "make" => $jsonArrayData[$i]["make"],
                    "width" => $jsonArrayData[$i]["width"],
                    "height" => $jsonArrayData[$i]["height"],
                    "upvc_type" => $jsonArrayData[$i]["upvc_type"],
                    "unit" => $jsonArrayData[$i]["unit"],
                    "price" => $jsonArrayData[$i]["price"],
                    "sgst" => $jsonArrayData[$i]["gst"]/2,
                    "cgst" => $jsonArrayData[$i]["gst"]/2,
                    "size" => $jsonArrayData[$i]["size"],
                    "cft" => $cft,
                    "cancel_status" => "0"
                )));
            }*/
            
            //vendor product table
            $productData = $collectionProVendor->findOne(array(
                "type" => $jsonData["type"], 
                "category" => $jsonData["category"],
                "sub_category" => $jsonData["sub_category"],
                "vendor" => $jsonData["company"],
                "product_name" => $jsonArrayData[$i]["item"],
				"product_desc" => $jsonArrayData[$i]["desc"]
            ));
            if(!$productData) {
                $collectionProVendor->insertOne(array(
                    "emp_id" => $emp_id,
                    "type" => $jsonData["type"],
                    "category" => $jsonData["category"],
                    "sub_category" => $jsonData["sub_category"],
                    "vendor" => $jsonData["company"],
                    "code" => $jsonArrayData[$i]["code"],
                    "product_name" => $jsonArrayData[$i]["item"],
					"product_desc" => $jsonArrayData[$i]["desc"],
                    "details" => $jsonArrayData[$i]["details"],
                    "make" => $jsonArrayData[$i]["make"],
                    "width" => $jsonArrayData[$i]["width"],
                    "height" => $jsonArrayData[$i]["height"],
                    "upvc_type" => $jsonArrayData[$i]["upvc_type"],
                    "unit" => $jsonArrayData[$i]["unit"],
                    "price" => $jsonArrayData[$i]["price"],
                    "sgst" => $jsonArrayData[$i]["gst"]/2,
                    "cgst" => $jsonArrayData[$i]["gst"]/2,
                    "size" => $jsonArrayData[$i]["size"],
                    "cft" => $cft
                ));
            }
            else {
                $collectionProVendor->updateOne(
                    array("type" => $jsonData["type"], 
                        "category" => $jsonData["category"],
                        "sub_category" => $jsonData["sub_category"],
                        "vendor" => $jsonData["company"],
                        "product_name" => $jsonArrayData[$i]["item"],
						"product_desc" => $jsonArrayData[$i]["desc"]
                    ),
                    array('$set' => array(
                    "code" => $jsonArrayData[$i]["code"],
                    "details" => $jsonArrayData[$i]["details"],
                    "make" => $jsonArrayData[$i]["make"],
                    "width" => $jsonArrayData[$i]["width"],
                    "height" => $jsonArrayData[$i]["height"],
                    "upvc_type" => $jsonArrayData[$i]["upvc_type"],
                    "unit" => $jsonArrayData[$i]["unit"],
                    "price" => $jsonArrayData[$i]["price"],
                    "sgst" => $jsonArrayData[$i]["gst"]/2,
                    "cgst" => $jsonArrayData[$i]["gst"]/2,
                    "size" => $jsonArrayData[$i]["size"],
                    "cft" => $cft
                )));
            }
            
            //purchase table
            $insertPurtable = $collectionPurtable->insertOne(array(
                "po_number" => $poNum,
                "code" => $jsonArrayData[$i]["code"],
                "item" => $jsonArrayData[$i]["item"],
				"desc" => $jsonArrayData[$i]["desc"],
                "quantity" => $jsonArrayData[$i]["quantity"],
                "unit" => $jsonArrayData[$i]["unit"],
                "price" => $jsonArrayData[$i]["price"],
                "sgst" => $jsonArrayData[$i]["gst"]/2,
                "cgst" => $jsonArrayData[$i]["gst"]/2,
                "details" => $jsonArrayData[$i]["details"],
                "make" => $jsonArrayData[$i]["make"],
                "width" => $jsonArrayData[$i]["width"],
                "height" => $jsonArrayData[$i]["height"],
                "upvc_type" => $jsonArrayData[$i]["upvc_type"],
                "size" => $jsonArrayData[$i]["size"],
                "cft" => $jsonArrayData[$i]["cft"]
            ));
            $lastPurId = $insertPurtable->getInsertedId();
            
            if($jsonData["category"]!='Marketing')
            {
                //inventory table
                $collectionInventory->insertOne(array(
                    "po_number" => $poNum,
                    "type" => $jsonData["type"],
                    "category" => $jsonData["category"],
                    "sub_category" => $jsonData["sub_category"],
                    "code" => $jsonArrayData[$i]["code"],
                    "item" => $jsonArrayData[$i]["item"],
					"desc" => $jsonArrayData[$i]["desc"],
                    "quantity" => (float)$jsonArrayData[$i]["quantity"],
                    "unit" => $jsonArrayData[$i]["unit"],
                    "price" => $jsonArrayData[$i]["price"],
                    "sgst" => $jsonArrayData[$i]["gst"]/2,
                    "cgst" => $jsonArrayData[$i]["gst"]/2,
                    "details" => $jsonArrayData[$i]["details"],
                    "make" => $jsonArrayData[$i]["make"],
                    "width" => $jsonArrayData[$i]["width"],
                    "height" => $jsonArrayData[$i]["height"],
                    "upvc_type" => $jsonArrayData[$i]["upvc_type"],
                    "size" => $jsonArrayData[$i]["size"],
                    "cft" => $jsonArrayData[$i]["cft"],
                    "project_id" => $projectData["_id"],
                    "project_short" => $projectData['project_short'],
                    "purchase_id" => $lastPurId,
                    "date_time" => date("Y-m-d H:i:s"),
                    "cancel_status" => "0",
                    "completed_status" => "0"
                ));
            }
        }
        if($jsonData["to_mail"]!=''){
            $mailData["po_number"] = $poNum;
            $mailData["order_type_short"] = $jsonData["order_type_short"];
            $mailData["emp_id"] = $jsonData["emp_id"];
            $mailData["to_mail"] = $jsonData["to_mail"];
        
            $mailData["cc_mail"] = $jsonData["cc_mail"];
            $mailData["bcc_mail"] = $jsonData["bcc_mail"];
            $mailData["subject"] = $jsonData["subject"];
            $mailData["content"] = $jsonData["content"];
            
            $reqResp = $this->sendpomail(json_encode($mailData));
            $response["code"] = $reqResp["code"];
        }
        else{
            $response["code"] = "Success";
        }
        return $response;   
    }
    
    //view order details
    public function viewpurchaseorder($getData) 
    {
        $jsonData = json_decode($getData);
        $poNum = $jsonData->po_number;
        
        $response["invoice_data"] = array();
        $response["purchase_list"] = array();
        
        $collectionMaster = $this->conn->purchase_master;
        
        //purchase order details
        $cursor = $collectionMaster->aggregate(array(
                array('$match' => array(
                    "po_number" => $poNum
                )),
                array( '$lookup' => array(
                    'from' => 'purchase_table',
                    'localField' => 'po_number',
                    'foreignField' => 'po_number',
                    'as' => 'purchase_table_data'
                )),
                array( '$lookup' => array(
                    'from' => 'signintable',
                    'localField' => 'emp_id',
                    'foreignField' => 'emp_id',
                    'as' => 'user_data'
                )),
                array( '$lookup' => array(
                    'from' => 'project_details',
                    'localField' => 'project_id',
                    'foreignField' => '_id',
                    'as' => 'project_details_data'
                )),
                array( '$lookup' => array(
                   'from' => 'vendor_details',
                   'localField' => 'company',
                   'foreignField' => 'company_name',
                   'as' => 'vendor_details_data'
                ))
            ));
        if($cursor)
        {
            foreach($cursor as $rowData)
            {
                $sendData['name'] = $rowData['user_data'][0]['name'];
                $sendData['sign'] = $rowData['user_data'][0]['sign'];
                $sendData['designation'] = $rowData['user_data'][0]['designation'];
                $sendData['po_number'] = $rowData['po_number'];
                $sendData['type'] = $rowData['type'];
                $sendData['folder'] = $rowData['folder'];
                $sendData['po_date'] = $rowData['po_date'];
                $sendData['company'] = $rowData['vendor_details_data'][0]['company_name'];
                $sendData['comp_address'] = $rowData['vendor_details_data'][0]['address'];
				$sendData['comp_mobile'] = $rowData['vendor_details_data'][0]['mobile'];
                $sendData['contact_person'] = $rowData['vendor_details_data'][0]['contact_person'];
                $sendData['party_gst'] = $rowData['vendor_details_data'][0]['party_gst'];
                $sendData['taxtype'] = $rowData['vendor_details_data'][0]['taxtype'];
                $sendData['reference_no'] = $rowData['reference_no'];
                $sendData['reference_date'] = $rowData['reference_date'];
                $sendData['po_subject'] = $rowData['po_subject'];
                $sendData['order_type'] = $rowData['order_type'];
                $sendData['order_type_short'] = $rowData['order_type_short'];
                $sendData['project_id'] = $rowData['project_id'];
                $sendData['work_ton'] = $rowData['work_ton'];
                $sendData['trans_amount'] = $rowData['trans_amount'];
                $sendData['trans_tax'] = $rowData['trans_tax'];
                $sendData['trans_gst_amount'] = $rowData['trans_amount']*($rowData['trans_tax']/100);
		$sendData['plain_amount'] = $rowData['plain_amount'];
                $sendData['plain_tax'] = $rowData['plain_tax'];
                $sendData['plain_gst_amount'] = $rowData['plain_amount']*($rowData['plain_tax']/100);
                $sendData['grand_total'] = $rowData['grand_total'];
 		$sendData['sum_cft'] = $rowData['sum_cft'];
                $sendData['payment'] = $rowData['payment'];
                $sendData['delivery_place'] = $rowData['delivery_place'];
                $sendData['vat'] = $rowData['vat'];
                $sendData['delivery_date'] = $rowData['delivery_date'];
                $sendData['site_contact_person'] = $rowData['site_contact_person'];
                $sendData['mobile'] = $rowData['mobile'];
                $sendData['sis_project_name'] = $rowData['project_details_data'][0]['project_name'];
                $sendData['sis_project_block'] = $rowData['project_details_data'][0]['block_name'];
                $sendData['our_gst'] = $rowData['project_details_data'][0]['gst_in'];
                $sendData['supply_place'] = $rowData['project_details_data'][0]['place'];
                $sendData['site_address'] = $rowData['project_details_data'][0]['address'];
				$sendData['note'] =  $rowData['note'];
               
                $sendData['sub_total']=0;
                $productCount = count($rowData['purchase_table_data']);
                for($i=0; $i<$productCount; $i++)
                {
                    $purchaseData['code']=$rowData['purchase_table_data'][$i]['code'];
                    $purchaseData['item']=$rowData['purchase_table_data'][$i]['item'];
		    $purchaseData['desc']=$rowData['purchase_table_data'][$i]['desc'];
                    $purchaseData['quantity']=$rowData['purchase_table_data'][$i]['quantity'];
                    $purchaseData['unit']=$rowData['purchase_table_data'][$i]['unit'];
                    $purchaseData['price']=$rowData['purchase_table_data'][$i]['price'];
                    if($rowData['type']=='Door'){
                        $purchaseData['amount']=$rowData['purchase_table_data'][$i]['cft'] * $rowData['purchase_table_data'][$i]['price'];
                    }
                    else{
                        $purchaseData['amount']=$rowData['purchase_table_data'][$i]['quantity'] * $rowData['purchase_table_data'][$i]['price'];
                    }
                    $purchaseData['gst']=$rowData['purchase_table_data'][$i]['sgst'] + $rowData['purchase_table_data'][$i]['cgst'];
                    $purchaseData['sgst_amount']=($rowData['purchase_table_data'][$i]['sgst']/100) * $purchaseData['amount'];
                    $purchaseData['cgst_amount']=($rowData['purchase_table_data'][$i]['cgst']/100) * $purchaseData['amount'];
                    $purchaseData['total']=$purchaseData['amount'] + $purchaseData['sgst_amount'] + $purchaseData['cgst_amount'];
                    $purchaseData['make']=$rowData['purchase_table_data'][$i]['make'];
                    $purchaseData['details']=$rowData['purchase_table_data'][$i]['details'];
                    $purchaseData['width']=$rowData['purchase_table_data'][$i]['width'];
                    $purchaseData['height']=$rowData['purchase_table_data'][$i]['height'];
                    $purchaseData['upvc_type']=$rowData['purchase_table_data'][$i]['upvc_type'];
                    $purchaseData['size']=$rowData['purchase_table_data'][$i]['size'];
                    $purchaseData['cft']=$rowData['purchase_table_data'][$i]['cft'];
                    
                    array_push($response['purchase_list'], $purchaseData);
                    
                    $sendData['sub_total'] += $purchaseData['total'];
                }
            }
            
            $response["invoice_data"] = $sendData;
            $response["code"] = "Success";
        }
        else {
            $response["code"] = "failed";
        }   
        return $response;
    }
    
    //edit order details
    public function editpurchaseorder($getData)
    {
        $response["product_list"] = array();
        $response['purchase_list'] = array();
        
        //collection
        $collectionMaster = $this->conn->purchase_master;
        $jsonData = json_decode($getData, true);
        
        //user_mail
        $resp = $this->getpomailcontent($getData);
        $response["user_mail"] = $resp["user_mail"];
        $sendData['from_mail'] = $resp['mail_id'];
        
        if($jsonData['page_type']=='edit') {
            //category
            $response["category_list"] = $this->internalcategorylist();
        }
        
        //vendor
        $response["vendor_details"] = $this->internalvendorlist();
        
        //project
        $response["project_list"] = $this->internalsisproject();
        
        //payment
        $respPayment = $this->internalpaymentandvat();
        $response["payment_list"] = $respPayment['payment'];
        $response["vat_list"] = $respVat['vat'];
        
        //order type
        $orderData = $this->internalordertype();
        $response["order_type"] = $orderData["order_type"];
        
        //purchase order details
        $cursor = $collectionMaster->aggregate(array(
                array('$match' => array(
                    "po_number" => $jsonData['po_number']
                )),
                array( '$lookup' => array(
                    'from' => 'purchase_table',
                    'localField' => 'po_number',
                    'foreignField' => 'po_number',
                    'as' => 'purchase_table_data'
                )),
                array( '$lookup' => array(
                    'from' => 'project_details',
                    'localField' => 'project_id',
                    'foreignField' => '_id',
                    'as' => 'project_details_data'
                )),
                array( '$lookup' => array(
                   'from' => 'vendor_details',
                   'localField' => 'company',
                   'foreignField' => 'company_name',
                   'as' => 'vendor_details_data'
                ))
            ));
        if($cursor)
        {
            foreach($cursor as $rowData)
            {
                $sendData['quot_number'] = $rowData['quot_number'];
                $sendData['po_number'] = $rowData['po_number'];
                $sendData['type'] = $rowData['type'];
                $sendData['category'] = $rowData['category'];
                $sendData['sub_category'] = $rowData['sub_category'];
                $sendData['po_date'] = $rowData['po_date'];
                $sendData['company'] = $rowData['vendor_details_data'][0]['company_name'];
                $sendData['comp_address'] = $rowData['vendor_details_data'][0]['address'];
                $sendData['contact_person'] = $rowData['vendor_details_data'][0]['contact_person'];
                $sendData['to_mail'] = $rowData['vendor_details_data'][0]['email'];
                $sendData['party_gst'] = $rowData['vendor_details_data'][0]['party_gst'];
                $sendData['reference_no'] = $rowData['reference_no'];
                $sendData['reference_date'] = $rowData['reference_date'];
                $sendData['po_subject'] = $rowData['po_subject'];
                $sendData['order_type'] = $rowData['order_type'];
                $sendData['order_type_short'] = $rowData['order_type_short'];
                $sendData['project_id'] = $rowData['project_id'];
                $sendData['work_ton'] = $rowData['work_ton'];
                $sendData['plain_amount'] = $rowData['plain_amount'];
                $sendData['plain_tax'] = $rowData['plain_tax'];
				$sendData['sum_cft'] = $rowData['sum_cft'];
				$sendData['trans_amount'] = $rowData['trans_amount'];
                $sendData['trans_tax'] = $rowData['trans_tax'];
                $sendData['grand_total'] = $rowData['grand_total'];
                $sendData['payment'] = $rowData['payment'];
                $sendData['delivery_place'] = $rowData['delivery_place'];
                $sendData['vat'] = $rowData['vat'];
                $sendData['delivery_date'] = $rowData['delivery_date'];
                $sendData['site_contact_person'] = $rowData['site_contact_person'];
                $sendData['mobile'] = $rowData['mobile'];
                $sendData['sis_project_name'] = $rowData['project_details_data'][0]['project_name'];
                $sendData['sis_project_block'] = $rowData['project_details_data'][0]['block_name'];
                $sendData['our_gst'] = $rowData['project_details_data'][0]['gst_in'];
                $sendData['supply_place'] = $rowData['project_details_data'][0]['place'];
                $sendData['site_address'] = $rowData['project_details_data'][0]['address'];
                $sendData['note'] = $rowData['note'];
				
                //get po number and contact person list
                $internalData['project_short'] = $rowData['project_details_data'][0]['project_short'];
                $internalData['project_name'] = $rowData['project_details_data'][0]['project_name'];
                $resData = $this->internalponum(json_encode($internalData));
                $response["invoice_no"] = $resData["po_number"];
                $response["person_list"] = $resData['person_list'];
                
                $subTotal = 0;
                $productCount = count($rowData['purchase_table_data']);
                for($i=0; $i<$productCount; $i++)
                {
                    $purchaseData['_id']=$rowData['purchase_table_data'][$i]['_id'].$oid;
                    $purchaseData['code']=$rowData['purchase_table_data'][$i]['code'];
                    $purchaseData['item']=$rowData['purchase_table_data'][$i]['item'];
					$purchaseData['desc']=$rowData['purchase_table_data'][$i]['desc'];
                    $purchaseData['quantity']=$rowData['purchase_table_data'][$i]['quantity'];
                    $purchaseData['unit']=$rowData['purchase_table_data'][$i]['unit'];
                    $purchaseData['price']=$rowData['purchase_table_data'][$i]['price'];
                    if($rowData['type']=='Door'){
                        $purchaseData['amount']=sprintf("%.2f", $rowData['purchase_table_data'][$i]['cft'] * $rowData['purchase_table_data'][$i]['price']);
                    }
                    else{
                        $purchaseData['amount']=sprintf("%.2f", $rowData['purchase_table_data'][$i]['quantity'] * $rowData['purchase_table_data'][$i]['price']);
                    }
                    $gstPercent=$rowData['purchase_table_data'][$i]['sgst'] + $rowData['purchase_table_data'][$i]['cgst'];
                    $purchaseData['gst']=$gstPercent;
                    $purchaseData['gst_amount']=sprintf("%.2f", ($gstPercent/100) * $purchaseData['amount']);
                    $purchaseData['total']=sprintf("%.2f", $purchaseData['amount'] + $purchaseData['gst_amount']);
                    $purchaseData['make']=$rowData['purchase_table_data'][$i]['make'];
                    $purchaseData['details']=$rowData['purchase_table_data'][$i]['details'];
                    $purchaseData['width']=$rowData['purchase_table_data'][$i]['width'];
                    $purchaseData['height']=$rowData['purchase_table_data'][$i]['height'];
                    $purchaseData['upvc_type']=$rowData['purchase_table_data'][$i]['upvc_type'];
                    $purchaseData['size']=$rowData['purchase_table_data'][$i]['size'];
                    $purchaseData['cft']=$rowData['purchase_table_data'][$i]['cft'];
                    
                    array_push($response['purchase_list'], $purchaseData);
                    
                    $subTotal += $purchaseData['total'];
                }
                $sendData['sub_total'] = sprintf("%.2f", $subTotal);
                
                //product
                $proData['type'] = $rowData['type'];
                $proData['category'] = $rowData['category'];
                $proData['sub_category'] = $rowData['sub_category'];
                $response["product_list"] = $this->internalproductlist(json_encode($proData));
            }
            $response["invoice_data"] = $sendData;
            $response["code"] = "Success";
        }
        else {
            $response["code"] = "failed";
        }
              
        return $response;        
    }
    
    //update purchase order
    public function updatepurchaseorder($getData)
    {
        $response = array();
        
        // collection
        $collectionMaster = $this->conn->purchase_master;
        $collectionPurtable = $this->conn->purchase_table;
        $collectionInventory = $this->conn->inventory;
        $collectionVendor = $this->conn->vendor_details;
        $collectionProduct = $this->conn->product_list;
        $collectionProVendor = $this->conn->vendor_product_list;
        $collectionProject = $this->conn->project_details;
        
        $jsonData = json_decode($getData, true);
        $emp_id = (int)$jsonData["emp_id"];
        
        //vendor
        $vendorData = $collectionVendor->findOne(array("company_name" => $jsonData["company"]));
        if(!$vendorData){
            $collectionVendor->insertOne(array(
                "emp_id" => $emp_id,
                "company_name" => $jsonData["company"],
                "contact_person" => $jsonData["contact_person"],
                "address" => $jsonData["comp_address"],
                "party_gst" => $jsonData["party_gst"],
                "date_time" => date("Y-m-d H:i:s"),
                "cancel_status" => "0"
            ));
        }
        else{
            $collectionVendor->updateOne(
                array("company_name" => $jsonData["company"]),
                array('$set' => array(
                "contact_person" => $jsonData["contact_person"],
                "address" => $jsonData["comp_address"],
                "party_gst" => $jsonData["party_gst"],
                "cancel_status" => "0"
            )));
        }
        
        //get project details
        $projectData = $collectionProject->findOne(array("project_name" => $jsonData["sis_project_name"], "block_name" => $jsonData["sis_project_block"]));
        
        //description
        $jsonArrayData = json_decode($jsonData["jsonArrayData"], true);
        $listCount = count($jsonArrayData);
        
        !empty($jsonArrayData[0]["item"]) ? $pro1 =$jsonArrayData[0]["item"] : $pro1= "";
        !empty($jsonArrayData[1]["item"]) ? $pro2 =$jsonArrayData[1]["item"] : $pro2= "";
        
        $desc = $this->internalsummary($projectData["project_short"],$jsonData["company"],$pro1,$pro2);
       
        $collectionMaster->updateOne(
            array("po_number" => $jsonData["po_number"]),
            array('$set' => array(
                "category" => $jsonData["category"],
                "sub_category" => $jsonData["sub_category"],
                "company" => $jsonData["company"],
                "reference_no" => $jsonData["reference_no"],
                "reference_date" => $jsonData["reference_date"],
                "po_subject" => $jsonData["po_subject"],
                "order_type" => $jsonData["order_type"],
                "order_type_short" => $jsonData["order_type_short"],
                "project_id" => $projectData["_id"],
                "work_ton" => $jsonData["work_ton"],
                "trans_amount" => $jsonData["trans_amount"],
                "trans_tax" => $jsonData["trans_tax"],
				"plain_amount" => $jsonData["plain_amount"],
                "plain_tax" => $jsonData["plain_tax"],
				"sum_cft" => $jsonData["sum_cft"],                
                "grand_total" => $jsonData["grand_total"],
                "payment" => $jsonData["payment"],
                "delivery_place" => $jsonData["delivery_place"],
                "vat" => $jsonData["vat"],
                "delivery_date" => $jsonData["delivery_date"],
                "site_contact_person" => $jsonData["site_contact_person"],
				"note" => $jsonData["note"],
                "description" => $desc,
                "mobile" => $jsonData["mobile"]
        )));
       
        //delete purchase & inventory table
        $purchaseData = $collectionPurtable->find(array("po_number" => $jsonData["po_number"]));
        if($purchaseData) {
            foreach($purchaseData as $row) {
                $arrayParent[] = $row['_id'].$oid;
            }
        }
        for($k=0; $k<$listCount; $k++) {
            $arrayChild[] = $jsonArrayData[$k]["_id"];
        }
        $arrayDelIds = array_merge(array_diff($arrayParent, $arrayChild));
        $idCount = count($arrayDelIds);
        for($i=0; $i<$idCount; $i++) {
            $collectionPurtable->deleteOne(array("_id" => new MongoDB\BSON\ObjectID($arrayDelIds[$i])));
            $collectionInventory->deleteMany(array("purchase_id" => new MongoDB\BSON\ObjectID($arrayDelIds[$i])));
        }
        
        for($i=0; $i<$listCount; $i++)
        {
            //product
            $cft = $jsonArrayData[$i]["cft"]/$jsonArrayData[$i]["quantity"];
            
            /*$productData = $collectionProduct->findOne(array(
                "type" => $jsonData["type"], 
                "category" => $jsonData["category"],
                "sub_category" => $jsonData["sub_category"],
                "product_name" => $jsonArrayData[$i]["item"]
            ));
            if(!$productData){
                $collectionProduct->insertOne(array(
                    "emp_id" => $emp_id,
                    "type" => $jsonData["type"],
                    "category" => $jsonData["category"],
                    "sub_category" => $jsonData["sub_category"],
                    "code" => $jsonArrayData[$i]["code"],
                    "product_name" => $jsonArrayData[$i]["item"],
                    "details" => $jsonArrayData[$i]["details"],
                    "make" => $jsonArrayData[$i]["make"],
                    "width" => $jsonArrayData[$i]["width"],
                    "height" => $jsonArrayData[$i]["height"],
                    "upvc_type" => $jsonArrayData[$i]["upvc_type"],
                    "unit" => $jsonArrayData[$i]["unit"],
                    "price" => $jsonArrayData[$i]["price"],
                    "sgst" => $jsonArrayData[$i]["gst"]/2,
                    "cgst" => $jsonArrayData[$i]["gst"]/2,
                    "size" => $jsonArrayData[$i]["size"],
                    "cft" => $cft,
                    "date_time" => date("Y-m-d H:i:s"),
                    "cancel_status" => "0"
                ));
            }
            else{
                $collectionProduct->updateOne(
                    array("type" => $jsonData["type"], 
                        "category" => $jsonData["category"],
                        "sub_category" => $jsonData["sub_category"],
                        "product_name" => $jsonArrayData[$i]["item"]
                    ),
                    array('$set' => array(
                    "code" => $jsonArrayData[$i]["code"],
                    "details" => $jsonArrayData[$i]["details"],
                    "make" => $jsonArrayData[$i]["make"],
                    "width" => $jsonArrayData[$i]["width"],
                    "height" => $jsonArrayData[$i]["height"],
                    "upvc_type" => $jsonArrayData[$i]["upvc_type"],
                    "unit" => $jsonArrayData[$i]["unit"],
                    "price" => $jsonArrayData[$i]["price"],
                    "sgst" => $jsonArrayData[$i]["gst"]/2,
                    "cgst" => $jsonArrayData[$i]["gst"]/2,
                    "size" => $jsonArrayData[$i]["size"],
                    "cft" => $cft,
                    "cancel_status" => "0"
                )));
            }*/
            
            //vendor product list
            $productData = $collectionProVendor->findOne(array(
                "type" => $jsonData["type"], 
                "category" => $jsonData["category"],
                "sub_category" => $jsonData["sub_category"],
                "vendor" => $jsonData["company"],
                "product_name" => $jsonArrayData[$i]["item"],
				 "product_desc" => $jsonArrayData[$i]["desc"]
            ));
            if(!$productData){
                $collectionProVendor->insertOne(array(
                    "emp_id" => $emp_id,
                    "type" => $jsonData["type"],
                    "category" => $jsonData["category"],
                    "sub_category" => $jsonData["sub_category"],
                    "vendor" => $jsonData["company"],
                    "code" => $jsonArrayData[$i]["code"],
                    "product_name" => $jsonArrayData[$i]["item"],
					"product_desc" => $jsonArrayData[$i]["desc"],
                    "details" => $jsonArrayData[$i]["details"],
                    "make" => $jsonArrayData[$i]["make"],
                    "width" => $jsonArrayData[$i]["width"],
                    "height" => $jsonArrayData[$i]["height"],
                    "upvc_type" => $jsonArrayData[$i]["upvc_type"],
                    "unit" => $jsonArrayData[$i]["unit"],
                    "price" => $jsonArrayData[$i]["price"],
                    "sgst" => $jsonArrayData[$i]["gst"]/2,
                    "cgst" => $jsonArrayData[$i]["gst"]/2,
                    "size" => $jsonArrayData[$i]["size"],
                    "cft" => $cft,
                    "date_time" => date("Y-m-d H:i:s"),
                    "cancel_status" => "0"
                ));
            }
            else{
                $collectionProVendor->updateOne(
                    array("type" => $jsonData["type"], 
                        "category" => $jsonData["category"],
                        "sub_category" => $jsonData["sub_category"],
                        "vendor" => $jsonData["company"],
                        "product_name" => $jsonArrayData[$i]["item"],
						"product_desc" => $jsonArrayData[$i]["desc"]
                    ),
                    array('$set' => array(
                    "code" => $jsonArrayData[$i]["code"],
                    "details" => $jsonArrayData[$i]["details"],
                    "make" => $jsonArrayData[$i]["make"],
                    "width" => $jsonArrayData[$i]["width"],
                    "height" => $jsonArrayData[$i]["height"],
                    "upvc_type" => $jsonArrayData[$i]["upvc_type"],
                    "unit" => $jsonArrayData[$i]["unit"],
                    "price" => $jsonArrayData[$i]["price"],
                    "sgst" => $jsonArrayData[$i]["gst"]/2,
                    "cgst" => $jsonArrayData[$i]["gst"]/2,
                    "size" => $jsonArrayData[$i]["size"],
                    "cft" => $cft,
                    "cancel_status" => "0"
                )));
            }
            
            //purchase & inventory table
            $purchaseData = $collectionPurtable->findOne(array("_id" => new MongoDB\BSON\ObjectID($jsonArrayData[$i]["_id"])));
            if($purchaseData)
            {
                //update purchase table
                $collectionPurtable->updateOne(
                    array("_id" => new MongoDB\BSON\ObjectID($jsonArrayData[$i]["_id"])),
                    array('$set' => array(
                        "code" => $jsonArrayData[$i]["code"],
                        "item" => $jsonArrayData[$i]["item"],
						"desc" => $jsonArrayData[$i]["desc"],
                        "quantity" => (float)$jsonArrayData[$i]["quantity"],
                        "unit" => $jsonArrayData[$i]["unit"],
                        "price" => $jsonArrayData[$i]["price"],
                        "sgst" => $jsonArrayData[$i]["gst"]/2,
                        "cgst" => $jsonArrayData[$i]["gst"]/2,
                        "details" => $jsonArrayData[$i]["details"],
                        "make" => $jsonArrayData[$i]["make"],
                        "width" => $jsonArrayData[$i]["width"],
                        "height" => $jsonArrayData[$i]["height"],
                        "upvc_type" => $jsonArrayData[$i]["upvc_type"],
                        "size" => $jsonArrayData[$i]["size"],
                        "cft" => $jsonArrayData[$i]["cft"]
                 )));
                   
                if($jsonData["category"]!='Marketing')
                {
                    //update inventory table
                    $collectionInventory->updateOne(
                        array("purchase_id" => new MongoDB\BSON\ObjectID($jsonArrayData[$i]["_id"])),
                        array('$set' => array(
                            "code" => $jsonArrayData[$i]["code"],
                            "item" => $jsonArrayData[$i]["item"],
							"desc" => $jsonArrayData[$i]["desc"],
                            "quantity" => (float)$jsonArrayData[$i]["quantity"],
                            "unit" => $jsonArrayData[$i]["unit"],
                            "price" => $jsonArrayData[$i]["price"],
                            "sgst" => $jsonArrayData[$i]["gst"]/2,
                            "cgst" => $jsonArrayData[$i]["gst"]/2,
                            "details" => $jsonArrayData[$i]["details"],
                            "make" => $jsonArrayData[$i]["make"],
                            "width" => $jsonArrayData[$i]["width"],
                            "height" => $jsonArrayData[$i]["height"],
                            "upvc_type" => $jsonArrayData[$i]["upvc_type"],
                            "size" => $jsonArrayData[$i]["size"],
                            "cft" => $jsonArrayData[$i]["cft"],
                            "project_id" => $projectData["_id"]
                    )));
                }
            }
            else
            {
                //insert purchase table
                $insertPurtable = $collectionPurtable->insertOne(array(
                    "po_number" => $jsonData["po_number"],
                    "code" => $jsonArrayData[$i]["code"],
                    "item" => $jsonArrayData[$i]["item"],
					"desc" => $jsonArrayData[$i]["desc"],
                    "quantity" => (float)$jsonArrayData[$i]["quantity"],
                    "unit" => $jsonArrayData[$i]["unit"],
                    "price" => $jsonArrayData[$i]["price"],
                    "sgst" => $jsonArrayData[$i]["gst"]/2,
                    "cgst" => $jsonArrayData[$i]["gst"]/2,
                    "details" => $jsonArrayData[$i]["details"],
                    "make" => $jsonArrayData[$i]["make"],
                    "width" => $jsonArrayData[$i]["width"],
                    "height" => $jsonArrayData[$i]["height"],
                    "upvc_type" => $jsonArrayData[$i]["upvc_type"],
                    "size" => $jsonArrayData[$i]["size"],
                    "cft" => $jsonArrayData[$i]["cft"]
                ));
                $lastPurId = $insertPurtable->getInsertedId();
                       
                if($jsonData["category"]!='Marketing')
                {
                    //insert inventory table
                    $collectionInventory->insertOne(array(
                        "po_number" => $jsonData["po_number"],
                        "type" => $jsonData["type"],
                        "category" => $jsonData["category"],
                        "sub_category" => $jsonData["sub_category"],
                        "code" => $jsonArrayData[$i]["code"],
                        "item" => $jsonArrayData[$i]["item"],
						"desc" => $jsonArrayData[$i]["desc"],
                        "quantity" => (float)$jsonArrayData[$i]["quantity"],
                        "unit" => $jsonArrayData[$i]["unit"],
                        "price" => $jsonArrayData[$i]["price"],
                        "sgst" => $jsonArrayData[$i]["gst"]/2,
                        "cgst" => $jsonArrayData[$i]["gst"]/2,
                        "details" => $jsonArrayData[$i]["details"],
                        "make" => $jsonArrayData[$i]["make"],
                        "width" => $jsonArrayData[$i]["width"],
                        "height" => $jsonArrayData[$i]["height"],
                        "upvc_type" => $jsonArrayData[$i]["upvc_type"],
                        "size" => $jsonArrayData[$i]["size"],
                        "cft" => $jsonArrayData[$i]["cft"],
                        "project_id" => $projectData["_id"],
                        "project_short" => $projectData["project_short"],
                        "purchase_id" => $lastPurId,
                        "date_time" => date("Y-m-d H:i:s"),
                        "cancel_status" => "0",
                        "completed_status" => "0"
                    ));
                }
            }
        }
        
        if($jsonData["to_mail"]!=''){
            $mailData["po_number"] = $jsonData["po_number"];
            $mailData["order_type_short"] = $projectData["project_short"];
            $mailData["emp_id"] = $jsonData["emp_id"];
            $mailData["to_mail"] = $jsonData["to_mail"];
            $mailData["cc_mail"] = $jsonData["cc_mail"];
            $mailData["bcc_mail"] = $jsonData["bcc_mail"];
            $mailData["subject"] = $jsonData["subject"];
            $mailData["content"] = $jsonData["content"];
            
            $reqResp = $this->sendpomail(json_encode($mailData));
            $response["code"] = $reqResp["code"];
        }
        else{
            $response["code"] = "Success";
        }
        return $response;   
    }
    
    //get po mail content
    public function getpomailcontent($getData) 
    {
        $jsonData = json_decode($getData);
        $poNum = $jsonData->po_number;
        
        $response["mail_data"] = array();
        
        $collectionMaster = $this->conn->purchase_master;
        $collectionUser = $this->conn->signintable;
        $collectionVendor = $this->conn->vendor_details;
        
        //from mail id
        $cursorData = $collectionUser->findOne(array("emp_id" => (int)$jsonData->emp_id));
        $response['mail_id'] = $cursorData['mail_id'];
            
        //sis employees
        //sis employees
        $userData = $collectionUser->find(array("status" => "Active"));
		
        $product = array();
        $response['user_mail'] = array();
    foreach($userData as $rowData)
        {
            $product['mail_id'] = $rowData['mail_id'];
			if($product['mail_id'])
			{
				array_push($response['user_mail'], $product);
			}
            
        }   
        //vendors
        $vendorData = $collectionVendor->aggregate(array(
            array( '$group' => array(
                '_id' => array(
                    'email' => '$email'
                )
            ))
        ));
        foreach($vendorData as $rowVendor)
        {
            $product['mail_id'] = $rowVendor['_id']['email'];
            if($product['mail_id'] != ''){
                array_push($response['user_mail'], $product);
            }
        }
        
        //purchase order details
        $result = $collectionMaster->aggregate(array(
            array( '$match' => array(
                "po_number" => $poNum
            )),
            array( '$lookup' => array(
                'from' => 'project_details',
                'localField' => 'project_id',
                'foreignField' => '_id',
                'as' => 'project_data'
            )),
            array( '$lookup' => array(
                'from' => 'vendor_details',
                'localField' => 'company',
                'foreignField' => 'company_name',
                'as' => 'vendor_data'
            ))
        ));
        if($result){
            foreach($result as $rows)
            {
                $response['mail_data'] = "<b>Dear ".$rows['vendor_data'][0]['contact_person'].",</b><br><br>We are pleased to place the ".$rows['order_type']." as per the details mentioned in the attachment for our project <b>".$rows['project_data'][0]['project_name']." at ".$rows['project_data'][0]['place'].".<br>&nbsp;<br>Please Mention PO No in your Bill, without which your Invoice will not be Processed.<br>Note : Please send a scanned copy of the invoice by mail for the further follow up.</b>";
                $response['vendor_mail'] = $rows['vendor_data'][0]['email'];
                $response['order_type_short'] = $rows['order_type_short'];
               // $response['subject'] = $rows['project_data'][0]['project_name'].' - Purchase order';
				$response['subject'] = 'PO No : '.$poNum.' - '.$rows['project_data'][0]['project_name'];
            }
            $response["code"] = "Success";
        }
        else {
            $response["code"] = "failed";
        }
        return $response;
    }
    
	//Get Mail Data
	
	 public function getmailid($getData) 
    {
        $jsonData = json_decode($getData);
     
        
        $response["mail_data"] = array();
        
        $collectionUser = $this->conn->signintable;
        $collectionVendor = $this->conn->vendor_details;
        
        //from mail id
        $cursorData = $collectionUser->findOne(array("emp_id" => (int)$jsonData->emp_id));
        $response['mail_id'] = $cursorData['mail_id'];
            
        //sis employees
   
		   $userData = $collectionUser->find(array("status" => "Active"));
        $product = array();
        $response['user_mail'] = array();
    foreach($userData as $rowData)
        {
            $product['mail_id'] = $rowData['mail_id'];
			if($product['mail_id'])
			{
				array_push($response['user_mail'], $product);
			}
            
        }   
        //vendors
        $vendorData = $collectionVendor->aggregate(array(
            array( '$group' => array(
                '_id' => array(
                    'email' => '$email'
                )
            ))
        ));
        foreach($vendorData as $rowVendor)
        {
            $product['mail_id'] = $rowVendor['_id']['email'];
            if($product['mail_id'] != ''){
                array_push($response['user_mail'], $product);
            }
        }

        return $response;
    }
	
	
	
	
	
    //send purchase order mail
//     public function sendpomail($getData)
//     {
//         $response = array();
        
//         //collection
//         $collection = $this->conn->mail_details;
//         $collectionMaster = $this->conn->purchase_master;
//         $collectionUser = $this->conn->signintable;
//         $collectionDuration = $this->conn->quotation_duration;
//         $collectionInventory = $this->conn->inventory;
//         $collectionVendor = $this->conn->vendor_details;
        
//         $jsonData = json_decode($getData, true);
          
//         file_get_contents($this->path.'/modules/materials/purchase/po_pdf.php?type=upload&id='.$jsonData['po_number'].'&emp_id='.$jsonData['emp_id']);
// 		sleep(8);
            
//        if(file_exists('../modules/materials/purchase/'.$jsonData['po_number'].'.pdf'))
//         {
           
//             $sendData['emp_id'] = $jsonData['emp_id'];
//             $sendData['mail_name'] = 'South India Shelters Pvt.Ltd.';
//             $sendData['to_mail'] = $jsonData['to_mail'];
//             $sendData['cc_mail'] = $jsonData['cc_mail'];
//             $sendData['bcc_mail'] = $jsonData['bcc_mail'];
//             $sendData['subject'] = $jsonData['subject'];
//             $sendData['content'] = $jsonData['content'];
//             $sendData['attachment'] = 'true';
//             $sendData['attached_file'] = '../modules/materials/purchase/'.$jsonData['po_number'].'.pdf';
//             $sendData['attached_name'] = date("d-m-Y ").$jsonData['order_type_short'].'-'.$jsonData['po_number'].'.pdf';
                
//             $reqResp = $this->internalMailer(json_encode($sendData));
            
//             if($reqResp=='Success')
//             {
//                 $purchaseData = $collectionMaster->findOne(array("po_number" => $jsonData['po_number']));
                
//                 $collectionVendor->updateOne(
//                     array("company_name" => $purchaseData['company']),
//                     array('$set' => array(
//                         "email" => $jsonData['to_mail']
//                 )));
                
//                 $collectionMaster->updateOne(
//                     array("po_number" => $jsonData['po_number']),
//                     array('$set' => array(
//                         "mail_status" => "send"
//                 )));
                
//                 $collectionInventory->updateMany(
//                     array("po_number" => $jsonData['po_number']),
//                     array('$set' => array(
//                         "mail_status" => "1"
//                 )));
                
//                 //timer
//                 $durationData = $collectionDuration->findOne(array("quot_number" => $purchaseData['quot_number']));
//                 if($durationData && !$durationData['po_datetime'])
//                 {
//                     $respDuration = $this->secondsToWords($durationData['approved_datetime'], date("Y-m-d H:i:s"));
//                     $collectionDuration->updateOne(
//                         array("quot_number" => $purchaseData['quot_number']),
//                             array('$set' => array(
//                             "po_number" => $jsonData['po_number'],
//                             "po_duration" => $respDuration,
//                             "po_datetime" => date("Y-m-d H:i:s")
//                     )));
//                 }
                
//                 $staffData = $collectionUser->findOne(array("emp_id" => (int)$jsonData['emp_id']));
//                 $collection->insertOne(array(
//                     "emp_id" => $staffData['emp_id'],
//                     "date_time" => date("Y-m-d H:i:s"),
//                     "po_number" => $jsonData['po_number'],
//                     "from_mail" => $staffData['mail_id'],
//                     "to_mail" => $jsonData['to_mail'],
//                     "cc_mail" => $jsonData['cc_mail'],
//                     "bcc_mail" => $jsonData['bcc_mail'],
//                     "subject" => $jsonData['subject'],
//                     "content" => $jsonData['content'],
//                     "type" => 'purchase'
//                 ));
//                 $resp = "Success";
//             }
//             else
//             {
//                 $collectionMaster->updateOne(
//                     array("po_number" => $jsonData['po_number']),
//                     array('$set' => array(
//                         "mail_status" => "failure"
//                 )));
//                 $resp = "Failure !!!";
//             }
//              unlink('../modules/materials/purchase/'.$jsonData['po_number'].'.pdf');
//    }
//       else {
//            $resp = "Network Error, Try Again !!!";
//       }
//         $response['code'] = $resp;
//         return $response;
//     }


public function sendpomail($getData)
{
    $response = array();

    // Decode JSON data
    $jsonData = json_decode($getData, true);

    // Generate PDF and wait for it to be available
    $pdfPath = $this->generatePDF($jsonData['po_number'], $jsonData['emp_id']);
    $response["filepath"] = $pdfPath;
    if ($this->waitForFile($pdfPath, 10)) {  // Wait for a maximum of 10 seconds
        // Prepare email data
        $sendData = $this->prepareEmailData($jsonData, $pdfPath);

        // Send email
        $reqResp = $this->internalMailer(json_encode($sendData));

        if ($reqResp == 'Success') {
            // Update database records
            $this->updateDatabase($jsonData);
            $resp = "Success";
        } else {
            $this->updateMailStatus($jsonData['po_number'], "failure");
            $resp = "Failure !!!";
        }

        // Delete PDF file
        unlink($pdfPath);
    } else {
        $resp = "Network Error, Try Again !!!";
    }

    $response['code'] = $resp;
    return $response;
}

private function generatePDF($poNumber, $empId)
{
    $pdfUrl = $this->path.'/modules/materials/purchase/po_pdf.php?type=upload&id='.$poNumber.'&emp_id='.$empId;
    file_get_contents($pdfUrl);
    return '../modules/materials/purchase/'.$poNumber.'.pdf';
}

private function waitForFile($filePath, $timeout)
{
    $start = time();
    while (!file_exists($filePath)) {
        if (time() - $start > $timeout) {
            return false;
        }
        usleep(500000); // Sleep for 0.5 seconds
    }
    return true;
}

private function prepareEmailData($jsonData, $pdfPath)
{
    return array(
        'emp_id' => $jsonData['emp_id'],
        'mail_name' => 'South India Shelters Pvt.Ltd.',
        'to_mail' => $jsonData['to_mail'],
        'cc_mail' => $jsonData['cc_mail'],
        'bcc_mail' => $jsonData['bcc_mail'],
        'subject' => $jsonData['subject'],
        'content' => $jsonData['content'],
        'attachment' => 'true',
        'attached_file' => $pdfPath,
        'attached_name' => date("d-m-Y ").$jsonData['order_type_short'].'-'.$jsonData['po_number'].'.pdf'
    );
}

private function updateDatabase($jsonData)
{
    $collectionMaster = $this->conn->purchase_master;
    $collectionVendor = $this->conn->vendor_details;
    $collectionInventory = $this->conn->inventory;
    $collectionDuration = $this->conn->quotation_duration;
    $collectionUser = $this->conn->signintable;
    $collection = $this->conn->mail_details;

    $purchaseData = $collectionMaster->findOne(array("po_number" => $jsonData['po_number']));

    $collectionVendor->updateOne(
        array("company_name" => $purchaseData['company']),
        array('$set' => array("email" => $jsonData['to_mail']))
    );

    $collectionMaster->updateOne(
        array("po_number" => $jsonData['po_number']),
        array('$set' => array("mail_status" => "send"))
    );

    $collectionInventory->updateMany(
        array("po_number" => $jsonData['po_number']),
        array('$set' => array("mail_status" => "1"))
    );

    $this->updateQuotationDuration($purchaseData, $jsonData['po_number']);

    $staffData = $collectionUser->findOne(array("emp_id" => (int)$jsonData['emp_id']));
    $collection->insertOne(array(
        "emp_id" => $staffData['emp_id'],
        "date_time" => date("Y-m-d H:i:s"),
        "po_number" => $jsonData['po_number'],
        "from_mail" => $staffData['mail_id'],
        "to_mail" => $jsonData['to_mail'],
        "cc_mail" => $jsonData['cc_mail'],
        "bcc_mail" => $jsonData['bcc_mail'],
        "subject" => $jsonData['subject'],
        "content" => $jsonData['content'],
        "type" => 'purchase'
    ));
}

private function updateQuotationDuration($purchaseData, $poNumber)
{
    $collectionDuration = $this->conn->quotation_duration;

    $durationData = $collectionDuration->findOne(array("quot_number" => $purchaseData['quot_number']));
    if ($durationData && !$durationData['po_datetime']) {
        $respDuration = $this->secondsToWords($durationData['approved_datetime'], date("Y-m-d H:i:s"));
        $collectionDuration->updateOne(
            array("quot_number" => $purchaseData['quot_number']),
            array('$set' => array(
                "po_number" => $poNumber,
                "po_duration" => $respDuration,
                "po_datetime" => date("Y-m-d H:i:s")
            ))
        );
    }
}

private function updateMailStatus($poNumber, $status)
{
    $collectionMaster = $this->conn->purchase_master;
    $collectionMaster->updateOne(
        array("po_number" => $poNumber),
        array('$set' => array("mail_status" => $status))
    );
}


	
	
	
	 public function stockuse_balanceupdate($getData)
    {
          $response = array();
        $collectionStockUse = $this->conn->stockupdate_use;
		 
        $jsonData = json_decode($getData, true);

		 $response['code'] = "success";
		  $response['id'] = $jsonData['id'];
		  $response['balance'] = $jsonData['balance'];		 
    $rowData =  $collectionStockUse->findOne(array("_id" => new MongoDB\BSON\ObjectID($jsonData['id'])));
          $response['stock_details'] = $rowData;
		 
		  if($rowData['_id'])
            {
				
				 $response[id] = $rowData['_id']; 
                $collectionStockUse->updateOne(
                    array("_id" => $rowData['_id']),
                    array('$set' => array("balance_qty" => $jsonData['balance']))
                );              
                $response["code"] = "Success";
            }
        else {
            $response["code"] = "failed";               
        }
        
        return $response;
	
    }	
	
	
    
    //delete order
    public function deleteorder($getData)
    {
        $response = array();
         
        // collection
        $collectionMaster = $this->conn->purchase_master;
        $collectionQuot = $this->conn->quotation_master;
        $collectionDuration = $this->conn->quotation_duration;
        $collectionPurtable = $this->conn->purchase_table;
        $collectionInventory = $this->conn->inventory;
        $collectionStockIn = $this->conn->stockupdate_in;
        $collectionStockUse = $this->conn->stockupdate_use;
        
        $jsonData = json_decode($getData);
        $po_number = $jsonData->po_number;
        $type = $jsonData->type;
        
        if($type=='deleteorder')
        {
            //indent status
            $cursorData = $collectionMaster->findOne(array("po_number" => $po_number));
            if($cursorData['quot_number'])
            {
                $collectionQuot->updateOne(
                    array("quot_number" => $cursorData['quot_number']),
                    array('$set' => array("po_raised" => ""))
                );
                
                $collectionDuration->updateOne(
                    array("quot_number" => $cursorData['quot_number']),
                    array('$unset' => array("po_number" => '', "po_duration" => '', "po_datetime" => ''))
                );
            }
            
            $result = $collectionMaster->deleteMany(array("po_number" => $po_number));
            if($result)
            {
                //purchase table
                $cursor = $collectionPurtable->deleteMany(array("po_number" => $po_number));
                //inventory
                $cursor = $collectionInventory->deleteMany(array("po_number" => $po_number));
                //stock maintenance
                $cursor = $collectionStockIn->deleteMany(array("po_number" => $po_number));
                $cursor = $collectionStockUse->deleteMany(array("po_number" => $po_number));
            }
        }
        else
        {
            if($type=='removeorder'){
                $cancelStatus='1';
            }
            elseif($type=='reopenorder'){
                $cancelStatus='0';
            }
            
            //purchase table
            $cursor = $collectionMaster->updateOne(
                array("po_number" => $po_number),
                array('$set' => array("cancel_status" => $cancelStatus))
            );
            
            //inventory
            $cursor = $collectionInventory->updateMany(
                array("po_number" => $po_number),
                array('$set' => array("cancel_status" => $cancelStatus))
            );
        }
       
        if ($cursor) {                
            $response["code"] = "Success";
        } 
        else {
            $response["code"] = "failed";               
        }
        return $response;
    }
    
    //delete all orders
    public function deleteallorders($getData)
    {
        $response = array();
         
        // collection
        $collectionMaster = $this->conn->purchase_master;
        $collectionQuot = $this->conn->quotation_master;
        $collectionDuration = $this->conn->quotation_duration;
        $collectionPurtable = $this->conn->purchase_table;
        $collectionInventory = $this->conn->inventory;
        $collectionStockIn = $this->conn->stockupdate_in;
        $collectionStockUse = $this->conn->stockupdate_use;
        
        $jsonData = json_decode($getData, true);
        
        $jsonArrayData = json_decode($jsonData["jsonArrayData"], true);
        $listCount = count($jsonArrayData);
        
        for($i=0; $i<$listCount; $i++)
        {
            if($jsonArrayData[$i]['type']=='usage') {
                
                //purchase table
                $collectionMaster->updateOne(
                    array("po_number" => $jsonArrayData[$i]['po_number']),
                    array('$set' => array("cancel_status" => '1'))
                );

                //inventory
                $collectionInventory->updateMany(
                    array("po_number" => $jsonArrayData[$i]['po_number']),
                    array('$set' => array("cancel_status" => '1'))
                );
            }
            else {
                //indent status
                $cursorData = $collectionMaster->findOne(array("po_number" => $jsonArrayData[$i]['po_number']));
                if($cursorData['quot_number'])
                {
                    $collectionQuot->updateOne(
                        array("quot_number" => $cursorData['quot_number']),
                        array('$set' => array("po_raised" => ""))
                    );
                    
                    $collectionDuration->updateOne(
                        array("quot_number" => $cursorData['quot_number']),
                        array('$unset' => array("po_number" => '', "po_duration" => '', "po_datetime" => ''))
                    );
                }
                
                //purchase table
                $collectionMaster->deleteMany(array("po_number" => $jsonArrayData[$i]['po_number']));
                $collectionPurtable->deleteMany(array("po_number" => $jsonArrayData[$i]['po_number']));
                //inventory
                $collectionInventory->deleteMany(array("po_number" => $jsonArrayData[$i]['po_number']));
                //stock maintenance
                $collectionStockIn->deleteMany(array("po_number" => $jsonArrayData[$i]['po_number']));
                $collectionStockUse->deleteMany(array("po_number" => $jsonArrayData[$i]['po_number']));
            }
        }
        
        $response["code"] = "Success";
        return $response;
    }
    
/***** INVENTORY *****/

    public function getinventorylist($getData)
    {
        $response = array();
        $jsonData = json_decode($getData, true);
        $response["code1"]="test";
        $collection_inventory = $this->conn->inventory;

        $collectionVendor = $this->conn->vendor_details;
        if($jsonData['cancel_status'] == 'incomplete'){ $compStatus = '0'; }
        else{ $compStatus = '1'; }
        
        //site list
        $siteData = $this->internalsitelist($jsonData['emp_id']);
        $response['site_list'] = $siteData["site_list"];
        
        $proShortArray = $siteData["project_short_list"];
        if($jsonData['project_short']!='') { $proShortArray = array($jsonData['project_short']); }

        // $collection_inventory->createIndex(['company' => 1]);
        // $collectionVendor->createIndex(['company_name' => 1]);

        // $lookupStage = array(
        //     '$lookup' => array(
        //         'from' => 'vendor_details',
        //         'let' => array('company' => '$company'),
        //         'pipeline' => array(
        //             array(
        //                 '$match' => array(
        //                     '$expr' => array(
        //                         '$eq' => array('$company_name', '$$company')
        //                     )
        //                 )
        //             ),
        //             array('$limit' => 1) // Limit to 1 document per match
        //         ),
        //         'as' => 'vendor_data'
        //     )
        // );

        $cursor = $collection_inventory->aggregate(array(
            array( '$match' => array(
                "cancel_status" => '0',
                "mail_status" => '1',
                "project_short" => array( '$in' => $proShortArray ),
                "completed_status" => $compStatus
            )),
            // $lookupStage,
            array( '$lookup' => array(
                'from' => 'purchase_master',
                'localField' => 'po_number',
                'foreignField' => 'po_number',
                'as' => 'purchase_data'
            )),
            array( '$lookup' => array(
                'from' => 'project_details',
                'localField' => 'project_id',
                'foreignField' => '_id',
                'as' => 'project_data'
            )),
			//  array( '$lookup' => array(
            //     'from' => 'vendor_details',
            //     'localField' => 'company',
            //     'foreignField' => 'company_name',
            //     'as' => 'vendor_data'
            // )),
            array( '$sort' => array( 
                '_id' => -1
            ))
        ));
        if($cursor) 
        {
            $response['inventory'] = array();
            foreach($cursor as $rowData){
                if(!$rowData['quantity']) { $rowData['quantity'] = '-'; }
                if(!$rowData['stock']) { $rowData['stock'] = '0'; }
                if(!$rowData['stock_percentage']) { $rowData['stock_percentage'] = '0'; }
                if(!$rowData['return']) { $rowData['return'] = '0'; }
				// if(!$rowData['vendor_data'][0]['mobile']) { $rowData['vendor_data'][0]['mobile'] = '0'; }
                if(!$rowData['return_percentage']) { $rowData['return_percentage'] = '0'; }
                if(!$rowData['purchase_data'][0]['po_number']) { $rowData['purchase_data'][0]['po_number'] = '-'; }
                if(!$rowData['purchase_data'][0]['po_date']) { $rowData['purchase_data'][0]['po_date'] = '-'; }
                if(!$rowData['purchase_data'][0]['delivery_date']) { $rowData['purchase_data'][0]['delivery_date'] = '-'; }

                $sendData['_id'] = $rowData['_id'];
                $sendData['po_number'] = $rowData['purchase_data'][0]['po_number'];
                $sendData['project_name'] = $rowData['project_data'][0]['project_name'];
                $sendData['block_name'] = $rowData['project_data'][0]['block_name'];
                $sendData['item'] = $rowData['item'];
                $sendData['code'] = $rowData['code'];
                $sendData['quantity'] = $rowData['quantity'];
				$sendData['vendorname'] = $rowData['purchase_data'][0]['company'];   
				
				// $vendorData = $collectionVendor->findOne(
                //     array("company_name" => $rowData['purchase_data'][0]['company']),
                //     array("mobile" => 1)
                // );
                

		        // $sendData['vendor_mobile'] = $vendor_mobile;
                $sendData['stock'] = $rowData['stock'];
                $sendData['stock_percentage'] = $rowData['stock_percentage'];
                $sendData['return'] = $rowData['return'];
                $sendData['unit'] = $rowData['unit'];
                $sendData['return_percentage'] = $rowData['return_percentage'];
                $sendData['po_date'] = $rowData['purchase_data'][0]['po_date'];
                $sendData['delivery_date'] = $rowData['purchase_data'][0]['delivery_date'];
                
                array_push($response['inventory'], $sendData);
            }
            $response["code"] = "Success";
        } 
        else {
            $response["code"] = "failed";               
        }
        return $response;
    }
    
    //inventory list details
    public function getstockdetails($getData)
    {
        $response = array();
        $response['dc_list'] = array();
        $jsonData = json_decode($getData, true);
        $id = $jsonData['id'];
         
        // collection
        $collection = $this->conn->inventory;
        $collectionStock = $this->conn->stockupdate_in;
        
        $cursor = $collection->aggregate(array(
            array( '$match' => array(
                "_id" => new MongoDB\BSON\ObjectID($id)
            )),
            array( '$lookup' => array(
                'from' => 'project_details',
                'localField' => 'project_id',
                'foreignField' => '_id',
                'as' => 'project_data'
            )),
            array( '$lookup' => array(
                'from' => 'purchase_table',
                'localField' => 'purchase_id',
                'foreignField' => '_id',
                'as' => 'purchase_data'
            ))
        ));
        if ($cursor) {
            
            $response['inventory'] = array();
          
            foreach($cursor as $rowData)
            {
                if($rowData['project_data'][0]['block_name']!='none') {
                $block=' - '.$rowData['project_data'][0]['block_name'].' Block';
                }
                if(!$rowData['stock']) { $rowData['stock'] = '0'; }
                if(!$rowData['return']) { $rowData['return'] = '0'; }
                if(!$rowData['usage']) { $rowData['usage'] = '0'; }
                if(!$rowData['breakage']) { $rowData['breakage'] = '0'; }
                if(!$rowData['transfer']) { $rowData['transfer'] = '0'; }
                
                $sendData['id'] = $id;
                $sendData['po_number'] = $rowData['po_number'];
                $sendData['item'] = $rowData['item'];
                $sendData['code'] = $rowData['code'];
                $sendData['size'] = $rowData['purchase_data'][0]['size'];
                $sendData['site'] = $rowData['project_data'][0]['project_name'].$block;
                $sendData['quantity'] = $rowData['quantity'];
                $sendData['stock'] = $rowData['stock'];
                $sendData['return'] = $rowData['return'];
                $sendData['balance'] = ($rowData['quantity'] - ($rowData['stock']+$rowData['return']));
                $sendData['usage'] = $rowData['usage']+$rowData['breakage']+$rowData['transfer'];
               
                $response['inventory'] = $sendData;
            }

            //received dc no
            $stockData = $collectionStock->find(array("inventory_id" => new MongoDB\BSON\ObjectID($id), "type" => "received"));
            if($stockData)
            {
                foreach ($stockData as $key => $value) {
                    $dcData['dc_no'] = $value['dc_no'];
                    array_push($response['dc_list'], $dcData);
                }
            }
            $response["code"] = "Success";
        }
        else {
            $response["code"] = "failed";               
        }
        return $response;
    }
    
    //inventory detailed view
    public function inventorydetailedview($getData)
    {
        $response = array();
        $jsonData = json_decode($getData);
        $id = $jsonData->id;
         
        // collection
        $collection = $this->conn->stockupdate_in;
        $collectionProject = $this->conn->project_details;
        
        $cursor = $collection->aggregate(array(
            array( '$match' => array(
                "inventory_id" => new MongoDB\BSON\ObjectID($id)
            )),
            array( '$lookup' => array(
                'from' => 'signintable',
                'localField' => 'emp_id',
                'foreignField' => 'emp_id',
                'as' => 'user_data'
            ))
        ));
        if ($cursor) 
        {
            $response['inventory'] = array();
            foreach($cursor as $rowData)
            {
                if(!$rowData['sis_grn_no']) { $rowData['sis_grn_no'] = '-'; } 
                if(!$rowData['vehicle_no']) { $rowData['vehicle_no'] = '-'; } 
                if(!$rowData['dc_no']) { $rowData['dc_no'] = '-'; } 
                $sendData['date_time'] = date("d-m-Y h:i a", strtotime($rowData['date_time']));

                $sendData['stock'] = $rowData['stock'];
                $sendData['sisgrnno'] = $rowData['sis_grn_no'];
                $sendData['dcno'] = $rowData['dc_no'];
                $sendData['vehicleno'] = $rowData['vehicle_no'];
                $sendData['id'] = $rowData['_id'];

                if($rowData['type']=='received') {
                    $sendData['status'] = 'Received';
                    $sendData['color'] = 'limegreen';
                    $sendData['quantity'] = $rowData['received_qty'];  
                }
                elseif($rowData['type']=='transfer') {
                    $projectData = $collectionProject->findOne(array("_id" => new MongoDB\BSON\ObjectID($rowData['transfer_from'])));
                    if($projectData['block_name']!='none') { $block=' - '.$projectData['block_name'].' Block'; }
                    $sendData['status'] = 'Receive from '.$projectData['project_name'].$block;
                    $sendData['color'] = 'steelblue';
                    $sendData['quantity'] = $rowData['transfer_qty'];
                }
                else {
                    $sendData['status'] = ucfirst($rowData['type']).' '.$rowData['return_type'];
                    $sendData['color'] = 'red';
                    $sendData['quantity'] = $rowData['return_qty'].' - '.$rowData['return_reason'];
                }
                $sendData['done_by'] = $rowData['user_data'][0]['name'].' - '.$rowData['user_data'][0]['department'];
               
                array_push($response['inventory'], $sendData);
            }
            $response["code"] = "Success";
        }
        else {
            $response["code"] = "failed";               
        }
        return $response;
    }
    

    //update vendor
    public function inventory_detailupdate($getData) 
    {
        $response = array();

        $collectionVendor = $this->conn->stockupdate_in;
        $jsonData = json_decode($getData, true);  
        $response['data'] = $jsonData;
        $cursor = $collectionVendor->updateOne(
                array("_id" => new MongoDB\BSON\ObjectID($jsonData["id"])),
                array('$set' => array(
                "sis_grn_no" => $jsonData["grn_no"],
                "dc_no" => $jsonData["dc_no"],
                "vehicle_no" => $jsonData["veh_no"]               
            )));
        if($cursor){
            $response["code"] = "Success";
        }
        else{
            $response["code"] = "failed";
        }
        return $response;
    }


    //stock update
    public function stockupdate_in($getData)
    {
        $response = array();
        $jsonData = json_decode($getData, true);
        
        // collection
        $collection = $this->conn->inventory;
        $collectionMaster = $this->conn->purchase_master;
        $collectionStockIn = $this->conn->stockupdate_in;
        $collectionStockUse = $this->conn->stockupdate_use;
        
        $cursorData = $collection->findOne(array("_id" => new MongoDB\BSON\ObjectID($jsonData["id"])));
        $divider = $cursorData['quantity']/100;
        
        if($jsonData["status"]=='receive') {
            $stock = $cursorData["stock"] + $jsonData["received_qty"];
            $stockPer = round($stock/$divider, 2);
            $return = $cursorData["return"];
            $returnPer = $cursorData["return_percentage"];
            $availQty = $cursorData["available"] + $jsonData["received_qty"];
        }
        else {
            if($jsonData["return_status"]=='at') {
                $stock = $cursorData["stock"];
                $stockPer = $cursorData["stock_percentage"];
                $return = $cursorData["return"] + $jsonData["return_qty"];
                $returnPer = round($return/$divider, 2);
                $availQty = $cursorData["available"];
                $returnType = 'at delivery';
            }
            else {
                $stock = $cursorData["stock"] - $jsonData["return_qty"];
                $stockPer = round($stock/$divider, 2);
                $return = $cursorData["return"] + $jsonData["return_qty"];
                $returnPer = round($return/$divider, 2);
                $availQty = $cursorData["available"] - $jsonData["return_qty"];
                $returnType = 'after received';
            }
        }
        
        //completed status
        if(($stock+$return) >= $cursorData['quantity']){ $completeStatus = '1'; }
        else{ $completeStatus = '0'; }

        $cursor = $collection->updateOne(
            array("_id" => new MongoDB\BSON\ObjectID($jsonData["id"])),
            array('$set' => array( 
                "stock" => $stock,
                "stock_percentage" => $stockPer,
                "return" => $return,
                "return_percentage" => $returnPer,
                "available" => $availQty,
                "completed_status" => $completeStatus
            ))
        );
        
        $validate = $collection->findOne(array("po_number" => $cursorData["po_number"], "completed_status" => '0'));
        if($validate){ $poCompStatus = '0'; }
        else{ $poCompStatus = '1'; }
        
        $poMasterData = $collectionMaster->findOne(array("po_number" => $cursorData["po_number"]));
        if($poMasterData)
        {
            if($jsonData["received_qty"]>0 && $poMasterData['bill_status']==2) { $poMasterData['bill_status']=0; }

            $collectionMaster->updateOne(
                array("po_number" => $cursorData["po_number"]),
                array('$set' => array("completed_status"=>$poCompStatus, "received_status"=>'1', "bill_status"=>$poMasterData['bill_status']))
            );
        }
        
        if ($cursor)
        {
            $cursorStock = $collectionStockUse->aggregate(array(
                array( '$match' => array(
                    "project_id" => new MongoDB\BSON\ObjectID($cursorData["project_id"]),
                    "code" => $cursorData["code"],
                    "item" => $cursorData["item"]
                )),
                array( '$sort' => array( 
                    '_id' => -1
                )),
                array( '$limit' => 1 )
            ));
            if($cursorStock) {
                foreach($cursorStock as $rowStock) {
                    $balanceQty=$rowStock['balance_qty'];
                }
            }
            
            $poArray = array();
            $sendArray['inventory_id']=$jsonData["id"].$oid;
            $sendArray['po_number']=$cursorData["po_number"];
            
            if($jsonData["received_qty"] > 0)
            {
                $collection->updateOne(
                    array("_id" => new MongoDB\BSON\ObjectID($jsonData["id"])),
                    array('$push' => array(
                        "qty_ledger" => array(
                            "dc_no" => $jsonData["dc_no"], "qty" => (float)$jsonData["received_qty"], "billed_qty" => 0,
                            "create_at"=> new MongoDB\BSON\UTCDateTime()
                        )
                    ))
                );

                $collectionStockIn->insertOne(array(
                    "type" => 'received',
                    "po_number" => $cursorData["po_number"],
                    "inventory_id" => $cursorData["_id"],
                    "emp_id" => (int)$jsonData["emp_id"],
                    "date_time" => date("Y-m-d H:i:s"),
                    "received_qty" => $jsonData["received_qty"],
                    "sis_grn_no" => $jsonData["grn_no"],
                    "dc_no" => $jsonData["dc_no"],
                    "vehicle_no" => $jsonData["veh_no"],
                    "stock" => $stock
                ));
                
                $sendArray['used_qty']=$jsonData["received_qty"];
                array_push($poArray, $sendArray);
                
                //stockupdate use
                $collectionStockUse->insertOne(array(
                    "emp_id" => (int)$jsonData["emp_id"],
                    "project_id" => $cursorData["project_id"],
                    "code" => $cursorData["code"],
                    "item" => $cursorData["item"],
                    "po_details" => $poArray,
                    "total_qty" => $jsonData["received_qty"],
                    "balance_qty" => $balanceQty+$jsonData["received_qty"],
                    "date_time" => date("Y-m-d H:i:s"),
                    "type" => 'received'
                ));
            }
            if($jsonData["return_qty"] > 0)
            {
                if($returnType == 'at delivery') { $jsonData["dc_no"] = ''; }

                $collectionStockIn->insertOne(array(
                    "type" => 'return',
                    "return_type" => $returnType,
                    "po_number" => $cursorData["po_number"],
                    "inventory_id" => $cursorData["_id"],
                    "emp_id" => (int)$jsonData["emp_id"],
                    "date_time" => date("Y-m-d H:i:s"),
                    "return_qty" => $jsonData["return_qty"],
                    "dc_no" => $jsonData["dc_no"],
                    "return_reason" => $jsonData["return_reason"],
                    "stock" => $stock
                ));
                
                if($returnType=='after received')
                {
                    $collection->updateOne(
                        array("_id" => new MongoDB\BSON\ObjectID($jsonData["id"]), "qty_ledger.dc_no" => $jsonData["dc_no"]),
                        array('$inc' => array( "qty_ledger.$.qty" => -(float)$jsonData["return_qty"] ))
                    );

                    $sendArray['used_qty']=$jsonData["return_qty"];
                    array_push($poArray, $sendArray);
                    
                    //stockupdate use
                    $collectionStockUse->insertOne(array(
                        "emp_id" => (int)$jsonData["emp_id"],
                        "project_id" => $cursorData["project_id"],
                        "code" => $cursorData["code"],
                        "item" => $cursorData["item"],
                        "po_details" => $poArray,
                        "total_qty" => $jsonData["return_qty"],
                        "balance_qty" => $balanceQty-$jsonData["return_qty"],
                        "date_time" => date("Y-m-d H:i:s"),
                        "type" => 'return'
                    ));
                }
                
                //send mail to vendor
                /*$cursorVendor = $collectionMaster->aggregate(array(
                    array( '$match' => array(
                        "po_number" => $jsonData['po_number']
                    )),
                    array( '$lookup' => array(
                        'from' => 'vendor_details',
                        'localField' => 'company',
                        'foreignField' => 'company_name',
                        'as' => 'vendor_data'
                    ))
                ));
                if($cursorVendor) {
                    foreach($cursorVendor as $vendorData)
                    {
                        if($vendorData['vendor_data'][0]['email']!='')
                        {
                            $sendData['emp_id'] = $jsonData['emp_id'];
                            $sendData['mail_name'] = 'South India Shelters Pvt.Ltd.';
                            $sendData['to_mail'] = $vendorData['vendor_data'][0]['email'];
                            $sendData['cc_mail'] = 'anisa@sis.in';
                            $sendData['subject'] = 'Return product';
                            $sendData['content'] = 'The following products are returned';
                            
                            $reqResp = $this->internalMailer(json_encode($sendData));
                        }
                    }
                }*/
            }  
            $response["code"] = "Success";
        }
        else {
            $response["code"] = "failed";               
        }
        return $response;
    }
    
/***** INVENTORY USE *****/
    
    //inventory out
    public function getinventoryuselist($getData)
    {
        $response = array();
        $jsonData = json_decode($getData, true);
        
        // collection
        $collection = $this->conn->inventory;
        $collectionProject = $this->conn->project_details;
        
        //site list
        $siteData = $this->internalsitelist($jsonData['emp_id']);
        $response['site_list'] = $siteData["site_list"];
        
        //project list
        $response['project_list'] = $this->internalsisproject();
        
        $proShortArray = $siteData["project_short_list"];
        if($jsonData['project_short']!='') { $proShortArray = array($jsonData['project_short']); }
        
        //retrieve data
        $cursor = $collection->aggregate([
            [
                '$match' => [
                    "project_short" => ['$in' => $proShortArray],
                    "category" => $jsonData['category'],
                    "cancel_status" => '0',
                    "mail_status" => '1',
                    "stock" => ['$gt' => 0]
                ]
            ],
            [
                '$group' => [
                    '_id' => [
                        'project_id' => '$project_id',
                        'item' => '$item',
                        'code' => '$code',
                        'unit' => '$unit'
                    ],
                    'inventory_id' => ['$push' => '$_id'],
                    'stock' => ['$sum' => '$stock'],
                    'usage' => ['$sum' => '$usage'],
                    'breakage' => ['$sum' => '$breakage'],
                    'transfer' => ['$sum' => '$transfer'],
                    'available' => ['$sum' => '$available']
                ]
            ],
            [
                '$lookup' => [
                    'from' => 'project_details',
                    'localField' => '_id.project_id',
                    'foreignField' => '_id',
                    'as' => 'projectData'
                ]
            ],
            [
                '$unwind' => '$projectData'
            ],
            [
                '$project' => [
                    'inventory_id' => 1,
                    'project_id' => '$_id.project_id',
                    'project_name' => '$projectData.project_name',
                    'block_name' => '$projectData.block_name',
                    'item' => '$_id.item',
                    'code' => '$_id.code',
                    'unit' => '$_id.unit',
                    'stock' => 1,
                    'usage' => 1,
                    'breakage' => 1,
                    'transfer' => 1,
                    'available' => 1
                ]
            ]
        ]);
        
        if ($cursor) {
            
            $response['inventory'] = array();
            
            foreach($cursor as $rowData){
                // $projectData = $collectionProject->findOne(array("_id" => new MongoDB\BSON\ObjectID($rowData['_id']['project_id'])));
                $sendData['inventory_id'] = $rowData['inventory_id'];
                $sendData['project_id'] = $rowData['_id']['project_id'];
                $sendData['project_name'] = $rowData['project_name'];
                $sendData['block_name'] = $rowData['block_name'];
                $sendData['item'] = $rowData['_id']['item'];
                $sendData['code'] = $rowData['_id']['code'];
                $sendData['unit'] = $rowData['_id']['unit'];
                $sendData['stock'] = $rowData['stock'];
                $sendData['usage'] = $rowData['usage'];
                $sendData['breakage'] = $rowData['breakage'];
                $sendData['transfer'] = $rowData['transfer'];
                $sendData['available'] = $rowData['available'];
                array_push($response['inventory'], $sendData);
            }
            $response["code"] = "Success";
        } 
        else {
            $response["code"] = "failed";               
        }
        return $response;
    }
    
    //inventory use detailed view
    public function inventoryusedetailedview($getData)
    {
        $response = array();
        $jsonData = json_decode($getData, true);
        $response['inventory'] = array();
        
        // collection
        $collection = $this->conn->stockupdate_use;
        
        $cursor = $collection->aggregate(array(
            array( '$match' => array(
                "project_id" => new MongoDB\BSON\ObjectID($jsonData["project_id"]['$oid']),
                "code" => $jsonData["code"],
                "item" => $jsonData["item"]
            )),
            array( '$lookup' => array(
                'from' => 'signintable',
                'localField' => 'emp_id',
                'foreignField' => 'emp_id',
                'as' => 'user_data'
            ))
        ));
        if($cursor) 
        {
             foreach($cursor as $rowData)
            {
                $sendData['date_time'] = date("d-m-Y h:i a", strtotime($rowData['date_time']));
                $sendData['quantity'] = $rowData['total_qty'];
				  $sendData['id'] = $rowData['_id'];
                $sendData['balance'] = $rowData['balance_qty'];
                $sendData['done_by'] = $rowData['user_data'][0]['name'].' - '.$rowData['user_data'][0]['department'];
            
                if($rowData['type']=='transfer in' || $rowData['type']=='transfer out') {
                    $sendData['po_list'] = '-';
                    $sendData['status'] = ucfirst($rowData['type']);
                    $sendData['flat_list'] = $rowData['usage_details'];
                }
                else {
                    $poDetails='';
                    $poCount = count($rowData['po_details']);
                    for($m=0; $m<$poCount; $m++)
                    {
                        if($rowData['po_details'][$m]['po_number']!='') {
              $poDetails .= $rowData['po_details'][$m]['po_number']." - ".$rowData['po_details'][$m]['used_qty']." Qty<br>";
                        }
                        else {
                            $poDetails = "-";
                        }
                    }
                    $sendData['po_list'] = $poDetails;
                    
                    $flatDetails='';
                    $count = count($rowData['usage_details']);
                    
                    if($rowData['type']=='received') {
                        $sendData['status'] = 'New Stock';
                        $flatDetails .= '-';
                    }
                    elseif($rowData['type']=='return') {
                        $sendData['status'] = 'Returned';
                        $flatDetails .= '-';
                    }
                    elseif($rowData['type']=='usage') {
                        $sendData['status'] = 'Usage';
                        for($k=0; $k<$count; $k++)
                        {
                            $flatDetails .= 'Flat No: '.$rowData['usage_details'][$k]['flat_no']." - ".$rowData['usage_details'][$k]['quantity']." Qty<br><span><b>".$rowData['usage_details'][$k]['contractor']."</b></span>";
                        }
                    }
				  elseif($rowData['type']=='balance') {
                        $sendData['status'] = 'Excess Return';
                        for($k=0; $k<$count; $k++)
                        {
                            $flatDetails .= 'Flat No: '.$rowData['usage_details'][$k]['flat_no']." - ".$rowData['usage_details'][$k]['quantity']." Qty<br><span><b>".$rowData['usage_details'][$k]['contractor']."</b></span>";
                        }
                    }
					
                    else {
                        $sendData['status'] = 'Breakage';
                        for($k=0; $k<$count; $k++)
                        {
                            $flatDetails .= 'Flat No: '.$rowData['usage_details'][$k]['flat_no']." - ".$rowData['usage_details'][$k]['quantity']." Qty (".$rowData['usage_details'][$k]['reason'].")<br>";
                        }
                    }
                    $sendData['flat_list'] = $flatDetails;
                }
                        
                array_push($response['inventory'], $sendData);
            }
            $response["code"] = "Success";
        }
        else {
            $response["code"] = "failed";               
        }
        
        return $response;
    }
    
 
        
    public function get_flatlist($getData)
    {
        $response['flat_details'] = array();
        $jsonArrayData = json_decode($getData, true);
        
        // collection
        $collection = $this->conn->flat_details;
        
        $id = $jsonArrayData['id']['$oid'];
        $cursor = $collection->find(array("project_id" => new MongoDB\BSON\ObjectID($id)));
        
        $product = array();
        foreach($cursor as $row)
        {
            $product['flat_no'] = $row['flat_no'];
            array_push($response['flat_details'], $product);
        }
        $response["code"] = "Success";
        return $response;
    }
    
// public function stockupdate_use($getData)
// {
//     $response = array();
//     $usageData = array();
//     $flatUsage = array();
//     $jsonArrayData = json_decode($getData, true);
//     $response["jsondata1"] = $jsonArrayData;

//     // Log the entire JSON data for initial debugging
//     error_log("Received data: " . print_r($jsonArrayData, true));

//     // MongoDB collections
//     $collection = $this->conn->inventory;
//     $collectionStockIn = $this->conn->stockupdate_in;
//     $collectionStockUse = $this->conn->stockupdate_use;
//     $collectionProject = $this->conn->project_details;

//     // Fetch project data
//     $projectData = $collectionProject->findOne(array("_id" => new MongoDB\BSON\ObjectID($jsonArrayData["project_id"]['$oid'])));
//     error_log("Project data: " . print_r($projectData, true));

//     if (!$projectData) {
//         $response["code"] = 'Error';
//         $response["message"] = "Project not found.";
//         return $response;
//     }

//     // Determine the type and total quantity based on status
//     switch ($jsonArrayData['status']) {
//         case 'use':
//             $totalQty = $jsonArrayData['total_use_qty'];
//             $type = 'usage';
//             break;
//         case 'break':
//             $totalQty = $jsonArrayData['total_break_qty'];
//             $type = 'breakage';
//             break;
//         case 'balance':
//             $totalQty = $jsonArrayData['total_balance_qty'];
//             $type = 'balance';
//             break;
//         case 'transfer':
//             $totalQty = $jsonArrayData['total_transfer_qty'];
//             $type = 'transfer';
//             break;
//         default:
//             $response["code"] = 'Error';
//             $response["message"] = "Invalid status.";
//             return $response;
//     }
    
//     error_log("Type: " . $type . ", Total Qty: " . $totalQty);

//     $usedQty = $totalQty;
//     $count = count($jsonArrayData['inventory_id']);
//     error_log("Inventory ID count: " . $count);

//     // Check the status and handle accordingly
//     if ($jsonArrayData['status'] == 'balance') {
//         $response["jsondata6"] = $jsonArrayData['status'];
//     } else {
//         $response["jsondata7"] = $jsonArrayData['status'];
//         for ($i = 0; $i < $count; $i++) {
//             if (isset($jsonArrayData['inventory_id'][$i]['$oid'])) {
//                 $id = $jsonArrayData['inventory_id'][$i]['$oid'];
//                 error_log("Inventory ID: " . $id);
//                 $response["jsondata8"] = $id;
//                 $inventoryData = $collection->findOne(array("_id" => new MongoDB\BSON\ObjectID($id)));
//                 $response["jsondata9"] = $inventoryData;
//             } else {
//                 error_log("Missing ID for inventory item at index " . $i);
//                 $response["jsondata8"] = null;
//             }
//         }
//     }

//     $response["code"] = 'Success';
//     return $response;
// }

public function stockupdate_use($getData)
    {
        $response = array();
        $usageData = array();
        $flatUsage = array();
        $jsonArrayData = json_decode($getData, true);
        
        // collection
        $collection = $this->conn->inventory;
        $collectionStockIn = $this->conn->stockupdate_in;
        $collectionStockUse = $this->conn->stockupdate_use;
        $collectionProject = $this->conn->project_details;
        
        $projectData = $collectionProject->findOne(array("_id" => new MongoDB\BSON\ObjectID($jsonArrayData["project_id"]['$oid'])));

        if($jsonArrayData['status']=='use'){
            $totalQty = $jsonArrayData['total_use_qty'];
            $type = 'usage';
        }
        elseif($jsonArrayData['status']=='break'){
            $totalQty = $jsonArrayData['total_break_qty'];
            $type = 'breakage';
        }
		     elseif($jsonArrayData['status']=='balance'){
            $totalQty = $jsonArrayData['total_balance_qty'];
            $type = 'balance';
        }
        else{
            $totalQty = $jsonArrayData['total_transfer_qty'];
            $type = 'transfer';
        }
		
        $usedQty = $totalQty;
        
        $count = count($jsonArrayData['inventory_id']);
      
        
		
		
		/** Balance Qty **/
		
		if($jsonArrayData['status']=='balance')
		{
		for($i=0; $i<$count; $i++)
        {
            $id = $jsonArrayData['inventory_id'][$i]['$oid'];
            $inventoryData = $collection->findOne(array("_id" => new MongoDB\BSON\ObjectID($id)));
            
            if($totalQty > 0)
            {
                if($totalQty >= $inventoryData['available'])
                {                  
                  $usage = $inventoryData['usage'] - $inventoryData['available'];
             $available = 0;                    
                  $totalQty = $totalQty + $inventoryData['available'];
                  $usageBackup = $inventoryData['available'];
                }
                else
                {
                    $usageBackup = $totalQty;
                    if($totalQty > 0)
                    { 
						$usage = $inventoryData['usage']- $totalQty;					
                        $available = $inventoryData['available'] + $totalQty;
                        $totalQty = 0;
                    }
                }
                
                if($usageBackup > 0)
                {
                   if($jsonArrayData['status']=='balance'){
                        $collection->updateOne(
                            array("_id" => new MongoDB\BSON\ObjectID($id)),
                            array('$set' => array( "usage" => $usage, "available" => $available ))
                        );
                    }
                    
                    $poData['inventory_id'] = $id;
                    $poData['po_number'] = $inventoryData["po_number"];
                    $poData['balance_qty'] = $usageBackup;
                    array_push($usageData, $poData);
                }
            }
        }
		
	}
	else
	{
		  for($i=0; $i<$count; $i++)
        {
            $id = $jsonArrayData['inventory_id'][$i]['$oid'];
            $inventoryData = $collection->findOne(array("_id" => new MongoDB\BSON\ObjectID($id)));
            
            if($totalQty > 0 && $inventoryData['available'] > 0)
            {
                if($totalQty >= $inventoryData['available'])
                {
                    if($jsonArrayData['status']=='use'){
                        $usage = $inventoryData['usage'] + $inventoryData['available'];
                    }
                    elseif($jsonArrayData['status']=='break'){
                        $breakage = $inventoryData['breakage'] + $inventoryData['available'];
                    }
                    elseif($jsonArrayData['status']=='transfer'){
                        $transfer = $inventoryData['transfer'] + $inventoryData['available'];
                    }
                    $available = 0;
                    
                    $totalQty = $totalQty - $inventoryData['available'];
                    $usageBackup = $inventoryData['available'];
                }
                else
                {
                    $usageBackup = $totalQty;
                    if($totalQty > 0)
                    {
                        if($jsonArrayData['status']=='use'){
                            $usage = $inventoryData['usage'] + $totalQty;
                        }
                        elseif($jsonArrayData['status']=='break'){
                            $breakage = $inventoryData['breakage'] + $totalQty;
                        }
                        elseif($jsonArrayData['status']=='transfer'){
                            $transfer = $inventoryData['transfer'] + $totalQty;
                        }
                        $available = $inventoryData['available'] - $totalQty;
                        $totalQty = 0;
                    }
                }
                
                if($usageBackup > 0)
                {
                    if($jsonArrayData['status']=='use'){
                        $collection->updateOne(
                            array("_id" => new MongoDB\BSON\ObjectID($id)),
                            array('$set' => array( "usage" => $usage, "available" => $available ))
                        );
                    }
                    elseif($jsonArrayData['status']=='transfer'){
                        $transferProArray = array();
                        if($inventoryData['transfer_site']) {
                            $siteCount=count($inventoryData['transfer_site']);
                            for($i=0; $i<$siteCount; $i++)
                            {
                                $existProData['project_name']=$inventoryData['transfer_site'][$i]['project_name'];
                                $existProData['block_name']=$inventoryData['transfer_site'][$i]['block_name'];
                                $existProData['quantity']=$inventoryData['transfer_site'][$i]['quantity'];
                                array_push($transferProArray, $existProData);
                            }
                        }
                        $sendProData['project_name']=$jsonArrayData['project_name'];
                        $sendProData['block_name']=$jsonArrayData['block_name'];
                        $sendProData['quantity']=$usageBackup;
                        array_push($transferProArray, $sendProData);
                        
                        $collection->updateOne(
                            array("_id" => new MongoDB\BSON\ObjectID($id)),
                            array('$set' => array(
                                "transfer_site" => $transferProArray, "transfer" => $transfer, "available" => $available
                            ))
                        );
                    }
                    elseif($jsonArrayData['status']=='break'){
                        $collection->updateOne(
                            array("_id" => new MongoDB\BSON\ObjectID($id)),
                            array('$set' => array( "breakage" => $breakage, "available" => $available ))
                        );
                    }
                    
                    $poData['inventory_id'] = $id;
                    $poData['po_number'] = $inventoryData["po_number"];
                    $poData['used_qty'] = $usageBackup;
                    array_push($usageData, $poData);
                }
            }
        }
	}
		
        $jsonFlatData = json_decode($jsonArrayData["jsonArrayData"], true);
        for($j=0; $j < count($jsonFlatData); $j++)
        {
            $product['flat_no'] = $jsonFlatData[$j]['flat_no'];
            $product['contractor'] = $jsonFlatData[$j]['contractor'];
            $product['quantity'] = $jsonFlatData[$j]['quantity'];
            $product['reason'] = $jsonFlatData[$j]['reason'];
            array_push($flatUsage, $product);
        }
        
        $cursorStock = $collectionStockUse->aggregate(array(
            array( '$match' => array(
                "project_id" => new MongoDB\BSON\ObjectID($projectData["_id"]),
                "code" => $inventoryData["code"],
                "item" => $inventoryData["item"]
            )),
            array( '$sort' => array( 
                '_id' => -1
            )),
            array( '$limit' => 1 )
        ));
        if($cursorStock) {
            foreach($cursorStock as $rowStock) {
                $balQuantity = $rowStock['balance_qty'];
            }
        }
        if($balQuantity) { 
			
			if($jsonArrayData['status'] == 'balance')
			{
			$balanceQty = $balQuantity+$usedQty;	
			}
			else
			{
				$balanceQty = $balQuantity-$usedQty;
			}
			 }
        else { $balanceQty = $usedQty; }
        
        if($type=='transfer')
        {
            //transfer in project
            $transferProjectData = $collectionProject->findOne(array("project_name" => $jsonArrayData['project_name'], "block_name" => $jsonArrayData['block_name']));
			
            //inventory
            $cursorNew = $collection->insertOne(array(
                "type" => $inventoryData["type"],
                "category" => $inventoryData["category"],
                "code" => $inventoryData["code"],
                "item" => $inventoryData["item"],
                "unit" => $inventoryData["unit"],
                "project_id" => $transferProjectData["_id"],
                "date_time" => date("Y-m-d H:i:s"),
                "cancel_status" => "0",
                "completed_status" => "1",
                "mail_status" => "1",
                "project_short" => $transferProjectData["project_short"],
                "stock" => (float)$usedQty,
                "stock_percentage" => 100.0,
                "available" => (float)$usedQty
            ));
            $lastInsertId = $cursorNew->getInsertedId();
            
            //stockupdate in
            $collectionStockIn->insertOne(array(
                "type" => 'transfer',
                "transfer_from" => $projectData["_id"],
                "inventory_id" => $lastInsertId,
                "emp_id" => (int)$jsonArrayData["emp_id"],
                "date_time" => date("Y-m-d H:i:s"),
                "transfer_qty" => (float)$usedQty,
                "stock" => (float)$usedQty
            ));
            
            //stockupdate use for transfer_in
            $cursorTransSite = $collectionStockUse->aggregate(array(
                array( '$match' => array(
                    "project_id" => new MongoDB\BSON\ObjectID($transferProjectData["_id"]),
                    "code" => $inventoryData["code"],
                    "item" => $inventoryData["item"]
                )),
                array( '$sort' => array( 
                    '_id' => -1
                )),
                array( '$limit' => 1 )
            ));
            if($cursorTransSite) {
                foreach($cursorTransSite as $rowTransSite) {
                    $balTransQuantity = $rowTransSite['balance_qty'];
                }
            }
            
            $collectionStockUse->insertOne(array(
                "emp_id" => (int)$jsonArrayData["emp_id"],
                "project_id" => $transferProjectData["_id"],
                "code" => $inventoryData["code"],
                "item" => $inventoryData["item"],
                "usage_details" => $projectData['project_name'].'<br>'.$projectData['block_name'].' Block',
                "total_qty" => $usedQty,
                "balance_qty" => $balTransQuantity+$usedQty,
                "date_time" => date("Y-m-d H:i:s"),
                "type" => 'transfer in'
            ));
            
            //stockupdate use for transfer_out
            $collectionStockUse->insertOne(array(
                "emp_id" => (int)$jsonArrayData["emp_id"],
                "project_id" => $projectData["_id"],
                "code" => $inventoryData["code"],
                "item" => $inventoryData["item"],
                "usage_details" => $jsonArrayData['project_name'].'<br>'.$jsonArrayData['block_name'].' Block',
                "total_qty" => $usedQty,
                "balance_qty" => $balanceQty,
                "date_time" => date("Y-m-d H:i:s"),
                "type" => 'transfer out'
            ));
        }
        else {
            $collectionStockUse->insertOne(array(
                "emp_id" => (int)$jsonArrayData["emp_id"],
                "project_id" => $projectData["_id"],
                "code" => $inventoryData["code"],
                "item" => $inventoryData["item"],
                "po_details" => $usageData,
                "usage_details" => $flatUsage,
                "total_qty" => $usedQty,
                "balance_qty" => $balanceQty,
                "date_time" => date("Y-m-d H:i:s"),
                "type" => $type
            ));
        }
        
        $response["code"] = 'Success';
        return $response;
    }

    
/***** USER LIST *****/
    
    public function userlistfilter()
    {
        $response=array();
        $response["userfilter"]=array();
        
        $collectionDprt = $this->conn->userdepartment;
        
        $cursorDprt = $collectionDprt->find();
        if($cursorDprt)
        {
            $dprtData = array();
            foreach($cursorDprt as $document)
            {
                if($document['department']!='' && $document['site']!='' && $document['status']!='')
                {
                    $dprtData['status'] = $document['status'];
                    $dprtData['site'] = $document['site'];
                    $dprtData['department'] = $document['department'];
                    
                    array_push($response["userfilter"], $dprtData);
                }
            }
        }
        return $response;
    }
    
    public function userlist($getData)
    {
        $jsonData = json_decode($getData, true);
        
        $collection = $this->conn->user_list;
        $response = array();
        $response['user_list'] = array();
        
        if($jsonData['status']!='All' && $jsonData['site']!='All' && $jsonData['department']!='All'){
            $cursor = $collection->aggregate(array(
                array( '$match' => array(
                    "status" => $jsonData['status'],
                    "site" =>  $jsonData['site'],
                    "department" => $jsonData['department']
                )),
                array( '$lookup' => array(
                    'from' => 'salary_history',
                    'localField' => 'emp_id',
                    'foreignField' => 'emp_id',
                    'as' => 'history_data'
                )),
                array( '$sort' => array( 
                    'timestamp' => 1
                ))
            ));
        }
        else if($jsonData['status'] =="All" && $jsonData['site'] =='All' && $jsonData['department']!='All'){
            $cursor = $collection->aggregate(array(
                array( '$match' => array(
                    "department" => $jsonData['department']
                )),
                array( '$lookup' => array(
                    'from' => 'salary_history',
                    'localField' => 'emp_id',
                    'foreignField' => 'emp_id',
                    'as' => 'history_data'
                )),
                array( '$sort' => array( 
                    'timestamp' => 1
                ))
            ));
        }
        else if($jsonData['status'] =="All" && $jsonData['site']!='All' && $jsonData['department'] =='All'){
           $cursor = $collection->aggregate(array(
                array( '$match' => array(
                   "site" =>  $jsonData['site']
                )),
                array( '$lookup' => array(
                    'from' => 'salary_history',
                    'localField' => 'emp_id',
                    'foreignField' => 'emp_id',
                    'as' => 'history_data'
                )),
                array( '$sort' => array( 
                    'timestamp' => 1
                ))
            ));
        }
        else if($jsonData['status'] =="All" && $jsonData['site']!='All' && $jsonData['department']!='All'){
           $cursor = $collection->aggregate(array(
                array( '$match' => array(
                    "site" =>  $jsonData['site'],
                    "department" => $jsonData['department']
                )),
                array( '$lookup' => array(
                    'from' => 'salary_history',
                    'localField' => 'emp_id',
                    'foreignField' => 'emp_id',
                    'as' => 'history_data'
                )),
                array( '$sort' => array( 
                    'timestamp' => 1
                ))
            ));
        }
        else if($jsonData['status'] =="All" && $jsonData['site'] =='All' && $jsonData['department'] =='All'){
           $cursor = $collection->aggregate(array(
                array( '$lookup' => array(
                    'from' => 'salary_history',
                    'localField' => 'emp_id',
                    'foreignField' => 'emp_id',
                    'as' => 'history_data'
                )),
                array( '$sort' => array( 
                    'timestamp' => 1
                ))
            ));
        }
        else if($jsonData['status'] =='Active' && $jsonData['site']!='All' && $jsonData['department']=='All'){
           $cursor = $collection->aggregate(array(
                array( '$match' => array(
                    "status" => $jsonData['status'],
                    "site" =>  $jsonData['site']
                )),
                array( '$lookup' => array(
                    'from' => 'salary_history',
                    'localField' => 'emp_id',
                    'foreignField' => 'emp_id',
                    'as' => 'history_data'
                )),
                array( '$sort' => array( 
                    'timestamp' => 1
                ))
            ));
        }
        else if($jsonData['status'] =='Active' && $jsonData['site'] =='All' && $jsonData['department']!='All'){
           $cursor = $collection->aggregate(array(
                array( '$match' => array(
                    "status" => $jsonData['status'],
                    "department" => $jsonData['department']
                )),
                array( '$lookup' => array(
                    'from' => 'salary_history',
                    'localField' => 'emp_id',
                    'foreignField' => 'emp_id',
                    'as' => 'history_data'
                )),
                array( '$sort' => array( 
                    'timestamp' => 1
                ))
            ));
        } 
        else if($jsonData['status']!='Active' && $jsonData['site'] =='All' && $jsonData['department']!='All'){
            $cursor = $collection->aggregate(array(
                array( '$match' => array(
                    "status" => $jsonData['status'],
                    "department" => $jsonData['department']
                )),
                array( '$lookup' => array(
                    'from' => 'salary_history',
                    'localField' => 'emp_id',
                    'foreignField' => 'emp_id',
                    'as' => 'history_data'
                )),
                array( '$sort' => array( 
                    'timestamp' => 1
                ))
            ));
         }
        else if($jsonData['status']!='Active' && $jsonData['site']!='All' && $jsonData['department'] =='All'){
             $cursor = $collection->aggregate(array(
                array( '$match' => array(
                    "status" => $jsonData['status'],
                    "site" =>  $jsonData['site']
                )),
                array( '$lookup' => array(
                    'from' => 'salary_history',
                    'localField' => 'emp_id',
                    'foreignField' => 'emp_id',
                    'as' => 'history_data'
                )),
                array( '$sort' => array( 
                    'timestamp' => 1
                ))
            ));
        }   
        else if($jsonData['status']!='Active' && $jsonData['site'] =='All' && $jsonData['department']!='All'){
            $cursor = $collection->aggregate(array(
                array( '$match' => array(
                    "site" =>  $jsonData['site']
                )),
                array( '$lookup' => array(
                    'from' => 'salary_history',
                    'localField' => 'emp_id',
                    'foreignField' => 'emp_id',
                    'as' => 'history_data'
                )),
                array( '$sort' => array( 
                    'timestamp' => 1
                ))
            ));
        }   
        else if($jsonData['status']!='Active' && $jsonData['site'] =='All' && $jsonData['department'] =='All'){
            $cursor = $collection->aggregate(array(
                array( '$match' => array(
                    "status" => $jsonData['status']
                )),
                array( '$lookup' => array(
                    'from' => 'salary_history',
                    'localField' => 'emp_id',
                    'foreignField' => 'emp_id',
                    'as' => 'history_data'
                )),
                array( '$sort' => array( 
                    'timestamp' => 1
                ))
            ));
        }
        else if($jsonData['status'] =='Active' && $jsonData['site'] =='All' && $jsonData['department'] =='All'){
            $cursor = $collection->aggregate(array(
                array( '$match' => array(
                    "status" => $jsonData['status']
                )),
                array( '$lookup' => array(
                    'from' => 'salary_history',
                    'localField' => 'emp_id',
                    'foreignField' => 'emp_id',
                    'as' => 'history_data'
                )),
                array( '$sort' => array( 
                    'timestamp' => 1
                ))
            ));
        }
        else{
             $cursor =  $collection->aggregate(array(
                array( '$match' => array(
                    "status" => "Active"
                )),
                array( '$lookup' => array(
                    'from' => 'salary_history',
                    'localField' => 'emp_id',
                    'foreignField' => 'emp_id',
                    'as' => 'history_data'
                )),
                array( '$sort' => array( 
                    'timestamp' => 1
                ))
            ));
         }
         
        if($cursor) { 
            foreach($cursor as $rowData)
            {
                if($rowData['history_data'][0]['emp_id']) { $sendData['pdf_status'] = 1; }
                else { $sendData['pdf_status'] = 0; }
                if(!$rowData['email']) { $rowData['email']='-'; }
                $sendData['emp_id']=$rowData['emp_id'];
                $sendData['name']=$rowData['sirname'].' '.$rowData['firstname'].' '.$rowData['lastname'];
                $sendData['site']=$rowData['site'];
                $sendData['designation']=$rowData['designation'];
                $sendData['department']=$rowData['department'];
                $sendData['email']=$rowData['email'];
                $sendData['officeno']=$rowData['officeno'];
                $sendData['status']=$rowData['status'];
                
                array_push($response["user_list"], $sendData);
            }
            $response["code"] = "Success";
        } 
        else {                     
            $response["code"] = "failed";   
        }
        return $response;
    }
    
    public function adduserdetails()
    {
        $response=array();
        $collection = $this->conn->user_list;
        $collectionDprt = $this->conn->sis_department;
        $collectionProject = $this->conn->project_details;
        
        //emp id
        $cursor = $collection ->aggregate(array(
            array( '$sort' => array( 
                'emp_id' => -1
            )),
            array('$limit' => 1)
        ));
        if($cursor)
        {
            foreach($cursor as $rowInvData)
            {
                $empId = $rowInvData['emp_id']+1;
            }
            if(!$empId){ $empId = 1001; }
            $response['emp_id'] = $empId;
        }

        //department
        $response["dprt_list"] = array();
        $cursorDprt = $collectionDprt->find();
        if($cursorDprt) 
        { 
            foreach($cursorDprt as $row)
            {
                $dprtData['department'] = $row['department'];
                $dprtData['designation'] = $row['designation'];
                array_push($response["dprt_list"], $dprtData);
            }
        }
        
        //project
        $response["project_list"] = array();
        $cursorProject = $collectionProject->aggregate(array(
            array( '$group' => array('_id' => array('project_name' => '$project_name')))
        ));
        if($cursorProject) 
        { 
            foreach($cursorProject as $rowData)
            {
                $proData['project_name'] = $rowData['_id']['project_name'];
                array_push($response["project_list"], $proData);
            }
        }
        return $response;
    }
    
    public function userdetails($getData)
    {
        $collection = $this->conn->user_list;
        $collectionDprt = $this->conn->sis_department;
        $collectionProject = $this->conn->project_details;
        $collectionDoc = $this->conn->user_documents;
        
        $response = array();
        $response['doc_list'] = array();
        $response['project_list'] = array();
        $response['dprt_list'] = array();
        $response['image_list'] = array();
        $jsonData = json_decode($getData, true);
        
        //document list
        $cursorDoc = $collectionDoc->find();
        foreach($cursorDoc as $rowDoc)
        {
            $docData['name'] = $rowDoc['name'];
            array_push($response['doc_list'], $docData);
        }
        
        //image list
        $dir = '../modules/uploads/documents/'.$jsonData['emp_id'];
        if($handle = opendir($dir))
        {
            while(false !== ($file = readdir($handle)))
            {
                if($file != "." && $file != "..")
                {
                    $arrayData = explode('.', $file);
                    $product['caption'] = $arrayData[0];
                    $product['url'] = $this->path.'/modules/uploads/documents/'.$jsonData['emp_id'].'/'.$file;
                    array_push($response['image_list'], $product);
                }
            }
            closedir($handle);
        }
        
        //project list
        $cursorProject = $collectionProject->find();
        foreach($cursorProject as $rowProject)
        {
            $proData['project_name'] = $rowProject['project_name'];
            $proData['block_name'] = $rowProject['block_name'];
            array_push($response['project_list'], $proData);
        }
        
        //department list
        $cursorDprt = $collectionDprt->find();
        foreach($cursorDprt as $rowDprt)
        {
            array_push($response['dprt_list'], $rowDprt);
        }
         
        //user details
        $cursor = $collection->aggregate(array(
            array('$match' => array(
                "emp_id" => (int)$jsonData['emp_id']
            )),
            array('$lookup' => array(
                'from' => 'signintable',
                'localField' => 'emp_id',
                'foreignField' => 'emp_id',
                'as' => 'signin_data'
            )),
            array('$lookup' => array(
                'from' => 'salary_list',
                'localField' => 'emp_id',
                'foreignField' => 'emp_id',
                'as' => 'salary_data'
            )),
            array('$lookup' => array(
                'from' => 'transfer_history',
                'localField' => 'emp_id',
                'foreignField' => 'emp_id',
                'as' => 'transfer_data'
            ))
        ));
        if($cursor) 
        { 
            foreach($cursor as $rowData)
            {
                //experience
                if($rowData['resigned_date']!='') { $expDate = $rowData['resigned_date']; }
                else { $expDate = date('Y-m-d'); }
                $diff = strtotime($expDate) - strtotime($rowData['doj']);
                $years = floor($diff / (365*60*60*24));
                $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
                $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
                if($years > 0) {
                    $exp = $years.' years '.$months.' months';
                }
                else {
                    $exp = $months.' months';
                }
                $expData = date("F", strtotime($rowData['doj'])).' '.date("Y", strtotime($rowData['doj'])).' - '.date("F Y");
                
                $sendData['age'] = (date('Y') - date('Y',strtotime($rowData['dob'])));
                $sendData['exp_duration']=$exp;
                $sendData['exp_date']=$expData;
                $sendData['gross']=$rowData['salary_data'][0]['gross'];
                $sendData['emp_id']=$rowData['emp_id'];
                $sendData['firstname']=$rowData['firstname'];
                $sendData['lastname']=$rowData['lastname'];
                $sendData['department']=$rowData['department'];
                $sendData['designation']=$rowData['designation'];
                $sendData['site']=$rowData['site'];
                $sendData['bloodgroup']=$rowData['bloodgroup'];
                $sendData['dob']=$rowData['dob'];
                $sendData['doj']=$rowData['doj'];
                $sendData['address']=$rowData['address'];
                $sendData['personnel_email']=$rowData['personnel_email'];
                $sendData['email']=$rowData['email'];
                $sendData['officeno']=$rowData['officeno'];
                $sendData['emergency_conno']=$rowData['emergency_conno'];
                $sendData['qualification']=$rowData['qualification'];
                $sendData['profileimage']=$rowData['profileimage'];
                $sendData['status']=$rowData['status'];
                $sendData['resigned_date']=$rowData['resigned_date'];
                $sendData['password']=$rowData['signin_data'][0]['password'];
                
                $response["user_data"] = $sendData;
                $response["transfer_data"] = $rowData['transfer_data'];
            }
            $response["code"] = "Success";  
        } 
        else {                     
            $response["code"] = "failed";
        }
        return $response;
    }
    
    public function userdelete($getData)
    {
        $collectionUser = $this->conn->user_list;
        $collectionSalary = $this->conn->salary_list;
        $collectionHistory = $this->conn->salary_history;
        $collectionLeave = $this->conn->userleave_details;
        $collectionLog = $this->conn->signintable;
        $collectionPerm = $this->conn->permissiontable;
        $collectionTransfer = $this->conn->transfer_history;
        $response = array();
        
        $jsonData = json_decode($getData, true);
        
        $userData = $collectionUser->findOne(array('emp_id' => (int)$jsonData['emp_id']));
        if($userData) {
            unlink($userData['profilepath']);
        }
        
        $collectionUser->deleteMany(array('emp_id' => (int)$jsonData['emp_id']));
        $collectionSalary->deleteMany(array('emp_id' => (int)$jsonData['emp_id']));
        $collectionHistory->deleteMany(array('emp_id' => (int)$jsonData['emp_id']));
        $collectionLeave->deleteMany(array('emp_id' => (int)$jsonData['emp_id']));
        $collectionPerm->deleteMany(array('emp_id' => (int)$jsonData['emp_id']));
        $collectionTransfer->deleteMany(array('emp_id' => (int)$jsonData['emp_id']));
        $cursorLog = $collectionLog->deleteMany(array('emp_id' => (int)$jsonData['emp_id']));
        
        if($cursorLog) { $response["code"] = "Success"; } 
        else { $response["code"] = "failed"; }
        return $response;
    }
    
    public function deleteallusers($getData)
    {
        $collectionUser = $this->conn->user_list;
        $collectionSalary = $this->conn->salary_list;
        $collectionHistory = $this->conn->salary_history;
        $collectionLeave = $this->conn->userleave_details;
        $collectionLog = $this->conn->signintable;
        $collectionPerm = $this->conn->permissiontable;
        $collectionTransfer = $this->conn->transfer_history;
        $response = array();
        
        $jsonData = json_decode($getData, true);
        $jsonArrayData = json_decode($jsonData["jsonArrayData"], true);
        $listCount = count($jsonArrayData);
        for($i=0; $i<$listCount; $i++)
        {
            $userData = $collectionUser->findOne(array('emp_id' => (int)$jsonArrayData[$i]['emp_id']));
            if($userData) {
                unlink($userData['profilepath']);
            }
            
            $collectionUser->deleteMany(array('emp_id' => (int)$jsonArrayData[$i]['emp_id']));
            $collectionSalary->deleteMany(array('emp_id' => (int)$jsonArrayData[$i]['emp_id']));
            $collectionHistory->deleteMany(array('emp_id' => (int)$jsonArrayData[$i]['emp_id']));
            $collectionLeave->deleteMany(array('emp_id' => (int)$jsonArrayData[$i]['emp_id']));
            $collectionLog->deleteMany(array('emp_id' => (int)$jsonArrayData[$i]['emp_id']));
            $collectionPerm->deleteMany(array('emp_id' => (int)$jsonArrayData[$i]['emp_id']));
            $collectionTransfer->deleteMany(array('emp_id' => (int)$jsonArrayData[$i]['emp_id']));
        }
        $response["code"] = "Success";
        return $response;
    }
    
    public function leaveyear($getData)
    {
        $jsonData = json_decode($getData, true);
        $collection = $this->conn->userleave_details;
        $response = array();
        $response["leave_data"] = array();
        
        $cursor = $collection->find(array("emp_id" => (int)$jsonData['emp_id'], "duration" => $jsonData['year']));
        if($cursor) 
        {
            foreach($cursor as $rowData)
            {
                $sendData['leave_from'] = $rowData['leave_from'];
                $sendData['leave_to'] = $rowData['leave_to'];
                $sendData['leave'] = $rowData['leave'];
                
                $leaveTaken += $rowData['leave'];
                $sendData['totalleave'] = $leaveTaken;
                
                array_push($response["leave_data"] , $sendData);
            }
        }
        return $response;
    }
    
    public function leaveinsert($getData)
    {
        $jsonData = json_decode($getData, true);
        
        $collectionLeave = $this->conn->userleave_details;
        
        //financial year
        if(date('m') < 4) { 
            $finStartYear = date('Y')-1;
            $finEndYear = date('Y');
        }
        else { 
            $finStartYear = date('Y');
            $finEndYear = date('Y')+1;
        }
        $finDuration = $finStartYear.' - '.$finEndYear;
        
        $cursor = $collectionLeave->insertOne(array(
            "emp_id" => (int)$jsonData['emp_id'],
            "leave_from"=> $jsonData['leavefrom'], 
            "leave_to"=> $jsonData['leaveto'],
            "leave" => (float)$jsonData['leave'],
            "reason"=> $jsonData['reason'],
            "year_from"=> (string)$finStartYear,
            "year_to"=> (string)$finEndYear,
            "duration" => $finDuration,
            "date_time" => date('Y-m-d H:i:s')
        ));
        
        if($cursor) { $response["code"] = "Success"; } 
        else { $response["code"] = "failed"; }
        return $response;
    }
    
    public function usertransfer($getData)
    {
        $response = array();
        $jsonData = json_decode($getData, true);
        $collectionTransfer = $this->conn->transfer_history;
        $collectionUser = $this->conn->user_list;
        $collectionSign = $this->conn->signintable;
        
        $cursor = $collectionTransfer->aggregate(array(
            array( '$match' => array(
                "emp_id" => (int)$jsonData['emp_id']
            )),
            array( '$sort' => array( 
                '_id' => -1
            )),
            array( '$limit' => 1 )
        ));
        foreach($cursor as $rowData)
        {
            if($rowData['transfer_site']!=$jsonData['site'] || $rowData['block']!=$jsonData['project_block'])
            {
                //update
                $diff = strtotime(date('Y-m-d')) - strtotime($rowData['transfer_date']);
                $years = floor($diff / (365*60*60*24));
                $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
                $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
                if($years > 0) { $workPeriod = $years.' years '.$months.' months'; }
                elseif($months > 0) { $workPeriod = $months.' months'; }
                else { $workPeriod = ''; }
                
                $collectionTransfer->updateOne(
                    array("_id" => new MongoDB\BSON\ObjectID($rowData['_id'].$oid)),
                    array('$set' => array(
                        "transfer_duration" => $workPeriod
                    ))
                );
                
                $collectionTransfer->insertOne(array(
                    "emp_id" => (int)$jsonData['emp_id'], 
                    "transfer_date" => date("d-m-Y"),
                    "transfer_dateformat" => date("d F Y"),
                    "transfer_site" => $jsonData['site'],
                    "block"=> $jsonData['project_block'],
                    "transfer_duration" => ''
                ));

                $collectionUser->updateOne(
                    array("emp_id" => (int)$jsonData['emp_id']),
                    array('$set' => array(
                        "site" => $jsonData['site']
                    ))
                );

                $collectionSign->updateOne(
                    array("emp_id" => (int)$jsonData['emp_id']),
                    array('$set' => array(
                        "site" => $jsonData['site']
                    ))
                );

                $response["code"] = "Success";
            }
            else {
                $response["code"] = "Transfer details already exist !";
            }
        }
        return $response;
    }
    
    public function permissiondetails($getData)
    {
        $response = array();
        $response['project_list'] = array();
        $jsonData = json_decode($getData, true);
        
        $collectionProject = $this->conn->project_details;
        $collectionPerm = $this->conn->permissiontable;
        $collectionSign = $this->conn->signintable;
        
        //user data
        $userData = $collectionSign->findOne(array('emp_id' => (int)$jsonData['emp_id']));
        $response['name'] = $userData['name'];
        $response['department'] = $userData['department'];
        
        //permission data
        $permData = $collectionPerm->findOne(array('emp_id' => (int)$jsonData['emp_id']));
        if($permData['site_list']=='All') { $sendData['checkedAll']=true; }
        $count = count($permData['permission']);
        for($i=0; $i<$count; $i++)
        {
            //project
            if($permData['permission'][$i]['permission_type']=='project')
            {
                $sendData['project'] = true;
                $sendData['project_add'] = $permData['permission'][$i]['add'];
                $sendData['project_edit'] = $permData['permission'][$i]['edit'];
                $sendData['project_delete'] = $permData['permission'][$i]['delete'];
            }
            
            //indent
            if($permData['permission'][$i]['permission_type']=='indent')
            {
                $sendData['materials'] = true;
                $sendData['indent'] = true;
                $sendData['indent_add'] = $permData['permission'][$i]['add'];
                $sendData['indent_edit'] = $permData['permission'][$i]['edit'];
                $sendData['indent_delete'] = $permData['permission'][$i]['delete'];
                $sendData['indent_mail'] = $permData['permission'][$i]['mail'];
                $sendData['indent_quot'] = $permData['permission'][$i]['quotation'];
            }
            
            //quotation
            if($permData['permission'][$i]['permission_type']=='quotation')
            {
                $sendData['materials'] = true;
                $sendData['quotation'] = true;
                $sendData['quot_add'] = $permData['permission'][$i]['add'];
                $sendData['quot_edit'] = $permData['permission'][$i]['edit'];
                $sendData['quot_delete'] = $permData['permission'][$i]['delete'];
                $sendData['quot_mail'] = $permData['permission'][$i]['mail'];
                $sendData['quot_approve'] = $permData['permission'][$i]['approve'];
            }
            
            //purchase order
            if($permData['permission'][$i]['permission_type']=='order')
            {
                $sendData['materials'] = true;
                $sendData['purchase'] = true;
                $sendData['purchase_add'] = $permData['permission'][$i]['add'];
                $sendData['purchase_edit'] = $permData['permission'][$i]['edit'];
                $sendData['purchase_delete'] = $permData['permission'][$i]['delete'];
                $sendData['purchase_mail'] = $permData['permission'][$i]['mail'];
            }
            
            //inventory
            if($permData['permission'][$i]['permission_type']=='inventory')
            {
                $sendData['materials'] = true;
                $sendData['inventory'] = true;
                $sendData['inv_edit'] = $permData['permission'][$i]['edit'];
            }
            
            //inventory use
            if($permData['permission'][$i]['permission_type']=='inventory_use')
            {
                $sendData['materials'] = true;
                $sendData['inventory_use'] = true;
                $sendData['invuse_edit'] = $permData['permission'][$i]['edit'];
            }
            
            //product
            if($permData['permission'][$i]['permission_type']=='product')
            {
                $sendData['materials'] = true;
                $sendData['product'] = true;
                $sendData['product_add'] = $permData['permission'][$i]['add'];
                $sendData['product_edit'] = $permData['permission'][$i]['edit'];
                $sendData['product_delete'] = $permData['permission'][$i]['delete'];
            }
            
            //vendor
            if($permData['permission'][$i]['permission_type']=='vendor')
            {
                $sendData['materials'] = true;
                $sendData['vendor'] = true;
                $sendData['vendor_add'] = $permData['permission'][$i]['add'];
                $sendData['vendor_edit'] = $permData['permission'][$i]['edit'];
                $sendData['vendor_delete'] = $permData['permission'][$i]['delete'];
            }
            
            //user list
            if($permData['permission'][$i]['permission_type']=='userlist')
            {
                $sendData['userlist'] = true;
                $sendData['user_add'] = $permData['permission'][$i]['add'];
                $sendData['user_edit'] = $permData['permission'][$i]['edit'];
                $sendData['user_delete'] = $permData['permission'][$i]['delete'];
                $sendData['user_leave'] = $permData['permission'][$i]['leave'];
            }
            
            //accounts
            if($permData['permission'][$i]['permission_type']=='accounts')
            {
                $sendData['accounts'] = true;
                $sendData['accounts_edit'] = $permData['permission'][$i]['edit'];
            }

            //billing
            if($permData['permission'][$i]['permission_type']=='billing')
            {
                $sendData['materials'] = true;
                $sendData['billing'] = true;
                $sendData['billing_add'] = $permData['permission'][$i]['add'];
                $sendData['billing_delete'] = $permData['permission'][$i]['delete'];
                $sendData['billing_approve'] = $permData['permission'][$i]['approve'];
            }
        }
        $response['permission_data'] = $sendData;
        
        //project list
        $cursorData = $collectionProject->aggregate(array(
            array( '$group' => array(
                '_id' => array(
                    'project_name' => '$project_name',
                    'project_short' => '$project_short'
                )
            ))
        ));
        if($cursorData) {
            foreach($cursorData as $rowData)
            {
                if($permData['site_list']=='All') {
                    $sendData['singlecheck'] = true;
                    $sendData['project_name'] = $rowData['_id']['project_name'];
                    $sendData['project_short'] = $rowData['_id']['project_short'];
                    array_push($response['project_list'], $sendData);
                }
                else {
                    $projectCount = count($permData['site_list']);
                    $sendData['singlecheck'] = false;
                    for($j=0; $j<$projectCount; $j++)
                    {
                        if($rowData['_id']['project_name']==$permData['site_list'][$j]['project_name']) {
                            $sendData['singlecheck'] = true;
                        }
                    }
                    $sendData['project_name'] = $rowData['_id']['project_name'];
                    $sendData['project_short'] = $rowData['_id']['project_short'];
                    array_push($response['project_list'], $sendData);
                }
            }
        }
            
        return $response;
    }
    
    public function permissionupdate($getData)
    {
        $response = array();
        $permission = array();
        $jsonData = json_decode($getData, true);
        
        $collection = $this->conn->permissiontable;
        
        //sis projects
        if($jsonData['checkedAll']) {
            $siteList = 'All';
        }
        else {
            $siteList = array();
            $jsonArrayData = json_decode($jsonData['jsonArrayData'], true);
            $count = count($jsonArrayData);
            for($i=0; $i<$count; $i++)
            {
                $prolistArray['project_name'] = $jsonArrayData[$i]['project_name'];
                $prolistArray['project_short'] = $jsonArrayData[$i]['project_short'];
                array_push($siteList, $prolistArray);
            }
        }
        
        //project
        if($jsonData['project']) {
            $projectArray['permission_type'] = 'project';
            $projectArray['add'] = $jsonData['project_add'];
            $projectArray['edit'] = $jsonData['project_edit'];
            $projectArray['delete'] = $jsonData['project_delete'];
            array_push($permission, $projectArray);
        }
        
        //indent
        if($jsonData['indent']) {
            $indentArray['permission_type'] = 'indent';
            $indentArray['add'] = $jsonData['indent_add'];
            $indentArray['edit'] = $jsonData['indent_edit'];
            $indentArray['delete'] = $jsonData['indent_delete'];
            $indentArray['mail'] = $jsonData['indent_mail'];
            $indentArray['quotation'] = $jsonData['indent_quot'];
            array_push($permission, $indentArray);
        }
        
        //quotation
        if($jsonData['quotation']) {
            $quotArray['permission_type'] = 'quotation';
            $quotArray['add'] = $jsonData['quot_add'];
            $quotArray['edit'] = $jsonData['quot_edit'];
            $quotArray['delete'] = $jsonData['quot_delete'];
            $quotArray['mail'] = $jsonData['quot_mail'];
            $quotArray['approve'] = $jsonData['quot_approve'];
            array_push($permission, $quotArray);
        }
        
        //purchase order
        if($jsonData['purchase']) {
            $purchaseArray['permission_type'] = 'order';
            $purchaseArray['add'] = $jsonData['purchase_add'];
            $purchaseArray['edit'] = $jsonData['purchase_edit'];
            $purchaseArray['delete'] = $jsonData['purchase_delete'];
            $purchaseArray['mail'] = $jsonData['purchase_mail'];
            array_push($permission, $purchaseArray);
        }
        
        //inventory
        if($jsonData['inventory']) {
            $invArray['permission_type'] = 'inventory';
            $invArray['edit'] = $jsonData['inv_edit'];
            array_push($permission, $invArray);
        }
        
        //inventory use
        if($jsonData['inventory_use']) {
            $invuseArray['permission_type'] = 'inventory_use';
            $invuseArray['edit'] = $jsonData['invuse_edit'];
            array_push($permission, $invuseArray);
        }
        
        //product
        if($jsonData['product']) {
            $productArray['permission_type'] = 'product';
            $productArray['add'] = $jsonData['product_add'];
            $productArray['edit'] = $jsonData['product_edit'];
            $productArray['delete'] = $jsonData['product_delete'];
            array_push($permission, $productArray);
        }
        
        //vendor
        if($jsonData['vendor']) {
            $vendorArray['permission_type'] = 'vendor';
            $vendorArray['add'] = $jsonData['vendor_add'];
            $vendorArray['edit'] = $jsonData['vendor_edit'];
            $vendorArray['delete'] = $jsonData['vendor_delete'];
            array_push($permission, $vendorArray);
        }
        
        //user list
        if($jsonData['userlist']) {
            $userArray['permission_type'] = 'userlist';
            $userArray['add'] = $jsonData['user_add'];
            $userArray['edit'] = $jsonData['user_edit'];
            $userArray['delete'] = $jsonData['user_delete'];
            $userArray['leave'] = $jsonData['user_leave'];       
            array_push($permission, $userArray);
        }
        
        //accounts
        if($jsonData['accounts']) {
            $accountsArray['permission_type'] = 'accounts';
            $accountsArray['edit'] = $jsonData['accounts_edit'];
            array_push($permission, $accountsArray);
        }

        //billing
        if($jsonData['billing']) {
            $billingArray['permission_type'] = 'billing';
            $billingArray['add'] = $jsonData['billing_add'];
            $billingArray['delete'] = $jsonData['billing_delete'];
            $billingArray['approve'] = $jsonData['billing_approve'];
            array_push($permission, $billingArray);
        }
        
        $cursor = $collection->updateOne(
            array("emp_id" => (int)$jsonData['emp_id']),
            array('$set' => array(
                "site_list" => $siteList,
                "permission" => $permission
            ))
        );
            
        if($cursor) { $response['code'] = 'Success'; }
        else { $response['code'] = 'failure'; }
        return $response;
    }
    
    public function holidaycalc($getData)
    {
        $response = array();
        $collection = $this->conn->holidays_list;
        $jsonData = json_decode($getData, true);
        
        if($jsonData['leavefrom'] && $jsonData['leaveto'] && strtotime($jsonData['leaveto']) > strtotime($jsonData['leavefrom']))
        {
            $dayDiff = ((strtotime($jsonData['leaveto'])- strtotime($jsonData['leavefrom']))/24/3600) + 1; 
            $leaveFrom = date("Y-m-d", strtotime($jsonData['leavefrom']));
            $leaveTo = date("Y-m-d", strtotime($jsonData['leaveto']));

            $holidayCount = 0;
            for($i=$leaveFrom; $i<=$leaveTo; $i++)
            {
                if(date("l",  strtotime($i)) == 'Sunday') {
                    $holidayCount++;
                }
                $cursor = $collection->findOne(array("leave" => date("d-m-Y", strtotime($i))));
                if($cursor) {
                    $holidayCount++;
                }
            }
            $response['leave'] = $dayDiff - $holidayCount;
        }
        else {
            $response['leave'] = 0;
        }
        return $response;
    }
    
    public function changepwd($getData)
    {
        $response = array();
        $collection = $this->conn->signintable;
        $jsonData = json_decode($getData, true);
        
        $cursor = $collection->updateOne(
            array("emp_id" => (int)$jsonData['emp_id']),
            array('$set' => array( 
                "password" => $jsonData['password']
            ))
        );
        if($cursor) { $response['code']='Success'; }
        else { $response['code']='failure'; }
        return $response;
    }
    
    /***** SALARY LIST *****/
    
    public function salarylist($getData)
    {   
        $jsonData = json_decode($getData, true);
        $collection = $this->conn->user_list;
        $response = array();
        $response["salary_list"] = array();
        
        $cursor = $collection->aggregate(array(
            array('$match' => array(
                "status" => array('$ne' => "Resigned")
            )),
            array( '$lookup' => array(
                'from' => 'salary_list',
                'localField' => 'emp_id',
                'foreignField' => 'emp_id',
                'as' => 'salary_data'
            )),
            array( '$lookup' => array(
                'from' => 'salary_history',
                'localField' => 'emp_id',
                'foreignField' => 'emp_id',
                'as' => 'history_data'
            ))
        ));
        if($cursor) {
            foreach($cursor as $rowData)
            {
                if($rowData['history_data'][0]['emp_id']) { $sendData['pdf_status'] = 1; }
                else { $sendData['pdf_status'] = 0; }
                $sendData['emp_id'] = $rowData['emp_id'];
                $sendData['name'] = $rowData['name'];
                $sendData['department'] = $rowData['department'];
                $sendData['site'] = $rowData['site'];
                $sendData['gross'] = $rowData['salary_data'][0]['gross'];
                array_push($response["salary_list"], $sendData);
            }
            $response["code"] = "Success";
        } 
        else {                     
            $response["code"] = "failed";
        }
        return $response;
    }
    
    public function salarydetails($getData)
    {
        $jsonData = json_decode($getData, true);
        $collection = $this->conn->salary_list;
        $collectionLeave = $this->conn->userleave_details;
        $collectionBank = $this->conn->bank_list;
        $response = array();
        $response['bank_list'] = array();
        
        //bank list
        $cursorBank = $collectionBank->find();
        foreach($cursorBank as $bankData)
        {
            $bankArray['name'] = $bankData['name'];
            array_push($response['bank_list'], $bankArray);
        }
        
        //salary data
        $salaryData = $collection->findOne(array("emp_id" => (int)$jsonData['emp_id']));
        if($salaryData) 
        {
            //leave details
            if(date('m') < 4) { 
                $finStartYear = date('Y')-1;
                $finEndYear = date('Y');
            }
            else { 
                $finStartYear = date('Y');
                $finEndYear = date('Y')+1;
            }
            $finDuration = $finStartYear.' - '.$finEndYear;
            $leaveTaken = 0;
            
            $cursorLeave = $collectionLeave->find(array("emp_id" => (int)$jsonData['emp_id'], 'duration' => $finDuration));
            if($cursorLeave)
            {
                foreach($cursorLeave as $rowLeaveData)
                {
                    $leaveTaken += $rowLeaveData['leave'];
                }
                $availability = 0;
                if($leaveTaken < 15) { $availability = 15 - $leaveTaken; }
                
                $salaryData['leave'] = $leaveTaken;
                $salaryData['userleave'] = $availability;
            }
            $response["salary_details"] = $salaryData;
        }
        return $response;
    }
    
    public function salaryupdate($getData)
    {
        $response = array();
        $jsonData = json_decode($getData, true);
        $collectionSalary = $this->conn->salary_list;
        $collectionHistory = $this->conn->salary_history;
        $collectionBank = $this->conn->bank_list;
        
        //bank list
        $bankData = $collectionBank->findOne(array("name" => $jsonData['mode']));
        if(!$bankData) {
            $collectionBank->insertOne(array("name" => $jsonData['mode']));
        }
        
        $nextmonth = date('F', strtotime('first day of +1 month'));
        if($jsonData['user_status'] =='waiting'  && $nextmonth == date('F',strtotime('first day of +1 month')))
        {
            $userStatus = "Resigned";
        }
        else { $userStatus = $jsonData['user_status']; }
        
        //salary data
        $collectionSalary->updateOne(
            array("emp_id" => (int)$jsonData['emp_id']),
            array('$set' => array( 
                "gross" => $jsonData['gross'], 
                "basicpay" => $jsonData['basicpay'],
                "hra" => $jsonData['hra'],
                "conveyance" => $jsonData['conveyance'],
                "flexibleplan" => $jsonData['flexibleplan'],
                "pf" => $jsonData['pf'],
                "pt" => $jsonData['pt'],
                "advance" => $jsonData['advance'],
                "amount" => $jsonData['amount'],
                "total_amount" => $jsonData['total_amount'],
                "totalearnedamount" => $jsonData['totalearnedamount'],
                "ndw" => date("t", strtotime($jsonData['credited'])),   //no of days in a month
                "leave" => $jsonData['leave'],
                "los" => $jsonData['los'],
                "dw" => $jsonData['dw'],
                "credited" => $jsonData['credited'],
                "salmonth" => date('m', strtotime($jsonData['credited'])),
                "salyear" => date('Y', strtotime($jsonData['credited'])),
                "monthname" => date('F', strtotime("last month")),
                "mode" => $jsonData['mode'],
                "it" => $jsonData['it'],
                "others" => $jsonData['others'],
                "inc" => $jsonData['inc'],
                "user_status" => $userStatus
            ))
        );
        
        //salary history
        $cursor = $collectionHistory->insertOne(array(
            "emp_id" => (int)$jsonData['emp_id'],
            "gross" => $jsonData['gross'], 
            "basicpay" => $jsonData['basicpay'],
            "hra" => $jsonData['hra'],
            "conveyance" => $jsonData['conveyance'],
            "flexibleplan" => $jsonData['flexibleplan'],
            "pf" => $jsonData['pf'],
            "pt" => $jsonData['pt'],
            "advance" => $jsonData['advance'],
            "amount" => $jsonData['amount'],
            "total_amount" => $jsonData['total_amount'],
            "totalearnedamount" => $jsonData['totalearnedamount'],
            "ndw" => date("t", strtotime($jsonData['credited'])),
            "leave" => $jsonData['leave'],
            "los" => $jsonData['los'],
            "dw" => $jsonData['dw'],
            "credited" => $jsonData['credited'],
            "salmonth" => date('m', strtotime($jsonData['credited'])),
            "salyear" => date('Y', strtotime($jsonData['credited'])),
            "monthname" => date('F', strtotime("last month")),
            "mode" => $jsonData['mode'],
            "it" => $jsonData['it'],
            "others" => $jsonData['others'],
            "inc" => $jsonData['inc'].' %'
        ));
        
        if($cursor) { 
            $response["code"] = "Success";
        } 
        else {                     
            $response["code"] = "failed";
        }
        return $response;
    }
     
    public function getdirectorlist()
    {
        $response=array();
        $collection = $this->conn->user_list;
    
        $userData = $collection->find(array('$or' => array(
            array("designation" => "Managing Director"),
            array("designation" => "Director")
        )));
        $product = array();
        $response['director_list'] = array();
        foreach($userData as $rowData)
        {
            $product['emp_id'] = $rowData['emp_id'];
            $product['name'] = $rowData['lastname'].'.'.$rowData['firstname'];
            array_push($response['director_list'], $product);
        }
        return $response;
    }
    
    public function salaryhistory($getData)
    {
        $response = array();
        $jsonData = json_decode($getData, true);
        $collectionSalary = $this->conn->salary_history;
        $collectionLeave = $this->conn->userleave_details;
        
        //financial year
        if(date('m') < 4) { 
            $finStartYear = date('Y')-1;
            $finEndYear = date('Y');
        }
        else { 
            $finStartYear = date('Y');
            $finEndYear = date('Y')+1;
        }
        $response["curr_fin_year"] = $finStartYear.' - '.$finEndYear;
        
        $leaveArray['emp_id'] = $jsonData['emp_id'];
        $leaveArray['year'] = $response["curr_fin_year"];
        $respLeaveData = $this->leaveyear(json_encode($leaveArray));
        $response['leave_data'] = $respLeaveData['leave_data'];
        
        $response["financial_year"]=array();
        $cursorLeave = $collectionLeave->aggregate(array(
            array('$match' => array(
                'emp_id' => (int)$jsonData['emp_id']
            )),
            array('$group' => array(
                '_id' => array('duration' => '$duration')
            ))
        ));
        if($cursorLeave) 
        {
            foreach($cursorLeave as $rowData)
            {
                $sendData['year'] = $rowData['_id']['duration'];
                array_push ($response["financial_year"] ,$sendData);
            }
        }
        
        $response["salary_data"]=array();
        $cursorSal = $collectionSalary->find(array("emp_id" => (int)$jsonData['emp_id']));
        if($cursorSal) 
        { 
            foreach($cursorSal as $salaryData)
            {
                array_push($response["salary_data"], $salaryData);
            }
        }
        return $response;
    }

    public function get_annualsales()
    {  
        $response = array();
        $response['project_blocked']=array();
        
        //collection
        $collection_apartment=$this->conn->apartment_bookingtracker;

        $fromDate = strtotime("2017-07-22 00:00:00")*1000;
        $toDate = strtotime("2017-07-22 23:59:59")*1000;

        $project_soldcount = $collection_apartment->find(array(
            "current_date" => array(
                '$gte' => new MongoDB\BSON\UTCDateTime($fromDate),
                '$lte' => new MongoDB\BSON\UTCDateTime($toDate)
            )
        ));

        if($project_soldcount)
        {
            foreach($project_soldcount as $rowdata)
            {
                array_push($response['project_blocked'], $rowdata['_id']); 
            }
        }
        return $response;
    }

    public function getbillinglist($getData)
    {
        $response = array();
        $response['bill_list'] = array();
        $jsonData = json_decode($getData, true);
        $collectionBill = $this->conn->purchase_bill;

        //site list
        $siteData = $this->internalsitelist($jsonData['emp_id']);
        $response['site_list'] = $siteData["site_list"];

        $proShortArray = $siteData["project_short_list"];
        if($jsonData['project_short']!='') { $proShortArray = array($jsonData['project_short']); }

        $cursor = $collectionBill->aggregate(array(
            array('$match' => array(
                "project_short" => array( '$in' => $proShortArray )
            )),
            array( '$lookup' => array(
                'from' => 'signintable',
                'localField' => 'emp_id',
                'foreignField' => 'emp_id',
                'as' => 'user_data'
            )),
            array( '$sort' => array( 
                '_id' => -1
            ))
        ));
        if($cursor) {
            foreach($cursor as $rowData)
            {
                $sendData['_id'] = $rowData['_id'].$oid;
                $sendData['po_no'] = $rowData['bill_list'];
                $sendData['sis_bill_no'] = $rowData['sis_bill_no'];
                $sendData['emp_name'] = $rowData['user_data'][0]['name'];
                $sendData['vendor_bill_no'] = $rowData['vendor_bill_no'];
                $sendData['vendor_name'] = $rowData['vendor_name'];
                $sendData['grand_total'] = $rowData['grand_total'];
                $sendData['status'] = $rowData['status'];
                $sendData['bill_date'] = $rowData['created_at']->toDateTime()->setTimezone(new \DateTimeZone(date_default_timezone_get()))->format("d-m-Y");
                array_push($response['bill_list'], $sendData);
            }
        }
        return $response;
    }

    public function getBillingVendorList($getData)
    {
        $response = array();
        $response["project_list"] = array();
        $response["vendor_details"] = array();
        $jsonData = json_decode($getData, true);
        $collectionProject = $this->conn->project_details;
        $collectionVendor = $this->conn->vendor_details;
        
        //vendor
        $result = $collectionVendor->find(array("cancel_status" => '0'));
        foreach($result as $rowData)
        {
            $vendorData['company_name'] = $rowData['company_name'];
            array_push($response["vendor_details"], $vendorData);
        }
        
        //project list
        $cursorProject = $collectionProject->find();
        foreach($cursorProject as $rowProject)
        {
            $proData['project_name'] = $rowProject['project_name'];
            $proData['project_short'] = $rowProject['project_short'];
            array_push($response['project_list'], $proData);
        }
        
        $response["code"] = 'Success';
        return $response;
    }

    public function getVendorPoList($getData)
    {
        $response = array();
        $response['po_list'] = array();
        $jsonData = json_decode($getData, true);
        $collectionMaster = $this->conn->purchase_master;

        $cursor = $collectionMaster->aggregate(array(
            array('$match' => array(
                "company" => $jsonData['company'], "project_short" => $jsonData['project_short'], "received_status" => "1"
            )),
            array( '$sort' => array( 
                'bill_status' => 1
            ))
        ));
        if($cursor) {
            foreach($cursor as $rowData)
            {
                $sendData['po_number'] = $rowData['po_number'];
                $sendData['po_date'] = $rowData['po_date'];
                $sendData['po_type'] = $rowData['type'];
                $sendData['project_id'] = $rowData['project_id'].$oid;
                $sendData['bill_status'] = $rowData['bill_status'];

                array_push($response['po_list'], $sendData);
            }
        }
        return $response;
    }

    public function billingPoDetails($getData)
    {
        $response = array();
        $response['purchase_items'] = array();
        $jsonData = json_decode($getData, true);
        $purchaseTable = $this->conn->purchase_table;

        $cursor = $purchaseTable->aggregate(array(
            array('$match' => array(
                "po_number" => $jsonData['po_number']
            )),
            array( '$lookup' => array(
                'from' => 'inventory',
                'localField' => '_id',
                'foreignField' => 'purchase_id',
                'as' => 'inventory_data'
            ))
        ));
        if($cursor) {
            foreach($cursor as $rowData)
            {
                if(!$rowData['inventory_data'][0]['stock']) { $rowData['inventory_data'][0]['stock'] = 0; }
                if(!$rowData['inventory_data'][0]['billed_qty']) { $rowData['inventory_data'][0]['billed_qty'] = 0; }

                $sendData['po_number'] = $jsonData['po_number'];
                $sendData['inventory_id'] = $rowData['inventory_data'][0]['_id'].$oid;
                $sendData['code'] = $rowData['code'];
                $sendData['item'] = $rowData['item'];
                $sendData['price'] = $rowData['price'];
                $sendData['sgst'] = $rowData['sgst'];
                $sendData['cgst'] = $rowData['cgst'];
                $sendData['make'] = $rowData['make'];
                $sendData['details'] = $rowData['details'];
                $sendData['size'] = $rowData['size'];				
                $sendData['po_qty'] = $rowData['quantity'];
                $sendData['received_qty'] = $rowData['inventory_data'][0]['stock'];
                $sendData['billed_qty'] = $rowData['inventory_data'][0]['billed_qty'];
		
               
				
                if($rowData['inventory_data'][0]['stock'] > $rowData['inventory_data'][0]['billed_qty']) {
                    $sendData['unbilled_qty'] = ($rowData['inventory_data'][0]['stock'] - $rowData['inventory_data'][0]['billed_qty']);
                }
                else {
                    $sendData['unbilled_qty'] = 0;
                }
		
				
                if($jsonData['po_type']=='Door') {					
//				$size = explode ("x",  $sendData['size']);  
//				$t1 = ($size[0]*$size[1]*$size[2])/144;
//				$t2 =  number_format($t1, 3, '.', '');
//				$sendData['cft'] = $t2*unbilled_qty;					
              $sendData['cft'] = sprintf("%.3f", (($rowData['cft'] / $rowData['quantity']) * $sendData['unbilled_qty']) );
                $sendData['amount'] = sprintf("%.2f", $sendData['cft'] * $rowData['price']);
                $sendData['unit'] = 'Nos';
                }
                else {
                    $sendData['amount'] = $sendData['unbilled_qty'] * $rowData['price'];
                    $sendData['unit'] = $rowData['unit'];
                }
                
                $sendData['sgst_amount'] = sprintf("%.2f", ($rowData['sgst']/100) * $sendData['amount']);
                $sendData['cgst_amount'] = sprintf("%.2f", ($rowData['cgst']/100) * $sendData['amount']);
                $sendData['total'] = $sendData['amount'] + $sendData['sgst_amount'] + $sendData['cgst_amount'];

                array_push($response['purchase_items'], $sendData);
            }
        }
        return $response;
    }

    public function generatePurchaseBill($getData)
    {
        $response = array();
        $jsonData = json_decode($getData, true);
        $collectionProject = $this->conn->project_details;
        $collectionInventory = $this->conn->inventory;
        $collectionBill = $this->conn->purchase_bill;

        //get po number
        $validate = $collectionBill->findOne(array("project_short" => $jsonData['project_short']));
        if($validate) {
            $regex = new \MongoDB\BSON\Regex($jsonData['project_short']);    //LIKE query
            $results = $collectionBill->aggregate(array(
                array( '$match' => array(
                    'sis_bill_no' => $regex
                )),
                array( '$sort' => array( 
                    '_id' => -1
                )),
                array( '$limit' => 1 )
            ));
            
            foreach ($results as $key => $value)
            {
                $billNo = preg_replace("/[^0-9,.]/", "", $value['sis_bill_no']) + 1;
                $purchaseBillNo = 'B-'.$jsonData['project_short'].sprintf('%04d',$billNo);
            }
        }
        else {
            $purchaseBillNo = 'B-'.$jsonData['project_short'].'0001';
        }
        
        $billListArray = array();
        $blockListArray = array();
        for($i=0; $i<count($jsonData['bill_list']); $i++)
        {
            $billItemListArray = array();
            for($j=0; $j<count($jsonData['bill_list'][$i]['bill_item_list']); $j++)
            {
                $dcListArray = array();
                $invId = $jsonData['bill_list'][$i]['bill_item_list'][$j]['inventory_id'];
                $unbilledQty = $jsonData['bill_list'][$i]['bill_item_list'][$j]['unbilled_qty'];

                $invData = $collectionInventory->findOne(array("_id" => new MongoDB\BSON\ObjectID($invId)));
                if($invData)
                {
                    for($k=0; $k<count($invData['qty_ledger']); $k++)
                    {
                        if($invData['qty_ledger'][$k]['qty'] > $invData['qty_ledger'][$k]['billed_qty'])
                        {
                            if($invData['qty_ledger'][$k]['create_at']) {
                                $dcDateTime = $invData['qty_ledger'][$k]['create_at']->toDateTime()->setTimezone(new \DateTimeZone(date_default_timezone_get()))->format("d-m-Y");
                            }
                            else { $dcDateTime = ''; }
                            $remainQty = ($invData['qty_ledger'][$k]['qty'] - $invData['qty_ledger'][$k]['billed_qty']);

                            if($remainQty >= $unbilledQty)
                            {
                                $dcArrayData['dc_no'] = $invData['qty_ledger'][$k]['dc_no'];
                                $dcArrayData['qty'] = (float)$unbilledQty;
                                $dcArrayData['dc_date'] = $dcDateTime;
                                array_push($dcListArray, $dcArrayData);
                                $unbilledQty = 0;
                            }
                            else {
                                if($unbilledQty > 0) {
                                    $dcArrayData['dc_no'] = $invData['qty_ledger'][$k]['dc_no'];
                                    $dcArrayData['qty'] = $remainQty;
                                    $dcArrayData['dc_date'] = $dcDateTime;
                                    array_push($dcListArray, $dcArrayData);
                                }
                                $unbilledQty = ($unbilledQty - $remainQty);
                            }
                        }
                    }
                }

                $sendInvData['dc_list'] = $dcListArray;
                $newItemList = array_merge($jsonData['bill_list'][$i]['bill_item_list'][$j], $sendInvData);
                array_push($billItemListArray, $newItemList);
            }

            $proBlockData = $collectionProject->findOne(array("_id" => new MongoDB\BSON\ObjectID($jsonData['bill_list'][$i]['project_id'])));
            if (!in_array($proBlockData['block_name'], $blockListArray))
            {
                array_push($blockListArray, $proBlockData['block_name']);
            }

            $billArrayData['po_number'] = $jsonData['bill_list'][$i]['po_number'];
            $billArrayData['po_date'] = $jsonData['bill_list'][$i]['po_date'];
            $billArrayData['po_type'] = $jsonData['bill_list'][$i]['po_type'];
            $billArrayData['project_id'] = $jsonData['bill_list'][$i]['project_id'];
            $billArrayData['bill_item_list'] = $billItemListArray;
            $billArrayData['unloading_cost'] = $jsonData['bill_list'][$i]['unloading_cost'];
			$billArrayData['loading_tax'] = $jsonData['bill_list'][$i]['loading_tax'];
			$billArrayData['loading_tax_amt'] = $jsonData['bill_list'][$i]['loading_tax_amt'];
            $billArrayData['transport_cost'] = $jsonData['bill_list'][$i]['transport_cost'];
            $billArrayData['transport_tax'] = $jsonData['bill_list'][$i]['transport_tax'];
            $billArrayData['transport_tax_amt'] = $jsonData['bill_list'][$i]['transport_tax_amt'];
			 $billArrayData['plain_cost'] = $jsonData['bill_list'][$i]['plain_cost'];
            $billArrayData['plain_tax'] = $jsonData['bill_list'][$i]['plain_tax'];
            $billArrayData['plain_tax_amt'] = $jsonData['bill_list'][$i]['plain_tax_amt'];
			$billArrayData['sum_cft'] = $jsonData['bill_list'][$i]['sum_cft'];
            $billArrayData['sub_total'] = $jsonData['bill_list'][$i]['sub_total'];
            array_push($billListArray, $billArrayData);
        }

        $projectData = $collectionProject->findOne(array("project_short" => $jsonData['project_short']));

        $cursor = $collectionBill->insertOne(array(
            "emp_id" => (int)$jsonData['emp_id'],
            "sis_bill_no" => $purchaseBillNo,
            "project_name" => $projectData['project_name'],
            "project_short" => $jsonData['project_short'],
            "block_list" => $blockListArray,
            "vendor_bill_no" => $jsonData['vendor_bill_no'],
            "vendor_bill_date" => $jsonData['vendor_bill_date'],
            "vendor_name" => $jsonData['vendor_name'],
            "bill_list" => $billListArray,
            "grand_total" => $jsonData['grand_total'],
            "status" => 0,
            "created_at" => new MongoDB\BSON\UTCDateTime()
        ));
        if($cursor) {
            $response['code'] = 'Success';
        }
        else {
            $response['code'] = 'failure';
        }
        return $response;
    }

    public function viewpurchasebill($getData)
    {
        $response = array();
        $jsonData = json_decode($getData, true);
        $collectionBill = $this->conn->purchase_bill;

        $cursorData = $collectionBill->findOne(array("_id" => new MongoDB\BSON\ObjectID($jsonData['bill_id'])));
        if($cursorData) {
            $totalDcList = '';
            $poList = '';
            $blockList = '';
            $billListArray = array();
            $dcTestArray = array();
            for($i=0; $i<count($cursorData['bill_list']); $i++)
            {
                $poList .= $cursorData['bill_list'][$i]['po_number'].', ';
                $dcListArray = array();
                $testArray = array();
                for($j=0; $j<count($cursorData['bill_list'][$i]['bill_item_list']); $j++)
                {
                    $dcArrayList = $cursorData['bill_list'][$i]['bill_item_list'][$j]['dc_list'];

                    for($k=0; $k<count($dcArrayList); $k++)
                    {
                        if(!in_array($dcArrayList[$k]['dc_no'], $testArray)) {
                            array_push($dcListArray, $dcArrayList[$k]);
                            array_push($testArray, $dcArrayList[$k]['dc_no']);
                        }

                        if(!in_array($dcArrayList[$k]['dc_no'], $dcTestArray)) {
                            $totalDcList .= $dcArrayList[$k]['dc_no'].', ';
                            array_push($dcTestArray, $dcArrayList[$k]['dc_no']);
                        }
                    }
                }

                $poData['po_number'] = $cursorData['bill_list'][$i]['po_number'];
                $poData['po_date'] = $cursorData['bill_list'][$i]['po_date'];
                $poData['po_type'] = $cursorData['bill_list'][$i]['po_type'];
                $poData['dc_list'] = $dcListArray;
                $poData['bill_item_list'] = $cursorData['bill_list'][$i]['bill_item_list'];
                $poData['unloading_cost'] = $cursorData['bill_list'][$i]['unloading_cost'];
				$poData['loading_tax'] = $jsonData['bill_list'][$i]['loading_tax'];
			    $poData['loading_tax_amt'] = $jsonData['bill_list'][$i]['loading_tax_amt'];
                $poData['transport_cost'] = $cursorData['bill_list'][$i]['transport_cost'];
                $poData['transport_tax'] = $cursorData['bill_list'][$i]['transport_tax'];
                $poData['transport_tax_amt'] = $cursorData['bill_list'][$i]['transport_tax_amt'];
				$poData['plain_cost'] = $cursorData['bill_list'][$i]['plain_cost'];
                $poData['plain_tax'] = $cursorData['bill_list'][$i]['plain_tax'];
                $poData['plain_tax_amt'] = $cursorData['bill_list'][$i]['plain_tax_amt'];
				$poData['sum_cft'] = $cursorData['bill_list'][$i]['sum_cft'];
                $poData['sub_total'] = $cursorData['bill_list'][$i]['sub_total'];

                array_push($billListArray, $poData);
            }

            for($k=0; $k<count($cursorData['block_list']); $k++)
            {
                $blockList .= $cursorData['block_list'][$k].', ';
            }

            $sendData['sis_bill_no'] = $cursorData['sis_bill_no'];
            $sendData['vendor_bill_no'] = $cursorData['vendor_bill_no'];
            $sendData['vendor_bill_date'] = $cursorData['vendor_bill_date'];
            $sendData['vendor_name'] = $cursorData['vendor_name'];
            $sendData['project_name'] = $cursorData['project_name'];
            $sendData['block_list'] = rtrim($blockList, ", ");
            $sendData['po_list'] = rtrim($poList, ", ");
            $sendData['dc_list'] = rtrim($totalDcList, ", ");
            $sendData['bill_list'] = $billListArray;
            $sendData['grand_total'] = $cursorData['grand_total'];
            $sendData['status'] = $cursorData['status'];
            $sendData['credit_note'] = $cursorData['credit_note'];

            $response['bill_details'] = $sendData;
            $response['code'] = 'Success';
        }
        else {
            $response['code'] = 'failure';
        }
        return $response;
    }

    public function billcheckout($getData)
    {
        $response = array();
        $jsonData = json_decode($getData, true);
        $collectionBill = $this->conn->purchase_bill;
        
        $result = $collectionBill->findOne(array("project_short" => $jsonData["project_short"],
            "vendor_name" => $jsonData["vendor_name"], "status" => 0));
        if(!$result) {
            $response["code"] = "Success";
        }
        else {
            $response["code"] = "failure";
        }
        return $response;
    }

    public function confirmpurchasebill($getData)
    {
        $response = array();
        $jsonData = json_decode($getData, true);
        $collectionMaster = $this->conn->purchase_master;
        $collectionInventory = $this->conn->inventory;
        $collectionBill = $this->conn->purchase_bill;

        $cursorData = $collectionBill->findOne(array("_id" => new MongoDB\BSON\ObjectID($jsonData['bill_id'])));
        if($cursorData) {
            if($jsonData['status']=='1')
            {
                for($i=0; $i<count($cursorData['bill_list']); $i++)
                {
                    for($j=0; $j<count($cursorData['bill_list'][$i]['bill_item_list']); $j++)
                    {
                        $invId = $cursorData['bill_list'][$i]['bill_item_list'][$j]['inventory_id'];
                        $dcList = $cursorData['bill_list'][$i]['bill_item_list'][$j]['dc_list'];
                        $unbilledQty = $cursorData['bill_list'][$i]['bill_item_list'][$j]['unbilled_qty'];
                        for($k=0; $k<count($dcList); $k++)
                        {
                            $collectionInventory->updateOne(
                                array("_id" => new MongoDB\BSON\ObjectID($invId), "qty_ledger.dc_no" => $dcList[$k]['dc_no']),
                                array('$inc' => array( "qty_ledger.$.billed_qty" => $dcList[$k]['qty'] ))
                            );
                        }

                        $collectionInventory->updateOne(
                            array("_id" => new MongoDB\BSON\ObjectID($invId)),
                            array('$inc' => array( "billed_qty" => (float)$unbilledQty ))
                        );
                    }

                    //check inventory for bill status
                    $checkInvData = $collectionInventory->find(array("po_number" => $cursorData['bill_list'][$i]['po_number']));
                    if($checkInvData) {
                        $itemCount = 0;
                        $completedItemCount = 0;
                        foreach ($checkInvData as $key => $value) {
                            $itemCount++;
                            if($value['stock']==$value['billed_qty']) { $completedItemCount++; }
                        }

                        if($itemCount==$completedItemCount) { $billStatus = 2; }
                        else { $billStatus = 0; }

                        $collectionMaster->updateOne(
                            array("po_number" => $cursorData['bill_list'][$i]['po_number']),
                            array('$set' => array("bill_status" => $billStatus))
                        );
                    }
                }
            }

            $collectionBill->updateOne(
                array("_id" => new MongoDB\BSON\ObjectID($jsonData['bill_id'])),
                array('$set' => array("status" => (int)$jsonData['status'], "credit_note" => $jsonData['credit_note']))
            );

            $response['code'] = 'Success';
        }
        else {
            $response['code'] = 'failure';
        }
        
        return $response;
    }

    public function deletepurchasebill($getData)
    {
        $response = array();
        $jsonData = json_decode($getData, true);
        $collectionBill = $this->conn->purchase_bill;
        
        $result = $collectionBill->deleteOne(array("_id" => new MongoDB\BSON\ObjectID($jsonData["bill_id"])));
        if($result) {
            $response["code"] = "Success";
        }
        else {
            $response["code"] = "failure";
        }
        return $response;
    }
    
/***** INTERNAL FUNCTIONS *****/
    public function getcategory($getData)
    {
          $response = array();
       $jsonData = json_decode($getData, true);
        $collectionCategory = $this->conn->category;
       
          $validate = $collectionCategory->findOne(array("category" => $jsonData['category']));
        if($validate)
        {
           $response["product_short"] = $validate["short"];
             $response["category"] = $validate["category"];
        }
        
        
  return $response;

   
    }
    //product code
    
     public function getproductcode($getData)
    {
        $response = array();
       
        $jsonData = json_decode($getData, true);
        
        // mongo collection
         $resData = $this->getcategory(json_encode($jsonData));
         $collectionMaster = $this->conn->product_list;
         $validate = $collectionMaster->findOne(array("category" => $resData['category']));
         
      if($validate) {
    //$response['product_code'] = $jsonData['category'].'0002';
            $regex = new \MongoDB\BSON\Regex("^" . $resData['product_short'] . "-");    //LIKE query
            $results = $collectionMaster->aggregate(array(
                array( '$match' => array(
                    'product_code' => $regex
                )),
                array( '$project' => array(
                    '_id' => 0,
                    'product_code' => 1
                )),
                array( '$sort' => array( 
                    'product_code' => -1
                )),
                array( '$limit' => 1 )
            ));
            
            foreach($results as $rowInvData)
            {
                $invNo = preg_replace("/[^0-9,.]/", "", $rowInvData['product_code']) + 1;
                $response['product_code'] = $resData['product_short'].'-'.sprintf('%04d',$invNo);
            }
        }
        else {
           
            $response['product_code'] = $resData['product_short'].'-'.'0001';
        }

        
  return $response;
       
         
    }
    
    //generate po number
    public function internalponum($getData)
    {
        $response = array();
        $response['person_list'] = array();
        $jsonData = json_decode($getData, true);
        
        // mongo collection
        $collectionProject = $this->conn->project_details;
        $collectionMaster = $this->conn->purchase_master;
        $collection = $this->conn->user_list;
        
        //get po number
        $validate = $collectionMaster->findOne(array("project_short" => $jsonData['project_short']));
        if($validate) {
            $regex = new \MongoDB\BSON\Regex($jsonData['project_short']);    //LIKE query
            $results = $collectionMaster->aggregate(array(
                array( '$match' => array(
                    'po_number' => $regex
                )),
                array( '$project' => array(
                    '_id' => 0,
                    'po_number' => 1
                )),
                array( '$sort' => array( 
                    'po_number' => -1
                )),
                array( '$limit' => 1 )
            ));
            
            foreach($results as $rowInvData)
            {
                $invNo = preg_replace("/[^0-9,.]/", "", $rowInvData['po_number']) + 1;
                $response['po_number'] = $jsonData['project_short'].sprintf('%04d',$invNo);
            }
        }
        else {
            $response['po_number'] = $jsonData['project_short'].'0001';
        }
        
        //get contact person details
        $cursor = $collection->find(
            array('$or' => array(
              array("designation" => "Supervisor"),
              array("designation" => "Site Supervisor"),
              array("designation" => "Store Keeper"),
              array("designation" => "Store Incharge"),
              array("designation" => "Sr.Site Engineer"),
              array("designation" => "Site Engineer")
            ))
        );
        if($cursor) {
            $product = array();
            foreach($cursor as $rowData)
            {
                if($rowData['site'] == $jsonData['project_name'] && $rowData['status']=='Active')
                {
                    $product['name'] = $rowData['name'];
                    $product['mobile'] = $rowData['officeno'];

                    array_push($response['person_list'], $product);
                }
            }
        }
       
        //get person details from project
        $cursorData = $collectionProject->aggregate(array(
            array( '$match' => array(
                "project_name" => $jsonData['project_name']
            )),
            array( '$group' => array(
                '_id' => array(
                    'emp_id' => '$emp_id'
                )
            )),
            array( '$lookup' => array(
                'from' => 'userData',
                'localField' => '_id.emp_id',
                'foreignField' => 'emp_id',
                'as' => 'user_data'
            ))
        ));
        if($cursorData) {
            $proArray = array();
            foreach($cursorData as $projectData)
            {
                $proArray['name'] = $projectData['user_data'][0]['name'];
                $proArray['mobile'] = $projectData['user_data'][0]['officeno'];
                
                array_push($response['person_list'], $proArray);
            }
        }
        
        return $response;
    }
    
    //generate prf number
    public function internalprfnum($getData)
    {
        $response = array();
        $jsonData = json_decode($getData, true);
        
        // mongo collection
        $collectionMaster = $this->conn->indent_master;
        
        //get po number
        $validate = $collectionMaster->findOne(array("project_short" => $jsonData['project_short']));
        if($validate)
        {
            $regex = new \MongoDB\BSON\Regex($jsonData['project_short']);    //LIKE query
            $results = $collectionMaster->aggregate(array(
                array( '$match' => array(
                    'prf_number' => $regex
                )),
                array( '$project' => array(
                    '_id' => 0,
                    'prf_number' => 1
                )),
                array( '$sort' => array( 
                    'prf_number' => -1
                )),
                array( '$limit' => 1 )
            ));
            
            foreach($results as $rowInvData)
            {
                $invNo = preg_replace("/[^0-9,.]/", "", $rowInvData['prf_number']) + 1;
                $response['prf_number'] = 'PRF-'.$jsonData['project_short'].sprintf('%04d',$invNo);
            }
        }
        else
        {
            $response['prf_number'] = 'PRF-'.$jsonData['project_short'].'0001';
        }
        return $response;
    }
    
    //get po type details
    public function internalordertype()
    {
        $response['order_type'] = array();
        $collection = $this->conn->order_types;

        $result = $collection->find();
        $type = array();
        foreach($result as $rowData)
        {
            $type['name'] = $rowData['name'];
            $type['short_name'] = $rowData['short_name'];
            array_push($response['order_type'], $type);
        }
        return $response;
    }
    
    //get sis project details
    public function internalsisproject()
    {
        $response = array();
        
        // mongo collection
        $collectionProject = $this->conn->project_details;
        
        $resultProject = $collectionProject->aggregate(array(
            array( '$lookup' => array(
                'from' => 'signintable',
                'localField' => 'emp_id',
                'foreignField' => 'emp_id',
                'as' => 'user_data'
            ))
        ));
        
        if($resultProject)
        {
            $project = array();
            foreach($resultProject as $rows)
            {
                $project['project_id'] = $rows['_id'];
                $project['project_name'] = $rows['project_name'];
                $project['block_name'] = $rows['block_name'];
                $project['place'] = $rows['place'];
                $project['contact_name'] = $rows['user_data'][0]['name'];
                $project['mobile_no'] = $rows['user_data'][0]['mobile'];
                $project['address'] = $rows['address'];
                $project['gst_in'] = $rows['gst_in'];
                $project['project_short'] = $rows['project_short'];
                
                array_push($response, $project);
            }   
           return $response;
        }
    }
    
    //get folder list
    public function internalfolderlist()
    {
        $response = array();
        
        $collectionFolder = $this->conn->folder;

        $resultFolder = $collectionFolder->find();
        if($resultFolder)
        {
            $folder = array();
            foreach($resultFolder as $rowFolData)
            {
                $folder['folder'] = $rowFolData['folder'];
                array_push($response, $folder);
            }
            return $response;
        }
    }
    
    //get category list
    public function internalcategorylist()
    {
        $response = array();
        $collection = $this->conn->category;

        $result = $collection->aggregate(array(
            array( '$sort' => array( 
                'category' => 1
            ))
        ));
        if($result)
        {
            $category = array();
            foreach($result as $rowData)
            {
                $category['type'] = $rowData['type'];
                $category['category'] = $rowData['category'];
//                $category['sub_category'] = $rowData['sub_category'];
                array_push($response, $category);
            }
            return $response;
        }
    }
    
    //get vendor list
    public function internalvendorlist()
    {
        $response = array();
        
        // mongo collection
        $collectionVendor = $this->conn->vendor_details;
    
        $resultvendor = $collectionVendor->find(array("cancel_status" => '0'));
        if($resultvendor)
        {
            foreach($resultvendor as $rowProData)
            {
                array_push($response, $rowProData);
            }
            return $response;
        }
    }
    
    //get product list
    public function internalproductlist($getData)
    {
        $response = array();
        $jsonData = json_decode($getData, true);
        
        // mongo collection
        $collectionProduct = $this->conn->product_list;
    
        if($jsonData['category']!='none')
        {
            $resultProduct = $collectionProduct->aggregate(array(
                array('$match' => array(
                    "cancel_status" => 0,
                    "type" => $jsonData['type'],
                    "category" => $jsonData['category']
//                    "sub_category" => $jsonData['sub_category']
                ))
            ));
        }
        else {
            $resultProduct = $collectionProduct->aggregate(array(
                array('$match' => array(
                    "cancel_status" => 0,
                    "type" => $jsonData['type']
                ))
            ));
        }
        if($resultProduct)
        {
            $product = array();
            foreach($resultProduct as $rowProData)
            {
             if($rowProData['product_code'])
               {
                $product['type'] = $rowProData['type'];
                $product['code'] = $rowProData['code'];
                $product['product_name'] = $rowProData['product_name'];
                $product['product_code'] = $rowProData['product_code'];
                $product['unit'] = $rowProData['unit'];
                $product['price'] = $rowProData['price'];
                $product['gst'] = $rowProData['sgst'] + $rowProData['cgst'];
                $product['taxtype'] = $rowProData['taxtype'];
                $product['details'] = $rowProData['details'];
                $product['make'] = $rowProData['make'];
                $product['width'] = $rowProData['width'];
                $product['height'] = $rowProData['height'];
                $product['upvc_type'] = $rowProData['upvc_type'];
                $product['size'] = $rowProData['size'];
                $product['cft'] = $rowProData['cft'];  
          }
                array_push($response, $product);
            }
            return $response;
        }
    }
    
    //get indent project list
    public function internalindentprojectlist($empId)
    {
        $response = array();
        $collection = $this->conn->permissiontable;
        $collectionProject = $this->conn->project_details;
        
        $cursorData = $collection->findOne(array("emp_id" => (int)$empId));
        if($cursorData['site_list'])
        {
            if($cursorData['site_list']=='All') {
                $projectData = $collectionProject->find();
                if($projectData) {
                    foreach($projectData as $rowData)
                    {
                        $sendData['project_short'] = $rowData['project_short'];
                        $sendData['project_name'] = $rowData['project_name'];
                        $sendData['block_name'] = $rowData['block_name'];
                        array_push($response, $sendData);
                    }
                }
            }
            else {
                $siteCount = count($cursorData['site_list']);
                for($i=0; $i<$siteCount; $i++)
                {
                    $projectData = $collectionProject->find(array("project_name" => $cursorData['site_list'][$i]['project_name']));
                    if($projectData) {
                        foreach($projectData as $rowData)
                        {
                            $sendData['project_short'] = $rowData['project_short'];
                            $sendData['project_name'] = $rowData['project_name'];
                            $sendData['block_name'] = $rowData['block_name'];
                            array_push($response, $sendData);
                        }
                    }
                }
            }   
        }
        return $response;
    }
    
    //get vendor list
    public function internalpaymentandvat()
    {
        $response = array();
        $response['payment'] = array();
        $response['vat'] = array();
        
        // mongo collection
        $collection = $this->conn->purchase_master;
    
        //payment
        $result = $collection->aggregate(array(
            array( '$group' => array(
                '_id' => array('payment' => '$payment')
            ))
        ));
        if($result)
        {
            $product = array();
            foreach($result as $rowData)
            {
                $product['payment'] = $rowData['_id']['payment'];
                array_push($response['payment'], $product);
            }
        }
        
        //vat
        $resultData = $collection->aggregate(array(
            array( '$group' => array(
                '_id' => array('vat' => '$vat')
            ))
        ));
        if($resultData)
        {
            $product = array();
            foreach($resultData as $row)
            {
                $product['vat'] = $row['_id']['vat'];
                array_push($response['vat'], $product);
            }
        }
        return $response;
    }
    
    //get indent list
    public function internalquotationlist()
    {
        $response = array();
        
        // mongo collection
        $collectionQuot = $this->conn->quotation_master;
    
        $result = $collectionQuot->find(array("approved_status" => 'approved', "po_raised" => array('$ne' => "1")));
        if($result)
        {
            $product = array();
            foreach($result as $rowData)
            {
                $product['quot_number'] = $rowData['quot_number'];
                array_push($response, $product);
            }
            return $response;
        }
    }
    
    //internal site list
    public function internalsitelist($emp_id)
    {
        $collection = $this->conn->permissiontable;
        $collectionProject = $this->conn->project_details;
        
        $response['site_list'] = array();
        $response['project_short_list'] = array(); //for query
        
        $cursorData = $collection->findOne(array("emp_id" => (int)$emp_id));
        if($cursorData['site_list'])
        {
            if($cursorData['site_list']=='All') {
                //vendors
                $projectData = $collectionProject->aggregate(array(
                    array( '$group' => array(
                        '_id' => array(
                            'project_name' => '$project_name',
                            'project_short' => '$project_short'
                        )
                    ))
                ));
                foreach($projectData as $rowData)
                {
                    $product['project_name'] = $rowData['_id']['project_name'];
                    $product['project_short'] = $rowData['_id']['project_short'];
                    
                    array_push($response['site_list'], $product);
                    array_push($response['project_short_list'], $rowData['_id']['project_short']);
                }
            }
            else {
                $response['site_list'] = $cursorData['site_list'];
                $proCount = count($cursorData['site_list']);
                for($i=0; $i<$proCount; $i++)
                {
                    array_push($response['project_short_list'], $cursorData['site_list'][$i]['project_short']);
                }
            }   
        }
        return $response;
    }
    
    //generate summary
    public function internalsummary($projectname,$comp,$product1,$product2)
    {
        if($comp != '') {
            $companyname = explode(" ", $comp);
            $companyname1=$companyname[0];
            $x = strlen($companyname[0]);
            $y=35-(($x+3)+1+1);
        }
        else {
            $y=35;
        }
        $a = explode(" ", $product1);
        $b = explode(" ", $product2);
        isset($a[0]) ? $a1 = $a[0] : $a1 = "";
        isset($a[1]) ? $a2 = $a[1] : $a2 = "";
        isset($a[2]) ? $a3 = $a[2] : $a3 = "";
        isset($a[3]) ? $a4 = $a[3] : $a4 = "";
        isset($a[4]) ? $a5 = $a[4] : $a5 = "";
        isset($b[0]) ? $b1 = $b[0] : $b1 = "";
        isset($b[1]) ? $b2 = $b[1] : $b2 = "";
        isset($b[2]) ? $b3 = $b[2] : $b3 = "";
        isset($b[3]) ? $b4 = $b[3] : $b4 = "";
        isset($b[4]) ? $b5 = $b[4] : $b5 = ""; 
        $a1_c=strlen($a1);
        $a2_c=strlen($a2);
        $a3_c=strlen($a3);
        $a4_c=strlen($a4);
        $a5_c=strlen($a5);
        $b1_c=strlen($b1);
        $b2_c=strlen($b2);
        $b3_c=strlen($b3);
        $b4_c=strlen($b4);
        $b5_c=strlen($b5);
        if($product2 == "")
        {
            if($a1_c+$a2_c+$a3_c+$a4_c <= $y) {
                $productname1 = $a1." ".$a2." ".$a3." ".$a4;
                $desc = $projectname.' '.$companyname1.'-'.$productname1;
            }
            elseif($a1_c+$a2_c+$a3_c+$a4_c <= $y) {
                $productname1 = $a1." ".$a2." ".$a3;
                $desc = $projectname.' '.$companyname1.'-'.$productname1;
            }
            elseif($a1_c+$a2_c <= $y) {
                $productname1 = $a1." ".$a2;
                $desc = $projectname.' '.$companyname1.'-'.$productname1;
            }
            elseif($a1_c <= $y) {
                $productname1 = $a1;
                $desc = $projectname.' '.$companyname1.'-'.$productname1;
            }
        }
        elseif($product2 != "")
        {
            if($a1_c+$a2_c+$a3_c+$a4_c+$b1_c+$b2_c+$b3_c+$b4_c <= $y)
            {
                $productname1 = $a1." ".$a2." ".$a3." ".$a4.", ".$b1." ".$b2." ".$b3." ".$b4;
                $desc = $projectname.' '.$companyname1.'-'.$productname1;
            }
            elseif($a1_c+$a2_c+$a3_c+$b1_c+$b2_c+$b3_c+$b4_c <= $y and ($a1_c+$a2_c+$b1_c+$b2_c+$b3_c+$b4_c < $a1_c+$a2_c+$a3_c+$a4_c+$b1_c+$b2_c) and $a1_c+$a2_c+$b1_c+$b2_c+$b3_c+$b4_c <=$y)
            {
                $productname1 = $a1." ".$a2.", ".$b1." ".$b2." ".$b3." ".$b4;
                
                $desc = $projectname.' '.$companyname1.'-'.$productname1;
            }
            elseif($a1_c+$a2_c+$a3_c+$a4_c+$b1_c+$b2_c <= $y and ($a1_c+$a2_c+$b1_c+$b2_c+$b3_c+$b4_c > $a1_c+$a2_c+$a3_c+$a4_c+$b1_c+$b2_c) and $a1_c+$a2_c+$a3_c+$a4_c+$b1_c+$b2_c <= $y)
            {
                $productname1 = $a1." ".$a2." ".$a3." ".$a4.", ".$b1." ".$b2." ".$b3." ".$b4;
                $desc = $projectname.' '.$companyname1.'-'.$productname1;
            }
            elseif($a1_c+$a2_c+$a3_c+$b1_c+$b2_c+$b3_c <= $y and ($a1_c+$a2_c+$b1_c+$b2_c+$b3_c+$b4_c > $a1_c+$a2_c+$a3_c+$a4_c+$b1_c+$b2_c)<=$y)
            {
                $productname1 = $a1." ".$a2." ".$a3.", ".$a4." ".$b1." ".$b2." ".$b3." ".$b4;
                $desc = $projectname.' '.$companyname1.'-'.$productname1;
            }
            elseif($a1_c+$a2_c+$a3_c+$b1_c+$b2_c+$b3_c <= $y)
            {
                $productname1 = $a1." ".$a2." ".$a3.", ".$b1." ".$b2." ".$b3;
                $desc = $projectname.' '.$companyname1.'-'.$productname1;
            }
            elseif(($a1_c+$a2_c+$b1_c+$b2_c) > $y and ($a1_c+$b1_c+$b2_c+$b3_c < $a1_c+$a2_c+$a3_c+$b1_c) and $a1_c+$b1_c+$b2_c+$b3_c <= $y)
            {
                $productname1 = $a1.", ".$b1." ".$b2." ".$b3;
                $desc = $projectname.' '.$companyname1.'-'.$productname1;
            }
            elseif(($a1_c+$a2_c+$b1_c+$b2_c) > $y and ($a1_c+$b1_c+$b2_c+$b3_c > $a1_c+$a2_c+$a3_c+$b1_c) and $a1_c+$a2_c+$a3_c+$b1_c <= $y)
            {
                $productname1 = $a1." ".$a2." ".$a3.", ".$b1;
                $desc = $projectname.' '.$companyname1.'-'.$productname1;
            }
            elseif(($a1_c+$a2_c+$b1_c+$b2_c) <= $y)
            {
                $productname1 = $a1." ".$a2.", ".$b1." ".$b2;
                $desc = $projectname.' '.$companyname1.'-'.$productname1;
            }
            elseif($a1_c+$b1_c < $y and $a1_c+$b1_c+$b2_c <= $y and $a1_c+$b1_c+$b2_c < $a1_c+$a2_c+ $b1_c)
            {
                $productname1 = $a1.", ".$b1." ".$b2;
                $desc = $projectname.' '.$companyname1.'-'.$productname1;
            }
            elseif($a1_c+$b1_c < $y  and $a1_c+$a2_c+$b1_c <= $y and $a1_c+$b1_c+$b2_c > $a1_c+$a2_c+ $b1_c)
            {
                $productname1 = $a1." ".$a2 .", ".$b1;
                $desc = $projectname.' '.$companyname1.'-'.$productname1;
            }
            elseif($a1 < $y and $a1_c+$a2_c < $a1_c+$b1_c and $a1_c+$a2_c <= $y)
            {
                $productname1 = $a1." ".$a2;
                $desc = $projectname.' '.$companyname1.'-'.$productname1;
            }
            elseif($a1_c < $y and $a1_c+a2_c > a1_c+b1_c and a1_c+b1_c <= $y )
            {
                $productname1 = $a1.", ".$b1;
                $desc = $projectname.' '.$companyname1.'-'.$productname1;
            }
            elseif( $a1_c >= $y)
            {
                $productname1 = $a1;
                $desc = $projectname.' '.$companyname1.'-'.$productname1;
            }
        }
        $desc = preg_replace('!\s+!', ' ', $desc);
        $desc = str_replace(" ,", ",", $desc);
        $desc = rtrim($desc, " ");
        return $desc;
    }
    
    //convert sec to words
    public function secondsToWords($start, $end)
    {
        $seconds = strtotime($end) - strtotime($start);
        $words = "";

        $days = intval(intval($seconds) / (3600*24));
        if($days> 0) {
            if($days > 1){ $format = " days "; }
            else{ $format = " day "; }
            $words .= $days.$format;
        }

        $hours = (intval($seconds) / 3600) % 24;
        if($hours > 0) {
            if($hours > 1){ $format = " hrs "; }
            else{ $format = " hr "; }
            $words .= $hours.$format;
        }

        $minutes = (intval($seconds) / 60) % 60;
        if($minutes > 0) {
            $words .= $minutes." min";
        }
        
        if($words==''){
            $words = '0 min';
        }

        return trim($words);
    }

        public function testmailer()
        {
                $response = array();
                $validate = array(
                    'mail_id' => 'prabha@sis.in', // Gmail ID
                    'mail_pwd' => 'vlou vadz wolu dfgn'    // Gmail app password
                );
                try {
                // Server settings
                $mail = new PHPMailer\PHPMailer\PHPMailer();
                $mail->isSMTP();                                            // Set mailer to use SMTP
                $mail->Host       = 'smtp.gmail.com';                     // Specify SMTP server
                $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
                $mail->Username   = $validate['mail_id'];               // SMTP username
                $mail->Password   = $validate['mail_pwd'];                        // SMTP password
                $mail->SMTPSecure = 'ssl';
                $mail->Port       = 465; 
                // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption
                // $mail->Port       = 587;           
                
                $mail->SMTPDebug = 2; // Enable verbose debug output
                $mail->Debugoutput = 'error_log'; // Log output to PHP error log

                $mail->setFrom($validate['mail_id'], 'Your Name');
                $mail->addAddress('prabha1094@gmail.com');
                $mail->addAddress('prabha@whitemastery.com');
                $mail->addAddress('hari@squareht.com');
                // $mail->addAddress('dev@bdcode.in'); // Add a recipient
                $mail->addReplyTo($validate['mail_id'], 'Your Name');

                // Content
                $mail->isHTML(true);                                        // Set email format to HTML
                $mail->Subject = 'Test Email from PHPMailer';
                $mail->Body    = '<p>Hello, this is a <strong>test email</strong> sent using PHPMailer.</p>';
                $mail->AltBody = 'Hello, this is a test email sent using PHPMailer.'; // Plain text for non-HTML mail clients

                // Send the email
                $mail->send();
                $response["message"] = "Email has been sent successfully!";
                } catch (Exception $e) 
                {
                    $response["message"] = "Error: " . $mail->ErrorInfo . "; Exception: " . $e->getMessage();
                    $response["message"] = $mail->ErrorInfo;
                }

                    $imapStream = imap_open("{imap.gmail.com:993/imap/ssl}", $validate['mail_id'], $validate['mail_pwd']);

                    // List all mailboxes/folders in Gmail
                    // $folders = imap_list($imapStream, "{imap.gmail.com:993/imap/ssl}", "*");
                    // if ($folders) {
                    //     $response["all"] = $folders;
                    // } else {
                    // $response["error"] = "Unable to list folders: " . imap_last_error();
                    // }

                    if (!$imapStream) {
                        $response["imap"] = imap_last_error();
                    exit;
                    }

                    // Get the MIME message (PHPMailer provides this method)
                    $sentMimeMessage = $mail->getSentMIMEMessage();

                    // Append the email to Gmail's "Sent Mail" folder
                    $result = imap_append($imapStream, "{imap.gmail.com:993/imap/ssl}[Gmail]/Sent Mail", $sentMimeMessage, "\\Seen");

                    if ($result) {
                            echo "Email appended to Sent Mail folder successfully!";
                            $response["imap"] = "Email appended to Sent Mail folder successfully!";
                    } else {
                            echo "Failed to append email: " . imap_last_error();
                            $response["imap"] = imap_last_error();
                    }
                    imap_close($imapStream);

            return $response;
        }
    
    //php mailer
    public function internalMailer($getData)
    {
        $jsonData = json_decode($getData, true);        
        // collection
        $collectionUser = $this->conn->signintable;        
        $validate = $collectionUser->findOne(array("emp_id" => (int)$jsonData['emp_id']));
        if($validate)
        {
            $mail = new PHPMailer\PHPMailer\PHPMailer();
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host       = 'smtp.gmail.com';                 // Specify main and backup SMTP servers
            $mail->SMTPAuth   = true;                             // Enable SMTP authentication
            $mail->Username = $validate['config_mailid'];
            $mail->Password = $validate['mail_pwd'];
            $mail->CharSet="UTF-8";
            $mail->Port = 465;
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = 'ssl';
            $mail->setFrom($validate['config_mailid'], $jsonData['mail_name']);            
            //to mail
            $to_mail_id = explode(',', $jsonData['to_mail']);
            foreach($to_mail_id as $toMailId)
            {
                if($toMailId != ''){
                    $mail->AddAddress($toMailId);
                }
            }
            
            //cc mail
            $cc_mail_id = explode(',', $jsonData['cc_mail']);
            foreach($cc_mail_id as $ccMailId)
            {
                if($ccMailId != ''){
                    $mail->addCC($ccMailId);
                }
            }
            
            //bcc mail
            $bcc_mail_id = explode(',', $jsonData['bcc_mail']);
            foreach($bcc_mail_id as $bccMailId)
            {
                if($bccMailId != ''){
                    $mail->addBCC($bccMailId);
                }
            }
            
            if($jsonData['attachment'] == 'true'){
                $mail->AddAttachment($jsonData['attached_file'], $jsonData['attached_name']);
            }
            $mail->IsHTML(true);
            $mail->Subject = $jsonData['subject'];
            $mail->Body = $jsonData['content']."<br><br>Regards,<div><br><img src=".$validate['mail_sign']."></img></div>";
            if($mail->Send()){
                $response = 'Success';
            }
            else
            {
                $response = 'Failure';
            }
                
            $imapStream = imap_open("{imap.gmail.com:993/imap/ssl}", $validate['config_mailid'], $validate['mail_pwd']);
            $result = imap_append($imapStream, "{imap.gmail.com:993/imap/ssl}[Gmail]/Sent Mail", $sentMimeMessage, "\\Seen");
            imap_close($imapStream);
        }
        return $response;
    }

}
?>