<?php
    class Opciones extends Controllers{
        public function __construct(){
            session_start();
            if(empty($_SESSION['login']) || $_SESSION['idUser'] != 1){
                header("location: ".base_url());
                die();
            }
            parent::__construct();
            sessionCookie();
        }
        public function opciones(){
            $data['botones'] = [
                "duplicar" => ["mostrar"=>true, "evento"=>"onClick","funcion"=>"mypop=window.open('".BASE_URL."/modulos/opciones"."','','');mypop.focus();"],
                "nuevo" => ["mostrar"=>true, "evento"=>"@click","funcion"=>"openModal()"],
            ];
            $data['page_tag'] = "Opciones | Modulos";
            $data['page_title'] = "Opciones | Modulos";
            $data['page_name'] = "opciones";
            $data['panelapp'] = "/Modulos/functions_opciones.js";
            $this->views->getView($this,"opciones",$data);
        }
        public function getDatos(){
            $request['modules'] = $this->model->selectModulos();
            $request['sections'] = $this->model->selectSecciones();
            echo json_encode($request,JSON_UNESCAPED_UNICODE);
        }
        public function setOpcion(){
            if($_POST){
                if(empty($_POST['name']) || empty($_POST['route']) || empty($_POST['module'])){
                    $arrResponse = array("status"=>false,"msg"=>"Los campos con (*) son obligatorios.");
                }else{
                    $strName = clear_cadena(strClean(ucfirst(strtolower($_POST['name']))));
                    $strRoute = clear_cadena(strClean($_POST['route']));
                    $intId = intval($_POST['id']);
                    $intModule = intval($_POST['module']);
                    $intSection = intval($_POST['section']);
                    $option = "";
                    if($intId==0){
                        $option = 1;
                        $request = $this->model->insertOpcion($strName,$intModule,$intSection,$strRoute);
                    }else{
                        $option = 2;
                        $request = $this->model->updateOpcion($intId,$strName,$intModule,$intSection,$strRoute);
                    }
                    if(is_numeric($request) && $request > 0){
                        if($option == 1){
                            $arrResponse = array("status"=>true,"msg"=>"Datos guardados correctamente.");
                        }else{
                            $arrResponse = array("status"=>true,"msg"=>"Datos actualizados correctamente.");
                        }
                    }else if($request=="existe"){
                        $arrResponse = array("status"=>false,"msg"=>"El módulo ya existe, inténte con otro nombre.");
                    }else{
                        $arrResponse = array("status"=>false,"msg"=>"No se ha podido guardar, intente de nuevo.");
                    }
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function getOpciones(){
            $intPage = intval($_POST['page']);
            $intPerPage = intval($_POST['per_page']);
            $strSearch = strClean($_POST['search']);
            $request = $this->model->selectOpciones($intPage,$intPerPage,$strSearch);
            echo json_encode($request,JSON_UNESCAPED_UNICODE);
            die();
        }
        public function getOpcion(){
            if($_POST){
                $intId = intval($_POST['id']);
                $request = $this->model->selectOpcion($intId);
                if(!empty($request)){
                    $arrResponse = array("status"=>true,"data"=>$request);
                }else{
                    $arrResponse = array("status"=>false,"msg"=>"Datos no encontratos.");
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                die();
            }
        }
        public function delOpcion(){
            if($_POST){
                $intId = intval($_POST['id']);
                $request = $this->model->deleteOpcion($intId);
                if(!empty($request)){
                    $arrResponse = array("status"=>true,"msg"=>"Se ha eliminado correctamente");
                }else{
                    $arrResponse = array("status"=>false,"msg"=>"No se ha podido eliminar.");
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                die();
            }
        }
    }
?>