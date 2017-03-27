<?php
    class Fase {
        private $fase_id;
        private $fase_navn;
        private $prosjekt_id;
        private $fase_startdato;
        private $fase_sluttdato;
        private $fase_tilstand;
        static $tilstander = array("Ikke påbegynt", "Aktiv", "Forsinket", "Ferdig");
        
        public function __construct(){
            
        }
        
        public function getId(){ return $this->fase_id; }
        public function getNavn(){ return $this->fase_navn; }
        public function getProsjektId(){ return $this->prosjekt_id; }
        public function getStartDato(){ return $this->fase_startdato; }
        public function getSluttDato(){ return $this->fase_sluttdato; }
        public function getTilstand() { return $this->fase_tilstand; }

        public function setId($id){ $this->fase_id = $id; }
        public function setNavn($navn){ $this->fase_navn = $navn; }
        public function setProsjektId($id){ $this->prosjekt_id = $id; }
        public function setStartDato($dato){ $this->fase_startdato = $dato; }
        public function setSluttDato($dato){ $this->fase_sluttdato = $dato; }
        public function setTilstand($tilstand) { $this->fase_tilstand = $tilstand; }
        
    }