<?php
    class MarqueteriaOpciones extends Controllers{
        public function __construct(){
            session_start();
            if(empty($_SESSION['login'])){
                header("location: ".base_url());
                die();
            }
            parent::__construct();
        }
        public function opciones(){
            if($_SESSION['permitsModule']['r']){
                $data['botones'] = [
                    "duplicar" => ["mostrar"=>true, "evento"=>"onclick","funcion"=>"mypop=window.open('".BASE_URL."/marqueteria/opciones-propiedades/"."','','');mypop.focus();"],
                    "nuevo" => ["mostrar"=>$_SESSION['permitsModule']['w'], "evento"=>"onclick","funcion"=>"openModal()"],
                ];
                $data['page_tag'] = implode(" | ",[$_SESSION['permitsModule']['option'],$_SESSION['permitsModule']['module']]);
                $data['page_title'] = implode(" | ",[$_SESSION['permitsModule']['option'],$_SESSION['permitsModule']['module']]);
                $data['page_name'] = strtolower($_SESSION['permitsModule']['option']);
                $data['panelapp'] = "/Marqueteria/functions_molding_options.js";
                $this->views->getView($this,"opciones",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        /*************************Options methods*******************************/
        public function getOptions(){
            if($_SESSION['permitsModule']['r']){
                $request = $this->model->selectOptions();
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 
                        
                        $btnEdit="";
                        $btnDelete="";
                        $btnOptions ="";
                        $status="";
                        
                        if($_SESSION['permitsModule']['u']){
                            if($request[$i]['is_material']){
                                $btnOptions='<button type="button" onclick="showMaterial('.$request[$i]['id'].')" class="btn btn-primary m-1 text-white" title="Asignar materiales"><i class="fa fa-list" aria-hidden="true"></i></button>';
                            }
                            $btnEdit = '<button class="btn btn-success m-1" type="button" title="Editar" onclick="editItem('.$request[$i]['id'].')"><i class="fas fa-pencil-alt"></i></button>';
                        }
                        if($_SESSION['permitsModule']['d']){
                            $btnDelete = '<button class="btn btn-danger m-1" type="button" title="Eliminar" onclick="deleteItem('.$request[$i]['id'].')"><i class="fas fa-trash-alt"></i></button>';
                        }
                        if($request[$i]['status']==1){
                            $status='<span class="badge me-1 bg-success">Activo</span>';
                        }else{
                            $status='<span class="badge me-1 bg-danger">Inactivo</span>';
                        }
                        $request[$i]['status'] = $status;
                        $request[$i]['options'] = $btnOptions.$btnEdit.$btnDelete;
                    }
                }
                echo json_encode($request,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function getOption(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    if(empty($_POST)){
                        $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $id = intval($_POST['id']);
                        $request = $this->model->selectOption($id);
                        if(!empty($request)){
                            $arrResponse = array("status"=>true,"data"=>$request);
                        }else{
                            $arrResponse = array("status"=>false,"msg"=>"Error, intenta de nuevo"); 
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }
        public function setOption(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    if(empty($_POST['txtName']) || empty($_POST['statusList'])){
                        $arrResponse = array("status" => false, "msg" => 'Error de datos');
                    }else{ 
                        $id = intval($_POST['id']);
                        $strName = ucwords(strClean($_POST['txtName']));
                        $strTag = ucwords(strClean($_POST['txtTag']));
                        $strTagFrame = ucwords(strClean($_POST['txtTagFrame']));
                        $intStatus = intval($_POST['statusList']);
                        $intProp = intval($_POST['propList']);
                        $intMargin = intval($_POST['margin']);
                        $isMargin = intval($_POST['is_margin']);
                        $isColor = intval($_POST['is_color']);
                        $isFrame = intval($_POST['is_frame']);
                        $isBocel = intval($_POST['is_bocel']);
                        $isVisible = intval($_POST['is_visible']);
                        $intOrder = intval($_POST['order']);
                        if($id == 0){
                            if($_SESSION['permitsModule']['w']){
                                $option = 1;
                                $request= $this->model->insertOption(
                                    $strName,
                                    $intStatus,
                                    $intProp,
                                    $isMargin,
                                    $isColor,
                                    $isFrame,
                                    $intMargin,
                                    $isBocel,
                                    $isVisible,
                                    $intOrder,
                                    $strTag,
                                    $strTagFrame
                                );
                            }
                        }else{
                            if($_SESSION['permitsModule']['u']){
                                $option = 2;
                                $request = $this->model->updateOption(
                                    $id,
                                    $strName,
                                    $intStatus,
                                    $intProp,
                                    $isMargin,
                                    $isColor,
                                    $isFrame,
                                    $intMargin,
                                    $isBocel,
                                    $isVisible,
                                    $intOrder,
                                    $strTag,
                                    $strTagFrame
                                );
                            }
                        }
                        if(is_numeric($request) && $request > 0 ){
                            if($option == 1){
                                $arrResponse = array("status"=>true,"msg"=>"Datos guardados.");
                            }else{
                                $arrResponse = array("status"=>true,"msg"=>"Datos actualizados.");
                            }
                        }else if($request == 'exist'){
                            $arrResponse = array('status' => false, 'msg' => 'La opción de esta propiedad ya existe, prueba con otro nombre.');		
                        }else{
                            $arrResponse = array("status" => false, "msg" => 'No es posible guardar los datos.');
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
			die();
		}
        public function delOption(){
            if($_SESSION['permitsModule']['d']){
                if($_POST){
                    if(empty($_POST['id'])){
                        $arrResponse=array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $id = intval($_POST['id']);
                        $request = $this->model->deleteOption($id);
                        if($request=="ok"){
                            $arrResponse = array("status"=>true,"msg"=>"Se ha eliminado.");
                        }else{
                            $arrResponse = array("status"=>false,"msg"=>"No es posible eliminar, intenta de nuevo.");
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }
        /*************************Material methods*******************************/
        public function setMaterial(){
            if($_SESSION['permitsModule']['u']){
                if($_POST){
                    $arrMaterials = json_decode($_POST['material'],true);
                    if(empty($arrMaterials) || empty($_POST['id'])){
                        $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $id = intval($_POST['id']);
                        $request = $this->model->insertMaterial($id,$arrMaterials);
                        if($request > 0){
                            $arrResponse = array("status"=>true,"msg"=>"Datos guardados");
                        }else{
                            $arrResponse = array("status"=>false,"msg"=>"Ha ocurrido un error, inténtelo más tarde");
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }
        /*************************Properties methods*******************************/
        public function getData(){
            if($_SESSION['permitsModule']['r']){
                $arrProperties = $this->model->selectProperties();
                $arrMaterials = $this->model->selectMaterials();
                $request = array("properties"=>$arrProperties,"materials"=>$arrMaterials);
                echo json_encode($request,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
    }

?>