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
        private $isVisible;
        private $intOrder;

        public function __construct(){
            parent::__construct();
        }
        /*************************Category methods*******************************/
        public function insertCategory(string $photo,string $strName, string $strDescription, string $strRoute, int $intStatus, string $button,int $isVisible){

			$this->strName = $strName;
			$this->strRoute = $strRoute;
            $this->strDescription = $strDescription;
            $this->strPhoto = $photo;
            $this->intStatus = $intStatus;
            $this->isVisible = $isVisible;
			$return = 0;

			$sql = "SELECT * FROM moldingcategory WHERE 
					name = '{$this->strName}'";
			$request = $this->select_all($sql);

			if(empty($request))
			{ 
				$query_insert  = "INSERT INTO moldingcategory(image,name,description,route,status,button,is_visible) 
								  VALUES(?,?,?,?,?,?,?)";
	        	$arrData = array($this->strPhoto,$this->strName,$this->strDescription,$this->strRoute,$this->intStatus,$button,$this->isVisible);
	        	$request_insert = $this->insert($query_insert,$arrData);
	        	$return = $request_insert;
			}else{
				$return = "exist";
			}
	        return $return;
		}
        public function updateCategory(int $intIdCategory,string $photo, string $strName, string $strDescription,string $strRoute, int $intStatus, string $button,int $isVisible){
            $this->intIdCategory = $intIdCategory;
            $this->strName = $strName;
            $this->strDescription = $strDescription;
            $this->intStatus = $intStatus;
			$this->strRoute = $strRoute;
            $this->strPhoto = $photo;
            $this->isVisible = $isVisible;

			$sql = "SELECT * FROM moldingcategory WHERE name = '{$this->strName}' AND id != $this->intIdCategory";
			$request = $this->select_all($sql);

			if(empty($request)){

                $sql = "UPDATE moldingcategory SET image=?, name=?,description=?, route=?, status=?, button=? ,is_visible=? WHERE id = $this->intIdCategory";
                $arrData = array($this->strPhoto,$this->strName,$this->strDescription,$this->strRoute,$this->intStatus,$button,$this->isVisible);
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
        public function selectCategories($flag=false){
            $status = "";
            if($flag)$status = " WHERE status != 3";
            $sql = "SELECT * FROM moldingcategory $status ORDER BY id DESC";       
            $request = $this->select_all($sql);
            return $request;
        }
        public function selectCategory($id){
            $this->intIdCategory = $id;
            $sql = "SELECT * FROM moldingcategory WHERE id = $this->intIdCategory";
            $request = $this->select($sql);
            return $request;
        }
        public function selectTipo($route){
            $sql = "SELECT * FROM moldingcategory WHERE route = '$route'";
            $request = $this->select($sql);
            return $request;
        }
        /*************************Properties methods*******************************/
        public function insertProperty(string $strName, int $intStatus,int $isVisible,int $intOrder,array $arrFraming){

			$this->strName = $strName;
            $this->intStatus = $intStatus;
            $this->isVisible = $isVisible;
            $this->intOrder = $intOrder;
			$return = 0;

			$sql = "SELECT * FROM molding_props WHERE 
					name = '{$this->strName}'";
			$request = $this->select_all($sql);

			if(empty($request)){  
				$query_insert  = "INSERT INTO molding_props(name,status,is_material,order_view) 
								  VALUES(?,?,?,?)";
	        	$arrData = array($this->strName,$this->intStatus,$this->isVisible, $this->intOrder);
	        	$request_insert = $this->insert($query_insert,$arrData);
	        	$return = $request_insert;
                foreach ($arrFraming as $d) {
                    $sql = "INSERT INTO molding_props_framing(prop_id,framing_id,is_check) VALUES(?,?,?)";
                    $this->insert($sql,array($request_insert,$d['id'],$d['is_check']));
                }
			}else{
				$return = "exist";
			}
	        return $return;
		}
        public function updateProperty(int $intId,string $strName, int $intStatus, int $isVisible,int $intOrder,array $arrFraming){
            $this->intIdCategory = $intId;
            $this->strName = $strName;
            $this->intStatus = $intStatus;
            $this->isVisible = $isVisible;
            $this->intOrder = $intOrder;
			$sql = "SELECT * FROM molding_props WHERE name = '{$this->strName}' AND id != $this->intIdCategory";
			$request = $this->select_all($sql);

			if(empty($request)){

                $sql = "UPDATE molding_props SET  name=?, status=? ,is_material=?, order_view=? WHERE id = $this->intIdCategory";
                $arrData = array($this->strName,$this->intStatus,$this->isVisible,$this->intOrder);
				$request = $this->update($sql,$arrData);
                $this->delete("DELETE FROM molding_props_framing WHERE prop_id = $this->intIdCategory");
                foreach ($arrFraming as $d) {
                    $sql = "INSERT INTO molding_props_framing(prop_id,framing_id,is_check) VALUES(?,?,?)";
                    $this->insert($sql,array($this->intIdCategory,$d['id'],$d['is_check']));
                }
			}else{
				$request = "exist";
			}
			return $request;
		
		}
        public function deleteProperty($id){
            $this->intIdCategory = $id;
            $this->delete("DELETE FROM molding_props_framing WHERE prop_id = $this->intIdCategory");
            $sql = "DELETE FROM molding_props WHERE id = $this->intIdCategory";
            $return = $this->delete($sql);
            return $return;
        }
        public function selectProperties(){
            $sql = "SELECT * FROM molding_props ORDER BY id DESC";       
            $request = $this->select_all($sql);
            return $request;
        }
        public function selectProperty($id){
            $this->intIdCategory = $id;
            $sql = "SELECT * FROM molding_props WHERE id = $this->intIdCategory";
            $request = $this->select($sql);
            $sqlFraming = "SELECT s.idsubcategory as id, s.name, p.is_check
            FROM subcategory s
            INNER JOIN category c ON s.categoryid = c.idcategory 
            LEFT JOIN molding_props_framing p ON s.idsubcategory = p.framing_id AND prop_id = $this->intIdCategory
            WHERE c.name LIKE '%molduras%' ORDER BY s.idsubcategory DESC";
            $request['framing'] = $this->select_all($sqlFraming);
            return $request;
        }
        public function selectCatFraming(){
            $sql = "SELECT s.idsubcategory as id, s.name
            FROM subcategory s
            INNER JOIN category c ON c.idcategory = s.categoryid
            WHERE c.name = 'Molduras' AND c.status = 1 AND s.status = 1 ORDER BY s.idsubcategory DESC";
            $request = $this->select_all($sql);
            return $request;
        }
       
        /*************************Color methods*******************************/
        public function insertColor(string $strName,string $strColor,int $intStatus,$isVisible,$intOrder){

			$this->strColor = $strName;
			$this->strHexColor = $strColor;
            $this->intStatus = $intStatus;

			$return = 0;
			$sql = "SELECT * FROM moldingcolor WHERE name = '{$this->strColor}'";
			$request = $this->select_all($sql);

			if(empty($request))
			{ 
				$query_insert  = "INSERT INTO moldingcolor(name,color,status,is_visible,order_view) VALUES(?,?,?,?,?)";	  
	        	$arrData = array($this->strColor, $this->strHexColor,$this->intStatus,$isVisible,$intOrder);
	        	$request_insert = $this->insert($query_insert,$arrData);
	        	$return = $request_insert;
			}else{
				$return = "exist";
			}
	        return $return;
		}
        public function updateColor(int $intIdColor,string $strName,string $strColor,int $intStatus,$isVisible,$intOrder){
            $this->intIdColor = $intIdColor;
            $this->strColor = $strName;
			$this->strHexColor = $strColor;
            $this->intStatus = $intStatus;
            

			$sql = "SELECT * FROM moldingcolor WHERE name = '{$this->strColor}' AND id != $this->intIdColor";
			$request = $this->select_all($sql);

			if(empty($request)){

                $sql = "UPDATE moldingcolor SET name=?,color=?, status=?,is_visible=?,order_view=? WHERE id = $this->intIdColor";
                $arrData = array($this->strColor, $this->strHexColor,$this->intStatus,$isVisible,$intOrder);
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
    }
?>