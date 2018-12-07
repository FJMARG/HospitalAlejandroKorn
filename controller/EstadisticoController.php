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
            $consultas = EstadisticoRepository::getInstance()->estadisticaGenero();
            $co = $this->convertirArrays($consultas);
            $cant = sizeof($co);
        }
        elseif ($mostrar == 'consulta') {
            $datos =EstadisticoRepository::getInstance()->estadisticaConsultaXMotivo();
             $result = $this->convertirArray($datos);
             $consultas = EstadisticoRepository::getInstance()->estadisticaMotivo();
             $co = $this->convertirArrays($consultas);
             $cant = sizeof($co);
        }
        else{
            $datos =EstadisticoRepository::getInstance()->estadisticaConsultaXLocalidad();
            $result = $this->convertirArray($datos);
              $consultas = EstadisticoRepository::getInstance()->estadisticaLocalidad();
            $co = $this->convertirArrays($consultas);
            $cant = sizeof($co);
        }



             $vista = TwigView::getTwig();            
           echo $vista->render('estadistica.html.twig', array('data'=>$result , 'cope'=>$co ,'sobre'=>$mostrar , 'total'=>$cant, 'user' => ($_SESSION['sesion'])));
        

    }



    public function convertirArray($datos){
        $result = array();
            foreach ($datos as $valor) {
                array_push($result, array('name' => $valor[1],'y' => $valor[0]));
                    }
              $result = json_encode( $result, JSON_NUMERIC_CHECK );  
              return $result; 
    }

     public function convertirArrays($datos){
        $result = array();
            foreach ($datos as $valor) {
                     array_push($result, array('nombre'=> $valor[0],'apellido'=> $valor[1], 'fecha'=> $valor[2], 'motivo'=> $valor [3] , 'diagnostico'=> $valor [4]));
                    }
              return $result; 
    }

}








 ?>