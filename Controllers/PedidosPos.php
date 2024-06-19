<?php
    class PedidosPos extends Controllers{
        private $objProduct;
        public function __construct(){
            
            session_start();
            if(empty($_SESSION['login'])){
                header("location: ".base_url());
                die();
            }
            parent::__construct();
            sessionCookie();
            getPermits(6);
        }
        public function venta(){
            if($_SESSION['permitsModule']['w']){
                $data['page_tag'] = "Punto de venta";
                $data['page_title'] = "Punto de venta";
                $data['page_name'] = "punto de venta";
                $data['panelapp'] = "functions_orders_venta.js";
                $data['framing'] = "functions_molding_custom.js";
                $this->views->getView($this,"venta",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        /*************************POS methods*******************************/
        public function getProduct(){
            if($_SESSION['permitsModule']['w']){
                if($_POST['id']){
                    $id = intval($_POST['id']);
                    $request = $this->model->selectProduct($id);
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
        public function getProducts(){
            if($_SESSION['permitsModule']['w']){
                $request = $this->model->selectProducts();
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 
                        $priceOffer = '<span class="text-decoration-line-through">'.formatNum($request[$i]['price']).'</span>'.'<span class="text-danger">'.formatNum($request[$i]['discount']).'</span>';
                        $price = $request[$i]['discount']>0 ? $priceOffer : formatNum($request[$i]['price']);
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
        public function getCustomers(){
            if($_SESSION['permitsModule']['w']){
                $request = $this->model->selectCustomers();
                echo json_encode($request,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function setOrder(){
            if($_SESSION['permitsModule']['w']){
                if($_POST){
                    if(empty($_POST['id'])){
                        $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $strDate = $_POST['strDate'] == "" ? date("Y-m-d") : strClean($_POST['strDate']);
                        $arrProducts = json_decode($_POST['products'],true);
                        $arrTotal = json_decode($_POST['total'],true);
                        $id = intval($_POST['id']);
                        $request_customer = $this->model->selectCustomer($id);
                        if(!empty($request_customer)){
                            if(is_array($arrProducts) && !empty($arrProducts) && is_array($arrTotal) && !empty($arrTotal) ){
                                $dateObj = new DateTime($strDate);
                                $dateCount = 0;
                                while ($dateCount < 30) {
                                    $dateObj->modify('+1 day');
                                    $dayWeek = $dateObj->format('N');
                                    if($dayWeek < 7){
                                        $dateCount++;
                                    }
                                }
                                $dateBeat = $dateObj->format("Y-m-d");
                                $data = array(
                                    "customer"=>$request_customer,
                                    "date"=>$strDate,
                                    "date_beat"=>$dateBeat,
                                    "type"=>strClean($_POST['paymentList']),
                                    "note"=>strClean($_POST['strNote']),
                                    "status_order"=>intval($_POST['statusOrder']),
                                    "products"=>$arrProducts,
                                    "total"=>$arrTotal
                                );
                                $request = $this->model->insertOrder($data);
                                if($request > 0){
                                    $arrResponse = array("status"=>true,"msg"=>"La venta se ha registrado con éxito");
                                }else{
                                    $arrResponse = array("status"=>false,"msg"=>"Ha ocurrido un error, inténtelo de nuevo");
                                }
                            }else{
                                $arrResponse = array("status"=>false,"msg"=>"Debe agregar artículos a la venta!");
                            }
                        }else{
                            $arrResponse = array("status"=>false,"msg"=>"El cliente no existe!"); 
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
                        $btn = '<button type="button" class="btn btn-primary" onclick="getConfig(this,'.$request[$i]['id'].')">Enmarcar</button>';
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