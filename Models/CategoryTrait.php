<?php
    require_once("Libraries/Core/Mysql.php");
    trait CategoryTrait{
        private $con;

        public function getCategoriesT(){
            $this->con=new Mysql();
            $sql = "SELECT * FROM category WHERE status = 1 ORDER BY name ";       
            $request = $this->con->select_all($sql);
            if(count($request)>0){
                for ($i=0; $i < count($request) ; $i++) { 
                    $idCategory = $request[$i]['idcategory'];
                    $sqlSub = "SELECT * FROM subcategory WHERE categoryid = $idCategory";
                    $requestSub = $this->con->select_all($sqlSub);
                    for ($j=0; $j < count($requestSub) ; $j++) { 
                        $idSubcategory = $requestSub[$j]['idsubcategory'];
                        $sqlQty = "SELECT COUNT(idproduct) as total FROM product WHERE subcategoryid = $idSubcategory";
                        $requestQty = $this->con->select($sqlQty);
                        $requestSub[$j]['total'] = $requestQty['total'];
                    }
                    $request[$i]['subcategories'] = $requestSub;
                }
            }
            return $request;
        }
    }
    
?>