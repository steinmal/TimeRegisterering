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
            $stmt = $this->db->prepare("INSERT INTO `prosjekt` ('foreldre_prosjekt_id', 'prosjekt_navn', 'prosjekt_leder', 'prosjekt_startdat', 'prosjekt_sluttdato', 'prosjekt_beskrivelse', 'team_id', 'prosjekt_product_owner', 'prosjekt_registrerings_dato')
            VALUES (:foreldre_id, :navn, :leder, :startdato, :sluttdato, :beskrivelse, :team_id, :product_owner, now())");
            $stmt->bindParam(':foreldre_id', $prosjekt->getProsjektParent(), PDO::PARAM_INT);
            $stmt->bindParam(':navn', $prosjekt->getProsjektNavn(), PDO::PARAM_STR);
            $stmt->bindParam(':leder', $prosjekt->getProsjektLeder(), PDO::PARAM_INT);
            $stmt->bindParam(':startdato', $prosjekt->getProsjektStartDato());
            $stmt->bindParam(':sluttdato', $prosjekt->getProsjektSluttDato());
            $stmt->bindParam(':beskrivelse', $prosjekt->getProsjektBeskrivelse(), PDO::PARAM_STR);
            $stmt->bindParam(':team_id', $prosjekt->getProsjektTeam(), PDO::PARAM_INT);
            $stmt->bindParam(':product_owner', $prosjekt->getProsjektProductOwner(), PDO::PARAM_STR);
            $stmt->execute();
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