<?php
spl_autoload_register(function ($class_name) {
    require_once 'classes/' . $class_name . '.class.php';
});

require_once 'vendor/autoload.php';
include('auth.php');

$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);

$UserReg = new UserRegister($db);
$TeamReg = new TeamRegister($db);
$TimeReg = new TimeregistreringRegister($db);
$OppgaveReg = new OppgaveRegister($db);

session_start();

if(!isset($_SESSION['innlogget']) || $_SESSION['innlogget'] == false){
    header("Location: index.php");
    return;
}

if(!isset($_SESSION['brukerTilgang']) || $_SESSION['brukerTilgang']->isTeamleder() != true){
    echo "Kun teamleder har tilgang til timegodkjenning";
    return;
}

if(isset($_GET['action'])){
    if($_GET['action'] == "godkjenn") {
        $TimeReg->godkjennTimeregistrering($_GET['timeregId']);
    }
    else if($_GET['action'] == "avvis") {
        $TimeReg->avvisTimeregistrering($_GET['timeregId']);
    }
    header('location: timegodkjenning.php');
}

$bruker = $_SESSION['bruker'];
$teamIDs = array();
$teamIDs = $TeamReg->getTeamIdFraTeamleder($bruker->getId());

$teams = array();
$brukerIds = array();
foreach ($teamIDs as $teamID) {
    $teams[] = $TeamReg->hentTeam($teamID);
    $brukerIdArray = $TeamReg->getTeamMedlemmerId($teamID);;
    foreach ($brukerIdArray as $brukerId) {
        $brukerIds[] = $brukerId;
    }
}

$timeregistreringer = array();
foreach ($brukerIds as $brukerId) {
    $timeregArray = $TimeReg->hentTimeregistreringerFraBruker($brukerId);
    foreach ($timeregArray as $timereg) {
        $timeregistreringer[] = $timereg;
    }
}


echo $twig->render(
    'timegodkjenning.html', 
    array('innlogget'=>$_SESSION['innlogget'], 
    'bruker'=>$_SESSION['bruker'],
    'userReg'=>$UserReg, 
    'teamReg'=>$TeamReg, 
    'timeReg'=>$TimeReg, 
    'oppgaveReg'=>$OppgaveReg, 
    'teams'=>$teams,
    'timeregistreringer'=>$timeregistreringer,
    'visGodkjent'=>$_GET['visGodkjent'], 'brukerTilgang'=>$_SESSION['brukerTilgang']));
?>