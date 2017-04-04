<?php
class ProsjektOversikt {
    private $prosjekt;
    private $prosjektListe = array(); //Alle underordnede prosjekt

    private $oversiktListe = array();
    private $faseOversiktListe = array();

    private $oppgaver = array();
    private $timeregistreringer = array();
    

    private $delNivaa;
    private $tid = array();
    private $totaltid = array();

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

        //$tidHelper = new DateHelper();
        //$totalHelper = new DateHelper();
        //$this->timeregistreringer = $TimeregRegister->hentTimeregistreringerFraProsjekt($prosjekt->getId());

        foreach($FaseReg->hentAlleFaser($prosjekt->getId()) as $fase){
            $oversikt = new FaseOversikt($fase, $OppgaveReg, $TimeregRegister);
            $faseOversiktListe[] = $oversikt;
            //$tidHelper->add($oversikt->getTid());
            
            foreach($oversikt->getTidArray() as $type => $tid){
                //Treg måte å gjøre dette på, alternativer krever omskriving av hvordan tid behandles
                $this->tid[$type] = DateHelper::sumDateInterval($this->tid[$type], $tid);
            }
        }

        //$this->tid = $tidHelper->getInterval();
        //$totalHelper->add($this->tid);
        foreach($this->tid as $type => $tid){
            $this->totaltid[$type] = $tid;
        }

        $oversiktListe[] = $this;
        $underProsjektListe = $ProsjektReg->hentUnderProsjekt($prosjekt->getId());
        //if(isset($underProsjektListe) && sizeof($underProsjektListe) > 0 && $underProsjektListe[0] != null && $underProsjektListe[0]->getId() != 1){
        foreach($underProsjektListe as $p){
            echo $p->getNavn() . "<br>";
            $oversikt = new ProsjektOversikt($p, $ProsjektReg, $FaseReg, $OppgaveReg, $TimeregRegister, $this->delNivaa + 1/*, $this->nivaa == 0 ? $this : $grunnRapport*/);
            $this->oversiktListe[] = $oversikt;
            $this->oversiktListe = array_merge($this->oversiktListe, $oversikt->getOversiktListe());
            $this->prosjektListe[] = $p;
            //$totalHelper->add($oversikt->getTid());
            //var_dump($this->totaltid);
            foreach($oversikt->getTotalTidArray() as $type => $tid){
                //Treg måte å gjøre dette på, alternativer krever omskriving av hvordan tid behandles
                $this->totaltid[$type] = DateHelper::sumDateInterval($this->totaltid[$type], $tid);
            }
            //var_dump($this->totaltid);
        }
        //}
        //$this->totaltid = $totalHelper->getInterval();
    }

    public function getProsjekt(){ return $this->prosjekt; }
    public function getAlleUnderProsjekt(){ return $this->prosjektListe; }
    public function getOversiktListe(){ return $this->oversiktListe; }
    public function getFaseOversiktListe(){ return $this->faseOversiktListe; }

    public function getNavnMedInnrykk(){
        $navn = $this->prosjekt->getNavn();
        for($i = 0; $i < $this->delNivaa; $i++){
            $navn = "&emsp;" . $navn;
        }
        return $navn;
    }
    public function getNavnMedSymbol($symbol){
        $navn = $this->prosjekt->getNavn();
        for($i = 0; $i < $this->delNivaa; $i++){
            $navn = $symbol . $navn;
        }
        return $navn;
    }

    public function getTid($type_id = 0){
        if($type_id == 0){
            return DateHelper::sumDateIntervalList($this->tid);
        }
        if(!isset($this->tid[$type_id])){
            return new DateInterval("PT0S");
        }
        return $this->tid[$type_id];
    }
    public function getTidArray(){
        return $this->tid;
    }
    
    public function getTotalTid($type_id = 0){
        if($type_id == 0){
            return DateHelper::sumDateIntervalList($this->totaltid);
        }
        if(!isset($this->totaltid[$type_id])){
            return new DateInterval("PT0S");
        }
        return $this->totaltid[$type_id];
    }
    public function getTotalTidArray(){
        return $this->totaltid;
    }
    
    public function getTimer($type_id = 0){
        return DateHelper::intervallTilTimer($this->getTid($type_id));
    }
    
    public function getTotalTimer($type_id = 0){
        return DateHelper::intervallTilTimer($this->getTotalTid($type_id));
    }
}

