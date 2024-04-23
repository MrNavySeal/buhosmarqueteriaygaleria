<?php 
    class ProductosMasivosModel extends Mysql{
        public function __construct(){
            parent::__construct();
        }
        
        public function selectNextId(){
            $sql = "SELECT MAX(idproduct) as id FROM product";
            $request = $this->select($sql)['id']+1;
            return $request;
        }
        public function selectCategories(){
            $sql = "SELECT name,idcategory FROM category WHERE status = 1";
            $request = $this->select_all($sql);
            $arrCategories = [];
            $arrSubcategories = [];
            for ($i=0; $i < count($request) ; $i++) { 
                $id = $request[$i]['idcategory'];
                $sql = "SELECT s.name,c.status 
                FROM subcategory s 
                INNER JOIN category c ON s.categoryid = c.idcategory
                WHERE c.status = 1 AND s.status = 1";
                $subcategories = $this->select_all($sql);
                for ($j=0; $j < count($subcategories) ; $j++) { 
                    $subcategories[$j] = $subcategories[$j]['name'];
                }
                array_push($arrCategories,$request[$i]['name']);
            }
            return array("categories"=>$arrCategories,"subcategories"=>$subcategories);
        }
    }
?>