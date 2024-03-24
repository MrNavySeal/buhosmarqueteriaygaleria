<?php 
    class ProductosOpcionesModel extends Mysql{
        private $intIdMeasure;
        private $intIdSpec;
        private $arrData;
        public function __construct(){
            parent::__construct();
        }
        /*************************Measures methods*******************************/
        public function insertMeasure(array $arrData){
            $this->arrData = $arrData;
			$return = 0;
			$sql = "SELECT * FROM measures WHERE name = '{$this->arrData['name']}'";
			$request = $this->select_all($sql);

			if(empty($request)){ 
				$query_insert  = "INSERT INTO measures(name,initials,status)  VALUES(?,?,?)";
	        	$arrData = array(
                    $this->arrData['name'],
                    $this->arrData['initials'],
                    $this->arrData['status']
                );
	        	$request_insert = $this->insert($query_insert,$arrData);
	        	$return = $request_insert;
			}else{
				$return = "exist";
			}
	        return $return;
		}
        public function updateMeasure(int $intIdMeasure,array $arrData){
            $this->intIdMeasure = $intIdMeasure;
            $this->arrData = $arrData;

			$sql = "SELECT * FROM measures WHERE name = '{$this->arrData['name']}' AND id_measure != $this->intIdMeasure";
			$request = $this->select_all($sql);
            $return = 0;
			if(empty($request)){
                
                $sql = "UPDATE measures SET name=?,initials=?,status=? WHERE id_measure = $this->intIdMeasure";
                $arrData = array(
                    $this->arrData['name'],
                    $this->arrData['initials'],
                    $this->arrData['status']
                );
				$request = $this->update($sql,$arrData);
                $return = intval($request);
			}else{
				$return = "exist";
			}
			return $return;
		
		}
        public function deleteMeasure($id){
            $this->intIdMeasure = $id;
            $sql = "DELETE FROM measures WHERE id_measure = $this->intIdMeasure";
            $request = $this->delete($sql);
            return $request;
        }
        public function selectMeasure($id){
            $this->intIdMeasure = $id;
            $sql = "SELECT * FROM measures WHERE id_measure = $this->intIdMeasure";
            $request = $this->select($sql);
            return $request;
        }
        public function selectMeasures(){
            $sql = "SELECT * FROM measures";
            $request = $this->select_all($sql);
            return $request;
        }
        /*************************Specs methods*******************************/
        public function insertSpec(array $arrData){
            $this->arrData = $arrData;
			$return = 0;
			$sql = "SELECT * FROM specifications WHERE name = '{$this->arrData['name']}'";
			$request = $this->select_all($sql);

			if(empty($request)){ 
				$query_insert  = "INSERT INTO specifications(name,status)  VALUES(?,?)";
	        	$arrData = array(
                    $this->arrData['name'],
                    $this->arrData['status']
                );
	        	$request_insert = $this->insert($query_insert,$arrData);
	        	$return = $request_insert;
			}else{
				$return = "exist";
			}
	        return $return;
		}
        public function updateSpec(int $id,array $arrData){
            $this->intIdSpec = $id;
            $this->arrData = $arrData;

			$sql = "SELECT * FROM specifications WHERE name = '{$this->arrData['name']}' AND id_specification != $this->intIdSpec";
			$request = $this->select_all($sql);
            $return = 0;
			if(empty($request)){
                
                $sql = "UPDATE specifications SET name=?,status=? WHERE id_specification = $this->intIdSpec";
                $arrData = array(
                    $this->arrData['name'],
                    $this->arrData['status']
                );
				$request = $this->update($sql,$arrData);
                $return = intval($request);
			}else{
				$return = "exist";
			}
			return $return;
		
		}
        public function deleteSpec($id){
            $this->intIdSpec = $id;
            $sql = "DELETE FROM specifications WHERE id_specification = $this->intIdSpec";
            $request = $this->delete($sql);
            return $request;
        }
        public function selectSpec($id){
            $this->intIdSpec = $id;
            $sql = "SELECT * FROM specifications WHERE id_specification = $this->intIdSpec";
            $request = $this->select($sql);
            return $request;
        }
        public function selectSpecs(){
            $sql = "SELECT * FROM specifications";
            $request = $this->select_all($sql);
            return $request;
        }
        
    }
?>