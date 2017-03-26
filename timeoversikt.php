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

date_default_timezone_set('Europe/Oslo');

$brukernavn = $_SESSION['bruker']->getBrukerNavn();
$timeregistreringer = $TimeReg->hentTimeregistreringerFraBruker($_SESSION['bruker']->getBrukerId());



echo $twig->render('timeoversikt.html', array('innlogget'=>$_SESSION['innlogget'], 'bruker'=>$_SESSION['bruker'], 'timeregistreringer'=>$timeregistreringer, 'brukernavn'=>$brukernavn,
    'oppgavereg'=>$OppgaveReg, 'brukerTilgang'=>$_SESSION['brukerTilgang'], 'noRadio'=>$_GET['noRadio'], 'deaktivertError'=>$_GET['deaktivertError']));
?>
