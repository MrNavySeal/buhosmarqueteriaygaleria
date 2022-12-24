<?php
    require_once("Models/PaginaTrait.php");
    class Nosotros extends Controllers{
        use PaginaTrait;
        public function __construct(){
            session_start();
            parent::__construct();
            sessionCookie();
        }
        public function nosotros(){
            $company=getCompanyInfo();
            $data['page'] = $this->selectPage(1);
            $data['page_tag'] = $company['name'];
            $data['page_name'] = "nosotros";
            $data['page_title'] =$data['page']['name']." | ".$company['name'];
            $this->views->getView($this,"nosotros",$data); 
        }
    }
?>