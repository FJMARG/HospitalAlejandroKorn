<?php
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

require_once ('vendor/autoload.php'); /* Carga todas las clases especificadas en el archivo composer.json (en la seccion autoload). */

class Router {

	static function start ($accion, $user, $pass){
		if (($user != -1) && ($pass != -1)){
			self::iniciarSesion ($user,$pass);
		}
		elseif (($accion == 'index') || ($accion == 'login')){
			FrontController::getInstance()->mostrar($accion,'','');
		}
		elseif (self::verificarSesion()){
			if($accion=='listarUsuarios'){
				UsuarioController::getInstance()->listarUsuarios($_SESSION['sesion']->getUsername());
			}
			elseif ($accion=='administracion'){
				FrontController::getInstance()->mostrar($accion,'',$_SESSION['sesion']->getUsername());
			}
			elseif($accion='logout'){
				self::cerrarSesion();
				FrontController::getInstance()->mostrar('index','','');
			}
		}
		else{
			FrontController::getInstance()->mostrar('login','No tienes permisos para acceder a esta funcionalidad.','');
		}
	}

	private static function iniciarSesion ($user,$pass){
		$sesion = new SessionController();
		if(SessionController::login ($user,$pass)){
			FrontController::getInstance()->mostrar('administracion','',$user);
		}
		else{
			FrontController::getInstance()->mostrar('login','Datos Erroneos.','');
		}
	}

	private static function verificarSesion (){
		return (SessionController::verifySession());
	}

	private static function cerrarSesion(){
		return (SessionController::logout());
	}
}


if (!isset($_GET['accion'])){
	$_GET['accion'] = 'index';
}
if (!isset($_POST['usuario']) && (!isset($_POST['password']))){
	$_POST['usuario']=-1;
	$_POST['password']=-1;
}

Router::start ($_GET['accion'], $_POST['usuario'], $_POST['password']);