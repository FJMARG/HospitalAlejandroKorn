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
    
    public function listarUsuarios($usr, $filtros, $msg){
        if ((!empty($filtros['username']))&&(($filtros['activo']==1)||($filtros['activo']==2))){
            $arrayUsuarios = UsuarioRepository::getInstance()->listBy($filtros['username'],$filtros['activo']);
        }
        elseif((!empty($filtros['username']))||(($filtros['activo']==1)||($filtros['activo']==2))){
            $arrayUsuarios = UsuarioRepository::getInstance()->listByArray($filtros);
        }
        else{
            $arrayUsuarios = UsuarioRepository::getInstance()->listAll();
        }
        $vista = TwigView::getTwig();
        echo $vista->render('listaUsuarios.html.twig', array('usuarios' => $arrayUsuarios, 'user' => $usr, 'mensaje' => $msg));
    }

    private function validarCamposUsuario($user, $pass, $nombre, $apellido, $email, $confirmUser, $confirmPass, $confirmEmail){
        if ((empty($user))||(empty($pass))||(empty($nombre))||(empty($apellido))||(empty($email))||(empty($confirmUser))||(empty($confirmPass))||(empty($confirmEmail))){
            return "No puede haber campos vacios.";
        }
        if (strlen($user)<6){
            return "La longitud del nombre de usuario debe ser de al menos 6 caracteres.";
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
        if (filter_var($email, FILTER_VALIDATE_EMAIL)){
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

    public function crearUsuario($user, $pass, $nombre, $apellido, $email, $confirmUser, $confirmPass, $confirmEmail){
        $this->validarCamposUsuario($user, $pass, $nombre, $apellido, $email, $confirmUser, $confirmPass, $confirmEmail);
        return UsuarioRepository::getInstance()->crearUsuario($user, $pass, $nombre, $apellido, $email);
    }

    public function modificarUsuario($usr, $msg, $id){
        $usuarioModificar = UsuarioRepository::getInstance()->findById($id);
        $roles=RolRepository::getInstance()->listAll();
        $rols=array();
        foreach ($roles as $nombrerol){
            $rols[]=$nombrerol->getNombre();
        }
        $roles=UsuarioRepository::getInstance() -> obtenerRoles($usuarioModificar); 
        $vista = TwigView::getTwig();
        echo $vista->render('modificarUsuario.html.twig', array('usuario' => $usuarioModificar, 'mensaje'=>$msg, 'user' => $usr, 'arrayRolesUsuario' => $roles, 'arrayRoles' => $rols));
    }

    public function actualizarUsuario($user, $pass, $nombre, $apellido, $email, $confirmUser, $confirmPass, $confirmEmail, $roles, $activo, $id){
        $this->validarCamposUsuario($user, $pass, $nombre, $apellido, $email, $confirmUser, $confirmPass, $confirmEmail);
        return UsuarioRepository::getInstance()->actualizarUsuario($user, $pass, $nombre, $apellido, $email, $roles, $activo, $id);
    }

    public function eliminarUsuario($usr,$id){
        $filtros=array();
        $filtros['activo']='';
        $filtros['username']='';
        if (UsuarioRepository::getInstance()->eliminarUsuario($id))
        	$this::getInstance()->listarUsuarios($usr,$filtros,'El usuario con id:'.$id.' se elimino correctamente.');
        else
        	$this::getInstance()->listarUsuarios($usr,$filtros,'Error al eliminar el usuario con id:'.$id.'.');
    }
   
}