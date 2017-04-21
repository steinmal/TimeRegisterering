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
        private $timereg_tilstand;
        //private $timereg_aktiv;
        private $timereg_automatisk;
        //private $timereg_godkjent;
        private $timereg_kommentar;
        
        function __construct() {
            
        }
        
        public function getStatus(){ return $this->timereg_status; }
        public function getId() { return $this->timereg_id; }
        public function getBrukerId() { return $this->bruker_id; }
        public function getOppgaveId() { return $this->oppgave_id; }
        public function getDato() { return $this->timereg_dato; }
        public function getFra() { return $this->timereg_start; }
        public function getTil() { return $this->timereg_stopp; }
        public function getPause() { return $this->timereg_pause; }
        public function getRegistreringsDato() { return $this->timereg_registreringsdato; }
        public function getTilstand() { return $this->timereg_tilstand; }   // 0 = godkjent, 1 = venter godkj., 2 = avvist, 3 = deaktivert, 4 = gjenopprettet

        // bool-metoder burde hete is... istedet for get...
        //public function getAktiv() { return $this->timereg_aktiv; } //byttes ut med getTilstand
        public function getAutomatisk() { return $this->timereg_automatisk; }
        //public function getGodkjent() { return $this->timereg_godkjent; } //byttes ut med getTilstand

        public function getKommentar() { return $this->timereg_kommentar; }
        public function getHourAsDateInterval(){
            $starttid = DateTime::createFromFormat('H:i:s', $this->getFra());
            $stopptid = DateTime::createFromFormat('H:i:s', $this->getTil());
            $pause = DateInterval::createFromDateString($this->getPause() . " minutes");
            $stopptid->sub($pause);
            $diff = DateInterval::createFromDateString('0 seconds');
            if($starttid && $starttid){
                $diff = $starttid->diff($stopptid);
            }
            return $diff;
        }
        public function getHourString() {
            return $this->getHourAsDateInterval()->format("%H:%I");
        }
        
       /* public function getGodkjentTekst() {    //byttes ut med getTilstandTekst
            if ($this->timereg_godkjent) {
                return "Godkjent";
            } else {
                return "Ikke godkjent";
            }
        }*/
        
        public function getTilstandTekst() {
            switch ($this->timereg_tilstand) {
                case 0:
                    return "Godkjent";
                    break;
                case 1:
                    return "Venter godkjenning";
                    break;
                case 2:
                    return "Avvist";
                    break;
                case 3:
                    return "Deaktivert";
                    break;
                case 4:
                    return "Gjenopprettet";
                    break;
                default:
                    break;
            }
        }
    }