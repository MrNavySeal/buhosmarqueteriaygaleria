<?php 
    class BannersModel extends Mysql{
        private $intIdBanner;
        private $strLink;
		private $strName;
		private $intStatus;

        public function __construct(){
            parent::__construct();
        }
        /*************************Category methods*******************************/
        public function insertBanner(string $photo,string $strName,int $status, string $strLink){

			$this->strName = $strName;
			$this->strLink = $strLink;
            $this->strPhoto = $photo;
            $this->intStatus = $status;
			$return = 0;

			$sql = "SELECT * FROM banners WHERE 
					name = '{$this->strName}'";
			$request = $this->select_all($sql);

			if(empty($request))
			{ 
				$query_insert  = "INSERT INTO banners(picture,status,link,name) 
								  VALUES(?,?,?,?)";
	        	$arrData = array(
                    $this->strPhoto,
                    $this->intStatus,
                    $this->strLink,
                    $this->strName
        		);
	        	$request_insert = $this->insert($query_insert,$arrData);
	        	$return = $request_insert;
			}else{
				$return = "exist";
			}
	        return $return;
		}
        public function updateBanner(int $intIdBanner,string $photo, string $strName,int $status, string $strLink){
            $this->intIdBanner = $intIdBanner;
            $this->strName = $strName;
			$this->strLink = $strLink;
            $this->strPhoto = $photo;
            $this->intStatus = $status;

			$sql = "SELECT * FROM banners WHERE name = '{$this->strName}' AND id_banner != $this->intIdBanner";
			$request = $this->select_all($sql);

			if(empty($request)){

                $sql = "UPDATE banners SET picture=?,status=?,link=?,name=? WHERE id_banner = $this->intIdBanner";
                $arrData = array(
                    $this->strPhoto,
                    $this->intStatus,
                    $this->strLink,
                    $this->strName
                );
				$request = $this->update($sql,$arrData);
			}else{
				$request = "exist";
			}
			return $request;
		
		}
        public function deleteBanner($id){
            $this->intIdBanner = $id;
            $sql = "DELETE FROM banners WHERE id_banner = $this->intIdBanner";
            $return = $this->delete($sql);
            return $return;
        }
        public function selectBanners(){
            $sql = "SELECT * FROM banners ORDER BY id_banner DESC";       
            $request = $this->select_all($sql);
            return $request;
        }
        public function selectBanner($id){
            $this->intIdBanner = $id;
            $sql = "SELECT * FROM banners WHERE id_banner = $this->intIdBanner";
            $request = $this->select($sql);
            return $request;
        }
    }
?>