<?php
require_once 'db.php';

$dataToDecode = file_get_contents("php://input");
 error_log($dataToDecode, 0);
$data = json_decode($dataToDecode);

$arrSuccesfullyCreatedStations = array();
$arrNotCreatedStations = array();
if(isset($data)){
    
    $customerName = $data->{'customerName'};
    
    $devices = $data->{'devices'};
    $numDevices = count($devices);
    
    foreach ($devices as $jsonDevice){
    
        $name = $jsonDevice->{'name'};
        
        $password = $jsonDevice->{'secret'};

        $devId = $jsonDevice->{'id'};

        $previousName = getDeviceNameWithId($devId);
        
        if($name && $password){

            

            $query=mysql_query("UPDATE sip_conf
                                SET name='$name', secret='$password'
                                WHERE id=$devId") or die(mysql_error());



            $query2=mysql_query("UPDATE extensions_table 
                                 SET exten='" . $name . "',appdata='SIP/" . $name . ",30'
                                 WHERE exten='" . $previousName . "'") or die(mysql_error());
            
            $arrSuccesfullyCreatedStations[] = $name;
        }
    
        
        
    }
}
$responseMessage = '{"updatedStations":[';
if (count($arrSuccesfullyCreatedStations)>0){
    foreach ($arrSuccesfullyCreatedStations as $station) {
        $responseMessage .= '"' . $station. '",';    
    }
    $responseMessage = substr_replace($responseMessage, '', -1); // to get rid of extra comma
   
}

$responseMessage .= '],"errorStations":[';
if (count($arrNotCreatedStations)>0){
    foreach ($arrNotCreatedStations as $station) {
    $responseMessage .= '"' . $station. '",';    
    }
    $responseMessage = substr_replace($responseMessage, '', -1); // to get rid of extra comma
}

$responseMessage .= "]}";


echo $responseMessage;


function getDeviceNameWithId($_id){
 $vysledek=mysql_query("select * from sip_conf where id='$_id'");
    
    $numRows=mysql_num_rows($vysledek);
    if ($numRows>0){
  
        $record=MySQL_Fetch_Array($vysledek);
        return $record["name"];
    }
    else{
        
        return -1; //zadny zaznam neexistuje
    }

}


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