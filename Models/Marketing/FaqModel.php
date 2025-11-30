<?php 
    class FaqModel extends Mysql{
        private $intId;
        private $strPregunta;
        private $strRespuesta;
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
            $sql = "SELECT * FROM faq WHERE answer like '$strBuscar%' OR question like '$strBuscar%' ORDER BY id DESC $limit";  
            $sqlTotal = "SELECT count(*) as total FROM faq WHERE answer like '$strBuscar%' OR question like '$strBuscar%' ORDER BY id DESC";
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

        public function insertFaq(string $strPregunta,string $strRespuesta,int $intEstado){
			$this->strPregunta = $strPregunta;
			$this->strRespuesta = $strRespuesta;
            $this->intEstado = $intEstado;
            $sql  = "INSERT INTO faq(question,answer,status)  VALUES(?,?,?)";
            $arrData = array($this->strPregunta,$this->strRespuesta,$this->intEstado);
            $request = $this->insert($sql,$arrData);
	        return $request;
		}

        public function updateFaq(int $intId,string $strPregunta,string $strRespuesta,int $intEstado){
            $this->intId = $intId;
            $this->strPregunta = $strPregunta;
			$this->strRespuesta = $strRespuesta;
            $this->intEstado = $intEstado;
            $sql = "UPDATE faq SET question=?,answer=?,status=? WHERE id = $this->intId";
            $arrData = array($this->strPregunta,$this->strRespuesta,$this->intEstado);
            $request = $this->update($sql,$arrData);
			return $request;
		
		}

        public function deleteFaq($id){
            $this->intId = $id;
            $sql = "DELETE FROM faq WHERE id = $this->intId";
            $request = $this->delete($sql);
            return $request;
        }
    }
?>