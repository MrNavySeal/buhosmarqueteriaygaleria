<?php 
    class InventarioModel extends Mysql{

        public function __construct(){
            parent::__construct();
        }
        public function selectProducts(){
            $arrProducts = [];
            $sql = "SELECT 
            p.idproduct,
            p.reference,
            p.name,
            p.stock,
            p.product_type,
            p.price_purchase,
            c.name as category,
            s.name as subcategory 
            FROM product p
            INNER JOIN category c ON c.idcategory = p.categoryid
            INNER JOIN subcategory s ON s.idsubcategory = p.subcategoryid
            WHERE p.is_stock = 1 AND p.status = 1 AND c.status = 1 AND s.status = 1";
            $request = $this->select_all($sql);
            if(!empty($request)){
                foreach ($request as $pro) {
                    if($pro['product_type']){
                        $sql = "SELECT name, price_purchase,stock,sku 
                        FROM product_variations_options 
                        WHERE product_id = '$pro[idproduct]'";
                        $requestVariants = $this->select_all($sql);
                        if(!empty($requestVariants)){
                            foreach ($requestVariants as $var) {
                                array_push($arrProducts,array(
                                    "idproduct"=>$pro['idproduct'],
                                    "reference"=>$var['sku'],
                                    "name"=>$pro['name']." ".$var['name'],
                                    "price_purchase"=>$var['price_purchase'],
                                    "price_purchase_format"=>formatNum($var['price_purchase']),
                                    "category"=>$pro['category'],
                                    "subcategory"=>$pro['subcategory'],
                                    "stock"=>$var['stock'],
                                    "total"=>$var['stock']*$var['price_purchase'],
                                    "total_format"=>formatNum($var['stock']*$var['price_purchase'])
                                ));
                            }
                        }
                    }else{
                        array_push($arrProducts,array(
                            "idproduct"=>$pro['idproduct'],
                            "reference"=>$pro['reference'],
                            "name"=>$pro['name'],
                            "price_purchase"=>$pro['price_purchase'],
                            "price_purchase_format"=>formatNum($pro['price_purchase']),
                            "category"=>$pro['category'],
                            "subcategory"=>$pro['subcategory'],
                            "stock"=>$pro['stock'],
                            "total"=>$pro['stock']*$pro['price_purchase'],
                            "total_format"=>formatNum($pro['stock']*$pro['price_purchase'])
                        ));
                    }
                }
            }
            return $arrProducts;
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
                    if($e['variant_name'] != ""){
                        $sql = "SELECT sku as reference FROM product_variations_options WHERE product_id ='$e[id]' AND name = '$e[variant_name]'";
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
            det.description
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
                    $description = json_decode($e['description'],true);
                    if(is_array($description)){
                        $arrDet = $description['detail'];
                        $variantName = implode("-",array_values(array_column($arrDet,"option")));
                        $id = $request[$i]['id'];
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
    }
?>