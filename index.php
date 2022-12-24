<?php 
    require_once ("Config/Config.php");
    require_once ("Helpers/Helpers.php");
    $url = !empty($_GET['url']) ? $_GET['url'] : 'home/home';//Si la url está vacía, me devuelva al inicio
    $arrUrl = explode("/",$url); // Dividie o explota la url
    $controller = $arrUrl[0];
    $method = $arrUrl[0];
    $params = "";

    if(!empty($arrUrl[1])){ //Comprobando si no está vacío el método
        if($arrUrl[1] != ""){
            $method = $arrUrl[1];
        }
    }
    if(!empty($arrUrl[2])){//Comprobando si no está vacío los parámetros
        if($arrUrl[2] != ""){
            for ($i=2; $i < count($arrUrl) ; $i++) { //Concatenando todos los parámetros
                $params .= $arrUrl[$i].',';
            }
            $params =trim($params,',');//Eliminar el ultimo carácter
        }
    }
    require_once("Libraries/Core/Autoload.php");
    require_once("Libraries/Core/Load.php");
?>