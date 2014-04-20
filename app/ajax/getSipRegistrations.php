<?php


$str = shell_exec('./getSipPeers.sh');

//$str = "1 sip peers [Monitored: 0 online, 0 offline Unmonitored: 1 online, 0 offline]"; 
preg_match('/.*Unmonitored:\s(?P<online>\d+)\sonline,\s(?P<offline>\d+).*/', $str, $matches);

/* This also works in PHP 5.2.2 (PCRE 7.0) and later, however 
 * the above form is recommended for backwards compatibility */
// preg_match('/(?<name>\w+): (?<digit>\d+)/', $str, $matches);


echo $json_response = json_encode($matches);

?>