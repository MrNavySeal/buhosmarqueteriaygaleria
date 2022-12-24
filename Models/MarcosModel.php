<?php
    class MarcosModel extends Mysql{
        private $intIdProduct;

        public function selectTipos(){
            
            $sql = "SELECT * FROM moldingcategory WHERE status = 1 ORDER BY id ASC";       
            $request = $this->select_all($sql);
            return $request;
        }
        public function selectTipo($route){
            
            $sql = "SELECT * FROM moldingcategory WHERE status = 1 AND route = '$route'";
            $request = $this->select($sql);
            return $request;
        }
        public function selectProducts($dimensions=""){
            
            $option = "";
            if($dimensions < 200){
                $option="";
            }else if($dimensions >= 200 && $dimensions < 400){
                $option = " AND waste > 33";
            }else if($dimensions >= 400){
                $option = " AND waste > 49";
            }
            $sql = "SELECT * FROM molding WHERE status = 1 $option ORDER BY waste DESC";
            $request = $this->select_all($sql);
            if(count($request)> 0){
                for ($i=0; $i < count($request); $i++) { 
                    $idProduct = $request[$i]['id'];
                    $sqlImg = "SELECT * FROM moldingimage WHERE moldingid = $idProduct";
                    $requestImg = $this->select_all($sqlImg);
                    if(count($requestImg)>0){
                        $request[$i]['image'] = media()."/images/uploads/".$requestImg[0]['name'];
                    }else{
                        $request[$i]['image'] = media()."/images/uploads/image.png";
                    }
                }
            }
            return $request;
        }
        public function selectProduct($id){
            
            $this->intIdProduct = $id;
            $sql = "SELECT reference,frame,price,discount,waste FROM molding WHERE id = $this->intIdProduct AND status = 1";
            $request = $this->select($sql);
            if(!empty($request)){
                $sqlImg = "SELECT * FROM moldingimage WHERE moldingid = $this->intIdProduct";
                $requestImg = $this->select_all($sqlImg);
                if(count($requestImg)){
                    for ($i=0; $i < count($requestImg); $i++) { 
                        $request['image'][$i] = media()."/images/uploads/".$requestImg[$i]['name'];
                    }
                }
            }
            return $request;
        }
        public function selectColors(){
            
            $sql = "SELECT * FROM moldingcolor WHERE status = 1 ORDER BY name ASC";       
            $request = $this->select_all($sql);
            return $request;
        }
        public function selectColor($id){
            
            $sql = "SELECT * FROM moldingcolor WHERE id = $id";
            $request = $this->select($sql);
            return $request;
        }
        public function searchT($search,$sort,$dimensions=""){
            

            $option = "";
            if($sort == 2){
                $option=" AND type = 1";
            }else if($sort == 3){
                $option = " AND type = 2";
            }

            $sql = "SELECT * FROM molding WHERE status = 1  $option AND reference LIKE '%$search%' ORDER BY waste DESC";
            $request = $this->select_all($sql);
            if(count($request)> 0){
                for ($i=0; $i < count($request); $i++) { 
                    $idProduct = $request[$i]['id'];
                    $sqlImg = "SELECT * FROM moldingimage WHERE moldingid = $idProduct";
                    $requestImg = $this->select_all($sqlImg);
                    if(count($requestImg)>0){
                        $request[$i]['image'] = media()."/images/uploads/".$requestImg[0]['name'];
                    }else{
                        $request[$i]['image'] = media()."/images/uploads/image.png";
                    }
                }
            }
            return $request;
        }
        public function sortT($search,$sort,$dimensions = ""){
            $this->con = new Mysql();
            //dep($dimensions);
            $option="";
            if($sort == 2){
                $option=" AND type = 1 ORDER BY waste DESC";
            }else if( $sort == 3){
                $option=" AND type = 2 ORDER BY waste DESC";
            }else{
                $option=" ORDER BY waste DESC";
            }
            $sql = "SELECT * FROM molding WHERE status = 1 AND reference LIKE '%$search%' $option";
            $request = $this->con->select_all($sql);
            if(count($request)> 0){
                for ($i=0; $i < count($request); $i++) { 
                    $idProduct = $request[$i]['id'];
                    $sqlImg = "SELECT * FROM moldingimage WHERE moldingid = $idProduct";
                    $requestImg = $this->con->select_all($sqlImg);
                    if(count($requestImg)>0){
                        $request[$i]['image'] = media()."/images/uploads/".$requestImg[0]['name'];
                    }else{
                        $request[$i]['image'] = media()."/images/uploads/image.png";
                    }
                }
            }
            return $request;
        }
        public function selectMaterials(){
            
            $sql = "SELECT * FROM moldingmaterial";       
            $request = $this->select_all($sql);
            return $request;
        }
    }
    
?>