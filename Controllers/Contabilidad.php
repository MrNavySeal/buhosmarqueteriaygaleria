<?php
    
    class Contabilidad extends Controllers{
        public function __construct(){
            session_start();
            parent::__construct();
            sessionCookie();
            getPermits(7);
        }

        public function contabilidad(){
            if($_SESSION['permitsModule']['r']){
                $data['page_tag'] = "costo/gasto";
                $data['page_title'] = "Contabilidad";
                $data['page_name'] = "contabilidad";
                $data['contabilidad'] = $this->getCosts();
                $data['orders'] = $this->getOrders();
                $year = date('Y');
                $month = date('m');
                $data['resumenMensual'] = $this->model->selectAccountMonth($year,$month);
                $data['resumenAnual'] = $this->model->selectAccountYear($year);

                $data['app'] = "functions_contabilidad.js";
                $this->views->getView($this,"contabilidad",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function getContabilidadMes(){
            if($_POST){
                    if($_SESSION['permitsModule']['r']){
                    $arrDate = explode(" - ",$_POST['date']);
                    $month = $arrDate[0];
                    $year = $arrDate[1];
                    $request = $this->model->selectAccountMonth($year,$month);
                    
                    $ingresos = $request['ingresos']['total'];
                    $costos = $request['costos']['total'];
                    $gastos=$request['gastos']['total'];
                    $neto = $ingresos-($costos+$gastos);
                    
                    $html ="";
                    if($neto < 0){
                        $html = '<span class="text-danger">'.formatNum($neto).'</span>';
                    }else{
                        $html = '<span class="text-success">'.formatNum($neto).'</span>';
                    }
                    $request['dataingresos'] = $request['ingresos'];
                    $request['datacostos'] = $request['costos'];
                    $request['datagastos'] = $request['gastos'];
                    $request['mes'] =$request['ingresos']['month'];
                    $request['anio'] = $request['ingresos']['year'];
                    $request['ingresos'] =formatNum($ingresos);
                    $request['costos'] =formatNum($costos);
                    $request['gastos'] =formatNum($gastos);
                    $request['neto'] = $html;
                    $request['chart'] = "month";
                    $request['script'] = getFile("Template/Chart/chart",$request);
                    echo json_encode($request,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }
        public function getContabilidadAnio(){
            if($_POST){
                if(empty($_POST['date'])){
                    $arrResponse=array("status"=>false,"msg"=>"Error de datos");
                }else{
                    //$year = intval($_POST['date']);
                    $strYear = strval($_POST['date']);
                    if(strlen($strYear)>4){
                        $arrResponse=array("status"=>false,"msg"=>"La fecha es incorrecta."); 
                    }else{
                        $year = intval($_POST['date']);
                        $request = $this->model->selectAccountYear($year);
                        $ingresos = $request['total'];
                        $costos = $request['costos'];
                        $gastos = $request['gastos'];
                        $neto = $ingresos-($costos+$gastos);
                        
                        $html ="";
                        if($neto < 0){
                            $html = '<span class="text-danger">'.formatNum($neto).'</span>';
                        }else{
                            $html = '<span class="text-success">'.formatNum($neto).'</span>';
                        }
                        //dep($html);exit;
                        $arrData = array("anio"=>$year,"ingresos"=>formatNum($ingresos),"costos"=>formatNum($costos),"gastos"=>formatNum($gastos),"neto"=>$html);
                        $request['chart'] = "year";
                        $request['contabilidad'] = $arrData;
                        $script = getFile("Template/Chart/chart",$request);
                        $arrResponse=array("status"=>true,"data"=>$arrData,"script"=>$script); 
                    }
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function getOrders($option=null,$params=null){
            if($_SESSION['permitsModule']['r']){
                $html="";
                $request = $this->model->selectOrders();
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 

                        $btnView='<a href="'.base_url().'/pedidos/pedido/'.$request[$i]['idorder'].'" target="_blank" class="btn btn-info text-white m-1" type="button" title="View order" name="btnView"><i class="fas fa-eye"></i></a>';
                        $btnPaypal='';
                        $btnDelete ="";
                        $html.='
                                <tr class="item">
                                    <td><strong>ID</strong>'.$request[$i]['idorder'].'</td>
                                    <td><strong>Total</strong>'.formatNum($request[$i]['amount']).'</td>
                                    <td><strong>Fecha</strong>'.$request[$i]['date'].'</td>
                                    <td class="item-btn">'.$btnView.'</td>
                                </tr>
                            '; 
                    }
                    $arrResponse = array("status"=>true,"data"=>$html,"datamanagement"=>$request);
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
        public function getCosts($option=null,$params=null){
            if($_SESSION['permitsModule']['r']){
                $html="";
                $request="";
                if($option == 1){
                    $request = $this->model->search($params);
                }else if($option == 2){
                    $request = $this->model->sort($params);
                }else{
                    $request = $this->model->selectCosts();
                }
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 

                        $btnEdit="";
                        $btnDelete="";
                        $status="";
                        $type = "";
                        $btnView = '<button class="btn btn-info m-1" type="button" title="Watch" data-id="'.$request[$i]['id'].'" name="btnView"><i class="fas fa-eye"></i></button>';
                        if($_SESSION['permitsModule']['u']){
                            $btnEdit = '<button class="btn btn-success m-1" type="button" title="Edit" data-id="'.$request[$i]['id'].'" name="btnEdit"><i class="fas fa-pencil-alt"></i></button>';
                        }
                        if($_SESSION['permitsModule']['d']){
                            $btnDelete = '<button class="btn btn-danger m-1" type="button" title="Delete" data-id="'.$request[$i]['id'].'" name="btnDelete"><i class="fas fa-trash-alt"></i></button>';
                        }
                        if($request[$i]['type']==1){
                            $type='Costo';
                        }else{
                            $type='Gasto';
                        }
                        $html.='
                            <tr class="item" data-name="'.$request[$i]['name'].'">
                                <td><strong>NIT:</strong>'.$request[$i]['nit'].'</td>
                                <td><strong>Nombre de empresa:</strong>'.$request[$i]['name'].'</td>
                                <td><strong>Tipo:</strong>'.$type.'</td>
                                <td><strong>Total:</strong>'.formatNum($request[$i]['total']).'</td>
                                <td><strong>Fecha: </strong>'.$request[$i]['date'].'</td>
                                <td class="item-btn">'.$btnView.$btnEdit.$btnDelete.'</td>
                            </tr>
                        ';
                    }
                    $arrResponse = array("status"=>true,"data"=>$html,"datamanagement"=>$request);
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
        public function getCost(){
            if($_SESSION['permitsModule']['r']){

                if($_POST){
                    if(empty($_POST)){
                        $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $id = intval($_POST['idContabilidad']);
                        $request = $this->model->selectCost($id);
                        $request['priceFormat'] = formatNum($request['total']);
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
        public function setCost(){
            //dep($_POST);exit;
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    if(empty($_POST['txtName']) || empty($_POST['typeList']) || empty($_POST['txtDescription']) || empty($_POST['txtAmount'])){
                        $arrResponse = array("status" => false, "msg" => 'Error de datos');
                    }else{ 
                        $id = intval($_POST['idContabilidad']);
                        $strName = ucwords(strClean($_POST['txtName']));
                        $strDescription = strClean($_POST['txtDescription']);
                        $intStatus = intval($_POST['typeList']);
                        $strNit = strClean($_POST['txtNit']);
                        $intTotal = intval($_POST['txtAmount']);
                        $strDate = $_POST['strDate'];
                        if($id == 0){
                            if($_SESSION['permitsModule']['w']){
                                $option = 1;

                                $request= $this->model->insertCost($intStatus,$strDate,$strNit,$strName,$strDescription,$intTotal);
                            }
                        }else{
                            if($_SESSION['permitsModule']['u']){
                                $option = 2;
                                $request = $this->model->updateCost($id,$intStatus,$strDate,$strNit,$strName,$strDescription,$intTotal);
                            }
                        }
                        if($request > 0 ){
                            if($option == 1){
                                $arrResponse = $this->getCosts();
                                $arrResponse['msg'] = 'Datos guardados.';
                            }else{
                                $arrResponse = $this->getCosts();
                                $arrResponse['msg'] = 'Datos actualizados.';
                            }
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
        public function delCost(){
            if($_SESSION['permitsModule']['d']){

                if($_POST){
                    if(empty($_POST['idContabilidad'])){
                        $arrResponse=array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $id = intval($_POST['idContabilidad']);
                        $request = $this->model->deleteCost($id);

                        if($request=="ok"){
                            $arrResponse = array("status"=>true,"msg"=>"Se ha eliminado.","data"=>$this->getCosts()['data']);
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
        public function search($params){
            if($_SESSION['permitsModule']['r']){
                $search = strClean($params);
                $arrResponse = $this->getCosts(1,$params);
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function sort($params){
            if($_SESSION['permitsModule']['r']){
                $params = intval($params);
                $arrResponse = $this->getCosts(2,$params);
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }


    }
?>