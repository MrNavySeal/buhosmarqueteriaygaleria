<?php
    class Marqueteria extends Controllers{
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
        public function categorias(){
            if($_SESSION['permitsModule']['r']){
                $data['page_tag'] = "Categorias";
                $data['page_title'] = "Categorias | Marquetería";
                $data['page_name'] = "categorias";
                $data['panelapp'] = "functions_moldingcategory.js";
                $this->views->getView($this,"categorias",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function propiedades(){
            if($_SESSION['permitsModule']['r']){
                $data['page_tag'] = "Propiedades";
                $data['page_title'] = "Propiedades | Marquetería";
                $data['page_name'] = "propiedades";
                $data['panelapp'] = "functions_molding_props.js";
                $this->views->getView($this,"propiedades",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function molduras(){
            if($_SESSION['permitsModule']['r']){
                $data['page_tag'] = "Molduras";
                $data['page_title'] = "Molduras";
                $data['page_name'] = "moldura";
                $data['panelapp'] = "functions_molding.js";
                $this->views->getView($this,"molduras",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function colores(){
            if($_SESSION['permitsModule']['r']){
                $data['page_tag'] = "Colores";
                $data['page_title'] = "Colores";
                $data['page_name'] = "colores";
                $data['panelapp'] = "functions_colors.js";
                $this->views->getView($this,"colores",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function materiales(){
            if($_SESSION['permitsModule']['r']){
                $data['page_tag'] = "Materiales";
                $data['page_title'] = "Materiales";
                $data['page_name'] = "materiales";
                $data['panelapp'] = "functions_materials.js";
                $this->views->getView($this,"materiales",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        
        public function calculadora(){
            if($_SESSION['permitsModule']['r']){
                $data['page_tag'] = "Calculadora de costos";
                $data['page_title'] = "Calculadora de costos";
                $data['page_name'] = "calculadora";
                $data['tipos'] = $this->model->selectCategories(true);
                $data['panelapp'] = "functions_calculadora.js";
                $this->views->getView($this,"calculadora",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function personalizar($params){
            $company = getCompanyInfo();
            $params = strClean($params);
            $request = $this->model->selectTipo($params);
            if(!empty($request)){
                $data['page_tag'] = 'Calculadora de costos '.$request['name'].' | '.$company['name'];
                $data['page_title'] = 'Calculadora de costos '.$request['name'].' | '.$company['name'];
                $data['page_name'] = "personalizar";
                
                $data['tipo'] = $request;
                $data['molduras'] = $this->getMolduras();
                if($request['id'] == 1){
                    $data['colores'] = $this->model->selectColors();
                    $data['panelapp'] = "marqueteria/functions_personalizar.js";
                    $data['option'] = getFile("Template/Enmarcar/marqueteria/marcos",$data);
                }elseif($request['id'] == 3){
                    $data['colores'] = $this->model->selectColors();
                    $data['panelapp'] = "marqueteria/functions_personalizar_foto.js";
                    $data['option'] = getFile("Template/Enmarcar/marqueteria/foto",$data);
                }elseif($request['id']==4){
                    $data['colores'] = $this->model->selectColors();
                    $data['panelapp'] = "marqueteria/functions_personalizar_lienzo.js";
                    $data['option'] = getFile("Template/Enmarcar/marqueteria/lienzo",$data);
                }elseif($request['id']==5){
                    $data['colores'] = $this->model->selectColors();
                    $data['panelapp'] = "marqueteria/functions_personalizar_espejo.js";
                    $data['option'] = getFile("Template/Enmarcar/marqueteria/espejo",$data);
                }elseif($request['id'] == 6){
                    $data['colores'] = $this->model->selectColors();
                    $data['panelapp'] = "marqueteria/functions_personalizar_papiro.js";
                    $data['option'] = getFile("Template/Enmarcar/papiro",$data);
                }elseif($request['id'] == 7){
                    $data['colores'] = $this->model->selectColors();
                    $data['panelapp'] = "marqueteria/functions_personalizar_directo.js";
                    $data['option'] = getFile("Template/Enmarcar/gobelino",$data);
                }elseif($request['id'] == 8){
                    $data['colores'] = $this->model->selectColors();
                    $data['panelapp'] = "marqueteria/functions_personalizar_retablo.js";
                    $data['option'] = getFile("Template/Enmarcar/marqueteria/retablo",$data);
                }elseif($request['id'] == 9){
                    $data['panelapp'] = "marqueteria/functions_personalizar_marco.js";
                    $data['option'] = getFile("Template/Enmarcar/marco",$data);
                }
                $this->views->getView($this,"personalizar",$data);
            }else{
                header("location: ".base_url()."/marqueteria/calculadora");
            }
            
        }
        /*************************Category methods*******************************/
        public function getCategories(){
            if($_SESSION['permitsModule']['r']){
                $request = $this->model->selectCategories();
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 
                        
                        $image =  media()."/images/uploads/".$request[$i]['image'];
                        $btnEdit="";
                        $btnDelete="";
                        $status="";
                        if($_SESSION['permitsModule']['u']){
                            $btnEdit = '<button class="btn btn-success m-1" type="button" title="Editar" onclick="editItem('.$request[$i]['id'].')"><i class="fas fa-pencil-alt"></i></button>';
                        }
                        if($_SESSION['permitsModule']['d']){
                            $btnDelete = '<button class="btn btn-danger m-1" type="button" title="Eliminar" onclick="deleteItem('.$request[$i]['id'].')"><i class="fas fa-trash-alt"></i></button>';
                        }
                        if($request[$i]['status']==1){
                            $status='<span class="badge me-1 bg-success">Activo</span>';
                        }else if($request[$i]['status']==3){
                            $status='<span class="badge me-1 bg-warning">En proceso</span>';
                        }else{
                            $status='<span class="badge me-1 bg-danger">Inactivo</span>';
                        }
                        $request[$i]['is_visible'] = $request[$i]['is_visible'] ? '<i class="fa fa-check text-success" aria-hidden="true"></i>' : '<i class="fa fa-times text-danger" aria-hidden="true"></i>';
                        $request[$i]['image'] = $image;
                        $request[$i]['status'] = $status;
                        $request[$i]['options'] = $btnEdit.$btnDelete;
                    }
                }
                echo json_encode($request,JSON_UNESCAPED_UNICODE);
            }
            die();
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
                            $request['image'] = media()."/images/uploads/".$request['image'];
                            $arrResponse = array("status"=>true,"data"=>$request);
                        }else{
                            $arrResponse = array("status"=>false,"msg"=>"Error, intenta de nuevo"); 
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }
        public function setCategory(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    if(empty($_POST['txtName']) || empty($_POST['txtDescription']) || empty($_POST['statusList'])){
                        $arrResponse = array("status" => false, "msg" => 'Error de datos');
                    }else{ 
                        $idCategory = intval($_POST['idCategory']);
                        $strName = ucwords(strClean($_POST['txtName']));
                        $strDescription = strClean($_POST['txtDescription']);
                        $strButton = strClean($_POST['txtBtn']);
                        $intStatus = intval($_POST['statusList']);
                        $isVisible = intval($_POST['is_visible']);
                        $route = str_replace(" ","-",$strName);
                        $route = str_replace("?","",$route);
                        $route = strtolower(str_replace("¿","",$route));
                        $route = clear_cadena($route);
                        $photo = "";
                        $photoCategory="";

                        if($idCategory == 0){
                            if($_SESSION['permitsModule']['w']){
                                $option = 1;

                                if($_FILES['txtImg']['name'] == ""){
                                    $photoCategory = "category.jpg";
                                }else{
                                    $photo = $_FILES['txtImg'];
                                    $photoCategory = 'moldingcategory_'.bin2hex(random_bytes(6)).'.png';
                                }

                                $request= $this->model->insertCategory($photoCategory,$strName,$strDescription,$route,$intStatus,$strButton,$isVisible);
                            }
                        }else{
                            if($_SESSION['permitsModule']['u']){
                                $option = 2;
                                $request = $this->model->selectCategory($idCategory);
                                if($_FILES['txtImg']['name'] == ""){
                                    $photoCategory = $request['image'];
                                }else{
                                    if($request['image'] != "category.jpg"){
                                        deleteFile($request['image']);
                                    }
                                    $photo = $_FILES['txtImg'];
                                    $photoCategory = 'moldingcategory_'.bin2hex(random_bytes(6)).'.png';
                                }
                                $request = $this->model->updateCategory($idCategory,$photoCategory,$strName,$strDescription,$route,$intStatus,$strButton,$isVisible);
                            }
                        }
                        if($request > 0 ){
                            if($photo!=""){
                                uploadImage($photo,$photoCategory);
                            }
                            if($option == 1){
                                $arrResponse = array("status"=>true,"msg"=>"Datos guardados.");
                            }else{
                                $arrResponse = array("status"=>true,"msg"=>"Datos actualizados.");
                            }
                        }else if($request == 'exist'){
                            $arrResponse = array('status' => false, 'msg' => 'La categoría ya existe, prueba con otro nombre.');		
                        }else{
                            $arrResponse = array("status" => false, "msg" => 'No es posible guardar los datos.');
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
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
                        $request = $this->model->selectCategory($id);
                        if($request['image']!="category.jpg"){
                            deleteFile($request['image']);
                        }
                        $request = $this->model->deleteCategory($id);
                        if($request=="ok"){
                            $arrResponse = array("status"=>true,"msg"=>"Se ha eliminado.");
                        }else{
                            $arrResponse = array("status"=>false,"msg"=>"No es posible eliminar, intenta de nuevo.");
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }
        /*************************Properties methods*******************************/
        public function getProperties(){
            if($_SESSION['permitsModule']['r']){
                $request = $this->model->selectProperties();
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 
                        
                        $btnEdit="";
                        $btnDelete="";
                        $status="";
                        if($_SESSION['permitsModule']['u']){
                            $btnEdit = '<button class="btn btn-success m-1" type="button" title="Editar" onclick="editItem('.$request[$i]['id'].')"><i class="fas fa-pencil-alt"></i></button>';
                        }
                        if($_SESSION['permitsModule']['d']){
                            $btnDelete = '<button class="btn btn-danger m-1" type="button" title="Eliminar" onclick="deleteItem('.$request[$i]['id'].')"><i class="fas fa-trash-alt"></i></button>';
                        }
                        if($request[$i]['status']==1){
                            $status='<span class="badge me-1 bg-success">Activo</span>';
                        }else{
                            $status='<span class="badge me-1 bg-danger">Inactivo</span>';
                        }
                        $request[$i]['is_material'] = $request[$i]['is_material'] = $request[$i]['is_material'] ? '<i class="fa fa-check text-success" aria-hidden="true"></i>' : '<i class="fa fa-times text-danger" aria-hidden="true"></i>';
                        $request[$i]['status'] = $status;
                        $request[$i]['options'] = $btnEdit.$btnDelete;
                    }
                }
                echo json_encode($request,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function getProperty(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    if(empty($_POST)){
                        $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $id = intval($_POST['id']);
                        $request = $this->model->selectProperty($id);
                        if(!empty($request)){
                            $arrResponse = array("status"=>true,"data"=>$request);
                        }else{
                            $arrResponse = array("status"=>false,"msg"=>"Error, intenta de nuevo"); 
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }
        public function setProperty(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    if(empty($_POST['txtName']) || empty($_POST['statusList'])){
                        $arrResponse = array("status" => false, "msg" => 'Error de datos');
                    }else{ 
                        $id = intval($_POST['id']);
                        $strName = ucwords(strClean($_POST['txtName']));
                        $intStatus = intval($_POST['statusList']);
                        $isVisible = intval($_POST['is_visible']);

                        if($id == 0){
                            if($_SESSION['permitsModule']['w']){
                                $option = 1;
                                $request= $this->model->insertProperty($strName,$intStatus,$isVisible);
                            }
                        }else{
                            if($_SESSION['permitsModule']['u']){
                                $option = 2;
                                $request = $this->model->updateProperty($id,$strName,$intStatus,$isVisible);
                            }
                        }
                        if($request > 0 ){
                            if($option == 1){
                                $arrResponse = array("status"=>true,"msg"=>"Datos guardados.");
                            }else{
                                $arrResponse = array("status"=>true,"msg"=>"Datos actualizados.");
                            }
                        }else if($request == 'exist'){
                            $arrResponse = array('status' => false, 'msg' => 'La propiedad ya existe, prueba con otro nombre.');		
                        }else{
                            $arrResponse = array("status" => false, "msg" => 'No es posible guardar los datos.');
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
			die();
		}
        public function delProperty(){
            if($_SESSION['permitsModule']['d']){
                if($_POST){
                    if(empty($_POST['id'])){
                        $arrResponse=array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $id = intval($_POST['id']);
                        $request = $this->model->deleteProperty($id);
                        if($request=="ok"){
                            $arrResponse = array("status"=>true,"msg"=>"Se ha eliminado.");
                        }else{
                            $arrResponse = array("status"=>false,"msg"=>"No es posible eliminar, intenta de nuevo.");
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }
        /*************************Calc methods*******************************/
        public function calcularMarcoInterno($estilo,$margin,$altura,$ancho,$datos,$option=true){
            $total =0;
            $altura = ceil($margin+$altura);
            $ancho = ceil($margin +$ancho);
            $area = $altura * $ancho;
            $perimetro = 0;
            $desperdicio = 0;
            if($option){
                $desperdicio = $datos['waste'];
                if($datos['type'] != 2){
                    $varas = ceil((2*($altura+$ancho))/(300-$datos['waste']));
                    $desperdicio = $datos['waste']*$varas;
                }
                $perimetro = (2*($altura+$ancho))+$desperdicio;
                if($datos['discount']>0){
                    $total = ($datos['price'] - ($datos['price']*($datos['discount']/100)))*$perimetro;
                }else{
                    $total = $datos['price']*$perimetro;
                }
            }
            $total = ceil($total/100)*100;
            $arrDatos = array("perimetro"=>$perimetro,"area"=>$area,"total"=>$total);
            return $arrDatos;
        }
        public function calcularMarcoEstilos($estilo,$perimetro,$area,$tipo,$vidrio){
            $material = $this->model->selectMaterials();
            $bastidor = 0;
            if($vidrio == 1){
                $vidrio = $material[12]['price'];
                $bastidor = $material[4]['price'];
            }elseif($vidrio == 2){
                $vidrio = $material[3]['price'];
            }else{
                $vidrio = 0;
                $bastidor = 0;
            }
            $paspartu = $material[0]['price'];
            $hijillo = $material[1]['price'];
            $bocel = $material[2]['price'];
            $triplex = $material[5]['price'];
            $espuma = $material[6]['price'];
            $espejo3mm =$material[7]['price'];
            $impresion =$material[8]['price'];
            $retablo =$material[9]['price'];
            $carton = $material[10]['price'];
            $espejo4mm =$material[11]['price'];
            $lienzo = $material[13]['price'];   
            
            $costoCarton = ceil(intval($area*$carton)/1000)*1000;
            $costoVidrio = ceil(intval($area*$vidrio)/1000)*1000;
            $costoBocel = ceil(intval($perimetro*$bocel)/1000)*1000;
            $costoPaspartu = ceil(intval($area*$paspartu)/1000)*1000;
            $costoImpresion = ceil(intval($area * $impresion)/1000)*1000;
            $costoEspejo3mm = ceil(intval($area*$espejo3mm)/1000)*1000;
            $costoEspejo4mm = ceil(intval($area*$espejo4mm)/1000)*1000;
            $costoTriplex = ceil(intval($area*$triplex)/1000)*1000;
            $costoHijillo = ceil(intval($perimetro*$hijillo)/1000)*1000;
            $costoBastidor = ceil(intval($perimetro*$bastidor)/1000)*1000;
            $costoRetablo = ceil(intval($area*$retablo)/1000)*1000;

            $total = 0;
            if($tipo==1){
                if($estilo == 1){
                    $total = $costoVidrio+$costoCarton;
                }else if($estilo == 2){
                    $total = $costoPaspartu+$costoBocel+$costoVidrio+$costoCarton;
                }else if($estilo == 3){
                    $total = $costoPaspartu+$costoVidrio+$costoCarton;
                }else if($estilo == 4){
                    $total = $costoTriplex+$costoHijillo+$costoVidrio+$costoImpresion+$costoCarton;
                }
            }else if($tipo == 3){
                if($estilo == 1){
                    $total = $costoVidrio+$costoImpresion+$costoCarton;
                }else if($estilo == 2){
                    $total = $costoPaspartu+$costoBocel+$costoVidrio+$costoImpresion+$costoCarton;
                }else if($estilo == 3){
                    $total = $costoPaspartu+$costoVidrio+$costoImpresion+$costoCarton;
                }else if($estilo == 4){
                    $total = $costoTriplex+$costoHijillo+$costoVidrio+$costoImpresion+$costoCarton;
                }
                
            }else if($tipo == 4){
                if($estilo == 1){
                    $total = $costoBastidor+$costoTriplex+$costoCarton;
                }else if($estilo == 2){
                    $total = $costoTriplex+$costoHijillo+$costoBastidor+$costoCarton;
                }else if($estilo == 3){
                    $total = $costoTriplex+$costoBastidor+$costoCarton;
                }
            }else if($tipo == 5){
                if($estilo == 1){
                    $total = $costoTriplex + $costoEspejo3mm;
                }else if($estilo == 2){
                    $total = $costoTriplex + $costoEspejo4mm;
                }
            }else if($tipo == 6){
                $total = $costoVidrio + $costoTriplex;
            }else if($tipo == 7){
                $total = ($area * $espuma) + $costoTriplex;
            }else if($tipo == 8){
                if($estilo == 1){
                    $total = $costoRetablo+$costoImpresion;
                }else if($estilo == 2){
                    $total = $costoRetablo;
                }
            }else if($tipo == 9){
                if($estilo == 1){
                    $total = 0;
                }else if($estilo == 2){
                    $total = $costoVidrio + $costoCarton;
                }
            }
            $arrData = array(
                "total"=>$total,
                "costos"=>array(
                    "vidrio"=>formatNum($costoVidrio),
                    "paspartu"=>formatNum($costoPaspartu),
                    "hijillo"=>formatNum($costoHijillo),
                    "bocel"=>formatNum($costoBocel),
                    "bastidor"=>formatNum($costoBastidor),
                    "mdf"=>formatNum($costoCarton),
                    "espejo4mm"=>formatNum($costoEspejo4mm),
                    "espejo3mm"=>formatNum($costoEspejo3mm),
                    "impresion"=>formatNum($costoImpresion),
                    "retablo"=>formatNum($costoRetablo),
                    "triplex"=>formatNum($costoTriplex)
                )
            );
            return $arrData;
        }
        public function calcularMarcoTotal($datos=null){
            
            if($_POST){
                $id = intval(openssl_decrypt($_POST['id'],METHOD,KEY));
                if(is_numeric($id)){
                    $request=array();
                    $option = false;
                    if($id != 0){
                        $request = $this->model->selectMoldura($id);
                        $option = true;
                    }
                    
                    $margin = intval($_POST['margin'])*2;
                    $altura = floatval($_POST['height']);
                    $ancho = floatval($_POST['width']);
                    $estilo = intval($_POST['style']);
                    $tipo = intval($_POST['type']);
                    $vidrio = intval($_POST['glass']);
                    
    
                    $marcoTotal = $this->calcularMarcoInterno($estilo,$margin,$altura,$ancho,$request,$option);
                    $marcoEstilos = $this->calcularMarcoEstilos($estilo,$marcoTotal['perimetro'],$marcoTotal['area'],$tipo,$vidrio);
                    
                    $total = ceil((intval($marcoEstilos['total']+$marcoTotal['total']))/1000)*1000;
                    $precio = ceil((intval(UTILIDAD*((($marcoEstilos['total']+$marcoTotal['total'])*COMISION)+TASA)))/1000)*1000;
                    $marcoEstilos['costos']['marco'] = formatNum(ceil($marcoTotal['total']/1000)*1000);
                    $request['total'] = array("total"=>$total,"price"=>formatNum($precio),"utilidad"=>formatNum($precio-$total),"format"=>formatNum($total));
                    $request['costo'] = $marcoEstilos['costos'];
                    $arrResponse = array("status"=>true,"data"=>$request);
                }else{
                    $arrResponse = array("status"=>false,"msg"=>"Error de datos"); 
                }
                //$request['total'] = $this->calcularMarco(floatval($_POST['height']),floatval($_POST['width']),$id);
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function getMolduras($option=null,$search=null,$sort=null,$perimetro=""){
            $html="";
            $request="";
            if($option == 1){
                //$request = $this->searchT($search,$sort,$perimetro);
            }else if($option == 2){
                $request = $this->model->sort($search,$sort,$perimetro);
            }else{
                $request = $this->model->selectMolduras($perimetro);
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
                        <div class="mb-3 frame--container" data-r="'.$request[$i]['reference'].'">
                            <div class="frame--item frame-main element--hover" data-id="'.$id.'" onclick="selectActive(this,`.frame-main`)">
                                '.$discount.'
                                <img src="'.$request[$i]['image'].'" alt="'.$type.'">
                                <p>REF: '.$request[$i]['reference'].'</p>
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
        public function sort(){
            if($_POST){
                $perimetro = (floatval($_POST['height'])+floatval($_POST['width']))*2;
                $arrResponse = $this->getMolduras(2,null,intval($_POST['sort']),null);
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        /*************************Product methods*******************************/
        public function getProducts($option=null,$params=null){
            if($_SESSION['permitsModule']['r']){
                $request = $this->model->selectProducts();
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 

                        $status="";
                        $type="";
                        $btnView = '<button class="btn btn-info m-1 text-white" type="button" title="Ver" onclick="viewItem('.$request[$i]['id'].')" ><i class="fas fa-eye"></i></button>';
                        $btnEdit="";
                        $btnDelete="";
                        $price = formatNum($request[$i]['price'],false);
                        if($request[$i]['discount']>0){
                            $discount = '<span class="text-success">'.$request[$i]['discount'].'% OFF</span>';
                        }else{
                            $discount = '<span class="text-danger">0%</span>';
                        }
                        if($_SESSION['permitsModule']['u']){
                            $btnEdit = '<button class="btn btn-success m-1 text-white" type="button" title="Editar" onclick="editItem('.$request[$i]['id'].')" ><i class="fas fa-pencil-alt"></i></button>';
                        }
                        if($_SESSION['permitsModule']['d']){
                            $btnDelete = '<button class="btn btn-danger m-1 text-white" type="button" title="Eliminar" onclick="deleteItem('.$request[$i]['id'].')" ><i class="fas fa-trash-alt"></i></button>';
                        }
                        if($request[$i]['status']==1){
                            $status='<span class="badge me-1 bg-success">Activo</span>';
                        }else{
                            $status='<span class="badge me-1 bg-warning">Inactivo</span>';
                        }
                        if($request[$i]['type']==1){
                            $type='Madera';
                        }elseif($request[$i]['type']==2){
                            $type='Poliestireno';
                        }else{
                            $type='Madera diseño unico';
                        }
                        $request[$i]['status'] = $status;
                        $request[$i]['discount'] = $discount;
                        $request[$i]['price'] = $price;
                        $request[$i]['type'] = $type;
                        $request[$i]['options'] = $btnView.$btnEdit.$btnDelete;
                    }
                }
                echo json_encode($request,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function getProduct(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    if(empty($_POST)){
                        $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $id = intval($_POST['idProduct']);
                        $request = $this->model->selectProduct($id);
                        $this->model->deleteTmpImage();
                        if(!empty($request)){
                            $request['priceFormat'] = formatNum($request['price'],false);
                            $arrImages = $this->model->selectImages($id);
                            for ($i=0; $i < count($arrImages) ; $i++) { 
                                $this->model->insertTmpImage($arrImages[$i]['name'],$arrImages[$i]['rename']);
                            }
                            $arrResponse = array("status"=>true,"data"=>$request);
                        }else{
                            $arrResponse = array("status"=>false,"msg"=>"No hay datos"); 
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }
        public function setProduct(){
            //dep($_POST);exit;
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    if(empty($_POST['txtReference']) || empty($_POST['statusList']) || empty($_POST['molduraList'])
                    || empty($_POST['txtWaste']) || empty($_POST['txtPrice'])){
                        $arrResponse = array("status" => false, "msg" => 'Error de datos');
                    }else{ 
                        $idProduct = intval($_POST['idProduct']);
                        $strReference = strtoupper(strClean($_POST['txtReference']));
                        $intType = strClean($_POST['molduraList']);
                        $intWaste = intval($_POST['txtWaste']);
                        $intPrice = intval($_POST['txtPrice']);
                        $intDiscount = intval($_POST['txtDiscount']);
                        $intStatus = intval($_POST['statusList']);
                        
                        $imgInfo = "";
                        $imgName="";

                        $photos = $this->model->selectTmpImages();
                        if($idProduct == 0){
                            if($_SESSION['permitsModule']['w']){
                                $option = 1;

                                if($_FILES['txtFrame']['name'] == ""){
                                    $photoCategory = "category.jpg";
                                }else{
                                    $imgInfo = $_FILES['txtFrame'];
                                    $imgName = 'frame_'.bin2hex(random_bytes(6)).'.png';
                                }

                                $request= $this->model->insertProduct($strReference,$intType, $intWaste, $intPrice, $intDiscount, $intStatus, $imgName, $photos);
                            }
                        }else{
                            if($_SESSION['permitsModule']['u']){
                                $option = 2;
                                $request = $this->model->selectProduct($idProduct);
                                if($_FILES['txtFrame']['name'] == ""){
                                    $imgName = $request['frame'];
                                }else{
                                    if($request['frame'] != "category.jpg"){
                                        deleteFile($request['frame']);
                                    }
                                    $imgInfo = $_FILES['txtFrame'];
                                    $imgName = 'frame_'.bin2hex(random_bytes(6)).'.png';
                                }
                                $request= $this->model->updateProduct($idProduct,$strReference,$intType, $intWaste, $intPrice, $intDiscount, $intStatus, $imgName, $photos);
                            }
                        }
                        if($request > 0 ){
                            $this->model->deleteTmpImage();
                            if($imgInfo!=""){
                                uploadImage($imgInfo,$imgName);
                            }
                            if($option == 1){
                                $arrResponse = array("status"=>true,"msg"=>"Datos guardados");
                            }else{
                                $arrResponse = array("status"=>true,"msg"=>"Datos actualizados");
                            }
                        }else if($request == 'exist'){
                            $arrResponse = array('status' => false, 'msg' => '¡Atención! La moldura ya existe, pruebe con otra referencia.');		
                        }else{
                            $arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
			die();
		}
        public function delProduct(){
            if($_SESSION['permitsModule']['d']){
                if($_POST){
                    if(empty($_POST['idProduct'])){
                        $arrResponse=array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $id = intval($_POST['idProduct']);
                        $request = $this->model->selectProduct($id);
                        if($request['frame']!="category.jpg"){
                            deleteFile($request['frame']);
                        }
                        $request = $this->model->deleteProduct($id);
                        if($request=="ok"){
                            $this->model->deleteTmpImage();
                            $arrResponse = array("status"=>true,"msg"=>"Se ha eliminado.");
                        }else{
                            $arrResponse = array("status"=>false,"msg"=>"No se ha podido eliminar, inténta de nuevo.");
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }
        public function setImg(){ 
            $arrImages = orderFiles($_FILES['txtImg'],"molding");
            for ($i=0; $i < count($arrImages) ; $i++) { 
                $request = $this->model->insertTmpImage($arrImages[$i]['name'],$arrImages[$i]['rename']);
            }
            $arrResponse = array("msg"=>"Uploaded");
            echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            die();
        }
        public function delImg(){
            $images = $this->model->selectTmpImages();
            $image = $_POST['image'];
            for ($i=0; $i < count($images) ; $i++) { 
                if($image == $images[$i]['name']){
                    deleteFile($images[$i]['rename']);
                    $this->model->deleteTmpImage($images[$i]['rename']);
                    break;
                }
            }
            $arrResponse = array("msg"=>"Deleted");
            echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            die();
        }
        /*************************Color methods*******************************/
        public function getColors($option=null,$params=null){
            if($_SESSION['permitsModule']['r']){
                $request = $this->model->selectColors();
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 

                        $btnEdit="";
                        $btnDelete="";
                        $status="";
                        
                        if($_SESSION['permitsModule']['u']){
                            $btnEdit = '<button class="btn btn-success m-1" type="button" title="Editar" onclick="editItem('.$request[$i]['id'].')"><i class="fas fa-pencil-alt"></i></button>';
                        }
                        if($_SESSION['permitsModule']['d']){
                            $btnDelete = '<button class="btn btn-danger m-1" type="button" title="Eliminar" onclick="deleteItem('.$request[$i]['id'].')"><i class="fas fa-trash-alt"></i></button>';
                        }
                        if($request[$i]['status']==1){
                            $status='<span class="badge me-1 bg-success">Activo</span>';
                        }else{
                            $status='<span class="badge me-1 bg-danger">Inactivo</span>';
                        }
                        $request[$i]['status'] = $status;
                        $request[$i]['options'] = $btnEdit.$btnDelete;
                    }
                }
                echo json_encode($request,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function getColor(){
            if($_SESSION['permitsModule']['r']){

                if($_POST){
                    if(empty($_POST)){
                        $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $idColor = intval($_POST['idColor']);
                        $request = $this->model->selectColor($idColor);
                        if(!empty($request)){
                            $arrResponse = array("status"=>true,"data"=>$request);
                        }else{
                            $arrResponse = array("status"=>false,"msg"=>"Error, intenta de nuevo"); 
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }
        public function setColor(){
            if($_SESSION['permitsModule']['w']){
                if($_POST){
                    if(empty($_POST['txtName']) || empty($_POST['txtColor']) || empty($_POST['statusList'])){
                        $arrResponse = array("status" => false, "msg" => 'Error de datos');
                    }else{ 
                        $idColor = intval($_POST['idColor']);
                        $strName = ucwords(strClean($_POST['txtName']));
                        $strColor = strClean($_POST['txtColor']);
                        $intStatus = intval($_POST['statusList']);

                        if($idColor == 0){
                            if($_SESSION['permitsModule']['w']){
                                $option = 1;

                                $request= $this->model->insertColor($strName,$strColor,$intStatus);
                            }
                        }else{
                            if($_SESSION['permitsModule']['u']){
                                $option = 2;
                                $request = $this->model->updateColor($idColor,$strName,$strColor,$intStatus);
                            }
                        }
                        if($request > 0 ){
                            if($option == 1){
                                $arrResponse = array("status"=>true,"Datos guardados");
                            }else{
                                $arrResponse = array("status"=>true,"Datos actualizados");
                            }
                        }else if($request == 'exist'){
                            $arrResponse = array('status' => false, 'msg' => 'El color ya existe, prueba con otro nombre.');		
                        }else{
                            $arrResponse = array("status" => false, "msg" => 'No es posible guardar los datos.');
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
			die();
		}
        public function delColor(){
            if($_SESSION['permitsModule']['d']){

                if($_POST){
                    if(empty($_POST['idColor'])){
                        $arrResponse=array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $id = intval($_POST['idColor']);
                        $request = $this->model->deleteColor($id);

                        if($request=="ok"){
                            $arrResponse = array("status"=>true,"msg"=>"Se ha eliminado.");
                        }else{
                            $arrResponse = array("status"=>false,"msg"=>"No es posible eliminar, intenta de nuevo.");
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }
        /*************************Materials methods*******************************/
        public function getMaterials($option=null,$params=null){
            if($_SESSION['permitsModule']['r']){
                $request = $this->model->selectMaterials();
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 

                        $btnEdit="";
                        $btnDelete="";
                        
                        if($_SESSION['permitsModule']['u']){
                            $btnEdit = '<button class="btn btn-success m-1" type="button" title="Editar" onclick="editItem('.$request[$i]['id'].')" ><i class="fas fa-pencil-alt"></i></button>';
                        }
                        if($_SESSION['permitsModule']['d']){
                            $btnDelete = '<button class="btn btn-danger m-1" type="button" title="Eliminar" onclick="deleteItem('.$request[$i]['id'].')" ><i class="fas fa-trash-alt"></i></button>';
                        }
                        $request[$i]['price'] = formatNum($request[$i]['price']).' X '.$request[$i]['unit'];
                        $request[$i]['options'] = $btnEdit.$btnDelete;
                    }
                }
                echo json_encode($request,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function getMaterial(){
            if($_SESSION['permitsModule']['r']){

                if($_POST){
                    if(empty($_POST)){
                        $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $idMaterial = intval($_POST['idMaterial']);
                        $request = $this->model->selectMaterial($idMaterial);
                        if(!empty($request)){
                            $arrResponse = array("status"=>true,"data"=>$request);
                        }else{
                            $arrResponse = array("status"=>false,"msg"=>"Error, intenta de nuevo"); 
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }
        public function setMaterial(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    if(empty($_POST['txtName']) || empty($_POST['txtPrice']) || empty($_POST['txtUnit'])){
                        $arrResponse = array("status" => false, "msg" => 'Error de datos');
                    }else{ 
                        $idMaterial = intval($_POST['idMaterial']);
                        $strName = ucwords(strClean($_POST['txtName']));
                        $intPrice = intval($_POST['txtPrice']);
                        $strUnit = strClean($_POST['txtUnit']);
                        $strPre = strtolower(str_replace(" ","",$strName));
                        if($idMaterial == 0){
                            if($_SESSION['permitsModule']['w']){
                                $option = 1;

                                $request= $this->model->insertMaterial($strName,$strUnit,$intPrice,$strPre);
                            }
                        }else{
                            if($_SESSION['permitsModule']['u']){
                                $option = 2;
                                $request = $this->model->updateMaterial($idMaterial,$strName,$strUnit,$intPrice,$strPre);
                            }
                        }
                        if($request > 0 ){
                            if($option == 1){
                                $arrResponse = array('status' => true, 'msg' => 'Datos guardados.');
                            }else{
                                $arrResponse = array('status' => true, 'msg' => 'Datos actualizados.');
                            }
                        }else if($request == 'exist'){
                            $arrResponse = array('status' => false, 'msg' => 'El material ya existe, prueba con otro nombre.');		
                        }else{
                            $arrResponse = array("status" => false, "msg" => 'No es posible guardar los datos.');
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
			die();
		}
        public function delMaterial(){
            if($_SESSION['permitsModule']['d']){

                if($_POST){
                    if(empty($_POST['idMaterial'])){
                        $arrResponse=array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $id = intval($_POST['idMaterial']);
                        $request = $this->model->deleteMaterial($id);

                        if($request=="ok"){
                            $arrResponse = array("status"=>true,"msg"=>"Se ha eliminado.");
                        }else{
                            $arrResponse = array("status"=>false,"msg"=>"No es posible eliminar, intenta de nuevo.");
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }
        
    }

?>