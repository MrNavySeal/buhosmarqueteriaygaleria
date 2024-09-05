<?php
    class Inventario extends Controllers{
        public function __construct(){
            session_start();
            if(empty($_SESSION['login'])){
                header("location: ".base_url());
                die();
            }
            parent::__construct();
            sessionCookie();
            getPermits(4);
            
        }
        public function inventario(){
            if($_SESSION['permitsModule']['r']){
                $data['page_tag'] = "inventario";
                $data['page_title'] = "Inventario | Panel";
                $data['page_name'] = "inventario";
                $data['panelapp'] = "functions_inventory.js";
                $this->views->getView($this,"inventario",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function getProducts(){
            if($_SESSION['permitsModule']['r']){
                $request = $this->model->selectProducts();
                echo json_encode($request,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
    }

?>