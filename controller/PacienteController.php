<?php

class PacienteController extends DoctrineRepository {
    
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
           case 'buscar':
              # Llamar a Pagina de buscar Pacientes
              $this->buscarPaciente(null);
              break;
           case 'verTodos':
              # Ver todos los pacientes del sistema
              $this->verTodosPaciente($_GET['pag']);
              break;
           case 'verNoRegistrados':
              # Ver todos los pacientes del sistema
              $this->verNoRegistrados($_GET['pag']);
              break;   
           case 'verBusqueda':
              # Ver resultados de los pacientes del sistema
              $this->verResultadoPaciente($_GET['pag']);
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
              $user = ($_SESSION['sesion']);
              $datos['user'] = $user;
              # Confirmar la creacion de paciente confirmacionBaja
              echo TwigView::getTwig()->render('confirmacion_alta.html.twig',$datos);
              break; 
           case 'confirmacionBaja':
              $user = ($_SESSION['sesion']);
              $datos['user'] = $user;
              # Confirmar la creacion de paciente 
              echo TwigView::getTwig()->render('confirmacion_baja.html.twig',$datos);
              break; 
           case 'confirmacionGuardado':
              $user = ($_SESSION['sesion']);
              $datos['user'] = $user;
              echo TwigView::getTwig()->render('confirmacion_guardado.html.twig',$datos);
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
       $user = ($_SESSION['sesion']);
       $datos['user'] = $user;

       $datos['documentos'] = ReferenciasRepository::getTipoDocumento();
       
       
       # Llamar planilla de documentos
       $vista = TwigView::getTwig(); /*var_dump($documentos);*/
       echo $vista->render('buscarPaciente.html.twig',$datos);
    }

    public function verTodosPaciente($pagActual){
        $Pacientes = PacienteRepository::getInstance()->listAll();

        /* ++++++++++++++++++++++++++++++++++++ Paginado +++++++++++++++++++++++++++++++++++++++++++++ */

        $paginacion = FrontController::getInstance()->paginar($Pacientes, $pagActual);

        /* ++++++++++++++++++++++++++++++++ Fin Paginado +++++++++++++++++++++++++++++++++++++++++++++ */

        $vista = TwigView::getTwig();
        echo $vista->render('listaPacientes.html.twig',array('pacientes' => $Pacientes,'user' => ($_SESSION['sesion']),'limite' => $paginacion['limit'], 'cantPags' => $paginacion['cantDePags'], 'pag' => $pagActual, 'despl' => $paginacion['offset']));
    }

    public function verNoRegistrados($pagActual){
        
        $Pacientes = PacienteRepository::getInstance()->pacienteSinRegistro();

        /* ++++++++++++++++++++++++++++++++++++ Paginado +++++++++++++++++++++++++++++++++++++++++++++ */

        $paginacion = FrontController::getInstance()->paginar($Pacientes, $pagActual);

        /* ++++++++++++++++++++++++++++++++ Fin Paginado +++++++++++++++++++++++++++++++++++++++++++++ */

        $vista = TwigView::getTwig();
        echo $vista->render('listaPacientes.html.twig',array('pacientes' => $Pacientes,'user' => ($_SESSION['sesion']), 'limite' => $paginacion['limit'], 'cantPags' => $paginacion['cantDePags'], 'pag' => $pagActual, 'despl' => $paginacion['offset']));

    } 

    public function verResultadoPaciente($pagActual){

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
               
            $Pacientes = PacienteRepository::getInstance()->recuperarPacientes($nombre,$apellido,$tipoDocumento,$numeroDocumento,$numeroHistoriaClinica);

          /* +++++++++++++++++++++++++ Paginado +++++++++++++++++++++++++++ */

          $cantPacientes = sizeof($Pacientes); /* Aca debe ir el total de elementos a listar */

          if (!empty($cantPacientes)){

            $config = ConfiguracionRepository::getInstance()->recuperarconfiguracion();

            $pagActual = intval($pagActual); /* Para convertir el numero a entero cuando se recibe por parametro. */

            $cantXPag = $config['paginado']->getValor();
            $cantDePags = intdiv($cantPacientes,$cantXPag);

            if (($cantPacientes % $cantXPag)!= 0){
                $cantDePags=$cantDePags+1;
            }

            if ($pagActual > $cantDePags){ /* Cuando se eliminan elementos, que se acomoden los valores. */
                $pagActual = $cantDePags;
            }

            $offset = ($pagActual-1) * $cantXPag;
            $limit = ($pagActual * $cantXPag)-1;

            if ($limit >= $cantPacientes){ /* Si la ultima pagina no se completa de elementos, se hace esta operacion para no superar el limite */
              $limit = $cantPacientes-1;
            }
          }
          else{
            $limit=0;
            $offset=0;
            $cantDePags=0;
          }

          /* +++++++++++++++++++++++++ Fin Paginado ++++++++++++++++++++++++++ */

            $vista = TwigView::getTwig();
              echo $vista->render('listaPacientes.html.twig',array('pacientes' => $Pacientes,'user' => ($_SESSION['sesion']), 'limite' => $limit, 'cantPags' => $cantDePags, 'pag' => $pagActual, 'despl' => $offset));
          }
                                            
    }

     
    public function crearPaciente($mensaje) {
          
          $datos = $this->datosDePantalla(); 

          # Mensaje de error del servidor
          if ($mensaje != null) 
          {
            $datos['mensaje'] = $mensaje;
          }

          $user = ($_SESSION['sesion']);
          $datos['user'] = $user;

          $vista = TwigView::getTwig();
          echo $vista->render('crearPaciente.html.twig',$datos);
    }

    public function insertarPaciente()  
    {

      /* Instanciar Campos */
      $this->instanciarPost();

      
      $partido = ReferenciasRepository::getPartidoId(($_POST["partido"]));
      $region = $partido["region_sanitaria_id"];  
        

      /* procesar las variables */
      $respuesta = PacienteRepository::getInstance()->crearPaciente(($_POST["nombre"]),
                                                                    ($_POST["apellido"]),
                                                                    ($_POST["fechaNacimineto"]),
                                                                    ($_POST["lugarNacimineto"]),
                                                                    ($_POST["partido"]),
                                                                    $region,
                                                                    ($_POST["localidad"]),
                                                                    ($_POST["domicilio"]),
                                                                    ($_POST["genero"]),
                                                                    ($_POST["tieneDoc"]),
                                                                    ($_POST['tipoDocumento']),
                                                                    ($_POST['nroDocumento']),
                                                                    ($_POST["nroHistClinica"]),
                                                                    ($_POST["nroCarpeta"]),
                                                                    ($_POST["telefono"]),
                                                                    ($_POST["obraSocial"]) ); 
        

      $this->manejador("C",$respuesta,"./index.php?categoria=paciente&accion=informar_alta","crearPaciente","");                                       
    }

    public function verPaciente($id){

        # Buscar los datos del paciente a mostrar
        $paciente = DoctrineRepository::getConnection()->getRepository('Paciente')->find($id);

        // datos desde api para documento
        if ( $paciente->getTipoDoc() != NULL ) 
        {
           
             $id = $paciente->getTipoDoc()->getId();
             $array =  ReferenciasRepository::getTipoDocumentoId($id);
             $paciente->getTipoDoc()->setNombre($array['nombre']); 
        }

         
        // datos desde api para obra social
        if ( $paciente->getObraSocial() != NULL ) 
        {
        
             $id = $paciente->getObraSocial()->getId();
             $array =  ReferenciasRepository::getObraSocialId($id);
             $paciente->getObraSocial()->setNombre($array['nombre']);
        }  


        // datos desde api para Region Sanitaria
        if ( $paciente->getRegionSanitaria() != NULL )
        {
             $id = $paciente->getRegionSanitaria()->getId(); 
             $array =  ReferenciasRepository::getRegionSanitariaId($id);
             $paciente->getRegionSanitaria()->setNombre($array['nombre']);
        }  

        // datos desde api para Region Sanitaria
        if ( $paciente->getLocalidad() != NULL )
        {
             $id = $paciente->getLocalidad()->getId(); 
             $array =  ReferenciasRepository::getLocalidadId($id);
             $paciente->getLocalidad()->setNombre($array['nombre']);
        }  


        // asignar datos en salida de pantalla 
        $datos['paciente'] = $paciente;

        $user = ($_SESSION['sesion']);
        $datos['user'] = $user;

        $vista = TwigView::getTwig();
        echo $vista->render('perfilPaciente.html.twig',$datos);        

    } 

    public function verPacienteBorrar($id){

        # Buscar los datos del paciente a mostrar
        $paciente = DoctrineRepository::getConnection()->getRepository('Paciente')->find($id);
         
        if ( $paciente != NULL )
        { 

            $datos['paciente'] = $paciente;

            $user = ($_SESSION['sesion']);
            $datos['user'] = $user;

            $vista = TwigView::getTwig();
            echo $vista->render('borradoPaciente.html.twig',$datos);        

        }

    } 

    public function pacienteBorrar($id)
    {
        
      $respuesta = PacienteRepository::getInstance()->borrarPaciente($id);
      $this->manejador("B",$respuesta,"./index.php?categoria=paciente&accion=informar_baja","","");    

    }
 
  public function verPacienteEditar($id,$mensaje)
  {
        
     $datos = $this->datosDePantalla(); 

     $datos['localidades'] = ReferenciasRepository::getLocalidad();

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

        $user = ($_SESSION['sesion']);
        $datos['user'] = $user;

        $vista = TwigView::getTwig();
        echo $vista->render('editardoPaciente.html.twig',$datos);      
  
     }
  }


  public function pacienteGuardar($id)
  {

       $partido = ReferenciasRepository::getPartidoId(($_POST["partido"]));
       $region = $partido["region_sanitaria_id"];  
     
       /* Leer las variables */
       $respuesta = PacienteRepository::getInstance()->guardarPaciente($id,
                                                       ($_POST["nombre"]),
                                                       ($_POST["apellido"]),
                                                       ($_POST["fechaNacimineto"]),
                                                       ($_POST["lugarNacimineto"]),
                                                       ($_POST["partido"]),
                                                        $region,
                                                       ($_POST["localidad"]),
                                                       ($_POST["domicilio"]),
                                                       ($_POST["genero"]),
                                                       ($_POST["tieneDoc"]),
                                                       ($_POST["tipoDocumento"]),
                                                       ($_POST["nroDocumento"]),
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
           case 8: #Campos numericos invalidos 
              $mensaje = new Mensaje("E","Error","Campos Numericos invalidos");
              if ( $page == "C" ) { $this->$metodo($mensaje); } else  { $this->$metodo($id,$mensaje); }
           break;
           case 9: #Campos numericos invalidos 
              $mensaje = new Mensaje("E","Error","Campo Numerico demasiado grandes");
              if ( $page == "C" ) { $this->$metodo($mensaje); } else  { $this->$metodo($id,$mensaje); }
           break;
        }  
  }

  public function instanciarPost() {

        if ((!(isset($_POST["nombre"]))) || (empty(($_POST["nombre"]))) )
        { $_POST["nombre"] = ""; }    

        if ((!(isset($_POST["apellido"]))) || (empty(($_POST["apellido"]))) )
        { $_POST["apellido"] = ""; }    
        
        if ((!(isset($_POST["tipoDocumento"]))) || (empty(($_POST["tipoDocumento"]))) ) 
        { $_POST["tipoDocumento"] = ""; }    
        
        if ((!(isset($_POST["fechaNacimineto"]))) || (empty(($_POST["fechaNacimineto"]))) )
        { $_POST['fechaNacimineto'] = ""; }
        
        if ((!(isset($_POST["partido"]))) || (empty(($_POST["partido"]))) )
        { $_POST["partido"] = ""; }   
        
        if ((!(isset($_POST["regionSanitaria"]))) || (empty(($_POST["regionSanitaria"]))) )
        { $_POST['regionSanitaria'] = ""; }
        
        if ((!(isset($_POST["localidad"]))) || (empty(($_POST["localidad"]))) )
        { $_POST["localidad"] = ""; }
        
        if ((!(isset($_POST["domicilio"]))) || (empty(($_POST["domicilio"]))) )
        { $_POST["domicilio"] = ""; }
        
        if ((!(isset($_POST["genero"]))) || (empty(($_POST["genero"]))) )
        { $_POST["genero"] = ""; }

        if ((!(isset($_POST["tieneDoc"]))) || (empty(($_POST["tieneDoc"]))) )
        { $_POST["tieneDoc"] = ""; }

        if ((!(isset($_POST["tipoDocumento"]))) || (empty(($_POST["tipoDocumento"]))) )
        { $_POST["tipoDocumento"] = 0; }       
        
        if ((!(isset($_POST["nroDocumento"]))) || (empty(($_POST["nroDocumento"]))) )
        { $_POST["nroDocumento"] = 0; } 

        if ((!(isset($_POST["nroHistClinica"]))) || (empty(($_POST["nroHistClinica"]))) ) 
        { $_POST["nroHistClinica"] = 0; } 

        if ((!(isset($_POST["nroCarpeta"]))) || (empty(($_POST["nroCarpeta"]))) )
        { $_POST["nroCarpeta"] = 0; } 
        
        if ((!(isset($_POST["telefono"]))) || (empty(($_POST["telefono"]))) )
        { $_POST["telefono"] = ""; } 

        if ((!(isset($_POST["obraSocial"]))) || (empty(($_POST["obraSocial"]))) )
         { $_POST["obraSocial"] = ""; } 
       
  }

  public function datosDePantalla()
  {

        # Levantar datos para mostrar en pantalla  correspondiente paciente
        $em = DoctrineRepository::getConnection(); 

        //$partidoRepository = $em->getRepository('Partido');
        //$datos['partidos'] = $partidoRepository->findAll();

        //$obraSocialRepository = $em->getRepository('ObraSocial'); 
        //$datos['obraSociales'] = $obraSocialRepository->findAll();

        //$docuRepository = $em->getRepository('TipoDocumento'); 
        //$datos['documentos'] = $docuRepository->findAll();

        $datos['partidos'] = ReferenciasRepository::getPartido();

        $generoRepository = $em->getRepository('Genero');
        $datos['generos'] = $generoRepository->findAll();

        $datos['obraSociales'] = ReferenciasRepository::getObraSocial();

        $datos['documentos'] = ReferenciasRepository::getTipoDocumento();

        return $datos; 
  }

} # FIN DE CLASE