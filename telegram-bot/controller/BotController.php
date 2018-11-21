<?php

    class BotController{
        public static function startBot(){
            $returnArray = true;
            $update = json_decode(file_get_contents('php://input') , $returnArray);
            $cmd = $update['message']['text'];
            $chat_id = $update['message']['chat']['id'];
            $name = $update['message']['from']['first_name'];

            $comando=explode(':',$cmd);

            if (sizeof($comando)>1){
                $cmd=$comando[0];
            }

            // Comandos que responde el bot

            $msg='';

            switch ($cmd) {
                case '/start':
                    $msg = 'Hola '.$name.'¿Que necesita? Si escribis /help te listare todos los comandos disponibles.';
                    break;
             
                case '/help':
                    $msg = 'Los comandos disponibles son los siguientes: /start Inicializa el bot. /instituciones Muestra las instituciones disponibles. /instituciones-region-sanitaria: {region-sanitaria​​} Muestra un listado de Instituciones a partir de una la región sanitaria indicada. /help Muestra los comandos disponibles (Los que te estoy mostrando ahora). Espero tu comando!!!';
                    break;
             
                case '/instituciones':
                    $msg = "Las instituciones disponibles son:";
                    $instituciones = InstitucionesRepository::getInstitucion();
                    foreach ($instituciones as $institucion){
                        $msg = $msg.$institucion['nombre']." - ";
                    }
                    break;

                case '/instituciones-region-sanitaria':
                    $msg = "Las instituciones disponibles para la region sanitaria ".$comando[1]." son: ";
                    $instituciones = InstitucionesRepository::getInstitucionRegionId($comando[1]);
                    foreach ($instituciones as $institucion){
                        $msg = $msg.$institucion['nombre']." - ";
                    }
                    break;
             
                default:
                    $msg  = 'Lo siento '.$update['message']['from']['first_name'].', pero no entiendo ['.$cmd.'], por lo que no puedo ayudarte con lo que me solicitas. Escribi el comando /help para ver la lista de comandos disponibles (los que entiendo).';
                    break;
            }

            //Envio de respuesta
            $url = 'https://api.telegram.org/bot794469660:AAFzyw5Ue3NfYqwtE15_H5F0ba2NDPyoKs0/sendMessage?text='.$msg.'&chat_id='.$chat_id;
            file_get_contents($url);
        }
    }
?>