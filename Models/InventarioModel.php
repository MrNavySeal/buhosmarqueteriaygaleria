<?php 
    class InventarioModel extends Mysql{
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
        public function __construct(){
            parent::__construct();
        }
        /*************************Productos methods*******************************/
        public function insertProduct(int $idCategory, int $idSubcategory,string $strReference, string $strName, 
        string $strShortDescription,string $strDescription, int $intPrice, int $intDiscount, int $intStock, 
        int $intStatus, string $route, array $photos, int $framingMode,string $photoFraming, 
        int $productType,array $variants,string $specs){
            
            $this->intIdCategory = $idCategory;
            $this->intIdSubCategory = $idSubcategory;
            $this->strReference = $strReference;
			$this->strName = $strName;
            $this->strDescription = $strDescription;
            $this->intPrice = $intPrice;
            $this->intDiscount = $intDiscount;
            $this->intStock = $intStock;
			$this->intStatus = $intStatus;
			$this->strRoute = $route;
            $this->strShortDescription = $strShortDescription;
            $this->intFramingMode = $framingMode;
            $this->strFramingImg = $photoFraming;
            $this->intProductType = $productType;
            $this->strSpecifications = $specs;

			$return = 0;
            $reference="";
            if($this->strReference!=""){
                $reference = "AND reference = '$this->strReference'";
            }
			$sql = "SELECT * FROM product WHERE name='$this->strName' $reference";
			$request = $this->select_all($sql);

			if(empty($request))
			{ 
				$query_insert  = "INSERT INTO product(categoryid,subcategoryid,reference,name,shortdescription,description,price,discount,stock,status,route,framing_mode,framing_img,product_type,specifications) 
                VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
	        	$arrData = array(
                    $this->intIdCategory,
                    $this->intIdSubCategory,
                    $this->strReference,
                    $this->strName,
                    $this->strShortDescription,
                    $this->strDescription,
                    $this->intPrice,
                    $this->intDiscount,
                    $this->intStock,
                    $this->intStatus,
                    $this->strRoute,
                    $this->intFramingMode,
                    $this->strFramingImg,
                    $this->intProductType,
                    $this->strSpecifications
        		);
	        	$request_insert = $this->insert($query_insert,$arrData);
                $this->insertImages($request_insert,$photos);
                $this->insertVariants($request_insert,$variants);
	        	$return = $request_insert;
			}else{
				$return = "exist";
			}
	        return $return;
		}
        public function updateProduct(int $idProduct,int $idCategory, int $idSubcategory,string $strReference, string $strName, 
        string $strShortDescription, string $strDescription, int $intPrice, int $intDiscount, int $intStock, int $intStatus,
         string $route, array $photos,int $framingMode,string $photoFraming, 
         int $productType,array $variants,string $specs){
            $this->intIdProduct = $idProduct;
            $this->intIdCategory = $idCategory;
            $this->intIdSubCategory = $idSubcategory;
            $this->strReference = $strReference;
			$this->strName = $strName;
            $this->strDescription = $strDescription;
            $this->intPrice = $intPrice;
            $this->intDiscount = $intDiscount;
            $this->intStock = $intStock;
			$this->intStatus = $intStatus;
			$this->strRoute = $route;
            $this->strShortDescription = $strShortDescription;
            $this->intFramingMode = $framingMode;
            $this->strFramingImg = $photoFraming;
            $this->intProductType = $productType;
            $this->strSpecifications = $specs;
            $reference="";
            if($this->strReference!=""){
                $reference = "AND reference = '$this->strReference' AND name = '{$this->strName}' AND idproduct != $this->intIdProduct";
            }

			$sql = "SELECT * FROM product WHERE name = '{$this->strName}' AND idproduct != $this->intIdProduct $reference";
			$request = $this->select_all($sql);
			if(empty($request)){
                

                $sql = "UPDATE product SET categoryid=?, 
                subcategoryid=?, reference=?, name=?, shortdescription=?,description=?, 
                price=?,discount=?,stock=?,status=?, route=?, framing_mode=?,framing_img=?,product_type=?,specifications=? WHERE idproduct = $this->intIdProduct";
                $arrData = array(
                    $this->intIdCategory,
                    $this->intIdSubCategory,
                    $this->strReference,
                    $this->strName,
                    $this->strShortDescription,
                    $this->strDescription,
                    $this->intPrice,
                    $this->intDiscount,
                    $this->intStock,
                    $this->intStatus,
                    $this->strRoute,
                    $this->intFramingMode,
                    $this->strFramingImg,
                    $this->intProductType,
                    $this->strSpecifications,
        		);
				$request = $this->update($sql,$arrData);
                if(!empty($photos)){
                    $this->deleteImages($this->intIdProduct);
                    $this->insertImages($this->intIdProduct,$photos);
                }
                if(!empty($variants)){
                    $this->deleteVariants($this->intIdProduct);
                    $this->insertVariants($this->intIdProduct,$variants);
                }
			}else{
				$request = "exist";
			}
			return $request;
		
		}
        public function deleteProduct($id){
            $this->intIdProduct = $id;
            $images = $this->selectImages($this->intIdProduct);
            for ($i=0; $i < count($images) ; $i++) { 
                deleteFile($images[$i]['name']);
            }
            $sql = "DELETE FROM product WHERE idproduct = $this->intIdProduct;SET @autoid :=0; 
            UPDATE productimage SET id = @autoid := (@autoid+1);
            ALTER TABLE productimage Auto_Increment = 1;";
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
                    if($request[$i]['product_type'] == 2){
                        $sqlV = "SELECT MIN(price) AS minimo FROM product_variant WHERE productid =$idProduct";
                        $sqlTotal = "SELECT SUM(stock) AS total FROM product_variant WHERE productid =$idProduct";
                        $request[$i]['price'] = $this->select($sqlV)['minimo'];
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
                p.shortdescription,
                p.description,
                p.price,
                p.discount,
                p.stock,
                p.status,
                p.route,
                p.product_type,
                p.framing_mode,
                p.specifications,
                p.framing_img,
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
            if(!empty($request)){
                $sqlImg = "SELECT * FROM productimage WHERE productid = $this->intIdProduct";
                $requestImg = $this->select_all($sqlImg);
                if(count($requestImg)){
                    for ($i=0; $i < count($requestImg); $i++) { 
                        $request['image'][$i] = array("url"=>media()."/images/uploads/".$requestImg[$i]['name'],"name"=>$requestImg[$i]['name'],"rename"=>$requestImg[$i]['name']);
                    }
                }
                if($request['product_type'] == 2){
                    $sqlV = "SELECT * FROM product_variant WHERE productid = $this->intIdProduct ORDER BY price ASC";
                    $requestV = $this->select_all($sqlV);
                    if(count($requestV)){
                        for ($i=0; $i < count($requestV); $i++) { 
                            $request['variants'][$i] = array(
                                "width"=>$requestV[$i]['width'],
                                "height"=>$requestV[$i]['height'],
                                "stock"=>$requestV[$i]['stock'],
                                "price"=>$requestV[$i]['price']
                            );
                        }
                    }
                }
            }
            return $request;
        }
        public function insertVariants($id,$variants){
            for ($i=0; $i < count($variants) ; $i++) { 
                $sql = "INSERT INTO product_variant(productid,width,height,stock,price) VALUES(?,?,?,?,?)";
                $arrData = array(
                    $id,
                    $variants[$i]['width'],
                    $variants[$i]['height'],
                    $variants[$i]['stock'],
                    $variants[$i]['price']
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
        public function selectImages($id){
            $this->intIdProduct = $id;
            $sql = "SELECT * FROM productimage WHERE productid=$this->intIdProduct";
            $request = $this->select_all($sql);
            for ($i=0; $i < count($request); $i++) { 
                $request[$i]['rename'] = $request[$i]['name'];
            }
            return $request;
        }
        public function deleteVariants($id){
            $this->intIdProduct = $id;
            $sql = "DELETE FROM product_variant WHERE productid=$this->intIdProduct";
            $request = $this->delete($sql);
            return $request;
        }
        public function deleteImages($id){
            $this->intIdProduct = $id;
            $sql = "DELETE FROM productimage WHERE productid=$this->intIdProduct";
            $request = $this->delete($sql);
            return $request;
        }
        public function getSelectSubcategories(int $intIdCategory){
            $this->intIdCategory = $intIdCategory;
            $sql = "SELECT  
                    s.idsubcategory,
                    s.name,
                    s.categoryid,
                    c.idcategory,
                    c.name as category
                    FROM subcategory s
                    INNER JOIN category c
                    ON c.idcategory = s.categoryid
                    WHERE s.categoryid = $this->intIdCategory
                    ORDER BY s.idsubcategory ASC";       
            $request = $this->select_all($sql);
            return $request;
        }
        /*************************Category methods*******************************/
        public function insertCategory(string $photo,string $strName,int $status, string $strDescription, string $strRoute){

			$this->strName = $strName;
			$this->strRoute = $strRoute;
            $this->strDescription = $strDescription;
            $this->strPhoto = $photo;
            $this->intStatus = $status;
			$return = 0;

			$sql = "SELECT * FROM category WHERE 
					name = '{$this->strName}'";
			$request = $this->select_all($sql);

			if(empty($request))
			{ 
				$query_insert  = "INSERT INTO category(picture,name,status,description,route) 
								  VALUES(?,?,?,?,?)";
	        	$arrData = array(
                    $this->strPhoto,
                    $this->strName,
                    $this->intStatus,
                    $this->strDescription,
                    $this->strRoute
        		);
	        	$request_insert = $this->insert($query_insert,$arrData);
	        	$return = $request_insert;
			}else{
				$return = "exist";
			}
	        return $return;
		}
        public function updateCategory(int $intIdCategory,string $photo, string $strName,int $status, string $strDescription,string $strRoute){
            $this->intIdCategory = $intIdCategory;
            $this->strName = $strName;
            $this->strDescription = $strDescription;
			$this->strRoute = $strRoute;
            $this->strPhoto = $photo;
            $this->intStatus = $status;

			$sql = "SELECT * FROM category WHERE name = '{$this->strName}' AND idcategory != $this->intIdCategory";
			$request = $this->select_all($sql);

			if(empty($request)){

                $sql = "UPDATE category SET picture=?, name=?,status=?,description=?, route=? WHERE idcategory = $this->intIdCategory";
                $arrData = array(
                    $this->strPhoto,
                    $this->strName,
                    $this->intStatus,
                    $this->strDescription,
                    $this->strRoute
                );
				$request = $this->update($sql,$arrData);
			}else{
				$request = "exist";
			}
			return $request;
		
		}
        public function deleteCategory($id){
            $this->intIdCategory = $id;
            $sql = "SELECT * FROM subcategory WHERE categoryid = $this->intIdCategory";
            $request = $this->select_all($sql);
            $return = "";
            if(empty($request)){
                $sql = "DELETE FROM category WHERE idcategory = $this->intIdCategory";
                $return = $this->delete($sql);
            }else{
                $return="exist";
            }
            return $return;
        }
        public function selectCategories(){
            $sql = "SELECT * FROM category ORDER BY idcategory DESC";       
            $request = $this->select_all($sql);
            return $request;
        }
        public function selectCategory($id){
            $this->intIdCategory = $id;
            $sql = "SELECT * FROM category WHERE idcategory = $this->intIdCategory";
            $request = $this->select($sql);
            return $request;
        }
        /*************************SubCategory methods*******************************/
        public function insertSubCategory(int $intIdCategory ,string $strName,int $status,string $strRoute){
            $this->intIdCategory = $intIdCategory;
			$this->strName = $strName;
			$this->strRoute = $strRoute;
            $this->intStatus = $status;
			$return = 0;
			$sql = "SELECT * FROM subcategory WHERE name = '{$this->strName}' AND categoryid = $this->intIdCategory";
			$request = $this->select_all($sql);
			if(empty($request)){
				$query_insert  = "INSERT INTO subcategory(categoryid,name,status,route) VALUES(?,?,?,?)";  
	        	$arrData = array(
                    $this->intIdCategory,
                    $this->strName,
                    $this->intStatus,
                    $this->strRoute
        		);
	        	$request_insert = $this->insert($query_insert,$arrData);
	        	$return = $request_insert;
			}else{
				$return = "exist";
			}
	        return $return;
		}
        public function updateSubCategory(int $intIdSubCategory,int $intIdCategory, string $strName,int $status,string $strRoute){
            $this->intIdSubCategory = $intIdSubCategory;
            $this->intIdCategory = $intIdCategory;
            $this->strName = $strName;
			$this->strRoute = $strRoute;
            $this->intStatus = $status;
			$sql = "SELECT * FROM subcategory WHERE name = '{$this->strName}' AND idsubcategory != $this->intIdSubCategory AND categoryid = $this->intIdCategory";
			$request = $this->select_all($sql);

			if(empty($request)){

                $sql = "UPDATE subcategory SET categoryid=?,name=?, status=?,route=? WHERE idsubcategory = $this->intIdSubCategory";
                $arrData = array(
                    $this->intIdCategory,
                    $this->strName,
                    $this->intStatus,
                    $this->strRoute
                );
				$request = $this->update($sql,$arrData);
			}else{
				$request = "exist";
			}
			return $request;
		}
        public function deleteSubCategory($id){
            $this->intIdSubCategory = $id;
            $sql="SELECT * FROM product WHERE subcategoryid = $id";
            $request = $this->select_all($sql);
            $return="";
            if(empty($request)){
                $sql = "DELETE FROM subcategory WHERE idsubcategory = $this->intIdSubCategory";
                $request = $this->delete($sql);
                $return = $request;
            }else{
                $return ="exist";
            }
            return $return;
        }
        public function selectSubCategories(){
            $sql = "SELECT  
                    s.idsubcategory,
                    s.name,
                    s.categoryid,
                    c.idcategory,
                    c.name as category,
                    s.status
                    FROM subcategory s
                    INNER JOIN category c
                    ON c.idcategory = s.categoryid
                    ORDER BY idsubcategory DESC";       
            $request = $this->select_all($sql);
            return $request;
        }
        public function selectSubCategory($id){
            $this->intIdSubCategory = $id;
            $sql = "SELECT * FROM subcategory WHERE idsubcategory = $this->intIdSubCategory";
            $request = $this->select($sql);
            return $request;
        }
        /*
        public function selectChangePrice($category){
            $request = $this->select_all("SELECT * FROM product WHERE product_type = 2 AND categoryid = $category");
            if(count($request)> 0){
                for ($i=0; $i < count($request) ; $i++) { 
                    $idProduct = $request[$i]['idproduct'];
                    $sqlV = "SELECT * FROM product_variant WHERE productid = $idProduct ORDER BY price ASC";
                    $requestV = $this->select_all($sqlV);
                    for ($j=0; $j < count($requestV); $j++) { 
                        $idVariant = $requestV[$j]['id_product_variant'];
                        
                        $price = round(($requestV[$j]['price']*1.22)/1000)*1000;
                        $arrData= array($price);
                        $this->update("UPDATE product_variant SET price=? WHERE id_product_variant = $idVariant",$arrData);
                        
                        $request[$i]['variants'][$j] = array(
                            "width"=>$requestV[$j]['width'],
                            "height"=>$requestV[$j]['height'],
                            "stock"=>$requestV[$j]['stock'],
                            "price"=>$requestV[$j]['price']
                        );
                        
                    }
                }
            }
            return $request;
        }*/
    }
?>