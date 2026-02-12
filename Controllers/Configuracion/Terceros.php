<?php
    class Terceros extends Controllers{

        public function __construct(){
            session_start();
            if(empty($_SESSION['login'])){
                header("location: ".base_url());
                die();
            }
            parent::__construct();
            sessionCookie();
        }

        public function terceros(){
            if($_SESSION['permitsModule']['r']){
                $data['botones'] = [
                    "duplicar" => ["mostrar"=>true, "evento"=>"onClick","funcion"=>"mypop=window.open('".BASE_URL.$_SESSION['permitsModule']['route']."','','');mypop.focus();"],
                    "nuevo" => ["mostrar"=>$_SESSION['permitsModule']['w'], "evento"=>"@click","funcion"=>"openModal()"],
                ];

                $data['page_tag'] = implode(" | ",[$_SESSION['permitsModule']['option'],$_SESSION['permitsModule']['module']]);
                $data['page_title'] = implode(" | ",[$_SESSION['permitsModule']['option'],$_SESSION['permitsModule']['module']]);
                $data['page_name'] = strtolower($_SESSION['permitsModule']['option']);
                $data['script_type'] = "module";
                $data['panelapp'] = "/Configuracion/functions_terceros.js";
                $this->views->getView($this,"terceros",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }

        public function getDatosIniciales(){
            if($_SESSION['permitsModule']['r']){
                $arrResponse = array(
                    "paises"=>getPaises(),
                    "tipo_regimen"=>HelperUsers::TIPO_REGIMEN,
                    "tipo_identificacion"=>HelperUsers::TIPO_IDENTIFICACION,
                    "tipo_persona"=>HelperUsers::TIPO_PERSONA
                );
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }

        public function setDatos(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    $intTipoPersona = intval($_POST['tipo_persona']);
                    $nickNombre = $intTipoPersona != 1 ? "nombre" : "razón social";
                    $errores = validator()->validate([
                        "nombre"=>"required;$nickNombre",
                        "pais"=>"required;pais",
                        "departamento"=>"required;departamento",
                        "ciudad"=>"required;ciudad",
                        "tipo_documento"=>"required; tipo de documento",
                        "tipo_regimen"=>"required; tipo de regimen",
                        "tipo_persona"=>"required; tipo de persona",
                    ])->getErrors();
                    if(empty($errores)){
                        $intId = intval($_POST['id']);
                        $data = [
                            "fecha"=>strClean($_POST['fecha']), 
                            "nombre"=>ucwords(strClean($_POST['nombre'])),
                            "apellido"=>ucwords(strClean($_POST['apellido'])),
                            "telefono"=>strClean($_POST['telefono']),
                            "indicativo"=>intval($_POST['indicativo']),
                            "correo"=>$_POST['correo'] != "" ? strtolower(strClean($_POST['correo'])) : "generico@generico.co",
                            "direccion"=>strClean($_POST['direccion']),
                            "pais"=>intval($_POST['pais']) != 0 ? intval($_POST['pais']) : 99999, 
                            "departamento"=>isset($_POST['departamento']) && intval($_POST['departamento']) != 0   ? intval($_POST['departamento']) : 99999,
                            "ciudad"=>isset($_POST['ciudad']) && intval($_POST['ciudad']) != 0 ? intval($_POST['ciudad']) : 99999,
                            "estado"=>intval($_POST['estado']),
                            "documento"=>strClean($_POST['documento']) !="" ? strClean($_POST['documento']) : "222222222",
                            "rol"=>2,
                            "tipo_documento"=>intval($_POST['tipo_documento']),
                            "tipo_regimen"=>intval($_POST['tipo_regimen']),
                            "tipo_persona"=>$intTipoPersona,
                            "is_cliente"=>intval($_POST['is_cliente']),
                            "is_proveedor"=>intval($_POST['is_proveedor']),
                            "is_otro"=>intval($_POST['is_otro']),
                            "is_usuario"=>intval($_POST['is_usuario']),
                            "digito_verificacion"=>strClean($_POST['digito_verificacion'])
                        ];
                        $request = "";
                        $strImagen = "";
                        $strImagenNombre="";
                        $strContrasena = strClean($_POST['contrasena']);
                        $strTempContrasena = $strContrasena;
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
                                $data['contrasena']=$strContrasena;
                                $data['imagen']=$strImagenNombre;
                                $request = $this->model->insertUsuario($data);
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

                                $data['contrasena']=$strContrasena;
                                $data['imagen']=$strImagenNombre;
                                $request = doubleval($this->model->updateUsuario($intId,$data));
                            }
                        }
                        if(is_numeric($request) && $request > 0){
                            if($strImagen!=""){ uploadImage($strImagen,$strImagenNombre); }
                            if($option == 1){
                                $arrResponse = array("status"=>true,"msg"=>'Datos guardados.');
                            }else{
                                $arrResponse = array("status"=>true,"msg"=>'Datos actualizados');
                            }
                        }else if($request == 'exist'){
                            $arrResponse = array('status' => false, 'msg' => '¡Atención! el correo electrónico, la identificación o el número de teléfono ya están registrados, pruebe con otro.');		
                        }else{
                            $arrResponse = array("status" => false, "msg" => 'No es posible guardar los datos.');
                        }
                    }else{
                        $arrResponse = array("status" => false, "msg" => 'No es posible guardar los datos.',"errores"=>$errores);
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
                        $arrResponse = array(
                            "status"=>true,
                            "data"=>$request,
                            "departamentos"=>getDepartamentos($request['countryid']),
                            "ciudades"=>getCiudades($request['stateid']),
                            "paises"=>getPaises(),
                        );
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