<?php
    class MarqueteriaEjemplos extends Controllers{
        public function __construct(){
            session_start();
            if(empty($_SESSION['login'])){
                header("location: ".base_url());
                die();
            }
            parent::__construct();
            sessionCookie();
            getPermits(12);
            
        }
        public function ejemplos(){
            if($_SESSION['permitsModule']['r']){
                $data['page_tag'] = "Ejemplos";
                $data['page_title'] = "Ejemplos | Marquetería";
                $data['page_name'] = "ejemplos";
                $data['panelapp'] = "functions_molding_example.js";
                $this->views->getView($this,"ejemplos",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function setExample(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    if(empty($_POST['statusList'])){
                        $arrResponse = array("status" => false, "msg" => 'Error de datos');
                    }else{ 
                        $id = intval($_POST['id']);
                        $intStatus = intval($_POST['statusList']);
                        $photo = "";
                        $photoCategory="";
                        if($id == 0){
                            if($_SESSION['permitsModule']['w']){
                                $option = 1;
                                /*
                                if($_FILES['txtImg']['name'] == ""){
                                    $photoCategory = "category.jpg";
                                }else{
                                    $photo = $_FILES['txtImg'];
                                    $photoCategory = 'moldingcategory_'.bin2hex(random_bytes(6)).'.png';
                                }

                                $request= $this->model->insertCategory($photoCategory,$strName,$strDescription,$route,$intStatus,$strButton,$isVisible);*/
                            }
                        }else{
                            if($_SESSION['permitsModule']['u']){
                                $option = 2;
                                $request = $this->model->selectExample($id);
                                if($_FILES['txtImg']['name'] == ""){
                                    $img = $request['img'] !="" ? $request['img'] : "category.jpg";
                                    $photoCategory = $img;
                                }else{
                                    if($request['img'] != "category.jpg" && $request['img']!=""){
                                        deleteFile($request['img']);
                                    }
                                    $photo = $_FILES['txtImg'];
                                    $photoCategory = 'frame_example_'.bin2hex(random_bytes(6)).'.png';
                                }
                                $request = $this->model->updateExample($id,$photoCategory,$intStatus);
                            }
                        }
                        if($request > 0 ){
                            if($photo!=""){
                                uploadImage($photo,$photoCategory);
                            }
                            if($option == 1){
                                $arrResponse = array("status"=>true,"msg"=>"Datos guardados.");
                            }else{
                                $arrResponse = array("status"=>true,"msg"=>"Datos actualizados.");
                            }
                        }else{
                            $arrResponse = array("status" => false, "msg" => 'No es posible guardar los datos.');
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
			die();
		}
        public function delExample(){
            if($_SESSION['permitsModule']['d']){
                if($_POST){
                    if(empty($_POST['id'])){
                        $arrResponse=array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $id = intval($_POST['id']);
                        $request = $this->model->selectExample($id);
                        if($request['img']!="category.jpg" && $request['img']!=""){
                            deleteFile($request['img']);
                        }
                        $request = $this->model->deleteExample($id);
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
        public function getExamples(){
            if($_SESSION['permitsModule']['r']){
                $request = $this->model->selectExamples();
                $total = count($request);
                if($total>0){
                    for ($i=0; $i < $total; $i++) { 
                        $arrSpecs = json_decode($request[$i]['specs'],true);
                        $btnDelete="";
                        $btnEdit="";
                        $status="";
                        if($_SESSION['permitsModule']['u']){
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
                        $request[$i]['category'] = $arrSpecs['name'];
                        $request[$i]['status'] = $status;
                        $request[$i]['options'] = $btnEdit.$btnDelete;
                        $request[$i]['total'] = formatNum($request[$i]['total']);
                    }
                }
                echo json_encode($request,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function getExample(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    if(empty($_POST)){
                        $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $id = intval($_POST['id']);
                        $request = $this->model->selectExample($id);
                        if(!empty($request)){
                            $img = $request['img'] !="" ? $request['img'] : "category.jpg";
                            $request['img'] = media()."/images/uploads/".$img;
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
    }

?>