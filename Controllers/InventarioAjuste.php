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
                $data['page_title'] = "Ajuste de inventario";
                $data['page_name'] = "Ajuste";
                $data['panelapp'] = "functions_inventory_adjustment.js";
                $this->views->getView($this,"ajuste",$data);
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
    }

?>