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
                $data['products'] = $this->getProducts();
                $data['app'] = "functions_molding.js";
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
                $data['colores'] = $this->getColors();
                $data['app'] = "functions_colors.js";
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
                $data['colores'] = $this->getMaterials();
                $data['app'] = "functions_materials.js";
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
                $data['categorias'] = $this->getCategories();
                $data['app'] = "functions_moldingcategory.js";
                $this->views->getView($this,"categorias",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        /*************************Product methods*******************************/
        public function getProducts($option=null,$params=null){
            if($_SESSION['permitsModule']['r']){
                $html="";
                $request="";
                if($option == 1){
                    $request = $this->model->searchm($params);
                }else if($option == 2){
                    $request = $this->model->sortm($params);
                }else{
                    $request = $this->model->selectProducts();
                }
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 

                        $status="";
                        $type="";
                        $btnView = '<button class="btn btn-info m-1" type="button" title="Watch" data-id="'.$request[$i]['id'].'" name="btnView"><i class="fas fa-eye"></i></button>';
                        $btnEdit="";
                        $btnDelete="";
                        $price = formatNum($request[$i]['price'],false);
                        if($request[$i]['discount']>0){
                            $discount = '<span class="text-success">'.$request[$i]['discount'].'% OFF</span>';
                        }else{
                            $discount = '<span class="text-danger">0%</span>';
                        }
                        if($_SESSION['permitsModule']['u']){
                            $btnEdit = '<button class="btn btn-success m-1" type="button" title="Edit" data-id="'.$request[$i]['id'].'" name="btnEdit"><i class="fas fa-pencil-alt"></i></button>';
                        }
                        if($_SESSION['permitsModule']['d']){
                            $btnDelete = '<button class="btn btn-danger m-1" type="button" title="Delete" data-id="'.$request[$i]['id'].'" name="btnDelete"><i class="fas fa-trash-alt"></i></button>';
                        }
                        if($request[$i]['status']==1){
                            $status='<span class="badge me-1 bg-success">Activo</span>';
                        }else{
                            $status='<span class="badge me-1 bg-warning">Inactivo</span>';
                        }
                        if($request[$i]['type']==1){
                            $type='Moldura en madera';
                        }else{
                            $type='Moldura importada';
                        }
                        $html.='
                            <tr class="item">
                                <td class="text-center">
                                    <img src="'.$request[$i]['image'].'" class="rounded">
                                </td>
                                <td data-label="Referencia: ">'.$request[$i]['reference'].'</td>
                                <td data-label="Tipo: ">'.$type.'</td>
                                <td data-label="Desperdicio: ">'.$request[$i]['waste'].' cm</td>
                                <td data-label="Costo: ">'.$price.' x cm</td>
                                <td data-label="Descuento: ">'.$discount.'</td>
                                <td data-label="Estado: ">'.$status.'</td>
                                <td class="item-btn">'.$btnView.$btnEdit.$btnDelete.'</td>
                            </tr>
                        ';
                    }
                    $arrResponse = array("status"=>true,"data"=>$html);
                }else{
                    $html = '<tr><td colspan="20">No hay datos</td></tr>';
                    $arrResponse = array("status"=>false,"data"=>$html);
                }
            }else{
                header("location: ".base_url());
                die();
            }
            
            return $arrResponse;
        }
        public function getProduct(){
            //dep($_POST);exit;
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
            }else{
                header("location: ".base_url());
                die();
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
                                $arrResponse = $this->getProducts();
                                $arrResponse['msg'] = 'Datos guardados.';
                            }else{
                                $arrResponse = $this->getProducts();
                                $arrResponse['msg'] = 'Datos actualizados';
                            }
                        }else if($request == 'exist'){
                            $arrResponse = array('status' => false, 'msg' => '¡Atención! La moldura ya existe, pruebe con otra referencia.');		
                        }else{
                            $arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }else{
                header("location: ".base_url());
                die();
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
                            $arrResponse = array("status"=>true,"msg"=>"Se ha eliminado.","data"=>$this->getProducts()['data']);
                        }else{
                            $arrResponse = array("status"=>false,"msg"=>"No se ha podido eliminar, inténta de nuevo.");
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }else{
                header("location: ".base_url());
                die();
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
        public function searchm($params){
            if($_SESSION['permitsModule']['r']){
                $search = strClean($params);
                $arrResponse = $this->getProducts(1,$params);
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function sortm($params){
            if($_SESSION['permitsModule']['r']){
                $params = intval($params);
                $arrResponse = $this->getProducts(2,$params);
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        /*************************Color methods*******************************/
        public function getColors($option=null,$params=null){
            if($_SESSION['permitsModule']['r']){
                $html="";
                $request="";
                if($option == 1){
                    $request = $this->model->searchc($params);
                }else if($option == 2){
                    $request = $this->model->sortc($params);
                }else{
                    $request = $this->model->selectColors();
                }
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 

                        $btnEdit="";
                        $btnDelete="";
                        $status="";
                        
                        if($_SESSION['permitsModule']['u']){
                            $btnEdit = '<button class="btn btn-success m-1" type="button" title="Edit" data-id="'.$request[$i]['id'].'" name="btnEdit"><i class="fas fa-pencil-alt"></i></button>';
                        }
                        if($_SESSION['permitsModule']['d']){
                            $btnDelete = '<button class="btn btn-danger m-1" type="button" title="Delete" data-id="'.$request[$i]['id'].'" name="btnDelete"><i class="fas fa-trash-alt"></i></button>';
                        }
                        if($request[$i]['status']==1){
                            $status='<span class="badge me-1 bg-success">Activo</span>';
                        }else{
                            $status='<span class="badge me-1 bg-warning">Inactivo</span>';
                        }
                        $html.='
                            <tr class="item" data-name="'.$request[$i]['name'].'">
                                <td class="d-flex justify-content-center"><div style="height: 50px;width: 50px; border:1px solid #000;background-color:#'.$request[$i]['color'].'"></div></td>
                                <td data-label="Nombre: ">'.$request[$i]['name'].'</td>
                                <td data-label="Código hexadecimal: ">#'.$request[$i]['color'].'</td>
                                <td data-label="Estado: ">'.$status.'</td>
                                <td class="item-btn">'.$btnEdit.$btnDelete.'</td>
                            </tr>
                        ';
                    }
                    $arrResponse = array("status"=>true,"data"=>$html);
                }else{
                    $html = '<tr><td colspan="20">No hay datos</td></tr>';
                    $arrResponse = array("status"=>false,"data"=>$html);
                }
            }else{
                header("location: ".base_url());
                die();
            }
            
            return $arrResponse;
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
            }else{
                header("location: ".base_url());
                die();
            }
            die();
        }
        public function setColor(){
            if($_SESSION['permitsModule']['r']){
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
                                $arrResponse = $this->getColors();
                                $arrResponse['msg'] = 'Datos guardados.';
                            }else{
                                $arrResponse = $this->getColors();
                                $arrResponse['msg'] = 'Datos actualizados.';
                            }
                        }else if($request == 'exist'){
                            $arrResponse = array('status' => false, 'msg' => 'El color ya existe, prueba con otro nombre.');		
                        }else{
                            $arrResponse = array("status" => false, "msg" => 'No es posible guardar los datos.');
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }else{
                header("location: ".base_url());
                die();
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
                            $arrResponse = array("status"=>true,"msg"=>"Se ha eliminado.","data"=>$this->getColors()['data']);
                        }else{
                            $arrResponse = array("status"=>false,"msg"=>"No es posible eliminar, intenta de nuevo.");
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }else{
                header("location: ".base_url());
                die();
            }
            die();
        }
        public function searchc($params){
            if($_SESSION['permitsModule']['r']){
                $search = strClean($params);
                $arrResponse = $this->getColors(1,$params);
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function sortc($params){
            if($_SESSION['permitsModule']['r']){
                $params = intval($params);
                $arrResponse = $this->getColors(2,$params);
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        /*************************Materials methods*******************************/
        public function getMaterials($option=null,$params=null){
            if($_SESSION['permitsModule']['r']){
                $html="";
                $request="";
                if($option == 1){
                    $request = $this->model->searchma($params);
                }else{
                    $request = $this->model->selectMaterials();
                }
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 

                        $btnEdit="";
                        $btnDelete="";
                        
                        if($_SESSION['permitsModule']['u']){
                            $btnEdit = '<button class="btn btn-success m-1" type="button" title="Edit" data-id="'.$request[$i]['id'].'" name="btnEdit"><i class="fas fa-pencil-alt"></i></button>';
                        }
                        if($_SESSION['permitsModule']['d']){
                            $btnDelete = '<button class="btn btn-danger m-1" type="button" title="Delete" data-id="'.$request[$i]['id'].'" name="btnDelete"><i class="fas fa-trash-alt"></i></button>';
                        }
                        $html.='
                            <tr class="item" data-name="'.$request[$i]['name'].'">
                                <td data-label="Nombre: ">'.$request[$i]['name'].'</td>
                                <td data-label="Costo: ">'.formatNum($request[$i]['price']).' X '.$request[$i]['unit'].'</td>
                                <td class="item-btn">'.$btnEdit.$btnDelete.'</td>
                            </tr>
                        ';
                    }
                    $arrResponse = array("status"=>true,"data"=>$html);
                }else{
                    $html = '<tr><td colspan="20">No hay datos</td></tr>';
                    $arrResponse = array("status"=>false,"data"=>$html);
                }
            }else{
                header("location: ".base_url());
                die();
            }
            
            return $arrResponse;
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
            }else{
                header("location: ".base_url());
                die();
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
                                $arrResponse = $this->getMaterials();
                                $arrResponse['msg'] = 'Datos guardados.';
                            }else{
                                $arrResponse = $this->getMaterials();
                                $arrResponse['msg'] = 'Datos actualizados.';
                            }
                        }else if($request == 'exist'){
                            $arrResponse = array('status' => false, 'msg' => 'El material ya existe, prueba con otro nombre.');		
                        }else{
                            $arrResponse = array("status" => false, "msg" => 'No es posible guardar los datos.');
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }else{
                header("location: ".base_url());
                die();
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
                            $arrResponse = array("status"=>true,"msg"=>"Se ha eliminado.","data"=>$this->getMaterials()['data']);
                        }else{
                            $arrResponse = array("status"=>false,"msg"=>"No es posible eliminar, intenta de nuevo.");
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }else{
                header("location: ".base_url());
                die();
            }
            die();
        }
        public function searchma($params){
            if($_SESSION['permitsModule']['r']){
                $search = strClean($params);
                $arrResponse = $this->getMaterials(1,$params);
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        /*************************Category methods*******************************/
        public function getCategories($option=null,$params=null){
            if($_SESSION['permitsModule']['r']){
                $html="";
                $request="";
                if($option == 1){
                    $request = $this->model->searchca($params);
                }else if($option == 2){
                    $request = $this->model->sortca($params);
                }else{
                    $request = $this->model->selectCategories();
                }
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 
                        
                        $image =  media()."/images/uploads/".$request[$i]['image'];
                        $btnEdit="";
                        $btnDelete="";
                        $status="";
                        if($_SESSION['permitsModule']['u']){
                            $btnEdit = '<button class="btn btn-success m-1" type="button" title="Edit" data-id="'.$request[$i]['id'].'" name="btnEdit"><i class="fas fa-pencil-alt"></i></button>';
                        }
                        if($_SESSION['permitsModule']['d']){
                            $btnDelete = '<button class="btn btn-danger m-1" type="button" title="Delete" data-id="'.$request[$i]['id'].'" name="btnDelete"><i class="fas fa-trash-alt"></i></button>';
                        }
                        if($request[$i]['status']==1){
                            $status='<span class="badge me-1 bg-success">Activo</span>';
                        }else{
                            $status='<span class="badge me-1 bg-danger">Inactivo</span>';
                        }
                        $html.='
                            <tr class="item" data-name="'.$request[$i]['name'].'">
                                <td class="text-center">
                                    <img src="'.$image.'" class="rounded">
                                </td>
                                <td data-label="Nombre: ">'.$request[$i]['name'].'</td>
                                <td data-label="Estado: ">'.$status.'</td>
                                <td class="item-btn">'.$btnEdit.$btnDelete.'</td>
                            </tr>
                        ';
                    }
                    $arrResponse = array("status"=>true,"data"=>$html);
                }else{
                    $html = '<tr><td colspan="20">No hay datos</td></tr>';
                    $arrResponse = array("status"=>false,"data"=>$html);
                }
            }else{
                header("location: ".base_url());
                die();
            }
            
            return $arrResponse;
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
            }else{
                header("location: ".base_url());
                die();
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

                                $request= $this->model->insertCategory($photoCategory,$strName,$strDescription,$route,$intStatus);
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
                                $request = $this->model->updateCategory($idCategory,$photoCategory,$strName,$strDescription,$route,$intStatus);
                            }
                        }
                        if($request > 0 ){
                            if($photo!=""){
                                uploadImage($photo,$photoCategory);
                            }
                            if($option == 1){
                                $arrResponse = $this->getCategories();
                                $arrResponse['msg'] = 'Datos guardados.';
                            }else{
                                $arrResponse = $this->getCategories();
                                $arrResponse['msg'] = 'Datos actualizados.';
                            }
                        }else if($request == 'exist'){
                            $arrResponse = array('status' => false, 'msg' => 'La categoría ya existe, prueba con otro nombre.');		
                        }else{
                            $arrResponse = array("status" => false, "msg" => 'No es posible guardar los datos.');
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }else{
                header("location: ".base_url());
                die();
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
                            $arrResponse = array("status"=>true,"msg"=>"Se ha eliminado.","data"=>$this->getCategories()['data']);
                        }else{
                            $arrResponse = array("status"=>false,"msg"=>"No es posible eliminar, intenta de nuevo.");
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }else{
                header("location: ".base_url());
                die();
            }
            die();
        }
        public function searchca($params){
            if($_SESSION['permitsModule']['r']){
                $search = strClean($params);
                $arrResponse = $this->getCategories(1,$search);
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function sortca($params){
            if($_SESSION['permitsModule']['r']){
                $sort = intval($params);
                $arrResponse = $this->getCategories(2,$sort);
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
    }

?>