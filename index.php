<?php
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

require_once ('vendor/autoload.php'); /* Carga todas las clases especificadas en el archivo composer.json (en la seccion autoload). */

if ((isset($_POST["usuario"])) && (isset($_POST["password"]))){
	$vista="administracion";
	FrontController::getInstance()->mostrar($vista);
}
else{
	if(!isset($_GET["accion"])){
		$vista='index';
		FrontController::getInstance()->mostrar($vista);
	}
	else{
		if ($_GET["accion"]== "listarUsuarios"){
	    	UsuarioController::getInstance()->listarUsuarios();
		}
		else{	
			if (isset($_GET["accion"])){
				$vista=$_GET["accion"];
				FrontController::getInstance()->mostrar($vista);
			}
		}
	}
}