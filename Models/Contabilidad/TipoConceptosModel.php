<?php 
    class TipoConceptosModel extends Mysql{
        public function __construct(){
            parent::__construct();
        }

        /*************************Category methods*******************************/
        public function insertDatos(string $strNombre,int $intEstado){
			$return = 0;

			$sql = "SELECT * FROM accounting_concept_types WHERE name = '$strNombre'";
			$request = $this->select_all($sql);

			if(empty($request)){ 
				$sql  = "INSERT INTO accounting_concept_types(name,status) VALUES(?,?)";
	        	$data = array($strNombre,$intEstado);
	        	$return = $this->insert($sql,$data);
			}else{
				$return = "exist";
			}
	        return $return;
		}

        public function updateDatos(int $intId,string $strNombre,int $intEstado){
			$sql = "SELECT * FROM accounting_concept_types WHERE name = '$strNombre' AND id != $intId";
			$request = $this->select_all($sql);
			if(empty($request)){
                $sql = "UPDATE accounting_concept_types SET name=?,status=? WHERE id = $intId";
                $data = array($strNombre,$intEstado);
				$request = $this->update($sql,$data);
			}else{
				$request = "exist";
			}
			return $request;
		}

        public function deleteDatos($id){
            $sql = "SELECT * FROM accounting_concepts WHERE type = $id";
            $request = $this->select_all($sql);
            $return = "";
            if(empty($request)){
                $sql = "DELETE FROM accounting_concept_types WHERE id = $id";
                $return = $this->delete($sql);
            }else{
                $return="exist";
            }
            return $return;
        }

        public function selectDatos($intPage,$intPerPage,$strSearch){
            $limit ="";
            $intStartPage = ($intPage-1)*$intPerPage;
            if($intPerPage != 0){
                $limit = " LIMIT $intStartPage,$intPerPage";
            }    
            $sql = "SELECT * FROM accounting_concept_types WHERE name like '$strSearch%' ORDER BY id DESC $limit";  
            $request = $this->select_all($sql);

            $sqlTotal = "SELECT count(*) as total FROM accounting_concept_types WHERE name like '$strSearch%' ORDER BY id";

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

        public function selectDato($id){
            $sql = "SELECT * FROM accounting_concept_types WHERE id = $id";
            $request = $this->select($sql);
            return $request;
        }
    }
?>