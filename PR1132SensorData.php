<?php

namespace NooLite
{
    class PR1132SensorData
    {
        public $Temperature = '';
        public $Humidity = '';
        public $State = '';
        
        function __construct($temperature, $humidity, $state)
        {
            $this->Temperature = $temperature;
            $this->Humidity = $humidity;
            $this->State = $state;
        }        
    }
}
