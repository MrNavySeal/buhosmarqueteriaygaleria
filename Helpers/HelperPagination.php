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

        public static function getSucursales($intPage,$intPerPage,$strSearch,$strFilterClass=""){
            $con = new Mysql();
            $limit ="";
            $intStartPage = ($intPage-1)*$intPerPage;
            if($intPerPage != 0){
                $limit = " LIMIT $intStartPage,$intPerPage";
            }
   
            $sql = "SELECT cab.*,
            co.name as country, 
            st.name as state, 
            ci.name as city 
            FROM sale_branch cab
            INNER JOIN countries co ON cab.country_id = co.id
            INNER JOIN states st ON cab.state_id = st.id
            INNER JOIN cities ci ON cab.city_id = ci.id
            WHERE cab.name like '$strSearch%' OR co.name like '$strSearch%' 
            OR st.name like '$strSearch%' OR ci.name like '$strSearch%' 
            ORDER BY cab.id DESC $limit";  

            $sqlTotal = "SELECT count(*) as total FROM sale_branch cab
            INNER JOIN countries co ON cab.country_id = co.id
            INNER JOIN states st ON cab.state_id = st.id
            INNER JOIN cities ci ON cab.city_id = ci.id
            WHERE cab.name like '$strSearch%' OR co.name like '$strSearch%' 
            OR st.name like '$strSearch%' OR ci.name like '$strSearch%' 
            ORDER BY cab.id";

            $request = $con->select_all($sql);
            $totalRecords = $con->select($sqlTotal)['total'];
            $arrData = getCalcPages($totalRecords,$intPage,$intPerPage);
            $arrData['data'] = $request;
            return $arrData;
        }
    }
?>