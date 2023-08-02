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
                $data['data'] = $this->getCategories();
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
                $data['data'] = $this->getIncomes();
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
                $data['data'] = $this->getOutgoings();
                $data['categories'] = $this->model->selectCatIncome(1);
                $data['panelapp'] = "functions_countegress.js";
                $this->views->getView($this,"egreso",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        /*************************Category methods*******************************/
        public function getCategories($option=null,$params=null){
            if($_SESSION['permitsModule']['r']){
                $html="";
                $request = $this->model->selectCategories();
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 
                        
                        $btnEdit="";
                        $btnDelete="";
                        $status="";
                        $type="";
                        if($_SESSION['permitsModule']['u']){
                            $btnEdit = '<button class="btn btn-success m-1" type="button" title="Edit" data-id="'.$request[$i]['id'].'" name="btnEdit"><i class="fas fa-pencil-alt"></i></button>';
                        }
                        if($_SESSION['permitsModule']['d']){
                            $btnDelete = '<button class="btn btn-danger m-1" type="button" title="Delete" data-id="'.$request[$i]['id'].'" name="btnDelete"><i class="fas fa-trash-alt"></i></button>';
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
                        $html.='
                            <tr class="item" data-name="'.$request[$i]['name'].'">
                                <td data-label="ID: ">'.$request[$i]['id'].'</td>
                                <td data-label="Nombre: ">'.$request[$i]['name'].'</td>
                                <td data-label="Tipo: ">'.$type.'</td>
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
            }else{
                header("location: ".base_url());
                die();
            }
            die();
        }
        public function setCategory(){
            //dep($_POST);exit;
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
                                $arrResponse = $this->getCategories();
                                $arrResponse['msg'] = 'Datos guardados.';
                            }else{
                                $arrResponse = $this->getCategories();
                                $arrResponse['msg'] = 'Datos actualizados.';
                            }
                        }else if($request == 'exist'){
                            $arrResponse = array('status' => false, 'msg' => 'La categoría ya existe, prueba con otro nombre.');		
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
        public function delCategory(){
            if($_SESSION['permitsModule']['d']){

                if($_POST){
                    if(empty($_POST['idCategory'])){
                        $arrResponse=array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $id = intval($_POST['idCategory']);
                        $request = $this->model->deleteCategory($id);

                        if($request=="ok"){
                            $arrResponse = array("status"=>true,"msg"=>"Se ha eliminado.","data"=>$this->getCategories()['data']);
                        }else if($request=="exists"){
                            $arrResponse = array("status"=>false,"msg"=>"La categoría tiene datos asignados, eliminelos e intente de nuevo.");
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

                        if($idIncome == 0){
                            if($_SESSION['permitsModule']['w']){
                                $option = 1;
                                $request= $this->model->insertIncome($intType,$intTopic,$strName,$intAmount,$strDate,$intStatus);
                            }
                        }else{
                            if($_SESSION['permitsModule']['u']){
                                $option = 2;
                                $request= $this->model->updateIncome($idIncome,$intType,$intTopic,$strName,$intAmount,$strDate,$intStatus);
                            }
                        }
                        if($request > 0 ){
                            if($option == 1){
                                $arrResponse = $this->getIncomes();
                                $arrResponse['msg'] = 'Datos guardados.';
                            }else{
                                $arrResponse = $this->getIncomes();
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
            }
			die();
        }
        public function getIncomes($option=null,$params=null){
            if($_SESSION['permitsModule']['r']){
                $html="";
                $request = $this->model->selectIncomes();
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 
                        
                        $btnEdit="";
                        $btnDelete="";
                        $status="";
                        $type="";
                        if($_SESSION['permitsModule']['u'] && $request[$i]['category_id'] != 1){
                            $btnEdit = '<button class="btn btn-success m-1" type="button" title="Edit" data-id="'.$request[$i]['id_income'].'" name="btnEdit"><i class="fas fa-pencil-alt"></i></button>';
                        }
                        if($_SESSION['permitsModule']['d'] && $request[$i]['category_id'] != 1){
                            $btnDelete = '<button class="btn btn-danger m-1" type="button" title="Delete" data-id="'.$request[$i]['id_income'].'" name="btnDelete"><i class="fas fa-trash-alt"></i></button>';
                        }
                        if($request[$i]['estado']==1){
                            $status='<span class="badge me-1 bg-success">Activo</span>';
                        }else{
                            $status='<span class="badge me-1 bg-danger">Inactivo</span>';
                        }
                        $html.='
                            <tr class="item">
                                <td data-label="ID: ">'.$request[$i]['id_income'].'</td>
                                <td data-label="Fecha: ">'.$request[$i]['date'].'</td>
                                <td data-label="Categoria: ">'.$request[$i]['categoria'].'</td>
                                <td data-label="Concepto: ">'.$request[$i]['concepto'].'</td>
                                <td data-label="Monto: ">'.formatNum($request[$i]['amount']).'</td>
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
            }else{
                header("location: ".base_url());
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
                            $arrResponse = array("status"=>true,"msg"=>"Se ha eliminado.","data"=>$this->getIncomes()['data']);
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

                        if($id == 0){
                            if($_SESSION['permitsModule']['w']){
                                $option = 1;
                                $request= $this->model->insertEgress($intType,$intTopic,$strName,$intAmount,$strDate,$intStatus);
                            }
                        }else{
                            if($_SESSION['permitsModule']['u']){
                                $option = 2;
                                $request= $this->model->updateEgress($id,$intType,$intTopic,$strName,$intAmount,$strDate,$intStatus);
                            }
                        }
                        if($request > 0 ){
                            if($option == 1){
                                $arrResponse = $this->getOutgoings();
                                $arrResponse['msg'] = 'Datos guardados.';
                            }else{
                                $arrResponse = $this->getOutgoings();
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
            }
			die();
        }
        public function getOutgoings($option=null,$params=null){
            if($_SESSION['permitsModule']['r']){
                $html="";
                $request = $this->model->selecOutgoings();
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 
                        
                        $btnEdit="";
                        $btnDelete="";
                        $status="";
                        $type="";
                        if($_SESSION['permitsModule']['u'] && $request[$i]['category_id'] != 2){
                            $btnEdit = '<button class="btn btn-success m-1" type="button" title="Edit" data-id="'.$request[$i]['id_egress'].'" name="btnEdit"><i class="fas fa-pencil-alt"></i></button>';
                        }
                        if($_SESSION['permitsModule']['d'] && $request[$i]['category_id'] != 2){
                            $btnDelete = '<button class="btn btn-danger m-1" type="button" title="Delete" data-id="'.$request[$i]['id_egress'].'" name="btnDelete"><i class="fas fa-trash-alt"></i></button>';
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
                        $html.='
                            <tr class="item">
                                <td data-label="ID: ">'.$request[$i]['id_egress'].'</td>
                                <td data-label="Fecha: ">'.$request[$i]['date'].'</td>
                                <td data-label="Tipo: ">'.$type.'</td>
                                <td data-label="Categoria: ">'.$request[$i]['categoria'].'</td>
                                <td data-label="Concepto: ">'.$request[$i]['concepto'].'</td>
                                <td data-label="Monto: ">'.formatNum($request[$i]['amount']).'</td>
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
            }else{
                header("location: ".base_url());
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
                            $arrResponse = array("status"=>true,"msg"=>"Se ha eliminado.","data"=>$this->getOutgoings()['data']);
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
    }
?>