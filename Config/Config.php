<?php
	/*Local host */
	
	const DB_HOST = "localhost";
	const BASE_URL = "http://localhost/buhosmarqueteriaygaleria";
	const DB_NAME = "db_buhos";
	const DB_USER = "root";
	const DB_PASSWORD = "";
	const DB_CHARSET = "utf8";

	/*000Web host */
	/*
	const DB_HOST = "localhost";
	const BASE_URL = "https://mediastorecommerce.000webhostapp.com";
	const DB_NAME = "id19411014_db_mediastore";
	const DB_USER = "id19411014_db_davidparrado";
	const DB_PASSWORD = "b&Y=^7mO%0ecI*[I";
	const DB_CHARSET = "utf8";*/
	
	date_default_timezone_set('America/Bogota');

	const DEC = ","; // Decimales;
	const MIL = ".";//Millares;
	
	//Encriptado
	const KEY = "ecommerce";
	const METHOD = "AES-128-ECB";
	//Estados
	const STATUS = ["confirmado","en preparacion","preparado","entregado"];
	
	const COMISION = 1.04;
	const TASA = 900;
	const PERPAGE = 12;
	const BUTTONS = 3;
	const UTILIDAD = 1.7;

?>