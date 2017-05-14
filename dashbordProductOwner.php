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
$aktiv = false;
$oppgave = "";
$tidsestimat = "";
$aktivTid = "";
$prosjekt = "";
$prosjekter = array();
$timeregManglerGodkjenning = array();


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

$brukerID = $bruker->getId();
$prosjekter = $ProsjektReg->hentProsjektFraProductOwner($brukerID);

echo $twig->render('dashbordProductOwner.html',
             array('innlogget'=>$_SESSION['innlogget'], 
                   'aktivert'=>$aktivert,
                   'bruker'=>$bruker,
                   'brukerType'=>$brukerType, 
                   'TeamReg'=>$TeamReg, 
                   'brukerReg'=>$BrukerReg, 
                   'brukerTilgang'=>$_SESSION['brukerTilgang'],
                   'prosjekter'=>$prosjekter));

?>