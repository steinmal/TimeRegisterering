<?php
class FaseRegister {
    
    private $db;
    
    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function hentFase($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM fase WHERE fase_id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            if($fase = $stmt->fetchObject('Fase')) {
                return $fase;
            }
        } catch (Exception $e) {
            $this->Feil($e->getMessage());
        }
    }
    
    public function hentAlleFaser($prosjekt_id){
        $faser = array();
        try {
            $stmt = $this->db->prepare("SELECT * FROM fase WHERE prosjekt_id=:pId");
            $stmt->bindParam(':pId', $prosjekt_id);
            $stmt->execute();
            
            while($fase = $stmt->fetchObject('Fase')){
                $faser[$fase->getId()] = $fase;
            }
        } catch (Exception $e) {
            $this->Feil($e->getMessage());
        }
        return $faser;
    }
    
    
    public function lagFase($fase) {
        try {
            $stmt = $this->db->prepare("INSERT INTO `fase` (fase_navn, prosjekt_id, fase_startdato, fase_sluttdato, fase_tilstand)
            VALUES (:navn, :prosjekt_id, :startdato, :sluttdato, :tilstand)");
            $stmt->bindParam(':navn', $fase->getNavn(), PDO::PARAM_STR);
            $stmt->bindParam(':prosjekt_id', $fase->getProsjektId(), PDO::PARAM_INT);
            $stmt->bindParam(':startdato', $fase->getStartDato());
            $stmt->bindParam(':sluttdato', $fase->getSluttDato());
            $stmt->bindParam(':tilstand', $fase->getTilstand());
            $stmt->execute();
        } catch (Exception $e) {
            $this->Feil($e->getMessage());
        }
    }
    
    public function redigerFase($fase){
        try {
            $stmt = $this->db->prepare("UPDATE `fase` SET fase_navn=:navn,
            fase_startdato=:startdato, fase_sluttdato=:sluttdato, fase_tilstand=:tilstand WHERE fase_id=:id");
            $stmt->bindParam(':navn', $fase->getNavn(), PDO::PARAM_STR);
            $stmt->bindParam(':startdato', $fase->getStartDato());
            $stmt->bindParam(':sluttdato', $fase->getSluttDato());
            $stmt->bindParam(':id', $fase->getId(), PDO::PARAM_INT);
            $stmt->bindParam(':tilstand', $fase->getTilstand());
            $stmt->execute();
        } catch (Exception $e) {
            $this->Feil($e->getMessage());
        }
    }
    
    
    private function Feil($feilmelding) {
        print "<h2>Oisann... Noe gikk galt </h2>";
        print "<h4>$feilmelding</h4>";
    }
}