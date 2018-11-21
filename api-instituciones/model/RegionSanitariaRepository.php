<?php
class RegionSanitariaRepository {
	public static function getRegionSanitaria(){
		$db = PDORepository::getConnection();
	    $stmt = $db->prepare("Select * from region_sanitaria;");
	    $stmt->execute();
	    return $stmt->fetchAll(PDO::FETCH_OBJ);
	}
}
?>