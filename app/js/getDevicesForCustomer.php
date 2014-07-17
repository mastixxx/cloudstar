<?
require_once 'db.php'; // The mysql database connection script

if(isset($_GET['status'])){
	$customerName = $_GET['customerName'];
}
$query=mysql_query("SELECT sip_conf.ID, sip_conf.NAME, sip_conf.USERAGENT, sip_conf.FULLCONTACT, sip_conf.IPADDR 
					FROM sip_conf 
					INNER JOIN customers ON sip_conf.customerId = customers.id
					WHERE customers.name ='$customerName'") or die(mysql_error());

# Collect the results
while($obj = mysql_fetch_object($query)) {
    $arr[] = $obj;
}

# JSON-encode the response
echo $json_response = json_encode($arr);
?>