<?php 
    class ContabilidadModel extends Mysql{
        private $intId;
        private $intTopic;
        private $strDate;
        private $strDescription;
        private $strName;
        private $intType;
        private $intAmount;

        public function __construct(){
            parent::__construct();
        }
        /*************************Category methods*******************************/
        public function insertCategory(string $strName, int $intType, int $intStatus){

			$this->strName = $strName;
            $this->intStatus = $intStatus;
            $this->intType = $intType;
			$return = 0;

			$sql = "SELECT * FROM count_category WHERE 
					name = '{$this->strName}'";
			$request = $this->select_all($sql);

			if(empty($request))
			{ 
				$query_insert  = "INSERT INTO count_category(name,type,status) VALUES(?,?,?)";
								  
	        	$arrData = array($this->strName,$this->intType,$this->intStatus);
	        	$request_insert = $this->insert($query_insert,$arrData);
	        	$return = $request_insert;
			}else{
				$return = "exist";
			}
	        return $return;
		}
        public function updateCategory(int $intIdCategory,string $strName, int $intType,int $intStatus){
            $this->intId = $intIdCategory;
            $this->strName = $strName;
            $this->intStatus = $intStatus;
            $this->intType = $intType;
			$sql = "SELECT * FROM count_category WHERE name = '{$this->strName}' AND id != $this->intId";
			$request = $this->select_all($sql);

			if(empty($request)){

                $sql = "UPDATE count_category SET name=?,type=?,status=? WHERE id = $this->intId";
                $arrData = array($this->strName,$this->intType,$this->intStatus);
				$request = $this->update($sql,$arrData);
			}else{
				$request = "exist";
			}
			return $request;
		
		}
        public function deleteCategory($id){
            $this->intId = $id;
            $sql = "SELECT * FROM count_amount WHERE category_id = $this->intId";
            $request = $this->select_all($sql);
            if(empty($request)){

                $sql = "DELETE FROM count_category WHERE id = $this->intId;SET @autoid :=0; 
                UPDATE count_category SET id = @autoid := (@autoid+1);
                ALTER TABLE count_category Auto_Increment = 1";
                $return = $this->delete($sql);
            }else{
                $return ="exists";
            }
            return $return;
        }
        public function selectCategories(){
            $sql = "SELECT * FROM count_category ORDER BY id DESC";       
            $request = $this->select_all($sql);
            return $request;
        }
        public function selectCategory($id){
            $this->intId = $id;
            $sql = "SELECT * FROM count_category WHERE id = $this->intId";
            $request = $this->select($sql);
            return $request;
        }
        /*************************Income methods*******************************/
        public function selectIncomes(){
            $sql = "SELECT *,
            a.id as id_income,
            a.name as concepto,
            a.status as estado,
            c.name as categoria,
            DATE_FORMAT(a.date, '%d/%m/%Y') as date
            FROM count_amount a
            INNER JOIN count_category c
            WHERE a.category_id = c.id AND a.type_id = 3 AND c.status = 1 ORDER BY a.id DESC";
            $request = $this->select_all($sql);
            return $request;
        }
        public function insertIncome(int $intType,int $intTopic,string $strName,int $intAmount,string $strDate,int $intStatus){

			$this->strName = $strName;
            $this->intType = $intType;
            $this->intTopic = $intTopic;
            $this->strName = $strName;
            $this->intAmount = $intAmount;
            $this->strDate = $strDate;
            $this->intStatus = $intStatus;
            $request="";
            if($this->strDate){
                $arrDate = explode("-",$this->strDate);
                $dateCreated = date_create($arrDate[2]."-".$arrDate[1]."-".$arrDate[0]);
                $dateFormat = date_format($dateCreated,"Y-m-d");

                $sql  = "INSERT INTO count_amount(type_id,category_id,name,amount,date,status) VALUES(?,?,?,?,?,?)";
								  
	        	$arrData = array(
                    $this->intType,
                    $this->intTopic,
                    $this->strName,
                    $this->intAmount,
                    $dateFormat,
                    $this->intStatus
                );
	        	$request = $this->insert($sql,$arrData);
            }else{
                $sql  = "INSERT INTO count_amount(type_id,category_id,name,amount,status) VALUES(?,?,?,?,?)";
								  
	        	$arrData = array(
                    $this->intType,
                    $this->intTopic,
                    $this->strName,
                    $this->intAmount,
                    $this->intStatus
                );
	        	$request = $this->insert($sql,$arrData);
            }
	        return $request;
		}
        public function updateIncome(int $intId,int $intType,int $intTopic,string $strName,int $intAmount,string $strDate,int $intStatus){

			$this->strName = $strName;
            $this->intType = $intType;
            $this->intTopic = $intTopic;
            $this->strName = $strName;
            $this->intAmount = $intAmount;
            $this->strDate = $strDate;
            $this->intStatus = $intStatus;
            $this->intId = $intId;
            $request="";
            if($this->strDate){
                $arrDate = explode("-",$this->strDate);
                $dateCreated = date_create($arrDate[2]."-".$arrDate[1]."-".$arrDate[0]);
                $dateFormat = date_format($dateCreated,"Y-m-d");

                $sql  = "UPDATE count_amount SET type_id=?,category_id=?,name=?,amount=?,date=?,status=? WHERE id = $this->intId";
								  
	        	$arrData = array(
                    $this->intType,
                    $this->intTopic,
                    $this->strName,
                    $this->intAmount,
                    $dateFormat,
                    $this->intStatus
                );
	        	$request = $this->update($sql,$arrData);
            }else{
                $sql  = "UPDATE count_amount SET type_id=?,category_id=?,name=?,amount=?,status=? WHERE id = $this->intID";
								  
	        	$arrData = array(
                    $this->intType,
                    $this->intTopic,
                    $this->strName,
                    $this->intAmount,
                    $this->intStatus
                );
	        	$request = $this->update($sql,$arrData);
            }
	        return $request;
		}
        public function selectIncome($id){
            $this->intId = $id;
            $sql = "SELECT *,DATE_FORMAT(date, '%d/%m/%Y') as date FROM count_amount WHERE id = $this->intId";
            $request = $this->select($sql);
            return $request;
        }
        public function deleteIncome($id){
            $this->intId = $id;
            $sql = "DELETE FROM count_amount WHERE id = $this->intId";
            $return = $this->delete($sql);
            return $return;
        }
        /*************************Egress methods*******************************/
        public function selecOutgoings(){
            $sql = "SELECT *,
            a.id as id_egress,
            a.name as concepto,
            a.status as estado,
            c.name as categoria,
            DATE_FORMAT(a.date, '%d/%m/%Y') as date
            FROM count_amount a
            INNER JOIN count_category c
            WHERE a.category_id = c.id AND a.type_id != 3 AND c.status = 1 ORDER BY a.id DESC";
            $request = $this->select_all($sql);
            return $request;
        }
        public function insertEgress(int $intType,int $intTopic,string $strName,int $intAmount,string $strDate,int $intStatus){

			$this->strName = $strName;
            $this->intType = $intType;
            $this->intTopic = $intTopic;
            $this->strName = $strName;
            $this->intAmount = $intAmount;
            $this->strDate = $strDate;
            $this->intStatus = $intStatus;
            $request="";
            if($this->strDate){
                $arrDate = explode("-",$this->strDate);
                $dateCreated = date_create($arrDate[2]."-".$arrDate[1]."-".$arrDate[0]);
                $dateFormat = date_format($dateCreated,"Y-m-d");

                $sql  = "INSERT INTO count_amount(type_id,category_id,name,amount,date,status) VALUES(?,?,?,?,?,?)";
								  
	        	$arrData = array(
                    $this->intType,
                    $this->intTopic,
                    $this->strName,
                    $this->intAmount,
                    $dateFormat,
                    $this->intStatus
                );
	        	$request = $this->insert($sql,$arrData);
            }else{
                $sql  = "INSERT INTO count_amount(type_id,category_id,name,amount,status) VALUES(?,?,?,?,?)";
								  
	        	$arrData = array(
                    $this->intType,
                    $this->intTopic,
                    $this->strName,
                    $this->intAmount,
                    $this->intStatus
                );
	        	$request = $this->insert($sql,$arrData);
            }
	        return $request;
		}
        public function updateEgress(int $intId,int $intType,int $intTopic,string $strName,int $intAmount,string $strDate,int $intStatus){

			$this->strName = $strName;
            $this->intType = $intType;
            $this->intTopic = $intTopic;
            $this->strName = $strName;
            $this->intAmount = $intAmount;
            $this->strDate = $strDate;
            $this->intStatus = $intStatus;
            $this->intId = $intId;
            $request="";
            if($this->strDate){
                $arrDate = explode("-",$this->strDate);
                $dateCreated = date_create($arrDate[2]."-".$arrDate[1]."-".$arrDate[0]);
                $dateFormat = date_format($dateCreated,"Y-m-d");

                $sql  = "UPDATE count_amount SET type_id=?,category_id=?,name=?,amount=?,date=?,status=? WHERE id = $this->intId";
								  
	        	$arrData = array(
                    $this->intType,
                    $this->intTopic,
                    $this->strName,
                    $this->intAmount,
                    $dateFormat,
                    $this->intStatus
                );
	        	$request = $this->update($sql,$arrData);
            }else{
                $sql  = "UPDATE count_amount SET type_id=?,category_id=?,name=?,amount=?,status=? WHERE id = $this->intID";
								  
	        	$arrData = array(
                    $this->intType,
                    $this->intTopic,
                    $this->strName,
                    $this->intAmount,
                    $this->intStatus
                );
	        	$request = $this->update($sql,$arrData);
            }
	        return $request;
		}
        public function selectEgress($id){
            $this->intId = $id;
            $sql = "SELECT *,DATE_FORMAT(date, '%d/%m/%Y') as date FROM count_amount WHERE id = $this->intId";
            $request = $this->select($sql);
            return $request;
        }
        public function deleteEgress($id){
            $this->intId = $id;
            $sql = "DELETE FROM count_amount WHERE id = $this->intId";
            $return = $this->delete($sql);
            return $return;
        }
        public function selectCatIncome(int $option){
            $sql = "select * from count_category where type = $option and status = 1 order by name desc";
            $request = $this->select_all($sql);
            return $request;
        }
    }
?>