<?php
    
    require_once("Models/ProductTrait.php");
    require_once("Models/CustomerTrait.php");
    require_once("Models/EnmarcarTrait.php");
    require_once("Models/BlogTrait.php");
    require_once("Models/CategoryTrait.php");
    class Home extends Controllers{
        use CustomerTrait,EnmarcarTrait,ProductTrait,BlogTrait,CategoryTrait;
        public function __construct(){
            session_start();
            parent::__construct();
            sessionCookie();
        }

        public function home(){
            $company = getCompanyInfo();
            $data['page_tag'] = $company['name'];
            $data['page_title'] = $company['name'];
            $data['productos'] = $this->getProductsT(4);
            $data['categorie1'] = $this->getProductsByCat(15,8);
            $data['categorie2'] = $this->getProductsByCat(18,8);
            $data['categorie3'] = $this->getProductsByCat(19,8);
            $data['page_name'] = "home";
            $data['app'] = "functions_contact.js";
            $data['categories'] = $this->getCategoriesShowT("15,18,19");
            $data['posts'] = $this->getArticlesT(3);
            $data['tipos'] = $this->selectTipos();
            $this->views->getView($this,"home",$data);
        }
    }
?>