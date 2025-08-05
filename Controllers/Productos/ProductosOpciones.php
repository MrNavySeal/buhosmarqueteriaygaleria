<?php
    class ProductosOpciones extends Controllers{
        public function __construct(){
            session_start();
            if(empty($_SESSION['login'])){
                header("location: ".base_url());
                die();
            }
            parent::__construct();
            sessionCookie();
        }
        public function unidades(){
            if($_SESSION['permitsModule']['r']){
                $data['page_tag'] = "Unidades de medida | Productos";
                $data['page_title'] = "Unidades de medida | Productos";
                $data['page_name'] = "unidades";
                $data['panelapp'] = "/Productos/functions_medidas.js";
                $this->views->getView($this,"unidades",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function caracteristicas(){
            if($_SESSION['permitsModule']['r']){
                $data['botones'] = [
                    "duplicar" => ["mostrar"=>true, "evento"=>"onClick","funcion"=>"mypop=window.open('".BASE_URL."/productos/caracteristicas/"."','','');mypop.focus();"],
                    "nuevo" => ["mostrar"=>$_SESSION['permitsModule']['w']],
                ];
                $data['page_tag'] = "Características | Productos";
                $data['page_title'] = "Características | Productos";
                $data['page_name'] = "caracteristicas";
                $data['panelapp'] = "/Productos/functions_caracteristicas.js";
                $this->views->getView($this,"caracteristicas",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function variantes(){
            if($_SESSION['permitsModule']['r']){
                $data['botones'] = [
                    "duplicar" => ["mostrar"=>true, "evento"=>"onClick","funcion"=>"mypop=window.open('".BASE_URL."/productos/variantes/"."','','');mypop.focus();"],
                    "nuevo" => ["mostrar"=>$_SESSION['permitsModule']['w']],
                ];
                $data['page_tag'] = "Variantes | Productos";
                $data['page_title'] = "Variantes | Productos";
                $data['page_name'] = "variantes";
                $data['panelapp'] = "/Productos/functions_variantes.js";
                $this->views->getView($this,"variantes",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        /*************************Measures methods*******************************/
        public function getMeasures(){
            if($_SESSION['permitsModule']['r']){
                $request = $this->model->selectMeasures();
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 

                        $btnEdit="";
                        $btnDelete="";
                        $status="";
                        if($request[$i]['status']==1){
                            $status='<span class="badge me-1 bg-success">Activo</span>';
                        }else{
                            $status='<span class="badge me-1 bg-danger">Inactivo</span>';
                        }

                        if($_SESSION['permitsModule']['u']){
                            $btnEdit = '<button class="btn btn-success m-1" type="button" title="Editar" onclick="editItem('.$request[$i]['id_measure'].')" ><i class="fas fa-pencil-alt"></i></button>';
                        }
                        if($_SESSION['permitsModule']['d']){
                            $btnDelete = '<button class="btn btn-danger m-1" type="button" title="Eliminar" onclick="deleteItem('.$request[$i]['id_measure'].')"><i class="fas fa-trash-alt"></i></button>';
                        }
                        $request[$i]['options'] = $btnEdit.$btnDelete;
                        $request[$i]['status'] = $status;
                    }   
                }
                echo json_encode($request,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function getMeasure(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    if(empty($_POST)){
                        $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $id = intval($_POST['id']);
                        $request = $this->model->selectMeasure($id);
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
        public function setMeasure(){
            if($_SESSION['permitsModule']['w']){
                if($_POST){
                    if(empty($_POST['txtName']) || empty($_POST['txtInitials'])){
                        $arrResponse = array("status" => false, "msg" => 'Error de datos');
                    }else{ 
                        $id = intval($_POST['id']);
                        $arrData = array(
                            "name"=>ucwords(strClean($_POST['txtName'])),
                            "initials"=>strtoupper(strClean($_POST['txtInitials'])),
                            "status"=>intval($_POST['statusList'])
                        );
                        if($id == 0){
                            if($_SESSION['permitsModule']['w']){
                                $option = 1;
                                $request= $this->model->insertMeasure($arrData);
                            }
                        }else{
                            if($_SESSION['permitsModule']['u']){
                                $option = 2;
                                $request = $this->model->updateMeasure($id,$arrData);
                            }
                        }
                        if(is_numeric($request) && $request > 0){
                            if($option == 1){
                                $arrResponse = array("status"=>true,"msg"=>"Datos guardados.");
                            }else{
                                $arrResponse = array("status"=>true,"msg"=>"Datos actualizados.");
                            }
                        }else if($request == "exist"){
                            $arrResponse = array('status' => false, 'msg' => 'La unidad de medida ya existe, prueba con otro nombre.');		
                        }else{
                            $arrResponse = array("status" => false, "msg" => 'No es posible guardar los datos.');
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
			die();
		}
        public function delMeasure(){
            if($_SESSION['permitsModule']['d']){

                if($_POST){
                    if(empty($_POST['id'])){
                        $arrResponse=array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $id = intval($_POST['id']);
                        $request = $this->model->deleteMeasure($id);
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
        /*************************Specs methods*******************************/
        public function getSpecs(){
            if($_SESSION['permitsModule']['r']){
                $request = $this->model->selectSpecs();
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 

                        $btnEdit="";
                        $btnDelete="";
                        $status="";
                        if($request[$i]['status']==1){
                            $status='<span class="badge me-1 bg-success">Activo</span>';
                        }else{
                            $status='<span class="badge me-1 bg-danger">Inactivo</span>';
                        }

                        if($_SESSION['permitsModule']['u']){
                            $btnEdit = '<button class="btn btn-success m-1" type="button" title="Editar" onclick="editItem('.$request[$i]['id_specification'].')" ><i class="fas fa-pencil-alt"></i></button>';
                        }
                        if($_SESSION['permitsModule']['d']){
                            $btnDelete = '<button class="btn btn-danger m-1" type="button" title="Eliminar" onclick="deleteItem('.$request[$i]['id_specification'].')"><i class="fas fa-trash-alt"></i></button>';
                        }
                        $request[$i]['options'] = $btnEdit.$btnDelete;
                        $request[$i]['status'] = $status;
                    }   
                }
                echo json_encode($request,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function getSpec(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    if(empty($_POST)){
                        $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $id = intval($_POST['id']);
                        $request = $this->model->selectSpec($id);
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
        public function setSpec(){
            if($_SESSION['permitsModule']['w']){
                if($_POST){
                    if(empty($_POST['txtName'])){
                        $arrResponse = array("status" => false, "msg" => 'Error de datos');
                    }else{ 
                        $id = intval($_POST['id']);
                        $arrData = array(
                            "name"=>ucwords(strClean($_POST['txtName'])),
                            "status"=>intval($_POST['statusList'])
                        );
                        if($id == 0){
                            if($_SESSION['permitsModule']['w']){
                                $option = 1;
                                $request= $this->model->insertSpec($arrData);
                            }
                        }else{
                            if($_SESSION['permitsModule']['u']){
                                $option = 2;
                                $request = $this->model->updateSpec($id,$arrData);
                            }
                        }
                        if(is_numeric($request) && $request > 0){
                            if($option == 1){
                                $arrResponse = array("status"=>true,"msg"=>"Datos guardados.");
                            }else{
                                $arrResponse = array("status"=>true,"msg"=>"Datos actualizados.");
                            }
                        }else if($request == "exist"){
                            $arrResponse = array('status' => false, 'msg' => 'La característica ya existe, prueba con otro nombre.');		
                        }else{
                            $arrResponse = array("status" => false, "msg" => 'No es posible guardar los datos.');
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
			die();
		}
        public function delSpec(){
            if($_SESSION['permitsModule']['d']){

                if($_POST){
                    if(empty($_POST['id'])){
                        $arrResponse=array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $id = intval($_POST['id']);
                        $request = $this->model->deleteSpec($id);
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

        /*************************Variants methods*******************************/
        public function getVariants(){
            if($_SESSION['permitsModule']['r']){
                $request = $this->model->selectVariants();
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 

                        $btnEdit="";
                        $btnDelete="";
                        $status="";
                        if($request[$i]['status']==1){
                            $status='<span class="badge me-1 bg-success">Activo</span>';
                        }else{
                            $status='<span class="badge me-1 bg-danger">Inactivo</span>';
                        }

                        if($_SESSION['permitsModule']['u']){
                            $btnEdit = '<button class="btn btn-success m-1" type="button" title="Editar" onclick="editItem('.$request[$i]['id_variation'].')" ><i class="fas fa-pencil-alt"></i></button>';
                        }
                        if($_SESSION['permitsModule']['d']){
                            $btnDelete = '<button class="btn btn-danger m-1" type="button" title="Eliminar" onclick="deleteItem('.$request[$i]['id_variation'].')"><i class="fas fa-trash-alt"></i></button>';
                        }
                        $request[$i]['options'] = $btnEdit.$btnDelete;
                        $request[$i]['status'] = $status;
                    }   
                }
                echo json_encode($request,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function getVariant(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    if(empty($_POST)){
                        $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $id = intval($_POST['id']);
                        $request = $this->model->selectVariant($id);
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
        public function setVariant(){
            if($_SESSION['permitsModule']['w']){
                if($_POST){
                    if(empty($_POST['txtName']) || empty($_POST['options'])){
                        $arrResponse = array("status" => false, "msg" => 'Error de datos');
                    }else{ 
                        $id = intval($_POST['id']);
                        $arrData = array(
                            "name"=>ucwords(strClean($_POST['txtName'])),
                            "options"=>json_decode($_POST['options'],true),
                            "status"=>intval($_POST['statusList'])
                        );
                        if($id == 0){
                            if($_SESSION['permitsModule']['w']){
                                $option = 1;
                                $request= $this->model->insertVariant($arrData);
                            }
                        }else{
                            if($_SESSION['permitsModule']['u']){
                                $option = 2;
                                $request = $this->model->updateVariant($id,$arrData);
                            }
                        }
                        if(is_numeric($request) && $request > 0){
                            if($option == 1){
                                $arrResponse = array("status"=>true,"msg"=>"Datos guardados.");
                            }else{
                                $arrResponse = array("status"=>true,"msg"=>"Datos actualizados.");
                            }
                        }else if($request == "exist"){
                            $arrResponse = array('status' => false, 'msg' => 'La variante ya existe, prueba con otro nombre.');		
                        }else{
                            $arrResponse = array("status" => false, "msg" => 'No es posible guardar los datos.');
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
			die();
		}
        public function delVariant(){
            if($_SESSION['permitsModule']['d']){

                if($_POST){
                    if(empty($_POST['id'])){
                        $arrResponse=array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $id = intval($_POST['id']);
                        $request = $this->model->deleteVariant($id);
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
    }

?>