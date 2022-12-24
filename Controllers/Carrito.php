<?php
    
    require_once("Models/ProductTrait.php");
    require_once("Models/CustomerTrait.php");
    require_once("Models/LoginModel.php");
    class Carrito extends Controllers{
        use ProductTrait, CustomerTrait;
        private $login;
        public function __construct(){
            session_start();
            parent::__construct();
            sessionCookie();
            $this->login = new LoginModel();
        }

        /******************************Views************************************/
        public function carrito(){
            $company=getCompanyInfo();
            $data['page_tag'] = $company['name'];
            $data['page_title'] ="Carrito de compras | ".$company['name'];
            $data['page_name'] = "carrito";
            $data['shipping'] = $this->selectShippingMode();
            if(isset($_GET['cupon'])){
                $cupon = strtoupper(strClean($_GET['cupon']));
                $data['cupon'] = $this->selectCouponCode($cupon);
                if(empty($data['cupon'])){
                    header("location: ".base_url()."/carrito");
                    die();
                }
            }
            if(isset($_GET['situ'])){
                $situ = strtolower(strClean($_GET['situ']));
                if($situ != "true" && $situ != "false"){
                    header("location: ".base_url()."/carrito");
                    die();
                }
                //header("location: ".base_url()."/carrito");
                //die();
            }
            $data['app'] = "functions_cart.js";
            $this->views->getView($this,"carrito",$data); 
        }
        /******************************Cart methods************************************/
        public function addCart(){
            //unset($_SESSION['arrCart']);exit;
            //dep($_POST);exit;
            if($_POST){ 
                $id = intval(openssl_decrypt($_POST['idProduct'],METHOD,KEY));
                $qty = intval($_POST['txtQty']);
                $topic = intval($_POST['topic']);
                $qtyCart = 0;
                $arrCart = array();
                $valiQty =true;
                if(is_numeric($id)){
                    $request = $this->getProductT($id);
                    
                    $price = $request['price'];
                    if($request['discount']>0){
                        $price = $request['price'] - ($request['price']*($request['discount']/100));
                    }
                    $data = array("name"=>$request['name'],"image"=>$request['image'][0]['url'],"route"=>base_url()."/tienda/producto/".$request['route']);

                    if(!empty($request)){
                        $arrProduct = array(
                            "topic"=>2,
                            "id"=>openssl_encrypt($id,METHOD,KEY),
                            "name" => $request['name'],
                            "qty"=>$qty,
                            "image"=>$request['image'][0]['url'],
                            "url"=>base_url()."/tienda/producto/".$request['route'],
                            "price" =>$price,
                            "stock"=>$request['stock']
                        );
                        if(isset($_SESSION['arrCart'])){
                            $arrCart = $_SESSION['arrCart'];
                            $currentQty = 0;
                            $flag = true;
                            for ($i=0; $i < count($arrCart) ; $i++) { 
                                if($arrCart[$i]['topic'] == 2){
                                    if($arrCart[$i]['id'] == $arrProduct['id']){
                                        $currentQty = $arrCart[$i]['qty'];
                                        $arrCart[$i]['qty']+= $qty;
                                        if($arrCart[$i]['qty'] > $request['stock']){
                                            $arrCart[$i]['qty'] = $currentQty;
                                            $arrResponse = array("status"=>false,"msg"=>"No hay suficientes unidades","data"=>$data);
                                            $flag = false;
                                            break;
                                        }else{
                                            $_SESSION['arrCart'] = $arrCart;
                                            foreach ($_SESSION['arrCart'] as $quantity) {
                                                $qtyCart += $quantity['qty'];
                                            }
                                            $arrResponse = array("status"=>true,"msg"=>"Ha sido agregado a tu carrito.","qty"=>$qtyCart,"data"=>$data);
                                        }
                                        $flag =false;
                                        break;
                                    }
                                }
                            }
                            if($flag){
                                if($qty > $request['stock']){
                                    $arrResponse = array("status"=>false,"msg"=>"No hay suficientes unidades","data"=>$data);
                                    $_SESSION['arrCart'] = $arrCart;
                                }else{
                                    array_push($arrCart,$arrProduct);
                                    $_SESSION['arrCart'] = $arrCart;
                                    foreach ($_SESSION['arrCart'] as $quantity) {
                                        $qtyCart += $quantity['qty'];
                                    }
                                    $arrResponse = array("status"=>true,"msg"=>"Ha sido agregado a tu carrito.","qty"=>$qtyCart,"data"=>$data);
                                }
                            }
                        }else{
                            if($qty > $request['stock']){
                                $arrResponse = array("status"=>false,"msg"=>"No hay suficientes unidades","data"=>$data);
                            }else{
                                array_push($arrCart,$arrProduct);
                                $_SESSION['arrCart'] = $arrCart;
                                foreach ($_SESSION['arrCart'] as $quantity) {
                                    $qtyCart += $quantity['qty'];
                                }
                                $arrResponse = array("status"=>true,"msg"=>"Ha sido agregado a tu carrito.","qty"=>$qtyCart,"data"=>$data);
                            } 
                        }
                    }else{
                        $arrResponse = array("status"=>false,"msg"=>"El producto no existe");
                    }
                    
                }else{
                    $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function updateCart(){
            //dep($_POST);exit;
            if($_POST){
                $id = $_POST['id'];
                $topic = intval($_POST['topic']);
                $code = strClean($_POST['cupon']);
                if($topic == 1){
                    $height = floatval($_POST['height']);
                    $width = floatval($_POST['width']);
                    $margin = intval($_POST['margin']);
                    $style = strClean($_POST['style']);
                    $colorMargin = strClean($_POST['colormargin']);
                    $colorBorder = strClean($_POST['colorborder']);
                    $idType = intval($_POST['idType']);
                    $reference = strClean($_POST['reference']);
                }
                $total =0;
                $totalPrice = 0;
                $subtotal = 0;
                $qty = intval($_POST['qty']);
                $city = intval($_POST['city']);
                if($qty > 0){
                    
                    $arrProducts = $_SESSION['arrCart'];
                    //dep($arrProducts);exit;
                    for ($i=0; $i < count($arrProducts) ; $i++) { 
                        if($arrProducts[$i]['topic'] == 1 && $topic == 1){
                            if($arrProducts[$i]['style'] == $style && $arrProducts[$i]['height'] == $height &&
                            $arrProducts[$i]['width'] == $width && $arrProducts[$i]['margin'] == $margin &&
                            $arrProducts[$i]['colormargin'] == $colorMargin && $arrProducts[$i]['colorborder'] == $colorBorder && 
                            $arrProducts[$i]['idType'] == $idType && $arrProducts[$i]['reference'] == $reference){
                                $arrProducts[$i]['qty'] = $qty;
                                $totalPrice =$arrProducts[$i]['qty']*$arrProducts[$i]['price'];
                                break;
                            }
                        }else if($arrProducts[$i]['topic'] == 2 && $topic == 2){
                            if($arrProducts[$i]['id'] == $id){
                                $idProduct = intval(openssl_decrypt($id,METHOD,KEY));
                                $stock = $this->getProductT($idProduct)['stock'];
                                //dep($stock);exit;
                                if($qty >= $stock ){
                                    $qty = $stock;
                                }
                                $arrProducts[$i]['qty'] = $qty;
                                $totalPrice =$arrProducts[$i]['qty']*$arrProducts[$i]['price'];
                                break;
                            }
                        }
                    }
                    $_SESSION['arrCart'] = $arrProducts;
                    $shipping = $this->calcTotalCart($_SESSION['arrCart'],$code,$city);
                    $subtotal = $shipping['subtotal'];
                    $total = $shipping['total'];
                    $cupon = $shipping['cupon'];
                    $arrResponse = array(
                        "status"=>true,
                        "total" =>formatNum($total),
                        "subtotal"=>formatNum($subtotal),
                        "totalPrice"=>formatNum($totalPrice,false),
                        "qty"=>$qty,
                        "cupon"=>formatNum($cupon)
                    );
                }else{
                    $arrResponse = array("status"=>false,"msg" =>"Error de datos.");
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function delCart(){
            if($_POST){
                $id = $_POST['id'];
                $topic = intval($_POST['topic']);
                $total=0;
                $qtyCart=0;
                $arrCart = $_SESSION['arrCart'];

                if($topic == 1){
                    $height = floatval($_POST['height']);
                    $width = floatval($_POST['width']);
                    $margin = intval($_POST['margin']);
                    $style = $_POST['style'];
                    $type = intval($_POST['type']);
                    $borderColor = $_POST['bordercolor'];
                    $marginColor = $_POST['margincolor'];
                    $reference = $_POST['reference'];
                    $photo = $_POST['photo'];
                }
                for ($i=0; $i < count($arrCart) ; $i++) { 
                    if($topic == 1){
                        if($id == $arrCart[$i]['id'] && $height == $arrCart[$i]['height']
                        && $width == $arrCart[$i]['width'] && $margin == $arrCart[$i]['margin'] && $style == $arrCart[$i]['style']
                        && $type == $arrCart[$i]['idType'] && $borderColor == $arrCart[$i]['colorborder'] && $marginColor == $arrCart[$i]['colormargin']
                        && $photo == $arrCart[$i]['photo'] && $reference == $arrCart[$i]['reference']){
                            if($photo!="" && $photo !="retablo.png"){
                                deleteFile($photo);
                            }
                            unset($arrCart[$i]);
                            break;
                        }
                    }else if($topic == 2){
                        if($id == $arrCart[$i]['id']){
                            unset($arrCart[$i]);
                            break;
                        }
                    }
                }
                
                sort($arrCart);
                $_SESSION['arrCart'] = $arrCart;
                foreach ($_SESSION['arrCart'] as $quantity) {
                    $qtyCart += $quantity['qty'];
                }
                $shipping = $this->calcTotalCart($_SESSION['arrCart']);
                $subtotal = $shipping['subtotal'];
                $total = $shipping['total'];
                $arrResponse = array("status"=>true,"total" =>formatNum($total),"subtotal"=>formatNum($subtotal),"qty"=>$qtyCart);
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function currentCart(){
            if(isset($_SESSION['arrCart']) && !empty($_SESSION['arrCart'])){
                $arrProducts = $_SESSION['arrCart'];
                $html="";
                for ($i=0; $i < count($arrProducts) ; $i++) { 
                    if($arrProducts[$i]['topic'] == 1){
                        $photo = $arrProducts[$i]['photo'] != "" ? media()."/images/uploads/".$arrProducts[$i]['photo'] : $arrProducts[$i]['img'];
                        $html.= '
                        <li class="cartlist--item" data-id="'.$arrProducts[$i]['id'].'" data-topic ="'.$arrProducts[$i]['topic'].'" data-h="'.$arrProducts[$i]['height'].'"
                        data-w="'.$arrProducts[$i]['width'].'" data-m="'.$arrProducts[$i]['margin'].'" data-s="'.$arrProducts[$i]['style'].'" 
                        data-mc="'.$arrProducts[$i]['colormargin'].'" data-bc="'.$arrProducts[$i]['colorborder'].'" data-t="'.$arrProducts[$i]['idType'].'" data-f="'.$arrProducts[$i]['photo'].'"
                        data-r="'.$arrProducts[$i]['reference'].'">
                            <a href="'.$arrProducts[$i]['url'].'">
                                <img src="'.$photo.'" alt="'.$arrProducts[$i]['name'].'">
                            </a>
                            <div class="item--info">
                                <a href="'.$arrProducts[$i]['url'].'">'.$arrProducts[$i]['name'].'</a>
                                <div class="item--qty">
                                    <span>
                                        <span class="fw-bold">'.$arrProducts[$i]['qty'].' x</span>
                                        <span class="item--price">'.formatNum($arrProducts[$i]['price'],false).'</span>
                                    </span>
                                </div>
                            </div>
                            <span class="delItem"><i class="fas fa-times"></i></span>
                        </li>
                        ';
                    }else if($arrProducts[$i]['topic'] == 2){
                        $html.= '
                        <li class="cartlist--item" data-id="'.$arrProducts[$i]['id'].'" data-topic ="'.$arrProducts[$i]['topic'].'">
                            <a href="'.$arrProducts[$i]['url'].'">
                                <img src="'.$arrProducts[$i]['image'].'" alt="'.$arrProducts[$i]['name'].'">
                            </a>
                            <div class="item--info">
                                <a href="'.$arrProducts[$i]['url'].'">'.$arrProducts[$i]['name'].'</a>
                                <div class="item--qty">
                                    <span>
                                        <span class="fw-bold">'.$arrProducts[$i]['qty'].' x</span>
                                        <span class="item--price">'.formatNum($arrProducts[$i]['price'],false).'</span>
                                    </span>
                                </div>
                            </div>
                            <span class="delItem"><i class="fas fa-times"></i></span>
                        </li>
                        ';
                    }
                }
                $total =0;
                $qty = 0;
                foreach ($arrProducts as $product) {
                    $total+=$product['qty']*$product['price'];
                    $qty+=$product['qty'];
                }
                $status=false;
                if(isset($_SESSION['login']) && !empty($_SESSION['arrCart'])){
                    $status=true;
                }
                $arrResponse = array("status"=>$status,"items"=>$html,"total"=>formatNum($total),"qty"=>$qty);
            }else{
                $arrResponse = array("items"=>"","total"=>formatNum(0),"qty"=>0);
            }
            echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            die();
        }
        public function calcTotalCart($arrProducts,$code=null,$city=null){
            $arrShipping = $this->selectShippingMode();
            $total=0;
            $subtotal=0;
            $shipping =0;
            $cupon = 0;
            for ($i=0; $i < count($arrProducts) ; $i++) { 
                $subtotal += $arrProducts[$i]['price']*$arrProducts[$i]['qty']; 
            }
            if($arrShipping['id'] != 3){
                $shipping = $arrShipping['value'];
            }else if($city > 0){
                $cityVal = $this->selectShippingCity($city)['value'];
                $shipping = $cityVal;
                $_SESSION['shippingcity'] = $shipping;
            }
            $total = $subtotal + $shipping;
            if($code != ""){
                $arrCupon = $this->selectCouponCode($code);
                $cupon = $subtotal-($subtotal*($arrCupon['discount']/100));
                $total =$cupon + $shipping;
            }
            $arrData = array("subtotal"=>$subtotal,"total"=>$total,"cupon"=>$cupon);
            return $arrData;
        }
        public function calculateShippingCity(){
            if($_POST){
                $arrProducts = $_SESSION['arrCart'];
                $city = intval($_POST['city']);
                $code = strClean($_POST['cupon']);
                $arrData = $this->calcTotalCart($arrProducts,$code,$city);
                $arrData['subtotal'] = formatNum($arrData['subtotal']);
                $arrData['total'] = formatNum($arrData['total']);
                $arrData['cupon'] = formatNum($arrData['cupon']); 
                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function setCouponCode(){
            if($_POST){
                if(empty($_POST['cupon'])){
                    $arrResponse = array("status"=>false,"msg"=>"Error de datos"); 
                }else{
                    $strCoupon = strClean(strtoupper($_POST['cupon']));
                    $request = $this->selectCouponCode($strCoupon);
                    if(!empty($request)){
                        $arrResponse = array("status"=>true,"data"=>$request); 
                    }else{
                        $arrResponse = array("status"=>false,"msg"=>"El cupón no existe o está inactivo."); 
                    }
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
    }
?>