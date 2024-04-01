<?php 
    class ProductosOpcionesModel extends Mysql{
        private $intIdMeasure;
        private $intIdSpec;
        private $intId;
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

        /*************************Variants methods*******************************/
        public function insertVariant(array $arrData){
            $this->arrData = $arrData;
			$return = 0;
			$sql = "SELECT * FROM variations WHERE name = '{$this->arrData['name']}'";
			$request = $this->select_all($sql);

			if(empty($request)){ 
				$sql  = "INSERT INTO variations(name,status)  VALUES(?,?)";
	        	$arrData = array(
                    $this->arrData['name'],
                    $this->arrData['status']
                );
	        	$request = $this->insert($sql,$arrData);
                $this->insertOptions($request,$this->arrData['options']);
	        	$return = $request;
			}else{
				$return = "exist";
			}
	        return $return;
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
        public function updateVariant(int $id,array $arrData){
            $this->intId = $id;
            $this->arrData = $arrData;

			$sql = "SELECT * FROM variations WHERE name = '{$this->arrData['name']}' AND id_variation != $this->intId";
			$request = $this->select_all($sql);
            $return = 0;
			if(empty($request)){
                
                $sql = "UPDATE variations SET name=?,status=? WHERE id_variation = $this->intId";
                $arrData = array(
                    $this->arrData['name'],
                    $this->arrData['status']
                );
                $request = $this->update($sql,$arrData);
                $this->insertOptions($id,$this->arrData['options']);
	        	$return = intval($request);
			}else{
				$return = "exist";
			}
			return $return;
		
		}
        public function deleteVariant($id){
            $this->intId = $id;
            $sql = "DELETE FROM variations WHERE id_variation = $this->intId";
            $request = $this->delete($sql);
            return $request;
        }
        public function selectVariant($id){
            $this->intId = $id;
            $sql = "SELECT * FROM variations WHERE id_variation = $this->intId";
            $request = $this->select($sql);
            $sql="SELECT * FROM variation_options WHERE variation_id = $this->intId";
            $request['options'] = $this->select_all($sql);
            return $request;
        }
        public function selectVariants(){
            $sql = "SELECT * FROM variations";
            $request = $this->select_all($sql);
            if(!empty($request)){
                $total = count($request);
                for ($i=0; $i < $total; $i++) { 
                    $id = $request[$i]['id_variation'];
                    $sql ="SELECT count(*) as total FROM variation_options WHERE variation_id = $id";
                    $request[$i]['qty'] = $this->select($sql)['total']." opciones";
                }
            }
            return $request;
        }
        
    }
?>