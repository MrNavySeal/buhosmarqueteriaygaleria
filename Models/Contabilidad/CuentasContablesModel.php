<?php 
    class CuentasContablesModel extends Mysql{

        public function __construct(){
            parent::__construct();
        }

        public function insertDatos($arrData){
            $code = $arrData['parent_code'].$arrData['code'];
            $name = $arrData['name'];
            $type = $arrData['type'];
            $nature = $arrData['nature'];
            $parentId = $arrData['parent_id'];
            $level = $arrData['level'];
            $status = $arrData['status'];

            $sql = "SELECT * FROM accounting_accounts WHERE code = '$code'";
            $request = $this->select($sql);
            if(empty($request)){
                $sql = "INSERT INTO accounting_accounts(code,name,type,nature,parent_id,level,status)
                VALUES(?,?,?,?,?,?,?)";
                $data = [$code,$name,$type,$nature,$parentId,$level,$status];
                $request = $this->insert($sql,$data);
            }else{
                $request = "existe";
            }
            return $request;
        }
    }
?>