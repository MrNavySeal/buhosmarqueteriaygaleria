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
                $data['initial_data'] = array("categories"=>$this->getSelectCategories(),"countries"=>$this->getCountries());
                $this->views->getView($this,"proveedores",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function categorias(){
            if($_SESSION['permitsModule']['r']){
                $data['page_tag'] = "categoria";
                $data['page_title'] = "Proveedores | Categorias";
                $data['page_name'] = "categoria";
                $data['panelapp'] = "functions_supplier_category.js";
                $this->views->getView($this,"categoria",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        /*************************Suppliers methods*******************************/
        public function getSuppliers(){
            if($_SESSION['permitsModule']['r']){
                $request = $this->model->selectCategories();
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 

                        $btnEdit="";
                        $btnDelete="";
                        $status="";
                        if($request[$i]['status']==1){
                            $status='<span class="badge me-1 bg-success">Activo</span>';
                        }else{
                            $status='<span class="badge me-1 bg-danger">Inactivo</span>';
                        }

                        if($_SESSION['permitsModule']['u']){
                            $btnEdit = '<button class="btn btn-success m-1" type="button" title="Editar" onclick="editItem('.$request[$i]['id_categories'].')" ><i class="fas fa-pencil-alt"></i></button>';
                        }
                        if($_SESSION['permitsModule']['d']){
                            $btnDelete = '<button class="btn btn-danger m-1" type="button" title="Eliminar" onclick="deleteItem('.$request[$i]['id_categories'].')"><i class="fas fa-trash-alt"></i></button>';
                        }
                        $request[$i]['options'] = $btnEdit.$btnDelete;
                        $request[$i]['status'] = $status;
                    }   
                }
                echo json_encode($request,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        /*************************Category methods*******************************/
        public function getCategories(){
            if($_SESSION['permitsModule']['r']){
                $request = $this->model->selectCategories();
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 

                        $btnEdit="";
                        $btnDelete="";
                        $status="";
                        if($request[$i]['status']==1){
                            $status='<span class="badge me-1 bg-success">Activo</span>';
                        }else{
                            $status='<span class="badge me-1 bg-danger">Inactivo</span>';
                        }

                        if($_SESSION['permitsModule']['u']){
                            $btnEdit = '<button class="btn btn-success m-1" type="button" title="Editar" onclick="editItem('.$request[$i]['id_categories'].')" ><i class="fas fa-pencil-alt"></i></button>';
                        }
                        if($_SESSION['permitsModule']['d']){
                            $btnDelete = '<button class="btn btn-danger m-1" type="button" title="Eliminar" onclick="deleteItem('.$request[$i]['id_categories'].')"><i class="fas fa-trash-alt"></i></button>';
                        }
                        $request[$i]['options'] = $btnEdit.$btnDelete;
                        $request[$i]['status'] = $status;
                    }   
                }
                echo json_encode($request,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function getCategory(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    if(empty($_POST)){
                        $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $idCategory = intval($_POST['id']);
                        $request = $this->model->selectCategory($idCategory);
                        if(!empty($request)){
                            $arrResponse = array("status"=>true,"data"=>$request);
                        }else{
                            $arrResponse = array("status"=>false,"msg"=>"Error, intenta de nuevo"); 
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }
        public function setCategory(){
            if($_SESSION['permitsModule']['w']){
                if($_POST){
                    if(empty($_POST['txtName'])){
                        $arrResponse = array("status" => false, "msg" => 'Error de datos');
                    }else{ 
                        $id = intval($_POST['id']);
                        $strName = ucwords(strClean($_POST['txtName']));
                        $status = intval($_POST['statusList']);
                        if($id == 0){
                            if($_SESSION['permitsModule']['w']){
                                $option = 1;
                                $request= $this->model->insertCategory($strName,$status);
                            }
                        }else{
                            if($_SESSION['permitsModule']['u']){
                                $option = 2;
                                $request = $this->model->updateCategory($id,$strName,$status);
                            }
                        }
                        if(is_numeric($request) && $request > 0){
                            if($option == 1){
                                $arrResponse = array("status"=>true,"msg"=>"Datos guardados.");
                            }else{
                                $arrResponse = array("status"=>true,"msg"=>"Datos actualizados.");
                            }
                        }else if($request == "exist"){
                            $arrResponse = array('status' => false, 'msg' => 'La categoría ya existe, prueba con otro nombre.');		
                        }else{
                            $arrResponse = array("status" => false, "msg" => 'No es posible guardar los datos.');
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
			die();
		}
        public function delCategory(){
            if($_SESSION['permitsModule']['d']){

                if($_POST){
                    if(empty($_POST['id'])){
                        $arrResponse=array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $id = intval($_POST['id']);
                        $request = $this->model->deleteCategory($id);
                        if($request=="ok"){
                            $arrResponse = array("status"=>true,"msg"=>"Se ha eliminado.");
                        }else if($request =="exist"){
                            $arrResponse = array("status"=>false,"msg"=>"La categoría tiene al menos una subcategoría asignada, no puede ser eliminada.");
                        }else{
                            $arrResponse = array("status"=>false,"msg"=>"No es posible eliminar, intenta de nuevo.");
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }
        public function getSelectCategories(){
            $request = $this->model->selectCategories(true);
            $html = "";
            foreach ($request as $key) {
                $html.='<option value="'.$key['id_categories'].'">'.$key['name'].'</option>';
            }
            return $html;
        }
        /*************************Others methods*******************************/
        public function getCountries(){
            $request = $this->model->selectCountries();
            $html='
            <option value="'.$request['id'].'">'.$request['name'].'</option>
            ';
            return $html;
        }
        public function getSelectCountry($id){
            $request = $this->model->selectStates($id);
            for ($i=0; $i < count($request) ; $i++) { 
                $html.='<option value="'.$request[$i]['id'].'">'.$request[$i]['name'].'</option>';
            }
            echo json_encode($html,JSON_UNESCAPED_UNICODE);
            die();
        }
        public function getSelectState($id){
            $request = $this->model->selectCities($id);
            for ($i=0; $i < count($request) ; $i++) { 
                $html.='<option value="'.$request[$i]['id'].'">'.$request[$i]['name'].'</option>';
            }
            echo json_encode($html,JSON_UNESCAPED_UNICODE);
            die();
        }
    }
?>