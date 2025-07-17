<?php
    class RolesModel extends Mysql{

        private $intId;
        private $strName; 


        public function __construct(){
            parent::__construct();
        }

        public function insertRol($strName){
            $this->strName = $strName;
            $sql = "SELECT * FROM role WHERE name = '$this->strName'";
            $request = $this->select($sql);
            if(empty($request)){
                $sql = "INSERT INTO role(name) VALUES(?)";
                $request = intval($this->insert($sql,[$this->strName]));
            }else{
                $request = "existe";
            }
            return $request;
        }
        public function updateRol($intId,$strName){
            $this->strName = $strName;
            $this->intId = $intId;
            $sql = "SELECT * FROM role WHERE name = '$this->strName' AND idrole != $this->intId";
            $request = $this->select($sql);
            if(empty($request)){
                $sql = "UPDATE role SET name=? WHERE idrole=$this->intId";
                $request = intval($this->update($sql,[$this->strName]));
            }else{
                $request = "existe";
            }
            return $request;
        }
        public function deleteRol($intId){
            $this->intId = $intId;
            $sql = "SELECT * FROM person WHERE roleid = $this->intId";
            $request = $this->select($sql);
            if(empty($request)){
                $sql = "DELETE FROM role WHERE idrole = $this->intId";
                $request = $this->delete($sql);
            }else{
                $request ="existe";
            }
            return $request;
        }
        public function selectRol($intId){
            $this->intId = $intId;
            $sql = "SELECT *,idrole as id FROM role WHERE idrole = $this->intId";
            $request = $this->select($sql);
            return $request;
        }
        public function selectRoles($intPage,$intPerPage,$strSearch){
            $limit ="";
            $intStartPage = ($intPage-1)*$intPerPage;
            if($intPerPage != 0){
                $limit = " LIMIT $intStartPage,$intPerPage";
            }
            $sql = "SELECT idrole as id, name FROM role WHERE name like  '$strSearch%' ORDER BY idrole DESC $limit";
            $request = $this->select_all($sql);

            $sqlTotal = "SELECT count(*) as total FROM role WHERE name like '$strSearch%'";
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