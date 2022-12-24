<?php
    require_once("Libraries/Core/Mysql.php");
    trait CustomerTrait{
        private $con;
        private $strName;
        private $strPicture;
        private $strPassword;
        private $intRoleId;
        private $intIdUser;
        private $strIdTransaction;
        private $strCoupon;
        private $intIdOrder;
        private $strFirstName;
        private $strLastName;
        private $strEmail;
        private $strPhone;
        private $strCountry;
        private $strState;
        private $strCity;
        private $strAddress;
        private $strPostalCode;
        private $strSubject;
        private $strMessage;
        private $intIdProduct;
        

        public function setCustomerT($strName,$strPicture,$strEmail,$strPassword,$rolid){
            $this->con = new Mysql();
            $this->strNombre = $strName;
            $this->strPicture = $strPicture; 
            $this->strEmail =  $strEmail;
            $this->strPassword = $strPassword;
            $this->intRolId = $rolid;
            $return="";
            
            $sql = "SELECT * FROM person WHERE email = '$this->strEmail'";
            $request = $this->con->select_all($sql);
            if(empty($request)){
                $query = "INSERT INTO person(firstname,image,email,countryid,stateid,cityid,password,roleid) VALUE(?,?,?,?,?,?,?,?)";
                $arrData = array($this->strNombre,
                                $this->strPicture,
                                $this->strEmail,
                                99999,
                                99999,
                                99999,
                                $this->strPassword,
                                $this->intRolId
                                );
                $request_insert = $this->con->insert($query,$arrData);
                $return = $request_insert;
            }else{
                $return ="exist";
            }
            return $return;
        }
        public function selectCountries(){
            $this->con = new Mysql();
            $sql = "SELECT * FROM countries WHERE id = 47";
            $request = $this->con->select($sql);
            return $request;
        }
        public function selectStates($country){
            $this->con = new Mysql();
            $sql = "SELECT * FROM states WHERE country_id = $country";
            $request = $this->con->select_all($sql);
            return $request;
        }
        public function selectCities($state){
            $this->con = new Mysql();
            $sql = "SELECT * FROM cities WHERE state_id = $state";
            $request = $this->con->select_all($sql);
            return $request;
        }
        public function selectCouponCode($strCoupon){
            $this->con = new Mysql();
            $this->strCoupon = $strCoupon;
            $sql = "SELECT * FROM coupon WHERE code = '$this->strCoupon' AND status = 1";
            $request = $this->con->select($sql);
            return $request;
        }
        public function checkCoupon($idUser,$idCoupon){
            $this->con = new Mysql();
            $this->intIdUser = $idUser;
            $sql = "SELECT * FROM usedcoupon WHERE personid = $this->intIdUser AND couponid = $idCoupon";
            $request = $this->con->select($sql);
            if(!empty($request)){
                $request = true;
            }else{
                $request = false;
            }
            return $request;
        }
        public function setCoupon($idCoupon,$idUser,$code){
            $this->con = new Mysql();
            $this->intIdUser = $idUser;
            $sql = "INSERT INTO usedcoupon(couponid,personid,code) VALUE(?,?,?)";
            $arrData = array($idCoupon,$this->intIdUser,$code);
            $request = $this->con->insert($sql,$arrData);
            return;
        }
        public function insertOrder(int $idUser, string $idTransaction, string $strName,string $strEmail,string $strPhone,string $strAddress,
        string $strNote,string $cupon,int $envio,int $total,string $status, string $type,string $statusOrder){

            $this->con = new Mysql();
            $this->strIdTransaction = $idTransaction;
            $this->intIdUser = $idUser;
            $this->strName = $strName;
            $this->strEmail = $strEmail;
            $this->strPhone = $strPhone;
            $this->strAddress = $strAddress;
            
            $sql ="INSERT INTO orderdata(personid,idtransaction,name,email,phone,address,note,amount,status,coupon,shipping,type,statusorder) VALUE(?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $arrData = array(
                $this->intIdUser, 
                $this->strIdTransaction,
                $this->strName,
                $this->strEmail,
                $this->strPhone,
                $this->strAddress,
                $strNote,
                $total,
                $status,
                $cupon,
                $envio,
                $type,
                $statusOrder
            );
            $request = $this->con->insert($sql,$arrData);
            return $request;
        }
        public function insertOrderDetail(array $arrOrder){
            $this->con = new Mysql();
            $this->intIdUser = $arrOrder['iduser'];
            $this->intIdOrder = $arrOrder['idorder'];
            $products = $arrOrder['products'];
            foreach ($products as $pro) {
                $this->intIdProduct = openssl_decrypt($pro['id'],METHOD,KEY);
                if($pro['topic'] == 1){
                    $description = json_encode(array(
                        "name"=>$pro['name'],
                        "type"=>$pro['type'],
                        "idType"=>$pro['idType'],
                        "orientation"=>$pro['orientation'],
                        "style"=>$pro['style'],
                        "reference"=>$pro['reference'],
                        "height"=>$pro['height'],
                        "width"=>$pro['width'],
                        "margin"=>$pro['margin'],
                        "colormargin"=>$pro['colormargin'],
                        "colorborder"=>$pro['colorborder'],
                        "img"=>$pro['img'],
                        "photo"=>$pro['photo']
                    ));
                }else{
                    $description = $pro['name'];
                    $selectProduct = $this->selectProductC($this->intIdProduct);
                    if($selectProduct['stock']>0){
                        $stock = $selectProduct['stock']-$pro['qty'];
                        $this->updateStock($this->intIdProduct,$stock);
                    }
                }
                $query = "INSERT INTO orderdetail(orderid,personid,productid,topic,description,quantity,price)
                        VALUE(?,?,?,?,?,?,?)";
                $arrData=array($this->intIdOrder,
                                $this->intIdUser,
                                $this->intIdProduct,
                                $pro['topic'],
                                $description,
                                $pro['qty'],
                                $pro['price']);
                $request = $this->con->insert($query,$arrData);
            }
            return $request;
        }
        public function getOrder($idOrder){
            $this->con = new Mysql();
            $this->intIdOrder =$idOrder;
            $sql = "SELECT *, DATE_FORMAT(date, '%d/%m/%Y') as date FROM orderdata WHERE idorder = $this->intIdOrder";
            $order = $this->con->select($sql);
            if(!empty($order)){
                $sql = "SELECT * FROM orderdetail WHERE orderid = $this->intIdOrder";
                $detail = $this->con->select_all($sql);
                $arrData = array("order"=>$order,"detail"=>$detail);
            }   
            return $arrData;
        }
        public function setMessage($strName,$strPhone,$strEmail,$strSubject,$strMessage){
            $this->con = new Mysql();
            $this->strName = $strName;
            $this->strEmail = $strEmail;
            $this->strSubject = $strSubject;
            $this->strMessage = $strMessage;
            $this->strPhone = $strPhone;

            $sql = "INSERT INTO contact(name,phone,email,subject,message,status) VALUES(?,?,?,?,?,?)";
            $arrData = array($this->strName,$this->strPhone,$this->strEmail,$this->strSubject,$strMessage,2);
            $request = $this->con->insert($sql,$arrData);
            return $request;
        }
        public function setSuscriberT($strEmail){
            $this->con = new Mysql();
            $this->strEmail = $strEmail;
            $return ="";
            $sql = "SELECT * FROM suscriber WHERE email = '$strEmail'";
            $request = $this->con->select($sql);
            if(empty($request)){
                $sql = "INSERT INTO suscriber(email) VALUES(?)";
                $arrData = array($strEmail);
                $request = $this->con->insert($sql,$arrData);
                $return = $request;
            }else{
                $return = "exists";
            }
            return $return;
        }
        public function statusCouponSuscriberT(){
            $this->con = new Mysql();
            $sql = "SELECT * FROM coupon WHERE id = 1 AND status = 1 AND discount > 0";
            $request = $this->con->select($sql);
            return $request;
        }
        public function selectShippingMode(){
            $this->con = new Mysql();
            $sql = "SELECT * FROM shipping WHERE status = 1";
            $request = $this->con->select($sql);
            if($request['id'] == 3){
                $sqlCities = "SELECT
                sh.id,
                c.name as country,
                s.name as state,
                cy.name as city,
                sh.value
                FROM shippingcity sh
                INNER JOIN countries c, states s, cities cy
                WHERE c.id = sh.country_id AND s.id = sh.state_id AND cy.id = sh.city_id
                ORDER BY cy.name ASC";
                $cities = $this->con->select_all($sqlCities);
                $request['cities'] = $cities;
            }
            return $request;
        }
        public function selectShippingCity($id){
            $this->con = new Mysql();
            $sql = "SELECT
            sh.id,
            c.name as country,
            s.name as state,
            cy.name as city,
            sh.value
            FROM shippingcity sh
            INNER JOIN countries c, states s, cities cy
            WHERE c.id = sh.country_id AND s.id = sh.state_id AND cy.id = sh.city_id AND sh.id = $id ORDER BY cy.name ASC";
            $request = $this->con->select($sql);
            return $request;
        }
        public function selectProductC($id){
            $this->con = new Mysql();
            $this->intIdProduct = $id;
            $sql = "SELECT * FROM product WHERE idproduct =$this->intIdProduct";
            $request = $this->con->select($sql);
            return $request;
        }
        public function updateStock($id,$stock){
            $this->con = new Mysql();
            $this->intIdProduct = $id;
            $sql = "UPDATE product SET stock=? WHERE idproduct = $this->intIdProduct";
            $arrData = array($stock);
            $request = $this->con->update($sql,$arrData);
            return $request;
        }
    }
    
?>