<?php
    class SeccionesModel extends Mysql{

        private $intId;
        private $strName; 
        private $intModule;
        private $intLevel;
        private $intStatus;

        public function __construct(){
            parent::__construct();
        }

        public function insertSeccion($strName,$intModule,$intLevel,$intStatus){
            $this->intModule = $intModule;
            $this->strName = $strName;
            $this->intLevel = $intLevel;
            $this->intStatus = $intStatus;
            $sql = "SELECT * FROM module_sections WHERE name = '$this->strName' AND module_id = $this->intModule";
            $request = $this->select($sql);
            if(empty($request)){
                $sql = "INSERT INTO module_sections(name,module_id,level,status) VALUES(?,?,?,?)";
                $request = intval($this->insert($sql,[$this->strName,$this->intModule,$this->intLevel,$this->intStatus]));
            }else{
                $request = "existe";
            }
            return $request;
        }
        public function updateSeccion($intId,$strName,$intModule,$intLevel,$intStatus){
            $this->intModule = $intModule;
            $this->strName = $strName;
            $this->intId = $intId;
            $this->intLevel = $intLevel;
            $this->intStatus = $intStatus;
            $sql = "SELECT * FROM module_sections WHERE name = '$this->strName' AND module_id = $this->intModule AND id != $this->intId";
            $request = $this->select($sql);
            if(empty($request)){
                $sql = "UPDATE module_sections SET name= ?, module_id = ?, level=?,status=? WHERE id=$this->intId";
                $request = intval($this->update($sql,[$this->strName,$this->intModule,$this->intLevel,$this->intStatus]));
            }else{
                $request = "existe";
            }
            return $request;
        }
        public function deleteSeccion($intId){
            $this->intId = $intId;
            $sql = "SELECT * FROM module_options WHERE section_id = $this->intId";
            $request = $this->select($sql);
            if(empty($request)){
                $this->delete("DELETE FROM module_sections_permissions WHERE section_id = $this->intId;SET @autoid :=0; 
                UPDATE module_sections_permissions SET id = @autoid := (@autoid+1);
                ALTER TABLE module_sections_permissions Auto_Increment = 1");
                $sql = "DELETE FROM module_sections WHERE id = $this->intId";
                $request = $this->delete($sql);
            }else{
                $request = "existe";
            }
            return $request;
        }
        public function selectSeccion($intId){
            $this->intId = $intId;
            $sql = "SELECT * FROM module_sections WHERE id = $this->intId";
            $request = $this->select($sql);
            return $request;
        }
        public function selectSecciones($intPage,$intPerPage,$strSearch){
            $limit ="";
            $intStartPage = ($intPage-1)*$intPerPage;
            if($intPerPage != 0){
                $limit = " LIMIT $intStartPage,$intPerPage";
            }
            $sql = "SELECT cab.name as module, det.* 
            FROM module_sections det
            INNER JOIN module cab ON det.module_id = cab.idmodule
            WHERE det.name like  '$strSearch%' OR cab.name like '$strSearch'
            ORDER BY det.id DESC $limit";
            $request = $this->select_all($sql);

            $sqlTotal = "SELECT count(*) as total 
            FROM module_sections det
            INNER JOIN module cab ON det.module_id = cab.idmodule
            WHERE det.name like  '$strSearch%' OR cab.name like '$strSearch'";
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
        public function selectModulos(){
            $sql = "SELECT *, idmodule as id FROM module WHERE status = 1 ORDER BY idmodule DESC";
            $request = $this->select_all($sql);
            return $request;
        }
        
    }
?>