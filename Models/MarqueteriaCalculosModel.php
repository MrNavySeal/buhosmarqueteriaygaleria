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
                $prop = $this->select("SELECT is_material FROM molding_props WHERE id = {$data['prop']}");
                $option = $this->select("SELECT * FROM molding_options WHERE prop_id = {$data['prop']} AND id = {$data['option_prop']}");
                $material = [];
                 if($prop['is_material']){
                    $sql = "SELECT
                    m.type,
                    m.product_id,
                    p.price_purchase
                    FROM molding_materials m
                    INNER JOIN product p ON m.product_id = p.idproduct
                    WHERE m.option_id = {$option['id']}";
                    $material = $this->select_all($sql);
                    $totalMaterial = count($material);
                    for ($j=0; $j < $totalMaterial; $j++) { 
                        $sql = "SELECT
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
            $sql = "SELECT price_purchase FROM product WHERE idproduct = $this->intId";
            $request['price_purchase'] = $this->select($sql)['price_purchase'];
            $sqlWaste = "SELECT 
            p.value as waste 
            FROM product_specs p
            INNER JOIN specifications s ON p.specification_id = s.id_specification
            WHERE p.product_id = $this->intId";
            $request['waste'] = $this->select($sqlWaste)['waste'];
            return $request;
        }
    }
?>