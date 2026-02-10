<?php
    class HelperPagination{
        public static function getConceptosContables($intPage,$intPerPage,$filterType,$strSearch){
            $con = new Mysql();
            $limit ="";
            $intStartPage = ($intPage-1)*$intPerPage;
            if($intPerPage != 0){
                $limit = " LIMIT $intStartPage,$intPerPage";
            }
            $sql = "SELECT co.*, ty.name as type
            FROM accounting_concepts co
            INNER JOIN accounting_concept_types ty ON co.type = ty.id
            WHERE co.name like '$strSearch%' AND co.type like '$filterType%' AND co.status = 1
            ORDER BY co.id DESC $limit";  
            $request = $con->select_all($sql);

            $sqlTotal = "SELECT count(*) as total FROM accounting_concepts 
            WHERE name like '$strSearch%' AND type like '$filterType%' AND status = 1";

            $totalRecords = $con->select($sqlTotal)['total'];
            $arrData = getCalcPages($totalRecords,$intPage,$intPerPage);
            $arrData['data'] = $request;
            $arrData['tipos'] = HelperAccounting::getConceptTypes();
            return $arrData;
        }

        public static function getRetenciones($intPage,$intPerPage,$strSearch,$strFilterClass=""){
            $con = new Mysql();
            $limit ="";
            $intStartPage = ($intPage-1)*$intPerPage;
            if($intPerPage != 0){
                $limit = " LIMIT $intStartPage,$intPerPage";
            }
   
            $sql = "SELECT * FROM withholding WHERE name like '$strSearch%' AND kind LIKE '$strFilterClass%' ORDER BY id DESC $limit";  
            $request = $con->select_all($sql);

            $sqlTotal = "SELECT count(*) as total FROM withholding WHERE name like '$strSearch%' AND kind LIKE '$strFilterClass%' ORDER BY id";

            $totalRecords = $con->select($sqlTotal)['total'];
            $arrData = getCalcPages($totalRecords,$intPage,$intPerPage);
            $arrData['data'] = $request;
            return $arrData;
        }
    }
?>