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
                $data['tipos'] = $this->model->selectCategories();
                $data['panelapp'] = "functions_orders_venta.js";
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
/*
        public function setOrder(){
            dep($_POST);exit;
            if($_SESSION['permitsModule']['w']){
                if($_POST){
                    if(empty($_POST['id'])){
                        $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $total = 0;
                        $idUser = intval($_POST['id']);
                        $idOrder = intval($_POST['idOrder']);
                        $customInfo = $this->model->selectCustomer($idUser);
                        $status = intval($_POST['statusList']);
                        $statusOrder = STATUS[intval($_POST['statusOrder'])];
                        $strNote = strClean($_POST['strNote']);
                        $strName = $customInfo['firstname']." ".$customInfo['lastname'];
                        $strEmail = $customInfo['email'];
                        $strPhone = $customInfo['phone'];
                        $strIdentification = $customInfo['identification'];
                        $strAddress = $customInfo['address']." ".$customInfo['city']."/".$customInfo['state']."/".$customInfo['country'];
                        $type = PAGO[intval($_POST['paymentList'])];
                        $envio = 0;
                        $option="";
                        $request="";
                        $arrSuscription=array();
                        $updateCustomer=intval($_POST['updateCustomer']);
                        $strDate = $_POST['strDate'];
                        $dateObj = new DateTime($strDate);
                        $dateCount = 0;
                        //$dateBeat = date("Y-m-d",strtotime($strDate . " +30 days"));
                        
                        while ($dateCount < 30) {
                            $dateObj->modify('+1 day');
                            $dayWeek = $dateObj->format('N');

                            if($dayWeek < 7){
                                $dateCount++;
                            }
                        }
                        $dateBeat = $dateObj->format("Y-m-d");

                        if($status == 1){
                            $status = "approved";
                        }else if($status == 2){
                            $status = "pendent";
                        }else{
                            $status = "canceled";
                        }
                        if($idOrder == 0){
                            $discount = intval($_POST['discount']);
                            $suscription = intval($_POST['received']);
                            $option = 1;
                            foreach ($_SESSION['arrPOS'] as $pro) {
                                if($pro['topic'] == 2){
                                    if($pro['producttype'] == 2){
                                        $total+=$pro['qty']*$pro['variant']['price'];
                                    }else{
                                        $total+=$pro['qty']*$pro['price'];
                                    }
                                }else{
                                    $total+=$pro['qty']*$pro['price'];
                                }
                            }
                            $total = $total-$discount;
                            
                            if($suscription-$discount == $total){
                                $arrSuscription = array(
                                    [
                                        "date"=>date("Y-m-d"),
                                        "debt"=>$total,
                                        "type"=>$type
                                    ]
                                );
                            }else if( $suscription < $total && $suscription > 0){
                                $arrSuscription = array(
                                    [
                                        "date"=>date("Y-m-d"),
                                        "debt"=>$suscription,
                                        "type"=>$type
                                    ]
                                );
                            }else{
                                $arrSuscription = array(
                                    [
                                        "date"=>date("Y-m-d"),
                                        "debt"=>0,
                                        "type"=>$type
                                    ]
                                );
                            }
                            $request = $this->model->insertOrder($idUser,$strName,$strIdentification,$strEmail,$strPhone,$strAddress,$strNote,$strDate,$discount,$envio,$arrSuscription,$total,$status,$type,$statusOrder,$dateBeat);          
                        }else{
                            $option = 2;
                            $arrSuscription = json_decode($_POST['suscription'],true);
                            $request = $this->model->updateOrder($idOrder,$strName,$strIdentification,$strEmail,$strPhone,$strAddress,$strDate,$strNote,$arrSuscription,$type,$status,$statusOrder,$dateBeat,$updateCustomer);          
                        }
                        if($request>0){
                            if($option == 1){
                                $arrOrder = array("idorder"=>$request,"iduser"=>$idUser,"products"=>$_SESSION['arrPOS']);
                                $requestDetail = $this->model->insertOrderDetail($arrOrder);
                                unset($_SESSION['arrPOS']);
                                $arrResponse = array("status"=>true,"msg"=>"Pedido realizado");
                            }else{
                                $arrResponse = array("status"=>true,"msg"=>"Pedido actualizado");
                            }
                        }else if(!$request){
                            $arrResponse = array("status"=>false,"msg"=>"Error, los anticipos superan al monto total, inténtelo de nuevo.");
                        }else{
                            $arrResponse = array("status"=>false,"msg"=>"Error, no se ha podido realizar el pedido, inténtelo de nuevo.");
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }*/
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
    }
?>