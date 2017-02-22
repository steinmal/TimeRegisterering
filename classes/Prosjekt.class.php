<?php
    class Prosjekt {
        private $prosjektId;
        private $foreldreId;
        private $prosjektNavn;
        
        function __construct($prosjektId, $foreldreId, $prosjektNavn) {
            $this->prosjektId = $prosjektId;
            $this->foreldreId = $foreldreId;
            $this->prosjektNavn = $prosjektNavn;
        }
        
        public static function hentAlleProsjekter($db) {
            $prosjekter = array();
            $stmt = $db->prepare("SELECT prosjekt_id, foreldre_prosjekt_id, prosjekt_navn FROM prosjekt");
            $stmt->execute();
            
            while ($rad = $stmt->fetch()) {
                $prosjektId = $rad['prosjekt_id'];
                $foreldreId = $rad['foreldre_prosjekt_id'];
                $prosjektNavn = $rad['prosjekt_navn'];
                $prosjekter[] = new Prosjekt($prosjektId, $foreldreId, $prosjektNavn);
            }
            return $prosjekter;
        }
        
        public function getProsjektNavn() { return $this->prosjektNavn; }
        
        
    }