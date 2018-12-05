<?php

require_once ('ReferenciasRepository.php');

class BuscarNombrePaciente 
{
  
    private static $instance;

    public static function getInstance() {

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getConnection()
    {


        $dsn = 'mysql:host=localhost;dbname=grupo11;charset=utf8';
        return new PDO($dsn, 'grupo11', 'ZDc1MjY5MTBlNjQ2');

        //$dsn = 'mysql:host=localhost;dbname=proyecto2018;charset=utf8';
        //return new PDO($dsn, 'root', 'alumno');

    }  


    public function BuscarDatosAutocomplete($valor)
    {

        $pdo = $this->getConnection();
        $reg = $valor.'%';    

        $query = $pdo->prepare("SELECT * FROM  paciente WHERE nombre LIKE ? OR apellido LIKE ? OR numero LIKE ?");
        $query->execute([$reg,$reg,$reg]);

        $resultado = array();

        $api = new ReferenciasRepository();

        while($row = $query->fetch())
        {
              if ($row['tiene_documento'] == 0) 
              {
                 // Es no tiene documento mostara S/D
                 $tipoDoc['nombre'] = "S/D";    
              }
              else 
              { 
                  // buscar DNI desde la API de documentos
                  $tipoDoc = $api->getTipoDocumentoId("1");   
              }  
                
              $data['label'] = $row['apellido'] . " " . $row['nombre'] . " " . $tipoDoc['nombre'] . " " .$row['numero'] ;
              $data['value'] = $row['apellido']; 
              $data['id']    = $row['id'];
              array_push($resultado, $data);
        }


        echo json_encode($resultado);

    }


     public function buscarMapa($id)
     {   
        
         // recuperar los datos del cliente
         $pdo = $this->getConnection();
         $query = $pdo->prepare("SELECT DISTINCT consulta.derivacion_id, institucion.nombre, institucion.director, institucion.lat, institucion.log, tipo_institucion.nombre as tipo
                                 FROM consulta 
                                 INNER JOIN institucion ON
                                 consulta.derivacion_id = institucion.id
                                 INNER JOIN tipo_institucion ON
                                 institucion.id  = tipo_institucion.id
                                 WHERE consulta.paciente_id = ? 
                                 AND consulta.derivacion_id IS NOT NULL");
         $query->execute([$id]);
                   
         
         // datos del arreglo   
         //$linea['derivacion_id'] = $row['derivacion_id'];
         //$linea['lat']           = $row['lat'];
         //$linea['log']           = $row['log'];
         //$linea['director']      = $row['director'];
         //$linea['nombre']        = $row['nombre'];
         //array_push($arrayName,$linea);
         //echo json_encode($arrayName);
     
        
          # Build GeoJSON feature collection array
          # Crear una matriz de colección de características GeoJSON
          $geojson = array(
             'type'      => 'FeatureCollection',
             'features'  => array()
          );
           
          while ( $row = $query->fetch()) { 
        
                    $feature = array(
                                    'id' => $row['derivacion_id'],  
                                    'type' => 'Feature', 
                                    'geometry' => array (
                                                          'type' => 'Point',
                                                          # Pass Longitude and Latitude Columns here
                                                          'coordinates' => array($row['log'],$row['lat']) 
                                                        ),
                                      # Pass other attribute columns here
                                      'properties' => array (
                                                              'name' => $row['tipo'],
                                                              'description' => $row['nombre']
                      )
                  );

                  # Add feature arrays to feature collection array  
                  array_push($geojson['features'], $feature);  

        }        
        
        // devolver el geoJson  
        echo json_encode($geojson, JSON_NUMERIC_CHECK);
        
     }

     public function buscarConsultas($id)
     {

         $pdo = $this->getConnection();
         $query = $pdo->prepare("SELECT count(*) FROM consulta WHERE paciente_id = ?");
         $query->execute([$id]);
         $count = $query->fetchColumn();

         $array['cantidad'] = $count;
         echo json_encode($array);

     }
}









