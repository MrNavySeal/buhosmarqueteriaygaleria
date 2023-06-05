<?php
    require_once("Libraries/Core/Mysql.php");
    trait BlogTrait{
        private $con;
        private $intIdArticle;
        private $intIdUser;
        private $strDescription;
        private $intIdComment;
        private $intIdReply;

        public function getArticlesT($cant = null){
            if($cant !=null){
                $cant = " LIMIT $cant";
            }
            $this->con=new Mysql();
            $sql = "SELECT *,
            DATE_FORMAT(date_created, '%d/%m/%Y') as date,
            DATE_FORMAT(date_updated, '%d/%m/%Y') as dateupdated
            FROM article WHERE status = 1 ORDER BY idarticle DESC $cant
            ";
            $request = $this->con->select_all($sql);
            return $request;
        }
        public function getArticlePageT(string $route){
            $this->con=new Mysql();
            $sql = "SELECT *,
            DATE_FORMAT(date_created, '%d/%m/%Y') as date,
            DATE_FORMAT(date_updated, '%d/%m/%Y') as dateupdated
            FROM article 
            WHERE status=1 AND route = '$route'";
            $request = $this->con->select($sql);
            return $request;
        }
        public function getArticlesRandT($cant = null){
            if($cant !=null){
                $cant = " LIMIT $cant";
            }
            $this->con=new Mysql();
            $sql = "SELECT *,
            DATE_FORMAT(date_created, '%d/%m/%Y') as date,
            DATE_FORMAT(date_updated, '%d/%m/%Y') as dateupdated
            FROM article WHERE status = 1 ORDER BY RAND() DESC $cant
            ";
            $request = $this->con->select_all($sql);
            return $request;
        }
        /*
        public function getRecentPostsT($cant){
            if($cant !=""){
                $cant = " LIMIT $cant";
            }
            $this->con=new Mysql();
            $sql = "SELECT 
            a.idarticle,
            a.categoryid,
            a.subcategoryid,
            a.name,
            a.picture,
            a.description,
            a.status,
            a.route,
            c.idcategory,
            c.name as category,
            s.idsubcategory,
            s.categoryid,
            s.name as subcategory,
            DATE_FORMAT(a.date_created, '%d/%m/%Y') as date,
            DATE_FORMAT(a.date_updated, '%d/%m/%Y') as dateupdated
            FROM article a
            INNER JOIN blogcategory c, blogsubcategory s
            WHERE c.idcategory = a.categoryid AND c.idcategory = s.categoryid AND a.subcategoryid = s.idsubcategory AND a.status =1
            ORDER BY a.idarticle DESC $cant
            ";
            //dep($sql);exit;
            $request = $this->con->select_all($sql);
            return $request;
        }
        public function getRelatedPostsT($cant,$idCategory){
            if($cant !=""){
                $cant = " LIMIT $cant";
            }
            $this->con=new Mysql();
            $sql = "SELECT 
            a.idarticle,
            a.categoryid,
            a.subcategoryid,
            a.name,
            a.picture,
            a.description,
            a.status,
            a.route,
            c.idcategory,
            c.name as category,
            s.idsubcategory,
            s.categoryid,
            s.name as subcategory,
            DATE_FORMAT(a.date_created, '%d/%m/%Y') as date,
            DATE_FORMAT(a.date_updated, '%d/%m/%Y') as dateupdated
            FROM article a
            INNER JOIN blogcategory c, blogsubcategory s
            WHERE c.idcategory = a.categoryid AND c.idcategory = s.categoryid AND a.subcategoryid = s.idsubcategory AND a.status =1 AND a.categoryid =$idCategory
            ORDER BY a.idarticle DESC $cant
            ";
            //dep($sql);exit;
            $request = $this->con->select_all($sql);
            return $request;
        }
        
        public function getTotalCommentsT($id){
            $this->con = new Mysql();
            $this->intIdArticle = $id;
            $sql="SELECT COUNT(*) AS total FROM blogcomments WHERE articleid= $this->intIdArticle";
            $request = $this->con->select($sql);
            return $request['total'];
        }
        
        public function getCommentsT($id){
            $this->con = new Mysql();
            $this->intIdArticle = $id;
            $sql = "SELECT 
                    c.idcomment,
                    c.personid,
                    c.articleid,
                    c.description,
                    p.idperson,
                    p.image,
                    p.firstname,
                    p.lastname,
                    DATE_FORMAT(c.date_created, '%d/%m/%Y') as date,
                    DATE_FORMAT(c.date_updated, '%d/%m/%Y') as dateupdated
                    FROM blogcomments c
                    INNER JOIN person p, article a
                    WHERE p.idperson = c.personid AND a.idarticle = c.articleid AND a.idarticle = $this->intIdArticle ORDER BY c.idcomment DESC";
            $request = $this->con->select_all($sql);
            if(count($request)>0){
                for ($i=0; $i < count($request) ; $i++) { 
                    $idComment = $request[$i]['idcomment'];
                    $sqlReply = "SELECT 
                                r.idreply,
                                r.commentid,
                                r.personid,
                                r.description,
                                DATE_FORMAT(r.date_created, '%d/%m/%Y') as date,
                                DATE_FORMAT(r.date_updated, '%d/%m/%Y') as dateupdated,
                                p.firstname,
                                p.lastname
                                FROM blogreplies r
                                INNER JOIN blogcomments c, person p
                                WHERE r.commentid = c.idcomment AND r.personid = p.idperson AND r.commentid = $idComment";
                    $requestReply = $this->con->select_all($sqlReply);
                    $request[$i]['replies'] = $requestReply;
                }
            }
            return $request;
        }
        public function setCommentT($idArticle,$idUser,$strDescription){
            $this->intIdArticle = $idArticle;
            $this->intIdUser = $idUser;
            $this->strDescription = $strDescription;
            $this->con = new Mysql();

            $sql="INSERT INTO blogcomments(personid,articleid,description) VALUES(?,?,?)";
            $arrData = array($this->intIdUser,$this->intIdArticle,$this->strDescription);
            $request = $this->con->insert($sql,$arrData);
            return $request;
        }
        public function getRepliesT($idComment){
            $this->intIdComment = $idComment;
            $sql = "SELECT 
                        r.idreply,
                        r.commentid,
                        r.personid,
                        r.description,
                        DATE_FORMAT(r.date_created, '%d/%m/%Y') as date,
                        DATE_FORMAT(r.date_updated, '%d/%m/%Y') as dateupdated,
                        p.firstname,
                        p.lastname
                        FROM blogreplies r
                        INNER JOIN blogcomments c, person p
                        WHERE r.commentid = c.idcomment AND r.personid = p.idperson AND r.commentid = $this->intIdComment";
            $request = $this->con->select_all($sql);
            return $request;
        }
        public function setReplyT($idComment,$idUser,$strDescription){
            $this->intIdComment = $idComment;
            $this->intIdUser = $idUser;
            $this->strDescription = $strDescription;
            $this->con = new Mysql();

            $sql="INSERT INTO blogreplies(commentid,personid,description) VALUES(?,?,?)";
            $arrData = array($this->intIdComment,$this->intIdUser,$this->strDescription);
            $request = $this->con->insert($sql,$arrData);
            return $request;
        }
        public function updateCommentT(int $idComment, string $strDescription){
            $this->intIdComment = $idComment;
            $this->strDescription = $strDescription;
            $this->con = new Mysql();

            $sql = "UPDATE blogcomments SET description=?, date_updated=NOW() WHERE idcomment = $this->intIdComment";
            $arrData=array($this->strDescription);
            $request=$this->con->update($sql,$arrData);
            return $request;
        }
        public function getCommentT($id){
            $this->intIdComment = $id;
            $this->con = new Mysql();
            $sql="SELECT idcomment,description FROM blogcomments WHERE idcomment = $this->intIdComment";
            $request = $this->con->select($sql);
            return $request;
        }
        public function getReplyT($id){
            $this->intIdReply = $id;
            $this->con = new Mysql();
            $sql="SELECT idreply,description FROM blogreplies WHERE idreply = $this->intIdReply";
            $request = $this->con->select($sql);
            return $request;
        }
        public function deleteCommentT($id){
            $this->intIdComment = $id;
            $this->con = new Mysql();
            $sql ="DELETE FROM blogcomments WHERE idcomment = $this->intIdComment";
            $request = $this->con->delete($sql);
            return $request;
        }
        public function deleteReplyT($id){
            $this->intIdReply = $id;
            $this->con = new Mysql();
            $sql ="DELETE FROM blogreplies WHERE idreply = $this->intIdReply";
            $request = $this->con->delete($sql);
            return $request;
        }
        public function updateReply(int $id,String $description){
            $this->con = new Mysql();
            $this->intIdReply = $id;
            $this->strDescription = $description;
            $sql = "UPDATE blogreplies SET description=?, date_updated=NOW() WHERE idreply = $this->intIdReply";
            $arrData = array($this->strDescription);
            $request = $this->con->update($sql,$arrData);
            return $request;
        }*/

    }
    
?>