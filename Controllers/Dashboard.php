<?php
    class Dashboard extends Controllers{
        public function __construct(){
            session_start();
            if(empty($_SESSION['login'])){
                header("location: ".base_url());
                die();
            }
            parent::__construct();
            sessionCookie();
            getPermits(1);
        }

        public function dashboard(){
            if($_SESSION['permitsModule']['r']){
                $idUser =  $_SESSION['userData']['roleid'] == 2 ? $_SESSION['idUser'] : "";
                $data['totalUsers'] = $this->model->getTotalUsers();
                $data['totalCustomers'] = $this->model->getTotalCustomers();
                $data['totalOrders'] = $this->model->getTotalOrders($idUser);
                $data['totalSales'] = $this->model->getTotalSales();
                $data['totalViews'] = $this->model->getTotalViews();
                $data['orders'] = $this->model->getLastOrders($idUser);
                $data['products'] = $this->model->getLastProducts(4);
                $data['page_tag'] = "Dashboard";
                $data['page_title'] = "Dashboard";
                $data['page_name'] = "dashboard";
                $data['panelapp'] = "functions_dashboard.js";
                $year = date('Y');
                $month = date('m');
                $data['resumenMensual'] = $this->model->selectAccountMonth($year,$month);
                $data['resumenAnual'] = $this->model->selectAccountYear($year);
                //dep($data['salesMonth']);
                $this->views->getView($this,"dashboard",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function getContabilidadMes(){
            if($_POST){
                    if($_SESSION['permitsModule']['r']){
                    $arrDate = explode(" - ",$_POST['date']);
                    $month = $arrDate[0];
                    $year = $arrDate[1];
                    $request = $this->model->selectAccountMonth($year,$month);
                    
                    $ingresos = $request['ingresos']['total'];
                    $costos = $request['costos']['total'];
                    $gastos = $request['gastos']['total'];
                    $neto = $ingresos-($costos+$gastos);
                    
                    $html ="";
                    if($neto < 0){
                        $html = '<span class="text-danger">'.formatNum($neto).'</span>';
                    }else{
                        $html = '<span class="text-success">'.formatNum($neto).'</span>';
                    }
                    $request['dataingresos'] = $request['ingresos'];
                    $request['datacostos'] = $request['costos'];
                    $request['datagastos'] = $request['gastos'];
                    $request['mes'] =$request['ingresos']['month'];
                    $request['anio'] = $request['ingresos']['year'];
                    $request['ingresos'] =formatNum($ingresos);
                    $request['costos'] =formatNum($costos);
                    $request['gastos'] =formatNum($gastos);
                    $request['neto'] = $html;
                    $request['chart'] = "month";
                    $request['script'] = getFile("Template/Chart/chart_sales_month",$request);
                    echo json_encode($request,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }
        public function getContabilidadAnio(){
            if($_POST){
                if(empty($_POST['date'])){
                    $arrResponse=array("status"=>false,"msg"=>"Error de datos");
                }else{
                    //$year = intval($_POST['date']);
                    $strYear = strval($_POST['date']);
                    if(strlen($strYear)>4){
                        $arrResponse=array("status"=>false,"msg"=>"La fecha es incorrecta."); 
                    }else{
                        $year = intval($_POST['date']);
                        $request = $this->model->selectAccountYear($year);
                        $ingresos = $request['total'];
                        $costos = $request['costos'];
                        $gastos = $request['gastos'];
                        $neto = $ingresos-($costos+$gastos);
                        $request['chart'] = "year";
                        $script = getFile("Template/Chart/chart_sales_year",$request);
                        $arrResponse=array("status"=>true,"script"=>$script); 
                    }
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function getVisitasMes(){
            if($_POST){
                    if($_SESSION['permitsModule']['r']){
                    $arrDate = explode(" - ",$_POST['date']);
                    $month = $arrDate[0];
                    $year = $arrDate[1];
                    $request = $this->model->selectViewsMonth($year,$month);
                    $total = $request['info']['total'];
                    $html = '<span class="text-success">'.$total.'</span>';
                    $request['info_vistas'] = $request['info'];
                    $request['mes'] =$request['info']['month'];
                    $request['anio'] = $request['info']['year'];
                    $request['vistas'] =$total;
                    $request['vistas_neto'] = $html;
                    $request['script'] = getFile("Template/Chart/chart_views_month",$request);
                    echo json_encode($request,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }
        public function getVisitasAnio(){
            if($_POST){
                if(empty($_POST['date'])){
                    $arrResponse=array("status"=>false,"msg"=>"Error de datos");
                }else{
                    $strYear = strval($_POST['date']);
                    if(strlen($strYear)>4){
                        $arrResponse=array("status"=>false,"msg"=>"La fecha es incorrecta."); 
                    }else{
                        $request = $this->model->selectViewsYear($strYear);
                        $script = getFile("Template/Chart/chart_views_year",$request);
                        $arrResponse=array("status"=>true,"script"=>$script); 
                    }
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function getVisitasPais(){
            $request = $this->model->selectViewsCountry();
            $script = getFile("Template/Chart/chart_views_country",$request);
            $arrResponse=array("status"=>true,"script"=>$script); 
            echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
        }
        public function getVisitasPaginas(){
            $request = $this->model->selectViewsPage();
            $script = getFile("Template/Chart/chart_views_pages",$request);
            $arrResponse=array("status"=>true,"script"=>$script); 
            echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
        }
    }
?>