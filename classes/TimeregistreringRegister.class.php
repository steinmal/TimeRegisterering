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
        
        
        public function kopierTimeregistrering($timeregId) {
            $opprinneligTime = $this->hentTimeregistrering($timeregId);
            $stmt = $this->db->prepare("INSERT INTO timeregistrering (bruker_id, oppgave_id, timereg_dato, timereg_start, timereg_stopp, timereg_redigeringsdato, timereg_aktiv, timereg_automatisk, timereg_godkjent) 
            VALUES (:bID, :oID, :dato, :start, :stopp, :rDato, :aktiv, :automatisk, :godkjent)");
            $stmt->bindParam(':oID', $opprinneligTime->getOppgaveId(), PDO::PARAM_INT);
            $stmt->bindParam(':bID', $opprinneligTime->getBrukerId(), PDO::PARAM_INT);
            $stmt->bindParam(':dato', $opprinneligTime->getDato());
            $stmt->bindParam(':start', $opprinneligTime->getFra());  
            $stmt->bindParam(':stopp', $opprinneligTime->getTil());
            $stmt->bindParam(':rDato', $opprinneligTime->getRegistreringsDato());
            $stmt->bindParam(':aktiv', $opprinneligTime->getAktiv());
            $stmt->bindParam(':automatisk', $opprinneligTime->getAutomatisk(), PDO::PARAM_INT);
            $stmt->bindParam(':godkjent', $opprinneligTime->getGodkjent(), PDO::PARAM_INT);
            $stmt->execute();
            
            $kopiId = $this->db->lastInsertId();
            $kopiTime = $this->hentTimeregistrering($kopiId);

            return $kopiTime;
        }
        
        
        public function deaktiverTimeregistrering($timeregId) {
            $stmt = $this->db->prepare("UPDATE timeregistrering SET timereg_aktiv=0 WHERE timereg_id=:id");
            $stmt->bindParam(':id', $timeregId, PDO::PARAM_INT);
            $stmt->execute();
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
        
        
         public function oppdaterTimeregistrering($timeId, $dato, $fra, $til, $kommentar, $godkjent=0) {
             $stmt = $this->db->prepare("UPDATE timeregistrering SET timereg_dato=:dato, timereg_start=:start, timereg_stopp=:slutt, timereg_kommentar=:komm, timereg_godkjent=:godkjent WHERE timereg_id=:id");
             $stmt->bindParam(':id', $timeId, PDO::PARAM_INT);
             $stmt->bindParam(':dato', $dato);
             $stmt->bindParam(':start', $fra);
             $stmt->bindParam(':slutt', $til);
             $stmt->bindParam(':komm', $kommentar, PDO::PARAM_STR);
             $stmt->bindParam(':godkjent', $godkjent, PDO::PARAM_INT);
             $stmt->execute();
         }

    }
?>