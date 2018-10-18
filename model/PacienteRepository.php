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
        if (!empty($nroDni)){ $array["numero"] = $nroDni; }
        # Filtro para Historia Clinica
        if (!empty($nro_hist_clinica)){ $array["nroHistoriaClinica"] = $nro_hist_clinica; }


        $em    = $this->getConnection();
        $articulos = $em->getRepository('Paciente')->findBy($array);

        return $articulos;

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
        # Validar los campos que son obligatorios
        if ( (empty($nombre)) || (empty($apellido)) || (empty($fechaNac)) ||
             (empty($domicilio)) || (empty($genero)) )
        { 
            return 1; # Campos obligatorios sin completar
        }  

  
        if ($tieneDoc == 1)  # Validad que tenga documento 
        {
         
            if ( (empty($tipoDoc)) || (empty($nroDoc)) ) # Verificar que estan cargados
            {
                # "Deben Completar Tipo y Numero de Documento";
                return 2; # Campos obligatorios sin completar
            } 
            else 
            
            {
                # Validar que el Nro y Tipo de Documento ya no estan registrados
                # Obtener ID de documento tipoDoc numero

                $em = DoctrineRepository::getConnection(); 
                $array = $em->getRepository('Paciente')->findBy((array('tipoDoc' => $tipoDoc,
                                                                        'numero' => $nroDoc )));
                if (!empty($array))
                {
                   # Ya hiciste un Paciente registrado con ese Tipo y Nro Documento
                   return 3; 
                }

            } 

        }
        elseif ($tieneDoc != 0) 
        {
           return 1; # Campos obligatorios sin completar     
        }

        #Validar Historia Clinica
        if (!empty($nro_hist_clinica))
        {
             $em = DoctrineRepository::getConnection(); 
             $array = $em->getRepository('Paciente')->findBy((array('nroHistoriaClinica' => $nro_hist_clinica)));

            if (!empty($array))
            {    
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

        $paciente->setApellido($nombre); 
        $paciente->setNombre($apellido);
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
        
       # Borramos Paciente
       $em->remove($paciente);
       $em->flush();

       return 0;  

    }
}


