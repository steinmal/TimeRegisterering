<?php

    class Timeregistrering {
        private $timereg_id;
        private $bruker_id;
        private $oppgave_id;
        private $timereg_dato;
        private $timereg_start;
        private $timereg_stopp;
        private $timereg_registreringsdato;
        private $timereg_aktiv;
        private $timereg_automatisk;
        private $timereg_godkjent;
        
        function __construct() {
            
        }
        
        public function getTimeregId() { return $this->timereg_id; }
        public function getBrukerId() { return $this->bruker_id; }
        public function getOppgaveId() { return $this->oppgave_id; }
        public function getDato() { return $this->timereg_dato; }
        public function getFra() { return $this->timereg_start; }
        public function getTil() { return $this->timereg_stopp; }
        public function getRegistreringsDato() { return $this->timereg_registreringdato; }
        public function getAktiv() { return $this->timereg_aktiv; }
        public function getAutomatisk() { return $this->timereg_automatisk; }
        public function getGodkjent() { return $this->timereg_godkjent; }
        
        public function getGodkjentTekst() {
            if ($this->timereg_godkjent) {
                return "Godkjent";
            } else {
                return "Ikke godkjent";
            }
        }
    }