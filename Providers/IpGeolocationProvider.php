<?php
    class IpGeolocationProvider implements LocationInterface{
        private $ip;
        private $key;

        public function getLocation(string $ip,string $format,array $fields=[]){
            $this->key = "f5dbd4464e734ac29e84d64fec64a1e7";
            $this->ip = $ip;
            $url = "https://api.ipgeolocation.io/ipgeo?apiKey=".$this->key."&ip=$this->ip";
            $cURL = curl_init();

            curl_setopt($cURL, CURLOPT_URL, $url);
            curl_setopt($cURL, CURLOPT_HTTPGET, true);
            curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($cURL, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Accept: application/json',
                'User-Agent: '.$_SERVER['HTTP_USER_AGENT']
            ));

            curl_exec($cURL);
            $arrData = [];
            $response = curl_exec($cURL);
            $data = json_decode($response,true);
            if(empty($data)){
                $arrData['status'] = "failed";
            }else{
                $arrData = [
                    "country"=>$data['country_name'],
                    "regionName"=>$data['state_prov'],
                    "city"=>$data['city'],
                    "zip"=>$data['zipcode'],
                    "lat"=>$data['latitude'],
                    "lon"=>$data['longitude'],
                    "timezone"=>$data['time_zone']['name'],
                    "isp"=>$data['isp'],
                    "org"=>$data['organization'],
                    "as"=>"",
                    "query"=>$data['ip'],
                    "status"=>"success",
                ];
            }
            return $arrData;
        }    
    }
?>