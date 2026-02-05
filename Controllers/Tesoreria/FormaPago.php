<?php
    class FormaPago extends Controllers{
        public function __construct(){
            session_start();
            if(empty($_SESSION['login'])){
                header("location: ".base_url());
                die();
            }
            parent::__construct();
        }

        public function formaPago(){
            if($_SESSION['permitsModule']['r']){
                $data['botones'] = [
                    "duplicar" => ["mostrar"=>true, "evento"=>"onClick","funcion"=>"mypop=window.open('".BASE_URL.$_SESSION['permitsModule']['route']."','','');mypop.focus();"],
                    "nuevo" => ["mostrar"=>$_SESSION['permitsModule']['w'], "evento"=>"@click","funcion"=>"openModal()"],
                ];
                $data['page_tag'] = "{$_SESSION['permitsModule']['option']} | {$_SESSION['permitsModule']['module']}";
                $data['page_title'] = "{$_SESSION['permitsModule']['option']} | {$_SESSION['permitsModule']['module']}";
                $data['page_name'] = "{$_SESSION['permitsModule']['option']} | {$_SESSION['permitsModule']['module']}";
                $data['script_type'] = "module";
                $data['panelapp'] = "/Tesoreria/functions_forma_pago.js";
                $this->views->getView($this,"forma-pago",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }

        /*************************Category methods*******************************/
        public function getBuscar(){
            if($_SESSION['permitsModule']['r']){
                $intPage = intval($_POST['page']);
                $intPerPage = intval($_POST['per_page']);
                $strSearch = strClean($_POST['search']);
                $strType = strClean($_POST['type']);
                $filterType = strClean($_POST['filter_type']);

                if($strType =="concepto"){
                    $request = HelperPagination::getConceptosContables($intPage,$intPerPage,$filterType,$strSearch);
                }else{
                    $request = $this->model->selectDatos($intPage,$intPerPage,$strSearch);
                }
                echo json_encode($request,JSON_UNESCAPED_UNICODE);
            }
            die();
        }

        public function getDatos(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    if(empty($_POST)){
                        $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $id = intval($_POST['id']);
                        $request = $this->model->selectDato($id);
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

        public function setDatos(){
            if($_SESSION['permitsModule']['r']){
                $data = json_decode(file_get_contents("php://input"),true);
                if(!empty($data)){
                    $campos = [ "nombre"=>$data['nombre'], "tipo"=>$data['tipo'], "conceptos"=>$data['detalle'],"total"=>$data['total']];
                    $validar = [ "nombre"=>"required", "tipo"=>"required", "conceptos"=>"required|array|min:1","total"=>"min:1"];
                    if($data['tipo']=="porcentaje"){
                        $validar['total'] = "min:1|max:100";
                    }

                    $errores = validator()->validate($validar,$campos)->getErrors();

                    if(!empty($errores)){
                        $arrResponse = ["status"=>false,"msg"=>"Por favor, revise los campos.","errores"=>$errores];
                    }else{ 
                        $intId = intval($data['id']);
                        if($intId == 0){
                            if($_SESSION['permitsModule']['w']){
                                $option = 1;
                                $request= $this->model->insertDatos($data);
                            }
                        }else{
                            if($_SESSION['permitsModule']['u']){
                                $option = 2;
                                $request= $this->model->updateDatos($data);
                            }
                        }
                        if(is_numeric($request) && $request > 0){
                            if($option == 1){
                                $arrResponse = array("status"=>true,"msg"=>"Datos guardados.");
                            }else{
                                $arrResponse = array("status"=>true,"msg"=>"Datos actualizados.");
                            }
                        }else if($request == 'exist'){
                            $arrResponse = array('status' => false, 'msg' => 'La retención ya existe, pruebe con otro nombre.');		
                        }else{
                            $arrResponse = array("status" => false, "msg" => 'No es posible guardar los datos.',"error"=>$request);
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }else{
                    $arrResponse = array("status"=>false,"msg"=>"No hay datos.");
                }
            }
			die();
		}

        public function delDatos(){
            if($_SESSION['permitsModule']['d']){
                if($_POST){
                    $id = intval($_POST['id']);
                    $request = $this->model->deleteDatos($id);
                    if($request=="ok"){
                        $arrResponse = array("status"=>true,"msg"=>"Se ha eliminado.");
                    }else{
                        $arrResponse = array("status"=>false,"msg"=>"No es posible eliminar, intenta de nuevo.");
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }
    }

?>