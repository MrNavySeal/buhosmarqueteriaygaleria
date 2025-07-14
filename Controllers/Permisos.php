<?php
    class Permisos extends Controllers{
        public function __construct(){
            session_start();
            parent::__construct();
            sessionCookie();
        }
        public function permisos(){
            $data['page_tag'] = "Permisos";
            $data['page_title'] = "Permisos";
            $data['page_name'] = "permisos";
            $data['panelapp'] = "functions_modulos.js";
            $this->views->getView($this,"permisos",$data);
        }
    }
?>