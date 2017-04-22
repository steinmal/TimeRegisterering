<?php
    class Team {
        private $team_id;
        private $team_leder;
        private $team_navn;
        
        public function getNavn() { return $this->team_navn; }
        public function getLeder() { return $this->team_leder; }
        public function getId() { return $this->team_id; }

        public function setId($id) { $this->team_id = $id; }
        public function setNavn($navn) { $this->team_navn = $navn; }
        public function setLeder($leder) { $this->team_leder = $leder; }
    }