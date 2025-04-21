<?php
    class Prueba extends Controllers{
        public function __construct(){
            session_start();
            
            if(empty($_SESSION['login'])){
                header("location: ".base_url());
                die();
            }
            parent::__construct();
            sessionCookie();
            getPermits(5);
        }
        public function prueba (){
            if($_SESSION['permitsModule']['r']){
                $data['page_tag'] = "Correo";
                $data['page_title'] = "Correo";
                $data['page_name'] = "correo";
                $data['panelapp'] = "functions_prueba.js";
                $this->views->getView($this,"prueba",$data);
            }else{
                header("location: ".base_url());
                die();
            }  
        }
    }
?>