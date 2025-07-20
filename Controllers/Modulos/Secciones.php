<?php
    class Secciones extends Controllers{
        public function __construct(){
            session_start();
            if(empty($_SESSION['login']) || $_SESSION['idUser'] != 1){
                header("location: ".base_url());
                die();
            }
            parent::__construct();
            sessionCookie();
        }
        public function secciones(){
            $data['botones'] = [
                "duplicar" => ["mostrar"=>true, "evento"=>"onClick","funcion"=>"mypop=window.open('".BASE_URL."/modulos/secciones"."','','');mypop.focus();"],
                "nuevo" => ["mostrar"=>true, "evento"=>"@click","funcion"=>"openModal()"],
            ];
            $data['page_tag'] = "Secciones | Modulos";
            $data['page_title'] = "Secciones | Modulos";
            $data['page_name'] = "secciones";
            $data['panelapp'] = "/Modulos/functions_secciones.js";
            $this->views->getView($this,"secciones",$data);
        }
        public function getDatos(){
            $request = $this->model->selectModulos();
            echo json_encode($request,JSON_UNESCAPED_UNICODE);
        }
        public function setSeccion(){
            if($_POST){
                if(empty($_POST['name']) || empty($_POST['module'])){
                    $arrResponse = array("status"=>false,"msg"=>"Los campos con (*) son obligatorios.");
                }else{
                    $strName = clear_cadena(strClean(ucfirst(strtolower($_POST['name']))));
                    $intId = intval($_POST['id']);
                    $intModule = intval($_POST['module']);
                    $intStatus = intval($_POST['status']);
                    $intLevel = intval($_POST['level']);
                    $option = "";
                    if($intId==0){
                        $option = 1;
                        $request = $this->model->insertSeccion($strName,$intModule,$intLevel,$intStatus);
                    }else{
                        $option = 2;
                        $request = $this->model->updateSeccion($intId,$strName,$intModule,$intLevel,$intStatus);
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
        public function getSecciones(){
            $intPage = intval($_POST['page']);
            $intPerPage = intval($_POST['per_page']);
            $strSearch = strClean($_POST['search']);
            $request = $this->model->selectSecciones($intPage,$intPerPage,$strSearch);
            echo json_encode($request,JSON_UNESCAPED_UNICODE);
            die();
        }
        public function getSeccion(){
            if($_POST){
                $intId = intval($_POST['id']);
                $request = $this->model->selectSeccion($intId);
                if(!empty($request)){
                    $arrResponse = array("status"=>true,"data"=>$request);
                }else{
                    $arrResponse = array("status"=>false,"msg"=>"Datos no encontratos.");
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                die();
            }
        }
        public function delSeccion(){
            if($_POST){
                $intId = intval($_POST['id']);
                $request = $this->model->deleteSeccion($intId);
                if($request=="ok"){
                    $arrResponse = array("status"=>true,"msg"=>"Se ha eliminado correctamente");
                }else if($request=="existe"){
                    $arrResponse = array("status"=>false,"msg"=>"La sección contiene opciones, debe eliminarlas primero.");
                }else{
                    $arrResponse = array("status"=>false,"msg"=>"No se ha podido eliminar.");
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                die();
            }
        }
    }
?>