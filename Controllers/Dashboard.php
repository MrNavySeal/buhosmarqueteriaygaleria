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
                $data['orders'] = $this->model->getLastOrders($idUser);
                $data['products'] = $this->model->getLastProducts();
                $data['page_tag'] = "Dashboard";
                $data['page_title'] = "Dashboard";
                $data['page_name'] = "dashboard";
                $data['app'] = "functions_dashboard.js";
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
                    $neto = $ingresos-$costos;
                    
                    $html ="";
                    if($neto < 0){
                        $html = '<span class="text-danger">'.formatNum($neto).'</span>';
                    }else{
                        $html = '<span class="text-success">'.formatNum($neto).'</span>';
                    }
                    $request['dataingresos'] = $request['ingresos'];
                    $request['datacostos'] = $request['costos'];
                    $request['mes'] =$request['ingresos']['month'];
                    $request['anio'] = $request['ingresos']['year'];
                    $request['ingresos'] =formatNum($ingresos);
                    $request['costos'] =formatNum($costos);
                    $request['neto'] = $html;
                    $request['chart'] = "month";
                    $request['script'] = getFile("Template/Chart/chart",$request);
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
                        $neto = $ingresos-$costos;
                        
                        $html ="";
                        if($neto < 0){
                            $html = '<span class="text-danger">'.formatNum($neto).'</span>';
                        }else{
                            $html = '<span class="text-success">'.formatNum($neto).'</span>';
                        }
                        $request['chart'] = "year";
                        $script = getFile("Template/Chart/chart",$request);
                        $arrResponse=array("status"=>true,"script"=>$script); 
                    }
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
    }
?>