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
          case 'insertar':
              # Insertar la consulta creada
              $this->insertarConsulta(null);
              break;   
          case 'confirmacionAlta':
              # Informar que la consulta fue creada
              $user = ($_SESSION['sesion']);
              $datos['user'] = $user;
              # Confirmar la creacion de paciente confirmacionBaja
              echo TwigView::getTwig()->render('confirmacion_altaconsulta.html.twig',$datos);
              break;     
          case 'verTodos':
              # Ver consultas del sistema
              $this->verConsultas($_GET['pag']);
              break; 
          case 'verConsultaPaciente': 
              # Ver detalles de una consulta particular
              if (!isset(($_GET['id']))) { $_GET['id'] = ""; }
              $id = ($_GET['id']);
              $this->verConsultaPaciente(($_GET['pag']),$id);
              break;         
          case 'verConsulta': 
              # Ver detalles de una consulta particular
              if (!isset(($_GET['id_consulta']))) { $_GET['id_consulta'] = ""; }
              $id = ($_GET['id_consulta']);
              $this->verConsulta($id);
              break;         
          default:
              # code...ERROR consulta_verPacientes
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


         $acompanamientoRepository = $em->getRepository('Institucion');
         $datos['instituciones'] = $acompanamientoRepository->findAll();

         $vista = TwigView::getTwig();
         echo $vista->render('crearConsulta.html.twig',$datos);

   }

   public function insertarConsulta($mensaje)
   {
       
      //leer parametros de entrada
      
      //ID paciente
      if (!isset(($_POST['id']))) { $_POST['id'] = -1; }  

      // fecha cosulta
      if (!isset(($_POST['fechaConsulta']))) { $_POST['fechaConsulta'] = ""; } 

      //  motivo de consulta
      if (!isset(($_POST['motivo']))) { $_POST['motivo'] = ""; } 

      // Articulacion con otros Instituciones
      if (!isset(($_POST['articulacion']))) { $_POST['articulacion'] = ""; } 

      // Internacion  
      if (!isset(($_POST['internacion']))) { $_POST['internacion'] = ""; } 

      // Diagnostico 
      if (!isset(($_POST['diagnostico']))) { $_POST['diagnostico'] = ""; } 
      
      // Observaciones
      if (!isset(($_POST['observaciones']))) { $_POST['observaciones'] = ""; } 

      // Tratamiento Farmacologico
      if (!isset(($_POST['farmacologico']))) { $_POST['farmacologico'] = ""; } 
      
      // Acompañamiento
      if (!isset(($_POST['acompa']))) { $_POST['acompa'] = ""; }   

      // Institucion  
      if (!isset(($_POST['institucion']))) { $_POST['institucion'] = ""; }    
      
      $resp = ConsultaRepository::getInstance()->crearConsulta( ($_POST['id']),
                                                                ($_POST['fechaConsulta']),
                                                                ($_POST['motivo']),
                                                                ($_POST['articulacion']),
                                                                ($_POST['internacion']),
                                                                ($_POST['diagnostico']),
                                                                ($_POST['observaciones']),
                                                                ($_POST['farmacologico']),
                                                                ($_POST['acompa']),
                                                                ($_POST['institucion']) ); // institucion = deviracion

     if ($resp == 0) // consulta creada
     {
        header("Location: ./index.php?categoria=consulta&accion=consulta_informeAlta");
     } 
     else  // error en la consulta
     {

     }
     
   }

   public function verConsultas($pagActual)
   {

     # User logueado
     $user = ($_SESSION['sesion']);
     $datos['user'] = $user;

     $datos['consultas'] = ConsultaRepository::getInstance()->listarTodasConsultas();

     /* ++++++++++++++++++++++++++++++++++++ Paginado +++++++++++++++++++++++++++++++++++++++++++++ */

     $paginacion = FrontController::getInstance()->paginar($datos['consultas'], $pagActual);

     /* ++++++++++++++++++++++++++++++++ Fin Paginado +++++++++++++++++++++++++++++++++++++++++++++ */

     $datos['limite'] = $paginacion['limit'];
     $datos['cantPags'] = $paginacion['cantDePags'];
     $datos['pag'] = $pagActual;
     $datos['despl'] = $paginacion['offset'];

     $vista = TwigView::getTwig();
     echo $vista->render('listaConsulta.html.twig',$datos);

   }

   public function verConsultaPaciente($pagActual,$id)
   {
       # User logueado
       $user = ($_SESSION['sesion']);
       $datos['user'] = $user;

       $datos['consultas'] = ConsultaRepository::getInstance()->listarConsultasPaciente($id);

       /* ++++++++++++++++++++++++++++++++++++ Paginado +++++++++++++++++++++++++++++++++++++++++++++ */

       $paginacion = FrontController::getInstance()->paginar($datos['consultas'], $pagActual);

       /* ++++++++++++++++++++++++++++++++ Fin Paginado +++++++++++++++++++++++++++++++++++++++++++++ */

       $datos['limite'] = $paginacion['limit'];
       $datos['cantPags'] = $paginacion['cantDePags'];
       $datos['pag'] = $pagActual;
       $datos['despl'] = $paginacion['offset'];

       $vista = TwigView::getTwig();
       echo $vista->render('listaConsulta.html.twig',$datos);
   }

   public function verConsulta($id){


        # Buscar los datos de la consulta que vamos a mostrar
        $datos['consulta'] = DoctrineRepository::getConnection()->getRepository('Consulta')->find($id);

        $user = ($_SESSION['sesion']);
        $datos['user'] = $user;

        $vista = TwigView::getTwig();
        echo $vista->render('datosConsulta.html.twig',$datos);        

    } 

}    