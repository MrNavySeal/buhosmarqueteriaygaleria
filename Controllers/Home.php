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
            $data['productos'] = $this->getProductsT(8);
            $data['banners'] = $this->getBanners();
            $data['page_name'] = "home";
            $data['app'] = "functions_home.js";
            $data['categories'] = $this->getProductsCategories("15,25,21",24);
            $data['posts'] = $this->getArticlesT(3);
            $data['tipos'] = $this->selectTipos();
            $this->views->getView($this,"home",$data);
        }
        public function getProductsCategories(string $categories,int $qty){
            $categories = $this->getCategoriesShowT($categories);
            for ($i=0; $i < count($categories) ; $i++) { 
                $categories[$i]['products'] = $this->getProductsByCat($categories[$i]['idcategory'],$qty);
            }
            return $categories;
        }
    }
?>