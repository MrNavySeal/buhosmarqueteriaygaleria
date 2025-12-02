<?php
    require_once("Libraries/Core/Mysql.php");
    trait CategoryTrait{
        private $con;

        public function getCategoriesT(){
            $this->con=new Mysql();
            $sql = "SELECT * FROM category WHERE status = 1 AND is_visible = 1 ORDER BY name ";       
            $request = $this->con->select_all($sql);
            if(count($request)>0){
                for ($i=0; $i < count($request) ; $i++) { 
                    $idCategory = $request[$i]['idcategory'];
                    $sqlSub = "SELECT * FROM subcategory WHERE categoryid = $idCategory AND status = 1";
                    $requestSub = $this->con->select_all($sqlSub);
                    for ($j=0; $j < count($requestSub) ; $j++) { 
                        $idSubcategory = $requestSub[$j]['idsubcategory'];
                        $sqlQty = "SELECT COUNT(idproduct) as total FROM product WHERE subcategoryid = $idSubcategory AND status = 1";
                        $requestQty = $this->con->select($sqlQty);
                        $requestSub[$j]['total'] = $requestQty['total'];
                    }
                    $request[$i]['subcategories'] = $requestSub;
                }
            }
            return $request;
        }

        public function getCategoriesShowT(string $categories){
            $this->con=new Mysql();
            $sql = "SELECT * FROM category WHERE idcategory IN ($categories) AND is_visible = 1";       
            $request = $this->con->select_all($sql);
            return $request;
        }

        public function getBanners(){
            $this->con=new Mysql();
            $sql = "SELECT * FROM banners WHERE status = 1 ORDER BY id_banner DESC";       
            $request = $this->con->select_all($sql);
            return $request;
        }

        public function getFaqs(){
            $this->con = new Mysql();
            $sql = "SELECT id, name FROM faq_section WHERE status = 1 ORDER BY name";
            $request = $this->con->select_all($sql);

            foreach ($request as &$section) {
                $sql = "SELECT id,question,answer FROM faq WHERE status = 1 AND section_id = {$section['id']}";
                $section['faqs'] = $this->con->select_all($sql);
            }
            unset($section);
            return $request;
        }

    }
    
?>