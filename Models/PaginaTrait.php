<?php
    require_once("Libraries/Core/Mysql.php");
    trait PaginaTrait{
        private $con;

        public function selectPage($id){
            $this->con = new Mysql();
            $sql = "SELECT *,DATE_FORMAT(date_created, '%d/%m/%Y') as date,DATE_FORMAT(date_updated, '%d/%m/%Y') as dateupdated  FROM pages WHERE id = $id";
            $request = $this->con->select($sql);
            return $request;
        }
        public function selectServices(){
            $this->con = new Mysql();
            $sql = "SELECT * FROM pages WHERE type = 2";
            $request = $this->con->select_all($sql);
            return $request;
        }
        public function selectService($route){
            $this->con = new Mysql();
            $sql = "SELECT * FROM pages WHERE route = '$route' AND type = 2";
            $request = $this->con->select($sql);
            return $request;
        }
    }
    
?>