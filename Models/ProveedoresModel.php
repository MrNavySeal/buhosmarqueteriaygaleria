<?php 
    class ProveedoresModel extends Mysql{
        private $strName;
        private $intStatus;
        private $intIdCategory;
        public function __construct(){
            parent::__construct();
        }
        /*************************Category methods*******************************/
        public function insertCategory(string $strName,int $status){
			$this->strName = $strName;
            $this->intStatus = $status;
			$return = 0;
			$sql = "SELECT * FROM supplier_categories WHERE name = '{$this->strName}'";
			$request = $this->select_all($sql);

			if(empty($request)){ 
				$query_insert  = "INSERT INTO supplier_categories(name,status)  VALUES(?,?)";
	        	$arrData = array($this->strName,$this->intStatus);
	        	$request_insert = $this->insert($query_insert,$arrData);
	        	$return = $request_insert;
			}else{
				$return = "exist";
			}
	        return $return;
		}
        public function updateCategory(int $intIdCategory,string $strName,int $status){
            $this->intIdCategory = $intIdCategory;
            $this->strName = $strName;
            $this->intStatus = $status;

			$sql = "SELECT * FROM supplier_categories WHERE name = '{$this->strName}' AND id_categories != $this->intIdCategory";
			$request = $this->select_all($sql);
            $return = 0;
			if(empty($request)){
                
                $sql = "UPDATE supplier_categories SET name=?,status=? WHERE id_categories = $this->intIdCategory";
                $arrData = array($this->strName,$this->intStatus);
				$request = $this->update($sql,$arrData);
                $return = intval($request);
			}else{
				$return = "exist";
			}
			return $return;
		
		}
        public function deleteCategory($id){
            $this->intIdCategory = $id;
            $sql = "DELETE FROM supplier_categories WHERE id_categories = $this->intIdCategory";
            $request = $this->delete($sql);
            return $request;
        }
        public function selectCategories($flag=false){
            $status ="";
            if($flag)$status = "WHERE status = 1";
            $sql = "SELECT * FROM supplier_categories $status ORDER BY id_categories DESC";     
            $request = $this->select_all($sql);
            return $request;
        }
        public function selectCountries(){
            $sql = "SELECT * FROM countries WHERE id = 47";
            $request = $this->select($sql);
            return $request;
        }
        public function selectStates($country){
            $request = $this->select_all("SELECT * FROM states WHERE country_id = $country");
            return $request;
        }
        public function selectCities($state){
            $request = $this->select_all("SELECT * FROM cities WHERE state_id = $state");
            return $request;
        }
    }
?>