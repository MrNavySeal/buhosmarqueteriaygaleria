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
            //unset($_SESSION['arrCart']);exit;
            if($_POST){
                $id = $_POST['id'];
                $code = strClean($_POST['cupon']);
                $situ = strtolower(strClean($_POST['situ']));
                $total =0;
                $totalPrice = 0;
                $subtotal = 0;
                $qty = intval($_POST['qty']);
                $city = intval($_POST['city']);
                if($qty > 0){
                    $arrProducts = $_SESSION['arrCart'];
                    $product = array_values(array_filter($arrProducts,function($e) use($id) {return $e['index'] == $id;}))[0];
                    $index = $product['index'];
                    if($product['topic'] == 2){
                        $idProduct = intval(openssl_decrypt($product['id'],METHOD,KEY));
                        $variant = $product['producttype']== 1 ? $product['variant']['name'] : "";
                        $request = $this->getProductT($idProduct,$variant);
                        if($request['is_stock'] && $qty >= $request['stock'] ){
                            $qty = $request['stock'];
                        }
                    }
                    $arrProducts[$index]['qty'] = $qty;
                    $totalPrice =$qty*$product['price'];

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
            if($_POST){
                $id = $_POST['id'];
                $code = isset($_POST['cupon']) ? strClean($_POST['cupon']) : "";
                $situ = isset($_POST['situ'])  ? strtolower(strClean($_POST['situ'])):"";
                $city = isset($_POST['city']) ? intval($_POST['city']) : 0;
                $total=0;
                $qtyCart=0;
                $arrCart = $_SESSION['arrCart'];
                $index = array_values(array_filter($arrCart,function($e) use($id) {return $e['index'] == $id;}))[0]['index'];
                unset($arrCart[$index]);
                $arrCart = array_values($arrCart);
                for ($i=0; $i < count($arrCart) ; $i++) { 
                    $qtyCart += $arrCart[$i]['qty'];
                    $arrCart[$i]['index'] = $i;
                }
                $_SESSION['arrCart'] = $arrCart;
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
                    $arrProducts[$i]['index'] = $i;
                    $arrProducts[$i]['subtotal'] = $arrProducts[$i]['price']*$arrProducts[$i]['qty'];
                    $arrProducts[$i]['subtotal_format'] = formatNum($arrProducts[$i]['subtotal'],false);
                    $arrProducts[$i]['price_format'] = formatNum($arrProducts[$i]['price'],false);
                    if($arrProducts[$i]['topic'] == 1){
                        $photo = $arrProducts[$i]['img'] != "" ? $arrProducts[$i]['img'] : media()."/images/uploads/".$arrProducts[$i]['cat_img'];
                        $url = base_url()."/enmarcar/personalizar/".$arrProducts[$i]['route'];
                        $arrProducts[$i]['url'] = $url;
                        $html.= '
                        <li class="cartlist--item" data-id="'.$i.'">
                            <a href="'.$url.'">
                                <img src="'.$photo.'" alt="'.$arrProducts[$i]['name'].'">
                            </a>
                            <div class="item--info">
                                <a href="'.$url.'">'.$arrProducts[$i]['name'].'</a>
                                <div class="item--qty">
                                    <span>
                                        <span class="fw-bold">'.$arrProducts[$i]['qty'].' x</span>
                                        <span class="item--price">'.formatNum($arrProducts[$i]['price'],false).'</span>
                                    </span>
                                </div>
                                <span class="text-secondary fw-bold">'.formatNum($arrProducts[$i]['subtotal'],false).'</span>
                            </div>
                            <span class="delItem" ><i class="fas fa-times"></i></span>
                        </li>
                        ';
                    }else if($arrProducts[$i]['topic'] == 2){
                        if($arrProducts[$i]['producttype'] != 1){
                            $html.= '
                            <li class="cartlist--item" data-id="'.$i.'">
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
                                    <span class="text-secondary fw-bold">'.formatNum($arrProducts[$i]['subtotal'],false).'</span>
                                </div>
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
                            <li class="cartlist--item" data-id="'.$i.'" 
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
                                    <span class="text-secondary fw-bold">'.formatNum($arrProducts[$i]['subtotal'],false).'</span>
                                </div>
                                <span class="delItem"><i class="fas fa-times"></i></span>
                            </li>
                            ';
                        }
                    }
                }
                $_SESSION['arrCart'] = $arrProducts;
                $subtotal = 0;
                $total =0;
                $qty = 0;
                foreach ($arrProducts as $pro) {
                    $total+=$pro['qty']*$pro['price'];
                    $subtotal+=$pro['qty']*$pro['price'];
                    $qty+=$pro['qty'];
                }
                $status=false;
                if(isset($_SESSION['login']) && !empty($_SESSION['arrCart'])){
                    $status=true;
                }
                $arrResponse = array("status"=>$status,"items"=>$html,"total"=>formatNum($total),"subtotal"=>formatNum($subtotal),"qty"=>$qty,"products"=>$arrProducts);
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
    }
?>