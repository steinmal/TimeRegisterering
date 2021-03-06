<?php
spl_autoload_register(function ($class_name) {
    require_once 'classes/' . $class_name . '.class.php';
});

require_once 'tilgangsfunksjoner.php';
require_once 'vendor/autoload.php';
include('auth.php');
$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);
$ProsjektReg = new ProsjektRegister($db);
$BrukerReg = new BrukerRegister($db);
$TeamReg = new TeamRegister($db);
$aktivert = "";


session_start();

if(!isInnlogget()){
    header("Location: index.php?error=ikkeInnlogget");
    return;
}

$bruker = $_SESSION['bruker'];

$aktivert = isAktiv();

$brukerTypeID = $bruker->getBrukertype();
$brukerType = $BrukerReg->getBrukerType($brukerTypeID)->getNavn();

$brukerID = $bruker->getId();
$teamIDs = $TeamReg->hentTeamIdFraBruker($brukerID);

$teamliste = array();
$prosjekter = array();
foreach ($teamIDs as $i) {
    $teamliste[] = $TeamReg->hentTeam($i);
    $prosjekter = array_merge($prosjekter, $ProsjektReg->hentProsjekterFraTeam($i));
}

$lederTeamListe = array();
$lederTeamIDs = $TeamReg->getTeamIdFraTeamleder($brukerID);
foreach ($lederTeamIDs as $i) {
    $lederTeamListe[] = $TeamReg->hentTeam($i);
}

$brukerIsTeamleder = isTeamLeder();

echo $twig->render('brukerdetaljer.html', array('innlogget'=>$_SESSION['innlogget'], 'aktivert'=>$aktivert, 'lederTeamListe'=>$lederTeamListe, 'bruker'=>$bruker, 'prosjekter'=>$prosjekter, 'teamliste'=>$teamliste, 'brukerIsTeamleder'=>$brukerIsTeamleder, 'brukerType'=>$brukerType, 'TeamReg'=>$TeamReg, 'brukerReg'=>$BrukerReg, 'brukerTilgang'=>$_SESSION['brukerTilgang']));

?>