<?php
    
    class Contabilidad extends Controllers{
        public function __construct(){
            session_start();
            parent::__construct();
            sessionCookie();
            getPermits(7);
        }
        public function categorias(){
            if($_SESSION['permitsModule']['r']){
                $data['page_tag'] = "Categorias";
                $data['page_title'] = "Contabilidad | Categorias";
                $data['page_name'] = "categorias";
                $data['panelapp'] = "functions_countcategory.js";
                $this->views->getView($this,"categorias",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function ingreso(){
            if($_SESSION['permitsModule']['r']){
                $data['page_tag'] = "Ingresos";
                $data['page_title'] = "Contabilidad | Ingresos";
                $data['page_name'] = "ingresos";
                $data['categories'] = $this->model->selectCatIncome(3);
                $data['panelapp'] = "functions_countincome.js";
                $this->views->getView($this,"ingreso",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function egreso(){
            if($_SESSION['permitsModule']['r']){
                $data['page_tag'] = "Egresos";
                $data['page_title'] = "Contabilidad | Egresos";
                $data['page_name'] = "egresos";
                $data['categories'] = $this->model->selectCatIncome(1);
                $data['panelapp'] = "functions_countegress.js";
                $this->views->getView($this,"egreso",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function movimientos(){
            if($_SESSION['permitsModule']['r']){
                $data['page_tag'] = "Caja";
                $data['page_title'] = "Contabilidad | Movimientos";
                $data['page_name'] = "movimientos";
                $data['panelapp'] = "functions_movimientos.js";
                $this->views->getView($this,"movimientos",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function informe(){
            if($_SESSION['permitsModule']['r']){
                $data['page_tag'] = "Informe";
                $data['page_title'] = "Contabilidad | Informe";
                $data['page_name'] = "informe";
                $data['categories'] = $this->model->selectCatIncome(1);
                $data['panelapp'] = "functions_countinfo.js";
                $year = date('Y');
                $month = date('m');
                $data['resumenMensual'] = $this->model->selectAccountMonth($year,$month);
                $data['resumenAnual'] = $this->model->selectAccountYear($year);
                $data['info'] = $this->model->selectEgressMonth($year,$month);
                $data['infoAnual'] = $this->model->selectEgressYear($year);
                $this->views->getView($this,"informe",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        /*************************Category methods*******************************/
        public function getCategories(){
            if($_SESSION['permitsModule']['r']){
                $request = $this->model->selectCategories();
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 
                        
                        $btnEdit="";
                        $btnDelete="";
                        $status="";
                        $type="";
                        if($_SESSION['permitsModule']['u']){
                            $btnEdit = '<button class="btn btn-success m-1" type="button" title="Edit" onclick="editItem('.$request[$i]['id'].')" name="btnEdit"><i class="fas fa-pencil-alt"></i></button>';
                        }
                        if($_SESSION['permitsModule']['d']){
                            $btnDelete = '<button class="btn btn-danger m-1" type="button" title="Delete" onclick="deleteItem('.$request[$i]['id'].')" name="btnDelete"><i class="fas fa-trash-alt"></i></button>';
                        }
                        if($request[$i]['status']==1){
                            $status='<span class="badge me-1 bg-success">Activo</span>';
                        }else{
                            $status='<span class="badge me-1 bg-danger">Inactivo</span>';
                        }
                        if($request[$i]['type']==1){
                            $type='Gastos';
                        }else if($request[$i]['type']==2){
                            $type='Costos';
                        }else{
                            $type='Ingresos';
                        }
                        $request[$i]['type'] = $type;
                        $request[$i]['status'] = $status;
                        $request[$i]['options'] = $btnEdit.$btnDelete;
                    }
                }
                echo json_encode($request,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function getCategory(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    if(empty($_POST)){
                        $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $idCategory = intval($_POST['idCategory']);
                        $request = $this->model->selectCategory($idCategory);
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
        public function setCategory(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    if(empty($_POST['txtName']) || empty($_POST['typeList']) || empty($_POST['statusList'])){
                        $arrResponse = array("status" => false, "msg" => 'Error de datos');
                    }else{ 
                        $idCategory = intval($_POST['idCategory']);
                        $strName = ucwords(strClean($_POST['txtName']));
                        $intType = intval($_POST['typeList']);
                        $intStatus = intval($_POST['statusList']);

                        if($idCategory == 0){
                            if($_SESSION['permitsModule']['w']){
                                $option = 1;
                                $request= $this->model->insertCategory($strName,$intType,$intStatus);
                            }
                        }else{
                            if($_SESSION['permitsModule']['u']){
                                $option = 2;
                                $request= $this->model->updateCategory($idCategory,$strName,$intType,$intStatus);
                            }
                        }
                        if($request > 0 ){
                            if($option == 1){
                                $arrResponse = array("status"=>true,"msg"=>"Datos guardados");
                            }else{
                                $arrResponse = array("status"=>true,"msg"=>"Datos actualizados");
                            }
                        }else if($request == 'exist'){
                            $arrResponse = array('status' => false, 'msg' => 'La categoría ya existe, prueba con otro nombre.');		
                        }else{
                            $arrResponse = array("status" => false, "msg" => 'No es posible guardar los datos.');
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
			die();
		}
        public function delCategory(){
            if($_SESSION['permitsModule']['d']){

                if($_POST){
                    if(empty($_POST['idCategory'])){
                        $arrResponse=array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $id = intval($_POST['idCategory']);
                        $request = $this->model->deleteCategory($id);

                        if($request=="ok"){
                            $arrResponse = array("status"=>true,"msg"=>"Se ha eliminado.");
                        }else if($request=="exists"){
                            $arrResponse = array("status"=>false,"msg"=>"La categoría tiene datos asignados, eliminelos e intente de nuevo.");
                        }else{
                            $arrResponse = array("status"=>false,"msg"=>"No es posible eliminar, intenta de nuevo.");
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }
        /*************************Income methods*******************************/
        public function setIncome(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    if(empty($_POST['typeList']) || empty($_POST['txtAmount']) || empty($_POST['statusList'])){
                        $arrResponse = array("status" => false, "msg" => 'Error de datos');
                    }else{ 
                        $idIncome = intval($_POST['idIncome']);
                        $strName = ucwords(strClean($_POST['txtName']));
                        $intTopic = intval($_POST['typeList']);
                        $intAmount = intval($_POST['txtAmount']);
                        $intType = 3;
                        $strDate = strClean($_POST['txtDate']);
                        $intStatus = intval($_POST['statusList']);
                        $strMethod = strClean($_POST['subType']);
                        if($idIncome == 0){
                            if($_SESSION['permitsModule']['w']){
                                $option = 1;
                                $request= $this->model->insertIncome($intType,$intTopic,$strName,$intAmount,$strDate,$intStatus,$strMethod);
                            }
                        }else{
                            if($_SESSION['permitsModule']['u']){
                                $option = 2;
                                $request= $this->model->updateIncome($idIncome,$intType,$intTopic,$strName,$intAmount,$strDate,$intStatus,$strMethod);
                            }
                        }
                        if($request > 0 ){
                            if($option == 1){
                                $arrResponse = array("status"=>true,"msg"=>"Datos guardados");
                            }else{
                                $arrResponse = array("status"=>true,"msg"=>"Datos actualizados");
                            }
                        }else{
                            $arrResponse = array("status" => false, "msg" => 'No es posible guardar los datos.');
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
			die();
        }
        public function getIncomes(){
            if($_SESSION['permitsModule']['r']){
                $request = $this->model->selectIncomes();
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 
                        $btnEdit="";
                        $btnDelete="";
                        $status="";
                        $type="";
                        if($_SESSION['permitsModule']['u'] && $request[$i]['category_id'] != 1){
                            $btnEdit = '<button class="btn btn-success m-1" type="button" title="Edit" onclick="editItem('.$request[$i]['id_income'].')"><i class="fas fa-pencil-alt"></i></button>';
                        }
                        if($_SESSION['permitsModule']['d'] && $request[$i]['category_id'] != 1){
                            $btnDelete = '<button class="btn btn-danger m-1" type="button" title="Delete" onclick="deleteItem('.$request[$i]['id_income'].')" ><i class="fas fa-trash-alt"></i></button>';
                        }
                        if($request[$i]['estado']==1){
                            $status='<span class="badge me-1 bg-success">Activo</span>';
                        }else{
                            $status='<span class="badge me-1 bg-danger">Inactivo</span>';
                        }
                        $request[$i]['options'] = $btnEdit.$btnDelete;
                        $request[$i]['estado'] = $status;
                        $request[$i]['amount'] = formatNum($request[$i]['amount']);
                    }
                    echo json_encode($request,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }
        public function getIncome(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    if(empty($_POST)){
                        $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $id = intval($_POST['idIncome']);
                        $request = $this->model->selectIncome($id);
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
        public function delIncome(){
            if($_SESSION['permitsModule']['d']){
                if($_POST){
                    if(empty($_POST['idIncome'])){
                        $arrResponse=array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $id = intval($_POST['idIncome']);
                        $request = $this->model->deleteIncome($id);

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
        /*************************Egress methods*******************************/
        public function setEgress(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    if(empty($_POST['categoryList']) || empty($_POST['typeList']) || empty($_POST['txtAmount']) || empty($_POST['statusList'])){
                        $arrResponse = array("status" => false, "msg" => 'Error de datos');
                    }else{ 
                        $id = intval($_POST['id']);
                        $strName = ucwords(strClean($_POST['txtName']));
                        $intTopic = intval($_POST['categoryList']);
                        $intAmount = intval($_POST['txtAmount']);
                        $intType = intval($_POST['typeList']);
                        $strDate = strClean($_POST['txtDate']);
                        $intStatus = intval($_POST['statusList']);
                        $strMethod = strClean($_POST['subType']);

                        if($id == 0){
                            if($_SESSION['permitsModule']['w']){
                                $option = 1;
                                $request= $this->model->insertEgress($intType,$intTopic,$strName,$intAmount,$strDate,$intStatus,$strMethod);
                            }
                        }else{
                            if($_SESSION['permitsModule']['u']){
                                $option = 2;
                                $request= $this->model->updateEgress($id,$intType,$intTopic,$strName,$intAmount,$strDate,$intStatus,$strMethod);
                            }
                        }
                        if($request > 0 ){
                            if($option == 1){
                                $arrResponse = array("status"=>true,"msg"=>"Datos guardados");
                            }else{
                                $arrResponse = array("status"=>true,"msg"=>"Datos actualizados");
                            }
                        }else{
                            $arrResponse = array("status" => false, "msg" => 'No es posible guardar los datos.');
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
			die();
        }
        public function getOutgoings(){
            if($_SESSION['permitsModule']['r']){
                $request = $this->model->selecOutgoings();
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 
                        
                        $btnEdit="";
                        $btnDelete="";
                        $status="";
                        $type="";
                        if($_SESSION['permitsModule']['u'] && $request[$i]['category_id'] != 2){
                            $btnEdit = '<button class="btn btn-success m-1" type="button" title="Edit" onclick="editItem('.$request[$i]['id_egress'].')"><i class="fas fa-pencil-alt"></i></button>';
                        }
                        if($_SESSION['permitsModule']['d'] && $request[$i]['category_id'] != 2){
                            $btnDelete = '<button class="btn btn-danger m-1" type="button" title="Delete" onclick="deleteItem('.$request[$i]['id_egress'].')"><i class="fas fa-trash-alt"></i></button>';
                        }
                        if($request[$i]['estado']==1){
                            $status='<span class="badge me-1 bg-success">Activo</span>';
                        }else{
                            $status='<span class="badge me-1 bg-danger">Inactivo</span>';
                        }
                        if($request[$i]['type_id'] == 1){
                            $type = "Gasto";
                        }else{
                            $type="Costo";
                        }

                        $request[$i]['options'] = $btnEdit.$btnDelete;
                        $request[$i]['estado'] = $status;
                        $request[$i]['amount'] = formatNum($request[$i]['amount']);
                        $request[$i]['type'] = $type;
                    }
                    echo json_encode($request,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }
        public function getEgress(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    if(empty($_POST)){
                        $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $id = intval($_POST['id']);
                        $request = $this->model->selectIncome($id);
                        $request['options'] = $this->getSelectCategories($request['type_id'],true);
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
        public function delEgress(){
            if($_SESSION['permitsModule']['d']){
                if($_POST){
                    if(empty($_POST['id'])){
                        $arrResponse=array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $id = intval($_POST['id']);
                        $request = $this->model->deleteIncome($id);

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
        public function getSelectCategories(int $option,bool $flag = false){
            $option = intval($option);
            $request = $this->model->selectCatIncome($option);
            $html="";
            for ($i=0; $i < count($request) ; $i++) { 
                $html.='<option value="'.$request[$i]['id'].'">'.$request[$i]['name'].'</option>';
            }
            $arrData = array("data"=>$html);
            if($flag){
                return $arrData;
            }else{
                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
            }
            die();
        }

        /*************************Info methods*******************************/
        public function getContabilidadMes(){
            if($_POST){
                if($_SESSION['permitsModule']['r']){
                    $arrDate = explode(" - ",$_POST['date']);
                    $month = $arrDate[0];
                    $year = $arrDate[1];
                    $request = $this->model->selectAccountMonth($year,$month);
                    
                    $ingresos = $request['ingresos']['total'];
                    $costos = $request['costos']['total'];
                    $gastos = $request['gastos']['total'];
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
                    $request['detail'] = $this->getInfoDetail($year,$month,$ingresos,$gastos,$costos,$neto);
                    echo json_encode($request,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }
        public function getInfoDetail(int $year,int $month,int $ingresos,int $gastos, int $costos,int $neto){
            $info = $this->model->selectEgressMonth($year,$month);
            $htmlG = '<tr><td colspan="2" class="text-center fw-bold">Gastos</td></tr>';
            $htmlC = '<tr><td colspan="2" class="text-center fw-bold">Costos</td></tr>';
            $htmlI = '<tr><td colspan="2" class="text-center fw-bold">Ingresos</td></tr>';
            
            for ($i=0; $i < count($info); $i++) { 
                //dep($info);exit;
                if($info[$i]['type'] == 1){
                    $htmlG.= '<tr><td>'.$info[$i]['name'].'</td><td>'.formatNum($info[$i]['total']).'</td></tr>';
                }else if($info[$i]['type'] == 2){
                    $htmlC.= '<tr><td>'.$info[$i]['name'].'</td><td>'.formatNum($info[$i]['total']).'</td></tr>';
                }else if($info[$i]['type'] == 3){
                    $htmlI.= '<tr><td>'.$info[$i]['name'].'</td><td>'.formatNum($info[$i]['total']).'</td></tr>';
                }
            }
            $htmlG.= '<tr><td class="text-end fw-bold">Total</td><td>'.formatNum($gastos).'</td></tr>';
            $htmlC.= '<tr><td class="text-end fw-bold">Total</td><td>'.formatNum($costos).'</td></tr>';
            $htmlI.= '<tr><td class="text-end fw-bold">Total</td><td>'.formatNum($ingresos).'</td></tr>';
            $tBody = $htmlC.$htmlG.$htmlI.'<tr><td class="text-end fw-bold">Ingresos - (costos+gastos)</td><td>'.formatNum($neto).'</td></tr>';
            return $tBody;
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
                        $request['chart'] = "year";
                        $script = getFile("Template/Chart/chart",$request);
                        $arrResponse=array("status"=>true,"script"=>$script,"info"=>$this->getInfoDetailYear($year,$ingresos,$gastos,$costos,$neto),"year"=>$year); 
                    }
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function getInfoDetailYear(int $year,int $ingresos,int $gastos, int $costos,int $neto){
            $info = $this->model->selectEgressYear($year);
            $tmonth='<tr><td>Descripción</td><td>Enero</td><td>Febrero</td><td>Marzo</td><td>Abril</td><td>Mayo</td>
            <td>Junio</td><td>Julio</td><td>Agosto</td><td>Septiembre</td><td>Octubre</td>
            <td>Noviembre</td><td>Diciembre</td><td>Monto</td></tr>';
            $htmlGA = '<tr class="bg-color-3"><td colspan="100" class="text-center fw-bold">Gastos</td></tr>';
            $htmlCA = '<tr class="bg-color-3"><td colspan="100" class="text-center fw-bold">Costos</td></tr>';
            $htmlIA = '<tr class="bg-color-3"><td colspan="100" class="text-center fw-bold">Ingresos</td></tr>';
            $htmlGA.=$tmonth;
            $htmlCA.=$tmonth;
            $htmlIA.=$tmonth;
            
            for ($i=0; $i < count($info); $i++) { 
                $month=$info[$i]['month'];
                $td="";
                $total=0; 
                if($info[$i]['type'] == 1){
                    for ($j=0; $j <count($month) ; $j++) { 
                        $total +=$month[$j+1];
                        $td.= '<td>'.formatNum($month[$j+1]).'</td>';
                    }
                    $htmlGA.= '<tr><td>'.$info[$i]['name'].'</td>'.$td.'</td><td>'.formatNum($total).'</td></tr>';
                }else if($info[$i]['type'] == 2){
                    for ($j=0; $j <count($month) ; $j++) { 
                        $total +=$month[$j+1];
                        $td.= '<td>'.formatNum($month[$j+1]).'</td>';
                    }
                    $htmlCA.= '<tr><td>'.$info[$i]['name'].'</td>'.$td.'</td><td>'.formatNum($total).'</td></tr>';
                }else if($info[$i]['type'] == 3){
                    for ($j=0; $j <count($month) ; $j++) { 
                        $total +=$month[$j+1];
                        $td.= '<td>'.formatNum($month[$j+1]).'</td>';
                    }
                    $htmlIA.= '<tr><td>'.$info[$i]['name'].'</td>'.$td.'</td><td>'.formatNum($total).'</td></tr>';
                }
            }
            $htmlGA.= '<tr><td class="text-end fw-bold" colspan="13">Total</td><td>'.formatNum($gastos).'</td></tr>';
            $htmlCA.= '<tr><td class="text-end fw-bold" colspan="13">Total</td><td>'.formatNum($costos).'</td></tr>';
            $htmlIA.= '<tr><td class="text-end fw-bold" colspan="13">Total</td><td>'.formatNum($ingresos).'</td></tr>';

            $tBody = $htmlCA.$htmlGA.$htmlIA.'<tr class="bg-color-2"><td class="text-end fw-bold" colspan="13">Ingresos - (costos+gastos)</td><td>'.formatNum($neto).'</td></tr>';
            return $tBody;
        }
        public function getMovements(){
            if($_SESSION['permitsModule']['r']){
                $request = $this->model->selectMovements();
                $movimientos = $request['movements'];
                if(!empty($movimientos)){
                    for ($i=0; $i < count($movimientos) ; $i++) { 
                        $movimientos[$i]['amount'] = formatNum($movimientos[$i]['amount']);
                        if($movimientos[$i]['type_id'] == 1){
                            $movimientos[$i]['type'] = "Gastos";
                        }else if($movimientos[$i]['type_id'] == 2){
                            $movimientos[$i]['type'] = "Costos";
                        }else{
                            $movimientos[$i]['type'] = "Ingresos";
                        }
                    }
                }
                echo json_encode($movimientos,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function getMovementsResume(){
            if($_SESSION['permitsModule']['r']){
                $request = $this->model->selectMovements();
                $resumen = $request['resume'];
                $total = 0;
                $arrData = [];
                if(!empty($resumen)){
                    $egresos = array_values(array_filter($resumen,function($e){return $e['type_id'] != 3;}));
                    $ingresos = array_values(array_filter($resumen,function($e){return $e['type_id'] == 3;}));
            
                    $totales = [];

                    foreach ($ingresos as $ingreso) {
                        if (!isset($totales[$ingreso['method']])) {
                            $totales[$ingreso['method']] = 0;
                        }
                        $totales[$ingreso['method']] += $ingreso['total'];
                    }

                    foreach ($egresos as $egreso) {
                        if (!isset($totales[$egreso['method']])) {
                            $totales[$egreso['method']] = 0;
                        }
                        $totales[$egreso['method']] -= $egreso['total'];
                    }

                    $totalGeneral = 0;
                    $detalle = [];

                    foreach ($totales as $method => $total) {
                        $detalle[] = ['method' => $method, 'total' => formatNum($total)];
                        $totalGeneral += $total;
                    }

                    $totalGeneral = formatNum($totalGeneral);

                    $arrData = [
                        'total' => $totalGeneral,
                        'detail' => $detalle
                    ];                  
                }
                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
    }
?>