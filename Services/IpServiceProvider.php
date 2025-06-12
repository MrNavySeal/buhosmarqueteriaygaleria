<?php
    class IpServiceProvider{
        private $location;
        public function __construct(LocationInterface $location,$ip,$format="json",$fields = []){
            $this->location = $location->getLocation($ip,$format,$fields);
        }
        public function getLocation(){
            return $this->location;
        }
    }
?>