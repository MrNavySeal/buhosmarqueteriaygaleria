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
        /*******************Suppliers**************************** */
        public function insertSupplier(string $strNit,string $strName, string $strEmail,string $strPhone,string $strAddress){
			$this->strName = $strName;
			$this->strNit = $strNit;
            $this->strEmail = $strEmail;
            $this->strPhone = $strPhone;
            $this->strAddress = $strAddress;

            $sql = "SELECT * FROM suppliers WHERE email = '$this->strEmail' OR phone = '$this->strPhone'";
            $request = $this->select($sql);
            $return ="";
            if(empty($request)){
                $query_insert  = "INSERT INTO suppliers(nit,name,email,phone,address) VALUES(?,?,?,?,?)";	  
                $arrData = array(
                    $this->strNit,
                    $this->strName, 
                    $this->strEmail,
                    $this->strPhone,
                    $this->strAddress);
                $return = $this->insert($query_insert,$arrData);
            }else{
                $return ="exists";
            }
	        return $return;
		}
        public function updateSupplier(int $id,string $strNit,string $strName, string $strEmail,string $strPhone,string $strAddress){
            $this->strName = $strName;
			$this->strNit = $strNit;
            $this->strEmail = $strEmail;
            $this->strPhone = $strPhone;
            $this->strAddress = $strAddress;
            $this->intId = $id;

            $sql = "SELECT * FROM suppliers WHERE (email = '$this->strEmail' OR phone = '$this->strPhone') AND idsupplier != $this->intId";
            $request = $this->select($sql);
            $return ="";
            if(empty($request)){
                $query  = "UPDATE suppliers SET nit =?,name=?,email=?,phone=?,address=? WHERE idsupplier = $this->intId";	  
                $arrData = array(
                    $this->strNit,
                    $this->strName, 
                    $this->strEmail,
                    $this->strPhone,
                    $this->strAddress);
                $return = $this->update($query,$arrData);
            }else{
                $return ="exists";
            }
	        return $return;
		}
        public function deleteSupplier($id){
            $this->intId = $id;
            $sql = "DELETE FROM suppliers WHERE idsupplier = $this->intId";
            $return = $this->delete($sql);
            return $return;
        }
        public function selectSuppliers(){
            $sql = "SELECT * FROM suppliers ORDER BY idsupplier DESC";       
            $request = $this->select_all($sql);
            return $request;
        }
        public function selectSupplier($id){ 
            $this->intId = $id;
            $sql = "SELECT * FROM suppliers WHERE idsupplier = $this->intId";
            $request = $this->select($sql);
            return $request;
        }
        public function search($search){
            $sql = "SELECT * FROM suppliers 
            WHERE name LIKE '%$search%' || nit LIKE '%$search%' || phone LIKE '%$search%' || email LIKE '%$search%'
            ORDER BY idsupplier DESC";
            $request = $this->select_all($sql);
            return $request;
        }
        public function sort($sort){
            $option=" ORDER BY idsupplier DESC";
            if($sort == 2){
                $option = " ORDER BY idsupplier ASC"; 
            }else if( $sort == 3){
                $option = " ORDER BY name ASC"; 
            }
            $sql = "SELECT * FROM suppliers $option";
            $request = $this->select_all($sql);
            return $request;
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
        public function searchP($search){
            $sql = "SELECT 
            p.idpurchase,
            p.supplierid,
            p.total,
            DATE_FORMAT(p.date, '%d/%m/%Y') as date,
            s.idsupplier,
            s.name,
            s.phone,
            s.email
            FROM purchase p
            INNER JOIN suppliers s
            WHERE p.supplierid = s.idsupplier 
            AND (s.name LIKE '%$search%' || s.phone LIKE '%$search%' || s.email LIKE '%$search%')
            ORDER BY p.idpurchase DESC";
            $request = $this->select_all($sql);
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
        public function selectProducts(int $id = null){
            $selProducts ="";
            if($id != null){
                $selProducts = " AND s.supplier_id = $id AND s.status = 1";
            }
            $sql = "SELECT 
            s.id_storage,
            s.name, 
            DATE_FORMAT(s.date, '%d/%m/%Y') as date,
            s.import,
            s.cost,
            s.status,
            s.reference,
            sp.name as supplier 
            FROM storage s 
            INNER JOIN suppliers sp
            WHERE sp.idsupplier = s.supplier_id $selProducts ORDER BY s.id_storage DESC";

            $request = $this->select_all($sql);
            if(!empty($request)){
                for ($i=0; $i < count($request) ; $i++) { 
                    $impt = 0;
                    $iva="0%";
                    if($request[$i]['import'] == 3){
                        $impt = 0.19;
                        $iva="19%";
                    }else if($request[$i]['import'] == 2){
                        $impt = 0.05;
                        $iva="5%";
                    }
                    $request[$i]['iva'] = $iva;
                    $request[$i]['costiva'] = round(intval($request[$i]['cost'] * $impt)/10)*10;
                    $request[$i]['costtotal'] = round(intval(($request[$i]['cost']+$request[$i]['costiva']))/100)*100;
                }
            }
            return $request;
        }
        public function selectProduct(int $id){
            $sql = "SELECT 
            s.id_storage,
            s.name, 
            DATE_FORMAT(s.date, '%d/%m/%Y') as date,
            s.import,
            s.cost,
            s.status,
            s.reference,
            s.supplier_id,
            sp.name as supplier 
            FROM storage s 
            INNER JOIN suppliers sp
            WHERE sp.idsupplier = s.supplier_id AND id_storage = $id";

            $request = $this->select($sql);
            return $request;
        }
        public function insertProduct(string $strReference, string $strName,int $intSupp,int $intCost,int $intImport,int $intStatus){
            $sql ="SELECT * FROM storage WHERE supplier_id = $intSupp AND name = '$strName'";
            $request = $this->select_all($sql);
            $return="";
            if(empty($request)){

                $sql = "INSERT INTO storage(reference,name,supplier_id,cost,import,status) VALUES(?,?,?,?,?,?)";
                $arrData = array(
                    $strReference,
                    $strName,
                    $intSupp,
                    $intCost,
                    $intImport,
                    $intStatus
                );
                $return = $this->insert($sql,$arrData);
            }else{
                $return = "exists";
            }
            return $return;
        }
        public function updateProduct(int $id,string $strReference, string $strName,int $intSupp,int $intCost,int $intImport,int $intStatus){
            $sql ="SELECT * FROM storage WHERE supplier_id = $intSupp AND name = '$strName' AND id_storage != $id";
            $request = $this->select_all($sql);
            $return="";
            if(empty($request)){

                $sql = "UPDATE storage SET reference=?,name=?,supplier_id=?,cost=?,import=?,status=? WHERE id_storage = $id";
                $arrData = array(
                    $strReference,
                    $strName,
                    $intSupp,
                    $intCost,
                    $intImport,
                    $intStatus
                );
                $return = $this->update($sql,$arrData);
            }else{
                $return = "exists";
            }
            return $return;
        }
        public function searchS($search){
            $sql = "SELECT 
            s.id_storage,
            s.name, 
            DATE_FORMAT(s.date, '%d/%m/%Y') as date,
            s.import,
            s.cost,
            s.status,
            s.reference,
            s.supplier_id,
            sp.name as supplier 
            FROM storage s 
            INNER JOIN suppliers sp
            WHERE s.supplier_id = sp.idsupplier 
            AND (s.name LIKE '%$search%' || sp.name LIKE '%$search%' || s.reference LIKE '%$search%')
            ORDER BY s.id_storage DESC";
            $request = $this->select_all($sql);
            if(!empty($request)){
                for ($i=0; $i < count($request) ; $i++) { 
                    $impt = 0;
                    $iva="0%";
                    if($request[$i]['import'] == 3){
                        $impt = 0.19;
                        $iva="19%";
                    }else if($request[$i]['import'] == 2){
                        $impt = 0.05;
                        $iva="5%";
                    }
                    $request[$i]['iva'] = $iva;
                    $request[$i]['costiva'] = round(intval($request[$i]['cost'] * $impt)/10)*10;
                    $request[$i]['costtotal'] = round(intval(($request[$i]['cost']+$request[$i]['costiva']))/100)*100;
                }
            }
            return $request;
        }
        public function deleteProduct($id){
            $this->intId = $id;
            $sql = "DELETE FROM storage WHERE id_storage = $this->intId;";
            $return = $this->delete($sql);
            return $return;
        }
    }
?>