<?php


/**
 * Description of TwigView
 *
 * @author fede
 */

abstract class TwigView {

    private static $twig;

    public static function getTwig() {

        if (!isset(self::$twig)) {

            /* No requerido en Twig 2.*, pero si en Twig 1.*, ya que se elimino porque es redundante (porque existe el autoloader de composer).

            Twig_Autoloader::register();
            
            */ 
            $loader = new Twig_Loader_Filesystem('./view/templates');
            self::$twig = new Twig_Environment($loader, array());
        }
        return self::$twig;
    }

}
