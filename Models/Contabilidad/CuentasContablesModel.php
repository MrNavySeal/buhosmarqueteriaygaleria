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
            $digits = $arrData['digits'];

            $sql = "SELECT * FROM accounting_accounts WHERE code = '$code'";
            $request = $this->select($sql);
            if(empty($request)){
                $sql = "INSERT INTO accounting_accounts(code,name,type,nature,parent_id,level,status,digits)
                VALUES(?,?,?,?,?,?,?,?)";
                $data = [$code,$name,$type,$nature,$parentId,$level,$status,$digits];
                $request = $this->insert($sql,$data);
            }else{
                $request = "existe";
            }
            return $request;
        }

        public function updateDatos($arrData){
            $code = $arrData['parent_code'].$arrData['code'];
            $name = $arrData['name'];
            $type = $arrData['type'];
            $nature = $arrData['nature'];
            $parentId = $arrData['parent_id'];
            $level = $arrData['level'];
            $status = $arrData['status'];
            $digits = $arrData['digits'];
            $id = $arrData['id'];

            $sql = "SELECT * FROM accounting_accounts WHERE code = '$code' AND id != $id";
            $request = $this->select($sql);
            if(empty($request)){
                $sql = "UPDATE accounting_accounts SET code=?,name=?,type=?,nature=?,parent_id=?,level=?,status=?,digits=? 
                WHERE id = $id";
                $data = [$code,$name,$type,$nature,$parentId,$level,$status,$digits];
                $request = $this->update($sql,$data);
            }else{
                $request = "existe";
            }
            return $request;
        }
    }
?>