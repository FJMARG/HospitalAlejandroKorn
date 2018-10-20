<?php 

class ConfiguracionRepository extends DoctrineRepository
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


    public function recuperarConfiguracion(){

        $titulo = DoctrineRepository::getConnection()->getRepository('Configuracion')->find(1);
        $descripcion = DoctrineRepository::getConnection()->getRepository('Configuracion')->find(2);
        $mail = DoctrineRepository::getConnection()->getRepository('Configuracion')->find(3);
        $paginado = DoctrineRepository::getConnection()->getRepository('Configuracion')->find(4);
        $habilitado = DoctrineRepository::getConnection()->getRepository('Configuracion')->find(5);
        

        $datos['titulo'] = $titulo;
        $datos['descripcion'] = $descripcion;
        $datos['mail'] = $mail;
        $datos['paginado'] = $paginado;
        $datos['habilitado'] = $habilitado;

        return $datos;
    }


    public function updateConfiguracion ($id ,$variable , $valor){


        $em = DoctrineRepository::getConnection();
        $conf = $em->getRepository('Configuracion')->find($id);
        $conf -> setVariable($variable);
        $conf -> setValor($valor);
        
        $em->flush();

    }

}

 ?>