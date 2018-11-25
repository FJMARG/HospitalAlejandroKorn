<?php

/**
 * Description of PacienteRepository
 *
 * @author pablo
 */
class PacienteRepository extends DoctrineRepository {

    private static $instance;

    public static function getInstance() {

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct() {
        
    }

    public function listAll() {
        $entityManager = $this->getConnection();
        $pacienteRepository = $entityManager->getRepository('Paciente');
        $pacientes = $pacienteRepository->findAll();
        return $pacientes;
    }

    public function recuperarPacientes($nombre, $apellido, $tipoDni, $nroDni, $nro_hist_clinica)
    {

        $array = array();   
        # Filtro para nombre 
        if (!empty($nombre)){ $array["nombre"] = $nombre; }
        # Filtro para  apellido
        if (!empty($apellido)){ $array["apellido"] = $apellido; }
        # Filtro para tipo de documento
        if (!empty($tipoDni)){ $array["tipoDoc"] = $tipoDni; }
        # Filtro para numero de documento
        if (strlen($nroDni) != 0){ $array["numero"] = $nroDni; }
        # Filtro para Historia Clinica
        if (strlen($nro_hist_clinica) != 0){ $array["nroHistoriaClinica"] = $nro_hist_clinica; }

        $em  = DoctrineRepository::getConnection();
        $articulos = $em->getRepository('Paciente')->findBy($array);

        return $articulos;

    }

    public function pacienteSinRegistro()
    {
        $em = DoctrineRepository::getConnection();
        $dql = "SELECT a from Paciente a where a.tipoDoc is NULL";    
        $query = $em->createQuery($dql);
        $Pacientes = $query->getResult();
        return $Pacientes;

    }

    public function crearPaciente($nombre, 
                                  $apellido, 
                                  $fechaNac, 
                                  $lugarNac,
                                  $partido,
                                  $regionSanitaria,
                                  $localidad,
                                  $domicilio,
                                  $genero,
                                  $tieneDoc,
                                  $tipoDoc,
                                  $nroDoc,
                                  $nro_hist_clinica,
                                  $nro_carpeta,
                                  $telefono,
                                  $obraSocial)
    {
        # preparo filtros
        # Validar los campos que son obligatorios
        if ( (empty($nombre)) || (empty($apellido)) || (empty($fechaNac)) ||
             (empty($domicilio)) || (empty($genero)) )
        { 
            return 1; # Campos obligatorios sin completar
        }  

        # Validar logitud de campos string para campos nombre, apellido, domicilio, 
        if ( (strlen($nombre) > 20 ) || (strlen($apellido) > 20 ) || (strlen($domicilio) > 70 ) || (strlen($telefono) > 70 ) ) 
        {
           # Un campo es demasiado largo
           return 7;  
        } 

        # Validar campos numericos 

        if ( (!(is_numeric($nroDoc))) || (!is_numeric($nro_hist_clinica)) || (!is_numeric($nro_carpeta)) ){
           return 8; # Campo numericos invalidos 
        } 

        # Validar sus rangos
        if ( ( $nroDoc < 0 && $nroDoc > 9999999999999 )
        || ( $nro_hist_clinica < 0 && $nro_hist_clinica > 999999 )
        || ( $nro_carpeta < 0 && $nro_carpeta > 99999 ) ) {
           return 9; # Desborde de numeros   
        } 

  
        if ($tieneDoc == 1)  # Validad que tenga documento 
        {
         
             if ( (empty($tipoDoc)) || (empty($nroDoc)) ) { 
                  # Verificar que estan cargados
                  # "Deben Completar Tipo y Numero de Documento";
                  return 2; # Campos obligatorios sin completar
             } 
             else  {

                # Validar que el Nro y Tipo de Documento ya no estan registrados
                # Obtener ID de documento tipoDoc numero

                $em = DoctrineRepository::getConnection(); 
                $array = $em->getRepository('Paciente')->findBy((array('tipoDoc' => $tipoDoc,
                                                                        'numero' => $nroDoc )));
                if (!empty($array)) {
                   # Ya hiciste un Paciente registrado con ese Tipo y Nro Documento
                   return 3; 
                }

             } 

        } elseif ($tieneDoc == 0) {

            # autocompleto los campos con valores nulos
            $tipoDoc = 0;
            $nroDoc  = 0; 
        }

        elseif ($tieneDoc != 0) {
            return 1; # Campos obligatorios sin completar     
        }

        #Validar Historia Clinica
        if (!empty($nro_hist_clinica))
        {
             $em = DoctrineRepository::getConnection(); 
             $array = $em->getRepository('Paciente')->findBy((array('nroHistoriaClinica' => $nro_hist_clinica)));

            if (!empty($array)) {    
               # Ya existe un paciente con la historia clinica ingresada
               return 4;    
            }    
        }

        # Verificamos los objetos a instanciar
                    
        $em = DoctrineRepository::getConnection(); 
        
        # Buscar objeto Tipo de Documento           
        $tipoDocumento       = $em->getRepository('TipoDocumento')->find($tipoDoc);
        # Buscar objeto Genero
        $tipoGenero          = $em->getRepository('Genero')->find($genero);
        # Buscar objeto Localidad
        $tipoLocalidad       = $em->getRepository('Localidad')->find($localidad);
        # Buscar objeto Obra Social
        $tipoSocial          = $em->getRepository('ObraSocial')->find($obraSocial);
        # Buscar objeto Region Sanitaria
        $arrayRegion['nombre'] = $regionSanitaria;   
        $tipoRegion          = $em->getRepository('RegionSanitaria')->findOneBy($arrayRegion);

        $paciente = new Paciente();
              
        # Setar los parametros para la creacion
        $paciente->setNombre($nombre);
        $paciente->setApellido($apellido);
        $paciente->setFechaNac(date_create($fechaNac));
        $paciente->setLugarNac($lugarNac);
        $paciente->setDomicilio($domicilio);
        $paciente->setTieneDocumento($tieneDoc);
        $paciente->setTipoDoc($tipoDocumento);
        $paciente->setNumero($nroDoc);
        $paciente->setTel($telefono);
        $paciente->setNroHistoriaClinica($nro_hist_clinica);
        $paciente->setNroCarpeta($nro_carpeta);
        $paciente->setGenero($tipoGenero);
        $paciente->setLocalidad($tipoLocalidad);;
        $paciente->setObraSocial($tipoSocial);
        $paciente->setRegionSanitaria($tipoRegion);

        $em->persist($paciente);
        $em->flush();

        return 0; # Se creo el paciente    
    }

    public function borrarPaciente($id)
    {

       $em = DoctrineRepository::getConnection(); 

       # Verificar si el ID pasado por parametro es valido
       $paciente  = $em->getRepository('Paciente')->find($id);              
       if ($paciente == NULL )
       {
           # No es un ID VALIDO
           return 1;  
       }

       $consulta = ConsultaRepository::getInstance()->listarConsultasPaciente($id);
        
       if ( $consulta != NULL )
       {
           # tiene consultas activas
           return 2;  
       }

       # Borramos Paciente
       $em->remove($paciente);
       $em->flush();

       return 0;  

    }


    public function guardarPaciente($id,
                                    $nombre, 
                                    $apellido, 
                                    $fechaNac, 
                                    $lugarNac,
                                    $partido,
                                    $regionSanitaria,
                                    $localidad,
                                    $domicilio,
                                    $genero,
                                    $tieneDoc,
                                    $tipoDoc,
                                    $nroDoc,
                                    $nro_hist_clinica,
                                    $nro_carpeta,
                                    $telefono,
                                    $obraSocial)  {
      
      $em = DoctrineRepository::getConnection(); 

      # Verificar si el ID pasado por parametro es valido
      $paciente  = $em->getRepository('Paciente')->find($id);              

      if ($paciente == NULL )
      {
         # No es un ID VALIDO
         return 5;  
      }

      # Validar los campos que son obligatorios
      if ( (empty($nombre)) || (empty($apellido)) || (empty($fechaNac)) || (empty($domicilio)) || (empty($genero)) )
      {  
        return 1; # Campos obligatorios sin completar
      }  

      # Validar logitudes para campos nombre, apellido, domicilio, 
      if ( (strlen($nombre) > 20 ) || (strlen($apellido) > 20 ) || (strlen($domicilio) > 70 ) || (strlen($telefono) > 70 ) ) 
      {
        # Un campo es demasiado largo
        return 7;  
      } 

      # Validar campos numericos 
      if ( (!(is_numeric($nroDoc))) || (!is_numeric($nro_hist_clinica)) || (!is_numeric($nro_carpeta)) )
      {
           return 8; # Campo numericos invalidos 
      } 

      # Validar sus rangos
      if ( ($nroDoc < 0 && $nroDoc > 9999999999999 )
      || ( $nro_hist_clinica < 0 && $nro_hist_clinica > 999999 )
      || ( $nro_carpeta < 0 && $nro_carpeta > 99999 ) ) 
      {
           return 9; # Desborde de numeros   
      } 

      if ($tieneDoc == 1)  # Validad que tenga documento 
      {
         
          if ( (empty($tipoDoc)) || (empty($nroDoc)) ) # Verificar que estan cargados
          {
                # "Deben Completar Tipo y Numero de Documento";
                return 2; # Campos obligatorios sin completar
          } 
          else {
                
                # Validar que el Nro y Tipo de Documento ya no estan registrados
                # Obtener ID de documento tipoDoc numero

                $array = $em->getRepository('Paciente')->findBy((array('tipoDoc' => $tipoDoc,
                                                                        'numero' => $nroDoc )));
                if (!empty($array)) 
                {  

                    # Recupero pacientes para validar que todavia no esta registrado 
                    foreach ($array as $pac) {
                        
                       if ( $pac->getId() != $id )
                       { 
                           # Ya hiciste un Paciente registrado con ese Tipo y Nro Documento
                           return 3;
                       } 
                   }

                }

            } 
      }
      elseif ($tieneDoc == 0) {

            # autocompleto los campos con valores nulos
            $tipoDoc = 0;
            $nroDoc  = 0; 
      }
      elseif ($tieneDoc != 0) 
      {    
         return 1; # Campos obligatorios sin completar
      } 

      # Sin documento no puede tener asociando un nro de documento  
      if (( $tipoDoc == 0 ) && ( $nroDoc != 0 ) ) { return 6; } 

      #Validar Historia Clinica
      if (!empty($nro_hist_clinica))
      {
          $array = $em->getRepository('Paciente')->findBy((array('nroHistoriaClinica' => $nro_hist_clinica)));
          if (!empty($array))
          {    

               # Recupero pacientes para 
               foreach ($array as $pac) 
               {
                        
                   if ( $pac->getId() != $id )
                   { 
                        # Ya existe un paciente con la historia clinica ingresada
                        return 4;  
                   } 
               }

          }    
      }

      # Verificamos los objetos a instanciar
                            
      # Buscar objeto Tipo de Documento           
      $tipoDocumento         = $em->getRepository('TipoDocumento')->find($tipoDoc);
      # Buscar objeto Genero
      $tipoGenero            = $em->getRepository('Genero')->find($genero);
      # Buscar objeto Localidad
      $tipoLocalidad         = $em->getRepository('Localidad')->find($localidad);
      # Buscar objeto Obra Social
      $tipoSocial            = $em->getRepository('ObraSocial')->find($obraSocial);
      # Buscar objeto Region Sanitaria
      $arrayRegion['nombre'] = $regionSanitaria;   
      $tipoRegion            = $em->getRepository('RegionSanitaria')->findOneBy($arrayRegion);

      # Si pasa todas la validacion procedemos 
      # Setar los parametros para la creacion
      $paciente->setNombre($nombre);
      $paciente->setApellido($apellido);
      $paciente->setFechaNac(date_create($fechaNac));
      $paciente->setLugarNac($lugarNac);
      $paciente->setDomicilio($domicilio);
      $paciente->setTieneDocumento($tieneDoc);
      $paciente->setTipoDoc($tipoDocumento);
      $paciente->setNumero($nroDoc);
      $paciente->setTel($telefono);
      $paciente->setNroHistoriaClinica($nro_hist_clinica);
      $paciente->setNroCarpeta($nro_carpeta);
      $paciente->setGenero($tipoGenero);
      $paciente->setLocalidad($tipoLocalidad);;
      $paciente->setObraSocial($tipoSocial);
      $paciente->setRegionSanitaria($tipoRegion);

      # Actualizar datos  
      $em->persist($paciente); 
      $em->flush();

        return 0;

  }

} # FIN CLASE 


