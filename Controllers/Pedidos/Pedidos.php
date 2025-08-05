<?php
    class Pedidos extends Controllers{
        public function __construct(){
            session_start();
            if(empty($_SESSION['login'])){
                header("location: ".base_url());
                die();
            }
            parent::__construct();
        }

        public function pedidos(){
            if($_SESSION['permitsModule']['r']){
                $data['botones'] = [
                    "duplicar" => ["mostrar"=>true, "evento"=>"onclick","funcion"=>"mypop=window.open('".BASE_URL.$_SESSION['permitsModule']['route']."','','');mypop.focus();"],
                    "nuevo" => ["mostrar"=>$_SESSION['permitsModule']['w'], "evento"=>"onclick","funcion"=>"window.location.href='".BASE_URL."/pedidos/punto-venta/'"],
                ];
                $data['page_tag'] = implode(" | ",[$_SESSION['permitsModule']['option'],$_SESSION['permitsModule']['module']]);
                $data['page_title'] = implode(" | ",[$_SESSION['permitsModule']['option'],$_SESSION['permitsModule']['module']]);
                $data['page_name'] = strtolower($_SESSION['permitsModule']['option']);
                $data['panelapp'] = "/Pedidos/functions_orders.js";
                $this->views->getView($this,"pedidos",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function creditos(){
            if($_SESSION['permitsModule']['r']){
                $data['botones'] = [
                    "duplicar" => ["mostrar"=>true, "evento"=>"onclick","funcion"=>"mypop=window.open('".BASE_URL.$_SESSION['permitsModule']['route']."','','');mypop.focus();"],
                    "nuevo" => ["mostrar"=>$_SESSION['permitsModule']['w'], "evento"=>"onclick","funcion"=>"window.location.href='".BASE_URL."/pedidos/punto-venta/'"],
                ];
                $data['page_tag'] = implode(" | ",[$_SESSION['permitsModule']['option'],$_SESSION['permitsModule']['module']]);
                $data['page_title'] = implode(" | ",[$_SESSION['permitsModule']['option'],$_SESSION['permitsModule']['module']]);
                $data['page_name'] = strtolower($_SESSION['permitsModule']['option']);
                $data['panelapp'] = "/Pedidos/functions_orders_creditos.js";
                $this->views->getView($this,"creditos",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function detalle(){
            if($_SESSION['permitsModule']['r']){
                $data['botones'] = [
                    "atras" => ["mostrar"=>true,"evento"=>"onclick","funcion"=>"window.location.href='".BASE_URL."/pedidos/'"],
                    "duplicar" => ["mostrar"=>true, "evento"=>"onclick","funcion"=>"mypop=window.open('".BASE_URL.$_SESSION['permitsModule']['route']."','','');mypop.focus();"],
                    "nuevo" => ["mostrar"=>$_SESSION['permitsModule']['w'], "evento"=>"onclick","funcion"=>"window.location.href='".BASE_URL."/pedidos/punto-venta/'"],
                ];
                $data['page_tag'] = implode(" | ",[$_SESSION['permitsModule']['option'],$_SESSION['permitsModule']['module']]);
                $data['page_title'] = implode(" | ",[$_SESSION['permitsModule']['option'],$_SESSION['permitsModule']['module']]);
                $data['page_name'] = strtolower($_SESSION['permitsModule']['option']);
                $data['panelapp'] = "/Pedidos/functions_orders_detail.js";
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
                $data['botones'] = [
                    "atras" => ["mostrar"=>true,"evento"=>"onclick","funcion"=>"window.location.href='".BASE_URL."/pedidos/'"],
                    "duplicar" => ["mostrar"=>true, "evento"=>"onclick","funcion"=>"mypop=window.open('".BASE_URL.$_SESSION['permitsModule']['route']."','','');mypop.focus();"],
                    "nuevo" => ["mostrar"=>$_SESSION['permitsModule']['w'], "evento"=>"onclick","funcion"=>"window.location.href='".BASE_URL."/pedidos/punto-venta/'"],
                ];
                $data['page_tag'] = "Transacción | ".$_SESSION['permitsModule']['module'];
                $data['page_title'] = "Transacción | ".$_SESSION['permitsModule']['module'];
                $data['page_name'] = strtolower($_SESSION['permitsModule']['module']);
                $data['panelapp'] = "/Pedidos/functions_orders.js";
                $this->views->getView($this,"transaccion",$data);
                
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function pdf($params){
            if($_SESSION['permitsModule']['r']){
                $data['page_title'] = " Factura de venta No. ".$params." | ".$_SESSION['permitsModule']['module'];
                $data['file_name'] = 'factura_venta_'.$params.'_'.rand()*10;
                $data['data'] = $this->model->selectOrder($params);
                $this->views->getView($this,"pedido-pdf",$data);
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
                $strSearch = strClean($_POST['search']);
                $intPerPage = intval($_POST['perpage']);
                $intPageNow = intval($_POST['page']);
                $strInitialDate = strClean($_POST['initial_date']);
                $strFinalDate = strClean($_POST['final_date']);
                $strStatusOrder = strClean($_POST['status_order']);
                $strStatusPayment = strClean($_POST['status_payment']);
                $intTotalAmount = 0;
                $intTotalPendent = 0;
                $arrData = $this->model->selectOrders($idPersona,$strSearch,$intPerPage,$intPageNow,$strInitialDate,$strFinalDate,$strStatusOrder,$strStatusPayment);
                $request = $arrData['data'];
                $html ="";
                $htmlTotal="";
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 
                        $btnView = '<button class="btn btn-info m-1 text-white" type="button" title="Ver" onclick="viewItem('.$request[$i]['idorder'].')"><i class="fas fa-eye"></i></button>';
                        $btnWpp="";
                        $btnPdf='<a href="'.base_url().'/pedidos/factura/'.$request[$i]['idorder'].'" target="_blank" class="btn btn-primary text-white m-1" type="button" title="Imprimir factura"><i class="fas fa-print"></i></a>';
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
                        }else if($request[$i]['statusorder'] =="enviado"){
                            $statusOrder = '<span class="badge bg-success text-white">Enviado</span>';
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
                        $request[$i]['format_pendent'] = formatNum($request[$i]['total_pendent']);
                        $request[$i]['actual_user'] = $_SESSION['userData']['firstname']." ".$_SESSION['userData']['lastname'];
                        $request[$i]['id_actual_user'] = $_SESSION['userData']['idperson'];
                        $pro = $request[$i];
                        $html.='
                        <tr>
                            <td data-title="ID" class="text-center">'.$pro['idorder'].'</td>
                            <td data-title="Transacción" class="text-center">'.$pro['idtransaction'].'</td>
                            <td data-title="Fecha" class="text-center">'.$pro['date'].'</td>
                            <td data-title="Nombre">'.$pro['name'].'</td>
                            <td data-title="Correo">'.$pro['email'].'</td>
                            <td data-title="Teléfono">'.$pro['phone'].'</td>
                            <td data-title="CC/NIT">'.$pro['identification'].'</td>
                            <td data-title="Método de pago" class="text-center">'.$pro['type'].'</td>
                            <td data-title="Total" class="text-end">'.$pro['format_amount'].'</td>
                            <td data-title="Total pendiente" class="text-end">'.$pro['format_pendent'].'</td>
                            <td data-title="Estado de pago" class="text-center">'.$status.'</td>
                            <td data-title="Estado de pedido" class="text-center">'.$statusOrder.'</td>
                            <td data-title="Opciones" ><div class="d-flex">'.$btnView.$btnWpp.$btnPdf.$btnPaypal.$btnEdit.$btnDelete.'</div></td>
                        </tr>
                        ';
                    }
                    foreach ($arrData['full_data'] as $data) {
                        $intTotalPendent += $data['total_pendent'];
                        $intTotalAmount +=$data['amount'];
                    }
                    $htmlTotal='
                        <tr class="fw-bold text-end">
                            <td data-title="Total">'.formatNum($intTotalAmount).'</td>
                            <td data-title="Total pendiente">'.formatNum($intTotalPendent).'</td>
                        </tr>
                    ';
                }
                $arrData['html'] = $html;
                $arrData['html_total'] = $htmlTotal;
                $arrData['html_pages'] = getPagination($intPageNow,$arrData['start_page'],$arrData['total_pages'],$arrData['limit_page']);
                $arrData['data'] = $request;
                $arrData['total_amount']= $intTotalAmount;
                $arrData['total_pendent'] = $intTotalPendent;
                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function getCreditOrders(){
            if($_SESSION['permitsModule']['r']){
                $idPersona = "";
                if($_SESSION['userData']['roleid'] == 2){
                    $idPersona = $_SESSION['idUser'];
                }
                $strSearch = strClean($_POST['search']);
                $intPerPage = intval($_POST['perpage']);
                $intPageNow = intval($_POST['page']);
                $strInitialDate = strClean($_POST['initial_date']);
                $strFinalDate = strClean($_POST['final_date']);
                $strStatusOrder = strClean($_POST['status_order']);
                $strStatusPayment = strClean($_POST['status_payment']);
                $intTotalAmount = 0;
                $intTotalPendent = 0;
                $arrData = $this->model->selectCreditOrders($idPersona,$strSearch,$intPerPage,$intPageNow,$strInitialDate,$strFinalDate,$strStatusOrder,$strStatusPayment);
                $request = $arrData['data'];
                $html ="";
                $htmlTotal="";
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 

                        $btnView = '<button class="btn btn-info m-1 text-white" type="button" title="Ver" onclick="viewItem('.$request[$i]['idorder'].')"><i class="fas fa-eye"></i></button>';
                        $btnWpp="";
                        $btnPdf='<a href="'.base_url().'/Pedidos/pdf/'.$request[$i]['idorder'].'" target="_blank" class="btn btn-primary text-white m-1" type="button" title="Imprimir factura"><i class="fas fa-print"></i></a>';
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
                        $request[$i]['format_pendent'] = formatNum($request[$i]['total_pendent']);
                        $pro=$request[$i];
                        $html.='
                        <tr>
                            <td data-title="ID" class="text-center">'.$pro['idorder'].'</td>
                            <td data-title="Transacción" class="text-center">'.$pro['idtransaction'].'</td>
                            <td data-title="Fecha" class="text-center">'.$pro['date'].'</td>
                            <td data-title="Nombre">'.$pro['name'].'</td>
                            <td data-title="Correo">'.$pro['email'].'</td>
                            <td data-title="Teléfono">'.$pro['phone'].'</td>
                            <td data-title="CC/NIT">'.$pro['identification'].'</td>
                            <td data-title="Método de pago" class="text-center">'.$pro['type'].'</td>
                            <td data-title="Total" class="text-end">'.$pro['format_amount'].'</td>
                            <td data-title="Total pendiente" class="text-end">'.$pro['format_pendent'].'</td>
                            <td data-title="Estado de pago" class="text-center">'.$status.'</td>
                            <td data-title="Estado de pedido" class="text-center">'.$statusOrder.'</td>
                            <td data-title="Opciones" ><div class="d-flex">'.$btnView.$btnWpp.$btnPdf.$btnPaypal.$btnEdit.$btnAdvance.$btnDelete.'</div></td>
                        </tr>
                        ';
                    }
                    foreach ($arrData['full_data'] as $data) {
                        $intTotalPendent += $data['total_pendent'];
                        $intTotalAmount +=$data['amount'];
                    }
                    $htmlTotal='
                        <tr class="fw-bold text-end">
                            <td data-title="Total">'.formatNum($intTotalAmount).'</td>
                            <td data-title="Total pendiente">'.formatNum($intTotalPendent).'</td>
                        </tr>
                    ';
                }
                $arrData['html'] = $html;
                $arrData['html_total'] = $htmlTotal;
                $arrData['html_pages'] = getPagination($intPageNow,$arrData['start_page'],$arrData['total_pages'],$arrData['limit_page']);
                $arrData['data'] = $request;
                $arrData['total_amount']= $intTotalAmount;
                $arrData['total_pendent'] = $intTotalPendent;
                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function getDetailOrders(){
            if($_SESSION['permitsModule']['r']){
                $idPersona = "";
                if($_SESSION['userData']['roleid'] == 2){
                    $idPersona = $_SESSION['idUser'];
                }
                $strSearch = strClean($_POST['search']);
                $intPerPage = intval($_POST['perpage']);
                $intPageNow = intval($_POST['page']);
                $strInitialDate = strClean($_POST['initial_date']);
                $strFinalDate = strClean($_POST['final_date']);
                $data = $this->model->selectDetailOrders($idPersona,$strSearch,$intPerPage,$intPageNow,$strInitialDate,$strFinalDate);
                $request = $data['data'];
                $intTotalPages = $data['pages'];
                $total = $this->model->selectTotalDetailOrders($idPersona,$strSearch,$strInitialDate,$strFinalDate);

                $maxButtons = 4;
                $totalPages = $intTotalPages;
                $page = $intPageNow;
                $startPage = max(1, $page - floor($maxButtons / 2));
                if ($startPage + $maxButtons - 1 > $totalPages) {
                    $startPage = max(1, $totalPages - $maxButtons + 1);
                }
                $html ="";
                $htmlPages = '
                    <li class="page-item">
                        <button type="button" class="page-link text-secondary" href="#" onclick="getData(1)" aria-label="First">
                            <span aria-hidden="true"><i class="fas fa-angle-double-left"></i></span>
                        </button>
                    </li>
                    <li class="page-item">
                        <button type="button" class="page-link text-secondary" href="#" onclick="getData('.max(1, $page-1).')" aria-label="Previous">
                            <span aria-hidden="true"><i class="fas fa-angle-left"></i></span>
                        </button>
                    </li>
                ';
                for ($i = $startPage; $i < min($startPage + $maxButtons, $totalPages + 1); $i++) {
                    $htmlPages .= '<li class="page-item">
                        <button type="button" class="page-link  '.($i == $page ? ' bg-primary text-white' : 'text-secondary').'" href="#" onclick="getData('.$i.')">'.$i.'</button>
                    </li>';
                }
                $htmlPages .= '
                    <li class="page-item">
                        <button type="button" class="page-link text-secondary" href="#" onclick="getData('.min($totalPages, $page+1).')" aria-label="Next">
                            <span aria-hidden="true"><i class="fas fa-angle-right"></i></span>
                        </button>
                    </li>
                    <li class="page-item">
                        <button type="button" class="page-link text-secondary" href="#" onclick="getData('.($intTotalPages).')" aria-label="Last">
                            <span aria-hidden="true"><i class="fas fa-angle-double-right"></i></span>
                        </button>
                    </li>
                ';
                
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
                        $request[$i] = $data;
                        $request[$i]['total'] = formatNum($data['price'] * $data['quantity']);
                        $request[$i]['price'] = formatNum($request[$i]['price']);
                        $request[$i]['description'] = $description;
                        $pro=$request[$i];
                        $html.='
                        <tr>
                            <td data-title="No. Factura" class="text-center">'.$pro['idorder'].'</td>
                            <td data-title="No. Transacción" class="text-center">'.$pro['idtransaction'].'</td>
                            <td data-title="Fecha" class="text-center">'.$pro['date'].'</td>
                            <td data-title="CC/NIT">'.$pro['identification'].'</td>
                            <td data-title="Nombre">'.$pro['name'].'</td>
                            <td data-title="Descripción">'.$description.'</td>
                            <td data-title="Precio">'.$pro['price'].'</td>
                            <td data-title="Cantidad" class="text-center">'.$pro['quantity'].'</td>
                            <td data-title="Total" class="text-end">'.$pro['total'].'</td>
                        </tr>
                        ';
                    }
                }
                $arrData = array(
                    "html"=>$html,
                    "html_pages"=>$htmlPages,
                    "total_records"=>$total,
                    "data"=>$request
                );
                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
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
                    }else if($_POST['status_order'] == "enviado" && (empty($_POST['send_by']) || empty($_POST['guide']) || empty($_POST['email']) ) ){
                        $arrResponse = array("status"=>false,"msg"=>"Faltan los datos de envio.");
                    }else{
                        $id = intval($_POST['id']);
                        $statusOrder = strClean($_POST['status_order']);
                        $strSendBy = strClean($_POST['send_by']);
                        $strGuide = strClean($_POST['guide']);
                        $isEmail = intval($_POST['is_email']);
                        $strEmail = strClean($_POST['email']);
                        $strName = strClean($_POST['name']);
                        $request = $this->model->updateOrder($id,$statusOrder,$strSendBy,$strGuide);
                        if($request > 0){
                            if($statusOrder == "enviado" && $isEmail){
                                $company = getCompanyInfo();
                                $arrResponse = array("status" => true,"msg"=>"Se ha registrado con éxito.");
                                $data = array(
                                    'nombreUsuario'=> $strName, 
                                    'email_remitente' => $company['email'], 
                                    'email_usuario'=>$strEmail, 
                                    'company'=>$company,
                                    'send_by'=>$strSendBy,
                                    'guide'=>$strGuide,
                                    'asunto' =>"¡Tu pedido está en camino!");
                                sendEmail($data,"email_send_by");
                            }
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