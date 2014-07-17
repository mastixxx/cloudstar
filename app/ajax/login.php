<?php

require_once 'db.php';

$dataToDecode = file_get_contents("php://input");
$data = json_decode($dataToDecode);

$name = $data->{'name'};
$password = $data->{'password'};

$query=mysql_query("select NAME, PASSWORD from users where name = '$name'") or die(mysql_error());

$row = mysql_fetch_assoc($query);


if ($row){
    
    if (strcmp ( $row['PASSWORD'] ,$password ) == 0 ){
        echo $json_response = json_encode("OK");
    }
    else {
        echo $json_response = json_encode("nicht OK");
    }   
}
else {
    echo $json_response = json_encode("nicht OK");
}




?>