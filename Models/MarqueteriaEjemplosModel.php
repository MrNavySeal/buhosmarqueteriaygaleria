<?php 
    class MarqueteriaEjemplosModel extends Mysql{
        private $intId;
        private $intStatus;
        private $arrData;
        private $strImg;
        private $strName;
        private $strDate;
        private $intOrder;
        private $isVisible;
        private $strDescription;
        private $strAddress;
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
            $sql = "SELECT *,DATE_FORMAT(created_at,'%d/%m/%Y') as date FROM molding_examples WHERE id = $this->intId";
            $request = $this->select($sql);
            $request['specs'] = json_decode($request['specs'],true);
            return $request;
        }
        public function insertExample(string $strImg,int $intStatus,string $strDate,string $strName,array $arrData,int $intOrder,int $isVisible,string $strDescription,string $strAddress){
            $this->intStatus = $intStatus;
            $this->strImg = $strImg;
            $this->strDate = $strDate;
            $this->strName = $strName;
            $this->arrData = $arrData;
            $this->intOrder = $intOrder;
            $this->isVisible = $isVisible;
            $this->strDescription = $strDescription;
            $this->strAddress = $strAddress;
            $sql = "INSERT INTO molding_examples
            ( 
            config,
            frame,
            margin,
            height,
            width,
            orientation,
            color_frame,
            color_margin,
            color_border,
            props,
            img,
            status,
            created_at,
            name,
            total,
            type_frame,
            specs,
            order_view,
            is_visible,
            description,
            address
            ) 
            VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $arrData = array(
                $this->arrData['id_config'],
                $this->arrData['id'],
                $this->arrData['margin'],
                $this->arrData['height'],
                $this->arrData['width'],
                $this->arrData['orientation'],
                $this->arrData['color_frame_id'],
                $this->arrData['color_margin_id'],
                $this->arrData['color_border_id'],
                json_encode($this->arrData['config'],JSON_UNESCAPED_UNICODE),
                $this->strImg,
                $this->intStatus,
                $this->strDate,
                $this->strName,
                $this->arrData['price_sell'],
                $this->arrData['type_frame'],
                json_encode($this->arrData['data'],JSON_UNESCAPED_UNICODE),
                $this->intOrder,
                $this->isVisible,
                $this->strDescription,
                $this->strAddress
            );
            $request = $this->insert($sql,$arrData);
            return $request;
        }
        public function updateExample(int $intId,string $strImg,int $intStatus,string $strDate,string $strName,array $arrData,int $intOrder,int $isVisible,string $strDescription,string $strAddress){
            $this->intId = $intId;
            $this->intStatus = $intStatus;
            $this->strImg = $strImg;
            $this->strDate = $strDate;
            $this->strName = $strName;
            $this->arrData = $arrData;
            $this->intOrder = $intOrder;
            $this->isVisible = $isVisible;
            $this->strDescription = $strDescription;
            $this->strAddress = $strAddress;
            $sql = "UPDATE molding_examples SET 
            config=?,
            frame=?,
            margin=?,
            height=?,
            width=?,
            orientation=?,
            color_frame=?,
            color_margin=?,
            color_border=?,
            props=?,
            img=?,
            status=?,
            created_at=?,
            name=?,
            total=?,
            type_frame=?,
            specs=?,
            order_view=?,
            is_visible=?,
            description=?,
            address=?,
            updated_at=NOW()
            WHERE id = $this->intId";
            $arrData = array(
                $this->arrData['id_config'],
                $this->arrData['id'],
                $this->arrData['margin'],
                $this->arrData['height'],
                $this->arrData['width'],
                $this->arrData['orientation'],
                $this->arrData['color_frame_id'],
                $this->arrData['color_margin_id'],
                $this->arrData['color_border_id'],
                json_encode($this->arrData['config'],JSON_UNESCAPED_UNICODE),
                $this->strImg,
                $this->intStatus,
                $this->strDate,
                $this->strName,
                $this->arrData['price_sell'],
                $this->arrData['type_frame'],
                json_encode($this->arrData['data'],JSON_UNESCAPED_UNICODE),
                $this->intOrder,
                $this->isVisible,
                $this->strDescription,
                $this->strAddress
            );
            $request = $this->update($sql,$arrData);
            return $request;
        }
        public function deleteExample($id){
            $this->intId = $id;
            $sql = "DELETE FROM molding_examples WHERE id = $this->intId";
            $return = $this->delete($sql);
            return $return;
        }
        /*************************Molding methods*******************************/
        public function selectMoldingCategories(){
            $sql = "SELECT * FROM moldingcategory WHERE status = 1 ORDER BY id ASC";       
            $request = $this->select_all($sql);
            return $request;
        }
        public function selectConfig(int $id){
            $this->intId = $id;
            $sql = "SELECT 
            co.id,
            co.category_id,
            co.is_frame,
            co.is_print,
            co.is_cost,
            co.img,
            c.name
            FROM molding_config co
            INNER JOIN moldingcategory c ON c.id = co.category_id
            WHERE co.category_id = $this->intId";
            $request = $this->select($sql);
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
                $request['detail']['molding'] = $request_frames;
                $request['detail']['props'] = $this->selectConfigProps($request['id']);
                $request['url'] = media()."/images/uploads/".$request['img'];
            }
            return $request;
        }
        public function selectConfigFrame(int $id){
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
            $request= $this->select_all($sql);
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
                    $arrProducts = $this->select_all($sql);
                    $totalProducts = count($arrProducts);
                    for ($j=0; $j < $totalProducts; $j++) { 
                        $idProduct = $arrProducts[$j]['idproduct'];
                        $sqlImg = "SELECT * FROM productimage WHERE productid = $idProduct";
                        $requestImg = $this->select_all($sqlImg);
                        $totalImg = count($requestImg);
                        for ($k=0; $k < $totalImg; $k++) { 
                            $requestImg[$k]['image'] = media()."/images/uploads/".$requestImg[$k]['name'];
                        }
                        $sqlWaste = "SELECT 
                        p.value as waste 
                        FROM product_specs p
                        INNER JOIN specifications s ON p.specification_id = s.id_specification
                        WHERE p.product_id = $idProduct";
                        $arrProducts[$j]['waste'] = $this->select($sqlWaste)['waste'];
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
            $request= $this->select_all($sql);
            if(!empty($request)){
                $total = count($request);
                for ($i=0; $i < $total; $i++) { 
                    $sql = "SELECT * FROM molding_options WHERE status = 1 AND prop_id = {$request[$i]['prop']} ORDER BY order_view";
                    $sql_framing = "SELECT 
                    s.name,
                    f.framing_id
                    FROM molding_props_framing f
                    INNER JOIN subcategory s ON f.framing_id = s.idsubcategory
                    WHERE f.prop_id = {$request[$i]['prop']} AND is_check = 1";

                    $request[$i]['options'] = $this->select_all($sql);
                    $arrPropsConfig = $this->select_all($sql_framing);
                    for ($j=0; $j < count($arrPropsConfig) ; $j++) { 
                        $arrPropsConfig[$j]['attribute'] = 'data-'.$j.'="'.$arrPropsConfig[$j]['name'].'"';
                    }
                    $request[$i]['attributes'] = $arrPropsConfig;
                }
            }
            return $request;
        }
        public function selectColors(){
            $sql = "SELECT * FROM moldingcolor WHERE status = 1 ORDER BY order_view";       
            $request = $this->select_all($sql);
            return $request;
        }
    }
?>