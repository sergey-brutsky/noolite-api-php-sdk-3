<?php

namespace Noolite {

    require_once "PR1132Configuration.php";    
    require_once "PR1132SensorData.php";
    require_once "SensorState.php";

    use NooLite\PR1132Configuration;
    use NooLite\PR1132SensorData;
    use NooLite\SensorState;   
    
    class PR1132Gateway
    {
        private $_host = "";
        
        function __construct($host) {
            $this->_host = $host;
        }
        
        public function LoadSensorData()
        {
            $response = simplexml_load_file("http://" . $this->_host . "/sens.xml");
            //$response = simplexml_load_file("sens.xml");
            
            $sensorsNumber = count($response)/3;
            
            if($sensorsNumber < 1) return array();
            
            $sensorsData = array();
            
            for($i = 0; $i < $sensorsNumber; $i++)
            {
                $t = (string)($response->{'snst'.$i});
                
                if($t == "-") $t = "";
                
                $h = (string)($response->{'snsh'.$i});
                
                if($h == "-") $h = "";
                
                $s = new SensorState((string)($response->{'snt'.$i}));
                                
                $sensorsData[] = new PR1132SensorData($t, $h, $s);
            }
            
            return $sensorsData;
        }
        
        public function LoadConfiguration()
        {
            $filename = "http://". $this->_host . "/noolite_settings.bin";
            $handle = fopen($filename, "rb"); 
            
            fseek($handle, 6);
            
            $cfg = new PR1132Configuration();
            
            for ($index = 0; $index < 16; ++$index)
            {
                $contentPart = fread($handle, 32);
                
                $group = new PR1132ControlGroup();
                $group->Name = trim(iconv("windows-1251", "utf-8", substr($contentPart, 0, 24)));
                                
                $buf = unpack("C*", $contentPart);
                
                $enabled = $group->Enabled = $buf[25] < 64; // < 64 - enabled, > 64 - doesn't
                
                for ($i = 0; $i < 4; ++$i)
                {
                    $group->Sensors[$i] = $buf[26 + $i] >= 64;
                }
                
                for ($i = 0; $i < 8; ++$i)
                {
                    $group->ChannelNumbers[$i] = $buf[25 + $i] & 63;
                }
                
                $cfg->Groups[] = $group;
            }
            
            fclose($handle);
            
            return $cfg;
        }
    }
}