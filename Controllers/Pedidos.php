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
                $data['page_tag'] = "pedido";
                $data['page_title'] = "Pedidos | Panel";
                $data['page_name'] = "pedidos";
                $data['panelapp'] = "functions_orders.js";
                $this->views->getView($this,"pedidos",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function creditos(){
            if($_SESSION['permitsModule']['r']){
                $data['page_tag'] = "pedido";
                $data['page_title'] = "Pedidos a crédito | Panel";
                $data['page_name'] = "creditos";
                $data['panelapp'] = "functions_orders_creditos.js";
                $this->views->getView($this,"creditos",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function detalle(){
            if($_SESSION['permitsModule']['r']){
                $data['page_tag'] = "pedido";
                $data['page_title'] = "Detalle de pedidos | Panel";
                $data['page_name'] = "creditos";
                $data['panelapp'] = "functions_orders_detail.js";
                $this->views->getView($this,"detalle",$data);
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
        public function getOrders(){
            if($_SESSION['permitsModule']['r']){
                $idPersona = "";
                if($_SESSION['userData']['roleid'] == 2){
                    $idPersona = $_SESSION['idUser'];
                }
                $request = $this->model->selectOrders($idPersona);
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 

                        $btnView = '<button class="btn btn-info m-1 text-white" type="button" title="Ver" onclick="viewItem('.$request[$i]['idorder'].')"><i class="fas fa-eye"></i></button>';
                        $btnWpp="";
                        $btnPdf='<a href="'.base_url().'/factura/generarFactura/'.$request[$i]['idorder'].'" target="_blank" class="btn btn-primary text-white m-1" type="button" title="Imprimir factura"><i class="fas fa-print"></i></a>';
                        $btnPaypal='';
                        $btnDelete ="";
                        $btnEdit ="";
                        $status="";
                        $statusOrder="";
                        if($request[$i]['type'] == "mercadopago"){
                            $btnPaypal = '<a href="'.base_url().'/pedidos/transaccion/'.$request[$i]['idtransaction'].'" class="btn btn-info m-1 text-white " type="button" title="Ver transacción" name="btnPaypal"><i class="fas fa-receipt"></i></a>';
                        }
                        
                        if($request[$i]['status'] =="pendent"){
                            $status = '<span class="badge bg-warning text-black">Credito</span>';
                        }else if($request[$i]['status'] =="approved"){
                            $status = '<span class="badge bg-success text-white">Pagado</span>';
                        }else if($request[$i]['status'] =="canceled"){
                            $status = '<span class="badge bg-danger text-white">Anulado</span>';
                        }
                        if($request[$i]['statusorder'] =="confirmado"){
                            $statusOrder = '<span class="badge bg-dark text-white">Confirmado</span>';
                        }else if($request[$i]['statusorder'] =="en preparacion"){
                            $statusOrder = '<span class="badge bg-warning text-black">En preparacion</span>';
                        }else if($request[$i]['statusorder'] =="preparado"){
                            $statusOrder = '<span class="badge bg-info text-white">Preparado</span>';
                        }else if($request[$i]['statusorder'] =="entregado"){
                            $statusOrder = '<span class="badge bg-success text-white">Entregado</span>';
                        }else if($request[$i]['statusorder'] =="rechazado" || $request[$i]['statusorder'] =="anulado"){
                            $statusOrder = '<span class="badge bg-danger text-white">Anulado</span>';
                        }
                        
                        if($_SESSION['permitsModule']['d'] && $request[$i]['status'] !="canceled"){
                            $btnDelete = '<button class="btn btn-danger m-1 text-white" type="button" title="Anular" onclick="deleteItem('.$request[$i]['idorder'].')" ><i class="fas fa-trash-alt"></i></button>';
                        }
                        if($_SESSION['permitsModule']['u'] && $request[$i]['status'] !="canceled"){
                            $btnEdit = '<button class="btn btn-success text-white m-1" type="button" title="Editar" onclick="editItem('.$request[$i]['idorder'].')"><i class="fas fa-pencil-alt"></i></button>';
                        }
                        if($_SESSION['userData']['roleid'] != 2){
                            $btnWpp='<a href="https://wa.me/57'.$request[$i]['phone'].'?text=Buen%20dia%20'.$request[$i]['name'].'" class="btn btn-success text-white m-1" type="button" title="Whatsapp" target="_blank"><i class="fab fa-whatsapp"></i></a>';
                        }
                        $request[$i]['format_amount'] = formatNum($request[$i]['amount']);
                        $request[$i]['statusval'] =  $request[$i]['status'];
                        $request[$i]['status'] = $status;
                        $request[$i]['statusorderval'] =  $request[$i]['statusorder'];
                        $request[$i]['statusorder'] = $statusOrder;
                        $request[$i]['options'] = $btnView.$btnWpp.$btnPdf.$btnPaypal.$btnEdit.$btnDelete;
                        $request[$i]['format_pendent'] = formatNum($request[$i]['total_pendent']);
                        $request[$i]['actual_user'] = $_SESSION['userData']['firstname']." ".$_SESSION['userData']['lastname'];
                        $request[$i]['id_actual_user'] = $_SESSION['userData']['idperson'];
                    }
                }
                echo json_encode($request,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function getCreditOrders(){
            if($_SESSION['permitsModule']['r']){
                $idPersona = "";
                if($_SESSION['userData']['roleid'] == 2){
                    $idPersona = $_SESSION['idUser'];
                }
                $request = $this->model->selectCreditOrders($idPersona);
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 

                        $btnView = '<button class="btn btn-info m-1 text-white" type="button" title="Ver" onclick="viewItem('.$request[$i]['idorder'].')"><i class="fas fa-eye"></i></button>';
                        $btnWpp="";
                        $btnPdf='<a href="'.base_url().'/factura/generarFactura/'.$request[$i]['idorder'].'" target="_blank" class="btn btn-primary text-white m-1" type="button" title="Imprimir factura"><i class="fas fa-print"></i></a>';
                        $btnPaypal='';
                        $btnDelete ="";
                        $btnEdit ="";
                        $btnAdvance="";
                        $status="";
                        $statusOrder="";
                        if($request[$i]['type'] == "mercadopago"){
                            $btnPaypal = '<a href="'.base_url().'/pedidos/transaccion/'.$request[$i]['idtransaction'].'" class="btn btn-info m-1 text-white " type="button" title="Ver transacción" name="btnPaypal"><i class="fas fa-receipt"></i></a>';
                        }
                        
                        if($request[$i]['status'] =="pendent"){
                            $status = '<span class="badge bg-warning text-black">Credito</span>';
                        }else if($request[$i]['status'] =="approved"){
                            $status = '<span class="badge bg-success text-white">Pagado</span>';
                        }else if($request[$i]['status'] =="canceled"){
                            $status = '<span class="badge bg-danger text-white">Anulado</span>';
                        }
                        if($request[$i]['statusorder'] =="confirmado"){
                            $statusOrder = '<span class="badge bg-dark text-white">Confirmado</span>';
                        }else if($request[$i]['statusorder'] =="en preparacion"){
                            $statusOrder = '<span class="badge bg-warning text-black">En preparacion</span>';
                        }else if($request[$i]['statusorder'] =="preparado"){
                            $statusOrder = '<span class="badge bg-info text-white">Preparado</span>';
                        }else if($request[$i]['statusorder'] =="entregado"){
                            $statusOrder = '<span class="badge bg-success text-white">Entregado</span>';
                        }else if($request[$i]['statusorder'] =="rechazado" || $request[$i]['statusorder'] =="anulado"){
                            $statusOrder = '<span class="badge bg-danger text-white">Anulado</span>';
                        }
                        
                        if($_SESSION['permitsModule']['d'] && $request[$i]['status'] !="canceled"){
                            $btnDelete = '<button class="btn btn-danger m-1 text-white" type="button" title="Anular" onclick="deleteItem('.$request[$i]['idorder'].')" ><i class="fas fa-trash-alt"></i></button>';
                        }
                        if($_SESSION['permitsModule']['u'] && $request[$i]['status'] !="canceled"){
                            $btnEdit = '<button class="btn btn-success text-white m-1" type="button" title="Editar" onclick="editItem('.$request[$i]['idorder'].')"><i class="fas fa-pencil-alt"></i></button>';
                        }
                        if($_SESSION['userData']['roleid'] != 2){
                            $btnWpp='<a href="https://wa.me/57'.$request[$i]['phone'].'?text=Buen%20dia%20'.$request[$i]['name'].'" class="btn btn-success text-white m-1" type="button" title="Whatsapp" target="_blank"><i class="fab fa-whatsapp"></i></a>';
                        }
                        if($_SESSION['permitsModule']['u'] && $request[$i]['status'] =="pendent"){
                            $btnAdvance = '<button class="btn btn-warning m-1 text-black" type="button" title="Abonar" onclick="advanceItem('.$request[$i]['idorder'].')"><i class="fas fa-hand-holding-usd"></i></button>';
                        }
                        $request[$i]['format_amount'] = formatNum($request[$i]['amount']);
                        $request[$i]['statusval'] =  $request[$i]['status'];
                        $request[$i]['status'] = $status;
                        $request[$i]['statusorderval'] =  $request[$i]['statusorder'];
                        $request[$i]['statusorder'] = $statusOrder;
                        $request[$i]['options'] = $btnView.$btnWpp.$btnPdf.$btnPaypal.$btnEdit.$btnAdvance.$btnDelete;
                        $request[$i]['format_pendent'] = formatNum($request[$i]['total_pendent']);
                        $request[$i]['actual_user'] = $_SESSION['userData']['firstname']." ".$_SESSION['userData']['lastname'];
                        $request[$i]['id_actual_user'] = $_SESSION['userData']['idperson'];
                    }
                }
                echo json_encode($request,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function getDetailOrders(){
            if($_SESSION['permitsModule']['r']){
                $idPersona = "";
                if($_SESSION['userData']['roleid'] == 2){
                    $idPersona = $_SESSION['idUser'];
                }
                $request = $this->model->selectDetailOrders($idPersona);
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 
                        $data = $request[$i];
                        $description="";
                        if($data['topic'] == 1){
                            $detail = json_decode($data['description']);
                            
                            $img ="";
                            if(isset($detail->type)){
                                $intWidth = floatval($detail->width);
                                $intHeight = floatval($detail->height);
                                $intMargin = floatval($detail->margin);
                                $colorFrame =  isset($detail->colorframe) ? $detail->colorframe : "";
                                $material = isset($detail->material) ? $detail->material : "";
                                $marginStyle = isset($detail->style) && $detail->style == "Flotante" || isset($detail->style) && $detail->style == "Flotante sin marco interno" ? "Fondo" : "Paspartú";
                                $borderStyle = isset($detail->style) && $detail->style == "Flotante" ? "marco interno" : "bocel";
                                $glassStyle = isset($detail->idType) && $detail->idType  == 4 ? "Bastidor" : "Tipo de vidrio";
                                $measureFrame = ($intWidth+($intMargin*2))."cm X ".($intHeight+($intMargin*2))."cm";
                                if($detail->photo !=""){
                                    $img = '<a href="'.media().'/images/uploads/'.$detail->photo.'" target="_blank">Ver imagen</a><br>';
                                }
                                $description.='
                                        '.$img.'
                                        '.$detail->name.'
                                        <ul>
                                            <li><span class="fw-bold t-color-3">Referencia: </span>'.$detail->reference.'</li>
                                            <li><span class="fw-bold t-color-3">Color del marco: </span>'.$colorFrame.'</li>
                                            <li><span class="fw-bold t-color-3">Material: </span>'.$material.'</li>
                                            <li><span class="fw-bold t-color-3">Orientación: </span>'.$marginStyle.'</li>
                                            <li><span class="fw-bold t-color-3">Estilo de enmarcación: </span>'.$detail->style.'</li>
                                            <li><span class="fw-bold t-color-3">'.$marginStyle.': </span>'.(isset($detail->margin) ? $detail->margin : "nada").'cm</li>
                                            <li><span class="fw-bold t-color-3">Medida imagen: </span>'.$detail->width.'cm X '.$detail->height.'cm</li>
                                            <li><span class="fw-bold t-color-3">Medida marco: </span>'.$measureFrame.'</li>
                                            <li><span class="fw-bold t-color-3">Color del '.$marginStyle.': </span>'.$detail->colormargin.'</li>
                                            <li><span class="fw-bold t-color-3">Color del '.$borderStyle.': </span>'.$detail->colorborder.'</li>
                                            <li><span class="fw-bold t-color-3">'.$glassStyle.': </span>'.(isset($detail->glass) ? $detail->glass : "").'</li>
                                        </ul>
                                ';
                            }else{
                                if($detail->img !="" && $detail->img !=null){
                                    $img = '<a href="'.media().'/images/uploads/'.$detail->img.'" target="_blank">Ver imagen</a><br>';
                                }
                                $htmlDetail ="";
                                $arrDet = $detail->detail;
                                foreach ($arrDet as $d) {
                                    $htmlDetail.='<li><span class="fw-bold t-color-3">'.$d->name.': </span>'.$d->value.'</li>';
                                }
                                $description = $img.$detail->name.'<ul>'.$htmlDetail.'</ul>';
                            }
                        }else{
                            $description=$data['description'];
                            $flag = substr($data['description'], 0,1) == "{" ? true : false;
                            if($flag){
                                $arrData = json_decode($data['description'],true);
                                $name = $arrData['name'];
                                $varDetail = $arrData['detail'];
                                $textDetail ="";
                                foreach ($varDetail as $d) {
                                    $textDetail .= '<li><span class="fw-bold t-color-3">'.$d['name'].':</span> '.$d['option'].'</li>';
                                }
                                $description = $name.'<ul>'.$textDetail.'</ul>';
                            }
                        }
                        $total = $data['price'] * $data['quantity'];
                        $request[$i] = $data;
                        $request[$i]['total'] = formatNum($total);
                        $request[$i]['price'] = formatNum($request[$i]['price']);
                        $request[$i]['description'] = $description;
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
        public function updateOrder(){
            if($_SESSION['permitsModule']['u']){
                if($_POST){
                    if(empty($_POST['id']) || empty($_POST['status_order'])){
                        $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $id = intval($_POST['id']);
                        $statusOrder = strClean($_POST['status_order']);
                        $request = $this->model->updateOrder($id,$statusOrder);
                        if($request > 0){
                            $arrResponse = array("status"=>true,"msg"=>"Pedido actualizado");
                        }else{
                            $arrResponse = array("status"=>false,"msg"=>"No se ha podido actualizar, inténtelo de nuevo.");
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }
        public function delOrder(){
            if($_SESSION['permitsModule']['d']){
                if($_POST){
                    if(empty($_POST['id'])){
                        $arrResponse=array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $id = intval($_POST['id']);
                        $request = $this->model->deleteOrder($id);
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
    }
?>