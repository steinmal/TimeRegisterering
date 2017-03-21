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

if (isset($_REQUEST['action'])) {
    switch ($_REQUEST['action']) {
        case 'Korriger':
            $timeId = $_REQUEST['timeregId'];
            $timeregKopi = $TimeReg->kopierTimeregistrering($timeId);
    
            $twigs['timereg'] = $timeregKopi;
            $twigs['oppgavenavn'] = $OppgaveReg->hentOppgave($timeregKopi->getOppgaveId())->getOppgaveNavn();
    
            break;
            
        case 'Deaktiver':
            $timeId = $_REQUEST['timeregId'];
    
            $TimeReg->deaktiverTimeregistrering($timeId);
            header('Location: timeoversikt.php');
            return;
            break;
    }
} else if (isset($_POST['lagre'])) {
    $timeId = $_REQUEST['timeId'];
    $dato = $_REQUEST['dato'];
    $fra = $_REQUEST['starttid'];
    $til = $_REQUEST['stopptid'];
    $kommentar = $_REQUEST['kommentar'];

    
    $TimeReg->oppdaterTimeregistrering($timeId, $dato, $fra, $til, $kommentar);
    header('Location: timeoversikt.php');
    return;
    
}

date_default_timezone_set('Europe/Oslo');

echo $twig->render('timekorrigering.html', $twigs);


?>
