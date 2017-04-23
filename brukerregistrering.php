<?php
spl_autoload_register(function ($class_name) {
    require_once 'classes/' . $class_name . '.class.php';
});

require_once 'vendor/autoload.php';
include('auth.php');
$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);
$BrukerReg = new BrukerRegister($db);
$TeamReg = new TeamRegister($db);
$error="";
$innlogget = 0;
$bruker = "";
$brukerTilgang = "";
session_start();


if(isset($_POST['opprettBruker'])){
    if($BrukerReg->brukernavnEksisterer($_POST['navn'])){
        $_SESSION['regnavn'] = $_POST['navn'];
        $_SESSION['regepost'] = $_POST['epost'];
        $_SESSION['regtelefon'] = $_POST['telefonnummer'];
        header("Location: brukerregistrering.php?error=nameExists" );
        return;
    }
    if($BrukerReg->emailEksisterer($_POST['epost'])){
        $_SESSION['regnavn'] = $_POST['navn'];
        $_SESSION['regepost'] = $_POST['epost'];
        $_SESSION['regtelefon'] = $_POST['telefonnummer'];
        header("Location: brukerregistrering.php?error=mailExists");
        return;
    }
    $nyBruker = new Bruker();
    $nyBruker->setNavn($_POST['navn']);
    $nyBruker->setEpost($_POST['epost']);
    $nyBruker->setPassord($_POST['passord']);
    $nyBruker->setTelefon($_POST['telefonnummer']);
    $BrukerReg->opprettBruker($nyBruker);

    header("Location: index.php?regSucc=1");
}
if(isset($_GET['error'])){
    $error=$_GET['error'];
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




echo $twig->render('brukerregistrering.html', array('brukerTilgang'=>$brukerTilgang, 'TeamReg'=>$TeamReg, 'bruker'=>$bruker, 'innlogget'=>$innlogget, 'error'=>$error, 'telefon'=>$_SESSION['regtelefon'], 'epost'=>$_SESSION['regepost'], 'navn'=>$_SESSION['regnavn']));
?>
