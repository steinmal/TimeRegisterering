<?php
    class TeamRegister {
        private $db;
        
        
        public function __construct(PDO $db) {
            $this->db = $db;
        }

        public function lagTeam($team) {
            $teamNavn = $team->getNavn();
            $teamLeder = $team->getLeder();

            try {
             $stmt = $this->db->prepare("INSERT INTO team (team_navn, team_leder) VALUES (:teamNavn, :teamLeder)");
             $stmt->bindParam(':teamNavn', $teamNavn, PDO::PARAM_STR);
             $stmt->bindParam(':teamLeder', $teamLeder, PDO::PARAM_INT);
             $stmt->execute();
            }
            catch(Exception $e) {
                $this->Feil($e->getMessage());
            }
        }
        public function redigerTeam($team) {
            $teamId = $team->getId();
            $teamNavn = $team->getNavn();
            $teamLeder = $team->getLeder();
            try {
                $stmt = $this->db->prepare("UPDATE team SET team_navn=:navn, team_leder=:leder WHERE team_id=:id");

                $stmt->bindParam(':navn', $teamNavn, PDO::PARAM_STR);
                $stmt->bindParam(':leder', $teamLeder, PDO::PARAM_INT);
                $stmt->bindParam(':id', $teamId, PDO::PARAM_INT);
                $stmt->execute();
            } catch (Exception $e) {
                $this->Feil($e->getMessage());
            }
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

        public function hentTeamMedlemmer($team_id, $BrukerReg) {
            $brukere = array();
            try {
                $stmt = $this->db->prepare("SELECT bruker_id FROM teammedlemskap WHERE team_id=:team_id");
                $stmt->bindparam(':team_id', $team_id, PDO::PARAM_INT);
                $stmt->execute();
                    
                while($bruker = $stmt->fetch()) {
                    $brukere[] = $BrukerReg->hentBruker($bruker['bruker_id']);
                }
            } catch (Exception $e) {
                $this->Feil($e->getMessage());
            }
            return $brukere;
        }

        public function hentAlleTeamledere($BrukerReg) {
            $brukere = array();
            try {
                $stmt = $this->db->prepare("SELECT * FROM bruker WHERE brukertype_id=3");
                $stmt->execute();

                while($bruker = $stmt->fetch()) {
                    $brukere[] = $BrukerReg->hentBruker($bruker['bruker_id']);
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

        public function harTeamlederEtTeam($bruker_id) {
            try {
                $stmt = $this->db->prepare("SELECT count(*) FROM team WHERE team_leder=:brukerId");
                $stmt->bindParam('brukerId', $bruker_id, PDO::PARAM_INT);
                $stmt->execute();

                $num = $stmt->fetchColumn();
                if($num > 0) {
                    return true;
                }
                else return false;
            }
            catch (Exception $e) {
                $this->Feil($e->getMessage());
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
                $this->Feil($e->getMessage());
            }
        }

        public function slettMedlemskap($bruker_id, $team_id) {
            try {
                $stmt = $this->db->prepare("DELETE FROM teammedlemskap WHERE bruker_id = :brukerId and $team_id = :teamId");
                $stmt->bindParam('teamId', $team_id, PDO::PARAM_INT);
                $stmt->bindParam('brukerId', $bruker_id, PDO::PARAM_INT);
                $stmt->execute();
            }
            catch (Exception $e) {
                $this->Feil($e->getMessage());
            }
        }

        public function leggTilMedlem($bruker_id, $team_id) {
            try {
                $stmt = $this->db->prepare("INSERT INTO teammedlemskap (bruker_id, team_id) VALUES (:brukerId, :teamId)");
                $stmt->bindParam(':teamId', $team_id, PDO::PARAM_INT);
                $stmt->bindParam(':brukerId', $bruker_id, PDO::PARAM_INT);
                $stmt->execute();
            }
        catch (Exception $e) {
                $this->Feil($e->getMessage());
            }
        }
        
        private function Feil($feilmelding) {
            print "<h2>Oisann... Noe gikk galt</h2>";
            print "<h4>$feilmelding</h4>";
        }
}