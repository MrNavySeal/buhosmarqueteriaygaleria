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
                $data['framing'] = "functions_molding_custom.js";
                $this->views->getView($this,"ejemplos",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function setExample(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    if(empty($_POST['statusList']) || empty($_POST['strDate']) || empty($_POST['strName']) || empty($_POST['frame'])){
                        $arrResponse = array("status" => false, "msg" => 'Error de datos');
                    }else{ 
                        $intId = intval($_POST['id']);
                        $intStatus = intval($_POST['statusList']);
                        $strDate = $_POST['strDate'];
                        $strName = ucwords(strClean($_POST['strName']));
                        $strDescription = strClean($_POST['strReview']);
                        $intOrder = intval($_POST['orderList']);
                        $isVisible = intval($_POST['is_visible']);
                        $arrFrame = json_decode($_POST['frame'],true);
                        $photo = "";
                        $photoCategory="";
                        if($intId == 0){
                            if($_SESSION['permitsModule']['w']){
                                $option = 1;
                                
                                if($_FILES['txtImg']['name'] == ""){
                                    $photoCategory = "category.jpg";
                                }else{
                                    $photo = $_FILES['txtImg'];
                                    $photoCategory = 'frame_example_'.bin2hex(random_bytes(6)).'.png';
                                }
                                $request = $this->model->insertExample($photoCategory,$intStatus,$strDate,$strName,$arrFrame,$intOrder,$isVisible,$strDescription);
                            }
                        }else{
                            if($_SESSION['permitsModule']['u']){
                                $option = 2;
                                $request = $this->model->selectExample($intId);
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
                                $request = $this->model->updateExample($intId,$photoCategory,$intStatus,$strDate,$strName,$arrFrame,$intOrder,$isVisible,$strDescription);
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
                        $btnView = '<button class="btn btn-info m-1 text-white" type="button" title="Ver" onclick="viewItem('.$request[$i]['id'].')" ><i class="fas fa-eye"></i></button>';
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
                        $request[$i]['options'] = $btnView.$btnEdit.$btnDelete;
                        $request[$i]['total'] = formatNum($request[$i]['total']);
                        $request[$i]['is_visible'] = $request[$i]['is_visible'] ? '<i class="fa fa-check text-success" aria-hidden="true"></i>' : '<i class="fa fa-times text-danger" aria-hidden="true"></i>';
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
        /*************************Molding methods*******************************/
        public function getMoldingProducts(){
            if($_SESSION['permitsModule']['w']){
                $request = $this->model->selectMoldingCategories();
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 
                        $btn = '<button type="button" class="btn btn-primary" data-bs-target="#modalFrameSetExample" data-bs-toggle="modal" onclick="getConfig(this,'.$request[$i]['id'].')">Cotizar</button>';
                        $request[$i]['options'] = $btn;
                    }
                }
                echo json_encode($request,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function getConfig(){
            if($_SESSION['permitsModule']['w']){
                if($_POST){
                    if(empty($_POST['id'])){
                        $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $intId = intval($_POST['id']);
                        $request = $this->model->selectConfig($intId);
                        if(empty($request)){
                            $arrResponse = array("status"=>false,"msg"=>"La categoria no está configurada");
                        }else{
                            $arrColors = $this->model->selectColors();
                            $arrResponse = array("status"=>true,"data"=>$request,"color"=>$arrColors);
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }
        
    }

?>