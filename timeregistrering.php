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
$visSkjema = false;
$tidsestimat = 0;
$aktivTid = 0;
$manuell = false;
session_start();

//hente ut nylig brukte oppgaver for å fylle listen man kan trykke fortsette på
$timeregs = $TimeReg->hentTimeregistreringerFraBruker($_SESSION['bruker']->getId(), false, false, true);
$nyligeOppgaveId = array();
$i = 0;
while (sizeof($nyligeOppgaveId) < 3 && $i < sizeof($timeregs)) {
    if (!in_array($timeregs[$i]->getOppgaveId(), $nyligeOppgaveId)) {
        $nyligeOppgaveId[] = $timeregs[$i]->getOppgaveId();
    }
    $i++;
}
$nyligeOppgaver = array();
foreach ($nyligeOppgaveId as $n) {
    $nyligeOppgaver[] = $OppgaveReg->hentOppgave($n);
}


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
            if (!$_SESSION['brukerTilgang']->isProsjektadmin()) {
                $prosjekt = $ProsjektReg->hentProsjektFraOppgave($_POST['oppgave']);
                $teamListe = $TeamReg->hentTeamIdFraBruker($_SESSION['bruker']->getId());
                if (!in_array($prosjekt->getTeam(), $teamListe)) {
                    header("Location: timeregistrering.php?error=ugyldigOppgave&prosjekt=" . $_POST['prosjektId']);
                    return;
                }
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
            if ($timereg->getStatus() == 3) { //status = stopp
                header("Location: timeregistrering.php?error=ugyldigStopp");
                return;
            }
            $TimeReg->stoppTimeReg($id);
            break;
        default:
            break;
    }
}
if(isset($_POST['forslag'])){
    $oppgaveId = $_POST['oppgave'];
    header("Location: nyttTidsestimat.php?oppgaveId=" . $oppgaveId);
    return;
}
if (isset($_POST['regManuell'])){
    if (!isset($_POST['kommentar']) || $_POST['kommentar'] == "" || !isset($_POST['dato']) || !isset($_POST['start']) || !isset($_POST['slutt'])) {
        header("Location: timeregistrering.php?error=ingenVerdi&manuell=on");
        return;
    }
    $oppgaveId = $_POST['oppgaveId'];
    $dato = $_POST['dato'];
    $fra = $_POST['start'];
    $til = $_POST['slutt'];
    if ($fra > $til) {
        header("Location: timeregistrering.php?error=startEtterSlutt&manuell=on");
        return;
    }
    $pause = $_POST['pause'];
    $kommentar = $_POST['kommentar'];
    $automatisk = 0;
    $TimeReg->lagTimeregistrering($oppgaveId, $_SESSION['bruker']->getId(), $dato, $fra, $til, $automatisk, $pause, $kommentar);
    header("Location: timeregistrering.php?error=manLagret");
    return;
}

if (isset($_REQUEST['fortsettTimereg'])) {  //bruker fortsett-knappen i listen over tidligere oppgaver
    $timereg = $TimeReg->hentAktiveTimeregistreringer($_SESSION['bruker']->getId());
    if(sizeof($timereg) > 0){
        header("Location: timeregistrering.php?error=alleredeAktivTimereg");
        return;
    }
    if(!isset($_REQUEST['oppgaveId'])){
        header("Location: timeregistrering.php?error=ugyldigOppgave&prosjekt=" . $_REQUEST['prosjektId']);
        return;
    }
    if (!$_SESSION['brukerTilgang']->isProsjektadmin()) {
        $prosjekt = $ProsjektReg->hentProsjekt($_REQUEST['prosjektId']);
        $teamListe = $TeamReg->hentTeamIdFraBruker($_SESSION['bruker']->getId());
        if (!in_array($prosjekt->getTeam(), $teamListe)) {
            header("Location: timeregistrering.php?error=ugyldigOppgave&prosjekt=" . $_REQUEST['prosjektId']);
            return;
        }
    }
    $TimeReg->startTimeReg($_REQUEST['oppgaveId'], $_SESSION['bruker']->getId());
}


$brukernavn = $_SESSION['bruker']->getNavn();
$registrering = $TimeReg->hentAktiveTimeregistreringer($_SESSION['bruker']->getId());
$prosjekt_id = 0;
//$prosjekt = $ProsjektReg->hentAlleProsjekt();
if($registrering != null && sizeof($registrering) > 0){     //aktiv timereg
    $registrering = $registrering[0];
    $oppgave = $OppgaveReg->hentOppgave($registrering->getOppgaveId());
    $prosjekt = $ProsjektReg->hentProsjektFraFase($oppgave->getFaseId());
    $prosjekt_id = $prosjekt->getId();
    
    echo $twig->render('timeregistrering.html', array( 'innlogget'=>$_SESSION['innlogget'], 'valgtOppgave'=>$oppgave->getId(), 'bruker'=>$_SESSION['bruker'], 'TeamReg'=>$TeamReg, 'aktiv'=>true, 'visOppgave'=>true, 'visSkjema'=>true, 'prosjekt'=>$prosjekt, 'oppgave'=>$oppgave, 'registrering'=>$registrering, 'brukernavn'=>$brukernavn, 'dagensdato'=>date("Y-m-d"), 'brukerTilgang'=>$_SESSION['brukerTilgang'], 'manuell'=>false, 'error'=>$error, 'oppgave'=>$oppgave, 'prosjekt'=>$prosjekt));
}
else{
    $brukerID = $_SESSION['bruker']->getId();
    //$alleProsjekter = array();
    if ($_SESSION['brukerTilgang']->isProsjektadmin()) {
        $prosjektListe = $ProsjektReg->hentAlleProsjekt();
    } else { // Optimalisert kode!! Hurra!!!!
        $prosjektListe = $ProsjektReg->hentTeamProsjektFraBruker($_SESSION['bruker']->getId());
        $idArr = array();
        foreach($prosjektListe as $p){
            $idArr[] = $p->getId();
        }
        $children = array();
        while($children = $ProsjektReg->hentUnderProsjektFraListe($idArr)){
            $idArr = array();
            foreach($children as $c){
                if(!isset($prosjektListe[$c->getId])){
                    $prosjektListe[$c->getId] = $c;
                    $idArr[] = $c->getId();
                }
            }
        }
    }

    if(isset($_POST['prosjektId'])) {
        if(!isset($prosjektListe[$_POST['prosjektId']])) {
            header("Location: timeregistrering.php?error=ugyldigProsjekt");
            return;
        }
        $prosjekt_id = $_POST['prosjektId'];
        $oppgaveListe = $OppgaveReg->hentOppgaverFraProsjekt($prosjekt_id);
    }
    
    $oppgave_id = 0;
    if(isset($_POST['oppgave'])) {
        $oppgave_id = $_POST['oppgave'];
        $tidsestimat = $OppgaveReg->hentOppgave($oppgave_id)->getTidsestimat();
        $aktivTid = $OppgaveReg->hentAktiveTimerPrOppgaveDesimal($oppgave_id);
        $prosjekt_id = $_POST['prosjektId'];
        $prosjekt = $ProsjektReg->hentProsjekt($prosjekt_id);
    }

    $visOppgave = ($prosjekt_id > 0 && sizeof($oppgaveListe) > 0 || $prosjekt_id > 0 && $oppgave_id > 0) ? true : false;
    $visSkjema = $oppgave_id > 0 ? true : false;
    $dagensdato = date('Y-m-d');
    $now = date('h:i:s');
    $sysReg = new SystemRegister($db);
    $sysVar = $sysReg->hentSystemvariabel();
    $ikkeLengerBak = date('Y-m-d', strtotime('-' . $sysVar[0]->getTidsparameter() . ' days'));
    
    if(isset($_REQUEST['manuell'])) {
        $manuell = true;
    }
    echo $twig->render('timeregistrering.html', 
                array('aktivert'=>$aktivert, 
                      'innlogget'=>$_SESSION['innlogget'], 
                      'TeamReg'=>$TeamReg, 
                      'sendt'=>$sendt, 
                      'bruker'=>$_SESSION['bruker'], 
                      'aktiv'=>false, 
                      'visOppgave'=>$visOppgave, 
                      'prosjektListe'=>$prosjektListe, 
                      'oppgaveListe'=>$oppgaveListe, 
                      'brukernavn'=>$brukernavn, 
                      'klokkeslett'=>date('H:i'), 
                      'valgtProsjekt'=>$prosjekt_id, 
                      'valgtOppgave'=>$oppgave_id, 
                      'brukerTilgang'=>$_SESSION['brukerTilgang'], 
                      'OppgaveReg'=>$OppgaveReg,
                      'visSkjema'=>$visSkjema,
                      'tidsestimat'=>$tidsestimat,
                      'aktivTid'=>$aktivTid,
                      'dagensdato'=>$dagensdato,
                      'now'=>$now,
                      'ikkeLengerBak'=>$ikkeLengerBak,
                      'manuell'=>$manuell,
                      'nyligeOppgaver'=>$nyligeOppgaver,
                      'ProsjektReg'=>$ProsjektReg,
                      'error'=>$error));
}



?>
