<?php 
    class PedidosModel extends Mysql{
        private $intIdOrder;
        private $intIdUser;
        private $intIdTransaction;
        private $strIdentification;
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
        private $suscription;
        public function __construct(){
            parent::__construct();
        }
        /*************************Category methods*******************************/

        public function selectOrders($idPerson){
            $whre="";
            if($idPerson!="")$whre=" WHERE personid=$idPerson";
            $sql = "SELECT 
            idorder,
            idtransaction,
            name,
            identification,
            email,
            phone,
            amount,
            status,
            type,
            statusorder,
            DATE_FORMAT(date, '%d/%m/%Y') as date 
            FROM orderdata $whre ORDER BY idorder DESC";       
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
            $sql = "SELECT * ,DATE_FORMAT(date, '%d/%m/%Y') as date,DATE_FORMAT(date_beat, '%d/%m/%Y') as date_beat FROM orderdata WHERE idorder = $this->intIdOrder $option";
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
                p.product_type,
                p.route,
                c.idcategory,
                c.name as category,
                s.idsubcategory,
                s.categoryid,
                s.name as subcategory,
                DATE_FORMAT(p.date, '%d/%m/%Y') as date
            FROM product p
            INNER JOIN category c, subcategory s
            WHERE c.idcategory = p.categoryid AND c.idcategory = s.categoryid AND p.subcategoryid = s.idsubcategory
            ORDER BY p.idproduct ASC
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
                    if($request[$i]['product_type'] == 2){
                        $sqlV = "SELECT MIN(price) AS minimo FROM product_variant WHERE productid =$idProduct";
                        $sqlTotal = "SELECT SUM(stock) AS total FROM product_variant WHERE productid =$idProduct";
                        $sqlVariants = "SELECT * FROM product_variant WHERE productid = $idProduct ORDER BY price ASC";
                        $request[$i]['price'] = $this->select($sqlV)['minimo'];
                        $request[$i]['stock'] = $this->select($sqlTotal)['total'];
                        $request[$i]['variants'] = $this->select_all($sqlVariants);
                    }
                }
            }
            return $request;
        }
        public function selectProduct($id,$variant=null){
            $this->intIdProduct = $id;
            $sql = "SELECT * FROM product WHERE idproduct = $this->intIdProduct";
            $request = $this->select($sql);
            $sqlImg = "SELECT * FROM productimage WHERE productid = $this->intIdProduct";
            $requestImg = $this->select_all($sqlImg);
            $request['image'] = media()."/images/uploads/".$requestImg[0]['name'];
            if($request['product_type'] == 2){
                $sqlV = "SELECT * FROM product_variant WHERE id_product_variant = $variant";
                $request['variant'] = $this->select($sqlV);
                //$request['variant']['price'] = round((($request['variant']['price']*COMISION)+TASA)/1000)*1000;
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
        public function insertOrder(int $idUser, string $strName,string $strIdentification,string $strEmail,string $strPhone,string $strAddress,
        string $strNote,string $strDate,string $cupon,int $envio,array $arrSuscription,int $total,string $status, string $type,string $statusOrder,$dateBeat){
            
            $suscription = json_encode($arrSuscription);
            //dep($suscription);exit;
            $this->intIdUser = $idUser;
            $this->strName = $strName;
            $this->strEmail = $strEmail;
            $this->strPhone = $strPhone;
            $this->strAddress = $strAddress;
            $this->strIdentification = $strIdentification;
            if($strDate !=""){
                $arrDate = explode("-",$strDate);
                $dateCreated = date_create($arrDate[2]."-".$arrDate[1]."-".$arrDate[0]);
                $dateFormat = date_format($dateCreated,"Y-m-d");
                
                $sql ="INSERT INTO orderdata(personid,name,identification,email,phone,address,note,amount,date,status,coupon,shipping,suscription,type,statusorder,date_beat) VALUE(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                $arrData = array(
                    $this->intIdUser, 
                    $this->strName,
                    $this->strIdentification,
                    $this->strEmail,
                    $this->strPhone,
                    $this->strAddress,
                    $strNote,
                    $total,
                    $dateFormat,
                    $status,
                    $cupon,
                    $envio,
                    $suscription,
                    $type,
                    $statusOrder,
                    $dateBeat,
                );
                $request = $this->insert($sql,$arrData);
            }else{
                $sql ="INSERT INTO orderdata(personid,name,identification,email,phone,address,note,amount,status,coupon,shipping,suscription,type,statusorder,date_beat) VALUE(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                $arrData = array(
                    $this->intIdUser, 
                    $this->strName,
                    $this->strIdentification,
                    $this->strEmail,
                    $this->strPhone,
                    $this->strAddress,
                    $strNote,
                    $total,
                    $status,
                    $cupon,
                    $envio,
                    $suscription,
                    $type,
                    $statusOrder,
                    $dateBeat
                );
                $request = $this->insert($sql,$arrData);
            }
            if($request>0){
                $status = $status=="pendent" ? 2 : 1;
                $this->insertIncome($request,3,1,"Venta de producto",$total,$strDate,$status);
            }
            return $request;
        }
        public function insertOrderDetail(array $arrOrder){
            $this->intIdUser = $arrOrder['iduser'];
            $this->intIdOrder = $arrOrder['idorder'];
            $products = $arrOrder['products'];
            foreach ($products as $pro) {
                $this->intIdProduct = $pro['id'];
                $price = 0;
                $price = $pro['price'];
                $reference = isset($pro['reference']) ? $pro['reference'] : " ";
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
                        "colorframe"=>$pro['colorframe'],
                        "material"=>$pro['material'],
                        "glass"=>$pro['glass'],
                        "img"=>$pro['img'],
                        "photo"=>$pro['photo']
                    ));
                }else if($pro['topic'] == 2){
                    $variant = $pro['producttype'] == 2 ? $pro['variant']['id_product_variant'] : null;
                    $selectProduct = $this->selectProduct($this->intIdProduct,$variant);
                    $price = $pro['producttype'] == 1 ? $pro['price'] : $pro['variant']['price'];
                    $description = $pro['producttype'] == 1 ? $pro['name'] : $pro['name']." ".$pro['variant']['width']."x".$pro['variant']['height']."cm";
                    if($selectProduct['stock']>0 && $pro['producttype'] == 1){
                        $stock = $selectProduct['stock']-$pro['qty'];
                        $this->updateStock($this->intIdProduct,$stock);
                    }else if($selectProduct['variant']['stock'] > 0 && $pro['producttype'] == 2){
                        $stock = $selectProduct['variant']['stock']-$pro['qty'];
                        $this->updateStock($this->intIdProduct,$stock,$pro['variant']['id_product_variant']);
                    }
                }else{
                    $description = $pro['name'];
                }
                
                $query = "INSERT INTO orderdetail(orderid,personid,productid,topic,description,quantity,price,reference)
                        VALUE(?,?,?,?,?,?,?,?)";
                $arrData=array(
                    $this->intIdOrder,
                    $this->intIdUser,
                    $this->intIdProduct,
                    $pro['topic'],
                    $description,
                    $pro['qty'],
                    $price,
                    $reference
                );
                $request = $this->insert($query,$arrData);
            }
            return $request;
        }
        public function updateStock($id,$stock,$variant=null){
            $this->intIdProduct = $id;
            if($variant != null){
                $sql = "UPDATE product_variant SET stock=? WHERE id_product_variant = $variant";
                $arrData = array($stock);
            }else{
                $sql = "UPDATE product SET stock=? WHERE idproduct = $this->intIdProduct";
                $arrData = array($stock);
            }
            $request = $this->update($sql,$arrData);
            return $request;
        }
        public function updateOrder($idOrder,string $strName,$strIdentification,string $strEmail,string $strPhone,string $strAddress,$strDate,$strNote,$arrSuscription,$type,$status,$statusOrder,$dateBeat,$updateCustomer){
            $sql="";
            $arrData="";
            $this->intIdOrder = $idOrder;
            $this->strName = $strName;
            $this->strEmail = $strEmail;
            $this->strPhone = $strPhone;
            $this->strAddress = $strAddress;
            $this->strIdentification = $strIdentification;
            $order = $this->selectOrder($this->intIdOrder,"");
            if(!empty($arrSuscription)){
                $subtotal = 0;
                for ($i=0; $i < count($arrSuscription) ; $i++) { 
                    if($arrSuscription[$i]['date'] == ""){
                        $arrSuscription[$i]['date'] = date("Y-m-d");
                    }
                    $subtotal+= $arrSuscription[$i]['debt'];
                }
                if($subtotal > $order['amount']){
                    return false;
                }
                if($subtotal == $order['amount'] && $status == "pendent"){
                    $status = "approved";
                }else if($subtotal < $order['amount'] && $status != "canceled"){
                    $status = "pendent";
                }
            }
            $arrSuscription = json_encode($arrSuscription);
            if($updateCustomer==2){
                $sql = "UPDATE orderdata SET name=?,identification=?,email=?,phone=?,address=?,note=?,suscription=?,type=?,status=?, date=?,statusorder=?, date_beat=? WHERE idorder = $this->intIdOrder";
                $arrData = array(
                    $this->strName,
                    $this->strIdentification,
                    $this->strEmail,
                    $this->strPhone,
                    $this->strAddress,
                    $strNote,
                    $arrSuscription,
                    $type,
                    $status,
                    $strDate,
                    $statusOrder,
                    $dateBeat
                );
            }else{
                $sql = "UPDATE orderdata SET suscription=?,type=?,status=?, date=?,statusorder=?, date_beat=? WHERE idorder = $this->intIdOrder";
                $arrData = array(
                    $arrSuscription,
                    $type,
                    $status,
                    $strDate,
                    $statusOrder,
                    $dateBeat
                );
            }
            $request = $this->update($sql,$arrData);
            if($request>0){
                $statusC = 1;
                if($status != "approved"){
                    $statusC = 2;
                }
                $this->update("UPDATE count_amount SET status=? WHERE order_id = $idOrder",array($statusC));
            }
            return $request;
        }
        public function insertIncome(int $id, int $intType,int $intTopic,string $strName,int $intAmount,string $strDate,int $intStatus){
            $request="";
            if($strDate !=""){
                $arrDate = explode("-",$strDate);
                $dateCreated = date_create($arrDate[2]."-".$arrDate[1]."-".$arrDate[0]);
                $dateFormat = date_format($dateCreated,"Y-m-d");

                $sql  = "INSERT INTO count_amount(order_id,type_id,category_id,name,amount,date,status) VALUES(?,?,?,?,?,?,?)";
								  
	        	$arrData = array(
                    $id,
                    $intType,
                    $intTopic,
                    $strName,
                    $intAmount,
                    $dateFormat,
                    $intStatus
                );
	        	$request = $this->insert($sql,$arrData);
            }else{
                $sql  = "INSERT INTO count_amount(order_id,type_id,category_id,name,amount,status) VALUES(?,?,?,?,?,?)";
								  
	        	$arrData = array(
                    $id,
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
        /*************************Category methods*******************************/
        public function selectCategories(){
            $sql = "SELECT * FROM moldingcategory WHERE status != 3 ORDER BY id ASC";       
            $request = $this->select_all($sql);
            return $request;
        }
    }
?>