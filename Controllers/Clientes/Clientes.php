<?php
    class Clientes extends Controllers{

        public function __construct(){
            session_start();
            if(empty($_SESSION['login'])){
                header("location: ".base_url());
                die();
            }
            parent::__construct();
            sessionCookie();
        }
        public function clientes(){
            if($_SESSION['permitsModule']['r']){
                $data['botones'] = [
                    "duplicar" => ["mostrar"=>true, "evento"=>"onClick","funcion"=>"mypop=window.open('".BASE_URL."/clientes/clientes/"."','','');mypop.focus();"],
                    "nuevo" => ["mostrar"=>$_SESSION['permitsModule']['w'], "evento"=>"@click","funcion"=>"openModal()"],
                ];
                $data['page_tag'] = "Clientes";
                $data['page_title'] = "Clientes";
                $data['page_name'] = "clientes";
                $data['script_type'] = "module";
                $data['panelapp'] = "/Clientes/functions_clientes.js";
                $this->views->getView($this,"clientes",$data);
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
                        $intRolId = 2;
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
                                        if(file_exists(media()."/images/uploads/".$request['image'])){
                                            deleteFile($request['image']);
                                        }
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
    }
?>