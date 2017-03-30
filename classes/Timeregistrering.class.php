<?php

    class Timeregistrering {
        private $timereg_id;
        private $bruker_id;
        private $oppgave_id;
        private $timereg_dato;
        private $timereg_start;
        private $timereg_stopp;
        private $timereg_pause;
        private $timereg_registreringsdato;
        private $timereg_status;
        private $timereg_aktiv;
        private $timereg_automatisk;
        private $timereg_godkjent;
        private $timereg_kommentar;
        
        function __construct() {
            
        }
        
        public function getStatus(){ return $this->timereg_status; }
        public function getTimeregId() { return $this->timereg_id; }
        public function getBrukerId() { return $this->bruker_id; }
        public function getOppgaveId() { return $this->oppgave_id; }
        public function getDato() { return $this->timereg_dato; }
        public function getFra() { return $this->timereg_start; }
        public function getTil() { return $this->timereg_stopp; }
        public function getPause() { return $this->timereg_pause; }
        public function getRegistreringsDato() { return $this->timereg_registreringdato; }

        // bool-metoder burde hete is... istedet for get...
        public function getAktiv() { return $this->timereg_aktiv; }
        public function getAutomatisk() { return $this->timereg_automatisk; }
        public function getGodkjent() { return $this->timereg_godkjent; }

        public function getKommentar() { return $this->timereg_kommentar; }
        public function getHours() {
            $starttid = DateTime::createFromFormat('H:i:s', $this->getFra());
            $stopptid = DateTime::createFromFormat('H:i:s', $this->getTil());
            $pause = DateInterval::createFromDateString($this->getPause() . " minutes");
            $stopptid->sub($pause);
            $hours = "00:00";
            if ($starttid && $stopptid)
            {
                $diff = $stopptid->diff($starttid);
                $hours = $diff->format("%H:%I");
            }
            return $hours;
        }
        
        public function getGodkjentTekst() {
            if ($this->timereg_godkjent) {
                return "Godkjent";
            } else {
                return "Ikke godkjent";
            }
        }
    }