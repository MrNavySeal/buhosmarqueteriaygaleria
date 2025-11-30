<?php
    class Faq extends Controllers{
        public function __construct(){
            session_start();
            if(empty($_SESSION['login'])){
                header("location: ".base_url());
                die();
            }
            parent::__construct();
        }

        public function faq(){
            if($_SESSION['permitsModule']['r']){
                $data['botones'] = [
                    "duplicar" => ["mostrar"=>$_SESSION['permitsModule']['r'], "evento"=>"onClick","funcion"=>"mypop=window.open('".BASE_URL."/marketing/faq/"."','','');mypop.focus();"],
                    "nuevo" => ["mostrar"=>$_SESSION['permitsModule']['w'], "evento"=>"@click","funcion"=>"openModal()"],
                ];
                $data['page_tag'] = implode(" | ",[$_SESSION['permitsModule']['option'],$_SESSION['permitsModule']['module']]);
                $data['page_title'] = implode(" | ",[$_SESSION['permitsModule']['option'],$_SESSION['permitsModule']['module']]);
                $data['page_name'] = strtolower($_SESSION['permitsModule']['option']);

                $data['script_type'] = "module";
                $data['panelapp'] = "/Marketing/functions_faq.js";
                $this->views->getView($this,"faq",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }

        public function setFaq(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    if(empty($_POST['respuesta']) || empty($_POST['pregunta'])){
                        $arrResponse = array("status" => false, "msg" => 'Error de datos');
                    }else{ 
                        $intId = intval($_POST['id']);
                        $strRespuesta = ucfirst(strClean($_POST['respuesta']));
                        $strPregunta = ucfirst(strClean($_POST['pregunta']));
                        $intEstado = intval($_POST['estado']);
                        if($intId == 0){
                            if($_SESSION['permitsModule']['w']){
                                $option = 1;
                                $request= $this->model->insertFaq($strPregunta,$strRespuesta,$intEstado);
                            }
                        }else{
                            if($_SESSION['permitsModule']['u']){
                                $option = 2;
                                $request = $this->model->updateFaq($intId,$strPregunta,$strRespuesta,$intEstado);
                            }
                        }
                        if($request > 0 ){
                            if($option == 1){ $arrResponse = array('status' => true, 'msg' => 'Datos guardados');	
                            }else{ $arrResponse = array('status' => true, 'msg' => 'Datos actualizados'); }
                        }else{
                            $arrResponse = array("status" => false, "msg" => 'No es posible guardar los datos.');
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
			die();
		}

        public function getBuscar(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    $intPorPagina = intval($_POST['per_page']);
                    $intPaginaActual = intval($_POST['page']);
                    $strBuscar = clear_cadena(strClean($_POST['search']));
                    $request = $this->model->selectFaqs($intPaginaActual,$intPorPagina, $strBuscar);
                    echo json_encode($request,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }

        public function getDatos(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    $intId = intval($_POST['id']);
                    $request = $this->model->selectFaq($intId);

                    if(!empty($request)){
                        $arrResponse = array("status"=>true,"data"=>$request);
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
                if($_POST){
                    $intId = intval($_POST['id']);
                    $request = $this->model->deleteFaq($intId);
                    if($request > 0 || $request == "ok"){
                        $arrResponse = array("status"=>true,"msg"=>"Se ha eliminado correctamente.");
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