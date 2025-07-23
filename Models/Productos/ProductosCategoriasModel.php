<?php 
    class ProductosCategoriasModel extends Mysql{
        private $intIdCategory;
        private $intIdSubCategory;
		private $strName;
        private $strPhoto;
        private $strDescription;
		private $intStatus;
        private $strRoute;
        public function __construct(){
            parent::__construct();
        }
        public function getSelectSubcategories(int $intIdCategory){
            $this->intIdCategory = $intIdCategory;
            $sql = "SELECT  
                    s.idsubcategory,
                    s.name,
                    s.categoryid,
                    c.idcategory,
                    c.name as category
                    FROM subcategory s
                    INNER JOIN category c
                    ON c.idcategory = s.categoryid
                    WHERE s.categoryid = $this->intIdCategory
                    ORDER BY s.name ASC";       
            $request = $this->select_all($sql);
            return $request;
        }
        /*************************Category methods*******************************/
        public function insertCategoria(string $photo,string $strName,int $status, string $strDescription, string $strRoute, int $isVisible){

			$this->strName = $strName;
			$this->strRoute = $strRoute;
            $this->strDescription = $strDescription;
            $this->strPhoto = $photo;
            $this->intStatus = $status;
			$return = 0;

			$sql = "SELECT * FROM category WHERE 
					name = '{$this->strName}'";
			$request = $this->select_all($sql);

			if(empty($request))
			{ 
				$query_insert  = "INSERT INTO category(picture,name,status,description,route,is_visible) 
								  VALUES(?,?,?,?,?,?)";
	        	$arrData = array(
                    $this->strPhoto,
                    $this->strName,
                    $this->intStatus,
                    $this->strDescription,
                    $this->strRoute,
                    $isVisible
        		);
	        	$request_insert = $this->insert($query_insert,$arrData);
	        	$return = $request_insert;
			}else{
				$return = "exist";
			}
	        return $return;
		}
        public function updateCategoria(int $intIdCategory,string $photo, string $strName,int $status, string $strDescription,string $strRoute, int $isVisible){
            $this->intIdCategory = $intIdCategory;
            $this->strName = $strName;
            $this->strDescription = $strDescription;
			$this->strRoute = $strRoute;
            $this->strPhoto = $photo;
            $this->intStatus = $status;

			$sql = "SELECT * FROM category WHERE name = '{$this->strName}' AND idcategory != $this->intIdCategory";
			$request = $this->select_all($sql);

			if(empty($request)){

                $sql = "UPDATE category SET picture=?, name=?,status=?,description=?, route=?,is_visible=? WHERE idcategory = $this->intIdCategory";
                $arrData = array(
                    $this->strPhoto,
                    $this->strName,
                    $this->intStatus,
                    $this->strDescription,
                    $this->strRoute,
                    $isVisible
                );
				$request = $this->update($sql,$arrData);
			}else{
				$request = "exist";
			}
			return $request;
		
		}
        public function deleteCategoria($id){
            $this->intIdCategory = $id;
            $sql = "SELECT * FROM subcategory WHERE categoryid = $this->intIdCategory";
            $request = $this->select_all($sql);
            $return = "";
            if(empty($request)){
                $sql = "DELETE FROM category WHERE idcategory = $this->intIdCategory";
                $return = $this->delete($sql);
            }else{
                $return="exist";
            }
            return $return;
        }
        public function selectCategorias($intPage,$intPerPage,$strSearch){
            $limit ="";
            $intStartPage = ($intPage-1)*$intPerPage;
            if($intPerPage != 0){
                $limit = " LIMIT $intStartPage,$intPerPage";
            }    
            $sql = "SELECT *,idcategory as id FROM category WHERE name like '$strSearch%' ORDER BY idcategory DESC $limit";  
            $request = $this->select_all($sql);

            $sqlTotal = "SELECT count(*) as total FROM category WHERE name like '$strSearch%' ORDER BY idcategory";
            foreach ($request as &$data) { 
                $data['url'] = media()."/images/uploads/".$data['picture'];
            }

            $totalRecords = $this->select($sqlTotal)['total'];
            $totalPages = intval($totalRecords > 0 ? ceil($totalRecords/$intPerPage) : 0);
            $totalPages = $totalPages == 0 ? 1 : $totalPages;
            $startPage = max(1, $intStartPage - floor(BUTTONS / 2));
            if ($startPage + BUTTONS - 1 > $totalPages) {
                $startPage = max(1, $totalPages - BUTTONS + 1);
            }
            $limitPages = min($startPage + BUTTONS, $totalPages+1);
            $arrButtons = [];
            for ($i=$startPage; $i < $limitPages; $i++) { 
                array_push($arrButtons,$i);
            }
            $arrData = array(
                "data"=>$request,
                "start_page"=>$startPage,
                "limit_page"=>$limitPages,
                "total_pages"=>$totalPages,
                "total_records"=>$totalRecords,
                "buttons"=>$arrButtons
            );
            return $arrData;
        }
        public function selectCategoria($id){
            $this->intIdCategory = $id;
            $sql = "SELECT * FROM category WHERE idcategory = $this->intIdCategory";
            $request = $this->select($sql);
            return $request;
        }
        /*************************SubCategory methods*******************************/
        public function insertSubCategory(int $intIdCategory ,string $strName,int $status,string $strRoute){
            $this->intIdCategory = $intIdCategory;
			$this->strName = $strName;
			$this->strRoute = $strRoute;
            $this->intStatus = $status;
			$return = 0;
			$sql = "SELECT * FROM subcategory WHERE name = '{$this->strName}' AND categoryid = $this->intIdCategory";
			$request = $this->select_all($sql);
			if(empty($request)){
				$query_insert  = "INSERT INTO subcategory(categoryid,name,status,route) VALUES(?,?,?,?)";  
	        	$arrData = array(
                    $this->intIdCategory,
                    $this->strName,
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
        public function updateSubCategory(int $intIdSubCategory,int $intIdCategory, string $strName,int $status,string $strRoute){
            $this->intIdSubCategory = $intIdSubCategory;
            $this->intIdCategory = $intIdCategory;
            $this->strName = $strName;
			$this->strRoute = $strRoute;
            $this->intStatus = $status;
			$sql = "SELECT * FROM subcategory WHERE name = '{$this->strName}' AND idsubcategory != $this->intIdSubCategory AND categoryid = $this->intIdCategory";
			$request = $this->select_all($sql);

			if(empty($request)){

                $sql = "UPDATE subcategory SET categoryid=?,name=?, status=?,route=? WHERE idsubcategory = $this->intIdSubCategory";
                $arrData = array(
                    $this->intIdCategory,
                    $this->strName,
                    $this->intStatus,
                    $this->strRoute
                );
				$request = $this->update($sql,$arrData);
			}else{
				$request = "exist";
			}
			return $request;
		}
        public function deleteSubCategory($id){
            $this->intIdSubCategory = $id;
            $sql="SELECT * FROM product WHERE subcategoryid = $id";
            $request = $this->select_all($sql);
            $return="";
            if(empty($request)){
                $sql = "DELETE FROM subcategory WHERE idsubcategory = $this->intIdSubCategory";
                $request = $this->delete($sql);
                $return = $request;
            }else{
                $return ="exist";
            }
            return $return;
        }
        public function selectCategoriesSel(){
            $sql = "SELECT * FROM category WHERE status = 1 ORDER BY idcategory DESC";       
            $request = $this->select_all($sql);
            return $request;
        }
        public function selectSubCategories(){
            $sql = "SELECT  
                    s.idsubcategory,
                    s.name,
                    s.categoryid,
                    c.idcategory,
                    c.name as category,
                    s.status
                    FROM subcategory s
                    INNER JOIN category c
                    ON c.idcategory = s.categoryid
                    ORDER BY idsubcategory DESC";       
            $request = $this->select_all($sql);
            return $request;
        }
        public function selectSubCategory($id){
            $this->intIdSubCategory = $id;
            $sql = "SELECT * FROM subcategory WHERE idsubcategory = $this->intIdSubCategory";
            $request = $this->select($sql);
            return $request;
        }
    }
?>