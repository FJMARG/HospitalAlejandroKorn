<<?php 
/**
 * 
 */
class EstadisticoController extends DoctrineRepository
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


    public function mostrarGrafico (){
    	$datos = EstadisticoRepository::getInstance()->estadisticaPacienteXGenero();




        $vista = TwigView::getTwig();
         echo $vista->render('estadistica.html.twig', $datos);
    }

}








 ?>