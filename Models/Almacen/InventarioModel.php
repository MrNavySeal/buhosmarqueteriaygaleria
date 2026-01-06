<?php 
    class InventarioModel extends Mysql{

        public function __construct(){
            parent::__construct();
        }

        public function selectBalance(string $strSearch,int $intPerPage,int $intPageNow){
            $start = ($intPageNow-1)*$intPerPage;
            $limit ="";

            if($intPageNow != 0){
                $limit = " LIMIT $start,$intPerPage";
            }

            $sql = "SELECT 
            det.product_id as id,
            det.variant_name,
            det.price as price_purchase,
            p.reference,
            c.name as category,
            s.name as subcategory,
            CONCAT(p.name,' ',det.variant_name) as name
            FROM warehouse_movements_det det 
            INNER JOIN product p ON p.idproduct = det.product_id
            INNER JOIN category c ON c.idcategory = p.categoryid
            INNER JOIN subcategory s ON s.idsubcategory = p.subcategoryid
            WHERE (p.name like '$strSearch%' OR det.variant_name like '$strSearch%' OR c.name like '$strSearch%' OR s.name like '$strSearch%') 
            AND p.is_stock = 1 AND p.status = 1 AND c.status = 1 AND s.status = 1
            GROUP BY det.product_id,det.variant_name $limit";

            $sqlTotal = "SELECT 
            det.product_id as id,
            det.variant_name
            FROM warehouse_movements_det det 
            INNER JOIN product p ON p.idproduct = det.product_id
            INNER JOIN category c ON c.idcategory = p.categoryid
            INNER JOIN subcategory s ON s.idsubcategory = p.subcategoryid
            WHERE (p.name like '$strSearch%' OR det.variant_name like '$strSearch%' OR c.name like '$strSearch%' OR s.name like '$strSearch%') 
            AND p.is_stock = 1 AND p.status = 1 AND c.status = 1 AND s.status = 1
            GROUP BY det.product_id,det.variant_name";

            $request = $this->select_all($sql);
            $fullData = $this->select_all($sqlTotal);
            $totalRecords = count($fullData);
            $arrProducts = [];
            $arrFullProducts = [];
            $total = 0;

            foreach ($request as $pro) {
                $data = HelperWarehouse::getProductMovement($pro['id'],$pro['variant_name']);
                if(!empty($data)){ array_push($arrProducts,$data); }
            }

            foreach ($fullData as $pro) {
                $data = HelperWarehouse::getProductMovement($pro['id'],$pro['variant_name']);
                if(!empty($data)){ $total+=$data['total'];array_push($arrFullProducts,$data); }
            }

            $arrData = getCalcPages($totalRecords,$intPageNow,$intPerPage);
            $arrData['data'] = $arrProducts;
            $arrData['full_data'] = $arrFullProducts;
            $arrData['total_balance'] = $total;
            return $arrData;
        }

        public function selectMovements(string $strInitialDate,string $strFinalDate,string $strSearch){
            $sql = "SELECT 
            det.product_id as id,
            det.variant_name,
            p.reference,
            CONCAT(p.name,' ',det.variant_name) as name
            FROM warehouse_movements cab
            INNER JOIN warehouse_movements_det det ON cab.id = det.warehouse_movement_id
            INNER JOIN product p ON p.idproduct = det.product_id
            INNER JOIN category c ON c.idcategory = p.categoryid
            INNER JOIN subcategory s ON s.idsubcategory = p.subcategoryid
            WHERE cab.date_create BETWEEN '$strInitialDate' AND '$strFinalDate' 
            AND (p.name like '$strSearch%' OR det.variant_name like '$strSearch%')
            AND p.is_stock = 1 AND p.status = 1 AND c.status = 1 AND s.status = 1
            GROUP BY det.product_id,det.variant_name";

            $request = $this->select_all($sql);
            $arrProducts = [];
            foreach ($request as $pro) {
                $data = HelperWarehouse::getProductMovement($pro['id'],$pro['variant_name'],[
                    "initial_date"=>$strInitialDate,
                    "final_date"=>$strFinalDate
                ]);
                if(!empty($data)){ array_push($arrProducts,$data); }    
            }
            return $arrProducts;
        }
        
    }
?>