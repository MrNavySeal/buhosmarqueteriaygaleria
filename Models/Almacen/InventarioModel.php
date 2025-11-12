<?php 
    class InventarioModel extends Mysql{

        public function __construct(){
            parent::__construct();
        }

        public function selectTotalInventory(string $strSearch){
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
            m.initials as measure
            FROM product p
            INNER JOIN category c ON c.idcategory = p.categoryid
            INNER JOIN subcategory s ON s.idsubcategory = p.subcategoryid
            LEFT JOIN product_variations_options v ON v.product_id = p.idproduct
            LEFT JOIN measures m ON m.id_measure = p.measure
            WHERE p.is_stock = 1 AND p.status = 1 AND c.status = 1 AND s.status = 1 
            AND (c.name like '$strSearch%' OR s.name like '$strSearch%' OR p.name like '$strSearch%' 
            OR v.name like '$strSearch%' OR v.sku like '$strSearch%' OR p.reference like '$strSearch%')
            AND ((p.product_type = 1 AND v.stock > 0) OR (p.product_type = 0 AND p.stock > 0))";
            $request = $this->select_all($sql);
            $total = 0;
            if(!empty($request)){
                foreach ($request as $pro) {
                    $finalPrice = getLastPrice($pro['idproduct'],$pro['variant_name']);
                    if($finalPrice == 0){
                        if($pro['variant_name'] != ""){
                            $finalPrice = $pro['variant_purchase'];
                        }else{
                            $finalPrice = $pro['price_purchase'];
                        }
                    }
                    array_push($arrProducts,array(
                        "id"=>$pro['idproduct'],
                        "reference"=>$pro['variant_sku'] != "" ? $pro['variant_sku'] : $pro['reference'],
                        "name"=>$pro['variant_name'] != "" ? $pro['name']." ".$pro['variant_name'] : $pro['name'],
                        "price_purchase"=>$finalPrice,
                        "price_purchase_format"=>formatNum($finalPrice),
                        "category"=>$pro['category'],
                        "subcategory"=>$pro['subcategory'],
                        "stock"=>$pro['variant_name'] != "" ? $pro['variant_stock'] : $pro['stock'],
                        "total"=>$pro['variant_name'] != "" ? $pro['variant_stock'] *$finalPrice:  $pro['stock']*$finalPrice,
                        "total_format"=>$pro['variant_name'] != "" ? formatNum($pro['variant_stock'] *$finalPrice):  formatNum($pro['stock']*$finalPrice),
                        "measure"=>$pro['measure']
                    ));
                }
                foreach ($arrProducts as $pro) {
                    $total+=$pro['total'];
                }
            }
            return array("total"=>$total,"products"=>$arrProducts);
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
            m.initials as measure
            FROM product p
            INNER JOIN category c ON c.idcategory = p.categoryid
            INNER JOIN subcategory s ON s.idsubcategory = p.subcategoryid
            LEFT JOIN product_variations_options v ON v.product_id = p.idproduct
            LEFT JOIN measures m ON m.id_measure = p.measure
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
                    $finalPrice = getLastPrice($pro['idproduct'],$pro['variant_name']);
                    if($finalPrice == 0){
                        if($pro['variant_name'] != ""){
                            $finalPrice = $pro['variant_purchase'];
                        }else{
                            $finalPrice = $pro['price_purchase'];
                        }
                    }
                    array_push($arrProducts,array(
                        "id"=>$pro['idproduct'],
                        "reference"=>$pro['variant_sku'] != "" ? $pro['variant_sku'] : $pro['reference'],
                        "name"=>$pro['variant_name'] != "" ? $pro['name']." ".$pro['variant_name'] : $pro['name'],
                        "price_purchase"=>$finalPrice,
                        "price_purchase_format"=>formatNum($finalPrice),
                        "category"=>$pro['category'],
                        "subcategory"=>$pro['subcategory'],
                        "stock"=>$pro['variant_name'] != "" ? $pro['variant_stock'] : $pro['stock'],
                        "total"=>$pro['variant_name'] != "" ? $pro['variant_stock'] *$finalPrice:  $pro['stock']*$finalPrice,
                        "total_format"=>$pro['variant_name'] != "" ? formatNum($pro['variant_stock'] *$finalPrice):  formatNum($pro['stock']*$finalPrice),
                        "measure"=>$pro['measure']
                    ));
                }
            }
            return array("products"=>$arrProducts,"pages"=>$totalPages);
        }

        public function selectPurchaseDet(string $strInitialDate,string $strFinalDate,string $strSearch){
            $sql = "SELECT 
            cab.idpurchase as document,
            cab.date,
            DATE_FORMAT(cab.date,'%d/%m/%Y') as date_format,
            det.qty,
            p.idproduct as id,
            p.reference,
            m.initials as measure,
            det.variant_name,
            COALESCE(det.price_purchase,0) as price,
            CONCAT(p.name,' ',det.variant_name) as name
            FROM purchase_det det 
            INNER JOIN purchase cab ON cab.idpurchase = det.purchase_id
            INNER JOIN product p ON p.idproduct = det.product_id
            LEFT JOIN measures m ON m.id_measure = p.measure
            WHERE cab.status = 1 AND p.is_stock = 1 AND cab.date 
            BETWEEN '$strInitialDate' AND '$strFinalDate' AND p.name like '$strSearch%'";
            $request = $this->select_all($sql);
            if(!empty($request)){
                $total = count($request);
                for ($i=0; $i < $total ; $i++) { 
                    $e = $request[$i];
                    $id = $e['id'];

                    if($e['variant_name'] != ""){
                        $sql = "SELECT sku as reference FROM product_variations_options WHERE product_id ='$id' AND name = '$e[variant_name]'";
                        $arrData = $this->select($sql);
                        $strReference = !empty($arrData) ? $arrData['reference'] : "";
                        $strName = strtoupper($strReference)." ".$e['name'];
                        $e['name'] = $strName;
                    }
                    $request[$i]=$e;
                }
                
            }
            return $request;
        }

        public function selectAdjustmentDet(string $strInitialDate,string $strFinalDate,string $strSearch,int $type){
            $sql = "SELECT 
            cab.id as document,
            cab.date,
            DATE_FORMAT(cab.date,'%d/%m/%Y') as date_format,
            det.adjustment as qty,
            p.idproduct as id,
            p.reference,
            det.type,
            m.initials as measure,
            det.variant_name,
            COALESCE(det.price,0) as price,
            CONCAT(p.name,' ',det.variant_name) as name
            FROM adjustment_det det 
            INNER JOIN adjustment_cab cab ON cab.id = det.adjustment_id
            INNER JOIN product p ON p.idproduct = det.product_id
            LEFT JOIN measures m ON m.id_measure = p.measure
            WHERE cab.status = 1 AND p.is_stock = 1 AND cab.date 
            BETWEEN '$strInitialDate' AND '$strFinalDate' AND p.name like '$strSearch%' AND det.type = $type";
            $request = $this->select_all($sql);

            if(!empty($request)){
                $total = count($request);
                for ($i=0; $i < $total ; $i++) { 
                    $e = $request[$i];
                    $id = $e['id'];
                    if($e['variant_name'] != ""){
                        $sql = "SELECT sku as reference FROM product_variations_options WHERE product_id ='$id' AND name = '$e[variant_name]'";
                        $arrData = $this->select($sql);
                        $strReference = !empty($arrData) ? $arrData['reference'] : "";
                        $strName = strtoupper($strReference)." ".$e['name'];
                        $e['name'] = $strName;
                    }
                    $request[$i]=$e;
                }
                
            }

            return $request;
        }

        public function selectOrderDet(string $strInitialDate,string $strFinalDate,string $strSearch){
            $sql = "SELECT 
            cab.idorder as document,
            cab.date,
            DATE_FORMAT(cab.date,'%d/%m/%Y') as date_format,
            det.quantity as qty,
            p.name,
            p.reference,
            COALESCE(p.price,0) AS price,
            p.idproduct as id,
            m.initials as measure,
            det.description,
            HEX(det.description) AS hex_value
            FROM orderdetail det 
            INNER JOIN orderdata cab ON cab.idorder = det.orderid
            INNER JOIN product p ON p.idproduct = det.productid
            LEFT JOIN measures m ON m.id_measure = p.measure
            WHERE cab.status != 'canceled' AND det.topic = 2 AND p.is_stock = 1 
            AND cab.date BETWEEN '$strInitialDate' AND '$strFinalDate' AND p.name like '$strSearch%'";
            $request = $this->select_all($sql);
            if(!empty($request)){
                $total = count($request);
                for ($i=0; $i < $total ; $i++) { 
                    $e = $request[$i];
                    $id = $e['id'];

                    $sqlBalance = "SELECT 
                    COALESCE(SUM(det.quantity),0) as qty,
                    COALESCE(SUM(det.quantity * p.price),0) as total
                    FROM orderdetail det 
                    INNER JOIN orderdata cab ON cab.idorder = det.orderid
                    INNER JOIN product p ON p.idproduct = det.productid
                    WHERE cab.status != 'canceled' AND det.topic = 2 AND p.is_stock = 1 
                    AND cab.date < '$strInitialDate' AND p.idproduct = $id";
                    
                    $arrBalance = $this->select($sqlBalance);

                    $e['previous_price'] = $arrBalance['total'];
                    $e['previous_qty'] = $arrBalance['qty'];

                    $description = json_decode($e['description'],true);
                    if(is_array($description)){
                        $arrDet = $description['detail'];
                        $variantName = implode("-",array_values(array_column($arrDet,"option")));
                        $sql = "SELECT sku as reference FROM product_variations_options WHERE product_id ='$id' AND name = '$variantName'";
                        $arrData = $this->select($sql);
                        $strReference = !empty($arrData) ? $arrData['reference'] : "";
                        $e['name'] = strtoupper($strReference)." ".$e['name']." ".$variantName;
                    }
                    $request[$i] = $e;
                }
            }
            return $request;
        }

        public function selectBalance($product,$strInitialDate){
            $id = $product['id'];
            $condition ="";
            $description =$product['description'];
            if(isset($product['variant_name']) && $product['variant_name'] != ""){
                $name = $product['variant_name'];
                $condition = "AND det.variant_name = '$name'";
            }else{
                $arrDescription = json_decode($description,true);
                if(isset($arrDescription['detail'])){
                    $name = implode("-",array_column($arrDescription['detail'],"option"));
                    $condition = "AND det.variant_name = '$name'";
                }
                $description = $product['hex_value'];
            }

            $sql = "SELECT 
            COALESCE(SUM(det.qty),0) as qty,
            COALESCE(SUM(det.qty * det.price_purchase),0) as total
            FROM purchase_det det 
            INNER JOIN purchase cab ON cab.idpurchase = det.purchase_id
            INNER JOIN product p ON p.idproduct = det.product_id
            WHERE cab.status = 1 AND p.is_stock = 1 AND cab.date 
            AND cab.date < '$strInitialDate'  AND p.idproduct = $id $condition";
            $arrIntputPurchase = $this->select($sql);

            $sql = "SELECT 
            COALESCE(SUM(det.adjustment),0) as qty,
            COALESCE(SUM(det.adjustment * det.price),0) as total
            FROM adjustment_det det 
            INNER JOIN adjustment_cab cab ON cab.id = det.adjustment_id
            INNER JOIN product p ON p.idproduct = det.product_id
            WHERE cab.status = 1 AND p.is_stock = 1 AND cab.date 
            AND cab.date < '$strInitialDate' AND det.type = 1 AND p.idproduct = $id $condition";
            $arrIntputAjust = $this->select($sql);

            $sql = "SELECT 
            COALESCE(SUM(det.adjustment),0) as qty,
            COALESCE(SUM(det.adjustment * det.price),0) as total
            FROM adjustment_det det 
            INNER JOIN adjustment_cab cab ON cab.id = det.adjustment_id
            INNER JOIN product p ON p.idproduct = det.product_id
            WHERE cab.status = 1 AND p.is_stock = 1 AND cab.date 
            AND cab.date < '$strInitialDate' AND det.type = 1 AND p.idproduct = $id $condition";
            $arrOutputAjust = $this->select($sql);

            $sql = "SELECT 
            COALESCE(SUM(det.quantity),0) as qty,
            COALESCE(SUM(det.quantity * p.price),0) as total
            FROM orderdetail det 
            INNER JOIN orderdata cab ON cab.idorder = det.orderid
            INNER JOIN product p ON p.idproduct = det.productid
            WHERE cab.status != 'canceled' AND det.topic = 2 AND p.is_stock = 1 
            AND cab.date < '$strInitialDate' AND p.idproduct = $id AND HEX(det.description) = '$description'";
            $arrOutputSale = $this->select($sql);

            /* dep($arrIntputPurchase);
            dep($arrIntputAjust);
            dep($arrOutputSale);
            dep($arrOutputAjust); */

            $totalQty = ($arrIntputAjust['qty']+$arrIntputPurchase['qty'])-($arrOutputSale['qty']+$arrOutputAjust['qty']);
            $totalPrice = ($arrIntputAjust['total']+$arrIntputPurchase['total'])-($arrOutputSale['total']+$arrOutputAjust['total']);
            $price = $totalQty > 0 ? $totalPrice/$totalQty : 0;

            $arrInitial = [
                'document'=>"N/A",
                "measure"=>"N/A",
                'type_move' => 0,
                "price"=>0,
                "last_price"=>0,
                'output' => 0,
                'output_total' => 0,
                'input' => 0,
                'input_total' => 0,
                'move' => "Saldo anterior",
                "balance"=>$totalQty,
                "balance_total"=>$price
            ];
            return $arrInitial;
        }
        
    }
?>