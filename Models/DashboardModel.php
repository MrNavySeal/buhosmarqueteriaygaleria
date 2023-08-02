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
        function getLastProducts($cant=""){
            if($cant !=""){
                $cant = " LIMIT $cant";
            }
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
                c.route as routec,
                s.route as routes,
                DATE_FORMAT(p.date, '%d/%m/%Y') as date
            FROM product p
            INNER JOIN category c, subcategory s
            WHERE c.idcategory = p.categoryid AND c.idcategory = s.categoryid AND p.subcategoryid = s.idsubcategory AND p.status = 1
            ORDER BY p.idproduct DESC $cant";
            
            $request = $this->select_all($sql);
            if(count($request)> 0){
                for ($i=0; $i < count($request); $i++) { 
                    $request[$i]['price'] = round((($request[$i]['price']*COMISION)+TASA)/1000)*1000;
                    $idProduct = $request[$i]['idproduct'];
                    $sqlImg = "SELECT * FROM productimage WHERE productid = $idProduct";
                    $requestImg = $this->select_all($sqlImg);
                    $request[$i]['favorite'] = 0;
                    if(isset($_SESSION['login'])){
                        $idUser = $_SESSION['idUser'];
                        $sqlFavorite = "SELECT * FROM wishlist WHERE productid = $idProduct AND personid = $idUser";
                        $requestFavorite = $this->select($sqlFavorite);
                        if(!empty($requestFavorite)){
                            $request[$i]['favorite'] = $requestFavorite['status'];
                        }
                    }
                    if(count($requestImg)>0){
                        $request[$i]['url'] = media()."/images/uploads/".$requestImg[0]['name'];
                        $request[$i]['image'] = $requestImg[0]['name'];
                    }else{
                        $request[$i]['image'] = media()."/images/uploads/image.png";
                    }
                    if($request[$i]['product_type'] == 2){
                        $sqlV = "SELECT MIN(price) AS minimo FROM product_variant WHERE productid =$idProduct AND stock > 0";
                        $sqlTotal = "SELECT SUM(stock) AS stock FROM product_variant WHERE productid =$idProduct AND stock > 0";
                        $request[$i]['price'] = round((($this->select($sqlV)['minimo']*COMISION)+TASA)/1000)*1000;
                        $request[$i]['stock'] = $this->select($sqlTotal)['stock'];
                    }
                }
            }
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

    }
?>