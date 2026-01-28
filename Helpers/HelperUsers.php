<?php
    class HelperUsers{
        const TIPO_REGIMEN = [ 1=>["id"=>1,"name"=>"Responsable de IVA"], 2=>["id"=>2,"name"=>"No responsable de IVA"]];
        const TIPO_PERSONA = [ 1=>["id"=>1,"name"=>"Persona jurídica"], 2=>["id"=>2,"name"=>"Persona natural"]];
        const TIPO_IDENTIFICACION = [ 
            11=>["id"=>11,"name"=>"Registro civil"], 
            12=>["id"=>12,"name"=>"Tarjeta de identidad"],
            13=>["id"=>13,"name"=>"Cedula de ciudadanía"],
            21=>["id"=>21,"name"=>"Tarjeta de extranjería"],
            22=>["id"=>22,"name"=>"Cédula de extranjería"],
            31=>["id"=>31,"name"=>"NIT"],
            41=>["id"=>41,"name"=>"Pasaporte"],
        ];

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