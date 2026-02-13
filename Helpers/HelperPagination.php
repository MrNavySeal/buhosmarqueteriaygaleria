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

        public static function getTerceros($intPage,$intPerPage,$strSearch,$types=[]){
            $isClient = isset($types['is_client']) ? " AND p.is_client = $types[is_client]" : "";
            $isUser =isset($types['is_user']) ? " AND p.is_user = $types[is_user]" : "";
            $isSupplier=isset($types['is_supplier']) ? " AND p.is_supplier = $types[is_supplier]" : "";
            $isOther=isset($types['is_other']) ? " AND p.is_other = $types[is_other]" : "";

            $con = new Mysql();

            $limit ="";
            $intStartPage = ($intPage-1)*$intPerPage;
            if($intPerPage != 0){
                $limit = " LIMIT $intStartPage,$intPerPage";
            }

            $sql = "SELECT p.idperson as id,
            DATE_FORMAT(p.date, '%d/%m/%Y') as fecha,
            p.status,
            p.image,
            p.identification as documento,
            co.name as pais,
            st.name as departamento,
            ci.name as ciudad,
            p.phone as telefono,
            CONCAT(p.firstname,' ',p.lastname) as nombre,
            p.address as direccion
            FROM person p
            LEFT JOIN countries co ON p.countryid = co.id
            LEFT JOIN states st ON p.stateid = st.id
            LEFT JOIN cities ci ON p.cityid = ci.id
            WHERE p.idperson != 1 AND p.status = 1 AND (CONCAT(p.firstname,p.lastname) like '$strSearch%' OR p.phone like '$strSearch%' 
            OR p.address like '$strSearch%' OR co.name like '$strSearch%' OR st.name like '$strSearch%' 
            OR ci.name like '$strSearch%') $isClient $isSupplier $isUser $isOther 
            ORDER BY p.idperson DESC $limit";  
            

            $sqlTotal = "SELECT count(*) as total FROM person p
            LEFT JOIN countries co ON p.countryid = co.id
            LEFT JOIN states st ON p.stateid = st.id
            LEFT JOIN cities ci ON p.cityid = ci.id
            WHERE p.idperson != 1 AND p.status = 1 AND (CONCAT(p.firstname,p.lastname) like '$strSearch%' OR p.phone like '$strSearch%' 
            OR p.address like '$strSearch%' OR co.name like '$strSearch%' OR st.name like '$strSearch%' 
            OR ci.name like '$strSearch%') $isClient $isSupplier $isUser $isOther 
            ORDER BY p.idperson";

            $request = $con->select_all($sql);
            $totalRecords = $con->select($sqlTotal)['total'];

            foreach ($request as &$data) { 
                $data['url'] = media()."/images/uploads/".$data['image'];
            }

            $request = $con->select_all($sql);
            $totalRecords = $con->select($sqlTotal)['total'];
            $arrData = getCalcPages($totalRecords,$intPage,$intPerPage);
            $arrData['data'] = $request;
            return $arrData;
        }
        
        public static function getCuentasBancarias($intPage,$intPerPage,$strSearch){
            $con = new Mysql();
            $limit ="";
            $intStartPage = ($intPage-1)*$intPerPage;
            if($intPerPage != 0){
                $limit = " LIMIT $intStartPage,$intPerPage";
            }    
            $sql = "SELECT cab.*, 
            ac.name as account,
            ac.code,
            CONCAT(p.firstname,' ',p.lastname) as nombre,
            CONCAT(p.firstname,' ',p.lastname,'-',cab.bank_account) as name 
            FROM banks cab
            LEFT JOIN person p ON p.idperson = cab.person_id
            LEFT JOIN accounting_accounts ac ON ac.id = cab.account_id
            WHERE (cab.bank_account like '$strSearch%' 
            OR p.firstname like '$strSearch%' 
            OR p.lastname like '$strSearch%') AND cab.status = 1
            ORDER BY cab.id DESC $limit";  

            $sqlTotal = "SELECT count(*) as total 
            FROM banks cab
            LEFT JOIN person p ON p.idperson = cab.person_id
            LEFT JOIN accounting_accounts ac ON ac.id = cab.account_id
            WHERE (cab.bank_account like '$strSearch%' 
            OR p.firstname like '$strSearch%' 
            OR p.lastname like '$strSearch%') AND cab.status = 1";

            $request = $con->select_all($sql);
            $totalRecords = $con->select($sqlTotal)['total'];

            $arrData = getCalcPages($totalRecords,$intPage,$intPerPage);
            $arrData['data'] = $request;
            return $arrData;
        }
    }
?>