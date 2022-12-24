<?php
    class Logout{
        public function __construct(){
            session_start();
            session_unset();
            unset($_COOKIE['usercookie']);
            unset($_COOKIE['passwordcookie']);
            setcookie('usercookie', null, -1, '/'); 
            setcookie('passwordcookie', null, -1, '/'); 
            session_destroy();
            header('location: '.base_url());
            die();
        }
    }
?>