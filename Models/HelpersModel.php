<?php 
    class HelpersModel extends Mysql{
        private $intId;
        private $strName;
        public function __construct(){
            parent::__construct();
        }
        public function selectPurchaseDet(int $intId,$strName =""){
            $this->intId = $intId;
            $this->strName = $strName;
            $condition = $strName != "" ? " AND det.product_id =  $this->intId AND det.variant_name = '$this->strName'" : " AND det.product_id =  $this->intId";
            $sql = "SELECT 
            det.qty,
            cab.date,
            COALESCE(det.price_purchase,0) as price
            FROM purchase_det det 
            INNER JOIN purchase cab ON cab.idpurchase = det.purchase_id
            INNER JOIN product p ON p.idproduct = det.product_id
            WHERE cab.status = 1 $condition";
            $request = $this->select_all($sql);
            return $request;
        }
        public function selectAdjustmentDet(int $intId,$strName =""){
            $this->intId = $intId;
            $this->strName = $strName;
            $condition = $strName != "" ? " AND det.product_id =  $this->intId AND det.variant_name = '$this->strName'" : " AND det.product_id =  $this->intId";
            $sql = "SELECT 
            det.adjustment as qty,
            det.type,
            cab.date,
            COALESCE(det.price,0) as price
            FROM adjustment_det det 
            INNER JOIN adjustment_cab cab ON cab.id = det.adjustment_id
            INNER JOIN product p ON p.idproduct = det.product_id
            WHERE cab.status = 1 $condition";
            $request = $this->select_all($sql);
            return $request;
        }
        public function selectOrderDet(int $intId, $strName =""){
            $this->intId = $intId;
            $this->strName = $strName;
            $arrProducts = [];
            $sql = "SELECT 
            det.quantity as qty,
            cab.date,
            COALESCE(p.price,0) AS price,
            det.description
            FROM orderdetail det 
            INNER JOIN orderdata cab ON cab.idorder = det.orderid
            INNER JOIN product p ON p.idproduct = det.productid
            LEFT JOIN measures m ON m.id_measure = p.measure
            WHERE cab.status != 'canceled' AND det.topic = 2 
            AND det.productid =  $this->intId";
            $request = $this->select_all($sql);
            if(!empty($request)){
                $total = count($request);
                for ($i=0; $i < $total ; $i++) { 
                    $e = $request[$i];
                    $description = json_decode($e['description'],true);
                    if(is_array($description)){
                        $arrDet = $description['detail'];
                        $variantName = implode("-",array_values(array_column($arrDet,"option")));
                        if($variantName == $this->strName){
                            array_push($arrProducts,$e);
                        }
                    }else{
                        array_push($arrProducts,$e);
                    }
                }
            }
            return $arrProducts;
        }
    }
?>