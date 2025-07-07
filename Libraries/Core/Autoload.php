<?php 
	spl_autoload_register(function($class){
		if(file_exists("Libraries/".'Core/'.$class.".php")){
			require_once("Libraries/".'Core/'.$class.".php");
		}
		if(file_exists("Interfaces/".$class.".php")){
			require_once("Interfaces/".$class.".php");
		}
		if(file_exists("Providers/".$class.".php")){
			require_once("Providers/".$class.".php");
		}
		if(file_exists("Services/".$class.".php")){
			require_once("Services/".$class.".php");
		}
		if(file_exists("Libraries/TCPDF/tcpdf.php")){
			require_once "Libraries/TCPDF/tcpdf.php";
		}
		if(file_exists("Libraries/vendor/autoload.php")){
			require_once "Libraries/vendor/autoload.php";
		}
	});
 ?>