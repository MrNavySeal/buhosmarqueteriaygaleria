<?php 
    class EmpresaModel extends Mysql{
        private $logo;
        private $strName;
        private $intCurrency;
        private $strCompanyEmail;
        private $strEmail;
        private $strPassword;
        private $strPhone;
        private $strAddress;
        private $strKeywords;
        private $strDescription;
        private $intCountry;
        private $intState;
        private $intCity;
        private $strClient;
        private $strSecret;

        public function __construct(){
            parent::__construct();
        }

        public function selectCurrencies(){
            $sql = "SELECT * FROM currency";
            $request = $this->select_all($sql);
            return $request;
        }
        public function selectCompany(){
            $data = array();
            $sql ="SELECT 
                    c.name as country,
                    s.name as state,
                    cy.name as city
                    FROM countries c 
                    INNER JOIN states s, cities cy, company co
                    WHERE c.id = co.country AND s.id = co.state AND cy.id = co.city";
            
            $data = $this->select("SELECT * FROM company");
            $location = $this->select($sql);
            $data['addressfull'] = $data['address'].", ".$location['country'].", ".$location['state'].", ".$location['city'];
            $data['currency'] = $this->select("SELECT c.id,c.code, c.symbol FROM currency c INNER JOIN company co WHERE c.id = co.currency");
            //dep($data);exit;
            return $data;
        }
        public function updateCompany($logo,$strName,$intCurrency,$strCompanyEmail,$strEmail,$strPassword,$intCountry,$intState,$intCity,$strPhone,$strAddress,$strKeywords,$strDescription){
            
            $this->logo = $logo;
            $this->strName = $strName;
            $this->intCurrency = $intCurrency;
            $this->strCompanyEmail = $strCompanyEmail;
            $this->strEmail = $strEmail;
            $this->strPassword = $strPassword;
            $this->strPhone = $strPhone;
            $this->strAddress = $strAddress;
            $this->strKeywords = $strKeywords;
            $this->strDescription = $strDescription;
            $this->intCountry = $intCountry;
            $this->intState = $intState;
            $this->intCity = $intCity;
            $phonecode = $this->select("SELECT phonecode FROM countries WHERE id = $this->intCountry");
            $code = $phonecode['phonecode'];

            $sql = "UPDATE company SET logo=?, name=?, currency=?,email=?,secondary_email=?,password=?,country=?,state=?,city=?,phonecode=?,phone=?,address=?,keywords=?,description=?";
            $arrData = array(
                $this->logo,
                $this->strName,
                $this->intCurrency,
                $this->strCompanyEmail,
                $this->strEmail,
                $this->strPassword,
                $this->intCountry,
                $this->intState,
                $this->intCity,
                $code,
                $this->strPhone,
                $this->strAddress,
                $this->strKeywords,
                $this->strDescription
            );
            $request = $this->update($sql,$arrData);
            return $request;
        }
        public function updateSocial($facebook,$twitter,$youtube,$instagram,$linkedin,$whatsapp){
            $sql = "UPDATE social SET facebook=?, twitter=?,youtube=?,instagram=?,linkedin=?,whatsapp=?";
            $arrData=array($facebook,$twitter,$youtube,$instagram,$linkedin,$whatsapp);
            $request = $this->update($sql,$arrData);
            return $request;
        }
        public function updateCredentials($client,$secret){
            $this->strClient = $client;
            $this->strSecret = $secret;
            $sql = "UPDATE paypalcredentials SET client=?,secret=?";
            $arrData = array($this->strClient,$this->strSecret);
            $request = $this->update($sql,$arrData);
            return $request;
        }
        public function selectCredentials(){
            $request = $this->select("SELECT * FROM paypalcredentials");
            return $request;
        }
        public function selectSocial(){
            $request = $this->select("SELECT * FROM social");
            return $request;
        }
        public function selectCountries(){
            $sql = "SELECT * FROM countries";
            $request = $this->select_all($sql);
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