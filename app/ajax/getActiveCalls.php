<?php


$str = shell_exec('./getActiveCalls.sh');

//$str = "5 active calls"; 
preg_match('/(?P<calls>\d+) (?P<name>[\w\s]+)/', $str, $matches);

/* This also works in PHP 5.2.2 (PCRE 7.0) and later, however 
 * the above form is recommended for backwards compatibility */
// preg_match('/(?<name>\w+): (?<digit>\d+)/', $str, $matches);

echo $json_response = json_encode($matches);

?>
