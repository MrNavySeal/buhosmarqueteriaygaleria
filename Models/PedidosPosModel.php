<?php 
    class PedidosPosModel extends Mysql{
        private $intIdProduct;
        private $intIdUser;
        private $intId;
        private $strDescription;
        private $strImg;
        private $arrData;
        private $arrProducts;
        private $arrCustomer;
        public function __construct(){
            parent::__construct();
        }
        /*************************methods to get products*******************************/
        public function selectProducts(){
            $sql = "SELECT 
                p.idproduct,
                p.reference,
                p.name,
                p.price,
                p.discount,
                p.stock,
                p.product_type,
                p.is_stock
            FROM product p
            INNER JOIN category c, subcategory s
            WHERE c.idcategory = p.categoryid AND c.idcategory = s.categoryid AND p.subcategoryid = s.idsubcategory
            AND (p.is_product =1 OR p.is_combo=1) AND p.status = 1 
            ORDER BY p.idproduct DESC
            ";
            $request = $this->select_all($sql);
            $arrProducts = [];
            if(count($request)> 0){
                for ($i=0; $i < count($request); $i++) { 
                    $isStock = $request[$i]['is_stock'];
                    $stock = $request[$i]['stock'];

                    $idProduct = $request[$i]['idproduct'];
                    $sqlImg = "SELECT * FROM productimage WHERE productid = $idProduct";
                    $requestImg = $this->select_all($sqlImg);
                    if(count($requestImg)>0){
                        $request[$i]['image'] = media()."/images/uploads/".$requestImg[0]['name'];
                    }else{
                        $request[$i]['image'] = media()."/images/uploads/image.png";
                    }
                    if($request[$i]['product_type'] == 1){
                        $stockCondition = $isStock ? " AND stock > 0" : "";
                        $sqlV = "SELECT MIN(price_sell) AS sell,MIN(price_offer) AS offer,MIN(price_purchase) AS purchase
                        FROM product_variations_options WHERE product_id =$idProduct $stockCondition";
                        $requestPrices = $this->select($sqlV);
                        $sqlTotal = "SELECT SUM(stock) AS total FROM product_variations_options WHERE product_id =$idProduct AND stock >= 0";
                        $stock = $this->select($sqlTotal)['total'];
                        $request[$i]['price_sell'] = $requestPrices['purchase'];
                        $request[$i]['price'] = $requestPrices['sell'];
                        $request[$i]['discount'] = $requestPrices['offer'];
                        $request[$i]['stock'] = $stock;
                    }
                    if(!$isStock || ($isStock && $stock > 0)){
                        array_push($arrProducts,$request[$i]);
                    }
                }
            }
            return $arrProducts;
        }
        public function selectProduct($id){
            $this->intIdProduct = $id;
            $sql = "SELECT 
                p.idproduct,
                p.name,
                p.reference,
                p.price as price_sell,
                p.discount as price_offer,
                p.product_type,
                p.is_stock,
                p.stock,
                p.import
            FROM product p
            INNER JOIN category c, subcategory s
            WHERE c.idcategory = p.categoryid AND c.idcategory = s.categoryid AND p.subcategoryid = s.idsubcategory
            AND (p.is_product =1 OR p.is_combo=1) AND p.status = 1 
            AND p.idproduct = $this->intIdProduct";
            $request = $this->select($sql);
            if(!empty($request)){
                if($request['product_type'] == 1){
                    $stockCondition = $request['is_stock'] ? " AND stock > 0" :"";
                    $sqlVariations = "SELECT * FROM product_variations WHERE product_id = $this->intIdProduct";
                    $sqlVarOptions ="SELECT * FROM product_variations_options WHERE product_id = $this->intIdProduct";
                    $request['variation'] = $this->select($sqlVariations);
                    $request['variation']['variation'] = json_decode($request['variation']['variation'],true);
                    $options = $this->select_all($sqlVarOptions);
                    $totalOptions = count($options);
                    for ($i=0; $i < $totalOptions ; $i++) {
                        $options[$i]['format_offer'] = "$".number_format($options[$i]['price_offer'],0,",",".");
                        $options[$i]['format_price'] = "$".number_format($options[$i]['price_sell'],0,",",".");
                    }
                    $request['options'] = $options;
                }
            }
            //dep($request);exit;
            return $request;
        }
        /*************************methods to get customers*******************************/
        public function selectCustomers(){
            $sql = "SELECT *,CONCAT(firstname,' ',lastname) as name FROM person WHERE roleid=2 AND status = 1 ORDER BY idperson DESC";
            $request = $this->select_all($sql);
            return $request;
        }
        public function selectCustomer($id){
            $this->intIdUser = $id;
            $sql = "SELECT 
                    p.idperson as id,
                    CONCAT(p.firstname,' ',p.lastname) as name,
                    p.email,
                    p.phone,
                    CONCAT(p.address,'/',t.name,'/',s.name,'/',c.name) as address,
                    p.identification,
                    c.name as country,
                    s.name as state,
                    t.name as city
                    FROM person p
                    INNER JOIN role r, countries c, states s,cities t 
                    WHERE c.id = p.countryid AND p.stateid = s.id AND t.id = p.cityid AND r.idrole = p.roleid AND p.idperson = $this->intIdUser
                    AND p.status = 1";
            $request = $this->select($sql);
            return $request;
        }
        /*************************methods to set order*******************************/
        public function insertOrder(array $data){
            $this->arrData = $data;
            $this->arrProducts = $data['products'];
            $this->arrCustomer = $data['customer'];
            $status = $this->arrData['type'] != "credito" ? "approved" : "pendent";
            //Insert header
            $sql = "INSERT INTO orderdata(personid,name,identification,email,phone,address,note,amount,date,status,coupon,type,statusorder,date_beat) 
            VALUE(?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $arrData = array(
                $this->arrCustomer['id'],
                clear_cadena($this->arrCustomer['name']),
                $this->arrCustomer['identification'],
                $this->arrCustomer['email'],
                $this->arrCustomer['phone'],
                $this->arrCustomer['address'],
                $this->arrData['note'],
                $this->arrData['total']['total'],
                $this->arrData['date'],
                $status,
                $this->arrData['total']['discount'],
                $this->arrData['type'],
                STATUS[$this->arrData['status_order']],
                $this->arrData['date_beat']
            );
            $request = $this->insert($sql,$arrData);
            //Insert detail
            if($request > 0){
                $this->insertOrderDet($request,$this->arrCustomer['id'],$this->arrProducts);
                //insert income
                if($data['type']!="credito"){
                    $this->insertIncome($request,3,1,"Venta de artículos y/o servicios",$data['total']['total'],
                    $data['date'],1,$data['type']);
                }
            }
            return $request;
        }
        public function insertOrderDet(int $id,int $idCustom,array $data){
            $this->intIdUser = $idCustom;
            $this->intId = $id;
            $this->arrData = $data;

            $total = count($this->arrData);
            for ($i=0; $i < $total ; $i++) { 
                $this->strDescription = $this->arrData[$i]['product_type'] == 1 ? json_encode($this->arrData[$i]['variant_detail']) : $this->arrData[$i]['name'];
                if($this->arrData[$i]['topic'] == 1){
                    //dep($arrImg);
                    if($this->arrData[$i]['img'] != ""){
                        $imgData = $this->arrData[$i]['img'];
                        list($type,$imgData) = explode(";",$imgData);
                        list(,$imgData)=explode(",",$imgData);
                        $img = base64_decode($imgData);
                        $name = "frame_print_".bin2hex(random_bytes(6))."_".$this->intId.'.png';
                        $route = "Assets/images/uploads/".$name;
                        $this->strImg = $name;
                        file_put_contents($route, $img);
                    }
                    $this->strDescription = json_encode(
                        array("name"=>$this->arrData[$i]['name'],"detail"=>$this->arrData[$i]['data'],"img"=>$this->strImg),
                        JSON_UNESCAPED_UNICODE
                    );
                }
                $sql = "INSERT INTO orderdetail(orderid,personid,productid,topic,description,quantity,price,reference) VALUE(?,?,?,?,?,?,?,?)";
                $arrData = array(
                    $id,
                    $this->intIdUser,
                    $this->arrData[$i]['id'],
                    $this->arrData[$i]['topic'],
                    $this->strDescription,
                    $this->arrData[$i]['qty'],
                    $this->arrData[$i]['price_sell'],
                    $this->arrData[$i]['reference']
                );
                $this->insert($sql,$arrData);
                //Update products
                if($this->arrData[$i]['topic'] == 2){
                    $sqlStock = "SELECT stock FROM product WHERE idproduct = {$this->arrData[$i]['id']}";
                    //$sqlPurchase = "SELECT AVG(price) as price_purchase FROM orderdetail WHERE product_id = {$this->arrData[$i]['id']}";
                    $sqlProduct ="UPDATE product SET stock=? 
                    WHERE idproduct = {$this->arrData[$i]['id']}";

                    if($this->arrData[$i]['product_type']){
                        $sqlStock = "SELECT stock FROM product_variations_options WHERE product_id = {$this->arrData[$i]['id']} AND name = '{$this->arrData[$i]['variant_name']}'";
                        
                        $sqlProduct = "UPDATE product_variations_options SET stock=?
                        WHERE product_id = {$this->arrData[$i]['id']} AND name = '{$this->arrData[$i]['variant_name']}'";
                        /*$sqlPurchase = "SELECT AVG(price) as price_purchase
                        FROM purchase_det 
                        WHERE product_id = {$this->arrData[$i]['id']} 
                        AND variant_name = '{$this->arrData[$i]['variant_name']}' ";*/
                    } 
                    $stock = $this->select($sqlStock)['stock'];
                    $stock = $stock -$this->arrData[$i]['qty'];
                    //$price_purchase = $this->select($sqlPurchase)['price_purchase'];
                    $arrData = array($this->arrData[$i]['is_stock'] ? $stock : 0);
                    $this->update($sqlProduct,$arrData);
                }
            }
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
        /*************************Molding methods*******************************/
        public function selectMoldingCategories(){
            $sql = "SELECT * FROM moldingcategory WHERE status = 1 ORDER BY id ASC";       
            $request = $this->select_all($sql);
            return $request;
        }
        public function selectConfig(int $id){
            $this->intId = $id;
            $sql = "SELECT 
            co.id,
            co.category_id,
            co.is_frame,
            co.is_print,
            co.is_cost,
            co.img,
            c.name
            FROM molding_config co
            INNER JOIN moldingcategory c ON c.id = co.category_id
            WHERE co.category_id = $this->intId";
            $request = $this->select($sql);
            if(!empty($request)){
                $request_frames = $this->selectConfigFrame($request['id']);
                if(!empty($request_frames)){
                    $total = count($request_frames);
                    for ($i=0; $i < $total; $i++) { 
                        $arrFrames = $request_frames[$i]['frames'];
                        $totalFrames = count($arrFrames);
                        for ($j=0; $j < $totalFrames; $j++) { 
                            $arrFrames[$j]['framing_img'] = "url(".media().'/images/uploads/'.$arrFrames[$j]['framing_img'].") 40% repeat";
                        }
                        $request_frames[$i]['frames'] = $arrFrames;
                    }
                }
                $request['detail']['molding'] = $request_frames;
                $request['detail']['props'] = $this->selectConfigProps($request['id']);
                $request['url'] = media()."/images/uploads/".$request['img'];
            }
            return $request;
        }
        public function selectConfigFrame(int $id){
            $this->intId = $id;
            $sql = "SELECT 
            f.topic, 
            f.prop, 
            f.is_check,
            s.name,
            s.idsubcategory
            FROM molding_config_frame f 
            INNER JOIN subcategory s ON f.prop = s.idsubcategory
            WHERE f.topic = 1 AND f.config_id = $this->intId AND f.is_check = 1 ORDER BY s.name";
            $request= $this->select_all($sql);
            if(!empty($request)){
                $total = count($request);
                for ($i=0; $i < $total ; $i++) { 
                    $sql = "SELECT 
                    idproduct,
                    reference,
                    name,
                    price,
                    price_purchase,
                    discount,
                    stock,
                    status,
                    product_type,
                    is_stock,
                    subcategoryid,
                    framing_img
                    FROM product p
                    WHERE subcategoryid = {$request[$i]['prop']} AND status = 1
                    ORDER BY p.idproduct DESC
                    ";
                    $arrProducts = $this->select_all($sql);
                    $totalProducts = count($arrProducts);
                    for ($j=0; $j < $totalProducts; $j++) { 
                        $idProduct = $arrProducts[$j]['idproduct'];
                        $sqlImg = "SELECT * FROM productimage WHERE productid = $idProduct";
                        $requestImg = $this->select_all($sqlImg);
                        $totalImg = count($requestImg);
                        for ($k=0; $k < $totalImg; $k++) { 
                            $requestImg[$k]['image'] = media()."/images/uploads/".$requestImg[$k]['name'];
                        }
                        $sqlWaste = "SELECT 
                        p.value as waste 
                        FROM product_specs p
                        INNER JOIN specifications s ON p.specification_id = s.id_specification
                        WHERE p.product_id = $idProduct";
                        $arrProducts[$j]['waste'] = $this->select($sqlWaste)['waste'];
                        $arrProducts[$j]['images'] = $requestImg;
                        $arrProducts[$j]['image'] = $requestImg[0]['image'];
                    }
                    usort($arrProducts,function($a,$b){return $b['waste'] < $a['waste'];});
                    $request[$i]['frames'] = $arrProducts;
                }
            }
            return $request;
        }
        public function selectConfigProps(int $id){
            $this->intId = $id;
            $sql = "SELECT 
            f.topic, 
            f.prop, 
            f.is_check,
            p.name
            FROM molding_config_frame f 
            INNER JOIN molding_props p ON f.prop = p.id
            WHERE f.config_id = $this->intId AND f.topic = 2 AND f.is_check = 1 
            AND p.status = 1 ORDER BY p.order_view";
            $request= $this->select_all($sql);
            if(!empty($request)){
                $total = count($request);
                for ($i=0; $i < $total; $i++) { 
                    $sql = "SELECT * FROM molding_options WHERE status = 1 AND prop_id = {$request[$i]['prop']} ORDER BY order_view";
                    $sql_framing = "SELECT 
                    s.name,
                    f.framing_id
                    FROM molding_props_framing f
                    INNER JOIN subcategory s ON f.framing_id = s.idsubcategory
                    WHERE f.prop_id = {$request[$i]['prop']} AND is_check = 1";

                    $request[$i]['options'] = $this->select_all($sql);
                    $arrPropsConfig = $this->select_all($sql_framing);
                    for ($j=0; $j < count($arrPropsConfig) ; $j++) { 
                        $arrPropsConfig[$j]['attribute'] = 'data-'.$j.'="'.$arrPropsConfig[$j]['name'].'"';
                    }
                    $request[$i]['attributes'] = $arrPropsConfig;
                }
            }
            return $request;
        }
        public function selectColors(){
            $sql = "SELECT * FROM moldingcolor WHERE status = 1 ORDER BY id DESC";       
            $request = $this->select_all($sql);
            return $request;
        }
    }
?>