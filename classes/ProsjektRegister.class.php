<?php
    class ProsjektRegister {
        
        private $db;

        public function __construct(PDO $db) {
            $this->db = $db;
        }

        public function hentAlleProsjekt($hentArkiverte=true) {
            $prosjekter = array();
            if($hentArkiverte){
                $stmt = $this->db->prepare("SELECT * FROM prosjekt");
            }
            else{
                $stmt = $this->db->prepare("SELECT * FROM prosjekt WHERE prosjekt_arkivert=0");
            }
            $stmt->execute();

            $i = 0;
            while ($prosjekt = $stmt->fetchObject('Prosjekt')) {
                $prosjekter[$i] = $prosjekt;
                $i++;
            }

            return $prosjekter;
        }

        public function lagProsjekt($prosjekt) {
            $stmt = $this->db->prepare("
            INSERT INTO `prosjekt` (foreldre_prosjekt_id, prosjekt_navn, prosjekt_leder, prosjekt_startdato, prosjekt_sluttdato, prosjekt_beskrivelse, team_id, prosjekt_product_owner, prosjekt_registreringsdato)
                            VALUES (:foreldre_id, :navn, :leder, :startdato, :sluttdato, :beskrivelse, :team_id, :product_owner, now());
            INSERT INTO `fase` (fase_navn, prosjekt_id, fase_startdato, fase_sluttdato)
                        VALUES ('Backlog', LAST_INSERT_ID(), :startdato, :sluttdato)");

            $stmt->bindParam(':foreldre_id', $prosjekt->getProsjektParent(), PDO::PARAM_INT);
            $stmt->bindParam(':navn', $prosjekt->getProsjektNavn(), PDO::PARAM_STR);
            $stmt->bindParam(':leder', $prosjekt->getProsjektLeder(), PDO::PARAM_INT);
            $stmt->bindParam(':startdato', $prosjekt->getProsjektStartDato());
            $stmt->bindParam(':sluttdato', $prosjekt->getProsjektSluttDato());
            $stmt->bindParam(':beskrivelse', $prosjekt->getProsjektBeskrivelse(), PDO::PARAM_STR);
            $stmt->bindParam(':team_id', $prosjekt->getProsjektTeam(), PDO::PARAM_INT);
            $stmt->bindParam(':product_owner', $prosjekt->getProsjektProductOwner(), PDO::PARAM_STR);

            $id = $stmt->execute();
        }

        public function hentProsjekt($id) {
            $stmt = $this->db->prepare("SELECT * FROM prosjekt WHERE prosjekt_id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            if ($prosjekt = $stmt->fetchObject('Prosjekt')) {
                return $prosjekt;
            }
        }

        public function redigerProsjekt($prosjekt) {
            $stmt = $this->db->prepare("UPDATE prosjekt SET prosjekt_navn=:navn, prosjekt_leder=:leder, prosjekt_startdato=:startdato, prosjekt_sluttdato=:sluttdato, prosjekt_beskrivelse=:beskrivelse WHERE prosjekt_id=:id");
            
            $stmt->bindParam(':navn', $prosjekt->getProsjektNavn(), PDO::PARAM_STR);
            $stmt->bindParam(':leder', $prosjekt->getProsjektLeder(), PDO::PARAM_INT);
            $stmt->bindParam(':startdato', $prosjekt->getProsjektStartDato());
            $stmt->bindParam(':sluttdato', $prosjekt->getProsjektSluttDato());
            $stmt->bindParam(':beskrivelse', $prosjekt->getProsjektBeskrivelse(), PDO::PARAM_STR);
            $stmt->bindParam(':id', $prosjekt->getId(), PDO::PARAM_STR);
            $stmt->execute();
        }
        
        public function arkiverProsjekt($id, $gjenopprett=false){
            $arkiver = ($gjenopprett ? 0 : 1);
            $stmt = $this->db->prepare("UPDATE prosjekt SET prosjekt_arkivert=:arkiver WHERE prosjekt_id=:id");
            $stmt->bindParam(':arkiver', $arkiver, PDO::PARAM_INT);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return "arkivert";
        }
        /*public function gjennopprettProsjekt($id){
            $stmt = $this->db->prepare("UPDATE prosjekt SET prosjekt_arkivert=0 WHERE prosjekt_id=:id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        }*/
        
        public function slettProsjekt($prosjekt) {
            $stmt = $this->db->prepare("DELETE FROM prosjekt WHERE prosjekt_id=:prosjektId");
            $stmt->bindParam(':prosjektId', $prosjekt->getId(), PDO::PARAM_INT);
            $stmt->execute();
        }
        
        
        
        public function hentProsjekterFraTeam($teamID) {
            $prosjekter = array();
            $stmt = $this->db->prepare("SELECT * FROM prosjekt WHERE team_id=:id");
            $stmt->bindParam(':id', $teamID, PDO::PARAM_INT);
            $stmt->execute();
            
            while ($prosjekt = $stmt->fetchObject('Prosjekt')) {
                $prosjekter[] = $prosjekt;
            }
            
            return $prosjekter;
        }
        
    }
?>