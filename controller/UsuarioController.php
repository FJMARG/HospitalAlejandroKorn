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
