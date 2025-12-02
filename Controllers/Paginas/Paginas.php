<?php
    require_once("Models/CategoryTrait.php");
    class Paginas extends Controllers{
        use CategoryTrait;
        public function __construct(){
            session_start();
            parent::__construct();
            sessionCookie();
        }

        public function faqs(){
            setView(BASE_URL."/paginas/faqs/");
            $company = getCompanyInfo();
            $data['page_tag'] = $company['name'];
            $data['page_title'] = "Preguntas Frecuentes - FAQs";
            $data['page_name'] = "Preguntas Frecuentes - FAQs";
            $data['faqs'] = $this->getFaqs();
            $data['app'] = "";
            $this->views->getView($this,"faqs",$data);
        }
    }
?>