<?php
    class HelperWarehouse{

        public const ENTRADA_COMPRA = ["id"=>1,"name"=>"Entrada por compra","status"=>true];
        public const ENTRADA_AJUSTE = ["id"=>3,"name"=>"Entrada por ajuste","status"=>true];
        public const SALIDA_AJUSTE = ["id"=>4,"name"=>"Salida por ajuste","status"=>false];
        public const SALIDA_VENTA = ["id"=>5,"name"=>"Salida por venta","status"=>false];

        public static function setMovement(array $data){
            $arrMovement = $data['movement'];
            $total = $data['total'];
            $detail = $data['detail'];
            $document = $data['document'];
            $movement = $arrMovement['id'];
            $description = $arrMovement['name'];
            $flag = $arrMovement['status'];
            $date = isset($data['date']) ? date_format(date_create($data['date']),"Y-m-d") : date_format(date_create("now"),"Y-m-d");

            $con = new Mysql();
            $sql = "INSERT INTO warehouse_movements(movement,document,description,total,date_create) VALUES(?,?,?,?,?)";
            $request = $con->insert($sql,[$movement,$document,$description,$total,$date]);
            foreach ($detail as $det) {
                if($arrMovement['id'] != 5 || $arrMovement['id'] == 5 && $det['topic'] == 2){
                    $price = 0;
                    $total = 0;
                    
                    $variantName ="";
                    $productId ="";
    
                    if(isset($det["price_purchase"])){
                        $price = $det['price_purchase'];
                        $qty = $det['qty'];
                        $total = $qty*$price;
                        $productId = isset($det['id']) ? $det['id'] : $det['product_id'];
                        $variantName = $det['variant_name'];
                    }else if(isset($det["price"])){
                        $price = $det['price'];
                        $qty = $det['qty'];
                        $total = $qty*$price;
                        $productId = isset($det['id']) ? $det['id'] : $det['product_id'];
                        $variantName = $det['variant_name'];
                    }
    
                    if(isset($det['description'])){
                        $description = json_decode($data['description'],true);
                        if(is_array($description)){
                            $arrDet = $description['detail'];
                            $variantName = implode("-",array_values(array_column($arrDet,"option")));
                        }
                        $productId = $det['productid'];
                        $price = HelperWarehouse::getLastPrice($productId,$variantName);
                        $qty = $det['quantity'];
                        $total = $qty*$price;
                    }
    
    
                    $variantName = $variantName != "" ? $variantName : "";
                    $inQty = $flag ? $qty : 0;
                    $outQty = !$flag ? $qty : 0;

                    $sql = "INSERT INTO warehouse_movements_det(warehouse_movement_id,product_id,variant_name,in_qty,out_qty,price,total) VALUES(?,?,?,?,?,?,?)";
                    $con->insert($sql,[
                        $request,
                        $productId,
                        $variantName,
                        $inQty,
                        $outQty,
                        $price,
                        $total
                    ]);
                }
                
            }
        }

        public static function delMovement($data,$document){
            $movement = $data['id'];
            $con = new Mysql();
            $sql = "DELETE FROM warehouse_movements WHERE movement = $movement AND document = $document";
            $con->delete($sql);
        }

        public static function getProductMovement($idProduct,$variantName="",$initialDate=""){
            $con = new Mysql();
            $condition = $variantName != "" ? " WHERE det.product_id =  $idProduct AND det.variant_name = '$variantName'" : " WHERE det.product_id =  $idProduct";
            $initialDate = $initialDate != "" ? " AND cab.date_create < '$initialDate'" : "";

            $data = [];
            $sql = "SELECT 
            det.product_id as id,
            det.variant_name,
            p.reference,
            c.name as category,
            s.name as subcategory,
            m.initials as measure,
            CONCAT(p.name,' ',det.variant_name) as name
            FROM warehouse_movements cab
            INNER JOIN warehouse_movements_det det ON cab.id = det.warehouse_movement_id
            INNER JOIN product p ON p.idproduct = det.product_id
            INNER JOIN category c ON c.idcategory = p.categoryid
            INNER JOIN subcategory s ON s.idsubcategory = p.subcategoryid
            LEFT JOIN measures m ON m.id_measure = p.measure
            $condition
            GROUP BY det.product_id,det.variant_name
            ORDER BY cab.date_create";
            $product = $con->select($sql);

            if(!empty($product)){
                $id = $product['id'];
                $variantName = $product['variant_name'];
                if($product['variant_name'] != ""){
                    $sql = "SELECT sku as reference FROM product_variations_options WHERE product_id ='$id' AND name = '$variantName'";
                    $reference = $con->select($sql)['reference'];
                    $product['reference'] = $reference;
                }

                $kardex = [];
                $initialMov = [
                    "date_format"=>"N/A",
                    "document"=>"N/A",
                    "move"=>"Saldo anterior",
                    "measure"=>"N/A",
                    "price"=>0,
                    "input"=>0,
                    "input_total"=>0,
                    "output"=>0,
                    "output_total"=>0,
                    "last_price"=>0,
                    "balance"=>0,
                    "balance_total"=>0,
                ];
    
                $sqlInitial = "SELECT 
                COALESCE(SUM(det.in_qty),0) AS input,
                COALESCE(SUM(det.in_qty * det.price),0) AS input_total,
                COALESCE(SUM(det.out_qty),0) AS output,
                COALESCE(SUM(det.out_qty * det.price),0) AS output_total
                FROM warehouse_movements cab
                INNER JOIN warehouse_movements_det det ON cab.id = det.warehouse_movement_id
                INNER JOIN product p ON p.idproduct = det.product_id
                LEFT JOIN measures m ON m.id_measure = p.measure
                $condition AND p.is_stock = 1 $initialDate";
    
                $initial = $con->select($sqlInitial);
    
                if(!empty($initial)){
                    $initialMov['input'] = $initial['input'];
                    $initialMov['input_total'] = $initial['input_total'];
                    $initialMov['output'] = $initial['output'];
                    $initialMov['output_total'] = $initial['output_total'];
                }
    
                array_push($kardex,$initialMov);
                
                $sqlMovements = "SELECT 
                cab.document,
                cab.description AS move,
                det.product_id,
                det.price,
                m.initials as measure,
                det.in_qty AS input,
                det.in_qty * det.price AS input_total,
                det.out_qty AS output,
                det.out_qty * det.price AS output_total,
                DATE_FORMAT(cab.date_create,'%d/%m/%Y') as date_format
                FROM warehouse_movements cab
                INNER JOIN warehouse_movements_det det ON cab.id = det.warehouse_movement_id
                INNER JOIN product p ON p.idproduct = det.product_id
                LEFT JOIN measures m ON m.id_measure = p.measure
                $condition AND p.is_stock = 1
                ORDER BY cab.date_create";
    
                $movements = $con->select_all($sqlMovements);
                foreach ($movements as $mov) {
                    $mov['last_price'] = $mov['price'];
                    $lastRow = $kardex[count($kardex) - 1];
                    $lastBalance = $lastRow['balance'];
                    $totalBalance = $lastBalance+$mov['input']-$mov['output'];
                    $totalCostBalance = $lastRow['balance_total'];
                    $mov['balance'] = $totalBalance;
    
                    if($mov['input'] > 0){
                        $totalCostBalance+=$mov['input'] * $mov['last_price'];
                        $lastPrice = $totalBalance > 0 ? $totalCostBalance/$totalBalance : 0;
                        $mov['last_price'] = $lastPrice;
                        $mov['balance_total'] = $mov['balance']*$lastPrice;
                    }else{
                        $mov['last_price'] =  $lastRow['last_price'];
                        $mov['output_total'] = $mov['output'] * $lastRow['last_price'];
                        $mov['balance_total'] = $mov['balance']*$lastRow['last_price'];
                    }
                    $mov['input_total'] = $mov['input'] * $mov['price'];
                    array_push($kardex,$mov);
                }

                $lastMov = $kardex[count($kardex)-1];
                $finalPrice = $lastMov['last_price'];
                $finalStock = $lastMov['output'];
                $data = array(
                    "id"=>$product['id'],
                    "reference"=>$product['reference'],
                    "name"=>$product['name'],
                    "price_purchase"=>$finalPrice,
                    "price_purchase_format"=>formatNum($finalPrice),
                    "category"=>$product['category'],
                    "subcategory"=>$product['subcategory'],
                    "stock"=>$finalStock,
                    "total"=>$finalStock*$finalPrice,
                    "total_format"=>formatNum($finalStock*$finalPrice),
                    "measure"=>$product['measure'],
                    "detail"=>$kardex
                );
            }
            return $data;
        }

        public static function getLastPrice($idProduct,$variantName=""){
            $price = 0;
            $data = HelperWarehouse::getProductMovement($idProduct,$variantName);
            if(!empty($data)){$price = $data['price_purchase'];}
            return $price;
        }
    }
    
?>