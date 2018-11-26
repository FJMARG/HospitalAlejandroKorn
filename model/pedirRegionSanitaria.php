<?php

require_once ('ReferenciasRepository.php');


$valor = ($_GET["id"]);

# Localidades
$localidad = ReferenciasRepository::getLocalidadPartido($valor);

foreach ($localidad as $row) {
	$nro = $row['id'];
    $localidades[$nro] = $row['nombre'];
}

# Region Sanitaria
$sanitaria = ReferenciasRepository::getRegionSanitariaId($valor);
$region[1] = $sanitaria['nombre'];

# Devolver datos Region y Localidades
//header('Content-type: application/json; charset=utf-8');
echo json_encode(array('region' => $region,'localidad' => $localidades), JSON_FORCE_OBJECT);

