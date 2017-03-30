<?php
    class TeamRegister {
        private $db;
        
        
        public function __construct(PDO $db) {
            $this->db = $db;
        }
        
        public function hentAlleTeam() {
            $teamListe = array();
            try {
                $stmt = $this->db->prepare("SELECT * FROM team");
                $stmt->execute();
                
                while($team = $stmt->fetchObject('Team')) {
                    $teamListe[] = $team;
                }
            } catch (Exception $e) {
                $this->Feil($e->getMessage());
            }
            return $teamListe;
        }        
        public function hentTeam($id) {
            try {
                $stmt = $this->db->prepare("SELECT * FROM team WHERE team_id=:id");
                $stmt->bindparam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                
                if($team = $stmt->fetchObject('Team')) {
                    return $team;
                }
            } catch (Exception $e) {
                $this->Feil($e->getMessage());
            }
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
                $this->Feil($e->getMessage());
            }
            return $teamId;
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
                $this->Feil($e->getMessage());
            }
            return $teamId;
        }

        public function hentTeamMedlemmer($team_id, $UserReg) {
            $brukere = array();
            try {
                $stmt = $this->db->prepare("SELECT bruker_id FROM teammedlemskap WHERE team_id=:team_id");
                $stmt->bindparam(':team_id', $team_id, PDO::PARAM_INT);
                $stmt->execute();
                    
                while($bruker = $stmt->fetch()) {
                    $brukere[] = $UserReg->hentBruker($bruker['bruker_id']);
                }
            } catch (Exception $e) {
                $this->Feil($e->getMessage());
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
                $this->Feil($e->getMessage());
            }
            return $brukerIds;
        }
        
        
        private function Feil($feilmelding) {
            print "<h2>Oisann... Noe gikk galt</h2>";
            print "<h4>$feilmelding</h4>";
        }
}