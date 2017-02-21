<?php
//spl_autoload_register(function ($class_name) {
//    require_once '/classes/' . $class_name . '.class.php';
//});
include('classes/User.class.php');
require_once 'vendor/autoload.php';
include('auth.php');
$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);
session_start();



if(isset($_POST['login'])) {
    $brukernavn = $_POST['brukernavn'];
    $passord = $_POST['passord'];
    User::login($db, $brukernavn, $passord);
}

if(isset($_SESSION['innlogget'])) {
    $innlogget = $_SESSION['innlogget'];
}


echo $twig->render('index.html', array('innlogget'=>$innlogget));
?>
