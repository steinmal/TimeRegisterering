<?php
    class OppgaveRegister {
        
        private $db;
        private $oppgavetyper;

        public function __construct(PDO $db) {
            $this->db = $db;
        }
        
        public function hentAlleOppgaver() {
            $oppgaver = array();
            $stmt = $this->db->prepare("SELECT * FROM oppgave");
            $stmt->execute();
            
            $i = 0;
            while ($oppgave = $stmt->fetchObject('Oppgave')) {
                $oppgaver[$i] = $oppgave;
                $i++;
            }
                
            
            return $oppgaver;
        }
        
        public function hentOppgaverFraProsjekt($prosjekt_id) {
            $oppgaver = array();
            //$stmt = $this->db->prepare("SELECT * FROM oppgave WHERE prosjekt_id=:pId");
            $stmt = $this->db->prepare("SELECT * FROM `oppgave` WHERE `fase_id` IN (SELECT `fase_id` FROM `fase` WHERE `fase`.`prosjekt_id`=:pId)");
            $stmt->bindParam(':pId', $prosjekt_id);
            $stmt->execute();
            
            $i = 0;
            while ($oppgave = $stmt->fetchObject('Oppgave')) {
                $oppgaver[$i] = $oppgave;
                $i++;
            }
                
            
            return $oppgaver;
        }

        public function lagOppgave($foreldre_oppgave_id, $oppgavetype_id, $fase_id, $oppgave_navn, $oppgave_tidsestimat, $oppgave_periode) {
            $stmt = $this->db->prepare("INSERT INTO oppgave (foreldre_oppgave_id, prosjekt_id, oppgavetype_id, fase_id, oppgave_navn, oppgave_tidsestimat, oppgave_periode)
            VALUES (:foreldre_id, :oppgavetype_id, :fase_id, :navn, :tidsestimat, :periode)");
            $stmt->bindParam(':foreldre_id', $foreldre_oppgave_id, PDO::PARAM_INT);
            $stmt->bindParam(':oppgavetype_id', $oppgavetype_id, PDO::PARAM_INT);
            $stmt->bindParam(':fase_id', $fase_id, PDO::PARAM_INT);
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
        
        //Use these functions to avoid many calls to the database
        public function getAlleOppgavetyper() {
            $oppgavetyper = array();
            $stmt = $this->db->prepare("SELECT * FROM oppgavetype");
            $stmt->execute();

            while($oppgavetype = $stmt->fetchObject('Oppgavetype')){
                $oppgavetyper[$oppgavetype->getId()] = $oppgavetype;
            }
            return $oppgavetyper;
        }
        
        public function getOppgavetype($oppgavetype_id) {
            if ($this->oppgavetyper == null)
                $this->oppgavetyper = $this->getAlleOppgavetyper();

            if (!isset($this->oppgavetyper[$oppgavetype_id]))
                throw new InvalidArgumentException('Oppgavetype not defined: ' . $oppgavetype_id);

            return $this->oppgavetyper[$oppgavetype_id];
        }

        
        //Outdated, as these requests sql data for each row
        public function hentOppgaveTypeTekst($id) {
            $stmt = $this->db->prepare("SELECT * FROM oppgavetype WHERE oppgavetype_id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            $oppgavetype = $stmt->fetch(PDO::FETCH_ASSOC);
            return $oppgavetype["oppgavetype_navn"];
        }
        
        public function hentAlleOppgaveTyper() {
            $oppgavetyper = array();
            $stmt = $this->db->prepare("SELECT * FROM oppgavetype");
            $stmt->execute();

            while ($oppgtype = $stmt->fetchObject('Oppgavetype')) {
                $oppgavetyper[$oppgtype->getId()] = $oppgtype;
            }
            return $oppgavetyper;
        }
        public function hentOppgaveType($id) {
            $stmt = $this->db->prepare("SELECT * FROM oppgavetype WHERE oppgavetype_id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            if($type = $stmt->fetchObject('Oppgavetype')) {
                return $type;
            }
        }
        public function lagOppgaveType($oppgavetype_navn) {
            $stmt = $this->db->prepare("INSERT INTO oppgavetype (oppgavetype_navn) VALUES (:navn)");
            $stmt->bindParam(':navn', $oppgavetype_navn);
            $stmt->execute();
        }
        public function redigerOppgaveType($oppgavetype) {
                $stmt = $this->db->prepare("UPDATE `oppgavetype` SET oppgavetype_navn=:navn
                  WHERE oppgavetype_id=:id");
                $stmt->bindParam(':navn', $oppgavetype->getNavn(), PDO::PARAM_STR);
                $stmt->bindParam(':id', $oppgavetype->getId(), PDO::PARAM_INT);
                $stmt->execute();
        }
        
        public function hentAktiveTimerPrOppgave($id) {
            $stmt = $this->db->prepare("SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(`timereg_stopp`, `timereg_start`)))) as sum FROM timeregistrering WHERE oppgave_id = :id AND timereg_aktiv = 1");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            $sum = $stmt->fetch(PDO::FETCH_ASSOC);
            return $sum["sum"];
        }
        
        public function hentGodkjenteTimerPrOppgave($id) {
            $stmt = $this->db->prepare("SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(`timereg_stopp`, `timereg_start`)))) AS sum FROM timeregistrering WHERE oppgave_id = :id AND timereg_aktiv = 1 AND timereg_godkjent = 1");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            $sum = $stmt->fetch(PDO::FETCH_ASSOC);
            return $sum["sum"];
        }
        
    }
?>