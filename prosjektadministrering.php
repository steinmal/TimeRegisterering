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
$error = "";
$visArkivert = "";
$aktivert = "";

session_start();

if(!isset($_SESSION['innlogget']) || $_SESSION['innlogget'] == false){
    header("Location: index.php?error=ikkeInnlogget");
    return;
}
$aktivert = $_SESSION['bruker']->isAktivert();

if(!isset($_SESSION['brukerTilgang']) || $_SESSION['brukerTilgang']->isTeamleder() != true || !$_SESSION['bruker']->isAktivert()){
    header("Location: index.php?error=manglendeRettighet&side=pradm");
    //echo "Du har ikke tilgang til prosjektadministrering";
    //Foreslår returnering til index.php?error=noAccess eller lignende
    return;
}

if(isset($_GET['visArkivert'])){
    $visArkivert = $_GET['visArkivert'];
}

if(isset($_GET['error'])){
    $error = $_GET['error'];
}
$prosjektliste = $ProsjektReg->hentAlleProsjekt();
unset($prosjektliste[0]); // Skjul abstrakt rot-prosjekt

echo $twig->render('prosjektadministrering.html', array('aktivert'=>$aktivert,'innlogget'=>$_SESSION['innlogget'], 'bruker'=>$_SESSION['bruker'],'register'=>$ProsjektReg, 'prosjektliste'=>$prosjektliste, 'brukerReg'=>$BrukerReg, 'TeamReg'=>$TeamReg, 'error'=>$error, 'visArkivert'=>$visArkivert, 'brukerTilgang'=>$_SESSION['brukerTilgang']));

?>