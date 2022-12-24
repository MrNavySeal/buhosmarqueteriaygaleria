<?php 
    class PaginasModel extends Mysql{
        private $intIdPage;
		private $strName;
        private $strRoute;
        private $strPhoto;
        private $strDescription;
        private $intStatus;
        private $intType;

        public function __construct(){
            parent::__construct();
        }
        /*************************Pages methods*******************************/
        public function insertPage(int $intType,string $photo,string $strName, string $strDescription,int $intStatus,string $strRoute){

			$this->strName = $strName;
			$this->strRoute = $strRoute;
            $this->strDescription = $strDescription;
            $this->strPhoto = $photo;
            $this->intType = $intType;
            $this->intStatus = $intStatus;
			$return = 0;

			$sql = "SELECT * FROM category WHERE 
					name = '{$this->strName}'";
			$request = $this->select_all($sql);

			if(empty($request))
			{ 
				$query_insert  = "INSERT INTO pages(type,picture,name,description,status,route) 
								  VALUES(?,?,?,?,?,?)";
	        	$arrData = array(
                    $this->intType,
                    $this->strPhoto,
                    $this->strName,
                    $this->strDescription,
                    $this->intStatus,
                    $this->strRoute
        		);
	        	$request_insert = $this->insert($query_insert,$arrData);
	        	$return = $request_insert;
			}else{
				$return = "exist";
			}
	        return $return;
		}
        public function updatePage(int $id,int $intType,string $photo,string $strName,string $strDescription,int $intStatus, string $strRoute){
            $this->intIdPage = $id;
            $this->strName = $strName;
			$this->strRoute = $strRoute;
            $this->strDescription = $strDescription;
            $this->strPhoto = $photo;
            $this->intType = $intType;
            $this->intStatus = $intStatus;
            

			$sql = "SELECT * FROM pages WHERE name = '{$this->strName}' AND id != $this->intIdPage";
			$request = $this->select_all($sql);

			if(empty($request)){

                $sql = "UPDATE pages SET type=?, picture=?, name=?,description=?, status=?,date_updated=NOW(), route=? WHERE id = $this->intIdPage";
                $arrData = array(
                    $this->intType,
                    $this->strPhoto,
                    $this->strName,
                    $this->strDescription,
                    $this->intStatus,
                    $this->strRoute
                );
				$request = $this->update($sql,$arrData);
			}else{
				$request = "exist";
			}
			return $request;
		
		}
        public function deletePage($id){
            $this->intIdPage = $id;
            $sql = "DELETE FROM pages WHERE id = $this->intIdPage;SET @autoid :=0; 
			UPDATE pages SET id = @autoid := (@autoid+1);
			ALTER TABLE pages Auto_Increment = 1";
            $return = $this->delete($sql);
            return $return;
        }
        public function selectPages(){
            $sql = "SELECT *,DATE_FORMAT(date_created, '%d/%m/%Y') as date,DATE_FORMAT(date_updated, '%d/%m/%Y') as dateupdated FROM pages ORDER BY id DESC";       
            $request = $this->select_all($sql);
            return $request;
        }
        public function selectPage($id){
            $this->intIdPage = $id;
            $sql = "SELECT * FROM pages WHERE id = $this->intIdPage";
            $request = $this->select($sql);
            return $request;
        }
        public function search($search){
            $sql = "SELECT *,DATE_FORMAT(date_created, '%d/%m/%Y') as date,DATE_FORMAT(date_updated, '%d/%m/%Y') as dateupdated FROM pages WHERE name LIKE '%$search%'";
            $request = $this->select_all($sql);
            return $request;
        }
        public function sort($sort){
            $option="DESC";
            if($sort == 2){
                $option = " ASC"; 
            }
            $sql = "SELECT *,DATE_FORMAT(date_created, '%d/%m/%Y') as date,DATE_FORMAT(date_updated, '%d/%m/%Y') as dateupdated FROM pages ORDER BY id $option ";
            $request = $this->select_all($sql);
            return $request;
        }
    }
?>