<?php

include_once "/model/InstitucionesRepository.php";

$update = json_decode(file_get_contents('php://input') , $returnArray);

if (isset ($update['message'])){
	$cmd = $update['message']['text'];
	$chat_id = $update['message']['chat']['id'];
	$name = $update['message']['from']['first_name'];
}

/*$comando=explode(':',$cmd);

if (sizeof($comando)>1){
    $cmd=$comando[0];
}*/

// Comandos

$msg='';

switch ($cmd) {
    case '/start':
        $msg = 'Hola '.$name.PHP_EOL.'¿Como puedo ayudarte? Puedes ver una lista de las opciones disponibles con el comando /help';
        break;
 
    case '/help':
        $msg = 'Los comandos disponibles son estos: '.PHP_EOL.'/start Inicializa el bot'.PHP_EOL.'/instituciones Muestra las instituciones disponibles.'.PHP_EOL.'/instituciones-region-sanitaria: {region-sanitaria​​} Muestra un listado de Instituciones a partir de una la región sanitaria indicada.'.PHP_EOL.'/help Muestra los comandos disponibles (Los que te estoy mostrando ahora).';
        break;
 
    case '/instituciones':
        $msg = 'Las instituciones disponibles son: '.PHP_EOL;
        $instituciones = InstitucionesRepository::getInstitucion();
        foreach ($instituciones as $institucion){
            $msg = $msg.$institucion['nombre']."\n ";
        }
        break;

    case '/instituciones-region-sanitaria:':
        $msg = 'Las instituciones disponibles para la region sanitaria indicada son: '.PHP_EOL;
        $instituciones = InstitucionesRepository::getInstitucionRegionId($comando[1]);
        foreach ($instituciones as $institucion){
            $msg = $msg.$institucion['nombre'].PHP_EOL;
        }
        break;
 
    case '/info':
        $msg  = json_encode($update);
        break;
 
    default:
        $msg  = 'Lo siento '.$update['message']['from']['first_name'].', pero ['.$cmd.'] no es un comando válido.'. PHP_EOL.'Prueba /help para ver la lista de comandos disponibles';
        break;
}

//Realizamos el envío
$url = 'https://api.telegram.org/bot794469660:AAFzyw5Ue3NfYqwtE15_H5F0ba2NDPyoKs0/sendMessage?text='.$msg.'&chat_id='.$chat_id;
file_get_contents($url);
?>