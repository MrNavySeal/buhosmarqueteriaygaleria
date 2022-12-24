<?php
    require_once("Models/CustomerTrait.php");
    class Contacto extends Controllers{
        use CustomerTrait;
        public function __construct(){
            parent::__construct();
            session_start();
            sessionCookie();
        }

        public function contacto(){
            $company=getCompanyInfo();
            $data['page_tag'] = "Contacto | ".$company['name'];
			$data['page_title'] = "Contacto | ".$company['name'];
			$data['page_name'] = "contacto";
            $data['app'] = "functions_contact.js";
            $this->views->getView($this,"contacto",$data);
        }
        public function setContact(){
            if($_POST){
                if(empty($_POST['txtContactName']) || empty($_POST['txtContactEmail']) || empty($_POST['txtContactMessage']) || empty($_POST['txtContactPhone'])){
                    $arrResponse = array("status"=>true,"msg"=>"Data error");
                }else{
                    $strName = ucwords(strClean($_POST['txtContactName']));
                    $strEmail = strtolower(strClean($_POST['txtContactEmail']));
                    $strPhone = strClean($_POST['txtContactPhone']);
                    $strMessage = strClean($_POST['txtContactMessage']);
                    $strSubject = "Nuevo mensaje";
                    $company = getCompanyInfo();
                    $request = $this->setMessage($strName,$strPhone,$strEmail,$strSubject,$strMessage);
                    if($request > 0){
                        $dataEmail = array('email_remitente' => $company['email'], 
                                                'email_usuario'=>$strEmail, 
                                                'email_copia'=>$company['secondary_email'],
                                                'asunto' =>$strSubject,
                                                "message"=>$strMessage,
                                                "company"=>$company,
                                                "phone"=>$strPhone,
                                                'name'=>$strName);
                        sendEmail($dataEmail,'email_contact');
                        $arrResponse = array("status"=>true,"msg"=>"Recibimos tu mensaje, pronto nos comunicaremos contigo.");
                    }else{
                        $arrResponse = array("status"=>false,"msg"=>"Error, intenta de nuevo");
                    }
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
    }
?>