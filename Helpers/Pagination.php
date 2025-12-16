<?php
    function getPaginacionCategorias($intPage,$intPerPage,$strSearch){
        $con = new Mysql();
        $limit ="";
        $intStartPage = ($intPage-1)*$intPerPage;
        if($intPerPage != 0){
            $limit = " LIMIT $intStartPage,$intPerPage";
        }    
        $sql = "SELECT *,idcategory as id FROM category WHERE status=1 AND name like '$strSearch%' ORDER BY idcategory DESC $limit";  
        $request = $con->select_all($sql);

        $sqlTotal = "SELECT count(*) as total FROM category WHERE status=1 AND name like '$strSearch%' ORDER BY idcategory";
        foreach ($request as &$data) { 
            $data['url'] = media()."/images/uploads/".$data['picture'];
        }

        $totalRecords = $con->select($sqlTotal)['total'];
        $arrData = getCalcPages($totalRecords,$intPage,$intPerPage);
        $arrData['data'] = $request;
        return $arrData;
    }

    function getPaginacionSubcategorias($intId,$intPage,$intPerPage,$strSearch){
        $con = new Mysql();
        $limit ="";
        $intStartPage = ($intPage-1)*$intPerPage;
        if($intPerPage != 0){
            $limit = " LIMIT $intStartPage,$intPerPage";
        }    
        $sql = "SELECT *,idsubcategory as id FROM subcategory WHERE status=1 AND name like '$strSearch%' AND categoryid = $intId ORDER BY idsubcategory DESC $limit";  
        $request = $con->select_all($sql);

        $sqlTotal = "SELECT count(*) as total FROM subcategory WHERE status=1 AND name like '$strSearch%' AND categoryid = $intId ORDER BY idsubcategory";
        foreach ($request as &$data) { 
            $data['url'] = media()."/images/uploads/".$data['picture'];
        }

        $totalRecords = $con->select($sqlTotal)['total'];
        $arrData = getCalcPages($totalRecords,$intPage,$intPerPage);
        $arrData['data'] = $request;
        return $arrData;
    }
?>