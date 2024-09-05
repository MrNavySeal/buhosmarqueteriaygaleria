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
    }
?>