<?php
    class Administracion extends Controllers{
        public function __construct(){
            session_start();
            
            if(empty($_SESSION['login'])){
                header("location: ".base_url());
                die();
            }
            parent::__construct();
            sessionCookie();
            getPermits(5);
        }

        /*************************Views*******************************/
        public function cupones(){
            if($_SESSION['permitsModule']['r']){
                $data['page_tag'] = "Cupones";
                $data['page_title'] = "Cupones";
                $data['page_name'] = "cupones";
                $data['app'] = "functions_coupon.js";
                $this->views->getView($this,"cupones",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function correo(){
            if($_SESSION['permitsModule']['r']){
                $data['inbox'] = $this->getMails();
                $data['sent'] = $this->getSentMails();
                $data['page_tag'] = "Correo";
                $data['page_title'] = "Correo";
                $data['page_name'] = "correo";
                $data['app'] = "functions_mailbox.js";
                $this->views->getView($this,"correo",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function mensaje($params){
            if($_SESSION['permitsModule']['r']){
                if(is_numeric($params)){
                    $id = intval($params);
                    $data['message'] = $this->model->selectMail($id);
                    $data['page_tag'] = "Mensaje";
                    $data['page_title'] = "Mensaje";
                    $data['page_name'] = "mensaje";
                    $data['app'] = "functions_mailbox.js";
                    $this->views->getView($this,"mensaje",$data);
                }else{
                    header("location: ".base_url()."/administracion/mailbox");
                    die();
                }
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function enviado($params){
            if($_SESSION['permitsModule']['r']){
                if(is_numeric($params)){
                    $id = intval($params);
                    $data['message'] = $this->model->selectSentMail($id);
                    $data['page_tag'] = "Enviados";
                    $data['page_title'] = "Enviados";
                    $data['page_name'] = "enviados";
                    $this->views->getView($this,"enviado",$data);
                }else{
                    header("location: ".base_url()."/administracion/mailbox");
                    die();
                }
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function suscriptores(){
            if($_SESSION['permitsModule']['r']){
                $data['page_tag'] = "Suscriptores";
                $data['page_title'] = "Suscriptores";
                $data['page_name'] = "suscriptores";
                $data['subscribers'] = $this->model->selectSubscribers();
                $this->views->getView($this,"suscriptores",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function envios(){
            if($_SESSION['permitsModule']['r']){
                $data['page_tag'] = "Envios";
                $data['page_title'] = "Envios";
                $data['page_name'] = "envios";
                $data['countries'] = $this->model->selectCountries();
                $data['ShippingCities'] = $this->getShippingCities();
                $data['flat'] = $this->model->selectFlatRate();
                $data['app'] = "functions_shipping.js";
                $this->views->getView($this,"envios",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        /*************************Coupon methods*******************************/
        public function getCoupons(){
            if($_SESSION['permitsModule']['r']){
                $html="";
                $request = $this->model->selectCoupons();
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 

                        $status="";
                        $btnEdit="";
                        $btnDelete="";
                        
                        if($_SESSION['permitsModule']['u']){
                            $btnEdit = '<button class="btn btn-success m-1" type="button" title="Edit" data-id="'.$request[$i]['id'].'" name="btnEdit"><i class="fas fa-pencil-alt"></i></button>';
                        }
                        if($_SESSION['permitsModule']['d'] && $request[$i]['id'] != 1){
                            $btnDelete = '<button class="btn btn-danger m-1" type="button" title="Delete" data-id="'.$request[$i]['id'].'" name="btnDelete"><i class="fas fa-trash-alt"></i></button>';
                        }
                        if($request[$i]['status']==1){
                            $status='<span class="badge me-1 bg-success">Activo</span>';
                        }else{
                            $status='<span class="badge me-1 bg-danger">Inactivo</span>';
                        }
                        $html.='
                            <tr class="item" data-name="'.$request[$i]['code'].'">
                                <td data-label="Código: ">'.$request[$i]['code'].'</td>
                                <td data-label="Descuento: ">'.$request[$i]['discount'].'%</td>
                                <td data-label="Estado: ">'.$status.'</td>
                                <td data-label="Fecha de creación: ">'.$request[$i]['date'].'</td>
                                <td data-label="Fecha de actualización: ">'.$request[$i]['dateupdate'].'</td>
                                <td class="item-btn">'.$btnEdit.$btnDelete.'</td>
                            </tr>
                        ';
                    }
                    $arrResponse = array("status"=>true,"data"=>$html);
                }else{
                    $arrResponse = array("status"=>false,"msg"=>"No hay datos");
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }else{
                header("location: ".base_url());
                die();
            }
            
            die();
        }
        public function getCoupon(){
            if($_SESSION['permitsModule']['r']){

                if($_POST){
                    if(empty($_POST)){
                        $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $idCoupon = intval($_POST['idCoupon']);
                        $request = $this->model->selectCoupon($idCoupon);
                        if(!empty($request)){
                            $arrResponse = array("status"=>true,"data"=>$request);
                        }else{
                            $arrResponse = array("status"=>false,"msg"=>"Error, intenta de nuevo."); 
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }else{
                header("location: ".base_url());
                die();
            }
            die();
        }
        public function setCoupon(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    if(empty($_POST['txtName']) || empty($_POST['statusList']) || empty($_POST['intDiscount'])){
                        $arrResponse = array("status" => false, "msg" => 'Error de datos');
                    }else{ 
                        $idCoupon = intval($_POST['idCoupon']);
                        $strCode = strtoupper(strClean($_POST['txtName']));
                        $intDiscount = intval(strClean($_POST['intDiscount']));
                        $intStatus = intval(strClean($_POST['statusList']));

                        if($idCoupon == 0){
                            if($_SESSION['permitsModule']['w']){
                                $option = 1;
                                $request= $this->model->insertCoupon($strCode,$intDiscount,$intStatus);
                            }
                        }else{
                            if($_SESSION['permitsModule']['u']){
                                $option = 2;
                                $request = $this->model->updateCoupon($idCoupon,$strCode,$intDiscount,$intStatus);
                            }
                        }
                        if($request > 0 ){
                            if($option == 1){
                                $arrResponse = array('status' => true, 'msg' => 'Datos guardados.');
                            }else{
                                $arrResponse = array('status' => true, 'msg' => 'Datos actualizados.');
                            }
                        }else if($request == 'exist'){
                            $arrResponse = array('status' => false, 'msg' => '¡Atención! El cupón ya existe, intente con otro código.');		
                        }else{
                            $arrResponse = array("status" => false, "msg" => 'No es posible guardar los datos.');
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }else{
                header("location: ".base_url());
                die();
            }
			die();
		}
        public function delCoupon(){
            if($_SESSION['permitsModule']['d']){

                if($_POST){
                    if(empty($_POST['idCoupon'])){
                        $arrResponse=array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $id = intval($_POST['idCoupon']);
                        $request = $this->model->deleteCoupon($id);
                        if($request=="ok"){
                            $arrResponse = array("status"=>true,"msg"=>"Se ha eliminado");
                        }else{
                            $arrResponse = array("status"=>false,"msg"=>"No se ha podido eliminar, intenta de nuevo.");
                        }
                        
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }else{
                header("location: ".base_url());
                die();
            }
            die();
        }
        /*************************Mailbox methods*******************************/
        public function getMails(){
            if($_SESSION['permitsModule']['r']){
                $html="";
                $total = 0;
                $request = $this->model->selectMails();
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 
                        $status ="";
                        $url = base_url()."/administracion/mensaje/".$request[$i]['id'];
                        if($request[$i]['status'] == 1){
                            $status="text-black-50";
                        }else{
                            $total++;
                        }
                        $html.='
                        <div class="mail-item '.$status.'">
                            <div class="row position-relative">
                                <div class="col-4">
                                    <p class="m-0 mail-info">'.$request[$i]['name'].'</p>
                                </div>
                                <div class="col-4">
                                    <p class="mail-info">'.$request[$i]['subject'].'</p>
                                </div>
                                <div class="col-4">
                                    <p class="m-0">'.$request[$i]['date'].'</p>
                                </div>
                                <a href="'.$url.'" class="position-absolute w-100 h-100"></a>
                            </div>
                            <button type="button" class="btn" onclick="delMail('.$request[$i]['id'].',1)"><i class="fas fa-trash-alt"></i></button>
                        </div>
                        ';
                    }
                    $arrResponse = array("status"=>true,"data"=>$html,"total"=>$total);
                }else{
                    $arrResponse = array("status"=>false,"msg"=>"No hay datos");
                }
            }
            return $arrResponse;
        }
        public function setReply(){
            if($_SESSION['permitsModule']['w']){
                if($_POST){
                    if(empty($_POST['txtMessage']) || empty($_POST['idMessage']) || empty($_POST['txtEmail']) || empty($_POST['txtName'])){
                        $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $strMessage = strClean($_POST['txtMessage']);
                        $idMessage = intval($_POST['idMessage']);
                        $strEmail = strClean(strtolower($_POST['txtEmail']));
                        $strName = strClean(ucwords($_POST['txtName']));
                        $request = $this->model->updateMessage($strMessage,$idMessage);
                        $company=getCompanyInfo();
                        if($request>0){
                            $dataEmail = array('email_remitente' => $company['email'], 
                                                    'email_usuario'=>$strEmail,
                                                    'asunto' =>'Respondiendo tu mensaje.',
                                                    "message"=>$strMessage,
                                                    'company'=>$company,
                                                    'name'=>$strName);
                            sendEmail($dataEmail,'email_reply');
                            $arrResponse = array("status"=>true,"msg"=>"Respuesta enviada"); 
                        }else{
                            $arrResponse = array("status"=>false,"msg"=>"Ha ocurrido un error, intenta de nuevo.");
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }
        public function sendEmail(){
            if($_SESSION['permitsModule']['w']){
                if($_POST){
                    if(empty($_POST['txtMessage']) ||  empty($_POST['txtEmail'])){
                        $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $strMessage = strClean($_POST['txtMessage']);
                        $strEmail = strClean(strtolower($_POST['txtEmail']));
                        $strEmailCC = strClean(strtolower($_POST['txtEmailCC']));
                        $strSubject = $_POST['txtSubject'] !="" ? strClean(($_POST['txtSubject'])) : "Has enviado un correo.";
                        $request = $this->model->insertMessage($strSubject,$strEmail,$strMessage);
                        $company = getCompanyInfo();
                        if($request>0){
                            $dataEmail = array('email_remitente' => $company['email'], 
                                                    'email_copia'=>$strEmailCC,
                                                    'email_usuario'=>$strEmail,
                                                    'asunto' =>$strSubject,
                                                    'company'=>$company,
                                                    "message"=>$strMessage);
                            sendEmail($dataEmail,'email_sent');
                            $arrResponse = array("status"=>true,"msg"=>"Mensaje enviado."); 
                        }else{
                            $arrResponse = array("status"=>false,"msg"=>"Ha ocurrido un error, intenta de nuevo.");
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }   
            }
            die();
        }
        public function getSentMails(){
            if($_SESSION['permitsModule']['r']){
                $html="";
                $request = $this->model->selectSentMails();
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 
                        $status ="";
                        $total = 0;
                        $email = explode("@",$request[$i]['email']);
                        $url = base_url()."/administracion/enviado/".$request[$i]['id'];
                        $html.='
                        <div class="mail-item text-black-50">
                            <div class="row position-relative">
                                <div class="col-4">
                                    <p class="m-0 mail-info">'.$email[0].'</p>
                                </div>
                                <div class="col-4">
                                    <p class="mail-info">'.$request[$i]['subject'].'</p>
                                </div>
                                <div class="col-4">
                                    <p class="m-0">'.$request[$i]['date'].'</p>
                                </div>
                                <a href="'.$url.'" class="position-absolute w-100 h-100"></a>
                            </div>
                            <button type="button" class="btn" onclick="delMail('.$request[$i]['id'].',2)"><i class="fas fa-trash-alt"></i></button>
                        </div>
                        ';
                    }
                    $arrResponse = array("status"=>true,"data"=>$html,"total"=>$total);
                }else{
                    $arrResponse = array("status"=>false,"msg"=>"No hay datos");
                }
            }
            return $arrResponse;
        }
        public function delMail(){
            if($_SESSION['permitsModule']['d']){
                if($_POST){
                    $id = intval($_POST['id']);
                    $option = intval($_POST['option']);

                    $request = $this->model->delEmail($id,$option);
                    
                    if($request=="ok"){
                        $arrResponse = array("status"=>true,"msg"=>"The mail has been deleted."); 
                    }else{
                        $arrResponse = array("status"=>false,"msg"=>"Error, try again."); 
                    }
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        /*************************Shipping methods*******************************/
        public function setShippingMode(){
            if($_SESSION['permitsModule']['u']){
                if($_POST){
                    $idShipping = intval($_POST['idShipping']);
                    if($idShipping == 2 && empty($_POST['intValue'])){
                        $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $value = !empty($_POST['intValue']) ? intval($_POST['intValue']) : 0;
                        $request = $this->model->setShippingMode($idShipping, $value);
                        $arrResponse = array("status"=>true,"msg"=>"Se ha guardado la configuración de envío.");
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }
        public function setShippingCity(){
            if($_SESSION['permitsModule']['w']){
                if($_POST){
                    if(empty($_POST['idCountry']) || empty($_POST['idState']) || empty($_POST['idCity']) || empty($_POST['value'])){
                        $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $idCountry = intval($_POST['idCountry']);
                        $idState = intval($_POST['idState']);
                        $idCity = intval($_POST['idCity']);
                        $value = intval($_POST['value']);
                        $request = $this->model->setShippingCity($idCountry,$idState,$idCity,$value);
                        if($request>0){
                            $html = $this->getShippingCities();
                            $arrResponse = array("status"=>true,"html"=>$html);
                        }else if($request = "exists"){
                            $arrResponse = array("status"=>false,"msg"=>"Ya existe, intenta con otro."); 
                        }else{
                            $arrResponse = array("status"=>false,"msg"=>"Error, intenta de nuevo."); 
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }
        public function getShippingCities(){
            if($_SESSION['permitsModule']['r']){
                $request = $this->model->selectShippingCities();
                //dep($request);exit;
                $html="";
                if(!empty($request)){
                    for ($i=0; $i < count($request); $i++) { 
                        $delete = "";
                        if($_SESSION['permitsModule']['d']){
                            $delete = '<td><button type="button" class="btn btn-sm btn-danger text-white" onclick="deleteCityShipp('.$request[$i]['id'].')"><i class="fas fa-trash-alt" aria-hidden="true"></i></button></td>';
                        }
                        $html.= '
                        <tr>
                            <td>'.$request[$i]['country'].'</td>
                            <td>'.$request[$i]['state'].'</td>
                            <td>'.$request[$i]['city'].'</td>
                            <td>'.formatNum($request[$i]['value']).'</td>
                            '.$delete.'
                        </tr>
                        ';
                    }   
                }
            }
            return $html;
        }
        public function getSelectCountry($id){
            $request = $this->model->selectStates($id);
            $html='<option selected value="0">Select</option>';
            for ($i=0; $i < count($request) ; $i++) { 
                $html.='<option value="'.$request[$i]['id'].'">'.$request[$i]['name'].'</option>';
            }
            echo json_encode($html,JSON_UNESCAPED_UNICODE);
            die();
        }
        public function delShippingCity($id){
            if($_SESSION['permitsModule']['d']){
                $id = intval($id);
                $request = $this->model->delShippingCity($id);
                if($request=="ok"){
                    $html = $this->getShippingCities();
                    $arrResponse = array("status"=>true,"html"=>$html); 
                }else{
                    $arrResponse = array("status"=>false,"msg"=>"Error, try again."); 
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function getSelectState($id){
            $request = $this->model->selectCities($id);
            $html='<option selected value="0">Select</option>';
            for ($i=0; $i < count($request) ; $i++) { 
                $html.='<option value="'.$request[$i]['id'].'">'.$request[$i]['name'].'</option>';
            }
            echo json_encode($html,JSON_UNESCAPED_UNICODE);
            die();
        }

        /*************************Pages methods*******************************/
        public function updatePage(){
            if($_SESSION['permitsModule']['u']){
                if($_POST){
                    if(empty($_POST['txtDescription']) || empty($_POST['txtName'])){
                        $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $id = intval($_POST['idPage']);
                        $strDescription = $_POST['txtDescription'];
                        $strName = strClean($_POST['txtName']);
                        $request = $this->model->updatePage($id,$strName,$strDescription);

                        if($request>0){
                            $arrResponse = array("status"=>true,"msg"=>"Página actualizada."); 
                        }else{
                            $arrResponse = array("status"=>false,"msg"=>"La página no se puede actualizar, inténtelo de nuevo.");
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }
    }
?>