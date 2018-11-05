<?php

class ConsultaController extends DoctrineRepository {
    
    private static $instance;

    public static function getInstance() {

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
    
    private function __construct() {
        
    }

    public function realizaAccion($accion)
    {
       switch ($accion) {	
       	  case 'crear':
              # Llamar a Pagina de buscar Pacientes
              $this->pantallaCrearConsulta(null);
              break;
          default:
              # code...ERROR
              break;    
	   }
    }   


   public function pantallaCrearConsulta($mensaje) {

         # Mensaje de error del servidor
         if ($mensaje != null) 
         {
            $datos['mensaje'] = $mensaje;
         }
         
         # User logueado
         $user = ($_SESSION['sesion']);
         $datos['user'] = $user;

         # Levantar datos para mostrar en pantalla  
         $em = DoctrineRepository::getConnection(); 

         # Motivo de consulta    
         $motivoRepository = $em->getRepository('MotivoConsulta');
         $datos['motivos'] = $motivoRepository->findAll();

         # Tratamiento Farmacologico
         $farmacologicoRepository = $em->getRepository('TratamientoFarmacologico');
         $datos['farmacologicos'] = $farmacologicoRepository->findAll();

         # Acompañamiento
         $acompanamientoRepository = $em->getRepository('Acompanamiento');
         $datos['acompas'] = $acompanamientoRepository->findAll();


         $vista = TwigView::getTwig();
         echo $vista->render('crearConsulta.html.twig',$datos);

   }

}    