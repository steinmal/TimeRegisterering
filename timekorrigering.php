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
$aktivert = "";
session_start();

if(!isset($_SESSION['innlogget']) || $_SESSION['innlogget'] == false){
    header("Location: index.php?error=ikkeInnlogget");
    return;
}
$aktivert = $_SESSION['bruker']->isAktivert();
if(!isset($_SESSION['brukerTilgang']) || !$_SESSION['bruker']->isAktivert()){
    header("Location: index.php?error=manglendeRettighet&side=timeKorr");
    return;
}

$twigs = array();
$twigs['TeamReg'] = $TeamReg;
$twigs['innlogget'] = $_SESSION['innlogget'];
$twigs['bruker'] = $_SESSION['bruker'];
$twigs['brukernavn'] = $_SESSION['bruker']->getNavn();
$twigs['brukerTilgang'] = $_SESSION['brukerTilgang'];
$twigs['oppgavereg'] = $OppgaveReg;
$twig['aktivert'] = $aktivert;
$error = "none";

if (isset($_REQUEST['action'])) {
    $timeId = $_REQUEST['timeregId'];
    if ($timeId == NULL) {
        $error = "ingenValgt";
    } else if ($TimeReg->hentTimeregistrering($timeId)->getBrukerId() != $_SESSION['bruker']->getId() ){
        $error = "ugyldigId";

    } else {
        switch ($_REQUEST['action']) {
            case 'Korriger':
                if ($TimeReg->hentTimeregistrering($timeId)->getTilstand() == 3) {
                    $error = "kanIkkeEndres";
                    
                } else {
                    $timereg = $TimeReg->hentTimeregistrering($timeId);
                    $twigs['timereg'] = $timereg;
                    $twigs['oppgavenavn'] = $OppgaveReg->hentOppgave($timereg->getOppgaveId())->getNavn();
                    if(isset($_GET['error'])){
                        $error = $_GET['error'];
                    }
                    $twigs['error'] = $error;
                    echo $twig->render('timekorrigering.html', $twigs);
                    return;
                }
                break;
                
            case 'Deaktiver':
                $TimeReg->deaktiverTimeregistrering($timeId);
                $error = "deaktivert";
                break;
            case 'Aktiver':
                $timeregKopi = $TimeReg->kopierTimeregistrering($timeId);
                $TimeReg->gjenopprettTimeregistrering($timeregKopi->getId());   //gir kopien tilstand gjenopprettet
                $error = "aktivert";
        }
    }
} else if (isset($_POST['lagre'])) {
    $gammelTimeId = $_REQUEST['timeId'];

    if(!isset($_REQUEST['starttid']) || !isset($_REQUEST['stopptid'])) {
        header("Location: timekorrigering.php?timeregId=". $gammelTimeId . "error=ingenVerdi");
        return;
    }
    
    $fra = $_REQUEST['starttid'];
    if(strlen($fra) == 5) {
        $fra = $fra . ':00';
    }
    
    $til = $_REQUEST['stopptid'];
    if(strlen($til) == 5) {
        $til = $til . ':00';
    }
    
    $startTid = DateTime::createFromFormat('H:i:s', $fra);
    $stoppTid = DateTime::createFromFormat('H:i:s', $til);

    if(!($startTid && $stoppTid)) {
            // Dette burde ikke skje ved normalt bruk
            header("Location: timekorrigering.php?timeregId=" . $gammelTimeId . "&error=datoFeilFormat");
            return;
    }

    if($startTid->getTimestamp() > $stoppTid->getTimestamp()){
        header("Location: timekorrigering.php?timeregId=" . $gammelTimeId . "&error=stoppEtterStart");
        return;
    }
    
    
    $pause = $_REQUEST['pause'];
    $differanse = $startTid->diff($stoppTid);
    var_dump($differanse);
    $arbeidstid = DateHelper::intervallTilMinutt($differanse);
    var_dump($arbeidstid);
    if($pause < 0 || $pause > $arbeidstid ){
        header("Location: timekorrigering.php?timeregId=" . $gammelTimeId . "&error=pauseForLang");
        return;
    }
    
    $kommentar = $_REQUEST['kommentar'];
    if (!isset($kommentar) || strcmp($kommentar, "") == 0) {
        header("Location: timekorrigering.php?timeregId=". $gammelTimeId . "error=ingenKommentar");
        return;
    }


    $timeregKopi = $TimeReg->kopierTimeregistrering($gammelTimeId);
    $TimeReg->deaktiverTimeregistrering($gammelTimeId);
    
    $timeId = $timeregKopi->getId();    
    $dato = $_REQUEST['dato'];
    if($dato != $timeregKopi->getDato()){
        header("Location: timekorrigering.php?timeregId=" . $gammelTimeId . "&error=datoForandret");
        return;
    }
    /* Legg inn funksjonalitet for å sjekke hvor lenge siden timeregistreringern er.
    Må sjekke hvor langt tilbake det skal være lov å endre timeregistreringer. */

    
    var_dump($timeId);
    var_dump($dato);
    $TimeReg->oppdaterTimeregistrering($timeId, $dato, $fra, $til, $pause, $kommentar);
    $error = "lagret";

    
} else if (isset($_POST['avbryt'])) {
    $error = "avbryt";
}

date_default_timezone_set('Europe/Oslo');

header("Location: timeoversikt.php?error=" . $error);
return;

?>
