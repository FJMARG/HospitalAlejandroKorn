<?php


class ConsultaRepository extends DoctrineRepository 
{
	
	private static $instance;

    public static function getInstance() {

        if (!isset(self::$instance)) 
        { 
        	self::$instance = new self(); 
        }

        return self::$instance;
    }


    public function listarTodasConsultas()
    {
        
        $em = DoctrineRepository::getConnection(); 
		    $registros = $em->getRepository('Consulta')->findBy( array(), array('fecha' => 'DESC') );
    	  return $registros;

    } 
   
   public function listarConsultasPaciente($id)
   {
        filter_var($id, FILTER_SANITIZE_SPECIAL_CHARS);

        $em = DoctrineRepository::getConnection();
        $registros = $em->getRepository('Consulta')->findBy( array('paciente' => $id), array('fecha' => 'DESC') );
        return $registros;

   }

   public function crearConsulta($id,
							      $fechaConsulta,
							      $motivo,
							      $articulacion,
							      $internacion,
							      $diagnostico,
							      $observaciones,
							      $farmacologico,
							      $acompa,
							      $institucion)
    {

       // Sanitizar parametros de entrada
       filter_var($id, FILTER_SANITIZE_SPECIAL_CHARS); 
       filter_var($fechaConsulta, FILTER_SANITIZE_SPECIAL_CHARS); 
       filter_var($motivo, FILTER_SANITIZE_SPECIAL_CHARS); 
       filter_var($articulacion, FILTER_SANITIZE_SPECIAL_CHARS); 
       filter_var($internacion, FILTER_SANITIZE_SPECIAL_CHARS); 
       filter_var($diagnostico, FILTER_SANITIZE_SPECIAL_CHARS);
       filter_var($observaciones, FILTER_SANITIZE_SPECIAL_CHARS); 
       filter_var($farmacologico, FILTER_SANITIZE_SPECIAL_CHARS); 
       filter_var($acompa, FILTER_SANITIZE_SPECIAL_CHARS); 
       filter_var($institucion, FILTER_SANITIZE_SPECIAL_CHARS); 

       // Verificar que los valores obligatorios estan completos
       if ((empty($id)))
       {
          return "Valor del Paciente vacio";
       }

       if ((empty($fechaConsulta)))
       {
          return "Valor para Fecha de Consulta vacio";
         
       }

       if ((empty($motivo)))
       {
          return "El motivo de la consulta no puede ser vacio";
       }

       if (!(is_numeric($internacion)))
       {
          return "La informacion referente a la internación no puede ser vacia";
       }

       if ((empty($diagnostico)))
       {
          return "diagnostico del paciente no puede ser vacio";   
       }


       // buscar datos a completar
       $em = DoctrineRepository::getConnection(); 
       $cl_paciente    = $em->getRepository('Paciente')->find($id);
       $cl_motivo      = $em->getRepository('MotivoConsulta')->find($motivo);
       $cl_derivacion  = $em->getRepository('Institucion')->find($institucion); 
       $cl_trata_farma = $em->getRepository('TratamientoFarmacologico')->find($farmacologico); 
	     $cl_acompa      = $em->getRepository('Acompanamiento')->find($acompa); 

       // Validar la existencia del paciente 
	     if ($cl_paciente == NULL)
	     {
	   	    return "Paciente NO registrado en el sistema"; 
	     }


	    // Guardar datos
	    $consulta = new Consulta();

	    $consulta->setFecha(date_create($fechaConsulta));
	    $consulta->setArticulacionConInstituciones($articulacion);
	    $consulta->setInternacion($internacion);
	    $consulta->setDiagnostico($diagnostico);
	    $consulta->setObservaciones($observaciones);
	    $consulta->setAcompanamiento($cl_acompa);
	    $consulta->setDerivacion($cl_derivacion);
	    $consulta->setMotivo($cl_motivo);
	    $consulta->setPaciente($cl_paciente);
	    $consulta->setTratamientoFarmacologico($cl_trata_farma);

	    // insertar datos
	    $em->persist($consulta);
      $em->flush();

      // return 0; # Se creo el paciente    
      return 0;
    
    }

    public function guardarConsulta($id_consulta,
                                    $id,
                                    $fechaConsulta,
                                    $motivo,
                                    $articulacion,
                                    $internacion,
                                    $diagnostico,
                                    $observaciones,
                                    $farmacologico,
                                    $acompa,
                                    $institucion)
    {
    
       // Sanitizar parametros de entrada
       filter_var($id_consulta, FILTER_SANITIZE_SPECIAL_CHARS); 
       filter_var($id, FILTER_SANITIZE_SPECIAL_CHARS); 
       filter_var($fechaConsulta, FILTER_SANITIZE_SPECIAL_CHARS); 
       filter_var($motivo, FILTER_SANITIZE_SPECIAL_CHARS); 
       filter_var($articulacion, FILTER_SANITIZE_SPECIAL_CHARS); 
       filter_var($internacion, FILTER_SANITIZE_SPECIAL_CHARS); 
       filter_var($diagnostico, FILTER_SANITIZE_SPECIAL_CHARS);
       filter_var($observaciones, FILTER_SANITIZE_SPECIAL_CHARS); 
       filter_var($farmacologico, FILTER_SANITIZE_SPECIAL_CHARS); 
       filter_var($acompa, FILTER_SANITIZE_SPECIAL_CHARS); 
       filter_var($institucion, FILTER_SANITIZE_SPECIAL_CHARS); 


       // validar que existe la consutla
       $em = DoctrineRepository::getConnection();
       
       # Verificar si el ID pasado por parametro es valido
       $consulta  = $em->getRepository('Consulta')->find($id_consulta);              
       if ($consulta == NULL )
       {
          # No es un ID VALIDO
          return "No se encontro de la consulta";  
       
       }

       // Verificar que los valores obligatorios estan completos
       if ((empty($id))) { return "Valor del Paciente vacio"; }

       if ((empty($fechaConsulta))) { return "Valor para Fecha de Consulta vacio"; }

       if ((empty($motivo))) { return "El motivo de la consulta no puede ser vacio"; }

       if (!(is_numeric($internacion))) { return "La informacion referente a la internación no puede ser vacia"; }

       if ((empty($diagnostico))) { return "diagnostico del paciente no puede ser vacio";  }


       // buscar datos a completar
       $cl_paciente    = $em->getRepository('Paciente')->find($id);
       $cl_motivo      = $em->getRepository('MotivoConsulta')->find($motivo);
       $cl_derivacion  = $em->getRepository('Institucion')->find($institucion); 
       $cl_trata_farma = $em->getRepository('TratamientoFarmacologico')->find($farmacologico); 
       $cl_acompa      = $em->getRepository('Acompanamiento')->find($acompa); 

       // Validar la existencia del paciente 
       if ($cl_paciente == NULL)
       {
          return "Paciente NO registrado en el sistema"; 
       }

       // guardar consulta
       $consulta->setFecha(date_create($fechaConsulta));
       $consulta->setArticulacionConInstituciones($articulacion);
       $consulta->setInternacion($internacion);
       $consulta->setDiagnostico($diagnostico);
       $consulta->setObservaciones($observaciones);
       $consulta->setAcompanamiento($cl_acompa);
       $consulta->setDerivacion($cl_derivacion);
       $consulta->setMotivo($cl_motivo);
       $consulta->setPaciente($cl_paciente);
       $consulta->setTratamientoFarmacologico($cl_trata_farma);

       # Actualizar datos  

       $em->persist($consulta); 
       $em->flush();

       return 0;

    }

    public function borrarConsulta($id)
    {  
       
       $em = DoctrineRepository::getConnection();

       # Verificar si el ID pasado por parametro es valido
       $consulta  = $em->getRepository('Consulta')->find($id);              
       if ($consulta == NULL )
       {
            # No es un ID VALIDO
            return "No se encontro el ID del paciente";  
       }
       else
       { 
           # Borramos la consulta
           $em->remove($consulta);
           $em->flush();

           return 0;  
       }
    }

    public function datosPantalla()
    {
       
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

          
         $institutosRepository = $em->getRepository('Institucion');
         $datos['instituciones'] = $institutosRepository->findAll();

         return $datos; 

    }
}