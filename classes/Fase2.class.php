<?php
    class Fase {
        private $fase_id;
        private $fase_navn;
        private $prosjekt_id;
        private $fase_startdato;
        private $fase_sluttdato;
        
        public function __construct(){
            
        }
        
        public function getFaseId(){ return $this->fase_id; }
        public function getFaseNavn(){ return $this->fase_navn; }
        public function getProsjektId(){ return $this->prosjekt_id; }
        public function getFaseStartDato(){ return $this->fase_startdato; }
        public function getFaseSluttDato(){ return $this->fase_sluttdato; }

        public function setFaseId($id){ $this->fase_id = $id; }
        public function setFaseNavn($navn){ $this->fase_navn = $navn; }
        public function setProsjektId($id){ $this->prosjekt_id = $id; }
        public function setFaseStartDato($dato){ $this->fase_startdato = $dato; }
        public function setFaseSluttDato($dato){ $this->fase_sluttdato = $dato; }
    }