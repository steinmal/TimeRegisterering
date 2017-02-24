<?php

    class UserRegister {
        private $db;

        public function __construct(PDO $db) {
            $this->db = $db;
        }
        public function login($brukernavn, $passord) {
            $stmt = $this->db->prepare("SELECT * FROM bruker WHERE bruker_epost=:email");
            $stmt->bindparam(':email', $brukernavn, PDO::PARAM_STR);
            $stmt->execute();
            
            $bruker = $stmt->fetchObject('User');
            
            if(password_verify($passord, $bruker->getPassord())) {
                    $_SESSION['innlogget'] = true;
                    $_SESSION['bruker'] = $user;
                    $_SESSION['brukerid'] = $id;
                    return true;
                }
            
            echo "Feil passord";
            return false;
        }
        public function hentAlleBrukere() {
            $brukere = array();
            $stmt = $this->db->prepare("SELECT * FROM bruker");
            $stmt->execute();
            
            $i = 0;
            while($bruker = $stmt->fetchObject('User')){
                $brukere[$i] = $bruker;
                $i++;
            }
            return $brukere;
        }
        public function hentBruker($id) {
            $stmt = $this->db->prepare("SELECT * FROM bruker WHERE bruker_id=:id");
            $stmt->bindparam(':id', $id, PDO::PARAM_STR);
            $stmt->execute();
            
            if($bruker = $stmt->fetchObject('User')) {
                return  $bruker;
            }
            
        }
    }