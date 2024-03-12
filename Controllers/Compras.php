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
                $data['panelapp'] = "functions_compras.js";
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
                $data['panelapp'] = "functions_proveedores.js";
                $this->views->getView($this,"proveedores",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function almacen(){
            if($_SESSION['permitsModule']['r']){
                $data['page_tag'] = "Compras | Almacén";
                $data['page_title'] = "Compras | Almacén";
                $data['page_name'] = "producto";
                $data['suppliers'] = $this->model->selectSuppliers();
                $data['panelapp'] = "functions_storage.js";
                $this->views->getView($this,"almacen",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        /*******************Suppliers**************************** */
        public function getSuppliers(){
            if($_SESSION['permitsModule']['r']){
                $request = $this->model->selectSuppliers();
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 

                        $btnEdit="";
                        $btnDelete="";
                        if($_SESSION['permitsModule']['u']){
                            $btnEdit = '<button class="btn btn-success m-1" type="button" title="Editar" onclick="editItem('.$request[$i]['idsupplier'].')"><i class="fas fa-pencil-alt"></i></button>';
                        }
                        if($_SESSION['permitsModule']['d']){
                            $btnDelete = '<button class="btn btn-danger m-1" type="button" title="Eliminar" onclick="deleteItem('.$request[$i]['idsupplier'].')"><i class="fas fa-trash-alt"></i></button>';
                        }
                        $request[$i]['options'] = $btnEdit.$btnDelete;
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
                    if(empty($_POST['arrProducts']) || empty($_POST['total'])){
                        $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $idSupplier = intval($_POST['idSupplier']);
                        $arrProducts = $_POST['arrProducts'];
                        $total = intval($_POST['total']);
                        $strDate = strClean($_POST['date']);
                        $request = $this->model->insertPurchase($idSupplier,$arrProducts,$total,$strDate);
                        dep($request);exit;
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
                        $btnView = '<a href="'.base_url().'/compras/compra/'.$request[$i]['idpurchase'].'"class="btn btn-info m-1 text-white" type="button" title="Watch" name="btnView"><i class="fas fa-eye"></i></a>';
                        $btnDelete="";
                        if($_SESSION['permitsModule']['d']){
                            $btnDelete = '<button class="btn btn-danger m-1 text-white" type="button" title="Delete" onclick="deleteItem('.$request[$i]['idpurchase'].')" ><i class="fas fa-trash-alt"></i></button>';
                        }
                        $request[$i]['options'] = $btnView.$btnDelete;
                        $request[$i]['total'] = formatNum($request[$i]['total'],false);
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
                    if(empty($_POST['idPurchase'])){
                        $arrResponse=array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $id = intval($_POST['idPurchase']);
                        $request = $this->model->deletePurchase($id);
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
        /*************************Products methods*******************************/
        public function setProduct(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    if(empty($_POST['suppList']) || empty($_POST['typeList']) || empty($_POST['txtName'])){
                        $arrResponse = array("status" => false, "msg" => 'Error de datos');
                    }else{ 
                        $id = intval($_POST['id']);
                        $strName = ucwords(strClean($_POST['txtName']));
                        $strReference = strtoupper(strClean($_POST['txtReference']));
                        $intSupp = intval($_POST['suppList']);
                        $intCost = intval($_POST['txtCost']);
                        $intStatus = intval($_POST['statusList']);
                        $intImport = intval($_POST['typeList']);
                        if($id == 0){
                            if($_SESSION['permitsModule']['w']){
                                $option = 1;
                                $request= $this->model->insertProduct($strReference,$strName,$intSupp,$intCost,$intImport,$intStatus);
                            }
                        }else{
                            if($_SESSION['permitsModule']['u']){
                                $option = 2;
                                $request= $this->model->updateProduct($id,$strReference,$strName,$intSupp,$intCost,$intImport,$intStatus);
                            }
                        }
                        if($request > 0 ){
                            if($option == 1){
                                $arrResponse = array("status"=>true,"msg"=>"Datos guardados.");
                            }else{
                                $arrResponse = array("status"=>true,"msg"=>"Datos actualizados.");
                            }
                        }else if($request ="exists"){
                            $arrResponse = array("status" => false, "msg" => 'El producto ya existe para este proveedor, intente con otro.');
                        }else{
                            $arrResponse = array("status" => false, "msg" => 'No es posible guardar los datos.');
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
			die();
        }
        public function getProducts(){
            if($_SESSION['permitsModule']['r']){
                $request = $this->model->selectProducts();
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 
                        $btnEdit="";
                        $btnDelete="";
                        $status="";
                        if($_SESSION['permitsModule']['u']){
                            $btnEdit = '<button class="btn btn-success m-1" type="button" title="Edit" onclick="editItem('.$request[$i]['id_storage'].')"><i class="fas fa-pencil-alt"></i></button>';
                        }
                        if($_SESSION['permitsModule']['d']){
                            $btnDelete = '<button class="btn btn-danger m-1" type="button" title="Delete" onclick="deleteItem('.$request[$i]['id_storage'].')"><i class="fas fa-trash-alt"></i></button>';
                        }
                        if($request[$i]['status']==1){
                            $status='<span class="badge me-1 bg-success">Activo</span>';
                        }else{
                            $status='<span class="badge me-1 bg-danger">Inactivo</span>';
                        }
                        $request[$i]['status'] = $status;
                        $request[$i]['options'] = $btnEdit.$btnDelete;
                        $request[$i]['cost'] = formatNum($request[$i]['cost']);
                        $request[$i]['costiva'] = formatNum($request[$i]['costiva']);
                        $request[$i]['costtotal'] = formatNum($request[$i]['costtotal']);
                    }
                }
                echo json_encode($request,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function getProduct(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    if(empty($_POST)){
                        $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $id = intval($_POST['id']);
                        $request = $this->model->selectProduct($id);
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
        public function delProduct(){
            if($_SESSION['permitsModule']['d']){
                if($_POST){
                    if(empty($_POST['id'])){
                        $arrResponse=array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $id = intval($_POST['id']);
                        $request = $this->model->deleteProduct($id);
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
        public function getSelectProducts(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    $id = intval($_POST['id']);
                    $html ='<option value ="0">Seleccione</option>';
                    $request = $this->model->selectProducts($id);
                    if(!empty($request)){
                        for ($i=0; $i < count($request); $i++) { 
                            $html.='<option value="'.$request[$i]['id_storage'].'">'.$request[$i]['name'].'</option>';
                        }
                    }
                    echo json_encode($html,JSON_UNESCAPED_UNICODE);
                }
            } 
            die();
        }
        public function getSelectProduct(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    $id="";
                    $qty = floatval($_POST['qty']);
                    $type = intval($_POST['type']);
                    $html ='';
                    $ivaText = "0%";
                    $iva = 0;
                    $valueIva = 0;
                    $subtotal = 0;
                    $discount = 0;
                    $total = 0;
                    $request ="";
                    $reference="";
                    $name="";
                    $cost = 0;
                    if($type == 1){
                        $id = intval($_POST['id']);
                        $discount = intval($_POST['discount'])/100;
                        $request = $this->model->selectProduct($id);
                        if(!empty($request)){
                            if($request['import'] == 2){
                                $ivaText = "5%";
                                $iva = 0.05;
                            }else if($request['import'] == 3){
                                $ivaText="19%";
                                $iva = 0.19;
                            }
                            $cost = $request['cost'];
                            $valueIva = round(intval($cost * $iva)/10)*10;
                            $iva = round((intval($cost * $iva)*$qty));
                            $subtotal = $cost*$qty;
                            $discount = round((intval(($cost * $qty)*($discount)))/10)*10;
                            $total = round((($subtotal-$discount)+$iva)/100)*100;
                            $reference = $request['reference'];
                            $name = $request['name'];
                        }
                    }else{
                        $name = ucwords(strClean($_POST['name']));
                        $cost = intval($_POST['price']);
                        $subtotal = $cost*$qty;
                        $total = $subtotal;
                    }
                    
                    $html = '
                    <td>'.$reference.'</td>
                    <td>'.$name.'</td>
                    <td>'.$qty.'</td>
                    <td>'.formatNum($cost).'</td>
                    <td>'.$ivaText.'</td>
                    <td>'.formatNum($valueIva).'</td>
                    <td>'.formatNum($subtotal).'</td>
                    <td><button class="btn btn-danger m-1" type="button" title="Delete" onclick="delProduct(this.parentElement.parentElement)"><i class="fas fa-trash-alt"></i></button></td>
                    ';

                    $arrData = array(
                        "reference"=>$reference,
                        "name"=>$name,
                        "qty"=>$qty,
                        "cost"=>$cost,
                        "ivatext"=>$ivaText,
                        "valueiva"=>$valueIva,
                        "type"=>$type,
                        "id"=> $id != "" ? $id : "",
                        "status"=>true,
                        "data"=>$html,
                        "subtotal"=>$subtotal,
                        "total"=>$total,
                        "discount"=>$discount,
                        "iva"=>$iva
                    );
                    echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                }
            } 
            die();
        }

    }
?>