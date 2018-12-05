<?php 
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


    public function mostrarGrafico ($mostrar){

        if ($mostrar == 'genero') {
            $datos = EstadisticoRepository::getInstance()->estadisticaPacienteXGenero();
            $result = $this->convertirArray($datos);
        }
        elseif ($mostrar == 'consulta') {
            $datos =EstadisticoRepository::getInstance()->estadisticaConsultaXMotivo();
             $result = $this->convertirArray($datos);
        }
        else{
            $datos =EstadisticoRepository::getInstance()->estadisticaConsultaXLocalidad();
            $result = $this->convertirArray($datos);
        }


             $vista = TwigView::getTwig();            
           echo $vista->render('estadistica.html.twig', array('data'=>$result , 'sobre'=>$mostrar));
        

    }



    public function convertirArray($datos){
        $result = array();
            foreach ($datos as $valor) {
                array_push($result, array('name' => $valor[1],'y' => $valor[0]));
                    }
              $result = json_encode( $result, JSON_NUMERIC_CHECK );  
              return $result; 
    }

}








 ?>