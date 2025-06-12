<?php
    class Views{
		private $viewFile;
        function getView($controller,$view,$data=""){
            $controller = get_class($controller);//Obtiene la clase del controlador
			if($controller == "Home"){
				$view = "Views/".$view.".php";
			}else{
				$view = $this->viewFile."/".$view.".php";
			}
			require_once ($view);
        }
		function setViewFile($viewFile){
			$this->viewFile = $viewFile;
			
		}
    }
?>