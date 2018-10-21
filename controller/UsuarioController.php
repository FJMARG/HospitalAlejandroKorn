<?php

/**
 * Description of UsuarioController
 *
 * @author fede
 */
class UsuarioController {
    
    private static $instance;

    public static function getInstance() {

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
    
    private function __construct() {
        
    }
    
    public function listarUsuarios($usr, $filtros, $msg, $pagActual){
        if ((!empty($filtros['username']))&&(($filtros['activo']==1)||($filtros['activo']==2))){ /* Activo y Username Seteados */
            $arrayUsuarios = UsuarioRepository::getInstance()->listBy($filtros['username'],$filtros['activo']);
        }
        elseif(($filtros['activo']==1)||($filtros['activo']==2)){ /* Activo Seteado */
            $arrayUsuarios = UsuarioRepository::getInstance()->listByActivo($filtros['activo']);
        }
        elseif(!empty($filtros['username'])){ /* Username Seteado */
            $arrayUsuarios = UsuarioRepository::getInstance()->listByUsername($filtros['username']);
        }
        else{ /* Nada seteado. */
            $arrayUsuarios = UsuarioRepository::getInstance()->listAll();
        }

        /* ++++++++++++++++++++++++++++++++++++ Paginado +++++++++++++++++++++++++++++++++++++++++++++ */

        $cantUsuarios = sizeof($arrayUsuarios); /* Aca debe ir el total de elementos a listar */

        if (!empty($cantUsuarios)){
            $config = ConfiguracionRepository::getInstance()->recuperarconfiguracion();

            $pagActual = intval($pagActual); /* Para convertir el numero a entero cuando se recibe por parametro. */

            $cantXPag = $config['paginado']->getValor();

            $cantDePags = intdiv($cantUsuarios,$cantXPag);

            if (($cantUsuarios % $cantXPag)!= 0){
                $cantDePags=$cantDePags+1;
            }

            if ($pagActual > $cantDePags){ /* Cuando se eliminan elementos, que se acomoden los valores. */
                $pagActual = $cantDePags;
            }

            $offset = ($pagActual-1) * $cantXPag;
            $limit = ($pagActual * $cantXPag)-1;

            if ($limit > $cantUsuarios){ /* Si la ultima pagina no se completa de elementos, se hace esta operacion para no superar el limite */
            	$limit = $cantUsuarios-1;
            }
        }
        else{
            $limit=0;
            $offset=0;
            $cantDePags=0;
        }

        /* ++++++++++++++++++++++++++++++++ Fin Paginado +++++++++++++++++++++++++++++++++++++++++++++ */

        $vista = TwigView::getTwig();
        echo $vista->render('listaUsuarios.html.twig', array('usuarios' => $arrayUsuarios, 'user' => $usr, 'mensaje' => $msg, 'limite' => $limit, 'cantPags' => $cantDePags, 'pag' => $pagActual, 'despl' => $offset, 'busqueda' => $filtros));
    }

    private function validarCamposUsuario($user, $pass, $nombre, $apellido, $email, $confirmUser, $confirmPass, $confirmEmail){
        if ((empty($user))||(empty($pass))||(empty($nombre))||(empty($apellido))||(empty($email))||(empty($confirmUser))||(empty($confirmPass))||(empty($confirmEmail))){
            return "No puede haber campos vacios.";
        }
        if (strlen($user)<6){
            return "La longitud del nombre de usuario debe ser de al menos 6 caracteres.";
        }
        if (str_replace(' ', '', $user)!= $user){
            return "El nombre de usuario no debe contener espacios en blanco.";
        }
        if (strlen($pass)<6){
            return "La longitud de la contrasena debe ser de al menos 6 caracteres.";
        }
        if (strlen($nombre)<3){
            return "La longitud del nombre debe ser de al menos de 3 caracteres.";
        }
        if (strlen($apellido)<3){
            return "La longitud del apellido debe ser de al menos de 3 caracteres.";
        }
        if (!(filter_var($email, FILTER_VALIDATE_EMAIL))){
            return "El e-mail ingresado no es un e-mail valido.";
        }
        if ($user != $confirmUser){
            return "El nombre de usuario no coincide con el ingresado en el campo de confirmacion de usuario.";
        }
        if ($pass != $confirmPass){
            return "La contrasena no coincide con la ingresada en el campo de confirmacion de contrasena.";
        }
        if ($email != $confirmEmail){
            return "El e-mail no coincide con el ingresado en el campo de confirmacion de e-mail.";
        }
    }

    public function mostrarUsuario($usr,$id,$filtros, $pag){
        $usuarioMostrar=UsuarioRepository::getInstance()->findById($id);
        if (!empty ($usuarioMostrar)){
            $rolesUsuario= UsuarioRepository::getInstance() -> obtenerRoles($usuarioMostrar);
            $vista = TwigView::getTwig();
            echo $vista->render('mostrarUsuario.html.twig', array('user' => $usr, 'usuario' => $usuarioMostrar, 'roles' => $rolesUsuario));
        }
        else{
            $this::getInstance()->listarUsuarios($usr,$filtros,'Error al intentar mostrar el usuario con id:'.$id.'. Posiblemente no exista.', $pag);
        }
    }

    public function crearUsuario($user, $pass, $nombre, $apellido, $email, $confirmUser, $confirmPass, $confirmEmail, $sessionUser){
        $msg = $this->validarCamposUsuario($user, $pass, $nombre, $apellido, $email, $confirmUser, $confirmPass, $confirmEmail);
        if (empty($msg)){
            $msg=UsuarioRepository::getInstance()->crearUsuario($user, $pass, $nombre, $apellido, $email);
        }
            FrontController::getInstance()->mostrar('crearUsuario',$msg,$sessionUser);
    }

    public function modificarUsuario($usr, $msg, $id, $pag, $filtros){
        $usuarioModificar = UsuarioRepository::getInstance()->findById($id);
        if($usr->getId()==$id){
            $this::getInstance()->listarUsuarios($usr,$filtros,'Error. No es posible modificarse a si mismo.', $pag);
        }
        elseif (!empty($usuarioModificar)){
            $roles=RolRepository::getInstance()->listAll();
            $rols=array();
            foreach ($roles as $nombrerol){
                $rols[]=$nombrerol->getNombre();
            }
            $roles=UsuarioRepository::getInstance() -> obtenerRoles($usuarioModificar); 
            $vista = TwigView::getTwig();
            echo $vista->render('modificarUsuario.html.twig', array('usuario' => $usuarioModificar, 'mensaje'=>$msg, 'user' => $usr, 'arrayRolesUsuario' => $roles, 'arrayRoles' => $rols));
        }
        else{
            $this::getInstance()->listarUsuarios($usr,$filtros,'Error al intentar modificar el usuario con id:'.$id.'. Posiblemente no exista.', $pag);
        }
    }

    public function actualizarUsuario($user, $pass, $nombre, $apellido, $email, $confirmUser, $confirmPass, $confirmEmail, $roles, $activo, $id, $pag, $filtros, $sessionUser){
        $msg = $this->validarCamposUsuario($user, $pass, $nombre, $apellido, $email, $confirmUser, $confirmPass, $confirmEmail);
        if (empty($msg)){
            $msg=UsuarioRepository::getInstance()->actualizarUsuario($user, $pass, $nombre, $apellido, $email, $roles, $activo, $id);
        }
        UsuarioController::getInstance()->modificarUsuario($sessionUser,$msg,$id, $pag, $filtros);
    }

    public function eliminarUsuario($usr, $id, $filtros, $pag){
        if($usr->getId() == $id){
            $this::getInstance()->listarUsuarios($usr,$filtros,'Error. Imposible eliminarse a si mismo.', $pag);
        }
        elseif (UsuarioRepository::getInstance()->eliminarUsuario($id)){
        	$this::getInstance()->listarUsuarios($usr,$filtros,'El usuario con id:'.$id.' se elimino correctamente.', $pag);
        }
        else{
        	$this::getInstance()->listarUsuarios($usr,$filtros,'Error al eliminar el usuario con id:'.$id.'.', $pag);
        }
    }
   
}
