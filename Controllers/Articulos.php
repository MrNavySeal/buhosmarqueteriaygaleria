<?php
    class Articulos extends Controllers{

        public function __construct(){
            session_start();
            if(empty($_SESSION['login'])){
                header("location: ".base_url());
                die();
            }
            parent::__construct();
            getPermits(10);
        }
        
        public function articulos(){
            if($_SESSION['permitsModule']['r']){
                $data['page_tag'] = "Articulos";
                $data['page_title'] = "Articulos";
                $data['page_name'] = "articulos";
                $data['data'] = $this->getArticles();
                $data['app'] = "functions_blog.js";
                $this->views->getView($this,"articulos",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function articulo($params){
            if($_SESSION['permitsModule']['w']){
                $data['page_tag'] = "Articulo";
                $data['page_title'] = "Articulo";
                $data['page_name'] = "articulo";
                $data['app'] = "functions_article.js";
                if($params==""){
                    $this->views->getView($this,"creararticulo",$data);
                }else{
                    $id = intval(strClean($params));
                    $data['article'] = $this->getArticle($id);
                    $this->views->getView($this,"editararticulo",$data);
                }
            }else{
                header("location: ".base_url());
                die();
            }
        }
        /*************************Article methods*******************************/
        public function getArticles($option=null,$params=null){
            if($_SESSION['permitsModule']['r']){
                $html="";
                $request="";
                if($option == 1){
                    $request = $this->model->search($params);
                }else if($option == 2){
                    $request = $this->model->sort($params);
                }else{
                    $request = $this->model->selectArticles();
                }
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 

                        $status="";
                        $btnGlobe = '<a href="'.base_url().'/blog/articulo/'.$request[$i]['route'].'" target="_blank" class="btn btn-primary m-1 text-white" title="Watch on website"><i class="fas fa-globe"></i></a>';
                        $btnEdit="";
                        $btnDelete="";
                        if($_SESSION['permitsModule']['u']){
                            $btnEdit = '<a href="'.base_url().'/articulos/articulo/'.$request[$i]['idarticle'].'" class="btn btn-success m-1 text-white" title="Editar" name="btnEdit"><i class="fas fa-pencil-alt"></i></a>';
                        }
                        if($_SESSION['permitsModule']['d']){
                            $btnDelete = '<button class="btn btn-danger m-1 text-white" type="button" title="Delete" data-id="'.$request[$i]['idarticle'].'" name="btnDelete"><i class="fas fa-trash-alt"></i></button>';
                        }
                        if($request[$i]['status']==1){
                            $status='<span class="badge me-1 bg-success">Activo</span>';
                        }else{
                            $status='<span class="badge me-1 bg-danger">Inactivo</span>';
                        }
                        $html.='
                            <tr class="item">
                                <td class="text-center">'.$request[$i]['name'].'</td>
                                <td data-label="Fecha de creación">'.$request[$i]['date'].'</td>
                                <td data-label="Fecha de actualización">'.$request[$i]['dateupdated'].'</td>
                                <td data-label="Estado: ">'.$status.'</td>
                                <td class="item-btn">'.$btnGlobe.$btnEdit.$btnDelete.'</td>
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
        public function getArticle($id){
            if($_SESSION['permitsModule']['r']){
                $request = $this->model->selectArticle($id);
                if($request['picture']!=""){
                    $request['picture'] = media()."/images/uploads/".$request['picture'];
                }
                return $request;
            }else{
                header("location: ".base_url());
            }
            die();
        }
        public function setArticle(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    if(empty($_POST['txtName']) || empty($_POST['txtShortDescription']) || empty($_POST['txtDescription'] ) || empty($_POST['statusList'])){
                        $arrResponse = array("status" => false, "msg" => 'Error de datos');
                    }else{ 
                        $idArticle = intval($_POST['idArticle']);
                        $strName = strClean($_POST['txtName']);
                        $strDescription = strClean($_POST['txtDescription']);
                        $strShortDescription = strClean($_POST['txtShortDescription']);
                        $intStatus = intval($_POST['statusList']);
                        $request_article = "";
                        $photo = "";
                        $photoPost="";

                        $route = clear_cadena($strName);
                        $route = strtolower(str_replace("¿","",$route));
                        $route = str_replace(" ","-",$route);
                        $route = str_replace("?","",$route);
                        
                        if($idArticle == 0){
                            if($_SESSION['permitsModule']['w']){

                                $option = 1;
                                if($_FILES['txtImg']['name'] != ""){
                                    $photo = $_FILES['txtImg'];
                                    $photoPost = 'article_'.bin2hex(random_bytes(6)).'.png';
                                }
                                $request_article = $this->model->insertArticle($photoPost,$strName,$strShortDescription,$strDescription,$intStatus,$route);
                            }
                        }else{
                            if($_SESSION['permitsModule']['u']){

                                $option = 2;
                                $request = $this->model->selectArticle($idArticle);

                                if($_FILES['txtImg']['name'] == ""){
                                    $photoPost = $request['picture'];
                                }else{
                                    if($request['picture'] != ""){
                                        deleteFile($request['picture']);
                                    }
                                    $photo = $_FILES['txtImg'];
                                    $photoPost = 'article_'.bin2hex(random_bytes(6)).'.png';
                                }
                                
                                $request_article = $this->model->updateArticle($idArticle,$photoPost,$strName,$strShortDescription,$strDescription,$intStatus,$route);
                            }
                        }
    
                        if($request_article > 0 ){
                            if($photo!=""){
                                uploadImage($photo,$photoPost);
                            }
                            if($option == 1){
                                $arrResponse = $this->getArticles();
                                $arrResponse['msg'] = 'Datos guardados.';
                            }else{
                                $arrResponse = $this->getArticles();
                                $arrResponse['msg'] = 'Datos actualizados.';
                            }
                        }else if($request_article == 'exist'){
                            $arrResponse = array('status' => false, 'msg' => '¡Atención! el título ya está registrado, pruebe con otro.');		
                        }else{
                            $arrResponse = array("status" => false, "msg" => 'No es posible guardar los datos.');
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
        public function delArticle(){
            if($_SESSION['permitsModule']['d']){
                if($_POST){
                    if(empty($_POST['idArticle'])){
                        $arrResponse=array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $id = intval($_POST['idArticle']);
                        $request = $this->model->selectArticle($id);
                        if($request['picture']!=""){
                            deleteFile($request['picture']);
                        }
                        $request = $this->model->deleteArticle($id);
                        if($request=="ok"){
                            $arrResponse = array("status"=>true,"msg"=>"Se ha eliminado.","data"=>$this->getArticles()['data']);
                        }else{
                            $arrResponse = array("status"=>false,"msg"=>"No se ha podido eliminar, intenta de nuevo.");
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }else{
                header("location: ".base_url());
            }
            die();
        }
    }
?>