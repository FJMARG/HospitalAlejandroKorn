<?php
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

require_once ('vendor/autoload.php'); /* Carga todas las clases especificadas en el archivo composer.json (en la seccion autoload). */

class Router {

	static function start ($categoria, $user, $pass, $accion, $filtrosUsuario){
		if (($user != -1) && ($pass != -1)){
			self::iniciarSesion ($user,$pass);
		}
		elseif (($categoria == 'index') || ($categoria == 'login')){
			/*echo "aca";*/
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
			# +++++++++++++++++++++  categoria pacientes ++++++++++++++++++++++++ #
			elseif($categoria=='paciente') {
				    # Modulo de pacientes y acciones a realizar
				    if (($accion=='paciente_buscar') && (SessionController::havePermission('paciente_index')) ) {
				        PacienteController::getInstance()->realizaAccion("buscar"); /* pagina de buscar pacientes en el sitema*/
			        }
					elseif (($accion=='paciente_verResultado') && (SessionController::havePermission('paciente_index')) ) {
					  	PacienteController::getInstance()->realizaAccion("verBusqueda"); /* pagina de buscar pacientes sitema*/	
					}
					elseif (($accion=='paciente_verTodos') && (SessionController::havePermission('paciente_index')) ) {
					  	PacienteController::getInstance()->realizaAccion("verTodos"); /* pagina de buscar pacientes sitema*/ 	
					}	
					elseif (($accion=='paciente_crear') && (SessionController::havePermission('paciente_new')) ) {						
					  	PacienteController::getInstance()->realizaAccion("crear");	/* Crear Paciente en sitema*/
					}
				    elseif (($accion=='paciente_insertar') && (SessionController::havePermission('paciente_new')) ) {   	
					  	PacienteController::getInstance()->realizaAccion("insertar"); 	/* Commit el paciente en el sitema*/
					}
					elseif (($accion=='informar_alta') && (SessionController::havePermission('paciente_new')) ) {				    	
					  	PacienteController::getInstance()->realizaAccion("confirmacionAlta"); /* Ver detella de un paciente */
					}
					elseif (($accion=='paciente_ver') && (SessionController::havePermission('paciente_show')) ) {		    	
					  	PacienteController::getInstance()->realizaAccion("verPaciente"); /* Ver detella de un paciente */	
					}
					elseif (($accion=='paciente_pantallaBorrado') && (SessionController::havePermission('paciente_destroy')) ) { 	
					  	PacienteController::getInstance()->realizaAccion("pantallaBorrar"); /* Ver detella de un paciente */	
					}
					elseif (($accion=='paciente_borrar') && (SessionController::havePermission('paciente_destroy')) ) { 
				    	PacienteController::getInstance()->realizaAccion("borrar"); /* Ver detella de un paciente */
					}
					elseif (($accion=='informar_baja') && (SessionController::havePermission('paciente_destroy')) ) { 
				    	PacienteController::getInstance()->realizaAccion("confirmacionBaja"); /* Ver detella de un paciente */
					}
					elseif (($accion=='paciente_pantallaEditar') && (SessionController::havePermission('paciente_update')) ) { 	
				    	PacienteController::getInstance()->realizaAccion("pantallaEditar"); /* Ver detella de un paciente */
					}
                    # Sin permisos
			        else{
				 	  FrontController::getInstance()->mostrar('administracion','No tienes permisos para acceder a esta funcionalidad.',$_SESSION['sesion']->getUsername());
				    }	
			}
			# ++++++++++++++++++++++++  fin pacientes  ++++++++++++++++++++++++ #

			# ++++++++++++++++++++++++  comienzo configuracion ++++++++++++++++++++++++ #
			elseif ($categoria== 'configuracion') {
			if ( ($accion== 'configuracion_ver') && (SessionController::havePermission('configuracion_ver'))) {
				ConfiguracionController::getInstance()->VerConfiguracion();
			}
			elseif ($accion== 'configuracion_modificacion') {
				ConfiguracionController::getInstance()->ModificarConfiguracion($_POST['titulo'], $_POST['descripcion'], $_POST['mail'], $_POST['paginado'], $_POST['habilitado']);
				}
				else{
					FrontController::getInstance()->mostrar('administracion','No tienes permisos para acceder a esta funcionalidad.',$_SESSION['sesion']->getUsername());
				}
			}

			# ++++++++++++++++++++++++  fin configuracion ++++++++++++++++++++++++ #
			else { 
				if (($categoria=='usuarios') && ($accion == '') && (SessionController::haveRol('Administrador'))){
					FrontController::getInstance()->mostrar($categoria,'',$_SESSION['sesion']->getUsername());
				}
				elseif(($categoria=='listarUsuarios') && ($accion=='listarUsuarios') && (SessionController::havePermission('usuario_index'))){
					UsuarioController::getInstance()->listarUsuarios($_SESSION['sesion']->getUsername(), $filtrosUsuario, '', $_GET['pag']);
				}
				elseif(($categoria=='crearUsuario') && (SessionController::havePermission('usuario_new'))){
					$mensaje='';
					if ($accion=='crear'){
						$mensaje=UsuarioController::getInstance()->crearUsuario($_POST['user'], $_POST['pass'], $_POST['nombre'], $_POST['apellido'], $_POST['email'], $_POST['confirmUser'], $_POST['confirmPass'], $_POST['confirmEmail']);
					}
					FrontController::getInstance()->mostrar($categoria,$mensaje,$_SESSION['sesion']->getUsername());
				}
				elseif(($categoria=='modificarUsuario')&&(SessionController::havePermission('usuario_update'))){
					$mensaje='';
					if($accion=="modificar"){
						$mensaje=UsuarioController::getInstance()->actualizarUsuario($_POST['user'], $_POST['pass'], $_POST['nombre'], $_POST['apellido'], $_POST['email'], $_POST['confirmUser'], $_POST['confirmPass'], $_POST['confirmEmail'], $_POST['rls'], $_POST['activo'], $_POST['id']);
					}
					UsuarioController::getInstance()->modificarUsuario($_SESSION['sesion']->getUsername(),$mensaje,$_GET['id']);
				}
				elseif(($categoria=='eliminarUsuario') && (SessionController::havePermission('usuario_destroy'))){
					UsuarioController::getInstance()->eliminarUsuario($_SESSION['sesion']->getUsername(), $_GET['id']);
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
if(!isset($_POST['confirmUser'])){
	$_POST['confirmUser']='';
}
if(!isset($_POST['confirmPass'])){
	$_POST['confirmPass']='';
}
if(!isset($_POST['confirmEmail'])){
	$_POST['confirmEmail']='';
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
if (!isset($_GET['uname'])){
	$_GET['uname']='';
}
if (!isset($_GET['act'])){
	$_GET['act']='';
}

if (!isset($_GET['pag'])){
	$_GET['pag']=1;
}

$filtrosUsuario=array();
$filtrosUsuario['username']=$_GET['uname'];
$filtrosUsuario['activo']=$_GET['act'];

Router::start ($_GET['categoria'], $_POST['usuario'], $_POST['password'], 
$_GET['accion'], $filtrosUsuario);