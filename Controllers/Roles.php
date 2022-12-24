<?php
    class Roles extends Controllers{
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
        public function roles(){
            if($_SESSION['idUser'] == 1){
                $data['page_tag'] = "Rol";
                $data['page_title'] = "Roles";
                $data['page_name'] = "role";
                $data['app'] = "functions_role.js";
                $data['data'] = $this->getRoles();
                $this->views->getView($this,"roles",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function setRole(){
            if($_SESSION['idUser'] == 1){
                if($_POST){
                    if(empty($_POST['txtName'])){
                        $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                    }else{
    
                        $idRol = intval($_POST['idRol']);
                        $strName = ucwords(strClean($_POST['txtName']));
                        if($idRol == 0){
                            $option = 1;
                            $request = $this->model->insertRole($strName);
                            if($request>0){
                                $modules = $this->model->selectModules();
                                $roleid = $request;
                                for ($i=0; $i < count($modules) ; $i++) { 
                                    $idModule = $modules[$i]['idmodule'];
                                    $r = 0;
                                    $w = 0;
                                    $u = 0;
                                    $d = 0;
                                    $request = $this->model->insertPermits($roleid,$idModule,$r,$w,$u,$d);
                                }
                            }
                        }else{
                            $option = 2;
                            $request = $this->model->updateRole($idRol,$strName);
                        }
                        if($request>0){
                            if($option == 1){
                                $arrResponse = $this->getRoles();
                                $arrResponse['msg'] = 'Datos guardados.';
                            }else{
                                $arrResponse = $this->getRoles();
                                $arrResponse['msg'] = 'Datos actualizados.';
                            }
                        }else if ($request=="exist"){
                            $arrResponse = array("status" =>false,"msg"=>"¡Atención! El rol ya existe, intente con otro nombre."); 
                        }else{
                            $arrResponse = array("status" =>false,"msg"=>"No se ha podido guardar los datos"); 
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
        public function getRoles($option=null,$params=null){
            if($_SESSION['idUser'] == 1){
                $html="";
                $request="";
                if($option == 1){
                    $request = $this->model->search($params);
                }else if($option == 2){
                    $request = $this->model->sort($params);
                }else{
                    $request = $this->model->selectRoles();
                }
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 
                        $delete = '<button class="btn btn-danger" type="button" title="Delete" data-id="'.$request[$i]['idrole'].'" name="btnDelete"><i class="fas fa-trash-alt"></i></button>';
                        if($request[$i]['idrole'] == 1 || $request[$i]['idrole']==2){
                            $delete='';
                        }
                        $html.='
                            <tr class="item"">
                                <td data-label="Rol: ">'.$request[$i]['name'].'</td>
                                <td class="item-btn">
                                    <button class="btn btn-secondary" type="button" title="Permits" data-id="'.$request[$i]['idrole'].'" name="btnPermit"><i class="fas fa-key"></i></button>
                                    <button class="btn btn-success" type="button" title="Edit" data-id="'.$request[$i]['idrole'].'" name="btnEdit"><i class="fas fa-pencil-alt"></i></button>
                                    '.$delete.' 
                                </td>
                            </tr>
                        ';
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
        public function getRole(){
            if($_SESSION['idUser'] == 1){
                if($_POST){
                    if(empty($_POST['idRol'])){
                        $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $idRol = intval($_POST['idRol']);
                        $request = $this->model->selectRole($idRol);
                        if(!empty($request)){
                            $arrResponse = array("status"=>true,"data"=>$request);
                        }else{
                            $arrResponse = array("status"=>false,"msg"=>"Error de datos");
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
        public function delRole(){
            if($_SESSION['idUser'] == 1){
                if($_POST){
                    if(empty($_POST['idRol'])){
                        $arrResponse=array("status"=>false,"Error de datos");
                    }else{
                        $id = intval($_POST['idRol']);
                        $request = $this->model->deleteRole($id);
                        if($request=="ok"){
                            $arrResponse = $this->getRoles();
                            $arrResponse['msg'] = "Se ha eliminado"; 
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
        public function getPermits(){
            if($_SESSION['idUser']==1){

                if($_POST['idRol']){
                    $id = intval($_POST['idRol']);
                    $arrModules = $this->model->selectModules();
                    $arrPermits = $this->model->selectPermits($id);
                    $arrResponse = array(
                        "module"=>$arrModules,
                        "permit"=>$arrPermits
                    );
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }else{
                header("location: ".base_url());
                die(); 
            }
            die();
        }
        public function setPermits(){
            if($_SESSION['idUser'] == 1){
                if($_POST){
                    $arrPermits = json_decode($_POST['permits'],true);
                    $idRole = intval($_POST['idRol']);
                    $request="";
                    $request = $this->model->deletePermits($idRole);
                    for ($i=0; $i < count($arrPermits); $i++) { 
                        $idmodule = $arrPermits[$i][0];
                        $r = $arrPermits[$i][1];
                        $w = $arrPermits[$i][2];
                        $u = $arrPermits[$i][3];
                        $d = $arrPermits[$i][4];
                        $request = $this->model->insertPermits($idRole,$idmodule,$r,$w,$u,$d);
                    }
                    if($request>0){
                        $arrResponse = array("status"=>true,"msg"=>"Permisos actualizados");
                    }else{
                        $arrResponse = array("status"=>false,"msg"=>"No es posible actualizar los permisos, intenta de nuevo.");
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
                $arrResponse = $this->getRoles(1,$search);
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function sort($params){
            if($_SESSION['permitsModule']['r']){
                $sort = intval($params);
                $arrResponse = $this->getRoles(2,$sort);
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
    }
?>