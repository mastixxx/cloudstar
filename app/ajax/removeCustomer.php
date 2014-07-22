<?php
require_once 'db.php'; // The mysql database connection script

$dataToDecode = file_get_contents("php://input");
 error_log($dataToDecode, 0);
$data = json_decode($dataToDecode);
$customerName = $data->{'customerName'};
error_log($customerName);
$query=mysql_query("SELECT sip_conf.id, sip_conf.name 
					FROM sip_conf 
					INNER JOIN customers ON sip_conf.customerId = customers.id
					WHERE customers.name ='" . $customerName . "'") or die(mysql_error("asdasd"));

$numOfDeletedDevices = 0;
# Collect the results
while($obj = mysql_fetch_object($query)) {
    
    error_log("removing from extension table with exten: '".$obj->name  . "'");
    $query=mysql_query("DELETE FROM extensions_table WHERE exten = '". $obj->name . "'") or die(mysql_error());
	$query=mysql_query("DELETE FROM sip_conf WHERE id =" .$obj->id) or die(mysql_error());    
	$numOfDeletedDevices++;
}

$query=mysql_query("DELETE FROM customers WHERE name = '" . $customerName . "'") or die(mysql_error());

echo $numOfDeletedDevices;
?>