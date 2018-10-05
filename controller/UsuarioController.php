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
   
}
