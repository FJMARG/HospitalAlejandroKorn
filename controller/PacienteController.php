<?php

/**
 * Description of UsuarioController
 *
 * @author fede
 */

class PacienteController extends DoctrineRepository {
    
    private static $instance;

    public static function getInstance() {

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
    
    private function __construct() {
      # sin implementar  
    }

    public function realizaAccion($accion)
    {       
       switch ($accion) {
           case 'buscar':
              # Llamar a Pagina de buscar Pacientes
              $this->buscarPaciente(null);
              break;
           case 'verTodos':
              # Ver todos los pacientes del sistema
              $this->verTodosPaciente();
              break;
           case 'verBusqueda':
              # Ver resultados de los pacientes del sistema
              $this->verResultadoPaciente();
              break;  
           case 'crear':
              # Ver resultados de los pacientes del sistema
              $this->crearPaciente(null);
              break;    
           case 'insertar':
              # Commit del paciente
              $this->insertarPaciente();
              break;    
           case 'verPaciente': 
              # Ver detalles de un paciente del paciente
              $id = ($_GET['id']);
              $this->verPaciente($id);
              break; 
           case 'pantallaBorrar': 
              # Ver detalles del paciente que se va a borrar del sistema
              $id = ($_GET['id']);
              $this->verPacienteBorrar($id);
              break;
           case 'borrar':
              # Borrado de paciente
              $id= ($_GET['id']);
              $this->pacienteBorrar($id);
           break;   
           case 'pantallaEditar':
              # Pantalla de edicion
              $id= ($_GET['id']);
              $this->verPacienteEditar($id,null);
           break;    
           case 'editar':
              # Pantalla de edicion
              $id= ($_GET['id']);
              $this->pacienteGuardar($id);
           break;       
           case 'confirmacionAlta':
              # Confirmar la creacion de paciente confirmacionBaja
              echo TwigView::getTwig()->render('confirmacion_alta.html.twig');
              break; 
           case 'confirmacionBaja':
              # Confirmar la creacion de paciente 
              echo TwigView::getTwig()->render('confirmacion_baja.html.twig');
              break; 
           case 'confirmacionGuardado':
              echo TwigView::getTwig()->render('confirmacion_guardado.html.twig');
              break;      
           default:
              # code...ERROR
              break;
         }  
    }


    public function buscarPaciente($mensaje){
        
       if ($mensaje != null) {
           $datos['mensaje'] = $mensaje;
       }
       
       # Llamamos la API de Tipos de Documentos
       $user = ($_SESSION['sesion']->getUsername());
       $datos['user'] = $user;

       /*   ATENCION !!!!!!!!!!!!!!!! QUEDA PARA 3 entrega
       # Llamamos la API de Tipos de Documentos  
       $json = ApiRequest::getInstance()->sendGet("https://api-referencias.proyecto2018.linti.unlp.edu.ar/tipo-documento");
       #$documentos = json_decode($json,true);
       $datos['documentos'] = json_decode($json,true); */
       
       $em = DoctrineRepository::getConnection();
       $docuRepository = $em->getRepository('TipoDocumento'); 
       $datos['documentos'] = $docuRepository->findAll();

       
       # Llamar planilla de documentos
       $vista = TwigView::getTwig(); /*var_dump($documentos);*/
       echo $vista->render('buscarPaciente.html.twig',$datos);
    }

    public function verTodosPaciente(){

        # Recuperar todos los pacientes registrados en el sistema 
        $Pacientes = PacienteRepository::getInstance()->listAll();
        $vista = TwigView::getTwig();
        echo $vista->render('listaPacientes.html.twig',array('pacientes' => $Pacientes));
    }

    public function verResultadoPaciente(){

          # Ver resultados de filtros

          $nombre                = ($_GET["nombrePaciente"]);
          $apellido              = ($_GET["apellidoPaciente"]);
          $tipoDocumento         = ($_GET["tipoDocumento"]);
          $numeroDocumento       = ($_GET["numeroDocumento"]);
          $numeroHistoriaClinica = ($_GET["numeroHistoriaClinica"]); 
          
          # Verificar que al menos un campo este cargado
          if ((empty($nombre)) 
          &&  (empty($apellido)) 
          &&  (empty($tipoDocumento))
          &&  (strlen($numeroDocumento) == 0)
          &&  (strlen($numeroHistoriaClinica) == 0))  
          {
              # Para el caso de que lleguen parametros vacios
              $mensaje = new Mensaje("E","Error","Debe completar al menos un parametro de busqueda");
              $this->buscarPaciente($mensaje);
          }
          
          else

          {
            
            # Obtengo los pacientes   
            $Pacientes = PacienteRepository::getInstance()->recuperarPacientes($nombre,$apellido,$tipoDocumento,$numeroDocumento,$numeroHistoriaClinica);

            $vista = TwigView::getTwig();
              echo $vista->render('listaPacientes.html.twig',array('pacientes' => $Pacientes));
          }
                                            
    }

     
    public function crearPaciente($mensaje) {
          
          # Mensaje de error del servidor, lo instancia NULL
          if ($mensaje != null) 
          {
            $datos['mensaje'] = $mensaje;
          }

          # Levantar datos para mostrar en pantalla  
          $em = DoctrineRepository::getConnection(); 

          $partidoRepository = $em->getRepository('Partido');
          $datos['partidos'] = $partidoRepository->findAll();

          $localidadRepository = $em->getRepository('Localidad');
          $datos['localidades'] = $localidadRepository->findAll();

          $generoRepository = $em->getRepository('Genero');
          $datos['generos'] = $generoRepository->findAll();

          /*   # No andan por el momentos las APIS
          $json = ApiRequest::getInstance()->sendGet("https://api-referencias.proyecto2018.linti.unlp.edu.ar/obra-social");
          $datos['obraSociales'] = json_decode($json,true);

          $json1 = ApiRequest::getInstance()->sendGet("https://api-referencias.proyecto2018.linti.unlp.edu.ar/tipo-documento");
          $datos['documentos'] = json_decode($json1,true); */

          $obraSocialRepository = $em->getRepository('ObraSocial'); 
          $datos['obraSociales'] = $obraSocialRepository->findAll();

          $docuRepository = $em->getRepository('TipoDocumento'); 
          $datos['documentos'] = $docuRepository->findAll();
          

          $vista = TwigView::getTwig();
          echo $vista->render('crearPaciente.html.twig',$datos);
    }

    public function insertarPaciente()  {

      /* Valdiar si los campos estan o no desabilidatos para Tipo documento */
      if (isset($_POST['tipoDocumento'])){ $tipoDocumento = ($_POST['tipoDocumento']); }       
      else { $tipoDocumento = '99'; /*SIN DOCUMENTO*/ } 
      
      /* Valdiar si los campos estan o no desabilidatosd para Nro documento*/
      if (isset($_POST['nroDocumento'])){ $nroDocumento = ($_POST['nroDocumento']); }       
      else { $nroDocumento = 0;}
        
      /* procesar las variables */
      $respuesta = PacienteRepository::getInstance()->crearPaciente(($_POST["nombre"]),
                                                                    ($_POST["apellido"]),
                                                                    ($_POST["fechaNacimineto"]),
                                                                    ($_POST["lugarNacimineto"]),
                                                                    ($_POST["partido"]),
                                                                    ($_POST["regionSanitaria"]),
                                                                    ($_POST["localidad"]),
                                                                    ($_POST["domicilio"]),
                                                                    ($_POST["genero"]),
                                                                    ($_POST["tieneDoc"]),
                                                                    $tipoDocumento,
                                                                    $nroDocumento,
                                                                    ($_POST["nroHistClinica"]),
                                                                    ($_POST["nroCarpeta"]),
                                                                    ($_POST["telefono"]),
                                                                    ($_POST["obraSocial"]) ); 
        

      $this->manejador("C",$respuesta,"./index.php?categoria=paciente&accion=informar_alta","crearPaciente","");
        
    }                                          
   

    public function verPaciente($id){

        # Buscar los datos del paciente a mostrar
        $datos['paciente'] = DoctrineRepository::getConnection()->getRepository('Paciente')->find($id);

        $vista = TwigView::getTwig();
        echo $vista->render('perfilPaciente.html.twig',$datos);        

    } 

    public function verPacienteBorrar($id){

        # Buscar los datos del paciente a mostrar
        $datos['paciente'] = DoctrineRepository::getConnection()->getRepository('Paciente')->find($id);

        $vista = TwigView::getTwig();
        echo $vista->render('borradoPaciente.html.twig',$datos);        

    } 

    public function pacienteBorrar($id){
    {
      
       $respuesta = PacienteRepository::getInstance()->borrarPaciente($id);
       $this->manejador("B",$respuesta,"./index.php?categoria=paciente&accion=informar_baja","","");    

       }
    }

    public function verPacienteEditar($id,$mensaje){
    
    # Mensaje de error del servidor, lo instancia NULL
    if ($mensaje != null) 
    {
        $datos['mensaje'] = $mensaje;
    }

    # Buscar los datos del paciente a mostrar
    $paciente = DoctrineRepository::getConnection()->getRepository('Paciente')->find($id);

    
    if ( $paciente != NULL )
    {
        
        $datos['paciente'] = $paciente;

        # Levantar datos para mostrar en pantalla  correspondiente paciente
        $em = DoctrineRepository::getConnection(); 

        $partidoRepository = $em->getRepository('Partido');
        $datos['partidos'] = $partidoRepository->findAll();

        $localidadRepository = $em->getRepository('Localidad');
        $datos['localidades'] = $localidadRepository->findAll();

        $generoRepository = $em->getRepository('Genero');
        $datos['generos'] = $generoRepository->findAll();

        $obraSocialRepository = $em->getRepository('ObraSocial'); 
        $datos['obraSociales'] = $obraSocialRepository->findAll();

        $docuRepository = $em->getRepository('TipoDocumento'); 
        $datos['documentos'] = $docuRepository->findAll();

        $vista = TwigView::getTwig();
        echo $vista->render('editardoPaciente.html.twig',$datos);      
  
    }
  
  }


  public function pacienteGuardar($id)
  {

       # Realizar el commit del paciente 

      /* Valdiar si los campos estan o no desabilidatos para Tipo documento */
      if (isset($_POST['tipoDocumento'])){ $tipoDocumento = ($_POST['tipoDocumento']); }       
      else { $tipoDocumento = '99'; /*SIN DOCUMENTO*/ } 
      
      /* Valdiar si los campos estan o no desabilidatosd para Nro documento*/
      if (isset($_POST['nroDocumento'])){ $nroDocumento = ($_POST['nroDocumento']); }       
      else { $nroDocumento = 0;}


             /* Leer las variables */
       $respuesta = PacienteRepository::getInstance()->guardarPaciente($id,
                                                       ($_POST["nombre"]),
                                                       ($_POST["apellido"]),
                                                       ($_POST["fechaNacimineto"]),
                                                       ($_POST["lugarNacimineto"]),
                                                       ($_POST["partido"]),
                                                       ($_POST["regionSanitaria"]),
                                                       ($_POST["localidad"]),
                                                       ($_POST["domicilio"]),
                                                       ($_POST["genero"]),
                                                       ($_POST["tieneDoc"]),
                                                       $tipoDocumento,
                                                       $nroDocumento,
                                                       ($_POST["nroHistClinica"]),
                                                       ($_POST["nroCarpeta"]),
                                                       ($_POST["telefono"]),
                                                       ($_POST["obraSocial"]) ); 

       $this->manejador("E",$respuesta,"./index.php?categoria=paciente&accion=informar_guardado","verPacienteEditar",$id);

  }

  public function manejador($page,$nro,$url,$metodo,$id)
  {
      

      switch ($nro) {
           case 0: # Es correcta la operacion, informo
                header("Location: $url");
           break;
           case 1: # Error en parametros obligatorios
              $mensaje = new Mensaje("E","Error","Campos Obligatorios Vacios");
              if ( $page == "C" ) { $this->$metodo($mensaje); } else  { $this->$metodo($id,$mensaje); }
           break;
           case 2: #Deben Completar Tipo y Numero de Documento
              $mensaje = new Mensaje("E","Error","El Tipo y Numero de documento no estan completos");
              if ( $page == "C" ) { $this->$metodo($mensaje); } else  { $this->$metodo($id,$mensaje); }
           break; 
           case 3: #Ya hiciste un Paciente registrado con ese Tipo y Nro Documento
              $mensaje = new Mensaje("E","Error","Ya hiciste un Paciente registrado con ese Tipo y Nro Documento");
              if ( $page == "C" ) { $this->$metodo($mensaje); } else  { $this->$metodo($id,$mensaje); }
           break;
           case 4: #Ya existe un paciente con la historia clinica ingresada
              $mensaje = new Mensaje("E","Error","Ya existe un paciente con la historia clinica ingresada");
              if ( $page == "C" ) { $this->$metodo($mensaje); } else  { $this->$metodo($id,$mensaje); }
           break;
           case 5: #ID incorrecto de pacietes 
              $mensaje = new Mensaje("E","Error","El usuario no es valido");
              if ( $page == "C" ) { $this->$metodo($mensaje); } else  { $this->$metodo($id,$mensaje); }
           break;
           case 6: #Campos superar el limite permitido 
              $mensaje = new Mensaje("E","Error","Sin documento (S/D) no puede tener asociando un nro de documento");
              if ( $page == "C" ) { $this->$metodo($mensaje); } else  { $this->$metodo($id,$mensaje); }
           break;
           case 7: #Campos superar el limite permitido 
              $mensaje = new Mensaje("E","Error","Campos desbordan la longitud permitia");
              if ( $page == "C" ) { $this->$metodo($mensaje); } else  { $this->$metodo($id,$mensaje); }
           break;
        }  

  }

} # FIN DE CLASE