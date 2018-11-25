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


    public function mostrarGrafico (){

        $datos = EstadisticoRepository::getInstance()->estadisticaPacienteXGenero();
        $result = $this->convertirArray($datos);

        $motivo =EstadisticoRepository::getInstance()->estadisticaConsultaXMotivo();
        $re = $this->convertirArray($motivo);

        $localidad =EstadisticoRepository::getInstance()->estadisticaConsultaXLocalidad();
            $loc = $this->convertirArray($localidad);

             $vista = TwigView::getTwig();            
            echo $vista->render('estadistica.html.twig', array('data'=>$result , 'motivo'=>$re , 'loca'=>$loc));
        

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