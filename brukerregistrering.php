<?php
spl_autoload_register(function ($class_name) {
    require_once 'classes/' . $class_name . '.class.php';
});

require_once 'vendor/autoload.php';
include('auth.php');
$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);

$userReg = new UserRegister($db);

session_start();

if(isset($_POST['opprettBruker'])){
    $nyBruker = new User();
    $nyBruker->setBrukerNavn($_POST['navn']);
    $nyBruker->setBrukerEpost($_POST['epost']);
    $nyBruker->setPassword($_POST['passord']);
    $nyBruker->setBrukerTelefon($_POST['telefonnummer']);
    
    $userReg->opprettBruker($nyBruker);
}

echo $twig->render('brukerregistrering.html', array());
?>
