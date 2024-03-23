<?php 
    class ProductosOpcionesModel extends Mysql{
        private $intIdMeasure;
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
        
    }
?>