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
$error = "";
$noRadio = "";
$deaktivertError = "";

session_start();

if(!isset($_SESSION['innlogget']) || $_SESSION['innlogget'] == false){
    header("Location: index.php?error=ikkeInnlogget");
    return;
}

date_default_timezone_set('Europe/Oslo');
$brukernavn = $_SESSION['bruker']->getNavn();

$firstDayOfMonth = mktime(0, 0, 0, date("m"), 1, date("Y"));
$lastDayOfMonth = mktime(0, 0, 0, date("m"), date("t"), date("Y"));

$datefrom = date("Y-m-d", $firstDayOfMonth);
$dateto = date("Y-m-d", $lastDayOfMonth);

if (isset($_SESSION['datefrom'])) $datefrom = $_SESSION['datefrom'];
if (isset($_SESSION['dateto'])) $dateto = $_SESSION['dateto'];


if (isset($_GET['daterange']) && strlen($_GET['daterange']) == 23) {
    $datefrom = substr($_GET['daterange'], 0, 10);
    $dateto = substr($_GET['daterange'], 13, 10);
}

$_SESSION['datefrom'] = $datefrom;
$_SESSION['dateto'] = $dateto;

if (isset($_GET['error'])) {
    $error = $_GET['error'];
}
if (isset($_GET['noRadio'])) {
    $noradio = $_GET['noRadio'];
}
if (isset($_GET['deaktivertError'])) {
    $deaktivertError = $_GET['deaktivertError'];
}

$timeregistreringer = $TimeReg->hentTimeregistreringerFraBruker($_SESSION['bruker']->getId(), $datefrom, $dateto);

echo $twig->render('timeoversikt.html', array('innlogget'=>$_SESSION['innlogget'], 'bruker'=>$_SESSION['bruker'], 'timeregistreringer'=>$timeregistreringer, 'brukernavn'=>$brukernavn,
    'oppgavereg'=>$OppgaveReg, 'brukerTilgang'=>$_SESSION['brukerTilgang'], 'noRadio'=>$noRadio, 'deaktivertError'=>$deaktivertError, 'datefrom'=>$datefrom, 'dateto'=>$dateto, 'error'=>$error));
?>
