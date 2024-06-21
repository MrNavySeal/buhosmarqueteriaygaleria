<?php
    class MarqueteriaCalculos extends Controllers{
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
        public function calcularMarcoTotal(){
            if($_SESSION['permitsModule']['w']){
                if($_POST){
                    if(empty($_POST['id']) || empty($_POST['data']) || empty($_POST['height']) || empty($_POST['width'])){
                        $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $intId = intval($_POST['id']);
                        $intHeight = floatval($_POST['height']);
                        $intWidth = floatval($_POST['width']);
                        $arrData = json_decode($_POST['data'],true);
                        $request = $this->model->selectFrameConfig($intId,$arrData);
                        /************Frame variables************* */
                        $frameLength = 290;
                        $framePainted = 2.87;
                        $frame = $request['frame'];
                        $cost= $frame['name'] =="molduras en madera" ? ceil(($frame['price_purchase']/$frameLength)*$framePainted) : ceil($frame['price_purchase']/$frameLength);
                        $waste = $frame['waste'];
                        $data = $request['data'];
                        $totalCostMaterial = 0;
                        $totalCostFrame = ((($intHeight+$intWidth)*2)+$waste)*$cost;
                        $totalCost = 0;
                        foreach ($data as $e ) {
                            $prop = $e['prop'];
                            $option = $e['option'];
                            $arrMaterial = $e['material'];
                            if($prop['is_material']){
                                $totalCostMaterial+=$this->calcularCostoMaterial($arrMaterial,$intHeight,$intWidth);
                            }
                        }
                        $totalCost = $totalCostMaterial+$totalCostFrame;
                        $price = ceil((intval(UTILIDAD*((($totalCost)*COMISION)+TASA)))/1000)*1000;
                        $arrResponse = array("status"=>true,"total"=>formatNum($price));
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }
        public function calcularCostoMaterial(array $data,float $intHeight, float $intWidth){
            $total = 0;
            foreach ($data as $m) {
                $type = $m['type'];
                $priceMaterial = $m['price_purchase'];
                $arrVariables = $m['variables'];
                $wasteMaterial = 0;
                $heightMaterial = 0;
                $widthMaterial = 0;
                $areaMaterial = 0;
                $perimetro = 0;
                $costMaterial = 0;
                foreach ($arrVariables as $v ) {
                    if($v['name'] == "Alto"){
                        $heightMaterial = $v['value'];
                    }else if($v['name']=="Ancho"){
                        $widthMaterial = $v['value'];
                    }else if($v['name'] == "Desperdicio"){
                        $wasteMaterial = $v['value'];
                    }
                }
                if($type == "area"){
                    $areaMaterial = $widthMaterial * $heightMaterial;
                    $costMaterial = ceil($priceMaterial/$areaMaterial); 
                    $total+=$costMaterial*($intHeight*$intWidth); 
                }else{
                    /*$perimetro = (($widthMaterial + $heightMaterial)*2)+$wasteMaterial;
                    $costMaterial = ceil($priceMaterial/$perimetro); */
                }
            }
            return $total;
        }
    }

?>