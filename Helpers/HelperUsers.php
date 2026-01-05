<?php
    class HelperUsers{
        public static function getUser($id){
            $con = new Mysql();
            $sql = "SELECT * FROM person WHERE idperson = $id";
            $request = $con->select($sql);
            return $request;
        }

        public static function destroySession(){
            session_unset();
            unset($_COOKIE['usercookie']);
            unset($_COOKIE['passwordcookie']);
            setcookie('usercookie', null, -1, '/'); 
            setcookie('passwordcookie', null, -1, '/'); 
            session_destroy();
        }

        public static function validEmail($email){
            $emailDomain = explode("@",$email)[1];
            $disposableDomains = ["airsworld.net"];
            return in_array($emailDomain,$disposableDomains);
        }
    }
    
?>