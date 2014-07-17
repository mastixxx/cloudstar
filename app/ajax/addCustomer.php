<?php
require_once 'db.php'; // The mysql database connection script

$dataToDecode = file_get_contents("php://input");

$data = json_decode($dataToDecode);

if(isset($data)){
    
    $customerName = $data->{'customerName'};
    $contact = $data->{'contact'};

    $insertQuery=mysql_query("	INSERT INTO customers(name, contact) 
 						 		VALUES ('$customerName', '$contact') ") or die(mysql_error());

}



?>