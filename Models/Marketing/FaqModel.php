<?php 
    class FaqModel extends Mysql{
        private $intId;
        private $strPregunta;
        private $strRespuesta;
        private $strNombre;
        private $intSeccion;
        private $intEstado;

        public function __construct(){
            parent::__construct();
        }

        public function selectFaqs($pageNow,$perPage, $strBuscar){
            $start = ($pageNow-1)*$perPage;
            $limit ="";

            if($perPage != 0){
                $limit = " LIMIT $start,$perPage";
            }

            $sql = "SELECT det.*, cab.name as section 
            FROM faq det 
            INNER JOIN faq_section cab ON cab.id = det.section_id
            WHERE det.answer like '$strBuscar%' OR det.question like '$strBuscar%' OR cab.name like '$strBuscar%'
            ORDER BY det.id DESC $limit";  

            $sqlTotal = "SELECT count(*) as total 
            FROM faq det 
            INNER JOIN faq_section cab ON cab.id = det.section_id
            WHERE det.answer like '$strBuscar%' OR det.question like '$strBuscar%' OR cab.name like '$strBuscar%'";
            $request = $this->select_all($sql);

            $totalRecords = $this->select($sqlTotal)['total'];
            $arrData = getCalcPages($totalRecords,$pageNow,$perPage);
            $arrData['data'] = $request;
            return $arrData;
        }

        public function selectSecciones($pageNow,$perPage, $strBuscar){
            $start = ($pageNow-1)*$perPage;
            $limit ="";

            if($perPage != 0){
                $limit = " LIMIT $start,$perPage";
            }
            $sql = "SELECT * FROM faq_section WHERE name like '$strBuscar%' ORDER BY id DESC $limit";  
            $sqlTotal = "SELECT count(*) as total FROM faq_section WHERE name like '$strBuscar%' ORDER BY id DESC";
            $request = $this->select_all($sql);

            $totalRecords = $this->select($sqlTotal)['total'];
            $arrData = getCalcPages($totalRecords,$pageNow,$perPage);
            $arrData['data'] = $request;
            return $arrData;
        }

        public function selectFaq($id){
            $this->intId = $id;
            $sql = "SELECT * FROM faq WHERE id = $this->intId";
            $request = $this->select($sql);
            return $request;
        }

        public function selectSeccion($id){
            $this->intId = $id;
            $sql = "SELECT * FROM faq_section WHERE id = $this->intId";
            $request = $this->select($sql);
            return $request;
        }

        public function insertFaq(string $strPregunta,string $strRespuesta,int $intSeccion,int $intEstado){
			$this->strPregunta = $strPregunta;
			$this->strRespuesta = $strRespuesta;
            $this->intEstado = $intEstado;
            $this->intSeccion = $intSeccion;
            $sql  = "INSERT INTO faq(question,answer,status,section_id) VALUES(?,?,?,?)";
            $arrData = array($this->strPregunta,$this->strRespuesta,$this->intEstado,$this->intSeccion);
            $request = $this->insert($sql,$arrData);
	        return $request;
		}

        public function insertSeccion(string $strNombre,int $intEstado){
			$this->strNombre = $strNombre;
            $this->intEstado = $intEstado;
            $sql  = "INSERT INTO faq_section(name,status) VALUES(?,?)";
            $arrData = array($this->strNombre,$this->intEstado);
            $request = $this->insert($sql,$arrData);
	        return $request;
		}

        public function updateFaq(int $intId,string $strPregunta,string $strRespuesta,int $intSeccion,int $intEstado){
            $this->intId = $intId;
            $this->strPregunta = $strPregunta;
			$this->strRespuesta = $strRespuesta;
            $this->intEstado = $intEstado;
            $sql = "UPDATE faq SET question=?,answer=?,status=?,section_id=? WHERE id = $this->intId";
            $arrData = array($this->strPregunta,$this->strRespuesta,$this->intEstado,$this->intSeccion);
            $request = $this->update($sql,$arrData);
			return $request;
		
		}

        public function updateSeccion(int $intId,string $strNombre,int $intEstado){
            $this->intId = $intId;
            $this->strNombre = $strNombre;
            $this->intEstado = $intEstado;
            $sql = "UPDATE faq_section SET name=?,status=? WHERE id = $this->intId";
            $arrData = array($this->strNombre,$this->intEstado);
            $request = $this->update($sql,$arrData);
			return $request;
		
		}

        public function deleteFaq($id){
            $this->intId = $id;
            $sql = "DELETE FROM faq WHERE id = $this->intId";
            $request = $this->delete($sql);
            return $request;
        }

        public function deleteSeccion($id){
            $this->intId = $id;
            $sql = "DELETE FROM faq_section WHERE id = $this->intId";
            $request = $this->delete($sql);
            return $request;
        }

        public function selectListSecciones(){
            $sql = "SELECT * FROM faq_section WHERE status = 1 ORDER BY name";
            $request = $this->select_all($sql);
            return $request;
        }
    }
?>