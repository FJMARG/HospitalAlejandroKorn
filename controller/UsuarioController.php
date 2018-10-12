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
    
    public function listarUsuarios($usr){
        $arrayUsuarios = UsuarioRepository::getInstance()->listAll();
        $vista = TwigView::getTwig();
        echo $vista->render('listaUsuarios.html.twig', array('usuarios' => $arrayUsuarios, 'user' => $usr));
    }

    public function actualizarUsuarios($usr){
        $arrayUsuarios = UsuarioRepository::getInstance()->listAll();
        $vista = TwigView::getTwig();
        echo $vista->render('actualizarUsuarios.html.twig', array('usuarios' => $arrayUsuarios, 'user' => $usr));
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
   
}
