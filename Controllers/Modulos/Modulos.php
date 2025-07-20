<?php
    class Modulos extends Controllers{
        public function __construct(){
            session_start();
            if(empty($_SESSION['login']) || $_SESSION['idUser'] != 1){
                header("location: ".base_url());
                die();
            }
            parent::__construct();
            sessionCookie();
        }
        public function modulos(){
            $data['botones'] = [
                "duplicar" => ["mostrar"=>true, "evento"=>"onClick","funcion"=>"mypop=window.open('".BASE_URL."/modulos"."','','');mypop.focus();"],
                "nuevo" => ["mostrar"=>true, "evento"=>"@click","funcion"=>"openModal()"],
            ];
            $data['page_tag'] = "Modulos";
            $data['page_title'] = "Modulos";
            $data['page_name'] = "modulos";
            $data['panelapp'] = "/Modulos/functions_modulos.js";
            $this->views->getView($this,"modulos",$data);
        }
        public function setModulo(){
            if($_POST){
                if(empty($_POST['name'])){
                    $arrResponse = array("status"=>false,"msg"=>"El nombre no puede estar vacío.");
                }else{
                    $strName = clear_cadena(strClean(ucfirst(strtolower($_POST['name']))));
                    $intId = intval($_POST['id']);
                    $intStatus = intval($_POST['status']);
                    $intLevel = intval($_POST['level']);
                    $strIcon = strClean($_POST['icon']);
                    $option = "";
                    if($intId==0){
                        $option = 1;
                        $request = $this->model->insertModulo($strName,$strIcon,$intLevel,$intStatus);
                    }else{
                        $option = 2;
                        $request = $this->model->updateModulo($intId,$strName,$strIcon,$intLevel,$intStatus);
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
        public function getModulos(){
            $intPage = intval($_POST['page']);
            $intPerPage = intval($_POST['per_page']);
            $strSearch = strClean($_POST['search']);
            $request = $this->model->selectModulos($intPage,$intPerPage,$strSearch);
            echo json_encode($request,JSON_UNESCAPED_UNICODE);
            die();
        }
        public function getModulo(){
            if($_POST){
                $intId = intval($_POST['id']);
                $request = $this->model->selectModulo($intId);
                if(!empty($request)){
                    $arrResponse = array("status"=>true,"data"=>$request);
                }else{
                    $arrResponse = array("status"=>false,"msg"=>"Datos no encontratos.");
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                die();
            }
        }
        public function delModulo(){
            if($_POST){
                $intId = intval($_POST['id']);
                $request = $this->model->deleteModulo($intId);
                if($request =="ok"){
                    $arrResponse = array("status"=>true,"msg"=>"Se ha eliminado correctamente");
                }else if($request=="existe"){
                    $arrResponse = array("status"=>false,"msg"=>"El módulo contiene secciones, debe eliminarlas primero.");
                }else{
                    $arrResponse = array("status"=>false,"msg"=>"No se ha podido eliminar.");
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                die();
            }
        }
    }
?>