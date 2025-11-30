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
            setView(BASE_URL."/tienda");
            $company=getCompanyInfo();
            $data['page_tag'] = $company['name'];
            $data['page_title'] = "Tienda | ".$company['name'];
            $data['page_name'] = "tienda";
            $data['banners'] = $this->getBanners();
            $data['categories'] = $this->getCategoriesT();
            $data['app'] = "functions_shop.js";
            $this->views->getView($this,"tienda",$data);
        }

        public function categoria($params){
            $params = strClean($params);
            setView(BASE_URL."/tienda/categoria/".str_replace(",","/",$params));
            $arrParams = explode(",",$params);
            $title = count($arrParams) > 1 ? ucwords(str_replace("-"," ",$arrParams[1])): ucwords(str_replace("-"," ",$arrParams[0]));
            $company=getCompanyInfo();
            $data['page_tag'] = $company['name'];
            $data['page_name'] = "categoria";
            $data['categories'] = $this->getCategoriesT();
            $data['category_route'] = $arrParams[0];
            $data['subcategory_route'] = count($arrParams) > 1 ? $arrParams[1] : "";
            $data['page_title'] = $title." | ".$company['name'];
            $data['app'] = "functions_shop.js";
            $this->views->getView($this,"categoria",$data);
        }

        public function buscar(){
            $search = isset($_GET['b']) ? strClean($_GET['b']) : "";
            $company=getCompanyInfo();
            $data['page_tag'] = $company['name'];
            $data['page_title'] = "Tienda | ".$company['name'];
            $data['page_name'] = "tienda";
            $data['categories'] = $this->getCategoriesT();
            $data['search'] = $search;
            $data['app'] = "functions_shop.js";
            $this->views->getView($this,"buscar",$data);
        }

        public function producto($params){
            if($params!= ""){
                $params = strClean($params);
                setView(BASE_URL."/tienda/producto/".$params);
                $data['product'] = $this->getProductPageT($params);
                if(!empty($data['product'])){
                    $company=getCompanyInfo();
                    $data['page_tag'] = $company['name'];
                    $data['page_name'] = "product";
                    $data['products'] = $this->getProductsRelT($data['product']['idproduct'],$data['product']['categoryid'],$data['product']['subcategoryid'],20);
                    $data['page_title'] =$data['product']['name']." | ".$company['name'];
                    $data['app'] = "functions_product.js";
                    $data['reviews'] = $this->getReviews($data['product']['idproduct']);
                    $data['review'] = $this->getRate($data['product']['idproduct']);
                    $data['modal'] = getFile("Template/Modal/modalReview",$data['product']['idproduct']);
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
        /******************************Review methods************************************/
        public function setReview(){
            //dep($_POST);exit;
            if($_POST){
                if(isset($_SESSION['login'])){
                    if(empty($_POST['intRate']) || empty($_POST['txtReview']) || empty($_POST['idProduct'])){
                        $arrResponse = array("status"=>false,"msg"=>"Por favor, califíque y escriba su reseña.");
                        echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                    }else{
                        $idUser = $_SESSION['idUser'];
                        $idProduct = intval(openssl_decrypt($_POST['idProduct'],METHOD,KEY));
                        $intRate = intval($_POST['intRate']);
                        $strReview = strClean($_POST['txtReview']);
                        $option=0;
                        $request = $this->setReviewT($idProduct,$idUser,$strReview,$intRate);
                        
                        if($request>0){
                            $arrResponse = array("status"=>true,"msg"=>"Su opinión fue enviada con éxito y está a la espera de que nuestro personal la publique.");
                        }else if($request=="exists"){
                            $arrResponse = array("status"=>false,"msg"=>"Ya ha compartido su opinión anteriormente.");
                        }else{
                            $arrResponse = array("status"=>false,"msg"=>"Error, intenta de nuevo.");
                        }
                    }
                }else{
                    $arrResponse = array("login"=>false,"msg"=>"Inicie sesión para compartir su reseña.");
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }

        public function getReviews($idProduct,$sort=null){
            $reviews="";
            if($sort != null){
                $reviews = $this->getReviewsSortT($idProduct,$sort);
            }else{
                $reviews = $this->getReviewsT($idProduct);
            }
            $rate = $this->getRate($idProduct);
            $html="";
            for ($i=0; $i < count($reviews); $i++) { 
                $image = media()."/images/uploads/".$reviews[$i]['image'];
                $name = $reviews[$i]['firstname'].' '.$reviews[$i]['lastname'];
                $rateComment ="";
                for ($j = 0; $j < 5; $j++) {
                    if($j >= intval($reviews[$i]['rate'])){
                        $rateComment.='<i class="far fa-star"></i>';
                    }else{
                        $rateComment.='<i class="fas fa-star"></i>';
                    }
                }

                $html.='
                <li class="comment-block">
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="comment-info d-flex justify-content-between">
                                <div class="d-flex justify-content-start">
                                    <div class="comment-img me-1">
                                        <img src="'.$image.'" alt="'.$name.'">
                                    </div>
                                    <div class="review-stars">
                                        <p class="m-0">'.$name.'</p>
                                        '.$rateComment.'
                                        
                                    </div>
                                </div>
                                <div class="product-rate text-end m-0">
                                    <p class="m-0 text-secondary">'.$reviews[$i]['date'].'</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-1">
                            <p class="m-0">'.$reviews[$i]['description'].'</p>
                        </div>
                    </div>
                </li>
                ';
            }
            return $html;
        }

        public function sortReviews(){
            if($_POST){
                $sort = intval($_POST['sort']);
                $id = intval(openssl_decrypt($_POST['id'],METHOD,KEY));
                $arrResponse = $this->getReviews($id,$sort);
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
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
                        $arrResponse = array("status"=>true,"msg"=>"Se ha enviado un código a tu correo electrónico para validar tus datos.");
                        
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
				if(empty($_POST['txtSignName']) || empty($_POST['txtSignEmail']) || 
                empty($_POST['txtSignPassword']) || empty($_POST['txtCode'])){
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
                        
                        if(is_numeric($request) && $request > 0){
                            $_SESSION['idUser'] = $request;
                            $_SESSION['login'] = true;
                            $this->login->sessionLogin($_SESSION['idUser']);
                            sessionUser($_SESSION['idUser']);
                            $company = getCompanyInfo();
                            $arrResponse = array("status" => true,"msg"=>"Se ha registrado con éxito.");
                            $data = array(
                                'nombreUsuario'=> $strName, 
                                'email_remitente' => $company['email'], 
                                'email_usuario'=>$strEmail, 
                                'company'=>$company,
                                'asunto' =>"Registro exitoso! Bienvenido a ".$company['name']);
                            sendEmail($data,"email_welcome");
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

        /******************************Product methods************************************/
        public function getProducts(){
            if($_POST){
                $intPerPage = intval($_POST['per_page']);
                $intPageNow = intval($_POST['page']);
                $intSort = intval($_POST['sort']);
                $strCategory = strClean($_POST['category']);
                $strSubcategory = strClean($_POST['subcategory']);
                $strSearch = strClean($_POST['search']);
                $request = $this->getProductsPageT($intPerPage,$intPageNow,$intSort,$strCategory,$strSubcategory,$strSearch);

                $htmlTotal ="de {$request['total_records']} resultados";
                ob_start();
                getComponent("cardProducts",$request['data']);
                $html =ob_get_clean();

                $arrData['html'] = $html;
                $arrData['html_total'] = $htmlTotal;
                $arrData['html_pages'] = getPagination($intPageNow,$request['start_page'],$request['total_pages'],$request['limit_page'],"page");

                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
            }
            die();
        }

        public function getProductVariant(){
            if($_POST){
                if(empty($_POST['id']) || empty($_POST['variant'])){
                    $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                }else{
                    $id = openssl_decrypt($_POST['id'],METHOD,KEY);
                    $variant = strClean($_POST['variant']);
                    if(is_numeric($id)){
                        $request = $this->selectProductVariant($id,$variant);
                        $discount = 0;
                        $priceDiscount = 0;
                        $percent =0;
                        if($request['price_offer']>0){
                            $percent = floor((1-($request['price_offer']/$request['price_sell']))*100);
                        }
                        $arrResponse = array(
                            "status"=>true,
                            "is_stock"=>boolval($request['is_stock']),
                            "stock"=>$request['stock'],
                            "price"=>formatNum($request['price_sell'],false),
                            "pricediscount"=>formatNum($request['price_offer'],false),
                            "percent"=>$percent > 0 ? "-".$percent."%" : ""
                        );
                    }else{
                        $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                    }
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
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

        function bubbleSortPrice($arr,$sort) {
            $n = count($arr);
            if($sort == 2){

                for ($i = 0; $i < $n - 1; $i++) {
                    for ($j = 0; $j < $n - $i - 1; $j++) {
                        if ($arr[$j]['price'] < $arr[$j + 1]['price']) {
                            // Intercambiar elementos si están en el orden incorrecto
                            $temp = $arr[$j];
                            $arr[$j] = $arr[$j + 1];
                            $arr[$j + 1] = $temp;
                        }
                    }
                }
            }else if($sort == 3){
                for ($i = 0; $i < $n - 1; $i++) {
                    for ($j = 0; $j < $n - $i - 1; $j++) {
                        if ($arr[$j]['price'] > $arr[$j + 1]['price']) {
                            // Intercambiar elementos si están en el orden incorrecto
                            $temp = $arr[$j];
                            $arr[$j] = $arr[$j + 1];
                            $arr[$j + 1] = $temp;
                        }
                    }
                }
            }
            return $arr;
        }
        /******************************wishlist methods************************************/
        public function addWishList(){
            if($_POST){
                if(isset($_SESSION['login'])){
                    $idProduct = openssl_decrypt($_POST['idProduct'],METHOD,KEY);
                    if(is_numeric($idProduct)){
                        $request = $this->addWishListT($idProduct,$_SESSION['idUser']);
                        if($request>0){
                            $arrResponse = array("status"=>true);
                        }else if("exists"){
                            $arrResponse = array("status"=>true);
                        }else{
                            $arrResponse = array("status"=>false);
                        }
                    }
                }else{
                    $arrResponse = array("status"=>false);
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }

        public function delWishList(){
            if($_POST){
                if(isset($_SESSION['login'])){
                    $idProduct = openssl_decrypt($_POST['idProduct'],METHOD,KEY);
                    if(is_numeric($idProduct)){
                        $request = $this->delWishListT($idProduct,$_SESSION['idUser']);
                        if($request>0){
                            $arrResponse = array("status"=>true);
                        }else{
                            $arrResponse = array("status"=>false);
                        }
                    }
                }else{
                    $arrResponse = array("status"=>false);
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
    }
?>