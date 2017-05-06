<?php

class FaseOversikt {
    private $fase;

    private $oppgaveOversiktListe = array();
    
    private $tid = array();

    //Data til burnup:
    private $estimat = array(); //Estimat for kvar oppgavetype
    private $tidprdag = array(); //Tid arbeidet for kvar dag, for kvar oppgavetype

    public function __construct(
            Fase $fase,
            OppgaveRegister $OppgaveReg,
            TimeregistreringRegister $TimeregReg)
    {
        $this->fase = $fase;

        $oppgaveListe = $OppgaveReg->hentOppgaverFraFase($fase->getId());
        foreach($oppgaveListe as $oppgave){
            $oversikt = new OppgaveOversikt($oppgave, $TimeregReg);
            $oppgaveOversiktListe[] = $oversikt;

            $type = $oppgave->getType();
            if (!array_key_exists($type, $this->tid)) $this->tid[$type] = new DateInterval('PT0S');
            if (!array_key_exists($type, $this->estimat)) $this->estimat[$type] = 0;
            $this->tid[$type] = DateHelper::sumDateInterval($this->tid[$type], $oversikt->getTid());
            $this->estimat[$type] += $oppgave->getTidsestimat();
            foreach($oversikt->getTidPrDagArray() as $dag => $tid){
                if (!array_key_exists($dag, $this->tidprdag)) $this->tidprdag[$dag] = array();
                if (!array_key_exists($type, $this->tidprdag[$dag])) $this->tidprdag[$dag][$type] = 0;
                $this->tidprdag[$dag][$type] += $tid;
            }
        }
    }
    
    public function getTid($type_id = 0){
        if($type_id == 0){
            return DateHelper::sumDateIntervalList($this->tid);
        }
        return $this->tid[$type_id];
    }
    public function getTidArray(){
        return $this->tid;
    }
    public function getEstimatArray(){
        return $this->estimat;
    }

    public function getTidPrDagArray(){
        return $this->tidprdag;
    }
}