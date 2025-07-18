<?php
    class Roles extends Controllers{
        public function __construct(){
            session_start();
            if(empty($_SESSION['login']) || $_SESSION['idUser'] != 1){
                header("location: ".base_url());
                die();
            }
            parent::__construct();
            sessionCookie();
        }
        public function roles(){
            $data['botones'] = [
                "duplicar" => ["mostrar"=>true, "evento"=>"onClick","funcion"=>"mypop=window.open('".BASE_URL."/sistema/roles"."','','');mypop.focus();"],
                "nuevo" => ["mostrar"=>true, "evento"=>"@click","funcion"=>"openModal()"],
            ];
            $data['page_tag'] = "Roles | Sistema";
            $data['page_title'] = "Roles | Sistema";
            $data['page_name'] = "roles";
            $data['panelapp'] = "/Sistema/functions_roles.js";
            $this->views->getView($this,"roles",$data);
        }
        public function getPermisos(){
            if($_POST){
                $intId = intval($_POST['id']);
                $request = $this->model->selectPermisos($intId);
                $arrResponse = [
                    "data"=>$request,
                    "r"=>!empty(array_filter($request,function($e){return $e['r'];})),
                    "w"=>!empty(array_filter($request,function($e){return $e['w'];})),
                    "u"=>!empty(array_filter($request,function($e){return $e['u'];})),
                    "d"=>!empty(array_filter($request,function($e){return $e['d'];})),
                ];
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function setPermisos(){
            if($_POST){
                $arrData = json_decode($_POST['data'],true);
                $intId = intval($_POST['id']);
                $request = $this->model->insertPermisos($intId,$arrData);
                if(is_numeric($request) && $request > 0){
                    $arrResponse = array("status"=>true,"msg"=>"Permisos asignados correctamente.");
                }else{
                    $arrResponse = array("status"=>false,"msg"=>"No se ha podido guardar, intente de nuevo.");
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function setRol(){
            if($_POST){
                if(empty($_POST['name'])){
                    $arrResponse = array("status"=>false,"msg"=>"El nombre no puede estar vacío.");
                }else{
                    $strName = clear_cadena(strClean(ucfirst(strtolower($_POST['name']))));
                    $intId = intval($_POST['id']);
                    $option = "";
                    if($intId==0){
                        $option = 1;
                        $request = $this->model->insertRol($strName);
                    }else{
                        $option = 2;
                        $request = $this->model->updateRol($intId,$strName);
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
        public function getRoles(){
            $intPage = intval($_POST['page']);
            $intPerPage = intval($_POST['per_page']);
            $strSearch = strClean($_POST['search']);
            $request = $this->model->selectRoles($intPage,$intPerPage,$strSearch);
            echo json_encode($request,JSON_UNESCAPED_UNICODE);
            die();
        }
        public function getRol(){
            if($_POST){
                $intId = intval($_POST['id']);
                $request = $this->model->selectRol($intId);
                if(!empty($request)){
                    $arrResponse = array("status"=>true,"data"=>$request);
                }else{
                    $arrResponse = array("status"=>false,"msg"=>"Datos no encontratos.");
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                die();
            }
        }
        public function delRol(){
            if($_POST){
                $intId = intval($_POST['id']);
                $request = $this->model->deleteRol($intId);
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