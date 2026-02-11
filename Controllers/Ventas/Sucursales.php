<?php
    class Sucursales extends Controllers{
        public function __construct(){
            session_start();
            if(empty($_SESSION['login'])){
                header("location: ".base_url());
                die();
            }
            parent::__construct();
        }
        public function sucursales(){
            if($_SESSION['permitsModule']['r']){
                $data['botones'] = [
                    "duplicar" => ["mostrar"=>true, "evento"=>"onClick","funcion"=>"mypop=window.open('".BASE_URL.$_SESSION['permitsModule']['route']."','','');mypop.focus();"],
                    "nuevo" => ["mostrar"=>$_SESSION['permitsModule']['w'], "evento"=>"@click","funcion"=>"openModal()"],
                ];
                $data['page_tag'] = "{$_SESSION['permitsModule']['option']} | {$_SESSION['permitsModule']['module']}";
                $data['page_title'] = "{$_SESSION['permitsModule']['option']} | {$_SESSION['permitsModule']['module']}";
                $data['page_name'] = "{$_SESSION['permitsModule']['option']} | {$_SESSION['permitsModule']['module']}";
                $data['script_type'] = "module";
                $data['panelapp'] = "/Ventas/functions_sucursales.js";
                $this->views->getView($this,"sucursales",$data);
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
                $request = $this->model->selectDatos($intPage,$intPerPage,$strSearch);
                echo json_encode($request,JSON_UNESCAPED_UNICODE);
            }
            die();
        }

        public function getDatosIniciales(){
            if($_SESSION['permitsModule']['r']){
                $arrResponse = array(
                    "paises"=>getPaises(),
                );
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }

        public function getDatos(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    $id = intval($_POST['id']);
                    $request = $this->model->selectDato($id);
                    if(!empty($request)){
                        $arrResponse = array(
                            "status"=>true,
                            "data"=>$request,
                            "departamentos"=>getDepartamentos($request['country_id']),
                            "ciudades"=>getCiudades($request['state_id']),
                            "paises"=>getPaises(),
                        );
                    }else{
                        $arrResponse = array("status"=>false,"msg"=>"Error, intenta de nuevo"); 
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
                    $errores = validator()->validate([
                        "nombre"=>"required",
                        "pais"=>"required",
                        "departamento"=>"required",
                        "ciudad"=>"required"
                    ],["nombre"=>$data['nombre'],"pais"=>$data['pais'],"departamento"=>$data['departamento'],"ciudad"=>$data['ciudad']])->getErrors();

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
                                $request= $this->model->updateDatos($intId,$data);
                            }
                        }

                        if(is_numeric($request) && $request > 0){
                            if($option == 1){
                                $arrResponse = array("status"=>true,"msg"=>"Datos guardados.");
                            }else{
                                $arrResponse = array("status"=>true,"msg"=>"Datos actualizados.");
                            }
                        }else{
                            $arrResponse = array("status" => false, "msg" => 'No es posible guardar los datos.');
                        }
                    }                    
                }else{
                    $arrResponse = array("status"=>false,"msg"=>"No hay datos.");
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
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
                    }else if($request =="exist"){
                        $arrResponse = array("status"=>false,"msg"=>"El tipo de concepto ya está siendo usado, no puede ser eliminado.");
                    }else{
                        $arrResponse = array("status"=>false,"msg"=>"No es posible eliminar, intenta de nuevo.");
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }

        public function getEstados($params){
            $arrParams = explode(",",$params);
            $strTipo = $arrParams[0];
            $intId = $arrParams[1];
            if($strTipo == "estado"){$request = getDepartamentos($intId);}
            else{$request = getCiudades($intId);}
            echo json_encode($request,JSON_UNESCAPED_UNICODE);
        }
    }

?>