<?php
    class Usuarios extends Controllers{

        public function __construct(){
            session_start();
            if(empty($_SESSION['login'])){
                header("location: ".base_url());
                die();
            }
            parent::__construct();
        }
        public function usuarios(){
            if($_SESSION['permitsModule']['r']){
                $data['botones'] = [
                    "duplicar" => ["mostrar"=>true, "evento"=>"onClick","funcion"=>"mypop=window.open('".BASE_URL."/sistema/usuarios/"."','','');mypop.focus();"],
                    "nuevo" => ["mostrar"=>$_SESSION['permitsModule']['w'], "evento"=>"@click","funcion"=>"openModal()"],
                ];
                $data['page_tag'] = "Usuarios | Sistema";
                $data['page_title'] = "Usuarios | Sistema";
                $data['page_name'] = "usuarios";
                $data['script_type'] = "module";
                $data['panelapp'] = "/Sistema/functions_usuarios.js";
                $this->views->getView($this,"usuarios",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function getDatosIniciales(){
            if($_SESSION['permitsModule']['r']){
                $arrResponse = array(
                    "paises"=>getPaises(),
                    "roles"=>$this->model->selectRoles()
                );
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function setUsuario(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    if(empty($_POST['nombre']) || empty($_POST['apellido']) || empty($_POST['telefono']) 
                    || empty($_POST['pais']) || empty($_POST['departamento'])  || empty($_POST['ciudad'])
                    ){
                        $arrResponse = array("status" => false, "msg" => 'Todos los campos con (*) son obligatorios');
                    }else{ 
                        $intId = intval($_POST['id']);
                        $strNombre = ucwords(strClean($_POST['nombre']));
                        $strApellido = ucwords(strClean($_POST['apellido']));
                        $intTelefono = doubleval(strClean($_POST['telefono']));
                        $strCorreo = $_POST['correo'] != "" ? strtolower(strClean($_POST['correo'])) : "generico@generico.co";
                        $strDireccion = strClean($_POST['direccion']);
                        $intPais = intval($_POST['pais']) != 0 ? intval($_POST['pais']) : 99999;
                        $intDepartamento = isset($_POST['departamento']) && intval($_POST['departamento']) != 0   ? intval($_POST['departamento']) : 99999;
                        $intCiudad = isset($_POST['ciudad']) && intval($_POST['ciudad']) != 0 ? intval($_POST['ciudad']) : 99999;
                        $strContrasena = strClean($_POST['contrasena']);
                        $intRolId = intval($_POST['rol']);
                        $intEstado = intval($_POST['estado']);
                        $strTempContrasena =$strContrasena;
                        $request = "";
                        $strDocumento = strClean($_POST['documento']) !="" ? strClean($_POST['documento']) : "222222222";
                        $strImagen = "";
                        $strImagenNombre="";
                        $company = getCompanyInfo();
                        if($intId == 0){
                            if($_SESSION['permitsModule']['w']){
                                $option = 1;
                                if($_FILES['imagen']['name'] == ""){
                                    $strImagenNombre = "user.jpg";
                                }else{
                                    $strImagen = $_FILES['imagen'];
                                    $strImagenNombre = 'profile_'.bin2hex(random_bytes(6)).'.png';
                                }
    
                                if($strContrasena !=""){
                                    $strContrasena =  hash("SHA256",$strContrasena);
                                }else{
                                    $strTempContrasena =bin2hex(random_bytes(4));
                                    $strContrasena =  hash("SHA256",$strTempContrasena);
                                }
    
                                $request = $this->model->insertUsuario(
                                    $strNombre, 
                                    $strApellido,
                                    $intTelefono,
                                    $strCorreo, 
                                    $strDireccion, 
                                    $intPais,
                                    $intDepartamento,
                                    $intCiudad,
                                    $strContrasena,
                                    $intEstado,
                                    $strDocumento,
                                    $intRolId,
                                    $strImagenNombre,
                                );
                            }
                        }else{
                            if($_SESSION['permitsModule']['u']){
    
                                $option = 2;
                                $request = $this->model->selectUsuario($intId);
    
                                if($_FILES['imagen']['name'] == ""){
                                    $strImagenNombre = $request['image'] != "" ? $request['image'] :"user.jpg";
                                }else{
                                    if($request['image'] != "user.jpg"){
                                        deleteFile($request['image']);
                                    }
                                    $strImagen = $_FILES['imagen'];
                                    $strImagenNombre = 'profile_'.bin2hex(random_bytes(6)).'.png';
                                }
                                if($strContrasena!=""){ $strContrasena =  hash("SHA256",$strContrasena); }
                                
                                $request = doubleval($this->model->updateUsuario(
                                    $intId, 
                                    $strNombre, 
                                    $strApellido,
                                    $intTelefono,
                                    $strCorreo, 
                                    $strDireccion, 
                                    $intPais,
                                    $intDepartamento,
                                    $intCiudad,
                                    $strContrasena,
                                    $intEstado,
                                    $strDocumento,
                                    $intRolId,
                                    $strImagenNombre,
                                ));
                            }
                        }
                        if(is_numeric($request) && $request > 0){
                            if($strImagen!=""){
                                uploadImage($strImagen,$strImagenNombre);
                            }
                            if($option == 1){
                                $data['nombreUsuario'] = $strNombre." ".$strApellido;
                                $data['asunto']="Credenciales";
                                $data['email_usuario'] = $strCorreo;
                                $data['email_remitente'] = $company['email'];
                                $data['password'] = $strTempContrasena;
                                $data['company'] = $company;
                                if($strCorreo !="generico@generico.co"){
                                    try { sendEmail($data,"email_credentials"); } catch (\Throwable $th) {}
                                    $arrResponse = array("status"=>true,"msg"=>'Datos guardados. Se ha enviado un correo electrónico al usuario con las credenciales.');
                                }else{
                                    $arrResponse = array("status"=>true,"msg"=>'Datos guardados.');
                                }
                            }else{
                                if($strContrasena!=""){
                                    $data['nombreUsuario'] = $strNombre." ".$strApellido;
                                    $data['asunto']="Credenciales";
                                    $data['email_usuario'] = $strCorreo;
                                    $data['email_remitente'] = $company['email'];
                                    $data['password'] = $strTempContrasena;
                                    $data['company'] = $company;
                                    if($strCorreo !="generico@generico.co"){
                                        try { sendEmail($data,"email_passwordUpdated"); } catch (\Throwable $th) {}
                                        $arrResponse = array("status"=>true,"msg"=>'La contraseña ha sido actualizada, se ha enviado un correo electrónico con la nueva contraseña.');
                                    }else{
                                        $arrResponse = array("status"=>true,"msg"=>'Datos actualizados');
                                    }
                                }else{
                                    $arrResponse = array("status"=>true,"msg"=>'Datos actualizados');
                                }
                                
                            }
                        }else if($request == 'exist'){
                            $arrResponse = array('status' => false, 'msg' => '¡Atención! el correo electrónico, la identificación o el número de teléfono ya están registrados, pruebe con otro.');		
                        }else{
                            $arrResponse = array("status" => false, "msg" => 'No es posible guardar los datos.');
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
			die();
		}
        public function getBuscar(){
            if($_SESSION['permitsModule']['r']){
                $intPage = intval($_POST['page']);
                $intPerPage = intval($_POST['per_page']);
                $strSearch = strClean($_POST['search']);
                $request = $this->model->selectUsuarios($intPage,$intPerPage,$strSearch);
                echo json_encode($request,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function getDatos(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    $intId = intval($_POST['id']);
                    $request = $this->model->selectUsuario($intId);
                    if(!empty($request)){
                        if(isset($request['image'])){$request['url'] = media()."/images/uploads/".$request['image'];}
                        $arrResponse = array("status"=>true,"data"=>$request);
                    }else{
                        $arrResponse = array("status"=>false,"msg"=>"Error, intenta de nuevo"); 
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }
        public function getEstados($params){
            $arrParams = explode(",",$params);
            $strTipo = $arrParams[0];
            $intId = $arrParams[1];
            if($strTipo == "estado"){$request = getDepartamentos($intId);}
            else{$request = getCiudades($intId);}
            echo json_encode($request,JSON_UNESCAPED_UNICODE);
        }
        public function delDatos(){
            if($_SESSION['permitsModule']['d']){
                if($_POST){
                    $intId = intval($_POST['id']);
                    $request = $this->model->selectUsuario($intId);
                    if($request['image']!="user.jpg"){ 
                        deleteFile($request['image']);
                     }
                    $request = $this->model->deleteUsuario($intId);
                    if($request > 0 || $request == "ok"){
                        $arrResponse = array("status"=>true,"msg"=>"Se ha eliminado correctamente.");
                    }else{
                        $arrResponse = array("status"=>false,"msg"=>"No es posible eliminar, intenta de nuevo.");
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }
        //Pemisos
        public function setPermisos(){
            if($_SESSION['permitsModule']['u']){
                if($_POST){
                    $arrData = json_decode($_POST['data'],true);
                    $intId = intval($_POST['id']);
                    $intRolId = intval($_POST['rol']);
                    $request = $this->model->insertPermisos($intRolId,$intId,$arrData);
                    if(is_numeric($request) && $request > 0){
                        $arrResponse = array("status"=>true,"msg"=>"Permisos asignados correctamente.");
                    }else{
                        $arrResponse = array("status"=>false,"msg"=>"No se ha podido guardar, intente de nuevo.");
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }
        public function getPermisos(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    $intId = intval($_POST['id']);
                    $intRolId = intval($_POST['rol']);
                    $request = $this->model->selectPermisos($intRolId,$intId);
                    $arrResponse = [
                        "data"=>$request,
                        "r"=>!empty(array_filter($request,function($e){return $e['r'];})),
                        "w"=>!empty(array_filter($request,function($e){return $e['w'];})),
                        "u"=>!empty(array_filter($request,function($e){return $e['u'];})),
                        "d"=>!empty(array_filter($request,function($e){return $e['d'];})),
                    ];
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }
        /*************************Profile methods*******************************/
        public function perfil(){
            $data['page_tag'] = "Perfil";
            $data['page_title'] = "Perfil";
            $data['page_name'] = "perfil";
            $data['panelapp'] = "/Sistema/functions_perfil.js";
            $this->views->getView($this,"perfil",$data);
        }
        public function getPerfil(){
            $request = $_SESSION['userData'];
            if(isset($request['image'])){$request['url'] = media()."/images/uploads/".$request['image'];}
            $arrResponse = array(
                "paises"=>getPaises(),
                "data"=>$request
            );
            echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            die();
        }
        public function getSelectLocationInfo(){

            $idCountry = $_SESSION['userData']['countryid'];
            $idState = $_SESSION['userData']['stateid'];
            $idCity = $_SESSION['userData']['cityid'];

            $countries = getPaises();
            $states = getDepartamentos($idCountry);
            $cities = getCiudades($idState);
            //dep($countries);exit;
            $countrieshtml='<option value="0">Seleccione</option>';
            $stateshtml='';
            $citieshtml='';
            foreach ($countries as $country) {
                if($idCountry == $country['id']){
                    $countrieshtml.='<option value="'.$country['id'].'" selected>'.$country['name'].'</option>';
                }else{
                    $countrieshtml.='<option value="'.$country['id'].'">'.$country['name'].'</option>';
                }
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
        public function updatePerfil(){
            if($_POST){
                if(empty($_POST['txtFirstName']) || empty($_POST['txtLastName']) || empty($_POST['txtPhone']) || empty($_POST['countryList'] ) || empty($_POST['stateList'] )
                || empty($_POST['txtEmail']) || empty($_POST['cityList'] ) || empty($_POST['txtAddress'] || empty($_POST['txtDocument']))){
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
                    $strDocument = strClean($_POST['txtDocument']);

                    $request_user = "";
                    $photo = "";
                    $photoProfile="";

                    $option = 2;
                    $request = $this->model->selectUsuario($idUser);

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
                    
                    $request_user = $this->model->updatePerfil(
                        $idUser, 
                        $strName, 
                        $strLastName,
                        $photoProfile, 
                        $intPhone, 
                        $strAddress,
                        $intCountry,
                        $intState,
                        $intCity,
                        $strDocument,
                        $strEmail,
                        $strPassword
                    );
                        
                    if($request_user > 0 ){
                        if($photo!=""){
                            uploadImage($photo,$photoProfile);
                        }
                        $arrResponse = array('status' => true, 'msg' => 'Datos actualizados');
                    }else if($request_user == 'exist'){
                        $arrResponse = array('status' => false, 'msg' => '¡Atención! el correo electrónico, la cédula o el número de teléfono ya están registrados, pruebe con otro.');		
                    }else{
                        $arrResponse = array("status" => false, "msg" => 'No es posible guardar los datos');
                    }
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
    }
?>