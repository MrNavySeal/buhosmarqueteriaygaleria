<?php 
	$controller = ucwords($controller);
	$controllerFile = "Controllers/".$controller.".php";//Ruta hacia los controladores
	if(file_exists($controllerFile))//Validar si el directorio existe
	{
		require_once($controllerFile);
		$controller = new $controller(); //Creo la instancia del controlador
		if(method_exists($controller, $method))//Valido si existe el método
		{
			$controller->{$method}($params);//Utilizo el método
		}else{
			require_once("Controllers/Error.php");
		}
	}else{
		require_once("Controllers/Error.php");
	}

 ?>