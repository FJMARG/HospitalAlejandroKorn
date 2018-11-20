<?php
class PartidoRepository {
	public static function getPartido(){
		$db = PDORepository::getConnection();
	    $stmt = $db->prepare("Select * from partido;");
	    $stmt->execute();
	    return $stmt->fetchAll(PDO::FETCH_OBJ);
	}
}
?>