<?php

require_once ('../vendor/autoload.php'); 
require_once ('ApiRequest.php');

$dsn = 'mysql:host=localhost;dbname=grupo11;charset=utf8';
$pdo = new PDO($dsn, 'grupo11', 'ZDc1MjY5MTBlNjQ2');

if(!empty($_POST["paciente"])) {
     
    $parametro = ($_POST["paciente"]) . "%";

	$query = $pdo->prepare("SELECT * FROM  paciente where apellido LIKE ?");
    $query->execute(array($parametro));
    
}
?>

<ul id="country-list">

<?php

    # Vericar si la consulta trajo datos
    if (!is_null($query)) 
    {
    	# procesar los pacientes recuperados 
		while ($results = $query->fetch()) 
		{	 

            if ($results['tipo_doc_id'] === NULL)
            {
               $tipo = "S/N";
            }
            else
            {
               $tipo = ""; 
               $url  = "https://api-referencias.proyecto2018.linti.unlp.edu.ar/tipo-documento/" . $results['tipo_doc_id']; 
               $obj  = ApiRequest::getInstance()->sendGet($url);
               $tipo = $obj->nombre; 
            }

            # armar datos para mostar
            $cadena = $results['apellido'] . " " . $results['nombre'] . "-Documento:" . $tipo . ":" . $results['numero'];

?>         
    	  <li onClick="selectPaciente('<?php echo $results['nombre'];?>','<?php echo $results['apellido'];?>','<?php echo $tipo; ?>','<?php echo $results['numero'];?>');"><?php echo $cadena; ?></li>
<?php 
        } 
 	 }
?>  	

</ul>

