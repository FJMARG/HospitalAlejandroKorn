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

            $loader = new Twig_Loader_Filesystem('./view/templates');
            self::$twig = new Twig_Environment($loader, array());
        }
        return self::$twig;
    }

}
