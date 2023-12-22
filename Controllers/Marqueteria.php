<?php
    class Marqueteria extends Controllers{
        public function __construct(){
            session_start();
            if(empty($_SESSION['login'])){
                header("location: ".base_url());
                die();
            }
            parent::__construct();
            sessionCookie();
            getPermits(4);
            
        }
        public function molduras(){
            if($_SESSION['permitsModule']['r']){
                $data['page_tag'] = "Molduras";
                $data['page_title'] = "Molduras";
                $data['page_name'] = "moldura";
                $data['panelapp'] = "functions_molding.js";
                $this->views->getView($this,"molduras",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function colores(){
            if($_SESSION['permitsModule']['r']){
                $data['page_tag'] = "Colores";
                $data['page_title'] = "Colores";
                $data['page_name'] = "colores";
                $data['panelapp'] = "functions_colors.js";
                $this->views->getView($this,"colores",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function materiales(){
            if($_SESSION['permitsModule']['r']){
                $data['page_tag'] = "Materiales";
                $data['page_title'] = "Materiales";
                $data['page_name'] = "materiales";
                $data['panelapp'] = "functions_materials.js";
                $this->views->getView($this,"materiales",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function categorias(){
            if($_SESSION['permitsModule']['r']){
                $data['page_tag'] = "Categorias";
                $data['page_title'] = "Categorias";
                $data['page_name'] = "categorias";
                $data['panelapp'] = "functions_moldingcategory.js";
                $this->views->getView($this,"categorias",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        /*************************Product methods*******************************/
        public function getProducts($option=null,$params=null){
            if($_SESSION['permitsModule']['r']){
                $request = $this->model->selectProducts();
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 

                        $status="";
                        $type="";
                        $btnView = '<button class="btn btn-info m-1 text-white" type="button" title="Ver" onclick="viewItem('.$request[$i]['id'].')" ><i class="fas fa-eye"></i></button>';
                        $btnEdit="";
                        $btnDelete="";
                        $price = formatNum($request[$i]['price'],false);
                        if($request[$i]['discount']>0){
                            $discount = '<span class="text-success">'.$request[$i]['discount'].'% OFF</span>';
                        }else{
                            $discount = '<span class="text-danger">0%</span>';
                        }
                        if($_SESSION['permitsModule']['u']){
                            $btnEdit = '<button class="btn btn-success m-1 text-white" type="button" title="Editar" onclick="editItem('.$request[$i]['id'].')" ><i class="fas fa-pencil-alt"></i></button>';
                        }
                        if($_SESSION['permitsModule']['d']){
                            $btnDelete = '<button class="btn btn-danger m-1 text-white" type="button" title="Eliminar" onclick="deleteItem('.$request[$i]['id'].')" ><i class="fas fa-trash-alt"></i></button>';
                        }
                        if($request[$i]['status']==1){
                            $status='<span class="badge me-1 bg-success">Activo</span>';
                        }else{
                            $status='<span class="badge me-1 bg-warning">Inactivo</span>';
                        }
                        if($request[$i]['type']==1){
                            $type='Madera';
                        }elseif($request[$i]['type']==2){
                            $type='Poliestireno';
                        }else{
                            $type='Madera diseño unico';
                        }
                        $request[$i]['status'] = $status;
                        $request[$i]['discount'] = $discount;
                        $request[$i]['price'] = $price;
                        $request[$i]['type'] = $type;
                        $request[$i]['options'] = $btnView.$btnEdit.$btnDelete;
                    }
                }
                echo json_encode($request,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function getProduct(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    if(empty($_POST)){
                        $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $id = intval($_POST['idProduct']);
                        $request = $this->model->selectProduct($id);
                        $this->model->deleteTmpImage();
                        if(!empty($request)){
                            $request['priceFormat'] = formatNum($request['price'],false);
                            $arrImages = $this->model->selectImages($id);
                            for ($i=0; $i < count($arrImages) ; $i++) { 
                                $this->model->insertTmpImage($arrImages[$i]['name'],$arrImages[$i]['rename']);
                            }
                            $arrResponse = array("status"=>true,"data"=>$request);
                        }else{
                            $arrResponse = array("status"=>false,"msg"=>"No hay datos"); 
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }
        public function setProduct(){
            //dep($_POST);exit;
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    if(empty($_POST['txtReference']) || empty($_POST['statusList']) || empty($_POST['molduraList'])
                    || empty($_POST['txtWaste']) || empty($_POST['txtPrice'])){
                        $arrResponse = array("status" => false, "msg" => 'Error de datos');
                    }else{ 
                        $idProduct = intval($_POST['idProduct']);
                        $strReference = strtoupper(strClean($_POST['txtReference']));
                        $intType = strClean($_POST['molduraList']);
                        $intWaste = intval($_POST['txtWaste']);
                        $intPrice = intval($_POST['txtPrice']);
                        $intDiscount = intval($_POST['txtDiscount']);
                        $intStatus = intval($_POST['statusList']);
                        
                        $imgInfo = "";
                        $imgName="";

                        $photos = $this->model->selectTmpImages();
                        if($idProduct == 0){
                            if($_SESSION['permitsModule']['w']){
                                $option = 1;

                                if($_FILES['txtFrame']['name'] == ""){
                                    $photoCategory = "category.jpg";
                                }else{
                                    $imgInfo = $_FILES['txtFrame'];
                                    $imgName = 'frame_'.bin2hex(random_bytes(6)).'.png';
                                }

                                $request= $this->model->insertProduct($strReference,$intType, $intWaste, $intPrice, $intDiscount, $intStatus, $imgName, $photos);
                            }
                        }else{
                            if($_SESSION['permitsModule']['u']){
                                $option = 2;
                                $request = $this->model->selectProduct($idProduct);
                                if($_FILES['txtFrame']['name'] == ""){
                                    $imgName = $request['frame'];
                                }else{
                                    if($request['frame'] != "category.jpg"){
                                        deleteFile($request['frame']);
                                    }
                                    $imgInfo = $_FILES['txtFrame'];
                                    $imgName = 'frame_'.bin2hex(random_bytes(6)).'.png';
                                }
                                $request= $this->model->updateProduct($idProduct,$strReference,$intType, $intWaste, $intPrice, $intDiscount, $intStatus, $imgName, $photos);
                            }
                        }
                        if($request > 0 ){
                            $this->model->deleteTmpImage();
                            if($imgInfo!=""){
                                uploadImage($imgInfo,$imgName);
                            }
                            if($option == 1){
                                $arrResponse = array("status"=>true,"msg"=>"Datos guardados");
                            }else{
                                $arrResponse = array("status"=>true,"msg"=>"Datos actualizados");
                            }
                        }else if($request == 'exist'){
                            $arrResponse = array('status' => false, 'msg' => '¡Atención! La moldura ya existe, pruebe con otra referencia.');		
                        }else{
                            $arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
			die();
		}
        public function delProduct(){
            if($_SESSION['permitsModule']['d']){
                if($_POST){
                    if(empty($_POST['idProduct'])){
                        $arrResponse=array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $id = intval($_POST['idProduct']);
                        $request = $this->model->selectProduct($id);
                        if($request['frame']!="category.jpg"){
                            deleteFile($request['frame']);
                        }
                        $request = $this->model->deleteProduct($id);
                        if($request=="ok"){
                            $this->model->deleteTmpImage();
                            $arrResponse = array("status"=>true,"msg"=>"Se ha eliminado.");
                        }else{
                            $arrResponse = array("status"=>false,"msg"=>"No se ha podido eliminar, inténta de nuevo.");
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }
        public function setImg(){ 
            $arrImages = orderFiles($_FILES['txtImg'],"molding");
            for ($i=0; $i < count($arrImages) ; $i++) { 
                $request = $this->model->insertTmpImage($arrImages[$i]['name'],$arrImages[$i]['rename']);
            }
            $arrResponse = array("msg"=>"Uploaded");
            echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            die();
        }
        public function delImg(){
            $images = $this->model->selectTmpImages();
            $image = $_POST['image'];
            for ($i=0; $i < count($images) ; $i++) { 
                if($image == $images[$i]['name']){
                    deleteFile($images[$i]['rename']);
                    $this->model->deleteTmpImage($images[$i]['rename']);
                    break;
                }
            }
            $arrResponse = array("msg"=>"Deleted");
            echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
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

                        if($idColor == 0){
                            if($_SESSION['permitsModule']['w']){
                                $option = 1;

                                $request= $this->model->insertColor($strName,$strColor,$intStatus);
                            }
                        }else{
                            if($_SESSION['permitsModule']['u']){
                                $option = 2;
                                $request = $this->model->updateColor($idColor,$strName,$strColor,$intStatus);
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
        /*************************Materials methods*******************************/
        public function getMaterials($option=null,$params=null){
            if($_SESSION['permitsModule']['r']){
                $request = $this->model->selectMaterials();
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 

                        $btnEdit="";
                        $btnDelete="";
                        
                        if($_SESSION['permitsModule']['u']){
                            $btnEdit = '<button class="btn btn-success m-1" type="button" title="Editar" onclick="editItem('.$request[$i]['id'].')" ><i class="fas fa-pencil-alt"></i></button>';
                        }
                        if($_SESSION['permitsModule']['d']){
                            $btnDelete = '<button class="btn btn-danger m-1" type="button" title="Eliminar" onclick="deleteItem('.$request[$i]['id'].')" ><i class="fas fa-trash-alt"></i></button>';
                        }
                        $request[$i]['price'] = formatNum($request[$i]['price']).' X '.$request[$i]['unit'];
                        $request[$i]['options'] = $btnEdit.$btnDelete;
                    }
                }
                echo json_encode($request,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function getMaterial(){
            if($_SESSION['permitsModule']['r']){

                if($_POST){
                    if(empty($_POST)){
                        $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $idMaterial = intval($_POST['idMaterial']);
                        $request = $this->model->selectMaterial($idMaterial);
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
        public function setMaterial(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    if(empty($_POST['txtName']) || empty($_POST['txtPrice']) || empty($_POST['txtUnit'])){
                        $arrResponse = array("status" => false, "msg" => 'Error de datos');
                    }else{ 
                        $idMaterial = intval($_POST['idMaterial']);
                        $strName = ucwords(strClean($_POST['txtName']));
                        $intPrice = intval($_POST['txtPrice']);
                        $strUnit = strClean($_POST['txtUnit']);
                        $strPre = strtolower(str_replace(" ","",$strName));
                        if($idMaterial == 0){
                            if($_SESSION['permitsModule']['w']){
                                $option = 1;

                                $request= $this->model->insertMaterial($strName,$strUnit,$intPrice,$strPre);
                            }
                        }else{
                            if($_SESSION['permitsModule']['u']){
                                $option = 2;
                                $request = $this->model->updateMaterial($idMaterial,$strName,$strUnit,$intPrice,$strPre);
                            }
                        }
                        if($request > 0 ){
                            if($option == 1){
                                $arrResponse = array('status' => true, 'msg' => 'Datos guardados.');
                            }else{
                                $arrResponse = array('status' => true, 'msg' => 'Datos actualizados.');
                            }
                        }else if($request == 'exist'){
                            $arrResponse = array('status' => false, 'msg' => 'El material ya existe, prueba con otro nombre.');		
                        }else{
                            $arrResponse = array("status" => false, "msg" => 'No es posible guardar los datos.');
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
			die();
		}
        public function delMaterial(){
            if($_SESSION['permitsModule']['d']){

                if($_POST){
                    if(empty($_POST['idMaterial'])){
                        $arrResponse=array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $id = intval($_POST['idMaterial']);
                        $request = $this->model->deleteMaterial($id);

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
        /*************************Category methods*******************************/
        public function getCategories($option=null,$params=null){
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

                                $request= $this->model->insertCategory($photoCategory,$strName,$strDescription,$route,$intStatus,$strButton);
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
                                $request = $this->model->updateCategory($idCategory,$photoCategory,$strName,$strDescription,$route,$intStatus,$strButton);
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
    }

?>