<?php
    class MarqueteriaConfiguracion extends Controllers{
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
        public function configuracion(){
            if($_SESSION['permitsModule']['r']){
                $data['page_tag'] = "Configuración";
                $data['page_title'] = "Configuracion de categorias | Marquetería";
                $data['page_name'] = "configuracion";
                $data['panelapp'] = "functions_molding_config.js";
                $this->views->getView($this,"configuracion",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function getCategories(){
            if($_SESSION['permitsModule']['r']){
                $request = $this->model->selectCategories();
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 
                        $btnEdit = "";
                        if($_SESSION['permitsModule']['u']){
                            $btnEdit='<button class="btn btn-secondary me-2" type="button" title="Configurar" onclick="editItem('.$request[$i]['id'].')"><i class="fas fa-key"></i></button>';
                        }
                        $request[$i]['options'] = $btnEdit;
                    }
                }
                echo json_encode($request,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function getData(){
            if($_SESSION['permitsModule']['u']){
                $arrFraming = $this->model->selectCatFraming();
                $arrProperties = $this->model->selectProperties();
                $arrData = array(
                    "framing"=>$arrFraming,
                    "properties"=>$arrProperties
                );
                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
            }   
            die();
        }
    }

?>