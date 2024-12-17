<?php
    class Cotizaciones extends Controllers{
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

        public function cotizaciones(){
            if($_SESSION['permitsModule']['r']){
                $data['page_tag'] = "cotizaciones";
                $data['page_title'] = "Cotizaciones | Pedidos";
                $data['page_name'] = "cotizaciones";
                $data['panelapp'] = "functions_quotes.js";
                $this->views->getView($this,"cotizaciones",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function setOrder(){
            if($_SESSION['permitsModule']['w']){
                if($_POST){
                    if(empty($_POST['id'])){
                        $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $id = intval($_POST['id']);
                        $statusOrder = intval($_POST['statusOrder']);
                        $paymentList = strClean($_POST['type']);
                        $request = $this->model->selectQuote($id);
                        if(!empty($request)){
                                $dateObj = new DateTime("now");
                                $currentDate = $dateObj->format("Y-m-d");
                                $dateCount = 0;
                                while ($dateCount < 30) {
                                    $dateObj->modify('+1 day');
                                    $dayWeek = $dateObj->format('N');
                                    if($dayWeek < 7){
                                        $dateCount++;
                                    }
                                }
                                $dateBeat = $dateObj->format("Y-m-d");
                                $request['date'] = $currentDate;
                                $request['date_beat'] = $dateBeat;
                                $request['status_order'] = $statusOrder;
                                $request['type'] = $paymentList;
                                $request = $this->model->insertOrder($id,$request);
                                if($request > 0){
                                    $arrResponse = array("status"=>true,"msg"=>"La cotizacion se ha facturado con éxito");
                                }else{
                                    $arrResponse = array("status"=>false,"msg"=>"Ha ocurrido un error, inténtelo de nuevo");
                                }
                            
                        }else{
                            $arrResponse = array("status"=>false,"msg"=>"El cliente no existe!"); 
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }
        public function getQuotes(){
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
                $data = $this->model->selectQuotes($idPersona,$strSearch,$intPerPage,$intPageNow,$strInitialDate,$strFinalDate);
                $request = $data['data'];
                $intTotalPages = $data['pages'];
                $total = $this->model->selectTotalQuotes($idPersona,$strSearch,$strInitialDate,$strFinalDate);
                
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
                        $btnView = '<button class="btn btn-info m-1 text-white" type="button" title="Ver" onclick="viewItem('.$request[$i]['id'].')"><i class="fas fa-eye"></i></button>';
                        $btnWpp="";
                        $btnPdf='<a href="'.base_url().'/factura/generarFactura/'.$request[$i]['id'].'" target="_blank" class="btn btn-primary text-white m-1" type="button" title="Imprimir factura"><i class="fas fa-print"></i></a>';
                        $btnEdit ="";
                        $status=$request[$i]['status'];
                        $currentDate = date("Y-m-d",strtotime("now"));
                        $strDateBeat = date("Y-m-d",strtotime($request[$i]['compare']));
                        if($currentDate == $strDateBeat){
                            $status = "vencido";
                        } 
                        $request[$i]['status'] = $status;

                        if($request[$i]['status'] =="cotizado"){
                            $status = '<span class="badge bg-warning text-black">Cotizado</span>';
                        }else if($request[$i]['status'] =="facturado"){
                            $status = '<span class="badge bg-success text-white">Facturado</span>';
                        }else if($request[$i]['status'] =="vencido"){
                            $status = '<span class="badge bg-danger text-white">Vencido</span>';
                        }
                        
                        if($_SESSION['permitsModule']['u'] && $request[$i]['status'] =="cotizado"){
                            $btnEdit = '<button class="btn btn-warning m-1" type="button" title="Facturar" onclick="editItem('.$request[$i]['id'].')"><i class="fas fa-shopping-cart"></i></button>';
                        }
                        if($_SESSION['userData']['roleid'] != 2){
                            $btnWpp='<a href="https://wa.me/57'.$request[$i]['phone'].'?text=Buen%20dia%20'.$request[$i]['name'].'" class="btn btn-success text-white m-1" type="button" title="Whatsapp" target="_blank"><i class="fab fa-whatsapp"></i></a>';
                        }
                        $request[$i]['format_amount'] = formatNum($request[$i]['amount']);
                        $request[$i]['status'] = $status;
                        $pro = $request[$i];
                        $html.='
                        <tr>
                            <td data-title="ID" class="text-center">'.$pro['id'].'</td>
                            <td data-title="Fecha de emisión" class="text-center">'.$pro['date'].'</td>
                            <td data-title="Fecha de vencimiento" class="text-center">'.$pro['date_beat'].'</td>
                            <td data-title="Nombre">'.$pro['name'].'</td>
                            <td data-title="Correo">'.$pro['email'].'</td>
                            <td data-title="Teléfono">'.$pro['phone'].'</td>
                            <td data-title="CC/NIT">'.$pro['identification'].'</td>
                            <td data-title="Total" class="text-end">'.$pro['format_amount'].'</td>
                            <td data-title="Estado" class="text-center">'.$status.'</td>
                            <td data-title="Opciones" ><div class="d-flex">'.$btnView.$btnWpp.$btnPdf.$btnEdit.'</div></td>
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
    }
?>