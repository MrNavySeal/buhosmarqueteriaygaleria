<?php 
    /*ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);*/
    class PedidosModel extends Mysql{
        private $intIdOrder;
        private $intIdUser;
        private $intIdTransaction;
        private $strCoupon;
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

        public function selectTotalDetailOrders($idPerson, $strSearch,$strInitialDate,$strFinalDate){
            $whre="";
            if($idPerson!="")$whre=" AND personid=$idPerson";
            $sql = "SELECT COALESCE(COUNT(*),0) as total
            FROM orderdata as cab  
            INNER JOIN orderdetail as det ON cab.idorder = det.orderid
            WHERE (det.description like '$strSearch%' OR det.price like '$strSearch%' OR det.reference like '$strSearch%'
            OR cab.identification like '$strSearch%' OR cab.name like '$strSearch%' OR cab.name like '$strSearch%'
            OR cab.idtransaction like '$strSearch%' OR cab.idorder like '$strSearch%' ) AND cab.date BETWEEN '$strInitialDate' AND '$strFinalDate'
            $whre";       
            $request = $this->select($sql)['total'];
            return $request;
        }

        public function selectOrders($idPerson,string $strSearch,int $intPerPage,int $intPageNow,$strInitialDate,$strFinalDate,$strStatusOrder,$strStatusPayment){
            $start = ($intPageNow-1)*$intPerPage;
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
            FROM orderdata WHERE (idorder like '$strSearch%' OR idtransaction like '$strSearch%' OR name like '$strSearch%'
            OR identification like '$strSearch%' OR email like '$strSearch%' OR phone like '$strSearch%' OR amount like '$strSearch%'
            OR type like '$strSearch%') AND DATE(date) BETWEEN '$strInitialDate' AND '$strFinalDate' 
            AND statusorder like '$strStatusOrder%' AND status like '$strStatusPayment%' $whre 
            ORDER BY idorder DESC LIMIT $start,$intPerPage";      
            $request = $this->select_all($sql);

            $sqlTotal = "SELECT 
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
            FROM orderdata WHERE (idorder like '$strSearch%' OR idtransaction like '$strSearch%' OR name like '$strSearch%'
            OR identification like '$strSearch%' OR email like '$strSearch%' OR phone like '$strSearch%' OR amount like '$strSearch%'
            OR type like '$strSearch%') AND DATE(date) BETWEEN '$strInitialDate' AND '$strFinalDate' 
            AND statusorder like '$strStatusOrder%' AND status like '$strStatusPayment%' $whre ORDER BY idorder DESC";    
            $requestFull = $this->select_all($sqlTotal);
            $totalRecords = count($requestFull);
            $totalPages = $totalRecords > 0 ? ceil($totalRecords/$intPerPage) : 0;  
            $maxButtons = 4;
            $page = $intPageNow;
            $startPage = max(1, $page - floor($maxButtons / 2));
            if ($startPage + $maxButtons - 1 > $totalPages) {
                $startPage = max(1, $totalPages - $maxButtons + 1);
            }
            $limitPages = min($startPage + $maxButtons, $totalPages + 1);
            
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
                for ($i=0; $i < count($requestFull); $i++) { 
                    $total = $requestFull[$i]['amount'];
                    $sql_det = "SELECT * FROM orderdetail WHERE orderid = {$requestFull[$i]['idorder']}";

                    $requestFull[$i]['detail']=$this->select_all($sql_det);
                    $requestFull[$i]['total_pendent'] = 0;
                    $requestFull[$i]['actual_user'] = $_SESSION['userData']['firstname']." ".$_SESSION['userData']['lastname'];
                    $requestFull[$i]['id_actual_user'] = $_SESSION['userData']['idperson'];
                    if($requestFull[$i]['type'] == "credito" || $requestFull[$i]['status'] == "pendent"){
                        $sql_credit = "SELECT COALESCE(SUM(advance),0) as total_advance FROM order_advance WHERE order_id = {$requestFull[$i]['idorder']}";
                        $advance = $this->select($sql_credit)['total_advance'];
                        $total = $total - $advance;
                        $requestFull[$i]['total_pendent'] = $total;
                        $sql_advance = "SELECT det.order_id, det.type, det.advance,DATE_FORMAT(det.date,'%Y-%m-%d') as date,det.user,
                        CONCAT(u.firstname,' ',u.lastname) as user_name
                        FROM order_advance det 
                        INNER JOIN person u
                        ON det.user = u.idperson
                        WHERE det.order_id = {$requestFull[$i]['idorder']}";
                        $requestFull[$i]['detail_advance']= $this->select_all($sql_advance);
                        $requestFull[$i]['total_advance'] = intval($advance);
                    }
                }
            }
            $arrResponse = array(
                "data"=>$request,
                "start_page"=>$startPage,
                "limit_page"=>$limitPages,
                "total_pages"=>$totalPages,
                "total_records"=>$totalRecords,
                "full_data"=>$requestFull
            ); 
            return  $arrResponse;
        }

        public function selectOrder(int $idOrder){
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
            FROM orderdata WHERE idorder = $idOrder";      
            $request = $this->select($sql);
            if(!empty($request)){
                $total = $request['amount'];
                $sql_det = "SELECT * FROM orderdetail WHERE orderid = $idOrder";

                $request['detail']=$this->select_all($sql_det);
                $request['total_pendent'] = 0;
                if($request['type'] == "credito" || $request['status'] == "pendent"){
                    $sql_credit = "SELECT COALESCE(SUM(advance),0) as total_advance FROM order_advance WHERE order_id = $idOrder";
                    $advance = $this->select($sql_credit)['total_advance'];
                    $total = $total - $advance;
                    $request['total_pendent'] = $total;
                    $sql_advance = "SELECT det.order_id, det.type, det.advance,DATE_FORMAT(det.date,'%d/%m/%Y') as date,det.user,
                    CONCAT(u.firstname,' ',u.lastname) as user_name
                    FROM order_advance det 
                    INNER JOIN person u
                    ON det.user = u.idperson
                    WHERE det.order_id = $idOrder";
                    $request['detail_advance']= $this->select_all($sql_advance);
                    $request['total_advance'] = intval($advance);
                }
            }
            return $request;
        }

        public function selectCreditOrders($idPerson,string $strSearch,int $intPerPage,int $intPageNow,$strInitialDate,$strFinalDate,$strStatusOrder,$strStatusPayment){
            $start = ($intPageNow-1)*$intPerPage;
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
            FROM orderdata WHERE (idorder like '$strSearch%' OR idtransaction like '$strSearch%' OR name like '$strSearch%'
            OR identification like '$strSearch%' OR email like '$strSearch%' OR phone like '$strSearch%' OR amount like '$strSearch%'
            OR type like '$strSearch%') AND date BETWEEN '$strInitialDate' AND '$strFinalDate'
            AND (type = 'credito' OR status = 'pendent') AND statusorder like '$strStatusOrder%' AND status like '$strStatusPayment%' $whre 
            ORDER BY idorder DESC LIMIT $start,$intPerPage";      
            $request = $this->select_all($sql);

            $sqlTotal = "SELECT 
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
            FROM orderdata WHERE (idorder like '$strSearch%' OR idtransaction like '$strSearch%' OR name like '$strSearch%'
            OR identification like '$strSearch%' OR email like '$strSearch%' OR phone like '$strSearch%' OR amount like '$strSearch%'
            OR type like '$strSearch%') AND date BETWEEN '$strInitialDate' AND '$strFinalDate'
            AND (type = 'credito' OR status = 'pendent') AND statusorder like '$strStatusOrder%' AND status like '$strStatusPayment%' $whre 
            ORDER BY idorder DESC";    
            $requestFull = $this->select_all($sqlTotal);
            $totalRecords = count($requestFull);
            $totalPages = $totalRecords > 0 ? ceil($totalRecords/$intPerPage) : 0;  
            $maxButtons = 4;
            $page = $intPageNow;
            $startPage = max(1, $page - floor($maxButtons / 2));
            if ($startPage + $maxButtons - 1 > $totalPages) {
                $startPage = max(1, $totalPages - $maxButtons + 1);
            }
            $limitPages = min($startPage + $maxButtons, $totalPages + 1);

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
                for ($i=0; $i < count($requestFull); $i++) { 
                    $total = $requestFull[$i]['amount'];
                    $sql_det = "SELECT * FROM orderdetail WHERE orderid = {$requestFull[$i]['idorder']}";

                    $requestFull[$i]['detail']=$this->select_all($sql_det);
                    $requestFull[$i]['total_pendent'] = 0;
                    $requestFull[$i]['actual_user'] = $_SESSION['userData']['firstname']." ".$_SESSION['userData']['lastname'];
                    $requestFull[$i]['id_actual_user'] = $_SESSION['userData']['idperson'];
                    if($requestFull[$i]['type'] == "credito" || $requestFull[$i]['status'] == "pendent"){
                        $sql_credit = "SELECT COALESCE(SUM(advance),0) as total_advance FROM order_advance WHERE order_id = {$requestFull[$i]['idorder']}";
                        $advance = $this->select($sql_credit)['total_advance'];
                        $total = $total - $advance;
                        $requestFull[$i]['total_pendent'] = $total;
                        $sql_advance = "SELECT det.order_id, det.type, det.advance,DATE_FORMAT(det.date,'%Y-%m-%d') as date,det.user,
                        CONCAT(u.firstname,' ',u.lastname) as user_name
                        FROM order_advance det 
                        INNER JOIN person u
                        ON det.user = u.idperson
                        WHERE det.order_id = {$requestFull[$i]['idorder']}";
                        $requestFull[$i]['detail_advance']= $this->select_all($sql_advance);
                        $requestFull[$i]['total_advance'] = intval($advance);
                    }
                }
            }
            $arrResponse = array(
                "data"=>$request,
                "start_page"=>$startPage,
                "limit_page"=>$limitPages,
                "total_pages"=>$totalPages,
                "total_records"=>$totalRecords,
                "full_data"=>$requestFull
            ); 
            return  $arrResponse;
        }

        public function selectDetailOrders($idPerson,string $strSearch,int $intPerPage,int $intPageNow,$strInitialDate,$strFinalDate){
            $start = ($intPageNow-1)*$intPerPage;
            $whre="";
            if($idPerson!="")$whre=" AND cab.personid=$idPerson";
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
            WHERE (det.description like '$strSearch%' OR det.price like '$strSearch%' OR det.reference like '$strSearch%'
            OR cab.identification like '$strSearch%' OR cab.name like '$strSearch%' OR cab.name like '$strSearch%'
            OR cab.idtransaction like '$strSearch%' OR cab.idorder like '$strSearch%' )  AND DATE(cab.date) BETWEEN '$strInitialDate' AND '$strFinalDate'
            $whre ORDER BY cab.idorder DESC LIMIT $start,$intPerPage";      
            $request = $this->select_all($sql);

            $sqlTotal = "SELECT COALESCE(COUNT(*),0) as total
            FROM orderdata as cab  
            INNER JOIN orderdetail as det ON cab.idorder = det.orderid
            WHERE (det.description like '$strSearch%' OR det.price like '$strSearch%' OR det.reference like '$strSearch%'
            OR cab.identification like '$strSearch%' OR cab.name like '$strSearch%' OR cab.name like '$strSearch%'
            OR cab.idtransaction like '$strSearch%' OR cab.idorder like '$strSearch%' )  AND DATE(cab.date) BETWEEN '$strInitialDate' AND '$strFinalDate'
            $whre";    
            $totalRecords = $this->select($sqlTotal)['total'];
            $totalPages = $totalRecords > 0 ? ceil($totalRecords/$intPerPage) : 0;  

            return  array("data"=>$request,"pages"=>$totalPages);
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
                HelperWarehouse::delMovement(HelperWarehouse::SALIDA_VENTA,$this->intIdOrder);
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