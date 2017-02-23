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
        
        public function __construct() {
            
        }
        
        public function getId() { return $this->prosjekt_id; }
        public function getProsjektNavn() { return $this->prosjekt_navn; }
        public function getProsjektParent() { return $this->foreldre_prosjekt_id; }
        //public function getProsjektParent() { return ProsjektRegister::hentProsjekt($this->foreldre_prosjekt_id); }
        public function getProsjektLeder() { return $this->prosjekt_leder; }
        public function getProsjektTeam() { return $this->team_id; }
        public function getProsjektStartDato() { return $this->prosjekt_startdato; }
        public function getProsjektSluttDato() { return $this->prosjekt_sluttdato; }
        public function getProsjektBeskrivelse() { return $this->prosjekt_beskrivelse; }
        public function getProsjektProductOwner() { return $this->prosjekt_product_owner; }
        public function getProsjektRegistreringsDato() { return $this->prosjekt_registrerings_dato; }
        
        public function setProsjektId($id) { $this->prosjekt_id = $id; }
        public function setProsjektNavn($navn) { $this->prosjekt_navn = $navn;}
        public function setProsjektParent($parent) { $this->foreldre_prosjekt_id = $parent;}
        public function setProsjektLeder($leder) { $this->prosjekt_leder = $leder;}
        public function setProsjektTeam($team) { $this->team_id = $team;}
        public function setProsjektStartDato($startdato) { $this->prosjekt_startdato = $startdato;}
        public function setProsjektSluttDato($sluttdato) { $this->prosjekt_sluttdato = $sluttdato;}
        public function setProsjektBeskrivelse($beskrivelse) { $this->prosjekt_beskrivelse = $beskrivelse;}
        public function setProsjektProductOwner($product_owner) { $this->prosjekt_product_owner = $prodct_owner;}
        
        
        
        
    }