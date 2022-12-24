<?php 
    class PedidosModel extends Mysql{
        private $intIdOrder;
        private $intIdUser;
        private $intIdTransaction;
        private $strFirstName;
        private $strLastName;
        private $strEmail;
        private $strPhone;
        private $strCountry;
        private $strState;
        private $strCity;
        private $strAddress;
        private $intTotal;
        private $intIdProduct;
        public function __construct(){
            parent::__construct();
        }
        /*************************Category methods*******************************/

        public function selectOrders(){
            $sql = "SELECT * ,DATE_FORMAT(date, '%d/%m/%Y') as date FROM orderdata ORDER BY idorder DESC";       
            $request = $this->select_all($sql);
            return $request;
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
        public function selectTransaction(string $intIdTransaction,$idPerson){
            $objTransaction = array();
            $this->intIdUser = $idPerson;
            $this->intIdTransaction = $intIdTransaction;

            $option="";
            if($idPerson !=""){
                $option =" AND personid = $this->intIdUser";
            }

            $sql = "SELECT * FROM orderdata WHERE idtransaction = '$this->intIdTransaction' $option";
            $request = $this->select($sql);
            if(!empty($request)){

                //dep($objData);exit;
                $urlTransaction ="https://api.mercadopago.com/v1/payments/".$this->intIdTransaction;
                $objTransaction = curlConnectionGet($urlTransaction,"application/json");
            }
            return $objTransaction;
        }
        public function deleteOrder($id){
            $this->intIdOrder = $id;
            $sql = "DELETE FROM orderdata WHERE idorder = $this->intIdOrder";
            $request = $this->delete($sql);
            return $request;
        }
        public function search($search){
            $sql = "SELECT * ,DATE_FORMAT(date, '%d/%m/%Y') as date FROM orderdata 
                    WHERE idtransaction 
                    LIKE '%$search%' || idorder 
                    LIKE '%$search%' || name 
                    LIKE '%$search%' || email 
                    LIKE '%$search%' || phone 
                    LIKE '%$search%' || status 
                    LIKE '%$search%' || statusorder 
                    LIKE '%$search%'
                    ORDER BY idorder DESC";
            $request = $this->select_all($sql);
            return $request;
        }
        public function sort($sort){
            $option="ORDER BY idorder DESC";
            if($sort == 2){
                $option = " ORDER BY status DESC"; 
            }else if($sort == 3){
                $option = " ORDER BY status ASC"; 
            }else if($sort == 4){
                $option = " ORDER BY statusorder DESC"; 
            }else if($sort == 5){
                $option = " ORDER BY statusorder ASC"; 
            }
            $sql = "SELECT * ,DATE_FORMAT(date, '%d/%m/%Y') as date FROM orderdata $option ";
            $request = $this->select_all($sql);
            return $request;
        }
        public function selectProducts(){
            $sql = "SELECT 
                p.idproduct,
                p.categoryid,
                p.subcategoryid,
                p.reference,
                p.name,
                p.description,
                p.price,
                p.discount,
                p.description,
                p.stock,
                p.status,
                p.route,
                c.idcategory,
                c.name as category,
                s.idsubcategory,
                s.categoryid,
                s.name as subcategory,
                DATE_FORMAT(p.date, '%d/%m/%Y') as date
            FROM product p
            INNER JOIN category c, subcategory s
            WHERE c.idcategory = p.categoryid AND c.idcategory = s.categoryid AND p.subcategoryid = s.idsubcategory AND p.status = 1 AND p.stock > 0
            ORDER BY p.idproduct DESC
            ";
            $request = $this->select_all($sql);
            if(count($request)> 0){
                for ($i=0; $i < count($request); $i++) { 
                    $idProduct = $request[$i]['idproduct'];
                    $sqlImg = "SELECT * FROM productimage WHERE productid = $idProduct";
                    $requestImg = $this->select_all($sqlImg);
                    if(count($requestImg)>0){
                        $request[$i]['image'] = media()."/images/uploads/".$requestImg[0]['name'];
                    }else{
                        $request[$i]['image'] = media()."/images/uploads/image.png";
                    }
                }
            }
            return $request;
        }
        public function selectProduct($id){
            $this->intIdProduct = $id;
            $sql = "SELECT * FROM product WHERE idproduct = $this->intIdProduct";
            $request = $this->select($sql);
            $sqlImg = "SELECT * FROM productimage WHERE productid = $this->intIdProduct";
            $requestImg = $this->select_all($sqlImg);
            $request['image'] = media()."/images/uploads/".$requestImg[0]['name'];
            return $request;
        }
        public function searchProducts($search){
            $sql="SELECT 
                p.idproduct,
                p.categoryid,
                p.subcategoryid,
                p.reference,
                p.name,
                p.description,
                p.price,
                p.discount,
                p.description,
                p.stock,
                p.status,
                p.route,
                c.idcategory,
                c.name as category,
                c.route as routec,
                s.idsubcategory,
                s.categoryid,
                s.name as subcategory,
                DATE_FORMAT(p.date, '%d/%m/%Y') as date
            FROM product p
            INNER JOIN category c, subcategory s
            WHERE c.idcategory = p.categoryid AND c.idcategory = s.categoryid AND p.subcategoryid = s.idsubcategory AND
            p.name LIKE  '%$search%' AND p.status= 1|| c.idcategory = p.categoryid AND c.idcategory = s.categoryid AND p.subcategoryid = s.idsubcategory AND
            c.name LIKE  '%$search%' AND p.status= 1|| c.idcategory = p.categoryid AND c.idcategory = s.categoryid AND p.subcategoryid = s.idsubcategory AND
            s.name LIKE '%$search%' AND p.status= 1
            ";
            $request = $this->select_all($sql);
            if(count($request)> 0){
                for ($i=0; $i < count($request); $i++) { 
                    $idProduct = $request[$i]['idproduct'];
                    $sqlImg = "SELECT * FROM productimage WHERE productid = $idProduct";
                    $requestImg = $this->select_all($sqlImg);
                    if(count($requestImg)>0){
                        $request[$i]['image'] = media()."/images/uploads/".$requestImg[0]['name'];
                    }else{
                        $request[$i]['image'] = media()."/images/uploads/image.png";
                    }
                }
            }
            return $request;
        }
        public function searchCustomers($search){
            $sql = "SELECT *,DATE_FORMAT(date, '%d/%m/%Y') as date
            FROM person 
            WHERE firstname LIKE '%$search%' AND roleid=2
            ||  lastname LIKE '%$search%' AND roleid=2 ||  email LIKE '%$search%' AND roleid=2
            ||  phone LIKE '%$search%' AND roleid=2
            ORDER BY idperson DESC";

            $request = $this->select_all($sql);
            return $request;
        }
        public function selectCustomer($id){
            $this->intIdUser = $id;
            $sql = "SELECT 
                    p.idperson,
                    p.image,
                    p.firstname,
                    p.lastname,
                    p.email,
                    p.phone,
                    p.address,
                    p.roleid,
                    p.countryid,
                    p.stateid,
                    p.cityid,
                    p.typeid,
                    p.identification,
                    DATE_FORMAT(p.date, '%d/%m/%Y') as date,
                    p.status,
                    r.idrole,
                    r.name as role,
                    c.id,
                    s.id,
                    t.id,
                    c.name as country,
                    s.name as state,
                    t.name as city
                    FROM person p
                    INNER JOIN role r, countries c, states s,cities t 
                    WHERE c.id = p.countryid AND p.stateid = s.id AND t.id = p.cityid AND r.idrole = p.roleid AND p.idperson = $this->intIdUser";
            $request = $this->select($sql);
            return $request;
        }
        public function insertOrder(int $idUser, string $idTransaction, string $strName,string $strEmail,string $strPhone,string $strAddress,
        string $strNote,string $strDate,string $cupon,int $envio,int $total,string $status, string $type,string $statusOrder){

            $this->strIdTransaction = $idTransaction;
            $this->intIdUser = $idUser;
            $this->strName = $strName;
            $this->strEmail = $strEmail;
            $this->strPhone = $strPhone;
            $this->strAddress = $strAddress;
            if($strDate !=""){
                $arrDate = explode("-",$strDate);
                $dateCreated = date_create($arrDate[2]."-".$arrDate[1]."-".$arrDate[0]);
                $dateFormat = date_format($dateCreated,"Y-m-d");
                
                $sql ="INSERT INTO orderdata(personid,idtransaction,name,email,phone,address,note,amount,date,status,coupon,shipping,type,statusorder) VALUE(?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                $arrData = array(
                    $this->intIdUser, 
                    $this->strIdTransaction,
                    $this->strName,
                    $this->strEmail,
                    $this->strPhone,
                    $this->strAddress,
                    $strNote,
                    $total,
                    $dateFormat,
                    $status,
                    $cupon,
                    $envio,
                    $type,
                    $statusOrder,
                );
                $request = $this->insert($sql,$arrData);
            }else{
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
                $request = $this->insert($sql,$arrData);
            }
            return $request;
        }
        public function insertOrderDetail(array $arrOrder){
            $this->intIdUser = $arrOrder['iduser'];
            $this->intIdOrder = $arrOrder['idorder'];
            $products = $arrOrder['products'];
            foreach ($products as $pro) {
                $this->intIdProduct = $pro['id'];
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
                }else if($pro['topic'] == 2){
                    $description = $pro['name'];
                    $selectProduct = $this->selectProduct($this->intIdProduct);
                    if($selectProduct['stock']>0){
                        $stock = $selectProduct['stock']-$pro['qty'];
                        $this->updateStock($this->intIdProduct,$stock);
                    }
                }else{
                    $description = $pro['name'];
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
                $request = $this->insert($query,$arrData);
            }
            return $request;
        }
        public function updateStock($id,$stock){
            $this->intIdProduct = $id;
            $sql = "UPDATE product SET stock=? WHERE idproduct = $this->intIdProduct";
            $arrData = array($stock);
            $request = $this->update($sql,$arrData);
            return $request;
        }
        public function updateOrder($idOrder,$idTransaction,$strDate,$strNote,$status,$statusOrder){
            $this->intIdOrder = $idOrder;
            $this->strIdTransaction = $idTransaction;
            $sql = "UPDATE orderdata SET idtransaction=?,note=?,status=?, date=?,statusorder=? WHERE idorder = $this->intIdOrder";
            $arrData = array($this->strIdTransaction,$strNote,$status,$strDate,$statusOrder);
            $request = $this->update($sql,$arrData);
            return $request;
        }
        /*************************Category methods*******************************/
        public function selectCategories(){
            $sql = "SELECT * FROM moldingcategory ORDER BY id ASC";       
            $request = $this->select_all($sql);
            return $request;
        }
    }
?>