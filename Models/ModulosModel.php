<?php
    class ModulosModel extends Mysql{

        private $intId;
        private $strName; 


        public function __construct(){
            parent::__construct();
        }

        public function insertModulo($strName){
            $this->strName = $strName;
            $sql = "SELECT * FROM module WHERE name = '$this->strName'";
            $request = $this->select($sql);
            if(empty($request)){
                $sql = "INSERT INTO module(name) VALUES(?)";
                $request = $this->insert($sql,[$this->strName]);
            }else{
                $request = "existe";
            }
            return $request;
        }
        public function updateModulo($intId,$strName){
            $this->strName = $strName;
            $this->intId = $intId;
            $sql = "SELECT * FROM module WHERE name = '$this->strName' AND idmodule != $this->intId";
            $request = $this->select($sql);
            if(empty($request)){
                $sql = "UPDATE module SET name=? WHERE idmodule=$this->intId";
                $request = $this->update($sql,[$this->strName]);
            }else{
                $request = "existe";
            }
            return $request;
        }
        public function selectModulo($intId){
            $this->intId = $intId;
            $sql = "SELECT * FROM module WHERE idmodule = $this->intId";
            $request = $this->select($sql);
            return $request;
        }
        public function selectModulos($intPage,$intPerPage,$strSearch){
            $limit ="";
            $intStartPage = ($intPage-1)*$intPerPage;
            if($intPerPage != 0){
                $limit = " LIMIT $intStartPage,$intPerPage";
            }
            $sql = "SELECT idmodule as id, name FROM module WHERE name like  '$strSearch%' ORDER BY idmodule DESC $limit";
            $request = $this->select_all($sql);

            $sqlTotal = "SELECT count(*) as total FROM module WHERE name like '$strSearch%'";
            $totalRecords = $this->select($sqlTotal)['total'];
            $totalPages = intval($totalRecords > 0 ? ceil($totalRecords/$intPerPage) : 0);
            $totalPages = $totalPages == 0 ? 1 : $totalPages;
            $startPage = max(1, $intStartPage - floor(BUTTONS / 2));
            if ($startPage + BUTTONS - 1 > $totalPages) {
                $startPage = max(1, $totalPages - BUTTONS + 1);
            }
            $limitPages = min($startPage + BUTTONS, $totalPages+1);
            $arrButtons = [];
            for ($i=$startPage; $i < $limitPages; $i++) { 
                array_push($arrButtons,$i);
            }
            $arrData = array(
                "data"=>$request,
                "start_page"=>$startPage,
                "limit_page"=>$limitPages,
                "total_pages"=>$totalPages,
                "total_records"=>$totalRecords,
                "buttons"=>$arrButtons
            );
            return $arrData;
        }
        
    }
?>