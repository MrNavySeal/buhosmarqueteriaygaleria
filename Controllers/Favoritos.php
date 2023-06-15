<?php
    
    require_once("Models/ProductTrait.php");
    //require_once("Models/CategoryTrait.php");
    class Favoritos extends Controllers{
        use ProductTrait;
        public function __construct(){
            session_start();
            if(empty($_SESSION['login'])){
                header("location: ".base_url());
                die();
            }
            parent::__construct();
        }

        public function Favoritos(){
            $company=getCompanyInfo();
            $data['page_tag'] = $company['name'];
            $data['page_title'] = "Mis Favoritos | ".$company['name'];;
            $data['page_name'] = "favoritos";
            $data['products'] = $this->getProductsFavorites($_SESSION['idUser']);
            $data['app'] = "functions_wishlist.js";
            $this->views->getView($this,"favoritos",$data);
        }
    }
?>