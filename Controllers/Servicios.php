<?php
    require_once("Models/PaginaTrait.php");
    class Servicios extends Controllers{
        use PaginaTrait;
        public function __construct(){
            session_start();
            parent::__construct();
            sessionCookie();
        }
        public function servicios(){
            $company=getCompanyInfo();
            $data['services'] = $this->selectServices();
            $data['page_tag'] = $company['name'];
            $data['page_name'] = "servicios";
            $data['page_title'] ="Servicios | ".$company['name'];
            $this->views->getView($this,"servicios",$data); 
        }
        public function servicio($params){
            $company=getCompanyInfo();
            $params = strClean($params);
            $data['service'] = $this->selectService($params);
            if(!empty($data['service'])){
                $data['page_tag'] = $company['name'];
                $data['page_name'] = "servicio";
                $data['page_title'] =$data['service']['name']." | ".$company['name'];
                $this->views->getView($this,"servicio",$data); 
            }else{
                header("location: ".base_url()."/error");
                die();
            }
        }
    }
?>