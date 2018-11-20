<?php
class InstitucionRepository {
    public static function getInstitucionId($id){
		$db = PDORepository::getConnection();
	    $stmt = $db->prepare("Select * from institucion where id = :id;");
	    $stmt->bindParam(':id',$id);
	    $stmt->execute();
	    return $stmt->fetchAll(PDO::FETCH_OBJ);
	}
	public static function getInstitucion(){
		$db = PDORepository::getConnection();
	    $stmt = $db->prepare("Select * from institucion;");
	    $stmt->execute();
	    return $stmt->fetchAll(PDO::FETCH_OBJ);
	}
	public static function getInstitucionRegion($id){
		$db = PDORepository::getConnection();
	    $stmt = $db->prepare("Select * from institucion where region_sanitaria_id = :id;");
	    $stmt->bindParam(':id',$id);
	    $stmt->execute();
	    return $stmt->fetchAll(PDO::FETCH_OBJ);
	}
}
?>