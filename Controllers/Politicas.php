<?php
    require_once("Models/PaginaTrait.php");
    class Politicas extends Controllers{
        use PaginaTrait;
        public function __construct(){
            session_start();
            parent::__construct();
            sessionCookie();
        }
        public function privacidad(){
            $company=getCompanyInfo();
            $data['page'] = $this->selectPage(2);
            $data['page_tag'] = $company['name'];
            $data['page_name'] = "privacidad";
            $data['page_title'] =$data['page']['name']." | ".$company['name'];
            $this->views->getView($this,"politica",$data); 
        }
        public function terminos(){
            $company=getCompanyInfo();
            $data['page'] = $this->selectPage(3);
            $data['page_tag'] = $company['name'];
            $data['page_name'] = "terminos";
            $data['page_title'] =$data['page']['name']." | ".$company['name'];
            $this->views->getView($this,"politica",$data); 
        }
    }
?>