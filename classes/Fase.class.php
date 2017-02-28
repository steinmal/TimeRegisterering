<?php
    class Fase {
        private $fase_id;
        private $prosjekt_id;
        private $fase_navn;
        private $fase_startdato;
        private $fase_sluttdato;
        
        function __construct() {
            
        }
        
        public function getFaseId() { return $this->$fase_id; }
        public function getFaseNavn() { return $this->$fase_navn; }

    }