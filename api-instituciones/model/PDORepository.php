<?php
class PDORepository {
    public static function getConnection() {
        try {
            $db = new PDO("mysql:dbname=grupo11;host=localhost;charset=UTF8","grupo11","ZDc1MjY5MTBlNjQ2");
        }
        catch(PDOException $e){}
        return $db;
    }
}
?>