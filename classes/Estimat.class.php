<?php

class Estimat
{
    private $estimat_id;
    private $oppgave_id;
    private $bruker_id;
    private $estimat;


    public function __construct(){

    }

    public function getEstimatId()
    {
        return $this->estimat_id;
    }

    public function setEstimatId($estimat_id)
    {
        $this->estimat_id = $estimat_id;
    }

    public function getOppgaveId()
    {
        return $this->oppgave_id;
    }

    public function setOppgaveId($oppgave_id)
    {
        $this->oppgave_id = $oppgave_id;
    }


    public function getBrukerId()
    {
        return $this->bruker_id;
    }

    public function setBrukerId($bruker_id)
    {
        $this->bruker_id = $bruker_id;
    }

    public function getEstimat()
    {
        return $this->estimat;
    }

    public function setEstimat($estimat)
    {
        $this->estimat = $estimat;
    }

}