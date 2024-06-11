<?php 
    class ProductosModel extends Mysql{
        private $intIdCategory;
        private $intIdSubCategory;
        private $intIdProduct;
        private $strReference;
		private $strName;
        private $strDescription;
        private $strShortDescription;
        private $intPrice;
        private $intDiscount;
        private $intStock;
		private $intStatus;
        private $strRoute;
        private $strFramingImg;
        private $intFramingMode;
        private $intProductType;
        private $strSpecifications;
        private $intIdMeasure;
        private $arrData;
        public function __construct(){
            parent::__construct();
        }
        /*************************Productos methods*******************************/
        public function insertProduct(array $data){
            $this->arrData = $data;
            $name = $this->arrData['name'];
			$return = 0;
            $reference="";
            if($this->arrData['reference']){
                $reference = $this->arrData['reference'];
                $reference = "AND reference = '$reference'";
            }
			$sql = "SELECT * FROM product WHERE name='$name' $reference";
			$request = $this->select_all($sql);
            
			if(empty($request)){
                $sql =  "INSERT INTO product(categoryid,
                subcategoryid,reference,name,shortdescription,description,measure,
                price,price_purchase,discount,stock,min_stock,status,route,
                framing_mode,framing_img,product_type,import,is_product,is_ingredient,is_combo,is_stock) 
                VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                
	        	$arrData = array(
                    $this->arrData['category'],
                    $this->arrData['subcategory'],
                    $this->arrData['reference'],
                    $this->arrData['name'],
                    $this->arrData['short_description'],
                    $this->arrData['description'],
                    $this->arrData['measure'],
                    $this->arrData['price_sell'],
                    $this->arrData['price_purchase'],
                    $this->arrData['price_offer'],
                    $this->arrData['stock'],
                    $this->arrData['min_stock'],
                    $this->arrData['status'],
                    $this->arrData['route'],
                    $this->arrData['framing_mode'],
                    $this->arrData['photo_framing'],
                    $this->arrData['product_type'],
                    $this->arrData['import'],
                    $this->arrData['is_product'],
                    $this->arrData['is_ingredient'],
                    $this->arrData['is_combo'],
                    $this->arrData['is_stock']
        		);
	        	$request = $this->insert($sql,$arrData);
                $this->insertImages($request,$this->arrData['images']);
                $this->insertSpecs($request,$this->arrData['specs']);
                $this->insertVariants($request,$this->arrData['variants']);
	        	$return = intval($request);
			}else{
				$return = "exist";
			}
	        return $return;
		}
        public function updateProduct(int $idProduct,array $data){
            $this->intIdProduct = $idProduct;
            $this->arrData = $data;
            $return = 0;
            $reference="";
            if($this->strReference!=""){
                $reference = "AND reference = '$this->strReference' AND name = '{$this->strName}' AND idproduct != $this->intIdProduct";
            }

			$sql = "SELECT * FROM product WHERE name = '{$this->strName}' AND idproduct != $this->intIdProduct $reference";
			$request = $this->select_all($sql);
			if(empty($request)){
                

                $sql = "UPDATE product SET categoryid=?,
                subcategoryid=?,reference=?,name=?,shortdescription=?,description=?,measure=?,
                price=?,price_purchase=?,discount=?,stock=?,min_stock=?,status=?,route=?,
                framing_mode=?,framing_img=?,product_type=?,import=?,is_product=?,is_ingredient=?,is_combo=?,is_stock=?
                WHERE idproduct = $this->intIdProduct";
                $arrData = array(
                    $this->arrData['category'],
                    $this->arrData['subcategory'],
                    $this->arrData['reference'],
                    $this->arrData['name'],
                    $this->arrData['short_description'],
                    $this->arrData['description'],
                    $this->arrData['measure'],
                    $this->arrData['price_sell'],
                    $this->arrData['price_purchase'],
                    $this->arrData['price_offer'],
                    $this->arrData['stock'],
                    $this->arrData['min_stock'],
                    $this->arrData['status'],
                    $this->arrData['route'],
                    $this->arrData['framing_mode'],
                    $this->arrData['photo_framing'],
                    $this->arrData['product_type'],
                    $this->arrData['import'],
                    $this->arrData['is_product'],
                    $this->arrData['is_ingredient'],
                    $this->arrData['is_combo'],
                    $this->arrData['is_stock']
        		);
				$request = $this->update($sql,$arrData);
                if(!empty($this->arrData['images'])){
                    $this->deleteImages($this->intIdProduct);
                    $this->insertImages($this->intIdProduct,$this->arrData['images']);
                }
                $this->insertSpecs($this->intIdProduct,$this->arrData['specs']);
                $this->insertVariants($this->intIdProduct,$this->arrData['variants']);
                $return = intval($request);
			}else{
				$return = "exist";
			}
			return $return;
		
		}
        public function deleteProduct($id){
            $this->intIdProduct = $id;
            $images = $this->selectImages($this->intIdProduct);
            for ($i=0; $i < count($images) ; $i++) { 
                if($images[$i]['name'] != "category.jpg"){
                    deleteFile($images[$i]['name']);
                }
            }
            $sql = "DELETE FROM product WHERE idproduct = $this->intIdProduct";
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
                p.price_purchase,
                p.discount,
                p.description,
                p.stock,
                p.status,
                p.product_type,
                p.route,
                p.is_stock,
                p.is_stock,
                p.is_product,
                p.is_ingredient,
                p.is_combo,
                c.idcategory,
                c.name as category,
                s.idsubcategory,
                s.categoryid,
                s.name as subcategory,
                DATE_FORMAT(p.date, '%d/%m/%Y') as date
            FROM product p
            INNER JOIN category c, subcategory s
            WHERE c.idcategory = p.categoryid AND c.idcategory = s.categoryid AND p.subcategoryid = s.idsubcategory
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
                    if($request[$i]['product_type'] == 1){
                        $sqlV = "SELECT MIN(price_sell) AS sell,MIN(price_offer) AS offer,MIN(price_purchase) AS purchase
                        FROM product_variations_options WHERE product_id =$idProduct";
                        $requestPrices = $this->select($sqlV);
                        $sqlTotal = "SELECT SUM(stock) AS total FROM product_variations_options WHERE product_id =$idProduct";
                        $request[$i]['price_purchase'] = $requestPrices['purchase'];
                        $request[$i]['price'] = $requestPrices['sell'];
                        $request[$i]['discount'] = $requestPrices['offer'];
                        $request[$i]['stock'] = $this->select($sqlTotal)['total'];
                    }
                }
            }
            return $request;
        }
        public function selectProduct($id){
            $this->intIdProduct = $id;
            $sql = "SELECT 
                p.idproduct,
                p.categoryid,
                p.subcategoryid,
                p.reference,
                p.name,
                p.measure,
                p.import,
                p.framing_mode,
                p.framing_img,
                p.shortdescription,
                p.price,
                p.price_purchase,
                p.discount,
                p.description,
                p.stock,
                p.min_stock,
                p.status,
                p.product_type,
                p.route,
                p.is_stock,
                p.is_product,
                p.is_ingredient,
                p.is_combo,
                c.idcategory,
                c.name as category,
                s.idsubcategory,
                s.categoryid,
                s.name as subcategory,
                DATE_FORMAT(p.date, '%d/%m/%Y') as date
            FROM product p
            INNER JOIN category c, subcategory s
            WHERE c.idcategory = p.categoryid AND c.idcategory = s.categoryid AND p.subcategoryid = s.idsubcategory
            AND p.idproduct = $this->intIdProduct";
            $request = $this->select($sql);
            $request['framing_img'] = media()."/images/uploads/".$request['framing_img'];
            if(!empty($request)){
                $sqlImg = "SELECT * FROM productimage WHERE productid = $this->intIdProduct";
                $requestImg = $this->select_all($sqlImg);
                if(count($requestImg)){
                    for ($i=0; $i < count($requestImg); $i++) { 
                        $request['image'][$i] = array("url"=>media()."/images/uploads/".$requestImg[$i]['name'],"name"=>$requestImg[$i]['name'],"rename"=>$requestImg[$i]['name']);
                    }
                }
                $sqlSpecs = "SELECT p.specification_id as id,p.value,s.name
                FROM product_specs p
                INNER JOIN specifications s
                ON p.specification_id = s.id_specification
                WHERE p.product_id = $this->intIdProduct";
                $request['specs'] = $this->select_all($sqlSpecs);
                if($request['product_type'] == 1){
                    $request['variation'] = $this->select("SELECT * FROM product_variations WHERE product_id = $this->intIdProduct");
                    $request['variation']['variation'] = json_decode($request['variation']['variation']);
                    $request['options'] = $this->select_all("SELECT * FROM product_variations_options WHERE product_id = $this->intIdProduct");
                }
            }
            return $request;
        }
        public function insertVariants($id,$data){
            $this->intIdProduct = $id;
            $this->delete("DELETE FROM product_variations WHERE product_id=$this->intIdProduct");

            $combinations = $data['combinations'];
            $variations = json_encode($data['variations'],JSON_UNESCAPED_UNICODE);
            $sql = "INSERT INTO product_variations(product_id,variation) VALUES (?,?)";
            $request_insert = $this->insert($sql,array($id,$variations));
            $total_combinations = count($combinations);
            for ($i=0; $i < $total_combinations ; $i++) { 
                $sql = "INSERT INTO product_variations_options(product_variation_id,product_id,name,price_purchase,price_sell,price_offer,stock,min_stock,sku,status) 
                VALUES(?,?,?,?,?,?,?,?,?,?)";
                $arrData = array(
                    $request_insert,
                    $this->intIdProduct,
                    $combinations[$i]['name'],
                    $combinations[$i]['price_purchase'],
                    $combinations[$i]['price_sell'],
                    $combinations[$i]['price_offer'],
                    $combinations[$i]['stock'],
                    $combinations[$i]['min_stock'],
                    $combinations[$i]['sku'],
                    $combinations[$i]['status']
                );
                $request = $this->insert($sql,$arrData);
            }
        }
        public function insertImages($id,$photos){
            for ($i=0; $i < count($photos) ; $i++) { 
                $sqlImg = "INSERT INTO productimage(productid,name) VALUES(?,?)";
                $arrImg = array($id,$photos[$i]);
                $requestImg = $this->insert($sqlImg,$arrImg);
            }
        }
        public function insertSpecs($id,$specs){
            $this->intIdProduct = $id;
            $this->delete("DELETE FROM product_specs WHERE product_id=$this->intIdProduct");
            if(!empty($specs)){
                $total = count($specs);
                for ($i=0; $i < $total ; $i++) { 
                    $sql = "INSERT INTO product_specs(product_id,specification_id,value) VALUES(?,?,?)";
                    $arrData = array($id,$specs[$i]['id'],$specs[$i]['value']);
                    $this->insert($sql,$arrData);
                }
            }
        }
        public function selectImages($id){
            $this->intIdProduct = $id;
            $sql = "SELECT * FROM productimage WHERE productid=$this->intIdProduct";
            $request = $this->select_all($sql);
            for ($i=0; $i < count($request); $i++) { 
                $request[$i]['rename'] = $request[$i]['name'];
            }
            return $request;
        }
        public function deleteImages($id){
            $this->intIdProduct = $id;
            $sql = "DELETE FROM productimage WHERE productid=$this->intIdProduct";
            $request = $this->delete($sql);
            return $request;
        }
        /*************************Temp methods*******************************/
        public function updateTempProduct(int $idProduct,array $data){
            $this->intIdProduct = $idProduct;
            $this->arrData = $data;
            if(isset($this->arrData['specs']) && !empty($this->arrData['specs'])){
                $this->insertSpecs($this->intIdProduct,$this->arrData['specs']);
            }
            if(isset($this->arrData['variants']) && !empty($this->arrData['variants'])){
                $this->insertVariants($this->intIdProduct,$this->arrData['variants']);
            }
		}
        public function insertOptions($id,$data){
            $total = count($data);
            $sql = "DELETE FROM variation_options WHERE variation_id = $id; SET @autoid :=0; 
            UPDATE variation_options SET id_options = @autoid := (@autoid+1);
            ALTER TABLE variation_options Auto_Increment = 1;";
            $this->delete($sql);
            for ($i=0; $i < $total; $i++) { 
                $sql = "INSERT INTO variation_options(variation_id,name) VALUES (?,?)";
                $arrData = array($id,ucwords($data[$i]));
                $this->insert($sql,$arrData);
            }
        }
        public function selectTempProducts(){
            $sql = "SELECT * FROM product";
            $request = $this->select_all($sql);
            if(!empty($request)){
                $total = count($request);
                for ($i=0; $i < $total ; $i++) { 
                    $id = $request[$i]['idproduct'];
                    $req = $this->select_all("SELECT * FROM product_variant WHERE productid = $id");
                    if(!empty($req)){
                        $request[$i]['variants'] = $req; 
                    }
                }
            }
            return $request;
        }
        public function selectTempSpec($name){
            $sql = "SELECT id_specification FROM specifications WHERE name= '$name'";
            $request = $this->select($sql)['id_specification'];
            return $request;
        }
        /*************************Other methods*******************************/
        public function selectSpecs(){
            $sql = "SELECT *,id_specification as id FROM specifications WHERE status =1 ORDER BY name";
            $request = $this->select_all($sql);
            return $request;
        }
        public function selectCategories(){
            $sql = "SELECT *,idcategory as id FROM category WHERE status = 1 ORDER BY name";       
            $request = $this->select_all($sql);
            return $request;
        }
        public function selectMeasures(){
            $sql = "SELECT id_measure as id, CONCAT(initials,' - ',name) as name, status FROM measures WHERE status = 1 ORDER BY name ";
            $request = $this->select_all($sql);
            return $request;
        }
        public function selectVariants(){
            $sql = "SELECT *, id_variation as id FROM variations WHERE status = 1";
            $request = $this->select_all($sql);
            if(!empty($request)){
                $total = count($request);
                for ($i=0; $i < $total; $i++) { 
                    $id = $request[$i]['id_variation'];
                    $sql ="SELECT * FROM variation_options WHERE variation_id = $id";
                    $request[$i]['options'] = $this->select_all($sql);
                }
            }
            return $request;
        }
        public function getSelectSubcategories(int $intIdCategory){
            $this->intIdCategory = $intIdCategory;
            $sql = "SELECT  
                    s.idsubcategory as id,
                    s.name,
                    s.categoryid,
                    c.idcategory,
                    c.name as category
                    FROM subcategory s
                    INNER JOIN category c
                    ON c.idcategory = s.categoryid
                    WHERE s.categoryid = $this->intIdCategory AND c.status = 1
                    ORDER BY s.name ASC";       
            $request = $this->select_all($sql);
            return $request;
        }
    }
?>