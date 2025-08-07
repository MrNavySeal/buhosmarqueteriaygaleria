<?php
    class OpcionesModel extends Mysql{

        private $intId;
        private $strName; 
        private $intModule;
        private $intSection;
        private $strRoute;
        private $intLevel;
        private $intStatus;

        public function __construct(){
            parent::__construct();
        }

        public function insertOpcion($strName,$intModule,$intSection,$strRoute,$intLevel,$intStatus){
            $this->intModule = $intModule;
            $this->intSection = $intSection;
            $this->strName = $strName;
            $this->strRoute = $strRoute;
            $this->intLevel = $intLevel;
            $this->intStatus = $intStatus;
            $sql = "SELECT * FROM module_options WHERE name = '$this->strName' AND module_id = $this->intModule AND section_id = $this->intSection";
            $request = $this->select($sql);
            if(empty($request)){
                $sql = "INSERT INTO module_options(name,module_id,section_id,route,level,status) VALUES(?,?,?,?,?,?)";
                $request = intval($this->insert($sql,[$this->strName,$this->intModule,$this->intSection,$this->strRoute,$this->intLevel,$this->intStatus]));
            }else{
                $request = "existe";
            }
            return $request;
        }
        public function updateOpcion($intId,$strName,$intModule,$intSection,$strRoute,$intLevel,$intStatus){
            $this->intModule = $intModule;
            $this->strName = $strName;
            $this->strRoute = $strRoute;
            $this->intId = $intId;
            $this->intSection = $intSection;
            $this->intLevel = $intLevel;
            $this->intStatus = $intStatus;
            $sql = "SELECT * FROM module_options 
            WHERE name = '$this->strName' AND module_id = $this->intModule AND section_id = $this->intSection AND id != $this->intId";
            $request = $this->select($sql);
            if(empty($request)){
                $sql = "UPDATE module_options SET name= ?, module_id = ?, section_id = ?, route=?,level=?,status=? WHERE id=$this->intId";
                $request = intval($this->update($sql,[$this->strName,$this->intModule,$this->intSection,$this->strRoute,$this->intLevel,$this->intStatus]));
            }else{
                $request = "existe";
            }
            return $request;
        }
        public function deleteOpcion($intId){
            $this->intId = $intId;

            $this->delete("DELETE FROM module_options_permissions WHERE option_id = $this->intId;SET @autoid :=0; 
            UPDATE module_options_permissions SET id = @autoid := (@autoid+1);
            ALTER TABLE module_options_permissions Auto_Increment = 1");

            $sql = "DELETE FROM module_options WHERE id = $this->intId";
            $request = $this->delete($sql);
            return $request;
        }
        public function selectOpcion($intId){
            $this->intId = $intId;
            $sql = "SELECT * FROM module_options WHERE id = $this->intId";
            $request = $this->select($sql);
            return $request;
        }
        public function selectOpciones($intPage,$intPerPage,$strSearch){
            $limit ="";
            $intStartPage = ($intPage-1)*$intPerPage;
            if($intPerPage != 0){
                $limit = " LIMIT $intStartPage,$intPerPage";
            }
            $sql = "SELECT cab.name as module, det.*, sec.name as section 
            FROM module_options det
            INNER JOIN module cab ON det.module_id = cab.idmodule
            LEFT JOIN module_sections sec ON det.section_id = sec.id
            WHERE det.name like  '$strSearch%' OR cab.name like '$strSearch' OR sec.name like '$strSearch'
            ORDER BY det.id DESC $limit";
            $request = $this->select_all($sql);

            $sqlTotal = "SELECT count(*) as total 
            FROM module_options det
            INNER JOIN module cab ON det.module_id = cab.idmodule
            LEFT JOIN module_sections sec ON det.section_id = sec.id
            WHERE det.name like  '$strSearch%' OR cab.name like '$strSearch' OR sec.name like '$strSearch'";
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
        public function selectSecciones(){
            $sql = "SELECT * FROM module_sections WHERE status = 1 ORDER BY id DESC";
            $request = $this->select_all($sql);
            return $request;
        }
        
    }
?>