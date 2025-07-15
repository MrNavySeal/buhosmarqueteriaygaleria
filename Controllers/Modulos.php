<?php
    class Modulos extends Controllers{
        public function __construct(){
            session_start();
            parent::__construct();
            sessionCookie();
        }
        public function modulos(){
            $data['botones'] = [
                "duplicar" => ["mostrar"=>true, "evento"=>"onClick","funcion"=>"mypop=window.open('".BASE_URL."/modulos"."','','');mypop.focus();"],
                "nuevo" => ["mostrar"=>true, "evento"=>"@click","funcion"=>"common.showModalModule = true"],
            ];
            $data['page_tag'] = "Modulos";
            $data['page_title'] = "Modulos";
            $data['page_name'] = "permisos";
            $data['panelapp'] = "functions_modulos.js";
            $this->views->getView($this,"modulos",$data);
        }
        public function setModulo(){
            if($_POST){
                if(empty($_POST['name'])){
                    $arrResponse = array("status"=>false,"msg"=>"El nombre no puede estar vacío.");
                }else{
                    $strName = clear_cadena(strClean(ucfirst(strtolower($_POST['name']))));
                    $intId = intval($_POST['id']);
                    $option = "";
                    if($intId==0){
                        $option = 1;
                        $request = $this->model->insertModulo($strName);
                    }else{
                        $option = 2;
                        $request = $this->model->updateModulo($intId,$strName);
                    }
                    if($request > 0){
                        if($option == 1){
                            $arrResponse = array("status"=>true,"msg"=>"Datos guardados correctamente.");
                        }else{
                            $arrResponse = array("status"=>true,"msg"=>"Datos actualizados correctamente.");
                        }
                    }else if($request=="existe"){
                        $arrResponse = array("status"=>false,"msg"=>"El módulo ya existe, inténte con otr nombre.");
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
    }
?>