<?php
spl_autoload_register(function ($class_name) {
    require_once 'classes/' . $class_name . '.class.php';
});

require_once 'tilgangsfunksjoner.php';
require_once 'vendor/autoload.php';
include('auth.php');
$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);
$BrukerReg = new BrukerRegister($db);
$TeamReg = new TeamRegister($db);
$error = "";
$adminerror = "";
$teamId = "";
$medlemsliste = array();
$team = "";
$medlemmer = array();
$fjernId = "";
$brukerliste = array();
$allebrukere = array();
$alleTeam = array();
$admin = false;
$aktivert = "";

session_start();

if(!isInnlogget()){
    header("Location: index.php?error=ikkeInnlogget");
    return;
}
$aktivert = isAktiv();
if(!(isProsjektadmin() || isTeamLeder()) || !$aktivert){
    header("Location: index.php?error=manglendeRettighet&side=teamadm");
    return;
}

if(isset($_GET['error'])){
    $error = $_GET['error'];
}

if(isset($_GET['teamId'])){
    $teamId = $_GET['teamId'];
    $team = $TeamReg->hentTeam($teamId);

    if($team == null
        || !(isProsjektAdmin() 
        || (isTeamLeder() && $team->getLeder() == $_SESSION['bruker']->getId())))
    {
        header("Location: teamadministrering.php?error=feilTeam");
        return;
    }

    $medlemsliste = $TeamReg->getTeamMedlemmerId($teamId);

    foreach($medlemsliste as $i) {
        $medlemmer[] = $BrukerReg->hentBruker($i);
    }

    if(isset($_GET['fjern'])){
        $fjernId = $_GET['fjern'];
        $TeamReg->slettMedlemskap($fjernId, $teamId);
        header("Location: teamadministrering.php?teamId=" . $teamId);
    }

    $allebrukere = $BrukerReg->hentAlleBrukere();
    $a = array();
    foreach($allebrukere as $i) {
        if ($i->isAktivert()) 
            $a[] = $i->getId();
    }
    $loggetInn = [$_SESSION['bruker']->getId()];
    $brukerliste = array_diff($a, $medlemsliste); //Alle brukere minus de som allerede er i team.
    $brukerliste = array_diff($brukerliste, $loggetInn); //Fjerne leder.

    if(isset($_POST['leggTilMedlem'])) {
        $teamId = $_POST['teamId'];
        if(!(isProsjektLeder || isTeamLeder($TimeReg, $teamId))) {
        //if ($TeamReg->hentTeam($teamId)->getLeder() != $_SESSION['bruker']->getId()) {
            header("Location: teamadministrering.php?feilTeam");
            return;
        }
        $brukerId = $_POST['navn'];
        $TeamReg->leggTilMedlem($brukerId, $teamId);
        header("Location: teamadministrering.php?teamId=" . $teamId);

    }
}
elseif(!isset($_GET['error'])){
    if(isset($_GET['adminerror'])) {
        $adminerror = $_GET['adminerror'];
    }
    if(isProsjektAdmin() || isSystemAdmin()) {
        $admin = true;
        $alleTeam = $TeamReg->hentAlleTeam();
    }
    else {
        header("Location: teamadministrering.php?error=ikkeTilgang");
        return;
    }
}




echo $twig->render('teamadministrering.html', array('aktivert'=>$aktivert, 'innlogget'=>$_SESSION['innlogget'], 'adminerror'=>$adminerror, 'alleteam'=>$alleTeam, 'admin'=>$admin, 'bruker'=>$_SESSION['bruker'], 'brukerliste'=>$brukerliste, 'team'=>$team, 'medlemmer'=>$medlemmer, 'brukerReg'=>$BrukerReg, 'TeamReg'=>$TeamReg, 'error'=>$error, 'brukerTilgang'=>$_SESSION['brukerTilgang']));

?>