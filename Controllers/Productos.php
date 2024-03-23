<?php
    class Productos extends Controllers{
        public function __construct(){
            session_start();
            if(empty($_SESSION['login'])){
                header("location: ".base_url());
                die();
            }
            parent::__construct();
            sessionCookie();
            getPermits(11);
            
        }
        public function productos(){
            if($_SESSION['permitsModule']['r']){
                $data['page_tag'] = "producto";
                $data['page_title'] = "Productos";
                $data['page_name'] = "productos";
                $data['panelapp'] = "functions_products.js";
                $this->views->getView($this,"productos",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function producto($params){
            if($_SESSION['permitsModule']['w']){
                $data['page_tag'] = "Productos";
                $data['page_title'] = "Productos";
                $data['page_name'] = "productos";
                $data['panelapp'] = "functions_product.js";
                if($params==""){
                    $this->views->getView($this,"crearproducto",$data);
                }else{
                    $id = intval(strClean($params));
                    $data['product'] = $this->getProduct($id);
                    $this->views->getView($this,"editarproducto",$data);
                }
            }else{
                header("location: ".base_url());
                die();
            }
        }
        /*************************Product methods*******************************/
        public function getProducts(){
            if($_SESSION['permitsModule']['r']){
                $request = $this->model->selectProducts();
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 

                        $status="";
                        $btnView = '<a href="'.base_url().'/tienda/producto/'.$request[$i]['route'].'" target="_blank" class="btn btn-info m-1 text-white" title="Ver página"><i class="fas fa-eye"></i></a>';
                        $btnEdit="";
                        $btnDelete="";
                        $price = "";
                        $variant = $request[$i]['product_type'] == 2? "Desde " : "";
                        if($request[$i]['discount']>0){
                            $price = '<span class="text-danger">'.$variant.formatNum($request[$i]['price']*(1-($request[$i]['discount']*0.01)),false).'</span>'.' <span class="text-secondary text-decoration-line-through">'.formatNum($request[$i]['price'],false).'</span>';
                            $discount = '<span class="text-danger">'.$request[$i]['discount'].'%</span>';
                        }else{
                            $price = $variant.formatNum($request[$i]['price'],false);
                            $discount = "0%";
                        }
                        if($_SESSION['permitsModule']['u']){
                            $btnEdit = '<a href="'.base_url().'/inventario/producto/'.$request[$i]['idproduct'].'" class="btn btn-success m-1 text-white" title="Editar"><i class="fas fa-pencil-alt"></i></a>';
                        }
                        if($_SESSION['permitsModule']['d']){
                            $btnDelete = '<button class="btn btn-danger m-1" type="button" title="Eliminar" onclick="deleteItem('.$request[$i]['idproduct'].')"><i class="fas fa-trash-alt"></i></button>';
                        }
                        if($request[$i]['status']==1 && $request[$i]['stock']>0){
                            $status='<span class="badge me-1 bg-success">Activo</span>';
                        }else if($request[$i]['status']==2){
                            $status='<span class="badge me-1 bg-danger">Inactivo</span>';
                        }else{
                            $status='<span class="badge me-1 bg-warning">Agotado</span>';
                        }
                        $request[$i]['status'] = $status;
                        $request[$i]['options'] = $btnView.$btnEdit.$btnDelete;
                        $request[$i]['discount'] = $discount;
                        $request[$i]['price'] = $price;
                    }
                }
                echo json_encode($request,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function getProduct($id){
            if($_SESSION['permitsModule']['r']){
                $request = $this->model->selectProduct($id);
                $request['categories'] = $this->model->selectCategories();
                $request['subcategories'] = $this->model->getSelectSubcategories($request['idcategory']);
                return $request;
            }else{
                header("location: ".base_url());
            }
            die();
        }
        public function setProduct(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    if(empty($_POST['txtName']) || empty($_POST['statusList']) || empty($_POST['categoryList'])
                    || empty($_POST['subcategoryList'])){
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
                        $intProductType = intval($_POST['selectProductType']);
                        $framingMode = intval($_POST['framingMode']);
                        $arrVariants = json_decode($_POST['variants'],true);
                        $arrSpecs = $_POST['specs'];
                        $imgFraming = "";
                        $photoFraming="category.jpg";
                        $reference = $strReference != "" ? $strReference."-" : "";

                        $route = clear_cadena($reference.$strName);
                        $route = strtolower(str_replace("¿","",$route));
                        $route = str_replace(" ","-",$route);
                        $route = str_replace("?","",$route);
                        $photos = json_decode($_POST['images'],true);

                        if($idProduct == 0){
                            if($_SESSION['permitsModule']['w']){
                                if($framingMode==1){
                                    if($framingMode == 1 && $_FILES['txtImgFrame']['name'] != ""){
                                        $imgFraming = $_FILES['txtImgFrame'];
                                        $photoFraming = 'framing_'.bin2hex(random_bytes(6)).'.png';
                                    }
                                }
                                $option = 1;
                                $request= $this->model->insertProduct(
                                    $idCategory,
                                    $idSubcategory,
                                    $strReference,
                                    $strName,
                                    $strShortDescription,
                                    $strDescription,
                                    $intPrice,
                                    $intDiscount,
                                    $intStock,
                                    $intStatus,
                                    $route,
                                    $photos,
                                    $framingMode,
                                    $photoFraming,
                                    $intProductType,
                                    $arrVariants,
                                    $arrSpecs
                                );
                            }
                        }else{
                            if($_SESSION['permitsModule']['u']){
                                $request = $this->model->selectProduct($idProduct);
                                if($framingMode==1){
                                    if($_FILES['txtImgFrame']['name'] == ""){
                                        $photoFraming = $request['framing_img'];
                                    }else{
                                        /*
                                        if($request['framing_img'] != "category.jpg" && $request['framing_img'] != null ){
                                            deleteFile($request['framing_img']);
                                        }*/
                                        $imgFraming = $_FILES['txtImgFrame'];
                                        $photoFraming = 'framing_'.bin2hex(random_bytes(6)).'.png';
                                    }
                                }
                                
                                $option = 2;
                                $request= $this->model->updateProduct(
                                    $idProduct,
                                    $idCategory,
                                    $idSubcategory,
                                    $strReference,
                                    $strName,
                                    $strShortDescription,
                                    $strDescription,
                                    $intPrice,
                                    $intDiscount,
                                    $intStock,
                                    $intStatus,
                                    $route,
                                    $photos,
                                    $framingMode,
                                    $photoFraming,
                                    $intProductType,
                                    $arrVariants,
                                    $arrSpecs
                                );
                            }
                        }
                        if($request > 0 ){
                            if($imgFraming!="" && $photoFraming !=""){
                                uploadImage($imgFraming,$photoFraming);
                            }
                            if($option == 1){
                                $arrResponse = array("status" => true,"msg"=>"Datos guardados.");
                            }else{
                                $arrResponse = array("status" => true,"msg"=>"Datos actualizados.");
                            }
                        }else if($request == 'exist'){
                            $arrResponse = array('status' => false, 'msg' => '¡Atención! El producto ya existe, pruebe con otro nombre y referencia.');		
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
                        $request = $this->model->deleteProduct($id);
                        if($request=="ok"){
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
    }

?>