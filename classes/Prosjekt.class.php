<?php
    class Prosjekt {
        private $prosjekt_id;
        private $foreldre_prosjekt_id;
        private $prosjekt_navn;
        private $prosjekt_leder;
        private $prosjekt_startdato;
        private $prosjekt_sluttdato;
        private $prosjekt_beskrivelse;
        private $team_id;
        private $prosjekt_product_owner;
        private $prosjekt_registrerings_dato;
        private $prosjekt_arkivert;
        
        public function __construct() {
            
        }
        
        public function getId() { return $this->prosjekt_id; }
        public function getNavn() { return $this->prosjekt_navn; }
        public function getParent() { return $this->foreldre_prosjekt_id; }
        //public function getProsjektParent() { return ProsjektRegister::hentProsjekt($this->foreldre_prosjekt_id); }
        public function getLeder() { return $this->prosjekt_leder; }
        public function getTeam() { return $this->team_id; }
        public function getStartDato() { return $this->prosjekt_startdato; }
        public function getSluttDato() { return $this->prosjekt_sluttdato; }
        public function getBeskrivelse() { return $this->prosjekt_beskrivelse; }
        public function getProductOwner() { return $this->prosjekt_product_owner; }
        public function getRegistreringsDato() { return $this->prosjekt_registrerings_dato; }
        public function getStatus() { return $this->isArkivert() ? "Arkivert" : "Aktiv"; }
        
        public function isArkivert() { return $this->prosjekt_arkivert; }
        
        public function setId($id) { $this->prosjekt_id = $id; }
        public function setNavn($navn) { $this->prosjekt_navn = $navn;}
        public function setParent($parent) { $this->foreldre_prosjekt_id = $parent;}
        public function setLeder($leder) { $this->prosjekt_leder = $leder;}
        public function setTeam($team) { $this->team_id = $team;}
        public function setStartDato($startdato) { $this->prosjekt_startdato = $startdato;}
        public function setSluttDato($sluttdato) { $this->prosjekt_sluttdato = $sluttdato;}
        public function setBeskrivelse($beskrivelse) { $this->prosjekt_beskrivelse = $beskrivelse;}
        public function setProductOwner($product_owner) { $this->prosjekt_product_owner = $product_owner;}
        
        public function __toString() {
            return $this->prosjekt_id . " " . $this->prosjekt_navn . " " . $this->prosjekt_leder;
        }
    }