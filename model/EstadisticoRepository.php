<?php 
 /**
  * 
  */
 class EstadisticoRepository extends DoctrineRepository
 {
 	
 	private static $instance;

    public static function getInstance() {

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct() {
        
    }



  public function estadisticaMotivo(){
  
              $pdo = PdoRepository::getInstance()->getConnection();

                $query = $pdo->prepare("SELECT paciente.nombre, paciente.apellido , consulta.fecha, motivo_consulta.nombre , consulta.diagnostico
FROM consulta  JOIN motivo_consulta
ON consulta.motivo_id = motivo_consulta.id
JOIN paciente
ON consulta.paciente_id= paciente.id
ORDER BY paciente.apellido
                        ");
              $query-> execute();
               $resultado = $query->fetchAll();
               return $resultado;
        

    }



    public function estadisticaPacienteXGenero(){
  
              $pdo = PdoRepository::getInstance()->getConnection();

                $query = $pdo->prepare("SELECT COUNT(paciente.id) as CANTIDAD, genero.nombre
            FROM consulta JOIN paciente
                        ON consulta.paciente_id = paciente.id
                        JOIN genero
                        ON paciente.genero_id = genero.id
                      GROUP BY genero.nombre
                        ");
              $query-> execute();
               $resultado = $query->fetchAll();
               return $resultado;
        

    }

 public function estadisticaLocalidad(){

  $pdo = PdoRepository::getInstance()->getConnection();

                $query = $pdo->prepare("SELECT paciente.nombre, paciente.apellido , consulta.fecha, localidad.nombre , consulta.diagnostico
         FROM consulta  JOIN institucion
         ON consulta.derivacion_id = institucion.id
            JOIN region_sanitaria
          ON institucion.region_sanitaria_id = region_sanitaria.id
             JOIN partido
           ON partido.region_sanitaria_id = region_sanitaria.id
         JOIN localidad
              ON localidad.partido_id = partido.id
          JOIN paciente
          ON consulta.paciente_id = paciente.id
ORDER BY paciente.apellido
                        ");
              $query-> execute();
               $resultado = $query->fetchAll();
               return $resultado;

 }



    public function estadisticaGenero(){


      $pdo = PdoRepository::getInstance()->getConnection();

                $query = $pdo->prepare("SELECT paciente.nombre, paciente.apellido , consulta.fecha, genero.nombre , consulta.diagnostico
FROM consulta JOIN paciente
ON consulta.paciente_id= paciente.id
JOIN genero
ON paciente.genero_id = genero.id
ORDER BY paciente.apellido
                        ");
              $query-> execute();
               $resultado = $query->fetchAll();
               return $resultado;


    }


    public function estadisticaConsultaXMotivo(){
            $pdo = PdoRepository::getInstance()->getConnection();

                $query = $pdo->prepare("SELECT COUNT(consulta.id) as CANTIDAD, motivo_consulta.nombre
                        FROM consulta  JOIN motivo_consulta
                    ON consulta.motivo_id = motivo_consulta.id
                    GROUP BY motivo_consulta.nombre
                        ");
                $query-> execute();
               $resultado = $query->fetchAll();
               return $resultado;


    }

    public function estadisticaConsultaXLocalidad(){
      $pdo = PdoRepository::getInstance()->getConnection();

        $query = $pdo->prepare("SELECT COUNT(consulta.id) as CANTIDAD, localidad.nombre
         FROM consulta  JOIN institucion
         ON consulta.derivacion_id = institucion.id
            JOIN region_sanitaria
          ON institucion.region_sanitaria_id = region_sanitaria.id
             JOIN partido
           ON partido.region_sanitaria_id = region_sanitaria.id
         JOIN localidad
              ON localidad.partido_id = partido.id
            GROUP BY localidad.nombre");
              $query-> execute();
               $resultado = $query->fetchAll();
               return $resultado;


    }





 }




 


 ?>