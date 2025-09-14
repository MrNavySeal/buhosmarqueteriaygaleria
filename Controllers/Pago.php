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
        public function pago(){
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
        }
        public function confirmar(){
            dep($_GET);exit;
            $company=getCompanyInfo();
            $data['page_tag'] = $company['name'];
            $data['page_title'] ="Estado de pedido | ".$company['name'];
            $data['page_name'] = "Estado de pedido";
            $this->views->getView($this,"confirmar",$data);   
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
        public function updatePayment(){
            echo "hola";
        }
        public function setPayment(){
            try {
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
                $cupon = "";
                $situ = "false";
                $type ="mercadopago";
                $arrTotal = $this->calcTotalCart($_SESSION['arrCart']);
                $strAddress = strClean($_POST['strCheckAddress']);
                $arrAddress = explode(" ",$strAddress);
                $strAddress = $strAddress.", ".$strCity."/".$strState."/".$strCountry." ".$strPostal;
                
                MercadoPagoConfig::setAccessToken(getCredentials()['secret']);
                $client = new PaymentClient();
                $request_options = new RequestOptions();
                $token = token();
                $request_options->setCustomHeaders(["X-Idempotency-Key: $token"]);
                $createRequest = [
                    "transaction_amount" => $arrTotal['total'],
                    "description" => "Productos",
                    "payment_method_id" => "pse",
                    "callback_url" => "https://pruebas.buhosmarqueteriaygaleria.co/pago/confirmar",
                    "notification_url" => "https://pruebas.buhosmarqueteriaygaleria.co/pago/updatePayment",
                    "additional_info" => [
                        "ip_address" => getIp()
                    ],
                    "transaction_details" => [
                        "financial_institution" => $_POST['strCheckBank']
                    ],
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
                if($payment->status == "pending"){
                    $strStatus = "pendent";
                }else if($payment->status == "rejected"){
                    $strStatus = "canceled";
                }else{
                    $strStatus = "approved";
                }
                if(!$_SESSION['login']){
                    $strName = ucwords(strClean($_POST['txtSignName']));
                    $strEmail = strtolower(strClean($_POST['txtSignEmail']));
                    $strPassword = hash("SHA256",bin2hex(random_bytes(6)));
                    $strPicture = "user.jpg";
                    $rolid = 2;
                    
                    $request = $this->setCustomerT($strName,$strPicture,$strEmail,$strPassword,$rolid);
                    if(is_numeric($request) && $request > 0){
                        $_SESSION['idUser'] = $request;
                        $_SESSION['login'] = true;
                        $this->login->sessionLogin($_SESSION['idUser']);
                        sessionUser($_SESSION['idUser']);
                    }
                }
                $this->setOrder([
                    "name"=>$strFullName,
                    "email"=>$strEmail,
                    "phone"=>$strPhone,
                    "address"=>$strAddress,
                    "note"=>"",
                    "cupon"=>$cupon,
                    "situ"=>$situ,
                    "document"=>$strDocument,
                    "city"=>$strCity,
                    "transaction"=>$strTransaction,
                    "status"=>$strStatus
                ]);
                $arrData = array("status"=>true,"url"=>$externalUrl);
                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
            } catch (MercadoPago\Exceptions\MPApiException $e) {
                $arrData = array("status"=>false,"msg"=>"Algo sucedió, inténtelo de nuevo.");
                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                /* echo "API Error: " . $e->getMessage() . "\n";
                echo "Status Code: " . $e->getApiResponse()->getStatusCode() . "\n";
                echo "Response Body: " . json_encode($e->getApiResponse()->getContent()) . "\n"; */
            }
            die();
        }
        public function calcTotalCart($arrProducts,$code=null,$city=null,$situ=null){
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
                    $this->setCoupon($arrCupon['id'],$_SESSION['idUser'],$code);
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
        public function checkInfo(){
            dep($_POST);exit;
            if($_POST){
                if(empty($_POST['txtNameOrder']) || empty($_POST['txtLastNameOrder']) || empty($_POST['txtEmailOrder'])
                || empty($_POST['txtPhoneOrder']) || empty($_POST['txtAddressOrder']) || empty($_POST['listCountry']) ||
                empty($_POST['listState']) || empty($_POST['listCity']) || empty($_POST['txtDocument'])){
                    $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                }else{
                    $strName = strClean(ucwords($_POST['txtNameOrder']));
                    $strLastName = strClean(ucwords($_POST['txtLastNameOrder']));
                    $strEmail = strClean(strtolower($_POST['txtEmailOrder']));
                    $strPhone = strClean($_POST['txtPhoneOrder']);
                    $strAddress = strClean($_POST['txtAddressOrder']);
                    $strCountry = strClean($_POST['country']);
                    $strState = strClean($_POST['state']);
                    $strCity = strClean($_POST['city']);
                    $cupon = strtoupper(strClean($_POST['cupon']));
                    $strPostal = strClean($_POST['txtPostCodeOrder']);
                    $strNote = strClean($_POST['txtNote']);
                    $strDocument = strClean($_POST['txtDocument']);
                    $situ = strtolower(strClean($_POST['situ']));
                    $strAddress = $strAddress.", ".$strCity."/".$strState."/".$strCountry." ".$strPostal;
                    $strName = $strName." ".$strLastName;

                    $_SESSION['orderDataInfo'] = array(
                        "name"=>$strName,
                        "email"=>$strEmail,
                        "phone"=>$strPhone,
                        "address"=>$strAddress,
                        "note"=>$strNote,
                        "cupon"=>$cupon,
                        "situ"=>$situ,
                        "document"=>$strDocument,
                        "city"=>$strCity
                    );
                    $arrResponse = array("status"=>true,"msg"=>"Datos guardados");
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
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
                $idOrder = openssl_encrypt($request,METHOD,KEY);
                $idTransaction = openssl_encrypt($orderInfo['order']['idtransaction'],METHOD,KEY);
                $orderData = array("order"=>$idOrder,"transaction"=>$idTransaction);
                /* unset($_SESSION['arrCart']);
                unset($_SESSION['shippingcity']); */
            }
            return $orderData;
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