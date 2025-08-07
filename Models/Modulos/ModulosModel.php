<?php
    class ModulosModel extends Mysql{

        private $intId;
        private $strName; 
        private $strIcon;
        private $intLevel;
        private $intStatus;


        public function __construct(){
            parent::__construct();
        }

        public function insertModulo($strName,$strIcon,$intLevel,$intStatus){
            $this->strName = $strName;
            $this->strIcon = $strIcon;
            $this->intLevel = $intLevel;
            $this->intStatus = $intStatus;
            $sql = "SELECT * FROM module WHERE name = '$this->strName'";
            $request = $this->select($sql);
            if(empty($request)){
                $sql = "INSERT INTO module(name,icon,level,status) VALUES(?,?,?,?)";
                $request = intval($this->insert($sql,[$this->strName,$this->strIcon,$this->intLevel,$this->intStatus]));
            }else{
                $request = "existe";
            }
            return $request;
        }
        public function updateModulo($intId,$strName,$strIcon,$intLevel,$intStatus){
            $this->strName = $strName;
            $this->strIcon = $strIcon;
            $this->intLevel = $intLevel;
            $this->intStatus = $intStatus;
            $this->intId = $intId;
            $sql = "SELECT * FROM module WHERE name = '$this->strName' AND idmodule != $this->intId";
            $request = $this->select($sql);
            if(empty($request)){
                $sql = "UPDATE module SET name=?,icon=?,level=?,status=? WHERE idmodule=$this->intId";
                $request = intval($this->update($sql,[$this->strName,$this->strIcon,$this->intLevel,$this->intStatus]));
            }else{
                $request = "existe";
            }
            return $request;
        }
        public function deleteModulo($intId){
            $this->intId = $intId;
            $sql = "SELECT * FROM module_sections WHERE module_id = $this->intId";
            $request = $this->select($sql);
            if(empty($request)){
                $this->delete("DELETE FROM module_permissions WHERE module_id = $this->intId;SET @autoid :=0; 
                UPDATE module_permissions SET id = @autoid := (@autoid+1);
                ALTER TABLE module_permissions Auto_Increment = 1");
                $sql = "DELETE FROM module WHERE idmodule = $this->intId";
                $request = $this->delete($sql);
            }else{
                $request ="existe";
            }
            return $request;
        }
        public function selectModulo($intId){
            $this->intId = $intId;
            $sql = "SELECT *,idmodule as id FROM module WHERE idmodule = $this->intId";
            $request = $this->select($sql);
            return $request;
        }
        public function selectModulos($intPage,$intPerPage,$strSearch){
            $limit ="";
            $intStartPage = ($intPage-1)*$intPerPage;
            if($intPerPage != 0){
                $limit = " LIMIT $intStartPage,$intPerPage";
            }
            $sql = "SELECT idmodule as id, name,status,level FROM module WHERE name like  '$strSearch%' ORDER BY idmodule DESC $limit";
            $request = $this->select_all($sql);

            $sqlTotal = "SELECT count(*) as total FROM module WHERE name like '$strSearch%'";
            $totalRecords = $this->select($sqlTotal)['total'];
            $totalPages = intval($totalRecords > 0 ? ceil($totalRecords/$intPerPage) : 0);
            $totalPages = $totalPages == 0 ? 1 : $totalPages;
            $startPage = max(1, $intPage - floor(BUTTONS / 2));
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