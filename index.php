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
		elseif (SessionController::verifySession()){
			if ($accion=='administracion'){
				FrontController::getInstance()->mostrar($accion,'',$_SESSION['sesion']->getUsername());
			}
			elseif($accion=='logout'){
				SessionController::logout();
				FrontController::getInstance()->mostrar('index','','');
			}
			else { 
				if(($accion=='listarUsuarios')&&(SessionController::havePermission('usuario_index'))){
					UsuarioController::getInstance()->listarUsuarios($_SESSION['sesion']->getUsername());
				}
				else{
					FrontController::getInstance()->mostrar('administracion','No tienes permisos para acceder a esta funcionalidad.',$_SESSION['sesion']->getUsername());
				}
			}
		}
		else{
			FrontController::getInstance()->mostrar('login','Tienes que iniciar sesion y tener permisos para acceder a esta funcionalidad.','');
		}
	}

	private static function iniciarSesion ($user,$pass){
		$response = SessionController::login ($user,$pass);
		if($response == 'ok'){
			FrontController::getInstance()->mostrar('administracion','',$user);
		}
		else{
			FrontController::getInstance()->mostrar('login',$response,'');
		}
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