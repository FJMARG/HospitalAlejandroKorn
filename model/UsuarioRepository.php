<?php

/**
 * Description of UsuarioRepository
 *
 * @author fede
 */
class UsuarioRepository extends DoctrineRepository {

    private static $instance;

    public static function getInstance() {

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct() {
        
    }

    public function listAll() {
        $entityManager = $this->getConnection();
        $usuarioRepository = $entityManager->getRepository('Usuario');
        $usuarios = $usuarioRepository->findAll();
        return $usuarios;
    }

    public function find($user){
        $entityManager = $this->getConnection();
        $usuarioRepository = $entityManager->getRepository('Usuario');
        $usuario = $usuarioRepository->findOneBy(array('username' => $user));
        return $usuario;
    }

    public function obtenerPermisos ($usuario){
    	$dbuserrol= $usuario->getRol();
    	$arraysPermisosUsuario=array();
		foreach ($dbuserrol as $rol) {
			$arraysPermisosUsuario[]= $rol->getPermiso();
		}
		$permisos=array();
		for ($i=0;$i<sizeof($arraysPermisosUsuario);$i++){
			$array=$arraysPermisosUsuario[$i];
			for ($j=0;$j<sizeof($array);$j++){
				$permisos[]=$array[$j]->getNombre();
			}
		}
		return array_unique($permisos);
    }

	function array_flatten($array) {
   		$return = array();
   		foreach ($array as $key => $value) {
       		if (is_array($value)){ 
       			$return = array_merge($return, array_flatten($value));
       		}
       		else {
       			$return[$key] = $value;
       		}
   		}
   		return $return;
	}
}
