<?php
    class ReferenciasRepository {
        
        /* Todas las funciones retornan un arreglo, el cual contiene ademas un arreglo por cada elemento (como si fuera un FetchArray de una consulta sql); en excepcion de las consultas que requieren un ID que traen solo un arreglo con la informacion del elemento del ID correspondiente. */

        private static function getData($data){
            return json_decode(file_get_contents("https://api-referencias.proyecto2018.linti.unlp.edu.ar/".$data),true);
        }

        public static function getTipoDocumento() {
            return self::getData('tipo-documento');
        }
        
        public static function getTipoDocumentoId($id) {
            return self::getData('tipo-documento/'.$id);
        }

        public static function getTipoVivienda() {
            return self::getData('tipo-vivienda');
        }

        public static function getTipoViviendaId($id) {
            return self::getData('tipo-vivienda/'.$id);
        }

        public static function getTipoCalefaccion() {
            return self::getData('tipo-calefaccion');
        }

        public static function getTipoCalefaccionId($id) {
            return self::getData('tipo-calefaccion/'.$id);
        }

        public static function getTipoAgua() {
            return self::getData('tipo-agua');
        }

        public static function getTipoAguaId($id) {
            return self::getData('tipo-agua/'.$id);
        }

        public static function getRegionSanitaria() {
            return self::getData('region-sanitaria');
        }

        public static function getRegionSanitariaId($id) {
            return self::getData('region-sanitaria/'.$id);
        }

        public static function getPartido() {
            return self::getData('partido');
        }

        public static function getPartidoId($id) {
            return self::getData('partido/'.$id);
        }

        public static function getLocalidad() {
            return self::getData('localidad');
        }

        public static function getLocalidadId($id) {
            return self::getData('localidad/'.$id);
        }

        public static function getLocalidadPartido($partidoId) {
            return self::getData('localidad/partido/'.$partidoId);
        }

        public static function getObraSocial() {
            return self::getData('obra-social');
        }

        public static function getObraSocialId($id) {
            return self::getData('obra-social/'.$id);
        }

    }
 