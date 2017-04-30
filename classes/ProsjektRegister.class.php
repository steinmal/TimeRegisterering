<?php
require_once("classhelper.php");

class ProsjektRegister {
    private $db;
    private $typeName = "Prosjekt";

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function hentAlleProsjekt($hentArkiverte=true) {
        if($hentArkiverte){
            $stmt = $this->db->prepare("SELECT * FROM prosjekt");
        }
        else{
            $stmt = $this->db->prepare("SELECT * FROM prosjekt WHERE prosjekt_arkivert=0");
        }
        return getAlle($stmt, $this->typeName);
    }
    
    public function hentUnderProsjekt($id){
        $stmt = $this->db->prepare("SELECT * FROM prosjekt WHERE foreldre_prosjekt_id = :id AND prosjekt_id != 1");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return getAlle($stmt, $this->typeName);
    }

    public function lagProsjekt($prosjekt) {
        $stmt = $this->db->prepare("
            INSERT INTO `prosjekt` (foreldre_prosjekt_id, prosjekt_navn, prosjekt_leder, prosjekt_startdato, prosjekt_sluttdato, prosjekt_beskrivelse, team_id, prosjekt_product_owner, prosjekt_registreringsdato)
            VALUES (:foreldre_id, :navn, :leder, :startdato, :sluttdato, :beskrivelse, :team_id, :product_owner, now());
            INSERT INTO `fase` (fase_navn, prosjekt_id, fase_startdato, fase_sluttdato)
            VALUES ('Backlog', LAST_INSERT_ID(), :startdato, :sluttdato)");

        $stmt->bindParam(':foreldre_id', $prosjekt->getParent(), PDO::PARAM_INT);
        $stmt->bindParam(':navn', $prosjekt->getNavn(), PDO::PARAM_STR);
        $stmt->bindParam(':leder', $prosjekt->getLeder(), PDO::PARAM_INT);
        $stmt->bindParam(':startdato', $prosjekt->getStartDato());
        $stmt->bindParam(':sluttdato', $prosjekt->getSluttDato());
        $stmt->bindParam(':beskrivelse', $prosjekt->getBeskrivelse(), PDO::PARAM_STR);
        $stmt->bindParam(':team_id', $prosjekt->getTeam(), PDO::PARAM_INT);
        $stmt->bindParam(':product_owner', $prosjekt->getProductOwner(), PDO::PARAM_STR);

        return execStmt($stmt);
    }

    public function hentProsjekt($id) {
        $stmt = $this->db->prepare("SELECT * FROM prosjekt WHERE prosjekt_id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return getEn($stmt, $this->typeName);
    }

    public function hentProsjektFraOppgave($oppgave_id){
        $stmt = $this->db->prepare(
                "SELECT * FROM `prosjekt` WHERE prosjekt_id=
                (SELECT `prosjekt_id` FROM `fase` WHERE `fase`.`fase_id`=
                (SELECT `fase_id` FROM `oppgave` WHERE `oppgave`.`oppgave_id`=:id))");
        $stmt->bindParam(':id', $oppgave_id, PDO::PARAM_INT);
        return getEn($stmt, $this->typeName);
    }
    
    public function redigerProsjekt($prosjekt) {
        $stmt = $this->db->prepare("UPDATE prosjekt SET prosjekt_navn=:navn, prosjekt_leder=:leder, prosjekt_startdato=:startdato, prosjekt_sluttdato=:sluttdato, prosjekt_beskrivelse=:beskrivelse, team_id=:team WHERE prosjekt_id=:id");
        
        $stmt->bindParam(':navn', $prosjekt->getNavn(), PDO::PARAM_STR);
        $stmt->bindParam(':leder', $prosjekt->getLeder(), PDO::PARAM_INT);
        $stmt->bindParam(':startdato', $prosjekt->getStartDato());
        $stmt->bindParam(':sluttdato', $prosjekt->getSluttDato());
        $stmt->bindParam(':beskrivelse', $prosjekt->getBeskrivelse(), PDO::PARAM_STR);
        $stmt->bindParam(':team', $prosjekt->getTeam(), PDO::PARAM_INT);
        $stmt->bindParam(':id', $prosjekt->getId(), PDO::PARAM_STR);
        return execStmt($stmt);
    }
    
    public function arkiverProsjekt($id, $gjenopprett=false){
        $arkiver = ($gjenopprett ? 0 : 1);
        $stmt = $this->db->prepare("UPDATE prosjekt SET prosjekt_arkivert=:arkiver WHERE prosjekt_id=:id");
        $stmt->bindParam(':arkiver', $arkiver, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return execStmt($stmt);
    }

    public function slettProsjekt($prosjekt) {
        $stmt = $this->db->prepare("DELETE FROM prosjekt WHERE prosjekt_id=:prosjektId");
        $stmt->bindParam(':prosjektId', $prosjekt->getId(), PDO::PARAM_INT);
        return execStmt($stmt);
    }
    
    public function hentProsjekterFraTeam($teamID) {
        $stmt = $this->db->prepare("SELECT * FROM prosjekt WHERE team_id=:id");
        $stmt->bindParam(':id', $teamID, PDO::PARAM_INT);
        return getAlle($stmt, $this->typeName);
    }
    
    public function hentProsjektFraFase($fase_id){
        $stmt = $this->db->prepare(
            "SELECT * FROM prosjekt WHERE prosjekt_id=
            (SELECT prosjekt_id FROM fase WHERE fase_id=:id)");
        $stmt->bindParam(':id', $fase_id, PDO::PARAM_INT);
        return getEn($stmt, $this->typeName);
    }
}
?>