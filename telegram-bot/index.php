<?php

include_once "/model/InstitucionesRepository.php";

$returnArray = true;
$update = json_decode(file_get_contents('php://input') , $returnArray);
$cmd = $update['message']['text'];
$chat_id = $update['message']['chat']['id'];
$name = $update['message']['from']['first_name'];


/*$comando=explode(':',$cmd);

if (sizeof($comando)>1){
    $cmd=$comando[0];
}*/

// Comandos

$msg='';

switch ($cmd) {
    case '/start':
        $msg = 'Hola '.$name.'¿Como puedo ayudarte? Puedes ver una lista de las opciones disponibles con el comando /help';
        break;
 
    case '/help':
        $msg = 'Los comandos disponibles son estos: /start Inicializa el bot. /instituciones Muestra las instituciones disponibles. /instituciones-region-sanitaria: {region-sanitaria​​} Muestra un listado de Instituciones a partir de una la región sanitaria indicada. /help Muestra los comandos disponibles (Los que te estoy mostrando ahora).';
        break;
 
    case '/instituciones':
        $msg = "Las instituciones disponibles son: \n";
        $instituciones = InstitucionesRepository::getInstitucion();
        foreach ($instituciones as $institucion){
            $msg = $msg.$institucion['nombre']."\n";
        }
        break;

    case '/instituciones-region-sanitaria:':
        $msg = "Las instituciones disponibles para la region sanitaria indicada son: \n";
        $instituciones = InstitucionesRepository::getInstitucionRegionId($comando[1]);
        foreach ($instituciones as $institucion){
            $msg = $msg.$institucion['nombre']."\n";
        }
        break;
 
    default:
        $msg  = 'Lo siento '.$update['message']['from']['first_name'].', pero ['.$cmd.'] no es un comando válido. Prueba /help para ver la lista de comandos disponibles';
        break;
}

//Realizamos el envío
$url = 'https://api.telegram.org/bot794469660:AAFzyw5Ue3NfYqwtE15_H5F0ba2NDPyoKs0/sendMessage?text='.$msg.'&chat_id='.$chat_id;
file_get_contents($url);
?>