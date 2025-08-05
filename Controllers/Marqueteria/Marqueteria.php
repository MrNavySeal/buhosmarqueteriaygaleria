<?php
    class Marqueteria extends Controllers{
        public function __construct(){
            session_start();
            if(empty($_SESSION['login'])){
                header("location: ".base_url());
                die();
            }
            parent::__construct(); 
        }
        public function categorias(){
            
            if($_SESSION['permitsModule']['r']){
                $data['botones'] = [
                    "duplicar" => ["mostrar"=>true, "evento"=>"onclick","funcion"=>"mypop=window.open('".BASE_URL."/marqueteria/categorias/"."','','');mypop.focus();"],
                    "nuevo" => ["mostrar"=>$_SESSION['permitsModule']['w'], "evento"=>"onclick","funcion"=>"openModal()"],
                ];
                $data['page_tag'] = implode(" | ",[$_SESSION['permitsModule']['option'],$_SESSION['permitsModule']['module']]);
                $data['page_title'] = implode(" | ",[$_SESSION['permitsModule']['option'],$_SESSION['permitsModule']['module']]);
                $data['page_name'] = strtolower($_SESSION['permitsModule']['option']);
                $data['panelapp'] = "/Marqueteria/functions_moldingcategory.js";
                $this->views->getView($this,"categorias",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function propiedades(){
            if($_SESSION['permitsModule']['r']){
                $data['botones'] = [
                    "duplicar" => ["mostrar"=>true, "evento"=>"onclick","funcion"=>"mypop=window.open('".BASE_URL."/marqueteria/propiedades/"."','','');mypop.focus();"],
                    "nuevo" => ["mostrar"=>$_SESSION['permitsModule']['w'], "evento"=>"onclick","funcion"=>"openModal()"],
                ];
                $data['page_tag'] = implode(" | ",[$_SESSION['permitsModule']['option'],$_SESSION['permitsModule']['module']]);
                $data['page_title'] = implode(" | ",[$_SESSION['permitsModule']['option'],$_SESSION['permitsModule']['module']]);
                $data['page_name'] = strtolower($_SESSION['permitsModule']['option']);
                $data['panelapp'] = "/Marqueteria/functions_molding_props.js";
                $this->views->getView($this,"propiedades",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function colores(){
            if($_SESSION['permitsModule']['r']){
                $data['botones'] = [
                    "duplicar" => ["mostrar"=>true, "evento"=>"onclick","funcion"=>"mypop=window.open('".BASE_URL."/marqueteria/colores/"."','','');mypop.focus();"],
                    "nuevo" => ["mostrar"=>$_SESSION['permitsModule']['w'], "evento"=>"onclick","funcion"=>"openModal()"],
                ];
                $data['page_tag'] = implode(" | ",[$_SESSION['permitsModule']['option'],$_SESSION['permitsModule']['module']]);
                $data['page_title'] = implode(" | ",[$_SESSION['permitsModule']['option'],$_SESSION['permitsModule']['module']]);
                $data['page_name'] = strtolower($_SESSION['permitsModule']['option']);
                $data['panelapp'] = "/Marqueteria/functions_molding_colors.js";
                $this->views->getView($this,"colores",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        /*************************Category methods*******************************/
        public function getCategories(){
            if($_SESSION['permitsModule']['r']){
                $request = $this->model->selectCategories();
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 
                        
                        $image =  media()."/images/uploads/".$request[$i]['image'];
                        $btnEdit="";
                        $btnDelete="";
                        $status="";
                        if($_SESSION['permitsModule']['u']){
                            $btnEdit = '<button class="btn btn-success m-1" type="button" title="Editar" onclick="editItem('.$request[$i]['id'].')"><i class="fas fa-pencil-alt"></i></button>';
                        }
                        if($_SESSION['permitsModule']['d']){
                            $btnDelete = '<button class="btn btn-danger m-1" type="button" title="Eliminar" onclick="deleteItem('.$request[$i]['id'].')"><i class="fas fa-trash-alt"></i></button>';
                        }
                        if($request[$i]['status']==1){
                            $status='<span class="badge me-1 bg-success">Activo</span>';
                        }else if($request[$i]['status']==3){
                            $status='<span class="badge me-1 bg-warning">En proceso</span>';
                        }else{
                            $status='<span class="badge me-1 bg-danger">Inactivo</span>';
                        }
                        $request[$i]['is_visible'] = $request[$i]['is_visible'] ? '<i class="fa fa-check text-success" aria-hidden="true"></i>' : '<i class="fa fa-times text-danger" aria-hidden="true"></i>';
                        $request[$i]['image'] = $image;
                        $request[$i]['status'] = $status;
                        $request[$i]['options'] = $btnEdit.$btnDelete;
                    }
                }
                echo json_encode($request,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function getCategory(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    if(empty($_POST)){
                        $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $idCategory = intval($_POST['idCategory']);
                        $request = $this->model->selectCategory($idCategory);
                        if(!empty($request)){
                            $request['image'] = media()."/images/uploads/".$request['image'];
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
        public function setCategory(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    if(empty($_POST['txtName']) || empty($_POST['txtDescription']) || empty($_POST['statusList'])){
                        $arrResponse = array("status" => false, "msg" => 'Error de datos');
                    }else{ 
                        $idCategory = intval($_POST['idCategory']);
                        $strName = ucwords(strClean($_POST['txtName']));
                        $strDescription = strClean($_POST['txtDescription']);
                        $strButton = strClean($_POST['txtBtn']);
                        $intStatus = intval($_POST['statusList']);
                        $isVisible = intval($_POST['is_visible']);
                        $route = str_replace(" ","-",$strName);
                        $route = str_replace("?","",$route);
                        $route = strtolower(str_replace("¿","",$route));
                        $route = clear_cadena($route);
                        $photo = "";
                        $photoCategory="";

                        if($idCategory == 0){
                            if($_SESSION['permitsModule']['w']){
                                $option = 1;

                                if($_FILES['txtImg']['name'] == ""){
                                    $photoCategory = "category.jpg";
                                }else{
                                    $photo = $_FILES['txtImg'];
                                    $photoCategory = 'moldingcategory_'.bin2hex(random_bytes(6)).'.png';
                                }

                                $request= $this->model->insertCategory($photoCategory,$strName,$strDescription,$route,$intStatus,$strButton,$isVisible);
                            }
                        }else{
                            if($_SESSION['permitsModule']['u']){
                                $option = 2;
                                $request = $this->model->selectCategory($idCategory);
                                if($_FILES['txtImg']['name'] == ""){
                                    $photoCategory = $request['image'];
                                }else{
                                    if($request['image'] != "category.jpg"){
                                        deleteFile($request['image']);
                                    }
                                    $photo = $_FILES['txtImg'];
                                    $photoCategory = 'moldingcategory_'.bin2hex(random_bytes(6)).'.png';
                                }
                                $request = $this->model->updateCategory($idCategory,$photoCategory,$strName,$strDescription,$route,$intStatus,$strButton,$isVisible);
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
                        }else if($request == 'exist'){
                            $arrResponse = array('status' => false, 'msg' => 'La categoría ya existe, prueba con otro nombre.');		
                        }else{
                            $arrResponse = array("status" => false, "msg" => 'No es posible guardar los datos.');
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
			die();
		}
        public function delCategory(){
            if($_SESSION['permitsModule']['d']){
                if($_POST){
                    if(empty($_POST['idCategory'])){
                        $arrResponse=array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $id = intval($_POST['idCategory']);
                        $request = $this->model->selectCategory($id);
                        if($request['image']!="category.jpg"){
                            deleteFile($request['image']);
                        }
                        $request = $this->model->deleteCategory($id);
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
        /*************************Properties methods*******************************/
        public function getFraming(){
            if($_SESSION['permitsModule']['w']){
                $request = $this->model->selectCatFraming();
                $html = "";
                foreach ($request as $d) {
                    $html.= '
                        <tr>
                            <td>'.$d['name'].'</td>
                            <td>
                                <div class="form-check form-switch">
                                    <input class="form-check-input frameCheck" type="checkbox" role="switch" data-id="'.$d['id'].'" checked>
                                </div>
                            </td>
                        </tr>
                    ';
                }
                echo json_encode($html,JSON_UNESCAPED_UNICODE);
            }   
            die();
        }
        public function getProperties(){
            if($_SESSION['permitsModule']['r']){
                $request = $this->model->selectProperties();
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 
                        
                        $btnEdit="";
                        $btnDelete="";
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
                        $request[$i]['is_material'] = $request[$i]['is_material'] = $request[$i]['is_material'] ? '<i class="fa fa-check text-success" aria-hidden="true"></i>' : '<i class="fa fa-times text-danger" aria-hidden="true"></i>';
                        $request[$i]['status'] = $status;
                        $request[$i]['options'] = $btnEdit.$btnDelete;
                    }
                }
                echo json_encode($request,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function getProperty(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    if(empty($_POST)){
                        $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $id = intval($_POST['id']);
                        $request = $this->model->selectProperty($id);
                        if(!empty($request)){
                            $html = "";
                            if(!empty($request['framing'])){
                                foreach ($request['framing'] as $d) {
                                    $checked = $d['is_check'] ? "checked" : "";
                                    $html.= '
                                        <tr>
                                            <td>'.$d['name'].'</td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input frameCheck" type="checkbox" role="switch" data-id="'.$d['id'].'" '.$checked.'>
                                                </div>
                                            </td>
                                        </tr>
                                    ';
                                }
                            }
                            $request['framing'] = $html;
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
        public function setProperty(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    if(empty($_POST['txtName']) || empty($_POST['statusList']) || empty($_POST['framing'])){
                        $arrResponse = array("status" => false, "msg" => 'Error de datos');
                    }else{ 
                        $id = intval($_POST['id']);
                        $strName = ucwords(strClean($_POST['txtName']));
                        $intStatus = intval($_POST['statusList']);
                        $isVisible = intval($_POST['is_visible']);
                        $intOrder = intval($_POST['orderList']);
                        $arrFraming = json_decode($_POST['framing'],true);
                        if($id == 0){
                            if($_SESSION['permitsModule']['w']){
                                $option = 1;
                                $request= $this->model->insertProperty($strName,$intStatus,$isVisible,$intOrder,$arrFraming);
                            }
                        }else{
                            if($_SESSION['permitsModule']['u']){
                                $option = 2;
                                $request = $this->model->updateProperty($id,$strName,$intStatus,$isVisible,$intOrder,$arrFraming);
                            }
                        }
                        if($request > 0 ){
                            if($option == 1){
                                $arrResponse = array("status"=>true,"msg"=>"Datos guardados.");
                            }else{
                                $arrResponse = array("status"=>true,"msg"=>"Datos actualizados.");
                            }
                        }else if($request == 'exist'){
                            $arrResponse = array('status' => false, 'msg' => 'La propiedad ya existe, prueba con otro nombre.');		
                        }else{
                            $arrResponse = array("status" => false, "msg" => 'No es posible guardar los datos.');
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
			die();
		}
        public function delProperty(){
            if($_SESSION['permitsModule']['d']){
                if($_POST){
                    if(empty($_POST['id'])){
                        $arrResponse=array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $id = intval($_POST['id']);
                        $request = $this->model->deleteProperty($id);
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
        
        /*************************Color methods*******************************/
        public function getColors($option=null,$params=null){
            if($_SESSION['permitsModule']['r']){
                $request = $this->model->selectColors();
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 

                        $btnEdit="";
                        $btnDelete="";
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
                        $request[$i]['status'] = $status;
                        $request[$i]['options'] = $btnEdit.$btnDelete;
                        $request[$i]['is_visible'] = $request[$i]['is_visible'] ? '<i class="fa fa-check text-success" aria-hidden="true"></i>' : '<i class="fa fa-times text-danger" aria-hidden="true"></i>';
                    }
                }
                echo json_encode($request,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function getColor(){
            if($_SESSION['permitsModule']['r']){

                if($_POST){
                    if(empty($_POST)){
                        $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $idColor = intval($_POST['idColor']);
                        $request = $this->model->selectColor($idColor);
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
        public function setColor(){
            if($_SESSION['permitsModule']['w']){
                if($_POST){
                    if(empty($_POST['txtName']) || empty($_POST['txtColor']) || empty($_POST['statusList'])){
                        $arrResponse = array("status" => false, "msg" => 'Error de datos');
                    }else{ 
                        $idColor = intval($_POST['idColor']);
                        $strName = ucwords(strClean($_POST['txtName']));
                        $strColor = strClean($_POST['txtColor']);
                        $intStatus = intval($_POST['statusList']);
                        $isVisible = intval($_POST['is_visible']);
                        $intOrder = intval($_POST['orderList']);

                        if($idColor == 0){
                            if($_SESSION['permitsModule']['w']){
                                $option = 1;

                                $request= $this->model->insertColor($strName,$strColor,$intStatus,$isVisible,$intOrder);
                            }
                        }else{
                            if($_SESSION['permitsModule']['u']){
                                $option = 2;
                                $request = $this->model->updateColor($idColor,$strName,$strColor,$intStatus,$isVisible,$intOrder);
                            }
                        }
                        if($request > 0 ){
                            if($option == 1){
                                $arrResponse = array("status"=>true,"Datos guardados");
                            }else{
                                $arrResponse = array("status"=>true,"Datos actualizados");
                            }
                        }else if($request == 'exist'){
                            $arrResponse = array('status' => false, 'msg' => 'El color ya existe, prueba con otro nombre.');		
                        }else{
                            $arrResponse = array("status" => false, "msg" => 'No es posible guardar los datos.');
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
			die();
		}
        public function delColor(){
            if($_SESSION['permitsModule']['d']){

                if($_POST){
                    if(empty($_POST['idColor'])){
                        $arrResponse=array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $id = intval($_POST['idColor']);
                        $request = $this->model->deleteColor($id);

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