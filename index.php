<?php
spl_autoload_register(function ($class_name) {
    require_once 'classes/' . $class_name . '.class.php';
});
require_once 'vendor/autoload.php';
include('auth.php');
$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);
$UserReg = new UserRegister($db);
$TeamReg = new TeamRegister($db);
$innlogget = false;
$error = "";
$loginFail="";
$bruker="";
$error="";
$side = "";
$brukerTilgang="";
session_start();


if(isset($_POST['login'])) {
    $brukernavn = $_POST['brukernavn'];
    $passord = $_POST['passord'];
    $UserReg->login($brukernavn, $passord);
    if(isset($_POST['fail'])){
        $loginfail = $_POST['fail'];
    }
}
if(isset($_SESSION['innlogget'])) {
    $innlogget = $_SESSION['innlogget'];
    $bruker = $_SESSION['bruker'];
    $brukerTilgang = $_SESSION['brukerTilgang'];
}
if(isset($_POST['fail'])){
    $loginFail = $_POST['fail'];
}

if(isset($_GET['error'])) {
    $error = $_GET['error'];
} if (isset($_GET['side'])) {
    $side = $_GET['side'];
}

echo $twig->render('index.html', array(
    'loginFail'=>$loginFail,
    'innlogget'=>$innlogget,
    'bruker'=>$bruker,
    'brukerTilgang'=>$brukerTilgang,
    'error'=>$error,
    'TeamReg'=>$TeamReg));
?>
