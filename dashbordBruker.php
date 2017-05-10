<?php
spl_autoload_register(function ($class_name) {
    require_once 'classes/' . $class_name . '.class.php';
});

require_once 'vendor/autoload.php';
include('auth.php');
$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);
$ProsjektReg = new ProsjektRegister($db);
$BrukerReg = new BrukerRegister($db);
$TeamReg = new TeamRegister($db);
$TimeReg = new TimeregistreringRegister($db);
$OppgaveReg = new OppgaveRegister($db);
$aktivert = "";


session_start();

if(!isset($_SESSION['innlogget']) || $_SESSION['innlogget'] != true){
    header("Location: index.php?error=ikkeInnlogget");
    return;
}


if(!isset($_SESSION['brukerTilgang'])){
    header('Location: index.php?error=feil');
    return;
}


$bruker = $_SESSION['bruker'];

$aktivert = $_SESSION['bruker']->isAktivert();

$brukerTypeID = $bruker->getBrukertype();
$brukerType = $BrukerReg->getBrukerType($brukerTypeID)->getNavn();
$brukerID = $bruker->getId();

//henter de timereg brukeren kan fortsette på
$timeregs = array_reverse($TimeReg->hentTimeregistreringerFraBruker($_SESSION['bruker']->getId()));
$nyligeOppgaveId = array();
$i = 0;
while (sizeof($nyligeOppgaveId) < 3 && $i < sizeof($timeregs)) {
    if (!in_array($timeregs[$i]->getOppgaveId(), $nyligeOppgaveId)) {
        $nyligeOppgaveId[] = $timeregs[$i]->getOppgaveId();
    }
    $i++;
}
$nyligeOppgaver = array();
foreach ($nyligeOppgaveId as $n) {
    $nyligeOppgaver[] = $OppgaveReg->hentOppgave($n);
}

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

$brukerIsTeamleder = $_SESSION['brukerTilgang']->isTeamleder();

echo $twig->render('dashbordBruker.html', 
             array('innlogget'=>$_SESSION['innlogget'], 
                   'aktivert'=>$aktivert, 
                   'lederTeamListe'=>$lederTeamListe, 
                   'bruker'=>$bruker, 
                   'prosjekter'=>$prosjekter, 
                   'teamliste'=>$teamliste, 
                   'brukerIsTeamleder'=>$brukerIsTeamleder, 
                   'brukerType'=>$brukerType, 
                   'TeamReg'=>$TeamReg, 
                   'brukerReg'=>$BrukerReg, 
                   'brukerTilgang'=>$_SESSION['brukerTilgang'],
                   'nyligeOppgaver'=>$nyligeOppgaver,
                   'OppgaveReg'=>$OppgaveReg,
                   'ProsjektReg'=>$ProsjektReg));

?>