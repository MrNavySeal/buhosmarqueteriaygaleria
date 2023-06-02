<?php
    class DashboardModel extends Mysql{
        private $intIdUser;
        public function __construct(){
            parent::__construct();
        }

        function getTotalUsers(){
            $sql = "SELECT COUNT(*) as total FROM person WHERE idperson!=1 ";
            $request = $this->select($sql);
            return $request['total'];
        }
        function getTotalCustomers(){
            $sql = "SELECT COUNT(*) as total FROM person WHERE roleid=2";
            $request = $this->select($sql);
            return $request['total'];
        }
        function getTotalOrders($idUser){
            $option="";
            if($idUser!=""){
                $option = " WHERE personid = $idUser";
            }

            $sql = "SELECT COUNT(*) as total FROM orderdata $option";
            $request = $this->select($sql);
            return $request['total'];
        }
        function getTotalSales(){
            $sql = "SELECT sum(amount) as total FROM orderdata WHERE status='approved'";
            $request = $this->select($sql);
            $request['total'] = $request['total'] =="" ? 0 : $request['total'];
            return formatNum($request['total']);
        }
        function getLastOrders($idUser){
            $option="";
            if($idUser!=""){
                $option = " WHERE personid = $idUser";
            }
            $sql = "SELECT * FROM orderdata $option ORDER BY idorder DESC LIMIT 10";
            $request = $this->select_all($sql);
            return $request;
        }
        function getLastProducts(){
            $sql = "SELECT 
                p.idproduct,
                p.categoryid,
                p.subcategoryid,
                p.reference,
                p.name,
                p.description,
                p.price,
                p.discount,
                p.description,
                p.stock,
                p.status,
                p.product_type,
                p.route,
                c.idcategory,
                c.name as category,
                s.idsubcategory,
                s.categoryid,
                s.name as subcategory,
                DATE_FORMAT(p.date, '%d/%m/%Y') as date
            FROM product p
            INNER JOIN category c, subcategory s
            WHERE c.idcategory = p.categoryid AND c.idcategory = s.categoryid AND p.subcategoryid = s.idsubcategory
            AND p.status = 1 ORDER BY idproduct  DESC LIMIT 10
            ";
            $request = $this->select_all($sql);
            if(count($request)> 0){
                for ($i=0; $i < count($request); $i++) { 
                    $idProduct = $request[$i]['idproduct'];
                    $request[$i]['price'] = round((($request[$i]['price']*COMISION)+TASA)/1000)*1000;
                    $sqlImg = "SELECT * FROM productimage WHERE productid = $idProduct";
                    $requestImg = $this->select_all($sqlImg);
                    if(count($requestImg)>0){
                        $request[$i]['image'] = media()."/images/uploads/".$requestImg[0]['name'];
                    }else{
                        $request[$i]['image'] = media()."/images/uploads/image.png";
                    }
                    if($request[$i]['product_type'] == 2){
                        $sqlV = "SELECT MIN(price) AS minimo FROM product_variant WHERE productid =$idProduct";
                        $sqlTotal = "SELECT SUM(stock) AS total FROM product_variant WHERE productid =$idProduct";
                        $sqlVariants = "SELECT * FROM product_variant WHERE productid = $idProduct ORDER BY price ASC";
                        $request[$i]['price'] = round((($this->select($sqlV)['minimo']*COMISION)+TASA)/1000)*1000;
                        $request[$i]['stock'] = $this->select($sqlTotal)['total'];
                    }
                }
            }
            return $request;
            /*$sql = "SELECT * FROM product WHERE status = 1 ORDER BY idproduct  DESC LIMIT 10";
            $request = $this->select_all($sql);
            if(!empty($request)){
                for ($i=0; $i < count($request) ; $i++) { 
                    $request[$i]['price'] = ($request[$i]['price']*COMISION)+TASA;
                }
            }
            return $request;*/
        }
        public function selectAccountMonth(int $year, int $month){
            $totalPerMonth = 0;
            $totalCostos = 0;
            //$month = 7;
            $arrSalesDay = array();
            $arrCostos = array();
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
                    COUNT(idpurchase) AS quantity, 
                    SUM(total) AS total FROM purchase 
                    WHERE DATE(date) = '$date_format'";
                $requestCostos = $this->select($sqlCostos);
                $requestCostos['day'] = $day;
                $requestCostos['total'] = $requestCostos['total'] =="" ? 0 : $requestCostos['total'];
                $totalCostos+=$requestCostos['total'];

                array_push($arrSalesDay,$request);
                array_push($arrCostos,$requestCostos);

                $day++;
            }
            $months = months();
            $arrData = array(
                "ingresos"=>array("year"=>$year,"month"=>$months[$month-1],"total"=>$totalPerMonth,"sales"=>$arrSalesDay),
                "costos"=>array("total"=>$totalCostos,"costos"=>$arrCostos)
            );
            return $arrData;
        }
        public function selectAccountYear(int $year){
            $arrSalesMonth = array();
            $months = months();
            $total =0;
            $costos=0;
            for ($i=1; $i <=12 ; $i++) { 
                $arrData = array("year"=>"","month"=>"","nmonth"=>"","sale"=>"","costos"=>"");
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
                        FROM purchase
                        WHERE MONTH(date) = $i AND YEAR(date) = $year 
                        GROUP BY MONTH(date)";
                $requestCostos = $this->select($sqlCostos);
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
                $total+=$arrData['sale'];
                $costos+=$arrData['costos'];
                array_push($arrSalesMonth,$arrData);
                
            }
            $arrData = array("data"=>$arrSalesMonth,"total"=>$total,"costos"=>$costos);
            //dep($arrData);exit;
            return $arrData;
        }

    }
?>