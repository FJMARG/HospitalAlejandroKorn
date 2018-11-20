<?php


/**
 * Description of TwigController
 *
 * @author fede
 */

abstract class TwigController {

    private static $twig;

    public static function getTwig() {

        if (!isset(self::$twig)) {

            $loader = new Twig_Loader_Filesystem('./view');
            self::$twig = new Twig_Environment($loader, array());
        }
        return self::$twig;
    }

}
