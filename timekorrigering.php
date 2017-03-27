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
$twigs['status'] = "ok";
$twigs['oppgavereg'] = $OppgaveReg;

if (isset($_REQUEST['action'])) {
    $timeId = $_REQUEST['timeregId'];
    if ($timeId == NULL) {
        $twigs['status'] = "ingenValgt";
        $twigs['timeregistreringer'] = $TimeReg->hentTimeregistreringerFraBruker($_SESSION['bruker']->getId());
        echo $twig->render('timeoversikt.html', $twigs);
    } else {
        switch ($_REQUEST['action']) {
            case 'Korriger':
                if ($TimeReg->hentTimeregistrering($timeId)->getAktiv() == 0) {
                    $twigs['status'] = "kanIkkeEndres";
                    $twigs['timeregistreringer'] = $TimeReg->hentTimeregistreringerFraBruker($_SESSION['bruker']->getId());
                    echo $twig->render('timeoversikt.html', $twigs);
                    
                } else {
                    $timereg = $TimeReg->hentTimeregistrering($timeId);
                    $twigs['timereg'] = $timereg;
                    $teigs['oppgavenavn'] = $OppgaveReg->hentOppgave($timereg->getOppgaveId())->getNavn();
                    
                    /*
                    $timeregKopi = $TimeReg->kopierTimeregistrering($timeId);
                    $TimeReg->deaktiverTimeregistrering($timeId);          
                    $twigs['gammelTimeId'] = $timeId;
                    $twigs['timereg'] = $timeregKopi;
                    $twigs['oppgavenavn'] = $OppgaveReg->hentOppgave($timeregKopi->getOppgaveId())->getNavn();*/
                    echo $twig->render('timekorrigering.html', $twigs);
                }
                break;
                
            case 'Deaktiver':
                $TimeReg->deaktiverTimeregistrering($timeId);
                $twigs['status'] = "deaktivert";
                $twigs['timeregistreringer'] = $TimeReg->hentTimeregistreringerFraBruker($_SESSION['bruker']->getId());
                
                echo $twig->render('timeoversikt.html', $twigs);
                break;
        }
    }
} else if (isset($_POST['lagre'])) {
    $gammelTimeId = $_REQUEST['timeId'];
    $timeregKopi = $TimeReg->kopierTimeregistrering($gammelTimeId);
    $TimeReg->deaktiverTimeregistrering($gammelTimeId);
    
    $timeId = $timeregKopi->getId();
    $dato = $_REQUEST['dato'];
    $fra = $_REQUEST['starttid'];
    $til = $_REQUEST['stopptid'];
    $pause = $_REQUEST['pause'];
    $kommentar = $_REQUEST['kommentar'];
    
    $TimeReg->oppdaterTimeregistrering($timeId, $dato, $fra, $til, $pause, $kommentar);
    $twigs['status'] = "lagret";

    $twigs['timeregistreringer'] = $TimeReg->hentTimeregistreringerFraBruker($_SESSION['bruker']->getId());
    echo $twig->render('timeoversikt.html', $twigs);
    
} else if (isset($_POST['avbryt'])) {
    $twigs['status'] = "avbryt";
    $twigs['timeregistreringer'] = $TimeReg->hentTimeregistreringerFraBruker($_SESSION['bruker']->getId());
    echo $twig->render('timeoversikt.html', $twigs);
}

date_default_timezone_set('Europe/Oslo');


?>
