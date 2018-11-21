<?php

    class BotController{
        public static function startBot(){
            $returnArray = true;
            $rawData = file_get_contents('php://input');
            $response = json_decode($rawData, $returnArray);
            $id_del_chat = $response['message']['chat']['id'];
             
            // Obtener comando (y sus posibles parametros)
            $regExp = '#^(\/[a-zA-Z0-9\/]+?)(\ .*?)$#i';


            $tmp = preg_match($regExp, $response['message']['text'], $aResults);

            if (isset($aResults[1])) {
                $cmd = trim($aResults[1]);
                $cmd_params = trim($aResults[2]);
            } else {
                $cmd = trim($response['message']['text']);
                $cmd_params = '';
            }
             
            // Mensaje de respuesta
            $msg = array();
            $msg['chat_id'] = $response['message']['chat']['id'];
            $msg['text'] = null;
            $msg['disable_web_page_preview'] = true;
            $msg['reply_to_message_id'] = (int)$response['message']['message_id'];
            $msg['reply_markup'] = null;
            
            $tmp=explode(':',$cmd);

            if (sizeof($tmp)>1){
                $cmd=$tmp[0];
            }

            // Comandos
            switch ($cmd) {
                case '/start':
                    $msg['text']  = 'Hola ' . $response['message']['from']['first_name'] . " Usuario: " . $response['message']['from']['username'] . '!' . PHP_EOL;
                    $msg['text'] .= '¿Como puedo ayudarte? Puedes ver una lista de las opciones disponibles con el comando /help';
                    $msg['reply_to_message_id'] = null;
                    break;
             
                case '/help':
                    $msg['text']  = 'Los comandos disponibles son estos:' . PHP_EOL;
                    $msg['text'] .= '/start Inicializa el bot' . PHP_EOL;
                    $msg['text'] .= '/instituciones Muestra las instituciones disponibles.' . PHP_EOL;
                    $msg['text'] .= '/instituciones-region-sanitaria: {region-sanitaria​​}: Muestra un listado de Instituciones a partir de una la región sanitaria indicada.' . PHP_EOL;
                    $msg['text'] .= '/help Muestra los comandos disponibles (Los que te estoy mostrando ahora).';
                    $msg['reply_to_message_id'] = null;
                    break;
             
                case '/instituciones':
                    $msg['text']  = "Las instituciones disponibles son: \n";
                    foreach (InstitucionesRepository::getInstitucion() as $institucion){
                        $msg['text'] .= $institucion['nombre']."\n ";
                    }
                    break;

                case '/instituciones-region-sanitaria:':
                    $msg['text']  = "Las instituciones disponibles para la region sanitaria indicada son: \n";
                    foreach (InstitucionesRepository::getInstitucionRegionId($tmp[1]) as $institucion){
                        $msg['text'] .= $institucion['nombre']."\n ";
                    }
                    break;
             
                case '/info':
                    $msg['text']  = json_encode($response);
                    break;
             
                default:
                    $msg['text']  = 'Lo siento ' . $response['message']['from']['first_name'] . ', pero [' . $cmd . '] no es un comando válido.' . PHP_EOL;
                    $msg['text'] .= 'Prueba /help para ver la lista de comandos disponibles';
                    break;
            }

            //Realizamos el envío
            $url = 'https://api.telegram.org/bot794469660:AAFzyw5Ue3NfYqwtE15_H5F0ba2NDPyoKs0/sendMessage';

            $options = array(
                'http' => array(
                    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method'  => 'POST',
                    'content' => http_build_query($msg)
                )
            );
                        
            $context  = stream_context_create($options);
            $result = file_get_contents($url, false, $context);
             
            exit(0);
        }
    }
?>