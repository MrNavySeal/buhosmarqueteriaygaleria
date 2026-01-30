<?php 
    class ConceptosContablesModel extends Mysql{
        private $data;
        private $id;

        public function __construct(){
            parent::__construct();
        }

        public function insertDatos($data){
            $this->data = $data;
            $sql = "INSERT INTO accounting_concepts(name,type,status)
            VALUES(?,?,?)";
            $data = [$data['nombre'],$data['tipo'],$data['estado']];
            $this->id = $this->insert($sql,$data);
            $this->insertDatosDetalle();
            return $this->id;
        }

        public function updateDatos($data){
            $this->data = $data;
            $this->id = $this->data['id'];
            $sql = "UPDATE accounting_concepts SET name=?,type=?,status=? WHERE id = $this->id";
            $data = [$data['nombre'],$data['tipo'],$data['estado']];
            $request = $this->update($sql,$data);
            $this->insertDatosDetalle();
            return $request;
        }

        private function insertDatosDetalle(){
            $this->delete("DELETE FROM accounting_concepts_det WHERE concept_id = $this->id");
            $detalle = $this->data['detalle'];
            foreach ($detalle as $det) {
                $sql = "INSERT INTO accounting_concepts_det(concept_id,account_id,nature)
                VALUES(?,?,?)";
                $values = [$this->id,$det['id'],$det['nature']];
                $this->insert($sql,$values);
            }
        }

        public function deleteDatos($id){
            $sql = "DELETE FROM accounting_concepts WHERE id = $id";
            $request = $this->delete($sql);
            return $request;
        }

        public function selectDatos($intPage,$intPerPage,$filterType,$strSearch){
            $limit ="";
            $intStartPage = ($intPage-1)*$intPerPage;
            if($intPerPage != 0){
                $limit = " LIMIT $intStartPage,$intPerPage";
            }
            $sql = "SELECT * FROM accounting_concepts 
            WHERE name like '$strSearch%' AND type like '$filterType%' 
            ORDER BY id DESC $limit";  
            $request = $this->select_all($sql);

            $sqlTotal = "SELECT count(*) as total FROM accounting_concepts 
            WHERE name like '$strSearch%' AND type like '$filterType%'";

            foreach ($request as &$data) { 
                $data['type'] = HelperAccounting::TIPOS_CONCEPTOS[$data['type']]['name'];
            }

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
                "buttons"=>$arrButtons,
                "tipos"=>HelperAccounting::TIPOS_CONCEPTOS
            );
            return $arrData;
        }

        public function selectDato($id){
            $sql = "SELECT * FROM accounting_concepts WHERE id = $id";
            $request = $this->select($sql);
            if(!empty($request)){
                $sql = "SELECT 
                acu.id,
                acu.name, 
                acu.code,
                det.nature
                FROM accounting_concepts_det det
                INNER JOIN accounting_accounts acu ON acu.id = det.account_id
                WHERE det.concept_id = {$request['id']}";
                $request['detail'] = $this->select_all($sql);
            }
            return $request;
        }
    }
?>