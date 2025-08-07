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
                    "nuevo" => ["mostrar"=>$_SESSION['permitsModule']['w'], "evento"=>"onclick","funcion"=>"openModal()"],
                ];
                $data['page_tag'] = implode(" | ",[$_SESSION['permitsModule']['option'],$_SESSION['permitsModule']['module']]);
                $data['page_title'] = implode(" | ",[$_SESSION['permitsModule']['option'],$_SESSION['permitsModule']['module']]);
                $data['page_name'] = strtolower($_SESSION['permitsModule']['option']);
                $data['panelapp'] = "/Marketing/functions_discount.js";
                $data['categories'] = $this->getSelectCategories();
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
        public function setDiscount(){
            //dep($_POST);exit;
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    if(empty($_POST['statusList']) || empty($_POST['intDiscount'])){
                        $arrResponse = array("status" => false, "msg" => 'Error de datos');
                    }else{ 
                        $idDiscount = intval($_POST['idDiscount']);
                        $idCategory = intval($_POST['categoryList']);
                        $idSubcategory = isset($_POST['subcategoryList']) ? intval($_POST['subcategoryList']) : 0;
                        $type = intval(intval($_POST['typeList']));
                        $intDiscount = intval(strClean($_POST['intDiscount']));
                        $intStatus = intval(strClean($_POST['statusList']));
                        $valiCategory="";
                        if($type == 1){
                            $valiCategory = $this->model->selectCategory($idCategory);
                        }else{
                            $valiCategory = $this->model->selectSubcategory($idSubcategory);
                        }
                        if(!empty($valiCategory)){
                            if($idDiscount == 0 && !empty($valiCategory)){
                                if($_SESSION['permitsModule']['w']){
                                    $option = 1;
                                    $request= $this->model->insertDiscount($type,$idCategory,$idSubcategory,$intDiscount,$intStatus);
                                }
                            }else{
                                if($_SESSION['permitsModule']['u']){
                                    $option = 2;
                                    $request = $this->model->selectDiscount($idDiscount);
                                    $this->model->applyDiscount($request['type'],$request['categoryid'],$request['subcategoryid'],0,2);
                                    $request = $this->model->updateDiscount($idDiscount,$type,$idCategory,$idSubcategory,$intDiscount,$intStatus);
                                }
                            }
                            if($request > 0 ){
                                $this->model->applyDiscount($type,$idCategory,$idSubcategory,$intDiscount,$intStatus);
                                if($option == 1){
                                    if($intStatus == 1){
                                        $arrResponse = array('status' => true, 'msg' => 'Descuento guardado y activado.');
                                    }else{
                                        $arrResponse = array('status' => true, 'msg' => 'Descuento guardado y desactivado.');
                                    }
                                }else{
                                    if($intStatus == 1){
                                        $arrResponse = array('status' => true, 'msg' => 'Descuento actualizado y activado.');
                                    }else{
                                        $arrResponse = array('status' => true, 'msg' => 'Descuento actualizado y desactivado.');
                                    }
                                }
                            }else if($request == 'exist'){
                                $arrResponse = array('status' => false, 'msg' => '¡Atención! El descuento ya existe, intente con otro.');		
                            }else{
                                $arrResponse = array("status" => false, "msg" => 'No es posible guardar los datos.');
                            }
                        }else{
                            $arrResponse = array("status" => false, "msg" => 'La categoría o subcategoria se encuentra inactiva. Por favor, actívela e intente de nuevo.');
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }else{
                header("location: ".base_url());
            }
			die();
        }
        public function getDiscounts(){
            if($_SESSION['permitsModule']['r']){
                $html="";
                $request = $this->model->selectDiscounts();
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 

                        $status="";
                        $btnEdit="";
                        $btnDelete="";
                        $name= $request[$i]['type'] == 1 ? $request[$i]['category'] : $request[$i]['category']."-".$request[$i]['subcategory'];
                        if($_SESSION['permitsModule']['u']){
                            $btnEdit = '<button class="btn btn-success m-1" type="button" title="Edit" data-id="'.$request[$i]['id_discount'].'" name="btnEdit"><i class="fas fa-pencil-alt"></i></button>';
                        }
                        if($_SESSION['permitsModule']['d']){
                            $btnDelete = '<button class="btn btn-danger m-1" type="button" title="Delete" data-id="'.$request[$i]['id_discount'].'" name="btnDelete"><i class="fas fa-trash-alt"></i></button>';
                        }
                        if($request[$i]['status']==1){
                            $status='<span class="badge me-1 bg-success">Activo</span>';
                        }else{
                            $status='<span class="badge me-1 bg-danger">Inactivo</span>';
                        }
                        $html.='
                            <tr class="item">
                                <td data-label="Nombre: ">'.$name.'</td>
                                <td data-label="Descuento: ">'.$request[$i]['discount'].'%</td>
                                <td data-label="Estado: ">'.$status.'</td>
                                <td data-label="Fecha de creación">'.$request[$i]['date'].'</td>
                                <td data-label="Fecha de actualización">'.$request[$i]['date_update'].'</td>
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
            }
            
            die();
        }
        public function getDiscount(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    if(empty($_POST)){
                        $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $idDiscount = intval($_POST['idDiscount']);
                        $request = $this->model->selectDiscount($idDiscount);
                        $categories = $this->model->selectCategories();
                        $subcategories = $this->model->selectSubcategories();
                        if(!empty($request)){   
                            $htmlC = '<option value="0" selected>Seleccione</option>';
                            $htmlS='<option value="0" selected>Seleccione</option>';
                            $request['htmlStatus'] = $request['status'] == 1 ? '<option value="1" selected>Activo</option><option value="2">Inactivo</option>' : '<option value="1">Activo</option><option value="2" selected>Inactivo</option>';
                            $request['htmlType'] = $request['type'] == 1 ? '<option value="1" selected>Por categorias</option><option value="2">Por subcategorias</option>' : '<option value="1">Por categorias</option><option value="2" selected>Por subcategorias</option>';
                            for ($i=0; $i < count($categories) ; $i++) { 
                                if($categories[$i]['idcategory'] == $request['categoryid']){
                                    $htmlC.='<option value="'.$categories[$i]['idcategory'].'" selected>'.$categories[$i]['name'].'</option>';
                                }else{
                                    $htmlC.='<option value="'.$categories[$i]['idcategory'].'">'.$categories[$i]['name'].'</option>';
                                }
                            }
                            for ($i=0; $i < count($subcategories) ; $i++) { 
                                if($subcategories[$i]['idsubcategory'] == $request['subcategoryid']){
                                    $htmlS.='<option value="'.$subcategories[$i]['idsubcategory'].'" selected>'.$subcategories[$i]['name'].'</option>';
                                }else{
                                    $htmlS.='<option value="'.$subcategories[$i]['idsubcategory'].'">'.$subcategories[$i]['name'].'</option>';
                                }
                            }
                            $request['htmlc'] = $htmlC;
                            $request['htmls'] = $htmlS;
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
        public function getSelectCategories(){
            $html='<option value="0" selected>Seleccione</option>';
            $request = $this->model->selectCategories();
            if(count($request)>0){
                for ($i=0; $i < count($request); $i++) { 
                    $html.='<option value="'.$request[$i]['idcategory'].'">'.$request[$i]['name'].'</option>';
                }
            }
            return $html;
        }
        public function getSelectSubcategories(){
            if($_POST){
                $idCategory = intval(strClean($_POST['idCategory']));
                $html='<option value="0" selected>Seleccione</option>';
                $request = $this->model->getSelectSubcategories($idCategory);
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 
                        $html.='<option value="'.$request[$i]['idsubcategory'].'">'.$request[$i]['name'].'</option>';
                    }
                    $arrResponse = array("data"=>$html);
                }else{
                    $arrResponse = array("data"=>"");
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function delDiscount(){
            if($_SESSION['permitsModule']['d']){

                if($_POST){
                    if(empty($_POST['idDiscount'])){
                        $arrResponse=array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $id = intval($_POST['idDiscount']);
                        $request = $this->model->selectDiscount($id);
                        $this->model->applyDiscount($request['type'],$request['categoryid'],$request['subcategoryid'],0,2);
                        $request = $this->model->deleteDiscount($id);
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
    }
?>