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

        #$dsn = 'mysql:host=localhost;dbname=proyecto2018;charset=utf8';
        #return new PDO($dsn, 'root', 'alumno');

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


     public function buscarMapa($idInstitucion)
     {
             
        /*
        $pdo = $this->getConnection();

        $query = $pdo->prepare("SELECT * FROM institucion WHERE id = ?");
        $query->execute([$idInstitucion]);
         
        $resultado = array(); 
        
        while($row = $query->fetch())
        {
           $data['mapa'] = $row['mapa'];
           array_push($resultado, $data);
        }
        
        
        //$resultado['unValor'] = 1; // para testeo
        echo json_encode($resultado);
        */
     }
}









