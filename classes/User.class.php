<?php
    class User {
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
        
        public function getBrukerId() { return $this->bruker_id; }
        public function getBrukerNavn() { return $this->bruker_navn; }
        public function getPassord() { return $this->bruker_passord; }
        public function getBrukertype() { return $this->brukertype_id; }
        public function getBrukerTelefon() { return $this->bruker_telefon; }
        public function getBrukerEpost() { return $this->bruker_epost; }
        public function getBrukerRegistreringsdato() { return $this->bruker_registreringsdato; }
        public function isAktivert() { return $this->bruker_aktivert; }
        public function getRegistreringsdato() { return $this->bruker_registreringsdato; }
        
        public function setBrukerNavn($navn) { $this->bruker_navn = $navn; }
        public function setBrukerEpost($epost) { $this->bruker_epost = $epost; }
        public function setBrukerTelefon($telefonnummer) { $this->bruker_telefon = $telefonnummer; }        
    }