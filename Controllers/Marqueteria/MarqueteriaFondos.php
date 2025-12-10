<?php
    class MarqueteriaFondos extends Controllers{
        public function __construct(){
            session_start();
            if(empty($_SESSION['login'])){
                header("location: ".base_url());
                die();
            }
            parent::__construct();
        }

        public function fondos(){
            if($_SESSION['permitsModule']['r']){
                $data['botones'] = [
                    "duplicar" => ["mostrar"=>true, "evento"=>"onClick","funcion"=>"mypop=window.open('".BASE_URL.$_SESSION['permitsModule']['route']."','','');mypop.focus();"],
                    "nuevo" => ["mostrar"=>true, "evento"=>"@click","funcion"=>"openModal()"],
                ];
                $data['page_tag'] = implode(" | ",[$_SESSION['permitsModule']['option'],$_SESSION['permitsModule']['module']]);
                $data['page_title'] = implode(" | ",[$_SESSION['permitsModule']['option'],$_SESSION['permitsModule']['module']]);
                $data['page_name'] = strtolower($_SESSION['permitsModule']['option']);
                $data['script_type'] = "module";
                $data['panelapp'] = "/Marqueteria/functions_molding_background.js";
                $this->views->getView($this,"fondos",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }

        public function setFondo(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){

                    $intId = intval($_POST['id']);
                    $intStatus = intval($_POST['status']);
                    $imgFile = "";
                    $imgName="";
                    $option = "";

                    if($intId==0){
                        $option = 1;
                        if($_SESSION['permitsModule']['w']){
                            if($_FILES['image']['name'] == ""){
                                $imgName = "category.jpg";
                            }else{
                                $imgFile = $_FILES['image'];
                                $imgName = 'frame_background_'.bin2hex(random_bytes(6)).'.png';
                            }
                            $request = $this->model->insertFondo($imgName,$intStatus);
                        }
                    }else{
                        $option = 2;
                        if($_SESSION['permitsModule']['u']){
                            $request = $this->model->selectFondo($intId);

                            if($_FILES['image']['name'] == ""){
                                $imgName = $request['image'];
                            }else{
                                if($request['image'] != "category.jpg"){
                                    deleteFile($request['image']);
                                }
                                $imgFile = $_FILES['image'];
                                $imgName = 'frame_background_'.bin2hex(random_bytes(6)).'.png';
                            }
                            $request = $this->model->updateFondo($intId,$imgName,$intStatus);
                        }
                    }

                    if(is_numeric($request) && $request > 0){
                        if($imgFile!=""){
                            uploadImage($imgFile,$imgName);
                        }
                        if($option == 1){
                            $arrResponse = array("status"=>true,"msg"=>"Datos guardados correctamente.");
                        }else{
                            $arrResponse = array("status"=>true,"msg"=>"Datos actualizados correctamente.");
                        }
                    }else{
                        $arrResponse = array("status"=>false,"msg"=>"No se ha podido guardar, intente de nuevo.");
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }

        public function getFondos(){
            $intPage = intval($_POST['page']);
            $intPerPage = intval($_POST['per_page']);
            $request = $this->model->selectFondos($intPage,$intPerPage);
            echo json_encode($request,JSON_UNESCAPED_UNICODE);
            die();
        }

        public function getFondo(){
            if($_POST){
                $intId = intval($_POST['id']);
                $request = $this->model->selectFondo($intId);
                if(!empty($request)){
                    $request['url'] = media()."/images/uploads/".$request['image'];
                    $arrResponse = array("status"=>true,"data"=>$request);
                }else{
                    $arrResponse = array("status"=>false,"msg"=>"Datos no encontratos.");
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                die();
            }
        }

        public function delFondo(){
            if($_POST){
                $intId = intval($_POST['id']);
                $request = $this->model->deleteFondo($intId);
                if($request =="ok"){
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