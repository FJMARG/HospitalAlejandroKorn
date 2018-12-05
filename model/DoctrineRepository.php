<?php

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once "./vendor/autoload.php";

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DoctrineRepository
 *
 * @author fede
 */

abstract class DoctrineRepository {
    
	const CONFIG = array('driver' => 'pdo_mysql', 'user' => 'grupo11', 'password' => 'ZDc1MjY5MTBlNjQ2', 'dbname' => 'grupo11');

	//const CONFIG = array('driver' => 'pdo_mysql', 'user' => 'root', 'password' => 'alumno', 'dbname' => 'proyecto2019');

    protected function getConnection(){
        $conn = Setup::createAnnotationMetadataConfiguration(array (__DIR__."../model/class"), true, null, null, false); /* (Array Directorios, Boolean ModoDesarrollador, null, null, Boolean SimpleAnnotationReader) */
        return $this::createEntityManager($conn);
    }

    private function createEntityManager($conn){
  		$config=self::CONFIG;
        return EntityManager::create($config, $conn);
    }
    
}