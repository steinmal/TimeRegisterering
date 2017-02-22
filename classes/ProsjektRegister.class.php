<?php
    class ProsjektRegister {
        
        private $db;

        public function __construct(PDO $db) {
            $this->db = $db;
        }
        
        public function hentAlleProsjekter() {
            $prosjekter = array();
            $stmt = $this->db->prepare("SELECT * FROM prosjekt");
            $stmt->execute();
            echo("execute");
            
            $i = 0;
            while ($post = $stmt->fetchObject('Prosjekt')) {
                $prosjekter[$i] = $post;
                $i++;
            }
                
            
            return $prosjekter;
        }
        public function lagProsjekt() {
            
        }
    }
?>