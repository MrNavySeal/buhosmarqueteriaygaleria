<?php
    class HelperAccounting{
        const TIPOS_CONCEPTOS = [
            1=>["id"=>1,"name"=>"Ingresos"],
            2=>["id"=>2,"name"=>"Gastos"],
            3=>["id"=>3,"name"=>"Almacén"],
            4=>["id"=>4,"name"=>"Retenciones"],
            5=>["id"=>5,"name"=>"Impuestos"],
        ];

        public static function getAccounts($parent = 0,$search=""){
            $con = new Mysql();
            $accounts = [];
            if($search != ""){
                $sql = "SELECT * FROM accounting_accounts WHERE code LIKE '$search%' OR name LIKE '$search%'  ORDER BY code";
                $request = $con->select_all($sql);
                foreach ($request as $acc) {
                    $acc['children'] = HelperAccounting::getAccounts($acc['id']);
                    $accounts[] = $acc;
                }
            }else{
                $sql = "SELECT * FROM accounting_accounts WHERE parent_id = $parent  ORDER BY code";
                $request = $con->select_all($sql);
                foreach ($request as $acc) {
                    $acc['children'] = HelperAccounting::getAccounts($acc['id']);
                    $accounts[] = $acc;
                }
            }
            return $accounts;
        }

        public static function getParentAccounts($account,$accounts=[],$flag = true){
            $con = new Mysql();
            $sql = "SELECT * FROM accounting_accounts WHERE id = $account";
            $request = $con->select($sql);

            if ($request) {
                if (!empty($request['parent_id']) && $request['parent_id'] != 0) {
                    $accounts = HelperAccounting::getParentAccounts($request['parent_id'], $accounts);
                }
                if($flag){$accounts[] = $request;}
                
            }
            return $accounts;
        }
    }
?>