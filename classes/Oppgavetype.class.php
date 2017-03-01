<?php

class Oppgavetype {
    private $oppgavetype_id;
    private $oppgavetype_navn;
    
    
    public function __construct() {
        
    }
    
    public function getId() { return $this->oppgavetype_id; }
    public function getNavn() { return $this->oppgavetype_navn; }
}
