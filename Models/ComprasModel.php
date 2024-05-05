<?php 
    class ComprasModel extends Mysql{
        private $intId;
        private $strNit;
        private $strName;
        private $strPhone;
        private $strEmail;
        private $strAddress;
        private $arrProducts;
        private $intTotal;
        public function __construct(){
            parent::__construct();
        }
        /*******************Purchases**************************** */
        public function insertPurchase(int $id,string $arrProducts,int $total, string $strDate){
            $this->intId = $id;
            $this->arrProducts = $arrProducts;
            $this->intTotal = $total;
            $sql="";
            $arrData ="";
            if($strDate!=""){
                $arrDate = explode("-",$strDate);
                $dateCreated = date_create($arrDate[2]."-".$arrDate[1]."-".$arrDate[0]);
                $dateFormat = date_format($dateCreated,"Y-m-d");
                $sql = "INSERT INTO purchase(supplierid,products,total,date) VALUE(?,?,?,?)";
                $arrData = array($this->intId,$this->arrProducts,$this->intTotal,$dateFormat);
                
            }else{
                $sql = "INSERT INTO purchase(supplierid,products,total) VALUE(?,?,?)";
                $arrData = array($this->intId,$this->arrProducts,$this->intTotal);
            }
            $request = $this->insert($sql,$arrData);
            if($request>0){
                $this->insertEgress($request,2,2,"Compra de material",$this->intTotal,$strDate,1);
            }
            return $request;
        }
        public function deletePurchase($id){
            $this->intId = $id;
            $sql = "DELETE FROM purchase WHERE idpurchase = $this->intId;DELETE FROM count_amount WHERE purchase_id = $this->intId";
            $return = $this->delete($sql);
            return $return;
        }
        public function selectPurchases(){
            $sql = "SELECT 
                    p.idpurchase,
                    p.supplierid,
                    p.total,
                    DATE_FORMAT(p.date, '%d/%m/%Y') as date,
                    s.idsupplier,
                    s.name
                    FROM purchase p
                    INNER JOIN suppliers s
                    WHERE p.supplierid = s.idsupplier
                    ORDER BY p.idpurchase DESC
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
        public function insertEgress(int $idPurchase,int $intType,int $intTopic,string $strName,int $intAmount,string $strDate,int $intStatus){
            $request="";
            if($strDate){
                $arrDate = explode("-",$strDate);
                $dateCreated = date_create($arrDate[2]."-".$arrDate[1]."-".$arrDate[0]);
                $dateFormat = date_format($dateCreated,"Y-m-d");

                $sql  = "INSERT INTO count_amount(purchase_id,type_id,category_id,name,amount,date,status) VALUES(?,?,?,?,?,?,?)";
								  
	        	$arrData = array(
                    $idPurchase,
                    $intType,
                    $intTopic,
                    $strName,
                    $intAmount,
                    $dateFormat,
                    $intStatus
                );
	        	$request = $this->insert($sql,$arrData);
            }else{
                $sql  = "INSERT INTO count_amount(purchase_id,type_id,category_id,name,amount,status) VALUES(?,?,?,?,?,?)";
								  
	        	$arrData = array(
                    $idPurchase,
                    $intType,
                    $intTopic,
                    $strName,
                    $intAmount,
                    $intStatus
                );
	        	$request = $this->insert($sql,$arrData);
            }
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
    }
?>