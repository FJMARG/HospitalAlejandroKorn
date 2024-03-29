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

            $cmdOriginal = $cmd;

            $cmd=strtolower($cmd);

            // Comandos que responde el bot

            $msg=array();
            $msg[0]='';

            switch ($cmd) {
                case '/start':
                    $msg[0] = 'Hola '.$name.'¿Que necesita? Si escribis /help te listare todos los comandos disponibles. Recuerda que los comandos deben escribirse tal cual como se indican (excepto por las mayusculas o minusculas que no importan).';
                    break;
             
                case '/help':
                    $msg[0] = 'Los comandos disponibles son los siguientes: /start Inicializa el bot. /instituciones Muestra las instituciones disponibles. /instituciones-region-sanitaria: {region-sanitaria​​} Muestra un listado de Instituciones a partir de una la región sanitaria indicada. /help Muestra los comandos disponibles (Los que te estoy mostrando ahora). Espero tu comando!!!';
                    break;
             
                case '/instituciones':
                    $instituciones = InstitucionesRepository::getInstitucion();
                    $i=0;
                    if (!empty($instituciones)){
                    	$msg[$i] = 'Las instituciones disponibles son:';
	                    foreach ($instituciones as $institucion){
	                    	if ( ((strlen($msg[$i])) + (strlen($institucion['nombre'])) + 4) > 4096 ){
	                    		$i = $i + 1;
                                $msg[$i] = '';
	                    	}
	                        $msg[$i] .= ' -> '.$institucion['nombre'];
	                    }
	                }
	                else{
	                	$msg[$i] = 'No hay instituciones disponibles.';
	                }
                    break;

                case '/instituciones-region-sanitaria':
                	$instituciones = InstitucionesRepository::getInstitucionRegionId($comando[1]);
                	$i=0;
                    if (!empty($instituciones)){
	                    $msg[$i] = 'Las instituciones disponibles para la region sanitaria '.$comando[1].' son:';
	                    foreach ($instituciones as $institucion){
	                    	if ( ((strlen($msg[$i])) + (strlen($institucion['nombre'])) + 4) > 4096 ){
	                    		$i = $i + 1;
                                $msg[$i] = '';
	                    	}
	                        $msg[$i] .= ' -> '.$institucion['nombre'];
	                    }
                	}
                	else{
                		$msg[$i] = 'No hay instituciones disponibles para la region sanitaria indicada.';
                	}
                    break;

                case ($cmd == 'hola' || $cmd == 'holaa' || $cmd == 'holaaa' || $cmd == 'hola!' || $cmd == 'holaa!' || $cmd == 'holaaa!'):
                	$opcion=rand(1,10);
                	switch($opcion){
                		case 1:
                			$msg[0] = 'Hola!, en que puedo ayudarte?';
                			break;
                		case 2:
                			$msg[0] = 'Hola!, que necesitas?';
                			break;
                		case 3:
                			$msg[0] = 'Buenas!';
                			break;
                		case 4:
                			$msg[0] = 'Aloh!';
                			break;
                		case 5:
                			$msg[0] = 'Hola!, que solicita?';
                			break;
                		case 6:
                			$msg[0] = 'Hola!, que quieres saber?';
                			break;
                		case 7:
                			$msg[0] = 'Buenas!, diga?';
                			break;
                		case 8:
                			$msg[0] = 'Hola!, necesita informacion?';
                			break;
                		case 9:
                			$msg[0] = 'Que tal?';
                			break;
                		case 10:
                			$msg[0] = 'Aloh! que puedo hacer por usted?';
                			break;
                	}
                    break;

                case ($cmd =='gracias' || $cmd =='gracias!' || $cmd =='graciass' || $cmd =='graciass!' || $cmd =='graciaas' || $cmd =='graciaas!' || $cmd =='graciaass!' || $cmd =='graciaass' || $cmd =='muchas gracias' || $cmd =='muchas gracias!' || $cmd =='muchas graciass' || $cmd =='muchas graciass!' || $cmd =='muchas graciaas' || $cmd =='muchas graciaas!' || $cmd =='muchas graciaass' || $cmd =='muchas graciaass!'):
                    $opcion=rand(1,10);
                	switch($opcion){
                		case 1:
                			$msg[0] = 'De nada!!!';
                			break;
                		case 2:
                			$msg[0] = 'No hay por que!';
                			break;
                		case 3:
                			$msg[0] = 'No, por favor!';
                			break;
                		case 4:
                			$msg[0] = ':)';
                			break;
                		case 5:
                			$msg[0] = 'Un gusto haber podido ayudarte!';
                			break;
                		case 6:
                			$msg[0] = 'Gracias a vos por contar conmigo!';
                			break;
                		case 7:
                			$msg[0] = 'Es un honor para mi ser util para vos!';
                			break;
                		case 8:
                			$msg[0] = 'Es un placer!';
                			break;
                		case 9:
                			$msg[0] = 'Con mucho gusto!';
                			break;
                		case 10:
                			$msg[0] = 'Esta bien!';
                			break;
                	}
                    break;
             	
                case ($cmd == 'chau' || $cmd == 'chau!' || $cmd == 'chauu' || $cmd == 'chauu!' || $cmd == 'adios' || $cmd == 'adios!' || $cmd == 'adioos!' || $cmd == 'adioos' || $cmd == 'adiooss' || $cmd == 'adiooss!' || $cmd == 'hasta luego' || $cmd == 'hasta luego!' || $cmd == 'adioss' || $cmd == 'adioss!' || $cmd == 'chaau' || $cmd == 'chaau!' || $cmd == 'chaauu' || $cmd == 'chaauu!'):
                	$opcion=rand(1,10);
                	switch($opcion){
                		case 1:
                			$msg[0] = 'Hasta la proxima!!!';
                			break;
                		case 2:
                			$msg[0] = 'Hasta luego!';
                			break;
                		case 3:
                			$msg[0] = 'Nos vemos!';
                			break;
                		case 4:
                			$msg[0] = 'Chau!';
                			break;
                		case 5:
                			$msg[0] = 'Adios!';
                			break;
                		case 6:
                			$msg[0] = 'Bye!';
                			break;
                		case 7:
                			$msg[0] = 'Chau, buena suerte!';
                			break;
                		case 8:
                			$msg[0] = 'Chau chau!!';
                			break;
                		case 9:
                			$msg[0] = 'Good bye!';
                			break;
                		case 10:
                			$msg[0] = 'Nos vemos, suerte!';
                			break;
                	}
                    break;

                default:
                	$opcion=rand(1,10);
                	switch($opcion){
                		case 1:
                			$msg[0]  = 'Lo siento '.$name.', pero no entiendo el comando ['.$cmdOriginal.'], por lo que no puedo ayudarte con lo que me solicitas. Escribi el comando /help para ver la lista de comandos disponibles (los que entiendo).';
                			break;
                		case 2:
                			$msg[0] = 'Disculpame, pero no entiendo o no te puedo ayudar con eso. Escribe /help para ver los comandos disponibles.';
                			break;
                		case 3:
                			$msg[0] = 'Que quiso decir?. Escribi /help y te mostrare los comandos que entiendo.';
                			break;
                		case 4:
                			$msg[0] = 'eh? perdon, pero no entendi. Escribi /help y te muestro los comandos disponibles.';
                			break;
                		case 5:
                			$msg[0] = 'Mil disculpas, pero mi funcionalidad es limitada a los comandos que aparecen al escribir el comando /help y a tener buenos modales!';
                			break;
                		case 6:
                			$msg[0] = 'Ese comando no lo entiendo. Lo habras escrito bien? Revisa y vuelve a escribirlo, o escribe /help para ver los comandos disponibles.';
                			break;
                		case 7:
                			$msg[0] = 'Disculpa, solo te puedo brindar informacion de las instituciones disponibles dada una region sanitaria (/instituciones-region-sanitaria: {region-sanitaria​​}), o sobre todas las instituciones en general (/instituciones).';
                			break;
                		case 8:
                			$msg[0] = 'Por el momento no puedo ayudarte con lo que solicitas. Escribi /help para conocer los comandos que entiendo.';
                			break;
                		case 9:
                			$msg[0] = 'No tengo la informacion de lo que me pides. Quizas en algun futuro la tengamos! Escribe /help para que sepas que comandos entiendo actualmente.';
                			break;
                		case 10:
                			$msg[0] = 'Comando incorrecto o inexistente! Escribe /help para que sepas que comandos entiendo.';
                			break;
                	}
                    break;
            }

            //Envio de respuesta
            foreach ($msg as $x){
                $m = filter_var($x, FILTER_SANITIZE_ENCODED);
	            $url = 'https://api.telegram.org/bot794469660:AAFzyw5Ue3NfYqwtE15_H5F0ba2NDPyoKs0/sendMessage?text='.$m.'&chat_id='.$chat_id;
	            file_get_contents($url);
	        }
        }
    }
?>