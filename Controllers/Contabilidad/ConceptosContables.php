<?php
    
    class ConceptosContables extends Controllers{
        public function __construct(){
            session_start();
            if(empty($_SESSION['login'])){
                header("location: ".base_url());
                die();
            }
            parent::__construct();
        }

        public function conceptos(){
            if($_SESSION['permitsModule']['r']){
                $data['botones'] = [
                    "duplicar" => ["mostrar"=>true, "evento"=>"onclick","funcion"=>"mypop=window.open('".BASE_URL.$_SESSION['permitsModule']['route']."','','');mypop.focus();"],
                    "nuevo" => ["mostrar"=>$_SESSION['permitsModule']['w'], "evento"=>"@click","funcion"=>"openModal();common.modalType='conceptos'"],
                ];
                $data['page_tag'] = implode(" | ",[$_SESSION['permitsModule']['option'],$_SESSION['permitsModule']['module']]);
                $data['page_title'] = implode(" | ",[$_SESSION['permitsModule']['option'],$_SESSION['permitsModule']['module']]);
                $data['page_name'] = strtolower($_SESSION['permitsModule']['option']);
                $data['panelapp'] = "/Contabilidad/functions_conceptos_contables.js";
                $data['script_type'] = "module";
                $this->views->getView($this,"conceptos-contables",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }

        public function getDatosIniciales(){
            if($_SESSION['permitsModule']['r']){
                $request = [
                    "cuentas"=>HelperAccounting::getAccounts(),
                    "tipos"=>HelperAccounting::getConceptTypes()
                ];
                echo json_encode($request,JSON_UNESCAPED_UNICODE);
            }
        }

        public function getBuscar(){
            if($_SESSION['permitsModule']['r']){
                $intPage = intval($_POST['page']);
                $intPerPage = intval($_POST['per_page']);
                $strSearch = strClean($_POST['search']);
                $strType = strClean($_POST['type']);
                $filterType = strClean($_POST['filter_type']);
                
                if($strType == "cuentas"){
                    $request = HelperAccounting::getAccounts(0,$strSearch);
                }else{
                    $request = $this->model->selectDatos($intPage,$intPerPage,$filterType,$strSearch);
                }
                echo json_encode($request,JSON_UNESCAPED_UNICODE);
            }
            die();
        }

        public function setDatos(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    $arrData = json_decode($_POST['data'],true);
                    $errores = validator()->validate([
                        "detalle"=>"required|array|min:1;cuentas contables",
                        "nombre"=>"required",
                        "tipo"=>"required",
                    ],$arrData)->getErrors();

                    if(!empty($errores)){
                        $arrResponse = ["status"=>false,"msg"=>"Por favor, revise los campos.","errores"=>$errores];
                    }else{
                        $option = "";
                        $arrData['tipo'] = intval($arrData['tipo']);
                        $arrData['nombre'] = strClean(ucfirst(strtolower($arrData['nombre'])));
                        $arrData['id'] = intval($arrData['id']);
                        $arrData['estado'] = intval($arrData['estado']);

                        if($arrData['id'] == 0){
                            if($_SESSION['permitsModule']['w']){
                                $option = 1;
                                $request = $this->model->insertDatos($arrData);
                            }
                        }else{
                            if($_SESSION['permitsModule']['u']){
                                $option = 2;
                                $request = $this->model->updateDatos($arrData);
                            }
                        }

                        if(is_numeric($request) && $request > 0 ){
                            if($option == 1){
                                $arrResponse = array('status' => true, 'msg' => 'Datos guardados.');
                            }else{
                                $arrResponse = array('status' => true, 'msg' => 'Datos actualizados.');
                            }
                        }else{
                            $arrResponse = array("status" => false, "msg" => 'No es posible guardar los datos.');
                        }
                    }

                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
        }

        public function getDatos(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    $intId = intval($_POST['id']);
                    $request = $this->model->selectDato($intId);
                    if(!empty($request)){
                        $arrResponse = array(
                            "status"=>true,
                            "data"=>$request,
                            "cuentas"=>HelperAccounting::getAccounts(),
                            "tipos"=>HelperAccounting::getConceptTypes()
                        );
                    }else{
                        $arrResponse = array("status"=>false,"msg"=>"Error, intenta de nuevo"); 
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }

        public function delDatos(){
            if($_SESSION['permitsModule']['d']){
                $id = intval($_POST['id']);
                $request = $this->model->deleteDatos($id);
                if($request == "ok"){
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