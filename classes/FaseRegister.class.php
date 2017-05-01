<?php
require_once("classhelper.php");

class FaseRegister {
    private $db;
    private $typeName = "Fase";
    
    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function hentFase($id) {
        $stmt = $this->db->prepare("SELECT * FROM fase WHERE fase_id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return getEn($stmt, $this->typeName);
    }
    
    public function hentAlleFaser($prosjekt_id){
        $stmt = $this->db->prepare("SELECT * FROM fase WHERE prosjekt_id=:pId");
        $stmt->bindParam(':pId', $prosjekt_id);
        return getAlle($stmt, $this->typeName);
    }
    
    
    public function lagFase($fase) {
        $stmt = $this->db->prepare("INSERT INTO `fase` (fase_navn, prosjekt_id, fase_startdato, fase_sluttdato, fase_tilstand)
        VALUES (:navn, :prosjekt_id, :startdato, :sluttdato, :tilstand)");
        $stmt->bindParam(':navn', $fase->getNavn(), PDO::PARAM_STR);
        $stmt->bindParam(':prosjekt_id', $fase->getProsjektId(), PDO::PARAM_INT);
        $stmt->bindParam(':startdato', $fase->getStartDato());
        $stmt->bindParam(':sluttdato', $fase->getSluttDato());
        $stmt->bindParam(':tilstand', $fase->getTilstand());
        return execStmt($stmt);
    }
    
    public function redigerFase($fase){
        $stmt = $this->db->prepare("UPDATE `fase` SET fase_navn=:navn,
        fase_startdato=:startdato, fase_sluttdato=:sluttdato, fase_tilstand=:tilstand WHERE fase_id=:id");
        $stmt->bindParam(':navn', $fase->getNavn(), PDO::PARAM_STR);
        $stmt->bindParam(':startdato', $fase->getStartDato());
        $stmt->bindParam(':sluttdato', $fase->getSluttDato());
        $stmt->bindParam(':id', $fase->getId(), PDO::PARAM_INT);
        $stmt->bindParam(':tilstand', $fase->getTilstand());
        return execStmt($stmt);
    }
}