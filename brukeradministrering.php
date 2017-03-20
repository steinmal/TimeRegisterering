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
if(isset($_GET['action']) && $_GET['action'] == "aktiver"){
    if(!isset($_GET['brukerId'])){
        $error = "noSelection";
    } else {
        if($UserReg->hentBruker($_GET['brukerId'])->isAktivert){
            $error = "erAktivert";
        } else {
            $UserReg->aktiverBruker($_GET['brukerId']);
            $error = "aktivert";
        }
    }
} else {
    $error = $_REQUEST['error'];
}

$brukere = $UserReg->hentAlleBrukere();
$venterGodkjenning = 0;
foreach($brukere as $bruker){
    if(!$bruker->isAktivert()){
        $venterGodkjenning++;
    }
}

echo $twig->render('brukeradministrering.html', array('innlogget'=>$_SESSION['innlogget'], 'error'=>$error, 'venterGodkjenning'=>$venterGodkjenning, 'visNye'=>($_GET['visNye'] == "on"), 'bruker'=>$_SESSION['bruker'], 'brukerReg'=>$UserReg, 'brukere'=>$brukere, 'brukerTilgang'=>$_SESSION['brukerTilgang']));

?>