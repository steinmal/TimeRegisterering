<?php

require_once("classhelper.php");

class TeamRegister {
    private $db;
    private $typeName = "Team";
    
    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function lagTeam($team) {
        $teamNavn = $team->getNavn();
        $teamLeder = $team->getLeder();
    
        $stmt = $this->db->prepare("INSERT INTO team (team_navn, team_leder) VALUES (:teamNavn, :teamLeder)");
        $stmt->bindParam(':teamNavn', $teamNavn, PDO::PARAM_STR);
        $stmt->bindParam(':teamLeder', $teamLeder, PDO::PARAM_INT);
        return execStmtReturnId($stmt, $this->db);
    }
    
    public function redigerTeam($team) {
        $teamId = $team->getId();
        $teamNavn = $team->getNavn();
        $teamLeder = $team->getLeder();
        $stmt = $this->db->prepare("UPDATE team SET team_navn=:navn, team_leder=:leder WHERE team_id=:id");

        $stmt->bindParam(':navn', $teamNavn, PDO::PARAM_STR);
        $stmt->bindParam(':leder', $teamLeder, PDO::PARAM_INT);
        $stmt->bindParam(':id', $teamId, PDO::PARAM_INT);
        return execStmt($stmt);
    }
    
    public function hentAlleTeam() {
        $stmt = $this->db->prepare("SELECT * FROM team");
        return getAlle($stmt, $this->typeName);
    }
    
    public function hentTeam($id) {
        $stmt = $this->db->prepare("SELECT * FROM team WHERE team_id=:id");
        $stmt->bindparam(':id', $id, PDO::PARAM_INT);
        return getEn($stmt, $this->typeName);
    }
    
    public function hentTeamIdFraBruker($brukerId) {
        $teamId = array();
        try {
            $stmt = $this->db->prepare("SELECT team_id FROM teammedlemskap WHERE bruker_id=:bId");
            $stmt->bindParam(':bId', $brukerId, PDO::PARAM_INT);
            $stmt->execute();
            
            while ($id = $stmt->fetch()) {
                $teamId[] = $id['team_id'];
            }
        } catch (Exception $e) {
            feil($e->getMessage());
        }
        return $teamId;
    }
    public function hentTeamFraTimeregistrering($id) {
        $stmt = $this->db->prepare("SELECT team.*"
            . " FROM timeregistrering t"
            . " INNER JOIN oppgave o ON o.oppgave_id=t.oppgave_id"
            . " INNER JOIN fase f on f.fase_id=o.fase_id"
            . " INNER JOIN prosjekt p on p.prosjekt_id=f.prosjekt_id"
            . " INNER JOIN team on team.team_id=o.team_id"
            . " WHERE t.timereg_id = :id");
        $stmt->bindparam(':id', $id, PDO::PARAM_INT);
        return getEn($stmt, $this->typeName);
    }
    public function hentAlleTeamledere($BrukerReg) {
        $brukere = array();
        try {
            $stmt = $this->db->prepare("SELECT b.*"
            . " FROM bruker b"
            . " INNER JOIN brukertype bt ON bt.brukertype_id=b.brukertype_id"
            . " WHERE bt.brukertype_teamleder=1");
            $stmt->execute();

            while($bruker = $stmt->fetch()) {
                $brukere[] = $BrukerReg->hentBruker($bruker['bruker_id']);
            }
        } catch (Exception $e) {
            feil($e->getMessage());
        }
        return $brukere;
    }
    
    public function getTeamMedlemmerId($team_id) {
        $brukerIds = array();
        try {
            $stmt = $this->db->prepare("SELECT bruker_id FROM teammedlemskap WHERE team_id=:teamId");
            $stmt->bindparam(':teamId', $team_id, PDO::PARAM_INT);
            $stmt->execute();
            
            while($bruker_id = $stmt->fetch()) {
                $brukerIds[] = $bruker_id['bruker_id'];
            }
        } catch (Exception $e) {
            feil($e->getMessage());
        }
        return $brukerIds;
    }

    public function getTeamIdFraTeamleder($brukerId) {
        $teamId = array();
        try {
            $stmt = $this->db->prepare("SELECT team_id FROM team WHERE team_leder=:brukerId");
            $stmt->bindParam(':brukerId', $brukerId, PDO::PARAM_INT);
            $stmt->execute();
   
            while ($id = $stmt->fetch()) {
                $teamId[] = $id['team_id'];
            }
        } catch (Exception $e) {
            feil($e->getMessage());
        }
        return $teamId;
    }

    // TODO: Burde kanskje være i BrukerRegister ettersom den returnerer brukere. Funksjonen brukes ikke?
    public function hentTeamMedlemmer($team_id) {
        $stmt = $this->db->prepare("SELECT * FROM brukere WHERE bruker_id IN (SELECT bruker_id FROM teammedlemskap WHERE team_id=:team_id)");
        $stmt->bindparam(':team_id', $team_id, PDO::PARAM_INT);
        return getAlle($stms, "Bruker");
    }
    

    public function harTeamlederEtTeam($bruker_id) {
        try {
            $stmt = $this->db->prepare("SELECT count(*) FROM team WHERE team_leder=:brukerId");
            $stmt->bindParam('brukerId', $bruker_id, PDO::PARAM_INT);
            $stmt->execute();

            $num = $stmt->fetchColumn();
            return $num;
        }
        catch (Exception $e) {
            feil($e->getMessage());
        }
    }

    public function antallMedlemmerTeam($team_id) {
        try {
            $stmt = $this->db->prepare("SELECT count(*) FROM teammedlemskap WHERE team_id=:teamId");
            $stmt->bindParam('teamId', $team_id, PDO::PARAM_INT);
            $stmt->execute();

            $num = $stmt->fetchColumn();
            return $num;
        }
        catch (Exception $e) {
            feil($e->getMessage());
        }
    }

    public function slettMedlemskap($bruker_id, $team_id) {
        $stmt = $this->db->prepare("DELETE FROM teammedlemskap WHERE bruker_id = :brukerId and $team_id = :teamId");
        $stmt->bindParam('teamId', $team_id, PDO::PARAM_INT);
        $stmt->bindParam('brukerId', $bruker_id, PDO::PARAM_INT);
        return execStmt($stmt);
    }

    public function leggTilMedlem($bruker_id, $team_id) {
        $stmt = $this->db->prepare("INSERT INTO teammedlemskap (bruker_id, team_id) VALUES (:brukerId, :teamId)");
        $stmt->bindParam(':teamId', $team_id, PDO::PARAM_INT);
        $stmt->bindParam(':brukerId', $bruker_id, PDO::PARAM_INT);
        return execStmt($stmt);
    }
}