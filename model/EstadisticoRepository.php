<<?php 

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


    public function estadisticaPacienteXGenero(){
          $dsn = 'mysql:host=localhost;dbname=proyecto2018;charset=utf8';
              $pdo = new PDO($dsn, 'root', 'root');

                $query = $pdo->prepare("SELECT COUNT(paciente.id) as CANTIDAD, genero.nombre
                        FROM paciente  JOIN genero
                        ON paciente.genero_id = genero.id
                      GROUP BY genero.nombre
                        ");
              $query-> execute();
               $resultado = $query->fetchAll();
              return $resultado;



         /* $em = DoctrineRepository::getConnection();
          $qb = $em->createQueryBuilder();
          $result = $qb->select('COUNT(p.id)')
                        ->from('paciente', 'p')
                        ->innerJoin('p', 'genero', 'g', 'g.id = p.genero_id')
                          ->groupBy('g.nombre');

          return $result;*/
        

    }

    public function estadisticaConsultaXMotivo(){
        $dsn = 'mysql:host=localhost;dbname=proyecto2018;charset=utf8';
              $pdo = new PDO($dsn, 'root', 'root');

                $query = $pdo->prepare("SELECT COUNT(consulta.id) as CANTIDAD, motivo_consulta.nombre
  FROM consulta  JOIN motivo_consulta
    ON consulta.motivo_id = motivo_consulta.id
 GROUP BY motivo_consulta.nombre
                        ");
              $query-> execute();
               $resultado = $query->fetchAll();
              var_dump($resultado);


    }





 }




 


 ?>