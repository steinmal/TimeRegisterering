<?php

class FaseOversikt {
    private $fase;

    private $oppgaveOversiktListe = array();
    
    private $tid = array();
    
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
            $this->tid[$type] = DateHelper::sumDateInterval($this->tid[$type], $oversikt->getTid());
        }
    }
    
    public function getTid($type_id = 0){
        if($type_id == 0){
            return DateHelper::sumDateIntervalList($this->tid);
        }
        return $this->tid[$type_id];
    }
}