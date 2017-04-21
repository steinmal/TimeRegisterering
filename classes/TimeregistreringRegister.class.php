<?php
    class TimeregistreringRegister {
        
        private $db;

        public function __construct(PDO $db) {
            $this->db = $db;
        }
        
        public function hentAlleTimeregistreringer() {
            $timeregistreringer = array();
            try {
                $stmt = $this->db->prepare("SELECT * FROM timeregistrering");
                $stmt->execute();
    
                while ($timereg = $stmt->fetchObject('Timeregistrering')) {
                    $timeregistreringer[] = $timereg;
                }
            } catch (Exception $e) {
                $this->Feil($e->getMessage());
            }
            return $timeregistreringer;
        }
        
        public function hentTimeregFraOppgave($oppgave_id){
            $timeregistreringer = array();
            try{
                $stmt = $this->db->prepare("SELECT * FROM timeregistrering WHERE oppgave_id=:id");
                $stmt->bindParam(':id', $oppgave_id, PDO::PARAM_INT);
                $stmt->execute();
                
                while($timereg = $stmt->fetchObject('Timeregistrering')){
                    $timeregistreringer[] = $timereg;
                }
            } catch (Exception $e) {
                $this->Feil($e->getMessage());
            }
            return $timeregistreringer;
        }
        
        
        public function lagTimeregistrering($oppgave_id, $bruker_id, $timereg_dato, $timereg_start, $timereg_stopp, $timereg_automatisk) {     
            try {  
                $stmt = $this->db->prepare("INSERT INTO `timeregistrering` (bruker_id, oppgave_id, timereg_dato, timereg_start, timereg_stopp, timereg_tilstand, timereg_automatisk)
                VALUES (:bruker_id, :oppgave_id, :dato, :start, :stopp, :tilstand, :automatisk)");
                $timeregTilstand = $timereg_automatisk ? 0 : 1;     //tilstand: godkjent (automatisk) = 0, venter godkjenning (manuelt reg) = 1
               // $timeRegGodkjent = $timereg_automatisk; //Automatiske registreringer er automatisk godkjent, manuelle (redigerte) registreringer krever godkjenning
                $stmt->bindParam(':oppgave_id', $oppgave_id, PDO::PARAM_INT);
                $stmt->bindParam(':bruker_id', $bruker_id, PDO::PARAM_INT);
                $stmt->bindParam(':dato', $timereg_dato);
                $stmt->bindParam(':start', $timereg_start);  
                $stmt->bindParam(':stopp', $timereg_stopp);
                $stmt->bindParam(':tilstand', $timeregTilstand, PDO::PARAM_INT);
                $stmt->bindParam(':automatisk', $timereg_automatisk, PDO::PARAM_INT);
                //$stmt->bindParam(':godkjent', $timeRegGodkjent, PDO::PARAM_INT);
                $stmt->execute();
            } catch (Exception $e) {
                $this->Feil($e->getMessage());
            }
        }
        
        public function startTimeReg($oppgave_id, $bruker_id){
            try {
                $stmt = $this->db->prepare(
                    "INSERT INTO timeregistrering (bruker_id, oppgave_id, timereg_status, timereg_dato, timereg_start, timereg_stopp, timereg_automatisk, timereg_godkjent)
                    VALUES (:bId, :oId, 0, CURDATE(), NOW(), NOW(), 1, 0)");
                $stmt->bindParam(':oId', $oppgave_id, PDO::PARAM_INT);
                $stmt->bindParam(':bId', $bruker_id, PDO::PARAM_INT);
                $stmt->execute();
            } catch (Exception $e) {
                $this->Feil($e->getMessage());
            }
        }
        
        public function pauserTimeReg($id){
            try {
                $stmt = $this->db->prepare("UPDATE timeregistrering SET timereg_stopp=NOW(), timereg_status=1, timereg_redigeringsdato=NOW() WHERE timereg_id=:id");
                $stmt->bindParam('id', $id, PDO::PARAM_INT);
                $stmt->execute();
            } catch (Exception $e) {
                $this->Feil($e->getMessage());
            }
        }
        
        public function fortsettTimeReg($id){
            $reg = $this->hentTimeregistrering($id);
            $pause = $reg->getPause();
            if($reg->getStatus() == 1){
                $pause += $this->beregnPause($reg->getTil(), date('H:i'));
            }
            try {
                $stmt = $this->db->prepare("UPDATE timeregistrering SET timereg_status=2, timereg_pause=:pause, timereg_redigeringsdato=NOW() WHERE timereg_id=:id");
                $stmt->bindParam('id', $id, PDO::PARAM_INT);
                $stmt->bindParam('pause', $pause, PDO::PARAM_INT);
                $stmt->execute();
            } catch (Exception $e) {
                $this->Feil($e->getMessage());
            }
        }
        
        public function stoppTimeReg($id){
            $reg = $this->hentTimeregistrering($id);
            $pause = $reg->getPause();
            if($reg->getStatus() == 1){
                $pause += $this->beregnPause($reg->getTil(), date('H:i'));
            }
            try {
                $stmt = $this->db->prepare(
                    "UPDATE timeregistrering SET timereg_stopp=NOW(), timereg_pause=:pause, timereg_status=3, timereg_godkjent=1, timereg_redigeringsdato=NOW() WHERE timereg_id=:id");
                $stmt->bindParam('id', $id, PDO::PARAM_INT);
                $stmt->bindParam('pause', $pause, PDO::PARAM_INT);
                $stmt->execute();
            } catch (Exception $e) {
                $this->Feil($e->getMessage());
            }
        }
        
        public function beregnPause($tid1, $tid2){
            $diff = (new DateTime($tid1))->diff(new DateTime($tid2));
            return $diff->i;
        }

        public function hentTimeregistrering($id) {
            try {
                $stmt = $this->db->prepare("SELECT * FROM timeregistrering WHERE timereg_id = :id");
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                
                if ($timereg = $stmt->fetchObject('Timeregistrering')) {
                    return $timereg;
                }
            } catch (Exception $e) {
                $this->Feil($e->getMessage());
            }
        }

        public function hentTimeregistreringerFraBruker($bruker_id, $datefrom = "", $dateto = "") {
            $timeregistreringer = array();
            try {
                if ($datefrom && $dateto) {
                    $stmt = $this->db->prepare("SELECT * FROM timeregistrering WHERE bruker_id = :id AND (timereg_dato BETWEEN :datefrom AND :dateto)");
                    $stmt->bindParam(':id', $bruker_id, PDO::PARAM_INT);
                    $stmt->bindParam(':datefrom', $datefrom, PDO::PARAM_STR);
                    $stmt->bindParam(':dateto', $dateto, PDO::PARAM_STR);
                } else {
                    $stmt = $this->db->prepare("SELECT * FROM timeregistrering WHERE bruker_id = :id");
                    $stmt->bindParam(':id', $bruker_id, PDO::PARAM_INT);
                }
                $stmt->execute();
                    
                while ($timereg = $stmt->fetchObject('Timeregistrering')) {
                    $timeregistreringer[] = $timereg;
                }
            } catch (Exception $e) {
                $this->Feil($e->getMessage());
            }
            return $timeregistreringer;
        }
        
        public function hentTimeregistreringerFraProsjekt($prosjekt_id){
          $timeregistreringer = array();
            try {
                $stmt = $this->db->prepare("
                        SELECT * FROM timeregistrering WHERE oppgave_id IN (
                        SELECT `oppgave_id` FROM `oppgave` WHERE `oppgave`.`fase_id` IN (
                        SELECT `fase_id` FROM `fase` WHERE `fase`.`prosjekt_id`=:pId))
                        ");
                $stmt->bindParam(':pId', $prosjekt_id, PDO::PARAM_INT);
                $stmt->execute();
                    
                while ($timereg = $stmt->fetchObject('Timeregistrering')) {
                    $timeregistreringer[] = $timereg;
                }
            } catch (Exception $e) {
                $this->Feil($e->getMessage());
            }
            return $timeregistreringer;        }
        
        public function hentAktiveTimeregistreringer($bruker_id){       // endring: henter alle som ikke er deaktivert (dvs godkjente, venter godkjenning, avviste og gjenopprettede)
            $registreringer = array();
            try {
                $stmt = $this->db->prepare("SELECT * FROM timeregistrering WHERE bruker_id=:id AND timereg_status < 3 AND timereg_tilstand != 3");
                $stmt->bindParam(':id', $bruker_id, PDO::PARAM_INT);
                $stmt->execute();
                
                while($reg = $stmt->fetchObject('Timeregistrering')){
                    $registreringer[] = $reg;
                }
            } catch (Exception $e) {
                $this->Feil($e->getMessage());
            }
            return $registreringer;
        }

        public function kopierTimeregistrering($timeregId) { //kopierte timeregistreringer får tilstand ikke godkjent (venter godkjenning)      
            $opprinneligTime = $this->hentTimeregistrering($timeregId);
            try {
                $stmt = $this->db->prepare("INSERT INTO timeregistrering (bruker_id, oppgave_id, timereg_dato, timereg_start, timereg_stopp, timereg_pause, timereg_status, timereg_tilstand, timereg_automatisk, timereg_kommentar) 
                VALUES (:bID, :oID, :dato, :start, :stopp, :pause, :status, :tilstand, :automatisk, :kommentar)");
                $oID = $opprinneligTime->getOppgaveId();
                $stmt->bindParam(':oID', $oID, PDO::PARAM_INT);
                $bID = $opprinneligTime->getBrukerId();
                $stmt->bindParam(':bID', $bID, PDO::PARAM_INT);
                $dato = $opprinneligTime->getDato();
                $stmt->bindParam(':dato', $dato);
                $fra = $opprinneligTime->getFra();
                $stmt->bindParam(':start', $fra);
                $til = $opprinneligTime->getTil();
                $stmt->bindParam(':stopp', $til);
                $pause = $opprinneligTime->getPause();
                $stmt->bindParam(':pause', $pause);
                $aktiv = $opprinneligTime->getAktiv();
                //$stmt->bindParam(':aktiv', $aktiv);
                //$status = $opprinneligTime->getStatus();
                $stmt->bindParam(':status', $status, PDO::PARAM_INT);
                $tilstand = 1;
                $stmt->bindParam(':tilstand', $tilstand, PDO::PARAM_INT);
                $automatisk = $opprinneligTime->getAutomatisk();
                $stmt->bindParam(':automatisk', $automatisk, PDO::PARAM_INT);
                $godkjent = $opprinneligTime->getGodkjent();
                //$stmt->bindParam(':godkjent', $godkjent, PDO::PARAM_INT);
                //$kommentar = $opprinneligTime->getKommentar();
                $stmt->bindParam(':kommentar', $kommentar, PDO::PARAM_STR);
                $stmt->execute();
                
                $kopiId = $this->db->lastInsertId();
                $kopiTime = $this->hentTimeregistrering($kopiId);
            } catch (Exception $e) {
                $this->Feil($e->getMessage());
            }
            return $kopiTime;
        }
        
        
        public function deaktiverTimeregistrering($timeregId) {     //tilstand 3 = deaktivert  
            try {
                $stmt = $this->db->prepare("UPDATE timeregistrering SET timereg_tilstand=3, timereg_redigeringsdato=now() WHERE timereg_id=:id");
                $stmt->bindParam(':id', $timeregId, PDO::PARAM_INT);
                $stmt->execute();
            } catch (Exception $e) {
                $this->Feil($e->getMessage());
            }
        }

        public function gjenopprettTimeregistrering($timeregId) {       // tilstand 4 = gjenopprettet
            try {
                $stmt = $this->db->prepare("UPDATE timeregistrering SET timereg_tilstand=4, timereg_redigeringsdato=now() WHERE timereg_id=:id");
                $stmt->bindParam(':id', $timeregId, PDO::PARAM_INT);
                $stmt->execute();
            } catch (Exception $e) {
                $this->Feil($e->getMessage());
            }
        }


        public function godkjennTimeregistrering($timeregId){       //tilstand 0 = godkjent 
            try {
                $stmt = $this->db->prepare("UPDATE `timeregistrering` SET timereg_tilstand=0 WHERE timereg_id=:id");
                $stmt->bindParam(':id', $timeregId, PDO::PARAM_INT);
                $stmt->execute();
            } catch (Exception $e) {
                $this->Feil($e->getMessage());
            }
        }
        
        public function avvisTimeregistrering($timeregId){      //tilstand 2 = avvist
            try {
                $stmt = $this->db->prepare("UPDATE `timeregistrering` SET timereg_tilstand=2 WHERE timereg_id=:id");
                $stmt->bindParam(':id', $timeregId, PDO::PARAM_INT);
                $stmt->execute();
            } catch (Exception $e) {
                $this->Feil($e->getMessage());
            }
        }
        
        
         public function oppdaterTimeregistrering($timeId, $dato, $fra, $til, $pause, $kommentar, $godkjent=0) {
             try {
                 $stmt = $this->db->prepare("UPDATE timeregistrering SET timereg_dato=:dato, timereg_start=:start, timereg_stopp=:slutt, timereg_pause=:pause, timereg_kommentar=:komm, timereg_godkjent=:godkjent, timereg_redigeringsdato=now() WHERE timereg_id=:id");
                 $stmt->bindParam(':id', $timeId, PDO::PARAM_INT);
                 $stmt->bindParam(':dato', $dato);
                 $stmt->bindParam(':start', $fra);
                 $stmt->bindParam(':slutt', $til);
                 $stmt->bindParam(':pause', $pause, PDO::PARAM_INT);
                 $stmt->bindParam(':komm', $kommentar, PDO::PARAM_STR);
                 $stmt->bindParam(':godkjent', $godkjent, PDO::PARAM_INT);
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