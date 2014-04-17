<?php
    $url = 'http://54.72.168.180/asteriskConfig/app/ajax/getSipStations.php';
    $htm = file_get_contents($url);
    echo $htm;
?>