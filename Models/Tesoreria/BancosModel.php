<?php 
    class BancosModel extends Mysql{
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
                $sql = "SELECT * FROM banks WHERE person_id = ? AND type=? AND bank_account=?";
                $request = $this->select_all($sql,[$this->data['tercero'],$this->data['tipo'],$this->data['cuenta_banco']]);
                if(empty($request)){
                    $sql = "INSERT INTO banks(person_id,type,bank_account,account_id,status) VALUES(?,?,?,?,?)";
                    $this->id = $this->insert($sql,[
                        $this->data['tercero'],
                        $this->data['tipo'],
                        $this->data['cuenta_banco'],
                        $this->data['cuenta'],
                        $this->data['estado']
                    ]);
                    $return = $this->id;
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


        public function updateDatos(array $data){
            $this->data = $data;
            $this->id = $data['id'];
            try {
                $this->beginTransaction();
                $sql = "SELECT * FROM banks WHERE person_id = ? AND type=? AND bank_account=? AND id != ?";
                $request = $this->select_all($sql,[$this->data['tercero'],$this->data['tipo'],$this->data['cuenta_banco'],$this->id]);
                if(empty($request)){
                    $sql = "UPDATE banks SET person_id=?,type=?,bank_account=?,account_id=?,status=? WHERE id = ?";
                    $request = $this->update($sql,[
                        $this->data['tercero'],
                        $this->data['tipo'],
                        $this->data['cuenta_banco'],
                        $this->data['cuenta'],
                        $this->data['estado'],
                        $this->id
                    ]);
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
            $sql = "DELETE FROM banks WHERE id = ?";
            $request = $this->delete($sql,[$id]);
            return $request;
        }

        public function selectDatos($intPage,$intPerPage,$strSearch){
            $limit ="";
            $intStartPage = ($intPage-1)*$intPerPage;
            if($intPerPage != 0){
                $limit = " LIMIT $intStartPage,$intPerPage";
            }    
            $sql = "SELECT cab.*, 
            ac.name as account,
            ac.code,
            CONCAT(p.firstname,' ',p.lastname) as nombre 
            FROM banks cab
            LEFT JOIN person p ON p.idperson = cab.person_id
            LEFT JOIN accounting_accounts ac ON ac.id = cab.account_id
            WHERE cab.bank_account like '$strSearch%' 
            OR p.firstname like '$strSearch%' 
            OR p.lastname like '$strSearch%' 
            ORDER BY cab.id DESC $limit";  

            $sqlTotal = "SELECT count(*) as total 
            FROM banks cab
            LEFT JOIN person p ON p.idperson = cab.person_id
            LEFT JOIN accounting_accounts ac ON ac.id = cab.account_id
            WHERE cab.bank_account like '$strSearch%' 
            OR p.firstname like '$strSearch%' 
            OR p.lastname like '$strSearch%'";

            $request = $this->select_all($sql);
            $totalRecords = $this->select($sqlTotal)['total'];

            $arrData = getCalcPages($totalRecords,$intPage,$intPerPage);
            $arrData['data'] = $request;
            return $arrData;
        }

        public function selectDato($id){
            $sql = "SELECT cab.*, 
            ac.name as account,
            ac.code,
            CONCAT(p.firstname,' ',p.lastname) as nombre 
            FROM banks cab
            LEFT JOIN person p ON p.idperson = cab.person_id
            LEFT JOIN accounting_accounts ac ON ac.id = cab.account_id
            WHERE cab.id = ?";
            $request = $this->select($sql,[$id]);
            return $request;
        }
    }
?>