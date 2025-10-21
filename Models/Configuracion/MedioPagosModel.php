<?php 
    class MedioPagosModel extends Mysql{
        private $intIdMeasure;
        private $intIdSpec;
        private $intId;
        private $arrData;
        public function __construct(){
            parent::__construct();
        }
        /*************************Specs methods*******************************/
        public function insertSpec(array $arrData){
            $this->arrData = $arrData;
			$return = 0;
			$sql = "SELECT * FROM payment_type WHERE name = '{$this->arrData['name']}'";
			$request = $this->select_all($sql);

			if(empty($request)){ 
				$query_insert  = "INSERT INTO payment_type(name,status)  VALUES(?,?)";
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

			$sql = "SELECT * FROM payment_type WHERE name = '{$this->arrData['name']}' AND id != $this->intIdSpec";
			$request = $this->select_all($sql);
            $return = 0;
			if(empty($request)){
                
                $sql = "UPDATE payment_type SET name=?,status=? WHERE id = $this->intIdSpec";
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
            $sql = "DELETE FROM payment_type WHERE id = $this->intIdSpec";
            $request = $this->delete($sql);
            return $request;
        }
        public function selectSpec($id){
            $this->intIdSpec = $id;
            $sql = "SELECT * FROM payment_type WHERE id = $this->intIdSpec";
            $request = $this->select($sql);
            return $request;
        }
        public function selectSpecs(){
            $sql = "SELECT * FROM payment_type";
            $request = $this->select_all($sql);
            return $request;
        }
    }
?>