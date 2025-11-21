<?php 
    class ProductosModel extends Mysql{
        private $intIdCategory;
        private $intIdProduct;
        private $strReference;
		private $strName;
        private $arrData;
        public function __construct(){
            parent::__construct();
        }
        /*************************Productos methods*******************************/
        public function insertProduct(array $data,$images){
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
                    $this->arrData['is_stock'],
        		);
	        	$request = $this->insert($sql,$arrData);
                $this->intIdProduct = $request;
                $this->insertImages($request,$images);
                $this->insertSpecs($request,$this->arrData['specs']);
                $this->insertVariants($request,$this->arrData['variants']);
                $this->insertIngredients();
                $this->insertWholesalePrice();
	        	$return = intval($request);
			}else{
				$return = "exist";
			}
	        return $return;
		}

        public function updateProduct(int $idProduct,array $data,array $images){
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
                    $this->arrData['is_stock'],
        		);
				$request = $this->update($sql,$arrData);
                if(!empty($this->arrData['images'])){
                    $this->insertImages($this->intIdProduct,$this->arrData['images'],false);
                }else{
                    $this->deleteImages($this->intIdProduct);
                }
                if(!empty($images)){
                    $this->insertImages($this->intIdProduct,$images);
                }
                $this->insertSpecs($this->intIdProduct,$this->arrData['specs']);
                $this->insertVariants($this->intIdProduct,$this->arrData['variants']);
                $this->insertIngredients();
                $this->insertWholesalePrice();
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

        public function selectTotalProducts(string $strSearch){
            $sql = "SELECT COALESCE(COUNT(*),0) as total
            FROM product p
            INNER JOIN category c ON c.idcategory = p.categoryid
            INNER JOIN subcategory s ON p.subcategoryid = s.idsubcategory
            WHERE c.idcategory = s.categoryid AND (s.name like '$strSearch%' OR c.name like '$strSearch%' OR p.name like '$strSearch%')"; 
            $request = $this->select($sql)['total'];
            return $request;
        }

        public function selectProductos($intPage,$intPerPage,$strSearch){
            $limit ="";
            $intStartPage = ($intPage-1)*$intPerPage;
            if($intPerPage != 0){
                $limit = " LIMIT $intStartPage,$intPerPage";
            }
            $sql = "SELECT 
                p.idproduct,
                p.categoryid,
                p.subcategoryid,
                p.reference,
                p.name,
                p.description,
                p.price as price_sell,
                p.price_purchase,
                p.discount as price_discount,
                p.description,
                p.stock,
                p.status,
                p.product_type,
                p.route,
                p.is_stock,
                p.is_product,
                p.is_ingredient,
                p.is_combo,
                c.idcategory,
                c.is_visible as visible_category,
                c.name as category,
                s.idsubcategory,
                s.categoryid,
                s.name as subcategory,
                DATE_FORMAT(p.date, '%d/%m/%Y') as date
            FROM product p
            INNER JOIN category c ON c.idcategory = p.categoryid
            INNER JOIN subcategory s ON p.subcategoryid = s.idsubcategory
            WHERE c.idcategory = s.categoryid AND (s.name like '$strSearch%' OR c.name like '$strSearch%' OR p.name like '$strSearch%')
            ORDER BY p.idproduct DESC $limit";
            $request = $this->select_all($sql);
            
            $sqlTotal = "SELECT COALESCE(COUNT(*),0) as total
            FROM product p
            INNER JOIN category c ON c.idcategory = p.categoryid
            INNER JOIN subcategory s ON p.subcategoryid = s.idsubcategory
            WHERE c.idcategory = s.categoryid AND (s.name like '$strSearch%' OR c.name like '$strSearch%' OR p.name like '$strSearch%')";    

            $totalRecords = $this->select($sqlTotal)['total'];
            
            $totalPages = intval($totalRecords > 0 ? ceil($totalRecords/$intPerPage) : 0);
            $totalPages = $totalPages == 0 ? 1 : $totalPages;
            $startPage = max(1, $intPage - floor(BUTTONS / 2));
            if ($startPage + BUTTONS - 1 > $totalPages) {
                $startPage = max(1, $totalPages - BUTTONS + 1);
            }
            $limitPages = min($startPage + BUTTONS, $totalPages+1);
            $arrButtons = [];
            for ($i=$startPage; $i < $limitPages; $i++) { 
                array_push($arrButtons,$i);
            }
                
            if(!empty($request)> 0){
                $strIdProducts = implode(",",array_column($request,"idproduct"));
                $sqlImg = "SELECT *,productid as product_id FROM productimage WHERE productid IN ($strIdProducts) GROUP BY productid";
                $requestImg = $this->select_all($sqlImg);
    
                $sqlPrices= "SELECT MIN(price_sell) AS sell,MIN(price_offer) AS offer,MIN(price_purchase) AS purchase,product_id
                FROM product_variations_options WHERE product_id IN ($strIdProducts) GROUP BY product_id";
                $requestPrices = $this->select_all($sqlPrices);
    
                $sqlStock = "SELECT SUM(stock) AS total,product_id FROM product_variations_options WHERE product_id IN ($strIdProducts) GROUP BY product_id";
                $requestStock = $this->select_all($sqlStock);
                $total = count($request);
                for ($i=0; $i < $total; $i++) { 
                    $idProduct = $request[$i]['idproduct'];
                    $arrImages = array_values(array_filter($requestImg,function($e) use($idProduct){return $e['product_id'] == $idProduct;}))[0];
                    if(!empty($arrImages)){
                        $request[$i]['image'] = media()."/images/uploads/".$arrImages['name'];
                    }else{
                        $request[$i]['image'] = media()."/images/default/product.png";
                    }
                    if($request[$i]['product_type'] == 1){
                        $arrPrices = array_values(array_filter($requestPrices,function($e) use($idProduct){return $e['product_id'] == $idProduct;}))[0];
                        $arrStock = array_values(array_filter($requestStock,function($e) use($idProduct){return $e['product_id'] == $idProduct;}))[0];
                        $request[$i]['price_purchase'] = $arrPrices['purchase'] ?? 0;
                        $request[$i]['price_sell'] = $arrPrices['sell'] ?? 0;
                        $request[$i]['price_discount'] = $arrPrices['offer'] ?? 0;
                        $request[$i]['stock'] = $arrStock['total'] ?? 0;
                    }
                    $request[$i]['price_purchase'] = formatNum($request[$i]['price_purchase']);
                    $request[$i]['price_sell'] = formatNum($request[$i]['price_sell']);
                    $request[$i]['price_discount'] = formatNum($request[$i]['price_discount']);
                }
            }
            $arrData = array(
                "data"=>$request,
                "start_page"=>$startPage,
                "limit_page"=>$limitPages,
                "total_pages"=>$totalPages,
                "total_records"=>$totalRecords,
                "buttons"=>$arrButtons
            );
            return $arrData;
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
                p.price as price_sell,
                p.price_purchase,
                p.discount as price_offer,
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
                DATE_FORMAT(p.date, '%d/%m/%Y') as date,
                m.initials
            FROM product p
            INNER JOIN category c ON c.idcategory = p.categoryid
            INNER JOIN subcategory s ON c.idcategory = s.categoryid AND p.subcategoryid = s.idsubcategory
            INNER JOIN measures m ON m.id_measure = p.measure
            AND p.idproduct = $this->intIdProduct";

            $request = $this->select($sql);
            $request['framing_url'] = media()."/images/uploads/".$request['framing_img'];
            if(!empty($request)){
                $sqlImg = "SELECT * FROM productimage WHERE productid = $this->intIdProduct";
                $requestImg = $this->select_all($sqlImg);
                $request['image'] = [];

                if(count($requestImg)){
                    for ($i=0; $i < count($requestImg); $i++) { 
                        $request['image'][$i] = array("route"=>media()."/images/uploads/".$requestImg[$i]['name'],"name"=>$requestImg[$i]['name'],"rename"=>$requestImg[$i]['name']);
                    }
                }

                $sqlIngredient = "SELECT * FROM product_ingredients WHERE product_id = $this->intIdProduct";
                $arrIngredients = $this->select_all($sqlIngredient);
                $arrFullIngredients = [];

                foreach ($arrIngredients as $data) {
                    $sql = "";
                    if($data['variant_name'] != ""){
                        $sql = "SELECT * FROM product_variations_options 
                        WHERE name = '{$data['variant_name']}' AND product_id = {$data['product']}";
                        $info = $this->select($sql);
                        $ingredient = [
                            "id"=>$data['product'],
                            "name"=> $data['name']." ".$info['name'],
                            "qty"=>$data['qty'],
                            "price_purchase"=>$info['price_purchase'],
                            "measure"=>$request['initials'],
                            "subtotal"=>$info['price_purchase']*$data['qty'],
                            "variant_name"=> $info['name'],
                            "reference"=>$info['sku']
                        ];
                    }else{
                        $sql = "SELECT reference,name,price_purchase FROM product WHERE idproduct = {$data['product']}";
                        $info = $this->select($sql);
                        $ingredient = [
                            "id"=>$data['product'],
                            "name"=> $info['name'],
                            "qty"=>$data['qty'],
                            "price_purchase"=>$info['price_purchase'],
                            "measure"=>$request['initials'],
                            "subtotal"=>$info['price_purchase']*$data['qty'],
                            "variant_name"=> null,
                            "reference"=>$info['reference']
                        ];
                    }
                    array_push($arrFullIngredients,$ingredient);
                }

                $request['ingredients']= $arrFullIngredients;

                $sqlSpecs = "SELECT p.specification_id as id,p.value,s.name
                FROM product_specs p
                INNER JOIN specifications s
                ON p.specification_id = s.id_specification
                WHERE p.product_id = $this->intIdProduct";
                $request['specs'] = $this->select_all($sqlSpecs);

                $request['wholesale_discount'] = $this->select_all("SELECT min,max,percent FROM product_wholesale_discount WHERE product_id = $this->intIdProduct");
                
                if($request['product_type'] == 1){
                    $request['variation'] = $this->select("SELECT * FROM product_variations WHERE product_id = $this->intIdProduct");
                    $request['variation']['variation'] = json_decode($request['variation']['variation']);
                    $request['options'] = $this->select_all("SELECT * FROM product_variations_options WHERE product_id = $this->intIdProduct");
                }
            }
            return $request;
        }

        private function insertWholesalePrice(){
            $this->delete("DELETE FROM product_wholesale_discount WHERE product_id = $this->intIdProduct");
            $data = $this->arrData['wholesale_discount'];
            foreach ($data as $det) {
                $sql = "INSERT INTO product_wholesale_discount (product_id,min,max,percent) VALUES(?,?,?,?)";
                $this->insert($sql,[  $this->intIdProduct, $det['min'], $det['max'], $det['percent'] ]);
            }
        }

        private function insertIngredients(){
            $this->delete("DELETE FROM product_ingredients WHERE product_id = $this->intIdProduct");
            $data = $this->arrData['ingredients'];
            foreach ($data as $det) {
                $sql = "INSERT INTO product_ingredients (product_id,product,variant_name,qty) VALUES(?,?,?,?)";
                $this->insert($sql,[
                    $this->intIdProduct,
                    $det['id'],
                    $det['variant_name'] == null ? "" : $det['variant_name'],
                    $det['qty']
                ]);
            }
        }

        private function insertVariants($id,$data){
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
                    strtoupper(strClean($combinations[$i]['sku'])),
                    1
                );
                $request = $this->insert($sql,$arrData);
            }
        }

        private function insertImages($id,$photos,$flag=true){
            if(!empty($photos)){
                if($flag){
                    $total = count($photos['name']);
                }else{
                    $total = count($photos);
                    $this->deleteImages($id);
                }
                for ($i=0; $i < $total ; $i++) { 
                    if($flag){
                        $strRoute = "product_".$id."_".bin2hex(random_bytes(6)).'.png';
                        uploadImage([
                            "name"=>$photos['name'][$i],
                            "full_path"=>$photos['full_path'][$i],
                            "type"=>$photos['type'][$i],
                            "tmp_name"=>$photos['tmp_name'][$i],
                            "error"=>$photos['error'][$i],
                            "size"=>$photos['size'][$i]
                        ],$strRoute);
                        $sqlImg = "INSERT INTO productimage(productid,name) VALUES(?,?)";
                        $arrImg = array($id,$strRoute);
                        $this->insert($sqlImg,$arrImg);
                    }else{
                        $sqlImg = "INSERT INTO productimage(productid,name) VALUES(?,?)";
                        $arrImg = array($id,$photos[$i]['name']);
                        $this->insert($sqlImg,$arrImg);
                    }
                }
            }
        }

        private function insertSpecs($id,$specs){
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

        public function selectInsumos($intPage,$intPerPage,$strSearch,$id=0){
            $limit ="";
            $intStartPage = ($intPage-1)*$intPerPage;
            if($intPerPage != 0){
                $limit = " LIMIT $intStartPage,$intPerPage";
            }

            $id = $id != 0 ? " AND p.idproduct != $id" : "";

            $arrProducts = [];
            $sql = "SELECT 
            p.idproduct,
            p.reference,
            p.name,
            p.stock,
            p.product_type,
            p.is_stock,
            p.price_purchase,
            p.price as price_sell,
            p.discount as price_offer,
            c.name as category,
            s.name as subcategory,
            v.name as variant_name,
            v.price_purchase as variant_purchase,
            v.price_sell as variant_sell,
            v.price_offer as variant_offer,
            v.stock as variant_stock,
            v.sku as variant_sku,
            m.initials as measure,
            p.import,
            va.variation
            FROM product p
            INNER JOIN category c ON c.idcategory = p.categoryid
            INNER JOIN subcategory s ON s.idsubcategory = p.subcategoryid
            LEFT JOIN product_variations_options v ON v.product_id = p.idproduct
            LEFT JOIN measures m ON m.id_measure = p.measure
            LEFT JOIN product_variations va ON va.id = v.product_variation_id
            WHERE p.status = 1 AND c.status = 1 AND s.status = 1 AND p.is_ingredient=1 
            AND (c.name like '$strSearch%' OR s.name like '$strSearch%' OR p.name like '$strSearch%'  OR c.name like '$strSearch'
            OR v.name like '$strSearch%' OR v.sku like '$strSearch%' OR p.reference like '$strSearch%' OR s.name like '$strSearch')  $id
            ORDER BY p.idproduct DESC $limit";
            $request = $this->select_all($sql);

            $sqlTotal = "SELECT COALESCE(COUNT(*),0) as total
            FROM product p
            INNER JOIN category c ON c.idcategory = p.categoryid
            INNER JOIN subcategory s ON s.idsubcategory = p.subcategoryid
            LEFT JOIN product_variations_options v ON v.product_id = p.idproduct
            WHERE p.status = 1 AND c.status = 1 AND s.status = 1 AND p.is_ingredient=1
            AND (c.name like '$strSearch%' OR s.name like '$strSearch%' OR p.name like '$strSearch%'  OR c.name like '$strSearch'
            OR v.name like '$strSearch%' OR v.sku like '$strSearch%' OR p.reference like '$strSearch%' OR s.name like '$strSearch') $id";

            $totalRecords = $this->select($sqlTotal)['total'];
            
            $totalPages = intval($totalRecords > 0 ? ceil($totalRecords/$intPerPage) : 0);
            $totalPages = $totalPages == 0 ? 1 : $totalPages;
            $startPage = max(1, $intPage - floor(BUTTONS / 2));
            if ($startPage + BUTTONS - 1 > $totalPages) {
                $startPage = max(1, $totalPages - BUTTONS + 1);
            }
            $limitPages = min($startPage + BUTTONS, $totalPages+1);
            $arrButtons = [];
            for ($i=$startPage; $i < $limitPages; $i++) { 
                array_push($arrButtons,$i);
            }

            if(!empty($request)){
                foreach ($request as $pro) {
                    $idProduct = $pro['idproduct'];
                    $sqlImg = "SELECT * FROM productimage WHERE productid = $idProduct";
                    $requestImg = $this->select_all($sqlImg);
                    $url = media()."/images/default/product.png";
                    if(count($requestImg)>0){
                        $url = media()."/images/uploads/".$requestImg[0]['name'];
                    }
                    $variation ="";
                    if($pro['product_type']){
                        $arrVariantName = explode("-",$pro['variant_name']);
                        $arrVariants = json_decode($pro['variation'],true);
                        $arrVariantDetail = [];
                        foreach ($arrVariantName as $name) {
                            foreach ($arrVariants as $variant) {
                                $arrOptions = $variant['options'];
                                foreach ($arrOptions as $option) {
                                    if($option ==$name){
                                        array_push($arrVariantDetail,array(
                                            "name"=>$variant['name'],
                                            "option"=>$name,
                                        ));
                                        break;
                                    }
                                }
                            }
                        }
                        $arrCombination = array(
                            "name"=>$pro['name'],
                            "detail"=>$arrVariantDetail
                        );
                        $variation = json_encode($arrCombination,JSON_UNESCAPED_UNICODE);
                    }
                    array_push($arrProducts,array(
                        "url"=>$url,
                        "id"=>$pro['idproduct'],
                        "reference"=>$pro['variant_sku'] != "" ? $pro['variant_sku'] : $pro['reference'],
                        "product_name"=>$pro['name'],
                        "name"=>$pro['variant_name'] != "" ? $pro['name']." ".$pro['variant_name'] : $pro['name'],
                        "price_purchase"=>$pro['variant_name'] != "" ? $pro['variant_purchase'] : $pro['price_purchase'],
                        "price_purchase_format"=>$pro['variant_name'] != "" ? formatNum($pro['variant_purchase']) : formatNum($pro['price_purchase']),
                        "price_sell"=>$pro['variant_name'] != "" ? $pro['variant_sell'] : $pro['price_sell'],
                        "price_sell_format"=>$pro['variant_name'] != "" ? formatNum($pro['variant_sell']) : formatNum($pro['price_sell']),
                        "category"=>$pro['category'],
                        "subcategory"=>$pro['subcategory'],
                        "stock"=>$pro['variant_name'] != "" ? $pro['variant_stock'] : $pro['stock'],
                        "measure"=>$pro['measure'],
                        "variation"=>$variation,
                        "variant_name"=>$pro['variant_name'],
                        "product_type"=>$pro['product_type'],
                        "is_stock"=>$pro['is_stock'],
                        "import"=>$pro['import']
                    ));
                }
            }

            $arrData = array(
                "data"=>$arrProducts,
                "start_page"=>$startPage,
                "limit_page"=>$limitPages,
                "total_pages"=>$totalPages,
                "total_records"=>$totalRecords,
                "buttons"=>$arrButtons
            );
            return $arrData;
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
            $sql = "SELECT id_measure as id, CONCAT(initials,' - ',name) as name, status FROM measures WHERE status = 1 ORDER BY id_measure ";
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