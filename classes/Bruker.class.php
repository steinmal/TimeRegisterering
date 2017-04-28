<?php
    class Bruker {
        private $bruker_navn;
        private $bruker_passord;
        private $bruker_id;
        private $brukertype_id;
        private $bruker_epost;
        private $bruker_telefon;
        private $bruker_aktivert;
        private $bruker_registreringsdato;
        
        public function __construct(){
        }
        public function setPassord($newPassword) {
			$this->bruker_passord = password_hash($newPassword, PASSWORD_DEFAULT);
			return $this;
		}
        
        public function getId() { return $this->bruker_id; }
        public function getNavn() { return $this->bruker_navn; }
        public function getPassord() { return $this->bruker_passord; }
        public function getBrukertype() { return $this->brukertype_id; }
        public function getTelefon() { return $this->bruker_telefon; }
        public function getEpost() { return $this->bruker_epost; }

        public function isAktivert() { return $this->bruker_aktivert > 0; }
        public function isAktivertTekst() { return $this->isAktivert() ? "Aktivert" : "Deaktivert"; }
        public function getRegistreringsdato() { return $this->bruker_registreringsdato; }
        
        public function setBrukertype($id) { $this->brukertype_id = $id; }
        public function setNavn($navn) { $this->bruker_navn = $navn; }
        public function setEpost($epost) { $this->bruker_epost = $epost; }
        public function setTelefon($telefonnummer) { $this->bruker_telefon = $telefonnummer; }
    }