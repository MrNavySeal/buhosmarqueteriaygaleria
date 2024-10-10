<?php 
    class InventarioAjusteModel extends Mysql{
        private $intId;
        public function __construct(){
            parent::__construct();
        }
        public function selectTotalInventory(string $strSearch){
            $sql = "SELECT coalesce(count(*),0) as total
            FROM product p
            INNER JOIN category c ON c.idcategory = p.categoryid
            INNER JOIN subcategory s ON s.idsubcategory = p.subcategoryid
            LEFT JOIN product_variations_options v ON v.product_id = p.idproduct
            WHERE p.is_stock = 1 AND p.status = 1 AND c.status = 1 AND s.status = 1 
            AND (c.name like '$strSearch%' OR s.name like '$strSearch%' OR p.name like '$strSearch%' 
            OR v.name like '$strSearch%' OR v.sku like '$strSearch%' OR p.reference like '$strSearch%')
            AND ((p.product_type = 1 AND v.stock > 0) OR (p.product_type = 0 AND p.stock > 0))";
            $request = $this->select($sql)['total'];
            return $request;
        }
        public function selectProducts(string $strSearch,int $intPerPage,int $intPageNow){
            $start = ($intPageNow-1)*$intPerPage;
            $arrProducts = [];
            $sql = "SELECT 
            p.idproduct,
            p.reference,
            p.name,
            p.stock,
            p.product_type,
            p.price_purchase,
            c.name as category,
            s.name as subcategory,
            v.name as variant_name,
            v.price_purchase as variant_purchase,
            v.stock as variant_stock,
            v.sku as variant_sku,
            m.initials as measure,
            va.variation
            FROM product p
            INNER JOIN category c ON c.idcategory = p.categoryid
            INNER JOIN subcategory s ON s.idsubcategory = p.subcategoryid
            LEFT JOIN product_variations_options v ON v.product_id = p.idproduct
            LEFT JOIN measures m ON m.id_measure = p.measure
            LEFT JOIN product_variations va ON va.id = v.product_variation_id
            WHERE p.is_stock = 1 AND p.status = 1 AND c.status = 1 AND s.status = 1 
            AND (c.name like '$strSearch%' OR s.name like '$strSearch%' OR p.name like '$strSearch%' 
            OR v.name like '$strSearch%' OR v.sku like '$strSearch%' OR p.reference like '$strSearch%')
            AND ((p.product_type = 1 AND v.stock > 0) OR (p.product_type = 0 AND p.stock > 0)) LIMIT $start,$intPerPage";
            $request = $this->select_all($sql);

            $sqlTotal = "SELECT COALESCE(COUNT(*),0) as total
            FROM product p
            INNER JOIN category c ON c.idcategory = p.categoryid
            INNER JOIN subcategory s ON s.idsubcategory = p.subcategoryid
            LEFT JOIN product_variations_options v ON v.product_id = p.idproduct
            WHERE p.is_stock = 1 AND p.status = 1 AND c.status = 1 AND s.status = 1 
            AND (c.name like '$strSearch%' OR s.name like '$strSearch%' OR p.name like '$strSearch%' 
            OR v.name like '$strSearch%' OR v.sku like '$strSearch%' OR p.reference like '$strSearch%')
            AND ((p.product_type = 1 AND v.stock > 0) OR (p.product_type = 0 AND p.stock > 0))";


            $totalRecords = $this->select($sqlTotal)['total'];
            $totalPages = $totalRecords > 0 ? ceil($totalRecords/$intPerPage) : 0;
            if(!empty($request)){
                foreach ($request as $pro) {
                    $variantHtml = "";
                    if($pro['product_type']){
                        $arrVariantName = explode("-",$pro['variant_name']);
                        $variantHtml = "<ul>";
                        $variation = json_decode($pro['variation'],true);
                        foreach ($variation as $var) {
                            $option ="";
                            $arrOptions = $var['options'];
                            $totalVariantName = count($arrVariantName);
                            for ($i=0; $i < $totalVariantName ; $i++) { 
                                $element = $arrVariantName[$i];
                                $optionFilter = array_values(array_filter($arrOptions,function($e)use($element){return $element == $e;}));
                                if(count($optionFilter) > 0){
                                    $option = $optionFilter[0];
                                    break;
                                }
                            } 
                            $variantHtml.= '<li ><span class="fw-bold">'.$var['name'].': </span>'.$option.'</li>';
                        }
                        $variantHtml.='</ul>';
                    }
                    array_push($arrProducts,array(
                        "id"=>$pro['idproduct'],
                        "reference"=>$pro['variant_sku'] != "" ? $pro['variant_sku'] : $pro['reference'],
                        "product_name"=>$pro['name'],
                        "name"=>$pro['variant_name'] != "" ? $pro['name']." ".$pro['variant_name'] : $pro['name'],
                        "price_purchase"=>$pro['variant_name'] != "" ? $pro['variant_purchase'] : $pro['price_purchase'],
                        "price_purchase_format"=>$pro['variant_name'] != "" ? formatNum($pro['variant_purchase']) : formatNum($pro['price_purchase']),
                        "category"=>$pro['category'],
                        "subcategory"=>$pro['subcategory'],
                        "stock"=>$pro['variant_name'] != "" ? $pro['variant_stock'] : $pro['stock'],
                        "total"=>$pro['variant_name'] != "" ? $pro['variant_stock'] *$pro['variant_purchase']:  $pro['stock']*$pro['price_purchase'],
                        "total_format"=>$pro['variant_name'] != "" ? formatNum($pro['variant_stock'] *$pro['variant_purchase']):  formatNum($pro['stock']*$pro['price_purchase']),
                        "measure"=>$pro['measure'],
                        "variation"=>$pro['variation'],
                        "variant_name"=>$pro['variant_name'],
                        "product_type"=>$pro['product_type'],
                        "variant_html"=>$variantHtml
                    ));
                }
            }
            return array("products"=>$arrProducts,"pages"=>$totalPages);
        }
        public function selectProductsAdjustment(string $strSearch,int $intPerPage,int $intPageNow){
            $start = ($intPageNow-1)*$intPerPage;
            $sql = "SELECT 
                p.idproduct,
                p.reference,
                p.name,
                p.price_purchase,
                p.stock,
                p.product_type,
                p.is_stock
            FROM product p
            INNER JOIN category c ON c.idcategory = p.categoryid
            INNER JOIN subcategory s ON s.idsubcategory = p.subcategoryid
            LEFT JOIN product_variations_options v ON v.product_id = p.idproduct
            WHERE p.is_stock = 1 AND p.status = 1 AND c.status = 1 AND s.status = 1 
            AND (c.name like '$strSearch%' OR s.name like '$strSearch%' OR p.name like '$strSearch%' 
            OR v.name like '$strSearch%' OR v.sku like '$strSearch%' OR p.reference like '$strSearch%')
            AND ((p.product_type = 1 AND v.stock > 0) OR (p.product_type = 0 AND p.stock > 0)) LIMIT $start,$intPerPage
            ";
            $request = $this->select_all($sql);
            $sqlTotal = "SELECT COALESCE(COUNT(*),0) as total
            FROM product p
            INNER JOIN category c ON c.idcategory = p.categoryid
            INNER JOIN subcategory s ON s.idsubcategory = p.subcategoryid
            LEFT JOIN product_variations_options v ON v.product_id = p.idproduct
            WHERE p.is_stock = 1 AND p.status = 1 AND c.status = 1 AND s.status = 1 
            AND (c.name like '$strSearch%' OR s.name like '$strSearch%' OR p.name like '$strSearch%' 
            OR v.name like '$strSearch%' OR v.sku like '$strSearch%' OR p.reference like '$strSearch%')
            AND ((p.product_type = 1 AND v.stock > 0) OR (p.product_type = 0 AND p.stock > 0)) LIMIT $start,$intPerPage";
            $totalRecords = $this->select($sqlTotal)['total'];
            $totalPages = $totalRecords > 0 ? ceil($totalRecords/$intPerPage) : 0;
            if(count($request)> 0){
                for ($i=0; $i < count($request); $i++) { 
                    $idProduct = $request[$i]['idproduct'];
                    if($request[$i]['product_type'] == 1){
                        $sqlV = "SELECT MIN(price_sell) AS sell,MIN(price_offer) AS offer,MIN(price_purchase) AS purchase
                        FROM product_variations_options WHERE product_id =$idProduct";
                        $requestPrices = $this->select($sqlV);
                        $sqlTotal = "SELECT SUM(stock) AS total FROM product_variations_options WHERE product_id =$idProduct";
                        $request[$i]['price_purchase'] = $requestPrices['purchase'];
                        $request[$i]['price'] = $requestPrices['sell'];
                        $request[$i]['discount'] = $requestPrices['offer'];
                        $request[$i]['stock'] = $this->select($sqlTotal)['total'];
                    }
                }
            }
            return array("products"=>$request,"pages"=>$totalPages);
        }
        public function selectProduct($id){
            $this->intId = $id;
            $sql = "SELECT 
                p.idproduct,
                p.name,
                p.reference,
                p.price_purchase,
                p.price,
                p.product_type,
                p.is_stock,
                p.stock,
                p.import
            FROM product p
            INNER JOIN category c, subcategory s
            WHERE c.idcategory = p.categoryid AND c.idcategory = s.categoryid AND p.subcategoryid = s.idsubcategory
            AND p.is_stock = 1 AND p.status = 1 AND p.idproduct = $this->intId";
            $request = $this->select($sql);
            if(!empty($request)){
                if($request['product_type'] == 1){
                    $request['variation'] = $this->select("SELECT * FROM product_variations WHERE product_id = $this->intId");
                    $request['variation']['variation'] = json_decode($request['variation']['variation']);
                    $options = $this->select_all("SELECT * FROM product_variations_options WHERE product_id = $this->intId");
                    $totalOptions = count($options);
                    for ($i=0; $i < $totalOptions ; $i++) { 
                        $options[$i]['format_purchase'] = "$".number_format($options[$i]['price_purchase'],0,",",".");
                    }
                    $request['options'] = $options;
                }
            }
            return $request;
        }
    }
?>