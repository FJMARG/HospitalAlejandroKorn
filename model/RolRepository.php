<?php

/**
 * Description of RolRepository
 *
 * @author fede
 */
class RolRepository extends DoctrineRepository {

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
        $rolRepository = $entityManager->getRepository('Rol');
        $roles = $rolRepository->findAll();
        return $roles;
    }


}
