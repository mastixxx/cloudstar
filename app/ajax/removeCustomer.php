<?php
require_once 'db.php'; // The mysql database connection script

if(isset($_GET['customerName'])){
	$customerName = $_GET['customerName'];
}

$query=mysql_query("select id, name from sip_conf") or die(mysql_error());

$numOfDeletedDevices = 0;
# Collect the results
while($obj = mysql_fetch_object($query)) {
    
    $query=mysql_query("DELETE FROM extensions_table WHERE exten = ". $obj['name']) or die(mysql_error());
	$query=mysql_query("DELETE FROM sip_conf WHERE id =" .$obj['id']) or die(mysql_error());    
	$numOfDeletedDevices++;
}

$query=mysql_query("DELETE FROM customers WHERE name = " . $customerName) or die(mysql_error());

echo $numOfDeletedDevices;
?>