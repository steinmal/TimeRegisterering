<?php
    class RapportRegister {
        private $db;
        
        public function __construct(PDO $db) {
            $this->db = $db;
        }
        
        // Henter timeregistreringer (array) til TeamRapport Ansatt
        public function getAnsattRapport($bruker_id, $datefrom = "", $dateto = "") {
            // TODO: Kun hente ut godkjente/aktive registreringer. 
            $timeregistreringer = array();
            try { 
                if ($datefrom && $dateto) {
                    // TODO: Refactor if-else
                    $stmt = $this->db->prepare("
                        SELECT a.*, b.oppgave_navn, c.oppgavetype_navn
                        FROM timeregistrering a
                        JOIN oppgave b ON a.oppgave_id = b.oppgave_id
                        JOIN oppgavetype c ON b.oppgavetype_id = c.oppgavetype_id
                        WHERE bruker_id = :id AND (a.timereg_dato BETWEEN :datefrom AND :dateto)
                        ");
                    $stmt->bindParam(':id', $bruker_id, PDO::PARAM_INT);
                    $stmt->bindParam(':datefrom', $datefrom, PDO::PARAM_STR);
                    $stmt->bindParam(':dateto', $dateto, PDO::PARAM_STR);
                } else {
                    $stmt = $this->db->prepare("
                        SELECT a.*, b.oppgave_navn, c.oppgavetype_navn
                        FROM timeregistrering a
                        JOIN oppgave b ON a.oppgave_id = b.oppgave_id
                        JOIN oppgavetype c ON b.oppgavetype_id = c.oppgavetype_id
                        WHERE bruker_id = :id
                        ");
                    $stmt->bindParam(':id', $bruker_id, PDO::PARAM_INT);
                }
                $stmt->execute();
                    
                while ($timereg = $stmt->fetchObject('Timeregistrering')) {
                    // TODO: Har foreløpig valgt å gjøre om fra objekt Timeregistrering til array her
                    // for å unngå å måtte loope gjennom alle timeregistreringene på nytt. Et bedre alternativ
                    // er nok å gjøre det mulig å hente ut getHourString() uten å måtte ha et Timeregistrerings objekt. BK.
                    $timeregistreringer[] = array(
                        'dato'=>$timereg->getDato(), 
                        'fra'=>$timereg->getFra(),
                        'til'=>$timereg->getTil(), 
                        'timer'=>$timereg->getHourString(),
                        'oppgaveNavn'=>$timereg->getOppgaveNavn(),
                        'oppgavetypeNavn'=>$timereg->getOppgavetypeNavn()
                    );
                }
            } catch (Exception $e) {
                $this->Feil($e->getMessage());
            }
            return $timeregistreringer;
        }

        private function Feil($feilmelding) {
            print "<h2>Oisann... Noe gikk galt</h2>";
            print "<h4>$feilmelding</h4>";
        }
    }