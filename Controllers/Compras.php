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
                $data['panelapp'] = "functions_compras.js";
                $this->views->getView($this,"compras",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function creditos(){
            if($_SESSION['permitsModule']['r']){
                $data['page_tag'] = "compras";
                $data['page_title'] = "Compras por crédito | Compras";
                $data['page_name'] = "compras";
                $data['panelapp'] = "functions_compras_creditos.js";
                $this->views->getView($this,"creditos",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function detalles(){
            if($_SESSION['permitsModule']['r']){
                $data['page_tag'] = "compras";
                $data['page_title'] = "Detalles de compras | Compras";
                $data['page_name'] = "compras";
                $data['panelapp'] = "functions_compras_detalles.js";
                $this->views->getView($this,"detalles",$data);
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
                        $status="";
                        if($request[$i]['status']==1){
                            $status='<span class="badge me-1 bg-success">Pagado</span>';
                        }else if($request[$i]['status'] == 2){
                            $status='<span class="badge me-1 bg-danger">Anulado</span>';
                        }else{
                            $status='<span class="badge me-1 bg-warning text-black">Crédito</span>';
                        }
                        if($_SESSION['permitsModule']['d'] && $request[$i]['status']!=2){
                            $btnDelete = '<button class="btn btn-danger m-1 text-white" type="button" title="Anular" onclick="deleteItem('.$request[$i]['idpurchase'].')" ><i class="fas fa-trash-alt"></i></button>';
                        }
                        $request[$i]['status'] = $status;
                        $request[$i]['format_total'] = formatNum($request[$i]['total']);
                        $request[$i]['options'] = $btnView.$btnDelete;
                        $request[$i]['format_pendent'] = formatNum($request[$i]['total_pendent']);
                        $request[$i]['actual_user'] = $_SESSION['userData']['firstname']." ".$_SESSION['userData']['lastname'];
                        $request[$i]['id_actual_user'] = $_SESSION['userData']['idperson'];
                    }
                }
                echo json_encode($request,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function getCreditPurchases(){
            if($_SESSION['permitsModule']['r']){
                $request = $this->model->selectCreditPurchases();
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
                            $btnAdvance = '<button class="btn btn-success m-1 text-white" type="button" title="Abonar" onclick="advanceItem('.$request[$i]['idpurchase'].')"><i class="fas fa-hand-holding-usd"></i></button>';
                            $status='<span class="badge me-1 bg-warning text-black">Crédito</span>';
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
        public function getDetailPurchases(){
            if($_SESSION['permitsModule']['r']){
                $request = $this->model->selectDetailPurchases();
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 
                        $request[$i]['price_purchase'] = formatNum($request[$i]['price_purchase']);
                        $request[$i]['price_discount'] = formatNum($request[$i]['price_discount']);
                        $request[$i]['subtotal'] = formatNum($request[$i]['subtotal']);
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
            if($_SESSION['permitsModule']['r']){
                $strSearch = strClean($_POST['search']);
                $intPerPage = intval($_POST['perpage']);
                $intPageNow = intval($_POST['page']);
                $request = $this->model->selectProducts($strSearch,$intPerPage,$intPageNow);
                $arrProducts = $request['products'];
                $intTotalPages = $request['pages'];
                $total = $this->model->selectTotalInventory($strSearch);
                $arrData = array(
                    "html"=>$this->getInventoryHtml($arrProducts,$intTotalPages,$intPageNow),
                    "total_records"=>$total,
                    "data"=>$arrProducts
                );
                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function getInventoryHtml(array $data,int $pages,$page){
            $maxButtons = 4;
            $totalPages = $pages;
            $startPage = max(1, $page - floor($maxButtons / 2));
            if ($startPage + $maxButtons - 1 > $totalPages) {
                $startPage = max(1, $totalPages - $maxButtons + 1);
            }
            $html ="";
            $htmlPages = '
                <li class="page-item">
                    <button type="button" class="page-link text-secondary"  onclick="getProducts(1)" aria-label="First">
                        <span aria-hidden="true"><i class="fas fa-angle-double-left"></i></span>
                    </button>
                </li>
                <li class="page-item">
                    <button type="button" class="page-link text-secondary"  onclick="getProducts('.max(1, $page-1).')" aria-label="Previous">
                        <span aria-hidden="true"><i class="fas fa-angle-left"></i></span>
                    </button>
                </li>
            ';
            for ($i = $startPage; $i < min($startPage + $maxButtons, $totalPages + 1); $i++) {
                $htmlPages .= '<li class="page-item">
                    <button type="button" class="page-link  '.($i == $page ? ' bg-primary text-white' : 'text-secondary').'" onclick="getProducts('.$i.')">'.$i.'</button>
                </li>';
            }
            foreach ($data as $pro) {
                $stock = $pro['is_stock'] ? $pro['stock'] : "N/A";
                $html.='
                    <tr role="button" onclick="addProduct('.$pro['id'].','."'".$pro['variant_name']."'".','.$pro['product_type'].')">
                        <td data-title="Portada" class="text-center"><img src="'.$pro['url'].'" height="50"  width="50"></td>
                        <td data-title="Stock" class="text-center">'.$stock.'</td>
                        <td data-title="Referencia">'.$pro['reference'].'</td>
                        <td data-title="Artículo">'.$pro['name'].'</td>
                        <td data-title="Precio" class="text-end">'.$pro['price_purchase_format'].'</td>
                    </tr>
                ';
            }
            $htmlPages .= '
                <li class="page-item">
                    <button type="button" class="page-link text-secondary"  onclick="getProducts('.min($totalPages, $page+1).')" aria-label="Next">
                        <span aria-hidden="true"><i class="fas fa-angle-right"></i></span>
                    </button>
                </li>
                <li class="page-item">
                    <button type="button" class="page-link text-secondary"  onclick="getProducts('.($pages).')" aria-label="Last">
                        <span aria-hidden="true"><i class="fas fa-angle-double-right"></i></span>
                    </button>
                </li>
            ';
            return array("products"=>$html,"pages"=>$htmlPages);
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