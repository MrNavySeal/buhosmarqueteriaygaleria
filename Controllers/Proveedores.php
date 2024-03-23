<?php
    class Proveedores extends Controllers{
        public function __construct(){
            session_start();
            if(empty($_SESSION['login'])){
                header("location: ".base_url());
                die();
            }
            parent::__construct();
            sessionCookie();
            getPermits(11);
        }
        public function proveedores(){
            if($_SESSION['permitsModule']['r']){
                $data['page_tag'] = "proveedor";
                $data['page_title'] = "Proveedores";
                $data['page_name'] = "proveedor";
                $data['panelapp'] = "functions_supplier.js";
                $this->views->getView($this,"proveedores",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function getSuppliers(){
            if($_SESSION['permitsModule']['r']){
                $request = $this->model->selectSuppliers();
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 
                        $btnView = '<button class="btn btn-info text-white m-1" type="button" title="Watch" onclick="viewItem('.$request[$i]['id_supplier'].')"><i class="fas fa-eye"></i></button>';
                        $btnEdit="";
                        $btnDelete="";
                        $status="";
                        if($request[$i]['status']==1){
                            $status='<span class="badge me-1 bg-success">Activo</span>';
                        }else{
                            $status='<span class="badge me-1 bg-danger">Inactivo</span>';
                        }

                        if($_SESSION['permitsModule']['u']){
                            $btnEdit = '<button class="btn btn-success m-1" type="button" title="Editar" onclick="editItem('.$request[$i]['id_supplier'].')" ><i class="fas fa-pencil-alt"></i></button>';
                        }
                        if($_SESSION['permitsModule']['d']){
                            $btnDelete = '<button class="btn btn-danger m-1" type="button" title="Eliminar" onclick="deleteItem('.$request[$i]['id_supplier'].')"><i class="fas fa-trash-alt"></i></button>';
                        }
                        $request[$i]['options'] = $btnView.$btnEdit.$btnDelete;
                        $request[$i]['status'] = $status;
                    }   
                }
                echo json_encode($request,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function getSupplier(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    if(empty($_POST)){
                        $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $id = intval($_POST['id']);
                        $request = $this->model->selectSupplier($id);
                        if(!empty($request)){
                            $countries = $this->model->selectCountries();
                            $states = $this->model->selectStates($request['id_country']);
                            $cities = $this->model->selectCities($request['id_state']);
    
                            $countrieshtml='<option disabled>Seleccione</option>';
                            $stateshtml="<option disabled>Seleccione</option>";
                            $citieshtml="<option disabled>Seleccione</option>";
    
                            if($request['id_country'] == $countries['id']){
                                $countrieshtml.='<option value="0">Seleccione</option><option value="'.$countries['id'].'" selected>'.$countries['name'].'</option>';
                            }
    
                            for ($i=0; $i < count($states) ; $i++) { 
                                if($request['id_state'] == $states[$i]['id']){
                                    $stateshtml.='<option value="'.$states[$i]['id'].'" selected>'.$states[$i]['name'].'</option>';
                                }else{
                                    $stateshtml.='<option value="'.$states[$i]['id'].'">'.$states[$i]['name'].'</option>';
                                }
                            }
                            for ($i=0; $i < count($cities) ; $i++) { 
                                if($request['id_city'] == $cities[$i]['id']){
                                    $citieshtml.='<option value="'.$cities[$i]['id'].'" selected>'.$cities[$i]['name'].'</option>';
                                }else{
                                    $citieshtml.='<option value="'.$cities[$i]['id'].'">'.$cities[$i]['name'].'</option>';
                                }
                            }
                            $arrResponse = array(
                                "status"=>true,
                                "data"=>$request,
                                "countries"=>$countrieshtml,
                                "states"=>$stateshtml,
                                "cities"=>$citieshtml
                            );
                        }else{
                            $arrResponse = array("status"=>false,"msg"=>"Error, intenta de nuevo"); 
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }
        public function setSupplier(){
            if($_SESSION['permitsModule']['w']){
                if($_POST){
                    if(empty($_POST['txtName']) || empty($_POST['txtPhone']) || $_POST['listCountry'] <= 0 
                    || $_POST['listState'] <= 0 || $_POST['listCity'] <= 0 || empty($_POST['txtAddress'])){
                        $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $intId = intval($_POST['id']);
                        $arrData = array(
                            "country"=>intval($_POST['listCountry']),
                            "state"=>intval($_POST['listState']),
                            "city"=>intval($_POST['listCity']),
                            "status"=>intval($_POST['statusList']),
                            "name"=>ucwords(strClean($_POST['txtName'])),
                            "nit"=>strClean($_POST['txtNit']),
                            "phone"=>strClean($_POST['txtPhone']),
                            "web"=>strClean($_POST['txtWeb']),
                            "email"=>strClean($_POST['txtEmail']),
                            "address"=>strClean($_POST['txtAddress']),
                            "contacts"=>$_POST['contacts']
                        );
                        $photo = "";
                        $photoRoute="";

                        if($intId == 0){
                            if($_SESSION['permitsModule']['w']){
                                $option = 1;
                                if($_FILES['txtImg']['name'] == ""){
                                    $photoRoute = "category.jpg";
                                }else{
                                    $photo = $_FILES['txtImg'];
                                    $photoRoute = 'supplier_'.bin2hex(random_bytes(6)).'.png';  
                                    
                                }
                                $arrData['img'] = $photoRoute;
                                $request= $this->model->insertSupplier($arrData);
                            }
                        }else{
                            if($_SESSION['permitsModule']['u']){
                                $option = 2;
                                $image = $this->model->selectSupplier($intId)['image'];
                                if($_FILES['txtImg']['name'] == ""){
                                    $photoRoute = $image;
                                }else{
                                    if($image != "category.jpg"){
                                        deleteFile($image);
                                    }
                                    $photo = $_FILES['txtImg'];
                                    $photoRoute = 'supplier_'.bin2hex(random_bytes(6)).'.png';
                                }
                                $arrData['img'] = $photoRoute;
                                $request= $this->model->updateSupplier($intId,$arrData);
                                
                            }
                        }
                        if(is_numeric($request) && $request > 0){
                            if($photo!=""){
                                uploadImage($photo,$photoRoute);
                            }
                            if($option == 1){
                                $arrResponse = array("status"=>true,"msg"=>"Datos guardados.");
                            }else{
                                $arrResponse = array("status"=>true,"msg"=>"Datos actualizados.");
                            }
                        }else if($request == "exist"){
                            $arrResponse = array('status' => false, 'msg' => 'El proveedor ya existe, prueba con otro nombre.');		
                        }else{
                            $arrResponse = array("status" => false, "msg" => 'No es posible guardar los datos.');
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }
        public function delSupplier(){
            if($_SESSION['permitsModule']['d']){

                if($_POST){
                    if(empty($_POST['id'])){
                        $arrResponse=array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $id = intval($_POST['id']);
                        $image = $this->model->selectSupplier($id)['image'];
                        if($image != "category.jpg"){
                            deleteFile($image);
                        }
                        $request = $this->model->deleteSupplier($id);
                        if($request=="ok"){
                            $arrResponse = array("status"=>true,"msg"=>"Se ha eliminado.");
                        }else{
                            $arrResponse = array("status"=>false,"msg"=>"No es posible eliminar, intenta de nuevo.");
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }
        public function getCountries(){
            $request = $this->model->selectCountries();
            $html='
            <option disabled selected>Seleccione</option>
            <option value="'.$request['id'].'">'.$request['name'].'</option>
            ';

            echo json_encode($html,JSON_UNESCAPED_UNICODE);
            die();
            
        }
        public function getSelectCountry($id){
            $request = $this->model->selectStates($id);
            $html = "<option disabled selected>Seleccione</option>";
            for ($i=0; $i < count($request) ; $i++) { 
                $html.='<option value="'.$request[$i]['id'].'">'.$request[$i]['name'].'</option>';
            }

            echo json_encode($html,JSON_UNESCAPED_UNICODE);
            die();
        }
        public function getSelectState($id,$flag=true){
            $request = $this->model->selectCities($id);
            $html = "<option disabled selected>Seleccione</option>";
            for ($i=0; $i < count($request) ; $i++) { 
                $html.='<option value="'.$request[$i]['id'].'">'.$request[$i]['name'].'</option>';
            }
            echo json_encode($html,JSON_UNESCAPED_UNICODE);
            die();
        }
    }
?>