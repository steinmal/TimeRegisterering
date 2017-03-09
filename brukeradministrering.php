<?php
spl_autoload_register(function ($class_name) {
    require_once 'classes/' . $class_name . '.class.php';
});

require_once 'vendor/autoload.php';
include('auth.php');
$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);
$UserReg = new UserRegister($db);

session_start();

if(!isset($_SESSION['innlogget']) || $_SESSION['innlogget'] == false){
    header("Location: index.php");
    return;
}

if(!isset($_SESSION['brukerTilgang']) || $_SESSION['brukerTilgang']->isBrukeradmin() != true){
    echo "Du har ikke tilgang til brukeradministrering";
    return;
}

$brukere = $UserReg->hentAlleBrukere();

echo $twig->render('brukeradministrering.html', array('brukerReg'=>$UserReg, 'brukere'=>$brukere, 'error'=>$_REQUEST['error']));

?>