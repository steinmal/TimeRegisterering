<?php
class ProsjektOversikt {
    private $prosjekt;
    private $prosjektListe = array(); //Alle underordnede prosjekt

    private $oversiktListe = array();
    private $faseOversiktListe = array();

    private $oppgaver = array();
    private $timeregistreringer = array();
    

    private $delNivaa;
    private $tid;
    private $totaltid;

    public function __construct(
            Prosjekt $prosjekt,
            ProsjektRegister $ProsjektReg,
            FaseRegister $FaseReg,
            OppgaveRegister $OppgaveReg,
            TimeregistreringRegister $TimeregRegister,
            $nivaa = 0)//,
            //ProsjektOversikt $grunnRapport = null) // For debugging
    {
        $this->prosjekt = $prosjekt;
        $this->delNivaa = $nivaa;
        $this->prosjektOgUnderProsjekt[] = $prosjekt;

        $tidHelper = new DateHelper();
        $totalHelper = new DateHelper();
        //$this->timeregistreringer = $TimeregRegister->hentTimeregistreringerFraProsjekt($prosjekt->getId());

        foreach($FaseReg->hentAlleFaser($prosjekt->getId()) as $fase){
            $oversikt = new FaseOversikt($fase, $OppgaveReg, $TimeregRegister);
            $faseOversiktListe[] = $oversikt;
            $tidHelper->add($oversikt->getTid());
        }

        $this->tid = $tidHelper->getInterval();
        $totalHelper->add($this->tid);

        $oversiktListe[] = $this;
        $underProsjektListe = $ProsjektReg->hentUnderProsjekt($prosjekt->getId());
        //if(isset($underProsjektListe) && sizeof($underProsjektListe) > 0 && $underProsjektListe[0] != null && $underProsjektListe[0]->getId() != 1){
        foreach($underProsjektListe as $p){
            $oversikt = new ProsjektOversikt($p, $ProsjektReg, $FaseReg, $OppgaveReg, $TimeregRegister, $this->delNivaa + 1/*, $this->nivaa == 0 ? $this : $grunnRapport*/);
            $this->oversiktListe[] = $oversikt;
            $this->oversiktListe = array_merge($this->oversiktListe, $oversikt->getOversiktListe());
            $this->prosjektListe[] = $p;
            $totalHelper->add($oversikt->getTid());
        }
        //}
        $this->totaltid = $totalHelper->getInterval();
    }

    public function getProsjekt(){ return $this->prosjekt; }
    public function getAlleUnderProsjekt(){ return $this->prosjektListe; }
    public function getOversiktListe(){ return $this->oversiktListe; }
    public function getFaseOversiktListe(){ return $this->faseOversiktListe; }

    public function getNavnMedInnrykk(){
        $navn = $this->prosjekt->getNavn();
        for($i = 0; $i < $delNivaa; $i++){
            $navn = "&emsp;" . $navn;
        }
        return $navn;
    }
    public function getNavnMedSymbol($symbol){
        $navn = $this->prosjekt->getNavn();
        for($i = 0; $i < $delNivaa; $i++){
            $navn = $symbol . $navn;
        }
        return $navn;
    }


    public function getTid(){
        return $this->tid;
    }
    
    public function getTotalTid(){
        return $this->totaltid;
    }
    
    public function getTimer(){
        $tid = $this->tid->h + ($this->tid->i + $this->tid->s / 60.0) / 60.0;
        return number_format($tid, 2);
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