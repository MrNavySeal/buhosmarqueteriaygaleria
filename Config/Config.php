<?php
	/*Local host */
	
	const DB_HOST = "localhost";
	const BASE_URL = "https://buhosmarqueteriaygaleria.co";
	const DB_NAME = "u209003010_buhosmyg";
	const DB_USER = "u209003010_buhos";
	const DB_PASSWORD = "kt+Tm~6aS";
	const DB_CHARSET = "utf8";

	
	date_default_timezone_set('America/Bogota');

	const DEC = ","; // Decimales;
	const MIL = ".";//Millares;

	
	//Encriptado
	const KEY = "ecommerce";
	const METHOD = "AES-128-ECB";
	//Estados
	const STATUS = ["rechazado","confirmado","en preparacion","preparado","entregado"];
	const PAGO = ["mercadopago","nequi","daviplata","transferencia","tarjeta","efectivo"];
	/*
	const COMISION = 1.04;
	const TASA = 950;
	const PERPAGE = 12;
	const BUTTONS = 3;
	const UTILIDAD = 1.7;*/
	
	const COMISION = 1;
	const TASA = 0;
	const PERPAGE = 12;
	const BUTTONS = 3;
	const UTILIDAD = 1.7;

?>
