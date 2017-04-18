<?php
class RapportProsjekt {
    private $ProsjektReg;
    private $underProsjekt = array();
    private $prosjekt;
    private $prosjektOgUnderProsjekt = array();
    private $prosjektRapporter = array();
    private $OppgaveReg;
    private $oppgaver = array();
    private $TimeregReg;
    private $timeregistreringer = array();
    
    private $delNivaa;
    private $tid;
    private $totaltid;

    public function __construct(
            ProsjektRegister $ProsjektReg,
            OppgaveRegister $OppgaveReg,
            TimeregistreringRegister $TimeregRegister,
            Prosjekt $prosjekt,
            $nivaa = 0,
            RapportProsjekt $grunnRapport = null){
        $this->ProsjektReg = $ProsjektReg;
        $this->prosjekt = $prosjekt;
        $this->delNivaa = $nivaa;
        $this->prosjektOgUnderProsjekt[] = $prosjekt;
        $this->OppgaveReg = $OppgaveReg;
        $this->TimeregReg = $TimeregRegister;

        $this->oppgaver = $OppgaveReg->hentOppgaverFraProsjekt($prosjekt->getId());
        
        echo "<font color=red>Nivå: " . $this->delNivaa . "</font>";
        if($grunnRapport != null){
            var_dump($grunnRapport->getTid());
        }
        $this->tid = DateTime::createFromFormat('!', "");
        $this->timeregistreringer = $TimeregRegister->hentTimeregistreringerFraProsjekt($prosjekt->getId());

        foreach($this->timeregistreringer as $reg){
            $this->tid->add($reg->getHourAsDateInterval());
        }
        if($grunnRapport != null){
            var_dump($grunnRapport->getTid());
        }
        $this->totaltid = $this->tid;

        $underProsjektListe = $ProsjektReg->hentUnderProsjekt($prosjekt->getId());
        if(isset($underProsjektListe) && sizeof($underProsjektListe) > 0 && $underProsjektListe[0] != null && $underProsjektListe[0]->getId() != 1){
            foreach($underProsjektListe as $p){
                //$this->prosjektOgUnderProsjekt[] = $p; // Ikke rekursiv
                $rapport = new RapportProsjekt($ProsjektReg, $OppgaveReg, $TimeregRegister, $p, $this->delNivaa + 1, $this->nivaa == 0 ? $this : $grunnRapport);
                $this->prosjektOgUnderProsjekt = array_merge($this->prosjektOgUnderProsjekt, $rapport->getProsjektOgUnderProsjekt());
                $this->prosjektRapporter[] = $rapport;
                $this->prosjektRapporter = array_merge($this->prosjektRapporter, $rapport->getProsjektRapporter());
                $this->underProsjekt[] = $rapport;
                $this->totaltid->add(DtimeToDInterval($rapport->getTid()));
            }
        }
        if($grunnRapport != null){
            var_dump($grunnRapport->getTid());
        }
        //var_dump($this);
    }

    public function getProsjektOgUnderProsjekt(){ return $this->prosjektOgUnderProsjekt; }
    public function getProsjektRapporter(){ return $this->prosjektRapporter; }
    public function getTimeregistreringer(){ return $this->timeregistreringer; }
    public function getProsjekt(){ return $this->prosjekt; }
    public function getUnderProsjekt(){ return $this->underProsjekt; }
    
    public function getNavnMedInnrykk(){
        $navn = $this->prosjekt->getNavn();
        for($i = 0; $i < $this->delNivaa; $i++){ //La til $this, phpstorm ga error her.
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


    public function getTid(){
        return $this->tid;
    }
    
    public function getTotalTid(){
        return $this->totaltid;
    }
    
    public function getTimer(){
        var_dump($this->tid);
        var_dump(number_format($this->tid->getTimestamp() / 3600.0, 2));
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