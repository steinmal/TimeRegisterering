<?php
spl_autoload_register(function ($class_name) {
    require_once 'classes/' . $class_name . '.class.php';
});

require_once 'vendor/autoload.php';
include('auth.php');
$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);
$ProsjektReg = new ProsjektRegister($db);
$UserReg = new UserRegister($db);
$TeamReg = new TeamRegister($db);


session_start();

if(!isset($_SESSION['innlogget']) || $_SESSION['innlogget'] != true){
    header("Location: index.php");
    return;
}

if(!isset($_SESSION['brukerTilgang'])){
    echo "Feil";
    return;
}


$bruker = $_SESSION['bruker'];

$brukerTypeID = $bruker->getBrukerType();
$brukerType = $UserReg->getBrukerType($brukerTypeID)->getNavn();

$brukerID = $bruker->getBrukerId();
$teamIDs = $TeamReg->hentTeamIdFraBruker($brukerID);

$teamliste = array();
$prosjekter = array();
foreach ($teamIDs as $i) {
    $teamliste[] = $TeamReg->hentTeam($i);
    $prosjekter = array_merge($prosjekter, $ProsjektReg->hentProsjekterFraTeam($i));
}

echo $twig->render('brukerdetaljer.html', array('innlogget'=>$_SESSION['innlogget'], 'bruker'=>$bruker, 'prosjekter'=>$prosjekter, 'teamliste'=>$teamliste, 'brukerType'=>$brukerType, 'UserReg'=>$UserReg));

?>