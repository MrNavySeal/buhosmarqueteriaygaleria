<?php 
    class ProductosMasivosModel extends Mysql{
        private $arrData;
        private $intIdProduct;
        public function __construct(){
            parent::__construct();
        }
        
        public function selectNextId(){
            $sql = "SELECT MAX(idproduct) as id FROM product";
            $request = $this->select($sql)['id']+1;
            return $request;
        }
        public function selectCategories(){
            $sql = "SELECT name,idcategory FROM category WHERE status = 1";
            $request = $this->select_all($sql);
            $arrCategories = [];
            $arrSubcategories = [];
            for ($i=0; $i < count($request) ; $i++) { 
                $id = $request[$i]['idcategory'];
                $sql = "SELECT s.name,c.status 
                FROM subcategory s 
                INNER JOIN category c ON s.categoryid = c.idcategory
                WHERE c.status = 1 AND s.status = 1 AND s.categoryid =$id";
                $subcategories = $this->select_all($sql);
                for ($j=0; $j < count($subcategories) ; $j++) { 
                    $subcategories[$j] = $subcategories[$j]['name'];
                }
                array_push($arrCategories,$request[$i]['name']);
                array_push($arrSubcategories,array($request[$i]['name']=>$subcategories));
            }
            return array("categories"=>$arrCategories,"subcategories"=>$arrSubcategories);
        }
        public function selectSpecs(){
            $sql = "SELECT name,id_specification as id FROM specifications WHERE status =1 ORDER BY name";
            $request = $this->select_all($sql);
            $arr = [];
            for ($i=0; $i < count($request) ; $i++) { 
                array_push($arr,$request[$i]['id']."_".$request[$i]['name']);
            }
            return $arr;
        }
        public function selectMeasures(){
            $sql = "SELECT name FROM measures";
            $request = $this->select_all($sql);
            $arrMeasures = [];
            for ($i=0; $i < count($request) ; $i++) { 
                array_push($arrMeasures,$request[$i]['name']);
            }
            return $arrMeasures;
        }
        public function selectMeasure($name){
            $sql = "SELECT id_measure FROM measures WHERE name = '$name'";
            $request = $this->select($sql)['id_measure'];
            return $request;
        }
        public function selectcategoryId(string $name){
            $sql = "SELECT idcategory FROM category WHERE name = '$name'";
            $request = $this->select($sql);
            return !empty($request) ? $request['idcategory'] : "";
        }
        public function selectSubcategoryId(int $id,string $name){
            $sql="SELECT idsubcategory FROM subcategory WHERE name = '$name' AND categoryid = $id";
            $request = $this->select($sql);
            return !empty($request) ? $request['idsubcategory'] : "";
        }
        public function insertProduct(array $data){
            $this->arrData = $data;
            $sql =  "INSERT INTO product(categoryid,
            subcategoryid,reference,name,shortdescription,description,measure,
            price,price_purchase,discount,stock,min_stock,status,route,
            product_type,import,is_product,is_ingredient,is_combo,is_stock) 
            VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
            
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
                $this->arrData['product_type'],
                $this->arrData['import'],
                $this->arrData['is_product'],
                $this->arrData['is_ingredient'],
                $this->arrData['is_combo'],
                $this->arrData['is_stock']
            );
            $request = $this->insert($sql,$arrData);
            $this->insertImages($request,$this->arrData['images']);
            if(!empty($this->arrData['specs'])){
                $this->insertSpecs($request,$this->arrData['specs']);
            }
            if(!empty($this->arrData['variants'])){
                $this->insertVariants($request,$this->arrData['variants'],1);
            }
			if(empty($request)){
	        	$return = intval($request);
			}else{
				$return = "exist";
			}
	        return $return;
		}
        public function insertVariants($id,$data,$is_stock){
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
                    1
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
        public function selectVariants(){
            $sql = "SELECT name,id_variation FROM variations WHERE status = 1";
            $request = $this->select_all($sql);
            if(!empty($request)){
                $total = count($request);
                for ($i=0; $i < $total; $i++) { 
                    $id = $request[$i]['id_variation'];
                    $sql ="SELECT * FROM variation_options WHERE variation_id = $id";
                    $options = $this->select_all($sql);
                    for ($j=0; $j < count($options) ; $j++) { 
                        $options[$j] = $id."_".$options[$j]['name'];
                    }
                    $request[$i]['options'] = $options;
                }
            }
            return $request;
        }
        public function orderVariants(array $arrVariants, array $arrOptions){
            $arrNew = array();
            for ($i=0; $i < count($arrVariants) ; $i++) { 
                $id = $arrVariants[$i];
                $sql = "SELECT name FROM variations WHERE status = 1 AND id_variation = $id";
                $name = $this->select($sql)['name'];
                $options = array();
                for ($j=0; $j < count($arrOptions); $j++) { 
                    $optionName = $arrOptions[$j];
                    $sql ="SELECT name FROM variation_options WHERE variation_id = $id AND name = '$optionName'";
                    $option = $this->select($sql);
                    if(!empty($option)){
                        array_push($options,$option['name']);
                    }
                }
                array_push($arrNew,array(
                    "id"=>$id,
                    "name"=>$name,
                    "options"=>$options
                ));
            }
            return $arrNew;
        }
    }
?>