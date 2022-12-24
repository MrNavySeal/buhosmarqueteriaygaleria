<?php
    
    class Empresa extends Controllers{
        public function __construct(){
            session_start();
            if(empty($_SESSION['login'])){
                header("location: ".base_url());
                die();
            }
            parent::__construct();
            sessionCookie();
        }

        public function empresa(){
            if($_SESSION['idUser'] == 1){
                $data['page_tag'] = "Empresa";
                $data['page_title'] = "Empresa";
                $data['page_name'] = "empresa";
                $data['app'] = "functions_company.js";
                $data['company'] = $this->model->selectCompany();
                $data['currencies'] = $this->model->selectCurrencies();
                $data['countries'] = $this->model->selectCountries();
                $data['states'] = $this->model->selectStates($data['company']['country']);
                $data['cities'] = $this->model->selectCities($data['company']['state']);
                $data['social'] = $this->model->selectSocial();
                $data['paypal'] = $this->model->selectCredentials();
                $this->views->getView($this,"empresa",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function setCompany(){
            //dep($_POST);exit;
            if($_SESSION['idUser']==1){
                if($_POST){
                    if(empty($_POST['txtName']) || empty($_POST['currencyList']) || empty($_POST['txtCompanyEmail']) || empty($_POST['txtEmail']) || empty($_POST['txtPhone']) 
                    || empty($_POST['txtAddress']) || empty($_POST['countryList']) || empty($_POST['stateList']) || empty($_POST['cityList']) || empty($_POST['txtPassword'])){
                        $arrResponse = array("status" => false, "msg" => 'Error de datos');
                    }else{ 
                        $strName = strClean($_POST['txtName']);
                        $intCurrency = intval($_POST['currencyList']);
                        $strCompanyEmail = strtolower(strClean($_POST['txtCompanyEmail']));
                        $strEmail = strtolower(strClean($_POST['txtEmail']));
                        $strPhone = strClean($_POST['txtPhone']);
                        $strAddress = strClean($_POST['txtAddress']);
                        $strKeywords = strClean($_POST['txtKeywords']);
                        $strDescription= strClean($_POST['txtDescription']);
                        $strPassword = strClean($_POST['txtPassword']);
                        $intCountry = intval($_POST['countryList']);
                        $intState = intval($_POST['stateList']);
                        $intCity = intval($_POST['cityList']);
                        $photo = "";
                        $logo="";

                        $request = $this->model->selectCompany();
                        
                        if($_FILES['txtImg']['name'] == ""){
                            $logo = $request['logo'];
                        }else{
                            if($request['logo'] != "ldefault.png"){
                                deleteFile($request['logo']);
                            }
                            $photo = $_FILES['txtImg'];
                            $logo = 'logo_'.bin2hex(random_bytes(6)).'.png';
                        }

                        $request = $this->model->updateCompany(
                            $logo,
                            $strName,
                            $intCurrency,
                            $strCompanyEmail,
                            $strEmail,
                            $strPassword,
                            $intCountry,
                            $intState,
                            $intCity,
                            $strPhone,
                            $strAddress,
                            $strKeywords,
                            $strDescription);
                        if($request > 0 ){
                            if($photo!=""){
                                uploadImage($photo,$logo);
                            }
                            $arrResponse = array("status" => true, "msg" => 'Datos guardados.');
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
        public function setSocial(){
            if($_SESSION['idUser']==1){
                if($_POST){
                    $facebook = strClean($_POST['txtFacebook']);
                    $twitter = strClean($_POST['txtTwitter']);
                    $youtube = strClean($_POST['txtYoutube']);
                    $instagram = strClean($_POST['txtInstagram']);
                    $linkedin = strClean($_POST['txtLinkedin']);
                    $whatsapp = strClean($_POST['txtWhatsapp']);

                    $request = $this->model->updateSocial($facebook,$twitter,$youtube,$instagram,$linkedin,$whatsapp);
                    if($request > 0 ){
                        $arrResponse = array("status" => true, "msg" => 'Datos guardados.');
                    }else{
                        $arrResponse = array("status" => false, "msg" => 'No es posible guardar los datos.');
                    }
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }else{
                header("location: ".base_url());
                die();
            }
            die();
        }
        public function setCredentials(){
            if($_SESSION['idUser']==1){
                if($_POST){
                    if(empty($_POST['txtClient']) || empty($_POST['txtSecret'])){
                        $arrResponse = array("status" => false, "msg" => 'Error de datos.');
                    }else{

                        $client = strClean($_POST['txtClient']);
                        $secret = strClean($_POST['txtSecret']);
    
                        $request = $this->model->updateCredentials($client,$secret);
                        if($request > 0 ){
                            $arrResponse = array("status" => true, "msg" => 'Datos guardados.');
                        }else{
                            $arrResponse = array("status" => false, "msg" => 'No es posible guardar los datos.');
                        }
                    }
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }else{
                header("location: ".base_url());
                die();
            }
            die();
        }
        public function getSelectCountry($id){
            $request = $this->model->selectStates($id);
            $html="";
            for ($i=0; $i < count($request) ; $i++) { 
                $html.='<option value="'.$request[$i]['id'].'">'.$request[$i]['name'].'</option>';
            }
            echo json_encode($html,JSON_UNESCAPED_UNICODE);
            die();
        }
        public function getSelectState($id){
            $request = $this->model->selectCities($id);
            $html="";
            for ($i=0; $i < count($request) ; $i++) { 
                $html.='<option value="'.$request[$i]['id'].'">'.$request[$i]['name'].'</option>';
            }
            echo json_encode($html,JSON_UNESCAPED_UNICODE);
            die();
        }

    }
?>