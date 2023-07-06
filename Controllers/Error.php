<?php
    Class Errors extends Controllers{
        public function __construct(){
            session_start();
            parent::__construct();
            sessionCookie();
        }

        public function notFound(){
            $data['page_tag'] = "Error";
            $data['page_title'] = "Error";
            $data['page_name'] = "error";
            $this->views->getView($this,"error",$data);
        }
    }
    $error = new Errors();
    $error->notFound();
?>