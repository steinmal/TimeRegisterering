<?php
require_once("classhelper.php");

class ProsjektRegister {
    // TODO: Smart solution for avoiding exceptions from prepare or bindParam methods.
    // As of now there is no try/catch around those in favor of the "pretty" code inside the classHelper file.
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
    
    public function hentUnderProsjektFraListe(array $idArr){
        $c = count($idArr);
        if($c > 0){
            $stmt = $this->db->prepare("SELECT * FROM prosjekt WHERE foreldre_prosjekt_id IN (?" . str_repeat(',?', $c-1) . ") AND prosjekt_id != 1");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return getAlle($stmt, $this->typeName, false, $idArr);
        }
    }
    
    public function hentTeamProsjektFraBruker($bruker_id){
        $stmt = $this->db->prepare("SELECT DISTINCT * FROM prosjekt WHERE team_id IN (SELECT team_id FROM teammedlemskap WHERE bruker_id=:bId)");
        $stmt->bindParam(':bId', $bruker_id, PDO::PARAM_INT);
        return getAlle($stmt, $this->typeName, true);
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
        $stmt = $this->db->prepare("UPDATE prosjekt SET prosjekt_navn=:navn, prosjekt_leder=:leder, prosjekt_product_owner=:productowner, prosjekt_startdato=:startdato, prosjekt_sluttdato=:sluttdato, prosjekt_beskrivelse=:beskrivelse, team_id=:team WHERE prosjekt_id=:id");
        
        $stmt->bindParam(':navn', $prosjekt->getNavn(), PDO::PARAM_STR);
        $stmt->bindParam(':leder', $prosjekt->getLeder(), PDO::PARAM_INT);
        $stmt->bindParam(':productowner', $prosjekt->getProductOwner(), PDO::PARAM_INT);
        $stmt->bindParam(':startdato', $prosjekt->getStartDato());
        $stmt->bindParam(':sluttdato', $prosjekt->getSluttDato());
        $stmt->bindParam(':beskrivelse', $prosjekt->getBeskrivelse(), PDO::PARAM_STR);
        $stmt->bindParam(':team', $prosjekt->getTeam(), PDO::PARAM_INT);
        $stmt->bindParam(':id', $prosjekt->getId(), PDO::PARAM_STR);
        return execStmt($stmt);
    }
    
    public function arkiverProsjekt($id, $status=1){
        $stmt = $this->db->prepare("UPDATE prosjekt SET prosjekt_arkivert=:arkiver WHERE prosjekt_id=:id");
        $stmt->bindParam(':arkiver', $status, PDO::PARAM_INT);
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
    public function hentGrunnprosjekterFraLeder($bruker_id){
        $stmt = $this->db->prepare("SELECT * FROM prosjekt WHERE prosjekt_leder=:prosjektleder AND foreldre_prosjekt_id=1");
        $stmt->bindParam(':prosjektleder', $bruker_id, PDO::PARAM_INT);
        return getAlle($stmt, $this->typeName);
    }
    
    public function hentProsjektFraProductOwner($bruker_id) {
        $stmt = $this->db->prepare("SELECT * FROM prosjekt WHERE prosjekt_product_owner=:id");
        $stmt->bindParam(':id', $bruker_id, PDO::PARAM_INT);
        return getAlle($stmt, $this->typeName);
    }

}
?>