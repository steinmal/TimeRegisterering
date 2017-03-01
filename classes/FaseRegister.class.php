<?php
    class FaseRegister {
        
        private $db;
        
        public function __construct(PDO $db) {
            $this->db = $db;
        }
        
        public function hentAlleFaser(){
            $faser = array();
            $stmt = $this->db->prepare("SELECT * FROM fase");
            $stmt->execute();
            
            $i = 0;
            while($fase = $stmt->fetchObject('Fase')){
                $faser[$i] = $fase;
                $i++;
            }
            
            return $faser;
        }
        
        public function lagFase($fase) {
            $stmt = $this->db->prepare("INSERT INTO `fase` (fase_navn, prosjekt_id, fase_startdato, fase_sluttdato)
            VALUES (:navn, :prosjekt_id, :startdato, :sluttdato)");
            $stmt->bindParam(':navn', $fase->getFaseNavn(), PDO::PARAM_STR);
            $stmt->bindParam(':prosjekt_id', $fase->getProsjektId(), PDO::PARAM_INT);
            $stmt->bindParam(':startdato', $fase->getFaseStartDato());
            $stmt->bindParam(':sluttdato', $fase->getFaseSluttDato());
            $stmt->execute();
        }
        
        public function hentFase($id) {
            $stmt = $this->db->prepare("SELECT * FROM fase WHERE fase_id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            if($fase = $stmt->fetchObject('Fase')) {
                return $fase;
            }
        }
    }