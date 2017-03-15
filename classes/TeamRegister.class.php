<?php
    class TeamRegister {
        private $db;
        
        public function __construct(PDO $db) {
            $this->db = $db;
        }
        public function hentTeam($id) {
            $stmt = $this->db->prepare("SELECT * FROM team WHERE team_id=:id");
            $stmt->bindparam(':id', $id, PDO::PARAM_STR);
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
                $teamId[] = (int) $id;
            }
            
            return $teamId;
        }

    }