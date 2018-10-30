<?php

$dsn = 'mysql:host=localhost;dbname=grupo11;charset=utf8';
$pdo = new PDO($dsn, 'grupo11', 'ZDc1MjY5MTBlNjQ2');


# Region
$query = $pdo->prepare("SELECT region_sanitaria.nombre 
						FROM region_sanitaria inner join partido 
						ON region_sanitaria.id = partido.region_sanitaria_id 
						where partido.id=?");

$query->execute(array($_GET["id"]));
for($i=1; $row = $query->fetch(); $i++)
{
   $region["$i"] = $row['nombre'];
}

# Localidades
$query2 = $pdo->prepare("SELECT * FROM localidad where partido_id=?");
$query2->execute(array($_GET["id"]));

while ($row = $query2->fetch()) 
{
   $localidades[$row['id']] = $row['nombre']; 	    
}

# Devolver datos Region y Localidades
header('Content-type: application/json; charset=utf-8');
echo json_encode(array('region' => $region,'localidad' => $localidades), JSON_FORCE_OBJECT);

