<?php 

	class LoginModel extends Mysql
	{
        private $intIdUser;
        private $strEmail;
        private $strPassword;
        private $strToken;

		public function __construct()
		{
			parent::__construct();
        }	
        
        public function loginUser(string $email, string $password){
			$this->strEmail = $email;
			$this->strPassword = $password;
			$sql = "SELECT idperson,email,status FROM person WHERE 
					email = '$this->strEmail' AND 
					password = '$this->strPassword'";
			$request = $this->select($sql);
			return $request;
        }
        
        public function sessionLogin(int $iduser){
            $this->intIdUser = $iduser;

            $sql = "SELECT  *  FROM person WHERE idperson = $this->intIdUser";
            $request = $this->select($sql);
            $_SESSION['userData'] = $request;
            return $request;
        }

        public function getUserEmail(string $email){
            $this->strEmail = $email;
            $sql = "SELECT idperson, firstname, lastname FROM person WHERE
                    email='$this->strEmail'";
            $request = $this->select($sql);
            return $request;
        }

        public function setTokenUser(int $idpersona,string $token){
            $this->intIdUser = $idpersona;
            $this->strToken = $token;
            $sql = "UPDATE person SET token = ? WHERE idperson = $this->intIdUser";
            $arrData = array($this->strToken);
            $request = $this->update($sql,$arrData);
            return $request;
        }

        public function getUser(string $email, string $token){
            $this->strEmail=$email;
            $this->strToken = $token;
            $sql ="SELECT idperson FROM person WHERE
                    email = '$this->strEmail' and token= '$this->strToken'";
            $request =$this->select($sql);
            return $request;
        }

        public function insertPassword(int $idperson, string $pass){
            $this->intIdUser = $idperson;
            $this->strPassword = $pass;
            $sql = "UPDATE person SET password = ?, token = ? WHERE idperson = $this->intIdUser";
            $arrData = array($this->strPassword,"");
            $request = $this->update($sql,$arrData);
            return $request;
        }
	}
 ?>