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
			if (empty ($dbuser)){
				throw new Exception ('Error',1);
			}elseif (!(self::verifyPassword ($dbuser,$pass))){
				throw new Exception ('Error',1);
			}
		}
		catch (Exception $e){
			return false;
		}
		self::generateSession($dbuser);
		return true;
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
	}

	private static function verifyPassword ($dbuser, $pass){
		return (($dbuser->getPassword()) == ($pass));
	}

	static function logout (){							/* Funcion para el cierre de sesion */
		session_unset ();
		return session_destroy();
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