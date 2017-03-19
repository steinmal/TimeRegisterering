<?php
    class TimeregistreringRegister {
        
        private $db;

        public function __construct(PDO $db) {
            $this->db = $db;
        }
        
        public function hentAlleTimeregistreringer() {
            $timeregistreringer = array();
            $stmt = $this->db->prepare("SELECT * FROM timeregistrering");
            $stmt->execute();

            while ($timereg = $stmt->fetchObject('Timeregistrering')) {
                $timeregistreringer[] = $timereg;
            }
            return $timeregistreringer;
        }
        
        
        public function lagTimeregistrering($oppgave_id, $bruker_id, $timereg_dato, $timereg_start, $timereg_stopp, $timereg_automatisk) {
            $stmt = $this->db->prepare("INSERT INTO `timeregistrering` (bruker_id, oppgave_id, timereg_dato, timereg_start, timereg_stopp, timereg_automatisk, timereg_godkjent)
            VALUES (:bruker_id, :oppgave_id, :dato, :start, :stopp, :automatisk, :godkjent)");
            $timeRegGodkjent = $timereg_automatisk; //Automatiske registreringer er automatisk godkjent, manuelle (redigerte) registreringer krever godkjenning
            $stmt->bindParam(':oppgave_id', $oppgave_id, PDO::PARAM_INT);
            $stmt->bindParam(':bruker_id', $bruker_id, PDO::PARAM_INT);
            $stmt->bindParam(':dato', $timereg_dato);
            $stmt->bindParam(':start', $timereg_start);  
            $stmt->bindParam(':stopp', $timereg_stopp);
            $stmt->bindParam(':automatisk', $timereg_automatisk, PDO::PARAM_INT);
            $stmt->bindParam(':godkjent', $timeRegGodkjent, PDO::PARAM_INT);
            $stmt->execute();
        }
        
        
        public function hentTimeregistrering($id) {
            $stmt = $this->db->prepare("SELECT * FROM timeregistrering WHERE timereg_id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            if ($timereg = $stmt->fetchObject('Timeregistrering')) {
                return $timereg;
            }
        }
        
        public function hentTimeregistreringerFraBruker($bruker_id) {
            $timeregistreringer = array();
            $stmt = $this->db->prepare("SELECT * FROM timeregistrering WHERE bruker_id = :id");
            $stmt->bindParam(':id', $bruker_id, PDO::PARAM_INT);
            $stmt->execute();
                
            while ($timereg = $stmt->fetchObject('Timeregistrering')) {
                $timeregistreringer[] = $timereg;
            }

            return $timeregistreringer;
        }
        
        public function godkjennTimeregistrering($timeregId){
            $stmt = $this->db->prepare("UPDATE `timeregistrering` SET timereg_godkjent=1 WHERE timereg_id=:id");
            $stmt->bindParam(':id', $timeregId, PDO::PARAM_INT);
            $stmt->execute();
        }
        
        public function avvisTimeregistrering($timeregId){
            $stmt = $this->db->prepare("UPDATE `timeregistrering` SET timereg_godkjent=0 WHERE timereg_id=:id");
            $stmt->bindParam(':id', $timeregId, PDO::PARAM_INT);
            $stmt->execute();
        }
        
    }
?>