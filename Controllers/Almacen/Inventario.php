<?php
    class Inventario extends Controllers{
        private $strInitialDate;

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

        public function inventarioExcel(){
            if($_SESSION['permitsModule']['r']){
                $data['data'] = json_decode($_POST['data'],true);
                $data['total'] = floatval($_POST['total']);
                $data['file_name'] = 'reporte_inventario_'.rand()*10;
                $data['file_title'] = "Inventario";
                $this->views->getView($this,"inventario-excel",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }

        public function inventarioPdf(){
            if($_SESSION['permitsModule']['r']){
                $data['data'] = json_decode($_POST['data'],true);
                $data['total'] = floatval($_POST['total']);
                $data['file_name'] = 'reporte_inventario_'.rand()*10;
                $data['file_title'] = "Inventario";
                $this->views->getView($this,"inventario-pdf",$data);
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

        public function kardexExcel(){
            if($_SESSION['permitsModule']['r']){
                $data['data'] = json_decode($_POST['data'],true);
                $data['initial_date'] = $_POST['strInititalDate'];
                $data['final_date'] = $_POST['strFinalDate'];
                $data['file_name'] = 'reporte_kardex_'.rand()*10;
                $data['file_title'] = "Kardex";
                $this->views->getView($this,"kardex-excel",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }

        public function kardexPdf(){
            if($_SESSION['permitsModule']['r']){
                $data['data'] = json_decode($_POST['data'],true);
                $data['initial_date'] = $_POST['strInititalDate'];
                $data['final_date'] = $_POST['strFinalDate'];
                $data['file_name'] = 'reporte_kardex_'.rand()*10;
                $data['file_title'] = "Kardex";
                $this->views->getView($this,"kardex-pdf",$data);
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
                $request = $this->model->selectBalance($strSearch,$intPerPage,$intPageNow);
                $arrData = array(
                    "data"=>$request['data'],
                    "full_data"=>$request['full_data'],
                    "total_format"=>formatNum($request['total_balance']),
                    "total"=>$request['total_balance'],
                    "total_records"=>$request['total_records'],
                    "html"=>$this->getInventoryHtml($intPageNow,$request),
                );
                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
            }
            die();
        }

        public function getInventoryHtml($intPageNow,$arrData){
            $html="";
            $data = $arrData['data'];
            foreach ($data as $pro) {
                $html.='
                    <tr>
                        <td>'.$pro['id'].'</td>
                        <td>'.$pro['reference'].'</td>
                        <td>'.$pro['name'].'</td>
                        <td>'.$pro['category'].'</td>
                        <td>'.$pro['subcategory'].'</td>
                        <td class="text-center">'.$pro['measure'].'</td>
                        <td class="text-end">'.$pro['price_purchase_format'].'</td>
                        <td class="text-center">'.$pro['stock'].'</td>
                        <td class="text-end">'.$pro['total_format'].'</td>
                    </tr>
                ';
            }
            return array("products"=>$html,"pages"=>getPagination($intPageNow,$arrData['start_page'],$arrData['total_pages'],$arrData['limit_page']));
        }

        public function getKardex(){
            if($_SESSION['permitsModule']['r']){
                $this->strInitialDate = strClean($_POST['initial_date']);
                $strFinalDate = strClean($_POST['final_date']);
                $strSearch = clear_cadena(strClean($_POST['search']));
                $arrMovements = $this->model->selectMovements($this->strInitialDate,$strFinalDate,$strSearch);
                $arrResponse = [];

                $html=' <tr> <td colspan="16" class="text-center">No hay datos</td></tr>';
                if(!empty($arrMovements)){
                    $html = $this->getKardexHtml($arrMovements);
                }

                $arrResponse = array("html"=>$html,"data"=>$arrMovements);
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
                $name=$e['reference'] != "" ? "$e[reference] - $e[name]" :  $e['name'];
                $html.= '
                    <tr>
                        <td colspan="4" class="table-primary">'.$name.'</td>
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
                        <td class="table-light text-center">Total</td>
                        <td class="table-light text-center">Valor</td>
                        <td class="table-light text-center">Cantidad</td>
                        <td class="table-light text-center">Total</td>
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
                        <td colspan="11" class="'.$bg.'fw-bold text-end">Saldo final:</td>
                        <td class="'.$bg.'text-center">'.$lastStock.'</td>
                        <td class="'.$bg.'text-end">'.formatNum($lastTotal).'</td>
                    </tr>
                ';
            }
            return $html;
        }
    }

?>