<?php 
    class ProductosMasivosModel extends Mysql{
        private $arrData;
        private $strName;
        private $strReference;
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
        public function selectMeasures(){
            $sql = "SELECT name FROM measures";
            $request = $this->select_all($sql);
            $arrMeasures = [];
            for ($i=0; $i < count($request) ; $i++) { 
                array_push($arrMeasures,$request[$i]['name']);
            }
            return $arrMeasures;
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
            price,price_purchase,discount,stock,min_stock,status,route,product_type,
            import,is_product,is_ingredient,is_combo,is_stock) 
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
            //$this->insertSpecs($request,$this->arrData['specs']);
            //$this->insertVariants($request,$this->arrData['variants']);
	        return $request;
		}
        public function insertImages($id,$name){
            $sqlImg = "INSERT INTO productimage(productid,name) VALUES(?,?)";
            $arrImg = array($id,$name);
            $requestImg = $this->insert($sqlImg,$arrImg);
            return $requestImg;
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
    }
?>