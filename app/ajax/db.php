<?php 
define('DB_SERVER', '54.72.157.121');
define('DB_USERNAME', 'asterisk');
define('DB_PASSWORD', 'asterisk');
define('DB_DATABASE', 'asterisk');
$connection = mysql_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD) or die(mysql_error());
$database = mysql_select_db(DB_DATABASE) or die(mysql_error());
?>