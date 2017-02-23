<?php
    class OppgaveRegister {
        
        private $db;

        public function __construct(PDO $db) {
            $this->db = $db;
        }
        
        public function hentAlleOppgaver() {
            $oppgaver = array();
            $stmt = $this->db->prepare("SELECT * FROM oppgave");
            $stmt->execute();
            
            $i = 0;
            while ($post = $stmt->fetchObject('Oppgave')) {
                $oppgaver[$i] = $post;
                $i++;
            }
                
            
            return $oppgaver;
        }
        
        public function hentOppgaverFraProsjekt($prosjekt_id) {
            $oppgaver = array();
            $stmt = $this->db->prepare("SELECT * FROM oppgave WHERE prosjekt_id=:pId");
            $stmt->bindParam(':pId', $prosjekt_id);
            $stmt->execute();
            
            $i = 0;
            while ($post = $stmt->fetchObject('Oppgave')) {
                $oppgaver[$i] = $post;
                $i++;
            }
                
            
            return $oppgaver;
        }
        
        
        public function lagOppgave($foreldre_oppgave_id, $prosjekt_id, $oppgavetype_id, $fase_id, $oppgave_navn, $oppgave_tidsestimat, $oppgave_periode) {
            $stmt = $this->db->prepare("INSERT INTO oppgave (foreldre_oppgave_id, prosjekt_id, oppgavetype_id, fase_id, oppgave_navn, oppgave_tidsestimat, oppgave_periode)
            VALUES (:foreldre_id, :prosjekt_id, :oppgavetype_id, :fase_id, :navn, :tidsestimat, :periode)");
            $stmt->bindParam(':foreldre_id', $foreldre_oppgave_id, PDO::PARAM_INT);
            $stmt->bindParam(':prosjekt_id', $prosjekt_id, PDO::PARAM_INT);
            $stmt->bindParam(':oppgavetype_id', $oppgavetype_id, PDO::PARAM_INT);
            $stmt->bindParam(':fase_id', $fase_id);
            $stmt->bindParam(':navn', $oppgave_navn);
            $stmt->bindParam(':tidsestimat', $oppgave_tidsestimat, PDO::PARAM_INT);
            $stmt->bindParam(':periode', $oppgave_periode, PDO::PARAM_INT);
            $stmt->execute();
        }
        
        
        public function hentOppgave($id) {
            $stmt = $this->db->prepare("SELECT * FROM oppgave WHERE oppgave_id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            if ($oppgave = $stmt->fetchObject('Oppgave')) {
                return $oppgave;
            }
        }
        
    }
?>