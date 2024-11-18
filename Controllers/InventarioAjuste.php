<?php
    class InventarioAjuste extends Controllers{
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
        public function ajuste(){
            if($_SESSION['permitsModule']['r']){
                $data['page_tag'] = "ajuste";
                $data['page_title'] = "Ajuste de inventario | Inventario";
                $data['page_name'] = "Ajuste";
                $data['panelapp'] = "functions_inventory_adjustment.js";
                $this->views->getView($this,"ajuste",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function reporte(){
            if($_SESSION['permitsModule']['r']){
                $data['page_tag'] = "ajuste";
                $data['page_title'] = "Reporte ajuste de inventario | Inventario";
                $data['page_name'] = "Reporte";
                $data['panelapp'] = "functions_inventory_adjustment_report.js";
                $this->views->getView($this,"reporte",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function reporteDetalle(){
            if($_SESSION['permitsModule']['r']){
                $data['page_tag'] = "ajuste";
                $data['page_title'] = "Reporte ajuste de inventario detalle | Inventario";
                $data['page_name'] = "Reporte";
                $data['panelapp'] = "functions_inventory_adjustment_report_det.js";
                $this->views->getView($this,"reportedet",$data);
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
                $total = $this->model->selectTotalInventory($strSearch);
                $arrData = array(
                    "html"=>$this->getInventoryHtml($arrProducts,$intTotalPages,$intPageNow),
                    "total_records"=>$total,
                    "data"=>$arrProducts
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
                    <a class="page-link text-secondary" href="#" onclick="getProducts(1)" aria-label="First">
                        <span aria-hidden="true"><i class="fas fa-angle-double-left"></i></span>
                    </a>
                </li>
                <li class="page-item">
                    <a class="page-link text-secondary" href="#" onclick="getProducts('.max(1, $page-1).')" aria-label="Previous">
                        <span aria-hidden="true"><i class="fas fa-angle-left"></i></span>
                    </a>
                </li>
            ';
            for ($i = $startPage; $i < min($startPage + $maxButtons, $totalPages + 1); $i++) {
                $htmlPages .= '<li class="page-item">
                    <a class="page-link  '.($i == $page ? ' bg-primary text-white' : 'text-secondary').'" href="#" onclick="getProducts('.$i.')">'.$i.'</a>
                </li>';
            }
            foreach ($data as $pro) {
                $html.='
                    <tr role="button" onclick="addProduct('.$pro['id'].','."'".$pro['variant_name']."'".','.$pro['product_type'].')">
                        <td class="text-center"><img src="'.$pro['url'].'" height="50"></td>
                        <td class="text-center">'.$pro['stock'].'</td>
                        <td>'.$pro['reference'].'</td>
                        <td>'.$pro['name'].'</td>
                        <td class="text-end">'.$pro['price_purchase_format'].'</td>
                    </tr>
                ';
            }
            $htmlPages .= '
                <li class="page-item">
                    <a class="page-link text-secondary" href="#" onclick="getProducts('.min($totalPages, $page+1).')" aria-label="Next">
                        <span aria-hidden="true"><i class="fas fa-angle-right"></i></span>
                    </a>
                </li>
                <li class="page-item">
                    <a class="page-link text-secondary" href="#" onclick="getProducts('.($pages).')" aria-label="Last">
                        <span aria-hidden="true"><i class="fas fa-angle-double-right"></i></span>
                    </a>
                </li>
            ';
            return array("products"=>$html,"pages"=>$htmlPages);
        }
        public function setAdjustment(){
            if($_SESSION['permitsModule']['w']){
                if($_POST){
                    $arrData = json_decode($_POST['products'],true);
                    if(is_array($arrData)){
                        $strConcept = strClean(clear_cadena($_POST['concept']));
                        $floatTotal = floatval($_POST['total']);
                        $request = $this->model->insertCab($strConcept,$floatTotal);
                        if($request > 0){
                            $requestDet = $this->model->insertDet($request,$arrData);
                            if($requestDet > 0){
                                $arrResponse = array("status"=>true,"msg"=>"Datos guardados.");
                            }else{
                                $arrResponse = array("status"=>false,"msg"=>"Error en el detalle");
                            }
                        }else{
                            $arrResponse = array("status"=>false,"msg"=>"Error en la cabecera");
                        }
                    }else{
                        $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }
        public function getAdjustment(){
            if($_SESSION['permitsModule']['r']){
                $strSearch = strClean($_POST['search']);
                $intPerPage = intval($_POST['perpage']);
                $intPageNow = intval($_POST['page']);
                $strInitialDate = strClean($_POST['initial_date']);
                $strFinalDate = strClean($_POST['final_date']);
                $request = $this->model->selectAdjustment( $strInitialDate, $strFinalDate, $strSearch, $intPerPage, $intPageNow);
                $arrProducts = $request['products'];
                $intTotalPages = $request['pages'];
                $arrData = array(
                    "html"=>$this->getAdjustmentHtml($arrProducts,$intTotalPages,$intPageNow),
                    "total_records"=>$request['total_records'],
                    "export"=>$request['data'],
                    "data"=>$arrProducts
                );
                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function getAdjustmentHtml(array $data,int $pages,$page){
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
                $btnView = '<button class="btn btn-info m-1 text-white" type="button" title="Ver" onclick="viewItem('.$pro['id'].')"><i class="fas fa-eye"></i></button>';

                $html.='
                    <tr>
                        <td class="text-center">'.$pro['id'].'</td>
                        <td>'.$pro['concept'].'</td>
                        <td class="text-end">'.formatNum($pro['total']).'</td>
                        <td class="text-center">'.$pro['date_created'].'</td>
                        <td class="text-center">'.$pro['user'].'</td>
                        <td class="text-center">'.$btnView.'</td>
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
        public function getAdjustmentDet(){
            if($_SESSION['permitsModule']['r']){
                $strSearch = strClean($_POST['search']);
                $intPerPage = intval($_POST['perpage']);
                $intPageNow = intval($_POST['page']);
                $strInitialDate = strClean($_POST['initial_date']);
                $strFinalDate = strClean($_POST['final_date']);
                $request = $this->model->selectAdjustmentDet( $strInitialDate, $strFinalDate, $strSearch, $intPerPage, $intPageNow);
                $arrProducts = $request['products'];
                $intTotalPages = $request['pages'];
                $arrData = array(
                    "html"=>$this->getAdjustmentDetHtml($arrProducts,$intTotalPages,$intPageNow),
                    "total_records"=>$request['total_records'],
                    "export"=>$request['data'],
                    "data"=>$arrProducts
                );
                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function getAdjustmentDetHtml(array $data,int $pages,$page){
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
                $strType = $pro['type'] == 1 ? "Adición" : "Reducción";
                $strName = $pro['variant_name'] != "" ? $pro['name']." ".$pro['variant_name'] : $pro['name'];
                $strReference = $pro['variant_name'] != "" ? $pro['variant_reference'] : $pro['reference'];
                $html.='
                <tr>
                    <td class="text-center">'.$pro['id'].'</td>
                    <td>'.$pro['user'].'</td>
                    <td class="text-center">'.$pro['date_created'].'</td>
                    <td>'.$strReference.'</td>
                    <td>'.$strName.'</td>
                    <td class="text-center">'.$pro['current'].'</td>
                    <td class="text-end">'.formatNum($pro['price']).'</td>
                    <td class="text-center">'.$strType.'</td>
                    <td class="text-center">'.$pro['adjustment'].'</td>
                    <td class="text-center">'.$pro['result'].'</td>
                    <td class="text-end">'.formatNum($pro['subtotal']).'</td>
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
    }

?>