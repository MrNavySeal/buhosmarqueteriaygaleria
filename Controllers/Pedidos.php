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
                $data['orders'] = $this->getOrders();
                $data['products'] = $this->getProducts();
                $data['tipos'] = $this->model->selectCategories();
                $data['app'] = "functions_orders.js";
                $this->views->getView($this,"pedidos",$data);
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
                    $data['app'] = "functions_orders.js";
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
                $data['app'] = "functions_orders.js";
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
                    $data['amount'] = formatNum($data['amount']);
                    $options ="";
                    $statusOrder="";
                    if($data['status'] == "pendent"){
                        $options='
                        <option value="1">approved</option>
                        <option value="2" selected>pendent</option>
                        ';
                    }else{
                        $options='
                        <option value="1" selected>approved</option>
                        <option value="2">pendent</option>
                        ';
                    }
                    for ($i=0; $i < count(STATUS) ; $i++) { 
                        if($data['statusorder'] == STATUS[$i]){
                            $statusOrder.='<option value="'.$i.'" selected>'.STATUS[$i].'</option>';
                        }else{
                            $statusOrder.='<option value="'.$i.'">'.STATUS[$i].'</option>';
                        }
                    }
                    $data['options'] = $options;
                    $data['statusorder'] = $statusOrder;
                    $arrResponse = array("status"=>true,"data"=>$data);
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function updateOrder(){
            if($_SESSION['permitsModule']['u']){
                //dep($_POST);exit;
                if($_POST){
                    if(empty($_POST['strNote']) || empty($_POST['statusList']) || empty($_POST['strDate']) || 
                    empty($_POST['txtTransaction']) || empty($_POST['statusOrder'])){
                        $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                    }
                    $idOrder = intval($_POST['idOrder']);
                    $status = intval($_POST['statusList']) == 1 ? "approved" : "pendent";
                    $statusOrder = intval($_POST['statusOrder']);
                    $strNote = strClean($_POST['strNote']);
                    $strDate = $_POST['strDate'];
                    $idTransaction = strClean($_POST['txtTransaction']);
                    $statusO ="";
                    for ($i=0; $i < count(STATUS) ; $i++) { 
                        if($statusOrder == $i){
                            $statusO = STATUS[$i];
                            break;
                        }
                    }
                    $request = $this->model->updateOrder($idOrder,$idTransaction,$strDate,$strNote,$status,$statusO);
                    if($request>0){
                        $arrResponse = array("status"=>true,"msg"=>"Pedido actualizado","data"=>$this->getOrders()['data']);
                    }else{
                        $arrResponse = array("status"=>false,"msg"=>"No se ha podido actualizar el pedido");
                    }
                }
                echo json_encode ($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function getOrders($option=null,$params=null){
            if($_SESSION['permitsModule']['r']){
                $html="";
                $request="";
                if($option == 1){
                    $request = $this->model->search($params);
                }else if($option == 2){
                    $request = $this->model->sort($params);
                }else{
                    $request = $this->model->selectOrders();
                }
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 

                        $btnView='<a href="'.base_url().'/pedidos/pedido/'.$request[$i]['idorder'].'" class="btn btn-info text-white m-1" type="button" title="Ver orden" name="btnView"><i class="fas fa-eye"></i></a>';
                        $btnPaypal='';
                        $btnDelete ="";
                        $btnEdit ="";
                        $status="";
                        $statusOrder="";
                        if($request[$i]['type'] != "pos" && $request[$i]['type'] != "other"){
                            $btnPaypal = '<a href="'.base_url().'/pedidos/transaccion/'.$request[$i]['idtransaction'].'" class="btn btn-info m-1 text-white " type="button" title="Ver transacción" name="btnPaypal"><i class="fas fa-receipt"></i></a>';
                        }
                        if($request[$i]['status'] =="pendent"){
                            $status = '<span class="badge bg-warning text-white">pendiente</span>';
                        }else{
                            $status = '<span class="badge bg-success text-white">aprobado</span>';
                        }
                        if($request[$i]['statusorder'] =="confirmado"){
                            $statusOrder = '<span class="badge bg-dark text-white">confirmado</span>';
                        }else if($request[$i]['statusorder'] =="en preparacion"){
                            $statusOrder = '<span class="badge bg-warning text-white">en preparacion</span>';
                        }else if($request[$i]['statusorder'] =="preparado"){
                            $statusOrder = '<span class="badge bg-info text-white">preparado</span>';
                        }else if($request[$i]['statusorder'] =="entregado"){
                            $statusOrder = '<span class="badge bg-success text-white">entregado</span>';
                        }
                        if($_SESSION['permitsModule']['d'] && $_SESSION['userData']['roleid'] == 1){
                            $btnDelete = '<button class="btn btn-danger text-white m-1" type="button" title="Delete" data-id="'.$request[$i]['idorder'].'" name="btnDelete"><i class="fas fa-trash-alt"></i></button>';
                        }
                        if($_SESSION['permitsModule']['u']){
                            $btnEdit = '<button class="btn btn-success text-white m-1" type="button" title="Edit" data-id="'.$request[$i]['idorder'].'" name="btnEdit"><i class="fas fa-pencil-alt"></i></button>';
                        }
                        if($_SESSION['userData']['roleid'] == 1 || $_SESSION['userData']['roleid'] == 3){

                            $html.='
                                <tr class="item">
                                    <td data-label="Id: ">'.$request[$i]['idorder'].'</td>
                                    <td data-label="Transacción: ">'.$request[$i]['idtransaction'].'</td>
                                    <td data-label="Fecha: ">'.$request[$i]['date'].'</td>
                                    <td data-label="Monto: ">'.formatNum($request[$i]['amount'],false).'</td>
                                    <td data-label="Tipo de pago: ">'.$request[$i]['type'].'</td>
                                    <td data-label="Estado de pago: ">'.$status.'</td>
                                    <td data-label="Estado de pedido: ">'.$statusOrder.'</td>
                                    <td class="item-btn">'.$btnView.$btnPaypal.$btnEdit.$btnDelete.'</td>
                                </tr>
                            ';

                        }elseif($_SESSION['idUser'] == $request[$i]['personid']){
                            $html.='
                            <tr class="item">
                                <td data-label="Id: ">'.$request[$i]['idorder'].'</td>
                                <td data-label="Transacción: ">'.$request[$i]['idtransaction'].'</td>
                                <td data-label="Fecha: ">'.$request[$i]['date'].'</td>
                                <td data-label="Monto: ">'.formatNum($request[$i]['amount'],false).'</td>
                                <td data-label="Tipo de pago: ">'.$request[$i]['type'].'</td>
                                <td data-label="Estado de pago: ">'.$status.'</td>
                                <td data-label="Estado de pedido: ">'.$statusOrder.'</td>
                                <td class="item-btn">'.$btnView.$btnPaypal.$btnDelete.'</td>
                            </tr>
                        ';
                        }
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
        public function search($params){
            if($_SESSION['permitsModule']['r']){
                $search = strClean($params);
                $arrResponse = $this->getOrders(1,$params);
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function sort($params){
            if($_SESSION['permitsModule']['r']){
                $params = intval($params);
                $arrResponse = $this->getOrders(2,$params);
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
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
                        $price = formatNum($request[$i]['price'],false);
                        if($request[$i]['discount']>0){
                            $discount = '<span class="text-success">'.$request[$i]['discount'].'% OFF</span>';
                        }else{
                            $discount = '<span class="text-danger">0%</span>';
                        }
                        $html.='
                            <tr class="item">
                                <td class="text-center">
                                    <img src="'.$request[$i]['image'].'" class="rounded">
                                </td>
                                <td class="text-center">'.$request[$i]['name'].'</td>
                                <td data-label="Precio: ">'.$price.'</td>
                                <td data-label="Descuento: ">'.$discount.'</td>
                                <td class="text-center"><button type="button" class="btn btn-primary" onclick="addProduct('.$request[$i]['idproduct'].',this)">Agregar</button></td>
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
            if($_SESSION['permitsModule']['w']){
                if($_POST){ 
                    $id = intval($_POST['idProduct']);
                    $qty = intval($_POST['txtQty']);
                    $topic = intval($_POST['topic']);
                    $qtyCart = 0;
                    $arrCart = array();
                    $valiQty =true;
                    $data = array();
                    $request = array();
                    $total = 0;
                    if($id != 0){
                        $request = $this->model->selectProduct($id);
                        $price = $request['price'];
        
                        if($request['discount']>0){
                            $price = $request['price'] - ($request['price']*($request['discount']/100));
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
                                "topic"=>2,
                                "id"=>$id,
                                "name" => $request['name'],
                                "qty"=>$qty,
                                "image"=>$request['image'],
                                "price" =>$price,
                                "stock"=>$request['stock']
                            );
                        }
                        if(isset($_SESSION['arrPOS'])){
                            $arrCart = $_SESSION['arrPOS'];
                            $currentQty = 0;
                            $flag = true;
                            
                            for ($i=0; $i < count($arrCart) ; $i++) { 
                                if($topic == 2){
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
                                                $total += $quantity['qty']*$quantity['price'];
                                            }
                                            $arrResponse = array("status"=>true,"msg"=>"Ha sido agregado a tu carrito.","total"=>formatNum($total),"value"=>floor($total),"data"=>$data);
                                        }
                                        $flag =false;
                                        break;
                                    }
                                }else if($topic == 3){
                                    if($service == $arrCart[$i]['name']){
                                        $arrResponse = array("status"=>true,"msg"=>"Ha sido agregado a tu carrito.","total"=>formatNum($total),"value"=>floor($total),"data"=>$data);
                                        break;
                                    }
                                }
                            }
                            if($flag){
                                if(!empty($request) && $qty > $request['stock']){
                                    $arrResponse = array("status"=>false,"msg"=>"No hay suficientes unidades","data"=>$data);
                                    $_SESSION['arrPOS'] = $arrCart;
                                }else{
                                    array_push($arrCart,$arrProduct);
                                    $_SESSION['arrPOS'] = $arrCart;
                                    foreach ($_SESSION['arrPOS'] as $quantity) {
                                        $total += $quantity['qty']*$quantity['price'];
                                    }
                                    $arrResponse = array("status"=>true,"msg"=>"Ha sido agregado a tu carrito.","total"=>formatNum($total),"value"=>floor($total),"data"=>$data);
                                }
                            }
                            
                        }else{
                            if(!empty($request) && $qty > $request['stock']){
                                $arrResponse = array("status"=>false,"msg"=>"No hay suficientes unidades","data"=>$data);
                            }else{
                                array_push($arrCart,$arrProduct);
                                $_SESSION['arrPOS'] = $arrCart;
                                foreach ($_SESSION['arrPOS'] as $quantity) {
                                    $total += $quantity['qty']*$quantity['price'];
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
            //dep($_POST);exit;
            //dep($_SESSION['arrPOS']);exit;
            if($_SESSION['permitsModule']['w']){
                if($_POST){
                    $id = intval($_POST['id']);
                    $topic = intval($_POST['topic']);
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
                                if($arrProducts[$i]['id'] == $id){
                                    $stock = $this->model->selectProduct($id)['stock'];
                                    if($qty >= $stock ){
                                        $qty = $stock;
                                    }
                                    $arrProducts[$i]['qty'] = $qty;
                                    $totalPrice =$arrProducts[$i]['qty']*$arrProducts[$i]['price'];
                                    break;
                                }
                            }
                        }
                        $_SESSION['arrPOS'] = $arrProducts;
                        foreach ($_SESSION['arrPOS'] as $pro) {
                            $total+=$pro['qty']*$pro['price'];
                        }
                        $arrResponse = array("status"=>true,"total" =>formatNum($total),"value"=>floor($total),"totalprice"=>formatNum($totalPrice,false),"qty"=>$qty);
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
                                unset($arrCart[$i]);
                                break;
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
                        $total += $pro['qty']*$pro['price'];
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
                                            <button type="button" class="btn btn-sm btn-secondary p-1 text-white productDec"><i class="fas fa-minus"></i></button>
                                            <button type="button" class="btn btn-sm btn-success p-1 text-white productInc"><i class="fas fa-plus"></i></button>
                                        </div>
                                        <p class="m-0 mt-1 fw-bold text-end productTotal">'.formatNum($arrProducts[$i]['price']*$arrProducts[$i]['qty'],false).'</p>
                                    </div>
                                </div>
                            </div>
                            ';
                        }else if($arrProducts[$i]['topic'] == 2){
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
                                            <button type="button" class="btn btn-sm btn-secondary p-1 text-white productDec"><i class="fas fa-minus"></i></button>
                                            <button type="button" class="btn btn-sm btn-success p-1 text-white productInc"><i class="fas fa-plus"></i></button>
                                        </div>
                                        <p class="m-0 mt-1 fw-bold text-end productTotal" >'.formatNum($arrProducts[$i]['price']*$arrProducts[$i]['qty'],false).'</p>
                                    </div>
                                </div>
                            </div>';
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
                    $total =0;
                    $qty = 0;
                    foreach ($arrProducts as $product) {
                        $total+=$product['qty']*$product['price'];
                        $qty+=$product['qty'];
                    }
                    
                }
                return $html;
            }
        }
        public function setOrder(){
            //dep($_POST);exit;
            if($_SESSION['permitsModule']['w']){
                if($_POST){
                    if(empty($_POST['id']) || empty($_POST['strNote']) || empty($_POST['txtTransaction'])){
                        $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $total = 0;
                        foreach ($_SESSION['arrPOS'] as $pro) {
                            $total +=$pro['qty']*$pro['price'];
                        }
                        
                        $idUser = intval($_POST['id']);
                        $customInfo = $this->model->selectCustomer($idUser);
                        $status = "approved";
                        $statusOrder = "confirmado";
                        $received = intval($_POST['received']);
                        $strNote = strClean($_POST['strNote']);
                        $strDate = $_POST['strDate'];
                        $strName = $customInfo['firstname']." ".$customInfo['lastname'];
                        $strEmail = $customInfo['email'];
                        $strPhone = $customInfo['phone'];
                        $strAddress = $customInfo['address'].", ".$customInfo['city']."/".$customInfo['state']."/".$customInfo['country'];
                        $cupon = "";
                        $idTransaction =strClean($_POST['txtTransaction']);
                        $type ="pos";
                        $envio = 0;
                        if($_POST['discount'] > 0 && $_POST['discount'] <=90){
                            
                            $discount = intval($_POST['discount']);
                            $strNote.="- Descuento del ".$discount."%";
                            $total = $total -($total*($discount*0.01));
                        }
                        if($received < $total){
                            $status = "pendent";
                            $strNote .= " - abona ".formatNum($received,false).", debe ".formatNum($total-$received,false);
                        }
                        $request = $this->model->insertOrder($idUser, $idTransaction,$strName,$strEmail,$strPhone,$strAddress,$strNote,$strDate,$cupon,$envio,$total,$status,$type,$statusOrder);          
                        if($request>0){
                            $arrOrder = array("idorder"=>$request,"iduser"=>$idUser,"products"=>$_SESSION['arrPOS']);
                            $requestDetail = $this->model->insertOrderDetail($arrOrder);
                            unset($_SESSION['arrPOS']);
                            $arrResponse = array("status"=>true,"msg"=>"Pedido realizado");
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