<?php
    
    require_once("Models/ProductTrait.php");
    require_once("Models/CategoryTrait.php");
    require_once("Models/CustomerTrait.php");
    require_once("Models/LoginModel.php");
    class Tienda extends Controllers{
        use ProductTrait, CategoryTrait, CustomerTrait;
        private $login;
        public function __construct(){
            session_start();
            parent::__construct();
            sessionCookie();
            $this->login = new LoginModel();
        }

        /******************************Views************************************/
        public function tienda(){
            $pageNow = isset($_GET['p']) ? intval(strClean($_GET['p'])) : 1;
            $sort = isset($_GET['s']) ? intval(strClean($_GET['s'])) : 1;
            $company=getCompanyInfo();
            $data['page_tag'] = $company['name'];
            $data['page_title'] = "Tienda | ".$company['name'];
            $data['page_name'] = "tienda";
            $data['categories'] = $this->getCategoriesT();
            $productsPage =  $this->getProductsPageT($pageNow,$sort);
            if($pageNow <= $productsPage['paginas']){
                $data['products'] = $productsPage;
                $data['app'] = "functions_shop.js";
                $this->views->getView($this,"tienda",$data);
            }else{
                header("location: ".base_url()."/error");
                die();
            }
        }
        public function categoria($params){
            $pageNow = isset($_GET['p']) ? intval(strClean($_GET['p'])) : 1;
            $sort = isset($_GET['s']) ? intval(strClean($_GET['s'])) : 1;
            $params = strClean($params);
            $title = ucwords(str_replace("-"," ",$params));
            $company=getCompanyInfo();
            $data['page_tag'] = $company['name'];
            $data['page_name'] = "categoria";
            $data['categories'] = $this->getCategoriesT();
            $data['ruta'] = $params;
            $productsPage =  $this->getProductsCategoryT($params,$pageNow,$sort);
            if($pageNow <= $productsPage['paginas']){
                $data['products'] = $productsPage;
                $data['page_title'] = $title." | ".$company['name'];
                $data['app'] = "functions_shop_category.js";
                $this->views->getView($this,"categoria",$data);
            }else{
                header("location: ".base_url()."/error");
                die();
            }

        }
        public function buscar(){
            $pageNow = isset($_GET['p']) ? intval(strClean($_GET['p'])) : 1;
            $sort = isset($_GET['s']) ? intval(strClean($_GET['s'])) : 1;
            $search = isset($_GET['b']) ? strClean($_GET['b']) : "";
            $company=getCompanyInfo();
            $data['page_tag'] = $company['name'];
            $data['page_title'] = "Tienda | ".$company['name'];
            $data['page_name'] = "tienda";
            $data['categories'] = $this->getCategoriesT();
            $productsPage =  $this->getProductsSearchT($pageNow,$sort,$search);
            if($pageNow <= $productsPage['paginas']){
                $data['products'] = $productsPage;
                $data['app'] = "functions_shop_search.js";
                $this->views->getView($this,"buscar",$data);
            }else{
                header("location: ".base_url()."/error");
                die();
            }
        }
        public function producto($params){
            if($params!= ""){
                $params = strClean($params);
                $data['product'] = $this->getProductPageT($params);
                if(!empty($data['product'])){
                    $company=getCompanyInfo();
                    $data['page_tag'] = $company['name'];
                    $data['page_name'] = "product";
                    $data['products'] = $this->getProductsRelT($data['product']['categoryid'],4);
                    $data['page_title'] =$data['product']['name']." | ".$company['name'];
                    $data['app'] = "functions_product.js";
                    $this->views->getView($this,"producto",$data); 
                }else{
                    header("location: ".base_url()."/error");
                    die();
                }
               
            }else{
                header("location: ".base_url()."/error");
                die();
            }
        }
        /******************************Customer methods************************************/
        public function validCustomer(){
            if($_POST){
				if(empty($_POST['txtSignName']) || empty($_POST['txtSignEmail']) || empty($_POST['txtSignPassword'])){
                    $arrResponse=array("status" => false, "msg" => "Error de datos");
                }else{
                    $strName = ucwords(strClean($_POST['txtSignName']));
                    $strEmail = strtolower(strClean($_POST['txtSignEmail']));
                    $company = getCompanyInfo();
                    $code = code(); 
                    $dataUsuario = array('nombreUsuario'=> $strName, 
                                        'email_remitente' => $company['email'], 
                                        'email_usuario'=>$strEmail, 
                                        'company' =>$company,
                                        'asunto' =>'Código de verificación - '.$company['name'],
                                        'codigo' => $code);
                    $_SESSION['code'] = $code;
                    $sendEmail = sendEmail($dataUsuario,'email_validData');
                    if($sendEmail){
                        $arrResponse = array("status"=>true,"msg"=>"Se ha enviado un código a su correo electrónico para validar sus datos.");
                        
                    }else{
                        $arrResponse = array("status"=>false,"msg"=>"Error, intenta de nuevo.");
                    }
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);

			}
			die();
        }
		public function setCustomer(){
			if($_POST){
				if(empty($_POST['txtSignName']) || empty($_POST['txtSignEmail']) || empty($_POST['txtSignPassword']) || empty($_POST['txtCode'])){
                    $arrResponse=array("status" => false, "msg" => "Error de datos");
                }else{
                    if($_POST['txtCode'] == $_SESSION['code']){
                        unset($_SESSION['code']);
                        $strName = ucwords(strClean($_POST['txtSignName']));
                        $strEmail = strtolower(strClean($_POST['txtSignEmail']));
                        $strPassword = hash("SHA256",$_POST['txtSignPassword']);
                        $strPicture = "user.jpg";
                        $rolid = 2;

                        $request = $this->setCustomerT($strName,$strPicture,$strEmail,$strPassword,$rolid);
                        
                        if($request > 0){
                            $_SESSION['idUser'] = $request;
                            $_SESSION['login'] = true;
                            
                            $arrData = $this->login->sessionLogin($_SESSION['idUser']);
                            sessionUser($_SESSION['idUser']);
    
                            $arrResponse = array("status" => true,"msg"=>"Se ha registrado con éxito.");
                        }else if($request =="exist"){
                            $arrResponse = array("status" => false,"msg"=>"El usuario ya existe, por favor inicie sesión.");
                        }else{
                            $arrResponse = array("status" => false,"msg"=>"No se pueden almacenar los datos, inténtelo más tarde.");
    
                        }
                    }else{
                        $arrResponse = array("status" => false,"msg"=>"Código incorrecto, inténtelo de nuevo.");
                    }

                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);

			}
			die();
		}
        public function setSuscriber(){
            if($_POST){
                if(empty($_POST['txtEmailSuscribe'])){
                    $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                }else{
                    $strEmail = strClean(strtolower($_POST['txtEmailSuscribe']));
                    $request = $this->setSuscriberT($strEmail);
                    $company = getCompanyInfo();
                    if($request>0){
                        $request = $this->statusCouponSuscriberT();
                        $dataEmail = array('email_remitente' => $company['email'], 
                                                'email_usuario'=>$strEmail,
                                                'asunto' =>'Te has suscrito a '.$company['name'],
                                                "code"=>$request['code'],
                                                'company'=>$company,
                                                "discount"=>$request['discount']);
                        sendEmail($dataEmail,'email_suscriber');
                        $arrResponse = array("status"=>true,"msg"=>"Suscrito");
                    }else if($request=="exists"){
                        $arrResponse = array("status"=>false,"msg"=>"Ya se ha suscrito antes.");
                    }else{
                        $arrResponse = array("status"=>false,"msg"=>"Error, intenta de nuevo.");
                    }
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function statusCouponSuscriber(){
            $request = $this->statusCouponSuscriberT();
            if(!empty($request)){
                $arrResponse = array("status"=>true,"discount"=>$request['discount']);
            }else{
                $arrResponse = array("status"=>false,"msg"=>"El cupón no existe o está inactivo");
            }
            echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            die();
        }
        /******************************General shop methods************************************/
        public function getProduct(){
            if($_POST){
                $idProduct = openssl_decrypt($_POST['idProduct'],METHOD,KEY);
                if(is_numeric($idProduct)){
                    $request = $this->getProductT($idProduct);
                    $request['idproduct'] = $_POST['idProduct']; 
                    $request['priceDiscount']=$request['price']-($request['price']*($request['discount']*0.01));
                    $request['price'] = $request['price'];
                    
                    $script = getFile("Template/Modal/modalQuickView",$request);
                    $data = array(
                        "name"=>$request['name'],
                        "url"=>base_url()."/tienda/producto/".$request['route'],
                        "img"=>$request['image'][0]['url'],
                        "stock"=>$request['stock']
                    );
                    $arrResponse= array("status"=>true,"script"=>$script,"data"=>$data);
                }else{
                    $arrResponse= array("status"=>false);
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
            
        }
    }
?>