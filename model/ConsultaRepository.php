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

}