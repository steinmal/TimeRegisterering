<?php
require_once("classhelper.php");

class SystemRegister
{
    private $db;
    private $typeName = "SystemVariabler";
    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function lagSystemvariabel($tidsparameter) {
        $stmt = $this->db->prepare("INSERT INTO `system` (tidsparameter) VALUES (:tidsparameter)");
        $stmt->bindParam(':tidsparameter', $tidsparameter);

        return execStmt($stmt);
    }

    public function redigerSystemvariabel($tidsparameter){
        $stmt = $this->db->prepare("UPDATE `system` SET tidsparameter=:tidsparameter LIMIT 1"); //Limit for å bare oppdatere første rad, siden vi bare har en rad.
        $stmt->bindParam(':tidsparameter', $tidsparameter);
        return execStmt($stmt);
    }

    public function hentSystemvariabel()
    {
        $stmt = $this->db->prepare("SELECT * FROM system");
        return getAlle($stmt, $this->typeName);
    }
}