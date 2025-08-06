<?php 
    class ArticulosModel extends Mysql{
        private $intIdArticle;
		private $strName;
        private $strDescription;
		private $intStatus;
        private $strRoute;
        private $strPicture;
        private $strShortDescription;

        public function __construct(){
            parent::__construct();
        }
        /*************************Article methods*******************************/
        public function insertArticle(string $strPicture,string $strName,string $strShortDescription,string $strDescription,int $intStatus,string $route){
            
			$this->strName = $strName;
            $this->strDescription = $strDescription;
            $this->strShortDescription = $strShortDescription;
            $this->strPicture = $strPicture;
			$this->intStatus = $intStatus;
			$this->strRoute = $route;

			$return = 0;
			$sql = "SELECT * FROM article WHERE name = '$this->strName'";
			$request = $this->select_all($sql);

			if(empty($request)){ 			
				$query_insert  = "INSERT INTO article(name,picture,shortdescription,description,status,route) VALUES(?,?,?,?,?,?)";
	        	$arrData = array($this->strName,$this->strPicture,$this->strShortDescription,$this->strDescription,$this->intStatus,$this->strRoute);
	        	$request_insert = $this->insert($query_insert,$arrData);
	        	$return = $request_insert;
			}else{
				$return = "exist";
			}
	        return $return;
		}
        public function updateArticle(int $idArticle, string $strPicture, string $strName,string $strShortDescription, string $strDescription,int $intStatus,string $route){
            $this->intIdArticle = $idArticle;
            $this->strName = $strName;
            $this->strDescription = $strDescription;
            $this->strShortDescription = $strShortDescription;
            $this->strPicture = $strPicture;
			$this->intStatus = $intStatus;
			$this->strRoute = $route;

			$sql = "SELECT * FROM article WHERE name = '{$this->strName}' AND idarticle != $this->intIdArticle";
			$request = $this->select_all($sql);

			if(empty($request)){
                $sql = "UPDATE article SET name=?,picture=?,shortdescription=?,description=?,status=?,route=?,date_updated=NOW() WHERE idarticle = $this->intIdArticle";
                $arrData = array($this->strName,$this->strPicture,$this->strShortDescription,$this->strDescription,$this->intStatus,$this->strRoute);
				$request = $this->update($sql,$arrData);
			}else{
				$request = "exist";
			}
			return $request;
		}
        public function deleteArticle($id){
            $this->intIdArticle = $id;
            $sql = "DELETE FROM article WHERE idarticle = $this->intIdArticle";
            $request = $this->delete($sql);
            return $request;
        }
        public function selectArticles(){
            $sql = "SELECT *,
                    DATE_FORMAT(date_created, '%d/%m/%Y') as date,
                    DATE_FORMAT(date_updated, '%d/%m/%Y') as dateupdated
                    FROM article ORDER BY idarticle DESC";
            $request = $this->select_all($sql);
            return $request;
        }
        public function selectArticle($id){
            $this->intIdArticle = $id;
            $sql = "SELECT *FROM article WHERE idarticle = $this->intIdArticle";
            $request = $this->select($sql);
            return $request;
        }
    }
?>