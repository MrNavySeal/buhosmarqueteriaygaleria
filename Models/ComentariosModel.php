<?php 
    class ComentariosModel extends Mysql{
        private $intIdUser;
        private $intIdReview;
        private $intIdProduct;
		private $intStatus;

        public function __construct(){
            parent::__construct();
        }
        public function updateReview(int $id,int $status){
            $this->intIdReview = $id;
            $this->intStatus = $status;
            $sql = "UPDATE productrate SET status=? WHERE id = $this->intIdReview";     
            $arrData = array($this->intStatus);    
            $request = $this->update($sql,$arrData);
			return $request;
		
		}
        public function selectReviews(){
            $sql = "SELECT 
                    r.id,
                    r.personid,
                    r.description,
                    r.rate,
                    p.idperson,
                    p.firstname,
                    p.lastname,
                    pr.name,
                    r.status,
                    DATE_FORMAT(r.date, '%d/%m/%Y') as date,
                    DATE_FORMAT(r.date_updated, '%d/%m/%Y') as dateupdated
                    FROM productrate r
                    INNER JOIN person p, product pr
                    WHERE p.idperson = r.personid AND pr.idproduct = r.productid";
            $request = $this->select_all($sql);
            //dep($request);exit;
            return $request;
        }
        public function selectReview($id){
            $this->intIdReview = $id;
            $sql = "SELECT 
                r.id,
                r.personid,
                r.description,
                r.rate,
                p.idperson,
                p.firstname,
                p.lastname,
                pr.name,
                r.status,
                DATE_FORMAT(r.date, '%d/%m/%Y') as date,
                DATE_FORMAT(r.date_updated, '%d/%m/%Y') as dateupdated
                FROM productrate r
                INNER JOIN person p, product pr
                WHERE p.idperson = r.personid AND pr.idproduct = r.productid AND r.id = $id";
            $request = $this->select($sql);
            return $request;
        }
        public function sort($sort){
            $option=" ORDER BY r.id DESC";
            if($sort == 2){
                $option = " AND r.status =1 ORDER BY status DESC"; 
            }else if($sort == 3){
                $option = " AND r.status =2 ORDER BY status DESC"; 
            }else if($sort == 4){
                $option = " AND r.status =3 ORDER BY status DESC"; 
            }
            $sql = "SELECT 
                r.id,
                r.personid,
                r.description,
                r.rate,
                p.idperson,
                p.firstname,
                p.lastname,
                pr.name,
                r.status,
                DATE_FORMAT(r.date, '%d/%m/%Y') as date,
                DATE_FORMAT(r.date_updated, '%d/%m/%Y') as dateupdated
                FROM productrate r
                INNER JOIN person p, product pr
                WHERE p.idperson = r.personid AND pr.idproduct = r.productid";
            $request = $this->select_all($sql);
            return $request;
        }
    }
?>