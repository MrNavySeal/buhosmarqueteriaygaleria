<?php
    class Views{
        function getView($controller,$view,$data=""){
            $controller = get_class($controller);//Obtiene la clase del controlador
			if($controller == "Home"){
				$view = "Views/".$view.".php";
			}else{
				$view = "Views/".$controller."/".$view.".php";
			}
			require_once ($view);
        }
    }
?>