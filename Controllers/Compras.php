<?php
    
    class Compras extends Controllers{
        public function __construct(){
            session_start();
            parent::__construct();
            sessionCookie();
            getPermits(8);
        }

        public function compras(){
            if($_SESSION['permitsModule']['r']){
                $data['page_tag'] = "compras";
                $data['page_title'] = "Compras";
                $data['page_name'] = "compras";
                $data['proveedores'] = $this->getSelectSuppliers();
                $data['data'] = $this->getPurchases();
                $data['app'] = "functions_compras.js";
                $this->views->getView($this,"compras",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function compra($params){
            if($_SESSION['permitsModule']['r']){
                $id = strClean(intval($params));
                $purchase = $this->getPurchase($id);
                if(!empty($purchase)){
                    $data['page_tag'] = "compra";
                    $data['page_title'] = "Compra";
                    $data['page_name'] = "compra";
                    $data['data'] = $purchase;
                    $data['company'] = getCompanyInfo();
                    //$data['app'] = "functions_compras.js";
                    $this->views->getView($this,"compra",$data);
                }else{
                    header("location: ".base_url()."/compras/compras");
                    die();
                }
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function proveedores(){
            if($_SESSION['permitsModule']['r']){
                $data['page_tag'] = "Proveedores";
                $data['page_title'] = "Proveedores";
                $data['page_name'] = "proveedores";
                $data['data'] = $this->getSuppliers();
                $data['app'] = "functions_proveedores.js";
                $this->views->getView($this,"proveedores",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        /*******************Suppliers**************************** */
        public function getSuppliers($option=null,$params=null){
            if($_SESSION['permitsModule']['r']){
                $html="";
                $request="";
                if($option == 1){
                    $request = $this->model->search($params);
                }else if($option == 2){
                    $request = $this->model->sort($params);
                }else{
                    $request = $this->model->selectSuppliers();
                }
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 

                        $btnEdit="";
                        $btnDelete="";
                        if($_SESSION['permitsModule']['u']){
                            $btnEdit = '<button class="btn btn-success m-1" type="button" title="Edit" data-id="'.$request[$i]['idsupplier'].'" name="btnEdit"><i class="fas fa-pencil-alt"></i></button>';
                        }
                        if($_SESSION['permitsModule']['d']){
                            $btnDelete = '<button class="btn btn-danger m-1" type="button" title="Delete" data-id="'.$request[$i]['idsupplier'].'" name="btnDelete"><i class="fas fa-trash-alt"></i></button>';
                        }
                        $html.='
                            <tr class="item"">
                                <td data-label="NIT: ">'.$request[$i]['nit'].'</td>
                                <td data-label="Nombre: ">'.$request[$i]['name'].'</td>
                                <td data-label="Correo: ">'.$request[$i]['email'].'</td>
                                <td data-label="Teléfono: ">'.$request[$i]['phone'].'</td>
                                <td data-label="Dirección: ">'.$request[$i]['address'].'</td>
                                <td class="item-btn">'.$btnEdit.$btnDelete.'</td>
                            </tr>
                        ';
                    }
                    $arrResponse = array("status"=>true,"data"=>$html);
                }else{
                    $html = '<tr><td colspan="20">No hay datos</td></tr>';
                    $arrResponse = array("status"=>false,"data"=>$html);
                }
            }else{
                header("location: ".base_url());
                die();
            }
            
            return $arrResponse;
        }
        public function getSupplier(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    if(empty($_POST)){
                        $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $id = intval($_POST['idSupplier']);
                        $request = $this->model->selectSupplier($id);
                        if(!empty($request)){
                            $arrResponse = array("status"=>true,"data"=>$request);
                        }else{
                            $arrResponse = array("status"=>false,"msg"=>"Error, intenta de nuevo"); 
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
        public function setSupplier(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    if(empty($_POST['txtName']) || empty($_POST['txtEmail']) || empty($_POST['txtPhone'])){
                        $arrResponse = array("status" => false, "msg" => 'Error de datos');
                    }else{ 
                        $id = intval($_POST['idSupplier']);
                        $strName = ucwords(strClean($_POST['txtName']));
                        $strEmail = strtolower(strClean($_POST['txtEmail']));
                        $strPhone = strClean($_POST['txtPhone']);
                        $strNit = strClean($_POST['txtNit']);
                        $strAddress = strClean($_POST['txtAddress']);
                        if($id == 0){
                            if($_SESSION['permitsModule']['w']){
                                $option = 1;
                                $request= $this->model->insertSupplier($strNit,$strName,$strEmail,$strPhone,$strAddress);
                            }
                        }else{
                            if($_SESSION['permitsModule']['u']){
                                $option = 2;
                                $request = $this->model->updateSupplier($id,$strNit,$strName,$strEmail,$strPhone,$strAddress);
                            }
                        }
                        if($request > 0 ){
                            if($option == 1){
                                $arrResponse = $this->getSuppliers();
                                $arrResponse['msg'] = 'Datos guardados.';
                            }else{
                                $arrResponse = $this->getSuppliers();
                                $arrResponse['msg'] = 'Datos actualizados.';
                            }
                        }else if($request =="exists"){
                            $arrResponse = array('status' => false, 'msg' => '¡Atención! el proveedor ya está registrado, pruebe con otro.');
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
        public function delSupplier(){
            if($_SESSION['permitsModule']['d']){

                if($_POST){
                    if(empty($_POST['idSupplier'])){
                        $arrResponse=array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $id = intval($_POST['idSupplier']);
                        $request = $this->model->deleteSupplier($id);

                        if($request=="ok"){
                            $arrResponse = array("status"=>true,"msg"=>"Se ha eliminado.","data"=>$this->getSuppliers()['data']);
                        }else{
                            $arrResponse = array("status"=>false,"msg"=>"No es posible eliminar, intenta de nuevo.");
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
        public function getSelectSuppliers(){
            if($_SESSION['permitsModule']['r']){
                $html ='<option value ="0">Seleccione</option>';
                $request = $this->model->selectSuppliers();
                if(!empty($request)){
                    for ($i=0; $i < count($request); $i++) { 
                        $html.='<option value="'.$request[$i]['idsupplier'].'">'.$request[$i]['name'].'</option>';
                    }
                }
                return $html;
            } 
            die();
        }
        public function search($params){
            if($_SESSION['permitsModule']['r']){
                $search = strClean($params);
                $arrResponse = $this->getSuppliers(1,$params);
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function sort($params){
            if($_SESSION['permitsModule']['r']){
                $params = intval($params);
                $arrResponse = $this->getSuppliers(2,$params);
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        /*******************Purchases**************************** */
        public function setPurchase(){
            if($_SESSION['permitsModule']['w']){
                if($_POST){
                    if(empty($_POST['arrProducts']) || empty($_POST['total']) || empty($_POST['date'])){
                        $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $idSupplier = intval($_POST['idSupplier']);
                        $arrProducts = $_POST['arrProducts'];
                        $total = intval($_POST['total']);
                        $strDate = strClean($_POST['date']);
                        $request = $this->model->insertPurchase($idSupplier,$arrProducts,$total,$strDate);
                        if($request > 0){
                            $arrResponse = array("status"=>true,"msg"=>"La compra se ha registrado con éxito","data"=>$this->getPurchases()['data']);
                        }else{
                            $arrResponse = array("status"=>false,"msg"=>"Ha ocurrido un error, inténtelo de nuevo");
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }
        public function getPurchases($option=null,$params=null){
            if($_SESSION['permitsModule']['r']){
                $html="";
                $request="";
                if($option == 1){
                    $request = $this->model->searchP($params);
                }else{
                    $request = $this->model->selectPurchases();
                }
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 

                        $btnView = '<a href="'.base_url().'/compras/compra/'.$request[$i]['idpurchase'].'"class="btn btn-info m-1 text-white" type="button" title="Watch" name="btnView"><i class="fas fa-eye"></i></a>';
                        $btnDelete="";
                        if($_SESSION['permitsModule']['d']){
                            $btnDelete = '<button class="btn btn-danger m-1 text-white" type="button" title="Delete" data-id="'.$request[$i]['idpurchase'].'" name="btnDelete"><i class="fas fa-trash-alt"></i></button>';
                        }
                        $html.='
                            <tr class="item"">
                                <td data-label="Id: ">'.$request[$i]['idpurchase'].'</td>
                                <td data-label="Proveedor: ">'.$request[$i]['name'].'</td>
                                <td data-label="Total: ">'.formatNum($request[$i]['total'],false).'</td>
                                <td data-label="Fecha: ">'.$request[$i]['date'].'</td>
                                <td class="item-btn">'.$btnView.$btnDelete.'</td>
                            </tr>
                        ';
                    }
                    $arrResponse = array("status"=>true,"data"=>$html);
                }else{
                    $html = '<tr><td colspan="20">No hay datos</td></tr>';
                    $arrResponse = array("status"=>false,"data"=>$html);
                }
            }else{
                header("location: ".base_url());
                die();
            }
            
            return $arrResponse;
        }
        public function getPurchase($id){
            if($_SESSION['permitsModule']['r']){
                $request = $this->model->selectPurchase($id);
                return $request;
            }
            die();
        }
        public function searchPurchase($params){
            if($_SESSION['permitsModule']['r']){
                $search = strClean($params);
                $arrResponse = $this->getPurchases(1,$params);
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
        }
        public function delPurchase(){
            if($_SESSION['permitsModule']['d']){
                if($_POST){
                    if(empty($_POST['idPurchase'])){
                        $arrResponse=array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $id = intval($_POST['idPurchase']);
                        $request = $this->model->deletePurchase($id);
                        if($request=="ok"){
                            $arrResponse = array("status"=>true,"msg"=>"Se ha eliminado.","data"=>$this->getPurchases()['data']);
                        }else{
                            $arrResponse = array("status"=>false,"msg"=>"No es posible eliminar, intenta de nuevo.");
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

    }
?>