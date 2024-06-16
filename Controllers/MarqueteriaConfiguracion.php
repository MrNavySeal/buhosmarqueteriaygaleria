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
        public function getConfig(){
            if($_SESSION['permitsModule']['u']){
                $id = intval($_POST['id']);
                $request = $this->model->selectConfig($id);
                if(!empty($request)){
                    $arrResponse = array("status"=>true,"data"=>$request);
                }else{
                    $arrResponse = array("status"=>false);
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }   
            die();
        }
        public function setConfig(){
            if($_SESSION['permitsModule']['u']){
                if($_POST){
                    if(empty($_POST['id']) || empty($_POST['props'])){
                        $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $intId = intval($_POST['id']);
                        $isCost = intval($_POST['is_cost']);
                        $isFrame = intval($_POST['is_frame']);
                        $isPrint = intval($_POST['is_print']);
                        $arrProps = json_decode($_POST['props'],true);
                        $photo = "";
                        $photoCategory="";
                        $logo = getCompanyInfo()['logo'];
                        
                        $request = $this->model->selectConfig($intId);
                        if($_FILES['txtImg']['name'] == ""){
                            $photoCategory = !empty($request) ? $request['img'] : $logo;
                        }else{
                            if($request['img'] != $logo){
                                deleteFile($request['img']);
                            }
                            $photo = $_FILES['txtImg'];
                            $photoCategory = 'config_'.$intId."_".bin2hex(random_bytes(6)).'.png';
                        }
                        $request = $this->model->updateConfig($intId,$isCost,$isFrame,$isPrint,$photoCategory);
                        if($request > 0){
                            if($photo!=""){
                                uploadImage($photo,$photoCategory);
                            }
                            $request = $this->model->insertPropConfig($request,$arrProps);
                            if($request > 0){
                                $arrResponse = array("status"=>true,"msg"=>"Datos guardados."); 
                            }else{
                                $arrResponse = array("status"=>false,"msg"=>"Error al guardar detalle de configuración"); 
                            }
                        }else{
                            $arrResponse = array("status"=>false,"msg"=>"Error al guardar cabecera de configuración"); 
                        }
                    }
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }   
            die();
        }
    }

?>