<?php
spl_autoload_register(function ($class_name) {
    require_once 'classes/' . $class_name . '.class.php';
});

require_once 'vendor/autoload.php';
include('auth.php');

$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);

$BrukerReg = new BrukerRegister($db);
$TeamReg = new TeamRegister($db);
$TimeReg = new TimeregistreringRegister($db);
$OppgaveReg = new OppgaveRegister($db);
$FaseReg = new FaseRegister($db);
$ProsjektReg = new ProsjektRegister($db);
$visGodkjent = "";
$error = "";
$aktivert = "";
session_start();

if(!isset($_SESSION['innlogget']) || $_SESSION['innlogget'] == false){
    header("Location: index.php?error=ikkeInnlogget");
    return;
}
$aktivert = $_SESSION['bruker']->isAktivert();
if(!isset($_SESSION['brukerTilgang']) || $_SESSION['brukerTilgang']->isTeamleder() != true || !$_SESSION['bruker']->isAktivert()){
    header("Location: index.php?error=manglendeRettighet&side=timegod");
    return;
}
if(isset($_GET['visGodkjent'])){
    $visGodkjent = $_GET['visGodkjent'];
}



if(isset($_GET['action'])){
    if($_GET['action'] == "Godkjenn alle markerte") {
        if (isset($_GET['selection']))
        {
            for($i=0; $i < count($_GET['selection']); $i++){
                if ($TimeReg->brukerKanRedigere($_GET['selection'][$i], $TeamReg))
                    $TimeReg->godkjennTimeregistrering($_GET['selection'][$i]);
                else
                    $error = "ugyldigTimereg"; //slår ut dersom teamleder ikke har tilgang til aktuell timereg, eller brukeren ikke har brukertilgang prosjektadmin
            }
        }
    }
    else if($_GET['action'] == "Avvis alle markerte") {
        if (isset($_GET['selection']))
        {
            for($i=0; $i < count($_GET['selection']); $i++){
                if ($TimeReg->brukerKanRedigere($_GET['selection'][$i], $TeamReg))
                    $TimeReg->avvisTimeregistrering($_GET['selection'][$i]);
                else
                    $error = "ugyldigTimereg";
            }
        }
    }
    else if($_GET['action'] == "godkjenn") {
        if ($TimeReg->brukerKanRedigere($_GET['timeregId'], $TeamReg))
            $TimeReg->godkjennTimeregistrering($_GET['timeregId']);
        else
            $error = "ugyldigTimereg";
    }
    else if($_GET['action'] == "avvis") {
        if ($TimeReg->brukerKanRedigere($_GET['timeregId'], $TeamReg))
            $TimeReg->avvisTimeregistrering($_GET['timeregId']);
        else
            $error = "ugyldigTimereg";
    }
} else {
    //Koden skal kjøre selv om ingen action er etterspurt
    //header('Location: timegodkjenning.php?error=noAction');
    //echo "ingen action";
    //return;
}

$bruker = $_SESSION['bruker'];
$teamIDs = array();
if ($_SESSION['brukerTilgang']->isProsjektadmin()) {
    $teams = $TeamReg->hentAlleTeam();
} else {
    $teams = $TeamReg->getAlleTeamFraTeamleder($bruker->getId());
}
$brukerIds = array();
$timeregistreringer = array();
foreach ($teams as $team) {
    $teamID = $team->getId();
    $timeregistreringer[$teamID] = $TimeReg->hentTimeregistreringerFraTeam($teamID);
    $manglerGodkjenning = 0;
    foreach ($timeregistreringer[$teamID] as $timeregistrering) {
        if ($timeregistrering->getTilstandTekst() == "Venter godkjenning" || $timeregistrering->getTilstandTekst() == "Gjenopprettet, venter godkjenning") {
            $manglerGodkjenning++;
        }
    }
    $timeregManglerGodkjenning[$teamID] = $manglerGodkjenning;
}

if (isset($_GET['error'])) {
    $error = $_GET['error'];
}


echo $twig->render('timegodkjenning.html', array(
    'innlogget'=>$_SESSION['innlogget'],
    'bruker'=>$_SESSION['bruker'],
    'brukerReg'=>$BrukerReg,
    'TeamReg'=>$TeamReg,
    'timeReg'=>$TimeReg,
    'oppgaveReg'=>$OppgaveReg,
    'teams'=>$teams,
    'timeregistreringer'=>$timeregistreringer,
    'timeregManglerGodkjenning'=>$timeregManglerGodkjenning,
    'visGodkjent'=>$visGodkjent,
    'brukerTilgang'=>$_SESSION['brukerTilgang'],
    'error'=>$error,
    'aktivert'=>$aktivert));
?>