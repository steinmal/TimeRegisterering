<?php
    class Team {
        private $team_id;
        private $team_leder;
        private $team_navn;
        
        public function getNavn() { return $this->team_navn; }
        public function getLeder() { return $this->team_leder; }
        public function getId() { return $this->team_id; }
    }