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
              $this->verPacienteEditar($id);
           break;    
           case 'guardar':
              # Pantalla de edicion
              #$id= ($_GET['id']);
              #$this->pacienteBorrar($id);
           break;       
           case 'confirmacionAlta':
              # Confirmar la creacion de paciente confirmacionBaja
              echo TwigView::getTwig()->render('confirmacion_alta.html.twig');
              break; 
           case 'confirmacionBaja':
              # Confirmar la creacion de paciente 
              echo TwigView::getTwig()->render('confirmacion_baja.html.twig');
              break;    
           default:
              # code...
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

       /* 
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
        $Pacientes = PacienteRepository::getInstance()->listAll();
        $vista = TwigView::getTwig();
        echo $vista->render('listaPacientes.html.twig',array('pacientes' => $Pacientes));
    }

    public function verResultadoPaciente(){

          $nombre                = ($_POST["nombrePaciente"]);
          $apellido              = ($_POST["apellidoPaciente"]);
          $tipoDocumento         = ($_POST["tipoDocumento"]);
          $numeroDocumento       = ($_POST["numeroDocumento"]);
          $numeroHistoriaClinica = ($_POST["numeroHistoriaClinica"]); 
          
          # Verificar que al menos un campo este cargado
          if ((empty($nombre)) 
          &&  (empty($apellido)) 
          &&  (empty($tipoDocumento))
          &&  (empty($numeroDocumento))
          &&  (empty($numeroHistoriaClinica)) )  
          {
              # Para el caso de que lleguen parametros vacios
              $mensaje = new Mensaje("E","Error","Debe completar al menos un parametro de busqueda");
              $this->buscarPaciente($mensaje);
          }
          
          else

          {
               
            $Pacientes = PacienteRepository::getInstance()->recuperarPacientes($nombre,$apellido,$tipoDocumento,$numeroDocumento,$numeroHistoriaClinica);

            $vista = TwigView::getTwig();
              echo $vista->render('listaPacientes.html.twig',array('pacientes' => $Pacientes));
          }
                                            
          /*
          $arrayPacientes = PacienteRepository::getInstance()->recuperarPacientes($name, $apellido, $nro_clinica);
          $vista = TwigView::getTwig();
          echo $vista->render('listaPacientes.html.twig', array('pacientes' => $arrayPacientes)); */
    }

     
    public function crearPaciente($mensaje) {
          
          # Mensaje de error del servidor
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

    public function insertarPaciente()  
    {

       # Realizar el commit del paciente 

      /* Valdiar si los campos estan o no desabilidatos para Tipo documento */
      if (isset($_POST['tipoDocumento'])){ $tipoDocumento = ($_POST['tipoDocumento']); }       
      else { $tipoDocumento = '99'; /*SIN DOCUMENTO*/ } 
      
      /* Valdiar si los campos estan o no desabilidatosd para Nro documento*/
      if (isset($_POST['nroDocumento'])){ $nroDocumento = ($_POST['nroDocumento']); }       
      else { $nroDocumento = 0;}
        
       /* Leer las variables */
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

       # Mostrar la alerta del en pantalla
       switch ($respuesta) {
           case 0: # Es correcto voy a la pantalla de correcto
                header('Location: ./index.php?categoria=paciente&accion=informar_alta');
           break;
           case 1: # Error en parametros obligatorios
              $mensaje = new Mensaje("E","Error","Campos Obligatorios Vacios");
              $this->crearPaciente($mensaje);
           break;
           case 2: #Deben Completar Tipo y Numero de Documento
              $mensaje = new Mensaje("E","Error","El Tipo y Numero de documento no estan completos");
              $this->crearPaciente($mensaje);
           break; 
           case 3: #Ya hiciste un Paciente registrado con ese Tipo y Nro Documento
              $mensaje = new Mensaje("E","Error","Ya hiciste un Paciente registrado con ese Tipo y Nro Documento");
              $this->crearPaciente($mensaje);
           break;
           case 4: #Ya existe un paciente con la historia clinica ingresada
              $mensaje = new Mensaje("E","Error","Ya existe un paciente con la historia clinica ingresada");
              $this->crearPaciente($mensaje);
           break;
       }                                          
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

    public function pacienteBorrar($id)
    {
      
       $respuesta = PacienteRepository::getInstance()->borrarPaciente($id);

       # Mostrar la alerta del en pantalla
       switch ($respuesta) {
           case 0: # Es correcto voy a la pantalla de correcto
                header('Location: ./index.php?categoria=paciente&accion=informar_baja');
           break;

       }
  }

  public function verPacienteEditar($id)
  {
      
    # Buscar los datos del paciente a mostrar
    $paciente = DoctrineRepository::getConnection()->getRepository('Paciente')->find($id);
    
    if ( $paciente != NULL )
    {
        $datos['paciente'] = $paciente;


        $vista = TwigView::getTwig();
        echo $vista->render('editardoPaciente.html.twig',$datos);      
    }
  }
   

}