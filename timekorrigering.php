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
session_start();

if(!isset($_SESSION['innlogget']) || $_SESSION['innlogget'] == false){
    header("Location: index.php");
    return;
}

$twigs = array();
$twigs['innlogget'] = $_SESSION['innlogget'];
$twigs['bruker'] = $_SESSION['bruker'];
$twigs['brukernavn'] = $_SESSION['bruker']->getBrukerNavn();
$twigs['brukerTilgang'] = $_SESSION['brukerTilgang'];
$twigs['oppgavereg'] = $OppgaveReg;
$error = "";

if (isset($_REQUEST['action'])) {
    $timeId = $_REQUEST['timeregId'];
    if ($timeId == NULL) {
        $error = "ingenValgt";
    } else if ($TimeReg->hentTimeregistrering($timeId)->getBrukerId() != $_SESSION['bruker']->getBrukerId() ){
        $error = "ugyldigId";

    } else {
        switch ($_REQUEST['action']) {
            case 'Korriger':
                if ($TimeReg->hentTimeregistrering($timeId)->getAktiv() == 0) {
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
        }
    }
} else if (isset($_POST['lagre'])) {
    $gammelTimeId = $_REQUEST['timeId'];
    $timeregKopi = $TimeReg->kopierTimeregistrering($gammelTimeId);
    $TimeReg->deaktiverTimeregistrering($gammelTimeId);
    
    $timeId = $timeregKopi->getId();
    $dato = $_REQUEST['dato'];
    //$dato = "2017-04-04";
    //var_dump($timeregKopi->getDato());
    if($dato != $timeregKopi->getDato()){
        header("Location: timekorrigering.php?timeregId=" . $gammelTimeId . "&error=datoForandret");
        return;
    }
    /* Legg inn funksjonalitet for å sjekke hvor lenge siden timeregistreringern er.
    Må sjekke hvor langt tilbake det skal være lov å endre timeregistreringer. */

    if(!isset($_REQUEST['starttid']) || !isset($_REQUEST['stopptid']) || !isset($_REQUEST['kommentar'])) {
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
    
    var_dump($fra);          // ------------------------------------------ FJERNES
    var_dump($til);          // ------------------------------------------ FJERNES
    $startTid = DateTime::createFromFormat('H:i:s', $fra);
    $stoppTid = DateTime::createFromFormat('H:i:s', $til);

    if(!($startTid && $stoppTid)) {
            var_dump($startTid);          // ------------------------------------------ FJERNES
            var_dump($stoppTid);          // ------------------------------------------ FJERNES
            // Dette burde ikke skje ved normalt bruk
            header("Location: timekorrigering.php?timeregId=" . $gammelTimeId . "&error=datoFeilFormat");
            return;
    }
    
    var_dump($startTid);          // ------------------------------------------ FJERNES
    var_dump($stoppTid);          // ------------------------------------------ FJERNES

    if($startTid->getTimestamp() > $stoppTid->getTimestamp()){
        header("Location: timekorrigering.php?timeregId=" . $gammelTimeId . "&error=stoppEtterStart");
        return;
    }
    
    
    $pause = $_REQUEST['pause'];
    if( true/* pause må være mellom 0 og jobblengden */ )
    
    $kommentar = $_REQUEST['kommentar'];
    
    $TimeReg->oppdaterTimeregistrering($timeId, $dato, $fra, $til, $pause, $kommentar);
    $error = "lagret";

    
} else if (isset($_POST['avbryt'])) {
    $error = "avbryt";
}

date_default_timezone_set('Europe/Oslo');

//$twigs['timeregistreringer'] = $TimeReg->hentTimeregistreringerFraBruker($_SESSION['bruker']->getBrukerId());
//echo $twig->render('timeoversikt.html', $twigs);
//header("Location: timeoversikt.php?error=" . $error);
return;

?>
