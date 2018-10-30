<?php

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once "vendor/autoload.php";

// Create a simple "default" Doctrine ORM configuration for Annotations
$isDevMode = true;
$config = Setup::createAnnotationMetadataConfiguration(array (__DIR__."/../model/class"), true, null, null, false); /* (Array Directorios, Boolean ModoDesarrollador, null, null, Boolean SimpleAnnotationReader) 
Se setea de esta manera para crear las Entidades y los getters/setters

*/


// database configuration parameters
$conn = array(
    'driver' => 'pdo_mysql',
    'user' => 'grupo11',
    'password' => 'ZDc1MjY5MTBlNjQ2',
    'dbname' => 'grupo11',
);





// obtaining the entity manager
$entityManager = EntityManager::create($conn, $config);

?>