<?php 
    class FacturaModel extends Mysql{
        private $intIdOrder;
        private $intIdUser;
        private $strCoupon;

        public function __construct(){
            parent::__construct();
        }
        public function selectOrder($id,$idPerson){
            $this->intIdOrder = $id;
            $this->intIdUser = $idPerson;
            $option="";
            if($idPerson !=""){
                $option =" AND personid = $this->intIdUser";
            }
            $sql = "SELECT * ,DATE_FORMAT(date, '%d/%m/%Y') as date FROM orderdata WHERE idorder = $this->intIdOrder $option";
            $request = $this->select($sql);
            return $request;
        }
        public function selectOrderDetail($id){
            $this->intIdOrder = $id;
            $sql = "SELECT * FROM orderdetail WHERE orderid = $this->intIdOrder";
            $request = $this->select_all($sql);
            return $request;
        }
        public function selectCouponCode($strCoupon){
            $this->strCoupon = $strCoupon;
            $sql = "SELECT * FROM coupon WHERE code = '$this->strCoupon' AND status = 1";
            $request = $this->select($sql);
            return $request;
        }
    }
?>