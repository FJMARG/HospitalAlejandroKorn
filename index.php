<?php
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

require_once ('vendor/autoload.php'); /* Carga todas las clases especificadas en el archivo composer.json (en la seccion autoload). */

class Router {

	static function start ($categoria, $user, $pass, $accion){
		if (($user != -1) && ($pass != -1)){
			self::iniciarSesion ($user,$pass);
		}
		elseif (($categoria == 'index') || ($categoria == 'login')){
			FrontController::getInstance()->mostrar($categoria,'','');
		}
		elseif (SessionController::verifySession()){
			if ($categoria=='administracion'){
				FrontController::getInstance()->mostrar($categoria,'',$_SESSION['sesion']->getUsername());
			}
			elseif($categoria=='logout'){
				SessionController::logout();
				FrontController::getInstance()->mostrar('index','','');
			}
			else { 
				if (($categoria=='usuarios') && ($accion == '') && (SessionController::haveRol('Administrador'))){
					FrontController::getInstance()->mostrar($categoria,'',$_SESSION['sesion']->getUsername());
				}
				elseif(($categoria=='listarUsuarios') && ($accion=='listarUsuarios') && (SessionController::havePermission('usuario_index'))){
					UsuarioController::getInstance()->listarUsuarios($_SESSION['sesion']->getUsername());
				}
				elseif(($categoria=='crearUsuario') && (SessionController::havePermission('usuario_new'))){
					$mensaje='';
					if ($accion=='crear'){
						$mensaje=UsuarioRepository::getInstance()->crearUsuario($_POST['user'], $_POST['pass'], $_POST['nombre'], $_POST['apellido'], $_POST['email']);
					}
					FrontController::getInstance()->mostrar($categoria,$mensaje,$_SESSION['sesion']->getUsername());
				}
				elseif((($categoria=='actualizarUsuarios')||($categoria=='modificarUsuario')) && (SessionController::havePermission('usuario_update'))){
					if($categoria=="actualizarUsuarios"){
						UsuarioController::getInstance()->actualizarUsuarios($_SESSION['sesion']->getUsername());
					}
					elseif($categoria=='modificarUsuario'){
						$mensaje='';
						if($accion=="modificar"){
							$mensaje=UsuarioRepository::getInstance()->actualizarUsuario($_POST['id'], $_POST['user'], $_POST['pass'], $_POST['nombre'], $_POST['apellido'], $_POST['email'], $_POST['rls'], $_POST['activo']);
						}
						UsuarioController::getInstance()->modificarUsuario($_SESSION['sesion']->getUsername(),$mensaje,$_GET['id']);
					}
				}
				elseif(($categoria=='eliminarUsuarios') && (SessionController::havePermission('usuario_destroy'))){
					$mensaje='';
					if ($accion='eliminar'){
						$mensaje=UsuarioRepository::getInstance()->eliminarUsuario('parametros necesarios');
					}
					FrontController::getInstance()->mostrar($categoria,$mensaje,$_SESSION['sesion']->getUsername());
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


if (!isset($_GET['categoria'])){
	$_GET['categoria'] = 'index';
}
if (!isset($_POST['usuario']) && (!isset($_POST['password']))){
	$_POST['usuario']=-1;
	$_POST['password']=-1;
}
if (!isset($_GET['accion'])){
	$_GET['accion']='';
}



if(!isset($_POST['user'])){
	$_POST['user']='';
}
if(!isset($_POST['pass'])){
	$_POST['pass']='';
}
if(!isset($_POST['nombre'])){
	$_POST['nombre']='';
}
if(!isset($_POST['apellido'])){
	$_POST['apellido']='';
}
if(!isset($_POST['email'])){
	$_POST['email']='';
}
if(!isset($_GET['id'])){
	$_GET['id']='';
}
if(!isset($_POST['id'])){
	$_POST['id']='';
}
if(!isset($_POST['rls'])){
	$_POST['rls']='';
}
if(!isset($_POST['activo'])){
	$_POST['activo']='';
}

Router::start ($_GET['categoria'], $_POST['usuario'], $_POST['password'], 
$_GET['accion']);