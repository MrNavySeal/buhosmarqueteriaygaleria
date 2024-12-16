<?php 
    /*ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);*/
    class CotizacionesModel extends Mysql{
        private $intIdUser;
        private $intId;
        private $arrData;
        private $strDescription;
        private $strImg;
        public function __construct(){
            parent::__construct();
        }
        /*************************Category methods*******************************/
        public function selectTotalQuotes($idPerson, $strSearch,$strInitialDate,$strFinalDate){
            $whre="";
            if($idPerson!="")$whre=" AND personid=$idPerson";
            $sql = "SELECT COALESCE(COUNT(*),0) as total
            FROM quote_cab WHERE (id like '$strSearch%' OR  name like '$strSearch%'
            OR identification like '$strSearch%' OR email like '$strSearch%' OR phone like '$strSearch%' OR amount like '$strSearch%'
            OR status like '$strSearch%') AND date BETWEEN '$strInitialDate' AND '$strFinalDate' $whre";    
            $request = $this->select($sql)['total'];
            return $request;
        }
        public function selectQuotes($idPerson,string $strSearch,int $intPerPage,int $intPageNow,$strInitialDate,$strFinalDate){
            $start = ($intPageNow-1)*$intPerPage;
            $whre="";
            if($idPerson!="")$whre=" AND personid=$idPerson";
            $sql = "SELECT 
            id,
            name,
            identification,
            email,
            phone,
            amount,
            shipping,
            personid,
            status,
            address,
            note,
            discount,
            DATE_FORMAT(date, '%d/%m/%Y') as date,
            DATE_FORMAT(date_beat, '%d/%m/%Y') as date_beat,
            date_beat as compare
            FROM quote_cab WHERE (id like '$strSearch%' OR name like '$strSearch%'
            OR identification like '$strSearch%' OR email like '$strSearch%' OR phone like '$strSearch%' OR amount like '$strSearch%'
            OR status like '$strSearch%') AND date BETWEEN '$strInitialDate' AND '$strFinalDate' $whre 
            ORDER BY id DESC LIMIT $start,$intPerPage";      
            $request = $this->select_all($sql);

            $sqlTotal = "SELECT COALESCE(COUNT(*),0) as total
            FROM quote_cab WHERE (id like '$strSearch%' OR name like '$strSearch%'
            OR identification like '$strSearch%' OR email like '$strSearch%' OR phone like '$strSearch%' OR amount like '$strSearch%'
            OR status like '$strSearch%') AND date BETWEEN '$strInitialDate' AND '$strFinalDate' $whre";    
            $totalRecords = $this->select($sqlTotal)['total'];
            $totalPages = $totalRecords > 0 ? ceil($totalRecords/$intPerPage) : 0;  
            if(!empty($request)){
                for ($i=0; $i < count($request); $i++) { 
                    $sql_det = "SELECT * FROM quote_det WHERE quote_id = {$request[$i]['id']}";
                    $request[$i]['detail']=$this->select_all($sql_det);
                }
            }
            return  array("data"=>$request,"pages"=>$totalPages);
        }
        public function selectQuote(int $id){
            $sql = "SELECT * FROM quote_cab WHERE id = $id";
            $request = $this->select($sql);
            if(!empty($request)){
                $sql_det = "SELECT q.product_id,q.topic,q.description,q.qty,q.price,q.reference,p.product_type,p.is_stock
                FROM quote_det q LEFT JOIN product p ON p.idproduct = q.product_id WHERE q.quote_id = $id";
                $request['detail']=$this->select_all($sql_det);
            }
            return $request;
        }
        public function insertOrder(int $id,array $data){
            $this->intId = $id;
            //Insert header
            $sql = "INSERT INTO orderdata(personid,name,identification,email,phone,address,note,amount,date,status,coupon,type,statusorder,date_beat) 
            VALUE(?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $arrData = array(
                $data['personid'],
                $data['name'],
                $data['identification'],
                $data['email'],
                $data['phone'],
                $data['address'],
                $data['note'],
                $data['amount'],
                $data['date'],
                "pendent",
                $data['discount'],
                "credito",
                "confirmado",
                $data['date_beat']
            );
            $request = $this->insert($sql,$arrData);
            //Insert detail
            if($request > 0){
                $this->update("UPDATE quote_cab SET status = ? WHERE id = $this->intId",["facturado"]);
                $this->insertOrderDet($request,$data['personid'],$data['detail'],$data['name'],$data['address']);
            }
            return $request;
        }
        public function insertOrderDet(int $id,int $idCustom,array $data,string $customer,string $address){
            $this->intIdUser = $idCustom;
            $this->intId = $id;
            $this->arrData = $data;
            $strAddress = explode("/",$address)[1];
            $total = count($this->arrData);
            for ($i=0; $i < $total ; $i++) { 
                $this->strDescription = $this->arrData[$i]['description'];
                if($this->arrData[$i]['topic'] == 1){
                    $arrFrame = json_decode($this->strDescription,true);
                    if($arrFrame['img'] != "" || $arrFrame['img'] != null){
                        $imgData = $arrFrame['img'];
                        list($type,$imgData) = explode(";",$imgData);
                        list(,$imgData)=explode(",",$imgData);
                        $img = base64_decode($imgData);
                        $name = "frame_print_".bin2hex(random_bytes(6))."_".$this->intId.'.png';
                        $route = "Assets/images/uploads/".$name;
                        $this->strImg = $name;
                        file_put_contents($route, $img);
                    }
                    $this->strDescription = json_encode(
                        array("name"=>$arrFrame['name'],"detail"=>$arrFrame['detail'],"img"=>$this->strImg),
                        JSON_UNESCAPED_UNICODE
                    );
                }
                //Update products
                if($this->arrData[$i]['topic'] == 2){
                    $sqlStock = "SELECT stock FROM product WHERE idproduct = {$this->arrData[$i]['product_id']}";
                    //$sqlPurchase = "SELECT AVG(price) as price_purchase FROM orderdetail WHERE product_id = {$this->arrData[$i]['id']}";
                    $sqlProduct ="UPDATE product SET stock=? 
                    WHERE idproduct = {$this->arrData[$i]['product_id']}";

                    if($this->arrData[$i]['product_type']){
                        $variants = json_decode($this->strDescription,true)['detail'];
                        $arrCombination = [];
                        foreach ($variants as $v) {
                            array_push($arrCombination,$v['option']);
                        }
                        $variantName=implode("-",$arrCombination);
                        $sqlStock = "SELECT stock FROM product_variations_options WHERE product_id = {$this->arrData[$i]['product_id']} AND name = '$variantName'";
                        $sqlProduct = "UPDATE product_variations_options SET stock=?
                        WHERE product_id = {$this->arrData[$i]['product_id']} AND name = '$variantName'";
                        /*$sqlPurchase = "SELECT AVG(price) as price_purchase
                        FROM purchase_det 
                        WHERE product_id = {$this->arrData[$i]['id']} 
                        AND variant_name = '{$this->arrData[$i]['variant_name']}' ";*/
                    } 
                    $stock = $this->select($sqlStock)['stock'];
                    $stock = $stock - $this->arrData[$i]['qty'];
                    //$price_purchase = $this->select($sqlPurchase)['price_purchase'];
                    $arrData = array($this->arrData[$i]['is_stock'] ? $stock : 0);
                    $this->update($sqlProduct,$arrData);
                }

                $sql = "INSERT INTO orderdetail(orderid,personid,productid,topic,description,quantity,price,reference) VALUE(?,?,?,?,?,?,?,?)";
                $arrData = array(
                    $id,
                    $this->intIdUser,
                    $this->arrData[$i]['product_id'],
                    $this->arrData[$i]['topic'],
                    $this->strDescription,
                    $this->arrData[$i]['qty'],
                    $this->arrData[$i]['price'],
                    $this->arrData[$i]['reference']
                );
                $this->insert($sql,$arrData);
            }
        }
        public function updateOrder(int $id,string $statusOrder,string $strSendBy,string $strGuide){
            $sql = "UPDATE orderdata SET statusorder =?, send_by =?,number_guide =?  WHERE idorder = $id";
            $request = $this->update($sql,array($statusOrder,$strSendBy,$strGuide));
            return $request;
        }
    }
?>