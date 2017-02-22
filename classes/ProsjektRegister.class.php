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
        public function lagProsjekt($prosjekt) {
            $stmt = $this->db->prepare("INSERT INTO `prosjekt` () VALUES ()");
            
        }
        public function hentProsjekt($id) {
            $stmt = $this->db->prepare("SELECT * FROM prosjekt WHERE prosekt_id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            if ($prosjekt = $stmt->fetchObject('Prosjekt')) {
                return $prosjekt;
            }
        }
        
    }
?>