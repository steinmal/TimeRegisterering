<?php

class OppgaveOversikt{
    private $oppgave;
    //private $OppgaveReg;
    
    private $tid;

    //Data til burnup:
    private $tidprdag = array(); //Tid arbeidet for kvar dag, for kvar oppgavetype

    public function __construct(Oppgave $oppgave, TimeregistreringRegister $TimeregReg){
        $this->oppgave = $oppgave;
        //$this->OppgaveReg = $OppgaveReg;

        $helper = new DateHelper();
        //$helperPrDag = new array();
        $timeregListe = $TimeregReg->hentTimeregFraOppgave($oppgave->getId());
        foreach($timeregListe as $timereg){
            $helper->add($timereg->getHourAsDateInterval());
            $dag = $timereg->getDato();
            //if (!isset($helperPrDag[$dag])) $helperPrDag[$dag] = new DateHelper();
            if (!array_key_exists($dag, $this->tidprdag)) $this->tidprdag[$dag] = 0;
            $this->tidprdag[$dag] += $timereg->getWorkHoursAsSeconds();
        }
        $this->tid = $helper->getInterval();
    }
    
    public function getOppgave(){ return $this->oppgave; }
    public function getTid(){ return $this->tid; }

    public function getTidPrDagArray(){
        return $this->tidprdag;
    }
}