<?php
use \Doctrine\Common\Collections\Criteria;
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

    public function listByArray ($filtros){
        $em = $this->getConnection();
        if (($filtros['activo']==2)||($filtros['activo']==1)){
            $activo=$filtros['activo']-1;
            $string = "select u
            from Usuario u
            where u.activo = :act";
            $query = $em->createQuery($string);
            $query->setParameter('act', $activo);
            $usuarioRepository = $query->getResult();
        }
        else{
            $string = "select u
            from Usuario u
            where u.username LIKE :uname";
            $query = $em->createQuery($string);
            $query->setParameter('uname', '%'.$filtros['username'].'%');
            $usuarioRepository = $query->getResult();
        }
        return $usuarioRepository;
    }

    public function listBy ($username, $activo){
        $activo=$activo-1;
        $em = $this->getConnection();
        $string = "select u
        from Usuario u
        where u.username LIKE :uname
        and u.activo = :act";
        $query = $em->createQuery($string);
        $query->setParameter('uname', '%'.$username.'%');
        $query->setParameter('act', $activo);
        $usuarioRepository = $query->getResult();
        return $usuarioRepository;
    }

    public function find($user){
        $entityManager = $this->getConnection();
        $usuarioRepository = $entityManager->getRepository('Usuario');
        $usuario = $usuarioRepository->findOneBy(array('username' => $user));
        return $usuario;
    }

    public function findById($id){
        $entityManager = $this->getConnection();
        $usuarioRepository = $entityManager->getRepository('Usuario');
        $usuario = $usuarioRepository->findOneBy(array('id' => $id));
        return $usuario;
    }

    public function obtenerRoles($usuario){
        $dbuserrol = $usuario->getRol();
        $arrayNombresRol= array();
        foreach ($dbuserrol as $rol){
            $arrayNombresRol[] = $rol->getNombre();
        }
        return $arrayNombresRol;
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

    public function crearUsuario ($user, $pass, $nombre, $apellido, $email){
        $entityManager = $this->getConnection();
        $usuarioRepository = $entityManager->getRepository('Usuario');
        $dbuser = $usuarioRepository->findOneBy(array('username' => $user));
        if (!empty ($dbuser)){
            return "El nombre de usuario ya existe.";
        }
        $dbemail = $usuarioRepository->findOneBy(array('email' => $email));
        if (!empty ($dbemail)){
            return "El email ingresado ya ha sido registrado para algun usuario.";
        }
        $dbuser = $usuarioRepository->findOneBy(array('firstName' => $nombre, 'lastName' => $apellido));
        if (!empty ($dbuser)){
            return "La persona a la cual desea crearle la cuenta ya tiene una cuenta registrada a su nombre.";
        }

        $objeto = new Usuario();

        $rolRepository = $entityManager->getRepository('Rol');
        $rol = $rolRepository->findOneBy(array('nombre' => 'EquipoDeGuardia'));

        $objeto -> setFirstName($nombre);
        $objeto -> setLastName($apellido);
        $objeto -> setUsername($user);
        $objeto -> setPassword($pass);
        $objeto -> setEmail($email);
        $objeto -> setUpdatedAt(date_create());
        $objeto -> setCreatedAt(date_create());
        $objeto -> setActivo(true);
        $objeto -> addRol($rol);

        $entityManager->persist($objeto);
        $entityManager->flush();

        return "El usuario ".$user." se ha creado correctamente.";
    }

    public function actualizarUsuario ($user, $pass, $nombre, $apellido, $email, $roles, $activo, $id){
        $em = $this->getConnection();
        $string = "select u
        from Usuario u
        where u.id != :iden
        and u.username = :usuar";
        $query = $em->createQuery($string);
        $query->setParameter('iden', $id);
        $query->setParameter('usuar', $user);
        $dbuser = $query->getResult();

        if (!empty ($dbuser)){
            return "El nombre de usuario ya existe.";
        }

        $string = "select u
        from Usuario u
        where u.id != :iden
        and u.email = :em";
        $query = $em->createQuery($string);
        $query->setParameter('iden', $id);
        $query->setParameter('em', $email);
        $dbemail = $query->getResult();
        if (!empty ($dbemail)){
            return "El email ingresado ya ha sido registrado para algun usuario.";
        }
        $string = "select u
        from Usuario u
        where u.id != :iden
        and u.firstName = :fn and u.lastName = :ln";
        $query = $em->createQuery($string);
        $query->setParameter('iden', $id);
        $query->setParameter('fn', $nombre);
        $query->setParameter('ln', $apellido);
        $dbuser = $query->getResult();
        if (!empty ($dbuser)){
            return "La persona a la cual desea crearle la cuenta ya tiene una cuenta registrada a su nombre.";
        }

        $em = $this->getConnection();

        $dbuser = $em->getRepository('Usuario')->find($id);

        if (empty ($dbuser)){
            return "El usuario que intentas editar no existe en el sistema.";
        }

        $rols=array();
        foreach($dbuser->getRol() as $rol){
            $rols[]=$rol->getNombre();
        }
        foreach ($roles as $rol){
            $r = $em->getRepository('Rol')->findOneBy(array('nombre' => $rol));
            if(!in_array($rol, $rols)){
                $dbuser -> addRol($r);
            }
        }

        foreach ($rols as $rol){
            $r = $em->getRepository('Rol')->findOneBy(array('nombre' => $rol));
            if(!in_array($rol, $roles)){
                $dbuser -> removeRol($r);
            }
        }

        $dbuser -> setFirstName($nombre);
        $dbuser -> setLastName($apellido);
        $dbuser -> setUsername($user);
        $dbuser -> setPassword($pass);
        $dbuser -> setEmail($email);
        $dbuser -> setUpdatedAt(date_create());

        if ($activo == 'on')
            $dbuser -> setActivo(true);
        else
            $dbuser -> setActivo(false);

        $em->persist($dbuser);
        $em->flush();

        return "El usuario ".$user." se ha modificado correctamente.";
    }

    public function eliminarUsuario ($id){
        $em = $this->getConnection();
        $usuario = $em->getRepository('Usuario')->find($id);
        if (!empty ($usuario)){
            $em->remove($usuario);
            $em->flush();
            return true;
        }
        return false;
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
