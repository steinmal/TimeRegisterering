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
$error = "";
$teamId = "";
$medlemsliste = array();
$team = "";
$medlemmer = array();
$fjernId = "";
$brukerliste = array();
$allebrukere = array();

session_start();

if(!isset($_SESSION['innlogget']) || $_SESSION['innlogget'] == false){
    header("Location: index.php?error=ikkeInnlogget");
    return;
}

if(!isset($_SESSION['brukerTilgang']) || $_SESSION['brukerTilgang']->isTeamleder() != true){
    header("Location: index.php?error=manglendeRettighet&side=pradm");
    //echo "Du har ikke tilgang til prosjektadministrering";
    //Foreslår returnering til index.php?error=noAccess eller lignende
    return;
}

if(isset($_GET['error'])){
    $error = $_GET['error'];
}
if(isset($_GET['teamId'])){
    $teamId = $_GET['teamId'];
    $team = $TeamReg->hentTeam($teamId);
    $medlemsliste = $TeamReg->getTeamMedlemmerId($teamId);

    foreach($medlemsliste as $i) {
        $medlemmer[] = $UserReg->hentBruker($i);
    }

    if(isset($_GET['fjern'])){
        $fjernId = $_GET['fjern'];
        $TeamReg->slettMedlemskap($fjernId, $teamId);
        header("Location: teamadministrering.php?teamId=" . $teamId);
    }

    $allebrukere = $UserReg->hentAlleBrukere();
    $a = array();
    foreach($allebrukere as $i) {
        $a[] = $i->getId();
    }
    $loggetInn = [$_SESSION['bruker']->getId()];
    $brukerliste = array_diff($a, $medlemsliste); //Alle brukere minus de som allerede er i team.
    $brukerliste = array_diff($brukerliste, $loggetInn); //Fjerne leder.

    if(isset($_POST['leggTilMedlem'])) {
        $brukerId = $_POST['navn'];
        $teamId = $_POST['teamId'];
        $TeamReg->leggTilMedlem($brukerId, $teamId);
        header("Location: teamadministrering.php?teamId=" . $teamId);

    }
}




echo $twig->render('teamadministrering.html', array('innlogget'=>$_SESSION['innlogget'], 'bruker'=>$_SESSION['bruker'], 'brukerliste'=>$brukerliste, 'team'=>$team, 'medlemmer'=>$medlemmer, 'userReg'=>$UserReg, 'TeamReg'=>$TeamReg, 'error'=>$error, 'brukerTilgang'=>$_SESSION['brukerTilgang']));

?>