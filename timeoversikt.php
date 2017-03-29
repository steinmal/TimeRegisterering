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

$firstDayOfMonth = mktime(0, 0, 0, date("m"), 1, date("Y"));
$lastDayOfMonth = mktime(0, 0, 0, date("m"), date("t"), date("Y"));

$datefrom = date("Y-m-d", $firstDayOfMonth);
$dateto = date("Y-m-d", $lastDayOfMonth);
//$datefrom = "";
//$dateto = "";
if (isset($_GET['daterange']) && strlen($_GET['daterange']) == 23) {
    $datefrom = substr($_GET['daterange'], 0, 10);
    $dateto = substr($_GET['daterange'], 13, 10);
}
$timeregistreringer = $TimeReg->hentTimeregistreringerFraBruker($_SESSION['bruker']->getBrukerId(), $datefrom, $dateto);

echo $twig->render('timeoversikt.html', array('innlogget'=>$_SESSION['innlogget'], 'bruker'=>$_SESSION['bruker'], 'timeregistreringer'=>$timeregistreringer, 'brukernavn'=>$brukernavn,
    'oppgavereg'=>$OppgaveReg, 'brukerTilgang'=>$_SESSION['brukerTilgang'], 'noRadio'=>$_GET['noRadio'], 'deaktivertError'=>$_GET['deaktivertError'], 'datefrom'=>$datefrom, 'dateto'=>$dateto));
?>
