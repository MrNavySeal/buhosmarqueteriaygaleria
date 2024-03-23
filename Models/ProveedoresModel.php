<?php 
    class ProveedoresModel extends Mysql{
        private $strName;
        private $intStatus;
        private $intIdCategory;
        private $intIdSupplier;
        private $intIdMeasure;
        private $arrData;
        public function __construct(){
            parent::__construct();
        }
        /*************************Measures methods*******************************/
        public function insertMeasure(array $arrData){
            $this->arrData = $arrData;
			$return = 0;
			$sql = "SELECT * FROM measures WHERE name = '{$this->arrData['name']}'";
			$request = $this->select_all($sql);

			if(empty($request)){ 
				$query_insert  = "INSERT INTO measures(name,initials,status)  VALUES(?,?,?)";
	        	$arrData = array(
                    $this->arrData['name'],
                    $this->arrData['initials'],
                    $this->arrData['status']
                );
	        	$request_insert = $this->insert($query_insert,$arrData);
	        	$return = $request_insert;
			}else{
				$return = "exist";
			}
	        return $return;
		}
        public function updateMeasure(int $intIdMeasure,array $arrData){
            $this->intIdMeasure = $intIdMeasure;
            $this->arrData = $arrData;

			$sql = "SELECT * FROM measures WHERE name = '{$this->arrData['name']}' AND id_measure != $this->intIdMeasure";
			$request = $this->select_all($sql);
            $return = 0;
			if(empty($request)){
                
                $sql = "UPDATE measures SET name=?,initials=?,status=? WHERE id_measure = $this->intIdMeasure";
                $arrData = array(
                    $this->arrData['name'],
                    $this->arrData['initials'],
                    $this->arrData['status']
                );
				$request = $this->update($sql,$arrData);
                $return = intval($request);
			}else{
				$return = "exist";
			}
			return $return;
		
		}
        public function deleteMeasure($id){
            $this->intIdMeasure = $id;
            $sql = "DELETE FROM measures WHERE id_measure = $this->intIdMeasure";
            $request = $this->delete($sql);
            return $request;
        }
        public function selectMeasure($id){
            $this->intIdMeasure = $id;
            $sql = "SELECT * FROM measures WHERE id_measure = $this->intIdMeasure";
            $request = $this->select($sql);
            return $request;
        }
        public function selectMeasures(){
            $sql = "SELECT * FROM measures";
            $request = $this->select_all($sql);
            return $request;
        }

        /*************************Suppliers methods*******************************/
        public function insertSupplier(array $arrData){
            $this->arrData = $arrData;
            $sql = "SELECT * FROM supplier WHERE name = '{$this->arrData['name']}'";
            $request = $this->select_all($sql);
            $return = "";
            if(empty($request)){
                $sql="INSERT 
                INTO supplier(name,nit,email,website,phone,address,country_id,state_id,city_id,status,img,contacts) 
                VALUES(?,?,?,?,?,?,?,?,?,?,?,?)";
                $arrData = array(
                    $this->arrData['name'],
                    $this->arrData['nit'],
                    $this->arrData['email'],
                    $this->arrData['web'],
                    $this->arrData['phone'],
                    $this->arrData['address'],
                    $this->arrData['country'],
                    $this->arrData['state'],
                    $this->arrData['city'],
                    $this->arrData['status'],
                    $this->arrData['img'],
                    $this->arrData['contacts']
                );
                $request_insert = $this->insert($sql,$arrData);
                $return = $request_insert;
            }else{
                $return = "exist";
            }
            return $return;
        }
        public function updateSupplier(int $id,array $arrData){
            $this->arrData = $arrData;
            $this->intIdSupplier = $id;
            $sql = "SELECT * FROM supplier WHERE name = '{$this->arrData['name']}' AND id_supplier != $this->intIdSupplier";
            
            $request = $this->select_all($sql);
            $return = "";
            if(empty($request)){
                $sql="UPDATE supplier SET dateupdated=NOW(),name=?,nit=?,email=?,
                website=?,phone=?,address=?,country_id=?,
                state_id=?,city_id=?,status=?,img=?,contacts=? 
                WHERE id_supplier = $this->intIdSupplier";
                $arrData = array(
                    $this->arrData['name'],
                    $this->arrData['nit'],
                    $this->arrData['email'],
                    $this->arrData['web'],
                    $this->arrData['phone'],
                    $this->arrData['address'],
                    $this->arrData['country'],
                    $this->arrData['state'],
                    $this->arrData['city'],
                    $this->arrData['status'],
                    $this->arrData['img'],
                    $this->arrData['contacts']
                );
                $request = $this->update($sql,$arrData);
                $return = intval($request);
            }else{
                $return = "exist";
            }
            return $return;
        }
        public function selectSuppliers(){
            $sql = "SELECT 
                s.id_supplier,
                s.name,
                s.nit,
                s.email,
                s.website,
                s.phone,
                s.address,
                co.name as country,
                st.name as state,
                ci.name as city,
                s.status,
                DATE_FORMAT(s.datecreated,'%d/%m/%Y') as datecreated,
                DATE_FORMAT(s.dateupdated,'%d/%m/%Y') as dateupdated,
                s.img
                FROM supplier s
                INNER JOIN countries co ON s.country_id = co.id
                INNER JOIN cities ci ON s.city_id = ci.id
                INNER JOIN states st on s.state_id = st.id
                ORDER BY s.id_supplier DESC
            ";
            $request = $this->select_all($sql);
            if(!empty($request)){
                $rows = count($request);
                for ($i=0; $i < $rows; $i++) { 
                    $request[$i]['img'] = media()."/images/uploads/".$request[$i]['img'];
                    $request[$i]['address'] = $request[$i]['address']." - ".$request[$i]['city']."/".$request[$i]['state']."/".$request[$i]['country'];
                }
            }
            return $request;
        }
        public function selectSupplier(int $id){
            $this->intIdSupplier = $id;
            $sql = "SELECT 
                s.id_supplier,
                s.name,
                s.nit,
                s.email,
                s.website,
                s.phone,
                s.address,
                co.name as country,
                st.name as state,
                ci.name as city,
                s.status,
                s.contacts,
                co.id as id_country,
                st.id as id_state,
                ci.id as id_city,
                DATE_FORMAT(s.datecreated,'%d/%m/%Y') as datecreated,
                DATE_FORMAT(s.dateupdated,'%d/%m/%Y') as dateupdated,
                s.img
                FROM supplier s
                INNER JOIN countries co ON s.country_id = co.id
                INNER JOIN cities ci ON s.city_id = ci.id
                INNER JOIN states st on s.state_id = st.id
                WHERE s.id_supplier = $this->intIdSupplier
            ";
            $request = $this->select($sql);
            $request['image'] = $request['img'];
            $request['img'] = media()."/images/uploads/".$request['img'];
            $request['compact_address'] = $request['address']." - ".$request['city']."/".$request['state']."/".$request['country'];
            $request['contacts'] = $request['contacts'] != "" ? json_decode($request['contacts'],true) : [];
            return $request;
        }
        public function deleteSupplier(int $id){
            $this->intIdSupplier = $id;
            $sql = "DELETE FROM supplier WHERE id_supplier = $this->intIdSupplier";
            $request = $this->delete($sql);
            return $request;
        }
        /*************************Others methods*******************************/
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