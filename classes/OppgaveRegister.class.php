<?php
    class OppgaveRegister {
        
        private $db;
        private $oppgavetyper;

        public function __construct(PDO $db) {
            $this->db = $db;
        }
        
        public function hentAlleOppgaver() {
            $oppgaver = array();
            try {
                $stmt = $this->db->prepare("SELECT * FROM oppgave");
                $stmt->execute();
                
                $i = 0;
                while ($oppgave = $stmt->fetchObject('Oppgave')) {
                    $oppgaver[$i] = $oppgave;
                    $i++;
                }
                    
            } catch (Exception $e) {
                $this->Feil($e->getMessage());
            }
            return $oppgaver;
        }
        
        public function hentOppgaverFraProsjekt($prosjekt_id) {
            $oppgaver = array();
            try {
                $stmt = $this->db->prepare("SELECT * FROM `oppgave` WHERE `fase_id` IN (SELECT `fase_id` FROM `fase` WHERE `fase`.`prosjekt_id`=:pId)");
                $stmt->bindParam(':pId', $prosjekt_id);
                $stmt->execute();
                
                $i = 0;
                while ($oppgave = $stmt->fetchObject('Oppgave')) {
                    $oppgaver[$i] = $oppgave;
                    $i++;
                }
            } catch (Exception $e) {
                $this->Feil($e->getMessage());
            }
            return $oppgaver;
        }
        
        public function hentProsjektFraOppgave($oppgave_id){
            try {
                $stmt = $this->db->prepare(
                        "SELECT * FROM `prosjekt` WHERE prosjekt_id=
                        (SELECT `prosjekt_id` FROM `fase` WHERE `fase`.`fase_id`=
                        (SELECT `fase_id` FROM `oppgave` WHERE `oppgave`.`oppgave_id`=:id))");
                $stmt->bindParam(':id', $oppgave_id, PDO::PARAM_INT);
                $stmt->execute();
                
                return $stmt->fetchObject('Prosjekt');
            } catch (Exception $e) {
                $this->Feil($e->getMessage());
            }
        }

        public function lagOppgave($foreldre_oppgave_id, $oppgavetype_id, $fase_id, $oppgave_navn, $oppgave_tidsestimat, $oppgave_periode) {
            try {
                $stmt = $this->db->prepare("INSERT INTO oppgave (foreldre_oppgave_id, oppgavetype_id, fase_id, oppgave_navn, oppgave_tidsestimat, oppgave_periode)
                VALUES (:foreldre_id, :oppgavetype_id, :fase_id, :navn, :tidsestimat, :periode)");
                $stmt->bindParam(':foreldre_id', $foreldre_oppgave_id, PDO::PARAM_INT);
                $stmt->bindParam(':oppgavetype_id', $oppgavetype_id, PDO::PARAM_INT);
                $stmt->bindParam(':fase_id', $fase_id, PDO::PARAM_INT);
                $stmt->bindParam(':navn', $oppgave_navn);
                $stmt->bindParam(':tidsestimat', $oppgave_tidsestimat, PDO::PARAM_INT);
                $stmt->bindParam(':periode', $oppgave_periode, PDO::PARAM_INT);
                $stmt->execute();
            } catch (Exception $e) {
                $this->Feil($e->getMessage());
            }
        }
        
        
        public function hentOppgave($id) {
            try {
                $stmt = $this->db->prepare("SELECT * FROM oppgave WHERE oppgave_id = :id");
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                
                if ($oppgave = $stmt->fetchObject('Oppgave')) {
                    return $oppgave;
                }
            } catch (Exception $e) {
                $this->Feil($e->getMessage());
            }
        }
        
        //Use these functions to avoid many calls to the database
        public function getAlleOppgavetyper() {
            $oppgavetyper = array();
            try {
                $stmt = $this->db->prepare("SELECT * FROM oppgavetype");
                $stmt->execute();
    
                while($oppgavetype = $stmt->fetchObject('Oppgavetype')){
                    $oppgavetyper[$oppgavetype->getId()] = $oppgavetype;
                }
            } catch (Exception $e) {
                $this->Feil($e->getMessage());
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
            try {
                $stmt = $this->db->prepare("SELECT * FROM oppgavetype WHERE oppgavetype_id = :id");
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                
                $oppgavetype = $stmt->fetch(PDO::FETCH_ASSOC);
                return $oppgavetype["oppgavetype_navn"];
            } catch (Exception $e) {
                $this->Feil($e->getMessage());
            }
        }
        
        public function hentAlleOppgaveTyper() {
            $oppgavetyper = array();
            try {
                $stmt = $this->db->prepare("SELECT * FROM oppgavetype");
                $stmt->execute();
    
                while ($oppgtype = $stmt->fetchObject('Oppgavetype')) {
                    $oppgavetyper[$oppgtype->getId()] = $oppgtype;
                }
            } catch (Exception $e) {
                $this->Feil($e->getMessage());
            }
            return $oppgavetyper;
        }
        
        public function hentOppgaveType($id) {
            try {
                $stmt = $this->db->prepare("SELECT * FROM oppgavetype WHERE oppgavetype_id = :id");
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
    
                if($type = $stmt->fetchObject('Oppgavetype')) {
                    return $type;
                }
            } catch (Exception $e) {
                $this->Feil($e->getMessage());
            }
        }
        
        public function lagOppgaveType($oppgavetype_navn) {
            try {
                $stmt = $this->db->prepare("INSERT INTO oppgavetype (oppgavetype_navn) VALUES (:navn)");
                $stmt->bindParam(':navn', $oppgavetype_navn);
                $stmt->execute();
            } catch (Exception $e) {
                $this->Feil($e->getMessage());
            }
        }
        
        public function redigerOppgaveType($oppgavetype) {
            try {
                $stmt = $this->db->prepare("UPDATE `oppgavetype` SET oppgavetype_navn=:navn
                  WHERE oppgavetype_id=:id");
                $stmt->bindParam(':navn', $oppgavetype->getNavn(), PDO::PARAM_STR);
                $stmt->bindParam(':id', $oppgavetype->getId(), PDO::PARAM_INT);
                $stmt->execute();
            } catch (Exception $e) {
                $this->Feil($e->getMessage());
            }
        }
        
        public function hentAktiveTimerPrOppgave($id) {
            try {
                $stmt = $this->db->prepare("SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(`timereg_stopp`, `timereg_start`)))) as sum FROM timeregistrering WHERE oppgave_id = :id AND timereg_aktiv = 1");
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                
                $sum = $stmt->fetch(PDO::FETCH_ASSOC);
                return $sum["sum"];
            } catch (Exception $e) {
                $this->Feil($e->getMessage());
            }
        }
        
        public function hentGodkjenteTimerPrOppgave($id) {
            try {
                $stmt = $this->db->prepare("SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(`timereg_stopp`, `timereg_start`)))) AS sum FROM timeregistrering WHERE oppgave_id = :id AND timereg_aktiv = 1 AND timereg_godkjent = 1");
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                
                $sum = $stmt->fetch(PDO::FETCH_ASSOC);
                return $sum["sum"];
            } catch (Exception $e) {
                $this->Feil($e->getMessage());
            }
        }

        public function lagNyttEstimat($oppgave_id, $estimat, $bruker) {
            try {
                $stmt = $this->db->prepare("INSERT INTO forslag_tidsestimat (oppgave_id, estimat, bruker_id) VALUES (:oppgaveId, :estimat, :brukerId)");
                $stmt->bindParam(':oppgaveId', $oppgave_id, PDO::PARAM_INT);
                $stmt->bindParam(':brukerId', $bruker->getId(), PDO::PARAM_INT);
                $stmt->bindParam(':estimat', $estimat);
                $stmt->execute();
            } catch (Exception $e) {
                $this->Feil($e->getMessage());
            }
        }
        
        public function hentAlleEstimatForOppgave($oppgave_id) {
            $estimater = array();
            try {
                $stmt = $this->db->prepare("SELECT * FROM forslag_tidsestimat WHERE oppgave_id = :id");
                $stmt->bindParam(':id', $oppgave_id, PDO::PARAM_INT);
                $stmt->execute();

                while ($estimat = $stmt->fetchObject('Estimat')) {
                    $estimater[$estimat->getId()] = $estimat;
                }

            } catch (Exception $e) {
                $this->Feil($e->getMessage());
            }
            return $estimater;
        }

        public function endreEstimatForOppgave($oppgave_id, $nyttEstimat){
            try {
                $stmt = $this->db->prepare("UPDATE oppgave SET oppgave_tidsestimat = :estimat WHERE oppgave_id = :id");
                $stmt->bindParam(':id', $oppgave_id, PDO::PARAM_INT);
                $stmt->bindParam(':estimat', $nyttEstimat, PDO::PARAM_INT);
                $stmt->execute();


            } catch (Exception $e) {
                $this->Feil($e->getMessage());
            }
        }

        public function slettEstimatForslag($estimatId) {
            try {
                $stmt = $this->db->prepare("DELETE FROM forslag_tidsestimat WHERE estimat_id = :estimatId");
                $stmt->bindParam(':estimatId', $estimatId, PDO::PARAM_INT);
                $stmt->execute();


            } catch (Exception $e) {
                $this->Feil($e->getMessage());
            }
        }
        
        private function Feil($feilmelding) {
            print "<h2>Oisann... Noe gikk galt</h2>";
            print "<h4>$feilmelding</h4>";
        }
        
    }
?>