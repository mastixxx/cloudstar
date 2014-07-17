<?
require_once 'db.php'; // The mysql database connection script

if(isset($_GET['customerName'])){
	$customerName = $_GET['customerName'];
}
$query=mysql_query("SELECT sip_conf.id, sip_conf.name, sip_conf.useragent, sip_conf.fullcontact, sip_conf.ipaddr, sip_conf.secret
					FROM sip_conf 
					INNER JOIN customers ON sip_conf.customerId = customers.id
					WHERE customers.name ='$customerName'") or die(mysql_error());

$arr = array();
# Collect the results
while($obj = mysql_fetch_object($query)) {
    array_push($arr, $obj);
}

# JSON-encode the response
echo $json_response = json_encode($arr);
?>