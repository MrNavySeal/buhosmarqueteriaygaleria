<?php 
    class RetencionesModel extends Mysql{
        private $data;
        private $id;
        public function __construct(){
            parent::__construct();
        }

        /*************************Category methods*******************************/
        public function insertDatos(array $data){
            $this->data = $data;
            $return = 0;
            try {
                $this->beginTransaction();
                $sql = "SELECT * FROM withholding WHERE name = ?";
                $request = $this->select_all($sql,[$this->data['nombre']]);
                if(empty($request)){
                    $sql = "INSERT INTO withholding(name,type,status,kind) VALUES(?,?,?,?)";
                    $this->id = $this->insert($sql,[$this->data['nombre'],$this->data['tipo'],$this->data['estado'],$this->data['clase']]);
                    $return = $this->id;
                    $this->insertDatosDetalle();
                }else{
                    $return = "exist";
                }
                $this->commit();
            } catch (Exception $e) {
                $this->rollback();
                $return = $e->getMessage();
            }
	        return $return;
		}

        public function insertDatosDetalle(){
            $this->delete("DELETE FROM withholding_det WHERE withholding_id = ?",[$this->id]);
            $tipo = $this->data['tipo'];
            $detalle = $this->data['detalle'];
            foreach ($detalle as $det) {
                $valor = $tipo =="valor" ? $det['valor']['valor'] : $det['porcentaje'];
                $sql = "INSERT INTO withholding_det(withholding_id,concept_id,value) VALUES(?,?,?)";
                $this->insert($sql,[ $this->id, $det['id'], $valor]);
            }
        }

        public function updateDatos(array $data){
            $this->data = $data;
            $this->id = $data['id'];
            try {
                $this->beginTransaction();
                $sql = "SELECT * FROM withholding WHERE name = ? AND id != ?";
                $request = $this->select_all($sql,[$this->data['nombre'],$this->id]);
                if(empty($request)){
                    $sql = "UPDATE withholding SET name=?,type=?,status=?, kind=? WHERE id = ?";
                    $request = $this->update($sql,[$this->data['nombre'],$this->data['tipo'],$this->data['estado'],$this->data['clase'],$this->id,]);
                    $this->insertDatosDetalle();
                }else{
                    $request = "exist";
                }
                $this->commit();
            } catch (Exception $e) {
                $this->rollback();
                $request = $e->getMessage();
            }
			return $request;
		}

        public function deleteDatos($id){
            $sql = "DELETE FROM withholding WHERE id = ?";
            $request = $this->delete($sql,[$id]);
            return $request;
        }

        public function selectDatos($intPage,$intPerPage,$strSearch){
            $limit ="";
            $intStartPage = ($intPage-1)*$intPerPage;
            if($intPerPage != 0){
                $limit = " LIMIT $intStartPage,$intPerPage";
            }    
            $sql = "SELECT * FROM withholding WHERE name like '$strSearch%' ORDER BY id DESC $limit";  
            $request = $this->select_all($sql);

            $sqlTotal = "SELECT count(*) as total FROM withholding WHERE name like '$strSearch%' ORDER BY id";

            $totalRecords = $this->select($sqlTotal)['total'];
            $arrData = getCalcPages($totalRecords,$intPage,$intPerPage);
            $arrData['data'] = $request;
            return $arrData;
        }

        public function selectDato($id){
            $sql = "SELECT * FROM withholding WHERE id = ?";
            $request = $this->select($sql,[$id]);
            if(!empty($request)){
                $tipo = $request['type'];
                $sql = "SELECT co.id, co.name, det.value
                FROM withholding_det det 
                INNER JOIN accounting_concepts co ON det.concept_id = co.id
                WHERE det.withholding_id = $id";
                $detalle = $this->select_all($sql);
                $arrDetalle = [];
                foreach ($detalle as $det) {
                    $porcentaje = $tipo == "porcentaje" ? $det['value'] : 0;
                    $valor = $tipo == "valor" ? $det['value'] : 0;
                    array_push($arrDetalle,[
                        "id"=>$det['id'],
                        "name"=>$det['name'],
                        "valor"=>[ "valor"=>$valor,"valor_formato"=>formatNum($valor,false,MIL,DEC)],
                        "porcentaje"=>$porcentaje
                    ]);
                }
                $request['detalle'] = $arrDetalle;
            }
            return $request;
        }
    }
?>