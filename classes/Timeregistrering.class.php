<?php

    class Timeregistrering {
        private $timereg_id;
        private $bruker_id;
        private $oppgave_id;
        private $timereg_dato;
        private $timereg_start;
        private $timereg_slutt;
        private $timereg_registreringsdato;
        private $timereg_aktiv;
        private $timereg_automatisk;
        private $timereg_godkjent;
        
        function __construct() {
            
        }
        
        public function getTimeregId() { return $this->timereg_id; }
        
        
    }