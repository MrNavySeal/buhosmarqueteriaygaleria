<?php 
    class MarqueteriaEjemplosModel extends Mysql{
        private $intId;
        private $intStatus;
        private $arrData;
        private $strImg;
        public function __construct(){
            parent::__construct();
        }
        public function selectCategory(int $intId){
            $this->intId = $intId;
            $sql = "SELECT m.is_print,c.name
            FROM molding_config m 
            INNER JOIN moldingcategory c ON c.id = m.category_id
            WHERE m.category_id = $this->intId";
            $request = $this->select($sql);
            return $request;
        }
        public function selectExamples(){
            $sql = "SELECT *,DATE_FORMAT(created_at,'%d/%m/%Y') as date FROM molding_examples";
            $request = $this->select_all($sql);
            if(!empty($request)){
                $total = count($request);
                for ($i=0; $i < $total ; $i++) { 
                    $img = $request[$i]['img'] != "" ? $request[$i]['img'] : "category.jpg";
                    $request[$i]['img'] = media()."/images/uploads/".$img;
                }
            }
            return $request;
        }
        public function selectExample($id){
            $this->intId = $id;
            $sql = "SELECT id,name,specs,img,status,DATE_FORMAT(created_at,'%d/%m/%Y') as date FROM molding_examples WHERE id = $this->intId";
            $request = $this->select($sql);
            $request['specs'] = json_decode($request['specs'],true);
            return $request;
        }
        public function insertExample(int $intId,int $intStatus){
            $this->intId = $intId;
            $this->intStatus = $intStatus;
            $sql = "INSERT INTO molding_examples(img,status) VALUES(?,?)";
            $arrData = array($this->intId,$this->intStatus);
            $request = $this->insert($sql,$arrData);
            return $request;
        }
        public function updateExample(int $intId,string $strImg,int $intStatus){
            $this->intId = $intId;
            $this->intStatus = $intStatus;
            $this->strImg = $strImg;
            $sql = "UPDATE molding_examples SET img=?,status=?,updated_at=NOW() WHERE id = $this->intId";
            $arrData = array($this->strImg,$this->intStatus);
            $request = $this->update($sql,$arrData);
            return $request;
        }
        public function deleteExample($id){
            $this->intId = $id;
            $sql = "DELETE FROM molding_examples WHERE id = $this->intId";
            $return = $this->delete($sql);
            return $return;
        }
    }
?>