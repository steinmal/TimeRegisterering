<?php
class ProsjektOversikt {
    private $prosjekt;
    private $prosjektListe = array(); //Alle underordnede prosjekt
    private $prosjektListeRekursiv = array();

    private $oversiktListe = array();
    private $oversiktListeRekursiv = array();
    
    private $faseOversiktListe = array();

    private $oppgaver = array();
    private $timeregistreringer = array();

    private $delNivaa;
    private $tid = array(); //Tid arbeidet for kvar oppgavetype, ikkje inkludert underprosjekt
    private $totaltid = array(); //Tid arbeidet for kvar oppgavetype, inkludert underprosjekt

    //Data til burnup:
    private $totalestimat = array(); //Estimat for kvar oppgavetype, inkludert underprosjekt
    private $totaltidprdag = array(); //Tid arbeidet for kvar dag, for kvar oppgavetype, inkludert underprosjekt

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

        //$tidHelper = new DateHelper();
        //$totalHelper = new DateHelper();
        //$this->timeregistreringer = $TimeregRegister->hentTimeregistreringerFraProsjekt($prosjekt->getId());

        foreach($FaseReg->hentAlleFaser($prosjekt->getId()) as $fase){
            $oversikt = new FaseOversikt($fase, $OppgaveReg, $TimeregRegister);
            $faseOversiktListe[] = $oversikt;
            //$tidHelper->add($oversikt->getTid());

            foreach($oversikt->getTidArray() as $type => $tid){
                if (!array_key_exists($type, $this->tid)) $this->tid[$type] = new DateInterval('PT0S');
                //Treg måte å gjøre dette på, alternativer krever omskriving av hvordan tid behandles
                $this->tid[$type] = DateHelper::sumDateInterval($this->tid[$type], $tid);
            }
            foreach($oversikt->getEstimatArray() as $type => $tid){
                if (!array_key_exists($type, $this->totalestimat)) $this->totalestimat[$type] = 0;
                $this->totalestimat[$type] += $tid;
            }
            foreach($oversikt->getTidPrDagArray() as $dag => $data){
                foreach($data as $type => $tid){
                    if (!array_key_exists($dag, $this->totaltidprdag)) $this->totaltidprdag[$dag] = array();
                    if (!array_key_exists($type, $this->totaltidprdag[$dag])) $this->totaltidprdag[$dag][$type] = 0;
                    $this->totaltidprdag[$dag][$type] += $tid;
                }
            }
        }

        //$this->tid = $tidHelper->getInterval();
        //$totalHelper->add($this->tid);
        foreach($this->tid as $type => $tid){
            $this->totaltid[$type] = new DateInterval("PT0S");
            $this->totaltid[$type]->d = $tid->d;
            $this->totaltid[$type]->h = $tid->h;
            $this->totaltid[$type]->i = $tid->i;
            $this->totaltid[$type]->s = $tid->s;
        }

        $this->oversiktListeRekursiv[] = $this;
        $underProsjektListe = $ProsjektReg->hentUnderProsjekt($prosjekt->getId());
        //if(isset($underProsjektListe) && sizeof($underProsjektListe) > 0 && $underProsjektListe[0] != null && $underProsjektListe[0]->getId() != 1){
        foreach($underProsjektListe as $p){
            $oversikt = new ProsjektOversikt($p, $ProsjektReg, $FaseReg, $OppgaveReg, $TimeregRegister, $this->delNivaa + 1/*, $this->nivaa == 0 ? $this : $grunnRapport*/);
            $this->oversiktListe[] = $oversikt;
            $this->oversiktListeRekursiv = array_merge($this->oversiktListeRekursiv, $oversikt->getOversiktListe());
            $this->oversiktListeRekursiv[] = $p;
            $this->prosjektListeRekursiv[] = $p;
            $this->prosjektListeRekursiv = array_merge($this->prosjektListeRekursiv, $oversikt->getAlleUnderProsjekt(true));
            //$totalHelper->add($oversikt->getTid());
            //var_dump($this->totaltid);
            foreach($oversikt->getTotalTidArray() as $type => $tid){
                //Treg måte å gjøre dette på, alternativer krever omskriving av hvordan tid behandles
                $this->totaltid[$type] = DateHelper::sumDateInterval($this->totaltid[$type], $tid);
            }
            //var_dump($this->totaltid);
            foreach($oversikt->getEstimatArray() as $type => $tid){
                if (!array_key_exists($type, $this->totalestimat)) $this->totalestimat[$type] = 0;
                $this->totalestimat[$type] += $tid;
            }
            foreach($oversikt->getTidPrDagArray() as $dag => $data){
                foreach($data as $type => $tid){
                    if (!array_key_exists($dag, $this->totaltidprdag)) $this->totaltidprdag[$dag] = array();
                    if (!array_key_exists($type, $this->totaltidprdag[$dag])) $this->totaltidprdag[$dag][$type] = 0;
                    $this->totaltidprdag[$dag][$type] += $tid;
                }
            }
        }
        ksort($this->totaltidprdag);
    }
    
    public function gjennopprett(ProsjektRegister $ProsjektReg){
        if($this->prosjekt->getStatus() == 1 || $this->prosjekt->getParent() == 1){
            $this->gjennopprettUnderProsjekt($ProsjektReg);
        } else {
            // TODO: Feilmelding dersom man forsøker å gjennopprette et prosjekt som ikke er arkivert direkte
        }
    }
    
    private function gjennopprettUnderProsjekt(ProsjektRegister $ProsjektReg){
        echo "INSIDE INNER: " . $this->prosjekt->getId();
        $ProsjektReg->arkiverProsjekt($this->prosjekt->getId(), 0);
        //$this->prosjekt->setStatus(0);
        foreach($this->oversiktListe as $oversikt){
            if($oversikt->getProsjekt()->getStatus() != 1){
                $oversikt->gjennopprettUnderProsjekt($ProsjektReg);
            }
        }
    }

    public function getProsjekt(){ return $this->prosjekt; }
    public function getAlleUnderProsjekt($rekursiv = false){ return $rekursiv ? $this->prosjektListeRekursiv : $this->prosjektListe; }
    public function getOversiktListe($rekursiv = true){ return $rekursiv ? $this->oversiktListeRekursiv : $this->oversiktListe; }
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

    public function getEstimatArray(){
        return $this->totalestimat;
    }

    public function getTotalEstimat(){ //timer
        $totaltid = 0;
        foreach($this->getEstimatArray() as $type => $tid){
            $totaltid += $tid;
        }
        return $totaltid;
    }

    public function getTotalEstimatAsLinearData(){
        $total = 0;
        $totalestimataslinear = array();
        //$totalestimataslinear[strtotime($this->prosjekt->getStartDato())] = 0;
        //$totalestimataslinear[strtotime($this->prosjekt->getSluttDato())] = $this->getTotalEstimat();
        $totalestimataslinear[$this->prosjekt->getStartDato()] = 0;
        $totalestimataslinear[$this->prosjekt->getSluttDato()] = $this->getTotalEstimat();
        return $totalestimataslinear;
    }

    public function getTidPrDagArray(){
        return $this->totaltidprdag;
    }

    public function getTotalTidPrDagArrayAsHours(){
        $totaltidprdag = array();
        foreach($this->getTidPrDagArray() as $dag => $data){
            foreach($data as $type => $tid){
                if (!array_key_exists($dag, $totaltidprdag)) $totaltidprdag[$dag] = 0;
                $totaltidprdag[$dag] += ($tid / 86400);
            }
        }
        return $totaltidprdag;
    }

    public function getTotalTidPrDagArrayAsLinearData(){
        $total = 0;
        $startVerdi = 0;
        $totaltidprdagaslinear = array();
        $startDato = $this->prosjekt->getStartDato();
        $sluttDato = $this->prosjekt->getSluttDato();
        $idag = date("Y-m-d");
        foreach($this->getTotalTidPrDagArrayAsHours() as $dag => $tid){
            if (count($totaltidprdagaslinear) == 0 && $dag > $startDato) {
                $totaltidprdagaslinear[$startDato] = $total;
            }
            if ($dag > $idag) break;
            $total += $tid;
            //$totaltidprdagaslinear[strtotime($dag)] = $total;
            if ($dag >= $this->prosjekt->getStartDato() && $dag <= $idag) {
                $totaltidprdagaslinear[$dag] = $total;
            }
        }
        if ($idag >= $startDato) { //Sjekk at det er minst to punkt
            if (count($totaltidprdagaslinear) == 0) {
                $totaltidprdagaslinear[$startDato] = $total;
            }
            if ($idag < $sluttDato) {
                if (!isset($totaltidprdagaslinear[$idag])) {
                    $totaltidprdagaslinear[$idag] = $total;
                }
            } else {
                if (!isset($totaltidprdagaslinear[$sluttDato])) {
                    $totaltidprdagaslinear[$sluttDato] = $total;
                }
            }
        }
        return $totaltidprdagaslinear;
    }

    public function getTimer($type_id = 0){
        return DateHelper::intervallTilTimer($this->getTid($type_id));
    }
    
    public function getTotalTimer($type_id = 0){
        return DateHelper::intervallTilTimer($this->getTotalTid($type_id));
    }

}

