<?php
    class Inventario extends Controllers{
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
        public function productos(){
            if($_SESSION['permitsModule']['r']){
                $data['page_tag'] = "Productos";
                $data['page_title'] = "Productos";
                $data['page_name'] = "productos";
                $data['products'] = $this->getProducts();
                $data['app'] = "functions_product.js";
                $this->views->getView($this,"productos",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function categorias(){
            if($_SESSION['permitsModule']['r']){
                $data['page_tag'] = "Categoría";
                $data['page_title'] = "Categorias";
                $data['page_name'] = "categoria";
                $data['categories'] = $this->getCategories();
                $data['app'] = "functions_productcategory.js";
                $this->views->getView($this,"categorias",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function subcategorias(){
            if($_SESSION['permitsModule']['r']){
                $data['page_tag'] = "Subcategoria";
                $data['page_title'] = "Subcategorias";
                $data['page_name'] = "subcategoria";
                $data['subcategories'] = $this->getSubCategories();
                $data['app'] = "functions_productsubcategory.js";
                $this->views->getView($this,"subcategorias",$data);
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
                    $request = $this->model->search($params);
                }else if($option == 2){
                    $request = $this->model->sort($params);
                }else{
                    $request = $this->model->selectProducts();
                }
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 

                        $status="";
                        $btnGlobe = '<a href="'.base_url().'/tienda/producto/'.$request[$i]['route'].'" target="_blank" class="btn btn-primary m-1 text-white" title="Ver página"><i class="fas fa-globe"></i></a>';
                        $btnView = '<button class="btn btn-info m-1" type="button" title="Watch" data-id="'.$request[$i]['idproduct'].'" name="btnView"><i class="fas fa-eye"></i></button>';
                        $btnEdit="";
                        $btnDelete="";
                        $price = formatNum($request[$i]['price']);
                        if($request[$i]['discount']>0){
                            $discount = '<span class="text-success">'.$request[$i]['discount'].'% OFF</span>';
                        }else{
                            $discount = '<span class="text-danger">0%</span>';
                        }
                        if($_SESSION['permitsModule']['u']){
                            $btnEdit = '<button class="btn btn-success m-1" type="button" title="Edit" data-id="'.$request[$i]['idproduct'].'" name="btnEdit"><i class="fas fa-pencil-alt"></i></button>';
                        }
                        if($_SESSION['permitsModule']['d']){
                            $btnDelete = '<button class="btn btn-danger m-1" type="button" title="Delete" data-id="'.$request[$i]['idproduct'].'" name="btnDelete"><i class="fas fa-trash-alt"></i></button>';
                        }
                        if($request[$i]['status']==1 && $request[$i]['stock']>0){
                            $status='<span class="badge me-1 bg-success">Activo</span>';
                        }else if($request[$i]['status']==2){
                            $status='<span class="badge me-1 bg-danger">Inactivo</span>';
                        }else{
                            $status='<span class="badge me-1 bg-warning">Agotado</span>';
                        }
                        $html.='
                            <tr class="item">
                                <td class="text-center">
                                    <img src="'.$request[$i]['image'].'" class="rounded">
                                </td>
                                <td class="text-center">'.$request[$i]['name'].'</td>
                                <td data-label="Categoría: ">'.$request[$i]['category'].'</td>
                                <td data-label="Subcategoría: ">'.$request[$i]['subcategory'].'</td>
                                <td data-label="Precio: ">'.$price.'</td>
                                <td data-label="Descuento: ">'.$discount.'</td>
                                <td data-label="Cantidad: ">'.$request[$i]['stock'].'</td>
                                <td data-label="Fecha: ">'.$request[$i]['date'].'</td>
                                <td data-label="Estado: ">'.$status.'</td>
                                <td class="item-btn">'.$btnGlobe.$btnView.$btnEdit.$btnDelete.'</td>
                            </tr>
                        ';
                    }
                    $arrResponse = array("status"=>true,"data"=>$html);
                }else{
                    $html = '<tr><td colspan="11">No hay datos</td></tr>';
                    $arrResponse = array("status"=>false,"data"=>$html);
                }
            }else{
                header("location: ".base_url());
                die();
            }
            
            return $arrResponse;
        }
        public function getProduct(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    unset($_SESSION['filesInfo']);
                    if(empty($_POST)){
                        $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $id = intval($_POST['idProduct']);
                        $request = $this->model->selectProduct($id);
                        $this->model->deleteTmpImage();
                        if(!empty($request)){
                            $request['priceFormat'] = formatNum($request['price']);
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
                    if(empty($_POST['txtName']) || empty($_POST['statusList']) || empty($_POST['categoryList'])
                    || empty($_POST['subcategoryList']) || empty($_POST['txtPrice']) || empty($_POST['txtStock'])){
                        $arrResponse = array("status" => false, "msg" => 'Error de datos');
                    }else{ 
                        $idProduct = intval($_POST['idProduct']);
                        $strReference = strtoupper(strClean($_POST['txtReference']));
                        $strName = ucwords(strClean($_POST['txtName']));
                        $strShortDescription = strClean($_POST['txtShortDescription']);
                        $idCategory = intval($_POST['categoryList']);
                        $idSubcategory = intval($_POST['subcategoryList']);
                        $intPrice = intval($_POST['txtPrice']);
                        $intDiscount = intval($_POST['txtDiscount']);
                        $intStock =  intval($_POST['txtStock']);
                        $intStatus = intval($_POST['statusList']);
                        $strDescription = strClean($_POST['txtDescription']);
                        
                        $route = clear_cadena($strName);
                        $route = strtolower(str_replace("¿","",$route));
                        $route = str_replace(" ","-",$route);
                        $route = str_replace("?","",$route);

                        $photos = $this->model->selectTmpImages();
                        //dep($photos);
                        if($idProduct == 0){
                            if($_SESSION['permitsModule']['w']){
                                $option = 1;
                                $request= $this->model->insertProduct($idCategory,$idSubcategory,$strReference,$strName,$strShortDescription,$strDescription,$intPrice,$intDiscount,$intStock,$intStatus,$route,$photos);
                            }
                        }else{
                            if($_SESSION['permitsModule']['u']){
                                $option = 2;
                                $request= $this->model->updateProduct($idProduct,$idCategory,$idSubcategory,$strReference,$strName,$strShortDescription,$strDescription,$intPrice,$intDiscount,$intStock,$intStatus,$route,$photos);
                            }
                        }
                        if($request > 0 ){
                            $this->model->deleteTmpImage();
                            if($option == 1){
                                $arrResponse = $this->getProducts();
                                $arrResponse['msg'] = 'Datos guardados.';
                            }else{
                                $arrResponse = $this->getProducts();
                                $arrResponse['msg'] = 'Datos actualizados';
                            }
                        }else if($request == 'exist'){
                            $arrResponse = array('status' => false, 'msg' => '¡Atención! El producto ya existe, pruebe con otro nombre y referencia.');		
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
        public function getSelectCategories(){
            $html='<option value="0" selected>Seleccione</option>';
            $request = $this->model->selectCategories();
            if(count($request)>0){
                for ($i=0; $i < count($request); $i++) { 
                    $html.='<option value="'.$request[$i]['idcategory'].'">'.$request[$i]['name'].'</option>';
                }
                $arrResponse = array("data"=>$html);
            }else{
                $arrResponse = array("data"=>"");
            }
            echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            die();
        }
        public function getSelectSubcategories(){
            if($_POST){
                $idCategory = intval(strClean($_POST['idCategory']));
                $html='<option value="0" selected>Seleccione</option>';
                $request = $this->model->getSelectSubcategories($idCategory);
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 
                        $html.='<option value="'.$request[$i]['idsubcategory'].'">'.$request[$i]['name'].'</option>';
                    }
                    $arrResponse = array("data"=>$html);
                }else{
                    $arrResponse = array("data"=>"");
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function setImg(){ 
            $arrImages = orderFiles($_FILES['txtImg'],"product");
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
        public function search($params){
            if($_SESSION['permitsModule']['r']){
                $search = strClean($params);
                $arrResponse = $this->getProducts(1,$params);
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function sort($params){
            if($_SESSION['permitsModule']['r']){
                $params = intval($params);
                $arrResponse = $this->getProducts(2,$params);
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
                    $request = $this->model->searchc($params);
                }else if($option == 2){
                    $request = $this->model->sortc($params);
                }else{
                    $request = $this->model->selectCategories();
                }
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 

                        $btnEdit="";
                        $btnDelete="";
                        
                        if($_SESSION['permitsModule']['u']){
                            $btnEdit = '<button class="btn btn-success m-1" type="button" title="Edit" data-id="'.$request[$i]['idcategory'].'" name="btnEdit"><i class="fas fa-pencil-alt"></i></button>';
                        }
                        if($_SESSION['permitsModule']['d']){
                            $btnDelete = '<button class="btn btn-danger m-1" type="button" title="Delete" data-id="'.$request[$i]['idcategory'].'" name="btnDelete"><i class="fas fa-trash-alt"></i></button>';
                        }
                        $html.='
                            <tr class="item">
                                <td data-label="Nombre: ">'.$request[$i]['name'].'</td>
                                <td class="item-btn">'.$btnEdit.$btnDelete.'</td>
                            </tr>
                        ';
                    }
                    $arrResponse = array("status"=>true,"data"=>$html);
                }else{
                    $html = '<tr><td colspan="11">No hay datos</td></tr>';
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
                            $request['picture'] = media()."/images/uploads/".$request['picture'];
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
                    if(empty($_POST['txtName'])){
                        $arrResponse = array("status" => false, "msg" => 'Error de datos');
                    }else{ 
                        $idCategory = intval($_POST['idCategory']);
                        $strName = ucwords(strClean($_POST['txtName']));
                        $strDescription = strClean($_POST['txtDescription']);
                        $route = clear_cadena($strName);
                        $route = strtolower(str_replace("¿","",$route));
                        $route = str_replace(" ","-",$route);
                        $route = str_replace("?","",$route);
                        $photo = "";
                        $photoCategory="";

                        if($idCategory == 0){
                            if($_SESSION['permitsModule']['w']){
                                $option = 1;

                                if($_FILES['txtImg']['name'] == ""){
                                    $photoCategory = "category.jpg";
                                }else{
                                    $photo = $_FILES['txtImg'];
                                    $photoCategory = 'category_'.bin2hex(random_bytes(6)).'.png';
                                }

                                $request= $this->model->insertCategory(
                                    $photoCategory, 
                                    $strName,
                                    $strDescription,
                                    $route
                                );
                            }
                        }else{
                            if($_SESSION['permitsModule']['u']){
                                $option = 2;
                                $request = $this->model->selectCategory($idCategory);
                                if($_FILES['txtImg']['name'] == ""){
                                    $photoCategory = $request['picture'];
                                }else{
                                    if($request['picture'] != "category.jpg"){
                                        deleteFile($request['picture']);
                                    }
                                    $photo = $_FILES['txtImg'];
                                    $photoCategory = 'category_'.bin2hex(random_bytes(6)).'.png';
                                }
                                $request = $this->model->updateCategory(
                                    $idCategory, 
                                    $photoCategory,
                                    $strName,
                                    $strDescription,
                                    $route
                                );
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
                        if($request['picture']!="category.jpg"){
                            deleteFile($request['picture']);
                        }
                        
                        $request = $this->model->deleteCategory($id);

                        if($request=="ok"){
                            $arrResponse = array("status"=>true,"msg"=>"Se ha eliminado.","data"=>$this->getCategories()['data']);
                        }else if($request =="exist"){
                            $arrResponse = array("status"=>false,"msg"=>"La categoría tiene al menos una subcategoría asignada, no puede ser eliminada.");
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
                $arrResponse = $this->getCategories(1,$search);
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function sortc($params){
            if($_SESSION['permitsModule']['r']){
                $sort = intval($params);
                $arrResponse = $this->getCategories(2,$sort);
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        /*************************SubCategory methods*******************************/
        public function getSubCategories($option=null,$params=null){
            if($_SESSION['permitsModule']['r']){
                $html="";
                $request="";
                if($option == 1){
                    $request = $this->model->searchs($params);
                }else if($option == 2){
                    $request = $this->model->sorts($params);
                }else{
                    $request = $this->model->selectSubCategories();
                }
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 

                        $btnEdit="";
                        $btnDelete="";
                        
                        if($_SESSION['permitsModule']['u']){
                            $btnEdit = '<button class="btn btn-success m-1" type="button" title="Edit" data-id="'.$request[$i]['idsubcategory'].'" name="btnEdit"><i class="fas fa-pencil-alt"></i></button>';
                        }
                        if($_SESSION['permitsModule']['d']){
                            $btnDelete = '<button class="btn btn-danger m-1" type="button" title="Delete" data-id="'.$request[$i]['idsubcategory'].'" name="btnDelete"><i class="fas fa-trash-alt"></i></button>';
                        }
                        $html.='
                            <tr class="item">
                                <td data-label="Nombre: ">'.$request[$i]['name'].'</td>
                                <td data-label="Categoría: ">'.$request[$i]['category'].'</td>
                                <td class="item-btn">'.$btnEdit.$btnDelete.'</td>
                            </tr>
                        ';
                    }
                    $arrResponse = array("status"=>true,"data"=>$html);
                }else{
                    $html = '<tr><td colspan="11">No hay datos</td></tr>';
                    $arrResponse = array("status"=>false,"data"=>$html);
                }
            }else{
                header("location: ".base_url());
                die();
            }
            return $arrResponse;
        }
        public function getSubCategory(){
            if($_SESSION['permitsModule']['r']){

                if($_POST){
                    if(empty($_POST)){
                        $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $idCategory = intval($_POST['idSubCategory']);
                        $request = $this->model->selectSubCategory($idCategory);
                        if(!empty($request)){
                            $arrResponse = array("status"=>true,"data"=>$request);
                        }else{
                            $arrResponse = array("status"=>false,"msg"=>"Error, intenta de nuevo."); 
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
        public function setSubCategory(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    if(empty($_POST['txtName']) ||empty($_POST['categoryList'])){
                        $arrResponse = array("status" => false, "msg" => 'Error de datos.');
                    }else{ 
                        $idSubCategory = intval($_POST['idSubCategory']);
                        $strName = ucwords(strClean($_POST['txtName']));
                        $idCategory = intval(strClean($_POST['categoryList']));
                        $route = clear_cadena($strName);
                        $route = strtolower(str_replace("¿","",$route));
                        $route = str_replace(" ","-",$route);
                        $route = str_replace("?","",$route);

                        if($idSubCategory == 0){
                            if($_SESSION['permitsModule']['w']){

                                $option = 1;
                                $request= $this->model->insertSubCategory($idCategory,$strName, $route);
                            }
                        }else{
                            if($_SESSION['permitsModule']['u']){
                                $option = 2;
                                $request = $this->model->updateSubCategory($idSubCategory,$idCategory, $strName, $route);
                            }
                        }
                        if($request > 0 ){
                            if($option == 1){
                                $arrResponse = $this->getSubCategories();
                                $arrResponse['msg'] = 'Datos guardados.';
                            }else{
                                $arrResponse = $this->getSubCategories();
                                $arrResponse['msg'] = 'Datos actualizados.';
                            }
                        }else if($request == 'exist'){
                            $arrResponse = array('status' => false, 'msg' => 'La subcategoría ya existe, intenta con otro nombre.');		
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
        public function delSubCategory(){
            if($_SESSION['permitsModule']['d']){
                if($_POST){
                    if(empty($_POST['idSubCategory'])){
                        $arrResponse=array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $id = intval($_POST['idSubCategory']);
                        $request = $this->model->deleteSubCategory($id);
                        if($request=="ok"){
                            $arrResponse = array("status"=>true,"msg"=>"Se ha eliminado.","data"=>$this->getSubCategories()['data']);
                        }else if($request=="exist"){
                            $arrResponse = array("status"=>false,"msg"=>"La subcategoría tiene al menos un producto asignado, no puede ser eliminado.");
                        }
                        else{
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
        public function searchs($params){
            if($_SESSION['permitsModule']['r']){
                $search = strClean($params);
                $arrResponse = $this->getSubCategories(1,$search);
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function sorts($params){
            if($_SESSION['permitsModule']['r']){
                $sort = intval($params);
                $arrResponse = $this->getSubCategories(2,$sort);
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
    }

?>