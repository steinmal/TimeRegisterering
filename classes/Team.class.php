<?php
    class Team {
        private $team_id;
        private $team_leder;
        private $team_navn;
        
        public function getTeamNavn() { return $this->team_navn; }
        public function getTeamLeder() { return $this->team_leder; }
    }