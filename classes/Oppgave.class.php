<?php
    class Oppgave {
        private $oppgave_id;
        private $foreldre_oppgave_id;
        private $prosjekt_id;
        private $oppgavetype_id;
        private $fase_id;
        private $oppgave_navn;
        private $oppgave_tidsestimat;
        private $oppgave_periode;
        
        function __construct() {
            
        }
        
        public function getOppgaveId() { return $this->oppgave_id; }
        public function getOppgaveNavn() { return $this->oppgave_navn; }

    }