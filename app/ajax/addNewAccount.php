<?php

require_once 'db.php';



$dataToDecode = file_get_contents("php://input");
 error_log($dataToDecode, 0);
$data = json_decode($dataToDecode);

 
 

if(isset($data)){
    
    
    $customerName = $data->{'customerName'};
    
    $customerNameId = getCustomerNameDatabaseId($customerName);
        
    if ($customerNameId<0){
        
        $query=mysql_query("INSERT INTO customers(name)  VALUES ('$customerName')") or die(mysql_error());
        $customerNameId = getCustomerNameDatabaseId($customerName);
       
    }
    
    
    $devices = $data->{'devices'};
    $numDevices = count($devices);
    
    foreach ($devices as $jsonDevice){
    
        $name = $customerName ."-" . $jsonDevice->{'name'};
        
        $password = $jsonDevice->{'password'};
        
        if (!isSipConfNameExistsWithName($name) && !isExtensionRecordExistsWithName($name)){
            if($name && $password){

                $query=mysql_query("INSERT INTO sip_conf(name,secret,host,canreinvite,nat,allow,context,customerId) VALUES ('$name', '$password','dynamic', 'no','yes','h264','internal','$customerNameId')") or die(mysql_error());

                $query2=mysql_query("INSERT INTO extensions_table(context, exten, priority, app, appdata) VALUES ('internal', '$name', '1','Dial','SIP/$name,30')") or die(mysql_error());
                
                $arrSuccesfullyCreatedStations[] = $name;
            }
        }
        else{
            
            $arrNotCreatedStations[]= $name;
        }
        
    }
}
$responseMessage = '{"createdStations":[';
if (count($arrSuccesfullyCreatedStations)>0){
    foreach ($arrSuccesfullyCreatedStations as $station) {
        $responseMessage .= '"' . $station. '",';    
    }
    $responseMessage = substr_replace($responseMessage, '', -1); // to get rid of extra comma
   
}

$responseMessage .= '],"wrongStations":[';
if (count($arrNotCreatedStations)>0){
    foreach ($arrNotCreatedStations as $station) {
    $responseMessage .= '"' . $station. '",';    
    }
    $responseMessage = substr_replace($responseMessage, '', -1); // to get rid of extra comma
}

$responseMessage .= "]}";


echo $responseMessage;




function getCustomerNameDatabaseId($_customerName){
    
    $vysledek=mysql_query("select * from customers where name='$_customerName'");
    
    $numRows=mysql_num_rows($vysledek);
    if ($numRows>0){
  
        $record=MySQL_Fetch_Array($vysledek);
        return $record["id"];
    }
    else{
        
        return -1; //zadny zaznam neexistuje
    }
    
}

function isSipConfNameExistsWithName($_name){
    $vysledek=mysql_query("select * from sip_conf where name='$_name'");
    $numRows=mysql_num_rows($vysledek);
    if ($numRows>0){
        return TRUE;
    }
    else {
        return FALSE;
    }
    
}
function isExtensionRecordExistsWithName($_name){
    $vysledek=mysql_query("select * from extensions_table where exten='$_name'");
    $numRows=mysql_num_rows($vysledek);
    if ($numRows>0){
        return TRUE;
    }
    else {
        return FALSE;
    }
}

?>