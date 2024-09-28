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
        public function getProducts(){
            if($_SESSION['permitsModule']['r']){
                $request = $this->model->selectProducts();
                echo json_encode($request,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function getKardex(){
            if($_SESSION['permitsModule']['r']){
                $arrPurchase = $this->model->selectPurchaseDet();
                $arrOrder = $this->model->selectOrderDet();
                $arrData = [];
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
                    dep($arrData);exit;
                }
                return $arrData;
            }
        }
        public function orderData(array $data){
            $arrData = [];
            $total = count($data);
            foreach ($data as $e) {
                $total = count($e);
                $arrProduct = [];
                $price = array_values(array_filter($e,function($f){return $f['price'] > 0;}))[0]['price'];
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