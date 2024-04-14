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
            if($_POST){ 
                $id = intval(openssl_decrypt($_POST['idProduct'],METHOD,KEY));
                $qty = intval($_POST['txtQty']);
                $topic = intval($_POST['topic']);
                $productType = intval($_POST['type']);
                $variant = strClean($_POST['variant']);
                $qtyCart = 0;
                $arrCart = array();
                $valiQty =true;
                $reference = "";
                if(is_numeric($id)){
                    $request = $this->getProductT($id,$variant);
                    $price = $request['price'];
                    $variant = $productType == 1 ? $request['combination'] : array();
                    $props = $productType == 1 ? $request['variants'] : array();
                    $id = openssl_encrypt($id,METHOD,KEY);

                    $data = array(
                        "reference"=>$request['reference'],
                        "name"=>$request['name'],
                        "image"=>$request['image'][0]['url'],
                        "route"=>base_url()."/tienda/producto/".$request['route']
                    );
                    if(!empty($request)){
                        $arrProduct = array(
                            "topic"=>2,
                            "producttype" => $request['product_type'],
                            "id"=>$id,
                            "name" => $request['name'],
                            "reference" => $request['reference'],
                            "qty"=>$qty,
                            "image"=>$request['image'][0]['url'],
                            "url"=>base_url()."/tienda/producto/".$request['route'],
                            "price" =>$price,
                            "stock"=>$request['stock'],
                            "is_stock"=>$request['is_stock'],
                            "variant"=>$variant,
                            "props"=>$props
                        );
                        //dep($arrProduct);exit;
                        if(isset($_SESSION['arrCart'])){
                            $arrCart = $_SESSION['arrCart'];
                            $currentQty = 0;
                            $flag = true;
                            for ($i=0; $i < count($arrCart) ; $i++) { 
                                if($arrCart[$i]['topic'] == 2){
                                    if($arrCart[$i]['producttype'] == 1){
                                        if($arrCart[$i]['id'] == $arrProduct['id']
                                        && $arrCart[$i]['variant']['name'] == $arrProduct['variant']['name']){
                                            $currentQty = $arrCart[$i]['qty'];
                                            $arrCart[$i]['qty']+= $qty;
                                            if($arrCart[$i]['is_stock'] && $arrCart[$i]['qty'] > $arrProduct['stock']){
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
                                    }else{
                                        if($arrCart[$i]['id'] == $arrProduct['id']){
                                            $currentQty = $arrCart[$i]['qty'];
                                            $arrCart[$i]['qty']+= $qty;
                                            if($arrCart[$i]['is_stock'] && $arrCart[$i]['qty'] > $arrProduct['stock']){
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
                            }
                            if($flag){
                                if(!empty($request) && $request['is_stock']  && $qty > $request['stock'] && $productType !=1){
                                    $arrResponse = array("status"=>false,"msg"=>"No hay suficientes unidades","data"=>$data);
                                    $_SESSION['arrCart'] = $arrCart;
                                }else if(!empty($request['variant']) && $request['is_stock']  && $qty > $variant['stock'] && $productType == 1){
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
                            if(!empty($request) && $request['is_stock'] && $qty > $request['stock'] && $productType != 1){
                                $arrResponse = array("status"=>false,"msg"=>"No hay suficientes unidades","data"=>$data);
                                $_SESSION['arrCart'] = $arrCart;
                            }else if(!empty($request['variant']) && $request['is_stock'] && $qty > $variant['stock'] && $productType == 1){
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
                $situ = strtolower(strClean($_POST['situ']));
                
                $variant = $_POST['variant'] != null ? $_POST['variant'] : null;
                if($topic == 1){
                    $height = floatval($_POST['height']);
                    $width = floatval($_POST['width']);
                    $margin = intval($_POST['margin']);
                    $style = strClean($_POST['style']);
                    $colorMargin = strClean($_POST['colormargin']);
                    $colorBorder = strClean($_POST['colorborder']);
                    $idType = intval($_POST['idType']);
                    $reference = strClean($_POST['reference']);
                    $material = strClean($_POST['material']);
                    $glass = strClean($_POST['glass']);
                    $frameColor = $_POST['framecolor'];
                }
                $total =0;
                $totalPrice = 0;
                $subtotal = 0;
                $qty = intval($_POST['qty']);
                $city = intval($_POST['city']);
                if($qty > 0){
                    $arrProducts = $_SESSION['arrCart'];
                    
                    for ($i=0; $i < count($arrProducts) ; $i++) { 
                        
                        if($arrProducts[$i]['topic'] == 1 && $topic == 1){
                            if($arrProducts[$i]['style'] == $style && $arrProducts[$i]['height'] == $height &&
                            $arrProducts[$i]['width'] == $width && $arrProducts[$i]['margin'] == $margin && $glass == $arrProducts[$i]['glass'] 
                            && $frameColor == $arrProducts[$i]['colorframe'] && $material == $arrProducts[$i]['material'] &&
                            $arrProducts[$i]['colormargin'] == $colorMargin && $arrProducts[$i]['colorborder'] == $colorBorder && 
                            $arrProducts[$i]['idType'] == $idType && $arrProducts[$i]['reference'] == $reference){
                                $arrProducts[$i]['qty'] = $qty;
                                $totalPrice =$arrProducts[$i]['qty']*$arrProducts[$i]['price'];
                                break;
                            }
                        }else if($arrProducts[$i]['topic'] == 2 && $topic == 2){
                            if($arrProducts[$i]['id'] == $id && $arrProducts[$i]['producttype'] == 2 
                            && $arrProducts[$i]['variant']['id_product_variant'] == $variant){
                                $idProduct = intval(openssl_decrypt($id,METHOD,KEY));
                                $id_variant = intval(openssl_decrypt($variant,METHOD,KEY));
                                
                                $stock = $this->getProductT($idProduct,$id_variant)['variant']['stock'];
                                if($qty >= $stock ){
                                    $qty = $stock;
                                }
                                $arrProducts[$i]['qty'] = $qty;
                                
                                $totalPrice =$arrProducts[$i]['qty']*$arrProducts[$i]['variant']['price'];
                                break;
                            }else if($arrProducts[$i]['id'] == $id && $arrProducts[$i]['producttype'] == 1 ){
                                $idProduct = intval(openssl_decrypt($id,METHOD,KEY));
                                $stock = $this->getProductT($idProduct,$variant)['stock'];
                                if($qty >= $stock ){
                                    $qty = $stock;
                                }
                                
                                $arrProducts[$i]['qty'] = $qty;
                                $totalPrice =$arrProducts[$i]['qty']*$arrProducts[$i]['price'];
                                break;
                            }
                            //dep($arrProducts[$i]);exit;
                        }
                    }
                    $_SESSION['arrCart'] = $arrProducts;
                    $shipping = $this->calcTotalCart($_SESSION['arrCart'],$code,$city,$situ);
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
            //dep($_POST);exit;
            if($_POST){
                $id = $_POST['id'];
                $topic = intval($_POST['topic']);
                $code = isset($_POST['cupon']) ? strClean($_POST['cupon']) : "";
                $situ = isset($_POST['situ'])  ? strtolower(strClean($_POST['situ'])):"";
                $city = isset($_POST['city']) ? intval($_POST['city']) : 0;
                $total=0;
                $qtyCart=0;
                $arrCart = $_SESSION['arrCart'];
                $variant = $_POST['variant'] != null ? $_POST['variant'] : null;
                if($topic == 1){
                    $height = floatval($_POST['height']);
                    $width = floatval($_POST['width']);
                    $margin = intval($_POST['margin']);
                    $style = $_POST['style'];
                    $type = intval($_POST['type']);
                    $borderColor = $_POST['bordercolor'];
                    $marginColor = $_POST['margincolor'];
                    $frameColor = $_POST['framecolor'];
                    $reference = $_POST['reference'];
                    $photo = $_POST['photo'];
                    $material = strClean($_POST['material']);
                    $glass = strClean($_POST['glass']);
                }
                for ($i=0; $i < count($arrCart) ; $i++) { 
                    if($topic == 1){
                        if($id == $arrCart[$i]['id'] && $height == $arrCart[$i]['height'] && $glass == $arrCart[$i]['glass'] 
                        && $frameColor == $arrCart[$i]['colorframe'] && $material == $arrCart[$i]['material']
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
                            if($arrCart[$i]['producttype'] == 1){
                                if($arrCart[$i]['variant']['name'] == $variant){
                                    unset($arrCart[$i]);
                                    break;
                                }
                            }else if($arrCart[$i]['producttype'] == 0){
                                unset($arrCart[$i]);
                                break;
                            }
                        }
                    }
                }
                
                sort($arrCart);
                $_SESSION['arrCart'] = $arrCart;
                foreach ($_SESSION['arrCart'] as $quantity) {
                    $qtyCart += $quantity['qty'];
                }
                $shipping = $this->calcTotalCart($_SESSION['arrCart'],$code,$city,$situ);
                $cupon = $shipping['cupon'];
                $subtotal = $shipping['subtotal'];
                $total = $shipping['total'];
                $arrResponse = array(
                    "status"=>true,
                    "total" =>formatNum($total),
                    "subtotal"=>formatNum($subtotal),
                    "qty"=>$qtyCart,
                    "cupon"=>formatNum($cupon)
                );
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
                        data-r="'.$arrProducts[$i]['reference'].'" data-fc="'.$arrProducts[$i]['colorframe'].'" data-glass="'.$arrProducts[$i]['glass'].'"
                        data-material="'.$arrProducts[$i]['material'].'">
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
                        if($arrProducts[$i]['producttype'] != 1){
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
                                <span class="text-secondary fw-bold">'.formatNum($arrProducts[$i]['price']*$arrProducts[$i]['qty'],false).'</span>
                                <span class="delItem"><i class="fas fa-times"></i></span>
                            </li>
                            ';
                        }else{
                            $arrVariant = explode("-",$arrProducts[$i]['variant']['name']); 
                            $props = $arrProducts[$i]['props'];
                            $propsTotal = count($props);
                            $htmlComb="";
                            
                            for ($j=0; $j < $propsTotal; $j++) { 
                                $options = $props[$j]['options'];
                                $optionsTotal = count($options);
                                for ($k=0; $k < $optionsTotal ; $k++) { 
                                    if($options[$k]== $arrVariant[$j]){
                                        $htmlComb.='<p class="m-0" >'.$props[$j]['name'].': '.$arrVariant[$j].'</p>';
                                        break;
                                    }
                                }
                            }
                            $html.= '
                            <li class="cartlist--item" data-id="'.$arrProducts[$i]['id'].'" 
                            data-topic ="'.$arrProducts[$i]['topic'].'" data-variant="'.$arrProducts[$i]['variant']['name'].'">
                                <a href="'.$arrProducts[$i]['url'].'">
                                    <img src="'.$arrProducts[$i]['image'].'" alt="'.$arrProducts[$i]['name'].'">
                                </a>
                                <div class="item--info">
                                    <a href="'.$arrProducts[$i]['url'].'">'.$arrProducts[$i]['reference']." ".$arrProducts[$i]['name'].'</a>
                                    '.$htmlComb.'
                                    <div class="item--qty">
                                        <span>
                                            <span class="fw-bold">'.$arrProducts[$i]['qty'].' x</span>
                                            <span class="item--price">'.formatNum($arrProducts[$i]['price'],false).'</span>
                                        </span>
                                    </div>
                                    <span class="text-secondary fw-bold">'.formatNum($arrProducts[$i]['price']*$arrProducts[$i]['qty'],false).'</span>
                                </div>
                                <span class="delItem"><i class="fas fa-times"></i></span>
                            </li>
                            ';
                        }
                    }
                }
                $total =0;
                $qty = 0;
                foreach ($arrProducts as $pro) {
                    $total+=$pro['qty']*$pro['price'];
                    $qty+=$pro['qty'];
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
        public function calcTotalCart($arrProducts,$code=null,$city=null,$situ=null){
            $arrShipping = $this->selectShippingMode();
            $total=0;
            $subtotal=0;
            $shipping =0;
            $cupon = 0;
            for ($i=0; $i < count($arrProducts) ; $i++) { 
                if($arrProducts[$i]['topic'] == 2){
                    if($arrProducts[$i]['producttype'] == 2){
                        $subtotal+=$arrProducts[$i]['qty']*$arrProducts[$i]['variant']['price'];
                    }else{
                        $subtotal+=$arrProducts[$i]['qty']*$arrProducts[$i]['price'];
                    }
                }else{
                    $subtotal += $arrProducts[$i]['price']*$arrProducts[$i]['qty']; 
                }
            }
            if($arrShipping['id'] != 3){
                $shipping = $arrShipping['value'];
            }else if($city > 0){
                $cityVal = $this->selectShippingCity($city)['value'];
                $shipping = $cityVal;
                $_SESSION['shippingcity'] = $shipping;
            }
            $shipping = $situ == "true" ? 0 : $shipping;
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