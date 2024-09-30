<?php
    class Inventario extends Controllers{
        public function __construct(){
            session_start();
            if(empty($_SESSION['login'])){
                header("location: ".base_url());
                die();
            }
            parent::__construct();
            sessionCookie();
            getPermits(4);
            
        }
        public function inventario(){
            if($_SESSION['permitsModule']['r']){
                $data['page_tag'] = "inventario";
                $data['page_title'] = "Inventario | Panel";
                $data['page_name'] = "inventario";
                $data['panelapp'] = "functions_inventory.js";
                $this->views->getView($this,"inventario",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function kardex(){
            if($_SESSION['permitsModule']['r']){
                $data['page_tag'] = "Kardex";
                $data['page_title'] = "Kardex | Panel";
                $data['page_name'] = "kardex";
                $data['panelapp'] = "functions_kardex.js";
                $this->views->getView($this,"kardex",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function getProducts(){
            if($_SESSION['permitsModule']['r']){
                $request = $this->model->selectProducts();
                echo json_encode($request,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function getKardex(){
            if($_SESSION['permitsModule']['r']){
                $strInitialDate = strClean($_POST['initial_date']);
                $strFinalDate = strClean($_POST['final_date']);
                $strSearch = clear_cadena(strClean($_POST['search']));
                $arrPurchase = $this->model->selectPurchaseDet($strInitialDate,$strFinalDate,$strSearch);
                $arrOrder = $this->model->selectOrderDet($strInitialDate,$strFinalDate,$strSearch);
                $arrData = [];
                $arrResponse = [];
                $html ="";
                if(!empty($arrPurchase)){
                    foreach ($arrPurchase as $e) {
                        $e['type_move'] = 1;
                        $e['input'] = $e['qty'];
                        $e['output'] = 0;
                        $e['balance'] = 0;
                        $e['move'] ="Entrada por compra";
                        array_push($arrData,$e);
                    }
                }
                if(!empty($arrOrder)){
                    foreach ($arrOrder as $e) {
                        $e['type_move'] = 2;
                        $e['output'] = $e['qty'];
                        $e['input'] = 0;
                        $e['balance'] = 0;
                        $e['move'] ="Salida por venta";
                        array_push($arrData,$e);
                    }
                }
                if(!empty($arrData)){
                    $arrTemp = $arrData;
                    $arrProductId = array_unique(array_values(array_column($arrTemp,"id")));
                    $arrProductName = array_unique(array_values(array_column($arrTemp,"name")));
                    $arrData = [];
                    foreach ($arrProductName as $e) {
                        $arrProduct = array_values(array_filter($arrTemp,function($p)use($e,$arrProductId){return $e == $p['name'] && in_array($p['id'],$arrProductId);}));
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
                $html.= '
                    <tr>
                        <td colspan="4" class="table-secondary">'.$e['name'].'</td>
                        <td colspan="3" class="text-center table-secondary">Entradas</td>
                        <td colspan="3" class="text-center table-secondary">Salidas</td>
                        <td colspan="3" class="text-center table-secondary">Saldo</td>
                    </tr>
                    <tr>
                        <td class="table-light text-center">Fecha</td>
                        <td class="table-light text-center">Factura</td>
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
                            <td></td>
                            <td class="text-end">'.formatNum($f['price']).'</td>
                            <td class="text-center">'.$f['input'].'</td>
                            <td class="text-end">'.formatNum($f['input_total']).'</td>
                            <td class="text-end">'.formatNum($f['price']).'</td>
                            <td class="text-center">'.$f['output'].'</td>
                            <td class="text-end">'.formatNum($f['output_total']).'</td>
                            <td class="text-end">'.formatNum($f['price']).'</td>
                            <td class="text-center">'.$f['balance'].'</td>
                            <td class="text-end">'.formatNum($f['balance_total']).'</td>
                        </tr>
                    ';
                    $lastStock = $f['balance'];
                    $lastTotal = $f['balance_total'];
                }
                $html.='
                    <tr>
                        <td colspan="11" class="fw-bold text-end">Total:</td>
                        <td class="text-center">'.$lastStock.'</td>
                        <td class="text-end">'.formatNum($lastTotal).'</td>
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
                $price = array_values(array_filter($e,function($f){return $f['price'] > 0;}));
                $price = !empty($price) ? $price[0]['price'] : 0;
                for ($i=0; $i < $total ; $i++) { 
                    $e[$i]['price'] = $price;
                    if($i == 0){
                        $e[$i]['balance'] = $e[$i]['input'] - $e[$i]['output'];
                    }else{
                        $lastBalance = $e[$i-1]['balance'];
                        $e[$i]['balance'] = $lastBalance+$e[$i]['input']-$e[$i]['output'];
                    }
                    $e[$i]['output_total'] = $e[$i]['output'] * $price;
                    $e[$i]['input_total'] = $e[$i]['input'] * $price;
                    $e[$i]['balance_total'] = $e[$i]['balance']*$price;
                    array_push($arrProduct,$e[$i]);
                }
                $lastData = $arrProduct[count($arrProduct)-1];
                array_push($arrData,array(
                    "name"=>$arrProduct[0]['name'],
                    "detail"=>$arrProduct,
                    "stock"=>$lastData['balance'],
                    "total"=>$lastData['balance']*$lastData['price'],
                ));
            }
            return $arrData;
        }
    }

?>