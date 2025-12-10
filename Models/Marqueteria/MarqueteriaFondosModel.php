<?php
    class MarqueteriaFondosModel extends Mysql{

        private $intId;
        private $strImage; 
        private $strIcon;
        private $intLevel;
        private $intStatus;


        public function __construct(){
            parent::__construct();
        }

        public function insertFondo($strImage,$intStatus){
            $this->strImage = $strImage;
            $this->intStatus = $intStatus;
            $sql = "INSERT INTO molding_background(image,status) VALUES(?,?)";
            $request = intval($this->insert($sql,[$this->strImage,$this->intStatus]));
            return $request;
        }

        public function updateFondo($intId,$strImage,$intStatus){
            $this->strImage = $strImage;
            $this->intStatus = $intStatus;
            $this->intId = $intId;
            $sql = "UPDATE molding_background SET image=?,status=? WHERE id=$this->intId";
            $request = intval($this->update($sql,[$this->strImage,$this->intStatus]));
            return $request;
        }

        public function deleteFondo($intId){
            $this->intId = $intId;
            $sql = "DELETE FROM molding_background WHERE id = $this->intId";
            $request = $this->delete($sql);
            return $request;
        }

        public function selectFondo($intId){
            $this->intId = $intId;
            $sql = "SELECT * FROM molding_background WHERE id = $this->intId";
            $request = $this->select($sql);
            return $request;
        }

        public function selectFondos($intPage,$intPerPage){
            $limit ="";
            $intStartPage = ($intPage-1)*$intPerPage;
            if($intPerPage != 0){
                $limit = " LIMIT $intStartPage,$intPerPage";
            }
            $sql = "SELECT id, image,status FROM molding_background  ORDER BY id DESC $limit";
            $request = $this->select_all($sql);

            foreach ($request as &$det) {
                $det['url'] = media()."/images/uploads/".$det['image'];
            }

            $sqlTotal = "SELECT count(*) as total FROM molding_background";
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