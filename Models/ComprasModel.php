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
                $this->arrData['code_bill'],
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
            }
            //insert egress
            if($request>0 && $data['type']!="credito"){
                $this->insertEgress($request,2,2,"Compra de material",$data['total']['total'],$data['date'],1,$data['type']);
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
                    $id,
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
                if($this->arrData[$i]['is_stock']){
                    $sqlPurchase = "SELECT AVG(price_purchase) as price_purchase FROM purchase_det WHERE product_id = {$this->arrData[$i]['id']}";
                    $sql ="UPDATE product SET stock=?, price=?, price_purchase=? 
                    WHERE idproduct = {$this->arrData[$i]['id']}";
                    if($this->arrData[$i]['product_type']){
                        $sql = "UPDATE product_variations_options SET stock=?,price_sell=?, price_purchase=?
                        WHERE product_id = {$this->arrData[$i]['id']} AND name = '{$this->arrData[$i]['variant_name']}'";
                        $sqlPurchase = "SELECT AVG(price_purchase) as price_purchase
                        FROM purchase_det 
                        WHERE product_id = {$this->arrData[$i]['id']} 
                        AND variant_name = '{$this->arrData[$i]['variant_name']}' ";
                    }
                    $price_purchase = $this->select($sqlPurchase)['price_purchase'];
                    $arrData = array(
                        $this->arrData[$i]['qty']+$this->arrData[$i]['stock'],
                        $this->arrData[$i]['price_sell'],
                        $price_purchase
                    );
                    $this->update($sql,$arrData);
                }  
            }
        }
        public function deletePurchase($id){
            $this->intId = $id;
            $sql = "UPDATE purchase SET status = ? WHERE idpurchase = $this->intId;DELETE FROM count_amount WHERE purchase_id = $this->intId";
            $arrData = array(2);
            $return = $this->update($sql,$arrData);
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
                    INNER JOIN suppliers s
                    ON p.supplierid = s.idsupplier
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
        public function selectPurchase($id){
            $this->intId = $id;
            $sql = "SELECT 
                    p.idpurchase,
                    p.supplierid,
                    p.products,
                    p.total,
                    DATE_FORMAT(p.date, '%d/%m/%Y') as date,
                    s.idsupplier,
                    s.name,
                    s.phone,
                    s.email,
                    s.nit,
                    s.address
                    FROM purchase p
                    INNER JOIN suppliers s
                    WHERE p.supplierid = s.idsupplier AND p.idpurchase = $this->intId
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
        public function selectProducts($search=""){
            $perPage = 100;
            $sql = "SELECT 
                p.idproduct,
                p.reference,
                p.name,
                p.price_purchase,
                p.stock,
                p.product_type,
                p.is_stock
            FROM product p
            INNER JOIN category c, subcategory s
            WHERE c.idcategory = p.categoryid AND c.idcategory = s.categoryid AND p.subcategoryid = s.idsubcategory
            AND p.is_combo !=1 AND p.status = 1 
            AND (c.name LIKE '$search%' || s.name LIKE '$search%' || p.name LIKE '$search%' || p.reference LIKE '$search%')
            ORDER BY p.idproduct DESC
            ";
            $request = $this->select_all($sql);
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
            return $request;
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
            AND p.is_combo !=1 AND p.status = 1 
            AND p.idproduct = $this->intId";
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
                    $this->insertEgress($this->intId,2,2,"Abono a factura de compra",$d['advance'],$d['date'],1,$d['type']);
                }
            }
            return intval($request);
        }
    }
?>