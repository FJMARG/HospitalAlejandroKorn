<?php
	require_once 'vendor/autoload.php';
	require_once 'controller/SlimController.php';
	require_once 'controller/FrontController.php';
	require_once 'controller/TwigController.php';
	require_once 'model/PDORepository.php';
	require_once 'model/InstitucionRepository.php';
	require_once 'model/PartidoRepository.php';
	require_once 'model/RegionSanitariaRepository.php';
	require_once 'model/TipoInstitucionRepository.php';

	SlimController::startAPI();

?>
