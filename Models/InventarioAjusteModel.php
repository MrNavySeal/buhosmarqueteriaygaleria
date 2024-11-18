<?php 
    class InventarioAjusteModel extends Mysql{
        private $intId;
        private $strConcept;
        private $floatTotal;
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
            AND (c.name like '$strSearch%' OR s.name like '$strSearch%' OR p.name like '$strSearch%'  OR c.name like '$strSearch'
            OR v.name like '$strSearch%' OR v.sku like '$strSearch%' OR p.reference like '$strSearch%' OR s.name like '$strSearch')";
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
            AND (c.name like '$strSearch%' OR s.name like '$strSearch%' OR p.name like '$strSearch%'  OR c.name like '$strSearch'
            OR v.name like '$strSearch%' OR v.sku like '$strSearch%' OR p.reference like '$strSearch%' OR s.name like '$strSearch') LIMIT $start,$intPerPage";
            $request = $this->select_all($sql);

            $sqlTotal = "SELECT COALESCE(COUNT(*),0) as total
            FROM product p
            INNER JOIN category c ON c.idcategory = p.categoryid
            INNER JOIN subcategory s ON s.idsubcategory = p.subcategoryid
            LEFT JOIN product_variations_options v ON v.product_id = p.idproduct
            WHERE p.is_stock = 1 AND p.status = 1 AND c.status = 1 AND s.status = 1 
            AND (c.name like '$strSearch%' OR s.name like '$strSearch%' OR p.name like '$strSearch%'  OR c.name like '$strSearch'
            OR v.name like '$strSearch%' OR v.sku like '$strSearch%' OR p.reference like '$strSearch%' OR s.name like '$strSearch')";


            $totalRecords = $this->select($sqlTotal)['total'];
            $totalPages = $totalRecords > 0 ? ceil($totalRecords/$intPerPage) : 0;
            if(!empty($request)){
                foreach ($request as $pro) {
                    $idProduct = $pro['idproduct'];
                    $sqlImg = "SELECT * FROM productimage WHERE productid = $idProduct";
                    $requestImg = $this->select_all($sqlImg);
                    $url = media()."/images/uploads/image.png";
                    if(count($requestImg)>0){
                        $url = media()."/images/uploads/".$requestImg[0]['name'];
                    }
                    array_push($arrProducts,array(
                        "url"=>$url,
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
                        "product_type"=>$pro['product_type']
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
        public function insertCab(string $strConcept,float $floatTotal){
            $this->strConcept = $strConcept;
            $this->floatTotal = $floatTotal;
            $sql = "INSERT INTO adjustment_cab(concept,total,user) VALUES (?,?,?)";
            $request = $this->insert($sql,[$this->strConcept,$this->floatTotal,$_SESSION['userData']['idperson']]);
            return $request;
        }
        public function insertDet(int $intId,array $arrData){
            $this->intId = $intId;
            foreach ($arrData as $data) {
                $sql = "INSERT INTO adjustment_det(adjustment_id,product_id,current,adjustment,price,type,result,variant_name,subtotal) VALUES(?,?,?,?,?,?,?,?,?)";
                $arrValues = [
                    $this->intId,
                    $data['id'],
                    $data['stock'],
                    $data['qty'],
                    $data['price_purchase'],
                    $data['type'],
                    $data['qty_result'],
                    $data['variant_name'],
                    $data['subtotal']
                ];
                $request = $this->insert($sql,$arrValues);
                //Update products
                $sqlProduct ="UPDATE product SET stock=?, price_purchase=? 
                WHERE idproduct = $data[id]";
                if($data['product_type']){
                    $sqlProduct = "UPDATE product_variations_options SET stock=?, price_purchase=?
                    WHERE product_id = $data[id] AND name = '$data[variant_name]'";
                } 
                $price_purchase = getLastPrice($this->intId,$data['variant_name']);
                if($price_purchase == 0){
                    $price_purchase = $data['price_purchase'];
                }
                $this->update($sqlProduct,[$data['qty_result'],$price_purchase]);
            }
            return $request;
        }
        public function selectAdjustment(string $strInitialDate,string $strFinalDate,string $strSearch,int $intPerPage,int $intPageNow){
            $totalPages = 0;
            $totalRecords = 0;
            $requestTotal = [];
            $start = ($intPageNow-1)*$intPerPage;
            $sql = "SELECT 
            cab.id,
            cab.concept, 
            cab.total, 
            DATE_FORMAT(cab.date,'%d/%m/%Y') as date_created, 
            DATE_FORMAT(cab.date_updated,'%d/%m/%Y') as date_updated,
            cab.status,
            CONCAT(u.firstname,' ',u.lastname) as user
            FROM adjustment_cab cab
            LEFT JOIN person u ON cab.user = u.idperson
            WHERE cab.date BETWEEN '$strInitialDate' AND '$strFinalDate' AND 
            (cab.concept LIKE '$strSearch%' OR cab.id LIKE '$strSearch%' OR u.firstname LIKE '$strSearch%' OR u.lastname LIKE '$strSearch%')
            ORDER BY cab.id DESC LIMIT $start,$intPerPage ";
            $request = $this->select_all($sql);
            $total = count($request);
            if($total > 0){
                $sqlTotal = "SELECT 
                cab.id,
                cab.concept, 
                cab.total, 
                DATE_FORMAT(cab.date,'%d/%m/%Y') as date_created, 
                DATE_FORMAT(cab.date_updated,'%d/%m/%Y') as date_updated,
                cab.status,
                CONCAT(u.firstname,' ',u.lastname) as user
                FROM adjustment_cab cab
                LEFT JOIN person u ON cab.user = u.idperson
                WHERE cab.date BETWEEN '$strInitialDate' AND '$strFinalDate' 
                AND (cab.concept LIKE '$strSearch%' OR cab.id LIKE '$strSearch%' OR u.firstname LIKE '$strSearch%' OR u.lastname LIKE '$strSearch%') ORDER BY cab.id DESC";
                $requestTotal = $this->select_all($sqlTotal);
                $total = count($request);
                if(!empty($request)){
                    for ($i=0; $i < $total ; $i++) { 
                        $id = $request[$i]['id'];
                        $sqlDet = "SELECT DISTINCT
                        det.product_id,
                        det.current,
                        det.price,
                        det.type,
                        det.result,
                        det.variant_name,
                        det.subtotal,
                        det.adjustment,
                        p.name,
                        p.reference,
                        pv.sku as variant_reference
                        FROM adjustment_det det
                        INNER JOIN product p ON p.idproduct = det.product_id
                        LEFT JOIN product_variations_options pv ON pv.product_id = det.product_id
                        WHERE det.adjustment_id = '$id'";
                        $requestDet = $this->select_all($sqlDet);
                        $request[$i]['det'] = $requestDet;
                    }
                }
                $totalRecords = $total;
                $totalPages = $totalRecords > 0 ? ceil($totalRecords/$intPerPage) : 0;
            }
            return array("products"=>$request,"pages"=>$totalPages,"data"=>$requestTotal,"total_records"=>$totalRecords);
        }
        public function selectAdjustmentDet(string $strInitialDate,string $strFinalDate,string $strSearch,int $intPerPage,int $intPageNow){
            $totalPages = 0;
            $totalRecords = 0;
            $requestTotal  = [];
            $start = ($intPageNow-1)*$intPerPage;
            $sql = "SELECT DISTINCT
            cab.id,
            det.id as id_det,
            DATE_FORMAT(cab.date,'%d/%m/%Y') as date_created, 
            CONCAT(u.firstname,' ',u.lastname) as user,
            det.product_id,
            det.current,
            det.price,
            det.type,
            det.result,
            det.variant_name,
            det.subtotal,
            det.adjustment,
            p.name,
            p.reference,
            pv.sku as variant_reference
            FROM adjustment_det det
            INNER JOIN adjustment_cab cab ON cab.id = det.adjustment_id
            INNER JOIN product p ON p.idproduct = det.product_id
            LEFT JOIN person u ON cab.user = u.idperson
            LEFT JOIN product_variations_options pv ON pv.product_id = det.product_id
            WHERE cab.date BETWEEN '$strInitialDate' AND '$strFinalDate' AND 
            (cab.id LIKE '$strSearch%' OR u.firstname LIKE '$strSearch%' OR u.lastname LIKE '$strSearch%'
            OR p.name LIKE '$strSearch%' OR p.reference LIKE '$strSearch%' OR pv.sku LIKE '$strSearch%' OR det.variant_name LIKE '$strSearch%')
            ORDER BY cab.id DESC LIMIT $start,$intPerPage";
            $request = $this->select_all($sql);
            $total = count($request);
            if($total > 0){
                $sql = "SELECT DISTINCT
                cab.id,
                det.id as id_det,
                DATE_FORMAT(cab.date,'%d/%m/%Y') as date_created, 
                CONCAT(u.firstname,' ',u.lastname) as user,
                det.product_id,
                det.current,
                det.price,
                det.type,
                det.result,
                det.variant_name,
                det.subtotal,
                det.adjustment,
                p.name,
                p.reference,
                pv.sku as variant_reference
                FROM adjustment_det det
                INNER JOIN adjustment_cab cab ON cab.id = det.adjustment_id
                INNER JOIN product p ON p.idproduct = det.product_id
                LEFT JOIN person u ON cab.user = u.idperson
                LEFT JOIN product_variations_options pv ON pv.product_id = det.product_id
                WHERE cab.date BETWEEN '$strInitialDate' AND '$strFinalDate' AND 
                (cab.id LIKE '$strSearch%' OR u.firstname LIKE '$strSearch%' OR u.lastname LIKE '$strSearch%'
                OR p.name LIKE '$strSearch%' OR p.reference LIKE '$strSearch%' OR pv.sku LIKE '$strSearch%' OR det.variant_name LIKE '$strSearch%')
                ORDER BY cab.id DESC";
                $requestTotal = $this->select_all($sql);
                $totalRecords = count($requestTotal);
                $totalPages = $totalRecords > 0 ? ceil($totalRecords/$intPerPage) : 0;
            }
            return array("products"=>$request,"pages"=>$totalPages,"data"=>$requestTotal,"total_records"=>$totalRecords);
        }
    }
?>