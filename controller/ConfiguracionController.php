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

    	$confi = ConfiguracionRepository::getInstance();

        $datos = $confi-> recuperarConfiguracion();

         $vista = TwigView::getTwig();
         echo $vista->render('configuracion.html.twig', array('data'=>$datos, 'user'=>$_SESSION['sesion'])); 

    }



    public function ModificarConfiguracion($titulo, $descripcion, $mail, $paginado, $habilitado){


        $confi = ConfiguracionRepository::getInstance();

        $confi-> updateConfiguracion( 1 ,'titulo', $titulo);
        $confi-> updateConfiguracion( 2 ,'descripcion', $descripcion);
        $confi-> updateConfiguracion( 3 ,'mail', $mail);
        $confi-> updateConfiguracion( 4 ,'paginado', $paginado);
        $confi-> updateConfiguracion( 5 ,'habilitado', $habilitado);

        $datos = $confi-> recuperarConfiguracion();

        $vista = TwigView::getTwig();
        $msj = new ClaseMensaje ('success','Los cambios se realizaron correctamente.','Exito: ');

        echo $vista->render('configuracion.html.twig', array('mensaje' => $msj, 'data' => $datos, 'user' => $_SESSION['sesion']));


    }




}






 ?>