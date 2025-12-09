<?php
    
    require_once("Models/EnmarcarTrait.php");
    require_once("Models/CategoryTrait.php");
    class Enmarcar extends Controllers{
        use EnmarcarTrait,CategoryTrait;
        public function __construct(){
            session_start();
            parent::__construct();
            sessionCookie();
        }

        public function enmarcar(){
            setView(BASE_URL."/enmarcar");
            $company = getCompanyInfo();
            $data['page_tag'] = "Enmarcar | ".$company['name'];
            $data['page_title'] = "Enmarcar | ".$company['name'];
            $data['page_name'] = "enmarcar";
            $data['tipos'] = $this->selectTipos();
            $data['app'] = "functions_home.js";
            $this->views->getView($this,"enmarcar",$data);
            
        }

        public function personalizar($params){
            $company = getCompanyInfo();
            $params = strClean($params);
            $request = $this->selectTipo($params);
            if(!empty($request)){
                setView(BASE_URL."/enmarcar/$params");
                $data['page_tag'] = 'Enmarcar '.$request['name'].' | '.$company['name'];
                $data['page_title'] = 'Enmarcar '.$request['name'].' | '.$company['name'];
                $data['page_name'] = "personalizar";
                $data['examples'] = $this->selectExamples($request['id']);
                $data['name'] = $request['name'];
                $data['faqs'] = $this->getFaqs();
                $data['app'] = "functions_molding_public.js";
                $this->views->getView($this,"personalizar",$data);
            }else{
                header("location: ".base_url()."/enmarcar");
            }
        }

        public function getConfig($params){
            $params = strClean($params);
            $request = $this->selectConfig($params);
            if(!empty($request)){
                $arrColors = $this->selectColors();
                $arrProps = array_values(array_filter($request['detail']['props'],function($e){return isset($e['topic']);}));
                $request['detail']['props'] = $arrProps;
                $arrResponse = array("status"=>true,"data"=>$request,"color"=>$arrColors);
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }

        public function calcularMarcoTotal($bypass=false,$arrInfo = array()){
            if($_POST || $bypass){
                if(!$bypass && (empty($_POST['id']) || empty($_POST['data']) || empty($_POST['height']) || empty($_POST['width']) || empty($_POST['id_config']) 
                || empty($_POST['orientation']))){
                    $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                }else if($bypass && empty($arrInfo)){
                    $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                }else{
                    $intId = intval(!$bypass ? $_POST['id'] : $arrInfo['id']);
                    $intIdConfig = intval(!$bypass ? $_POST['id_config'] : $arrInfo['id_config']);
                    $intMargin = intval(!$bypass ? $_POST['margin'] : $arrInfo['margin']);
                    $intHeight = floatval(!$bypass ? $_POST['height'] : $arrInfo['height']);
                    $intWidth = floatval(!$bypass ? $_POST['width'] : $arrInfo['width']);
                    $intHeightM = $intHeight+($intMargin*2);
                    $intWidthM = $intWidth+($intMargin*2);
                    $arrData = json_decode(!$bypass ? $_POST['data'] : $arrInfo['data'],true);
                    $strOrientation = strClean(!$bypass ? $_POST['orientation'] : $arrInfo['orientation']);
                    $strColorFrame = strClean(!$bypass ? $_POST['color_frame'] : $arrInfo['color_frame']);
                    $strColorMargin = strClean(!$bypass ? $_POST['color_margin'] : $arrInfo['color_margin']);
                    $strColorBorder = strClean(!$bypass ? $_POST['color_border'] : $arrInfo['color_border']);
                    $intIdColorFrame = intval(!$bypass ? $_POST['color_frame_id'] : $arrInfo['color_frame_id']);
                    $intIdColorMargin = intval(!$bypass ? $_POST['color_margin_id'] : $arrInfo['color_margin_id']);
                    $intIdColorBorder = intval(!$bypass ? $_POST['color_border_id'] : $arrInfo['color_border_id']);
                    $intIdTypeFrame = intval(!$bypass ? $_POST['type_frame'] : $arrInfo['type_frame']);
                    $request = $this->selectFrameConfig($intId,$arrData);
                    if(!empty($request)){
                        $request_config=$this->selectCategory($intIdConfig);
                        $isPrint = $request_config['is_print'];
                        /************Frame variables************* */
                        $frameLength = 290;
                        $framePainted = 2.87;
                        $frame = $request['frame'];
                        $cost= 0;
                        $waste = $frame['waste'];
                        $data = $request['data'];
                        $flag = strpos($frame['name'],"madera") > 0 ? true : false;
                        if($flag){
                            $cost = ceil(($frame['price_purchase']/$frameLength)*$framePainted);
                        }else{
                            $cost = ceil($frame['price_purchase']/$frameLength);
                        }
                        $totalCostFrame = ((($intHeightM+$intWidthM)*2)+$waste)*$cost;
                        if( $frame['name'] !="molduras importadas"){
                            $perimetro = 2*($intHeightM+$intWidthM);
                            $varas = ceil(($perimetro)/($frameLength-$waste));
                            $totalCostFrame = ($perimetro+($waste*$varas))*$cost;
                        }
                        $totalCostFrame = ceil(($totalCostFrame)/1000)*1000;
                        $totalCostMaterial = 0;
                        $totalCost = 0;
                        $arrSpecs = [];
                        $arrCostData = [];
                        $htmlCostData = "";
                        $htmlSpecs ="";
                        $price = ceil((UTILIDAD*$totalCostFrame)/1000)*1000;
                        $htmlCostData ='<tr>
                            <td>Marco '.$frame['reference'].'</td>
                            <td class="text-end">'.formatNum($totalCostFrame).'</td>
                            <td class="text-end">'.formatNum(ceil((UTILIDAD*$totalCostFrame)/1000)*1000).'</td>
                        </tr>';
                        array_push($arrCostData,array("name"=>"Referencia","value"=>$frame['reference']));
                        array_push($arrSpecs,
                            array("name"=>"Referencia","value"=>$frame['reference']),
                            array("name"=>"Material","value"=>ucfirst($frame['name'])),
                            array("name"=>"Orientación","value"=>$strOrientation),
                            array("name"=>"Medida imagen","value"=>$intWidth." x ".$intHeight." cm"),
                            array("name"=>"Medida marco","value"=>$intWidthM." x ".$intHeightM." cm")
                        );
                        $htmlSpecs.='<tr><td>Referencia</td><td>'.$frame['reference'].'</td></tr>';
                        $htmlSpecs.='<tr><td>Material</td><td>'.ucfirst($frame['name']).'</td></tr>';
                        $htmlSpecs.='<tr><td>Orientación</td><td>'.$strOrientation.'</td></tr>';
                        $htmlSpecs.='<tr><td>Medida imagen</td><td>'.$intWidth." x ".$intHeight." cm".'</td></tr>';
                        $htmlSpecs.='<tr><td>Medida marco</td><td>'.$intWidthM." x ".$intHeightM." cm".'</td></tr>';
                        
                        if($frame['name'] !="molduras importadas" && $frame['name'] !="bastidores"){
                            array_push($arrSpecs,array("name"=>"Color del marco","value"=>$strColorFrame));
                            $htmlSpecs.='<tr><td>Color del marco</td><td>'.$strColorFrame.'</td></tr>';
                        }
                        foreach ($data as $e ) {
                            $prop = $e['prop'];
                            $option = $e['option'];
                            $arrMaterial = $e['material'];
                            if($option['is_margin']){
                                array_push($arrSpecs,array("name"=>"Medida del ".$option['tag'],"value"=>$intMargin." cm"));
                                $htmlSpecs.='<tr><td>'."Medida del ".$option['tag'].'</td><td>'.$intMargin.'</td></tr>';

                                if($option['is_color']){
                                    array_push($arrSpecs,array("name"=>"Color del ".$option['tag'],"value"=>$strColorMargin));
                                    $htmlSpecs.='<tr><td>'."Color del ".$option['tag'].'</td><td>'.$strColorMargin.'</td></tr>';
                                }
                            }
                            if($option['is_bocel'] || $option['is_frame']){
                                array_push($arrSpecs,array("name"=>"Color del ".$option['tag_frame'],"value"=>$strColorBorder));
                                $htmlSpecs.='<tr><td>'."Color del ".$option['tag_frame'].'</td><td>'.$strColorBorder.'</td></tr>';
                            }
                            
                            array_push($arrSpecs,array("name"=>$prop['name'],"value"=>$option['name']));
                            $htmlSpecs.='<tr><td>'.$prop['name'].'</td><td>'.$option['name'].'</td></tr>';
                            if($prop['is_material']){
                                if($isPrint != 1){
                                    $arrMaterial = array_filter($arrMaterial,function($e){return $e['name'] != "Impresion";});
                                }
                                foreach ($arrMaterial as $d ) {
                                    $totalMaterial = $this->calcularCostoMaterial($d,$intHeight,$intWidth,$intMargin);
                                    $totalCostMaterial+= $totalMaterial;
                                    $price+=ceil((UTILIDAD*$totalMaterial)/1000)*1000;
                                    array_push($arrCostData,array("name"=>$d['name'], "total"=>$totalMaterial ));
                                    $htmlCostData.='
                                    <tr>
                                        <td>'.$d['name'].'</td>
                                        <td class="text-end">'.formatNum($totalMaterial).'</td>
                                        <td class="text-end">'.formatNum(ceil((UTILIDAD*$totalMaterial)/1000)*1000).'</td>
                                    </tr>';
                                }
                            }
                        }

                        $htmlWholesaleData = "<tr><td colspan='4' class='text-center'>No hay descuentos</td></tr>";
                        $arrWholesale = $frame['wholesale'];
                        if(!empty($arrWholesale)){
                            $htmlWholesaleData ='
                            <tr>
                                <td colspan="3" class="fw-bold text-end">Precio normal:</td>
                                <td class="text-end fw-bold">'.formatNum($price).'</td>
                            </tr>';

                            $totalWholeSale = count($arrWholesale);
                            for ($i=0; $i < $totalWholeSale ; $i++) { 
                                $e = $arrWholesale[$i];
                                $max = $i == $totalWholeSale-1 ? "En adelante" : $e['max'];
                                $discount = $price - ($price*($e['percent']/100));
        
                                $htmlWholesaleData.='
                                <tr>
                                    <td class="text-center">'.$e['min'].'</td>
                                    <td class="text-center">'.$max.'</td>
                                    <td class="text-center">'.$e['percent'].'%</td>
                                    <td class="text-end">'.formatNum($discount).'</td>
                                </tr>';
                            }

                            
                        }

                        $arrResponse = array(
                            "status"=>true,
                            "total"=>formatNum($price),
                            "specs"=>$arrSpecs,
                            "html_specs"=>$htmlSpecs,
                            "html_wholesale"=>$htmlWholesaleData,
                            "total_clean"=>$price,
                            "name"=>$request_config['name'],
                            "cat_img"=>$request_config['image'],
                            "route"=>$request_config['route'],
                            "config"=>array(
                                "frame"=>$intId,
                                "config"=>$intIdConfig,
                                "margin"=>$intMargin,
                                "height"=>$intHeight,
                                "width"=>$intWidth,
                                "orientation"=>$strOrientation,
                                "color_frame"=>$intIdColorFrame,
                                "color_margin"=>$intIdColorMargin,
                                "color_border"=>$intIdColorBorder,
                                "props"=>$arrData,
                                "type_frame"=>$intIdTypeFrame,
                                "wholesale"=>$arrWholesale
                            )
                        );
                    }else{
                        $arrResponse = array("status"=>false,"msg"=>"Error, la moldura no existe.");
                    }
                }
                if(!$bypass){
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }else{
                    return $arrResponse;
                }
            }
            die();
        }

        public function calcularCostoMaterial($data,float $intHeight, float $intWidth,int $intMargin){
            $total = 0;
            $type = $data['type'];
            $method = $data['method'];
            $factor = $data['factor'];
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
                $areaMaterial = $areaMaterial > 0 ? $areaMaterial : 1;
                $costMaterial = ceil(($priceMaterial/$areaMaterial)*$factor); 
                $total+=$costMaterial*($heigth*$width); 
            }else{
                $lengthMaterial = $lengthMaterial > 0 ? $lengthMaterial : 1;
                $costMaterial = ceil(($priceMaterial/$lengthMaterial)*$factor); 
                $perimetro = (($heigth + $width)*2)+$wasteMaterial;
                $total+=$costMaterial*($perimetro); 
            }
            $total = ceil($total/1000)*1000;
            return $total;
        }

        public function addCart(){
            if($_POST){
                $arrData = $this->calcularMarcoTotal(true,$_POST);
                $arrCart = [];
                if($arrData['status']){
                    $arrData['img'] = $_POST['img'];
                    $arrData['topic'] = 1;
                    $arrData['qty'] = $_POST['qty'] <= 0 ? 1 : intval($_POST['qty']);
                    $arrData['price'] = $arrData['total_clean'];
                    $arrData['current_price'] = $arrData['total_clean'];
                    $arrData['discount'] = 0;
                    $pop = array(
                        "name"=>$arrData['name'],
                        "image"=>$arrData['img'] !="" ? $arrData['img'] : media()."/images/uploads/".$arrData['cat_img'],
                        "route"=>base_url()."/enmarcar/personalizar/".$arrData['route']
                    );
                    if(isset($_SESSION['arrCart'])){
                        $arrCart = $_SESSION['arrCart'];
                        $flag = true;
                        for ($i=0; $i < count($arrCart) ; $i++) { 
                            if($arrCart[$i]['topic'] == 1){
                                if($arrCart[$i]['name'] == $arrData['name'] && $arrCart[$i]['img'] == $arrData['img']){
                                    $arrProductData = $arrCart[$i]['specs'];
                                    $arrObjData = $arrData['specs'];
                                    $flagFrame = false;
                                    if(count($arrProductData) == count($arrObjData)){
                                        for ($j = 0; $j < count($arrProductData); $j++) {
                                            if($arrProductData[$j]['value'] == $arrObjData[$j]['value']){
                                                $flagFrame = false;
                                            }else{
                                                $flagFrame = true;
                                                break;
                                            }
                                        }
                                        if(!$flagFrame){
                                            $arrCart[$i]['qty'] +=$arrData['qty'];
                                            $arrCart[$i] = calcWholsale($arrCart[$i]);
                                            $flag = false;
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                        if($flag){
                            $arrData = calcWholsale($arrData);
                            array_push($arrCart,$arrData);
                        }

                        $_SESSION['arrCart'] = $arrCart;
                    }else{
                        $arrData = calcWholsale($arrData);
                        array_push($arrCart,$arrData);

                        $_SESSION['arrCart'] = $arrCart;
                    }
                    $qtyCart = 0;
                    foreach ($_SESSION['arrCart'] as $quantity) {
                        $qtyCart += $quantity['qty'];
                    }
                    $arrResponse = array("status"=>true,"msg"=>"Ha sido agregado a tu carrito.","qty"=>$qtyCart,"data"=>$pop);
                }else{
                    $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
    }
?>