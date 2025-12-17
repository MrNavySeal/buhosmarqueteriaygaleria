<?php
    class Descuentos extends Controllers{
        public function __construct(){
            session_start();
            if(empty($_SESSION['login'])){
                header("location: ".base_url());
                die();
            }
            parent::__construct();
        }
        /*************************Views*******************************/
        public function cupones(){
            if($_SESSION['permitsModule']['r']){
                $data['botones'] = [
                    "duplicar" => ["mostrar"=>true, "evento"=>"onclick","funcion"=>"mypop=window.open('".BASE_URL.$_SESSION['permitsModule']['route']."','','');mypop.focus();"],
                    "nuevo" => ["mostrar"=>$_SESSION['permitsModule']['w'], "evento"=>"onclick","funcion"=>"addItem()"],
                ];
                $data['page_tag'] = implode(" | ",[$_SESSION['permitsModule']['option'],$_SESSION['permitsModule']['module']]);
                $data['page_title'] = implode(" | ",[$_SESSION['permitsModule']['option'],$_SESSION['permitsModule']['module']]);
                $data['page_name'] = strtolower($_SESSION['permitsModule']['option']);
                $data['panelapp'] = "/Marketing/functions_coupon.js";
                $this->views->getView($this,"cupones",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }

        public function descuentos(){
            if($_SESSION['permitsModule']['r']){
                $data['botones'] = [
                    "duplicar" => ["mostrar"=>true, "evento"=>"onclick","funcion"=>"mypop=window.open('".BASE_URL.$_SESSION['permitsModule']['route']."','','');mypop.focus();"],
                    "nuevo" => ["mostrar"=>$_SESSION['permitsModule']['w'], "evento"=>"@click","funcion"=>"openModal()"],
                ];
                $data['page_tag'] = implode(" | ",[$_SESSION['permitsModule']['option'],$_SESSION['permitsModule']['module']]);
                $data['page_title'] = implode(" | ",[$_SESSION['permitsModule']['option'],$_SESSION['permitsModule']['module']]);
                $data['page_name'] = strtolower($_SESSION['permitsModule']['option']);
                $data['script_type'] = "module";
                $data['panelapp'] = "/Marketing/functions_discount.js";
                $this->views->getView($this,"descuentos",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        /*************************Coupon methods*******************************/
        public function getCoupons(){
            if($_SESSION['permitsModule']['r']){
                $html="";
                $request = $this->model->selectCoupons();
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 

                        $status="";
                        $btnEdit="";
                        $btnDelete="";
                        
                        if($_SESSION['permitsModule']['u']){
                            $btnEdit = '<button class="btn btn-success m-1" type="button" title="Edit" data-id="'.$request[$i]['id'].'" name="btnEdit"><i class="fas fa-pencil-alt"></i></button>';
                        }
                        if($_SESSION['permitsModule']['d'] && $request[$i]['id'] != 1){
                            $btnDelete = '<button class="btn btn-danger m-1" type="button" title="Delete" data-id="'.$request[$i]['id'].'" name="btnDelete"><i class="fas fa-trash-alt"></i></button>';
                        }
                        if($request[$i]['status']==1){
                            $status='<span class="badge me-1 bg-success">Activo</span>';
                        }else{
                            $status='<span class="badge me-1 bg-danger">Inactivo</span>';
                        }
                        $html.='
                            <tr class="item" data-name="'.$request[$i]['code'].'">
                                <td data-label="Código: ">'.$request[$i]['code'].'</td>
                                <td data-label="Descuento: ">'.$request[$i]['discount'].'%</td>
                                <td data-label="Estado: ">'.$status.'</td>
                                <td data-label="Fecha de creación: ">'.$request[$i]['date'].'</td>
                                <td data-label="Fecha de actualización: ">'.$request[$i]['dateupdate'].'</td>
                                <td class="item-btn">'.$btnEdit.$btnDelete.'</td>
                            </tr>
                        ';
                    }
                    $arrResponse = array("status"=>true,"data"=>$html);
                }else{
                    $arrResponse = array("status"=>false,"msg"=>"No hay datos");
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }else{
                header("location: ".base_url());
                die();
            }
            
            die();
        }

        public function getCoupon(){
            if($_SESSION['permitsModule']['r']){

                if($_POST){
                    if(empty($_POST)){
                        $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $idCoupon = intval($_POST['idCoupon']);
                        $request = $this->model->selectCoupon($idCoupon);
                        if(!empty($request)){
                            $arrResponse = array("status"=>true,"data"=>$request);
                        }else{
                            $arrResponse = array("status"=>false,"msg"=>"Error, intenta de nuevo."); 
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

        public function setCoupon(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    if(empty($_POST['txtName']) || empty($_POST['statusList']) || empty($_POST['intDiscount'])){
                        $arrResponse = array("status" => false, "msg" => 'Error de datos');
                    }else{ 
                        $idCoupon = intval($_POST['idCoupon']);
                        $strCode = strtoupper(strClean($_POST['txtName']));
                        $intDiscount = intval(strClean($_POST['intDiscount']));
                        $intStatus = intval(strClean($_POST['statusList']));

                        if($idCoupon == 0){
                            if($_SESSION['permitsModule']['w']){
                                $option = 1;
                                $request= $this->model->insertCoupon($strCode,$intDiscount,$intStatus);
                            }
                        }else{
                            if($_SESSION['permitsModule']['u']){
                                $option = 2;
                                $request = $this->model->updateCoupon($idCoupon,$strCode,$intDiscount,$intStatus);
                            }
                        }
                        if($request > 0 ){
                            if($option == 1){
                                $arrResponse = array('status' => true, 'msg' => 'Datos guardados.');
                            }else{
                                $arrResponse = array('status' => true, 'msg' => 'Datos actualizados.');
                            }
                        }else if($request == 'exist'){
                            $arrResponse = array('status' => false, 'msg' => '¡Atención! El cupón ya existe, intente con otro código.');		
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

        public function delCoupon(){
            if($_SESSION['permitsModule']['d']){

                if($_POST){
                    if(empty($_POST['idCoupon'])){
                        $arrResponse=array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $id = intval($_POST['idCoupon']);
                        $request = $this->model->deleteCoupon($id);
                        if($request=="ok"){
                            $arrResponse = array("status"=>true,"msg"=>"Se ha eliminado");
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
        /*************************Discount methods*******************************/
        public function setDatos(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    $arrData = json_decode($_POST['data'],true);
                    
                    $arrValidData = [];
                    if($arrData['type'] == 1){
                        $arrValidData = ["discount"=>$arrData['discount']];
                        $arrValidFields = ["discount"=>"required|greater:0;descuento"];
                    }else{
                        $arrValidData = [ "wholesale"=>$arrData['wholesale_discount']];
                        $arrValidFields = ["wholesale"=>"required|min:1;descuentos al por mayor"];
                    }
                    $errores = validator()->validate($arrValidFields,$arrValidData)->getErrors();

                    if(!empty($errores)){
                        $arrResponse = ["status"=>false,"msg"=>"Por favor, revise los campos.","errors"=>$errores];
                    }else{
                        $intId = intval($arrData['id']);
                        if($intId == 0){
                            if($_SESSION['permitsModule']['w']){
                                $option = 1;
                                $request= $this->model->insertDescuento($arrData);
                            }
                        }else{
                            if($_SESSION['permitsModule']['u']){
                                $option = 2;
                                $request = $this->model->updateDescuento($intId,$arrData);
                            }
                        }

                        if($request > 0 ){
                            if($option == 1){
                                $this->model->applyDiscount($request);
                                $arrResponse = array('status' => true, 'msg' => 'Datos guardados.');
                            }else{
                                $this->model->applyDiscount($intId);
                                $arrResponse = array('status' => true, 'msg' => 'Datos actualizados.');
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

        public function getDescuentos(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    $intPage = intval($_POST['page']);
                    $intPerPage = intval($_POST['per_page']);
                    $strSearch = strClean($_POST['search']);
                    $request = $this->model->selectDescuentos($intPage,$intPerPage,$strSearch);
                    echo json_encode($request,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }

        public function getDescuento(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    $id = intval($_POST['id']);
                    $request = $this->model->selectDescuento($id);
                    if(!empty($request)){   
                        $request['wholesale'] = $request['wholesale'] != "" && $request['wholesale'] != "null" ? json_decode($request['wholesale'],true) : [];
                        $arrResponse = array("status"=>true,"data"=>$request); 
                    }else{
                        $arrResponse = array("status"=>false,"msg"=>"Error, intenta de nuevo."); 
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }

        public function getSelectCategorias(){
            $intPage = intval($_POST['page']);
            $intPerPage = intval($_POST['per_page']);
            $strSearch = strClean($_POST['search']);
            $request = getPaginacionCategorias($intPage,$intPerPage,$strSearch);
            echo json_encode($request,JSON_UNESCAPED_UNICODE);
            die();
        }

        public function getSelectSubcategorias(){
            $intId = intval($_POST['id']);
            $intPage = intval($_POST['page']);
            $intPerPage = intval($_POST['per_page']);
            $strSearch = strClean($_POST['search']);
            $request = getPaginacionSubcategorias($intId,$intPage,$intPerPage,$strSearch);
            echo json_encode($request,JSON_UNESCAPED_UNICODE);
            die();
        }

        public function delDescuento(){
            if($_SESSION['permitsModule']['d']){
                if($_POST){

                    $id = intval($_POST['id']);
                    $request = $this->model->selectDescuento($id);
                    if(!empty($request)){
                        $this->model->applyDiscount($id,true);
                        $request = $this->model->deleteDescuento($id);
                        if($request=="ok"){
                            $arrResponse = array("status"=>true,"msg"=>"Se ha eliminado");
                        }else{
                            $arrResponse = array("status"=>false,"msg"=>"No se ha podido eliminar, intenta de nuevo.");
                        }   
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }
    }
?>