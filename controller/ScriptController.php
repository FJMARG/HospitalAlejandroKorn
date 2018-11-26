<?php
ini_set('display_errors',1);
error_reporting(E_ALL);

require_once ('../model/buscarNombrePaciente.php');

if (!isset($_GET['act'])){
     $_GET['act']='';
}

if (!isset($_GET['valor'])){
     $_GET['valor']='';
}

$opcion = ($_GET['act']);


switch ($opcion) {
     case 'datosAutocomplete':
        $valor = ($_GET['valor']); 
        BuscarNombrePaciente::getInstance()->BuscarDatosAutocomplete($valor); 
     break;
     case 'mapaInstitucion':
        $valor = ($_GET['valor']); 
        BuscarNombrePaciente::getInstance()->buscarMapa($valor); 
     break;
     case 'cantidadConsultas':
        $id = ($_GET['valor']);  
        BuscarNombrePaciente::getInstance()->buscarConsultas($id);
     break;
     default:
     
     break;
}






