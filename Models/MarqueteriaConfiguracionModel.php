<?php 
    class MarqueteriaConfiguracionModel extends Mysql{
        private $intId;
        
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
        
    }
?>