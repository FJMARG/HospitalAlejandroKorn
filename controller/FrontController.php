<?php

/**
 * Description of FrontController
 *
 * @author fede
 */
class FrontController {
    
    private static $instance;

    public static function getInstance() {

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
    
    private function __construct() {
        
    }
    
    public function mostrar($string){
        $vista = TwigView::getTwig();
    	echo $vista->render($string.'.html.twig');
    }
    
}
