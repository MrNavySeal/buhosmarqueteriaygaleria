<?php 
    class ClientesModel extends Mysql{
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
        public function insertCustomer(string $strName,string $strLastName, string $strPicture, string $intPhone, string $strEmail,string $strAddress, int $intCountry,int $intState,int $intCity,string $strPassword,int $intStatus,int $intRolId){
            $this->strPicture = $strPicture;
			$this->strName = $strName;
			$this->strLastName = $strLastName;
            $this->strEmail = $strEmail;
			$this->intPhone = $intPhone;
            $this->strAddress = $strAddress;
            $this->intCountryId = $intCountry;
            $this->intStateId = $intState;
            $this->intCityId = $intCity;
            $this->strPassword = $strPassword;
            $this->intStatus = $intStatus;
            $this->intRolId = $intRolId;
			$return = 0;

			$sql = "SELECT * FROM person WHERE 
					email = '{$this->strEmail}' OR phone = '{$this->intPhone}'";
			$request = $this->select_all($sql);

			if(empty($request))
			{ 
				$query_insert  = "INSERT INTO person(image,firstname,lastname,email,phone,address,countryid,stateid,cityid,password,status,roleid) 
								  VALUES(?,?,?,?,?,?,?,?,?,?,?,?)";
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
        public function updateCustomer(int $idUser, string $strName,string $strLastName, string $strPicture, string $intPhone,string $strEmail, string $strAddress, int $intCountry,int $intState,int $intCity, string $strPassword,int $intStatus,int $intRolId){
            $this->intIdUser = $idUser;
			$this->strPicture = $strPicture;
			$this->strName = $strName;
			$this->strLastName = $strLastName;
            $this->strEmail = $strEmail;
			$this->intPhone = $intPhone;
            $this->strAddress = $strAddress;
            $this->intCountryId = $intCountry;
            $this->intStateId = $intState;
            $this->intCityId = $intCity;
            $this->strPassword = $strPassword;
            $this->intStatus = $intStatus;
            $this->intRolId = $intRolId;

			$sql = "SELECT * FROM person WHERE email = '{$this->strEmail}' AND phone = '{$this->intPhone}' AND idperson != $this->intIdUser";
			$request = $this->select_all($sql);

			if(empty($request)){
				if($this->strPassword  != ""){
					$sql = "UPDATE person SET image=?, firstname=?, lastname=?,email=?, phone=?,address=?,countryid=?,stateid=?,cityid=?, password=?, status=?,roleid=? 
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
                        $this->strPassword,
                        $this->intStatus,
                        $this->intRolId
                    );
				}else{
					$sql = "UPDATE person SET image=?, firstname=?, lastname=?,email=?, phone=?,address=?,countryid=?,stateid=?,cityid=?,status=?,roleid=? 
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
        public function deleteCustomer($id){
            $this->intIdUser = $id;
            $sql = "DELETE FROM person WHERE idperson = $this->intIdUser";
            $request = $this->delete($sql);
            return $request;
        }
        public function selectCustomers(){
            $sql = "SELECT *,DATE_FORMAT(date, '%d/%m/%Y') as date FROM person  WHERE roleid = 2 ORDER BY idperson DESC";
            $request = $this->select_all($sql);
            if(count($request)>0){
                for ($i=0; $i < count($request) ; $i++) { 
                    $request[$i]['image'] = media()."/images/uploads/".$request[$i]['image'];
                }
            }
            return $request;
        }
        public function selectCustomer($id){
            $this->intIdUser = $id;
            $sql = "SELECT 
                    p.idperson,
                    p.image,
                    p.firstname,
                    p.lastname,
                    p.email,
                    p.phone,
                    p.address,
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
        public function search($search){
            $sql = "SELECT *,DATE_FORMAT(date, '%d/%m/%Y') as date
            FROM person 
            WHERE firstname LIKE '%$search%' AND roleid=2
            ||  lastname LIKE '%$search%' AND roleid=2 ||  email LIKE '%$search%' AND roleid=2
            ||  phone LIKE '%$search%' AND roleid=2
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
            $option=" DESC";
            if($sort == 2){
                $option = " ASC"; 
            }
            $sql = "SELECT *,DATE_FORMAT(date, '%d/%m/%Y') as date FROM person WHERE roleid=2 ORDER BY idperson $option";
            

            $request = $this->select_all($sql);
            if(count($request)>0){
                for ($i=0; $i < count($request) ; $i++) { 
                    $request[$i]['image'] = media()."/images/uploads/".$request[$i]['image'];
                }
            }
            return $request;
        }
        public function selectCountries(){
            $sql = "SELECT * FROM countries WHERE id = 47";
            $request = $this->select($sql);
            return $request;
        }
        public function selectStates($country){
            $request = $this->select_all("SELECT * FROM states WHERE country_id = $country");
            return $request;
        }
        public function selectCities($state){
            $request = $this->select_all("SELECT * FROM cities WHERE state_id = $state");
            return $request;
        }

    }
?>