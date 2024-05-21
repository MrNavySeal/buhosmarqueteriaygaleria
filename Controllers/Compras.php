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
                $data['page_title'] = "Historial de compras";
                $data['page_name'] = "compras";
                $data['panelapp'] = "functions_compras.js";
                $this->views->getView($this,"compras",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function compra(){
            if($_SESSION['permitsModule']['w']){
                $data['page_tag'] = "compras";
                $data['page_title'] = "Nueva compra";
                $data['page_name'] = "compras";
                $data['panelapp'] = "functions_compra.js";
                $this->views->getView($this,"compra",$data);
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
                $data['panelapp'] = "functions_proveedores.js";
                $this->views->getView($this,"proveedores",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        /*******************Suppliers**************************** */
        public function getSuppliers(){
            if($_SESSION['permitsModule']['r']){
                $request = $this->model->selectSuppliers();
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
            }
            die();
        }
        public function setSupplier(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    if(empty($_POST['txtName']) || empty($_POST['txtPhone'])){
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
                                $arrResponse = array("status"=>true,"msg"=>"Datos guardados");
                            }else{
                                $arrResponse = array("status"=>true,"msg"=>"Datos actualizados");
                            }
                        }else if($request =="exists"){
                            $arrResponse = array('status' => false, 'msg' => '¡Atención! el proveedor ya está registrado, pruebe con otro.');
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
                    if(empty($_POST['idSupplier'])){
                        $arrResponse=array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $id = intval($_POST['idSupplier']);
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
        /*******************Purchases**************************** */
        public function setPurchase(){
            if($_SESSION['permitsModule']['w']){
                if($_POST){
                    if(empty($_POST['id'])){
                        $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $strDate = $_POST['strDate'] == "" ? date("Y-m-d") : strClean($_POST['strDate']);
                        $data = array(
                            "id"=>intval($_POST['id']),
                            "date"=>$strDate,
                            "code_bill"=>strClean($_POST['strCode']),
                            "type"=>strClean($_POST['paymentList']),
                            "note"=>strClean($_POST['strNote']),
                            "products"=>json_decode($_POST['products'],true),
                            "total"=>json_decode($_POST['total'],true)
                        );
                        $request = $this->model->insertPurchase($data);
                        if($request > 0){
                            $arrResponse = array("status"=>true,"msg"=>"La compra se ha registrado con éxito");
                        }else{
                            $arrResponse = array("status"=>false,"msg"=>"Ha ocurrido un error, inténtelo de nuevo");
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }
        public function getPurchases(){
            if($_SESSION['permitsModule']['r']){
                $request = $this->model->selectPurchases();
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 
                        $btnView = '<button class="btn btn-info m-1 text-white" type="button" title="Ver" onclick="viewItem('.$request[$i]['idpurchase'].')"><i class="fas fa-eye"></i></button>';
                        $btnDelete="";
                        $btnAdvance="";
                        $status="";
                        if($request[$i]['status']==1){
                            $status='<span class="badge me-1 bg-success">Pagado</span>';
                        }else if($request[$i]['status'] == 2){
                            $status='<span class="badge me-1 bg-danger">Anulado</span>';
                        }else{
                            $status='<span class="badge me-1 bg-warning">Crédito</span>';
                        }
                        if($request[$i]['type']=="credito"){
                            $btnAdvance = '<button class="btn btn-success m-1 text-white" type="button" title="Abonar" onclick="advanceItem('.$request[$i]['idpurchase'].')"><i class="fas fa-hand-holding-usd"></i></button>';
                        }
                        if($_SESSION['permitsModule']['d'] && $request[$i]['status']!=2){
                            $btnDelete = '<button class="btn btn-danger m-1 text-white" type="button" title="Anular" onclick="deleteItem('.$request[$i]['idpurchase'].')" ><i class="fas fa-trash-alt"></i></button>';
                        }
                        $request[$i]['status'] = $status;
                        $request[$i]['format_total'] = formatNum($request[$i]['total']);
                        $request[$i]['options'] = $btnView.$btnAdvance.$btnDelete;
                        $request[$i]['format_pendent'] = formatNum($request[$i]['total_pendent']);
                        $request[$i]['actual_user'] = $_SESSION['userData']['firstname']." ".$_SESSION['userData']['lastname'];
                        $request[$i]['id_actual_user'] = $_SESSION['userData']['idperson'];
                    }
                }
                echo json_encode($request,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function getPurchase($id){
            if($_SESSION['permitsModule']['r']){
                $request = $this->model->selectPurchase($id);
                return $request;
            }
            die();
        }
        public function delPurchase(){
            if($_SESSION['permitsModule']['d']){
                if($_POST){
                    if(empty($_POST['id'])){
                        $arrResponse=array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $id = intval($_POST['id']);
                        $request = $this->model->deletePurchase($id);
                        if($request=="ok"){
                            $arrResponse = array("status"=>true,"msg"=>"La factura ha sido anulada correctamente.");
                        }else{
                            $arrResponse = array("status"=>false,"msg"=>"No es posible anular, intenta de nuevo.");
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }
        /*******************Advance**************************** */
        public function setAdvance(){
            if($_SESSION['permitsModule']['u']){
                if($_POST){
                    if(empty($_POST['id'])){
                        $arrResponse=array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $id = intval($_POST['id']);
                        $data = json_decode($_POST['data'],true);
                        $isSuccess = intval($_POST['is_success']);
                        if(is_array($data)){
                            $request = $this->model->insertAdvance($id,$data,$isSuccess);
                            if($request>0){
                                $arrResponse = array("status"=>true,"msg"=>"La factura ha sido abonada correctamente.");
                            }else{
                                $arrResponse = array("status"=>false,"msg"=>"No es posible abonar, intenta de nuevo.");
                            }
                        }else{
                            $arrResponse = array("status"=>false,"msg"=>"El tipo de dato es incorrecto.");
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }
        /*************************Products methods*******************************/
        public function getProducts(){
            if($_SESSION['permitsModule']['w']){
                $search="";
                if($_POST['search']){
                    $search = strClean($_POST['search']);
                }
                $request = $this->model->selectProducts($search);
                
                $html="";
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 
                        $request[$i]['stock'] = !$request[$i]['is_stock'] ? "N/A" : $request[$i]['stock'];
                        $variant = $request[$i]['product_type'] == 1 ? "Desde " : "";
                        $request[$i]['format_purchase'] = $variant.formatNum($request[$i]['price_purchase'] != null ? $request[$i]['price_purchase'] : 0);
                        $html.='
                            <tr>
                                <td>'.$request[$i]['stock'].'</td>
                                <td>'.$request[$i]['name'].'</td>
                                <td>'.$request[$i]['format_purchase'].'</td>
                                <td>
                                    <button type="button" class="btn btn-primary" onclick="getProduct(this,'.$request[$i]['idproduct'].')"><i class="fas fa-plus"></i></button>
                                </td>
                            </tr>
                        ';
                    }
                }
                echo json_encode($html,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function getProduct(){
            if($_SESSION['permitsModule']['w']){
                if($_POST['id']){
                    $id = intval($_POST['id']);
                    $request = $this->model->selectProduct($id);
                    if(!empty($request)){
                        $arrResponse = array("status"=>true,"data"=>$request);
                    }else{
                        $arrResponse = array("status"=>false,"msg"=>"El artículo no existe");
                    }
                }else{
                    $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function getProductVariant(){
            if($_SESSION['permitsModule']['w']){
                if($_POST){
                    $name = strClean($_POST['variant']);
                    $request = $this->model->selectProductVariant($name);
                    if(!empty($request)){
                        $arrResponse = array("status"=>true,"data"=>$request);
                    }else{
                        $arrResponse = array("status"=>false,"msg"=>"El artículo no existe");
                    }
                }else{
                    $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }

    }
?>