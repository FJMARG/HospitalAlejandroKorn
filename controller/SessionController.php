<?php

class SessionController extends DoctrineRepository {

/*

	private $nombre;
	private $apellido;
	private $email;
	private $id;
	private $username;
	private $activo;

	public setNombre($nom){
		self->$nombre = $nom;
	}
	public setApellido($ape){
		self->$apellido = $ape;
	}
	public setEmail($e){
		self->$email = $e;
	}
	public setId($i){
		self->$id = $i;
	}
	public setUsername($user){
		self->$username = $user;
	}
	public setActivo($a){
		self->$activo = $a;
	}

	public getNombre(){
		return self->$nombre;
	}
	public getApellido(){
		return self->$apellido;
	}
	public getEmail(){
		return self->$email;
	}
	public getId(){
		return self->$id;
	}
	public getUsername(){
		return self->$username;
	}
	public getActivo(){
		return self->$activo;
	}

*/

	static function login ($user, $pass){	/* Metodo comparacion de datos */
		$dbuser=UsuarioRepository::getInstance()->find($user);
		try {	/* 	Excepcion en caso de que existan datos erroneos */
			if ((empty ($dbuser)) || !(self::verifyPassword ($dbuser,$pass))){
				throw new Exception ('Datos erroneos.',1);
			}elseif ($dbuser->getActivo() == false){
				throw new Exception ('Usuario bloqueado.',2);
			}
		}
		catch (Exception $e){
			return new ClaseMensaje ('danger',$e->getMessage(),'Error: ');
		}
		self::generateSession($dbuser);
		return new ClaseMensaje ('success','Se ha iniciado sesion correctamente.','Exito: ');
	}

	static function loginAdministrador ($user, $pass){	/* Metodo comparacion de datos */
		$dbuser=UsuarioRepository::getInstance()->find($user);
		try {	/* 	Excepcion en caso de que existan datos erroneos */
			if ((empty ($dbuser)) || !(self::verifyPassword ($dbuser,$pass))){
				throw new Exception ('Datos erroneos.',1);
			}elseif ($dbuser->getActivo() == false){
				throw new Exception ('Usuario bloqueado.',2);
			}else{
				$roles=UsuarioRepository::getInstance()->obtenerRoles($dbuser);
				if(!in_array ('Administrador',$roles)){
					throw new Exception ('No eres administrador.',3);
				}
			}
		}
		catch (Exception $e){
			return new ClaseMensaje ('danger',$e->getMessage(),'Error: ');
		}
		self::generateSession($dbuser);
		return new ClaseMensaje ('success','Se ha iniciado sesion correctamente.','Exito: ');
	}

	private static function generateSession ($dbuser){
		session_start();
		$_SESSION['status']= "connected";
		
		/*

		$_SESSION['timehours']= date("H");
		$_SESSION['timemins']= date("i");
		$_SESSION['timesegs']= date("s");

		*/

		$_SESSION['sesion']=$dbuser;
		$_SESSION['permisos']=UsuarioRepository::getInstance()->obtenerPermisos($dbuser);
		$_SESSION['roles']=UsuarioRepository::getInstance()->obtenerRoles($dbuser);

		session_commit();
	}

	private static function verifyPassword ($dbuser, $pass){
		return (($dbuser->getPassword()) == ($pass));
	}

	public static function logout (){							/* Funcion para el cierre de sesion */
		session_destroy();
	}

	static function haveRol ($rol){
		return in_array ($rol,$_SESSION['roles']);
	}

	static function havePermission($permission){
		return in_array($permission,$_SESSION['permisos']);
	}

	static function verifySession (){
		/*$timehours = date('H');
		$timemins = date('i');
		$timesegs = date('s');
		$segsactual = ($timesegs)+($timemins * 60)+($timehours * 60 * 60);
		$segssesion = ($_SESSION['timesegs'])+($_SESSION['timemins'] * 60)+($_SESSION['timehours'] * 60 * 60);
		try {
			if (($_SESSION["status"] != "connected") or ((($segsactual - $segssesion) > (5*60)) xor ((0 < (24*60*60 - ($segssesion - $segsactual)) and ((24*60*60 - ($segssesion - $segsactual) < (24*60*60))))))) {
				throw new Exception ('Se perdio la Sesion.',0);
				exit();
			}
		}
		catch (Exception $e){
			return false;
		}
		$_SESSION['timehours']= date("H");
		$_SESSION['timemins']= date("i");
		$_SESSION['timesegs']= date("s");

		*/

		$ok=false;
		$respuesta=session_start();
		if (isset ($_SESSION['status'])){
			if ($_SESSION['status'] == 'connected'){
				$ok=true;
			}
		}

		return (($respuesta) && ($ok));
	}
}

?>