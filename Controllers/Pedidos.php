<?php
    require_once("Controllers/Inventario.php");
    class Pedidos extends Controllers{
        private $objProduct;
        public function __construct(){
            
            session_start();
            if(empty($_SESSION['login'])){
                header("location: ".base_url());
                die();
            }
            parent::__construct();
            sessionCookie();
            getPermits(6);
        }

        public function pedidos(){
            if($_SESSION['permitsModule']['r']){
                $data['page_tag'] = "Pedidos";
                $data['page_title'] = "Pedidos";
                $data['page_name'] = "pedidos";
                //$data['data'] = $this->getOrders();
                $data['panelapp'] = "functions_orders.js";
                $this->views->getView($this,"pedidos",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function pos(){
            if($_SESSION['permitsModule']['r']){
                $data['page_tag'] = "Punto de venta";
                $data['page_title'] = "Punto de venta";
                $data['page_name'] = "punto de venta";
                $data['products'] = $this->getProducts();
                $data['tipos'] = $this->model->selectCategories();
                $data['panelapp'] = "functions_pos.js";
                $this->views->getView($this,"pos",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function pedido($idOrder){
            if($_SESSION['permitsModule']['r']){
                if(is_numeric($idOrder)){
                    $idPerson ="";
                    if($_SESSION['userData']['roleid'] == 2 ){
                        $idPerson= $_SESSION['idUser'];
                    }
                    $data['orderdata'] = $this->model->selectOrder($idOrder,$idPerson);
                    $data['orderdetail'] = $this->model->selectOrderDetail($idOrder);
                    if($data['orderdata']['coupon']!=""){
                        $data['cupon'] = $this->model->selectCouponCode($data['orderdata']['coupon']);
                    }
                    $data['page_tag'] = "Pedido";
                    $data['page_title'] = "Pedido";
                    $data['page_name'] = "pedido";
                    $data['company'] = getCompanyInfo();
                    //$data['app'] = "functions_orders.js";
                    $this->views->getView($this,"pedido",$data);
                }else{
                    header("location: ".base_url()."/pedidos");
                }
                
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function transaccion($idTransaction){
            if($_SESSION['permitsModule']['r']){
                $idPerson ="";
                if($_SESSION['userData']['roleid'] == 2 ){
                    $idPerson= $_SESSION['idUser'];
                }
                $data['transaction'] = $this->model->selectTransaction($idTransaction,$idPerson);
                $data['page_tag'] = "Transacción";
                $data['page_title'] = "Transacción";
                $data['page_name'] = "transaccion";
                $data['panelapp'] = "functions_orders.js";
                $this->views->getView($this,"transaccion",$data);
                
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function getOrder(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    $idOrder = intval($_POST['id']);
                    $data = $this->model->selectOrder($idOrder,"");
                    $suscription = !empty($data['suscription']) ? json_decode($data['suscription'],true) : "";
                    $status="";
                    $statusOrder="";
                    $payments="";
                    $pago ="";
                    $subTotal = 0;
                    $total = $data['amount'];
                    for ($i=0; $i < count(PAGO) ; $i++) { 
                        if($data['type'] == PAGO[$i]){
                            $payments.='<option value="'.$i.'" selected>'.PAGO[$i].'</option>';
                        }else{
                            $payments.='<option value="'.$i.'">'.PAGO[$i].'</option>';
                        }
                        $pago .='<option value="'.$i.'">'.PAGO[$i].'</option>';
                    }
                    for ($i=0; $i < count(STATUS) ; $i++) { 
                        if($data['statusorder'] == STATUS[$i]){
                            $statusOrder.='<option value="'.$i.'" selected>'.STATUS[$i].'</option>';
                        }else{
                            $statusOrder.='<option value="'.$i.'">'.STATUS[$i].'</option>';
                        }
                    }
                    $html='<tr>
                                <td class="fw-bold">Fecha</td>
                                <td class="fw-bold">Anticipo</td>
                                <td class="fw-bold">Tipo de pago</td>
                            </tr>
                            <tr>
                                <td><input type="date" class="form-control" id="subDate"></td>
                                <td><input type="number" class="form-control" id="subDebt" placeholder="Abono"></td>
                                <td><select class="form-control" aria-label="Default select example">'.$pago.'</select></td>
                                <td><button class="btn btn-primary" type="button" title="add" onclick="addSuscription()"><i class="fas fa-plus"></i></button></td>
                            </tr>';
                    

                    if($data['status'] == "pendent"){
                        $status = 2;
                    }else if($data['status'] == "approved"){
                        $status = 1;
                    }else{
                        $status = 3;
                    }

                    if(!empty($suscription)){
                        $accountSelect="";
                        $date ="";
                        for ($i=0; $i < count($suscription) ; $i++) { 
                            for ($j=0; $j < count(PAGO) ; $j++) { 
                                if($suscription[$i]['type'] == PAGO[$j]){
                                    $accountSelect.='<option value="'.$j.'" selected>'.PAGO[$j].'</option>';
                                }else{
                                    $accountSelect.='<option value="'.$j.'">'.PAGO[$j].'</option>';
                                }
                            }
                            $subTotal+= $suscription[$i]['debt'];
                            $html.='<tr class="itemAccount" data-total="'.$suscription[$i]['debt'].'">
                                        <td><input type="date" class="form-control" value="'.$suscription[$i]['date'].'"></td>
                                        <td><input type="number" disabled class="form-control" value="'.$suscription[$i]['debt'].'" placeholder="Abono"></td>
                                        <td><select class="form-control" aria-label="Default select example">'.$accountSelect.'</select></td>
                                        <td><button class="btn btn-danger" type="button" title="Delete" onclick="delSuscription(this.parentElement.parentElement)"><i class="fas fa-trash-alt"></i></button></td>
                                    </tr>';
                        }
                    }
                    $totalSus = $total-$subTotal;
                    $data['statusorder'] = $statusOrder;
                    $data['payments'] = $payments;
                    $data['status'] = $status;
                    $data['suscription'] = $html;
                    $data['totalDebt'] = '<tr data-total="'.$total.'">
                        <td class="text-end fw-bold">Saldo total:</td>
                        <td>'.formatNum($totalSus).'</td>
                    </tr>';
                    $arrResponse = array("status"=>true,"data"=>$data);
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function getOrders($option=null,$params=null){
            if($_SESSION['permitsModule']['r']){
                $idPersona = "";
                if($_SESSION['userData']['roleid'] == 2){
                    $idPersona = $_SESSION['idUser'];
                }
                $request = $this->model->selectOrders($idPersona);
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 

                        $btnView='<a href="'.base_url().'/pedidos/pedido/'.$request[$i]['idorder'].'" class="btn btn-info text-white m-1" type="button" title="Ver orden" name="btnView"><i class="fas fa-eye"></i></a>';
                        $btnWpp="";
                        $btnPdf='<a href="'.base_url().'/factura/generarFactura/'.$request[$i]['idorder'].'" target="_blank" class="btn btn-danger text-white m-1" type="button" title="Ver orden"><i class="fas fa-file-pdf"></i></a>';
                        $btnPaypal='';
                        $btnDelete ="";
                        $btnEdit ="";
                        $status="";
                        $statusOrder="";
                        if($request[$i]['type'] == "mercadopago"){
                            $btnPaypal = '<a href="'.base_url().'/pedidos/transaccion/'.$request[$i]['idtransaction'].'" class="btn btn-info m-1 text-white " type="button" title="Ver transacción" name="btnPaypal"><i class="fas fa-receipt"></i></a>';
                        }
                        if($request[$i]['status'] =="pendent"){
                            $status = '<span class="badge bg-warning text-white">pendiente</span>';
                        }else if($request[$i]['status'] =="approved"){
                            $status = '<span class="badge bg-success text-white">aprobado</span>';
                        }else if($request[$i]['status'] =="canceled"){
                            $status = '<span class="badge bg-danger text-white">cancelado</span>';
                        }
                        if($request[$i]['statusorder'] =="confirmado"){
                            $statusOrder = '<span class="badge bg-dark text-white">confirmado</span>';
                        }else if($request[$i]['statusorder'] =="en preparacion"){
                            $statusOrder = '<span class="badge bg-warning text-white">en preparacion</span>';
                        }else if($request[$i]['statusorder'] =="preparado"){
                            $statusOrder = '<span class="badge bg-info text-white">preparado</span>';
                        }else if($request[$i]['statusorder'] =="entregado"){
                            $statusOrder = '<span class="badge bg-success text-white">entregado</span>';
                        }else if($request[$i]['statusorder'] =="cancelado"){
                            $statusOrder = '<span class="badge bg-danger text-white">cancelado</span>';
                        }
                        
                        /*if($_SESSION['permitsModule']['d'] && $_SESSION['userData']['roleid'] == 1){
                            $btnDelete = '<button class="btn btn-danger text-white m-1" type="button" title="Delete" data-id="'.$request[$i]['idorder'].'" name="btnDelete"><i class="fas fa-trash-alt"></i></button>';
                        }*/
                        if($_SESSION['permitsModule']['u']){
                            $btnEdit = '<button class="btn btn-success text-white m-1" type="button" title="Edit" data-id="'.$request[$i]['idorder'].'" onclick="openModalOrder('.$request[$i]['idorder'].')"><i class="fas fa-pencil-alt"></i></button>';
                        }
                        if($_SESSION['userData']['roleid'] == 1 || $_SESSION['userData']['roleid'] == 3){
                            $btnWpp='<a href="https://wa.me/57'.$request[$i]['phone'].'?text=Buen%20dia%20'.$request[$i]['name'].'" class="btn btn-success text-white m-1" type="button" title="Whatsapp" target="_blank"><i class="fab fa-whatsapp"></i></a>';
                        }
                        $request[$i]['amount'] = formatNum($request[$i]['amount']);
                        $request[$i]['status'] = $status;
                        $request[$i]['statusorder'] = $statusOrder;
                        $request[$i]['options'] = $btnView.$btnWpp.$btnPdf.$btnPaypal.$btnEdit;
                    }
                }
                echo json_encode($request,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function getTransaction(string $idTransaction){
            if($_SESSION['permitsModule']['r'] && $_SESSION['userData']['roleid'] !=2){
                $idTransaction = strClean($idTransaction);
                $request = $this->model->selectTransaction($idTransaction,"");
                if(!empty($request)){
                    $arrResponse = array("status"=>true,"data"=>$request);
                }else{
                    $arrResponse = array("status"=>false,"msg"=>"Datos no encontrados.");
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function delOrder(){
            if($_SESSION['permitsModule']['d']){

                if($_POST){
                    if(empty($_POST['idOrder'])){
                        $arrResponse=array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $id = intval($_POST['idOrder']);
                        $request = $this->model->deleteOrder($id);

                        if($request=="ok"){
                            $arrResponse = array("status"=>true,"msg"=>"Se ha eliminado.","data"=>$this->getOrders()['data']);
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
        public function getProduct(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    if(empty($_POST)){
                        $arrResponse = array("status"=>false,"msg"=>"Data error");
                    }else{
                        $id = intval($_POST['idProduct']);
                        $request = $this->model->selectProduct($id);
                        if($request['discount']>0){
                            $request['price'] = $request['price'] - ($request['price'] * ($request['discount']/100));
                        }
                        $request['priceFormat'] = formatNum($request['price']);
                        $arrResponse = array("status"=>true,"data"=>$request);
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }else{
                header("location: ".base_url());
                die();
            }
            die();
        }
        /*
        public function addSuscription(){
            if($_SESSION['permitsModule']['u']){
                if($_POST){
                    $id = intval($_POST['id']);
                    $request = $this->model->selectOrder($idOrder,"");
                    $suscription = $request['suscription'];
                    $total = $request['amount'];
                    $subTotal = 0;
                    if(!empty($suscription)){
                        $suscription = json_decode($suscription,true);
                        for ($i=0; $i < count($suscription) ; $i++) { 
                            $subtotal+= $suscription[$i]['debt'];
                        }
                        if($subTotal == $tot)
                    }
                }
            }
            die();
        }*/

    /*************************POS methods*******************************/
        public function getProducts($option=null,$params=null){
            if($_SESSION['permitsModule']['r']){
                $html="";
                $request="";
                if($option == 1){
                    $request = $this->model->searchProducts($params);
                }else{
                    $request = $this->model->selectProducts();
                }
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 

                        $status="";
                        $btnView = '<a href="'.base_url().'/tienda/producto/'.$request[$i]['route'].'" target="_blank" class="btn btn-info m-1 text-white" title="Ver página"><i class="fas fa-eye"></i></a>';
                        $btnEdit="";
                        $btnDelete="";
                        $price = "";
                        $selectVariant ="";
                        $variant = "";
                        if($request[$i]['product_type'] == 2){
                            $variant ="Desde ";
                            $arrVariants = $request[$i]['variants'];
                            $htmlOption ="";
                            for ($j=0; $j < count($arrVariants); $j++) { 
                                $htmlOption .= 
                                '<option value="'.$arrVariants[$j]['id_product_variant'].'">'.$arrVariants[$j]['width'].'x'.$arrVariants[$j]['height'].'</option>';
                            }
                            $selectVariant = '<select class="form-control me-2" aria-label="Default select example">'.$htmlOption.'</select>';
                        }
                        if($request[$i]['discount']>0){
                            $price = '<span class="text-danger">'.$variant.formatNum($request[$i]['price']*(1-($request[$i]['discount']*0.01)),false).'</span>'.' <span class="text-secondary text-decoration-line-through">'.formatNum($request[$i]['price'],false).'</span>';
                            $discount = '<span class="text-danger">'.$request[$i]['discount'].'%</span>';
                        }else{
                            $price = $variant.formatNum($request[$i]['price'],false);
                            $discount = "0%";
                        }
                        if($_SESSION['permitsModule']['u']){
                            $btnEdit = '<a href="'.base_url().'/inventario/producto/'.$request[$i]['idproduct'].'" class="btn btn-success m-1 text-white" title="Editar" name="btnEdit"><i class="fas fa-pencil-alt"></i></a>';
                        }
                        if($_SESSION['permitsModule']['d']){
                            $btnDelete = '<button class="btn btn-danger m-1" type="button" title="Delete" data-id="'.$request[$i]['idproduct'].'" name="btnDelete"><i class="fas fa-trash-alt"></i></button>';
                        }
                        if($request[$i]['status']==1 && $request[$i]['stock']>0){
                            $status='<span class="badge me-1 bg-success">Activo</span>';
                        }else if($request[$i]['status']==2){
                            $status='<span class="badge me-1 bg-danger">Inactivo</span>';
                        }else{
                            $status='<span class="badge me-1 bg-warning">Agotado</span>';
                        }
                        $html.='
                            <tr class="item">
                                <td class="text-center">
                                    <img src="'.$request[$i]['image'].'" class="rounded">
                                </td>
                                <td data-label="Referencia: ">'.$request[$i]['reference'].'</td>
                                <td class="text-center">'.$request[$i]['name'].'</td>
                                <td data-label="Precio: ">'.$price.'</td>
                                <td data-label="Descuento: ">'.$discount.'</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-start align-items-center">
                                        '.$selectVariant.'
                                        <button type="button" class="btn btn-primary" onclick="addProduct('.$request[$i]['product_type'].','.$request[$i]['idproduct'].',this)"><i class="fas fa-plus"></i></button>
                                    </div>
                                </td>
                            </tr>
                        ';
                    }
                    $arrResponse = array("status"=>true,"data"=>$html);
                }else{
                    $html = '<tr><td colspan="5">No hay datos</td></tr>';
                    $arrResponse = array("status"=>false,"data"=>$html);
                }
            }else{
                header("location: ".base_url());
                die();
            }
            
            return $arrResponse;
        }
        public function searchProducts($params){
            if($_SESSION['permitsModule']['r']){
                $search = strClean($params);
                $arrResponse = $this->getProducts(1,$params);
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function searchCustomers($params){
            if($_SESSION['permitsModule']['r']){
                $search = strClean($params);
                $request = $this->model->searchCustomers($search);
                $html ="";
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 
                        $html .='
                        <button class="p-2 btn w-100 text-start" data-id="'.$request[$i]['idperson'].'" onclick="addCustom(this)">
                            <p class="m-0 fw-bold">'.$request[$i]['firstname'].' '.$request[$i]['lastname'].'</p>
                            <p class="m-0">CC/NIT: <span>'.$request[$i]['identification'].'</span></p>
                            <p class="m-0">Correo: <span>'.$request[$i]['email'].'</span></p>
                            <p class="m-0">Teléfono: <span>'.$request[$i]['phone'].'</span></p>
                        </button>
                        ';
                    }
                    $arrResponse = array("status"=>true,"data"=>$html);
                }else{
                    $arrResponse = array("status"=>false,"data"=>$html);
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function addCart(){
            //unset($_SESSION['arrPOS']);exit;
            //dep($_POST);exit;
            if($_SESSION['permitsModule']['w']){
                if($_POST){ 
                    $id = intval($_POST['idProduct']);
                    $qty = intval($_POST['txtQty']);
                    $topic = intval($_POST['topic']);
                    $productType = intval($_POST['productType']);
                    $productVariant = isset($_POST['variant']) ? intval($_POST['variant']) : null;
                    $qtyCart = 0;
                    $arrCart = array();
                    $valiQty =true;
                    $data = array();
                    $request = array();
                    $total = 0;
                    $variant = array();
                    $price = 0;
                    if($id != 0){
                        $request = $this->model->selectProduct($id,$productVariant);
                        $price = $request['price'];
                        $variant = $productType == 2 ? $request['variant'] : array();
                        
                        
                        if($request['discount']>0 && $productType == 1){
                            $price = $request['price'] - ($request['price']*($request['discount']/100));
                        }else if($request['discount']>0 && $productType == 2){
                            $variant['price'] = $variant['price'] -($variant['price']*($request['discount']/100));
                        }
                        $data = array("name"=>$request['name'],"image"=>$request['image'],"route"=>base_url()."/tienda/producto/".$request['route']);
                    }else{
                        $service = ucwords(strClean($_POST['txtService']));
                        $servicePrice = intval($_POST['intPrice']);
                        $data = array("name"=>$service,"image"=>media()."/images/uploads/category.jpg");
                    }
                    if(!empty($request) || $id == 0){
                        if($topic== 3){
                            $arrProduct = array(
                                "topic"=>3,
                                "id"=>0,
                                "name" => $service,
                                "qty"=>$qty,
                                "image"=>media()."/images/uploads/category.jpg",
                                "price" =>$servicePrice
                            );
                        }else{
                            $arrProduct = array(
                                "reference" =>$request['reference'],
                                "topic"=>2,
                                "producttype" => $productType,
                                "id"=>$id,
                                "name" => $request['name'],
                                "qty"=>$qty,
                                "image"=>$request['image'],
                                "price" =>$price,
                                "stock"=>$request['stock'],
                                "variant"=>$variant
                            );
                        }
                        if(isset($_SESSION['arrPOS'])){
                            $arrCart = $_SESSION['arrPOS'];
                            $currentQty = 0;
                            $flag = true;
                            
                            for ($i=0; $i < count($arrCart) ; $i++) { 
                                if($topic == 2){
                                    if($productType == 2){
                                        if($arrCart[$i]['id'] == $arrProduct['id']
                                        && $arrCart[$i]['variant']['id_product_variant'] == $arrProduct['variant']['id_product_variant']){
                                            $currentQty = $arrCart[$i]['qty'];
                                            $arrCart[$i]['qty']+= $qty;
                                            if($arrCart[$i]['qty'] > $request['variant']['stock']){
                                                $arrCart[$i]['qty'] = $currentQty;
                                                $arrResponse = array("status"=>false,"msg"=>"No hay suficientes unidades","data"=>$data);
                                                $flag = false;
                                                break;
                                            }else{
                                                $_SESSION['arrPOS'] = $arrCart;
                                                foreach ($_SESSION['arrPOS'] as $quantity) {
                                                    if($quantity['topic'] == 2){
                                                        if($quantity['producttype'] == 2){
                                                            $total+=$quantity['qty']*$quantity['variant']['price'];
                                                        }else{
                                                            $total+=$quantity['qty']*$quantity['price'];
                                                        }
                                                    }else{
                                                        $total+=$quantity['qty']*$quantity['price'];
                                                    }
                                                }
                                                $arrResponse = array("status"=>true,"msg"=>"Ha sido agregado a tu carrito.","total"=>formatNum($total),"value"=>floor($total),"data"=>$data);
                                            }
                                            $flag =false;
                                            break;
                                        }
                                    }else{
                                        if($arrCart[$i]['id'] == $arrProduct['id']){
                                            $currentQty = $arrCart[$i]['qty'];
                                            $arrCart[$i]['qty']+= $qty;
                                            if($arrCart[$i]['qty'] > $request['stock']){
                                                $arrCart[$i]['qty'] = $currentQty;
                                                $arrResponse = array("status"=>false,"msg"=>"No hay suficientes unidades","data"=>$data);
                                                $flag = false;
                                                break;
                                            }else{
                                                $_SESSION['arrPOS'] = $arrCart;
                                                foreach ($_SESSION['arrPOS'] as $quantity) {
                                                    if($quantity['topic'] == 2){
                                                        if($quantity['producttype'] == 2){
                                                            $total+=$quantity['qty']*$quantity['variant']['price'];
                                                        }else{
                                                            $total+=$quantity['qty']*$quantity['price'];
                                                        }
                                                    }else{
                                                        $total+=$quantity['qty']*$quantity['price'];
                                                    }
                                                }
                                                $arrResponse = array("status"=>true,"msg"=>"Ha sido agregado a tu carrito.","total"=>formatNum($total),"value"=>floor($total),"data"=>$data);
                                            }
                                            $flag =false;
                                            break;
                                        }
                                    }
                                }else if($topic == 3){
                                    if($service == $arrCart[$i]['name']){
                                        $arrResponse = array("status"=>true,"msg"=>"Ha sido agregado a tu carrito.","total"=>formatNum($total),"value"=>floor($total),"data"=>$data);
                                        break;
                                    }
                                }
                            }
                            if($flag){
                                if(!empty($request) && $qty > $request['stock'] && $productType == 1){
                                    $arrResponse = array("status"=>false,"msg"=>"No hay suficientes unidades","data"=>$data);
                                    $_SESSION['arrPOS'] = $arrCart;
                                }else if(!empty($request['variant']) && $qty > $variant['stock'] && $productType == 2){
                                    $arrResponse = array("status"=>false,"msg"=>"No hay suficientes unidades","data"=>$data);
                                    $_SESSION['arrPOS'] = $arrCart;
                                }else{
                                    array_push($arrCart,$arrProduct);
                                    $_SESSION['arrPOS'] = $arrCart;
                                    foreach ($_SESSION['arrPOS'] as $quantity) {
                                        if($quantity['topic'] == 2){
                                            if($quantity['producttype'] == 2){
                                                $total+=$quantity['qty']*$quantity['variant']['price'];
                                            }else{
                                                $total+=$quantity['qty']*$quantity['price'];
                                            }
                                        }else{
                                            $total+=$quantity['qty']*$quantity['price'];
                                        }
                                    }
                                    $arrResponse = array("status"=>true,"msg"=>"Ha sido agregado a tu carrito.","total"=>formatNum($total),"value"=>floor($total),"data"=>$data);
                                }
                            }
                            
                        }else{
                            if(!empty($request) && $qty > $request['stock'] && $productType == 1){
                                $arrResponse = array("status"=>false,"msg"=>"No hay suficientes unidades","data"=>$data);
                            }else if(!empty($request['variant']) && $qty > $variant['stock'] && $productType == 2){
                                $arrResponse = array("status"=>false,"msg"=>"No hay suficientes unidades","data"=>$data);
                            }else{
                                array_push($arrCart,$arrProduct);
                                $_SESSION['arrPOS'] = $arrCart;
                                foreach ($_SESSION['arrPOS'] as $quantity) {
                                    if($quantity['topic'] == 2){
                                        if($quantity['producttype'] == 2){
                                            $total+=$quantity['qty']*$quantity['variant']['price'];
                                        }else{
                                            $total+=$quantity['qty']*$quantity['price'];
                                        }
                                    }else{
                                        $total+=$quantity['qty']*$quantity['price'];
                                    }
                                }
                                $arrResponse = array("status"=>true,"msg"=>"Ha sido agregado a tu carrito.","total"=>formatNum($total),"value"=>floor($total),"data"=>$data);
                            } 
                        }
                        $arrResponse['html'] = $this->currentCart();
                    }else{
                        $arrResponse = array("status"=>false,"msg"=>"El producto no existe");
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }
        public function updateCart(){
            //dep($_POST);
            //dep($_SESSION['arrPOS']);exit;
            if($_SESSION['permitsModule']['w']){
                if($_POST){
                    $id = intval($_POST['id']);
                    $topic = intval($_POST['topic']);
                    $variant = $_POST['variant'] != null ? intval($_POST['variant']) : null;
                    if($topic == 1){
                        $height = floatval($_POST['height']);
                        $width = floatval($_POST['width']);
                        $margin = intval($_POST['margin']);
                        $style = strClean($_POST['style']);
                        $colorMargin = strClean($_POST['colormargin']);
                        $colorBorder = strClean($_POST['colorborder']);
                        $idType = intval($_POST['idType']);
                        $reference = strClean($_POST['reference']);
                    }
                    $total =0;
                    $totalPrice = 0;
                    $qty = intval($_POST['qty']);
                    if($qty > 0){
                        $arrProducts = $_SESSION['arrPOS'];
                        for ($i=0; $i < count($arrProducts) ; $i++) { 
                            if($arrProducts[$i]['topic'] == 1 && $topic == 1){
                                if($arrProducts[$i]['style'] == $style && $arrProducts[$i]['height'] == $height &&
                                $arrProducts[$i]['width'] == $width && $arrProducts[$i]['margin'] == $margin &&
                                $arrProducts[$i]['colormargin'] == $colorMargin && $arrProducts[$i]['colorborder'] == $colorBorder && 
                                $arrProducts[$i]['idType'] == $idType && $arrProducts[$i]['reference'] == $reference){
                                    $arrProducts[$i]['qty'] = $qty;
                                    $totalPrice =$arrProducts[$i]['qty']*$arrProducts[$i]['price'];
                                    break;
                                }
                            }else if($arrProducts[$i]['topic'] == 2 && $topic == 2){
                                //dep($qty);exit;
                                if($arrProducts[$i]['id'] == $id && !empty($arrProducts[$i]['variant']) 
                                && $arrProducts[$i]['variant']['id_product_variant'] == $variant){
                                    $stock = $this->model->selectProduct($id,$variant)['variant']['stock'];
                                    
                                    if($qty >= $stock ){
                                        $qty = $stock;
                                    }
                                    $arrProducts[$i]['qty'] = $qty;
                                    
                                    $totalPrice =$arrProducts[$i]['qty']*$arrProducts[$i]['variant']['price'];
                                    break;
                                }else if($arrProducts[$i]['id'] == $id && $variant == null){
                                    $stock = $this->model->selectProduct($id,$variant)['stock'];
                                    if($qty >= $stock ){
                                        $qty = $stock;
                                    }
                                    $arrProducts[$i]['qty'] = $qty;
                                    $totalPrice =$arrProducts[$i]['qty']*$arrProducts[$i]['price'];
                                }
                            }
                        }
                        $_SESSION['arrPOS'] = $arrProducts;
                        foreach ($_SESSION['arrPOS'] as $pro) {
                            if($pro['topic'] == 2){
                                if($pro['producttype'] == 2){
                                    $total+=$pro['qty']*$pro['variant']['price'];
                                }else{
                                    $total+=$pro['qty']*$pro['price'];
                                }
                            }else{
                                $total+=$pro['qty']*$pro['price'];
                            }
                            
                        }
                        $html = $this->currentCart();
                        $arrResponse = array("status"=>true,"total" =>formatNum($total),"value"=>floor($total),"totalprice"=>formatNum($totalPrice,false),"qty"=>$qty,"html"=>$html);
                    }else{
                        $arrResponse = array("status"=>false,"msg" =>"Error de datos.");
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }
        public function delCart(){
            if($_SESSION['permitsModule']['w']){
                if($_POST){
                    $id = $_POST['id'];
                    $topic = intval($_POST['topic']);
                    $arrCart = $_SESSION['arrPOS'];
                    $variant = $_POST['variant'] != null ? intval($_POST['variant']) : null;
                    if($topic == 1){
                        $height = floatval($_POST['height']);
                        $width = floatval($_POST['width']);
                        $margin = intval($_POST['margin']);
                        $style = $_POST['style'];
                        $type = intval($_POST['type']);
                        $borderColor = $_POST['bordercolor'];
                        $marginColor = $_POST['margincolor'];
                        $reference = $_POST['reference'];
                        $photo = $_POST['photo'];
                    }else if($topic == 3){
                        $service = ucwords(strClean($_POST['txtService']));
                    }
                    for ($i=0; $i < count($arrCart) ; $i++) { 
                        if($topic == 1){
                            if($id == $arrCart[$i]['id'] && $height == $arrCart[$i]['height']
                            && $width == $arrCart[$i]['width'] && $margin == $arrCart[$i]['margin'] && $style == $arrCart[$i]['style']
                            && $type == $arrCart[$i]['idType'] && $borderColor == $arrCart[$i]['colorborder'] && $marginColor == $arrCart[$i]['colormargin']
                            && $photo == $arrCart[$i]['photo'] && $reference == $arrCart[$i]['reference']){
                                if($photo!="" && $photo !="retablo.png"){
                                    deleteFile($photo);
                                }
                                unset($arrCart[$i]);
                                break;
                            }
                        }else if($topic == 2){
                            if($id == $arrCart[$i]['id']){
                                if($arrCart[$i]['producttype'] == 2){
                                    if($arrCart[$i]['variant']['id_product_variant'] == $variant){
                                        unset($arrCart[$i]);
                                        break;
                                    }
                                }else if($arrCart[$i]['producttype'] == 1){
                                    unset($arrCart[$i]);
                                    break;
                                }
                            }
                        }else if($topic == 3){
                            if($id == $arrCart[$i]['id'] && $service == $arrCart[$i]['name']){
                                unset($arrCart[$i]);
                                break;
                            }
                        }
                    }
                    
                    sort($arrCart);
                    $_SESSION['arrPOS'] = $arrCart;
                    $total = 0;
                    foreach ($_SESSION['arrPOS'] as $pro) {
                        if($pro['topic'] == 2){
                            if($pro['producttype'] == 2){
                                $total+=$pro['qty']*$pro['variant']['price'];
                            }else{
                                $total+=$pro['qty']*$pro['price'];
                            }
                        }else{
                            $total+=$pro['qty']*$pro['price'];
                        }
                    }
                    $html = $this->currentCart();
                    $arrResponse = array("status"=>true,"total" =>formatNum($total),"value"=>floor($total),"html"=>$html);
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function currentCart(){
            if($_SESSION['permitsModule']['w']){
                $html="";
                if(isset($_SESSION['arrPOS']) && !empty($_SESSION['arrPOS'])){
                    $arrProducts = $_SESSION['arrPOS'];
                    $html="";
                    for ($i=0; $i < count($arrProducts) ; $i++) { 
                        if($arrProducts[$i]['topic'] == 1){
                            $photo = $arrProducts[$i]['photo'] != "" ? media()."/images/uploads/".$arrProducts[$i]['photo'] : $arrProducts[$i]['img'];
                            $html.= '
                            <div class="position-relative" data-id="'.$arrProducts[$i]['id'].'" data-topic ="'.$arrProducts[$i]['topic'].'" data-h="'.$arrProducts[$i]['height'].'"
                                data-w="'.$arrProducts[$i]['width'].'" data-m="'.$arrProducts[$i]['margin'].'" data-s="'.$arrProducts[$i]['style'].'" 
                                data-mc="'.$arrProducts[$i]['colormargin'].'" data-bc="'.$arrProducts[$i]['colorborder'].'" data-t="'.$arrProducts[$i]['idType'].'" data-f="'.$arrProducts[$i]['photo'].'"
                                data-r="'.$arrProducts[$i]['reference'].'">
                                <button class="btn text-danger p-0 rounded-circle position-absolute top-0 end-0 fs-5" onclick="delProduct(this)"><i class="fas fa-times-circle"></i></button>
                                <div class="p-1">
                                    <div class="d-flex justify-content-between">
                                        <div class="d-flex">
                                            <img src="'.$photo.'" alt="" class="me-1" height="60px" width="60px" >
                                            <div class="text-start">
                                                <div style="height:25px" class="overflow-hidden"><p class="m-0" >'.$arrProducts[$i]['name'].'</p></div>
                                                <p class="m-0 productData">
                                                    <span class="qtyProduct">'.$arrProducts[$i]['qty'].'</span> x '.formatNum($arrProducts[$i]['price'],false).'
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between mt-1">
                                        <div>
                                            <button type="button" class="btn btn-sm btn-secondary p-1 text-white productDec" onclick="productDec(this)"><i class="fas fa-minus"></i></button>
                                            <button type="button" class="btn btn-sm btn-success p-1 text-white productInc" onclick="productInc(this)"><i class="fas fa-plus"></i></button>
                                        </div>
                                        <p class="m-0 mt-1 fw-bold text-end productTotal">'.formatNum($arrProducts[$i]['price']*$arrProducts[$i]['qty'],false).'</p>
                                    </div>
                                </div>
                            </div>
                            ';
                        }else if($arrProducts[$i]['topic'] == 2){
                            if($arrProducts[$i]['producttype'] == 1){
                                $html.= '
                                <div class="position-relative" data-id="'.$arrProducts[$i]['id'].'" data-topic ="'.$arrProducts[$i]['topic'].'">
                                    <button class="btn text-danger p-0 rounded-circle position-absolute top-0 end-0 fs-5" onclick="delProduct(this)"><i class="fas fa-times-circle"></i></button>
                                    <div class="p-1">
                                        <div class="d-flex justify-content-between">
                                            <div class="d-flex">
                                                <img src="'.$arrProducts[$i]['image'].'" alt="" class="me-1" height="60px" width="60px" >
                                                <div class="text-start">
                                                    <div style="height:25px" class="overflow-hidden"><p class="m-0" >'.$arrProducts[$i]['name'].'</p></div>
                                                    <p class="m-0 productData">
                                                        <span class="qtyProduct">'.$arrProducts[$i]['qty'].'</span> x '.formatNum($arrProducts[$i]['price'],false).'
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between mt-1">
                                            <div>
                                            <button type="button" class="btn btn-sm btn-secondary p-1 text-white productDec" onclick="productDec(this)"><i class="fas fa-minus"></i></button>
                                            <button type="button" class="btn btn-sm btn-success p-1 text-white productInc" onclick="productInc(this)"><i class="fas fa-plus"></i></button>
                                            </div>
                                            <p class="m-0 mt-1 fw-bold text-end productTotal" >'.formatNum($arrProducts[$i]['price']*$arrProducts[$i]['qty'],false).'</p>
                                        </div>
                                    </div>
                                </div>';
                            }else{
                                $html.= '
                                <div class="position-relative" data-id="'.$arrProducts[$i]['id'].'" data-topic ="'.$arrProducts[$i]['topic'].'" data-variant="'.$arrProducts[$i]['variant']['id_product_variant'].'">
                                    <button class="btn text-danger p-0 rounded-circle position-absolute top-0 end-0 fs-5" onclick="delProduct(this)"><i class="fas fa-times-circle"></i></button>
                                    <div class="p-1">
                                        <div class="d-flex justify-content-between">
                                            <div class="d-flex">
                                                <img src="'.$arrProducts[$i]['image'].'" alt="" class="me-1" height="60px" width="60px" >
                                                <div class="text-start">
                                                    <div style="height:25px" class="overflow-hidden"> <p class="mb-2">'.$arrProducts[$i]['reference']." ".$arrProducts[$i]['name'].'</p></div>
                                                    <p class="m-0" >Tamaño: '.$arrProducts[$i]['variant']['width']."x".$arrProducts[$i]['variant']['height'].'</p>
                                                    <p class="m-0 productData">
                                                        <span class="qtyProduct">'.$arrProducts[$i]['qty'].'</span> x '.formatNum($arrProducts[$i]['variant']['price'],false).'
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between mt-1">
                                            <div>
                                                <button type="button" class="btn btn-sm btn-secondary p-1 text-white productDec" onclick="productDec(this)"><i class="fas fa-minus"></i></button>
                                                <button type="button" class="btn btn-sm btn-success p-1 text-white productInc" onclick="productInc(this)"><i class="fas fa-plus"></i></button>
                                            </div>
                                            <p class="m-0 mt-1 fw-bold text-end productTotal" >'.formatNum($arrProducts[$i]['variant']['price']*$arrProducts[$i]['qty'],false).'</p>
                                        </div>
                                    </div>
                                </div>';
                                }
                            
                        }else if($arrProducts[$i]['topic'] == 3){
                            $html.='
                            <div class="position-relative" data-id="'.$arrProducts[$i]['id'].'" data-name="'.$arrProducts[$i]['name'].'" data-topic ="'.$arrProducts[$i]['topic'].'">
                                <button class="btn text-danger p-0 rounded-circle position-absolute top-0 end-0 fs-5" onclick="delProduct(this)"><i class="fas fa-times-circle"></i></button>
                                <div class="p-1">
                                    <div class="d-flex justify-content-between">
                                        <div class="d-flex">
                                            <img src="'.media().'/images/uploads/category.jpg" alt="" class="me-1" height="60px" width="60px" >
                                            <div class="text-start">
                                                <div style="height:25px" class="overflow-hidden"><p class="m-0" >'.$arrProducts[$i]['name'].'</p></div>
                                                <p class="m-0 productData">
                                                    <span class="qtyProduct">'.$arrProducts[$i]['qty'].'</span> x '.formatNum($arrProducts[$i]['price'],false).'
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end mt-1">
                                        <p class="m-0 mt-1 fw-bold text-end productTotal" >'.formatNum($arrProducts[$i]['price']*$arrProducts[$i]['qty'],false).'</p>
                                    </div>
                                </div>
                            </div>
                            ';
                        }
                    }
                    /*$total =0;
                    $qty = 0;
                    foreach ($arrProducts as $pro) {
                        if($pro['producttype'] == 2){
                            $total+=$pro['qty']*$pro['variant']['price'];
                        }else{
                            $total+=$pro['qty']*$pro['price'];
                        }
                        $qty+=$pro['qty'];
                    }*/
                    
                }
                return $html;
            }
        }
        public function setOrder(){
            //dep($_POST);exit;
            if($_SESSION['permitsModule']['w']){
                if($_POST){
                    if(empty($_POST['id'])){
                        $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $total = 0;
                        $idUser = intval($_POST['id']);
                        $idOrder = intval($_POST['idOrder']);
                        $customInfo = $this->model->selectCustomer($idUser);
                        $status = intval($_POST['statusList']);
                        $statusOrder = STATUS[intval($_POST['statusOrder'])];
                        $strNote = strClean($_POST['strNote']);
                        $strName = $customInfo['firstname']." ".$customInfo['lastname'];
                        $strEmail = $customInfo['email'];
                        $strPhone = $customInfo['phone'];
                        $strIdentification = $customInfo['identification'];
                        $strAddress = $customInfo['address']." ".$customInfo['city']."/".$customInfo['state']."/".$customInfo['country'];
                        $type = PAGO[intval($_POST['paymentList'])];
                        $envio = 0;
                        $option="";
                        $request="";
                        $objSuscription="";
                        $arrSuscription=array();
                        $updateCustomer=intval($_POST['updateCustomer']);
                        $strDate = $_POST['strDate'];
                        $dateObj = new DateTime($strDate);
                        $dateCount = 0;
                        //$dateBeat = date("Y-m-d",strtotime($strDate . " +30 days"));
                        
                        while ($dateCount < 30) {
                            $dateObj->modify('+1 day');
                            $dayWeek = $dateObj->format('N');

                            if($dayWeek < 7){
                                $dateCount++;
                            }
                        }
                        $dateBeat = $dateObj->format("Y-m-d");

                        if($status == 1){
                            $status = "approved";
                        }else if($status == 2){
                            $status = "pendent";
                        }else{
                            $status = "canceled";
                        }
                        if($idOrder == 0){
                            $discount = intval($_POST['discount']);
                            $suscription = intval($_POST['received']);
                            $option = 1;
                            foreach ($_SESSION['arrPOS'] as $pro) {
                                if($pro['topic'] == 2){
                                    if($pro['producttype'] == 2){
                                        $total+=$pro['qty']*$pro['variant']['price'];
                                    }else{
                                        $total+=$pro['qty']*$pro['price'];
                                    }
                                }else{
                                    $total+=$pro['qty']*$pro['price'];
                                }
                            }
                            $total = $total-$discount;
                            
                            if($suscription-$discount == $total){
                                $arrSuscription = array(
                                    [
                                        "date"=>date("Y-m-d"),
                                        "debt"=>$total,
                                        "type"=>$type
                                    ]
                                );
                            }else if( $suscription < $total && $suscription > 0){
                                $arrSuscription = array(
                                    [
                                        "date"=>date("Y-m-d"),
                                        "debt"=>$suscription,
                                        "type"=>$type
                                    ]
                                );
                            }
                            $request = $this->model->insertOrder($idUser,$strName,$strIdentification,$strEmail,$strPhone,$strAddress,$strNote,$strDate,$discount,$envio,$arrSuscription,$total,$status,$type,$statusOrder,$dateBeat);          
                        }else{
                            $option = 2;
                            $arrSuscription = json_decode($_POST['suscription'],true);
                            $request = $this->model->updateOrder($idOrder,$strName,$strIdentification,$strEmail,$strPhone,$strAddress,$strDate,$strNote,$arrSuscription,$type,$status,$statusOrder,$dateBeat,$updateCustomer);          
                        }
                        if($request>0){
                            if($option == 1){
                                $arrOrder = array("idorder"=>$request,"iduser"=>$idUser,"products"=>$_SESSION['arrPOS']);
                                $requestDetail = $this->model->insertOrderDetail($arrOrder);
                                unset($_SESSION['arrPOS']);
                                $arrResponse = array("status"=>true,"msg"=>"Pedido realizado");
                            }else{
                                $arrResponse = array("status"=>true,"msg"=>"Pedido actualizado");
                            }
                        }else if(!$request){
                            $arrResponse = array("status"=>false,"msg"=>"Error, los anticipos superan al monto total, inténtelo de nuevo.");
                        }else{
                            $arrResponse = array("status"=>false,"msg"=>"Error, no se ha podido realizar el pedido, inténtelo de nuevo.");
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }
    }
?>