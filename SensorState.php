<?php

namespace NooLite
{
    class SensorState 
    {
        const READY = 0;
        const UNBOUND = 1;
        const UNDEFINED = 2;
        const LOWPOWER = 3;
        
        private $state = '';
        
        function __construct($state)
        {
            $this->state = $state;
        }
        
        private $enums = array(
            SensorState::READY => 'Ready',
            SensorState::UNBOUND => 'Unbound',
            SensorState::UNDEFINED => 'Undefined',
            SensorState::LOWPOWER => 'Low Power',
        );
        
        public function __toString()
        {
            if(isset($this->enums[$this->state]))
            {
                return $this->enums[$this->state];    
            }
            else
            {
                return '';
            }
        }
    }
}
