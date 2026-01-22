<?php
    class HelperAccounting{
        public static function getAccounts($parent = 0){
            $con = new Mysql();
            $sql = "SELECT * FROM accounting_accounts WHERE parent_id = $parent ORDER BY code";
            $request = $con->select_all($sql);
            $accounts = [];
            foreach ($request as $acc) {
                $acc['children'] = HelperAccounting::getAccounts($acc['id']);
                $accounts[] = $acc;
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