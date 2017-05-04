<?php
spl_autoload_register(function ($class_name) {
    require_once 'classes/' . $class_name . '.class.php';
});
require_once 'vendor/autoload.php';
include('auth.php');
$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);
$ProsjektReg = new ProsjektRegister($db);
$OppgaveReg = new OppgaveRegister($db);
$TimeReg = new TimeregistreringRegister($db);
$TeamReg = new TeamRegister($db);
$sendt = "";
$error = "";
$oppgaveListe= "";
$aktivert = "";
$visTid = false;
$tidsestimat = 0;
$aktivTid = 0;
session_start();

if(!isset($_SESSION['innlogget']) || $_SESSION['innlogget'] == false){
    header("Location: index.php?error=ikkeInnlogget");
    return;
}
$aktivert = $_SESSION['bruker']->isAktivert();
if(!isset($_SESSION['brukerTilgang']) || !$_SESSION['bruker']->isAktivert()){
    header("Location: index.php?error=manglendeRettighet&side=timeReg");
    return;
}
if(isset($_GET['sendt'])) {
    $sendt = $_GET['sendt'];
}
if(isset($_GET['error'])) {
    $error = $_GET['error'];
}

date_default_timezone_set('Europe/Oslo');

if(isset($_POST['submit'])){
    if($_POST['submit'] == "Start"){
        $timereg = $TimeReg->hentAktiveTimeregistreringer($_SESSION['bruker']->getId());
        if(sizeof($timereg) > 0){
            header("Location: timeregistrering.php?error=alleredeAktivTimereg");
            return;
        }
        if(!isset($_POST['oppgave'])){
            //if(!isset($_POST['prosjektId']))
            header("Location: timeregistrering.php?error=ugyldigOppgave&prosjekt=" . $_POST['prosjektId']);
            return;
        }
    } else {
        $id = 0;
        if(isset($_POST['timeregId']) && $_POST['timeregId'] == 0){
            header("Location: timeregistrering.php?error=ugyldigTimereg");
            return;
        } else {
            $id = $_POST['timeregId'];
            $timereg = $TimeReg->hentTimeregistrering($id);
            if($timereg == null || $timereg->getBrukerId() != $_SESSION['bruker']->getId()) {  //Registreringen hører ikke til innlogget bruker
                header("Location: timeregistrering.php?error=ugyldigTimereg");
                return;
            }
        }
    }

    switch($_POST['submit']){
        case 'Start':
            $prosjekt = $ProsjektReg->hentProsjektFraOppgave($_POST['oppgave']);
            $teamListe = $TeamReg->hentTeamIdFraBruker($_SESSION['bruker']->getId());
            if(!in_array($prosjekt->getTeam(), $teamListe)){
                header("Location: timeregistrering.php?error=ugyldigOppgave&prosjekt=" . $_POST['prosjektId']);
                return;
            }
            $TimeReg->startTimeReg($_POST['oppgave'], $_SESSION['bruker']->getId());
            break;
        case 'Pause':
            if ($timereg->getStatus() == 0 || $timereg->getStatus() == 2)  { //status = start eller fortsett
                $TimeReg->pauserTimeReg($id);
            } else {
                header("Location: timeregistrering.php?error=ugyldigPause");
                return;
            }
            break;
        case 'Fortsett':
            if ($timereg->getStatus() == 1) { //status = pause
                $TimeReg->fortsettTimeReg($id);
            } else {
                header("Location: timeregistrering.php?error=ugyldigFortsettelse");
                return;
            }
            break;
        case 'Stopp':
            if ($timereg->getStatus == 3) { //status = stopp
                header("Location: timeregistrering.php?error=ugyldigStopp");
                return;
            }
            $TimeReg->stoppTimeReg($id);
            break;
    }
}
if(isset($_POST['forslag'])){
    $oppgaveId = $_POST['oppgave'];
    header("Location: nyttTidsestimat.php?oppgaveId=" . $oppgaveId);
    return;
}


$brukernavn = $_SESSION['bruker']->getNavn();
$registrering = $TimeReg->hentAktiveTimeregistreringer($_SESSION['bruker']->getId());
$prosjekt_id = 0;
//$prosjekt = $ProsjektReg->hentAlleProsjekt();
if($registrering != null && sizeof($registrering) > 0){
    $registrering = $registrering[0];
    $oppgave = $OppgaveReg->hentOppgave($registrering->getOppgaveId());
    $prosjekt = $ProsjektReg->hentProsjektFraFase($oppgave->getFaseId());
    $prosjekt_id = $prosjekt->getId();
    echo $twig->render('timeregistrering.html', array( 'innlogget'=>$_SESSION['innlogget'], 'bruker'=>$_SESSION['bruker'], 'TeamReg'=>$TeamReg, 'aktiv'=>true, 'visSkjema'=>true, "visTid"=>true, 'prosjekt'=>$prosjekt, 'oppgave'=>$oppgave, 'registrering'=>$registrering, 'brukernavn'=>$brukernavn, 'dagensdato'=>date("Y-m-d"), 'brukerTilgang'=>$_SESSION['brukerTilgang'], 'error'=>$error));
}
else{
    $brukerID = $_SESSION['bruker']->getId();
    $teamIDs = $TeamReg->hentTeamIdFraBruker($brukerID);
    $grunnProsjekter = array();
    $alleProsjekter = array();
    foreach ($teamIDs as $i) {
        $grunnProsjekter = array_merge($grunnProsjekter, $ProsjektReg->hentProsjekterFraTeam($i));
    }
    foreach ($grunnProsjekter as $p) {
        $alleProsjekter[] = $p;
        $prosjektOversikt = new ProsjektOversikt($p, $ProsjektReg, new FaseRegister($db), $OppgaveReg, $TimeReg);
        $alleProsjekter = array_merge($alleProsjekter, $prosjektOversikt->getAlleUnderProsjekt());
    }

    $prosjektListe = array_unique($alleProsjekter);
    
    if(isset($_POST['prosjektId'])) {
        if(!in_array($ProsjektReg->hentProsjekt($_POST['prosjektId']), $prosjektListe)) {
            header("Location: timeregistrering.php?error=ugyldigProsjekt");
            return;
        }
        $prosjekt_id = $_POST['prosjektId'];
        $oppgaveListe = $OppgaveReg->hentOppgaverFraProsjekt($prosjekt_id);
    }
    
    $oppgave_id = 0;
    if(isset($_POST['oppgave'])) {
        $oppgave_id = $_POST['oppgave'];
        //$visTid = true;
        $tidsestimat = $OppgaveReg->hentOppgave($oppgave_id)->getTidsestimat();
        $aktivTid = $OppgaveReg->hentAktiveTimerPrOppgaveDesimal($oppgave_id);
        $prosjekt_id = $_POST['prosjektId'];
    }

    $visSkjema = ($prosjekt_id > 0 && sizeof($oppgaveListe) > 0 || $prosjekt_id > 0 && $oppgave_id > 0) ? true : false;
    $visTid = $oppgave_id > 0 ? true : false; 
    
    echo $twig->render('timeregistrering.html', 
                array('aktivert'=>$aktivert, 
                      'innlogget'=>$_SESSION['innlogget'], 
                      'TeamReg'=>$TeamReg, 'sendt'=>$sendt, 
                      'bruker'=>$_SESSION['bruker'], 
                      'aktiv'=>false, 
                      'visSkjema'=>$visSkjema, 
                      'prosjektListe'=>$prosjektListe, 
                      'oppgaveListe'=>$oppgaveListe, 
                      'brukernavn'=>$brukernavn, 
                      'dagensdato'=>date("Y-m-d"), 
                      'klokkeslett'=>date('H:i'), 
                      'valgtProsjekt'=>$prosjekt_id, 
                      'valgtOppgave'=>$oppgave_id, 
                      'brukerTilgang'=>$_SESSION['brukerTilgang'], 
                      'OppgaveReg'=>$OppgaveReg,
                      'visTid'=>$visTid,
                      'tidsestimat'=>$tidsestimat,
                      'aktivTid'=>$aktivTid,
                      'error'=>$error));
}



?>
