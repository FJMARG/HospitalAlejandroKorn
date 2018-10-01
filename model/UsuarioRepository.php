<?php

/**
 * Description of UsuarioRepository
 *
 * @author fede
 */
class UsuarioRepository extends DoctrineRepository {

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
        $usuarioRepository = $entityManager->getRepository('Usuario');
        $usuarios = $usuarioRepository->findAll();
        return $usuarios;
    }

}
