<?php
    class TeamRegister {
        private $db;
        
        
        public function __construct(PDO $db) {
            $this->db = $db;
        }
        public function hentTeam($id) {
            $stmt = $this->db->prepare("SELECT * FROM team WHERE team_id=:id");
            $stmt->bindparam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            if($team = $stmt->fetchObject('Team')) {
                return  $team;
            }
        }
        
        
        public function hentTeamIdFraBruker($brukerId) {
            $teamId = array();
            $stmt = $this->db->prepare("SELECT team_id FROM teammedlemskap WHERE bruker_id=:bId");
            $stmt->bindParam(':bId', $brukerId, PDO::PARAM_INT);
            $stmt->execute();
            
            while ($id = $stmt->fetch()) {
                $teamId[] = $id['team_id'];
            }
            
            return $teamId;
        }

        public function hentTeamMedlemmer($team_id, $UserReg) {
            $stmt = $this-db-prepare("SELECT bruker_id FROM teammedlemskap WHERE team_id=:team_id");
            $stmt->bindparam(':team_id', $team_id, PDO::PARAM_INT);
            $stmt->execute();
                
            $brukere = array();
            while($bruker_id = $stmt->fetch_assoc('bruker_id')) {
                $brukere[] = $UserReg.hentBruker($bruker_id);
            }
            return $brukere;
        }
    }