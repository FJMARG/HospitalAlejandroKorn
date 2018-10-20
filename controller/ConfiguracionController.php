<?php 


/**
 * 
 */
class ConfiguracionController extends DoctrineRepository
{
	
	private static $instance;

    public static function getInstance() {

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
    
    private function __construct() {
        
    }



    public function VerConfiguracion(){

    	$titulo = DoctrineRepository::getConnection()->getRepository('Configuracion')->find(1);
    	$descripcion = DoctrineRepository::getConnection()->getRepository('Configuracion')->find(2);
    	$mail = DoctrineRepository::getConnection()->getRepository('Configuracion')->find(3);
    	$paginado = DoctrineRepository::getConnection()->getRepository('Configuracion')->find(4);
    	$habilitado = DoctrineRepository::getConnection()->getRepository('Configuracion')->find(5);
    	

    	$datos['titulo'] = $titulo;
    	$datos['descripcion'] = $descripcion;
    	$datos['mail'] = $mail;
    	$datos['paginado'] = $paginado;
    	$datos['habilitado'] = $habilitado;


    	 $vista = TwigView::getTwig();
       	 echo $vista->render('configuracion.html.twig',$datos); 

    }



    public function ModificarConfiguracion($titulo, $descripcion, $mail, $paginado, $habilitado){


        $confi = ConfiguracionRepository::getInstance();

        $confi-> updateConfiguracion( 1 ,'titulo', $titulo);
        $confi-> updateConfiguracion( 2 ,'descripcion', $descripcion);
        $confi-> updateConfiguracion( 3 ,'mail', $mail);
        $confi-> updateConfiguracion( 4 ,'paginado', $paginado);
        $confi-> updateConfiguracion( 5 ,'habilitado', $habilitado);

        echo "los datos se cambiaron con exito";
        $vista = TwigView::getTwig();
        echo $vista->render('administracion.html.twig');


    }




}






 ?>