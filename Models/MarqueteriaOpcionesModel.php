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
            o.name,
            p.is_material
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
        public function selectMaterials(){
            $sql = "SELECT 
                p.idproduct,
                p.name,
                p.measure,
                p.price,
                p.price_purchase,
                p.discount,
                p.stock,
                p.status,
                p.product_type,
                p.is_stock
            FROM product p
            INNER JOIN category c ON p.categoryid = c.idcategory
            WHERE c.name='Materiales' AND p.status = 1";
            $request = $this->select_all($sql);
            if(!empty($request)){
                for ($i=0; $i < count($request); $i++) { 
                    $data = $request[$i];
                    $this->intId = $data['idproduct'];
                    $sqlSpecs = "SELECT p.specification_id as id,p.value,s.name
                    FROM product_specs p
                    INNER JOIN specifications s
                    ON p.specification_id = s.id_specification
                    WHERE p.product_id = {$this->intId}";
                    $request[$i]['specs'] = $this->select_all($sqlSpecs);
                    if($request[$i]['product_type'] == 1){
                        $request[$i]['variation'] = $this->select("SELECT * FROM product_variations WHERE product_id = $this->intId");
                        $request[$i]['variation']['variation'] = json_decode($request[$i]['variation']['variation']);
                        $request[$i]['options'] = $this->select_all("SELECT * FROM product_variations_options WHERE product_id = $this->intId");
                    }
                }
            }
            return $request;
        }
    }
?>