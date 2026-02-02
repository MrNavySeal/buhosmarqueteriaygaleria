<?php
    class HelperAccounting{
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

        public static function getConceptTypes(){
            $sql = "SELECT id,name FROM accounting_concept_types WHERE status = 1 ORDER BY name";
            $con = new Mysql();
            $request = $con->select_all($sql);
            return $request;
        }
    }
?>