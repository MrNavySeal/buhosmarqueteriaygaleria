<?php 
    class UsuariosModel extends Mysql{
        private $intIdUser;
		private $strName;
        private $strLastName;
		private $strPicture;
		private $intPhone;
		private $strAddress;
		private $strEmail;
        private $intCountryId;
		private $intStateId;
		private $intCityId;
		private $intTypeId;
		private $strIdentification;
		private $strPassword;
		private $strToken;
		private $intRolId;
		private $intStatus;

        public function __construct(){
            parent::__construct();
        }
        public function insertUser(string $strName,string $strLastName, string $strPicture, string $intPhone, string $strEmail, string $strPassword,int $intStatus,int $intRolId){

			$this->strName = $strName;
			$this->strLastName = $strLastName;
			$this->intPhone = $intPhone;
			$this->strEmail = $strEmail;
			$this->strPassword = $strPassword;
			$this->intRolId = $intRolId;
            $this->strPicture = $strPicture;
            $this->intCountryId = 99999;
            $this->intStateId = 99999;
            $this->intCityId = 99999;
			$this->intStatus = $intStatus;
			$return = 0;

			$sql = "SELECT * FROM person WHERE 
					email = '{$this->strEmail}' OR phone = '{$this->intPhone}'";
			$request = $this->select_all($sql);

			if(empty($request))
			{ 
				$query_insert  = "INSERT INTO person(image,firstname,lastname,email,phone,countryid,stateid,cityid,password,status,roleid) 
								  VALUES(?,?,?,?,?,?,?,?,?,?,?)";
	        	$arrData = array(
                    $this->strPicture,
                    $this->strName,
                    $this->strLastName,
                    $this->strEmail,
                    $this->intPhone,
                    $this->intCountryId,
                    $this->intStateId,
                    $this->intCityId,
                    $this->strPassword,
                    $this->intStatus,
                    $this->intRolId
        		);
	        	$request_insert = $this->insert($query_insert,$arrData);
	        	$return = $request_insert;
			}else{
				$return = "exist";
			}
	        return $return;
		}
        public function updateUser(int $idUser, string $strName,string $strLastName, string $strPicture, string $intPhone, string $strEmail, string $strPassword,int $intStatus,int $intRolId){
            $this->intIdUser = $idUser;
			$this->strName = $strName;
			$this->strLastName = $strLastName;
			$this->intPhone = $intPhone;
			$this->strEmail = $strEmail;
			$this->strPassword = $strPassword;
			$this->intRolId = $intRolId;
            $this->strPicture = $strPicture;
            $this->intStatus = $intStatus;

			$sql = "SELECT * FROM person WHERE email = '{$this->strEmail}' AND phone = '{$this->intPhone}' AND idperson != $this->intIdUser";
			$request = $this->select_all($sql);

			if(empty($request)){
				if($this->strPassword  != ""){
					$sql = "UPDATE person SET image=?, firstname=?, lastname=?,email=?, phone=?, password=?, status=?,roleid=? 
							WHERE idperson = $this->intIdUser";
					$arrData = array(
                        $this->strPicture,
                        $this->strName,
                        $this->strLastName,
                        $this->strEmail,
                        $this->intPhone,
                        $this->strPassword,
                        $this->intStatus,
                        $this->intRolId
                    );
				}else{
					$sql = "UPDATE person SET image=?, firstname=?, lastname=?,email=?, phone=?, status=?,roleid=? 
							WHERE idperson = $this->intIdUser";
					$arrData = array(
                        $this->strPicture,
                        $this->strName,
                        $this->strLastName,
                        $this->strEmail,
                        $this->intPhone,
                        $this->intStatus,
                        $this->intRolId
                    );
				}
				$request = $this->update($sql,$arrData);
			}else{
				$request = "exist";
			}
			return $request;
		
		}
        public function deleteUser($id){
            $this->intIdUser = $id;
            $sql = "DELETE FROM person WHERE idperson = $this->intIdUser";
            $request = $this->delete($sql);
            return $request;
        }
        public function selectUsers(){
            $sql = "SELECT 
                    p.idperson,
                    p.image,
                    p.firstname,
                    p.lastname,
                    p.email,
                    p.phone,
                    p.roleid,
                    DATE_FORMAT(p.date, '%d/%m/%Y') as date,
                    p.status,
                    r.idrole,
                    r.name as role
                    FROM person p
                    INNER JOIN role r
                    ON r.idrole = p.roleid 
                    ORDER BY idperson DESC";
            $request = $this->select_all($sql);
            if(count($request)>0){
                for ($i=0; $i < count($request) ; $i++) { 
                    $request[$i]['image'] = media()."/images/uploads/".$request[$i]['image'];
                }
            }
            return $request;
        }
        public function selectUser($id){
            $this->intIdUser = $id;
            $sql = "SELECT 
                    p.idperson,
                    p.image,
                    p.firstname,
                    p.lastname,
                    p.email,
                    p.phone,
                    p.roleid,
                    p.countryid,
                    p.stateid,
                    p.cityid,
                    p.typeid,
                    p.identification,
                    DATE_FORMAT(p.date, '%d/%m/%Y') as date,
                    p.status,
                    r.idrole,
                    r.name as role,
                    c.id,
                    s.id,
                    t.id,
                    c.name as country,
                    s.name as state,
                    t.name as city
                    FROM person p
                    INNER JOIN role r, countries c, states s,cities t 
                    WHERE c.id = p.countryid AND p.stateid = s.id AND t.id = p.cityid AND r.idrole = p.roleid AND p.idperson = $this->intIdUser";
            $request = $this->select($sql);
            return $request;
        }
        public function selectRoles(){
            $sql = "SELECT * FROM role ORDER BY idrole ASC";
            $request = $this->select_all($sql);
            return $request;
        }
        public function search($search){
            $sql = "SELECT 
            p.idperson,
            p.image,
            p.firstname,
            p.lastname,
            p.email,
            p.phone,
            p.roleid,
            DATE_FORMAT(p.date, '%d/%m/%Y') as date,
            p.status,
            r.idrole,
            r.name as role
            FROM person p
            INNER JOIN role r
            ON r.idrole = p.roleid 
            WHERE r.name LIKE '%$search%' || p.firstname LIKE '%$search%' 
            ||  p.lastname LIKE '%$search%' ||  p.email LIKE '%$search%' 
            ||  p.phone LIKE '%$search%'
            ORDER BY idperson DESC";

            $request = $this->select_all($sql);
            if(count($request)>0){
                for ($i=0; $i < count($request) ; $i++) { 
                    $request[$i]['image'] = media()."/images/uploads/".$request[$i]['image'];
                }
            }
            return $request;
        }
        public function sort($sort){
            $option="DESC";
            if($sort == 2){
                $option = " ASC"; 
            }
            $sql = "SELECT 
                    p.idperson,
                    p.image,
                    p.firstname,
                    p.lastname,
                    p.email,
                    p.phone,
                    p.roleid,
                    DATE_FORMAT(p.date, '%d/%m/%Y') as date,
                    p.status,
                    r.idrole,
                    r.name as role
                    FROM person p
                    INNER JOIN role r
                    ON r.idrole = p.roleid 
                    ORDER BY idperson $option";
            $request = $this->select_all($sql);
            if(count($request)>0){
                for ($i=0; $i < count($request) ; $i++) { 
                    $request[$i]['image'] = media()."/images/uploads/".$request[$i]['image'];
                }
            }
            return $request;
        }
        /*************************Profile methods*******************************/
        public function updateProfile(int $idUser, string $strName,string $strLastName, string $strPicture, string $intPhone,string $strAddress, 
            int $intCountry, int $intState,int $intCity,string $strEmail, string $strPassword){
            
            $this->intIdUser = $idUser;
			$this->strName = $strName;
			$this->strLastName = $strLastName;
			$this->intPhone = $intPhone;
			$this->strEmail = $strEmail;
			$this->strPassword = $strPassword;
            $this->strPicture = $strPicture;
            $this->strAddress = $strAddress;
            $this->intCountryId = $intCountry;
            $this->intStateId = $intState;
            $this->intCityId = $intCity;

			$sql = "SELECT * FROM person WHERE email = '{$this->strEmail}' AND phone = '{$this->intPhone}' AND idperson != $this->intIdUser";
			$request = $this->select_all($sql);

			if(empty($request)){
				if($this->strPassword  != ""){
					$sql = "UPDATE person SET image=?, firstname=?, lastname=?,email=?, phone=?, address=?, countryid=?, stateid=?, cityid=?, password=? 
							WHERE idperson = $this->intIdUser";
					$arrData = array(
                        $this->strPicture,
                        $this->strName,
                        $this->strLastName,
                        $this->strEmail,
                        $this->intPhone,
                        $this->strAddress,
                        $this->intCountryId,
                        $this->intStateId,
                        $this->intCityId,
                        $this->strPassword
                    );
				}else{
					$sql = "UPDATE person SET image=?, firstname=?, lastname=?,email=?, phone=?, address=?, countryid=?, stateid=?, cityid=? 
							WHERE idperson = $this->intIdUser";
					$arrData = array(
                        $this->strPicture,
                        $this->strName,
                        $this->strLastName,
                        $this->strEmail,
                        $this->intPhone,
                        $this->strAddress,
                        $this->intCountryId,
                        $this->intStateId,
                        $this->intCityId
                    );
				}
				$request = $this->update($sql,$arrData);
                $_SESSION['userData'] = sessionUser($this->intIdUser);

			}else{
				$request = "exist";
			}
			return $request;
		
		}
        public function selectCountries(){
            $sql = "SELECT * FROM countries WHERE id = 47";
            $request = $this->select($sql);
            return $request;
        }
        public function selectStates($id){
            $sql = "SELECT * FROM states WHERE country_id = $id";
            $request = $this->select_all($sql);
            return $request;
        }
        public function selectCities($id){
            $sql = "SELECT * FROM cities WHERE state_id = $id";
            $request = $this->select_all($sql);
            return $request;
        }
    }
?>