<?php
spl_autoload_register(function ($class_name) {
    require_once 'classes/' . $class_name . '.class.php';
});

require_once 'vendor/autoload.php';
include('auth.php');
$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);
$userReg = new UserRegister($db);
$mailExists = 0;
$nameExists = 0;
$innlogget = 0;
$bruker = "";
$brukerTilgang = "";
session_start();


if(isset($_POST['opprettBruker'])){
    if($userReg->brukernavnEksisterer($_POST['navn'])){
        header("Location: brukerregistrering.php?nameExists=1" );
        return;
    }
    if($userReg->emailEksisterer($_POST['epost'])){
        header("Location: brukerregistrering.php?mailExists=1");
        return;
    }
    $nyBruker = new User();
    $nyBruker->setNavn($_POST['navn']);
    $nyBruker->setEpost($_POST['epost']);
    $nyBruker->setPassord($_POST['passord']);
    $nyBruker->setTelefon($_POST['telefonnummer']);
    $userReg->opprettBruker($nyBruker);
}
if(isset($_GET['mailexists'])){
    if($_GET['mailExists'] == 1) {
        $mailExists = 1;
    }
}
if(isset($_GET['nameExists'])){
    if($_GET['nameExists'] == 1) {
        $nameExists = 1;
    }
}
if(isset($_SESSION['bruker'])){
    $bruker = $_SESSION['bruker'];
}
if(isset($_SESSION['innlogget'])){
    $innlogget = $_SESSION['innlogget'];
}
if(isset($_SESSION['burkerTilgang'])){
    $innlogget = $_SESSION['brukerTilgang'];
}


echo $twig->render('brukerregistrering.html', array('brukerTilgang'=>$brukerTilgang, 'bruker'=>$bruker, 'innlogget'=>$innlogget, 'mailExists'=>$mailExists, 'nameExists'=>$nameExists));
?>
