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
    
    public function mostrar($string,$msg, $usr){
        $datos = ConfiguracionRepository::getInstance()-> recuperarConfiguracion();
        $vista = TwigView::getTwig();
    	echo $vista->render($string.'.html.twig',array('mensaje'=>$msg,'user'=>$usr, 'dato'=> $datos));
    }

    public function paginar ($arrayElementos, $pagActual){
        $cant = sizeof($arrayElementos); /* Aca debe ir el total de elementos a listar */

        if (!empty($cant)){
            $config = ConfiguracionRepository::getInstance()->recuperarconfiguracion();

            $pagActual = intval($pagActual); /* Para convertir el numero a entero cuando se recibe por parametro. */

            $cantXPag = $config['paginado']->getValor();

            $cantDePags = intdiv($cant,$cantXPag);

            if (($cant % $cantXPag)!= 0){
                $cantDePags=$cantDePags+1;
            }

            if ($pagActual > $cantDePags){ /* Cuando se eliminan elementos, que se acomoden los valores. */
                $pagActual = $cantDePags;
            }

            $offset = ($pagActual-1) * $cantXPag;
            $limit = ($pagActual * $cantXPag)-1;

            if ($limit >= $cant){ /* Si la ultima pagina no se completa de elementos, se hace esta operacion para no superar el limite */
                $limit = $cant-1;
            }
        }
        else{
            $limit=0;
            $offset=0;
            $cantDePags=0;
        }
        $paginado=array();
        $paginado['cantDePags']=$cantDePags;
        $paginado['offset']=$offset;
        $paginado['limit']=$limit;
        return $paginado;
    }
    
}
