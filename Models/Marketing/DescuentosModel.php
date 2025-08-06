<?php 
    class DescuentosModel extends Mysql{
        private $intIdCoupon;
		private $strCode;
        private $intDiscount;
		private $intStatus;

        public function __construct(){
            parent::__construct();
        }
        /*************************Coupon methods*******************************/
        public function insertCoupon(string $strName, int $discount, int $intStatus){

			$this->strCode = $strName;
			$this->intDiscount = $discount;
			$this->intStatus = $intStatus;

			$return = 0;
			$sql = "SELECT * FROM coupon WHERE 
					code = '{$this->strCode}'";
			$request = $this->select_all($sql);

			if(empty($request))
			{ 
				$query_insert  = "INSERT INTO coupon(code,discount,status) 
								  VALUES(?,?,?)";
	        	$arrData = array(
                    $this->strCode,
                    $this->intDiscount,
                    $this->intStatus
        		);
	        	$request_insert = $this->insert($query_insert,$arrData);
	        	$return = $request_insert;
			}else{
				$return = "exist";
			}
	        return $return;
		}
        public function updateCoupon(int $intIdCoupon,string $strName, int $discount, int $intStatus){
            $this->intIdCoupon = $intIdCoupon;
            $this->strCode = $strName;
			$this->intDiscount = $discount;
			$this->intStatus = $intStatus;

			$sql = "SELECT * FROM coupon WHERE code = '{$this->strCode}' AND id != $this->intIdCoupon";
			$request = $this->select_all($sql);

			if(empty($request)){

                $sql = "UPDATE coupon SET code=?, discount=?, status=?, updatedate=NOW() WHERE id = $this->intIdCoupon";
                $arrData = array(
                    $this->strCode,
                    $this->intDiscount,
                    $this->intStatus
                );
				$request = $this->update($sql,$arrData);
			}else{
				$request = "exist";
			}
			return $request;
		
		}
        public function deleteCoupon($id){
            $this->intIdCoupon = $id;
            $sql = "DELETE FROM coupon WHERE id = $this->intIdCoupon";
            $request = $this->delete($sql);
            return $request;
        }
        public function selectCoupons(){
            $sql = "SELECT 
            id,
            code,
            discount,
            status,
            DATE_FORMAT(date, '%d/%m/%Y') as date,
            DATE_FORMAT(updatedate, '%d/%m/%Y') as dateupdate
            FROM coupon 
            ORDER BY id ASC";       
            $request = $this->select_all($sql);
            return $request;
        }
        public function selectCoupon($id){
            $this->intIdCoupon = $id;
            $sql = "SELECT * FROM coupon WHERE id = $this->intIdCoupon";
            $request = $this->select($sql);
            return $request;
        }
        /*************************Discount methods*******************************/
        public function insertDiscount(int $type,int $idCategory,int $idSubcategory, int $intDiscount, int $intStatus){
			$return = 0;
            $sql="";
            if($type == 1){
                $sql = "SELECT * FROM discount WHERE categoryid = $idCategory";
            }else{
                $sql = "SELECT * FROM discount WHERE subcategoryid = $idSubcategory";
            }
			$request = $this->select_all($sql);

			if(empty($request))
			{ 
				$query_insert  = "INSERT INTO discount(type,categoryid,subcategoryid,discount,status) VALUES(?,?,?,?,?)";
								  
	        	$arrData = array(
                    $type,
                    $idCategory,
                    $idSubcategory,
                    $intDiscount,
                    $intStatus
        		);
	        	$request_insert = $this->insert($query_insert,$arrData);
	        	$return = $request_insert;
			}else{
				$return = "exist";
			}
	        return $return;
		}
        public function updateDiscount($idDiscount,$type,$idCategory,$idSubcategory,$intDiscount,$intStatus){
            $sql="";
            if($type == 1){
                $sql = "SELECT * FROM discount WHERE categoryid = $idCategory AND id_discount != $idDiscount";
            }else{
                $sql = "SELECT * FROM discount WHERE subcategoryid = $idSubcategory AND id_discount !=$idDiscount";
            }
			$request = $this->select_all($sql);

			if(empty($request)){

                $sql = "UPDATE discount SET type=?,categoryid=?,subcategoryid=?,discount=?,status=? ,date=NOW() WHERE id_discount =$idDiscount";
                $arrData = array(
                    $type,
                    $idCategory,
                    $idSubcategory,
                    $intDiscount,
                    $intStatus
                );
				$request = $this->update($sql,$arrData);
			}else{
				$request = "exist";
			}
			return $request;
		
		}
        public function selectDiscounts(){
            $sql = "SELECT
                d.id_discount,
                d.type,
                d.categoryid,
                d.subcategoryid,
                d.discount,
                d.status,
                c.name AS category,
                CASE
                    WHEN d.subcategoryid = 0 THEN ''
                    ELSE s.name
                END AS subcategory,
                DATE_FORMAT(d.date, '%d/%m/%Y') AS date,
                DATE_FORMAT(d.date_update, '%d/%m/%Y') AS date_update
            FROM
                discount d
            INNER JOIN
                category c ON c.idcategory = d.categoryid
            LEFT JOIN
                subcategory s ON d.subcategoryid = s.idsubcategory";       
            $request = $this->select_all($sql);
            return $request;
        }
        public function selectDiscount($id){
            $idDiscount = $id;
            $sql = "SELECT * FROM discount WHERE id_discount = $idDiscount";
            $request = $this->select($sql);
            return $request;
        }
        public function selectCategories(){
            $sql = "SELECT * FROM category ORDER BY idcategory DESC";
            $request = $this->select_all($sql);
            return $request;
        }
        public function selectSubcategories(){
            $sql = "SELECT * FROM subcategory";
            $request = $this->select_all($sql);
            return $request;
        }
        public function getSelectSubcategories(int $intIdCategory){
            $this->intIdCategory = $intIdCategory;
            $sql = "SELECT  
                    s.idsubcategory,
                    s.name,
                    s.categoryid,
                    c.idcategory,
                    c.name as category
                    FROM subcategory s
                    INNER JOIN category c
                    ON c.idcategory = s.categoryid
                    WHERE s.categoryid = $this->intIdCategory
                    ORDER BY s.idsubcategory ASC";       
            $request = $this->select_all($sql);
            return $request;
        }
        public function selectCategory($id){
            $this->intIdCategory = $id;
            $sql = "SELECT * FROM category WHERE idcategory = $this->intIdCategory AND status = 1";
            $request = $this->select($sql);
            return $request;
        }
        public function selectSubCategory($id){
            $this->intIdSubCategory = $id;
            $sql = "SELECT
                    c.idcategory,
                    s.categoryid,
                    s.idsubcategory
                    FROM subcategory s
                    INNER JOIN category c 
                    WHERE s.categoryid = c.idcategory AND s.idsubcategory = $this->intIdSubCategory AND s.status = 1 AND c.status = 1";
            $request = $this->select($sql);
            return $request;
        }
        public function applyDiscount($type,$idCategory,$idSubcategory,$intDiscount,$intStatus){
            $this->intDiscount = $intStatus == 1 ? $intDiscount : 0;
            $sql = "";
            if($type == 1){
                $sql = "UPDATE product SET discount=? WHERE categoryid = $idCategory";
            }else{
                $sql = "UPDATE product SET discount=? WHERE subcategoryid = $idSubcategory";
            }
            $arrData = array($this->intDiscount);
            $request = $this->update($sql,$arrData);
            return $request;
        }
        public function deleteDiscount($id){
            $sql = "DELETE FROM discount WHERE id_discount = $id";
            $request = $this->delete($sql);
            return $request;
        }
    }
?>