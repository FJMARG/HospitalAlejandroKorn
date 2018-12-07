<?php 

/**
 * 
 */
class PdoRepository extends DoctrineRepository
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



    public function getConnection(){

    	   //$dsn = 'mysql:host=localhost;dbname=proyecto2018;charset=utf8';
           //$pdo = new PDO($dsn, 'root', 'root');

              $dsn = 'mysql:host=localhost;dbname=grupo11;charset=utf8';
              $pdo = new PDO($dsn, 'grupo11', 'ZDc1MjY5MTBlNjQ2');
            
            return $pdo;
    }


}





 ?>