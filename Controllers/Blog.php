<?php
    
    require_once("Models/BlogTrait.php");
    require_once("Models/LoginModel.php");
    class Blog extends Controllers{
        use BlogTrait;
        private $login;
        public function __construct(){
            session_start();
            parent::__construct();
            $this->login = new LoginModel();
        }

        /******************************Views************************************/
        public function blog(){
            $company=getCompanyInfo();
            $data['page_tag'] = $company['name'];
            $data['page_title'] = "Blog | ".$company['name'];
            $data['page_name'] = "blog";
            $data['posts'] = $this->getArticlesT();
            $this->views->getView($this,"blog",$data);
        }
        public function articulo($params){
            if($params!=""){
                $params = strClean($params);
                $data['article'] = $this->getArticlePageT($params);
                if(!empty($data['article'])){
                    $company=getCompanyInfo();
                    $data['page_tag'] = $company['name'];
                    $data['page_name'] = "artiulo";
                    $data['page_title'] =$data['article']['name']." | ".$company['name'];
                    $data['posts'] = $this->getArticlesRandT(3);
                    $this->views->getView($this,"articulo",$data);
                }else{
                    header("location: ".base_url()."/error");
                }
            }else{
                header("location: ".base_url()."/error");
            }
        }
        /******************************Methods************************************/
        public function setComment(){
            //dep($_POST);exit;
            if($_POST){
                $request="";
                $arrResponse="";
                $idComment = intval($_POST['idComment']);
                if($idComment>0){
                    if(empty($_POST['txtDescription']) || empty($_POST['idComment'])){
                        $arrResponse = array("status"=>false,"msg"=>"Por favor escribe tu comentario.");
                        echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                    }else{
                        $idArticle = intval($_POST['idArticle']);
                        $strDescription = strClean($_POST['txtDescription']);
                        $request = $this->updateCommentT($idComment,$strDescription); 
                        $comments = $this->getComments($idArticle);
                        $arrResponse = array("status"=>true,"msg"=>"Tu comentario se ha compartido.","html"=>$comments['html'],"total"=>$comments['total']);
                        echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                    }
                    
                }else{
                    if(!isset($_SESSION['login'])){
                        $arrResponse = array("login"=>false,"msg"=>"Por favor inicia sesión para comentar");
                    }else{
                        $idUser = $_SESSION['idUser'];
                        $idArticle = intval($_POST['idArticle']);
                        $strDescription = strClean($_POST['txtDescription']);
                        $request = $this->setCommentT($idArticle,$idUser,$strDescription);
                        if($request>0){
                            $comments = $this->getComments($idArticle);
                            $arrResponse = array("status"=>true,"msg"=>"Tu comentario se ha compartido.","html"=>$comments['html'],"total"=>$comments['total']);
                        }else{
                            $arrResponse = array("status"=>false,"msg"=>"Error, intenta de nuevo.");
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }
        public function getComments($idArticle){
            $idArticle = intval($idArticle);

            $comments = $this->getCommentsT($idArticle);
            $total = $this->getTotalCommentsT($idArticle);
            $html="";
            for ($i=0; $i < count($comments); $i++) { 
                $image = media()."/images/uploads/".$comments[$i]['image'];
                $name = $comments[$i]['firstname']." ".$comments[$i]['lastname'];
                $showReplies='<button class="btn text-white c-d"></button>';
                $htmlReplies="";
                $options="";
                $divId = "'".'comment'.$comments[$i]['idcomment']."'";
                if(isset($_SESSION['login'])){
                    if($_SESSION['idUser'] == $comments[$i]['personid']){
                        $options .= '
                            <button type="button" class="btn t-p p-0 me-2" onclick="editComment('.$comments[$i]['idcomment'].',comment'.$comments[$i]['idcomment'].')" title="edit">Editar</button>
                        ';
                    }
                    if($_SESSION['idUser'] == $comments[$i]['personid'] || $_SESSION['userData']['roleid'] == 1){
                        $options .= '
                            <button type="button" class="btn t-p p-0 me-2" onclick="deleteComment('.$comments[$i]['idcomment'].')" title="delete">Eliminar</button>
                        ';
                    }
                    $options.='<button type="button" class="btn t-p p-0" onclick="replyComment('.$comments[$i]['idcomment'].',comment'.$comments[$i]['idcomment'].')">Responder</button>';
                }
                if(count($comments[$i]['replies'])>0){
                    $replies =$comments[$i]['replies'];
                    $showReplies ='<button type="button" class="btn t-p p-0" onclick="showReplies(this,replies'.$comments[$i]['idcomment'].')">Mostrar menos</button>';

                    for ($j=0; $j < count($replies); $j++) { 
                        $nameReply = $replies[$j]['firstname']." ".$replies[$j]['lastname'];
                        $optionsReply="";
                        if(isset($_SESSION['login'])){
                            if($_SESSION['idUser'] == $replies[$j]['personid']){
                                $optionsReply.='<button type="button" class="btn t-p p-0 me-2" onclick="editReply('.$replies[$j]['idreply'].',reply'.$replies[$j]['idreply'].')" title="edit">Editar</button>';
                            }
                            if($_SESSION['idUser'] == $replies[$j]['personid'] || $_SESSION['userData']['roleid'] == 1){
                                $optionsReply.='<button type="button" class="btn t-p p-0" onclick="deleteReply('.$replies[$j]['idreply'].')" title="delete">Eliminar</button>';
                            }
                        }
                        $htmlReplies .= '
                            <div class="row position-relative mb-3">
                                <div class="col-md-12 af-s-line">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <p class="m-0">'.$nameReply.'</p>
                                            <p class="m-0 text-secondary">'.$replies[$j]['date'].'</p> 
                                        </div>
                                        <div>
                                            <p class="m-0 text-secondary">Última actualización</p>
                                            <p class="m-0 text-secondary">'.$replies[$j]['dateupdated'].'</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <p class="m-0">'.$replies[$j]['description'].'</p>
                                    <div id="reply'.$replies[$j]['idreply'].'"></div>
                                    <div class="text-end">
                                        '.$optionsReply.'
                                    </div>
                                </div>
                            </div>
                        ';
                    }
                }
                $html.='
                <li class="comment-block">
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="comment-info d-flex justify-content-between">
                                <div class="d-flex justify-content-start">
                                    <div class="comment-img me-1">
                                        <img src="'.$image.'" alt="'.$name.'">
                                    </div>
                                    <div>
                                        <p class="m-0">'.$name.'</p>
                                        <p class="m-0 text-secondary">'.$comments[$i]['date'].'</p>
                                    </div>
                                </div>
                                <div>
                                    <p class="m-0 text-secondary">Última actualización</p>
                                    <p class="m-0 text-secondary">'.$comments[$i]['dateupdated'].'</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-1">
                            <p class="m-0">'.$comments[$i]['description'].'</p>
                            <div id="comment'.$comments[$i]['idcomment'].'"></div>
                            <div class="d-flex justify-content-between">
                                '.$showReplies.'
                                <div>
                                    '.$options.'
                                </div>
                            </div>
                            <div class="ps-5 mt-3" id="replies'.$comments[$i]['idcomment'].'">'.$htmlReplies.'</div>                              
                        </div>
                    </div>
                </li>
                ';
            }
            $arrResponse = array("html"=>$html,"total"=>$total);
            return $arrResponse;
        }
        public function getComment($id){
            $id = intval($id);
            $request = $this->getCommentT($id);
            if(!empty($request)){
                $arrResponse = array("status"=>true,"data"=>$request);
            }else{
                $arrResponse = array("status"=>false);
            }
            echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            die();
        }
        public function getReply($id){
            $id = intval($id);
            $request = $this->getReplyT($id);
            if(!empty($request)){
                $arrResponse = array("status"=>true,"data"=>$request);
            }else{
                $arrResponse = array("status"=>false);
            }
            echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            die();
        }
        public function delComment(){
            if($_POST){
                $id = intval($_POST['idComment']);
                $idArticle = intval($_POST['idArticle']);
                $request = $this->deleteCommentT($id); 
                $comments = $this->getComments($idArticle);
                $arrResponse = array("status"=>true,"msg"=>"El comentario se ha eliminado.","html"=>$comments['html'],"total"=>$comments['total']);
            }
            echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            die();
        }
        public function delReply(){
            if($_POST){
                $id = intval($_POST['idReply']);
                $idArticle = intval($_POST['idArticle']);
                $request = $this->deleteReplyT($id); 
                $comments = $this->getComments($idArticle);
                $arrResponse = array("status"=>true,"msg"=>"El comentario se ha eliminado.","html"=>$comments['html'],"total"=>$comments['total']);
            }
            echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            die();
        }
        public function setReply(){
            if($_POST){
                $idArticle = intval($_POST['idArticle']);
                if(!empty($_POST['idReply'])){
                    $idReply = intval($_POST['idReply']);
                    $strDescription = strClean($_POST['txtDescription']);
                    $this->updateReply($idReply,$strDescription);
                    $comments = $this->getComments($idArticle);
                    $arrResponse = array("status"=>true,"msg"=>"Tu comentario se ha compartido.","html"=>$comments['html'],"total"=>$comments['total']);
                }else{
                    $request="";
                    $arrResponse="";
                    $idComment = intval($_POST['idComment']);
                    $idUser = $_SESSION['idUser'];
                    $strDescription = strClean($_POST['txtDescription']);
    
                    $request = $this->setReplyT($idComment,$idUser,$strDescription);
                    if($request>0){
                        $comments = $this->getComments($idArticle);
                        $arrResponse = array("status"=>true,"msg"=>"Tu comentario se ha compartido.","html"=>$comments['html'],"total"=>$comments['total']);
                    }else{
                        $arrResponse = array("status"=>false,"msg"=>"Error, intenta de nuevo.");
                    }
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
    }
?>