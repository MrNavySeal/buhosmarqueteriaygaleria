<?php
    class IpProvider implements LocationInterface{
        private $ip;
        private $format;
        private $fields;

        public function getLocation(string $ip,string $format,array $fields=[]){
            $this->ip = $ip;
            $this->format = $format;
            $this->fields = implode(",",$fields);
            $strField = $this->fields != "" ? "?fields=".$this->fields : "";
            $arrData = [];
            $url = "http://ip-api.com/$this->format/$this->ip".$strField;
            $response = file_get_contents($url);
            $arrData = json_decode($response,true);
            return $arrData;
        }    
    }
?>