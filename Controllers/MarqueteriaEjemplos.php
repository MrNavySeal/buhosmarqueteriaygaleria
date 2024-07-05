<?php
    class MarqueteriaEjemplos extends Controllers{
        public function __construct(){
            session_start();
            if(empty($_SESSION['login'])){
                header("location: ".base_url());
                die();
            }
            parent::__construct();
            sessionCookie();
            getPermits(12);
            
        }
        public function ejemplos(){
            if($_SESSION['permitsModule']['r']){
                $data['page_tag'] = "Ejemplos";
                $data['page_title'] = "Ejemplos | Marquetería";
                $data['page_name'] = "ejemplos";
                $data['panelapp'] = "functions_molding_example.js";
                $this->views->getView($this,"ejemplos",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function getExamples(){
            if($_SESSION['permitsModule']['r']){
                $request = $this->model->selectExamples();
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 
                        $btnView = '<button class="btn btn-info m-1 text-white" type="button" title="Ver" onclick="viewItem('.$request[$i]['id'].')"><i class="fas fa-eye"></i></button>';
                        $btnDelete="";
                        $btnEdit="";
                        $status="";
                        if($_SESSION['permitsModule']['u']){
                            $btnEdit = '<button class="btn btn-success m-1" type="button" title="Editar" onclick="editItem('.$request[$i]['id'].')"><i class="fas fa-pencil-alt"></i></button>';
                        }
                        if($_SESSION['permitsModule']['d']){
                            $btnDelete = '<button class="btn btn-danger m-1" type="button" title="Eliminar" onclick="deleteItem('.$request[$i]['id'].')"><i class="fas fa-trash-alt"></i></button>';
                        }
                        if($request[$i]['status']==1){
                            $status='<span class="badge me-1 bg-success">Activo</span>';
                        }else{
                            $status='<span class="badge me-1 bg-danger">Inactivo</span>';
                        }
                        $request[$i]['status'] = $status;
                        $request[$i]['options'] = $btnView.$btnEdit.$btnDelete;
                        $request[$i]['total'] = formatNum($request[$i]['total']);
                    }
                }
                echo json_encode($request,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
    }

?>