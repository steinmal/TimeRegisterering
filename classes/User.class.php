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
        
        function __construct(){
            
        }
        public function setPassword($newPassword) {
			$this->password = password_hash($newPassword, PASSWORD_DEFAULT);
			return $this;
		}
        
        public function getBrukerId() { return $this->bruker_id; }
        public function getBrukerNavn() { return $this->bruker_navn; }
        public function getPassord() { return $this->bruker_passord; }
    }