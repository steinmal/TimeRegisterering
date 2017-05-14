<?php
    class Oppgave {
        private $oppgave_id;
        private $foreldre_oppgave_id;
        private $oppgavetype_id;
        private $fase_id;
        private $oppgave_navn;
        private $oppgave_tidsestimat;
        private $oppgave_periode;
        private $oppgave_tilstand;

        function __construct() {
            
        }
        
        public function getId() { return $this->oppgave_id; }
        public function getParentId() { return $this->foreldre_oppgave_id; }
        public function getNavn() { return $this->oppgave_navn; }
        public function getTidsestimat() { return $this->oppgave_tidsestimat; }
        public function getType() { return $this->oppgavetype_id; }
        public function getFaseId() { return $this->fase_id; }
        public function getPeriode() { return $this->oppgave_periode; }

        public function setFaseID($id){ $this->fase_id = $id; }
        public function getTilstand() { return $this->oppgave_tilstand; }

        public static function getTilstander() {
            $tilstander = array("Ikke-påbegynt","Påbegynt", "Ferdig");
            return $tilstander;
        }

    }