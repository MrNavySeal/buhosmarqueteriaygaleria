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
                $data['data'] = $this->getSuppliers();
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
                $data['data'] = $this->getProducts();
                $data['suppliers'] = $this->model->selectSuppliers();
                $data['panelapp'] = "functions_storage.js";
                $this->views->getView($this,"almacen",$data);
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
                    if(empty($_POST['arrProducts']) || empty($_POST['total'])){
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
                                $arrResponse = $this->getProducts();
                                $arrResponse['status']=true;
                                $arrResponse['msg'] = 'Datos guardados.';
                            }else{
                                $arrResponse['status']=true;
                                $arrResponse = $this->getProducts();
                                $arrResponse['msg'] = 'Datos actualizados.';
                            }
                        }else if($request ="exists"){
                            $arrResponse = array("status" => false, "msg" => 'El producto ya existe para este proveedor, intente con otro.');
                        }else{
                            $arrResponse = array("status" => false, "msg" => 'No es posible guardar los datos.');
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }else{
                header("location: ".base_url());
            }
			die();
        }
        public function getProducts($option=null,$params=null){
            if($_SESSION['permitsModule']['r']){
                $html="";
                $request="";
                if($option == 1){
                    $request = $this->model->searchS($params);
                }else{
                    $request = $this->model->selectProducts();
                }
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 
                        
                        $btnEdit="";
                        $btnDelete="";
                        $status="";
                        if($_SESSION['permitsModule']['u']){
                            $btnEdit = '<button class="btn btn-success m-1" type="button" title="Edit" data-id="'.$request[$i]['id_storage'].'" name="btnEdit"><i class="fas fa-pencil-alt"></i></button>';
                        }
                        if($_SESSION['permitsModule']['d']){
                            $btnDelete = '<button class="btn btn-danger m-1" type="button" title="Delete" data-id="'.$request[$i]['id_storage'].'" name="btnDelete"><i class="fas fa-trash-alt"></i></button>';
                        }
                        if($request[$i]['status']==1){
                            $status='<span class="badge me-1 bg-success">Activo</span>';
                        }else{
                            $status='<span class="badge me-1 bg-danger">Inactivo</span>';
                        }

                        $html.='
                            <tr class="item">
                                <td data-label="ID: ">'.$request[$i]['id_storage'].'</td>
                                <td data-label="Referencia: ">'.$request[$i]['reference'].'</td>
                                <td data-label="Nombre: ">'.$request[$i]['name'].'</td>
                                <td data-label="Proveedor: ">'.$request[$i]['supplier'].'</td>
                                <td data-label="Precio: ">'.formatNum($request[$i]['cost']).'</td>
                                <td data-label="IVA: ">'.$request[$i]['iva'].'</td>
                                <td data-label="Precio IVA: ">'.formatNum($request[$i]['costiva']).'</td>
                                <td data-label="Precio Total: ">'.formatNum($request[$i]['costtotal']).'</td>
                                <td data-label="Estado: ">'.$status.'</td>
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
            }else{
                header("location: ".base_url());
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
                            $arrResponse = array("status"=>true,"msg"=>"Se ha eliminado.","data"=>$this->getProducts()['data']);
                        }else{
                            $arrResponse = array("status"=>false,"msg"=>"No es posible eliminar, intenta de nuevo.");
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }else{
                header("location: ".base_url());
            }
            die();
        }
        public function searchS($params){
            if($_SESSION['permitsModule']['r']){
                $search = strClean($params);
                $arrResponse = $this->getProducts(1,$params);
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
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
                    $qty = intval($_POST['qty']);
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