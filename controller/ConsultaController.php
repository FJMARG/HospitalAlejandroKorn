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

         $vista = TwigView::getTwig();
         echo $vista->render('crearConsulta.html.twig',$datos);

   }

}    