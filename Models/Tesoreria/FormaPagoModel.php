<?php 
    class FormaPagoModel extends Mysql{
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
                $sql = "SELECT * FROM payment_type WHERE name = ? AND type=? AND relation=?";
                $request = $this->select_all($sql,[$this->data['nombre'],$this->data['tipo'],$this->data['relacion']]);
                if(empty($request)){
                    $sql = "INSERT INTO payment_type(name,type,relation,withholding_id,status,bank_id) VALUES(?,?,?,?,?,?)";
                    $this->id = $this->insert($sql,[
                        $this->data['nombre'],
                        $this->data['tipo'],
                        $this->data['relacion'],
                        $this->data['ingreso'],
                        $this->data['estado'],
                        $this->data['banco']
                    ]);
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
            $this->delete("DELETE FROM payment_type_discounts WHERE payment_type_id = ?",[$this->id]);
            $detalle = $this->data['detalle'];
            foreach ($detalle as $det) {
                $sql = "INSERT INTO payment_type_discounts(payment_type_id,withholding_id) VALUES(?,?)";
                $this->insert($sql,[ $this->id, $det['id']]);
            }
        }

        public function updateDatos(array $data){
            $this->data = $data;
            $this->id = $data['id'];
            try {
                $this->beginTransaction();
                $sql = "SELECT * FROM payment_type WHERE name = ? AND type=? AND relation=? AND id != ?";
                $request = $this->select_all($sql,[$this->data['nombre'],$this->data['tipo'],$this->data['relacion'],$this->id]);
                if(empty($request)){
                    $sql = "UPDATE payment_type SET name=?,type=?,relation=?,withholding_id=?,status=?,bank_id=? WHERE id = ?";
                    $request = $this->update($sql,[
                        $this->data['nombre'],
                        $this->data['tipo'],
                        $this->data['relacion'],
                        $this->data['ingreso'],
                        $this->data['estado'],
                        $this->data['banco'],
                        $this->id
                    ]);
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
            $sql = "DELETE FROM payment_type WHERE id = ?";
            $request = $this->delete($sql,[$id]);
            return $request;
        }

        public function selectDatos($intPage,$intPerPage,$strSearch){
            $limit ="";
            $intStartPage = ($intPage-1)*$intPerPage;
            if($intPerPage != 0){
                $limit = " LIMIT $intStartPage,$intPerPage";
            }    
            $sql = "SELECT cab.*, wh.name as withholding,wh.code 
            FROM payment_type cab
            LEFT JOIN accounting_accounts wh ON wh.id = cab.withholding_id
            WHERE cab.name like '$strSearch%' 
            ORDER BY cab.id DESC $limit";  

            $sqlTotal = "SELECT count(*) as total FROM payment_type WHERE name like '$strSearch%' ORDER BY id";

            $request = $this->select_all($sql);
            $totalRecords = $this->select($sqlTotal)['total'];

            foreach ($request as &$det) {
                $det['type'] = HelperGeneral::TIPO_PAGO[$det['type']]['nombre'];
                $det['relation'] = HelperGeneral::RELACION_PAGO[$det['relation']]['nombre'];
                if($det['type']=="2"){
                    $bank = HelperAccounting::getBankAccount($det['bank_id']);
                    $det['withholding'] = $bank['account'];
                    $det['code'] = $bank['code'];
                }
            }

            unset($det);
            $arrData = getCalcPages($totalRecords,$intPage,$intPerPage);
            $arrData['data'] = $request;
            return $arrData;
        }

        public function selectDato($id){
            $sql = "SELECT cab.*,wh.name as withholding,wh.code 
            FROM payment_type cab
            LEFT JOIN accounting_accounts wh ON wh.id = cab.withholding_id
            WHERE cab.id = ?";
            $request = $this->select($sql,[$id]);
            if(!empty($request)){
                if($request['type']=="2"){
                    $bank = HelperAccounting::getBankAccount($request['bank_id']);
                    $request['bank'] = $bank;
                }
                
                $sql = "SELECT wh.id, wh.name
                FROM payment_type_discounts det 
                LEFT JOIN withholding wh ON wh.id = det.withholding_id
                WHERE det.payment_type_id = $id";
                $detalle = $this->select_all($sql);
                $request['detalle'] = $detalle;
            }
            return $request;
        }
    }
?>