<?php 
    class AdministracionModel extends Mysql{
        private $intIdCoupon;
		private $strCode;
        private $intDiscount;
		private $intStatus;
        private $strMessage;
        private $intIdMessage;
        private $strEmail;
        private $strSubject;
        private $intIdPage;
        private $strDescription;
        private $strName;
        private $intIdCountry;
        private $intIdState;
        private $intIdCity;
        private $intValue;
        private $idShipping;

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
        /*************************Mailbox methods*******************************/
        public function selectMails(){
            $sql = "SELECT * ,DATE_FORMAT(date, '%d/%m/%Y') as date FROM contact ORDER BY id DESC";       
            $request = $this->select_all($sql);
            return $request;
        }
        public function selectSentMails(){
            $sql = "SELECT * ,DATE_FORMAT(date, '%d/%m/%Y') as date FROM sendmessage ORDER BY id DESC";       
            $request = $this->select_all($sql);
            return $request;
        }
        public function selectMail(int $id){
            $sql = "UPDATE contact SET status=? WHERE id = $id";
            $arrData = array(1);
            $request = $this->update($sql,$arrData);
            $sql = "SELECT *, DATE_FORMAT(date, '%d/%m/%Y') as date, DATE_FORMAT(date_updated, '%d/%m/%Y') as dateupdated FROM contact WHERE id=$id";
            $request = $this->select($sql);
            return $request;
        }
        public function selectSentMail(int $id){
            $sql = "SELECT *, DATE_FORMAT(date, '%d/%m/%Y') as date FROM sendmessage WHERE id=$id";
            $request = $this->select($sql);
            return $request;
        }
        public function updateMessage($strMessage,$idMessage){
            $this->strMessage = $strMessage;
            $this->intIdMessage = $idMessage;
            $sql = "UPDATE contact SET reply=?, date_updated=NOW() WHERE id = $this->intIdMessage";
            $arrData = array($this->strMessage);
            $request = $this->update($sql,$arrData);
            return $request;
        }
        public function insertMessage($strSubject,$strEmail,$strMessage){
            $this->strMessage = $strMessage;
            $this->strEmail = $strEmail;
            $this->strSubject = $strSubject;

            $sql = "INSERT INTO sendmessage(email,subject,message) VALUES(?,?,?)";
            $arrData = array($this->strEmail,$this->strSubject,$this->strMessage);
            $request = $this->insert($sql,$arrData);
            return $request;
        }
        public function delEmail($id,$option){
            $sql ="";
            if($option == 1){
                $sql = "DELETE FROM contact WHERE id =$id";
            }else{
                $sql = "DELETE FROM sendmessage WHERE id =$id";
            }
            $request = $this->delete($sql);
            return $request;
        }
        /*************************Subscribers methods*******************************/
        public function selectSubscribers(){
            $sql = "SELECT *, DATE_FORMAT(date, '%d/%m/%Y') as date FROM suscriber";
            $request = $this->select_all($sql);
            return $request;
        }
        /*************************Shipping methods*******************************/
        public function selectFlatRate(){
            $sql = "SELECT value FROM shipping WHERE id = 2";
            $request = $this->select($sql);
            return $request['value'];
        }
        public function setShippingMode($idShipping, $value){
            $this->idShipping = $idShipping;
            $this->intValue = $value;
            $request="";
            if($this->idShipping == 2){
                $sql = "UPDATE shipping SET value = ?, status=? WHERE id = $this->idShipping";
                $arrData = array($this->intValue,2);
                $request = $this->update($sql,$arrData);
                $sql = "UPDATE shipping SET status=?";
                $arrData = array(2);
                $request = $this->update($sql,$arrData);
            }else{
                $sql = "UPDATE shipping SET status=?";
                $arrData = array(2);
                $request = $this->update($sql,$arrData);
            }
            $sql = "UPDATE shipping SET status=? WHERE id = $this->idShipping";
            $arrData = array(1);
            $request = $this->update($sql,$arrData);
            return $request;
        }
        public function setShippingCity(int $idCountry,int $idState,int $idCity,int $value){
            $this->intIdCountry = $idCountry;
            $this->intIdState = $idState;
            $this->intIdCity = $idCity;
            $this->intValue = $value;
            $return = "";
            $sql = "SELECT * FROM shippingcity WHERE country_id = $this->intIdCountry AND state_id = $this->intIdState AND city_id = $this->intIdCity";
            $request = $this->select_all($sql);
            if(empty($request)){
                $sql = "INSERT INTO shippingcity(country_id,state_id,city_id,value) VALUES(?,?,?,?)";
                $arrData = array($this->intIdCountry,$this->intIdState,$this->intIdCity,$this->intValue);
                $request = $this->insert($sql,$arrData);
                $return = $request;
            }else{
                $return = "exists";
            }
            return $return;
        }
        public function delShippingCity($idShipping){
            $this->idShipping = $idShipping;
            $sql = "DELETE FROM shippingcity WHERE id = $this->idShipping;SET @autoid :=0; 
			UPDATE shippingcity SET id = @autoid := (@autoid+1);
			ALTER TABLE shippingcity Auto_Increment = 1";
            $request = $this->delete($sql);
            return $request;
        }
        public function selectShippingCities(){
            $sql = "SELECT
                sh.id,
                c.name as country,
                s.name as state,
                cy.name as city,
                sh.value
                FROM shippingcity sh
                INNER JOIN countries c, states s, cities cy
                WHERE c.id = sh.country_id AND s.id = sh.state_id AND cy.id = sh.city_id
            ";
            $request = $this->select_all($sql);
            return $request;
        }
        public function selectCountries(){
            $sql = "SELECT * FROM countries";
            $request = $this->select_all($sql);
            return $request;
        }
        public function selectStates($country){
            $sql = "SELECT * FROM states WHERE country_id = $country";
            $request = $this->select_all($sql);
            return $request;
        }
        public function selectCities($state){
            $sql = "SELECT * FROM cities WHERE state_id = $state";
            $request = $this->select_all($sql);
            return $request;
        }
        /*************************Pages methods*******************************/
        public function selectPage(int $intIdPage){
            $this->intIdPage = $intIdPage;
            $sql = "SELECT *,DATE_FORMAT(date_created, '%d/%m/%Y') as datecreated,DATE_FORMAT(date_updated, '%d/%m/%Y') as dateupdated  FROM pages WHERE id = $this->intIdPage";
            $request = $this->select($sql);
            return $request;
        }
        public function updatePage(int $intIdPage,string $strName, string $strDescription){
            $this->intIdPage = $intIdPage;
            $this->strDescription = $strDescription;
            $this->strName = $strName;
            $sql = "UPDATE pages SET name=?,description=?, date_updated=NOW() WHERE id = $this->intIdPage";
            $arrData = array($this->strName,$this->strDescription);
            $request = $this->update($sql,$arrData);
            return $request;
        }
    }
?>