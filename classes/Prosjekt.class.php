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
        
        function __construct() {
            
        }
        
        // public static function hentAlleProsjekter($db) {
        //     $prosjekter = array();
        //     $stmt = $db->prepare("SELECT * FROM prosjekt");
        //     $stmt->execute();
        //     echo("execute");
            
        //     while ($rad = $stmt->fetch()) { // Kan byttes ut med fetchObject('Prosjekt');
        //         $prosjektId = $rad['prosjekt_id'];
        //         $foreldreId = $rad['foreldre_prosjekt_id'];
        //         $prosjektNavn = $rad['prosjekt_navn'];
        //         $prosjektLeder = $rad['prosjekt_leder'];
        //         $prosjektTeam = $rad['team_id'];
        //         $prosjektStartDato = $rad['prosjekt_startdato'];
        //         $prosjektSluttDato = $rad['prosjekt_sluttdato'];
        //         $prosjektBeskrivelse = $rad['prosjekt_beskrivelse'];
                
        //         $prosjekter[] = new Prosjekt($prosjektId, $foreldreId, $prosjektNavn, $prosjektLeder, $prosjektTeam, $prosjektStartDato, $prosjektSluttDato, $prosjektBeskrivelse);
        //     }
            
        //     return $prosjekter;
        // }
        public function getId() { return $this->prosjekt_id; }
        public function getProsjektNavn() { return $this->prosjekt_navn; }
        public function getProsjektParent() { return $this->foreldre_prosjekt_id; }
        public function getProsjektLeder() { return $this->prosjekt_leder; }
        public function getProsjektTeam() { return $this->team_id; }
        public function getProsjektStartDato() { return $this->prosjekt_startdato; }
        public function getProsjektSluttDato() { return $this->prosjekt_sluttdato; }
        public function getProsjektBeskrivelse() { return $this->prosjekt_beskrivelse; }
        public function getProsjektProductOwner() { return $this->prosjekt_product_owner; }
        public function getProsjektRegistreringsDato() { return $this->prosjekt_registrerings_dato; }
        
        
        
    }