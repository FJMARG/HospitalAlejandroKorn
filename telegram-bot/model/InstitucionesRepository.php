<?php
    class InstitucionesRepository {
        
        /* Todas las funciones retornan un arreglo, el cual contiene ademas un arreglo por cada elemento (como si fuera un FetchArray de una consulta sql); en excepcion de las consultas que requieren un ID que traen solo un arreglo con la informacion del elemento del ID correspondiente. */

        private static function getData($data){
            return json_decode(file_get_contents("https://grupo11.proyecto2018.linti.unlp.edu.ar/api-instituciones/".$data),true);
        }

        public static function getInstitucion() {
            return self::getData("instituciones");
        }
        
        public static function getInstitucionRegionId($id) {
            return self::getData("instituciones/region-sanitaria/​".$id);
        }
        
    }
 