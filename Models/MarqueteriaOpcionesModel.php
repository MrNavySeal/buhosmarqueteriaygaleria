<?php 
    class MarqueteriaOpcionesModel extends Mysql{
        private $intId;
        private $intIdProp;
        private $intStatus;
        private $strName;
        private $isMargin;
        private $isColor;
        private $isFrame;
        private $intMargin;
        private $isBocel;
        private $isVisible;
        private $intOrder;
        private $strTag;
        private $strTagFrame;
        public function __construct(){
            parent::__construct();
        }
        /*************************Properties methods*******************************/
        public function insertOption(string $strName, int $intStatus,int $intIdProp, int $isMargin,int $isColor, int $isFrame,
        int $intMargin, int $isBocel,int $isVisible,int $intOrder,string $strTag,string $strTagFrame){

			$this->strName = $strName;
            $this->intStatus = $intStatus;
            $this->intIdProp = $intIdProp;
            $this->isMargin = $isMargin;
            $this->isColor = $isColor;
            $this->isFrame = $isFrame;
            $this->intMargin = $intMargin;
            $this->isBocel = $isBocel;
            $this->isVisible = $isVisible;
            $this->intOrder = $intOrder;
            $this->strTag = $strTag;
            $this->strTagFrame = $strTagFrame;
			$return = 0;

			$sql = "SELECT * FROM molding_options WHERE 
					name = '{$this->strName}' AND prop_id = $this->intIdProp";
			$request = $this->select_all($sql);
			if(empty($request))
			{ 
				$query_insert  = "INSERT INTO molding_options(name,status,prop_id,is_margin,is_color,is_frame,margin,is_bocel,is_visible,order_view,tag,tag_frame) 
								  VALUES(?,?,?,?,?,?,?,?,?,?,?,?)";
	        	$arrData = array($this->strName,$this->intStatus,$this->intIdProp,
                $this->isMargin,$this->isColor,$this->isFrame,$this->intMargin,$this->isBocel,$this->isVisible,$this->intOrder,$this->strTag,$this->strTagFrame);
	        	$request_insert = $this->insert($query_insert,$arrData);
	        	$return = $request_insert;
			}else{
				$return = "exist";
			}
	        return $return;
		}
        public function updateOption(int $intId,string $strName, int $intStatus, int $intIdProp,int $isMargin,int $isColor, 
        int $isFrame,int $intMargin,int $isBocel,int $isVisible,int $intOrder,string $strTag,string $strTagFrame){
            $this->intId = $intId;
            $this->strName = $strName;
            $this->strTagFrame = $strTagFrame;
            $this->intStatus = $intStatus;
            $this->intIdProp = $intIdProp;
            $this->isMargin = $isMargin;
            $this->isColor = $isColor;
            $this->isFrame = $isFrame;
            $this->intMargin = $intMargin;
            $this->isBocel = $isBocel;
            $this->isVisible = $isVisible;
            $this->intOrder = $intOrder;
            $this->strTag = $strTag;
			$sql = "SELECT * FROM molding_options WHERE name = '{$this->strName}' AND prop_id = $this->intIdProp AND id != $this->intId";
			$request = $this->select_all($sql);

			if(empty($request)){

                $sql = "UPDATE molding_options SET name=?, status=? ,prop_id=?,is_margin=?,is_color=?,is_frame=?,margin=?,is_bocel=?,is_visible=?,order_view=?,tag=?,tag_frame=?
                WHERE id = $this->intId";
                $arrData = array($this->strName,$this->intStatus,$this->intIdProp,
                $this->isMargin,$this->isColor,$this->isFrame,$this->intMargin,$this->isBocel,$this->isVisible,$this->intOrder,$this->strTag,$this->strTagFrame);
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
            INNER JOIN molding_props p ON p.id = o.prop_id 
            ORDER BY o.id DESC";       
            $request = $this->select_all($sql);
            $total = count($request);
            for ($i=0; $i < $total; $i++) { 
                $option = $request[$i];
                if($option['is_material']){
                    $sql = "SELECT 
                    m.product_id as idproduct,
                    m.type,
                    m.method,
                    m.factor,
                    p.name
                    FROM molding_materials m 
                    INNER JOIN product p ON p.idproduct = m.product_id
                    WHERE m.option_id = {$option['id']}";
                    $option['materials'] = $this->select_all($sql);
                    $request[$i] = $option;
                }
            }
            return $request;
        }
        public function selectOption($id){
            $this->intId = $id;
            $sql = "SELECT * FROM molding_options WHERE id = $this->intId";
            $request = $this->select($sql);
            return $request;
        }
        /*************************Material methods*******************************/
        public function insertMaterial(int $id,array $data){
            $this->intId = $id;
            $this->delete("DELETE FROM molding_materials WHERE option_id = $this->intId");
            $total = count($data);
            for ($i=0; $i < $total ; $i++) { 
                $intFactor = $data[$i]['factor'] > 0 ? $data[$i]['factor'] : 1;
                $sql = "INSERT INTO molding_materials(option_id,product_id,type,method,factor) VALUES(?,?,?,?,?)";
                $arrData = array($this->intId,$data[$i]['idproduct'],$data[$i]['type'],$data[$i]['method'],$data[$i]['factor']);
                $request = $this->insert($sql,$arrData);
            }
            return $request;
        }
        public function selectMaterials(){
            $sql = "SELECT 
                p.idproduct,
                p.name
            FROM product p
            INNER JOIN category c ON p.categoryid = c.idcategory
            WHERE c.name='Materiales' AND p.status = 1";
            $request = $this->select_all($sql);
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