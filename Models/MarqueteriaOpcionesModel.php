<?php 
    class MarqueteriaOpcionesModel extends Mysql{
        private $intId;
        private $intIdProp;
        private $intStatus;
        private $strName;
        
        public function __construct(){
            parent::__construct();
        }
        /*************************Properties methods*******************************/
        public function insertOption(string $strName, int $intStatus,int $intIdProp){

			$this->strName = $strName;
            $this->intStatus = $intStatus;
            $this->intIdProp = $intIdProp;
			$return = 0;

			$sql = "SELECT * FROM molding_options WHERE 
					name = '{$this->strName}' AND prop_id = $this->intIdProp";
			$request = $this->select_all($sql);
			if(empty($request))
			{ 
				$query_insert  = "INSERT INTO molding_options(name,status,prop_id) 
								  VALUES(?,?,?)";
	        	$arrData = array($this->strName,$this->intStatus,$this->intIdProp);
	        	$request_insert = $this->insert($query_insert,$arrData);
	        	$return = $request_insert;
			}else{
				$return = "exist";
			}
	        return $return;
		}
        public function updateOption(int $intId,string $strName, int $intStatus, int $intIdProp){
            $this->intId = $intId;
            $this->strName = $strName;
            $this->intStatus = $intStatus;
            $this->intIdProp = $intIdProp;
			$sql = "SELECT * FROM molding_options WHERE name = '{$this->strName}' AND prop_id = $this->intIdProp AND id != $this->intId";
			$request = $this->select_all($sql);

			if(empty($request)){

                $sql = "UPDATE molding_options SET name=?, status=? ,prop_id=? WHERE id = $this->intId";
                $arrData = array($this->strName,$this->intStatus,$this->intIdProp);
				$request = intval($this->update($sql,$arrData));
			}else{
				$request = "exist";
			}
			return $request;
		
		}
        public function deleteOption($id){
            $this->intId = $id;
            $sql = "DELETE FROM molding_options WHERE id = $this->intId";
            $return = $this->delete($sql);
            return $return;
        }
        public function selectOptions(){
            $sql = "SELECT 
            o.id,
            p.name as property,
            o.status,
            o.name
            FROM molding_options o
            INNER JOIN molding_props p
            ON p.id = o.prop_id 
            ORDER BY o.id DESC";       
            $request = $this->select_all($sql);
            return $request;
        }
        public function selectOption($id){
            $this->intId = $id;
            $sql = "SELECT * FROM molding_options WHERE id = $this->intId";
            $request = $this->select($sql);
            return $request;
        }
        /*************************Properties methods*******************************/
        public function selectProperties(){
            $sql = "SELECT * FROM molding_props WHERE status = 1 ORDER BY name";       
            $request = $this->select_all($sql);
            return $request;
        }
    }
?>