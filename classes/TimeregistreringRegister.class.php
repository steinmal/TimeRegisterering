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

            $i = 0;
            while ($timereg = $stmt->fetchObject('Timeregistrering')) {
                $timeregistreringer[$i] = $timereg;
                $i++;
            }
                
            
            return $timeregistreringer;
        }
        
        
        public function lagTimeregistrering($oppgave_id, $bruker_id, $timereg_dato, $timereg_start, $timereg_slutt, $timereg_automatisk) {
            $stmt = $this->db->prepare("INSERT INTO `timeregistrering` (bruker_id, oppgave_id, timereg_dato, timereg_start, timereg_slutt, timereg_automatisk)
            VALUES (:bruker_id, :oppgave_id, :dato, :start, :slutt, :automatisk)");
            $stmt->bindParam(':oppgave_id', $oppgave_id, PDO::PARAM_INT);
            $stmt->bindParam(':bruker_id', $bruker_id, PDO::PARAM_INT);
            $stmt->bindParam(':dato', $timereg_dato);
            $stmt->bindParam(':start', $timereg_start);
            $stmt->bindParam(':slutt', $timereg_slutt);
            $stmt->bindParam(':automatisk', $timereg_automatisk, PDO::PARAM_INT);
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
            $stmt = $this->db->prepare("SELECT * FROM timeregistrering WHERE bruker_id = :id");
            $stmt->bindParam(':id', $bruker_id, PDO::PARAM_INT);
            $stmt->execute();
                
                while ($timereg = $stmt->fetchObject('Timeregistrering')) {
                $timeregistreringer[] = $timereg;
            }

            return $timeregistreringer;
        }
        
    }
?>