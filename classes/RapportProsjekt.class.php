<?php
class RapportProsjekt {
    private $ProsjektReg;
    private $underProsjekt = array();
    private $prosjekt;
    private $prosjektOgUnderProsjekt = array();
    private $OppgaveReg;
    private $oppgaver = array();
    private $TimeregReg;
    private $timeregistreringer = array();
    
    private $tid;
    private $totaltid;

    public function __construct(
            ProsjektRegister $ProsjektReg,
            OppgaveRegister $OppgaveReg,
            TimeregistreringRegister $TimeregRegister,
            Prosjekt $prosjekt){
        $this->ProsjektReg = $ProsjektReg;
        $this->prosjekt = $prosjekt;
        $this->prosjektOgUnderProsjekt[] = $prosjekt;
        $this->OppgaveReg = $OppgaveReg;
        $this->TimeregReg = $TimeregRegister;

        $this->oppgaver = $OppgaveReg->hentOppgaverFraProsjekt($prosjekt->getId());
        
        $this->tid = DateTime::createFromFormat('!', "");
        $this->timeregistreringer = $TimeregRegister->hentTimeregistreringerFraProsjekt($prosjekt->getId());

        foreach($this->timeregistreringer as $reg){
            $this->tid->add($reg->getHourAsDateInterval());
        }
        $this->totaltid = $this->tid;

        $underProsjektListe = $ProsjektReg->hentUnderProsjekt($prosjekt->getId());
        if($underProsjektListe[0] != null && $underProsjektListe[0]->getId() != 1){
            foreach($underProsjektListe as $p){
                $this->prosjektOgUnderProsjekt[] = $p;
                $rapport = new RapportProsjekt($ProsjektReg, $OppgaveReg, $TimeregRegister, $p);
                $this->underProsjekt[] = $rapport;
                $this->totaltid->add(DtimeToDInterval($rapport->getTid()));
            }
        }
    }

    public function getProsjektOgUnderProsjekt(){ return $this->prosjektOgUnderProsjekt; }
    public function getTimeregistreringer(){ return $this->timeregistreringer; }
    public function getProsjekt(){ return $this->prosjekt; }
    public function getUnderProsjekt(){ return $this->underProsjekt; }

    public function getTid(){
        return $this->tid;
    }
    
    public function getTotalTid(){
        return $this->totaltid;
    }
    
    public function getTimer(){
        return number_format($this->tid->getTimestamp() / 3600.0, 2);
    }
    
    public function getTotalTimer(){
        return number_format($this->totaltid->getTimestamp() / 3600.0, 2);
    }
    
    /*public function getProsjektOgTid(array $prosjektMedTid, bool $innrykk){
        $prosjektMedTid[]["prosjekt"] = $this->prosjekt;
        $prosjektMedTid[]["tid"] = $this->getTid();
    }*/
}

function DtimeToDInterval(DateTime $dt){
    $formatted = $dt->format('H:i:s');
    list($hours, $minutes, $seconds) = sscanf($formatted, '%d:%d:%d');
    return new DateInterval(sprintf('PT%dH%dM%dS', $hours, $minutes, $seconds));
}