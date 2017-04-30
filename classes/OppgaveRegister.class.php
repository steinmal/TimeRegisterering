<?php
require_once("classhelper.php");

class OppgaveRegister {
    private $db;
    private $oppgavetyper;
    private $typeName = "Oppgave";

    public function __construct(PDO $db) {
        $this->db = $db;
    }
    
    public function hentAlleOppgaver() {
        $stmt = $this->db->prepare("SELECT * FROM oppgave");
        return getAlle($stmt, $this->typeName);
    }
    
    public function hentOppgaverFraFase($fase_id){
        $stmt = $this->db->prepare("SELECT * FROM oppgave WHERE fase_id=:id");
        $stmt->bindParam(':id', $fase_id, PDO::PARAM_INT);
        return getAlle($stmt, $this->typeName);
    }
    
    public function hentOppgaverFraProsjekt($prosjekt_id) {
        $stmt = $this->db->prepare("SELECT * FROM `oppgave` WHERE `fase_id` IN (SELECT `fase_id` FROM `fase` WHERE `fase`.`prosjekt_id`=:pId)");
        $stmt->bindParam(':pId', $prosjekt_id);
        return getAlle($stmt, $this->typeName);
    }

    public function lagOppgave($foreldre_oppgave_id, $oppgavetype_id, $fase_id, $oppgave_navn, $oppgave_tidsestimat, $oppgave_periode) {
        $stmt = $this->db->prepare("INSERT INTO oppgave (foreldre_oppgave_id, oppgavetype_id, fase_id, oppgave_navn, oppgave_tidsestimat, oppgave_periode)
        VALUES (:foreldre_id, :oppgavetype_id, :fase_id, :navn, :tidsestimat, :periode)");
        $stmt->bindParam(':foreldre_id', $foreldre_oppgave_id, PDO::PARAM_INT);
        $stmt->bindParam(':oppgavetype_id', $oppgavetype_id, PDO::PARAM_INT);
        $stmt->bindParam(':fase_id', $fase_id, PDO::PARAM_INT);
        $stmt->bindParam(':navn', $oppgave_navn);
        $stmt->bindParam(':tidsestimat', $oppgave_tidsestimat, PDO::PARAM_INT);
        $stmt->bindParam(':periode', $oppgave_periode, PDO::PARAM_INT);
        return execStmt($stmt);
    }
    
    public function hentOppgave($id) {
        $stmt = $this->db->prepare("SELECT * FROM oppgave WHERE oppgave_id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return getEn($stmt, $this->typeName);
    }

    // TODO: Duplicate methods, remove one
    public function getOppgavetypeTekst($oppgavetype_id) {
        if ($this->oppgavetyper == null)
            $this->oppgavetyper = $this->hentAlleOppgavetyper();

        // TODO: Fiks dette
        if (!isset($this->oppgavetyper[$oppgavetype_id])) {
            //return "Feil ID " . $oppgavetype_id;
                //throw new InvalidArgumentException('Oppgavetype not defined: ' . $oppgavetype_id);
        }

        return $this->oppgavetyper[$oppgavetype_id]->getNavn();
    }

    
    //Outdated, as these requests sql data for each row
    public function hentOppgaveTypeTekst($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM oppgavetype WHERE oppgavetype_id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            $oppgavetype = $stmt->fetch(PDO::FETCH_ASSOC);
            return $oppgavetype["oppgavetype_navn"];
        } catch (Exception $e) {
            feil($e->getMessage());
        }
    }
    
    //Use these functions to avoid many calls to the database
    public function hentAlleOppgaveTyper() {
        $stmt = $this->db->prepare("SELECT * FROM oppgavetype");
        return getAlle($stmt, "Oppgavetype", true);
    }
    
    public function hentOppgaveType($id) {
        $stmt = $this->db->prepare("SELECT * FROM oppgavetype WHERE oppgavetype_id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return getEn($stmt, "Oppgavetype");
    }
    
    public function lagOppgaveType($oppgavetype_navn) {
        $stmt = $this->db->prepare("INSERT INTO oppgavetype (oppgavetype_navn) VALUES (:navn)");
        $stmt->bindParam(':navn', $oppgavetype_navn);
        return execStmt($stmt);
    }
    
    public function redigerOppgaveType($oppgavetype) {
        $stmt = $this->db->prepare("UPDATE `oppgavetype` SET oppgavetype_navn=:navn
          WHERE oppgavetype_id=:id");
        $stmt->bindParam(':navn', $oppgavetype->getNavn(), PDO::PARAM_STR);
        $stmt->bindParam(':id', $oppgavetype->getId(), PDO::PARAM_INT);
        return execStmt($stmt);
    }
    
    //Kan gjøres fra OppgaveOversikt
    public function hentAktiveTimerPrOppgave($id) {
        try {
            $stmt = $this->db->prepare("SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(`timereg_stopp`, `timereg_start`)))) as sum FROM timeregistrering WHERE oppgave_id = :id AND timereg_aktiv = 1");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            $sum = $stmt->fetch(PDO::FETCH_ASSOC);
            return $sum["sum"];
        } catch (Exception $e) {
            feil($e->getMessage());
        }
    }
    
    public function hentAktiveTimerPrOppgaveDesimal($id) {
        try {
            $stmt = $this->db->prepare("SELECT SUM(TIME_TO_SEC(TIMEDIFF(`timereg_stopp`, `timereg_start`))) as sum FROM timeregistrering WHERE oppgave_id = :id AND timereg_aktiv = 1");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            $sum = $stmt->fetch(PDO::FETCH_ASSOC);
            return round($sum["sum"] / 3600, 1); //hours, 1 decimal
        } catch (Exception $e) {
            feil($e->getMessage());
        }
    }
    
    public function calculatePercent($id) {
        $arbeidet = $this->hentAktiveTimerPrOppgaveDesimal($id);
        $estimat = $this->hentOppgave($id)->getTidsestimat();
        $percent = -1;
        if ($estimat > 0) {
            $percent = round($arbeidet * 100 / $estimat);
        }
        return $percent;
    }
    
    public function hentGodkjenteTimerPrOppgave($id) {
        try {
            $stmt = $this->db->prepare("SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(`timereg_stopp`, `timereg_start`)))) AS sum FROM timeregistrering WHERE oppgave_id = :id AND timereg_aktiv = 1 AND timereg_godkjent = 1");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            $sum = $stmt->fetch(PDO::FETCH_ASSOC);
            return $sum["sum"];
        } catch (Exception $e) {
            feil($e->getMessage());
        }
    }

    public function lagNyttEstimat($oppgave_id, $estimat, $bruker) {
        $stmt = $this->db->prepare("INSERT INTO forslag_tidsestimat (oppgave_id, estimat, bruker_id) VALUES (:oppgaveId, :estimat, :brukerId)");
        $stmt->bindParam(':oppgaveId', $oppgave_id, PDO::PARAM_INT);
        $stmt->bindParam(':brukerId', $bruker->getId(), PDO::PARAM_INT);
        $stmt->bindParam(':estimat', $estimat);
        execStmt($stms);
    }
    
    public function hentAlleEstimatForOppgave($oppgave_id) {
        $stmt = $this->db->prepare("SELECT * FROM forslag_tidsestimat WHERE oppgave_id = :id");
        $stmt->bindParam(':id', $oppgave_id, PDO::PARAM_INT);
        return getAlle($stmt, "Estimat", true);
    }

    public function endreEstimatForOppgave($oppgave_id, $nyttEstimat){
        $stmt = $this->db->prepare("UPDATE oppgave SET oppgave_tidsestimat = :estimat WHERE oppgave_id = :id");
        $stmt->bindParam(':id', $oppgave_id, PDO::PARAM_INT);
        $stmt->bindParam(':estimat', $nyttEstimat, PDO::PARAM_INT);
        return execStmt($stmt);
    }

    public function slettEstimatForslag($estimatId) {
        $stmt = $this->db->prepare("DELETE FROM forslag_tidsestimat WHERE estimat_id = :estimatId");
        $stmt->bindParam(':estimatId', $estimatId, PDO::PARAM_INT);
        return execStmt($stmt);
    }
}
?>