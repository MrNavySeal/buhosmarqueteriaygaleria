<?php 
    class DescuentosModel extends Mysql{
        private $intIdCoupon;
		private $strCode;
        private $intDiscount;
		private $intStatus;
        private $arrData;
        private $intId;

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
        public function insertDescuento(array $arrData){
            $this->arrData = $arrData;

			$sql  = "INSERT INTO discount(type,categoryid,subcategoryid,discount,status,wholesale,time_limit,from_date,to_date) 
            VALUES(?,?,?,?,?,?,?,?,?)";

            $data = [
                $this->arrData['type'],
                intval($this->arrData['category']),
                intval($this->arrData['subcategory']),
                floatval($this->arrData['discount']),
                $this->arrData['status'],
                json_encode($this->arrData['wholesale_discount'],JSON_UNESCAPED_UNICODE),
                $this->arrData['time_limit'],
                $this->arrData['from_date'],
                $this->arrData['to_date'],
            ];

            $this->intId = $this->insert($sql,$data);
	        return $this->intId;
		}

        public function updateDescuento(int $intId,array $arrData){
            $this->arrData = $arrData;
            $this->intId = $intId;

			$sql  = "UPDATE discount SET type=?,categoryid=?,subcategoryid=?,discount=?,status=?,wholesale=?,time_limit=?,from_date=?,to_date=?,
            date_update = NOW() WHERE id_discount = $this->intId";

            $data = [
                $this->arrData['type'],
                intval($this->arrData['category']),
                intval($this->arrData['subcategory']),
                floatval($this->arrData['discount']),
                $this->arrData['status'],
                json_encode($this->arrData['wholesale_discount'],JSON_UNESCAPED_UNICODE),
                $this->arrData['time_limit'],
                $this->arrData['from_date'],
                $this->arrData['to_date'],
            ];
            
            $request = $this->update($sql,$data);
            return $request;
		}

        public function selectDescuentos($pageNow,$perPage, $strBuscar){
            $start = ($pageNow-1)*$perPage;
            $limit ="";

            if($perPage != 0){
                $limit = " LIMIT $start,$perPage";
            }

            $sql = "SELECT
                d.id_discount,
                d.type,
                d.categoryid,
                d.subcategoryid,
                d.discount,
                d.status,
                c.name AS category,
                d.time_limit,
                d.from_date,
                d.to_date,
                CASE
                    WHEN d.subcategoryid = 0 THEN ''
                    ELSE s.name
                END AS subcategory,
                DATE_FORMAT(d.date, '%d/%m/%Y') AS date,
                DATE_FORMAT(d.date_update, '%d/%m/%Y') AS date_update,
                CONCAT('Desde ',DATE_FORMAT(d.from_date,'%d/%m/%Y'),' hasta ',DATE_FORMAT(d.to_date,'%d/%m/%Y')) as range_time
            FROM discount d
            LEFT JOIN category c ON c.idcategory = d.categoryid
            LEFT JOIN subcategory s ON d.subcategoryid = s.idsubcategory
            WHERE c.name like '$strBuscar%' OR s.name like '$strBuscar%' OR d.type LIKE '%' ORDER BY d.id_discount DESC $limit";      

            $sqlTotal = "SELECT count(*) as total 
            FROM discount d
            LEFT JOIN category c ON c.idcategory = d.categoryid
            LEFT JOIN subcategory s ON d.subcategoryid = s.idsubcategory
            WHERE c.name like '$strBuscar%' OR s.name like '$strBuscar%' OR d.type LIKE '%'";

            $request = $this->select_all($sql);
            $totalRecords = $this->select($sqlTotal)['total'];

            $arrData = getCalcPages($totalRecords,$pageNow,$perPage);
            $arrData['data'] = $request;
            return $arrData;
        }

        public function selectDescuento($id){
            $sql = "SELECT d.*,
            c.name AS category,
            CASE
                WHEN d.subcategoryid = 0 THEN ''
                ELSE s.name
            END AS subcategory,
            DATE_FORMAT(d.from_date, '%Y-%m-%d') AS from_date,
            DATE_FORMAT(d.to_date, '%Y-%m-%d') AS to_date 
            FROM discount d
            LEFT JOIN category c ON c.idcategory = d.categoryid
            LEFT JOIN subcategory s ON d.subcategoryid = s.idsubcategory
            WHERE d.id_discount = $id";
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
        
        public function deleteDescuento($id){
            $sql = "DELETE FROM discount WHERE id_discount = $id";
            $request = $this->delete($sql);
            return $request;
        }
    }
?>