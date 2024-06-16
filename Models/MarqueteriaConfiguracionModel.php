<?php 
    class MarqueteriaConfiguracionModel extends Mysql{
        private $intId;
        private $isCost;
        private $isFrame;
        private $isPrint;
        private $strImg;
        private $arrData;
        public function __construct(){
            parent::__construct();
        }
        public function selectCategories(){
            $sql = "SELECT * FROM moldingcategory WHERE status = 1 ORDER BY id DESC";       
            $request = $this->select_all($sql);
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
        public function selectProperties(){
            $sql = "SELECT * FROM molding_props WHERE status = 1 ORDER BY name";       
            $request = $this->select_all($sql);
            return $request;
        }
        public function updateConfig(int $intId,int $isCost ,int $isFrame,int $isPrint,string $strImg){
            $this->intId = $intId;
            $this->isCost = $isCost;
            $this->isFrame = $isFrame;
            $this->isPrint = $isPrint;
            $this->strImg = $strImg;

            $this->delete("DELETE FROM molding_config WHERE category_id = $this->intId");
            $sql = "INSERT INTO molding_config(category_id,is_frame,is_print,is_cost,img)
            VALUES(?,?,?,?,?)";
            $arrData = array($this->intId,$this->isFrame,$this->isPrint,$this->isCost,$this->strImg);
            $request = $this->insert($sql,$arrData);
            return $request;
        }
        public function insertPropConfig(int $intId,array $arrData){
            $this->intId = $intId;
            $this->arrData = $arrData;
            $request = 0;
            foreach ($this->arrData as $data) {
                $sql = "INSERT INTO molding_config_frame(config_id,topic,prop,is_check) VALUES(?,?,?,?)";
                $arrData = array($this->intId,$data['topic'],$data['prop'],$data['is_check']);
                $request = $this->insert($sql,$arrData);
            }
            return $request;
        }
        public function selectConfig(int $id){
            $this->intId = $id;
            $sql = "SELECT * FROM molding_config  WHERE category_id = $this->intId";
            $request = $this->select($sql);
            if(!empty($request)){
                $sql = "SELECT * FROM molding_config_frame WHERE config_id = {$request['id']}";
                $request['props'] = $this->select_all($sql);
                $request['url'] = media()."/images/uploads/".$request['img'];
            }
            return $request;
        }
        
    }
?>