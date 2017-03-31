<?php
spl_autoload_register(function ($class_name) {
    require_once 'classes/' . $class_name . '.class.php';
});
require_once 'vendor/autoload.php';
//require_once 'vendor/phpoffice/phpexcel/Classes/PHPExcel.php';
include('auth.php');
$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);
$ProsjektReg = new ProsjektRegister($db);
$OppgaveReg = new OppgaveRegister($db);
$TimeReg = new TimeregistreringRegister($db);
$TeamReg = new TeamRegister($db);
$UserReg = new UserRegister($db);
session_start();

if(!isset($_SESSION['innlogget']) || $_SESSION['innlogget'] == false){
    header("Location: index.php");
    return;
}

if(!isset($_SESSION['brukerTilgang']) || $_SESSION['brukerTilgang']->isTeamleder() != true){
    echo "Kun teamleder har tilgang til TeamRapport";
    return;
}

date_default_timezone_set('Europe/Oslo');
$firstDayOfMonth = mktime(0, 0, 0, date("m"), 1, date("Y"));
$lastDayOfMonth = mktime(0, 0, 0, date("m"), date("t"), date("Y"));
$datefrom = date("Y-m-d", $firstDayOfMonth);
$dateto = date("Y-m-d", $lastDayOfMonth);
if (isset($_GET['daterange']) && strlen($_GET['daterange']) == 23) {
    $datefrom = substr($_GET['daterange'], 0, 10);
    $dateto = substr($_GET['daterange'], 13, 10);
}

$bruker = $_SESSION['bruker'];

$teamIDs = array();
$teamIDs = $TeamReg->getTeamIdFraTeamleder($bruker->getId());

$teams = array();
$brukerIds = array();
foreach ($teamIDs as $teamID) {
    $teams[] = $TeamReg->hentTeam($teamID);
    $brukerIdArray = array();
    $brukerIdArray = $TeamReg->getTeamMedlemmerId($teamID);
    foreach ($brukerIdArray as $brukerId) {
        $brukerIds[] = $brukerId;
    }
}

$timeregistreringer = array();
foreach($brukerIds as $brukerId) {
    $brukersTimeregistreringer = array();
    $brukersTimeregistreringer = $TimeReg->hentTimeregistreringerFraBruker($brukerId, $datefrom, $dateto);
    foreach ($brukersTimeregistreringer as $timereg) {
        $timeregistreringer[] = $timereg;
    }
}

echo $twig->render(
    'teamrapportansatt.html',
    array('innlogget'=>$_SESSION['innlogget'],
        'bruker'=>$_SESSION['bruker'],
        'timeregistreringer'=>$timeregistreringer,
        'oppgavereg'=>$OppgaveReg,
        'teamReg'=>$TeamReg,
        'timeReg'=>$TimeReg,
        'userReg'=>$UserReg,
        'brukerIds'=>$brukerIds,
        'teams'=>$teams,
        'datefrom'=>$datefrom,
        'dateto'=>$dateto,
        'brukerTilgang'=>$_SESSION['brukerTilgang']));
?>