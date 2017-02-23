<?php
    class User {
        private $brukernavn;
        private $passord;
        private $bruker_id;
        
        function __construct($bruker_id,$brukernavn,$passord){
            $this->bruker_id = $bruker_id;
            $this->brukernavn = $brukernavn;
            $this->passord = $passord;
        }
        public function setPassword($newPassword) {
			$this->password = password_hash($newPassword, PASSWORD_DEFAULT);
			return $this;
		}
        public static function login($db, $brukernavn, $passord) {
            $stmt = $db->prepare("SELECT bruker_id, bruker_epost, bruker_passord FROM bruker WHERE bruker_epost=:email");
            $stmt->bindparam(':email', $brukernavn, PDO::PARAM_STR);
            $stmt->execute();
            
            if($rad = $stmt->fetch()) {
                $id = $rad['bruker_id'];
                $email = $rad['bruker_epost'];
                $hash = $rad['bruker_passord'];

                
            }
            if(password_verify($passord, $hash)) {
                    $_SESSION['innlogget'] = true;
                    $_SESSION['bruker'] = new User($id, $email, $passord);
                    return true;
                }
            
            echo "Feil passord";
            return false;
        }
        public static function hentAlleBrukere($db) {
            $brukere = array();
            $stmt = $db->prepare("SELECT * FROM bruker");
            $stmt->execute();
            
            if($rad = $stmt->fetch()) {
                $bruker_id = $rad['bruker_id']; 
                $brukernavn = $rad['bruker_navn'];
            }
            return $brukere;
        }
        
        public function getBrukerId() { return $this->bruker_id; }
    }