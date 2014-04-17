<?php

require_once 'db.php';

$dataToDecode = file_get_contents("php://input");
 error_log($dataToDecode, 0);
$data = json_decode($dataToDecode);

 
 

if(isset($data)){
    $name = $data->{'name'};
    $password = $data->{'password'};
    //$status = "0";
    //$created = time();
    $query=mysql_query("INSERT INTO sip_conf(name,secret,canreinvite,nat,allow,context)  VALUES ('$name', '$password', 'no','yes','h264','internal')") or die(mysql_error());

    $query2=mysql_query("INSERT INTO extensions_table(context, exten, priority, app, appdata)  VALUES ('internal', '$name', '1','Dial','SIP/$name,30')") or die(mysql_error());
}
if ($query){
echo "New entry created";    
}
else {
echo "It is a mystery, i wasnt able to save it ...fuck";    
}

?>