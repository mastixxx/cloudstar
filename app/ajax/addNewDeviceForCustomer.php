<?php

require_once 'db.php'; // The mysql database connection script

if(isset($_GET['customerId'])){
	$customerId = $_GET['customerId'];
}
 $getCustomernameQuery = mysql_query("SELECT name FROM customers WHERE id=" . $customerId) or die(mysql_error());

 $obj = mysql_fetch_object($getCustomernameQuery);

 $customerName = $obj->name ."-". generateRandomString(5);


 $insertQuery=mysql_query("INSERT INTO sip_conf(name,secret,host,canreinvite,nat,allow,context,customerId) 
 					 VALUES ('$customerName', '','dynamic', 'no','yes','h264','internal'," . $customerId . ")") or die(mysql_error());

 $query2=mysql_query("INSERT INTO extensions_table(context, exten, priority, app, appdata) VALUES ('internal', '$customerName', '1','Dial','SIP/$customerName,30')") or die(mysql_error());
                

function generateRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}


?>