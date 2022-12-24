<?php
    class Usuarios extends Controllers{

        public function __construct(){
            session_start();
            if(empty($_SESSION['login'])){
                header("location: ".base_url());
                die();
            }
            parent::__construct();
            sessionCookie();
            getPermits(2);
        }
        public function usuarios(){
            if($_SESSION['permitsModule']['r']){
                $data['page_tag'] = "Usuario";
                $data['page_title'] = "Usuarios";
                $data['page_name'] = "user";
                $data['data'] = $this->getUsers();
                $data['app'] = "functions_user.js";
                $this->views->getView($this,"usuarios",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function getUsers($option=null,$params=null){
            if($_SESSION['permitsModule']['r']){
                $html="";
                $request="";
                if($option == 1){
                    $request = $this->model->search($params);
                }else if($option == 2){
                    $request = $this->model->sort($params);
                }else{
                    $request = $this->model->selectUsers();
                }
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 

                        $btnEdit="";
                        $btnDelete="";
                        $btnView = '<button class="btn btn-info m-1" type="button" title="Watch" data-id="'.$request[$i]['idperson'].'" name="btnView"><i class="fas fa-eye"></i></button>';
                        
                        if($_SESSION['permitsModule']['u'] && $request[$i]['roleid'] != 1 || $_SESSION['idUser'] == 1){
                            $btnEdit = '<button class="btn btn-success m-1" type="button" title="Edit" data-id="'.$request[$i]['idperson'].'" name="btnEdit"><i class="fas fa-pencil-alt"></i></button>';
                        }
                        if($_SESSION['permitsModule']['d'] && $request[$i]['roleid'] != 1 || $_SESSION['idUser'] == 1){
                            $btnDelete = '<button class="btn btn-danger m-1" type="button" title="Delete" data-id="'.$request[$i]['idperson'].'" name="btnDelete"><i class="fas fa-trash-alt"></i></button>';
                        }
                        if($request[$i]['status']==1){
                            $status='<span class="badge me-1 bg-success">Activo</span>';
                        }else{
                            $status='<span class="badge me-1 bg-danger">Inactivo</span>';
                        }
                        if($request[$i]['idperson'] != 1 && $request[$i]['roleid'] != 2){
                            $html.='
                                <tr class="item">
                                    <td class="text-center">
                                        <img src="'.$request[$i]['image'].'">
                                    </td>
                                    <td data-label="Nombres: ">'.$request[$i]['firstname'].'</td>
                                    <td data-label="Apellidos: ">'.$request[$i]['lastname'].'</td>
                                    <td data-label="Correo: ">'.$request[$i]['email'].'</td>
                                    <td data-label="Teléfono: ">'.$request[$i]['phone'].'</td>
                                    <td data-label="Fecha: ">'.$request[$i]['date'].'</td>
                                    <td data-label="Rol: ">'.$request[$i]['role'].'</td>
                                    <td data-label="Estado: ">'.$status.'</td>
                                    <td class="item-btn">'.$btnView.$btnEdit.$btnDelete.'</td>
                                </tr>
                            ';
                        }
                    }
                    $arrResponse = array("status"=>true,"data"=>$html);
                }else{
                    $arrResponse = array("status"=>false,"data"=>"No hay datos");
                }
            }else{
                header("location: ".base_url());
                die();
            }
            
            return $arrResponse;
        }
        public function getUser(){
            if($_SESSION['permitsModule']['r']){

                if($_POST){
                    if(empty($_POST)){
                        $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $idUser = intval($_POST['idUser']);
                        $request = $this->model->selectUser($idUser);
                        if(!empty($request)){
                            $request['image'] = media()."/images/uploads/".$request['image'];
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
        public function setUser(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    if(empty($_POST['txtFirstName']) || empty($_POST['txtLastName']) || empty($_POST['txtPhone']) || empty($_POST['typeList'] ) || empty($_POST['statusList'] )
                    || empty($_POST['txtEmail'])){
                        $arrResponse = array("status" => false, "msg" => 'Error de datos');
                    }else{ 
                        $idUser = intval($_POST['idUser']);
                        $strName = ucwords(strClean($_POST['txtFirstName']));
                        $strLastName = ucwords(strClean($_POST['txtLastName']));
                        $intPhone = intval(strClean($_POST['txtPhone']));
                        $strEmail = strtolower(strClean($_POST['txtEmail']));
                        $strPassword = strClean($_POST['txtPassword']);
                        $intRolId = intval(strClean($_POST['typeList']));
                        $intStatus = intval($_POST['statusList']);
                        $password =$strPassword;
                        $request_user = "";
                        $photo = "";
                        $photoProfile="";
                        $company = getCompanyInfo();
                        if($idUser == 0){
                            if($_SESSION['permitsModule']['w']){

                                $option = 1;

                                if($_FILES['txtImg']['name'] == ""){
                                    $photoProfile = "user.jpg";
                                }else{
                                    $photo = $_FILES['txtImg'];
                                    $photoProfile = 'profile_'.bin2hex(random_bytes(6)).'.png';
                                }

                                if($strPassword !=""){
                                    $strPassword =  hash("SHA256",$strPassword);
                                }else{
                                    $password =bin2hex(random_bytes(4));
                                    $strPassword =  hash("SHA256",$password);
                                }

                                $request_user = $this->model->insertUser(
                                    $strName, 
                                    $strLastName,
                                    $photoProfile, 
                                    $intPhone, 
                                    $strEmail,
                                    $strPassword,
                                    $intStatus,
                                    $intRolId
                                );
                            }
                        }else{
                            if($_SESSION['permitsModule']['u']){

                                $option = 2;
                                $request = $this->model->selectUser($idUser);

                                if($_FILES['txtImg']['name'] == ""){
                                    $photoProfile = $request['image'];
                                }else{
                                    if($request['image'] != "user.jpg"){
                                        deleteFile($request['image']);
                                    }
                                    $photo = $_FILES['txtImg'];
                                    $photoProfile = 'profile_'.bin2hex(random_bytes(6)).'.png';
                                }

                                if($strPassword!=""){
                                    $strPassword =  hash("SHA256",$strPassword);
                                }
                                
                                $request_user = $this->model->updateUser(
                                    $idUser, 
                                    $strName, 
                                    $strLastName,
                                    $photoProfile, 
                                    $intPhone, 
                                    $strEmail,
                                    $strPassword, 
                                    $intStatus,
                                    $intRolId
                                );
                            }
                        }
    
                        if($request_user > 0 ){
                            if($photo!=""){
                                uploadImage($photo,$photoProfile);
                            }
                            
                            if($option == 1){
                                $data['nombreUsuario'] = $strName." ".$strLastName;
                                $data['asunto']="Credentials";
                                $data['email_usuario'] = $strEmail;
                                $data['email_remitente'] = $company['email'];
                                $data['company'] = $company;
                                $data['password'] = $password;
                                sendEmail($data,"email_credentials");
                                $arrResponse = $this->getUsers();
                                $arrResponse['msg'] = 'Datos guardados. Se ha enviado un correo electrónico al usuario con las credenciales.';
                            }else{
                                if($strPassword!=""){
                                    $data['nombreUsuario'] = $strName." ".$strLastName;
                                    $data['asunto']="Credentials";
                                    $data['email_usuario'] = $strEmail;
                                    $data['email_remitente'] = $company['email'];
                                    $data['company'] = $company;
                                    $data['password'] = $password;
                                    sendEmail($data,"email_passwordUpdated");
                                    $arrResponse = $this->getUsers();
                                    $arrResponse['msg'] = 'La contraseña ha sido actualizada, se ha enviado un correo electrónico con la nueva contraseña.';
                                }else{
                                    $arrResponse = $this->getUsers();
                                    $arrResponse['msg'] = 'Datos actualizados';
                                }
                                
                            }
                        }else if($request_user == 'exist'){
                            $arrResponse = array('status' => false, 'msg' => '¡Atención! el correo electrónico o el número de teléfono ya están registrados, pruebe con otro.');		
                        }else{
                            $arrResponse = array("status" => false, "msg" => 'No es posible guardar los datos');
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
        public function getRoles(){
            $html="";
            $request = $this->model->selectRoles();
            if(count($request)>0){
                for ($i=0; $i < count($request); $i++) { 
                    if($_SESSION['idUser'] == 1){
                        $html.='<option value="'.$request[$i]['idrole'].'">'.$request[$i]['name'].'</option>';
                    }else if($request[$i]['idrole'] != 1){
                        $html.='<option value="'.$request[$i]['idrole'].'">'.$request[$i]['name'].'</option>';
                    }
                }
                $arrResponse = array("data"=>$html);
            }else{
                $arrResponse = array("data"=>"");
            }
            echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            die();
        }
        public function delUser(){
            if($_SESSION['permitsModule']['d']){

                if($_POST){
                    if(empty($_POST['idUser'])){
                        $arrResponse=array("status"=>false,"Error de datos");
                    }else{
                        $id = intval($_POST['idUser']);
                        
                        $request = $this->model->selectUser($id);
                        if($request['image'] !="user.jpg"){
                            deleteFile($request['image']);
                        }

                        $request = $this->model->deleteUser($id);
                        if($request=="ok"){
                            $arrResponse = $this->getUsers();
                            $arrResponse['msg'] = "Se ha eliminado";
                        }else{
                            $arrResponse = array("status"=>false,"msg"=>"No es posible eliminar, intenta de nuevo");
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
        public function search($params){
            if($_SESSION['permitsModule']['r']){
                $search = strClean($params);
                $arrResponse = $this->getUsers(1,$search);
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function sort($params){
            if($_SESSION['permitsModule']['r']){
                $sort = intval($params);
                $arrResponse = $this->getUsers(2,$sort);
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        /*************************Profile methods*******************************/
        public function perfil(){
            $data['page_tag'] = "Perfil";
            $data['page_title'] = "Perfil";
            $data['page_name'] = "profile";
            $data['app'] = "functions_profile.js";
            $this->views->getView($this,"perfil",$data);
        }
        public function updateProfile(){
            if($_POST){
                if(empty($_POST['txtFirstName']) || empty($_POST['txtLastName']) || empty($_POST['txtPhone']) || empty($_POST['countryList'] ) || empty($_POST['stateList'] )
                || empty($_POST['txtEmail']) || empty($_POST['cityList'] ) || empty($_POST['txtAddress'] )){
                    $arrResponse = array("status" => false, "msg" => 'Error de datos');
                }else{ 
                    $idUser = intval($_POST['idUser']);
                    $strName = ucwords(strClean($_POST['txtFirstName']));
                    $strLastName = ucwords(strClean($_POST['txtLastName']));
                    $intPhone = intval(strClean($_POST['txtPhone']));
                    $strEmail = strtolower(strClean($_POST['txtEmail']));
                    $strPassword = strClean($_POST['txtPassword']);
                    $strAddress = strClean($_POST['txtAddress']);
                    $intCountry = intval(strClean($_POST['countryList']));
                    $intState = intval($_POST['stateList']);
                    $intCity = intval($_POST['cityList']);

                    $request_user = "";
                    $photo = "";
                    $photoProfile="";

                    $option = 2;
                    $request = $this->model->selectUser($idUser);

                    if($_FILES['txtImg']['name'] == ""){
                        $photoProfile = $request['image'];
                    }else{
                        if($request['image'] != "user.jpg"){
                            deleteFile($request['image']);
                        }
                        $photo = $_FILES['txtImg'];
                        $photoProfile = 'profile_'.bin2hex(random_bytes(6)).'.png';
                    }

                    if($strPassword!=""){
                        $strPassword =  hash("SHA256",$strPassword);
                    }
                    
                    $request_user = $this->model->updateProfile(
                        $idUser, 
                        $strName, 
                        $strLastName,
                        $photoProfile, 
                        $intPhone, 
                        $strAddress,
                        $intCountry,
                        $intState,
                        $intCity,
                        $strEmail,
                        $strPassword
                    );
                        
                    if($request_user > 0 ){
                        if($photo!=""){
                            uploadImage($photo,$photoProfile);
                        }
                        $arrResponse = array('status' => true, 'msg' => 'Datos actualizados');
                    }else if($request_user == 'exist'){
                        $arrResponse = array('status' => false, 'msg' => '¡Atención! el correo electrónico o el número de teléfono ya están registrados, pruebe con otro.');		
                    }else{
                        $arrResponse = array("status" => false, "msg" => 'No es posible guardar los datos');
                    }
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function getSelectLocationInfo(){

            $idCountry = $_SESSION['userData']['countryid'];
            $idState = $_SESSION['userData']['stateid'];
            $idCity = $_SESSION['userData']['cityid'];

            $countries = $this->model->selectCountries();
            $states = $this->model->selectStates($idCountry);
            $cities = $this->model->selectCities($idState);
            //dep($countries);exit;
            $countrieshtml='';
            $stateshtml='';
            $citieshtml='';
            if($idCountry == $countries['id']){
                $countrieshtml.='<option value="0">Seleccione</option><option value="'.$countries['id'].'" selected>'.$countries['name'].'</option>';
            }else{
                $countrieshtml.='<option value="0" selected>Seleccione</option><option value="'.$countries['id'].'">'.$countries['name'].'</option>';
            }
            for ($i=0; $i < count($states) ; $i++) { 
                if($idState == $states[$i]['id']){
                    $stateshtml.='<option value="'.$states[$i]['id'].'" selected>'.$states[$i]['name'].'</option>';
                    
                }else{
                    $stateshtml.='<option value="'.$states[$i]['id'].'">'.$states[$i]['name'].'</option>';
                }
            }
            for ($i=0; $i < count($cities) ; $i++) { 
                if($idCity == $cities[$i]['id']){
                    $citieshtml.='<option value="'.$cities[$i]['id'].'" selected>'.$cities[$i]['name'].'</option>';
                }else{
                    $citieshtml.='<option value="'.$cities[$i]['id'].'">'.$cities[$i]['name'].'</option>';
                }
            }
            $arrResponse = array("countries"=>$countrieshtml,"states"=>$stateshtml,"cities"=>$citieshtml);
            echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
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