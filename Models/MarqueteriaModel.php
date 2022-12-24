<?php 
    class MarqueteriaModel extends Mysql{
        private $intIdProduct;
        private $intIdCategory;
        private $strReference;
        private $intPrice;
        private $intWaste;
        private $strFrame;
        private $intDiscount;
		private $intStatus;
        private $intType;
        private $intIdColor;
        private $intIdMaterial;
        private $strMaterial;
        private $strUnit;
        private $intMaterialPrice;
        private $strColor;
        private $strHexColor;
        private $strRoute;
        private $strDescription;
        private $strPhoto;
        private $strName;

        public function __construct(){
            parent::__construct();
        }

        /*************************Product methods*******************************/
        public function insertProduct(string $strReference,int $intType, int $intWaste, int $intPrice, int $intDiscount, int $intStatus, string $strFrame, array $photos){
            
            //$this->intIdProduct = $intIdProduct;
            $this->strReference = $strReference;
            $this->intType = $intType;
            $this->intWaste = $intWaste;
            $this->intPrice = $intPrice;
            $this->intDiscount = $intDiscount;
			$this->intStatus = $intStatus;
            $this->strFrame = $strFrame;

			$return = 0;

			$sql = "SELECT * FROM molding WHERE reference = '$this->strReference'";
			$request = $this->select_all($sql);

			if(empty($request))
			{ 
				$query_insert  = "INSERT INTO molding(reference,type,discount,price,waste,status,frame) VALUES(?,?,?,?,?,?,?)";
	        	$arrData = array(
                    $this->strReference,
                    $this->intType,
                    $this->intDiscount,
                    $this->intPrice,
                    $this->intWaste,
                    $this->intStatus,
                    $this->strFrame
        		);
	        	$request_insert = $this->insert($query_insert,$arrData);
                for ($i=0; $i < count($photos) ; $i++) { 
                    $sqlImg = "INSERT INTO moldingimage(moldingid,name) VALUES(?,?)";
                    $arrImg = array($request_insert,$photos[$i]['re_name']);
                    $requestImg = $this->insert($sqlImg,$arrImg);
                }
	        	$return = $request_insert;
			}else{
				$return = "exist";
			}
	        return $return;
		}
        public function updateProduct(int $intIdProduct,string $strReference,int $intType, int $intWaste, int $intPrice, int $intDiscount, int $intStatus, string $strFrame, array $photos){
            $this->intIdProduct = $intIdProduct;
            $this->strReference = $strReference;
            $this->intType = $intType;
            $this->intWaste = $intWaste;
            $this->intPrice = $intPrice;
            $this->intDiscount = $intDiscount;
			$this->intStatus = $intStatus;
            $this->strFrame = $strFrame;

			$sql = "SELECT * FROM molding WHERE reference = '{$this->strReference}' AND id != $this->intIdProduct";
			$request = $this->select_all($sql);

			if(empty($request)){

                $sql = "UPDATE molding SET reference=?, type=?, discount=?,price=?,waste=?,status=?,frame=? WHERE id = $this->intIdProduct";
                
                $arrData = array(
                    $this->strReference,
                    $this->intType,
                    $this->intDiscount,
                    $this->intPrice,
                    $this->intWaste,
                    $this->intStatus,
                    $this->strFrame
        		);
				$request = $this->update($sql,$arrData);
                if(!empty($photos)){
                    $delImages = $this->deleteImages($this->intIdProduct);
                    for ($i=0; $i < count($photos) ; $i++) { 
                        $sqlImg = "INSERT INTO moldingimage(moldingid,name) VALUES(?,?)";
                        $arrImg = array($this->intIdProduct,$photos[$i]['rename']);
                        $requestImg = $this->insert($sqlImg,$arrImg);
                    }
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
            $sql = "DELETE FROM molding WHERE id = $this->intIdProduct;SET @autoid :=0; 
            UPDATE moldingimage SET id = @autoid := (@autoid+1);
            ALTER TABLE moldingimage Auto_Increment = 1;";
            $request = $this->delete($sql);
            return $request;
        }
        public function selectProducts(){
            $sql = "SELECT * FROM molding ORDER BY id DESC";
            $request = $this->select_all($sql);
            if(count($request)> 0){
                for ($i=0; $i < count($request); $i++) { 
                    $idProduct = $request[$i]['id'];
                    $sqlImg = "SELECT * FROM moldingimage WHERE moldingid = $idProduct";
                    $requestImg = $this->select_all($sqlImg);
                    if(count($requestImg)>0){
                        $request[$i]['image'] = media()."/images/uploads/".$requestImg[0]['name'];
                    }else{
                        $request[$i]['image'] = media()."/images/uploads/image.png";
                    }
                }
            }
            return $request;
        }
        public function selectProduct($id){
            $this->intIdProduct = $id;
            $sql = "SELECT * FROM molding WHERE id = $this->intIdProduct";
            $request = $this->select($sql);
            if(!empty($request)){
                $sqlImg = "SELECT * FROM moldingimage WHERE moldingid = $this->intIdProduct";
                $requestImg = $this->select_all($sqlImg);
                if(count($requestImg)){
                    for ($i=0; $i < count($requestImg); $i++) { 
                        $request['image'][$i] = array("url"=>media()."/images/uploads/".$requestImg[$i]['name'],"name"=>$requestImg[$i]['name'],"rename"=>$requestImg[$i]['name']);
                    }
                }
            }
            return $request;
        }
        public function insertTmpImage(string $name, string $rename){
            $sql = "INSERT INTO imagetmp(name,re_name) VALUES(?,?)";
            $arrData = array($name,$rename);
            $request = $this->insert($sql,$arrData);
            return $request;
        }
        public function deleteTmpImage($rename = null){
            if($rename == null){
                $sql = "DELETE FROM imagetmp ; SET @autoid :=0; 
                UPDATE imagetmp SET id = @autoid := (@autoid+1);
                ALTER TABLE imagetmp Auto_Increment = 1;";
                $request = $this->delete($sql);
            }else{
                $sql = "DELETE FROM imagetmp WHERE re_name = '$rename'; SET @autoid :=0; 
                UPDATE imagetmp SET id = @autoid := (@autoid+1);
                ALTER TABLE imagetmp Auto_Increment = 1;";
                $request = $this->delete($sql);
            }
            return $request;
        }
        public function selectTmpImages(){
            $sql = "SELECT * FROM imagetmp";
            $request = $this->select_all($sql);
            for ($i=0; $i < count($request); $i++) { 
                $request[$i]['rename'] = $request[$i]['re_name'];
            }
            return $request;
        }
        public function selectImages($id){
            $this->intIdProduct = $id;
            $sql = "SELECT * FROM moldingimage WHERE moldingid=$this->intIdProduct";
            $request = $this->select_all($sql);
            for ($i=0; $i < count($request); $i++) { 
                $request[$i]['rename'] = $request[$i]['name'];
            }
            return $request;
        }
        public function deleteImages($id){
            $this->intIdProduct = $id;
            $sql = "DELETE FROM moldingimage WHERE moldingid=$this->intIdProduct";
            $request = $this->select_all($sql);
            return $request;
        }
        public function searchm($search){
            $sql = "SELECT * FROM molding WHERE reference LIKE '%$search%'";
            $request = $this->select_all($sql);
            if(count($request)> 0){
                for ($i=0; $i < count($request); $i++) { 
                    $idProduct = $request[$i]['id'];
                    $sqlImg = "SELECT * FROM moldingimage WHERE moldingid = $idProduct";
                    $requestImg = $this->select_all($sqlImg);
                    if(count($requestImg)>0){
                        $request[$i]['image'] = media()."/images/uploads/".$requestImg[0]['name'];
                    }else{
                        $request[$i]['image'] = media()."/images/uploads/image.png";
                    }
                }
            }
            return $request;
        }
        public function sortm($sort){
            $option=" ORDER BY id DESC";
            if($sort == 2){
                $option = " ORDER BY id ASC"; 
            }else if( $sort == 3){
                $option = " ORDER BY type ASC"; 
            }
            $sql = "SELECT * FROM molding $option";
            $request = $this->select_all($sql);
            if(count($request)> 0){
                for ($i=0; $i < count($request); $i++) { 
                    $idProduct = $request[$i]['id'];
                    $sqlImg = "SELECT * FROM moldingimage WHERE moldingid = $idProduct";
                    $requestImg = $this->select_all($sqlImg);
                    if(count($requestImg)>0){
                        $request[$i]['image'] = media()."/images/uploads/".$requestImg[0]['name'];
                    }else{
                        $request[$i]['image'] = media()."/images/uploads/image.png";
                    }
                }
            }
            return $request;
        }
        /*************************Color methods*******************************/
        public function insertColor(string $strName,string $strColor,int $intStatus){

			$this->strColor = $strName;
			$this->strHexColor = $strColor;
            $this->intStatus = $intStatus;

			$return = 0;
			$sql = "SELECT * FROM moldingcolor WHERE name = '{$this->strColor}'";
			$request = $this->select_all($sql);

			if(empty($request))
			{ 
				$query_insert  = "INSERT INTO moldingcolor(name,color,status) VALUES(?,?,?)";	  
	        	$arrData = array($this->strColor, $this->strHexColor,$this->intStatus);
	        	$request_insert = $this->insert($query_insert,$arrData);
	        	$return = $request_insert;
			}else{
				$return = "exist";
			}
	        return $return;
		}
        public function updateColor(int $intIdColor,string $strName,string $strColor,int $intStatus){
            $this->intIdColor = $intIdColor;
            $this->strColor = $strName;
			$this->strHexColor = $strColor;
            $this->intStatus = $intStatus;
            

			$sql = "SELECT * FROM moldingcolor WHERE name = '{$this->strColor}' AND id != $this->intIdColor";
			$request = $this->select_all($sql);

			if(empty($request)){

                $sql = "UPDATE moldingcolor SET name=?,color=?, status=? WHERE id = $this->intIdColor";
                $arrData = array($this->strColor, $this->strHexColor,$this->intStatus);
				$request = $this->update($sql,$arrData);
			}else{
				$request = "exist";
			}
			return $request;
		
		}
        public function deleteColor($id){
            $this->intIdColor = $id;
            $sql = "DELETE FROM moldingcolor WHERE id = $this->intIdColor";
            $return = $this->delete($sql);
            return $return;
        }
        public function selectColors(){
            $sql = "SELECT * FROM moldingcolor ORDER BY id DESC";       
            $request = $this->select_all($sql);
            return $request;
        }
        public function selectColor($id){
            $this->intIdColor = $id;
            $sql = "SELECT * FROM moldingcolor WHERE id = $this->intIdColor";
            $request = $this->select($sql);
            return $request;
        }
        public function searchc($search){
            $sql = "SELECT * FROM moldingcolor WHERE name LIKE '%$search%'";
            $request = $this->select_all($sql);
            return $request;
        }
        public function sortc($sort){
            $option=" ORDER BY id DESC";
            if($sort == 2){
                $option = " ORDER BY id ASC"; 
            }else if( $sort == 3){
                $option = " ORDER BY name ASC"; 
            }
            $sql = "SELECT * FROM moldingcolor $option";
            $request = $this->select_all($sql);
            return $request;
        }
        /*************************Material methods*******************************/
        public function insertMaterial(string $strName,string $strUnit,int $intMaterialPrice,string $strPre){

			$this->strMaterial = $strName;
			$this->strUnit = $strUnit;
            $this->intMaterialPrice = $intMaterialPrice;

			$return = 0;
			$sql = "SELECT * FROM moldingmaterial WHERE name = '{$this->strMaterial}'";
			$request = $this->select_all($sql);

			if(empty($request))
			{ 
				$query_insert  = "INSERT INTO moldingmaterial(name,unit,price,pre) VALUES(?,?,?,?)";	  
	        	$arrData = array($this->strMaterial, $this->strUnit,$this->intMaterialPrice,$strPre);
	        	$request_insert = $this->insert($query_insert,$arrData);
	        	$return = $request_insert;
			}else{
				$return = "exist";
			}
	        return $return;
		}
        public function updateMaterial(int $intIdMaterial,string $strName,string $strUnit,int $intMaterialPrice, string $strPre){
            $this->intIdMaterial = $intIdMaterial;
            $this->strMaterial = $strName;
			$this->strUnit = $strUnit;
            $this->intMaterialPrice = $intMaterialPrice;
            

			$sql = "SELECT * FROM moldingmaterial WHERE name = '{$this->strMaterial}' AND id != $this->intIdMaterial";
			$request = $this->select_all($sql);

			if(empty($request)){

                $sql = "UPDATE moldingmaterial SET name=?,unit=?, price=?, pre=? WHERE id = $this->intIdMaterial";
                $arrData = array($this->strMaterial, $this->strUnit,$this->intMaterialPrice,$strPre);
				$request = $this->update($sql,$arrData);
			}else{
				$request = "exist";
			}
			return $request;
		
		}
        public function deleteMaterial($id){
            $this->intIdMaterial = $id;
            $sql = "DELETE FROM moldingmaterial WHERE id = $this->intIdMaterial";
            $return = $this->delete($sql);
            return $return;
        }
        public function selectMaterials(){
            $sql = "SELECT * FROM moldingmaterial ORDER BY id DESC";       
            $request = $this->select_all($sql);
            return $request;
        }
        public function selectMaterial($id){
            $this->intIdMaterial = $id;
            $sql = "SELECT * FROM moldingmaterial WHERE id = $this->intIdMaterial";
            $request = $this->select($sql);
            return $request;
        }
        public function searchma($search){
            $sql = "SELECT * FROM moldingmaterial WHERE name LIKE '%$search%'";
            $request = $this->select_all($sql);
            return $request;
        }

        /*************************Category methods*******************************/
        public function insertCategory(string $photo,string $strName, string $strDescription, string $strRoute, int $intStatus){

			$this->strName = $strName;
			$this->strRoute = $strRoute;
            $this->strDescription = $strDescription;
            $this->strPhoto = $photo;
            $this->intStatus = $intStatus;
			$return = 0;

			$sql = "SELECT * FROM moldingcategory WHERE 
					name = '{$this->strName}'";
			$request = $this->select_all($sql);

			if(empty($request))
			{ 
				$query_insert  = "INSERT INTO moldingcategory(image,name,description,route,status) 
								  VALUES(?,?,?,?,?)";
	        	$arrData = array($this->strPhoto,$this->strName,$this->strDescription,$this->strRoute,$this->intStatus);
	        	$request_insert = $this->insert($query_insert,$arrData);
	        	$return = $request_insert;
			}else{
				$return = "exist";
			}
	        return $return;
		}
        public function updateCategory(int $intIdCategory,string $photo, string $strName, string $strDescription,string $strRoute, int $intStatus){
            $this->intIdCategory = $intIdCategory;
            $this->strName = $strName;
            $this->strDescription = $strDescription;
            $this->intStatus = $intStatus;
			$this->strRoute = $strRoute;
            $this->strPhoto = $photo;
            

			$sql = "SELECT * FROM moldingcategory WHERE name = '{$this->strName}' AND id != $this->intIdCategory";
			$request = $this->select_all($sql);

			if(empty($request)){

                $sql = "UPDATE moldingcategory SET image=?, name=?,description=?, route=?, status=? WHERE id = $this->intIdCategory";
                $arrData = array($this->strPhoto,$this->strName,$this->strDescription,$this->strRoute,$this->intStatus);
				$request = $this->update($sql,$arrData);
			}else{
				$request = "exist";
			}
			return $request;
		
		}
        public function deleteCategory($id){
            $this->intIdCategory = $id;
            $sql = "DELETE FROM moldingcategory WHERE id = $this->intIdCategory";
            $return = $this->delete($sql);
            return $return;
        }
        public function selectCategories(){
            $sql = "SELECT * FROM moldingcategory ORDER BY id DESC";       
            $request = $this->select_all($sql);
            return $request;
        }
        public function selectCategory($id){
            $this->intIdCategory = $id;
            $sql = "SELECT * FROM moldingcategory WHERE id = $this->intIdCategory";
            $request = $this->select($sql);
            return $request;
        }
        public function searchca($search){
            $sql = "SELECT * FROM moldingcategory WHERE name LIKE '%$search%'";
            $request = $this->select_all($sql);
            return $request;
        }
        public function sortca($sort){
            $option="DESC";
            if($sort == 2){
                $option = " ASC"; 
            }
            $sql = "SELECT * FROM moldingcategory ORDER BY id $option ";
            $request = $this->select_all($sql);
            return $request;
        }
    }
?>