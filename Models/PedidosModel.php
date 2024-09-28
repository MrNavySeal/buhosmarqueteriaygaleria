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
        private $intStatus;
        public function __construct(){
            parent::__construct();
        }
        /*************************Category methods*******************************/
        public function selectAdvances(){
            $request = $this->select_all("SELECT *,DATE_FORMAT(date, '%Y-%m-%d') as date FROM order_advance");
            return $request;
        }
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
            shipping,
            status,
            type,
            address,
            statusorder,
            coupon,
            note,
            send_by,
            number_guide,
            DATE_FORMAT(date, '%d/%m/%Y') as date,
            DATE_FORMAT(date_beat, '%d/%m/%Y') as date_beat  
            FROM orderdata $whre ORDER BY idorder DESC";      
            $request = $this->select_all($sql);
            if(!empty($request)){
                for ($i=0; $i < count($request); $i++) { 
                    $total = $request[$i]['amount'];
                    $sql_det = "SELECT * FROM orderdetail WHERE orderid = {$request[$i]['idorder']}";
                    $request[$i]['detail']=$this->select_all($sql_det);
                    $request[$i]['total_pendent'] = 0;
                    if($request[$i]['type'] == "credito" || $request[$i]['status'] == "pendent"){
                        $sql_credit = "SELECT COALESCE(SUM(advance),0) as total_advance FROM order_advance WHERE order_id = {$request[$i]['idorder']}";
                        $advance = $this->select($sql_credit)['total_advance'];
                        $total = $total - $advance;
                        $request[$i]['total_pendent'] = $total;
                        $sql_advance = "SELECT det.order_id, det.type, det.advance,DATE_FORMAT(det.date,'%Y-%m-%d') as date,det.user,
                        CONCAT(u.firstname,' ',u.lastname) as user_name
                        FROM order_advance det 
                        INNER JOIN person u
                        ON det.user = u.idperson
                        WHERE det.order_id = {$request[$i]['idorder']}";
                        $request[$i]['detail_advance']= $this->select_all($sql_advance);
                        $request[$i]['total_advance'] = intval($advance);
                    }
                }
            }
            return $request;
        }
        public function selectCreditOrders($idPerson){
            $whre="";
            if($idPerson!="")$whre=" AND personid=$idPerson";
            $sql = "SELECT 
            idorder,
            idtransaction,
            name,
            identification,
            email,
            phone,
            amount,
            shipping,
            status,
            type,
            address,
            statusorder,
            coupon,
            note,
            send_by,
            number_guide,
            DATE_FORMAT(date, '%d/%m/%Y') as date,
            DATE_FORMAT(date_beat, '%d/%m/%Y') as date_beat  
            FROM orderdata WHERE (type = 'credito' OR status = 'pendent') $whre ORDER BY idorder DESC";      
            $request = $this->select_all($sql);
            if(!empty($request)){
                for ($i=0; $i < count($request); $i++) { 
                    $total = $request[$i]['amount'];
                    $sql_det = "SELECT * FROM orderdetail WHERE orderid = {$request[$i]['idorder']}";
                    $request[$i]['detail']=$this->select_all($sql_det);
                    $request[$i]['total_pendent'] = 0;

                    $sql_credit = "SELECT COALESCE(SUM(advance),0) as total_advance FROM order_advance WHERE order_id = {$request[$i]['idorder']}";
                    $advance = $this->select($sql_credit)['total_advance'];
                    $total = $total - $advance;
                    $request[$i]['total_pendent'] = $total;
                    $sql_advance = "SELECT det.order_id, det.type, det.advance,DATE_FORMAT(det.date,'%Y-%m-%d') as date,det.user,
                    CONCAT(u.firstname,' ',u.lastname) as user_name
                    FROM order_advance det 
                    INNER JOIN person u
                    ON det.user = u.idperson
                    WHERE det.order_id = {$request[$i]['idorder']}";
                    $request[$i]['detail_advance']= $this->select_all($sql_advance);
                    $request[$i]['total_advance'] = intval($advance);
                }
            }
            return $request;
        }
        public function selectDetailOrders($idPerson){
            $whre="";
            if($idPerson!="")$whre=" WHERE cab.personid=$idPerson";
            $sql = "SELECT 
            cab.idorder,
            cab.idtransaction,
            cab.name,
            cab.identification,
            DATE_FORMAT(cab.date, '%d/%m/%Y') as date,
            det.description,
            det.quantity,
            det.price,
            det.reference,
            det.topic
            FROM orderdata as cab  
            INNER JOIN orderdetail as det ON cab.idorder = det.orderid
            $whre ORDER BY cab.idorder DESC";      
            $request = $this->select_all($sql);
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
            $sql = "SELECT * FROM orderdetail WHERE orderid  = $this->intIdOrder AND topic = 2";
            $request = $this->select_all($sql);
            $sql = "UPDATE orderdata SET status=?,statusorder =? WHERE idorder = $this->intIdOrder;DELETE FROM count_amount WHERE order_id = $this->intIdOrder";
            $return = $this->update($sql,array("canceled","anulado"));
            if(!empty($request)){
                foreach ($request as $e) {
                    $description = json_decode($e['description'],true);
                    if(is_array($description)){
                        $arrDet = $description['detail'];
                        $variantName = implode("-",array_values(array_column($arrDet,"option")));
                        $sqlProduct = "SELECT pv.stock,p.is_stock
                        FROM product_variations_options pv
                        INNER JOIN product p ON p.idproduct = pv.product_id
                        WHERE pv.name='$variantName' AND pv.product_id = $e[productid]";
                        $requestProduct = $this->select($sqlProduct);
                        if($requestProduct['is_stock']){
                            $stock = $requestProduct['stock']+$e['quantity'];
                            $this->update("UPDATE product_variations_options SET stock =? WHERE name='$variantName' AND product_id = $e[productid]",[$stock]);
                        }
                    }else{
                        $sqlProduct = "SELECT stock,is_stock FROM product WHERE idproduct = $e[productid]";
                        $requestProduct = $this->select($sqlProduct);
                        if($requestProduct['is_stock']){
                            $stock = $requestProduct['stock']+$e['quantity'];
                            $this->update("UPDATE product SET stock =? WHERE idproduct = $e[productid]",[$stock]);
                        }
                    }
                }
            }
            return $return;
        }
        public function updateOrder(int $id,string $statusOrder,string $strSendBy,string $strGuide){
            $sql = "UPDATE orderdata SET statusorder =?, send_by =?,number_guide =?  WHERE idorder = $id";
            $request = $this->update($sql,array($statusOrder,$strSendBy,$strGuide));
            return $request;
        }
        /*************************Advance methods*******************************/
        public function insertAdvance(int $id,array $data,bool $isSuccess){
            $this->intIdOrder = $id;
            $request = $this->delete("DELETE FROM order_advance WHERE order_id = $this->intIdOrder");
            $request = $this->delete("DELETE FROM count_amount WHERE order_id = $this->intIdOrder");
            if(!empty($data)){
                if($isSuccess){
                    $request = $this->update("UPDATE orderdata SET status=? WHERE idorder = $id",array("approved")); 
                }
                foreach ($data as $d) {
                    //Insert advance
                    $sql = "INSERT INTO order_advance(order_id,type,advance,date,user)
                    VALUES(?,?,?,?,?)";
                    $arrData = array($this->intIdOrder,$d['type'],$d['advance'],$d['date'],$d['user']);
                    $request = $this->insert($sql,$arrData);

                    //Insert income
                    if($isSuccess){
                        $this->insertIncome($this->intIdOrder,3,1,"Venta de artículos y/o servicios",$d['advance'],$d['date'],1,$d['type']);
                    }else{
                        $this->insertIncome($this->intIdOrder,3,3,"Abono a factura de venta",$d['advance'],$d['date'],1,$d['type']);
                    }
                }
            }
            return intval($request);
        }
        public function insertIncome(int $id,int $intType,int $intTopic,string $strName,int $intAmount,string $strDate,int $intStatus, string $method){
            $request="";
            
            $sql  = "INSERT INTO count_amount(order_id,type_id,category_id,name,amount,date,status,method) VALUES(?,?,?,?,?,?,?,?)";      
            $arrData = array(
                $id,
                $intType,
                $intTopic,
                $strName,
                $intAmount,
                $strDate,
                $intStatus,
                $method
            );
            $request = $this->insert($sql,$arrData);
	        return $request;
		}
    }
?>