<?php 
	if(!file_exists($controllerFile)){
		$controllerFile = "Controllers/Errors.php";
		$controller = "Errors";
	}
	if(file_exists($controllerFile)){
		require_once($controllerFile);
		$controller = new $controller(); //Creo la instancia del controlador
		$controller->load($controllerFile);
		if(method_exists($controller, $method)){
			$controller->{$method}($params);//Utilizo el método
		}
	}else{
		
	}

 ?>