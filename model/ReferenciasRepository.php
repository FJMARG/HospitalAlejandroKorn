<?php
    class ReferenciasRepository {
        
        /* Todas las funciones retornan un arreglo, el cual contiene ademas un arreglo por cada elemento (como si fuera un FetchArray de una consulta sql); en excepcion de las consultas que requieren un ID que traen solo un arreglo con la informacion del elemento del ID correspondiente. */

        private static function getData($data){
            return file_get_contents("https://api-referencias.proyecto2018.linti.unlp.edu.ar/".$data);
        }

        public static function getTipoDocumento() {
            return json_decode(self::getData('tipo-documento'),true);
        }
        
        public static function getTipoDocumentoId($id) {
            return json_decode(self::getData('tipo-documento/'.$id),true);
        }

        public static function getTipoVivienda() {
            return json_decode(self::getData('tipo-vivienda'),true);
        }

        public static function getTipoViviendaId($id) {
            return json_decode(self::getData('tipo-vivienda/'.$id),true);
        }

        public static function getTipoCalefaccion() {
            return json_decode(self::getData('tipo-calefaccion'),true);
        }

        public static function getTipoCalefaccionId($id) {
            return json_decode(self::getData('tipo-calefaccion/'.$id),true);
        }

        public static function getTipoAgua() {
            return json_decode(self::getData('tipo-agua'),true);
        }

        public static function getTipoAguaId($id) {
            return json_decode(self::getData('tipo-agua/'.$id),true);
        }

        public static function getRegionSanitaria() {
            return json_decode(self::getData('region-sanitaria'),true);
        }

        public static function getRegionSanitariaId($id) {
            return json_decode(self::getData('region-sanitaria/'.$id),true);
        }

        public static function getPartido() {
            return json_decode(self::getData('partido'),true);
        }

        public static function getPartidoId($id) {
            return json_decode(self::getData('partido/'.$id),true);
        }

        public static function getLocalidad() {
            return json_decode(self::getData('localidad'),true);
        }

        public static function getLocalidadId($id) {
            return json_decode(self::getData('localidad/'.$id),true);
        }

        public static function getLocalidadPartido($partidoId) {
            return json_decode(self::getData('localidad/partido/'.$partidoId),true);
        }

        public static function getObraSocial() {
            return json_decode(self::getData('obra-social'),true);
        }

        public static function getObraSocialId($id) {
            return json_decode(self::getData('obra-social/'.$id),true);
        }

    }
 