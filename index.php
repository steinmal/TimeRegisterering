<?php
spl_autoload_register(function ($class_name) {
    require_once 'classes/' . $class_name . '.class.php';
});
//include('classes/User.class.php');
require_once 'vendor/autoload.php';
include('auth.php');
$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);
$UserReg = new UserRegister($db);
session_start();

//Om du vil lage en hash til en passord som skal testes i fbm. databasen, kjør denne for å finne hash.
//echo(password_hash("passord", PASSWORD_DEFAULT));


if(isset($_POST['login'])) {
    $brukernavn = $_POST['brukernavn'];
    $passord = $_POST['passord'];
    $UserReg->login($brukernavn, $passord);
    //User::login($db, $brukernavn, $passord);
}

if(isset($_SESSION['innlogget'])) {
    $innlogget = $_SESSION['innlogget'];
}


echo $twig->render('index.html', array('innlogget'=>$innlogget, 'brukerTilgang'=>$_SESSION['brukerTilgang']));
?>
