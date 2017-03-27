<?php
    class Oppgave {
        private $oppgave_id;
        private $foreldre_oppgave_id;
        private $oppgavetype_id;
        private $fase_id;
        private $oppgave_navn;
        private $oppgave_tidsestimat;
        private $oppgave_periode;
        
        function __construct() {
            
        }
        
        public function getOppgaveId() { return $this->oppgave_id; }
        public function getParentId() { return $this->foreldre_oppgave_id; }
        public function getOppgaveNavn() { return $this->oppgave_navn; }
        public function getTidsestimat() { return $this->oppgave_tidsestimat; }
        public function getOppgavetype() { return $this->oppgavetype_id; }
        public function getFaseId() { return $this->fase_id; }
        public function getPeriode() { return $this->oppgave_periode; }

    }