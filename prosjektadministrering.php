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

if(!isset($_SESSION['innlogget']) || $_SESSION['innlogget'] == false){
    header("Location: index.php");
    return;
}

if(!isset($_SESSION['brukerTilgang']) || $_SESSION['brukerTilgang']->isTeamleder() != true){
    echo "Du har ikke tilgang til prosjektadministrering";
    //Foreslår returnering til index.php?error=noAccess eller lignende
    return;
}

if(isset($_GET['error'])) { $error = $_GET['error']; }
$prosjektliste = $ProsjektReg->hentAlleProsjekt();
unset($prosjektliste[0]); // Skjul abstrakt rot-prosjekt

echo $twig->render('prosjektadministrering.html', array('innlogget'=>$_SESSION['innlogget'], 'bruker'=>$_SESSION['bruker'],'register'=>$ProsjektReg, 'prosjektliste'=>$prosjektliste, 'userReg'=>$UserReg, 'teamReg'=>$TeamReg, 'error'=>$error, 'visArkivert'=>$_GET['visArkivert'], 'brukerTilgang'=>$_SESSION['brukerTilgang']));

?>