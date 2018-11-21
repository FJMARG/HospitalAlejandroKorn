<?php
class TipoInstitucionRepository {
	public static function getTipoInstitucion(){
		$db = PDORepository::getConnection();
	    $stmt = $db->prepare("Select * from tipo_institucion;");
	    $stmt->execute();
	    return $stmt->fetchAll(PDO::FETCH_OBJ);
	}
}
?>