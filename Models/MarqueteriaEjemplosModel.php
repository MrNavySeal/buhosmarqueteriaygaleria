<?php 
    class MarqueteriaEjemplosModel extends Mysql{
        private $intId;
        private $arrData;
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
            $sql = "SELECT * FROM molding_examples";
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
    }
?>