<?php
    
    require_once("Models/ProductTrait.php");
    require_once("Models/CustomerTrait.php");
    require_once("Models/EnmarcarTrait.php");
    class Home extends Controllers{
        use CustomerTrait,EnmarcarTrait,ProductTrait;
        public function __construct(){
            session_start();
            parent::__construct();
            sessionCookie();
        }

        public function home(){
            $company = getCompanyInfo();
            $data['page_tag'] = $company['name'];
            $data['page_title'] = $company['name'];
            $data['productos'] = $this->getProductsT(8);
            $data['page_name'] = "home";
            $data['app'] = "functions_contact.js";
            $data['tipos'] = $this->selectTipos();
            $this->views->getView($this,"home",$data);
        }
    }
?>