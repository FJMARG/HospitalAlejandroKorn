<?php
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

require_once ('vendor/autoload.php'); /* Carga todas las clases especificadas en el archivo composer.json (en la seccion autoload). */

class Router {

	static function start ($categoria, $user, $pass, $accion, $filtrosUsuario){
		# +++++++++++++++++++++  Seccion publica ++++++++++++++++++++++++ #
		if (($user != -1) && ($pass != -1)){
			self::iniciarSesion ($user,$pass);
		}

		elseif ((($categoria == 'index') || ($categoria == 'login'))&&(empty($accion))){
			FrontController::getInstance()->mostrar($categoria,'','');
		}
		# +++++++++++++++++++++  Fin seccion publica ++++++++++++++++++++++++ #
		# +++++++++++++++++++++  Seccion Usuario Logueado ++++++++++++++++++++++++ #
		elseif (SessionController::verifySession()){
			if ($categoria=='administracion'){
				FrontController::getInstance()->mostrar($categoria,'',$_SESSION['sesion']->getUsername());
			}
			elseif($accion=='logout'){
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
			# ++++++++++++++++++++++++  Inicio Seccion Usuarios ++++++++++++++++++++++++ #
			elseif(($categoria=='usuarios')&&(SessionController::haveRol('Administrador'))){ 
				if ($accion == ''){ # Seccion donde se muestran las funcionalidades disponibles para los usuarios. #
					FrontController::getInstance()->mostrar($categoria,'',$_SESSION['sesion']->getUsername()); 
				}
				elseif(($accion=='listarUsuarios') && (SessionController::havePermission('usuario_index'))){ /* Categoria donde se muestra un listado de los usuarios */
					UsuarioController::getInstance()->listarUsuarios($_SESSION['sesion']->getUsername(), $filtrosUsuario, '', $_GET['pag']);
				}
				elseif(($accion=='verUsuario') && (SessionController::havePermission('usuario_show'))){
					UsuarioController::getInstance()->mostrarUsuario($_SESSION['sesion']->getUsername(), $_GET['id'], $filtrosUsuario, $_GET['pag']);
				}
				elseif(($accion=='crearUsuario') && (SessionController::havePermission('usuario_new'))){ /* Categoria donde se crea usuario. */
					FrontController::getInstance()->mostrar($accion,'',$_SESSION['sesion']->getUsername());
				}
				elseif (($accion=='crear') && (SessionController::havePermission('usuario_new'))){
					UsuarioController::getInstance()->crearUsuario($_POST['user'], $_POST['pass'], $_POST['nombre'], $_POST['apellido'], $_POST['email'], $_POST['confirmUser'], $_POST['confirmPass'], $_POST['confirmEmail'], $_SESSION['sesion']->getUsername());
				}
				elseif(($accion=='modificarUsuario')&&(SessionController::havePermission('usuario_update'))){ /* Accion que muestra la seccion donde se modifica a un usuario */
					UsuarioController::getInstance()->modificarUsuario($_SESSION['sesion']->getUsername(),'',$_GET['id'], $_GET['pag'], $filtrosUsuario);
				}
				elseif(($accion=="modificar")&&(SessionController::havePermission('usuario_update'))){ /* Accion de modificar a un usuario. */
					UsuarioController::getInstance()->actualizarUsuario($_POST['user'], $_POST['pass'], $_POST['nombre'], $_POST['apellido'], $_POST['email'], $_POST['confirmUser'], $_POST['confirmPass'], $_POST['confirmEmail'], $_POST['rls'], $_POST['activo'], $_POST['id'], $_GET['pag'], $filtrosUsuario, $_SESSION['sesion']->getUsername());
				}
				elseif(($accion=='eliminarUsuario') && (SessionController::havePermission('usuario_destroy'))){ /* Seccion donde se elimina a un usuario. */
					UsuarioController::getInstance()->eliminarUsuario($_SESSION['sesion']->getUsername(), $_GET['id'], $filtrosUsuario, $_GET['pag']);
				}
			}
			else{ # Usuario sin permisos. #
				FrontController::getInstance()->mostrar('administracion','No tienes permisos para acceder a esta funcionalidad.',$_SESSION['sesion']->getUsername());
			}
			# ++++++++++++++++++++++++  Fin Seccion Usuarios ++++++++++++++++++++++++ #
		}
		# ++++++++++++++++++++++++  Fin Seccion Usuario Logueado ++++++++++++++++++++++++ #
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

# ++++++++++++++++++++++++ Preparacion de variables y llamada a metodo inicial ++++++++++++++++++++++++ #

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