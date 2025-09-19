<?php
    
    require_once("Models/ProductTrait.php");
    require_once("Models/CustomerTrait.php");
    require_once("Models/LoginModel.php");
    use MercadoPago\Client\Common\RequestOptions;
    use MercadoPago\Client\Payment\PaymentClient;
    use MercadoPago\Client\PaymentMethod\PaymentMethodClient;
    use MercadoPago\MercadoPagoConfig;
    class Pago extends Controllers{
        use ProductTrait, CustomerTrait;
        private $login;
        public function __construct(){
            session_start();
            parent::__construct();
            sessionCookie();
            $this->login = new LoginModel();
        }

        /******************************Views************************************/
        /* public function pago(){
            if(isset($_SESSION['login']) && isset($_SESSION['arrCart']) && !empty($_SESSION['arrCart'])){
                $company=getCompanyInfo();
                $data['page_tag'] = $company['name'];
                $data['page_title'] ="Pago | ".$company['name'];
                $data['page_name'] = "pago";
                $data['credentials'] = getCredentials();
                $data['company'] = getCompanyInfo();
                $data['shipping'] = $this->selectShippingMode();
                $data['app'] = "functions_checkout.js";
                if(isset($_GET['situ'])){
                    $situ = strtolower(strClean($_GET['situ']));
                    if($situ != "true" && $situ != "false"){
                        header("location: ".base_url()."/pago");
                        die();
                    }else if($situ =="true"){
                        $data['shipping'] = array("id"=>4,"value"=>0);
                    }else if($situ =="false"){
                        if($data['shipping']['id'] == 3 && !isset($_SESSION['shippingcity'])){
                            header("location: ".base_url()."/carrito");
                            die();
                        }
                    }
                }else if($data['shipping']['id'] == 3 && !isset($_SESSION['shippingcity'])){
                    header("location: ".base_url()."/carrito");
                     die();
                }
                
                if(isset($_GET['cupon'])){
                    $cupon = strtoupper(strClean($_GET['cupon']));
                    $cuponData = $this->selectCouponCode($cupon);
                    if(!empty($cuponData)){
                        $data['cupon'] = $cuponData;
                        $data['cupon']['check'] = $this->checkCoupon($_SESSION['idUser'],$data['cupon']['id']);
                    }else{
                        header("location: ".base_url()."/pago");
                        die();
                    }
                }
                
                $this->views->getView($this,"pago",$data); 
            }else{
                header("location: ".base_url());
                die();
            }
        } */
        public function confirmar(){
            $paymentId = strClean($_GET['payment_id']);
            $request = $this->getOrder($paymentId);
            if(!empty($request) && $request['order']['status']!="approved"){
                try {
                    MercadoPagoConfig::setAccessToken(getCredentials()['secret']);
                    $client = new PaymentClient();
                    $token = token();  
                    $request_options = new RequestOptions();
                    $request_options->setCustomHeaders(["X-Idempotency-Key: $token"]);
                    $order = $client->get($paymentId);
                    $this->updateOrder($paymentId,$order->status,$request['order']['amount'],$request['order']['idorder']);
                    $company=getCompanyInfo();
                    if($order->status == "rejected"){ $this->delCoupon($request['order']['idorder']);}
                    $data['page_tag'] = $company['name'];
                    $data['data'] = $request;
                    $data['page_title'] ="Estado de pedido | ".$company['name'];
                    $data['page_name'] = "Estado de pedido";
                    $this->views->getView($this,"confirmar",$data);
                    
                } catch (MercadoPago\Exceptions\MPApiException $e) {
                    header("location: ".base_url()."/errors");
                }
            }else{
                header("location: ".base_url());
            }
            die();
        }
        public function error(){
            $company=getCompanyInfo();
            $data['page_tag'] = $company['name'];
            $data['page_title'] ="Error | ".$company['name'];
            $data['page_name'] = "Error";
            $this->views->getView($this,"error",$data); 
        }
        public function getPaymentMethods(){
            MercadoPagoConfig::setAccessToken(getCredentials()['secret']);
            $client = new PaymentMethodClient();
            $payment_methods = $client->list();
            echo json_encode($payment_methods,JSON_UNESCAPED_UNICODE);
        }
        public function notificacion(){
            $input = file_get_contents("php://input");
            $data = json_decode($input, true);
            $paymentId = strClean($data["data"]["id"]);
            $request = $this->getOrder($paymentId);
            if(!empty($request)){
                try {
                    MercadoPagoConfig::setAccessToken(getCredentials()['secret']);
                    $client = new PaymentClient();
                    $token = token();  
                    $request_options = new RequestOptions();
                    $request_options->setCustomHeaders(["X-Idempotency-Key: $token"]);
                    $order = $client->get($paymentId);
                    $this->updateOrder($paymentId,$order->status,$request['order']['amount'],$request['order']['idorder']);
                    if($order->status == "rejected"){ $this->delCoupon($request['order']['idorder']);}
                    echo "Pedido actualizado";
                } catch (MercadoPago\Exceptions\MPApiException $e) {
                    echo "API Error: " . $e->getMessage() . "\n";
                    echo "Status Code: " . $e->getApiResponse()->getStatusCode() . "\n";
                    echo "Response Body: " . json_encode($e->getApiResponse()->getContent()) . "\n";
                }
            }
        }
        public function setPayment(){
            if($_POST){
                $errors = validator()->validate([   
                    "strCheckName"=>"required|min:1|max:32;nombres",
                    "strCheckLastname"=>"required|min:1|max:32;apellidos",
                    "strCheckDocument"=>"required|min:7|max:10;documento",
                    "strCheckEmail"=>"required|email;correo",
                    "strCheckPhone"=>"required|numeric|min:10;teléfono",
                    "strCheckAddress"=>"required|min:18;dirección",
                    "listCountry"=>"required;país",
                    "listState"=>"required;departamento",
                    "listCity"=>"required;ciudad",
                    "strCheckPersonType"=>"required|string;tipo de persona",
                    "strCheckDocumentType"=>"required|string|min:2|max:3;tipo de documento",
                    "strCheckBank"=>"required|numeric;banco"
                    ])->getErrors();
                    if(empty($errors)){
                        try {
                            $idDevice = $_POST['device_id'];
                            $arrProducts = $_SESSION['arrCart'];
                            $strName = ucwords(strClean($_POST['strCheckName']));
                            $strLastname = ucwords(strClean($_POST['strCheckLastname']));
                            $strFullName = $strName." ".$strLastname;
                            $strDocument = strClean($_POST['strCheckDocument']);
                            $strEmail = strClean($_POST['strCheckEmail']);
                            $strPhone = strClean($_POST['strCheckPhone']);
                            $intCity = intval($_POST['listCity']);
                            $intCountry = intval($_POST['listCountry']);
                            $intState = intval($_POST['listState']);
                            $strCity = getCiudad($intCity)['name'];
                            $strState = getDepartamento($intState)['name'];
                            $strCountry = getPais($intCountry)['name'];
                            $strPostal = strClean($_POST['strCheckCode']);
                            $cupon = $_POST['cupon'] != "" ? strtoupper(strClean($_POST['cupon'])) : "";
                            $situ = "false";
                            $type ="mercadopago";
                            $arrTotal = $this->calcTotalCart($arrProducts,$cupon);
                            $strAddress = strClean($_POST['strCheckAddress']);
                            $arrAddress = explode(" ",$strAddress);
                            $strAddress = $strAddress.", ".$strCity."/".$strState."/".$strCountry." ".$strPostal;
                            $items = [];
                            foreach ($arrProducts as $pro) {
                                $id = openssl_decrypt($pro['id'],METHOD,KEY);
                                $category = $pro['category'];
                                $image = $pro['image'];
                                if($pro['topic'] == 1){
                                    $id = $pro['index'];
                                    $category = $pro['name'];
                                    $image = media()."/images/uploads/".$pro['cat_img'];
                                }
                                array_push($items,[
                                    "id"=>$pro['topic'] == 1 ? $pro['index'] : $id,
                                    "title"=>$pro['name'],
                                    "description"=>$pro['name'],
                                    "picture_url"=> $image,
                                    "category_id"=> $category,
                                    "quantity"=> $pro['qty'],
                                    "unit_price"=> $pro['price'],
                                    "type"=> $category,
                                    "warranty"=> false,
                                ]);
                            }
                            $arrOrder = $this->setOrder([
                                "name"=>$strFullName,
                                "email"=>$strEmail,
                                "phone"=>$strPhone,
                                "address"=>$strAddress,
                                "note"=>"",
                                "cupon"=>$cupon,
                                "situ"=>$situ,
                                "document"=>$strDocument,
                                "city"=>$strCity,
                                "transaction"=>"",
                                "status"=>"pendent",
                            ]);
                            $idOrder = $arrOrder['order'];
                            MercadoPagoConfig::setAccessToken(getCredentials()['secret']);
                            $client = new PaymentClient();
                            $request_options = new RequestOptions();
                            $token = token();
                            $request_options->setCustomHeaders(["X-Idempotency-Key: $token","X-meli-session-id: $idDevice"]);
                            $createRequest = [
                                "transaction_amount" => $arrTotal['total'],
                                "description" => "Productos",
                                "payment_method_id" => "pse",
                                "callback_url" => base_url()."/pago/confirmar",
                                "notification_url" => base_url()."/pago/notificacion",
                                "additional_info" => [
                                    "ip_address" => getIp(),
                                    "items"=>$items
                                ],
                                "external_reference"=>$idOrder,
                                "transaction_details" => [
                                    "financial_institution" => $_POST['strCheckBank']
                                ],
                                "statement_descriptor"=> "MercadoPago",
                                "payer" => [
                                    "email" => $strEmail,
                                    "entity_type" => $_POST['strCheckPersonType'],
                                    "first_name" => $strName,
                                    "last_name" => $strLastname,
                                    "identification" => [
                                        "type" => $_POST['strCheckDocumentType'],
                                        "number" => $_POST['strCheckDocument']
                                    ],
                                    "address" => [
                                        "zip_code" => $strPostal !="" ? $strPostal : 50000,
                                        "street_name" => $arrAddress[0],
                                        "street_number" => isset($arrAddress[1]) ? $arrAddress[1] : $arrAddress[1],
                                        "neighborhood" => isset($arrAddress[2]) ? $arrAddress[2] : $arrAddress[2],
                                        "city" => $strCity,
                                    ],
                                    "phone" => [
                                        "area_code" => "+57",
                                        "number" => $_POST['strCheckPhone']
                                    ],
                                ],
                            ];
                            $payment = $client->create($createRequest, $request_options);
                            $strTransaction = $payment->id;
                            $details = $payment->transaction_details;
                            $externalUrl = $details->external_resource_url;
                            $this->setTransaction($idOrder,$strTransaction);
                            if(!$_SESSION['login']){
                                $strPassword = hash("SHA256",bin2hex(random_bytes(6)));
                                $strPicture = "user.jpg";
                                $rolid = 2;
                                
                                $request = $this->setCheckoutCustomerT($strName,$strLastname,$strDocument,$strPicture,
                                $strEmail,$strPhone,$intCountry,$intState,$intCity,$strAddress,$strPassword,$rolid);
                                if(is_numeric($request) && $request > 0){
                                    $_SESSION['idUser'] = $request;
                                }else{
                                    $_SESSION['idUser'] = $request['id'];
                                }
                                $_SESSION['login'] = true;
                                $this->login->sessionLogin($_SESSION['idUser']);
                                sessionUser($_SESSION['idUser']);
                            }
                            $arrTotal = $this->calcTotalCart($arrProducts,$cupon,null,null,$request,true);
                            $arrData = array("status"=>true,"url"=>$externalUrl);
                            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                        } catch (MercadoPago\Exceptions\MPApiException $e) {
                            $arrData = array("status"=>false,"msg"=>"Algo sucedió, inténtelo de nuevo.");
                            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                            echo "API Error: " . $e->getMessage() . "\n";
                            echo "Status Code: " . $e->getApiResponse()->getStatusCode() . "\n";
                            echo "Response Body: " . json_encode($e->getApiResponse()->getContent()) . "\n";
                        }
                }else{
                    echo json_encode(["status"=>false,"msg"=>"Por favor, revise los campos obligatorios.","errors"=>$errors],JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }
        public function calcTotalCart($arrProducts,$code=null,$city=null,$situ=null,$idOrder=null,$setCupon=false){
            $arrShipping = $this->selectShippingMode();
            $total=0;
            $subtotal=0;
            $shipping =0;
            $cupon = 0;
            $status = true;
            $discount=0;
            $arrCupon = array();
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
            }
            $shipping = $situ == "true" ? 0 : $shipping;
            $total = $subtotal + $shipping;
            if($code != ""){
                $arrCupon = $this->selectCouponCode($code);
                $status = $this->checkCoupon($_SESSION['idUser'],$arrCupon['id']);
                if(!$status){
                    $discount=$subtotal*($arrCupon['discount']/100);
                    $cupon = $subtotal-$discount;
                    $total =$cupon + $shipping;
                    if($setCupon){
                        $this->setCoupon($arrCupon['id'],$_SESSION['idUser'],$code,$idOrder);
                    }
                }else{
                    $arrCupon = array();
                }
            }
            $arrData = array("total"=>$total,"discount"=>$discount,"cupon"=>$cupon,"arrcupon"=>$arrCupon,"subtotal"=>$subtotal,"status"=>$status);
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
        /******************************Checkout methods************************************/
        public function setOrder($arrData){
            $total = 0;
            $arrTotal = array();
            $idUser = $_SESSION['idUser'];
            $strName = $arrData['name'];
            $strDocument = $arrData['document'];
            $strEmail = $arrData['email'];
            $strPhone = $arrData['phone'];
            $strCity = $arrData['city'];
            $strAddress = $arrData['address'];
            $cupon = $arrData['cupon'];
            $strNote = $arrData['note'];
            $status = $arrData['status'];
            $idTransaction =$arrData['transaction'];
            $situ = $arrData['situ'];
            $type ="mercadopago";
            $envio = 0;
            $statusOrder ="confirmado";
            $arrProducts = $_SESSION['arrCart'];
            $arrTotal = $this->calcTotalCart($arrProducts,$cupon,null,$situ);
            $cupon = $arrTotal['discount'];
            $total = $arrTotal['total'];

            /* if($type==""){
                $status = "approved";
            } */

            $arrShipping = $this->selectShippingMode();
            if($arrShipping['id']<3){
                $envio = $arrShipping['value'];
            }else if($arrShipping['id']==3){
                $envio = $_SESSION['shippingcity'];
            }
            if($situ =="true"){
                $envio = 0;
            }
            $request = $this->insertOrder($idUser, $idTransaction,$strName,$strDocument,$strEmail,$strPhone,$strAddress,$strNote,$cupon,$envio,$total,$status,$type,$statusOrder);          
            if($request>0){
                $arrOrder = array(
                    "idorder"=>$request,
                    "iduser"=>$_SESSION['idUser'],
                    "products"=>$_SESSION['arrCart'],
                    "city"=>$strCity,
                    "name"=>$strName
                );
                $requestDetail = $this->insertOrderDetail($arrOrder);
                $orderInfo = $this->getOrder($request);
                $company = getCompanyInfo();
                $dataEmailOrden = array(
                    'asunto' => "Se ha generado un pedido",
                    'email_usuario' => $strEmail, 
                    'email_remitente'=>$company['email'],
                    'company'=>$company,
                    'email_copia' => $company['secondary_email'],
                    'order' => $orderInfo);

                try {sendEmail($dataEmailOrden,'email_order');} catch (Exception $e) {}
                $idOrder = $request;
                $idTransaction = $orderInfo['order']['idtransaction'];
                $orderData = array("order"=>$idOrder,"transaction"=>$idTransaction);
            }
            return $orderData;
        }
        public function setCouponCode(){
            if($_POST){
                if(empty($_POST['cupon'])){
                    $arrResponse = array("status"=>false,"msg"=>"Error de datos"); 
                }else{
                    $strCoupon = strClean(strtoupper($_POST['cupon']));
                    $request = $this->selectCouponCode($strCoupon);
                    if(!empty($request)){
                        if(!$this->checkCoupon($_SESSION['idUser'],$request['id'])){
                            $arrProducts = $_SESSION['arrCart'];
                            $data = $this->calcTotalCart($arrProducts,$strCoupon);
                            $data['subtotal'] = formatNum($data['subtotal']);
                            $data['total'] = formatNum($data['total']);
                            $arrResponse = array("status"=>true,"data"=>$data); 
                        }else{
                            $arrResponse = array("status"=>false,"msg"=>"El cupón ya fue usado."); 
                        }
                    }else{
                        $arrResponse = array("status"=>false,"msg"=>"El cupón no existe o está inactivo."); 
                    }
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function getCountries(){
            $request = $this->selectCountries();
            $html='
            <option value="0" selected>Seleccione</option>
            <option value="'.$request['id'].'">'.$request['name'].'</option>
            ';
            echo json_encode($html,JSON_UNESCAPED_UNICODE);
            die();
        }
        public function getSelectCountry($id){
            $request = $this->selectStates($id);
            $html='<option value="0" selected>Seleccione</option>';
            for ($i=0; $i < count($request) ; $i++) { 
                $html.='<option value="'.$request[$i]['id'].'">'.$request[$i]['name'].'</option>';
            }
            echo json_encode($html,JSON_UNESCAPED_UNICODE);
            die();
        }
        public function getSelectState($id){
            $request = $this->selectCities($id);
            $html='<option value="0" selected>Seleccione</option>';
            for ($i=0; $i < count($request) ; $i++) { 
                $html.='<option value="'.$request[$i]['id'].'">'.$request[$i]['name'].'</option>';
            }
            echo json_encode($html,JSON_UNESCAPED_UNICODE);
            die();
        }
    }
?>