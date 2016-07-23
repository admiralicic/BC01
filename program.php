<?php 

if(count($argv) !== 4){
    die("Invalid number of parameters!".PHP_EOL);
}

if(!file_exists($argv[2])){
    die($argv[2]." file doesn't exist!".PHP_EOL);
}

if(!filter_var($argv[3], FILTER_VALIDATE_IP)){
    die("Invalid ip format!".PHP_EOL);
}

$ip_address = $argv[3];
$networks = explode("\n", file_get_contents($argv[2]));

$convert_ip_to_int = function($ip){

    $parts = explode(".", $ip);

    return  (int)$parts[0] * (pow(2,24)-1) +
            (int)$parts[1] * (pow(2,16)-1) +
            (int)$parts[2] * (pow(2,8)-1) +
            (int)$parts[3] * (pow(2,0));
};

$ip_address = $convert_ip_to_int($ip_address);
// echo $ip_address.PHP_EOL;
foreach ($networks as $network) {
    $parts = explode("/", $network);
    $netaddress = $parts[0];
    $subnet = $parts[1];

    $rangeStart = $convert_ip_to_int($netaddress);
    $rangeEnd = $rangeStart + (pow(2,32-(int)$subnet));

    // echo $rangeStart." ---- ".$rangeEnd.PHP_EOL;
    if($ip_address >= $rangeStart && $ip_address <= $rangeEnd){
        echo $network.PHP_EOL;
    }
}