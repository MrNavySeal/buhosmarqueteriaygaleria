<?php
    
    require_once("Models/EnmarcarTrait.php");
    class Enmarcar extends Controllers{
        use EnmarcarTrait;
        public function __construct(){
            session_start();
            parent::__construct();
            sessionCookie();
        }

        public function enmarcar(){
            $company = getCompanyInfo();
            $data['page_tag'] = "Enmarcar | ".$company['name'];
            $data['page_title'] = "Enmarcar | ".$company['name'];
            $data['page_name'] = "enmarcar";
            $data['tipos'] = $this->selectTipos();
            $this->views->getView($this,"enmarcar",$data);
            
        }
        public function personalizar($params){
            $company = getCompanyInfo();
            $params = strClean($params);
            $request = $this->selectTipo($params);
            if(!empty($request)){
                $data['page_tag'] = 'Enmarcar '.$request['name'].' | '.$company['name'];
                $data['page_title'] = 'Enmarcar '.$request['name'].' | '.$company['name'];
                $data['page_name'] = "personalizar";
                $data['tipo'] = $request;
                $data['molduras'] = $this->getProducts();
                if($request['id'] == 1){
                    $data['colores'] = $this->selectColors();
                    $data['app'] = "functions_personalizar.js";
                    $data['option'] = getFile("Template/Enmarcar/general",$data);
                }elseif($request['id'] == 3){
                    $data['app'] = "functions_personalizar_espejo.js";
                    $data['option'] = getFile("Template/Enmarcar/espejo",$data);
                }elseif($request['id']==4){
                    $data['colores'] = $this->selectColors();
                    $data['app'] = "functions_personalizar.js";
                    $data['option'] = getFile("Template/Enmarcar/lienzo",$data);
                }elseif($request['id']==5){
                    $data['colores'] = $this->selectColors();
                    $data['app'] = "functions_personalizar_foto.js";
                    $data['option'] = getFile("Template/Enmarcar/fotografia",$data);
                }elseif($request['id'] == 6){
                    $data['colores'] = $this->selectColors();
                    $data['app'] = "functions_personalizar_papiro.js";
                    $data['option'] = getFile("Template/Enmarcar/papiro",$data);
                }elseif($request['id'] == 7){
                    $data['colores'] = $this->selectColors();
                    $data['app'] = "functions_personalizar_directo.js";
                    $data['option'] = getFile("Template/Enmarcar/gobelino",$data);
                }elseif($request['id'] == 8){
                    $data['colores'] = $this->selectColors();
                    $data['app'] = "functions_personalizar_retablo.js";
                    $data['option'] = getFile("Template/Enmarcar/retablo",$data);
                }elseif($request['id'] == 9){
                    $data['app'] = "functions_personalizar_marco.js";
                    $data['option'] = getFile("Template/Enmarcar/marco",$data);
                }
                $this->views->getView($this,"personalizar",$data);
            }else{
                header("location: ".base_url()."/enmarcar");
            }
            
        }
        public function getProducts($option=null,$search=null,$sort=null,$perimetro=""){
            $html="";
            $request="";
            if($option == 1){
                $request = $this->searchT($search,$sort,$perimetro);
            }else if($option == 2){
                $request = $this->sortT($search,$sort,$perimetro);
            }else{
                $request = $this->selectProducts($perimetro);
            }
            if(count($request)>0){
                for ($i=0; $i < count($request); $i++) { 

                    $type="";
                    $discount="";
                    $price = formatNum($request[$i]['price']);
                    $id = openssl_encrypt($request[$i]['id'],METHOD,KEY);
                    if($request[$i]['discount']>0){
                        $discount = '<span class="discount">'.$request[$i]['discount'].'%</span>';
                    }
                    if($request[$i]['type']==1){
                        $type='Moldura en madera';
                    }else{
                        $type='Moldura importada';
                    }
                    $html.='
                        <div class="col-4 col-lg-3 col-md-4 mb-3">
                            <div class="frame--item frame-main element--hover" data-id="'.$id.'" onclick="selectActive(this,`.frame-main`)">
                                '.$discount.'
                                <img src="'.$request[$i]['image'].'" alt="'.$type.'">
                            </div>
                        </div>
                    ';
                }
                $arrResponse = array("status"=>true,"data"=>$html);
            }else{
                $arrResponse = array("status"=>false,"data"=>"No hay resultados");
            }
            return $arrResponse;
        }
        public function getProduct(){
            if($_POST){
                if(empty($_POST)){
                    $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                }else{
                    $id = intval(openssl_decrypt($_POST['id'],METHOD,KEY));
                    $request = $this->selectProduct($id);
                    if(!empty($request)){
                        $request['total'] = $this->calcularMarcoTotal();
                        $arrResponse = array("status"=>true,"data"=>$request);
                    }else{
                        $arrResponse = array("status"=>false,"msg"=>"No hay datos"); 
                    }
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function search(){
            if($_POST){
                $perimetro = (floatval($_POST['height'])+floatval($_POST['width']))*2;
                $arrResponse = $this->getProducts(1,strClean($_POST['search']),intval($_POST['sort']),$perimetro);
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function sort(){
            if($_POST){
                $perimetro = (floatval($_POST['height'])+floatval($_POST['width']))*2;
                $arrResponse = $this->getProducts(2,strClean($_POST['search']),intval($_POST['sort']),$perimetro);
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function calcularMarcoInterno($estilo,$margin,$altura,$ancho,$datos,$option=true){
            if($estilo == 1){
                $margin = 0;
            }
            $total =0;
            $altura = $margin+$altura;
            $ancho = $margin +$ancho;
            $area = $altura * $ancho;
            $perimetro = 0;
            if($option){
                $perimetro = (2*($altura+$ancho))+$datos['waste'];
                if($datos['discount']>0){
                    $total = ($datos['price'] - ($datos['price']*($datos['discount']/100)))*$perimetro;
                }else{
                    $total = $datos['price']*$perimetro;
                }
            }
            $arrDatos = array("perimetro"=>$perimetro,"area"=>$area,"total"=>$total);
            return $arrDatos;
        }
        public function calcularMarcoEstilos($estilo,$perimetro,$area,$tipo){
            $material = $this->selectMaterials();
            $paspartu = $material[0]['price'];
            $hijillo = $material[1]['price'];
            $bocel = $material[2]['price'];
            $bastidor = $material[4]['price'];
            $triplex = $material[5]['price'];
            $vidrio = $material[3]['price'];
            $espuma = $material[6]['price'];
            $espejo3mm =$material[7]['price'];
            $impresion =$material[8]['price'];
            $retablo =$material[9]['price'];
            $carton = $material[10]['price'];
            $espejo4mm =$material[11]['price'];
            //$espejoBicelado =$material[12]['price'];

            $total = 0;
            if($tipo==1){
                if($estilo == 1){
                    $total = ($area * $vidrio)+($area*$carton);
                }else if($estilo == 2){
                    $total = ($area * $paspartu)+($perimetro*$bocel)+($area*$vidrio)+($area*$carton);
                }else if($estilo == 3){
                    $total = ($area * $paspartu)+($area*$vidrio)+($area*$carton);
                }else if($estilo == 4){
                    $total = ($area * $triplex)+($perimetro*$hijillo)+($area*$vidrio)+($area*$carton);
                }
            }else if($tipo == 3){
                if($estilo == 1){
                    $total = ($area * $triplex) +($area * $espejo3mm);
                }else if($estilo == 2){
                    $total = ($area * $triplex) + ($area * $espejo4mm);
                }/*else if($estilo == 3){
                    $total = ($area * $triplex) + ($area * $espejoBicelado);
                }*/
                
            }else if($tipo == 4){
                if($estilo == 1){
                    $total = $perimetro * $bastidor;
                }else if($estilo == 4){
                    $total = ($area * $triplex)+($perimetro*$hijillo)+($perimetro*$bastidor);
                }else if($estilo == 5){
                    $total = ($area * $triplex)+($perimetro*$bastidor);
                }
            }else if($tipo == 5){
                if($estilo == 1){
                    $total = ($area * $vidrio)+($area*$impresion)+($area*$carton);
                }else if($estilo == 2){
                    $total = ($area * $paspartu)+($perimetro*$bocel)+($area*$vidrio)+($area*$impresion)+($area*$carton);
                }else if($estilo == 3){
                    $total = ($area * $paspartu)+($area*$vidrio)+($area*$impresion)+($area*$carton);
                }else if($estilo == 4){
                    $total = ($area * $triplex)+($perimetro*$hijillo)+($area*$vidrio)+($area*$impresion)+($area*$carton);
                }
            }else if($tipo == 6){
                $total = ($area * $vidrio) + ($area*$triplex);
            }else if($tipo == 7){
                $total = ($area * $espuma) + ($area*$triplex);
            }else if($tipo == 8){
                if($estilo == 1){
                    $total = ($area*$retablo)+($area*$impresion);
                }else if($estilo == 2){
                    $total = ($area*$retablo);
                }
            }else if($tipo == 9){
                if($estilo == 1){
                    $total = 0;
                }else if($estilo == 2){
                    $total = ($area * $vidrio) + ($area * $carton);
                }
            }
            return $total;

        }
        public function calcularMarcoTotal($datos=null){
            if($datos==null){
                if($_POST){
                    $id = intval(openssl_decrypt($_POST['id'],METHOD,KEY));
                    if(is_numeric($id)){
                        $request=array();
                        $option = false;
                        if($id != 0){
                            $request = $this->selectProduct($id);
                            $option = true;
                        }
                        
                        $margin = intval($_POST['margin'])*2;
                        $altura = floatval($_POST['height']);
                        $ancho = floatval($_POST['width']);
                        $estilo = intval($_POST['style']);
                        $tipo = intval($_POST['type']);
        
        
                        $marcoTotal = $this->calcularMarcoInterno($estilo,$margin,$altura,$ancho,$request,$option);
                        $marcoEstilos = $this->calcularMarcoEstilos($estilo,$marcoTotal['perimetro'],$marcoTotal['area'],$tipo);
        
                        $total = intval(UTILIDAD*((($marcoEstilos+$marcoTotal['total'])*COMISION)+TASA));
                        $request['total'] = array("total"=>$total,"format"=>formatNum($total));
                        
                        $arrResponse = array("status"=>true,"data"=>$request);
                    }else{
                        $arrResponse = array("status"=>false,"msg"=>"Error de datos"); 
                    }
                    //$request['total'] = $this->calcularMarco(floatval($_POST['height']),floatval($_POST['width']),$id);
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
                die();
            }elseif($datos !=null){
                //dep($datos);exit;
                $margin = $datos['margin']*2;
                $altura = $datos['height'];
                $ancho = $datos['width'];
                $estilo = $datos['style'];
                $tipo = $datos['type'];
                $frame = array();

                if(!empty($datos['frame'])){
                    $frame = $datos['frame'];
                }

                $marcoTotal = $this->calcularMarcoInterno($estilo,$margin,$altura,$ancho,$frame,$datos['option']);
                $marcoEstilos = $this->calcularMarcoEstilos($estilo,$marcoTotal['perimetro'],$marcoTotal['area'],$tipo);

                $total = intval(UTILIDAD*((($marcoEstilos+$marcoTotal['total'])*COMISION)+TASA));
                return $total;
            }else{
                die();
            }
            
        }
        public function addCart(){
            //unset($_SESSION['arrCart']);exit;
            if($_POST){
                $id = intval(openssl_decrypt($_POST['id'],METHOD,KEY));
                $arrCart = array();
                $qty = intval($_POST['qty']);
                
                if(is_numeric($id)){
                    $option=true;
                    $photo="";
                    if($id != 0){
                        $frame = $this->selectProduct($id);
                    }else{
                        $photo = "retablo.png";
                        $frame = array();
                        $option = false;
                    }

                    $colorMargin = $this->selectColor(intval($_POST['colorMargin']));
                    $colorBorder = $this->selectColor(intval($_POST['colorBorder']));
                    $colorMargin = !empty($colorMargin) ? $colorMargin['name'] : "";
                    $colorBorder = !empty($colorBorder) ? $colorBorder['name'] : "";
                    $height = floatval($_POST['height']);
                    $width = floatval($_POST['width']);
                    $margin = intval($_POST['margin']);
                    $styleName = strClean($_POST['styleName']);
                    $styleValue = intval($_POST['styleValue']);
                    $route = $_POST['route'];
                    $type = $_POST['type'];
                    $idType = intval($_POST['idType']);
                    $orientation = $_POST['orientation'];

                    if(!empty($_FILES['txtPicture'])){
                        if($id!=0){
                            $photo = 'impresion_'.bin2hex(random_bytes(6)).'.png';
                        }else if($id == 0 && $styleValue == 1){
                            $photo = 'retablo_'.bin2hex(random_bytes(6)).'.png';
                        }
                        uploadImage($_FILES['txtPicture'],$photo);
                    }

                    $data = array("frame"=>$frame,"height"=>$height,"width"=>$width,"margin"=>$margin,"style"=>$styleValue,"type"=>$idType,"option"=>$option);
                    $price = $this->calcularMarcoTotal($data);
                    $pop = array("name"=>$type,"image"=>$photo !="" ? media()."/images/uploads/".$photo : $frame['image'][0],"route"=>base_url()."/enmarcar/personalizar/".$route);
                    $arrProduct = array(
                        "topic"=>1,
                        "id"=>openssl_encrypt($id,METHOD,KEY),
                        "name"=>$pop['name'],
                        "type"=>$type,
                        "idType"=>$idType,
                        "orientation"=>$orientation,
                        "style"=>$styleName,
                        "reference"=>$id != 0 ? $frame['reference'] : "",
                        "height"=>$height,
                        "width"=>$width,
                        "margin"=>$styleValue == 1 ? 0:$margin,
                        "colormargin"=>$colorMargin,
                        "colorborder"=>$colorBorder,
                        "price"=>$price,
                        "qty"=>$qty,
                        "url"=>$pop['route'],
                        "img"=>$pop['image'],
                        "photo"=>$photo
                    );
                    if(isset($_SESSION['arrCart'])){
                        $arrCart = $_SESSION['arrCart'];
                        $flag = true;
                        for ($i=0; $i < count($arrCart) ; $i++) { 
                            if($arrCart[$i]['topic'] == 1){
                                if($arrCart[$i]['style'] == $arrProduct['style'] && $arrCart[$i]['height'] == $arrProduct['height'] &&
                                $arrCart[$i]['width'] == $arrProduct['width'] && $arrCart[$i]['margin'] == $arrProduct['margin'] &&
                                $arrCart[$i]['colormargin'] == $arrProduct['colormargin'] && $arrCart[$i]['colorborder'] == $arrProduct['colorborder'] && 
                                $arrCart[$i]['id'] == $arrProduct['id'] && $arrCart[$i]['idType'] == $arrProduct['idType']){
                                    $arrCart[$i]['qty'] +=$qty;
                                    $flag = false;
                                    break;
                                }
                            }
                        }
                        if($flag){
                            array_push($arrCart,$arrProduct);
                        }
                        $_SESSION['arrCart'] = $arrCart;

                    }else{
                        array_push($arrCart,$arrProduct);
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
        public function filterProducts(){
            if($_POST){
                $perimetro = (floatval($_POST['height'])+floatval($_POST['width']))*2;
                $arrResponse = $this->getProducts(null,null,null,$perimetro);
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
    }
?>