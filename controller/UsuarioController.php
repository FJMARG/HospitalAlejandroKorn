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

        $paginacion = FrontController::getInstance()->paginar($arrayUsuarios, $pagActual);

        /* ++++++++++++++++++++++++++++++++ Fin Paginado +++++++++++++++++++++++++++++++++++++++++++++ */

        $vista = TwigView::getTwig();
        echo $vista->render('listaUsuarios.html.twig', array('usuarios' => $arrayUsuarios, 'user' => $usr, 'mensaje' => $msg, 'limite' => $paginacion['limit'], 'cantPags' => $paginacion['cantDePags'], 'pag' => $pagActual, 'despl' => $paginacion['offset'], 'busqueda' => $filtros));
    }

    private function validarCamposUsuario($user, $pass, $nombre, $apellido, $email, $confirmUser, $confirmPass, $confirmEmail){
        if ((empty($user))||(!isset($user))||(empty($pass))||(!isset($pass))||(empty($nombre))||(!isset($nombre))||(empty($apellido))||(!isset($apellido))||(empty($email))||(!isset($email))||(empty($confirmUser))||(!isset($confirmUser))||(empty($confirmPass))||(!isset($confirmPass))||(empty($confirmEmail))||(!isset($confirmEmail))){
            return new ClaseMensaje ('danger','No puede haber campos vacios.','Error: ');
        }
        if (strlen($user)<6){
            return new ClaseMensaje ('danger','La longitud del nombre de usuario debe ser de al menos 6 caracteres.','Error: ');
        }
        if (str_replace(' ', '', $user)!= $user){
            return new ClaseMensaje ('danger','El nombre de usuario no debe contener espacios en blanco.','Error: ');
        }
        if (strlen($pass)<6){
            return new ClaseMensaje ('danger','La longitud de la contrasena debe ser de al menos 6 caracteres.','Error: ');
        }
        if (strlen($nombre)<3){
            return new ClaseMensaje ('danger','La longitud del nombre debe ser de al menos de 3 caracteres.','Error: ');
        }
        if (strlen($apellido)<3){
            return new ClaseMensaje ('danger','La longitud del apellido debe ser de al menos de 3 caracteres.','Error: ');
        }
        if (!(filter_var($email, FILTER_VALIDATE_EMAIL))){
            return new ClaseMensaje ('danger','El e-mail ingresado no es un e-mail valido.','Error: ');
        }
        if ($user != $confirmUser){
            return new ClaseMensaje ('danger','El nombre de usuario no coincide con el ingresado en el campo de confirmacion de usuario.','Error: ');
        }
        if ($pass != $confirmPass){
            return new ClaseMensaje ('danger','La contrasena no coincide con la ingresada en el campo de confirmacion de contrasena.','Error: ');
        }
        if ($email != $confirmEmail){
            return new ClaseMensaje ('danger','El e-mail no coincide con el ingresado en el campo de confirmacion de e-mail.','Error: ');
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
            $msj = new ClaseMensaje ('danger','Error al intentar mostrar el usuario con id:'.$id.'. Posiblemente no exista.','Error: ');
            $this::getInstance()->listarUsuarios($usr,$filtros,$msj,$pag);
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
        if (!empty($usuarioModificar)){
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
            $msj = new ClaseMensaje ('danger','Error al intentar modificar el usuario con id:'.$id.'. Posiblemente no exista.','Error: ');
            $this::getInstance()->listarUsuarios($usr,$filtros,$msj,$pag);
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
            $msj = new ClaseMensaje ('danger','Error. Imposible eliminarse a si mismo.','Error: ');
            $this::getInstance()->listarUsuarios($usr,$filtros,$msj, $pag);
        }
        elseif (UsuarioRepository::getInstance()->eliminarUsuario($id)){
            $msj = new ClaseMensaje ('success','El usuario con id:'.$id.' se elimino correctamente.','Error: ');
        	$this::getInstance()->listarUsuarios($usr,$filtros,$msj,$pag);
        }
        else{
            $msj = new ClaseMensaje ('danger','Error al eliminar el usuario con id:'.$id.'.','Error: ');
        	$this::getInstance()->listarUsuarios($usr,$filtros,$msj,$pag);
        }
    }
   
}
