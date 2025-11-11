<?php
    class Inventario extends Controllers{
        public function __construct(){
            session_start();
            if(empty($_SESSION['login'])){
                header("location: ".base_url());
                die();
            }
            parent::__construct();
            
        }

        public function inventario(){
            if($_SESSION['permitsModule']['r']){
                $data['botones'] = [
                    "duplicar" => ["mostrar"=>true, "evento"=>"onclick","funcion"=>"mypop=window.open('".BASE_URL.$_SESSION['permitsModule']['route']."','','');mypop.focus();"],
                    "nuevo" => ["mostrar"=>$_SESSION['permitsModule']['w'], "evento"=>"onclick","funcion"=>"openModal()"],
                    "pdf" => ["mostrar"=>$_SESSION['permitsModule']['r'], "evento"=>"onclick","funcion"=>"exportPdf()"],
                    "excel" => ["mostrar"=>$_SESSION['permitsModule']['r'], "evento"=>"onclick","funcion"=>"exportExcel()"],
                ];
                $data['page_tag'] = implode(" | ",[$_SESSION['permitsModule']['option'],$_SESSION['permitsModule']['module']]);
                $data['page_title'] = implode(" | ",[$_SESSION['permitsModule']['option'],$_SESSION['permitsModule']['module']]);
                $data['page_name'] = strtolower($_SESSION['permitsModule']['option']);
                $data['panelapp'] = "/Almacen/functions_inventory.js";
                $this->views->getView($this,"inventario",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }

        public function kardex(){
            if($_SESSION['permitsModule']['r']){
                $data['botones'] = [
                    "duplicar" => ["mostrar"=>true, "evento"=>"onclick","funcion"=>"mypop=window.open('".BASE_URL.$_SESSION['permitsModule']['route']."','','');mypop.focus();"],
                    "pdf" => ["mostrar"=>$_SESSION['permitsModule']['r'], "evento"=>"onclick","funcion"=>"exportPdf()"],
                    "excel" => ["mostrar"=>$_SESSION['permitsModule']['r'], "evento"=>"onclick","funcion"=>"exportExcel()"],
                ];
                $data['page_tag'] = implode(" | ",[$_SESSION['permitsModule']['option'],$_SESSION['permitsModule']['module']]);
                $data['page_title'] = implode(" | ",[$_SESSION['permitsModule']['option'],$_SESSION['permitsModule']['module']]);
                $data['page_name'] = strtolower($_SESSION['permitsModule']['option']);
                $data['panelapp'] = "/Almacen/functions_inventory_kardex.js";
                $this->views->getView($this,"kardex",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }

        public function getProducts(){
            if($_SESSION['permitsModule']['r']){
                $strSearch = strClean($_POST['search']);
                $intPerPage = intval($_POST['perpage']);
                $intPageNow = intval($_POST['page']);
                $request = $this->model->selectProducts($strSearch,$intPerPage,$intPageNow);
                $arrProducts = $request['products'];
                $intTotalPages = $request['pages'];
                $arrInventory = $this->model->selectTotalInventory($strSearch);
                $arrData = array(
                    "data"=>$arrInventory['products'],
                    "html"=>$this->getInventoryHtml($arrProducts,$intTotalPages,$intPageNow),
                    "total"=>$arrInventory['total'],
                    "total_format"=>formatNum($arrInventory['total']),
                    "total_records"=>count($arrInventory['products'])
                );
                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
            }
            die();
        }

        public function getInventoryHtml(array $data,int $pages,$page){
            $maxButtons = 4;
            $totalPages = $pages;
            $startPage = max(1, $page - floor($maxButtons / 2));
            if ($startPage + $maxButtons - 1 > $totalPages) {
                $startPage = max(1, $totalPages - $maxButtons + 1);
            }
            $html ="";
            $htmlPages = '
                <li class="page-item">
                    <a class="page-link text-secondary" href="#" onclick="getData(1)" aria-label="First">
                        <span aria-hidden="true"><i class="fas fa-angle-double-left"></i></span>
                    </a>
                </li>
                <li class="page-item">
                    <a class="page-link text-secondary" href="#" onclick="getData('.max(1, $page-1).')" aria-label="Previous">
                        <span aria-hidden="true"><i class="fas fa-angle-left"></i></span>
                    </a>
                </li>
            ';
            for ($i = $startPage; $i < min($startPage + $maxButtons, $totalPages + 1); $i++) {
                $htmlPages .= '<li class="page-item">
                    <a class="page-link  '.($i == $page ? ' bg-primary text-white' : 'text-secondary').'" href="#" onclick="getData('.$i.')">'.$i.'</a>
                </li>';
            }
            foreach ($data as $pro) {
                $html.='
                    <tr>
                        <td>'.$pro['id'].'</td>
                        <td>'.$pro['reference'].'</td>
                        <td>'.$pro['name'].'</td>
                        <td>'.$pro['category'].'</td>
                        <td>'.$pro['subcategory'].'</td>
                        <td class="text-center">'.$pro['measure'].'</td>
                        <td class="text-center">'.$pro['stock'].'</td>
                        <td class="text-end">'.$pro['price_purchase_format'].'</td>
                        <td class="text-end">'.$pro['total_format'].'</td>
                    </tr>
                ';
            }
            $htmlPages .= '
                <li class="page-item">
                    <a class="page-link text-secondary" href="#" onclick="getData('.min($totalPages, $page+1).')" aria-label="Next">
                        <span aria-hidden="true"><i class="fas fa-angle-right"></i></span>
                    </a>
                </li>
                <li class="page-item">
                    <a class="page-link text-secondary" href="#" onclick="getData('.($pages).')" aria-label="Last">
                        <span aria-hidden="true"><i class="fas fa-angle-double-right"></i></span>
                    </a>
                </li>
            ';
            return array("products"=>$html,"pages"=>$htmlPages);
        }

        public function getKardex(){
            if($_SESSION['permitsModule']['r']){
                $strInitialDate = strClean($_POST['initial_date']);
                $strFinalDate = strClean($_POST['final_date']);
                $strSearch = clear_cadena(strClean($_POST['search']));
                $arrPurchase = $this->model->selectPurchaseDet($strInitialDate,$strFinalDate,$strSearch);
                $arrOrder = $this->model->selectOrderDet($strInitialDate,$strFinalDate,$strSearch);
                $arrAdjustmentInputs = $this->model->selectAdjustmentDet($strInitialDate,$strFinalDate,$strSearch,1);
                $arrAdjustmentOutputs = $this->model->selectAdjustmentDet($strInitialDate,$strFinalDate,$strSearch,2);
                
                $arrData = [];
                $arrResponse = [];

                $html='
                    <tr>
                        <td colspan="16" class="text-center">No hay datos</td>
                    </tr>
                ';

                if(!empty($arrPurchase)){
                    foreach ($arrPurchase as $e) {
                        $e['type_move'] = 1;
                        $e['input'] = $e['qty'];
                        $e['input_total'] = 0;
                        $e['output'] = 0;
                        $e['output_total'] = 0;
                        $e['balance'] = 0;
                        $e['move'] ="Entrada por compra";
                        $e['move_code'] = 101;
                        array_push($arrData,$e);
                    }
                }

                if(!empty($arrAdjustmentInputs)){
                    foreach ($arrAdjustmentInputs as $e) {
                        $e['type_move'] = 1;
                        $e['input'] = $e['qty'];
                        $e['input_total'] = 0;
                        $e['output'] = 0;
                        $e['output_total'] = 0;
                        $e['balance'] = 0;
                        $e['move'] ="Entrada por ajuste";
                        $e['move_code'] = 102;
                        array_push($arrData,$e);
                    }
                }

                if(!empty($arrAdjustmentOutputs)){
                    foreach ($arrAdjustmentOutputs as $e) {
                        $e['type_move'] = 2;
                        $e['output'] = $e['qty'];
                        $e['output_total'] = 0;
                        $e['input'] = 0;
                        $e['input_total'] = 0;
                        $e['balance'] = 0;
                        $e['move'] ="Salida por ajuste";
                        $e['move_code'] = 202;
                        array_push($arrData,$e);
                    }
                }

                if(!empty($arrOrder)){
                    foreach ($arrOrder as $e) {
                        $e['type_move'] = 2;
                        $e['output'] = $e['qty'];
                        $e['output_total'] = 0;
                        $e['input'] = 0;
                        $e['input_total'] = 0;
                        $e['balance'] = 0;
                        $e['move'] ="Salida por venta";
                        $e['move_code'] = 201;
                        array_push($arrData,$e);
                    }
                }

                if(!empty($arrData)){
                    $arrTemp = $arrData;
                    $arrProductId = array_unique(array_values(array_column($arrTemp,"id")));
                    //$arrProductName = array_unique(array_values(array_column($arrTemp,"name")));
                    $arrProductName = array_unique( array_map( function($name){return trim($name);}, array_column($arrTemp, "name") ) );
                    $arrData = [];
                    //dep($arrProductName);exit;
                    foreach ($arrProductName as $e) {
                        $arrProduct = array_values(array_filter($arrTemp,function($p)use($e,$arrProductId){return $e == trim($p['name']) && in_array($p['id'],$arrProductId);}));
                        usort($arrProduct,function($a,$b){
                            $date1 = strtotime($a['date']);
                            $date2 = strtotime($b['date']);
                            return $date1 > $date2;
                        });
                        array_push($arrData,$arrProduct);
                    }
                    $arrData = $this->orderData($arrData);
                    $html = $this->getKardexHtml($arrData);
                }

                $arrResponse = array("html"=>$html,"data"=>$arrData);
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function getKardexHtml($data){
            $html ="";
            foreach ($data as $e) {
                $detail = $e['detail'];
                $lastStock = 0;
                $lastTotal = 0;
                $bg = "";
                $html.= '
                    <tr>
                        <td colspan="4" class="table-primary">'.$e['name'].'</td>
                        <td colspan="3" class="text-center table-secondary">Entradas</td>
                        <td colspan="3" class="text-center table-secondary">Salidas</td>
                        <td colspan="3" class="text-center table-secondary">Saldo</td>
                    </tr>
                    <tr>
                        <td class="table-light text-center">Fecha</td>
                        <td class="table-light text-center">Documento</td>
                        <td class="table-light text-center">Movimiento</td>
                        <td class="table-light text-center">Unidad</td>
                        <td class="table-light text-center">Valor</td>
                        <td class="table-light text-center">Cantidad</td>
                        <td class="table-light text-center">Saldo</td>
                        <td class="table-light text-center">Valor</td>
                        <td class="table-light text-center">Cantidad</td>
                        <td class="table-light text-center">Saldo</td>
                        <td class="table-light text-center">Valor</td>
                        <td class="table-light text-center">Cantidad</td>
                        <td class="table-light text-center">Saldo</td>
                    </tr>
                ';
                foreach ($detail as $f) {
                    $html.='
                        <tr>
                            <td class="text-center">'.$f['date_format'].'</td>
                            <td>'.$f['document'].'</td>
                            <td>'.$f['move'].'</td>
                            <td class="text-center">'.$f['measure'].'</td>
                            <td class="text-end">'.formatNum($f['price']).'</td>
                            <td class="text-center">'.$f['input'].'</td>
                            <td class="text-end">'.formatNum($f['input_total']).'</td>
                            <td class="text-end">'.formatNum($f['last_price']).'</td>
                            <td class="text-center">'.$f['output'].'</td>
                            <td class="text-end">'.formatNum($f['output_total']).'</td>
                            <td class="text-end">'.formatNum($f['last_price']).'</td>
                            <td class="text-center">'.$f['balance'].'</td>
                            <td class="text-end">'.formatNum($f['balance_total']).'</td>
                        </tr>
                    ';
                    $lastStock = $f['balance'];
                    $lastTotal = $f['balance_total'];
                }
                
                if($lastStock < 0){
                    $bg = "bg-warning ";
                }
                $html.='
                    <tr>
                        <td colspan="11" class="'.$bg.'fw-bold text-end">Total:</td>
                        <td class="'.$bg.'text-center">'.$lastStock.'</td>
                        <td class="'.$bg.'text-end">'.formatNum($lastTotal).'</td>
                    </tr>
                ';
            }
            return $html;
        }

        public function orderData(array $data){
            $arrData = [];
            $total = count($data);
            foreach ($data as $e) {
                $total = count($e);
                $arrProduct = [];
                $totalCostBalance = 0;


                $arrOutputSale = $this->getInitialBalance(201,$e);
                $arrOutputAjust = $this->getInitialBalance(202,$e);

                $arrIntputPurchase = $this->getInitialBalance(101,$e);
                $arrIntputAjust = $this->getInitialBalance(102,$e);

                $arrInitial = [
                    'document'=>"N/A",
                    "measure"=>"N/A",
                    'type_move' => 0,
                    "price"=>0,
                    "last_price"=>0,
                    'output' => $arrOutputSale['previous_qty']+$arrOutputAjust['previous_qty'],
                    'output_total' => $arrOutputSale['previous_price']+$arrOutputAjust['previous_price'],
                    'input' => $arrIntputPurchase['previous_qty']+$arrIntputAjust['previous_qty'],
                    'input_total' => $arrIntputPurchase['previous_price']+$arrIntputAjust['previous_price'],
                    'move' => "Saldo anterior"
                ];
                
                $intInitialStock = $arrInitial['input'] - $arrInitial['output'];
                $intInitialStock = $intInitialStock > 0? $intInitialStock : 1;
                $intInititalPrice = $arrInitial['input_total'] - $arrInitial['output_total'];
                $intFinalPrice = $intInititalPrice/$intInitialStock;

                $arrInitial['balance'] = $arrInitial['input'] - $arrInitial['output'];
                $arrInitial['balance_total'] = $intFinalPrice;
                array_push($arrProduct,$arrInitial);

                for ($i=0; $i < $total ; $i++) { 
                    $price = $e[$i]['price'];
                    $e[$i]['last_price'] = $e[$i]['price'];
                    if($i == 0){
                        $e[$i]['balance'] = $arrInitial['balance']+($e[$i]['input'] - $e[$i]['output']);
                        $e[$i]['balance_total'] = $arrInitial['balance_total']+($e[$i]['balance']*$price);
                        $e[$i]['output_total'] = $arrInitial['output_total']+($e[$i]['output'] * $price);
                    }else{
                        $lastRow = $e[$i-1];
                        $lastBalance = $lastRow['balance'];
                        $totalBalance = $lastBalance+$e[$i]['input']-$e[$i]['output'];
                        $totalCostBalance = $lastRow['balance_total'];
                        $e[$i]['balance'] = $totalBalance;

                        if($e[$i]['type_move'] == 1){
                            $totalCostBalance+=$e[$i]['input'] * $e[$i]['last_price'];
                            $lastPrice = $totalBalance > 0 ? $totalCostBalance/$totalBalance : 0;
                            $e[$i]['last_price'] = $lastPrice;
                            $e[$i]['balance_total'] = $e[$i]['balance']*$lastPrice;
                        }else{
                            $e[$i]['last_price'] =  $lastRow['last_price'];
                            $e[$i]['output_total'] = $e[$i]['output'] * $lastRow['last_price'];
                            $e[$i]['balance_total'] = $e[$i]['balance']*$lastRow['last_price'];
                        }
                    }
                    $e[$i]['input_total'] = $e[$i]['input'] * $price;
                    array_push($arrProduct,$e[$i]);
                }

                $lastData = $arrProduct[count($arrProduct)-1];
                array_push($arrData,array(
                    "name"=>$arrProduct[1]['name'],
                    "detail"=>$arrProduct,
                    "stock"=>$lastData['balance'],
                    "total"=>$lastData['balance']*$lastData['price'],
                ));
            }
            return $arrData;
        }
        
        private function getInitialBalance($code,$data){
            return array_values(array_filter($data,function($e) use($code){return $e['move_code'] == $code;}))[0];
        }
    }

?>