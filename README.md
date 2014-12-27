noolite-api-php-sdk-3
=====================

php SDK for noolite API

Example of usage

<?php

require_once "PR1132Gateway.php";

use Noolite\PR1132Gateway;

$gateway = new PR1132Gateway("192.168.1.1");

// get sensor data
$sensors = $gateway->LoadSensorData();

for($index = 0; $index < count($sensors); $index++) 
{
    $sensor = $sensors[$index];
    echo "sensor-". $index .": t=". $sensor->Temperature .", h=". $sensor->Humidity .", state=". $sensor->State ."\n";
}

echo PHP_EOL;

// receive gateway configuration	
$cfg = $gateway->LoadConfiguration();

foreach ($cfg->Groups as $k => $gr)
{
    if ($gr->Enabled)
    {
        echo Utf8ToWindows8Cmd($gr->Name)."\n";
    }
}

function Utf8ToWindows8Cmd($utf8_str)
{
    $s = "";
    
    $buf = unpack("C*", $utf8_str);
    
    for($i = 1; $i <= count($buf); $i++) 
    {        
        $c = $buf[$i];
        
        if($c == 208 || $c == 209) continue;
        
        
        if($c >= 176 && $c <= 191) // a - ï
        {
            $s .= chr($c - 16);
        }
        else if($c >= 128 && $c <= 143) // ð - ÿ
        {
            $s .= chr($c + 96);
        }
        else if($c >= 144 && $c <= 175) // À - ß
        {
            $s .= chr($c - 16);
        }      
        else if($c == 32) // space
        {
            $s.= chr($c);
        }
        
    }
    
    return $s;
}

?>

The result of work


