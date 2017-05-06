<?php
require_once("classhelper.php");

class TimeregistreringRegister {
    private $typeName = "Timeregistrering";
    
    private $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function hentAlleTimeregistreringer() {
        $stmt = $this->db->prepare("SELECT * FROM timeregistrering");
        return getAlle($stmt, $this->typeName);
    }

    public function hentTimeregFraOppgave($oppgave_id){
        $stmt = $this->db->prepare("SELECT * FROM timeregistrering WHERE oppgave_id=:id");
        $stmt->bindParam(':id', $oppgave_id, PDO::PARAM_INT);
        return getAlle($stmt, $this->typeName);
    }

    public function lagTimeregistrering($oppgave_id, $bruker_id, $timereg_dato, $timereg_start, $timereg_stopp, $timereg_automatisk, $timereg_pause=0, $timereg_kommentar="") {     
        $stmt = $this->db->prepare("INSERT INTO `timeregistrering` (bruker_id, oppgave_id, timereg_dato, timereg_start, timereg_stopp, timereg_tilstand, timereg_automatisk, timereg_pause, timereg_kommentar)
        VALUES (:bruker_id, :oppgave_id, :dato, :start, :stopp, :tilstand, :automatisk, :pause, :kommentar)");
        $timeregTilstand = $timereg_automatisk ? 0 : 1;
        $stmt->bindParam(':oppgave_id', $oppgave_id, PDO::PARAM_INT);
        $stmt->bindParam(':bruker_id', $bruker_id, PDO::PARAM_INT);
        $stmt->bindParam(':dato', $timereg_dato);
        $stmt->bindParam(':start', $timereg_start);  
        $stmt->bindParam(':stopp', $timereg_stopp);
        $stmt->bindParam(':tilstand', $timeregTilstand, PDO::PARAM_INT);
        $stmt->bindParam(':automatisk', $timereg_automatisk, PDO::PARAM_INT);
        $stmt->bindParam(':pause', $timereg_pause, PDO::PARAM_INT);
        $stmt->bindParam(':kommentar', $timereg_kommentar, PDO::PARAM_STR);
        return execStmt($stmt);
    }
    
    public function startTimeReg($oppgave_id, $bruker_id){
        $stmt = $this->db->prepare(
            "INSERT INTO timeregistrering (bruker_id, oppgave_id, timereg_status, timereg_dato, timereg_start, timereg_stopp, timereg_automatisk, timereg_godkjent)
            VALUES (:bId, :oId, 0, CURDATE(), NOW(), NOW(), 1, 0)");
        $stmt->bindParam(':oId', $oppgave_id, PDO::PARAM_INT);
        $stmt->bindParam(':bId', $bruker_id, PDO::PARAM_INT);
        $stmt->execute();
        return execStmt($stmt);
    }
    
    public function pauserTimeReg($id){
        $stmt = $this->db->prepare("UPDATE timeregistrering SET timereg_stopp=NOW(), timereg_status=1, timereg_redigeringsdato=NOW() WHERE timereg_id=:id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return execStmt($stmt);
    }
    
    public function fortsettTimeReg($id){
        $reg = $this->hentTimeregistrering($id);
        $pause = $reg->getPause();
        if($reg->getStatus() == 1){
            $pause += $this->beregnPause($reg->getTil(), date('H:i'));
        }
        $stmt = $this->db->prepare("UPDATE timeregistrering SET timereg_status=2, timereg_pause=:pause, timereg_redigeringsdato=NOW() WHERE timereg_id=:id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':pause', $pause, PDO::PARAM_INT);
        return execStmt($stmt);
    }
    
    public function stoppTimeReg($id){
        $reg = $this->hentTimeregistrering($id);
        $pause = $reg->getPause();
        if($reg->getStatus() == 1){
            $pause += $this->beregnPause($reg->getTil(), date('H:i'));
        }
        $stmt = $this->db->prepare(
            "UPDATE timeregistrering SET timereg_stopp=NOW(), timereg_pause=:pause, timereg_status=3, timereg_godkjent=1, timereg_redigeringsdato=NOW() WHERE timereg_id=:id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':pause', $pause, PDO::PARAM_INT);
        return execStmt($stmt);
    }
    
    private function beregnPause($tid1, $tid2){
        $diff = (new DateTime($tid1))->diff(new DateTime($tid2));
        return $diff->i;
    }

    public function hentTimeregistrering($id) {
        $stmt = $this->db->prepare("SELECT * FROM timeregistrering WHERE timereg_id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return getEn($stmt, $this->typeName);
    }
    
    public function hentTimeregistreringerFraBruker($bruker_id, $datefrom = "", $dateto = "") {
        $stmt = $this->db->prepare(
            "SELECT t.*, o.oppgave_navn, ot.oppgavetype_navn, b.bruker_navn"
            . " FROM timeregistrering t"
            . " INNER JOIN oppgave o ON o.oppgave_id=t.oppgave_id"
            . " INNER JOIN oppgavetype ot on ot.oppgavetype_id=o.oppgavetype_id"
            . " INNER JOIN bruker b on b.bruker_id=t.bruker_id"
            . " WHERE t.bruker_id = :id"
            . ($datefrom && $dateto ? " AND (t.timereg_dato BETWEEN :datefrom AND :dateto)" : ""));
        $stmt->bindParam(':id', $bruker_id, PDO::PARAM_INT);
        if($datefrom && $dateto){
            $stmt->bindParam(':datefrom', $datefrom, PDO::PARAM_STR);
            $stmt->bindParam(':dateto', $dateto, PDO::PARAM_STR);
        }
        return getAlle($stmt, $this->typeName);
    }

    public function hentTimeregistreringerFraTeam($team_id, $datefrom = "", $dateto = "") {
        $stmt = $this->db->prepare("SELECT t.*, o.oppgave_navn, ot.oppgavetype_navn, b.bruker_navn"
            . " FROM timeregistrering t"
            . " INNER JOIN oppgave o ON o.oppgave_id=t.oppgave_id"
            . " INNER JOIN oppgavetype ot on ot.oppgavetype_id=o.oppgavetype_id"
            . " INNER JOIN bruker b on b.bruker_id=t.bruker_id"
            . " INNER JOIN fase f on f.fase_id=o.fase_id"
            . " INNER JOIN prosjekt p on p.prosjekt_id=f.prosjekt_id"
            . " WHERE p.team_id = :id"
            . ($datefrom && $dateto ? " AND (t.timereg_dato BETWEEN :datefrom AND :dateto)" : ""));
        $stmt->bindParam(':id', $team_id, PDO::PARAM_INT);
        if ($datefrom && $dateto) {
            $stmt->bindParam(':datefrom', $datefrom, PDO::PARAM_STR);
            $stmt->bindParam(':dateto', $dateto, PDO::PARAM_STR);
        }
        return getAlle($stmt, $this->typeName);
    }

    public function hentTimeregistreringerFraProsjekt($prosjekt_id){
        $stmt = $this->db->prepare("
                SELECT * FROM timeregistrering WHERE oppgave_id IN (
                SELECT `oppgave_id` FROM `oppgave` WHERE `oppgave`.`fase_id` IN (
                SELECT `fase_id` FROM `fase` WHERE `fase`.`prosjekt_id`=:pId))
                ");
        $stmt->bindParam(':pId', $prosjekt_id, PDO::PARAM_INT);
        return getAlle($stmt, $this->typeName);
    }

    public function hentAktiveTimeregistreringer($bruker_id){       // endring: henter alle som ikke er deaktivert (dvs godkjente, venter godkjenning, avviste og gjenopprettede)
        $stmt = $this->db->prepare("SELECT * FROM timeregistrering WHERE bruker_id=:id AND timereg_status < 3 AND timereg_tilstand != 3");
        $stmt->bindParam(':id', $bruker_id, PDO::PARAM_INT);
        return getAlle($stmt, $this->typeName);
    }

    public function brukerKanRedigere($id, $TeamReg){
        if ($_SESSION['brukerTilgang']->isProsjektadmin()) return true;
        $team = $TeamReg->hentTeamFraTimeregistrering($id);
        if ($team->getLeder() == $_SESSION['bruker']->getId()) return true;
        return false;
    }

    public function kopierTimeregistrering($timeregId) { //kopierte timeregistreringer får tilstand ikke godkjent (venter godkjenning)  
        // TODO: Refactor vha hentTimeregistrering -> gjør endringer på denne -> lagTimeregistrering
        $opprinneligTime = $this->hentTimeregistrering($timeregId);
        $stmt = $this->db->prepare("INSERT INTO timeregistrering (bruker_id, oppgave_id, timereg_dato, timereg_start, timereg_stopp, timereg_pause, timereg_status, timereg_tilstand, timereg_automatisk, timereg_kommentar) 
        VALUES (:bID, :oID, :dato, :start, :stopp, :pause, :status, :tilstand, :automatisk, :kommentar)");
        $oID = $opprinneligTime->getOppgaveId();
        $stmt->bindParam(':oID', $oID, PDO::PARAM_INT);
        $bID = $opprinneligTime->getBrukerId();
        $stmt->bindParam(':bID', $bID, PDO::PARAM_INT);
        $dato = $opprinneligTime->getDato();
        $stmt->bindParam(':dato', $dato);
        $fra = $opprinneligTime->getFra();
        $stmt->bindParam(':start', $fra);
        $til = $opprinneligTime->getTil();
        $stmt->bindParam(':stopp', $til);
        $pause = $opprinneligTime->getPause();
        $stmt->bindParam(':pause', $pause);
        $status = $opprinneligTime->getStatus();
        $stmt->bindParam(':status', $status, PDO::PARAM_INT);
        $tilstand = 1;
        $stmt->bindParam(':tilstand', $tilstand, PDO::PARAM_INT);
        $automatisk = $opprinneligTime->getAutomatisk();
        $stmt->bindParam(':automatisk', $automatisk, PDO::PARAM_INT);
        $kommentar = $opprinneligTime->getKommentar();
        $stmt->bindParam(':kommentar', $kommentar, PDO::PARAM_STR);
        if(execStmt($stmt)){
            $kopiId = $this->db->lastInsertId();
            $kopiTime = $this->hentTimeregistrering($kopiId);
            return $kopiTime;
        }
    }
    
    
    public function deaktiverTimeregistrering($timeregId) {     //tilstand 3 = deaktivert  
        $stmt = $this->db->prepare("UPDATE timeregistrering SET timereg_tilstand=3, timereg_redigeringsdato=now() WHERE timereg_id=:id");
        $stmt->bindParam(':id', $timeregId, PDO::PARAM_INT);
        return execStmt($stmt);
    }

    public function gjenopprettTimeregistrering($timeregId, $deaktiv=false) {       // tilstand 4 = gjenopprettet (Deaktiv), tilstand 5 = gjenopprettet, venter godkjenning
        if ($deaktiv) {
            $tilstand = 4;
        } else {
            $tilstand = 5;
        }
        $stmt = $this->db->prepare("UPDATE timeregistrering SET timereg_tilstand=:tilstand, timereg_redigeringsdato=now() WHERE timereg_id=:id");
        $stmt->bindParam(':id', $timeregId, PDO::PARAM_INT);
        $stmt->bindParam(':tilstand', $tilstand, PDO::PARAM_INT);
        return execStmt($stmt);
    }


    public function godkjennTimeregistrering($timeregId){       //tilstand 0 = godkjent
        $stmt = $this->db->prepare("UPDATE `timeregistrering` SET timereg_tilstand=0 WHERE timereg_id=:id");
        $stmt->bindParam(':id', $timeregId, PDO::PARAM_INT);
        return execStmt($stmt);
    }
    
    public function avvisTimeregistrering($timeregId){      //tilstand 2 = avvist
        $stmt = $this->db->prepare("UPDATE `timeregistrering` SET timereg_tilstand=2 WHERE timereg_id=:id");
        $stmt->bindParam(':id', $timeregId, PDO::PARAM_INT);
        return execStmt($stmt);
    }
    
    public function oppdaterTimeregistrering($timeId, $dato, $fra, $til, $pause, $kommentar, $godkjent=0) {
        $stmt = $this->db->prepare("UPDATE timeregistrering SET timereg_dato=:dato, timereg_start=:start, timereg_stopp=:slutt, timereg_pause=:pause, timereg_kommentar=:komm, timereg_godkjent=:godkjent, timereg_redigeringsdato=now() WHERE timereg_id=:id");
        $stmt->bindParam(':id', $timeId, PDO::PARAM_INT);
        $stmt->bindParam(':dato', $dato);
        $stmt->bindParam(':start', $fra);
        $stmt->bindParam(':slutt', $til);
        $stmt->bindParam(':pause', $pause, PDO::PARAM_INT);
        $stmt->bindParam(':komm', $kommentar, PDO::PARAM_STR);
        $stmt->bindParam(':godkjent', $godkjent, PDO::PARAM_INT);
        return execStmt($stmt);
    }
}
?>