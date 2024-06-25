<?php 
    class MarqueteriaCalculosModel extends Mysql{
        private $intId;
        private $arrData;
        public function __construct(){
            parent::__construct();
        }
        public function selectFrameConfig(int $intId, array $arrData){
            $this->arrData = $arrData;
            $this->intId = $intId;
            $request = array();
            $arrInfo = [];
            $total = count($this->arrData);
            for ($i=0; $i < $total ; $i++) { 
                $data = $this->arrData[$i];
                $sql_prop = "SELECT is_material,name FROM molding_props WHERE id = {$data['prop']}";
                $prop = $this->select($sql_prop);
                $sql_option ="SELECT * FROM molding_options WHERE prop_id = {$data['prop']} AND id = {$data['option_prop']}";
                $option = $this->select($sql_option);
                $material = [];
                 if($prop['is_material']){
                    $sql = "SELECT
                    m.type,
                    m.method,
                    m.product_id,
                    p.price_purchase,
                    p.name
                    FROM molding_materials m
                    INNER JOIN product p ON m.product_id = p.idproduct
                    WHERE m.option_id = {$option['id']}";
                    $material = $this->select_all($sql);
                    $totalMaterial = count($material);
                    for ($j=0; $j < $totalMaterial; $j++) { 
                        $sql = "SELECT
                        s.name,
                        p.value
                        FROM product_specs p
                        INNER JOIN specifications s ON p.specification_id = s.id_specification
                        WHERE p.product_id = {$material[$j]['product_id']}";
                        $material[$j]['variables'] = $this->select_all($sql);
                    }
                }
                 
                array_push($arrInfo,array("prop"=>$prop,"option"=>$option,"material"=>$material));
            }
            $request = array("data"=>$arrInfo,"frame"=>$this->selectFrame($this->intId));
            return $request;
        }
        public function selectFrame(int $intId){
            $this->intId = $intId;
            $sql = "SELECT p.price_purchase,s.name,p.reference
            FROM product p 
            INNER JOIN subcategory S ON s.idsubcategory = p.subcategoryid
            WHERE p.idproduct = $this->intId";
            $request = $this->select($sql);
            $request['name'] = strtolower($request['name']);
            $sqlWaste = "SELECT 
            p.value as waste 
            FROM product_specs p
            INNER JOIN specifications s ON p.specification_id = s.id_specification
            WHERE p.product_id = $this->intId";
            $request['waste'] = $this->select($sqlWaste)['waste'];
            return $request;
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
    }
?>