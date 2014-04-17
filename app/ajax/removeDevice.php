<?php

require_once 'db.php';

$devId = $_GET['id'];
$devName = $_GET['name'];
$query=mysql_query("DELETE FROM sip_conf WHERE id = $devId") or die(mysql_error());

$query=mysql_query("DELETE FROM extensions_table WHERE exten = $devName") or die(mysql_error());

echo "OK kamo";
?>