<?php
    class Brukertype {
        private $brukertype_id;
        private $brukertype_navn;
        private $brukertype_teamleder;
        private $brukertype_prosjektadmin;
        private $brukertype_brukeradmin;
        private $brukertype_systemadmin;
        private $brukertype_product_owner;

        public function __construct(){
        }

        public function getId() { return $this->brukertype_id; }
        public function getNavn() { return $this->brukertype_navn; }
        public function isTeamleder() { return $this->brukertype_teamleder; }
        public function isProsjektadmin() { return $this->brukertype_prosjektadmin; }
        public function isBrukeradmin() { return $this->brukertype_brukeradmin; }
        public function isSystemadmin() { return $this->brukertype_systemadmin; }
        public function isProductOwner() { return $this->brukertype_product_owner; }
    }