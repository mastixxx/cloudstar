<?
require_once 'db.php'; // The mysql database connection script

$query=mysql_query("select id, name, contact from customers") or die(mysql_error());

# Collect the results
while($obj = mysql_fetch_object($query)) {
    $arr[] = $obj;
}

# JSON-encode the response
echo $json_response = json_encode($arr);
?>