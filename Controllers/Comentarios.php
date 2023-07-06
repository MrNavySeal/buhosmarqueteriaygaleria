<?php
    class Comentarios extends Controllers{

        public function __construct(){
            session_start();
            if(empty($_SESSION['login'])){
                header("location: ".base_url());
                die();
            }
            parent::__construct();
            sessionCookie();
            getPermits(9);
        }
        public function opiniones(){
            if($_SESSION['permitsModule']['r']){
                $data['page_tag'] = "Opiniones";
                $data['page_title'] = "Opiniones";
                $data['page_name'] = "opiniones";
                $data['data'] = $this->getReviews();
                $data['panelapp'] = "functions_reviews.js";
                $this->views->getView($this,"opiniones",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function getReviews($option=null,$params=null){
            if($_SESSION['permitsModule']['r']){
                $html="";
                $request="";
                if($option == 1){
                    $request = $this->model->search($params);
                }else if($option == 2){
                    $request = $this->model->sort($params);
                }else{
                    $request = $this->model->selectReviews();
                }
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 

                        $btnCheck="";
                        $btnDelete="";
                        $btnView = '<button class="btn btn-info m-1 text-white" type="button" title="Ver" data-id="'.$request[$i]['id'].'" name="btnView"><i class="fas fa-eye"></i></button>';
                        if($_SESSION['permitsModule']['u']){
                            $btnCheck = '<button class="btn btn-success m-1 text-white" type="button" title="Aprobar" data-id="'.$request[$i]['id'].'" data-status="1" name="btnEdit"><i class="fas fa-check"></i></button>';
                        }
                        if($_SESSION['permitsModule']['d']){
                            $btnDelete = '<button class="btn btn-danger m-1 text-white" type="button" title="Rechazar" data-id="'.$request[$i]['id'].'" data-status="2" name="btnDelete"><i class="fas fa-times"></i></button>';
                        }
                        if($request[$i]['status']==1){
                            $status='<span class="badge me-1 bg-success">Aprobado</span>';
                        }else if($request[$i]['status'] == 2){
                            $status='<span class="badge me-1 bg-danger">Rechazado</span>';
                        }else{
                            $status='<span class="badge me-1 bg-warning">En espera</span>';
                        }

                        $html.='
                            <tr class="item">
                                <td class="text-center">'.$request[$i]['name'].'</td>
                                <td data-label="Usuario: ">'.$request[$i]['firstname']." ".$request[$i]['lastname'].'</td>
                                <td data-label="Calificación: ">'.$request[$i]['rate'].'</td>
                                <td data-label="Fecha: ">'.$request[$i]['date'].'</td>
                                <td data-label="Estado: ">'.$status.'</td>
                                <td class="item-btn">'.$btnView.$btnCheck.$btnDelete.'</td>
                            </tr>
                        ';
                    }
                    $arrResponse = array("status"=>true,"data"=>$html);
                }else{
                    $arrResponse = array("status"=>false,"data"=>"No hay datos");
                }
            }else{
                header("location: ".base_url());
                die();
            }
            
            return $arrResponse;
        }
        public function getReview(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    if(empty($_POST)){
                        $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $id = intval($_POST['idReview']);
                        $request = $this->model->selectReview($id);
                        if(!empty($request)){

                            $arrResponse = array("status"=>true,"data"=>$request);
                        }else{
                            $arrResponse = array("status"=>false,"msg"=>"Error, intenta de nuevo."); 
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }else{
                header("location: ".base_url());
                die();
            }
            die();
        }
        public function setReview(){
            if($_SESSION['permitsModule']['w']){
                if($_POST){
                    $id = intval($_POST['id']);
                    $status = intval($_POST['status']);
                    $request = $this->model->updateReview($id,$status);
                    if($request > 0){
                        $arrResponse = $this->getReviews();
                        $arrResponse['msg'] = 'Opinión aprobada';
                    }else{
                        $arrResponse = array("status" => false, "msg" => 'No es posible actualizar los datos.');
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }else{
                header("location: ".base_url());
            }
			die();
		}
        public function sort($params){
            if($_SESSION['permitsModule']['r']){
                $sort = intval($params);
                $arrResponse = $this->getReviews(2,$sort);
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }

    }
?>