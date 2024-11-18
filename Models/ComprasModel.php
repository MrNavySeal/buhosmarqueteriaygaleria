<?php 
    class ComprasModel extends Mysql{
        private $intId;
        private $strNit;
        private $strName;
        private $strPhone;
        private $strEmail;
        private $strAddress;
        private $intTotal;
        private $arrData;
        private $arrProducts;
        public function __construct(){
            parent::__construct();
        }
        /*******************Purchases**************************** */
        public function insertPurchase(array $data){
            $this->arrData = $data;
            $this->arrProducts = $data['products'];
            $status = $this->arrData['type'] != "credito" ? 1 : 3;
            //Insert header
            $sql = "INSERT INTO purchase(supplierid,cod_bill,date,note,type,total,subtotal,iva,discount,user,status) VALUE(?,?,?,?,?,?,?,?,?,?,?)";
            
            $arrData = array(
                $this->arrData['id'],
                strtoupper($this->arrData['code_bill']),
                $this->arrData['date'],
                $this->arrData['note'],
                $this->arrData['type'],
                $this->arrData['total']['total'],
                $this->arrData['total']['subtotal'],
                $this->arrData['total']['iva'],
                $this->arrData['total']['discount'],
                $_SESSION['userData']['idperson'],
                $status
            );
            $request = $this->insert($sql,$arrData);
            //Insert detail
            if($request > 0){
                $this->insertPurchaseDet($request,$this->arrProducts);
                //insert egress
                if($data['type']!="credito"){
                    $this->insertEgress($request,2,2,"Compra de material",$data['total']['total'],$data['date'],1,$data['type']);
                }
            }
            return $request;
        }
        public function insertPurchaseDet(int $id,array $data){
            $this->intId = $id;
            $this->arrData = $data;
            $total = count($this->arrData);
            for ($i=0; $i < $total ; $i++) { 
                $sql = "INSERT INTO purchase_det(purchase_id,product_id,qty,price_base,price_purchase,
                price_discount,subtotal,variant_name) VALUE(?,?,?,?,?,?,?,?)";
                $arrData = array(
                    $this->intId,
                    $this->arrData[$i]['id'],
                    $this->arrData[$i]['qty'],
                    $this->arrData[$i]['price_base'],
                    $this->arrData[$i]['price_purchase'],
                    $this->arrData[$i]['discount'],
                    $this->arrData[$i]['subtotal'],
                    $this->arrData[$i]['variant_name']
                );
                $this->insert($sql,$arrData);
                //Update products
                $sqlProduct ="UPDATE product SET stock=?, price=?, price_purchase=? 
                WHERE idproduct = {$this->arrData[$i]['id']}";
                if($this->arrData[$i]['product_type']){
                    $sqlProduct = "UPDATE product_variations_options SET stock=?,price_sell=?, price_purchase=?
                    WHERE product_id = {$this->arrData[$i]['id']} AND name = '{$this->arrData[$i]['variant_name']}'";
                } 
                $price_purchase = getLastPrice($this->arrData[$i]['id'],$this->arrData[$i]['variant_name']);
                if($price_purchase == 0){
                    $price_purchase = $this->arrData[$i]['price_purchase'];
                }
                $arrData = array(
                    $this->arrData[$i]['is_stock'] ? $this->arrData[$i]['qty']+$this->arrData[$i]['stock'] : 0,
                    $this->arrData[$i]['price_sell'],
                    $price_purchase
                );
                $this->update($sqlProduct,$arrData);
            }
        }
        public function deletePurchase($id){
            $this->intId = $id;
            $sql = "SELECT * FROM purchase_det WHERE purchase_id = $this->intId";
            $request = $this->select_all($sql);
            $sql = "UPDATE purchase SET status = ? WHERE idpurchase = $this->intId;DELETE FROM count_amount WHERE purchase_id = $this->intId";
            $arrData = array(2);
            $return = $this->update($sql,$arrData);
            if(!empty($request)){
                foreach ($request as $e) {
                    if($e['variant_name'] != ""){
                        $sqlProduct = "SELECT pv.stock,p.is_stock
                        FROM product_variations_options pv
                        INNER JOIN product p ON p.idproduct = pv.product_id
                        WHERE pv.name='$e[variant_name]' AND pv.product_id = $e[product_id]";
                        $requestProduct = $this->select($sqlProduct);
                        if($requestProduct['is_stock']){
                            $stock = $requestProduct['stock']-$e['qty'];
                            $this->update("UPDATE product_variations_options SET stock =? WHERE name='$e[variant_name]' AND product_id = $e[product_id]",[$stock]);
                        }
                    }else{
                        $sqlProduct = "SELECT stock,is_stock FROM product WHERE idproduct = $e[product_id]";
                        $requestProduct = $this->select($sqlProduct);
                        if($requestProduct['is_stock']){
                            $stock = $requestProduct['stock']-$e['qty'];
                            $this->update("UPDATE product SET stock =? WHERE idproduct = $e[product_id]",[$stock]);
                        }
                    }
                }
            }
            
            return $return;
        }
        public function selectPurchases(){
            $sql = "SELECT 
                    p.idpurchase,
                    p.total,
                    p.subtotal,
                    p.iva,
                    p.discount,
                    DATE_FORMAT(p.date, '%d/%m/%Y') as date,
                    s.name as supplier,
                    CONCAT(u.firstname,' ',u.lastname) as user,
                    p.cod_bill,
                    p.type,
                    p.status
                    FROM purchase p
                    INNER JOIN supplier s
                    ON p.supplierid = s.id_supplier
                    INNER JOIN person u
                    ON p.user = u.idperson
                    ORDER BY p.idpurchase DESC
            ";
            $request = $this->select_all($sql);
            if(!empty($request)){
                $rows = count($request);
                for ($i=0; $i < $rows ; $i++) { 
                    $id = $request[$i]['idpurchase'];
                    $type = $request[$i]['type'];
                    $total = $request[$i]['total'];
                    $sql_det = "SELECT 
                    p.name, 
                    det.qty,
                    det.price_purchase,
                    subtotal,
                    det.price_base,
                    variant_name 
                    FROM purchase_det det
                    INNER JOIN product p
                    ON p.idproduct = det.product_id
                    WHERE det.purchase_id = $id";
                    $request[$i]['detail'] = $this->select_all($sql_det);
                    $request[$i]['total_pendent'] = 0;
                    if($type == "credito"){
                        $sql_credit = "SELECT COALESCE(SUM(advance),0) as total_advance FROM purchase_advance WHERE purchase_id = $id";
                        $advance = $this->select($sql_credit)['total_advance'];
                        $total = $total - $advance;
                        $request[$i]['total_pendent'] = $total;
                        $sql_advance = "SELECT det.purchase_id, det.type, det.advance,DATE_FORMAT(det.date,'%Y-%m-%d') as date,det.user,
                        CONCAT(u.firstname,' ',u.lastname) as user_name
                        FROM purchase_advance det 
                        INNER JOIN person u
                        ON det.user = u.idperson
                        WHERE det.purchase_id = $id";
                        $request[$i]['detail_advance']= $this->select_all($sql_advance);
                        $request[$i]['total_advance'] = intval($advance);
                    }
                }
            }
            return $request;
        }
        public function selectCreditPurchases(){
            $sql = "SELECT 
                    p.idpurchase,
                    p.total,
                    p.subtotal,
                    p.iva,
                    p.discount,
                    DATE_FORMAT(p.date, '%d/%m/%Y') as date,
                    s.name as supplier,
                    CONCAT(u.firstname,' ',u.lastname) as user,
                    p.cod_bill,
                    p.type,
                    p.status
                    FROM purchase p
                    INNER JOIN supplier s
                    ON p.supplierid = s.id_supplier
                    INNER JOIN person u
                    ON p.user = u.idperson
                    WHERE p.type = 'credito'
                    ORDER BY p.idpurchase DESC
            ";
            $request = $this->select_all($sql);
            if(!empty($request)){
                $rows = count($request);
                for ($i=0; $i < $rows ; $i++) { 
                    $id = $request[$i]['idpurchase'];
                    $type = $request[$i]['type'];
                    $total = $request[$i]['total'];
                    $sql_det = "SELECT 
                    p.name, 
                    det.qty,
                    det.price_purchase,
                    subtotal,
                    det.price_base,
                    variant_name 
                    FROM purchase_det det
                    INNER JOIN product p
                    ON p.idproduct = det.product_id
                    WHERE det.purchase_id = $id";
                    $request[$i]['detail'] = $this->select_all($sql_det);
                    $request[$i]['total_pendent'] = 0;
                    if($type == "credito"){
                        $sql_credit = "SELECT COALESCE(SUM(advance),0) as total_advance FROM purchase_advance WHERE purchase_id = $id";
                        $advance = $this->select($sql_credit)['total_advance'];
                        $total = $total - $advance;
                        $request[$i]['total_pendent'] = $total;
                        $sql_advance = "SELECT det.purchase_id, det.type, det.advance,DATE_FORMAT(det.date,'%Y-%m-%d') as date,det.user,
                        CONCAT(u.firstname,' ',u.lastname) as user_name
                        FROM purchase_advance det 
                        INNER JOIN person u
                        ON det.user = u.idperson
                        WHERE det.purchase_id = $id";
                        $request[$i]['detail_advance']= $this->select_all($sql_advance);
                        $request[$i]['total_advance'] = intval($advance);
                    }
                }
            }
            return $request;
        }
        public function selectDetailPurchases(){
            $sql = "SELECT 
                    CONCAT(p.name,' ',det.variant_name) as name, 
                    det.purchase_id,
                    det.qty,
                    det.price_purchase,
                    det.subtotal,
                    det.price_discount,
                    det.variant_name,
                    s.name as supplier,
                    cab.cod_bill,
                    s.nit as document,
                    m.initials as measure,
                    DATE_FORMAT(cab.date,'%d/%m/%Y') as date
                    FROM purchase_det det
                    INNER JOIN product p
                    ON p.idproduct = det.product_id
                    INNER JOIN purchase cab
                    ON det.purchase_id = cab.idpurchase
                    INNER JOIN supplier s
                    ON cab.supplierid = s.id_supplier
                    INNER JOIN measures m
                    ON p.measure = m.id_measure
                    ORDER BY det.purchase_id DESC
            ";
            $request = $this->select_all($sql);
            return $request;
        }
        public function selectPurchase($id){
            $this->intId = $id;
            $sql = "SELECT 
                    p.idpurchase,
                    p.supplierid,
                    p.products,
                    p.total,
                    DATE_FORMAT(p.date, '%d/%m/%Y') as date,
                    s.id_supplier,
                    s.name,
                    s.phone,
                    s.email,
                    s.nit,
                    s.address
                    FROM purchase p
                    INNER JOIN supplier s
                    WHERE p.supplierid = s.id_supplier AND p.idpurchase = $this->intId
                    ORDER BY p.idpurchase DESC
            ";
            $request = $this->select($sql);
            return $request;
        }
        public function insertEgress(int $idPurchase,int $intType,int $intTopic,string $strName,int $intAmount,string $strDate,int $intStatus, string $method){
            $request="";
            
            $sql  = "INSERT INTO count_amount(purchase_id,type_id,category_id,name,amount,date,status,method) VALUES(?,?,?,?,?,?,?,?)";      
            $arrData = array(
                $idPurchase,
                $intType,
                $intTopic,
                $strName,
                $intAmount,
                $strDate,
                $intStatus,
                $method
            );
            $request = $this->insert($sql,$arrData);
	        return $request;
		}
        /*************************Products methods*******************************/
        public function selectTotalInventory(string $strSearch){
            $sql = "SELECT coalesce(count(*),0) as total
            FROM product p
            INNER JOIN category c ON c.idcategory = p.categoryid
            INNER JOIN subcategory s ON s.idsubcategory = p.subcategoryid
            LEFT JOIN product_variations_options v ON v.product_id = p.idproduct
            WHERE p.status = 1 AND c.status = 1 AND s.status = 1 AND p.is_combo!=1
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
            p.is_stock,
            p.price_purchase,
            p.price as price_sell,
            p.discount as price_offer,
            c.name as category,
            s.name as subcategory,
            v.name as variant_name,
            v.price_purchase as variant_purchase,
            v.price_sell as variant_sell,
            v.price_offer as variant_offer,
            v.stock as variant_stock,
            v.sku as variant_sku,
            m.initials as measure,
            p.import,
            va.variation
            FROM product p
            INNER JOIN category c ON c.idcategory = p.categoryid
            INNER JOIN subcategory s ON s.idsubcategory = p.subcategoryid
            LEFT JOIN product_variations_options v ON v.product_id = p.idproduct
            LEFT JOIN measures m ON m.id_measure = p.measure
            LEFT JOIN product_variations va ON va.id = v.product_variation_id
            WHERE p.status = 1 AND c.status = 1 AND s.status = 1 AND  p.is_combo!=1
            AND (c.name like '$strSearch%' OR s.name like '$strSearch%' OR p.name like '$strSearch%'  OR c.name like '$strSearch'
            OR v.name like '$strSearch%' OR v.sku like '$strSearch%' OR p.reference like '$strSearch%' OR s.name like '$strSearch') LIMIT $start,$intPerPage";
            $request = $this->select_all($sql);

            $sqlTotal = "SELECT COALESCE(COUNT(*),0) as total
            FROM product p
            INNER JOIN category c ON c.idcategory = p.categoryid
            INNER JOIN subcategory s ON s.idsubcategory = p.subcategoryid
            LEFT JOIN product_variations_options v ON v.product_id = p.idproduct
            WHERE p.status = 1 AND c.status = 1 AND s.status = 1 AND p.is_combo!=1
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
                    $variation ="";
                    if($pro['product_type']){
                        $arrVariantName = explode("-",$pro['variant_name']);
                        $arrVariants = json_decode($pro['variation'],true);
                        $arrVariantDetail = [];
                        foreach ($arrVariantName as $name) {
                            foreach ($arrVariants as $variant) {
                                $arrOptions = $variant['options'];
                                foreach ($arrOptions as $option) {
                                    if($option ==$name){
                                        array_push($arrVariantDetail,array(
                                            "name"=>$variant['name'],
                                            "option"=>$name,
                                        ));
                                        break;
                                    }
                                }
                            }
                        }
                        $arrCombination = array(
                            "name"=>$pro['name'],
                            "detail"=>$arrVariantDetail
                        );
                        $variation = json_encode($arrCombination,JSON_UNESCAPED_UNICODE);
                    }
                    array_push($arrProducts,array(
                        "url"=>$url,
                        "id"=>$pro['idproduct'],
                        "reference"=>$pro['variant_sku'] != "" ? $pro['variant_sku'] : $pro['reference'],
                        "product_name"=>$pro['name'],
                        "name"=>$pro['variant_name'] != "" ? $pro['name']." ".$pro['variant_name'] : $pro['name'],
                        "price_purchase"=>$pro['variant_name'] != "" ? $pro['variant_purchase'] : $pro['price_purchase'],
                        "price_purchase_format"=>$pro['variant_name'] != "" ? formatNum($pro['variant_purchase']) : formatNum($pro['price_purchase']),
                        "price_sell"=>$pro['variant_name'] != "" ? $pro['variant_sell'] : $pro['price_sell'],
                        "price_sell_format"=>$pro['variant_name'] != "" ? formatNum($pro['variant_sell']) : formatNum($pro['price_sell']),
                        "category"=>$pro['category'],
                        "subcategory"=>$pro['subcategory'],
                        "stock"=>$pro['variant_name'] != "" ? $pro['variant_stock'] : $pro['stock'],
                        "measure"=>$pro['measure'],
                        "variation"=>$variation,
                        "variant_name"=>$pro['variant_name'],
                        "product_type"=>$pro['product_type'],
                        "is_stock"=>$pro['is_stock'],
                        "import"=>$pro['import']
                    ));
                }
            }
            return array("products"=>$arrProducts,"pages"=>$totalPages);
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
            AND p.status = 1 AND c.status = 1 AND s.status = 1 AND  p.is_combo!=1 AND p.idproduct = $this->intId";
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
        /*************************Suppliers methods*******************************/
        public function selectSuppliers(){
            $sql = "SELECT id_supplier,name,nit,phone,email FROM supplier WHERE status = 1 ORDER BY id_supplier";
            $request = $this->select_all($sql);
            return $request;
        }
        /*************************Advance methods*******************************/
        public function insertAdvance(int $id,array $data,bool $isSuccess){
            $this->intId = $id;
            $request = $this->delete("DELETE FROM purchase_advance WHERE purchase_id = $id");
            $request = $this->delete("DELETE FROM count_amount WHERE purchase_id = $id");
            if(!empty($data)){
                if($isSuccess){
                    $request = $this->update("UPDATE purchase SET status=? WHERE idpurchase = $id",array(1)); 
                }
                foreach ($data as $d) {
                    //Insert advance
                    $sql = "INSERT INTO purchase_advance(purchase_id,type,advance,date,user)
                    VALUES(?,?,?,?,?)";
                    $arrData = array($this->intId,$d['type'],$d['advance'],$d['date'],$d['user']);
                    $request = $this->insert($sql,$arrData);

                    //Insert egress
                    if($isSuccess){
                        $this->insertEgress($this->intId,2,2,"Compra de material",$d['advance'],$d['date'],1,$d['type']);
                    }else{
                        $this->insertEgress($this->intId,2,29,"Abono a factura de compra",$d['advance'],$d['date'],1,$d['type']);
                    }
                }
            }
            return intval($request);
        }
    }
?>