<?php 
    class ContabilidadModel extends Mysql{
        private $intId;
        private $intTopic;
        private $strDate;
        private $strDescription;
        private $strName;
        private $intType;
        private $intAmount;
        private $intStatus;

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
        public function insertIncome(int $intType,int $intTopic,string $strName,int $intAmount,string $strDate,int $intStatus,string $method){

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

                $sql  = "INSERT INTO count_amount(type_id,category_id,name,amount,date,status,method) VALUES(?,?,?,?,?,?,?)";
								  
	        	$arrData = array(
                    $this->intType,
                    $this->intTopic,
                    $this->strName,
                    $this->intAmount,
                    $dateFormat,
                    $this->intStatus,
                    $method
                );
	        	$request = $this->insert($sql,$arrData);
            }else{
                $sql  = "INSERT INTO count_amount(type_id,category_id,name,amount,status,method) VALUES(?,?,?,?,?,?)";
								  
	        	$arrData = array(
                    $this->intType,
                    $this->intTopic,
                    $this->strName,
                    $this->intAmount,
                    $this->intStatus,
                    $method
                );
	        	$request = $this->insert($sql,$arrData);
            }
	        return $request;
		}
        public function updateIncome(int $intId,int $intType,int $intTopic,string $strName,int $intAmount,string $strDate,int $intStatus,string $method){

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

                $sql  = "UPDATE count_amount SET type_id=?,category_id=?,name=?,amount=?,date=?,status=?,method=? WHERE id = $this->intId";
								  
	        	$arrData = array(
                    $this->intType,
                    $this->intTopic,
                    $this->strName,
                    $this->intAmount,
                    $dateFormat,
                    $this->intStatus,
                    $method
                );
	        	$request = $this->update($sql,$arrData);
            }else{
                $sql  = "UPDATE count_amount SET type_id=?,category_id=?,name=?,amount=?,status=?,method=? WHERE id = $this->intId";
								  
	        	$arrData = array(
                    $this->intType,
                    $this->intTopic,
                    $this->strName,
                    $this->intAmount,
                    $this->intStatus,
                    $method
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
        public function selecOutgoings(string $strSearch,int $intPerPage,int $intPageNow,string $strInitialDate,string $strFinalDate){
            $start = ($intPageNow-1)*$intPerPage;
            $sql = "SELECT a.*,
            a.id as id_egress,
            a.name as concepto,
            a.status as estado,
            c.name as categoria,
            DATE_FORMAT(a.date, '%d/%m/%Y') as date
            FROM count_amount a
            INNER JOIN count_category c
            WHERE a.category_id = c.id AND a.type_id != 3 AND c.status = 1 
            AND DATE(date) BETWEEN '$strInitialDate' AND '$strFinalDate' 
            AND (a.name LIKE '$strSearch%' OR c.name LIKE '$strSearch%' OR a.id LIKE '$strSearch%' OR a.id LIKE '$strSearch%' OR a.method LIKE '$strSearch%')
            ORDER BY DATE(a.date) DESC LIMIT $start,$intPerPage";
            $request = $this->select_all($sql);

            $sql = "SELECT a.*,
            a.id as id_egress,
            a.name as concepto,
            a.status as estado,
            c.name as categoria,
            DATE_FORMAT(a.date, '%d/%m/%Y') as date
            FROM count_amount a
            INNER JOIN count_category c
            WHERE a.category_id = c.id AND a.type_id != 3 AND c.status = 1 
            AND DATE(date) BETWEEN '$strInitialDate' AND '$strFinalDate' 
            AND (a.name LIKE '$strSearch%' OR c.name LIKE '$strSearch%' OR a.id LIKE '$strSearch%' OR a.id LIKE '$strSearch%' OR a.method LIKE '$strSearch%')
            ORDER BY DATE(a.date) DESC";
            $requestFull = $this->select_all($sql);

            $sqlTotal = "SELECT a.method,
            a.amount,
            a.id as id_egress,
            a.name as concepto,
            a.status as estado,
            c.name as categoria,
            DATE_FORMAT(a.date, '%d/%m/%Y') as date
            FROM count_amount a
            INNER JOIN count_category c
            WHERE a.category_id = c.id AND a.type_id != 3 AND c.status = 1 
            AND DATE(date) BETWEEN '$strInitialDate' AND '$strFinalDate' 
            AND (a.name LIKE '$strSearch%' OR c.name LIKE '$strSearch%' OR a.id LIKE '$strSearch%' OR a.id LIKE '$strSearch%' OR a.method LIKE '$strSearch%')
            ORDER BY DATE(a.date) DESC";

            $requestFull = $this->select_all($sqlTotal);
            $totalRecords = count($requestFull);
            $totalPages = $totalRecords > 0 ? ceil($totalRecords/$intPerPage) : 0;  
            $maxButtons = 4;
            $page = $intPageNow;
            $startPage = max(1, $page - floor($maxButtons / 2));
            if ($startPage + $maxButtons - 1 > $totalPages) {
                $startPage = max(1, $totalPages - $maxButtons + 1);
            }
            $limitPages = min($startPage + $maxButtons, $totalPages + 1);
            $arrResponse = array(
                "data"=>$request,
                "start_page"=>$startPage,
                "limit_page"=>$limitPages,
                "total_pages"=>$totalPages,
                "total_records"=>$totalRecords,
                "full_data"=>$requestFull
            );
            return $arrResponse;
        }
        public function insertEgress(int $intType,int $intTopic,string $strName,int $intAmount,string $strDate,int $intStatus,string $method){

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

                $sql  = "INSERT INTO count_amount(type_id,category_id,name,amount,date,status,method) VALUES(?,?,?,?,?,?,?)";
								  
	        	$arrData = array(
                    $this->intType,
                    $this->intTopic,
                    $this->strName,
                    $this->intAmount,
                    $dateFormat,
                    $this->intStatus,
                    $method
                );
	        	$request = $this->insert($sql,$arrData);
            }else{
                $sql  = "INSERT INTO count_amount(type_id,category_id,name,amount,status,method) VALUES(?,?,?,?,?,?)";
								  
	        	$arrData = array(
                    $this->intType,
                    $this->intTopic,
                    $this->strName,
                    $this->intAmount,
                    $this->intStatus,
                    $method
                );
	        	$request = $this->insert($sql,$arrData);
            }
	        return $request;
		}
        public function updateEgress(int $intId,int $intType,int $intTopic,string $strName,int $intAmount,string $strDate,int $intStatus,string $method){

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

                $sql  = "UPDATE count_amount SET type_id=?,category_id=?,name=?,amount=?,date=?,status=?,method=? WHERE id = $this->intId";
								  
	        	$arrData = array(
                    $this->intType,
                    $this->intTopic,
                    $this->strName,
                    $this->intAmount,
                    $dateFormat,
                    $this->intStatus,
                    $method
                );
	        	$request = $this->update($sql,$arrData);
            }else{
                $sql  = "UPDATE count_amount SET type_id=?,category_id=?,name=?,amount=?,status=?, method=? WHERE id = $this->intId";
								  
	        	$arrData = array(
                    $this->intType,
                    $this->intTopic,
                    $this->strName,
                    $this->intAmount,
                    $this->intStatus,
                    $method
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
        /*************************Info methods*******************************/
        public function selectAccountMonth(int $year, int $month){
            $totalPerMonth = 0;
            $totalCostos = 0;
            $totalGastos = 0;
            //$month = 7;
            $arrSalesDay = array();
            $arrCostos = array();
            $arrGastos = array();
            $days = cal_days_in_month(CAL_GREGORIAN,$month,$year);
            $day = 1;
            for ($i=0; $i < $days ; $i++) { 
                $date_create = date_create($year."-".$month."-".$day);
                $date_format = date_format($date_create,"Y-m-d");
                //Ingresos
                $sql ="SELECT 
                    DAY(date) AS day, 
                    COUNT(id) AS quantity, 
                    SUM(amount) AS total FROM count_amount
                    WHERE DATE(date) = '$date_format' AND status = 1 AND type_id = 3";
                $request = $this->select($sql);
                $request['day'] = $day;
                $request['total'] = $request['total'] =="" ? 0 : $request['total'];
                $totalPerMonth+=$request['total'];

                //Costos
                $sqlCostos ="SELECT 
                    DAY(date) AS day, 
                    COUNT(id) AS quantity, 
                    SUM(amount) AS total FROM count_amount 
                    WHERE DATE(date) = '$date_format' AND status = 1 AND type_id = 2";
                $requestCostos = $this->select($sqlCostos);
                $requestCostos['day'] = $day;
                $requestCostos['total'] = $requestCostos['total'] =="" ? 0 : $requestCostos['total'];
                $totalCostos+=$requestCostos['total'];

                //Gastos
                $sqlGastos ="SELECT 
                    DAY(date) AS day, 
                    COUNT(id) AS quantity, 
                    SUM(amount) AS total FROM count_amount 
                    WHERE DATE(date) = '$date_format' AND status = 1 AND type_id = 1";
                $requestGastos = $this->select($sqlGastos);
                $requestGastos['day'] = $day;
                $requestGastos['total'] = $requestGastos['total'] =="" ? 0 : $requestGastos['total'];
                $totalGastos+=$requestGastos['total'];

                array_push($arrSalesDay,$request);
                array_push($arrCostos,$requestCostos);
                array_push($arrGastos,$requestGastos);
                $day++;
            }
            $months = months();
            $arrData = array(
                "ingresos"=>array("year"=>$year,"month"=>$months[$month-1],"total"=>$totalPerMonth,"sales"=>$arrSalesDay),
                "costos"=>array("total"=>$totalCostos,"costos"=>$arrCostos),
                "gastos"=>array("total"=>$totalGastos,"gastos"=>$arrGastos)
            );
            return $arrData;
        }
        public function selectEgressMonth(int $year, int $month){
            $categories = $this->select_all("SELECT * FROM count_category WHERE status = 1 ORDER BY name");
            for ($i=0; $i < count($categories) ; $i++) { 
                $idCategory = $categories[$i]['id'];
                $categories[$i]['total'] = $this->select(
                    "SELECT IFNULL(SUM(CASE WHEN amount != '' THEN amount ELSE 0 END),0) as total 
                    FROM count_amount
                    WHERE category_id = $idCategory
                    AND MONTH(date) = $month AND YEAR(date) = $year AND status = 1"
                )['total'];
            }
            return $categories;   
        }
        public function selectAccountYear(int $year){
            $arrSalesMonth = array();
            $months = months();
            $total =0;
            $costos=0;
            $gastos=0;
            for ($i=1; $i <=12 ; $i++) { 
                $arrData = array("year"=>"","month"=>"","nmonth"=>"","sale"=>"","costos"=>"","gastos"=>"");
                //Ingresos
                $sql = "SELECT $year as year, 
                        $i as month, 
                        sum(amount) as sale 
                        FROM count_amount
                        WHERE MONTH(date) = $i AND YEAR(date) = $year AND status = 1 AND type_id = 3 
                        GROUP BY MONTH(date)";
                $request = $this->select($sql);
                //Costos
                $sqlCostos = "SELECT $year as year, 
                        $i as month, 
                        sum(amount) as total 
                        FROM count_amount
                        WHERE MONTH(date) = $i AND YEAR(date) = $year  AND status = 1 AND type_id = 2
                        GROUP BY MONTH(date)";
                $requestCostos = $this->select($sqlCostos);
                //Gastos
                $sqlGastos = "SELECT $year as year, 
                        $i as month, 
                        sum(amount) as total 
                        FROM count_amount
                        WHERE MONTH(date) = $i AND YEAR(date) = $year  AND status = 1 AND type_id = 1
                        GROUP BY MONTH(date)";
                $requestGastos = $this->select($sqlGastos);
                $arrData['month'] = $months[$i-1];
                if(empty($request)){
                    $arrData['year'] = $year;
                    $arrData['nmonth'] = $i;
                    $arrData['sale'] = 0;
                }else{
                    $arrData['year'] = $request['year'];
                    $arrData['nmonth'] = $request['month'];
                    $arrData['sale'] = $request['sale'];
                }
                if(empty($requestCostos)){
                    $arrData['costos'] = 0;
                }else{
                    $arrData['costos'] = $requestCostos['total'];
                }
                if(empty($requestGastos)){
                    $arrData['gastos'] = 0;
                }else{
                    $arrData['gastos'] = $requestGastos['total'];
                }
                $total+=$arrData['sale'];
                $costos+=$arrData['costos'];
                $gastos+=$arrData['gastos'];
                array_push($arrSalesMonth,$arrData);
                
            }
            $arrData = array("data"=>$arrSalesMonth,"total"=>$total,"costos"=>$costos,"gastos"=>$gastos);
            //dep($arrData);exit;
            return $arrData;
        }
        public function selectEgressYear(int $year){
            $categories = $this->select_all("SELECT * FROM count_category WHERE status = 1 ORDER BY name");
            for ($i=0; $i < count($categories) ; $i++) { 
                $idCategory = $categories[$i]['id'];
                for ($j=1; $j <= 12 ; $j++) { 
                    $categories[$i]['month'][$j] = $this->select(
                        "SELECT IFNULL(SUM(CASE WHEN amount != '' THEN amount ELSE 0 END),0) as total 
                        FROM count_amount
                        WHERE category_id = $idCategory
                        AND MONTH(date) = $j AND YEAR(date) = $year AND status = 1"
                    )['total'];
                }
                /*$categories[$i]['total'] = $this->select(
                    "SELECT IFNULL(SUM(CASE WHEN amount != '' THEN amount ELSE 0 END),0) as total 
                    FROM count_amount
                    WHERE category_id = $idCategory
                    AND MONTH(date) = $month AND YEAR(date) = $year AND status = 1"
                )['total'];*/

            }
            return $categories;   
        }
        public function selectMovements(){
            $year = date("Y");
            $sql = "SELECT 
            a.id,
            c.name,
            a.method,
            a.amount,
            a.name as detail,
            a.type_id,
            DATE_FORMAT(a.date,'%d/%m/%Y') as date 
            FROM count_amount as a
            INNER JOIN count_category as c
            ON a.category_id = c.id
            WHERE a.status = 1 AND YEAR(a.date) = '$year';
            ORDER BY DATE(a.date) DESC;";
            $movements = $this->select_all($sql);
            $sql_resume="SELECT 
            type_id,
            method,
            sum(amount) as total
            FROM count_amount
            WHERE status = 1 AND YEAR(date) = '$year'
            GROUP BY method, type_id
            ORDER BY method DESC;";
            $resume = $this->select_all($sql_resume);
            return array("movements"=>$movements,"resume"=>$resume);
        }
    }
?>