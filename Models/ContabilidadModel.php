<?php 
    class ContabilidadModel extends Mysql{
        private $intId;
        private $strNit;
        private $strDescription;
        private $strName;
        private $intType;
        private $intAmount;

        public function __construct(){
            parent::__construct();
        }

        public function insertCost(int $intStatus,string $strDate, string $strNit, string $strName, string $strDescription, int $intTotal){

			$this->strName = $strName;
			$this->strNit = $strNit;
            $this->intStatus = $intStatus;
            $this->strDescription = $strDescription;
            $this->intAmount = $intTotal;
            if($strDate !=""){
                $arrDate = explode("-",$strDate);
                $dateCreated = date_create($arrDate[2]."-".$arrDate[1]."-".$arrDate[0]);
                $dateFormat = date_format($dateCreated,"Y-m-d");

                $query_insert  = "INSERT INTO accounting(type,nit,name,description,total,date) VALUES(?,?,?,?,?,?)";	  
                $arrData = array($this->intStatus, $this->strNit,$this->strName, $this->strDescription,$this->intAmount,$dateFormat);
                $request = $this->insert($query_insert,$arrData);
            }else{
                $query_insert  = "INSERT INTO accounting(type,nit,name,description,total) VALUES(?,?,?,?,?)";	  
                $arrData = array($this->intStatus, $this->strNit,$this->strName, $this->strDescription,$this->intAmount);
                $request = $this->insert($query_insert,$arrData);
            }
	        return $request;
		}
        public function updateCost(int $id,int $intStatus,string $strDate, string $strNit, string $strName, string $strDescription, int $intTotal){
            $this->strName = $strName;
			$this->strNit = $strNit;
            $this->intStatus = $intStatus;
            $this->strDescription = $strDescription;
            $this->intAmount = $intTotal;
            $this->intId = $id;
            
            $arrDate = explode("-",$strDate);
            $dateCreated = date_create($arrDate[2]."-".$arrDate[1]."-".$arrDate[0]);
            $dateFormat = date_format($dateCreated,"Y-m-d");

            $sql = "UPDATE accounting SET type=?,nit=?,name=?,description=?, total=?,date=? WHERE id = $this->intId";
            $arrData = array($this->intStatus, $this->strNit,$this->strName, $this->strDescription,$this->intAmount,$dateFormat);
            $request = $this->update($sql,$arrData);
            
			return $request;
		
		}
        public function deleteCost($id){
            $this->intId = $id;
            $sql = "DELETE FROM accounting WHERE id = $this->intId";
            $return = $this->delete($sql);
            return $return;
        }
        public function selectCosts(){
            $sql = "SELECT *,DATE_FORMAT(date, '%d/%m/%Y') as date FROM accounting ORDER BY date DESC";       
            $request = $this->select_all($sql);
            return $request;
        }
        public function selectCost($id){
            $this->intId = $id;
            $sql = "SELECT *,DATE_FORMAT(date, '%d/%m/%Y') as date FROM accounting WHERE id = $this->intId";
            $request = $this->select($sql);
            return $request;
        }
        public function search($search){
            $sql = "SELECT * FROM accounting WHERE name LIKE '%$search%' || nit LIKE '%$search%'";
            $request = $this->select_all($sql);
            return $request;
        }
        public function sort($sort){
            $option=" ORDER BY id DESC";
            if($sort == 2){
                $option = " ORDER BY id ASC"; 
            }else if( $sort == 3){
                $option = " ORDER BY type ASC"; 
            }
            $sql = "SELECT * FROM accounting $option";
            $request = $this->select_all($sql);
            return $request;
        }
        public function selectOrders(){
            $sql = "SELECT * ,DATE_FORMAT(date, '%d/%m/%Y') as date FROM orderdata ORDER BY date DESC";       
            $request = $this->select_all($sql);
            return $request;
        }
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
                    COUNT(idorder) AS quantity, 
                    SUM(amount) AS total FROM orderdata 
                    WHERE DATE(date) = '$date_format' AND status = 'approved'";
                $request = $this->select($sql);
                $request['day'] = $day;
                $request['total'] = $request['total'] =="" ? 0 : $request['total'];
                $totalPerMonth+=$request['total'];

                //Costos
                $sqlCostos ="SELECT 
                    DAY(date) AS day, 
                    COUNT(id) AS quantity, 
                    SUM(total) AS total FROM accounting 
                    WHERE DATE(date) = '$date_format' AND type = 1";
                $requestCostos = $this->select($sqlCostos);
                $requestCostos['day'] = $day;
                $requestCostos['total'] = $requestCostos['total'] =="" ? 0 : $requestCostos['total'];
                $totalCostos+=$requestCostos['total'];
                

                //Gastos
                $sqlGastos ="SELECT 
                    DAY(date) AS day, 
                    COUNT(id) AS quantity, 
                    SUM(total) AS total FROM accounting 
                    WHERE DATE(date) = '$date_format' AND type = 2";
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
                "gastos"=>array("total"=>$totalGastos,"gastos"=>$arrGastos),
            );
            return $arrData;
        }
        public function selectAccountYear(int $year){
            $arrSalesMonth = array();
            $months = months();
            $total =0;
            $costos=0;
            $gastos=0;
            for ($i=1; $i <= 12 ; $i++) { 
                $arrData = array("year"=>"","month"=>"","nmonth"=>"","sale"=>"","costos"=>"","gastos"=>"");
                //Ingresos
                $sql = "SELECT $year as year, 
                        $i as month, 
                        sum(amount) as sale 
                        FROM orderdata
                        WHERE MONTH(date) = $i AND YEAR(date) = $year AND status = 'approved' 
                        GROUP BY MONTH(date)";
                $request = $this->select($sql);
                //Costos
                $sqlCostos = "SELECT $year as year, 
                        $i as month, 
                        sum(total) as total 
                        FROM accounting
                        WHERE MONTH(date) = $i AND YEAR(date) = $year AND type = 1 
                        GROUP BY MONTH(date)";
                $requestCostos = $this->select($sqlCostos);
                //Gastos
                $sqlGastos = "SELECT $year as year, 
                        $i as month, 
                        sum(total) as total 
                        FROM accounting
                        WHERE MONTH(date) = $i AND YEAR(date) = $year AND type = 2 
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
    }
?>