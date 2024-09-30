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
                $data['page_tag'] = "Crear Producto";
                $data['page_title'] = "Nuevo Producto";
                $data['page_name'] = "productos";
                $data['panelapp'] = "functions_product.js";
                if($params==""){
                    $this->views->getView($this,"crearproducto",$data);
                }else{
                    $data['page_title'] = "Editar Producto";
                    $data['id'] = intval(strClean($params));
                    $data['images'] = $this->model->selectProduct($data['id'])['image'];
                    $this->views->getView($this,"editarproducto",$data);
                }
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function insumo($params){
            if($_SESSION['permitsModule']['w']){
                $data['page_tag'] = "Asignar insumos";
                $data['page_title'] = "Asignar insumos";
                $data['page_name'] = "insumoos";
                $data['panelapp'] = "functions_productos_insumos.js";
                $data['id'] = intval(strClean($params));
                $data['data'] = $this->model->selectProduct($data['id']);
                $this->views->getView($this,"insumo",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        /*************************Product methods*******************************/
        public function getInsumo(){
            if($_SESSION['permitsModule']['w']){
                if($_POST['id']){
                    $id = intval($_POST['id']);
                    $request = $this->model->selectInsumo($id);
                    if(!empty($request)){
                        $arrResponse = array("status"=>true,"data"=>$request);
                    }else{
                        $arrResponse = array("status"=>false,"msg"=>"El artículo no existe");
                    }
                }else{
                    $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function getInsumos(){
            if($_SESSION['permitsModule']['u']){
                $request = $this->model->selectInsumos();
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 
                        $price = formatNum($request[$i]['price']);
                        $btn = '<button type="button" class="btn btn-primary" onclick="getProduct(this,'.$request[$i]['idproduct'].')"><i class="fas fa-plus"></i></button>';
                        $request[$i]['stock'] = !$request[$i]['is_stock'] ? "N/A" : $request[$i]['stock'];
                        $variant = $request[$i]['product_type'] == 1 ? "Desde " : "";
                        $request[$i]['format_price'] = $variant.$price;
                        $request[$i]['options'] = $btn;
                    }
                }
                echo json_encode($request,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function getProducts(){
            if($_SESSION['permitsModule']['r']){
                $request = $this->model->selectProducts();
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 

                        $status="";
                        $btnView = "";
                        $btnEdit="";
                        $btnDelete="";
                        $btnOptions = "";
                        $variant = $request[$i]['product_type'] == 1 ? "Desde " : "";
                        if($request[$i]['is_product'] || $request[$i]['is_combo']){
                            $btnView = '<a href="'.base_url().'/tienda/producto/'.$request[$i]['route'].'" target="_blank" class="btn btn-info m-1 text-white" title="Ver página"><i class="fas fa-eye"></i></a>';
                        }
                        if($_SESSION['permitsModule']['u']){
                            if($request[$i]['is_combo']){
                                $btnOptions='<a href="'.base_url().'/Productos/insumo/'.$request[$i]['idproduct'].'" target="_blank" class="btn btn-primary m-1 text-white" title="Asignar insumos"><i class="fa fa-list" aria-hidden="true"></i></a>';
                            }
                            $btnEdit = '<a href="'.base_url().'/Productos/producto/'.$request[$i]['idproduct'].'" class="btn btn-success m-1 text-white" title="Editar"><i class="fas fa-pencil-alt"></i></a>';
                        }
                        if($_SESSION['permitsModule']['d']){
                            $btnDelete = '<button class="btn btn-danger m-1" type="button" title="Eliminar" onclick="deleteItem('.$request[$i]['idproduct'].')"><i class="fas fa-trash-alt"></i></button>';
                        }
                        if($request[$i]['is_stock'] && $request[$i]['status']==1){
                            if($request[$i]['stock'] <= 0){
                                $status='<span class="badge me-1 bg-warning">Agotado</span>';
                            }
                        }else if( $request[$i]['status']==1){
                            $status='<span class="badge me-1 bg-success">Activo</span>';
                        }else if($request[$i]['status'] == 2){
                            $status='<span class="badge me-1 bg-danger">Inactivo</span>';
                        }

                        $request[$i]['status'] = $status;
                        $request[$i]['options'] = $btnView.$btnOptions.$btnEdit.$btnDelete;
                        $request[$i]['price_purchase'] = $variant.formatNum($request[$i]['price_purchase'] != null ? $request[$i]['price_purchase'] : 0);
                        $request[$i]['price'] = $variant.formatNum($request[$i]['price'] != null ? $request[$i]['price'] : 0);
                        $request[$i]['discount'] = $variant.formatNum($request[$i]['discount'] != null ? $request[$i]['discount'] : 0);
                        $request[$i]['stock'] = !$request[$i]['is_stock'] ? "No maneja inventario" : $request[$i]['stock'];
                        $request[$i]['is_product'] = $request[$i]['is_product'] ? '<i class="fa fa-check text-success" aria-hidden="true"></i>' : '<i class="fa fa-times text-danger" aria-hidden="true"></i>';
                        $request[$i]['is_ingredient'] = $request[$i]['is_ingredient'] ? '<i class="fa fa-check text-success" aria-hidden="true"></i>' : '<i class="fa fa-times text-danger" aria-hidden="true"></i>';
                        $request[$i]['is_combo'] = $request[$i]['is_combo'] ? '<i class="fa fa-check text-success" aria-hidden="true"></i>' : '<i class="fa fa-times text-danger" aria-hidden="true"></i>';
                    }
                }
                echo json_encode($request,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function getProduct($id){
            if($_SESSION['permitsModule']['r']){
                $id = intval($id);
                $arrProduct = $this->model->selectProduct($id);
                if(!empty($arrProduct)){
                    $arrInitial = array(
                        "categories"=>$this->model->selectCategories(),
                        'specs' => $this->model->selectSpecs(),
                        'measures' => $this->model->selectMeasures(),
                        'variants' => $this->model->selectVariants(),
                        'subcategories' => $this->model->getSelectSubcategories($arrProduct['idcategory'])
                    );
                    $arrData = array("product"=>$arrProduct,"initial"=>$arrInitial);
                    echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                }else{
                    header("location: ".base_url()."/Productos");
                }
            }
            die();
        }
        public function setProduct(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    $arrData = json_decode($_POST['data'],true);
                    if(empty($arrData)){
                        $arrResponse = array("status" => false, "msg" => 'Error de datos');
                    }else{ 
                        $arrGeneral = $arrData['general'];
                        $id = intval($arrGeneral['id']);
                        $strName = ucwords(strClean($arrGeneral['name']));
                        $strReference = strtoupper(strClean($arrGeneral['reference']));
                        $imgFraming = "";
                        $photoFraming="category.jpg";
                        $reference = $strReference != "" ? $strReference."-" : "";
                        $route = clear_cadena($reference.$strName);
                        $route = strtolower(str_replace("¿","",$route));
                        $route = str_replace(" ","-",$route);
                        $route = str_replace("?","",$route);

                        $data = array(
                            "images"=>$arrGeneral['images'],
                            "specs"=>$arrGeneral['specs'],
                            "status"=>intval($arrGeneral['status']),
                            "subcategory"=>intval($arrGeneral['subcategory']),
                            "category"=>intval($arrGeneral['category']),
                            "framing_mode"=> intval($arrGeneral['framing_mode']),
                            "measure"=>intval($arrGeneral['measure']),
                            "import"=>intval($arrGeneral['import']),
                            "is_product"=>intval($arrGeneral['is_product']),
                            "is_ingredient"=>intval($arrGeneral['is_ingredient']),
                            "is_combo"=>intval($arrGeneral['is_combo']),
                            "is_stock"=>intval($arrGeneral['is_stock']),
                            "price_purchase"=>intval($arrGeneral['price_purchase']),
                            "price_sell"=>intval($arrGeneral['price_sell']),
                            "price_offer"=>intval($arrGeneral['price_offer']),
                            "product_type"=>intval($arrGeneral['product_type']),
                            "stock"=>intval($arrGeneral['stock']),
                            "min_stock"=>intval($arrGeneral['min_stock']),
                            "short_description"=>strClean($arrGeneral['short_description']),
                            "description"=>strClean($arrGeneral['description']),
                            "name"=>$strName,
                            "reference"=>$strReference,
                            "route"=>$route,
                            "variants"=>array(
                                "combinations"=>$arrData['combinations'],
                                "variations"=>$arrData['variants'],
                                "is_stock"=>$arrData['is_stock']
                            )
                        );
                        //dep($data);exit;
                        if($id == 0){
                            if($_SESSION['permitsModule']['w']){
                                if($data['framing_mode']==1){
                                    if($data['framing_mode']==1 && $_FILES['txtImgFrame']['name'] != ""){
                                        $imgFraming = $_FILES['txtImgFrame'];
                                        $photoFraming = 'framing_'.bin2hex(random_bytes(6)).'.png';
                                    }
                                }
                                $option = 1;
                                $data['photo_framing'] = $photoFraming;
                                $request= $this->model->insertProduct($data);
                            }
                        }else{
                            if($_SESSION['permitsModule']['u']){
                                $request = $this->model->selectProduct($id);
                                if($request['framing_mode']==1){
                                    if($_FILES['txtImgFrame']['name'] == ""){
                                        $photoFraming = $request['framing_img'];
                                    }else{
                                        if($request['framing_img'] != "category.jpg" && $request['framing_img'] != null ){
                                            deleteFile($request['framing_img']);
                                        }
                                        $imgFraming = $_FILES['txtImgFrame'];
                                        $photoFraming = 'framing_'.bin2hex(random_bytes(6)).'.png';
                                    }
                                } 
                                $option = 2;
                                $data['photo_framing'] = $photoFraming;
                                $request= $this->model->updateProduct($id,$data);
                            }
                        }
                        if(is_numeric($request) && $request > 0){
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

        /*************************Other methods*******************************/
        public function reFactProducts(){
            $request = $this->model->selectTempProducts();
            $total = count($request);
            $variantsToInsert = [];
            for ($i=0; $i < $total; $i++) { 
                $specs = json_decode($request[$i]['specifications'],true);
                if(!empty($specs)){
                    for ($j=0; $j < count($specs); $j++) { 
                        $specs[$j]['id'] = $this->model->selectTempSpec($specs[$j]['name']);
                    }
                    $request[$i]['specs'] = $specs;
                }
                if(!empty($request[$i]['variants'])){
                    $variants = $request[$i]['variants'];
                    $options = [];
                    $combination = [];
                    for ($j=0; $j < count($variants); $j++) {
                        array_push($combination,array(
                            "name"=>$variants[$j]['width']."x".$variants[$j]['height'],
                            "price_purchase"=>0,
                            "price_sell"=>$variants[$j]['price'],
                            "price_offer"=>0,
                            "stock"=>100,
                            "min_stock"=>0,
                            "sku"=>"",
                            "status"=>1,
                            "is_stock"=>0
                        ));
                        array_push($variantsToInsert,$variants[$j]['width']."x".$variants[$j]['height']);
                        array_push($options,$variants[$j]['width']."x".$variants[$j]['height']);
                    }
                    $request[$i]['variants'] = array("combinations"=>$combination,"variations"=>[array("id"=>5,"name"=>"Medidas","options"=>$options)]);
                }
                $this->model->updateTempProduct($request[$i]['idproduct'],$request[$i]);
            }
            $variantsToInsert =array_values(array_unique($variantsToInsert));
            $this->model->insertOptions(5,$variantsToInsert);
            dep($variantsToInsert);
            dep($request);
            die();
        }
        public function getData(){
            $request['specs'] = $this->model->selectSpecs();
            $request['categories'] = $this->model->selectCategories();
            $request['measures'] = $this->model->selectMeasures();
            $request['variants'] = $this->model->selectVariants();
            echo json_encode($request,JSON_UNESCAPED_UNICODE);
            die();
        }
    }

?>