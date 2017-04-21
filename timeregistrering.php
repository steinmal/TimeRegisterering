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
$oppgave_id = "";
$oppgaveListe= "";
session_start();

if(!isset($_SESSION['innlogget']) || $_SESSION['innlogget'] == false){
    header("Location: index.php?error=ikkeInnlogget");
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
    $id = $_POST['regId'];
    if($_POST['submit'] != "Start" && isset($_POST['regId'])  && $TimeReg->hentTimeregistrering($id)->getBrukerId() != $_SESSION['bruker']->getId()) {  //Registreringen hører ikke til innlogget bruker
        header("Location: timeregistrering.php?error=ugyldigTimereg");
        return;
    }
    
    $timereg = $TimeReg->hentTimeregistrering($id);
    switch($_POST['submit']){
        case 'Start':
            if ($timereg != NULL && $timereg->getStatus() == 0) {   //status = start, allerede aktiv
                header("Location: timeregistrering.php?error=alleredeAktivTimereg");
                return;
            }
            $prosjekt = $OppgaveReg->hentProsjektFraOppgave($_POST['oppgave']);
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
    echo $twig->render('timeregistrering.html', array( 'innlogget'=>$_SESSION['innlogget'], 'bruker'=>$_SESSION['bruker'], 'TeamReg'=>$TeamReg, 'aktiv'=>true, 'visSkjema'=>true, 'prosjekt'=>$prosjekt, 'oppgave'=>$oppgave, 'registrering'=>$registrering, 'brukernavn'=>$brukernavn, 'dagensdato'=>date("Y-m-d"), 'brukerTilgang'=>$_SESSION['brukerTilgang'], 'error'=>$error));
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
        $rProsjekt = new RapportProsjekt($ProsjektReg, $OppgaveReg, $TimeReg, $p);
        $alleProsjekter = array_merge($alleProsjekter, $rProsjekt->getProsjektOgUnderProsjekt());
    }

    $prosjektListe = array_unique($alleProsjekter);
    
    if(isset($_POST['prosjekt'])) {
        if(!in_array($ProsjektReg->hentProsjekt($_POST['prosjekt']), $prosjektListe)) {
            header("Location: timeregistrering.php?error=ugyldigProsjekt");
            return;
        }
        $prosjekt_id = $_POST['prosjekt'];
        $oppgaveListe = $OppgaveReg->hentOppgaverFraProsjekt($prosjekt_id);
    }


    $visSkjema = ($prosjekt_id > 0 && sizeof($oppgaveListe) > 0) ? true : false;
    echo $twig->render('timeregistrering.html', array( 'innlogget'=>$_SESSION['innlogget'], 'TeamReg'=>$TeamReg, 'sendt'=>$sendt, 'bruker'=>$_SESSION['bruker'], 'aktiv'=>false, 'visSkjema'=>$visSkjema, 'prosjektListe'=>$prosjektListe, 'oppgaveListe'=>$oppgaveListe, 'brukernavn'=>$brukernavn, 'dagensdato'=>date("Y-m-d"), 'klokkeslett'=>date('H:i'), 'valgtProsjekt'=>$prosjekt_id, 'valgtOppgave'=>$oppgave_id, 'brukerTilgang'=>$_SESSION['brukerTilgang'], 'error'=>$error));
}



?>
