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
            $arrDate = explode("-",$strDate);
            $dateCreated = date_create($arrDate[2]."-".$arrDate[1]."-".$arrDate[0]);
            $dateFormat = date_format($dateCreated,"Y-m-d");
            $sql = "INSERT INTO purchase(supplierid,products,total,date) VALUE(?,?,?,?)";
            $arrData = array($this->intId,$this->arrProducts,$this->intTotal,$dateFormat);
            $request = $this->insert($sql,$arrData);
            return $request;
        }
        public function deletePurchase($id){
            $this->intId = $id;
            $sql = "DELETE FROM purchase WHERE idpurchase = $this->intId";
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
    }
?>