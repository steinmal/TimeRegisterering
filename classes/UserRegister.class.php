<?php

    class UserRegister {
        private $db;
        private $brukertyper;

        public function __construct(PDO $db) {
            $this->db = $db;
        }
        public function login($brukernavn, $passord) {
            $stmt = $this->db->prepare("SELECT * FROM bruker WHERE bruker_epost=:email");
            $stmt->bindparam(':email', $brukernavn, PDO::PARAM_STR);
            $stmt->execute();
            
            $bruker = $stmt->fetchObject('User');
            
            if($bruker != null && password_verify($passord, $bruker->getPassord())) {
                    $_SESSION['innlogget'] = true;
                    $_SESSION['bruker'] = $bruker;
                    $_SESSION['brukerTilgang'] = $this->getBrukertype($bruker->getBrukertype());
                    return true;
                }
            
            echo "Feil brukernavn/passord";
            return false;
        }
        
        
        // ----- Ikke ferdig
        public function opprettBruker($bruker) {
            $stmt = $this->db->prepare("INSERT INTO `bruker` (bruker_navn, bruker_epost, bruker_telefon, bruker_passord, bruker_registreringsdato, brukertype_id, bruker_aktivert)
            VALUES (:navn, :epost, :telefonnummer, :passord, now(), 4, 1)");
  
            $stmt->bindParam(':navn', $bruker->getBrukerNavn(), PDO::PARAM_STR);
            $stmt->bindParam(':epost', $bruker->getBrukerEpost(), PDO::PARAM_STR);
            $stmt->bindParam(':telefonnummer', $bruker->getBrukerTelefon(), PDO::PARAM_INT);
            $stmt->bindParam(':passord', $bruker->getPassord(), PDO::PARAM_STR);
            
            $stmt->execute();
        }
        
        public function redigerBruker($bruker){
            $stmt = $this->db->prepare("UPDATE bruker SET brukertype_id=:type, bruker_navn=:navn, bruker_epost=:epost, bruker_telefon=:telefon, bruker_passord=:passord WHERE bruker_id=:id");
            
            $stmt->bindParam(':id', $bruker->getBrukerId(), PDO::PARAM_INT);
            $stmt->bindParam(':type', $bruker->getBrukerType(), PDO::PARAM_INT);
            $stmt->bindParam(':navn', $bruker->getBrukerNavn(), PDO::PARAM_STR);
            $stmt->bindParam(':epost', $bruker->getBrukerEpost(), PDO::PARAM_STR);
            $stmt->bindParam(':telefon', $bruker->getBrukerTelefon(), PDO::PARAM_INT);
            $stmt->bindParam(':passord', $bruker->getPassord(), PDO::PARAM_STR);
            
            $stmt->execute();
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
                return $bruker;
            }
            
        }
        
        public function getAlleBrukertyper() {
            $brukere = array();
            $stmt = $this->db->prepare("SELECT * FROM brukertype");
            $stmt->execute();

            while($brukertype = $stmt->fetchObject('Brukertype')){
                $brukertyper[$brukertype->getId()] = $brukertype;
            }
            return $brukertyper;
        }
        
        public function getBrukertype($brukertype_id) {
            if ($this->brukertyper == null)
                $this->brukertyper = $this->getAlleBrukertyper();

            if (!isset($this->brukertyper[$brukertype_id]))
                throw new InvalidArgumentException('Usertype not defined: ' . $brukertype_id);

            return $this->brukertyper[$brukertype_id];
        }

    }