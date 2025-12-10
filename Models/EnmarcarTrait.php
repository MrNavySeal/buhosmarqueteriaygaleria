<?php
    require_once("Libraries/Core/Mysql.php");
    trait EnmarcarTrait{
        private $con;
        private $intId;
        private $strRoute;

        public function selectTipos(){
            $this->con = new Mysql();
            $sql = "SELECT * FROM moldingcategory WHERE status = 1 AND is_visible = 1 ORDER BY id ASC";       
            $request = $this->con->select_all($sql);
            return $request;
        }
        
        public function selectTipo($route){
            $this->con = new Mysql();
            $sql = "SELECT * FROM moldingcategory WHERE status = 1 AND route = '$route'";
            $request = $this->con->select($sql);
            return $request;
        }

        public function selectExamples(int $intId){
            $this->intId = $intId;
            $this->con = new Mysql();
            $sql = "SELECT * FROM molding_examples WHERE status = 1 AND config = $this->intId ORDER BY order_view";
            $request = $this->con->select_all($sql);
            return $request;
        }

        public function selectConfig(string $strRoute){
            $this->con = new Mysql();
            $this->strRoute = $strRoute;
            $sql = "SELECT 
            co.id,
            co.category_id,
            co.is_frame,
            co.is_print,
            co.is_cost,
            co.img,
            c.name,
            c.route
            FROM molding_config co
            INNER JOIN moldingcategory c ON c.id = co.category_id
            WHERE c.route = '$this->strRoute' AND c.status = 1 AND c.is_visible = 1";
            $request = $this->con->select($sql);
            if(!empty($request)){
                $request_frames = $this->selectConfigFrame($request['id']);
                if(!empty($request_frames)){
                    $total = count($request_frames);
                    for ($i=0; $i < $total; $i++) { 
                        $arrFrames = $request_frames[$i]['frames'];
                        $totalFrames = count($arrFrames);
                        for ($j=0; $j < $totalFrames; $j++) { 
                            $arrFrames[$j]['framing_img'] = "url(".media().'/images/uploads/'.$arrFrames[$j]['framing_img'].") 40% repeat";
                        }
                        $request_frames[$i]['frames'] = $arrFrames;
                    }
                }
                $arrProps = $this->selectConfigProps($request['id']);
                if(!empty($arrProps)){
                    $request['detail']['molding'] = $request_frames;
                    $request['detail']['props'] = $arrProps;
                    $request['url'] = media()."/images/uploads/".$request['img'];
                }
            }
            return $request;
        }

        public function selectConfigFrame(int $id){
            $this->con = new Mysql();
            $this->intId = $id;
            $sql = "SELECT 
            f.topic, 
            f.prop, 
            f.is_check,
            s.name,
            s.idsubcategory
            FROM molding_config_frame f 
            INNER JOIN subcategory s ON f.prop = s.idsubcategory
            WHERE f.topic = 1 AND f.config_id = $this->intId AND f.is_check = 1 ORDER BY s.name";
            $request= $this->con->select_all($sql);
            if(!empty($request)){
                $total = count($request);
                for ($i=0; $i < $total ; $i++) { 
                    $sql = "SELECT 
                    idproduct,
                    reference,
                    name,
                    price,
                    price_purchase,
                    discount,
                    stock,
                    status,
                    product_type,
                    is_stock,
                    subcategoryid,
                    framing_img
                    FROM product p
                    WHERE subcategoryid = {$request[$i]['prop']} AND status = 1
                    ORDER BY p.idproduct DESC
                    ";
                    $arrProducts = $this->con->select_all($sql);
                    $totalProducts = count($arrProducts);
                    for ($j=0; $j < $totalProducts; $j++) { 
                        $idProduct = $arrProducts[$j]['idproduct'];
                        $sqlImg = "SELECT * FROM productimage WHERE productid = $idProduct";
                        $requestImg = $this->con->select_all($sqlImg);
                        $totalImg = count($requestImg);
                        for ($k=0; $k < $totalImg; $k++) { 
                            $requestImg[$k]['image'] = media()."/images/uploads/".$requestImg[$k]['name'];
                        }
                        $sqlWaste = "SELECT 
                        p.value as waste 
                        FROM product_specs p
                        INNER JOIN specifications s ON p.specification_id = s.id_specification
                        WHERE p.product_id = $idProduct";
                        $arrProducts[$j]['waste'] = $this->con->select($sqlWaste)['waste'];
                        $arrProducts[$j]['images'] = $requestImg;
                        $arrProducts[$j]['image'] = $requestImg[0]['image'];
                    }
                    usort($arrProducts,function($a,$b){return $b['waste'] < $a['waste'];});
                    $request[$i]['frames'] = $arrProducts;
                }
            }
            return $request;
        }

        public function selectConfigProps(int $id){
            $this->con = new Mysql();
            $this->intId = $id;
            $sql = "SELECT 
            f.topic, 
            f.prop, 
            f.is_check,
            p.name
            FROM molding_config_frame f 
            INNER JOIN molding_props p ON f.prop = p.id
            WHERE f.config_id = $this->intId AND f.topic = 2 AND f.is_check = 1 
            AND p.status = 1 ORDER BY p.order_view";
            $request= $this->con->select_all($sql);
            if(!empty($request)){
                $total = count($request);
                for ($i=0; $i < $total; $i++) { 
                    $sql = "SELECT * FROM molding_options WHERE status = 1 AND is_visible=1 AND prop_id = {$request[$i]['prop']} ORDER BY order_view";
                    $sql_framing = "SELECT 
                    s.name,
                    f.framing_id
                    FROM molding_props_framing f
                    INNER JOIN subcategory s ON f.framing_id = s.idsubcategory
                    WHERE f.prop_id = {$request[$i]['prop']} AND is_check = 1";
                    $arrOptions = $this->con->select_all($sql);
                    if(!empty($arrOptions)){
                        $request[$i]['options'] = $arrOptions;
                        $arrPropsConfig = $this->con->select_all($sql_framing);
                        for ($j=0; $j < count($arrPropsConfig) ; $j++) { 
                            $arrPropsConfig[$j]['attribute'] = 'data-'.$j.'="'.$arrPropsConfig[$j]['name'].'"';
                        }
                        $request[$i]['attributes'] = $arrPropsConfig;
                    }else{

                        $request[$i] = [];
                    }
                }
            }
            return $request;
        }

        public function selectColors(){
            $this->con = new Mysql();
            $sql = "SELECT * FROM moldingcolor WHERE status = 1 AND is_visible = 1 ORDER BY order_view";       
            $request = $this->con->select_all($sql);
            return $request;
        }

        public function selectFrameConfig(int $intId, array $arrData){
            $this->con = new Mysql();
            $this->arrData = $arrData;
            $this->intId = $intId;
            $request = array();
            $arrInfo = [];
            $arrDisabledProps = [];
            $total = count($this->arrData);
            for ($i=0; $i < $total ; $i++) { 
                $data = $this->arrData[$i];

                $sql_option ="SELECT * FROM molding_options WHERE prop_id = {$data['prop']} AND id = {$data['option_prop']}";
                $option = $this->con->select($sql_option);

                $disabledProps = $option['disabled_props'] != "" ? json_decode($option['disabled_props'],true) : [];

                if(!empty($disabledProps)){
                    foreach ($disabledProps as $disabled) { array_push($arrDisabledProps,$disabled['id']);}
                }

                if(!in_array($data['prop'],$arrDisabledProps)){
                    $sql_prop = "SELECT is_material,name FROM molding_props WHERE id = {$data['prop']}";
                    $prop = $this->con->select($sql_prop);
                    $material = [];

                    if($prop['is_material']){
                        $sql = "SELECT
                        m.type,
                        m.method,
                        m.factor,
                        m.product_id,
                        p.price_purchase,
                        p.name
                        FROM molding_materials m
                        INNER JOIN product p ON m.product_id = p.idproduct
                        WHERE m.option_id = {$option['id']}";
                        $material = $this->con->select_all($sql);
                        $totalMaterial = count($material);
                        for ($j=0; $j < $totalMaterial; $j++) { 
                            $sql = "SELECT
                            s.name,
                            p.value
                            FROM product_specs p
                            INNER JOIN specifications s ON p.specification_id = s.id_specification
                            WHERE p.product_id = {$material[$j]['product_id']}";
                            $material[$j]['variables'] = $this->con->select_all($sql);
                        }
                        array_push($arrInfo,array("prop"=>$prop,"option"=>$option,"material"=>$material));
                    }
                }
            }
            $request = array("data"=>$arrInfo,"frame"=>$this->selectFrame($this->intId));
            return $request;
        }

        public function selectFrame(int $intId){
            $this->con = new Mysql();
            $this->intId = $intId;

            $sql = "SELECT p.price_purchase,s.name,p.reference
            FROM product p 
            INNER JOIN subcategory s ON s.idsubcategory = p.subcategoryid
            WHERE p.idproduct = $this->intId";
            $request = $this->con->select($sql);

            $request['name'] = strtolower($request['name']);
            $sqlWaste = "SELECT 
            p.value as waste 
            FROM product_specs p
            INNER JOIN specifications s ON p.specification_id = s.id_specification
            WHERE p.product_id = $this->intId";
            $request['waste'] = $this->con->select($sqlWaste)['waste'];

            $request['wholesale'] = $this->con->select_all("SELECT min,max,percent FROM product_wholesale_discount WHERE product_id = $this->intId");
            return $request;
        }

        public function selectCategory(int $intId){
            $this->con = new Mysql();
            $this->intId = $intId;
            $sql = "SELECT m.is_print,c.name,c.image,c.route
            FROM molding_config m 
            INNER JOIN moldingcategory c ON c.id = m.category_id
            WHERE m.category_id = $this->intId";
            $request = $this->con->select($sql);
            return $request;
        }

        public function selectBackgrounds(){
            $this->con = new Mysql();
            $sql = "SELECT * FROM molding_background WHERE status = 1";
            $request = $this->con->select_all($sql);
            return $request;
        }
    }
    
?>