<?php

class OppgaveOversikt{
    private $oppgave;
    //private $OppgaveReg;
    
    private $tid;
    
    public function __construct(Oppgave $oppgave, TimeregistreringRegister $TimeregReg){
        $this->oppgave = $oppgave;
        //$this->OppgaveReg = $OppgaveReg;
        
        $helper = new DateHelper();
        $timeregListe = $TimeregReg->hentTimeregFraOppgave($oppgave->getId());
        foreach($timeregListe as $timereg){
            $helper->add($timereg->getHourAsDateInterval());
        }
        $this->tid = $helper->getInterval();
    }
    
    public function getOppgave(){ return $this->oppgave; }
    public function getTid(){ return $this->tid; }
}