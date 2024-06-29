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
                    if(empty($_POST['id']) || empty($_POST['data']) || empty($_POST['height']) || empty($_POST['width']) || empty($_POST['id_config']) 
                    || empty($_POST['orientation'])){
                        $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $intId = intval($_POST['id']);
                        $intIdConfig = intval($_POST['id_config']);
                        $intMargin = intval($_POST['margin']);
                        $intHeight = floatval($_POST['height']);
                        $intWidth = floatval($_POST['width']);
                        $intHeightM = $intHeight+$intMargin;
                        $intWidthM = $intWidth+$intMargin;
                        $arrData = json_decode($_POST['data'],true);
                        $strOrientation = strClean($_POST['orientation']);
                        $strColorFrame = strClean($_POST['color_frame']);
                        $strColorMargin = strClean($_POST['color_margin']);
                        $strColorBorder = strClean($_POST['color_border']);
                        $request = $this->model->selectFrameConfig($intId,$arrData);
                        $request_config=$this->model->selectCategory($intIdConfig);
                        $isPrint = $request_config['is_print'];
                        /************Frame variables************* */
                        $frameLength = 290;
                        $framePainted = 2.87;
                        $frame = $request['frame'];
                        $cost= $frame['name'] =="molduras importadas" ?  ceil($frame['price_purchase']/$frameLength) : ceil(($frame['price_purchase']/$frameLength)*$framePainted);
                        $waste = $frame['waste'];
                        $data = $request['data'];
                        $totalCostMaterial = 0;

                        $totalCostFrame = ((($intHeightM+$intWidthM)*2)+$waste)*$cost;
                        if( $frame['name'] !="molduras importadas"){
                            $perimetro = 2*($intHeightM+$intWidthM);
                            $varas = ceil(($perimetro)/($frameLength-$waste));
                            $totalCostFrame = ($perimetro+($waste*$varas))*$cost;
                        }
                        $totalCost = 0;
                        $arrSpecs = [];
                        array_push($arrSpecs,
                            array("name"=>"Referencia","value"=>$frame['reference']),
                            array("name"=>"Material","value"=>ucfirst($frame['name'])),
                            array("name"=>"Orientación","value"=>$strOrientation),
                            array("name"=>"Medida imagen","value"=>$intWidth." x ".$intHeight." cm"),
                            array("name"=>"Medida marco","value"=>$intWidthM." x ".$intHeightM." cm")
                        );
                        if($frame['name'] !="molduras importadas"){
                            array_push($arrSpecs,array("name"=>"Color del marco","value"=>$strColorFrame));
                        }
                        foreach ($data as $e ) {
                            $prop = $e['prop'];
                            $option = $e['option'];
                            $arrMaterial = $e['material'];
                            if($option['is_margin']){
                                array_push($arrSpecs,array("name"=>"Medida del ".$option['tag'],"value"=>$intMargin." cm"));
                                array_push($arrSpecs,array("name"=>"Color del ".$option['tag'],"value"=>$strColorMargin));
                            }
                            if($option['is_bocel'] || $option['is_frame']){
                                array_push($arrSpecs,array("name"=>"Color del ".$option['tag_frame'],"value"=>$strColorBorder));
                            }
                            
                            array_push($arrSpecs,array("name"=>$prop['name'],"value"=>$option['name']));
                            if($prop['is_material']){
                                if($isPrint != 1){
                                    $arrMaterial = array_filter($arrMaterial,function($e){return $e['name'] != "Impresion";});
                                }
                                foreach ($arrMaterial as $d ) {
                                    $totalCostMaterial+=$this->calcularCostoMaterial($d,$intHeight,$intWidth,$intMargin);
                                }
                            }
                        }
                        $totalCost = $totalCostMaterial+$totalCostFrame;
                        $price = ceil((intval(UTILIDAD*((($totalCost)*COMISION)+TASA)))/1000)*1000;
                        $arrResponse = array("status"=>true,"total"=>formatNum($price),"specs"=>$arrSpecs,"total_clean"=>$price,"name"=>$request_config['name']);
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }
        public function calcularCostoMaterial($data,float $intHeight, float $intWidth,int $intMargin){
            $total = 0;
            $type = $data['type'];
            $method = $data['method'];
            $priceMaterial = $data['price_purchase'];
            $arrVariables = $data['variables'];
            $wasteMaterial = 0;
            $heightMaterial = 0;
            $widthMaterial = 0;
            $lengthMaterial = 0;
            $areaMaterial = 0;
            $costMaterial = 0;
            $heigth = $method == "completo" ?  ($intHeight+$intMargin) : $intHeight;
            $width = $method == "completo" ?  ($intWidth+$intMargin) : $intWidth;
            foreach ($arrVariables as $v ) {
                if($v['name'] == "Alto"){
                    $heightMaterial = $v['value'];
                }else if($v['name']=="Ancho"){
                    $widthMaterial = $v['value'];
                }else if($v['name'] == "Desperdicio"){
                    $wasteMaterial = $v['value'];
                }else if($v['name']=="Largo"){
                    $lengthMaterial = $v['value'];
                }
            }
            if($type == "area"){
                $areaMaterial = $widthMaterial * $heightMaterial;
                $costMaterial = ceil($priceMaterial/$areaMaterial); 
                $total+=$costMaterial*($heigth*$width); 
            }else{
                $costMaterial = ceil(($priceMaterial/$lengthMaterial)*6); 
                $perimetro = (($heigth + $width)*2)+$wasteMaterial;
                $total+=$costMaterial*($perimetro); 
            }
            return $total;
        }
    }

?>