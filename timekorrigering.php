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

if(isset($_POST['korriger'])) {
    
    $timeId = $_POST['timeregId'];
    $timeregKopi = $TimeReg->kopierTimeregistrering($timeId);
    
    $twigs['time'] = $timeregKopi;
    $twigs['oppgavenavn'] = $OppgaveReg->hentOppgave($timeId)->getOppgaveNavn();
    
    echo $twig->render('timekorrigering.html', array('brukernavn'=>'Hei', 'time'=>$timeregKopi));
    
} else if(isset($_POST['lagre'])) {
    $timeId = $_REQUEST['timeId'];
   // $oppgaveId = $_REQUEST['oppgaveId'] oppgave skal ikke endres, samme som i den opprinnelige timereg
    $dato = $_REQUEST['dato'];
    $fra = $_REQUEST['starttid'];
    $til = $_REQUEST['stopptid'];
    //$automatisk = $_REQUEST['automatisk']; alle endringer i timereg skal vel merkes som manuelle?
    $kommentar = $_REQUEST['kommentar'];
    //korrigering av timen skal lagres, lagre endringene i kopien
    ///HENT UT ALLE NYE VERDIER; SEND DEM TIL OPPDATERTIME (LAG OPPDATERTIME)
    
    $TimeReg->oppdaterTimeregistrering($timeId, $dato, $fra, $til, $kommentar);
    header('Location: timeoversikt.php');
    return;
    
} else if(isset($_POST['deaktiver'])) {
    $timeId = $_POST['timeregId'];
    
    $TimeReg->deaktiverTimeregistrering($timeId);
    header('Location: timeoversikt.php');
    return;
}

date_default_timezone_set('Europe/Oslo');

echo $twig->render('timekorrigering.html', $twigs);
?>
